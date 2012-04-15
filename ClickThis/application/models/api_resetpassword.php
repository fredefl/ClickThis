<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Api_Resetpassword extends CI_Model{

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Api_Resetpassword(){
		$this->load->config("api");
	}

	/**
	 * This function checks if a user exists based on the users email
	 * @param string $Email The email to check for
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function User_Exists($Email = NULL){
		if(!is_null($Email)){
			$Query = $this->db->select("Id,Username,Password,Status")->where(array("Email" => $Email))->limit(1)->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if(!is_null($Row->Username) && $Row->Username != "" /*&& !is_null($Row->Password) && $Row->Password != "" && $Row->Status == 1*/){
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
	 * This function checks if a token exists for a user
	 * @param string $Email The email to check for
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function TokenExists($Email = NULL){
		if(!is_null($Email)){
			if(self::_Get_User_Information($Email,$Name,$UserId)){
				$Query = $this->db->where(array("Email" => $Email,"UserId" => $UserId))->limit(1)->get($this->config->item("api_resetpassword_table"));
				if($Query->num_rows() > 0){
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return TRUE;
			}
		} else {
			return TRUE;
		}
	}

	/**
	 * This function checks if a token exists
	 * @param string $Token The token to check for
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function Is_Valid_Token($Token = NULL){
		if(!is_null($Token)){
			$Query = $this->db->where(array("Token" => $Token))->limit(1)->get($this->config->item("api_resetpassword_table"));
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
	 * This function returns the name and user id of a user found by the email
	 * @param string $Email   The email to search for
	 * @param pointer|string &$Name   A variable to store the name of the user
	 * @param [pointer|integer &$UserId A variable to store the user id
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Get_User_Information($Email = NULL,&$Name = NULL,&$UserId = NULL){
		if(!is_null($Email)){
			$Query = $this->db->select("Id,RealName")->where(array("Email" => $Email))->limit(1)->get($this->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$Name = $Row->RealName;
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
	 * This function checks if the user exists and then a token
	 * is generated and inserted and send
	 * @param string $Email The email to send the token too
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function Reset_Password_Token($Email = NULL){
		if(!is_null($Email) && self::User_Exists($Email)){
			$Token = self::_Rand_Str(64);
			if(self::_Get_User_Information($Email,$Name,$UserId)){
				return self::_Reset_Password_Token($Email,$Name,$UserId,$Token);
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function inserts the token into the table 
	 * and sends the email to the user
	 * @param string $Email  The email to send the email too
	 * @param string $Name   The name of the user
	 * @param ínteger $UserId The user id of the user
	 * @param string $Token  The reset token
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Reset_Password_Token($Email = NULL,$Name = NULL,$UserId = NULL,$Token = NULL){
		if(!is_null($Email) && !is_null($Name) && !is_null($UserId) && !is_null($Token)){
			$Data = array(
				"Token" => $Token,
				"UserId" => $UserId,
				"Email" => $Email,
				"CreatedTime" => time()
			);
			$this->db->insert($this->config->item("api_resetpassword_table"),$Data);
			self::_Reset_Email($Email,$Token,$Name);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function sends an reset password email to the user
	 * @param string $Email The email to send the email too
	 * @param string $Token The reset password token
	 * @param string $Name  The name of the user
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Reset_Email($Email = NULL,$Token = NULL,$Name = NULL){
		$To      = $Name." <".$Email.">";
		$Subject = 'Resetting of your password for ClickThis';
		$Url = base_url()."reset/password/change/".$Token;
		$Message = 'Here\'s your reset link <a href="'.$Url.'">'.$Url.'</a>';
		$Headers  = 'MIME-Version: 1.0' . "\r\n";
		$Headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$Headers .= "From: Illution <".$this->config->item("api_email") . "> " . "\r\n" .
		    'Reply-To: '. $this->config->item("api_email") . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		mail($To, $Subject, $Message, $Headers);
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
	 * This function gets the user id based on a token
	 * @param string $Token   The token to search for
	 * @param pointer|integer &$UserId A variable to store the user id
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Get_User_Information_From_Token($Token = NULL,&$UserId = NULL){
		if(!is_null($Token)){
			$Query = $this->db->select("UserId")->limit(1)->where(array("Token" => $Token))->get($this->config->item("api_resetpassword_table"));
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
	 * This function changes the users password
	 * @param string $Password The new password
	 * @param string $Token    The reset password token
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function ChangePassword($Password = NULL,$Token = NULL){
		if(!is_null($Password) && !is_null($Token) && self::Is_Valid_Token($Token) && self::_Get_User_Information_From_Token($Token,$UserId)){
			$Data = array(
				"Password" => hash_hmac("sha512", $Password, $this->config->item("api_hash_hmac"))
			);
			$this->db->where(array("Id" => $UserId))->update($this->config->item("api_users_table"),$Data);
			self::_Remove_Token($UserId);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function removes the token
	 * @param integer $UserId The user that has requested password reset
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Remove_Token($UserId = NULL){
		if(!is_null($UserId)){
			$this->db->where(array("UserId" => $UserId))->delete($this->config->item("api_resetpassword_table"));
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>