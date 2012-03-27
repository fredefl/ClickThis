<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Api_Authentication{

	/** 
	 * The app_id, this is used in the Auth request
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_App_Id = NULL;

	/**
	 * The url to redirect too, when the request is done
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Redirect_Url = NULL;

	/**
	 * The request code, this code is generated in the Auth function,
	 * and used in the request token function
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Request_Code = NULL;

	/**
	 * The request token, it's generated in the Request_Token request,
	 * and used in the access token request
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Request_Token = NULL;

	/**
	 * The request secret token, it's generated in the Request_Token function
	 * and used in the Access_Token function
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Request_Token_Secret = NULL;

	/**
	 * The consumer key/app owner key, it's used on the Requst_Token and Access_Token request
	 * and on all normal api requests
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Consumer_Key = NULL;

	/**
	 * The consumer key secret/app owner secret key, it's used on the Requst_Token and Access_Token request
	 * and on all normal api requests
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Consumer_Secret = NULL;

	/**
	 * This array is storing the errors, if some occured
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	private $_Errors = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function Api_Authentication(){
		$this->_CI =& get_instance();
		$this->_CI->load->model("Api_Auth");
	}

	/**
	 * This function returns FALSE if no errors or the error array if errors
	 * @since 1.0
	 * @return boolean|array The errors array or FALSE
	 * @access private
	 */
	public function Errors(){
		if(!is_null($this->_Errors) && count($this->_Errors) > 0){
			return $this->_Errors();
		} else {
			return FALSE;
		}
	}

	/**
	 * This function adds a error reason, to the _Errors array
	 * @since 1.0
	 * @access private
	 * @param string $Reason The error reason to add
	 */
	private function _Add_Error($Reason = NULL){
		if(!is_array($this->_Errors)){
			$this->_Errors = array();
		}
		if(!is_null($Reason)){
			$this->_Errors[] = $Reason;
		}
	}

	/**
	 * This function validates the request url, if it's set
	 * @return boolean If it is set and is a valid url
	 * @since 1.0
	 * @access private
	 */
	private function _Redirect_Url($Redirect = NULL){
		if(isset($_GET["redirect"]) && !empty($_GET["redirect"])){
			$Redirect = $_GET["redirect"];
		} else {
			if(!is_null($this->_App_Id)){
				$Result = $this->_CI->Api_Auth->AuthenticationEndpoint($this->_App_Id);
				if($Result !== false){
					$Redirect = $Result;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
		if(!is_null($Redirect)){
			if(strpos($Redirect, "http://") === false && strpos($Redirect, "https://") === false){
				$Redirect = "http://".$Redirect;
			}
			if(self::_Is_Url($Redirect)){
				$this->_Redirect_Url = $Redirect;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function validates the app_id, it's used in the auth request
	 * @since 1.0
	 * @access private
	 */
	private function _App_Id(){
		if(isset($_GET["app_id"]) && !empty($_GET["app_id"])){
			$this->_App_Id = $_GET["app_id"];
			return TRUE;
		} else {
			return  FALSE;
		}
	}

	/**
	 * This function tests if the input is a valid url
	 * @param String $Url The url to test
	 * @return Boolean If the input is a url true is returned
	 * @access private
	 * @since 1.0
	 */
	private function _Is_Url($Url = NULL){
		if(!is_null($Url)){
			$Matches = NULL;
			$Result = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $Url, $Matches);
			if ($Result == 1) {
				return true;
			}
			else {
				return false;
			}
		} else {
			return FALS;
		}
	}

	/**
	 * This function gets the consumer_key and consumer secret,
	 * and validates it.
	 * @return boolean If the two keys are set and are valid
	 * @since 1.0
	 * @access private
	 */
	private function _Consumer(){
		$Return = FALSE;

		if(isset($_GET["consumer_key"]) && !empty($_GET["consumer_key"])){
			$this->_Consumer_Key = $_GET["consumer_key"];
			$Return = true;
		} else {
			$Return = FALSE;
		}

		if(isset($_GET["consumer_secret"]) && !empty($_GET["consumer_secret"])){
			$this->_Consumer_Secret = $_GET["consumer_secret"];
			if($Return === true){
				$Return = TRUE;
			}
		} else {
			$Return = FALSE;
		}
		return $Return;
	}

	/**
	 * This function checks if the request_tokens
	 * are set in the $_GET array and if they are then they are assigned
	 * to this class.
	 * @since 1.0
	 * @access private
	 */
	private function _Request(){
		$Return = FALSE;
		if(isset($_GET["request_token"]) && !empty($_GET["request_token"])){
			$this->_Request_Token = $_GET["request_token"];
			$Return = TRUE;
		} else {
			$Return = FALSE;
		}
		if(isset($_GET["request_token_secret"]) && !empty($_GET["request_token_secret"])){
			$this->_Request_Token_Secret = $_GET["request_token_secret"];
			if($Return === true){
				$Return = true;
			}
		} else {
			$Return = FALSE;
		}
		return $Return;
	}

	/**
	 * This function gets the request code if is set, else FALSE is returned
	 * @return boolean If is set and is valid
	 * @since 1.0
	 * @access private
	 */
	private function _Request_Code(){
		if(isset($_GET["request_code"]) && !empty($_GET["request_code"])){
			$this->_Request_Code = $_GET["request_code"];
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function generates a random string,
	 * for the RequestCode Auth field.
	 * @param integer $Size The length of the string
	 * @since 1.0
	 * @access private
	 * @return string The request code
	 */
	private function _Generate_Request_Code($Size = 32){
		$Code = self::_Rand_Str($Size);
		return $Code;
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
	 * This function performs the Request_Token api authentication request
	 * @since 1.0
	 * @access public
	 */
	public function Request_Token(){
		if (self::_Consumer() && self::_Request_Code() && self::_Redirect_Url()) {
			echo "Success, here's your request token:Fisk";
		} else {
			return FALSE;
		}
	}

	private function _Accepted_Auth(){
		if(isset($_POST["auth"]) && !empty($_POST)){
			if(isset($_SESSION["UserId"]) && !empty($_SESSION) && $this->_CI->Api_Auth->User_Exists($_SESSION["UserId"])){

			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs the user Authentication request
	 * @since 1.0
	 * @access public
	 */
	public function Auth(){
		$UserId = 1;
		if(self::_App_Id() && self::_Redirect_Url() && !is_null($UserId)){
			$Request_Code = self::_Generate_Request_Code(32);
			if($this->_CI->Api_Auth->Auth($Request_Code,$this->_App_Id,$UserId)){
				return TRUE;
			} else 	{
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs the Access_Token api authentication request
	 * @since 1.0
	 * @access public
	 */
	public function Access_Token(){
		if(self::_Consumer() && self::_Request() && self::_Redirect_Url()){
			echo "Success, heres an access token:Llama";
		} else {
			return FALSE;
		}
	}
}
?>