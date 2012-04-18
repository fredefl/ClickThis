<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Login extends CI_Model{

	/**
	 * This is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Login(){
		$this->load->config("api");
	}

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
				"RealName" => $Name
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
				$Row = current($Query);
				$UserId = $Row->Id;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
