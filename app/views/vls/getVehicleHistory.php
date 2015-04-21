<?php

$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');
$port = $redis->get('restservices:port');

if (! Auth::check ()) {
	return Redirect::to ( 'login' );
}

$username = Auth::user ()->username;

if (App::environment('development'))
{
    $ipaddress='localhost';
    $port ='9005';
}
else {
    $port ='9000';
}


$parameters='?userId='. $username;
foreach ($input as $key => $value) {
   
        $parameters="{$parameters}&{$key}={$value}";
   
}
log::info( ' parameters :' . $parameters);


	$url = 'http://' .$ipaddress . ':'.$port.'/getVehicleHistory' . $parameters;
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
log::info( 'curl status  :' .$httpCode  );


echo $response;
?>

