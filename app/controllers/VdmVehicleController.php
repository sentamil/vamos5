<?php
class VdmVehicleController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		log::info( 'User name  ::' . $username);
		
		
		$redis = Redis::connection ();
		
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		
		Log::info('fcode=' . $fcode);
		
		$vehicleListId = 'S_Vehicles_' . $fcode;
		
		Log::info('vehicleListId=' . $vehicleListId);

		$vehicleList = $redis->smembers ( $vehicleListId);
		
		$deviceList = null;
		$deviceId = null;

		foreach ( $vehicleList as $vehicle ) {
			
		
			$vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
			
			$vehicleRefData=json_decode($vehicleRefData);
	
			$deviceId = $vehicleRefData->deviceId;

		//	$deviceId = array_get($vehicleRefData, 'deviceId');
			$deviceList = array_add ( $deviceList, $vehicle,$deviceId );
		}
		
		return View::make ( 'vdm.vehicles.index', array (
				'vehicleList' => $vehicleList 
		) )->with ( 'deviceList', $deviceList );
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
		return View::make ( 'vdm.vehicles.create' );
	}
	
	/**
	 * Store a newly created resource in storage.
	 * TODO validations should be improved to prevent any attacks
	 * 
	 * @return Response
	 */
	public function store() {
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		
		$rules = array (
				'deviceId' => 'required',
				'vehicleId' => 'required',
				'shortName' => 'required',
				'regNo' => 'required',
				'vehicleMake' => 'required',
				'vehicleType' => 'required',
				'oprName' => 'required',
				'overSpeedLimit' => 'required',
				'deviceModel' => 'required', 
				'odoDistance' => 'required',
				'driverName' => 'required' ,
				'gpsSimNo' => 'required'
		);
		$validator = Validator::make ( Input::all (), $rules );
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
			$useSOS4Conf = Input::get ('useSOS4Conf');
            $sendGeoFenceSMS = Input::get ('sendGeoFenceSMS');
			
			$redis = Redis::connection ();
			
		
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
					'useSOS4Conf' => $useSOS4Conf,
					'sendGeoFenceSMS' => $sendGeoFenceSMS
			);
			
			$refDataJson = json_encode ( $refDataArr );
            
			// H_RefData

			
			$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
			
			$cpyDeviceSet = 'S_Device_' . $fcode;
			
			$redis->sadd ( $cpyDeviceSet, $deviceId );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );
			
			$redis->set('SOSConf'.':' .$vehicleId.':'.$fcode, $useSOS4Conf);
            $redis->expire('SOSConf'.':' .$vehicleId.':'.$fcode,$useSOS4Conf*60*60);
			
           //this is for security check			
			$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
			
			$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
            
            $time =microtime(true);
            /*latitude,longitude,speed,alert,date,distanceCovered,direction,position,status,odoDistance,msgType,insideGeoFence
             13.104870,80.303138,0,N,$time,0.0,N,P,ON,$odoDistance,S,N
              13.04523,80.200222,0,N,0,0.0,null,null,null,0.0,null,N vehicleId=Prasanna_Amaze
             * 
			*/
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
		
		Log::info('entering edit');
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
	//    $refData = json_decode ( $details, true );
       
	    $refData = array_add($refData, 'overSpeedLimit', '50');
	    $refData = array_add($refData, 'driverName', '');
	    $refData = array_add($refData, 'gpsSimNo', '');
	    $refData = array_add($refData, 'email', ' ');
	    $refData = array_add($refData, 'useSOS4Conf', '0');
        $refData = array_add($refData, 'odoDistance', '0');
         $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
     
   /*  if(isset($refData['odoDistance'])) {
         //do nothing
     }else {
         $refData = array_add($refData, 'odoDistance', '0');
     }
    */
  
       $refData1 = json_decode ( $details, true );
	
        $refData2 = array_merge($refData,$refData1);
        $refData=$refData2;
 
	   // Log::info('update vehicles' . $refData);
		return View::make ( 'vdm.vehicles.edit', array (
				'vehicleId' => $vehicleId ) )->with ( 'refData', $refData );
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
				'vehicleMake' => 'required',
				'vehicleType' => 'required',
				'oprName' => 'required',
				'mobileNo' => 'required',
				'overSpeedLimit' => 'required',
				'deviceModel' => 'required',
				'driverName' => 'required',
			
		);
		
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			Log::error(' VdmDeviceConrtoller update validation failed ....:' );
			
			return Redirect::to ( 'vdmVehicles/update' )->withErrors ( $validator );
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
			$useSOS4Conf = Input::get ( 'useSOS4Conf' );
            $sendGeoFenceSMS = Input::get ( 'sendGeoFenceSMS' );
            $gpsSimNo = Input::get ('gpsSimNo');
            
            $redis = Redis::connection ();
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
            
            $vehicleRefData=json_decode($vehicleRefData,true);
		
			$deviceId=$vehicleRefData['deviceId'];
            $odoDistance=$vehicleRefData['odoDistance'];
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
					'useSOS4Conf' => $useSOS4Conf,
					'sendGeoFenceSMS' => $sendGeoFenceSMS
			);
			
			$refDataJson = json_encode ( $refDataArr );
			// H_RefData
			

			$redis->hset ( 'H_RefData_' . $fcode, $vehicleId, $refDataJson );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
			$redis->sadd ( 'S_Vehicles_' . $fcode, $vehicleId );
			$redis->hset('H_Device_Cpy_Map',$deviceId,$fcode);
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $vehicleId . '!' );
	//		return Redirect::to ( 'vdmVehicles' );
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
		
		$redis->hdel ( 'H_RefData_' . $fcode, $vehicleId );
		$redis->hdel('H_Device_Cpy_Map',$deviceId);
		
		$redisVehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );
		
		$redis->hdel ( $vehicleDeviceMapId, $redisVehicleId );
		
		$redis->hdel ( $vehicleDeviceMapId, $deviceId );
		
		$redis->srem ( 'S_Vehicles_' . $fcode, $redisVehicleId );
		
		$groupList = $redis->smembers('S_Groups_' . $fcode);
		
		foreach ( $groupList as $group ) {
			
			$result = $redis->srem($group,$redisVehicleId);
		//	Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
		}
		
		Session::flash ( 'message', 'Successfully deleted ' . $deviceId . '!' );
		return Redirect::to ( 'vdmVehicles' );
	}
}
