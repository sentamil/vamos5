<?php
Log::info("post stopSmsSubscription....");
$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');

if (! Auth::check ()) {
	return Redirect::to ( 'login' );
}

$username 		= Auth::user ()->username;
$vehiId       	= Input::get('orgId');
$mobileNo       = Input::get('mobNum');


$data = array('orgId' => $vehiId, 'mobileNo' => $mobileNo, 'userId'=>$username);

$url = 'http://' .$ipaddress . ':9000/stopSmsSubscription';
log::info( ' Routing to backed :' . $url);
$ch = curl_init();
$url=htmlspecialchars_decode($url);
curl_setopt($ch, CURLOPT_URL, $url );
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
log::info( 'curl status  :' .$httpCode  );
echo $response;
?>

