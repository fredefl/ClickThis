<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This model is used get information on OAuth tokens,
 * generate new ones or remove tokens
 * @package Authentication
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage OAuth
 * @category Model
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
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
			"scope" => implode(",", $scope)
		);

		$this->db->where(array("app_id" => $app_id,"user_id" => $user_id))->delete($this->config->item("oauth_access_token_table"));
		$this->db->insert($this->config->item("oauth_access_token_table"),$data);

		if (($grant_type == "code" || $grant_type == "authorization_code") && $access_type == "offline") {
			if (!self::has_refresh_token($app_id,$user_id,$refresh_token,$scope)) {
				$refresh_token_data = array(
					"refresh_token" => $refresh_token,
					"scope" => implode(",", $scope),
					"created_time" => time(),
					"user_id" => $user_id,
					"app_id" => $app_id
				);
				$this->db->insert($this->config->item("oauth_refresh_token_table"),$refresh_token_data);
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
			"scope" => implode(",", $scope),
			"access_type" => $access_type
		);
		$result = $this->db->where($query)->get($this->config->item("oauth_authenticated_table"));
		return ($result->num_rows() > 0);
	}

	/**
	 * This function checks if a refresh token exists
	 * @param  string  $refresh_token The refresh token to search for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function is_valid_refresh_token ( $refresh_token ) {
		$query = $this->db->where(array("refresh_token" => $refresh_token))->select("id")->get($this->config->item("oauth_refresh_token_table"));
		return ($query->num_rows() > 0);
	}

	/**
	 * This function gets information that are stored of a refresh token
	 * @param  string $refresh_token The refresh token to search for
	 * @param  integer &$app_id       A variable to store the app id that are the owner of the refresh token
	 * @param  integer &$user_id      A variable to store the user id that the refresh token gives access too
	 * @param  array &$scope        A variable to store the authenticated scopes of a refresh token
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function get_information_by_refresh_token( $refresh_token, &$app_id, &$user_id, &$scope ){
		$query = $this->db->where(array("refresh_token" => $refresh_token))->get($this->config->item("oauth_refresh_token_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			$app_id = $row->app_id;
			$user_id = $row->user_id;
			$scope = explode(",", $row->scope);
			return TRUE;
		} else {
			return FALSE;
		}
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
				"scope" => implode(",", $scope),
				"created_time" => time(),
				"access_type" => $access_type
			);
			$this->db->insert($this->config->item("oauth_authenticated_table"),$data);
		}
		return TRUE;
	}

	/**
	 * This function validates if a request code exists and is valid
	 * @param  string  $code The request code validate
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function is_valid_request_code( $code ){
		$query = $this->db->select("created_time")->where(array("code" => $code))->get($this->config->item("oauth_request_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			return ($row->created_time >= time() - $this->config->item("oauth_request_code_time_alive"));
		} else {
			return FALSE;
		}
	}


	/**
	 * This function checks if it's a valid device code and the devifce code is authenticated
	 * @param  string  $code The device code
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function is_valid_device_code( $code ){
		$query = $this->db->select("created_time,autehnticated")->where(array("device_code" => $code))->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			return ($row->created_time >= time() - $this->config->item("oauth_device_code_time_alive") && ($row->autehnticated == "1" || $row->autehnticated == 1));
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a device code exists
	 * @param  string $code The decice code to check for
	 * @return booelan
	 * @since 1.0
	 * @access public
	 */
	public function device_code_exists( $code ){
		$query = $this->db->select("id")->where(array("device_code" => $code))->get($this->config->item("oauth_device_code_table"));
		return ($query->num_rows() > 0);
	}

	/**
	 * This function checks if a access token exists and is valid
	 * @param  string  $access_token The access token to search for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function is_valid_access_token ( $access_token ) {
		$query = $this->db->where(array("access_token" => $access_token))->select("id,created_time")->get($this->config->item("oauth_access_token_table"));
		if ($query->num_rows() > 0) {
			$row = current($query->result());
			return (floor($this->config->item("oauth_access_token_time_alive") - (time() - $row->created_time)) > 0);
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a access token exists in the database
	 * @since 1.0
	 * @access public
	 * @param  string $access_token The access token to check for
	 * @return boolean
	 */
	public function access_token_exists ( $access_token ) {
		$query = $this->db->where(array("access_token" => $access_token))->select("id")->get($this->config->item("oauth_access_token_table"));
		return ($query->num_rows() > 0);
	}

	/**
	 * This function gets the scope, user_id and app_id linked to a access token
	 * @param  string $access_token The access token to search for
	 * @param  integer &$app_id      A variable to store the idof the app that owns the token
	 * @param  integer &$user_id     A variable to store the user id of the access token
	 * @param  arrray &$scope       A variable to store the affiliated scope(s)
	 * @since 1.0
	 * @see token_info
	 * @access public
	 * @return boolean
	 */
	public function get_information_by_access_token ( $access_token, &$app_id, &$user_id, &$scope ) {
		return self::token_info($access_token, $app_id, $user_id, $scope, $expires_in);
	}

	/**
	 * This function is used to get the needed information for the
	 * @param  string $access_token The access token to search for
	 * @param  integer &$app_id      A variable to store the idof the app that owns the token
	 * @param  integer &$user_id     A variable to store the user id of the access token
	 * @param  arrray &$scope       A variable to store the affiliated scope(s)
	 * @param  integer &$expires_in  The time in seconds before the token times out
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function token_info ( $access_token, &$app_id, &$user_id, &$scope, &$expires_in ) {
		$query = $this->db->where(array("access_token" => $access_token))->get($this->config->item("oauth_access_token_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			$app_id = $row->app_id;
			$user_id = $row->user_id;
			$scope = explode(",", $row->scope);
			$expires_in = floor($this->config->item("oauth_access_token_time_alive") - (time() - $row->created_time));
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function removes a request code
	 * @param  string $code The request code to remove
	 * @since 1.0
	 * @access public
	 */
	public function remove_request_code ( $code ) {
		$this->db->where(array("code" => $code))->delete($this->config->item("oauth_request_code_table"));
	}

	/**
	 * This function removes the authentication of a app for a user
	 * and all the affiliated tokens
	 * @param  string $access_token  The access token to use to get the needed information
	 * @param  string $refresh_token The refresh token to use to deauth
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function deautherize_app( $access_token = NULL, $refresh_token = NULL){
		if (!is_null($access_token)) {	
			self::get_information_by_access_token($access_token, $app_id, $user_id, $scope);
			self::remove_authentication($user_id, $app_id);
			self::delete_refresh_token($user_id, $app_id);
			self::delete_access_token($user_id, $app_id);
		} else if (!is_null($refresh_token)) {
			self::get_information_by_refresh_token($refresh_token, $app_id, $user_id, $scope);
			self::remove_authentication($app_id, $user_id);
			self::delete_access_token($user_id,$app_id);
			self::delete_refresh_token($user_id, $app_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * This function deletes the access token(s) that are linked to that app and user
	 * @since 1.0
	 * @access public
	 * @param  integer $user_id The id of the user that the token gives access too
	 * @param  integer $app_id  The app that owns the token
	 */
	public function delete_access_token ($user_id, $app_id) {
		$where = array(
			"user_id" => $user_id,
			"app_id" => $app_id
		);
		$this->db->where($where)->delete($this->config->item("oauth_access_token_table"));
	}

	/**
	 * This function removes the refresh token(s) that are linked to that app and user
	 * @param  integer $user_id The id of the user that the refresh token can generate a access token for
	 * @param  ínteger $app_id  The id of the owning app
	 * @since 1.0
	 * @access public
	 */
	public function delete_refresh_token ($user_id, $app_id) {
		$where = array(
			"user_id" => $user_id,
			"app_id" => $app_id
		);
		$this->db->where($where)->delete($this->config->item("oauth_refresh_token_table"));
	}

	/**
	 * This function removes the authentication from a app
	 * @param  integer $user_id The id of the user that is access too
	 * @param  integer $app_id  The id of the app that are authenticated to a user
	 * @since 1.0
	 * @access public
	 */
	public function remove_authentication ($user_id, $app_id) {
		$where = array(
			"user_id" => $user_id,
			"app_id" => $app_id
		);
		$this->db->where($where)->delete($this->config->item("oauth_authenticated_table"));
	}

	/**
	 * This function deletes a access token from the database
	 * @param  string $access_token The access token to delete
	 * @since 1.0
	 * @access public
	 */
	public function remove_access_token ( $access_token ) {
		$where = array(
			"access_token" => $access_token
		);
		$this->db->where($where)->delete($this->config->item("oauth_access_token_table"));
	}

	/**
	 * This function deletes a refresh token
	 * @param  string $refresh_token The refresh token to delete
	 * @since 1.0
	 * @access public
	 */
	public function remove_refresh_token ( $refresh_token ) {
		$where = array(
			"refresh_token" => $refresh_token
		);
		$this->db->where($where)->delete($this->config->item("oauth_refresh_token_table"));
	}

	/**
	 * This function removes a device code
	 * @param  string $code The device code to remove
	 * @since 1.0
	 * @access public
	 */
	public function remove_device_code ( $code ) {
		$this->db->where(array("device_code" => $code))->delete($this->config->item("oauth_device_code_table"));
	}

	/**
	 * This function retrieves information on a request code
	 * @param  string $code         The request code to seach for
	 * @param  integer &$app_id      A variable to store the registred app id of the code
	 * @param  integer &$user_id     A variable to store the user id of the user the code gives access too
	 * @param  array &$scope       A variable to store the access code of the request
	 * @param  string &$access_type The access type to create the token with offline or online
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function get_information_by_request_code ( $code, &$app_id, &$user_id, &$scope, &$access_type ) {
		$query = $this->db->where(array("code" => $code))->get($this->config->item("oauth_request_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			if ($row->created_time >= time() - $this->config->item("oauth_request_code_time_alive")) {
				$app_id = $row->app_id;
				$user_id = $row->user_id;
				$scope = explode(",", $row->scope);
				$access_type = $row->access_type;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the information used to create the access token
	 * from a device code
	 * @param  string $code     The device code to search for
	 * @param  integer &$app_id  The app id of the device code
	 * @param  integer &$user_id The user id of th device code
	 * @param  array &$scope   The authenticated scopes
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function get_information_by_device_code ( $code, &$app_id, &$user_id, &$scope ) {
		$query = $this->db->where(array("device_code" => $code))->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			if ($row->created_time >= time() - $this->config->item("oauth_device_code_time_alive")) {
				$app_id = $row->app_id;
				$user_id = $row->user_id;
				$scope = explode(",", $row->scope);
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if that app and that user already have an assosiated refresh token
	 * @param  string  $app_id         The app id
	 * @param  string  $user_id        The user id
	 * @param  pointer  &$refresh_token An variable to store a possible refresh token
	 * @param array $scope The registred scopes of the user
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function has_refresh_token ($app_id, $user_id, &$refresh_token , $scope = NULL) {
		sort($scope);
		$query = $this->db->select("refresh_token")->where(array("app_id" => $app_id,"user_id" => $user_id, "scope" => implode(";", $scope)))->get($this->config->item("oauth_refresh_token_table"));
		if ($query->num_rows() > 0) {
			$refresh_token = current($query->result())->refresh_token;
			return TRUE;
		} else {
			return FALSE;
		}
	}
}