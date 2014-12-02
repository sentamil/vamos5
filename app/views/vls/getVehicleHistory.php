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
   $url = "http://128.199.175.189:20141/getVehicleHistory";
  # $response = file_get_contents($url);

    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);

    // Include header in result? (0 = yes, 1 = no)
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch);
  curl_close($ch);

}
else {
	$url = "http://128.199.175.189:20141/getVehicleHistory" . $parameters;

  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
 
    // Include header in result? (0 = yes, 1 = no)
   # curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  #$verbose = fopen('php://temp', 'rw+');
  #curl_setopt($ch, CURLOPT_STDERR, $verbose);
  #curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  $response = curl_exec($ch); 
#  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
  curl_close($ch); 


  #if ($response === FALSE) {
  #  printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),
  #         htmlspecialchars(curl_error($ch)));
  #}

  # rewind($verbose);
  # $verboseLog = stream_get_contents($verbose);

  # echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";


}

echo $response;
?>

