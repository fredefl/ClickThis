<?php
// Set set the URL
$url = 'http://api.beaconpush.com/1.0.0/ed02c2f4/channels/mychannel';

// Open the connection
$ch = curl_init();

// Set the url, the number of POST variables and the POST data.
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Beacon-Secret-Key: e3ebeaad362d0d4887c449dfe232fc29c5d614cb'));
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,'{"update":""}');

// Execute POST request.
$result = curl_exec($ch);
// Echo the result.
echo $result;

// Close the connection.
curl_close($ch);
?>