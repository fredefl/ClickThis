<?php
$Code = $_GET['code'];
$Client_Id = '6e16dd5bae9e4f41a71f';
$Client_Secret = '20e58913980420e07ed2a5a3239640b1118347ab';

//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,"https://github.com/login/oauth/access_token?client_id=$Client_Id&client_secret=$Client_Secret&code=$Code");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_POSTFIELDS,"");
//curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: token 3f7ed211500b2b50749d34cf2a9fc32b'));

//execute post
$result = curl_exec($ch);
echo $result;
?>