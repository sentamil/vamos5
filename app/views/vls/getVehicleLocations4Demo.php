<?php

$input = Input::all();

$username = 'demouser1';

$parameters="";
foreach ($input as $key => $value) {
    if(strlen($parameters)==0) {
	$parameters= "?{$key}={$value}";
    }
    else {
        $parameters="{$parameters}&{$key}={$value}";
    }

}
 log::info( ' parameters :' . $parameters);

if(strlen($parameters)==0) {
   $url = "http://128.199.175.189:20141/getVehicleLocations";
}
else {
		$parameters=$parameters . '&userId='. $username;

 log::info( ' parameters :' . $parameters);

		$url = "http://128.199.175.189:20141/getVehicleLocations" . $parameters;
		$url=htmlspecialchars_decode($url);
		 log::info( 'Routing to backed  :' . $url );

}
//$response = file_get_contents($url);


    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);

    // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
	 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch);
log::info( 'curl status  :' .$httpCode  );

echo $response;
?>

