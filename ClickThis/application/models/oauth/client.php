<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Client extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->config->load("oauth");
	}

	/**
	 * This function validates a client pair or just the client id or client secret
	 * @param  string $client_id     The client id to validate
	 * @param  string $client_secret The client secret to validate
	 * @param integer &$app_id A variable to store the app_id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function validate ($client_id = NULL,$client_secret = NULL,&$app_id) {
		if (!is_null($client_id)) {
			$query = array(
				"client_id" => $client_id
			);
			if (!is_null($client_secret)) {
				$query["client_secret"] = $client_secret;
			}
			$result = $this->db->select('id')->where($query)->get($this->config->item("oauth_apps_table"));
			if ($result->num_rows() > 0) {
				$app_id = current($result->result())->id;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function  returns the authentication endpoint,
	 * specified by the app owner
	 * @param  integer $app_id The id of the app to search for
	 * @return string
	 * @since 1.0
	 * @access public
	 */
	public function auth_endpoint($app_id = NULL){
		if(!is_null($app_id)){
			$query = $this->db->select("auth_endpoint")->where(array("id" => $app_id))->get($this->config->item("oauth_apps_table"));
			if($query->num_rows() > 0){
				return current($query->result())->auth_endpoint;
			}
		}
	}
}