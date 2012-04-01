<?php
	$config["api_request_code_table"] = "RequestCodes";
	$config["api_apps_table"] = "Apps";
	$config["api_users_table"] = "Users";
	$config["api_request_token_table"] = "RequestTokens";
	$config["api_access_token_table"] = "AccessTokens";
	$config["api_simple_token_table"] = "Tokens";

	//In seconds
	$config["api_request_code_alive_time"] = 3600;
	$config["api_request_token_alive_time"] = 3600;

	//Time to live for access tokens, Level => Time in seconds
	$config["api_access_tokens_time_to live"] = array(
		1 => 3600,
		2 => 0,
		3 => 86400, 
		4 => 86400,
		5 => 432000,
		6 => 432000,
		7 => 432000,
		8 => 432000,
		9 => 432000,
		10 => 3600
	);

	//The largets token that has secret access(Password access)
	$config["api_secret_access_token_max"] = 1;

	//The largets token that has write access
	$config["api_write_access_token_max"] = 5;

	//The largets token level that has read access
	$config["api_read_access_token_max"] = 10;

	//The largets token that has delete access
	$config["api_delete_access_token_max"] = 3;

	//The not allowed paramter in a search request
	$config["api_search_not_allowed"] = array("redirect","consumer_key","consumer_secret","access_token","access_secret","token","request_code","request_token","request_secret");
