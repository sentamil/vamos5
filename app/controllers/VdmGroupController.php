<?php

class VdmGroupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
	
		$redis = Redis::connection();
		
		$vehicleListArr = null;
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$redisGrpId = 'S_Groups_' . $fcode ;
		
	    $shortName =null;
        $shortNameList = null;
        $shortNameListArr =null;
		$groupList = $redis->smembers($redisGrpId);
		
		foreach($groupList as $key=>$group) {
		
			$vehicleList = $redis->smembers($group);
               $shortNameList=null;   
            foreach ( $vehicleList as $vehicle ) {
                $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
                $vehicleRefData=json_decode($vehicleRefData);
                $shortName = $vehicleRefData->shortName; 
                $shortNameList [] = $shortName;
            }
            $vehicleList =implode('<br/>',$vehicleList);
            $vehicleListArr = array_add($vehicleListArr,$group,$vehicleList);
            if(isset($shortNameList)) {
	          $shortNameList =implode('<br/>',$shortNameList);
            }
            $shortNameListArr = array_add($shortNameListArr,$group,$shortNameList);
         
		
		}
	
		return View::make('vdm.groups.index', array('groupList'=> $groupList))->with('vehicleListArr',$vehicleListArr)->with('shortNameListArr',$shortNameListArr);

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$vehicleList = $redis->smembers('S_Vehicles_' . $fcode);
		
		$userVehicles=null;
		$shortName =null;
        $shortNameList = null;
        
		foreach ($vehicleList as $key=>$value) {
			$userVehicles=array_add($userVehicles, $value, $value);
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $value );
            $vehicleRefData=json_decode($vehicleRefData);
             $shortName = $vehicleRefData->shortName; 
            $shortNameList = array_add($shortNameList,$value,$shortName);
            
		}
		
			return View::make('vdm.groups.create')->with('userVehicles',$userVehicles)->with('shortNameList',$shortNameList);;
	}


	/**
	 * Store a newly created resource in storage.
	 *TODO validations should be improved to prevent any attacks
	 * @return Response
	 */
	public function store()
	{
		
	
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$rules = array(
				'groupId'       => 'required',
				'vehicleList' => 'required'
 		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('vdmGroups/create')
			->withErrors($validator);
			
 		} else {
			// store
			
			$groupId       = Input::get('groupId');
			$vehicleList      = Input::get('vehicleList');
			
			$redis = Redis::connection();
			$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
			$redis->sadd('S_Groups_' . $fcode, $groupId . ':' . $fcode);
			foreach($vehicleList as $vehicle) {
				$redis->sadd($groupId . ':' . $fcode,$vehicle);
			}

 			// redirect
 			Session::flash('message', 'Successfully created ' . $groupId . '!');
 			return Redirect::to('vdmGroups');
	 		}
		
	}


	/**
	 * Display the specified resource.
	 *
	 *  Does a lazy correction here
	 *  In case the device got deleted, its very difficult and inefficient to scan all the groups 
	 *  which are associated with the vehicles. Instead when showing do the cross verification
	 *  and remove the zombie devices/vehicles.
	 *
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
					
		$redis = Redis::connection();
		$groupId=$id;
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		
		$vehicleList = $redis->smembers($groupId . ':' . $fcode);
		
		//S_Vehicles_
		foreach($vehicleList as $vehicle) {
			$result = $redis->sismember("S_Vehicles_" . $fcode,$vehicle);
			if($result == 0) {
				$redis->srem($groupId. ':' . $fcode,$vehicle);
			}
		}
		//query again to get the fresh list
		$vehicleList = $redis->smembers($groupId. ':' . $fcode);
		$vehicleList = implode('<br/>',$vehicleList);
			
		return View::make('vdm.groups.show',array('groupId'=>$groupId. ':' . $fcode))->with('vehicleList', $vehicleList);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$groupId=$id;
		Log::info(' $groupId ' . $groupId);
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

		$vehicles = $redis->smembers('S_Vehicles_' . $fcode);
		
		$selectedVehicles =  $redis->smembers($groupId);
		      $shortName =null;
        $shortNameList = null;
		$vehicleList=null;
		foreach($vehicles as $key=>$value) {
		     $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $value );
            $vehicleRefData=json_decode($vehicleRefData);
             $shortName = $vehicleRefData->shortName; 
            $shortNameList = array_add($shortNameList,$value,$shortName);
			$vehicleList=array_add($vehicleList, $value, $value);
		}
		return View::make('vdm.groups.edit',array('groupId'=>$groupId))->with('vehicleList', $vehicleList)->
		with('selectedVehicles',$selectedVehicles)->with('shortNameList',$shortNameList);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		//TODO Add validation
		
			$username = Auth::user()->username;
			$groupId       = Input::get('groupId');
			$vehicleList      = Input::get('vehicleList');
			$redis = Redis::connection();
			$redis->del($id);
			
			foreach($vehicleList as $vehicle) {
				$redis->sadd($id,$vehicle);
			}
			
	
				
				// redirect
 			Session::flash('message', 'Successfully updated ' . $id . '!');
 			return Redirect::to('vdmGroups');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$groupId = 	$id;
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		
		$redis->srem('S_Groups_' . $fcode,$groupId);
		
		$redis->del($groupId);
		
		
		
		$userList = $redis->smembers('S_Users_' . $fcode);
		
		foreach ( $userList as $user ) {
			$redis->srem($user,$groupId);
		}
			
		Session::flash('message', 'Successfully deleted ' . $groupId . '!');
		return Redirect::to('vdmGroups');
	}


}
