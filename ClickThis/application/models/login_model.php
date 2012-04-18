<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Login_Model extends CI_Model{

	/**
	 * This is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Login_Model(){
		$this->load->config("api");
	}

	/**
	 * This function returns the user id of the current usser
	 * or creates a new user with the correct details
	 * @param string $Name    The name of the user
	 * @param string $Email   The email of the user
	 * @param string $Picture The user picture
	 * @param integer $Id      The user id returned from Google
	 * @param string $Locale  The locale of the user
	 * @param pointer|integer &$UserId A variable to store the user id of the current user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Google($Name = NULL,$Email = NULL,$Picture = NULL,$Id = NULL,$Locale = NULL,&$UserId = NULL){
		if(self::User_Exists($Email,"Google",$UserId)){
			$this->db->where(array("Id" => $UserId))->update($this->config->item("api_users_table"),array("Email" => $Email,"Google" => $Id));
			return TRUE;
		} else if(self::User_Exists($Id,"Google",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"Google" => $Id,
				"Email" => $Email,
				"RealName" => $Name,
				"Language" => $Locale
			);
			if(!is_null($Picture)){
				$Data["ProfileImage"] = $Picture;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function returns the user id of the current user or creates
	 * a new and return the user id
	 * @param integer $Id       The facebook id
	 * @param string $Name     The full name
	 * @param string $Email    The email of the user
	 * @param string $Country  The country of the user etc "DK"
	 * @param string $Language The language of the user etc "da"
	 * @param pointer|integer &$UserId  A variable to store the current user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Facebook($Id = NULL,$Name = NULL,$Email = NULL,$Country = NULL,$Language = NULL,&$UserId){
		if(self::User_Exists($Id,"Facebook",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"Facebook" => $Id,
				"RealName" => $Name,
				"Language" => $Language,
				"Country" => $Country
			);
			if(!is_null($Email)){
				$Data["Email"] = $Email;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function checks if a user exists
	 * @param integer|string $Id       The data to search for
	 * @param string $Provider The provider/field to search in
	 * @param pointer|integer &$UserId  If the user exists, then this will be the user id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function User_Exists($Id = NULL,$Provider = NULL,&$UserId = NULL){
		if(!is_null($Id)){
			$Query = $this->db->where(array($Provider => $Id))->limit(1)->select("Id")->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$UserId = $Row->Id;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function adds a new provider to an existing user
	 * @param string $Provider The name of the provider|field
	 * @param string|integer $Id       The provider user identifier
	 * @since 1.0
	 * @access public
	 */
	public function Update($UserId = NULL,$Provider = NULL,$Id = NULL){
		if(!is_null($UserId) && self::User_Exists($UserId,"Id")){
			$Data = array(
				$Provider => $Id
			);
			$this->db->where(array("Id" => $UserId))->update($this->config->item("api_users_table"),$Data);
		}
	}
}
