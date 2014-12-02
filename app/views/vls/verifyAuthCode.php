<?php

$input = Input::all();

$parameters="";
foreach ($input as $key => $value) {
    if(strlen($parameters)==0) {
	$parameters= "?{$key}={$value}";
    }
    else {
        $parameters="{$parameters}&{$key}={$value}";
    }

}
   # check with the session or we should take the user Id from the session
if(strlen($parameters)==0) {
   $url = "http://128.199.175.189:20141/verifyAuthSMS";
}
else {
	$url = "http://128.199.175.189:20141/verifyAuthSMS" . $parameters;
}
$response = file_get_contents($url);
echo $response;
?>

