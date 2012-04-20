<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library is used to Authenticate with LinkedIn
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage LinkedIn
 * @category Third Party
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class LinkedIn{

	/**
	 * The url to the LinkedIn OAuth endpoint
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_oauth_url = "https://api.linkedin.com/uas/oauth/";

	/**
	 * The url to the LinkedIn api
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_api_url = "http://api.linkedin.com/v1/";

	/**
	 * The OAuth consumer key, to the LinkedIn api
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $consumer_key = NULL;

	/**
	 * The OAuth consumer secret key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $consumer_secret = NULL;

	/**
	 * The OAuth callback url
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $callback = NULL;

	/**
	 * The OAuth api access token secret
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token_secret = NULL;

	/**
	 * The OAuth api access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * The request token, used to auth a user
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $request_token = NULL;

	/**
	 * The OAuth request secret key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $request_token_secret = NULL;

	/**
	 * The OAuth token returned from the auth callback
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $token = NULL;

	/**
	 * The OAuth verifier
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $verifier = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function LinkedIn(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("linkedin");
		$this->_CI->load->helper("string"); 
		$this->_CI->load->helper("array_data");
	}

	/**
	 * This function either assigns the consumer keys by the 
	 * parameters of this function or from the linkedin config file
	 * @param  string $consumer_key    The Oauth consumer key
	 * @param  string $consumer_secret The Oauth consumer secret to assing
	 * @since 1.0
	 * @access public
	 */
	public function consumer($consumer_key = NULL,$consumer_secret = NULL){
		if(!is_null($consumer_key)){
			$this->consumer_key = $consumer_key;
		} else if(is_string($this->_CI->config->item("linkedin_api_key"))){
			$this->consumer_key = $this->_CI->config->item("linkedin_api_key");
 		}
 		if(!is_null($consumer_secret)){
 			$this->consumer_secret = $consumer_secret;
 		} else if(is_string($this->_CI->config->item("linkedin_api_secret"))){
 			$this->consumer_secret = $this->_CI->config->item("linkedin_api_secret");
 		}
	}

	/**
	 * This function generates an request token
	 * and if success the user is then send over to LinkedIn
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function auth(){
		$request_url = $this->_oauth_url."authorize";
		if(self::_requestToken()){
			$request_url .= "?oauth_token=".$this->request_token;
			header("Location: ".$request_url);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function generates the 
	 * @param  string $method The HTTP request method
	 * @param  string $url    The request url
	 * @param  array $fields The request oauth fields
	 * @return boolean|string
	 * @since 1.0
	 * @access private
	 */
	private function _signature($method = "GET",$url = NULL,$fields = NULL){
		if(!is_null($url) && !is_null($fields)){
			if(is_null($this->access_token_secret) && is_null($this->request_token_secret)){
				$signature_key = $this->consumer_secret."&";
			} else if(!is_null($this->access_token_secret)){
				$signature_key = urlencode_rfc3986($this->consumer_secret)."&".urlencode_rfc3986($this->access_token_secret);
			} else {
				$signature_key = urlencode_rfc3986($this->consumer_secret)."&".urlencode_rfc3986($this->request_token_secret);
			}
			$base_string = $method."&".urlencode_rfc3986($url)."&".urlencode_rfc3986(assoc_implode("=","&",$fields));
			return base64_encode(hmacsha1($signature_key,$base_string));
		}
	}

    /**
     * This function request an access token keypair
     * @return boolean
     * @since 1.0
     * @access private
     */
	private function _requestToken(){
		if(!is_null($this->consumer_key)){
			$request_url = $this->_oauth_url."requestToken";
			$params = array(
				"oauth_version" => "1.0",
	            "oauth_nonce" => Rand_Str(),
	            "oauth_timestamp" => time(),
	            "oauth_consumer_key" => $this->consumer_key,
	            "oauth_signature_method" => "HMAC-SHA1",
			);
			uksort($params,"strcmp");
			$params['oauth_signature'] = self::_signature("GET",$request_url,$params); 

			$request_url .= "?".assoc_implode("=","&",$params);

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$result = curl_exec($ch);

			curl_close($ch);

			parse_str($result,$array);
			if(isset($array["oauth_token"])){
				$this->request_token = $array["oauth_token"];
				$this->request_token_secret = $array["oauth_token_secret"];
				$_SESSION["oauth_token_secret"] = $array["oauth_token_secret"];
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
	 * This function gets the access token based,
	 * on the return from the auth request
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function callback(){
		if(isset($_GET["oauth_token"])){
			$this->token = $_GET["oauth_token"];
			$this->verifier = $_GET["oauth_verifier"];
			self::_access_token();
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function builds a header string
	 * @param  array $params The paramters to build on
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _build_header($params){
		$return = "";
		foreach ($params as $key => $value) {
			$return .= $key.'="'.$value.'", ';
		}
		$return = rtrim($return,", ");
		return $return;
	}

	/**
	 * This function request the set of access tokens
	 * @return boolean
	 * @access private
	 * @since 1.0
	 */
	private function _access_token(){
		$request_url = $this->_oauth_url."accessToken";
		if(isset($_SESSION["oauth_token_secret"])){
			$this->request_token_secret = $_SESSION["oauth_token_secret"];
		} else {
			return FALSE;
		}
		$params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => Rand_Str(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->consumer_key,
            "oauth_signature_method" => "HMAC-SHA1",
            "oauth_token" => trim($this->token),
            "oauth_verifier" => $this->verifier
	    );

		uksort($params,"strcmp");

        $params['oauth_signature'] = urlencode_rfc3986(self::_signature("GET",$request_url,$params));  
       	$header = "Authorization: OAuth ".self::_build_header($params);

       	$request_url .= "?".assoc_implode("=","&",$params);

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);

		curl_close($ch);

		parse_str($result,$array);
		if(!isset($array["oauth_problem"])){
			$this->access_token = $array["oauth_token"];
			$this->access_token_secret = $array["oauth_token_secret"];
			unset($_SESSION["oauth_token_secret"]);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function is does the same as auth,
	 * but at the server LinkedIn doesn't request users
	 * permission each time
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function login(){
		$request_url = $this->_oauth_url."authenticate";
		if(self::_requestToken()){
			$request_url .= "?oauth_token=".$this->request_token;
			header("Location: ".$request_url);
			return TRUE;
		} else {
			return FALSE;
		}
	}	

	/**
	 * This function request user data of the current user from the LinkedIn api
	 * @param  array $fields An optinal array containing extra profile fields
	 * @return boolean|object
	 * @since 1.0
	 * @access public
	 */
	public function user($fields = NULL){
		$request_url = $this->_api_url."people/~";

		if(!is_null($fields)){
			if(count($this->_CI->config->item("linkedin_fields")) > 0){
				$available_fields = $this->_CI->config->item("linkedin_fields");
				foreach ($fields as $field) {
					if(!in_array($field, $available_fields)){
						unset($fields[$field]);
					}
				}
			}
			$request_url .= ":(".implode(",", $fields).")";
		}

		$params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => Rand_Str(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->consumer_key,
            "oauth_signature_method" => "HMAC-SHA1",
            "oauth_token" => $this->access_token
	    );

	    uksort($params,"strcmp");

        $params['oauth_signature'] = urlencode_rfc3986(self::_signature("GET",$request_url,$params));  
       	$header = "Authorization: OAuth ".self::_build_header($params);        
       
		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array($header));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);
		
		curl_close($ch);

		$object = simplexml_load_string($result);

		if(!property_exists($object, "error-code")){
			return $object;
		} else {
			return FALSE;
		}
	}
}
?>