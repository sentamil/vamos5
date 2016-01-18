<?php
Log::info("Get Vehicle Locations....");

$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');

$username='';
if(isset(Auth::user ()->username)){
	$username = Auth::user ()->username;
}

$parameters='?userId='. $username;
foreach ($input as $key => $value) {
   
        $parameters="{$parameters}&{$key}={$value}";
   
}
$web="web";
 $val="y";
 $parameters="{$parameters}&{$web}={$val}";
 log::info( ' parameters :' . $parameters);

		 $url = 'http://' .$ipaddress .':9000/getSelectedVehicleLocation' . $parameters;
		$url=htmlspecialchars_decode($url);
		 log::info( 'Routing to backed  :' . $url );


    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
    // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
	 $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
     if(curl_errno($ch)){
        log::info( 'Curl error: ' . curl_error($ch));
      }
  curl_close($ch);
log::info( 'curl status  :' .$httpCode  );

echo $response;
?>


