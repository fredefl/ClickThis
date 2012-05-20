<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Token extends CI_Model{

	/**
	 * This function is the constructor it load up some configs
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->config->load("oauth");
	}

	/**
	 * This function inserts the new access token and either inserts the
	 * new refresh token or get the old one
	 * @param  string $access_token   The access token to insert
	 * @param  integer $app_id         The id of the app that owns the access token
	 * @param  integer $user_id        The id of the user the token gives access too
	 * @param  string $grant_type     The grant type "code" or "token"
	 * @param  array $scope          The scopes that the token has access too
	 * @param  string &$refresh_token A refresh token
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function create_token ($access_token, $app_id, $user_id, $grant_type, $scope ,$access_type = "online", &$refresh_token) {
		if (empty($user_id) || empty($app_id)) {
			return FALSE;
		}
		sort($scope);
		$data = array(
			"access_token" => $access_token,
			"created_time" => time(),
			"app_id" => $app_id,
			"user_id" => $user_id,
			"grant_type" => $grant_type,
			"scope" => implode(";", $scope)
		);

		$this->db->where(array("app_id" => $app_id,"user_id" => $user_id,"created_time <" => time() - $this->config->item("oauth_access_token_time_alive")))->delete($this->config->item("oauth_access_token_table"));
		$this->db->insert($this->config->item("oauth_access_token_table"),$data);

		if ($grant_type == "code" && $access_type == "online") {
			if (!self::has_refresh_token($app_id,$user_id,$refresh_token)) {
				$refresh_token_data = array(
					"refresh_token" => $refresh_token,
					"scope" => implode(";", $scope),
					"created_time" => time(),
					"user_id" => $user_id,
					"app_id" => $app_id
				);
				$this->db->insert($this->config->item("oauth_refresh_token_table"),$data);
			}
		}
		return TRUE;
	}

	/**
	 * This function checks if a user has authenticated the app before
	 * with that scopes.
	 * @param  integer  $app_id  The app that requests
	 * @param  integer  $user_id The user that is requested access too
	 * @param  array  $scope   The access scopes that is requested
	 * @param string $access_type If the token has offline or online access
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function is_authenticated ($app_id, $user_id, $scope, $access_type) {
		sort($scope);
		$query = array(
			"app_id" => $app_id,
			"user_id" => $user_id,
			"scope" => implode(";", $scope),
			"access_type" => $access_type
		);
		$result = $this->db->where($query)->get($this->config->item("oauth_authenticated_table"));
		return ($result->num_rows() > 0);
	}

	/**
	 * This function inserts the generated request code
	 * @param  string $code        The request code to insert
	 * @param  integer $app_id      The id of the requesting app
	 * @param  integer $user_id     The id of the user that has authenticated
	 * @param  array $scope       The scope that the request code  can give access too
	 * @param  string $access_type The access type "online" or "offline" of the access token
	 * this code should be used to generate
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function request_code ($code, $app_id, $user_id, $scope, $access_type) {
		sort($scope);
		if(empty($user_id) || empty($app_id)){
			return FALSE;
		}
		$this->db->where(array("app_id" => $app_id,"user_id" => $user_id,"created_time <" => time() - $this->config->item("oauth_request_code_time_alive")))->delete($this->config->item("oauth_request_code_table"));
		$data = array(
			"code" => $code,
			"user_id" => $user_id,
			"app_id" => $app_id,
			"created_time" => time(),
			"scope" => implode(",", $scope),
			"access_type" => $access_type
		);
		$this->db->insert($this->config->item("oauth_request_code_table"),$data);
		return TRUE;
	}

	/**
	 * This function authenticate an app with a user
	 * @param  integer $app_id  The app to auth
	 * @param  integer $user_id The user to authe too
	 * @param  array $scope   An array of the access scopes
	 * @param string $access_type If the token has offline or online access
	 * @since 1.0
	 * @access public
	 */
	public function authenticate ($app_id, $user_id ,$scope, $access_type) {
		if (empty($user_id) || empty($app_id)) {
			return FALSE;
		}
		sort($scope);
		if (!self::is_authenticated($app_id, $user_id, $scope, $access_type)) {
			$this->db->where(array("app_id" => $app_id,"user_id" => $user_id))->delete($this->config->item("oauth_authenticated_table"));
			$data = array(
				"app_id" => $app_id,
				"user_id" => $user_id,
				"scope" => implode(";", $scope),
				"created_time" => time(),
				"access_type" => $access_type
			);
			$this->db->insert($this->config->item("oauth_authenticated_table"),$data);
		}
		return TRUE;
	}

	/**
	 * This function checks if that app and that user already have an assosiated refresh token
	 * @param  string  $app_id         The app id
	 * @param  string  $user_id        The user id
	 * @param  pointer  &$refresh_token An variable to store a possible refresh token
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function has_refresh_token ($app_id, $user_id, &$refresh_token) {
		$query = $this->db->select("refresh_token")->where(array("app_id" => $app_id,"user_id" => $user_id))->get($this->config->item("oauth_refresh_token_table"));
		if ($query->num_rows() > 0) {
			$refresh_token = current($query->result())->refresh_token;
			return TRUE;
		} else {
			return FALSE;
		}
	}
}