<?php

/*
 * GF - Point/Places of Interest
 */

class VdmGFController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$vehicleList = $redis->smembers('S_Vehicles_' . $fcode);
		//H_Vehicle_Device_Map
		$i=0;
		$proximityArr=null;
		$geoParamArr=null;
		$addressArr = null;
		
	
		return View::make('vdm.gf.index', array('vehicleList'=> $vehicleList));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		
		$vehicleList = $redis->smembers('S_Vehicles_' . $fcode);
		
		$userVehicles=null;
		
		foreach ($vehicleList as $key=>$value) {
			$userVehicles=array_add($userVehicles, $value, $value);
		}
		$vehicleList=$userVehicles;

		return View::make ( 'vdm.gf.create' )->with('vehicleList',$vehicleList);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		var_dump(Input::all());

		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$rules = array (
				'vehicleId' => 'required',
				'proximityLevel1' => 'required',
				'gfLocation1' => 'required'
	
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmGF/create' )->withErrors ( $validator );
		} else {
			// store
			
			$redis = Redis::connection ();
			$vehicleId = Input::get ( 'vehicleId' );
			
			$periodicalAlert = Input::get ( 'periodicalAlert' );
			
			$i=1;
			
			$proximityLevel1 = Input::get ( 'proximityLevel1' );
			$gfLocation1 = Input::get ( 'gfLocation1' );
			$gfType1 = Input::get ( 'gfType1' );
			$geoFenceId1 = Input::get ( 'geoFenceId1' );
				
			$proximityLevel2 = Input::get ( 'proximityLevel2' );
			$gfLocation2 = Input::get ( 'gfLocation2' );
			$gfType2 = Input::get ( 'gfType2' );
			$geoFenceId2 = Input::get ( 'geoFenceId2' );
			
			$proximityLevel3 = Input::get ( 'proximityLevel3' );
			$gfLocation3 = Input::get ( 'gfLocation3' );
			$gfType3 = Input::get ( 'gfType3' );
			$geoFenceId3 = Input::get ( 'geoFenceId3' );
			
			$proximityLevel4 = Input::get ( 'proximityLevel4' );
			$gfLocation4 = Input::get ( 'gfLocation4' );
			$gfType4 = Input::get ( 'gfType4' );
			$geoFenceId4 = Input::get ( 'geoFenceId4' );
			
			$proximityLevel5 = Input::get ( 'proximityLevel5' );
			$gfLocation5 = Input::get ( 'gfLocation5' );
			$gfType5 = Input::get ( 'gfType5' );
			$geoFenceId5 = Input::get ( 'geoFenceId5' );

			$redis->hset($vehicleDeviceMapId,$vehicleId . ':userId',$username);
			$redis->hset($vehicleDeviceMapId,$vehicleId . ':periodicalAlert',$periodicalAlert);
				
			
			
			if($gfLocation1 != "") {
				log::info(" GF Adding gfLocation1" . $vehicleId);
				
				$pieces = explode(",", $gfLocation1);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
				
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:proxLevel',$proximityLevel1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1',$gfLocation1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:type',$gfType1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:Id',$geoFenceId1);
			
			}
			if($gfLocation2 != "") {	
	
				$pieces = explode(",", $gfLocation2);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
				
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:proxLevel',$proximityLevel2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2',$gfLocation2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:type',$gfType2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:Id',$geoFenceId2);
		
			}
			
			if($gfLocation3 != "") {
				$pieces = explode(",", $gfLocation3);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
				
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:proxLevel',$proximityLevel3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3',$gfLocation3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:type',$gfType3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:Id',$geoFenceId3);
		
			}
			if($gfLocation4 != "") {
				$pieces = explode(",", $gfLocation4);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
				
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:proxLevel',$proximityLevel4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4',$gfLocation4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:type',$gfType4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:Id',$geoFenceId4);
		
				
			}
			if($gfLocation5 != "") {
				$pieces = explode(",", $gfLocation5);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
				
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:proxLevel',$proximityLevel5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5',$gfLocation5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:type',$gfType5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:Id',$geoFenceId5);
		
			}
			
			Session::flash ( 'message', 'Successfully added places of interest ' . $vehicleId . '!' );
			return Redirect::to ( 'vdmGF' );
	
		
		}
	}
	
	public function getGeoCoding($inputStr) {
		$encodedInput = str_replace (" ", "+", urlencode($inputStr));
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBQFgD9_Pm59zGz0ZfLYCUiH_7zbuZ_bFM&address=' . $encodedInput;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);
	
		// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
		if ($response['status'] != 'OK') {
			return null;
		}
	
		//	print_r($response);
		$geometry = $response['results'][0]['geometry'];
		return $geometry;
	}
	
	public function getGeoReverseCoding($latlng) {
		
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latlng . '&key=AIzaSyBQFgD9_Pm59zGz0ZfLYCUiH_7zbuZ_bFM';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($ch), true);
		
		// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
		if ($response['status'] != 'OK') {
			return null;
		}
		
			print_r($response);
	//	$geometry = $response['results'][0]['geometry'];  //TODO
	//	return $geometry;
	
		
	}


	/**
	 * Display the specified resource.
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
		$vehicleId=$id;
		$i=1;
		$proximityArr=null;
		$geoAddres=null;
		$geoCoordinates =null;
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		
		while($i<=6) {
			$proxLevel = $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence' . $i .':proxLevel' );
			$lat = $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence'. $i. ':lat' );
			$lng = $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence'. $i. ':lng' );
			$address = $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence' . $i);
		//	$str = getGeoReverseCoding($lat .',' . $lng);
			$proximityArr = array_add($proximityArr, $i, $proxLevel);
			$geoAddres = array_add($geoAddres, $i,$address);
			$geoCoordinates = array_add($geoCoordinates, $i, $lat .',' . $lng);
			
			$i=$i+1;
		}
		$proximityArr = implode('<br/>',$proximityArr);
		$geoAddres = implode('<br/>',$geoAddres);
		$geoCoordinates = implode('<br/>',$geoCoordinates);
	//	var_dump($geoCoordinates);
		return View::make('vdm.gf.show',array('vehicleId'=>$vehicleId))->with('geoAddres', $geoAddres)->with('proximityArr',$proximityArr)->with('geoCoordinates',$geoCoordinates);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
			
		$redis = Redis::connection();
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$vehicleId=$id;
		$i=1;
		$gfDataArr=null;
		$gfDataArr=array_add($gfDataArr,'periodicalAlert',$redis->hget($vehicleDeviceMapId,$vehicleId . ':periodicalAlert' ));
		
		
		while($i<=6) { 
			$proxLevel = $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence' . $i .':proxLevel');
			Log::info('Prox Level ' . $proxLevel );
			if(!empty($proxLevel))
			{
				$gfDataArr=array_add($gfDataArr, 'proximityLevel'.$i, $proxLevel);
				$gfDataArr=array_add($gfDataArr, 'gfLocation'.$i, $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence'. $i ));
				$gfDataArr=array_add($gfDataArr,'gfType'.$i, $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i.':type' ));
				$gfDataArr=array_add($gfDataArr,'geoFenceId'.$i, $redis->hget($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i.':Id' ));
				
				Log::info('Prox Level not null ' . $proxLevel . $i);
			
				
			}
			else {
				$gfDataArr=array_add($gfDataArr, 'proximityLevel'.$i, '');
				$gfDataArr=array_add($gfDataArr, 'gfLocation'.$i, '');
				$gfDataArr=array_add($gfDataArr,'gfType'.$i, '' );
				$gfDataArr=array_add($gfDataArr,'geoFenceId'.$i, '' );
				Log::info('empty');
			}
			$i=$i+1;
			
		}
		//var_dump($gfDataArr);
		
		return View::make ( 'vdm.gf.edit', array ('gfDataArr' => $gfDataArr) )->with('vehicleId',$vehicleId);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();
	//	$fcode = $redis->hget ( $vehicleDeviceMapId, $username . ':fcode' );
		$fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
	
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$rules = array (
				
				'proximityLevel1' => 'required',
				'gfLocation1' => 'required'
	
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmGF/create' )->withErrors ( $validator );
		} else {
			// store
			
			$redis = Redis::connection ();
			$vehicleId =$id;
				
			$periodicalAlert = Input::get ( 'periodicalAlert' );
				
			$i=1;
				
			$proximityLevel1 = Input::get ( 'proximityLevel1' );
			$gfLocation1 = Input::get ( 'gfLocation1' );
			$gfType1 = Input::get ( 'gfType1' );
			$geoFenceId1 = Input::get ( 'geoFenceId1' );
			
			$proximityLevel2 = Input::get ( 'proximityLevel2' );
			$gfLocation2 = Input::get ( 'gfLocation2' );
			$gfType2 = Input::get ( 'gfType2' );
			$geoFenceId2 = Input::get ( 'geoFenceId2' );
				
			$proximityLevel3 = Input::get ( 'proximityLevel3' );
			$gfLocation3 = Input::get ( 'gfLocation3' );
			$gfType3 = Input::get ( 'gfType3' );
			$geoFenceId3 = Input::get ( 'geoFenceId3' );
				
			$proximityLevel4 = Input::get ( 'proximityLevel4' );
			$gfLocation4 = Input::get ( 'gfLocation4' );
			$gfType4 = Input::get ( 'gfType4' );
			$geoFenceId4 = Input::get ( 'geoFenceId4' );
				
			$proximityLevel5 = Input::get ( 'proximityLevel5' );
			$gfLocation5 = Input::get ( 'gfLocation5' );
			$gfType5 = Input::get ( 'gfType5' );
			$geoFenceId5 = Input::get ( 'geoFenceId5' );
				
			$redis->hset($vehicleDeviceMapId,$vehicleId . ':userId',$username);
			$redis->hset($vehicleDeviceMapId,$vehicleId . ':periodicalAlert',$periodicalAlert);
			
				
				
			if(!empty($gfLocation1)) {
				log::info(" GF Adding gfLocation1" . $vehicleId);
			
				$pieces = explode(",", $gfLocation1);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
			
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:proxLevel',$proximityLevel1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1',$gfLocation1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:type',$gfType1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence1:Id',$geoFenceId1);
			
			
		
			}
			if(!empty($gfLocation2)) { 
			
				$pieces = explode(",", $gfLocation2);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
			
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:proxLevel',$proximityLevel2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2',$gfLocation2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:type',$gfType2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence2:Id',$geoFenceId2);
			
			}
				
			if(!empty($gfLocation3)) {
				$pieces = explode(",", $gfLocation3);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
			
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:proxLevel',$proximityLevel3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3',$gfLocation3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:type',$gfType3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence3:Id',$geoFenceId3);
			
			}
			if(!empty($gfLocation4)) {
				$pieces = explode(",", $gfLocation4);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
			
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:proxLevel',$proximityLevel4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4',$gfLocation4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:type',$gfType4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence4:Id',$geoFenceId4);
			
			
			}
			if(!empty($gfLocation5)) {
				$pieces = explode(",", $gfLocation5);
				$latitude = $pieces[0];
				$longitude = $pieces[1];
			
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:proxLevel',$proximityLevel5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5',$gfLocation5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:type',$gfType5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':geoFence5:Id',$geoFenceId5);
			
			}
		
			
			Session::flash ( 'message', 'Successfully added GeoFencing Locations ' . $vehicleId . '!' );
			return Redirect::to ( 'vdmGF' );
	
		
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
		log::info("VdmGFController received destroy request");
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$fcode = $redis->hget('H_UserId_Cust_Map', $username . ':fcode');
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $fcode;
		$vehicleId = 	$id;
		$i=1;
		while($i<6) {
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i.':proxLevel');
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i);
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i.':type');
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':geoFence'.$i.':Id');
			$i=$i+1;
		}
		log::info("VdmGFController received destroy request completed");
		Session::flash('message', 'Successfully deleted ' . $vehicleId .' geo fencing!');
		return Redirect::to('vdmGF');
	}


}
