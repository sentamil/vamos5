<?php
class VdmVehicleController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check () ) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  ::' . $username);
		Session::forget('page');
		
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		Log::info('fcode=' . $fcode);
		
		if(Session::get('cur')=='dealer')
		{
			$vehicleListId='S_Vehicles_Dealer_'.$username.'_'.$fcode;
		}
		else if(Session::get('cur')=='admin')
		{
			$vehicleListId='S_Vehicles_Admin_'.$fcode;
		}
		else{
			$vehicleListId = 'S_Vehicles_' . $fcode;
		}
	
		
		
		Log::info('vehicleListId=' . $vehicleListId);

		$vehicleList = $redis->smembers ( $vehicleListId);
		
		$deviceList = null;
		$deviceId = null;
        $shortName =null;
        $shortNameList = null;
        $portNo =null;
        $portNoList = null;
        $mobileNo =null;
        $mobileNoList = null;
        
		foreach ( $vehicleList as $vehicle ) {
			
		      Log::info('$vehicle ' .$vehicle);
			$vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
			
            if(isset($vehicleRefData)) {
                Log::info('$vehicle ' .$vehicleRefData);
            }else {
                continue;
            }
            
			$vehicleRefData=json_decode($vehicleRefData,true);
	
			$deviceId = $vehicleRefData['deviceId'];
            
			$deviceList = array_add ( $deviceList, $vehicle,$deviceId );
            $shortName = $vehicleRefData['shortName']; 
            $shortNameList = array_add($shortNameList,$vehicle,$shortName);
            $portNo=isset($vehicleRefData['portNo'])?$vehicleRefData['portNo']:9964; 
            $portNoList = array_add($portNoList,$vehicle,$portNo);
             $mobileNo=isset($vehicleRefData['gpsSimNo'])?$vehicleRefData['gpsSimNo']:99999; 
            $mobileNoList = array_add($mobileNoList,$vehicle,$mobileNo);
		}
		$demo='ahan';
		$user=null;
		
		$user1= new VdmDealersController;
		$user=$user1->checkuser();
		
		return View::make ( 'vdm.vehicles.index', array (
				'vehicleList' => $vehicleList 
		) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList)->with('demo',$demo)->with ( 'user', $user );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        //get the Org list
        $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
		
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				 $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
			}
		
        $orgList=null;
		$orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
		return View::make ( 'vdm.vehicles.create' )->with ( 'orgList', $orgList );
	}
	
	/**
	 * Store a newly created resource in storage.
	 * TODO validations should be improved to prevent any attacks
	 * 
	 * @return Response
	 */
	 
	/* Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match('/^[\pL\s]+$/u', $value);
});*/
	
	 
	public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		
		$rules = array (
				'deviceId' => 'required|alpha_dash',
				'vehicleId' => 'required|alpha_dash',
				'shortName' => 'required',
				'regNo' => 'required',
				'shortName' => 'required|alpha_dash',
				'vehicleType' => 'required',
				'oprName' => 'required',
				'deviceModel' => 'required', 
				'odoDistance' => 'required',
				'gpsSimNo' => 'required'
				
		);
		$validator = Validator::make ( Input::all (), $rules );
        $redis = Redis::connection ();
        $vehicleId = Input::get ( 'vehicleId' );
        $vehicleIdCheck = $redis->sismember('S_Vehicles_' . $fcode, $vehicleId);
		log::info( '------vehicleIdCheck---------- ::' . $vehicleIdCheck);
        if($vehicleIdCheck==1) {
            Session::flash ( 'message', 'VehicleId' . $vehicleId . 'already exist. Please choose another one' );
            return Redirect::to ( 'vdmVehicles/create' );
        }
        
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmVehicles/create' )->withErrors ( $validator );
		} else {
			// store
			
			$deviceId = Input::get ( 'deviceId' );
			$vehicleId = Input::get ( 'vehicleId' );
			$shortName = Input::get ( 'shortName' );
			$regNo = Input::get ( 'regNo' );
			$vehicleMake = Input::get ( 'vehicleMake' );
			$vehicleType = Input::get ( 'vehicleType' );
			$oprName = Input::get ( 'oprName' );
			$mobileNo = Input::get ( 'mobileNo' );
			$overSpeedLimit = Input::get ( 'overSpeedLimit' );
			$deviceModel = Input::get ( 'deviceModel' );
			$odoDistance = Input::get ('odoDistance');
			$driverName = Input::get ('driverName');
			$gpsSimNo = Input::get ('gpsSimNo');
			$email = Input::get ('email');
            $sendGeoFenceSMS = Input::get ('sendGeoFenceSMS');
            $morningTripStartTime = Input::get ('morningTripStartTime');
            $eveningTripStartTime = Input::get ('eveningTripStartTime');
			
			$orgId = Input::get ('orgId');
            $altShortName= Input::get ('altShortName');
            $parkingAlert = Input::get('parkingAlert');
			
			$refDataArr = array (
					'deviceId' => $deviceId,
					'shortName' => $shortName,
					'deviceModel' => $deviceModel,
					'regNo' => $regNo,
					'vehicleMake' => $vehicleMake,
					'vehicleType' => $vehicleType,
					'oprName' => $oprName,
					'mobileNo' => $mobileNo,
					'overSpeedLimit' => $overSpeedLimit,
					'odoDistance' => $odoDistance,
					'driverName' => $driverName,
					'gpsSimNo' => $gpsSimNo,
					'email' => $email,
					'sendGeoFenceSMS' => $sendGeoFenceSMS,
					'morningTripStartTime' => $morningTripStartTime,
					'eveningTripStartTime' => $eveningTripStartTime,
					'orgId'=>$orgId,
					'parkingAlert'=>$parkingAlert,
					'altShortName' => $altShortName,
					
					
			);
			
			$refDataJson = json_encode ( $refDataArr );
            
			// H_RefData
			
			$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
			
			$cpyDeviceSet = 'S_Device_' . $fcode;
			
			$redis->sadd ( $cpyDeviceSet, $deviceId );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );
			
	       //this is for security check			
			$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
			
			$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
            $redis->sadd('S_Vehicles_'.$orgId.'_'.$fcode , $vehicleId);
            $time =microtime(true);
            /*latitude,longitude,speed,alert,date,distanceCovered,direction,position,status,odoDistance,msgType,insideGeoFence
             13.104870,80.303138,0,N,$time,0.0,N,P,ON,$odoDistance,S,N
              13.04523,80.200222,0,N,0,0.0,null,null,null,0.0,null,N vehicleId=Prasanna_Amaze
			*/
			 $redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
			$time = round($time * 1000);
			
			
			if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				$redis->sadd('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);
			}
			else if(Session::get('cur')=='admin')
			{
				$redis->sadd('S_Vehicles_Admin_'.$fcode,$vehicleId);
			}
			$tmpPositon =  '13.104870,80.303138,0,N,' . $time . ',0.0,N,P,ON,' .$odoDistance. ',S,N';
            $redis->hset ( 'H_ProData_' . $fcode, $vehicleId, $tmpPositon );
			// redirect
			Session::flash ( 'message', 'Successfully created ' . $vehicleId . '!' );
			return Redirect::to ( 'vdmVehicles' );
		}
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function show($id) {
		 Log::info(' inside show....');
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
	
		
		$redis = Redis::connection ();
		$deviceId = $id;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$deviceRefData = $redis->hget ( 'H_RefData_'.$fcode , $deviceId );
		$refDataArr = json_decode ( $deviceRefData, true );
		$deviceRefData = null;
		if (is_array ( $refDataArr )) {
			foreach ( $refDataArr as $key => $value ) {
				
				$deviceRefData = $deviceRefData . $key . ' : ' . $value . ',<br/>';
			}
		} else {
			echo 'JSON decode failed';
			var_dump ( $refDataArr );
		}
		$vehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );
		if($vehicleId==null)
		{
			return Redirect::to('vdmVehicles/dealerSearch');
		}
		
		return View::make ( 'vdm.vehicles.show', array (
				'deviceId' => $deviceId 
		) )->with ( 'deviceRefData', $deviceRefData )->with ( 'vehicleId', $vehicleId );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	
	public function edit($id) {
		
		Log::info('entering edit......');
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$redis = Redis::connection ();
		$vehicleId = $id;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

		$details = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
       
		$refData=null;
	    $refData = array_add($refData, 'overSpeedLimit', '50');
	    $refData = array_add($refData, 'driverName', '');
	    $refData = array_add($refData, 'gpsSimNo', '');
	    $refData = array_add($refData, 'email', ' ');
        $refData = array_add($refData, 'odoDistance', '0');
        $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
        $refData = array_add($refData, 'morningTripStartTime', ' ');
        $refData = array_add($refData, 'eveningTripStartTime', ' ');
		$refData= array_add($refData, 'altShortName',' ');
  
       $refDataFromDB = json_decode ( $details, true );
       
	
        $refDatatmp = array_merge($refData,$refDataFromDB);
        $refData=$refDatatmp;
        //S_Schl_Rt_CVSM_ALH
        
        
       
       $orgId =isset($refDataFromDB['orgId'])?$refDataFromDB['orgId']:'NotAvailabe';
       Log::info(' orgId = ' . $orgId);
       $refData = array_add($refData, 'orgId', $orgId);
       $parkingAlert = isset($refDataFromDB->parkingAlert)?$refDataFromDB->parkingAlert:0;
       $refData= array_add($refData,'parkingAlert',$parkingAlert);
	    $tmpOrgList = $redis->smembers('S_Organisations_' . $fcode);
		log::info( '------login 1---------- '.Session::get('cur'));
		if(Session::get('cur')=='dealer')
			{
				log::info( '------login 1---------- '.Session::get('cur'));
				 $tmpOrgList = $redis->smembers('S_Organisations_Dealer_'.$username.'_'.$fcode);
			}
			else if(Session::get('cur')=='admin')
			{
				 $tmpOrgList = $redis->smembers('S_Organisations_Admin_'.$fcode);
			}
		
		
        $orgList=null;
		  $orgList=array_add($orgList,'Default','Default');
        foreach ( $tmpOrgList as $org ) {
                $orgList = array_add($orgList,$org,$org);
                
            }
	 
	//  var_dump($refData);
		return View::make ( 'vdm.vehicles.edit', array (
				'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with ( 'orgList', $orgList );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function update($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$vehicleId = $id;
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
        
        
		$rules = array (
				'shortName' => 'required',
				'regNo' => 'required',
				'vehicleType' => 'required',
			//	'oprName' => 'required',
			//	'mobileNo' => 'required',
		//		'overSpeedLimit' => 'required',
		);
	
		$validator = Validator::make ( Input::all (), $rules );
        
		if ($validator->fails ()) {
			Log::error(' VdmVehicleConrtoller update validation failed++' );
			return Redirect::to ( 'vdmVehicles/edit' )->withErrors ( $validator );
		} else {
			// store
			$shortName = Input::get ( 'shortName' );
			$regNo = Input::get ( 'regNo' );
			$vehicleMake = Input::get ( 'vehicleMake' );
			$vehicleType = Input::get ( 'vehicleType' );
			$oprName = Input::get ( 'oprName' );
			$mobileNo = Input::get ( 'mobileNo' );
			$overSpeedLimit = Input::get ( 'overSpeedLimit' );
			$deviceModel = Input::get ( 'deviceModel' );
			$driverName = Input::get ( 'driverName' );
			$email = Input::get ( 'email' );
            $orgId = Input::get ( 'orgId' );
            $sendGeoFenceSMS = Input::get ( 'sendGeoFenceSMS' );
            $gpsSimNo = Input::get ('gpsSimNo');
            $odoDistance = Input::get ('odoDistance');
            $morningTripStartTime = Input::get ('morningTripStartTime');
            $eveningTripStartTime = Input::get ('eveningTripStartTime');
            $parkingAlert = Input::get ('parkingAlert');
			
            $altShortName=Input::get ('altShortName');
            $redis = Redis::connection ();
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
            
            $vehicleRefData=json_decode($vehicleRefData,true);
		
			$deviceId=$vehicleRefData['deviceId'];
        //    $odoDistance=$vehicleRefData['odoDistance'];
            //gpsSimNo
          //    $gpsSimNo=$vehicleRefData['gpsSimNo'];
			$refDataArr = array (
					'deviceId' => $deviceId,
					'shortName' => $shortName,
					'deviceModel' => $deviceModel,
					'regNo' => $regNo,
					'vehicleMake' => $vehicleMake,
					'vehicleType' => $vehicleType,
					'oprName' => $oprName,
					'mobileNo' => $mobileNo,
					'overSpeedLimit' => $overSpeedLimit,
					'odoDistance' => $odoDistance,
					'driverName' => $driverName,
					'gpsSimNo' => $gpsSimNo,
					'email' => $email,
					'orgId' =>$orgId,
					'sendGeoFenceSMS' => $sendGeoFenceSMS,
					'morningTripStartTime' => $morningTripStartTime,
					'eveningTripStartTime' => $eveningTripStartTime,
					'parkingAlert' => $parkingAlert,
					'altShortName'=>$altShortName,
			);
			
			$refDataJson = json_encode ( $refDataArr );
			// H_RefData
			$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);//ram
			$refDataJson1=json_decode($refDataJson1,true);
		
			//$org=isset($refDataJson1['orgId'])?$refDataJson1->orgId:'def';
			$org=isset($refDataFromDB->orgId)?$refDataFromDB->orgId:$refDataJson1['orgId'];
			$oldroute=isset($refDataFromDB->shortName)?$refDataFromDB->shortName:$refDataJson1['shortName'];
			
			if($org!=$orgId)
			{
				Log::info('--------------------inside equal--------------------------------'.$org);
				$redis->srem ( 'S_Vehicles_' . $org.'_'.$fcode, $vehicleId);
				$redis->srem('S_Organisation_Route_'.$org.'_'.$fcode,$oldroute);
				$redis->sadd('S_Organisation_Route_'.$org.'_'.$fcode,$shortName);
			}
			if($oldroute!=$shortName && $org==$orgId)
			{
				Log::info('--------------------inside equal1--------------------------------'.$org);
				$redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$oldroute);
				$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
				
			}
			//$redis->sadd('S_Organisation_Route_'.$orgId.'_'.$fcode,$shortName);
			$redis->sadd ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);
			
			$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
			$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
			$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );
			
			
			return VdmVehicleController::edit($vehicleId);
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return Response
	 */
	public function destroy($id) {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		
		$vehicleId = $id;
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$cpyDeviceSet = 'S_Device_' . $fcode;
		
		$deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );
		
		$redis->srem ( $cpyDeviceSet, $deviceId );
		
		$refDataJson1=$redis->hget ( 'H_RefData_' . $fcode, $vehicleId);//ram
			$refDataJson1=json_decode($refDataJson1,true);
		
			$orgId=$refDataJson1['orgId'];
		
		$redis->hdel ( 'H_RefData_' . $fcode, $vehicleId );
        
		$redis->hdel('H_Device_Cpy_Map',$deviceId);
		
		$redisVehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );
		
		$redis->hdel ( $vehicleDeviceMapId, $redisVehicleId );
		
		$redis->hdel ( $vehicleDeviceMapId, $deviceId );
		
		$redis->srem ( 'S_Vehicles_' . $fcode, $redisVehicleId );
		
		$redis->srem ( 'S_Vehicles_' . $orgId.'_'.$fcode, $vehicleId);
		
		$groupList = $redis->smembers('S_Groups_' . $fcode);
		
		foreach ( $groupList as $group ) {
			
			$result = $redis->srem($group,$redisVehicleId);
		//	Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
		}
		
		
		
				$redis->srem('S_Vehicles_Dealer_'.Session::get('page').'_'.$fcode,$vehicleId);
				$redis->srem('S_Vehicles_Dealer_'.$username.'_'.$fcode,$vehicleId);
			
				$redis->srem('S_Vehicles_Admin_'.$fcode,$vehicleId);
			
		
		
		
		Session::flash ( 'message', 'Successfully deleted ' . $deviceId . '!' );
		return Redirect::to ( 'vdmVehicles' );
	}


    public function multi() {
        Log::info(' inside multi....');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
        
        $orgList = $redis->smembers('S_Organisations_'. $fcode);
        
      
        
        $orgArr = array();
        foreach($orgList as $org) {
            $orgArr = array_add($orgArr, $org,$org);
        }
        $orgList = $orgArr;
      
        return View::make ( 'vdm.vehicles.multi' )->with('orgList',$orgList);        
        
    }

	
	 public function dealerSearch() {
        Log::info('------- inside dealerSearch--------');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info(' inside multi ' );
        
        $dealerId = $redis->smembers('S_Dealers_'. $fcode);
        
      
        
        $orgArr = array();
        foreach($dealerId as $org) {
            $orgArr = array_add($orgArr, $org,$org);
        }
        $dealerId = $orgArr;
      
	 

	  
        return View::make ( 'vdm.vehicles.dealerSearch' )->with('dealerId',$dealerId);        
        
    }

	
	
	
	public function findDealerList() {
		log::info( '-----------List----------- ::');
		if (! Auth::check () ) {
			return Redirect::to ( 'login' );
		}
		
			$username = Input::get ( 'dealerId' );
			
		
		
		if($username==null)
		{
			log::info( '--------use one----------' );
			$username = Session::get('page');
		}
		else{
			log::info( '--------use two----------' );
			Session::put('page',$username);
		}
			
		 //$user = User::find(10);
		 $user=User::where('username', '=', $username)->firstOrFail();
log::info( '--------new name----------' .$user);
		Auth::login($user);
		
		
		/*log::info( 'findDealerList----------- ::' . $username);
		
		
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		Log::info('fcode=' . $fcode);
		
	
			$vehicleListId='S_Vehicles_Dealer_'.$username.'_'.$fcode;
		
	
		
		
		Log::info('vehicleListId=' . $vehicleListId);

		$vehicleList = $redis->smembers ( $vehicleListId);
		
		$deviceList = null;
		$deviceId = null;
        $shortName =null;
        $shortNameList = null;
        $portNo =null;
        $portNoList = null;
        $mobileNo =null;
        $mobileNoList = null;
        
		foreach ( $vehicleList as $vehicle ) {
			
		      Log::info('$vehicle ' .$vehicle);
			$vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
			
            if(isset($vehicleRefData)) {
                Log::info('$vehicle1 ' .$vehicleRefData);
            }else{
				continue;
			}
            
			$vehicleRefData=json_decode($vehicleRefData,true);
	
			$deviceId = $vehicleRefData['deviceId'];
            
			$deviceList = array_add ( $deviceList, $vehicle,$deviceId );
            $shortName = $vehicleRefData['shortName']; 
            $shortNameList = array_add($shortNameList,$vehicle,$shortName);
            $portNo=isset($vehicleRefData['portNo'])?$vehicleRefData['portNo']:9964; 
            $portNoList = array_add($portNoList,$vehicle,$portNo);
             $mobileNo=isset($vehicleRefData['gpsSimNo'])?$vehicleRefData['gpsSimNo']:99999; 
            $mobileNoList = array_add($mobileNoList,$vehicle,$mobileNo);
		}
		$demo='ahan';
		$user=null;
		
		$user1= new VdmDealersController;
		$user=$user1->checkuser();
		log::info( '-----final success----------- ::' );
		return View::make ( 'vdm.vehicles.dealerList', array (
				'vehicleList' => $vehicleList 
		) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList);*/
		return Redirect::to ( 'live' );
	}
	
	
	
	
	
	//ram
	
	public function stops($id,$demo) {
        Log::info(' --------------inside 1-----------------'.$id);
		 Log::info(' --------------inside url-----------------'.Request::url() );
		
		  $redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
		Log::info(' stops Ip....'.$ipaddress);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
		Log::info('id------------>'.$username);
		 $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		 Log::info('id------------>'.$fcode);
		 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );            
          $vehicleRefData=json_decode($vehicleRefData,true);
		
			$orgId=$vehicleRefData['orgId'];
		 Log::info('id------------>'.$orgId);
		 $type=0;
        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $id . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
		$url=htmlspecialchars_decode($url);
 
		log::info( ' url :' . $url);
    
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
			// Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		log::info( ' response :' . $response);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch);
         log::info( 'finished');
		 
       $sugStop = json_decode($response,true);
	    log::info( ' user :');
		if(!$sugStop['error']==null)
		{
			 log::info( ' ---------inside null--------- :');
			 
			 //return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);

		}	  
	// var_dump($sugStop);
	  $value = $sugStop['suggestedStop'];
	  log::info( ' 1 :');
	 //  var_dump($value);
	  
	   $address = array();
	   log::info( ' 2 :');
	   try
	   {
		   
	  
        foreach($value as $org => $geoAddress) {			
			  $rowId1 = json_decode($geoAddress,true);
			  $t =0;
			 foreach($rowId1 as $org1 => $rowId2) {
				  if ($t==1) 
				  {
					  $address = array_add($address, $org,$rowId2);
						log::info( ' 3 :' . $t .$rowId2);
						
				   }
				 
				 $t++;
			 }
			 log::info( ' final :'.$t);	
        }	
		}catch(\Exception $e)
	   {
		return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);  
	   }		
        $sugStop = $address;	  
	   log::info( ' success :');
        return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);        
        
    }

	
	public function removeStop($id,$demo) {
        Log::info(' --------------inside remove-----------------'.$id);
		
		Log::info(' --------------inside remove-----------------'.$demo);
		  $redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
		Log::info(' stops Ip....'.$ipaddress);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
		Log::info('id------------>'.$username);
		 $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		 Log::info('id------------>'.$fcode);
		 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );            
          $vehicleRefData=json_decode($vehicleRefData,true);
		
			$orgId=$vehicleRefData['orgId'];
			$routeNo=$vehicleRefData['shortName'];
		 Log::info('org------------>'.$orgId);
		 Log::info('route------------>'.$routeNo);
		 
		
		 $suggeststop=$redis->LRANGE ('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
		  $suggeststop1=$redis->LRANGE ('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
		 if(!$suggeststop==null)
		 {
			if($demo=='normal')
			{
				
			
			 
			$arraystop= $redis->lrange('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
			  foreach($arraystop as $org => $geoAddress){
				  Log::info('inside value present------------>'.$org);
				  $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , $routeNo.':stop'.$org);
			 }
			$redis->del('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode);
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':morning');
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':evening');
			 $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$routeNo);
			
			 
			 
			 //HDEL myhash
			 return Redirect::to ( 'vdmVehicles' );  
			 }
		 }
		  if(!$suggeststop1==null)
		 {
			 Log::info('1');
			if($demo=='alternate')
			{
			 Log::info('2');
			$arraystop= $redis->lrange('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
			  foreach($arraystop as $org => $geoAddress){
				  Log::info('inside value present------------>'.$org);
				  $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':stop'.$org);
			 }
			$redis->del('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode);
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':morning');
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':evening');
			 
			
			 
			 
			 //HDEL myhash
			 return Redirect::to ( 'vdmVehicles' );  
			}
		 }
		 else{
			  Log::info('inside no value present------------>');
			 return Redirect::to ( 'vdmVehicles' ); 
		 }
       // L_Suggest_$routeNo_$orgId_$fcode
	   //H_Stopseq_$orgId_$fcode $routeNo:morning
	   //H_Stopseq_$orgId_$fcode $routeNo:evening
       // return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);        
        
    }

    
	
	public function stops1($id,$demo) {
        Log::info(' --------------inside 1-----------------'.$id);
		 Log::info(' --------------inside url-----------------'.Request::url() );
		
		  $redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
		Log::info(' stops Ip....'.$ipaddress);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
		Log::info('id------------>'.$username);
		 $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		 Log::info('id------------>'.$fcode);
		 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );            
          $vehicleRefData=json_decode($vehicleRefData,true);
		
			$orgId=$vehicleRefData['orgId'];
		 Log::info('id------------>'.$orgId);
		 $type=0;
        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $id . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
		$url=htmlspecialchars_decode($url);
 
		log::info( ' url :' . $url);
    
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
			// Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		log::info( ' response :' . $response);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch);
         log::info( 'finished');
		 
       $sugStop = json_decode($response,true);
	    log::info( ' user :');
		if(!$sugStop['error']==null)
		{
			 log::info( ' ---------inside null--------- :');
			 
			 //return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);

		}	  
	// var_dump($sugStop);
	  $value = $sugStop['suggestedStop'];
	  log::info( ' 1 :');
	 //  var_dump($value);
	  
	   $address = array();
	   log::info( ' 2 :');
	   try
	   {
		   
	  
        foreach($value as $org => $geoAddress) {			
			  $rowId1 = json_decode($geoAddress,true);
			  $t =0;
			 foreach($rowId1 as $org1 => $rowId2) {
				  if ($t==1) 
				  {
					  $address = array_add($address, $org,$rowId2);
						log::info( ' 3 :' . $t .$rowId2);
						
				   }
				 
				 $t++;
			 }
			 log::info( ' final :'.$t);	
        }	
		}catch(\Exception $e)
	   {
		return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$id)->with('demo',$demo);  
	   }		
        $sugStop = $address;	  
	   log::info( ' success :');
        return View::make ( 'vdm.vehicles.dealerSearch' )->with('sugStop',$sugStop)->with('vehicleId',$id);        
        
    }

	
	public function removeStop1($id,$demo) {
        Log::info(' --------------inside remove1-----------------'.$id);
		
		Log::info(' --------------inside remove1-----------------'.$demo);
		  $redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
		Log::info(' stops Ip....'.$ipaddress);
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
		Log::info('id------------>'.$username);
		 $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		 Log::info('id------------>'.$fcode);
		 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $id );            
          $vehicleRefData=json_decode($vehicleRefData,true);
		
			$orgId=$vehicleRefData['orgId'];
			$routeNo=$vehicleRefData['shortName'];
		 Log::info('org------------>'.$orgId);
		 Log::info('route------------>'.$routeNo);
		 
		
		 $suggeststop=$redis->LRANGE ('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
		  $suggeststop1=$redis->LRANGE ('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode , 0, -1);
		 if(!$suggeststop==null)
		 {
			if($demo=='normal')
			{
				
			
			 
			$arraystop= $redis->lrange('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
			  foreach($arraystop as $org => $geoAddress){
				  Log::info('inside value present------------>'.$org);
				  $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , $routeNo.':stop'.$org);
			 }
			$redis->del('L_Suggest_'.$routeNo.'_'.$orgId.'_'.$fcode);
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':morning');
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , $routeNo.':evening');
			 $redis->srem('S_Organisation_Route_'.$orgId.'_'.$fcode,$routeNo);
			
			 
			 
			 //HDEL myhash
			 return Redirect::to ( 'vdmVehicles/dealerSearch' );  
			 }
		 }
		  if(!$suggeststop1==null)
		 {
			 Log::info('1');
			if($demo=='alternate')
			{
			 Log::info('2');
			$arraystop= $redis->lrange('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode ,0 ,-1);
			  foreach($arraystop as $org => $geoAddress){
				  Log::info('inside value present------------>'.$org);
				  $redis->hdel('H_Bus_Stops_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':stop'.$org);
			 }
			$redis->del('L_Suggest_Alt'.$routeNo.'_'.$orgId.'_'.$fcode);
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':morning');
			 $redis->hdel('H_Stopseq_'.$orgId.'_'.$fcode , 'Alt'.$routeNo.':evening');
			 
			
			 
			 
			 //HDEL myhash
			 return Redirect::to ( 'vdmVehicles/dealerSearch' );  
			}
		 }
		 else{
			  Log::info('inside no value present------------>');
			 return Redirect::to ( 'vdmVehicles/dealerSearch' ); 
		 }
       // L_Suggest_$routeNo_$orgId_$fcode
	   //H_Stopseq_$orgId_$fcode $routeNo:morning
	   //H_Stopseq_$orgId_$fcode $routeNo:evening
       // return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$id);        
        
    }

    
	
	public function generate()
	{
		
		log::info(" inside generate");
		$vehicleId=Input::get('vehicleId');
		$type=Input::get('type');
		$demo=Input::get('demo');
		 log::info("id------------>".$type);
		  log::info("demo------------>".$demo);
			log::info(" inside generate .." . $vehicleId);	
		$rules = array (
				'date' => 'required|date:dd-MM-yyyy|',
				'mst' => 'required|date_format:H:i',
				'met' => 'required|date_format:H:i',
				'est' => 'required|date_format:H:i',
				'eet' => 'required|date_format:H:i',
				'type' => 'required'
				
		);
		log::info(" inside 1 .." . $vehicleId);
		$validator = Validator::make ( Input::all (), $rules );
		log::info(" inside 2 .." . $vehicleId);
		if ($validator->fails ()) {
			log::info(" inside 4 .." . $vehicleId);
			return Redirect::to ( 'vdmVehicles/stops/'.$vehicleId .'/'.$demo)->withErrors ( $validator );
		} else {
			log::info(" inside 3 .." . $vehicleId);
			$date=Input::get('date');
		$mst=Input::get('mst');
		$met=Input::get('met');
		$est=Input::get('est');
		$eet=Input::get('eet');
		
		Log::info(' inside generate....'.$date .$mst .$met .$est .$eet. $vehicleId);			
		 if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
		  $redis = Redis::connection();
		$ipaddress = $redis->get('ipaddress');
        $parameters='?userId='. $username;
		Log::info('id------------>'.$username);
		 $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		 Log::info('id------------>'.$fcode);
		 $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );            
          $vehicleRefData=json_decode($vehicleRefData,true);
		
			$orgId=$vehicleRefData['orgId'];
		 Log::info('id------------>'.$orgId);
        $parameters=$parameters . '&vehicleId=' . $vehicleId . '&fcode=' . $fcode . '&orgcode=' .$orgId. '&presentDay=' . $date . '&mST=' .$mst. '&mET=' .$met. '&eST=' .$est . '&eET='.$eet .'&type='.$type.'&demo='.$demo;        		
        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?'. $parameters;
		$url=htmlspecialchars_decode($url); 
		log::info( ' url :' . $url);    
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 150);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$response = curl_exec($ch);
		log::info( ' response :' . $response);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch);
  
		$sugStop1 = json_decode($response,true);
	    log::info( ' ------------check----------- :');
		if(!$sugStop1['error']==null)
		{
			 log::info( ' ---------inside null--------- :');
			 return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$vehicleId)->with('demo',$demo);
		}
		
        $url = 'http://' .$ipaddress . ':9000/getSuggestedStopsForVechiles?vehicleId=' . $vehicleId . '&fcode=' . $fcode . '&orgcode=' .$orgId . '&type=' .$type.'&demo='.$demo;
        $url=htmlspecialchars_decode($url);
 
		 log::info( ' url :' . $url);    
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $url);
    // Include header in result? (0 = yes, 1 = no)
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		  $response = curl_exec($ch);
         log::info( ' response :' . $response);
         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
         curl_close($ch);
         log::info( 'finished');
		 
        $sugStop = json_decode($response,true);
	    log::info( ' user :');
		if(!$sugStop['error']==null)
		{
			 log::info( ' ---------inside null--------- :');
			 return View::make ( 'vdm.vehicles.stopgenerate' )->with('vehicleId',$vehicleId)->with('demo',$demo);
		}
	     $value = $sugStop['suggestedStop'];
	     log::info( ' 1 :');
	     $address = array();
	     log::info( ' 2 :');
         foreach($value as $org => $rowId) {			
		 $rowId1 = json_decode($rowId,true);
		 $t =0;
		 foreach($rowId1 as $org1 => $rowId2) {
			  if ($t==1) 
			  {
				  $address = array_add($address, $org,$rowId2);
					log::info( ' 3 :' . $t .$rowId2);
					
			  }
			 
			 $t++;
		 }
		 log::info( ' final :'.$t);		
        }		
        $sugStop = $address;	  
	   log::info( ' success :');	
        return View::make ( 'vdm.vehicles.showStops' )->with('sugStop',$sugStop)->with('vehicleId',$vehicleId)->with('demo',$demo);   
		 
		 }
		 
	}
    
    public function storeMulti() {
        Log::info(' inside multiStore....');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
     
        $vehicleDetails = Input::get ( 'vehicleDetails' );
        
        $orgId = Input::get('orgId');
     
        Log::info(' inside multi ' . $orgId); 
        
        $redis = Redis::connection ();
        $redis->set('MultiVehicle:'.$fcode, $vehicleDetails) ;
            
        $parameters = 'key='.'MultiVehicle:'.$fcode . '&orgId='.$orgId;
        
        //TODO - remove ..this is just for testing
       // $ipaddress = 'localhost';
        
        $url = 'http://' .$ipaddress . ':9000/addMultipleVehicles?' . $parameters;
    $url=htmlspecialchars_decode($url);
 
    log::info( ' url :' . $url);
    
    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
    // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch);
        
       return Redirect::to ( 'vdmVehicles' );   
       
       
        
    }
        
    
}
