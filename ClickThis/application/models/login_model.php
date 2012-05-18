<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This model is used to get the user id of third party provider users linked
 * with ClickThis users and create third party linked users
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Third Party
 * @category Model
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
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
				"google" => $Id,
				"email" => $Email,
				"real_name" => $Name,
				"language" => $Locale,
				"status" => 1
			);
			if(!is_null($Picture)){
				$Data["profile_image"] = $Picture;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function either returns the user id of the
	 * linked account with that linkedin identifier
	 * or create a new account based on the LinkedIn info
	 * @param string $Name    The name of the user returned from linked in
	 * @param integer $Id      The linkedin Unique identyfier
	 * @param string $Picture An optinal url to the linkedin profile image
	 * @param stirng $Country The name of the country the user is based in
	 * @param pointer|integer &$UserId A variable to store the user ud
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function LinkedIn($Name = NULL,$Id = NULL,$Picture = NULL,$Country = NULL,&$UserId = NULL){
		if(self::User_Exists((string)$Id,"LinkedIn",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"linkedin" => (string)$Id,
				"real_name" => $Name,
				"status" => 1
			);
			if(!is_null($Country)){
				$Data["country"] = $Country;
			}
			if(!is_null($Picture)){
				$Data["profile_image"] = (string)$Picture;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function either returns gets the id of a linked accoutn
	 * or create a new account based on Foursquare data
	 * @param string $Name    The name of the user
	 * @param integer $Id      The foursquare identifier
	 * @param string $Picture The users profile picture if set
	 * @param string $Email   The users email taken fromt foursquare
	 * @param pointer|integer &$UserId A variable to store the returned user id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Foursquare($Name = NULL,$Id = NULL,$Picture = NULL,$Email = NULL,&$UserId = NULL){
		if(self::User_Exists((string)$Id,"Foursquare",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"foursquare" => (string)$Id,
				"real_name" => $Name,
				"status" => 1
			);
			if(!is_null($Email)){
				$Data["email"] = $Email;
			}
			if(!is_null($Picture)){
				$Data["profile_image"] = (string)$Picture;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function either returns the id of the linked instagram user
	 * or creates a new user based on the input
	 * @param string $Name    The name of the user taken from Instagram
	 * @param integer $Id      The Instagram user id
	 * @param string $Picture A url string to the profile image of the user
	 * @param pointer|integer &$UserId A variable to store the user id of the new or linked user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Instagram($Name = NULL,$Id = NULL,$Picture = NULL,&$UserId = NULL){
		if(self::User_Exists((string)$Id,"Instagram",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"instagram" => (string)$Id,
				"real_name" => $Name,
				"status" => 1
			);
			if(!is_null($Picture)){
				$Data["profile_image"] = (string)$Picture;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function inserts or returns the user id of the newly created or current user
	 * @param string $Name    The name of the user
	 * @param integer $Id      The Github user id
	 * @param string $Picture The gravar url, for the users profile image
	 * @param pointer|integer &$UserId A variable to store the user id of the user
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function Github($Name = NULL,$Id = NULL,$Email = NULL,$Picture = NULL,&$UserId = NULL){
		if(self::User_Exists($Id,"Github",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"github" => $Id,
				"email" => $Email,
				"real_name" => $Name,
				"status" => 1
			);
			if(!is_null($Picture)){
				$Data["profile_image"] = $Picture;
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
				"facebook" => $Id,
				"real_name" => $Name,
				"language" => $Language,
				"country" => $Country,
				"status" => 1
			);
			if(!is_null($Email)){
				$Data["email"] = $Email;
			}
			$this->db->insert($this->config->item("api_users_table"),$Data);
			$UserId = $this->db->insert_id();
			return TRUE;
		}
	}

	/**
	 * This function creates a user based on Twitter or gets the id of the linked user
	 * @param string $Name     The name of the user
	 * @param interger $Id       The Twitter id of the user
	 * @param string $Picture  An optional url to the profile image
	 * @param string $Language The language code of the user
	 * @param string $Country  An optional country name for the user
	 * @param pointer|integer &$UserId  The id of the created user or linked user
	 * @since 1.0
	 * @access public
	 */
	public function Twitter($Name = NULL,$Id = NULL,$Picture = NULL,$Language = NULL,$Country = NULL,&$UserId = NULL){
		if(self::User_Exists($Id,"Twitter",$UserId)){
			return TRUE;
		} else {
			$Data = array(
				"twitter" => $Id,
				"real_name" => $Name,
				"language" => $Language,
				"status" => 1
			);
			if(!is_null($Country)){
				$Data["Country"] = $Country;
			}
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
			$Query = $this->db->where(array(strtolower($Provider) => $Id))->limit(1)->select("id")->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$UserId = $Row->id;
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
			$this->db->where(array("id" => $UserId))->update($this->config->item("api_users_table"),$Data);
		}
	}
}
