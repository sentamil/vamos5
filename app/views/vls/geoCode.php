<?php

   $geoLocation= Input::get ( 'geoLocation' );
   $geoAddress= Input::get ( 'geoAddress' );
        Log::info(' geoLocation ' . $geoLocation . ' geoAddress ' .$geoAddress);
    
$input = Input::all();

$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');


if (! Auth::check ()) {
    return Redirect::to ( 'login' );
}
$ch=curl_init();
$username = Auth::user ()->username;
$parameters='?userId='. $username;
foreach ($input as $key => $value) {
        $value=curl_escape($value);
        $parameters="{$parameters}&{$key}={$value}";
   
}
 log::info( ' parameters :' . $parameters);

    $url = 'http://' .$ipaddress .':9000/storeGeoCode' . $parameters;
    $url=htmlspecialchars_decode($url);
    log::info( 'Routing to backed  :' . $url );


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

