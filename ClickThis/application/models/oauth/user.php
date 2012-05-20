<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class User extends CI_Model{

	/**
	 * This function is the constructor it load up some configs
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->config->load("oauth");
		$this->config->load("api");
	}

	/**
	 * This function checks if a user exist
	 * @param  integer $user_id The id of the user to search for
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function user_exists($user_id){
		$query = $this->db->where(array("id" => $user_id))->get($this->config->item("api_users_table"));
		return ($query->num_rows() > 0);
	}
}