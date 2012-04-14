<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Api_Register extends CI_Model{

	/**
	 * The activation token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Token = NULL;

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Api_Register(){
		$this->load->config("api");
	}

	/**
	 * This function checks if a user exists based on email and username
	 * @param string $Username The username that the user is trying to register with
	 * @param string $Email    The email that the user is trying to register with
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function User_Exists($Username = NULL,$Email = NULL){
		if(!is_null($Username) && !is_null($Email)){
			$EmailQuery = $this->db->where(array("Email" => $Email))->limit(1)->get($this->config->item("api_users_table"));
			$UsernameQuery = $this->db->where(array("Username" => $Username))->limit(1)->get($this->config->item("api_users_table"));
			if($EmailQuery->num_rows() > 0 || $UsernameQuery->num_rows() > 0){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}

	/**
	 * This function registers the user and send an activation email
	 * @param string $Username The username to register
	 * @param string $Password The password to register for
	 * @param string $Name     The name of the user
	 * @param string $Email    The email of the new user
	 * @param pointer|integer &$ReturnUserId The newly created user id
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function Register($Username = NULL,$Password = NULL,$Name = NULL,$Email = NULL,&$ReturnUserId){
		$Data = array("UserGroup" => "User","Status" => 0);
		if(!is_null($Username)){
			$Data["Username"] = $Username;
		}
		if(!is_null($Password)){
			$Data["Password"] = $Password;
		}
		if(!is_null($Email)){
			$Data["Email"] = $Email;
		}
		if(!is_null($Name)){
			$Data["RealName"] = $Name;
		}
		$this->db->insert($this->config->item("api_users_table"),$Data);
		if(is_integer($this->db->insert_id())){
			$UserId = $this->db->insert_id();
			$ReturnUserId = $UserId;
			if(self::_User_Exists($UserId)){
				return self::_ActivationToken($UserId,$Data["Email"],$Data["RealName"]);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function sends a activation email to the user
	 * @param string $Email The email to send too
	 * @param string $Token The token to activate with
	 * @param string $Name The name of the new user
	 * @since 1.0
	 * @access private
	 */
	private function _ActivationEmail($Email = NULL,$Token = NULL,$Name = NULL){
		$To      = $Name." <".$Email.">";
		$Subject = 'Activation of your ClickThis account';
		$Url = $this->config->item("api_host_url")."/activate/".$Token;
		$Message = 'Here\'s your activation link <a href="'.$Url.'">'.$Url.'</a>';
		$Headers  = 'MIME-Version: 1.0' . "\r\n";
		$Headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$Headers .= "From: Illution <".$this->config->item("api_email") . "> " . "\r\n" .
		    'Reply-To: '. $this->config->item("api_email") . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		mail($To, $Subject, $Message, $Headers);
	}

	/**
	 * This function creates the activation token
	 * and inserts it into the database
	 * @param integer $UserId The user id of the owner
	 * @param string $Email The email of the owning user
	 * @param string $Name The name of the user
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _ActivationToken($UserId = NULL,$Email = NULL,$Name = NULL){
		if(!is_null($UserId) && !is_null($Email)){
			$Token = self::_Rand_Str(64);
			$TokenData = array(
				"Token" => $Token,
				"UserId" => $UserId,
				"Email" => $Email,
				"CreatedTime" => time()
			);
			$this->Token = $Token;
			$this->db->insert($this->config->item("api_activation_token_table"),$TokenData);
			self::_ActivationEmail($Email,$Token,$Name);
			return TRUE;
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
	 * This function generates a random string
	 * @param  integer $Length The length of the random string
	 * @param  string  $Chars  The Charset to use
	 * @return string
	 * @author Kyle Florence <kyle.florence@gmail.com>
	 * @since 1.0
	 * @access private
	 */
	private function _Rand_Str($Length = 32, $Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
	    $Chars_Length = (strlen($Chars) - 1);
	    $String = $Chars{rand(0, $Chars_Length)};
	    for ($I = 1; $I < $Length; $I = strlen($String))
	    {
	        $R = $Chars{rand(0, $Chars_Length)};
	        if ($R != $String{$I - 1}) $String .=  $R;
	    }
	    return $String;
	}

	/**
	 * This function checks if an activation token exists and the user exists
	 * @param string $Token   The token to check for
	 * @param pointer|integer &$UserId A variable to store the user id if succes
	 * @param pointer|integer &$Status The activation status of the user
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Get_User_By_Activation_Token($Token = NULL,&$UserId,&$Status = NULL){
		if(!is_null($Token)){
			$Query = $this->db->where(array("Token" => $Token))->limit(1)->get($this->config->item("api_activation_token_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if(self::_User_Exists($Row->UserId)){
					$UserId = $Row->UserId;
					if(!is_null($Status) && !is_null($Row->Status)){
						$Status = $Row->Status;
					} else {
						$Status = 0;
					}
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
	 * This function activates a user
	 * @param integer $UserId The user to activate
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Activate_User($UserId = NULL){
		if(!is_null($UserId)){
			$Data = array("Status" => 1);
			$this->db->where(array("Id" => $UserId))->update($this->config->item("api_users_table"),$Data);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function removes the activation token
	 * @param string $Token The token to remove
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Remove_Token($Token = NULL){
		if(!is_null($Token)){
			$this->db->where(array("Token" => $Token))->delete($this->config->item("api_activation_token_table"));
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function activates the user and removes the token
	 * @param string $Token The token to activate and remove
	 * @param pointer|integer &$ReturnUserId A variable to store the user id
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function Activate($Token = NULL,&$ReturnUserId){
		if(!is_null($Token) && self::_Get_User_By_Activation_Token($Token,$UserId,$Status)){
			if($Status == 0){
				$ReturnUserId = $UserId;
	 			if(self::_Activate_User($UserId)){
	 				return self::_Remove_Token($Token);
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
	 * This function gets the users name and email by the user id
	 * @param integer $UserId The user id to search for
	 * @param pointer|string &$Email A variable to store the users email
	 * @param pointer|string &$Name  A variable to store the users name
	 * @param pointer|integer &$Status The activation status of the user
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Get_User_Information($UserId = NULL,&$Email,&$Name,&$Status = NULL){
		if(!is_null($UserId)){
			$Query = $this->db->where(array("Id" => $UserId))->limit(1)->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$Email = $Row->Email;
				$Name = $Row->RealName;
				$Status = $Row->Status;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function resends a users activation email
	 * @param integer $UserId The user id of the registred user
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function ResendEmail($UserId = NULL){
		if(!is_null($UserId) && self::_User_Exists($UserId)){
			if(self::_Get_User_Information($UserId,$Email,$Name,$Status) && $Status == 0){
				return self::_ActivationToken($UserId,$Email,$Name);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}