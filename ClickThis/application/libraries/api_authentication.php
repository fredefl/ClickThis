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
	 * The access token
	 * @var String
	 * @access private
	 * @since 1.0
	 */
	private $_Access_Token = NULL;

	/**
	 * The secret access token, used for changing password with the api
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Access_Token_Secret = NULL;

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
	 * The access level of the token
	 * @var integer
	 * @access private
	 * @since 1.0
	 */
	private $_Level = NULL;

	/**
	 * The sections that the requester want's access too
	 * @var array
	 * @since 1.0
	 * @access private
	 */
	private $_Sections = NULL;

	/**
	 * This array is storing the errors, if some occured
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	private $_Errors = NULL;

	/**
	 * The id of the current user if one
	 * @var integer
	 * @access private
	 * @since 1.0
	 */
	private $_User_Id = NULL;

	/**
	 * The clickthis token of a user
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_ClickThis_Token = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The base url of codeigniter
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Base_Url = NULL;

	/**
	 * This property contains the sections
	 * that a set of access tokens are autherized too
	 * @var array
	 * @since 1.1
	 * @access private
	 */
	private $_Autherized_Sections = NULL;

	/**
	 * This property contains the 
	 * access level of a specified set of access tokens
	 * @var integer
	 * @access private
	 * @since 1.0
	 */
	private $_Autherized_Level = NULL;

	/**
	 * If the access token secret is set and is correct
	 * @var boolean
	 * @access private
	 * @since 1.0
	 */
	private $_Secret_Access = FALSE;

	/**
	 * If this is true, then the token(s) has write access
	 * @var boolean
	 * @access private
	 * @since 1.0
	 */
	private $_Write_Access = FALSE;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function Api_Authentication(){
		$this->_CI =& get_instance();
		$this->_CI->load->model("Api_Auth");
		$this->_CI->load->config("api");
	}

	/**
	 * This function sets the internal base url
	 * @since 1.0
	 * @access public
	 */
	public function Base_Url($Url = NULL){
		if(!is_null($Url)){
			$this->_Base_Url = $Url;
		}
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
		if(isset($_GET["redirect"]) && !empty($_GET["redirect"]) && $_GET["redirect"] != "null"){
			$Redirect = $_GET["redirect"];
		} else {
			if(!is_null($this->_App_Id)){
				$Result = $this->_CI->Api_Auth->AuthenticationEndpoint($this->_App_Id);
				if($Result !== false && !is_null($Result)){
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
		if(isset($_GET["app_id"]) && !empty($_GET["app_id"]) && $this->_CI->Api_Auth->App_Exists($_GET["app_id"])){
			$this->_App_Id = $_GET["app_id"];
			return TRUE;
		} else {
			self::_Add_Error("No app id specified");
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
			self::_Add_Error("No consumer key is specified");
		}

		if(isset($_GET["consumer_secret"]) && !empty($_GET["consumer_secret"])){
			$this->_Consumer_Secret = $_GET["consumer_secret"];
			if($Return === true){
				$Return = TRUE;
			}
		} else {
			self::_Add_Error("No consumer secret is specified");
			$Return = FALSE;
		}
		if($this->_CI->Api_Auth->Is_Valid_Consumer($this->_Consumer_Key,$this->_Consumer_Secret,$this->_Request_Code)){
			$Return = TRUE;
		} else {
			self::_Add_Error("The consumer keys aren't valid");
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
		$Request_Token = NULL;
		$Request_Secure = NULL;
		if(isset($_GET["request_token"]) && !empty($_GET["request_token"])){
			$Request_Token = $_GET["request_token"];
			//$this->_Request_Token = $_GET["request_token"];
			$Return = TRUE;
		} else {
			self::_Add_Error("No request token specified");
			$Return = FALSE;
		}
		if(isset($_GET["request_secret"]) && !empty($_GET["request_secret"])){
			$Request_Secret = $_GET["request_secret"];
			if($Return === true){
				if($this->_CI->Api_Auth->Validate_Request_Tokens($Request_Token,$Request_Secret,$this->_Consumer_Secret,$this->_Consumer_Key)){
					$Return = true;
					$this->_Request_Token = $Request_Token;
					$this->_Request_Token_Secret = $Request_Secret;
				} else {
					self::_Add_Error("The specified tokens aren't matching the app");
					$Return = FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			self::_Add_Error("No request secret is deffined");
			$Return = FALSE;
		}
		return $Return;
	}

	/**
	 * Returns a value of the specified key minus the _
	 * @param string $Key The key name to get
	 * @since 1.0
	 * @access public
	 */
	public function Get($Key = NULL){
		if(!is_null($Key) && property_exists($this, "_".$Key)){
			$Key = "_".$Key;
			return $this->{$Key};
		}
	}

	/**
	 * This function sets one of the class properties
	 * @param string $Key   The key to set the value too, minus the _
	 * @param string $Value The value to set
	 * @since 1.0
	 * @access public
	 */
	public function Set($Key = NULL,$Value = NULL){
		if(!is_null($Key) && !is_null($Value) && property_exists($this, "_".$Key)){
			$Key = "_".$Key;
			$this->{$Key} = $Value;
		}
	}

	/**
	 * This function checks if a user id is set and the user exists
	 * @since 1.0
	 * @access private
	 * @return boolean If the user is set and exists
	 */
	private function _User_Id(){
		if(isset($_SESSION["UserId"]) && !is_null($_SESSION["UserId"]) && $this->_CI->Api_Auth->User_Exists($_SESSION["UserId"])){
			$this->_User_Id = $_SESSION["UserId"];
			return TRUE;
		} else {
			self::_Add_Error("The user isn't existing");
			return FALSE;
		}
	}

	/**
	 * This function gets the request code if is set, else FALSE is returned
	 * @return boolean If is set and is valid
	 * @since 1.0
	 * @access private
	 */
	private function _Request_Code(){
		if(isset($_GET["request_code"]) && !empty($_GET["request_code"]) && $this->_CI->Api_Auth->Is_Valid_Request_Code($_GET["request_code"])){
			$this->_Request_Code = $_GET["request_code"];
			return TRUE;
		} else {
			self::_Add_Error("The request code is not valid");
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
	 * This function creates a secret and a request token and assign em to a specified variable.
	 * @param integer $Length   The length of the token, secret will be length*2
	 * @param pointer  &$Token  The variable to assign the token too
	 * @param pointer  &$Secret The variable to assign the secret too
	 */
	private function _Generate_Request_Tokens($Length = 64,&$Token = NULL,&$Secret = NULL){
		if(is_integer($Length)){
			$Token = self::_Rand_Str($Length);
			$Secret = self::_Rand_Str($Length*2);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function uses the model to generate a token and then it's placed in $this->_ClickThis_Token
	 * @param integer $Level The access level of the token 1-10
	 * @access public
	 * @return boolean
	 * @since 1.0
	 */
	public function ClickThis_Token($Level = 2){
		if(self::_User_Id()){
			$Token = self::_Rand_Str(64);
			if($this->_CI->Api_Auth->ClickThis_Token($Token,$this->_User_Id,$this->_Level)){
				$this->_ClickThis_Token = $Token;
				return TRUE;
			} else {
				return FALSE;
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if the access token is set and is valid, and if the secret is set then it's validated too
	 * @since 1.0
	 * @access private
	 * @return boolean If success or not
	 */
	private function _Access(){
		if(isset($_GET["access_secret"]) && !empty($_GET["access_secret"])){
			if(isset($_GET["access_token"]) && !empty($_GET["access_token"]) && isset($_GET["access_secret"]) && !empty($_GET["access_secret"]) && $this->_CI->Api_Auth->Is_Valid_Access_Token($_GET["access_token"],$_GET["access_secret"])){
				$this->_Access_Token_Secret = $_GET["access_secret"];
				$this->_Access_Token = $_GET["access_token"];
				$this->_Secret_Access = TRUE;
				$this->_Write_Access = TRUE;
				return TRUE;
			} else{
				self::_Add_Error("The access token and secret is not correct or not deffined");
				return FALSE;
			}
		} else {
			if(isset($_GET["access_token"]) && !empty($_GET["access_token"]) && $this->_CI->Api_Auth->Is_Valid_Access_Token($_GET["access_token"])){
				$this->_Access_Token = $_GET["access_token"];
				$this->_Secret_Access = FALSE;
				return TRUE;
			} else {
				self::_Add_Error("The access token is not correct or not deffined");
				return FALSE;
			}
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
	 * This function checks if a simple token/clickthis token is set and is valid
	 * @access private
	 * @since 1.0
	 */
	private function _ClickThis_Token(){
		if(isset($_GET["token"]) && !empty($_GET["token"]) && $this->_CI->Api_Auth->Is_Valid_SimpleToken($_GET["token"])){
			$this->_ClickThis_Token = $_GET["token"];
			return TRUE;
		} else {
			self::_Add_Error("Simple token is not deffined");
			return FALSE;
		}
	}

	/**
	 * This function ensures that the access token(s) are validated
	 * @access public
	 * @since 1.0
	 */
	public function Authenticate(){
		if(self::_Access()){
			if($this->_CI->Api_Auth->Authenticate($this->_Access_Token,$this->_Access_Token_Secret,$this->_Level,$this->_Sections,$this->_User_Id)){
				if($this->_Level < $this->_CI->config->item("api_secret_access_token_max")+1 && $this->_Level > 0 && $this->_Secret_Access === TRUE){
					$this->_Secret_Access = TRUE;
				} else {
					$this->_Secret_Access = FALSE;
				}
				if($this->_Level > 0 && $this->_Level < $this->_CI->config->item("api_write_access_token_max")+1 && $this->_Write_Access === true){
					$this->_Write_Access = TRUE;
				}
				return TRUE;
			} else {
				self::_Add_Error("The authentication failed no access given");
				return FALSE;
			}
		} else if(self::_ClickThis_Token()){
			if($this->_CI->Api_Auth->Token_Authenticate($this->_ClickThis_Token,$this->_Level,$this->_User_Id)){
				if($this->_Level < $this->_CI->config->item("api_secret_access_token_max")+1 && $this->_Level > 0){
					$this->_Secret_Access = TRUE;
				} else {
					$this->_Secret_Access = FALSE;
				}
				if($this->_Level > 0 && $this->_Level < $this->_CI->config->item("api_write_access_token_max")+1){
					$this->_Write_Access = TRUE;
				}
				return TRUE;
			} else {
				self::_Add_Error("A token isn't set or the specified token isn't valid");
				return FALSE;
			}
		} else {
			self::_Add_Error("You are not authenticated");
			return FALSE;
		}
	}

	/**
	 * This function performs the Request_Token api authentication request
	 * @since 1.0
	 * @access public
	 */
	public function Request_Token(){
		self::_Redirect_Url();
		if (self::_Request_Code() && self::_Consumer() && self::_Redirect_Url()) {
			if(self::_Generate_Request_Tokens(32,$Request_Token,$Request_Secret)){
				if($this->_CI->Api_Auth->Request_Token($Request_Token,$Request_Secret,$this->_Request_Code)){
					$this->_Request_Token_Secret = $Request_Secret;
					$this->_Request_Token = $Request_Token;
					return TRUE;
				} else {
					self::_Add_Error("Specified input isn't valid or an request token with that request code is existing");
					return FALSE;
				}
			} else {
				self::_Add_Error("An error occured");
				return FALSE;
			}
		} else {
			self::_Add_Error("Your validation failed");
			return FALSE;
		}
	}

	/**
	 * This function checks if the Authentication, was accepted or not
	 * @access private
	 * @since 1.0
	 */
	private function _Accepted_Auth(){
		if(isset($_POST["auth"]) && !empty($_POST) && $_POST["auth"] === "auth" && strpos($_SERVER["HTTP_REFERER"],$this->_Base_Url) !== false){
			if(self::_User_Id()){
				return TRUE;
			} else {
				self::_Add_Error("The user doesn't exist");
				return FALSE;
			}
		} else {
			self::_Add_Error("User denied");
			return FALSE;
		}
	}

	/**
	 * This function gets the requested level of the token
	 * @access private
	 * @since 1.0
	 */
	private function _Level(){
		if(isset($_GET["level"]) && $_GET["level"] > 1 && is_integer($_GET["level"])){
			$this->_Level = $_GET["level"];
		} else {
			$this->_Level = 2;
		}
	}

	/**
	 * This function get's the sections that the reqeuster want's access too;
	 * @access private
	 * @since 1.0
	 */
	private function _Sections(){
		if(isset($_GET["sections"])){
			$Sections = str_replace(",", ";", $_GET["sections"]);
			$Sections = explode(";", $Sections);
			$Sections = $Sections.";";
			foreach ($Sections as $Key => $Value) {
				if($Key = "Password" || $Key = "TOPT"){
					unset($Sections[$Key]);
				}
			}
			$this->_Sections = $Sections;
		}
	}

	/**
	 * This function checks if the user exists and the App exists
	 * @access public
	 * @since 1.0
	 */
	public function AuthDialog(){
		self::_Redirect_Url();
		if(self::_App_Id() && self::_Redirect_Url() && self::_User_Id()){
			return TRUE;
		} else {
			self::_Add_Error("Wrong input");
			return FALSE;
		}
	}

	/**
	 * This function performs the user Authentication request
	 * @since 1.0
	 * @access public
	 */
	public function Auth(){
		self::_Redirect_Url();
		if(self::_Accepted_Auth()){
			if(self::_App_Id() && self::_Redirect_Url() && self::_User_Id()){
				self::_Level();
				self::_Sections();
				$Request_Code = self::_Generate_Request_Code(32);
				if($this->_CI->Api_Auth->Auth($Request_Code,$this->_App_Id,$this->_User_Id,$this->_Level,$this->_Sections)){
					$this->_Request_Code = $Request_Code;
					return TRUE;
				} else 	{
					self::_Add_Error("Specified input isn't valid");
					return FALSE;
				}
			} else {
				self::_Add_Error("Wrong input");
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
		self::_Redirect_Url();
		if(self::_Consumer() && self::_Request() && self::_Redirect_Url()){
			$Secret = self::_Rand_Str(64);
			$Key = self::_Rand_Str(32);
			if($this->_CI->Api_Auth->Access_Token($Key,$Secret,$this->_Request_Token,$this->_Request_Token_Secret)){
				$this->_Access_Token = $Key;
				$this->_Access_Token_Secret = $Secret;
				return TRUE;
			} else {
				self::_Add_Error("The request tokens have been used or an error occured");
				return FALSE;
			}
		} else {
			self::_Add_Error("Your validation failed");
			return FALSE;
		}
	}
}
?>