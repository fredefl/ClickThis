<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library is used to Authenticate with Twitter
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Twitter
 * @category Third Party
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Twitter{

	/**
	 * The public api key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $consumer_public = NULL;

	/**
	 * The secret api consumer key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $consumer_secret = NULL;

	/**
	 * The Twitter OAuth endpoint
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_oauth_url = "https://api.twitter.com/oauth/";

	/**
	 * The Twitter api url
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_api_url = "https://api.twitter.com/";

	/**
	 * The auth callback url
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $callback = NULL;

	/**
	 * An optional access type "read" or "write"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_type = NULL;

	/**
	 * The id of the current twitter user
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $user_id = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * If the request_token operation succeded, then this
	 * will contain the request token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $token = NULL;

	/**
	 * The OAuth request token secret
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $token_secret = NULL;

	/**
	 * If the user is going to be forced to enter it's credidentials.
	 * Values are "true" or "false"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $force_login = NULL;

	/**
	 * An optional string to store the current,
	 * twitter name of the user to auth
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $screen_name = NULL;

	/**
	 * The OAuth verifier returned from the auth step
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $verifier = NULL;

	/**
	 * The OAuth access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	public $access_token_secret = NULL;


	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Twitter(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("twitter");
		$this->_CI->load->helper("string"); 
		$this->_CI->load->helper("array_data");
	}

	/**
	 * This function generates the OAuth signature
	 * @param  string $method The HTTP request method
	 * @param  string $url    The request url
	 * @param  array $fields The request oauth fields
	 * @return boolean|string
	 * @since 1.0
	 * @access private
	 */
	private function _signature($method = "GET",$url = NULL,$fields = NULL){
		if(!is_null($url) && !is_null($fields)){
			if(is_null($this->access_token_secret)){
				$signature_key = $this->consumer_secret."&";
			} else {
				$signature_key = self::_urlencode_rfc3986($this->consumer_secret)."&".self::_urlencode_rfc3986($this->access_token_secret);
			}
			$base_string = $method."&".self::_urlencode_rfc3986($url)."&".self::_urlencode_rfc3986(assoc_implode("=","&",$fields));
			return base64_encode(hmacsha1($signature_key,$base_string));
		}
	}

	/**
	 * This function sets the consumer keys either from the parameters or 
	 * from the config
	 * @param  string $consumer_public The consumer_client key
	 * @param  string $consumer_secret The consumer secret key
	 * @since 1.0
	 * @access public
	 */
	public function consumer($consumer_public = NULL,$consumer_secret = NULL){
		if(!is_null($consumer_public)){
			$this->consumer_public = $consumer_public;
		} else if(is_string($this->_CI->config->item("twitter_consumer_key"))){
			$this->consumer_public = $this->_CI->config->item("twitter_consumer_key");
		}
		if(!is_null($consumer_secret)){
			$this->consumer_secret = $consumer_secret;
		} else if(is_string($this->_CI->config->item("twitter_consumer_secret"))){
			$this->consumer_secret = $this->_CI->config->item("twitter_consumer_secret");
		}
	}

	/**
	 * This function encodes the url with the right encoding
	 * @param  string|array $input The string to encode
	 * @return string|array
	 */
 	private function _urlencode_rfc3986($input) {
        if (is_array($input)) {
            return array_map(array($this, '_urlencode_rfc3986'), $input);
        }
        else if (is_scalar($input)) {
            return str_replace('+',' ',str_replace('%7E', '~', rawurlencode($input)));
        } else{
            return '';
        }
    }

	/**
	 * This function bulds a normal get url
	 * @param  array $params The data to build fromt
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _build_url($params = NULL){
		if(!is_null($params) && is_array($params)){
			$return = "";
			foreach ($params as $key => $value) {
				$return .= $key."=".$value."&";
			}
			$return = rtrim($return,"&");
			return $return;
		}
	}

	/**
	 * This function gets the request token keypair
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _request_token(){
		if(!is_null($this->consumer_public) && !is_null($this->callback)){
	        $params = array(
	            "oauth_version" => "1.0",
	            "oauth_nonce" => Rand_Str(),
	            "oauth_timestamp" => time(),
	            "oauth_consumer_key" => $this->consumer_public,
	            "oauth_signature_method" => "HMAC-SHA1",
	        );
	        uksort($params,"strcmp");
	        $request_url = $this->_oauth_url."request_token";
	        $params['oauth_signature'] = self::_signature("GET",$request_url,$params);  

	        $request_url .= "?".self::_build_url($params);
	        if(!is_null($this->access_type)){
	        	$request_url .= "&x_auth_access_type=".$this->access_type;
	        }
	        $ch = curl_init();

			curl_setopt($ch,CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);
			
			curl_close($ch);

			parse_str($result,$array);

			if(isset($array["oauth_token"])){
				$this->token = $array["oauth_token"];
				$this->token_secret = $array["oauth_token_secret"];
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs the auth request and redirects the user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function auth(){
		if(!is_null($this->consumer_public)){
			if(self::_request_token()){
				$request_url = $this->_oauth_url."authenticate?oauth_token=".$this->token;
				if(!is_null($this->force_login)){
					$request_url .= "&force_login=".$this->force_login;
				}
				if(!is_null($this->screen_name)){
					$request_url .= "&screen_name=".$this->screen_name;
				}
				header("Location: ".$request_url);
			} else {
				return FALSE;
			}
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
	 * This function gets the token returned and request an access token set
	 * @return boolean
	 * @access public
	 * @since 1.0
	 */
	public function callback(){
		if(isset($_GET["oauth_verifier"])){
			$this->verifier = $_GET["oauth_verifier"];
			$this->token = $_GET["oauth_token"];
			if(self::_access_token()){
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * This function request the set of access tokens
	 * @return boolean
	 * @access private
	 * @since 1.0
	 */
	private function _access_token(){
		$request_url = $this->_oauth_url."access_token";
		$params = array(
            "oauth_version" => "1.0",
            "oauth_nonce" => Rand_Str(),
            "oauth_timestamp" => time(),
            "oauth_consumer_key" => $this->consumer_public,
            "oauth_signature_method" => "HMAC-SHA1",
            "oauth_token" => $this->token
	    );

    	uksort($params,"strcmp");

        $params['oauth_signature'] = self::_urlencode_rfc3986(self::_signature("GET",$request_url,$params));  
       	$header = "Authorization: OAuth ".self::_build_header($params);        
       
		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,"oauth_verifier=".$this->verifier);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array($header));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = curl_exec($ch);

		curl_close($ch);

		parse_str($result,$array);

		if(isset($array["oauth_token"])){
			$this->access_token = $array["oauth_token"];
			$this->access_token_secret = $array["oauth_token_secret"];
			if(isset($array["screen_name"])){
				$this->screen_name = $array["screen_name"];
			}
			if(isset($array["user_id"])){
				$this->user_id = $array["user_id"];
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the user information from the Twitter api
	 * @return boolean|object
	 * @since 1.0
	 * @access public
	 */
	public function user(){
		if(!is_null($this->access_token)){
			$request_url = $this->_api_url."1/account/verify_credentials.json";

			$params = array(
	            "oauth_version" => "1.0",
	            "oauth_nonce" => Rand_Str(),
	            "oauth_timestamp" => time(),
	            "oauth_consumer_key" => $this->consumer_public,
	            "oauth_signature_method" => "HMAC-SHA1",
	            "oauth_token" => $this->access_token
		    );

			uksort($params,"strcmp");

	        $params['oauth_signature'] = self::_urlencode_rfc3986(self::_signature("GET",$request_url,$params));  
	       	$header = "Authorization: OAuth ".self::_build_header($params);        
	       
			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array($header));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$result = curl_exec($ch);
			
			curl_close($ch);
			$object = json_decode($result);
			if(property_exists($object, "id")){
				return $object;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}