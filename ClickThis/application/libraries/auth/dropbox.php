<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Dropbox{

	private $_endpoints = array(
		"web_endpoint" => "https://www.dropbox.com/1/",
		"request_token" => "https://api.dropbox.com/1/oauth/request_token",
		"access_token" => "https://api.dropbox.com/1/oauth/access_token",
		"auth" => "https://api.dropbox.com/1/oauth/authorize",
		"auth_alternative" => "https://www.dropbox.com/1/oauth/authorize",
		"api_url" => "https://api.dropbox.com/1/",
		"content_url" => "https://api-content.dropbox.com/1/"
	);
}
?>