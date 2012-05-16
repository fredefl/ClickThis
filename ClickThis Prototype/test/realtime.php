<?php
header("access-control-allow-origin: *");
header("allow:POST, GET, PUT, DELETE, HEAD, PATCH, OPTIONS");
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            "http://127.0.0.1:82" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "update" ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);
?>