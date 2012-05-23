<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Device_Token extends CI_Model{

	public function __construct () {
		parent::__construct();
		$this->config->load("oauth");
	}

	/**
	 * This function inserts the newly created device code auth row
	 * @param  string $device_code The device code, later on to become a request code
	 * @param  string $user_code   The user code, that the user must enter
	 * @param  integer $app_id      The id of the app that requests
	 * @param  array $scope       [description]
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function device_code ($device_code, $user_code, $app_id, $scope) {
		$data = array(
			"device_code" => $device_code,
			"user_code" => $user_code,
			"app_id" => $app_id,
			"scope" =>implode(";", $scope),
			"created_time" => time()
		);
		$this->db->insert($this->config->item("oauth_device_code_table"),$data);
		return TRUE;
	}
}