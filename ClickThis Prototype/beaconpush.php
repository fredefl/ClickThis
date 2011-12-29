<?php
//set POST variables
$url = 'http://api.beaconpush.com/1.0.0/ed02c2f4/channels/mychannel';

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Beacon-Secret-Key: e3ebeaad362d0d4887c449dfe232fc29c5d614cb'));
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,'{"update":""}');

//execute post
$result = curl_exec($ch);
echo $result;

//close connection
curl_close($ch);
?>