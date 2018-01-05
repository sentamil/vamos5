<?php
Log::info("Save Address Url...");

$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');

$username = Auth::user ()->username;

$parameters='?';

//log::info($input);
  
  foreach ($input as $key => $value) {
    if($key=='address') {
      $valss=urlencode($value);
      $parameters="{$parameters}&{$key}={$valss}";
    } else {
      $parameters="{$parameters}&{$key}={$value}";
    }
  }

 $web="web";
 $val="y";
 $parameters="{$parameters}&{$web}={$val}";
 log::info( ' parameters :' . $parameters);

	$url = 'http://'.$ipaddress.':9000/saveAddressFromFrontend'.$parameters;
		//$url = htmlspecialchars_decode($url);
    //$url = urlencode($url);

	log::info( 'Routing to backed :' . $url );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
//Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch);
  log::info( 'curl status  :' .$httpCode  );

echo $response;
?>

