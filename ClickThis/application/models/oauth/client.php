<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This model is used get information of registred applications in ClickThis
 * @package Authentication
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage OAuth
 * @category Model
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Client extends CI_Model{

	/**
	 * This function is the contructor it loads up some configs
	 * @since 1.0
	 * @access public
	 */
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

	/**
	 * This function gets the app client id based on the app_id
	 * @param  integer $app_id     The app id to search for
	 * @param  string &$client_id A variable to store the client id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function get_client_id ( $app_id, &$client_id ) {
		$query = $this->db->select("client_id")->where(array("id" => $app_id))->get($this->config->item("oauth_apps_table"));
		if($query->num_rows() > 0){
			$row = current($query->result());
			$client_id = $row->client_id;
			return TRUE;
		} else {
			return FALSE;
		}
	}
}