<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 
 */
class Api_Login extends CI_Model{

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Api_Login(){
		$this->config->load("api");
	}

	/**
	 * This function gets a random keypair from the database
	 * @since 1.0
	 * @access public
	 * @return array
	 */
	public function Keypair(){
		$Query = $this->db->limit(1)->order_by("","random")->get($this->config->item("api_rsa_key_table"));//query("SELECT * FROM  ORDER BY RAND() LIMIT 1");
		return current($Query->result_array());
	}

	/**
	 * This function checks if a user, with the specified username exists.
	 * If it does then the pointer $Result is set to that row
	 * @param string $Username The username to search for
	 * @since 1.0
	 * @access public
	 * @param pointer|array &$Result  The variable where to store the result row
	 * @return boolean
	 */
	public function Username($Username = NULL,&$Result = NULL){
		if(!is_null($Username)){
			$Query = $this->db->select("Id,Password,Username,Status")->limit(1)->where(array("Username" => $Username))->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Result = current($Query->result_array());
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}