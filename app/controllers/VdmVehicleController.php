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
        $shortName =null;
        $shortNameList = null;
        $portNo =null;
        $portNoList = null;
        $mobileNo =null;
        $mobileNoList = null;
        
		foreach ( $vehicleList as $vehicle ) {
			
		  
			$vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicle );
			
            if(isset($vehicleRefData)) {
         //       Log::info('$vehicle ' .$vehicleRefData);
            }else {
                continue;
            }
            
			$vehicleRefData=json_decode($vehicleRefData);
	
			$deviceId = $vehicleRefData->deviceId;
            
			$deviceList = array_add ( $deviceList, $vehicle,$deviceId );
            $shortName = $vehicleRefData->shortName; 
            $shortNameList = array_add($shortNameList,$vehicle,$shortName);
            $portNo=isset($vehicleRefData->portNo)?$vehicleRefData->portNo:9964; 
            $portNoList = array_add($portNoList,$vehicle,$portNo);
             $mobileNo=isset($vehicleRefData->gpsSimNo)?$vehicleRefData->gpsSimNo:99999; 
            $mobileNoList = array_add($mobileNoList,$vehicle,$mobileNo);
		}
		
		return View::make ( 'vdm.vehicles.index', array (
				'vehicleList' => $vehicleList 
		) )->with ( 'deviceList', $deviceList )->with('shortNameList',$shortNameList)->with('portNoList',$portNoList)->with('mobileNoList',$mobileNoList);
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
        //get the school list
        $tmpSchoolList = $redis->smembers('S_Schools_' . $fcode);
        $schoolList=null;
        foreach ( $tmpSchoolList as $school ) {
                $schoolList = array_add($schoolList,$school,$school);
                
            }
		return View::make ( 'vdm.vehicles.create' )->with ( 'schoolList', $schoolList );
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
        if($vehicleIdCheck==1) {
            Session::flash ( 'message', 'VehicleId' . $vehicleId . 'already exist. Please choose another one' );
            return Redirect::to ( 'vdmVehicles' );
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
			$schoolName = Input::get ('schoolName');
            $routeNo = Input::get ('routeNo');
			
            $routeNo=isset($routeNo) ?$routeNo:$shortName;
            
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
					'schoolName'=>$schoolName,
					'routeNo'=>$routeNo
					
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
            
            $time =microtime(true);
            /*latitude,longitude,speed,alert,date,distanceCovered,direction,position,status,odoDistance,msgType,insideGeoFence
             13.104870,80.303138,0,N,$time,0.0,N,P,ON,$odoDistance,S,N
              13.04523,80.200222,0,N,0,0.0,null,null,null,0.0,null,N vehicleId=Prasanna_Amaze
             * 
			*/
			$time = round($time * 1000);
			
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
	    $refData = array_add($refData, 'overSpeedLimit', '50');
	    $refData = array_add($refData, 'driverName', '');
	    $refData = array_add($refData, 'gpsSimNo', '');
	    $refData = array_add($refData, 'email', ' ');
        $refData = array_add($refData, 'odoDistance', '0');
        $refData = array_add($refData, 'sendGeoFenceSMS', 'no');
        $refData = array_add($refData, 'morningTripStartTime', ' ');
        $refData = array_add($refData, 'eveningTripStartTime', ' ');
  
  
       $refData1 = json_decode ( $details, true );
       
	
        $refData2 = array_merge($refData,$refData1);
        $refData=$refData2;
        //S_Schl_Rt_CVSM_ALH
        
        //TODO - 'Undefined index: schoolName'
        $schoolName =isset($refData['schoolName'])?$refData['schoolName']:0;
        
        
        $tmpRouteList = $redis->smembers('S_School_Route_' . $schoolName . '_' . $fcode);
        $routeList=array();
         foreach ( $tmpRouteList as $routeNo ) {
                $routeList = array_add($routeList,$routeNo,$routeNo);
                
            }
        $routeNo  = isset($vehicleRefData->routeNo)?$vehicleRefData->routeNo:0; 
        $refData= array_add($refData,'routeNo',$routeNo);
        $schoolName  = isset($vehicleRefData->schoolName)?$vehicleRefData->schoolName:0; 
        $refData= array_add($refData,'schoolName',$schoolName);
	    Log::info('$schoolName ' . $schoolName);
		return View::make ( 'vdm.vehicles.edit', array (
				'vehicleId' => $vehicleId ) )->with ( 'refData', $refData )->with('routeList',$routeList);
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
				'oprName' => 'required',
				'mobileNo' => 'required',
				'overSpeedLimit' => 'required',
				'deviceModel' => 'required',
				'driverName' => 'required'
		);
	
		$validator = Validator::make ( Input::all (), $rules );
        
		if ($validator->fails ()) {
			Log::error(' VdmDeviceConrtoller update validation failed++' );
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
            $sendGeoFenceSMS = Input::get ( 'sendGeoFenceSMS' );
            $gpsSimNo = Input::get ('gpsSimNo');
            $odoDistance = Input::get ('odoDistance');
            $routeNo = Input::get ('routeNo');
            $morningTripStartTime = Input::get ('morningTripStartTime');
            $eveningTripStartTime = Input::get ('eveningTripStartTime');
            
            $redis = Redis::connection ();
            $vehicleRefData = $redis->hget ( 'H_RefData_' . $fcode, $vehicleId );
            
            $vehicleRefData=json_decode($vehicleRefData,true);
		
			$deviceId=$vehicleRefData['deviceId'];
            $schoolName = $vehicleRefData['schoolName'];
            Log::info(' route No =' .$routeNo );
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
					'sendGeoFenceSMS' => $sendGeoFenceSMS,
					'morningTripStartTime' => $morningTripStartTime,
					'eveningTripStartTime' => $eveningTripStartTime,
					'routeNo' => $routeNo,
					'schoolName' => $schoolName
					
			);
			
			$refDataJson = json_encode ( $refDataArr );
			// H_RefData
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


    public function multi() {
        Log::info(' inside multi....');
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        
        $schoolListId = 'S_Schools_' . $fcode;
        Log::info('schoolListId=' . $schoolListId);
        $schools = $redis->smembers ( $schoolListId);
        $schoolList= array();
        foreach ($schools as $key => $value) {
            $schoolList=array_add($schoolList, $value,$value);
        }
        
        
        Log::info(' inside multi');
        return View::make ( 'vdm.vehicles.multi' )->with('schoolList',$schoolList);        
        
    }

    
    
    public function storeMulti() {
        Log::info(' inside multiStore....');
        
        if (App::environment('development'))
        {
            $ipaddress='localhost';
            $port ='9005';
        }
        else {
            $port ='9000';
        }
        
        
        if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
       
        $vehicleDetails = Input::get ( 'vehicleDetails' );
        $schoolId = Input::get ( 'schoolId' );
        $redis = Redis::connection ();
        $redis->set('MultiVehicle:'.$fcode, $vehicleDetails) ;
        
        $parameters = 'key='.'MultiVehicle:'.$fcode . '&schoolId=' . $schoolId ;
        $ipaddress = $redis->get('ipaddress');
   
     //   $url = 'http://localhost:9005/addMultipleVehicles?' . $parameters;
    
    log::info( ' ipaddress :' . $ipaddress);
    
        $url = 'http://' .$ipaddress . ':'.$port .'/addMultipleVehicles?' . $parameters;
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
