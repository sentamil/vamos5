<?php

class ReportsController extends BaseController {
	
	
	public function liveReport()
	{
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		$username = Auth::user ()->username;
		$redis = Redis::connection ();	
		$url = "http://128.199.175.189:20141/getVehicleLocations?userId=demouser1&group=personal";
		$url=htmlspecialchars_decode($url);
		//$ch = curl_init();
	//	curl_setopt($ch, CURLOPT_URL, $url);
	//	$response = curl_exec($ch);
	//	echo $response;
		//curl_close($ch);
		$response= file_get_contents($url);
		$locDetails = json_decode($response);
		
		$vehicleLocations = $locDetails[0]->vehicleLocations;
	//	var_dump($vehicleLocations);
		$lat = $vehicleLocations[0]->latitude;
		$lng = $vehicleLocations[0]->longitude;

		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
		if(is_array($jsondata) && $jsondata['status'] == "OK")
		{
			$city = $jsondata['results']['0']['address_components']['2']['long_name'];
			$country = $jsondata['results']['0']['address_components']['5']['long_name'];
			$street = $jsondata['results']['0']['address_components']['1']['long_name'];
		}
		$address = $street . ','.$city . ',' . $country;
		
		
		return View::make('reports.live')->with('vehicleLocations',$vehicleLocations)->with('address',$address);
		
	}
}