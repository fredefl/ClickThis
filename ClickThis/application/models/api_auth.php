<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Api_Auth extends CI_Model{

	/**
	 * This is the constructor id loads the config file
	 * @since 1.0
	 * @access public
	 */
	public function Api_Auth(){
		$this->load->config("api");
	}

	/**
	 * This function check the data for errors and call the _Auth function
	 * @param string $RequestCode The generated request code
	 * @param integer $AppId       The database id of the app to auth
	 * @param integer $UserId      The database id of the user to auth access too
	 * @see _Auth
	 * @since 1.0
	 * @access public
	 */
	public function Auth($RequestCode = NULL,$AppId = NULL,$UserId = NULL){
		if(!is_null($RequestCode) 
			&& !is_null($AppId) 
			&& !is_null($UserId) 
			&& self::_App_Exists($AppId) 
			&& self::_User_Exists($UserId)
		){
			return self::_Auth($RequestCode,$AppId,$UserId);
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if an app exists in the database
	 * @param integer $AppId The database id of the id
	 * @return boolean If it exists or not
	 * @since 1.0
	 * @access private
	 */
	private function _App_Exists($AppId = NULL){
		if(!is_null($AppId)){
			$Query = $this->db->select("Id")->where(array("Id" => $AppId))->get($this->config->item("api_apps_table"));
			if($Query->num_rows() > 0){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a user exists
	 * @since 1.0
	 * @access private
	 * @param integer $UserId The database id of the user
	 * @return boolean If it exists or not
	 */
	private function _User_Exists($UserId = NULL){
		if(!is_null($UserId)){
			$Query = $this->db->select("Id")->where(array("Id" => $UserId))->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a user exists
	 * @param integer $UserId The database id of the user
	 * @since 1.0
	 * @access public
	 * @return boolean If the user exists
	 */
	public function User_Exists($UserId = NULL){
		if(!is_null($UserId)){
			return self::_User_Exists($UserId);
		} else {
			return FALSE;
		}
	}
	
	/**
	 * This function inserts the newly created RequestCode to the database
	 * @param string $RequestCode The RequestCode
	 * @param integer $AppId       The database id of the application
	 * @param integer $UserId      The id of the user to auth
	 * @return boolean if success
	 * @since 1.0
	 * @access private
	 */
	private function _Auth($RequestCode = NULL,$AppId = NULL,$UserId = NULL){
		if(!is_null($RequestCode) && !is_null($AppId) && !is_null($UserId)){
			$this->db->insert($this->config->item("api_request_code_table"),
				array(
					"StartTime" => time(),
					"RequestCode" => $RequestCode,
					"AppId" => $AppId,
					"UserId" => $UserId
			));	
			if($this->db->insert_id() !== false && $this->db->insert_id() != 0){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the AuthenticationEndpoint url of a Application
	 * @param integer $AppId The database id of the app
	 * @return string The AuthenticationEndpoint url
	 * @since 1.0
	 * @access public
	 */
	public function AuthenticationEndpoint($AppId = NULL){
		if(!is_null($AppId)){
			if(self::_App_Exists($AppId)){
				$Query = $this->db->select("AuthenticationEndpoint")->limit(1)->where(array("Id" => $AppId))->get($this->config->item("api_apps_table"));
				if($Query->num_rows() > 0){
					$Data = $Query->result();
					return $Data[0]->AuthenticationEndpoint;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}