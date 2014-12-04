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
		
		log::info( 'User name  :' . $username);
		
		
		$redis = Redis::connection ();
		
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		
		$vehicleListId = 'S_Vehicles_' . $cpyCode;

		$vehicleList = $redis->smembers ( $vehicleListId);
		
		$deviceList = null;
		$deviceId = null;

		foreach ( $vehicleList as $vehicle ) {
			
		
			$vehicleRefData = $redis->hget ( 'H_RefData_' . $cpyCode, $vehicle );
			
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		
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
			$vehicleCap = Input::get ( 'vehicleCap' );
			$deviceModel = Input::get ( 'deviceModel' );
			$odoDistance = Input::get ('odoDistance');
			
			$redis = Redis::connection ();
			
			// $refDataArr = array('regNo'=>$regNo,'vehicleMake'=>$vehicleMake,'vehicleType'=>$vehicleType,'oprName'=>$oprName,
			// 'mobileNo'=>$mobileNo,'vehicleCap'=>$vehicleCap,'deviceModel'=>$deviceModel);
			
			$refDataArr = array (
					'deviceId' => $deviceId,
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

			
			$redis->hset ( 'H_RefData_' . $cpyCode, $vehicleId, $refDataJson );
			
			$cpyDeviceSet = 'S_Device_' . $cpyCode;
			
			$redis->sadd ( $cpyDeviceSet, $deviceId );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId , $deviceId, $deviceId, $vehicleId );
			
           //this is for security check			
			$redis->sadd ( 'S_Vehicles_' . $cpyCode, $vehicleId );
			
			$redis->hset('H_Device_Cpy_Map',$deviceId,$cpyCode);
			
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$deviceRefData = $redis->hget ( 'H_RefData_'.$cpyCode , $deviceId );
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
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		
		$redis = Redis::connection ();
		$vehicleId = $id;
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );

		$details = $redis->hget ( 'H_RefData_' . $cpyCode, $vehicleId );
		
		$refData = json_decode ( $details, true );


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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$rules = array (
				
				'deviceId' => 'required',
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
			return Redirect::to ( 'vdmVehicles/update' )->withErrors ( $validator );
		} else {
			// store
			
			$deviceId = Input::get ( 'deviceId' );
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

			$refDataArr = array (
					'deviceId' => $deviceId,
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
			

			$redis->hset ( 'H_RefData_' . $cpyCode, $vehicleId, $refDataJson );
			
			$redis->hmset ( $vehicleDeviceMapId, $vehicleId, $deviceId, $deviceId, $vehicleId );
			$redis->sadd ( 'S_Vehicles_' . $cpyCode, $vehicleId );
			
			// redirect
			Session::flash ( 'message', 'Successfully updated ' . $deviceId . '!' );
			return Redirect::to ( 'vdmVehicles' );
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$cpyDeviceSet = 'S_Device_' . $cpyCode;
		
		$deviceId = $redis->hget ( $vehicleDeviceMapId, $vehicleId );
		
		$redis->srem ( $cpyDeviceSet, $deviceId );
		
		$redis->hdel ( 'H_RefData_' . $cpyCode, $vehicleId );
		$redis->hdel('H_Device_Cpy_Map',$deviceId);
		
		$redisVehicleId = $redis->hget ( $vehicleDeviceMapId, $deviceId );
		
		$redis->hdel ( $vehicleDeviceMapId, $redisVehicleId );
		
		$redis->hdel ( $vehicleDeviceMapId, $deviceId );
		
		$redis->srem ( 'S_Vehicles_' . $cpyCode, $redisVehicleId );
		
		$groupList = $redis->smembers('S_Groups_' . $cpyCode);
		
		foreach ( $groupList as $group ) {
			
			$result = $redis->srem($group,$redisVehicleId);
		//	Log::info('going to delete vehicle from group ' . $group . $redisVehicleId . $result);
		}
		
		Session::flash ( 'message', 'Successfully deleted ' . $deviceId . '!' );
		return Redirect::to ( 'vdmVehicles' );
	}
}
