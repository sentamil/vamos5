<?php

class VdmGroupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()

	{

		Log::info('  reached group controller ');		
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
		if(Session::get('cur')=='dealer')
		{
			$redisGrpId = 'S_Groups_Dealer_'.$username.'_'.$fcode;
		}
		else if(Session::get('cur')=='admin')
		{
			$redisGrpId = 'S_Groups_Admin_'.$fcode;
		}
		else{
			$redisGrpId = 'S_Groups_' . $fcode ;
		}
		
		
		$groupList1=array();	
		$groupList = $redis->smembers($redisGrpId);
		$i=0;
		foreach($groupList as $key=>$group) {
		Log::info(' ---------inside---------------- '.$group);
        $group1=strtoupper($group);	
        if($group==$group1)	
	    {		
			$groupList1=array_add($groupList1,$i,$group);
			$vehicleList = $redis->smembers($group);
               $shortNameList=null;   
            foreach ( $vehicleList as $vehicle ) {
				Log::info(' ---------vehicle---------------- '.$vehicle);		
                $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
                $vehicleRefData=json_decode($vehicleRefData,true);
                $shortName = $vehicleRefData['shortName']; 
                $shortNameList [] = $shortName;
            }
            $vehicleList =implode('<br/>',$vehicleList);
            $vehicleListArr = array_add($vehicleListArr,$group,$vehicleList);
            if(isset($shortNameList)) {
	          $shortNameList =implode('<br/>',$shortNameList);
            }
            $shortNameListArr = array_add($shortNameListArr,$group,$shortNameList);
              $i=$i+1;
		}
		
		}
		Log::info('  reached group controller1 ');		
		return View::make('vdm.groups.index', array('groupList'=> $groupList1))->with('vehicleListArr',$vehicleListArr)->with('shortNameListArr',$shortNameListArr);

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	 public function groupSearch()		
    {		
        log::info(' reach the road speed function ');		
        $orgLis = [];		
            return View::make('vdm.groups.index1')->with('groupList', $orgLis);		
    }		
	public function groupScan() {		
       Log::info('  reached group controller ');        		
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
        if(Session::get('cur')=='dealer')		
        {		
            $redisGrpId = 'S_Groups_Dealer_'.$username.'_'.$fcode;		
        }		
        else if(Session::get('cur')=='admin')		
        {		
            $redisGrpId = 'S_Groups_Admin_'.$fcode;		
        }		
        else{		
            $redisGrpId = 'S_Groups_' . $fcode ;		
        }		
        $groupList = $redis->smembers($redisGrpId);		
        $text_word1 = Input::get('text_word');
        $text_word = strtoupper($text_word1);		
        $cou = $redis->SCARD($redisGrpId); //log::info($cou);		
        $orgLi = $redis->sScan( $redisGrpId, 0, 'count', $cou, 'match', '*'.$text_word.'*'); //log::info($orgLi);		
        $orgL = $orgLi[1];		
        foreach ( $orgL as $key=>$group ) {       		
        Log::info(' ---------inside---------------- '.$group);      		
            $vehicleList = $redis->smembers($group);		
               $shortNameList=null;   		
            foreach ( $vehicleList as $vehicle ) {		
                Log::info(' ---------vehicle---------------- '.$vehicle);       		
                $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );		
                $vehicleRefData=json_decode($vehicleRefData,true);		
                $shortName = $vehicleRefData['shortName']; 		
                $shortNameList [] = $shortName;		
            }		
            $vehicleList =implode('<br/>',$vehicleList);		
            $vehicleListArr = array_add($vehicleListArr,$group,$vehicleList);		
            if(isset($shortNameList)) {		
              $shortNameList =implode('<br/>',$shortNameList);		
            }		
            $shortNameListArr = array_add($shortNameListArr,$group,$shortNameList);		
        }		
        Log::info('  reached group controller1 ');      		
        return View::make('vdm.groups.index1', array('groupList'=> $orgL))->with('vehicleListArr',$vehicleListArr)->with('shortNameListArr',$shortNameListArr);		
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
			if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				
				$vehicleList = $redis->smembers('S_Vehicles_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				$vehicleList = $redis->smembers('S_Vehicles_Admin_'.$fcode);
			}
		
		
		$userVehicles=null;
		$shortName =null;
        $shortNameList = null;
        try{
			Log::info('-------------- $try-----------');
			if($vehicleList!=null)
			{
				Log::info('-------------- $try11-----------');
				foreach ($vehicleList as $key=>$value) {
			 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $value );
            $vehicleRefData=json_decode($vehicleRefData,true);
            $deviceId = $vehicleRefData['deviceId'];
if((Session::get('cur')=='dealer' &&  $redis->sismember('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode, $deviceId)==0) || Session::get('cur')=='admin')
        {


			$shortName = $vehicleRefData['shortName']; 
			$userVehicles=array_add($userVehicles, $value , $value);
           	$shortNameList = array_add($shortNameList,$value,$shortName);
			}
			
		}	
			
            
		}
		 
		Log::info('-------------- $out-----------');
		}catch(\Exception $e)
	   {
		
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
				'groupId'       => 'required|alpha_dash',
				'vehicleList' => 'required'
 		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('vdmGroups/create')
			->withErrors($validator);
			
 		} else {
			// store

			$groupId0       = Input::get('groupId');
            $groupId        = strtoupper($groupId0);			
			$vehicleList    = Input::get('vehicleList');
			
			$redis = Redis::connection();
			$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
			$fcode1=strtoupper($fcode);
			$value1=$redis->SISMEMBER('S_Groups_' . $fcode, $groupId . ':' . $fcode1);
			if($value1==1)
			{
				
				Session::flash('message', 'This group name already present along the Admin level '. '!');
				return Redirect::to('vdmGroups/create')
				;
			}
			$redis->sadd('S_Groups_' . $fcode, $groupId . ':' . $fcode1);
			foreach($vehicleList as $vehi) {
				$vehicle    = explode(" || ",$vehi)[0];
                $redis->sadd($groupId . ':' . $fcode1,$vehicle);
                ///ram noti
                $redis->sadd('S_'.$vehicle.'_'.$fcode, 'S_'.$groupId . ':' . $fcode1);
			   ///
			}

			
			if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				$redis->sadd('S_Groups_Dealer_'.$username.'_'.$fcode,$groupId . ':' . $fcode1);
			}
			else if(Session::get('cur')=='admin')
			{
				$redis->sadd('S_Groups_Admin_'.$fcode,$groupId . ':' . $fcode1);
			}
			
 			// redirect
 			Session::flash('message', 'Successfully created ' . $groupId . '!');
 			return Redirect::to('vdmGroupsScan/Search'.$groupId);
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
		$groupId0=$id;
		$groupId=strtoupper($groupId0);
		Log::info('-------------- $groupId 1-----------'.$groupId);
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$fcode1 =strtoupper($fcode);
		
		$vehicleList = $redis->smembers($groupId . ':' . $fcode1);
		
		//S_Vehicles_
		foreach($vehicleList as $vehicle) {
			$result = $redis->sismember("S_Vehicles_" . $fcode,$vehicle);
			
			if($result == 0) {
				$redis->srem($groupId. ':' . $fcode1,$vehicle);
			}
		}
		//query again to get the fresh list
		$vehicleList = $redis->smembers($groupId. ':' . $fcode1);
		$vehicleList = implode('<br/>',$vehicleList);
			
		return View::make('vdm.groups.show',array('groupId'=>$groupId. ':' . $fcode1))->with('vehicleList', $vehicleList);
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
		if(strtoupper($groupId) == $groupId)
       {
		Log::info(' $groupId ' . $groupId);
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');

		$vehicles = $redis->smembers('S_Vehicles_' . $fcode);
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				
				$vehicles = $redis->smembers('S_Vehicles_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				$vehicles = $redis->smembers('S_Vehicles_Admin_'.$fcode);
			}
		
		
		$selectedVehicles =  $redis->smembers($groupId);
		      $shortName =null;
        $shortNameList = null;
		$vehicleList=null;
		
		foreach($vehicles as $key=>$value) {
			Log::info('-------------- $groupId in-----------'.$value);
		     $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $value );
            $vehicleRefData=json_decode($vehicleRefData);

$deviceId = isset($vehicleRefData->deviceId)?$vehicleRefData->deviceId:"nill";

        if((Session::get('cur')=='dealer' &&  $redis->sismember('S_Pre_Onboard_Dealer_'.$username.'_'.$fcode, $deviceId)==0) || Session::get('cur')=='admin')
        {

             $shortName = isset($vehicleRefData->shortName)?$vehicleRefData->shortName:""; 
            $shortNameList = array_add($shortNameList,$value,$shortName);
			$vehicleList=array_add($vehicleList, $value, $value);
		}
		}
		Log::info('-------------- $groupId 2 -----------');
		return View::make('vdm.groups.edit',array('groupId'=>$groupId))->with('vehicleList', $vehicleList)->
		with('selectedVehicles',$selectedVehicles)->with('shortNameList',$shortNameList);
	   }
	    else
       {
       return Redirect::back()->with('message','Please use Groups in UpperCase!');
      }

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
      

        $username       = Auth::user()->username;
		$groupId0       = Input::get('groupId');
        $groupId        = strtoupper($groupId0);
        $vehicleList    = Input::get('vehicleList');
        $redis          = Redis::connection();
        $oldVehi        = $redis->smembers($id);
        
        $redis->del($id);
        $updateVehi     = array();
        
        
        if($vehicleList != NULL || sizeof($vehicleList) > 0)
        {
        	log::info(' vehicles are available !!!!');
        	foreach($vehicleList as $vehi) {
        		$vehicle  = explode(" || ",$vehi)[0];
        		$redis->sadd($id,$vehicle);
        		$updateVehi[]   = $vehicle;

        	}

        	$mailId = array();
        	$fcode      =       $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        	$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
        	$franchiseDetails=json_decode($franDetails_json,true);
        	if(isset($franchiseDetails['email2'])==1){
                            // $mailId[]    =       $franchiseDetails['email1'];
        		$mailId[]               = $franchiseDetails['email2'];
        		log::info(array_values($mailId));
        	}
        	
        	if(Session::get('cur')=='dealer')
        	{
        		log::info( '------login 1---------- '.$redis->hget ( 'H_UserId_Cust_Map', $username . ':email' ));
                $mailId[] =   $redis->hget ( 'H_UserId_Cust_Map', $username . ':email' );
        		
        		
        	}
        	// else if(Session::get('cur')=='admin')
        	// {
        	// 	log::info( '------login 2---------- '.Session::get('cur'));
        	// }
           		   //Session::put('email',$mailId);

        /// ram noti
          $result=array_diff($oldVehi,$updateVehi);
	      log::info($result);
	      log::info($id);
            foreach ($result as $key => $oldV) 
	         {
             $Ulist=$redis->sismember('S_'.$oldV.'_'.$fcode, 'S_'.$id);
             $vehiGrpDel=$redis->srem('S_'.$oldV.'_'.$fcode, 'S_'.$id);
         }
         $result1=array_diff($updateVehi,$oldVehi);
	      log::info($result1);
	      log::info($id);
            foreach ($result1 as $key => $newV) 
	         {
             $Ulist1=$redis->sismember('S_'.$newV.'_'.$fcode, 'S_'.$id);
             $vehiGrpDel1=$redis->sadd('S_'.$newV.'_'.$fcode, 'S_'.$id);
         }
         ///user notification
         $remVehi=array_diff($oldVehi,$updateVehi);
	      log::info($remVehi);
	      log::info($id);
           foreach ($remVehi as $keyV => $vehiList) 
	       {
	         $checkVehiOld=$redis->hget('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         $redis->hdel('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         $getGroup=$redis->smembers('S_'.$vehiList.'_'.$fcode);
	         foreach ($getGroup as $keyG => $groupList) 
	         {
	         	$getUser=$redis->smembers($groupList);
	         	foreach ($getUser as $keyU => $userList) 
	         	{
	         	  $checkVehi=$redis->hget('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         	  if(empty($checkVehi))
	         	  {
	         	  	log::info('now olny added users');
	         	 	//$redis->hdel('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         	 	$success=$redis->hset('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList, ','.$userList.',');
	         	   }
	         	   else {
	         	   	log::info('else already have a users');
	         	     $success=$redis->hset('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList, $checkVehi.$userList.',');
                   }
	         	}
	         }
           }
         ///
        	log::info('  before sending mail ');
        	log::info(array_values($mailId));
		try {
        	if(sizeof($mailId) > 0)
	        	Mail::queue('emails.group', array('username'=>$username, 'groupName'=>$id, 'oldVehi'=>$oldVehi, 'newVehi'=>$updateVehi), function($message) use ($mailId, $id)
	        	{
	                //Log::info("Inside email :" . Session::get ( 'email' ));
	        		$message->to($mailId)->subject('Group Updated -' . $id);
	        	});
        } catch (Exception $e) {
		}
        	Session::flash('message', 'Successfully updated ' . $id . '!');
        	return Redirect::to('vdmGroupsScan/Search'.$id);
        }else {
            $redis->sadd($id,$oldVehi);
            ///ram noti
            $result2=array_diff($oldVehi,$updateVehi);
            foreach ($result2 as $key => $oldV) 
	        {
             $Ulist=$redis->sismember('S_'.$oldV.'_'.$fcode, 'S_'.$id);
             $vehiGrpDel=$redis->srem('S_'.$oldV.'_'.$fcode, 'S_'.$id);
            }
            ///
        	log::info(' vehicles are not available  !!!!');
        	return Redirect::to('vdmGroups/' . $id . '/edit')->with('message','Please select any one vehicle .  ');

        }

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
		///ram noti
		$getVehicle=$redis->smembers($id);
		foreach ($getVehicle as $key => $getV) {
			 $redis->srem('S_'.$getV.'_'.$fcode, 'S_'.$id);
		}
		///
		
		$redis->srem('S_Groups_' . $fcode,$groupId);
		
		
		$redis->srem('S_Groups_Dealer_'.$username.'_'.$fcode,$groupId );
		$redis->srem('S_Groups_Admin_'.$fcode,$groupId );
		
		
		Log::info('-------------- $g -----------'.$groupId);
		$redis->del($groupId);
		
		
		
		$userList = $redis->smembers('S_Users_' . $fcode);
		
		foreach ( $userList as $user ) {
			$redis->srem($user,$groupId);
		}
			
		Session::flash('message', 'Successfully deleted ' . $groupId . '!');
		return Redirect::to('vdmGroups');
	}



	//check the group name
	public function groupIdCheck(){
			
		if(!Auth::check()){
			return Redirect::to('login');
		}
		$username =	Auth::user()->username;
		$redis = Redis::connection();
		$newGroupId1 = Input::get ( 'id');
	    $newGroupId=strtoupper($newGroupId1);
		$fcode = $redis->hget('H_UserId_Cust_Map',$username.':fcode');
		Log::info(' fcode '.$fcode);
		$fcode1 = strtoupper($fcode);
		$groupValue = $redis->SISMEMBER('S_Groups_'.$fcode, $newGroupId.':'.$fcode1);
		if($groupValue == $newGroupId)
		{
			log::info($groupValue);
			log::info($newGroupId);
			return 'fail';
		}
		else{
			return $newGroupId;
		}
		Log::info(' groupValue  '.$groupValue);
	}


	// remove group from user
	public function removeGroup(){

		if(!Auth::check()){
			return Redirect::to('login');
		}
		$username 	=	Auth::user()->username;
		$redis 		= Redis::connection();
		$fcode 		= $redis->hget('H_UserId_Cust_Map',$username.':fcode');
		$ownerShip	=	$redis->hget('H_UserId_Cust_Map', $username.':OWN');

		$grpNameId = Input::get ('grpName');
		$getVehicle=$redis->smembers($grpNameId);
		$redis->srem('S_Groups_' . $fcode,$grpNameId);
		if($ownerShip == 'admin')
			$redis->srem('S_Groups_Admin_'.$fcode,$grpNameId );
		else
			$redis->srem('S_Groups_Dealer_'.$ownerShip.'_'.$fcode,$grpNameId );
		$redis->del($grpNameId);
		$userList = $redis->smembers('S_Users_' . $fcode);
		
		foreach ( $userList as $user ) {
			$redis->srem($user,$grpNameId);
		}
        ///ram noti
		foreach ($getVehicle as $key => $getV) {
			 $redis->srem('S_'.$getV.'_'.$fcode, 'S_'.$grpNameId);
		}
		$userDel=$redis->del('S_'.$grpNameId);
		///

		
		return 'sucess';
		}


	public function _getRef_data($vId, $fcode){

		$redis 		= Redis::connection();
		$refData 	= $redis->hget ( 'H_RefData_' . $fcode, $vId );
		$refData	= json_decode($refData,true);
		return isset($refData['shortName'])?$refData['shortName']:' ';

	}

	// show vehicles from user
        public function _showGroup(){
        		log::info(' arun here ');
                if(!Auth::check()){
                        return Redirect::to('login');
                }
                $username =     Auth::user()->username;
                $redis = Redis::connection();
                $fcode = $redis->hget('H_UserId_Cust_Map',$username.':fcode');
				
				$grpName0 = Input::get ('grpName');
                $grpName  = strtoupper($grpName0);
                $g_List         = $redis->smembers($username);
                $vehiList = [];
                if($grpName !== ''){
                        if($g_List && $g_List !== '' && sizeof($g_List)>0){
                                
                                
                                foreach ( $g_List as $g_Name ) {
                                    $g_level_vehi   =       $redis->smembers($g_Name);
                                        foreach ($g_level_vehi as $key => $vehicles) {
                                            $check  = ($grpName == $g_Name)? true : false;
                                            $vehiList[] =  array('vehicles' => $vehicles, 'check' => $check);
                                        }

                                }


                                $_data = array();
                                foreach ($vehiList as $v) {
                                  if (isset($_data['vehicles'])) {
                                    continue;
                                  }
                                  $_data[] = $v['vehicles'];
                                }

                                
                                $_data = array_unique($_data);
                                // log::info($_data);

                                $g_vehi         =       $redis->smembers($grpName);
                                $vehiList = [];
                                foreach ($_data as $key => $value) {
                                        $check = false;
                                        foreach ($g_vehi as $ke => $val) {
                                                if($val == $value)
                                                        $check = true;
                                        }
                                        // log::info($redis->hget ( 'H_RefData_' . $fcode, $value ));
                                        	// $redis->hget ( 'H_RefData_' . $fcode, $value );
                                        $vehiList[] = array('vehicles' => $value, 'check' => $check, 'shortName' => $this->_getRef_data($value, $fcode));
                                }

                        }
                }

		
		else{
                    log::info('new group');

                /*
                        for new group
                */
                if($g_List && $g_List !== '' && sizeof($g_List)>0){

                    $_data = array();
                    foreach ( $g_List as $g_Name ) {
                            $g_level_vehi   =       $redis->smembers($g_Name);
                            foreach ($g_level_vehi as $key => $vehicles) {
                                    // $check       = ($grpName == $g_Name)? true : false;
                                    array_push($_data,  $vehicles);
                            }

                    }
                    // avoid duplicate value

                    // $_data = array();
                    // foreach ($vehiList as $v) {
                    //   if (isset($_data['vehicles'])) {
                    //     continue;
                    //   }
                    //   $_data[] = $v['vehicles'];
                    // }
					// log::info($_data);
                    $_data = array_unique($_data);
                    // log::info($_data);
                    $vehiList =[];
                    foreach ($_data as $key => $value) {
                            $vehiList[] =  array('vehicles' => $value, 'check' => false, 'shortName' => $this->_getRef_data($value, $fcode));
                    }
                }
            }


            // log::info($vehiList);

            return $vehiList;
    }




	//save group for user 
	public function _saveGroup(){

		log::info(' save function');

		if(!Auth::check()){
			return Redirect::to('login');
		}
		$username 	=	Auth::user()->username;
		$redis 		= 	Redis::connection();
		$fcode 		= 	$redis->hget('H_UserId_Cust_Map',$username.':fcode');
		$fcode1     =   strtoupper($fcode);
		$GrpName2	= 	Input::get ('grpName');
        $GrpName1   =   str_replace(' ', '', $GrpName2);
		$grpName    =   strtoupper($GrpName1);
		$newValue 	= 	Input::get ('newValu');
		$vehList 	= 	Input::get ('grplist');
		$mailId 	= 	array();
		$ownerShip	=	$redis->hget('H_UserId_Cust_Map', $username.':OWN');
		$oldVehi    = 	$redis->smembers($grpName);

		log::info('  ownerShip ');
		log::info($ownerShip);


		if(strpos($grpName, $fcode) === false){
			$grpName = $grpName .':'.$fcode1;
		}

    	
    	$franDetails_json = $redis->hget ( 'H_Franchise', $fcode);
    	$franchiseDetails=json_decode($franDetails_json,true);

    	if(isset($franchiseDetails['email2'])==1)
    		$mailId[]               = $franchiseDetails['email2'];
    		    	
    	if(Session::get('cur')=='dealer')
    		$mailId[] =   $redis->hget ( 'H_UserId_Cust_Map', $username . ':email' );
    		
		if($newValue !== ''){
			$value1=$redis->SISMEMBER('S_Groups_' . $fcode, $grpName);
			if($value1==1)
			{
				return '';
			}
			$redis->sadd('S_Groups_' . $fcode, $grpName);
			$redis->sadd ( $username, $grpName );
		    $redis->sadd ( 'S_'.$grpName, $username );
			if($ownerShip == 'admin')
				$redis->sadd('S_Groups_Admin_'.$fcode,$grpName);
			else
				$redis->sadd('S_Groups_Dealer_'.$ownerShip.'_'.$fcode,$grpName);
		}
		
		$redis->del($grpName);
		log::info(' group del ');		
		foreach($vehList as $vehi) {
			$redis->sadd($grpName,$vehi);
		}
		/// ram noti
          $result=array_diff($oldVehi,$vehList);
            foreach ($result as $key => $oldV) 
	         {
             $Ulist=$redis->sismember('S_'.$oldV.'_'.$fcode, 'S_'.$grpName);
             $vehiGrpDel=$redis->srem('S_'.$oldV.'_'.$fcode, 'S_'.$grpName);
         }
         $result1=array_diff($vehList,$oldVehi);
            foreach ($result1 as $key => $newV) 
	         { 
             $Ulist1=$redis->sismember('S_'.$newV.'_'.$fcode, 'S_'.$grpName);
             $vehiGrpDel1=$redis->sadd('S_'.$newV.'_'.$fcode, 'S_'.$grpName);
         }
         ///user notification
         $remVehi=array_diff($oldVehi,$vehList);
           foreach ($remVehi as $keyV => $vehiList) 
	       {
	         $checkVehiOld=$redis->hget('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         $redis->hdel('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         $getGroup=$redis->smembers('S_'.$vehiList.'_'.$fcode);
	         foreach ($getGroup as $keyG => $groupList) 
	         {
	         	$getUser=$redis->smembers($groupList);
	         	foreach ($getUser as $keyU => $userList) 
	         	{
	         	  $checkVehi=$redis->hget('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         	  if(empty($checkVehi))
	         	  {
	         	  	log::info('now olny added users');
	         	 	//$redis->hdel('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList);
	         	 	$success=$redis->hset('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList, ','.$userList.',');
	         	   }
	         	   else {
	         	   	log::info('else already have a users');
	         	     $success=$redis->hset('H_Vamo_Notification_Vehicle_map_'.$fcode, $vehiList, $checkVehi.$userList.',');
                   }
	         	}
	         }
		   }
		  ///
		if(sizeof($mailId) > 0)
        	Mail::queue('emails.group', array('username'=>$username, 'groupName'=>$grpName, 'oldVehi'=>$oldVehi, 'newVehi'=>$vehList), function($message) use ($mailId, $grpName)
        	{
                //Log::info("Inside email :" . Session::get ( 'email' ));
        		$message->to($mailId)->subject('Group Updated -' . $grpName);
        	});

		log::info(' group insert ');
		return 'sucess';
	
	}
	
	public function groupScanNew($id) {		
       Log::info('  reached group controller ');        		
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
        if(Session::get('cur')=='dealer')		
        {		
            $redisGrpId = 'S_Groups_Dealer_'.$username.'_'.$fcode;		
        }		
        else if(Session::get('cur')=='admin')		
        {		
            $redisGrpId = 'S_Groups_Admin_'.$fcode;		
        }		
        else{		
            $redisGrpId = 'S_Groups_' . $fcode ;		
        }		
        $groupList = $redis->smembers($redisGrpId);		
        $text_word1 = $id;
        $text_word=strtoupper($text_word1);		
        $cou = $redis->SCARD($redisGrpId); //log::info($cou);		
        $orgLi = $redis->sScan( $redisGrpId, 0, 'count', $cou, 'match', '*'.$text_word.'*'); //log::info($orgLi);		
        $orgL = $orgLi[1];		
        foreach ( $orgL as $key=>$group ) {       		
        Log::info(' ---------inside---------------- '.$group);      		
            $vehicleList = $redis->smembers($group);		
               $shortNameList=null;   		
            foreach ( $vehicleList as $vehicle ) {		
                Log::info(' ---------vehicle---------------- '.$vehicle);       		
                $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );		
                $vehicleRefData=json_decode($vehicleRefData,true);		
                $shortName = $vehicleRefData['shortName']; 		
                $shortNameList [] = $shortName;		
            }		
            $vehicleList =implode('<br/>',$vehicleList);		
            $vehicleListArr = array_add($vehicleListArr,$group,$vehicleList);		
            if(isset($shortNameList)) {		
              $shortNameList =implode('<br/>',$shortNameList);		
            }		
            $shortNameListArr = array_add($shortNameListArr,$group,$shortNameList);		
        }		
        Log::info('  reached group controller1 ');      		
        return View::make('vdm.groups.index1', array('groupList'=> $orgL))->with('vehicleListArr',$vehicleListArr)->with('shortNameListArr',$shortNameListArr);		
    }			


}
