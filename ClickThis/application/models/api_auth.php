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
	 * This function makes the last error checks and inserts the created,
	 * ClickThis token to the database
	 * @param string  $Token  The created token
	 * @param integer  $UserId The user to create the token for
	 * @param integer $Level  The level of the token
	 * @return boolean
	 * @access public
	 * @since 1.0
	 */
	public function ClickThis_Token($Token = NULL,$UserId = NULL,$Level = 2){
		if(!is_null($Token) && !is_null($UserId) && self::_User_Exists($UserId)){
			$StartTime = time();
			$TimeToLive = $this->config->item("api_access_tokens_time_to live");
			if(is_array($TimeToLive) && array_key_exists($Level, $TimeToLive)){
				$EndTime = $TimeToLive[$Level];
			} else {
				$Level = 10;
				$EndTime = 3600;
			}
			$Query = $this->db->insert($this->config->item("api_simple_token_table"),array(
				"Token" => $Token,
				"StartTime" => $StartTime,
				"EndTime" => $EndTime,
				"Level" => $Level,
				"UserId" => $UserId
			));
			if(is_integer($this->db->insert_id())){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function deletes a simple token, based on a user id and a token
	 * @param integer $UserId The user owning the token
	 * @param string $Token The token to unset
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function Logout($UserId = NULL,$Token = NULL){
		if(!is_null($UserId)){
			if(!is_null($Token)){
				$this->db->where(array("UserId" => $UserId,"Token" => $Token))->delete($this->config->item("api_simple_token_table"));
			} else {
				$this->db->where(array("UserId" => $UserId))->delete($this->config->item("api_simple_token_table"));
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function check the data for errors and call the _Auth function
	 * @param string $RequestCode The generated request code
	 * @param integer $AppId       The database id of the app to auth
	 * @param integer $UserId      The database id of the user to auth access too
	 * @param integer $Level The level that the requester has been given access too
	 * @param array $Sections The sections the requester has been given access too
	 * @see _Auth
	 * @since 1.0
	 * @access public
	 */
	public function Auth($RequestCode = NULL,$AppId = NULL,$UserId = NULL,$Level = NULL,$Sections = NULL){
		if(!is_null($RequestCode) 
			&& !is_null($AppId) 
			&& !is_null($UserId) 
			&& self::App_Exists($AppId) 
			&& self::_User_Exists($UserId)
		){
			return self::_Auth($RequestCode,$AppId,$UserId,$Level,$Sections);
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
	 * @param integer $Level The access level
	 * @param array $Sections The accessible extra fields
	 * @return boolean if success
	 * @since 1.0
	 * @access private
	 */
	private function _Auth($RequestCode = NULL,$AppId = NULL,$UserId = NULL,$Level = NULL,$Sections = NULL){
		if(!is_null($RequestCode) && !is_null($AppId) && !is_null($UserId)){
			if(is_array($Sections) && count($Sections) > 0){
				$Sections = ";".implode(";", $Sections).";";
			}
			$this->db->insert($this->config->item("api_request_code_table"),
				array(
					"StartTime" => time(),
					"RequestCode" => $RequestCode,
					"AppId" => $AppId,
					"UserId" => $UserId,
					"Level" => $Level,
					"Sections" => $Sections
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
					if(!is_null($Data[0]->AuthenticationEndpoint)){
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
	 * This function validates a simple token, and if it's valid
	 * @param string $Token The simple token to match
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Is_Valid_SimpleToken($Token = NULL){
		if(!is_null($Token)){
			$Query = $this->db->where(array("Token" => $Token))->limit(1)->from($this->config->item("api_simple_token_table"))->get();
			if(!is_null($Query) && $Query->num_rows() > 0){
				$Row = current($Query->result());
				if($Row->EndTime == 0){
					return TRUE;
				} else {
					return time() > $Row->EndTime;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function validates a simple token, and checks if the userid is valid
	 * @param string $Token   The token to match
	 * @param pointer|integer &$Level  A variable to store he level in
	 * @param pointer|integer &$UserId A variable to store the user id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Token_Authenticate($Token = NULL,&$Level = NULL,&$UserId = NULL){
		if(!is_null($Token) && self::Is_Valid_SimpleToken($Token)){
			$Query = $this->db->select("Level,UserId")->where(array("Token" => $Token))->from($this->config->item("api_simple_token_table"))->limit(1)->get();
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if(!is_null($Row->Level) && $Row->Level != "" && $Row->Level != 0){
					$Level = $Row->Level;
				}
				if(!is_null($Row->UserId) && $Row->UserId != "" && self::_User_Exists($Row->UserId)){
					$UserId = $Row->UserId;
					return TRUE;
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
	 * This function validates a simple token
	 * @param string $Token The simple token to validate
	 * @since 1.0
	 * @access public
	 */
	public function Is_Valid_ClickThis_Token($Token = NULL){
		if(!is_null($Token)){
			$Query = $this->db->where(array("Token" => $Token))->limit(1)->get($this->config->item("api_simple_token_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if($Row->EndTime == 0 || is_null($Row->EndTime)){
					return TRUE;
				} else {
					if(time() > $Row->StartTime + $Row->EndTime){
						return FALSE;
					} else {
						return TRUE;
					}
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a set of access tokens exists and is valid
	 * @param string $Token  The access token
	 * @param string $Secret The access token secret
	 * @return boolean If it exists and are active
	 * @since 1.0
	 * @access public
	 */
	public function Is_Valid_Access_Token($Token = NULL,$Secret = NULL){
		if(!is_null($Token)){
			if(!is_null($Secret)){
				$Query = $this->db->limit(1)->where(array("AccessKey" => $Token,"AccessSecret" => $Secret))->get($this->config->item("api_access_token_table"));
			} else {
				$Query = $this->db->limit(1)->where(array("AccessKey" => $Token))->get($this->config->item("api_access_token_table"));
			}
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if($Row->EndTime == 0){
					return TRUE;
				} else {
					if(time() > $Row->StartTime + $Row->EndTime || is_null($Row->EndTime)){
						return FALSE;
					} else {
						return TRUE;
					}
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the user id based on a set of request tokens
	 * @param string $Token   The request token
	 * @param string $Secret  The request secret token
	 * @param pointer|integer &$UserId A variable to store the user id on
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Get_User_On_Request_Tokens($Token = NULL,$Secret = NULL,&$UserId = NULL){
		if(!is_null($Token) && !is_null($Secret)){
			$JoinString = $this->config->item("api_request_code_table").".RequestCode"." = ".$this->config->item("api_request_token_table").".RequestCode";
			$Query = $this->db->select("UserId")->where(array("RequestKey" => $Token,"RequestSecret" => $Secret))->from($this->config->item("api_request_token_table"))->join($this->config->item("api_request_code_table"),$JoinString,"left")->get();
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$UserId = $Row->UserId;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function validates the access tokens
	 * and returns the Level and Sections
	 * @return boolean If the access token is valid
	 * @since 1.0
	 * @access public
	 * @param string $Token     The access token
	 * @param string $Secret    The access token secret
	 * @param pointer|integer &$Level    The access level of the token
	 * @param pointer|array &$Sections An array of extra sections that the token has access too
	 */
	public function Authenticate($Token = NULL,$Secret = NULL,&$Level = NULL,&$Sections = NULL,&$UserId = NULL){
		if(!is_null($Token) && self::Is_Valid_Access_Token($Token,$Secret)){
			if(!is_null($Secret)){
				$Query = $this->db->limit(1)->where(array("AccessKey" => $Token,"AccessSecret" => $Secret))->get($this->config->item("api_access_token_table"));
			} else {
				$Query = $this->db->limit(1)->where(array("AccessKey" => $Token))->get($this->config->item("api_access_token_table"));
			}
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if(self::_Get_User_On_Request_Tokens($Row->RequestKey,$Row->RequestSecret,$UserId)){
					if(!is_null($Row->Level) && $Row->Level != ""){
						$Level = $Row->Level;
					}
					if(!is_null($Row->Sections) && $Row->Sections != "" && $Row->Sections != ";"){
						$Sections = explode(";", rtrim($Row->Sections,";"));
					}
					self::_Get_User_On_Request_Tokens($Row->RequestKey,$Row->RequestSecret);
					return TRUE;
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
		$Query = $this->db->insert($this->config->item("api_request_token_table"),array("AppId" => $AppId,"RequestKey" => $Key,"RequestSecret" => $Secret,"RequestCode" => $RequestCode,"StartTime" => time()));
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
					return TRUE;
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
	 * This function gets the app id and request code based on the request tokens
	 * @param string $Key          The request token
	 * @param string $Secret       The request secret token
	 * @param pointer|string &$RequestCode The pointer to store the RequestCode
	 * @param pointer|string &$AppId       The pointer to store the AppId
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Get_App_Of_Request_Token($Key = NULL,$Secret = NULL,&$RequestCode,&$AppId){
		if(!is_null($Key) && !is_null($Secret)){
			$Query = $this->db->select("AppId,RequestCode")->limit(1)->where(array("RequestKey" => $Key,"RequestSecret" => $Secret))->get($this->config->item("api_request_token_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$RequestCode = $Row->RequestCode;
				$AppId = $Row->AppId;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the sections and level based on a request code
	 * @param string $RequestCode The request code to search for
	 * @param pointer|string &$Sections   A pointer where to store the sections
	 * @param pointer|string &$Level      A pointer where to store the level
	 * @access private
	 * @since 1.0
	 */
	private function _Get_Permissions($RequestCode = NULL,&$Sections,&$Level){
		if(!is_null($RequestCode)){
			$Query = $this->db->select("Sections,Level")->limit(1)->where(array("RequestCode" => $RequestCode))->get($this->config->item("api_request_code_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$Sections = $Row->Sections;
				$Level = $Row->Level;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function inserts the newly created access token to the database
	 * @param string $Key           The created access token
	 * @param string $Secret        The created access secret
	 * @param string $RequestToken  The used reqeust token
	 * @param string $RequestSecret The used request secret token
	 * @param integer $Level         The access level of the token
	 * @param string $Sections      The extra fields that the token have access too
	 * @param integer $AppId         The app id of the owner of the tokens
	 * @access private
	 * @since 1.0
	 */
	private function _Insert_Access_Token($Key,$Secret,$RequestToken,$RequestSecret,$Level,$Sections,$AppId){
		$StartTime = time();
		if(is_null($Level)){
			$Level = 5;
		}
		$TimeToLive = $this->config->item("api_access_tokens_time_to live");
		if(is_array($TimeToLive) && array_key_exists($Level, $TimeToLive)){
			$EndTime = $TimeToLive[$Level];
		} else {
			$EndTime = 432000;
		}
		$Query = $this->db->insert($this->config->item("api_access_token_table"),array(
			"AppId" => $AppId,
			"RequestKey" => $RequestToken,
			"RequestSecret" => $RequestSecret,
			"AccessKey" => $Key,
			"AccessSecret" => $Secret,
			"StartTime" => $StartTime,
			"EndTime" => $EndTime,
			"Level" => $Level,
			"Sections" => $Sections
		));
		if(is_integer($this->db->insert_id()) && $this->db->insert_id() != 0){
			return true;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if the Request tokens already have been used
	 * @param string $Key    The request token
	 * @param string $Secret The request secret token
	 * @access private
	 * @since 1.0
	 * @return boolean
	 */
	private function _Is_Request_Token_Used($Key = NULL,$Secret = NULL){
		if(!is_null($Key) && !is_null($Secret)){
			$Query = $this->db->select("Id")->limit(1)->where(array("RequestKey" => $Key,"RequestSecret" => $Secret))->get($this->config->item("api_access_token_table"));
			if(!is_null($Query) && $Query->num_rows() == 0){
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}

	/**
	 * This function get's the last information and then call the function to insert the action tokens
	 * @param string $Key           The access token
	 * @param string $Secret        The access token secret
	 * @param string $Request       The request token
	 * @param string $RequestSecret The request secret token
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	public function Access_Token($Key = NULL,$Secret = NULL,$Request = NULL,$RequestSecret = NULL){
		if(!is_null($Key) && !is_null($Secret) && !is_null($Request) && !is_null($RequestSecret) && !self::_Is_Request_Token_Used($Request,$RequestSecret)){
			if(self::_Get_App_Of_Request_Token($Request,$RequestSecret,$RequestCode,$AppId)){
				if(self::_Get_Permissions($RequestCode,$Sections,$Level)){
					return self::_Insert_Access_Token($Key,$Secret,$Request,$RequestSecret,$Level,$Sections,$AppId);
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