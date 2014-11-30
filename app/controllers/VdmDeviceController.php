<?php
class VdmDeviceController extends \BaseController {
	
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
		
		log::info( 'User name  :' . $username);
		
		
		$redis = Redis::connection ();
		
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$deviceListId = 'L_Device_' . $cpyCode;
		
		$size = $redis->llen ( $deviceListId );
		
		$deviceList = $redis->lrange ( $deviceListId, 0, $size );
		
		
		
		$vehicleList = null;
		
		$deviceRefArr = null;
		
		foreach ( $deviceList as $device ) {
			
			$deviceRefData = $redis->hget ( 'H_RefData', $device );
			echo $deviceRefData['deviceModel'];
			echo $deviceRefData['deviceId'];
			
			// $deviceRefArr = array_add($deviceRefArr, $device, $deviceRefData);
			$vehicleId = $redis->hget ( 'H_Vehicle_Device_Map', $device );
			$vehicleList = array_add ( $vehicleList, $device, $vehicleId );
		}
		
		return View::make ( 'vdm.devices.index', array (
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
		return View::make ( 'vdm.devices.create' );
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$rules = array (
				'deviceId' => 'required',
				'vehicleId' => 'required',
				'shortName' => 'required',
				'regNo' => 'required',
				'vehicleMake' => 'required',
				'vehicleType' => 'required',
				'oprName' => 'required',
				'mobileNo' => 'required|numeric',
				'vehicleCap' => 'required',
				'deviceModel' => 'required', 
				'odoDistance' => 'required' 
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmDevices/create' )->withErrors ( $validator );
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
			$vehicleCap = Input::get ( 'vehicleCap' );
			$deviceModel = Input::get ( 'deviceModel' );
			$odoDistance = Input::get ('odoDistance');
			
			$redis = Redis::connection ();
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			
			$refDataArr = array (
					'shortName' => $shortName,
					'deviceModel' => $deviceModel,
					'regNo' => $regNo,
					'vehicleMake' => $vehicleMake,
					'vehicleType' => $vehicleType,
					'oprName' => $oprName,
					'mobileNo' => $mobileNo,
					'vehicleCap' => $vehicleCap,
					'odoDistance' => $odoDistance
			);
			
			$refDataJson = json_encode ( $refDataArr );
			// H_RefData
			
			$redis->hmset ( 'H_RefData', $deviceId, $refDataJson );
			
			$cpyDeviceList = 'L_Device_' . $cpyCode;
			
			$redis->rpush ( $cpyDeviceList, $deviceId );
			
			$redis->hmset ( 'H_Vehicle_Device_Map', $vehicleId, $deviceId, $deviceId, $vehicleId );
			
           //this is for security check			
			$redis->sadd ( 'S_Vehicles_' . $cpyCode, $vehicleId );
			
			// redirect
			Session::flash ( 'message', 'Successfully created ' . $deviceId . '!' );
			return Redirect::to ( 'vdmDevices' );
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
		$deviceRefData = $redis->hget ( 'H_RefData', $deviceId );
		
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
		$vehicleId = $redis->hget ( 'H_Vehicle_Device_Map', $deviceId );
		
		return View::make ( 'vdm.devices.show', array (
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
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$redis = Redis::connection ();
		$deviceId = $id;
		
		$vehicleId = $redis->hget ( 'H_Vehicle_Device_Map', $deviceId );
		
		$details = $redis->hget ( 'H_RefData', $deviceId );
		
		$refData = json_decode ( $details, true );
		$result = array_key_exists('odoDistance', $refData);

		if($result == false){
			
			$refData = array_add($refData, 'odoDistance', '0.0');
			
		}
		$result = array_key_exists('shortName', $refData);

		
		if($result == false){
					$refData = array_add($refData, 'shortName', '');
		}

		return View::make ( 'vdm.devices.edit', array (
				'deviceId' => $deviceId 
		) )->with ( 'refData', $refData )->with ( 'vehicleId', $vehicleId );
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
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$rules = array (
				
				'vehicleId' => 'required',
				'shortName' => 'required',
				'regNo' => 'required',
				'vehicleMake' => 'required',
				'vehicleType' => 'required',
				'oprName' => 'required',
				'mobileNo' => 'required|numeric',
				'vehicleCap' => 'required',
				'deviceModel' => 'required',
				'odoDistance' => 'required'
		)
		;
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			Log::error(" VdmDeviceConrtoller update validation failed :");
			dd(Input::all ());
			return Redirect::to ( 'vdmDevices/update' )->withErrors ( $validator );
		} else {
			// store
			
			$vehicleId = Input::get ( 'vehicleId' );
			$shortName = Input::get ( 'shortName' );
			$regNo = Input::get ( 'regNo' );
			$vehicleMake = Input::get ( 'vehicleMake' );
			$vehicleType = Input::get ( 'vehicleType' );
			$oprName = Input::get ( 'oprName' );
			$mobileNo = Input::get ( 'mobileNo' );
			$vehicleCap = Input::get ( 'vehicleCap' );
			$deviceModel = Input::get ( 'deviceModel' );
			$odoDistance = Input::get ( 'odoDistance' );
		
			$redis = Redis::connection ();
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'vehicleType'=>$vehicleType);
			//
			
			$refDataArr = array (
					'shortName' => $shortName,
					'deviceModel' => $deviceModel,
					'regNo' => $regNo,
					'vehicleMake' => $vehicleMake,
					'vehicleType' => $vehicleType,
					'oprName' => $oprName,
					'mobileNo' => $mobileNo,
					'vehicleCap' => $vehicleCap,
					'odoDistance' => $odoDistance
			);
			
			$refDataJson = json_encode ( $refDataArr );
			// H_RefData
			
			$deviceId = $id;
			$redis->hmset ( 'H_RefData', $deviceId, $refDataJson );
			
			$cpyDeviceList = 'L_Device_' . $cpyCode;
			
			//TODO  - This will not be fixed - https://github.com/prkothan/VAMOS/issues/1
			//Identify previous vehicle ID - remove it from S_Vehicles_$cpy
			//update the H_Vehicle_Device_Map
			//remove unncessary keys
			//scan the groups, remove old and update it with new one
			
			$redis->hmset ( 'H_Vehicle_Device_Map', $vehicleId, $deviceId, $deviceId, $vehicleId );
			$redis->sadd ( 'S_Vehicles_' . $cpyCode, $vehicleId );
			
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $deviceId . '!' );
			return Redirect::to ( 'vdmDevices' );
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
		
		$deviceId = $id;
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$cpyDeviceList = 'L_Device_' . $cpyCode;
		
		$redis->lrem ( $cpyDeviceList, 1, $deviceId );
		
		$redis->hdel ( 'H_RefData', $deviceId );
		
		$redisVehicleId = $redis->hget ( 'H_Vehicle_Device_Map', $deviceId );
		
		$redis->hdel ( 'H_Vehicle_Device_Map', $redisVehicleId );
		
		$redis->hdel ( 'H_Vehicle_Device_Map', $deviceId );
		
		$redis->srem ( 'S_Vehicles_' . $cpyCode, $redisVehicleId );
		
		$size = $redis->llen('L_Groups_' . $cpyCode);
		
		$groupList = $redis->lrange('L_Groups_' . $cpyCode,0,$size);
		
		foreach ( $groupList as $group ) {
			
			$result = $redis->lrem($group,-1, $redisVehicleId);
		//	Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
		}
		
		Session::flash ( 'message', 'Successfully deleted ' . $deviceId . '!' );
		return Redirect::to ( 'vdmDevices' );
	}
}
