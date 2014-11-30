<?php

/*
 * POI - Point/Places of Interest
 */

class VdmPOIController extends \BaseController {

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
		$cpyCode = $redis->hget('H_UserId_Cust_Map', $username . ':cpyCode');
		$vehicleList = $redis->smembers('S_Vehicles_' . $cpyCode);
		//H_Vehicle_Device_Map
		$i=0;
		$proximityArr=null;
		$geoParamArr=null;
		$addressArr = null;
		
	
		return View::make('vdm.poi.index', array('vehicleList'=> $vehicleList));
	//	https://maps.googleapis.com/maps/api/geocode/json?latlng=12.9190519,80.2300343&key=AIzaSyBQFgD9_Pm59zGz0ZfLYCUiH_7zbuZ_bFM
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
		
		$cpyCode = $redis->hget('H_UserId_Cust_Map', $username . ':cpyCode');
		
		$vehicleList = $redis->smembers('S_Vehicles_' . $cpyCode);
		
		$userVehicles=null;
		
		foreach ($vehicleList as $key=>$value) {
			$userVehicles=array_add($userVehicles, $value, $value);
		}
		$vehicleList=$userVehicles;

		return View::make ( 'vdm.poi.create' )->with('vehicleList',$vehicleList);
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
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$rules = array (
				'vehicleId' => 'required',
				'proximityLevel1' => 'required',
				'location1' => 'required'
	
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmPOI/create' )->withErrors ( $validator );
		} else {
			// store
			
			$redis = Redis::connection ();
			$vehicleId = Input::get ( 'vehicleId' );
			
			//clear all existing places of interest 
			
			$i=1;
		
			
			while($i<6) {
			
				$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':proxLevel');
				$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':lat');
				$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':lng');
				$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i );
				$i=$i+1;
			}
			
			//now add freshly
			
			$proximityLevel1 = Input::get ( 'proximityLevel1' );
			$location1 = Input::get ( 'location1' );
			$proximityLevel2 = Input::get ( 'proximityLevel2' );
			$location2 = Input::get ( 'location2' );
			$proximityLevel3 = Input::get ( 'proximityLevel3' );
			$location3 = Input::get ( 'location3' );
			$proximityLevel4 = Input::get ( 'proximityLevel4' );
			$location4 = Input::get ( 'location4' );
			$proximityLevel5 = Input::get ( 'proximityLevel5' );
			$location5 = Input::get ( 'location5' );


			$poiController = new VdmPOIController();
			$redis->hset($vehicleDeviceMapId,$vehicleId . ':userId',$username);
			
			if($location1 != "") {
				log::info(" POI Adding location1" . $vehicleId);
				$geometry = $poiController->getGeoCoding($location1);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:proxLevel',$proximityLevel1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1',$location1);
				
				$i=1;
				$proxLevel = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':proxLevel' );
				$lat = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc'. $i. ':lat' );
				$lng = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc'. $i. ':lng' );
				
				
				log::info(" POI Added location1 " . $latitude . ',' . $longitude);
				log::info( $proxLevel . ',' . $lat . ',' . $lng);
				sleep(1);
			}
			if($location2 != "") {	
	
				$geometry = $poiController->getGeoCoding($location2);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:proxLevel',$proximityLevel2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2',$location2);
				sleep(1);
			}
			
			if($location3 != "") {
				$geometry = $poiController->getGeoCoding($location3);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:proxLevel',$proximityLevel3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3',$location3);
				
				sleep(1);
			}
			if($location4 != "") {
				$geometry = $poiController->getGeoCoding($location4);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:proxLevel',$proximityLevel4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4',$location4);
				sleep(1);
				
			}
			if($location5 != "") {
				$geometry = $poiController->getGeoCoding($location5);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:proxLevel',$proximityLevel5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5',$location5);
			}
			
			Session::flash ( 'message', 'Successfully added places of interest ' . $vehicleId . '!' );
			return Redirect::to ( 'vdmPOI' );
	
		
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
		$cpyCode = $redis->hget('H_UserId_Cust_Map', $username . ':cpyCode');
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		
		while($i<=6) {
			$proxLevel = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':proxLevel' );
			$lat = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc'. $i. ':lat' );
			$lng = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc'. $i. ':lng' );
			$address = $redis->hget($vehicleDeviceMapId,$vehicleId . ':loc' . $i);
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
		return View::make('vdm.poi.show',array('vehicleId'=>$vehicleId))->with('geoAddres', $geoAddres)->with('proximityArr',$proximityArr)->with('geoCoordinates',$geoCoordinates);
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
	//	$cpyCode = $redis->hget ( $vehicleDeviceMapId, $username . ':cpyCode' );
		$cpyCode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':cpyCode' );
	
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$rules = array (
				'vehicleId' => 'required',
				'proximityLevel1' => 'required',
				'location1' => 'required'
	
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::to ( 'vdmPOI/create' )->withErrors ( $validator );
		} else {
			// store
			

			$vehicleId = Input::get ( 'vehicleId' );
			
			$proximityLevel1 = Input::get ( 'proximityLevel1' );
			$location1 = Input::get ( 'location1' );
			$proximityLevel2 = Input::get ( 'proximityLevel2' );
			$location2 = Input::get ( 'location2' );
			$proximityLevel3 = Input::get ( 'proximityLevel3' );
			$location3 = Input::get ( 'location3' );
			$proximityLevel4 = Input::get ( 'proximityLevel4' );
			$location4 = Input::get ( 'location4' );
			$proximityLevel5 = Input::get ( 'proximityLevel5' );
			$location5 = Input::get ( 'location5' );

			
			$redis = Redis::connection ();
			
			//H_Vehicle_Device_Map  - vehicleId:proximity1 = 1
			// vehicleId:location1.lat=
			//vehicleId:locatoin1:lng= 
			$poiController = new VdmPOIController();
			if($location1 != "") {
	
				$geometry = $poiController->getGeoCoding($location1);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
					
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:proxLevel',$proximityLevel1);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc1',$location1);
				sleep(1);
			}
			if($location2 != "") {	
	
				$geometry = $poiController->getGeoCoding($location2);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:proxLevel',$proximityLevel2);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc2',$location2);
				sleep(1);
			}
			
			if($location3 != "") {
				$geometry = $poiController->getGeoCoding($location3);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:proxLevel',$proximityLevel3);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc3',$location3);
				
				sleep(1);
			}
			if($location4 != "") {
				$geometry = $poiController->getGeoCoding($location4);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:proxLevel',$proximityLevel4);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc4',$location4);
				sleep(1);
				
			}
			if($location5 != "") {
				$geometry = $poiController->getGeoCoding($location5);
				$latitude = $geometry['location']['lat'];
				$longitude = $geometry['location']['lng'];
				
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:proxLevel',$proximityLevel5);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:lat',$latitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5:lng',$longitude);
				$redis->hset($vehicleDeviceMapId,$vehicleId . ':loc5',$location5);
			}
			
			Session::flash ( 'message', 'Successfully added places of interest ' . $vehicleId . '!' );
			return Redirect::to ( 'vdmPOI' );
	
		
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
		log::info("VdmPOIController received destroy request");
		if(!Auth::check()) {
			return Redirect::to('login');
		}
		$username = Auth::user()->username;
		$redis = Redis::connection();
		$cpyCode = $redis->hget('H_UserId_Cust_Map', $username . ':cpyCode');
		$vehicleDeviceMapId = 'H_Vehicle_Device_Map_' . $cpyCode;
		$vehicleId = 	$id;
		$i=1;
		while($i<6) {

			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':proxLevel');
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':lat');
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i .':lng');
			$redis->hdel($vehicleDeviceMapId,$vehicleId . ':loc' . $i );
			$i=$i+1;
		}
		log::info("VdmPOIController received destroy request completed");
		Session::flash('message', 'Successfully deleted ' . $vehicleId .' places of interest!');
		return Redirect::to('vdmPOI');
	}


}
