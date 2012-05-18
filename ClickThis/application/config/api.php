<?php
/**
 * @package API
 * @subpackage API Config
 * @category API
 * @version 1.0
 */
	
	/**
	 * The topt secret key
	 * @since 1.0
	 */
	$config["api_topt_key"] = "12345678901234567890";

	/**
	 * The hash algorith to use for th topt system
	 * @since 1.0
	 */
	$config["api_topt_algorithm"] = "sha1";

	/**
	 * The  number of digits in the topt key
	 * @since 1.0
	 */
	$config["api_topt_digist"] = "6";

	/**
	 * The time in seconds the topt key should live
	 * @since 1.0
	 */
	$config["api_topt_timealive"] = 30;
	
	/**
	 * The table for the rsa 1024 keys
	 * @since 1.0
	 */
	$config["api_rsa_key_table"] = "rsa1024";

	/**
	 * Password hmac password
	 */
	$config["api_hash_hmac"] = "fqqC7bsU5zt5cGHzvtGN";

	/**
	 * The length of the rsa keys
	 */
	$config["api_rsa_key_length"] = 1024;

	/**
	 * The database table for the request codes
	 * @since 1.0
	 */
	$config["api_request_code_table"] = "request_codes";

	/**
	 * The database tables for the apps
	 * @since 1.0
	 */
	$config["api_apps_table"] = "apps";

	/**
	 * The host of the api
	 * @since 1.0
	 */
	$config["api_host_url"] = "http://illution.dk/ClickThis/api";

	/**
	 * The support email for the api
	 * @since 1.0
	 */
	$config["api_email"] = "support@illution.dk";

	/**
	 * The database table for the users
	 * @since 1.0
	 */
	$config["api_users_table"] = "users";

	/**
	 * The name of the table to store the reset password tokens
	 * @since 1.0
	 */
	$config["api_resetpassword_table"] = "reset_password_tokens";

	/**
	 * The database table for the request tokens
	 * @since 1.0
	 */
	$config["api_request_token_table"] = "request_tokens";

	/**
	 * The database table for the access tokens
	 * @since 1.0
	 */
	$config["api_access_token_table"] = "access_tokens";

	/**
	 * The database table for the simple tokens
	 * @since 1.0
	 */
	$config["api_simple_token_table"] = "tokens";

	/**
	 * The time to live for the request tokens in seconds
	 * @since 1.0
	 */
	$config["api_request_code_alive_time"] = 3600;

	/**
	 * The time to live for the request tokens in seconds
	 * @since 1.0
	 */
	$config["api_request_token_alive_time"] = 3600;

	/**
	 * Time to live for access tokens, Level => Time in seconds
	 * @since 1.0
	 */
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

	/**
	 * The table for the activation tokens
	 * @since 1.0
	 */
	$config["api_activation_token_table"] = "activation_tokens";

	/**
	 * The largets token that has secret access(Password access)
	 * @since 1.0
	 */
	$config["api_secret_access_token_max"] = 1;

	/**
	 * The largets token that has write access
	 * @since 1.0
	 */
	$config["api_write_access_token_max"] = 5;

	/**
	 * The largets token level that has read access
	 * @since 1.0
	 */
	$config["api_read_access_token_max"] = 10;

	/**
	 * The largets token that has delete access
	 * @since 1.0
	 */
	$config["api_delete_access_token_max"] = 3;

	/**
	 * The not allowed paramter in a search request
	 * @since 1.0
	 */
	$config["api_search_not_allowed"] = array("format","redirect","consumer_key","consumer_secret","access_token","access_secret","token","request_code","request_token","request_secret");
