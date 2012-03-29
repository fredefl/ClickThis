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
			&& self::App_Exists($AppId) 
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
	 * @access public
	 */
	public function App_Exists($AppId = NULL){
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
			if(self::App_Exists($AppId)){
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

	/**
	 * This function get's an app id based on the request code
	 * @param string $RequestCode The request code to search for
	 * @since 1.0
	 * @access public
	 * @return integer The app id
	 */
	public function Get_App_Id($RequestCode = NULL){
		if(!is_null($RequestCode)){
			$Query = $this->db->select("AppId")->where(array("RequestCode" => $RequestCode))->limit(1)->get($this->config->item("api_request_code_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				return $Row->AppId;
			}
		}
	}

	/**
	 * This function checks if a set of consumer keys is valid and contains to the specified app id.
	 * @param string $Key    The consumer key
	 * @param string $Secret The consumer secret key
	 * @param integer $AppId  The app id
	 * @return boolean If it's valid
	 * @since 1.0
	 */
	public function Is_Valid_Consumer($Key = NULL,$Secret = NULL,$RequestCode = NULL){
		if(!is_null($Key) && !is_null($Secret)){
			if(!is_null($RequestCode)){
				$AppId = self::Get_App_Id($RequestCode);
				if(self::App_Exists($AppId)){
					$Query = $this->db->select("Id")->limit(1)->where(array("ConsumerKey" => $Key,"ConsumerSecret" => $Secret,"Id" => $AppId))->get($this->config->item("api_apps_table"));
					if($Query->num_rows > 0){
						return TRUE;
					} else {
						return FALSE;
					}
				} 
		    }else {
		    	$Query = $this->db->select("Id")->limit(1)->where(array("ConsumerKey" => $Key,"ConsumerSecret" => $Secret))->get($this->config->item("api_apps_table"));
				if($Query->num_rows > 0){
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function validates a request token, if it's still active and so on
	 * @param string $Code The request code to validate
	 * @access public
	 * @since 1.0
	 */
	public function Is_Valid_Request_Code($Code = NULL){
		if(!is_null($Code)){
			$Query = $this->db->select("StartTime")->where(array("RequestCode" => $Code))->limit(1)->get($this->config->item("api_request_code_table"));
			if($Query->num_rows() > 0){
				$Row = $Query->result();
				$Row = $Row[0];
				$TimeAlive = $Row->StartTime + $this->config->item("api_request_code_alive_time");
				if(time() > $TimeAlive){
					return FALSE;
				}	else {
					return TRUE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function inserts the newly created request tokens into the database
	 * @param string $Key         The created token
	 * @param string $Secret      The created secret token
	 * @param string $RequestCode The request code of the auth
	 * @param integer $AppId       The appid that the key correspond too
	 * @return boolean If the insertion was a success
	 * @access private
	 * @since 1.0
	 */
	private function _Insert_Request_Tokens($Key,$Secret,$RequestCode,$AppId){
		$Query = $this->db->insert($this->config->item("api_request_token_table"),array("AppId" => $AppId,"RequestKey" => $Key,"RequestSecret" => $Secret,"RequestCode" => $RequestCode));
		if(is_integer($this->db->insert_id())){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a request token with that request code is already existing
	 * @param string $RequestCode The request code
	 * @return boolean If it exists
	 * @since 1.0
	 * @access private
	 */
	private function _Is_Request_Token_With_Request_Code_Existing($RequestCode = NULL){
		if(!is_null($RequestCode)){
			$Query = $this->db->select("Id")->where("RequestCode",$RequestCode)->limit(1)->get($this->config->item("api_request_token_table"));
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
	 * This function checks the incomming data and get's the app id,
	 * and then it calls the function that inserts the data
	 * @param string $Key         The request token
	 * @param string $Secret      The request secret token
	 * @param string $RequestCode The request code of the auth
	 * @since 1.0
	 * @access public
	 * @return boolean If it was a success
	 */
	public function Request_Token($Key = NULL,$Secret = NULL,$RequestCode = NULL){
		if(!is_null($Key) && !is_null($Secret) && !is_null($RequestCode) && !self::_Is_Request_Token_With_Request_Code_Existing($RequestCode)){
			$AppId = self::Get_App_Id($RequestCode);
			if(self::App_Exists($AppId)){
				return self::_Insert_Request_Tokens($Key,$Secret,$RequestCode,$AppId);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the app id of a app with based on the consumer keys
	 * @param string $ConsumerSecret The consumer secret
	 * @param string $ConsumerKey    The consumer key
	 * @since 1.0
	 * @return integer
	 * @access private
	 */
	private function _Get_App_Of_Consumer($ConsumerSecret = NULL,$ConsumerKey = NULL){
		if(!is_null($ConsumerKey) && !is_null($ConsumerSecret)){
			$Query = $this->db->limit(1)->select("Id")->where(array("ConsumerKey" => $ConsumerKey,"ConsumerSecret" => $ConsumerSecret))->get($this->config->item("api_apps_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				return $Row->Id;
			}
		}
	}

	/**
	 * This function validates if the request tokens are valid
	 * @param string $Key            The request token
	 * @param string $Secret         The request secret token
	 * @param string $ConsumerSecret The app's consumer secret key
	 * @param string $ConsumerKey    The app's consumer key
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Validate_Request_Tokens($Key = NULL,$Secret = NULL,$ConsumerSecret = NULL,$ConsumerKey = NULL){
		if(!is_null($Key) && !is_null($Secret) && !is_null($ConsumerSecret) && !is_null($ConsumerKey)){
			$AppId = self::_Get_App_Of_Consumer($ConsumerSecret,$ConsumerKey);
			if(!is_null($AppId)){
				$Query = $this->db->limit(1)->select("Id")->where(array("AppId" => $AppId,"RequestKey" => $Key,"RequestSecret" => $Secret))->get($this->config->item("api_request_token_table"));
				if(!is_null($Query) && $Query->num_rows() > 0){
					return FALSE;
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