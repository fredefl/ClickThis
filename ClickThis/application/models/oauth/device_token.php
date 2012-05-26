<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Device_Token extends CI_Model{

	public function __construct () {
		parent::__construct();
		$this->config->load("oauth");
		$this->load->model("oauth/token");
	}

	/**
	 * This function inserts the newly created device code auth row
	 * @param  string $device_code The device code, later on to become a request code
	 * @param  string $user_code   The user code, that the user must enter
	 * @param  integer $app_id      The id of the app that requests
	 * @param  array $scope       The scopes that the app want's access too
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function device_code ($device_code, $user_code, $app_id, $scope) {
		sort($scope);
		$data = array(
			"device_code" => $device_code,
			"user_code" => $user_code,
			"app_id" => $app_id,
			"scope" => implode(",", $scope),
			"created_time" => time()
		);
		$this->db->insert($this->config->item("oauth_device_code_table"),$data);
		return TRUE;
	}

	/**
	 * Thsi function sets the authenticated to one for the device code
	 * @param  string $code The device code to accept
	 * @param integer $user_id The id of the authenticated user
	 * @since 1.0
	 * @access public
	 */
	public function accept_device_code ( $code, $user_id) {
		$data = array(
			"autehnticated" => 1,
			"user_id" => $user_id
		);
		$where = array(
			"device_code" => $code
		);
		$this->db->where($where)->update($this->config->item("oauth_device_code_table"),$data);
	}

	/**
	 * This function checks if a device code exists
	 * and requests the information on it
	 * @param  string $code         The user code to search for
	 * @param  pointer|integer &$app_id      The app id that created the user code
	 * @param  pointer|array &$scope       The scopes of the user code
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function validate($code, &$app_id, &$scope){
		$data = array(
			"user_code" => $code,
			"created_time > " => time() - $this->config->item("oauth_device_auth_time_alive")
		);
		$query = $this->db->where($data)->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			$app_id = $row->app_id;
			$scope = explode(",", $row->scope);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function returns the app id
	 * that are linked to a user code
	 * @param  string $code The user code
	 * @return integer
	 * @since 1.0
	 * @access public
	 */
	public function app_id($code){
		$data = array(
			"user_code" => $code,
			"created_time > " => time() - $this->config->item("oauth_device_auth_time_alive")
		);
		$query = $this->db->where($data)->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			return $row->app_id;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function returns the scoped
	 * that are linked to a user code
	 * @param  string $code The user code to search for
	 * @return array
	 * @since 1.0
	 * @access public
	 */
	public function scope ($code) {
		$data = array(
			"user_code" => $code,
			"created_time > " => time() - $this->config->item("oauth_device_auth_time_alive")
		);
		$query = $this->db->where($data)->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			return explode(",", $row->scope);
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a code exists
	 * @param  stirng $code The code to check for
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function exists($code){
		$data = array(
			"user_code" => $code,
			"created_time > " => time() - $this->config->item("oauth_device_auth_time_alive")
		);
		$query = $this->db->where($data)->get($this->config->item("oauth_device_code_table"));
		return ($query->num_rows() > 0);
	}

	/**
	 * This function returns the device code of a user code
	 * @param  string $code The user code to search for
	 * @return string
	 * @since 1.0
	 * @access public
	 */
	public function get_device_code($code){
		$data = array(
			"user_code" => $code
		);
		$query = $this->db->where($data)->get($this->config->item("oauth_device_code_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			return $row->device_code;
		}
	}

	/**
	 * This function authenticate the user with the app,
	 * with the correct scopes
	 * @param  string $code         The user code used to authenticate
	 * @param  integer $user_id      The user id to authenticate with
	 * @param  integer $app_id       The app to give access
	 * @param  array $scope        The access scopes
	 * @since 1.0
	 * @access public
	 */
	public function authenticate($code, $user_id, $app_id, $scope){
		if (empty($user_id) || empty($app_id)) {
			return FALSE;
		}
		sort($scope);
		$device_code = self::get_device_code($code);
		$data = array(
			"user_id" => $user_id,
			"app_id" => $app_id,
			"created_time" => time(),
			"scope" => implode(",", $scope),
			"access_type" => "offline"
		);
		$this->db->insert($this->config->item("oauth_authenticated_table"),$data);
		self::accept_device_code($device_code, $user_id);
	}

	/**
	 * This function removes the user code
	 * @param  string $code The user code to remove
	 * @since 1.0
	 * @access public
	 */
	public function remove ( $code ) {
		$this->db->where(array("user_code" => $code))->delete($this->config->item("oauth_device_code_table"));
	}

	/**
	 * This function checks if an existing request has been made
	 * @param  string $code The device code
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function last_request_exists ( $code ) {
		$data = array(
			"device_code" => $code
		);
		$query = $this->db->select("id")->where( $data )->get($this->config->item("oauth_device_code_last_request_table"));
		return ($query->num_rows() > 0);
	}

	/**
	 * This function sets the last request time to the current time
	 * @param string $code The request code used
	 * @since 1.0
	 * @access public
	 */
	public function set_request_time ( $code ) {
		if (self::last_request_exists($code)) {
			$data = array(
				"time" => time()
			);
			$where = array(
				"device_code" => $code
			);
			$this->db->where($where)->update($this->config->item("oauth_device_code_last_request_table"),$data);
		} else {
			$data = array(
				"device_code" => $code,
				"time" => time()
			);
			$this->db->insert($this->config->item("oauth_device_code_last_request_table"),$data);
		}
	}

	/**
	 * This function removes the last request item for the device code
	 * @param  string code The device code
	 * @since 1.0
	 * @access public
	 */
	public function remove_last_request ( $code ) {
		$where = array(
			"device_code" => $code
		);
		$this->db->where($where)->delete($this->config->item("oauth_device_code_last_request_table"));
	}

	/**
	 * This function returns FALSE if the client hasn't waited the requested interval since the last reqeust
	 * @param  string $code The device code to look for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function device_code_interval( $code ){
		$where = array(
			"device_code" => $code
		);
		$query = $this->db->select("time")->where($where)->get($this->config->item("oauth_device_code_last_request_table"));
		if ($query->num_rows() > 0) {
			$row = current($query->result());
			return (time() > $row->time + $this->config->item("oauth_device_code_interval"));
		} else {
			return TRUE;
		}
	}
}