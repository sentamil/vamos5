<?php

$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');

if (! Auth::check ()) {
	return Redirect::to ( 'login' );
}

$username = Auth::user ()->username;

//TODO - this hardcoding should be removed
//$username='demouser1';

$parameters='?userId='. $username;
foreach ($input as $key => $value) {
   
        $parameters="{$parameters}&{$key}={$value}";
}
$web="web";
 $val="y";
 $parameters="{$parameters}&{$web}={$val}";
log::info( ' parameters :' . $parameters);


  $url = 'http://' .$ipaddress . ':9000/getFuelReportDaily' . $parameters;
	
	$url=htmlspecialchars_decode($url);
 
	log::info( ' url :' . $url);
	
    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
    // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
	 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch);
log::info( 'curl status  :' .$httpCode  );


echo $response;
?>

