<?php
	/**
	 * The OAuth apps table name
	 * @since 1.0
	 */
	$config["oauth_apps_table"] = "apps";

	/**
	 * The name of the table storing the OAuth refresh token
	 * @since 1.0
	 */
	$config["oauth_refresh_token_table"] = "refresh_tokens";

	/**
	 * The name of the OAuth table storing the request codes
	 * @since 1.0
	 */
	$config["oauth_request_code_table"] = "request_codes";

	/**
	 * The name of the table storing the OAuth access tokens
	 * @since 1.0
	 */
	$config["oauth_access_token_table"] = "access_tokens";

	/**
	 * The time in seconds the access token is alive
	 * @since 1.0
	 */
	$config["oauth_access_token_time_alive"] = 3600;

	/**
	 * The number of seconds the request code stays alive
	 */
	$config["oauth_request_code_time_alive"] = 300;

	/**
	 * The name of the table to store that an app has been authenticated by the user
	 * @since 1.0
	 */
	$config["oauth_authenticated_table"] = "authenticated_apps";

	/**
	 * The session key storing the current user id
	 * @since 1.0
	 */
	$config["oauth_user_id_session_key"] = "UserId";

	/**
	 * The available authentication scopes
	 * @since 1.0
	 */
	$config["oauth_auth_scopes"] = array(
		"user_series_read",
		"user_groups_read",
		"user_profile_read",
		"user_answers_read",
		"user_apps_read",
		"groups_series_read",
		"groups_profile_read",
		"groups_answer_read",
		"groups_members_read",
		"user_series_write",
		"user_group_write",
		"user_profile_write",
		"user_app_write",
		"user_answer_write",
		"groups_series_write",
		"groups_profile_write",
		"groups_members_write"

		//"user_sensitive",
		//"user_sensitive_write"
	);

	/**
	 * This array contains strings that explains the user what the different scopes gives access too
	 * @since 1.0
	 */
	$config["oauth_scope_description"] = array(
		"user_series_read" => "This app will have the ability to read the series that you can see",
		"user_groups_read" => "This app will have the ability to read infomation about the groups you are member of"
	);
?>