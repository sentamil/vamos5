<?php
Log::info("post addMobileNumberSubscription....");
$input = Input::all();
$redis = Redis::connection ();
$ipaddress = $redis->get('ipaddress');

if (! Auth::check ()) {
	return Redirect::to ( 'login' );
}

$username = Auth::user ()->username;
$vehiId       = Input::get('orgId');
$stuDetails   = Input::get('studentDetails');
$data = array('orgId' => $vehiId, 'studentDetails'=>$stuDetails, 'userId'=>$username);
$url = 'http://' .$ipaddress . ':9000/addMobileNumberSubscription';
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

