<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Github{
		
	/**
	 * The url to the OAuth auth endpoint for
	 * the Github api
	 * @since 1.0
	 * @access private
	 * @var string
	 */
	private $_auth_url = "https://github.com/login/oauth/";

	/**
	 * The Github api endpoint
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_api_url = "https://api.github.com/";

	/**
	 * The Github client id, of your application
	 * @var string
	 * @since 1,0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The Github client secret of your application
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_secret = NULL;

	/**
	 * The redirect url after a success auth and access token request
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * A list of access scopes send to the api
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $scope = NULL;

	/**
	 * The returned Auth code
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $code = NULL;

	/**
	 * The returned access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * The type of the returned access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $token_type = NULL;

	/**
	 * The error message of an error occur
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $error_message = NULL;

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
	public function Github(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("github");
	}

	/**
	 * This function is used to set the access scopes of the api
	 * @param  array $scopes An array of the access scopes you would like to request access too
	 * @since 1.0
	 * @access public
	 */
	public function scope($scopes = NULL){
		if(!is_null($scopes) && is_array($scopes)){
			foreach ($scopes as $scope) {
				if(is_array($this->_CI->config->item("github_scopes")) && !in_array($scope, $this->_CI->config->item("github_scopes"))){
					unset($scopes[$scope]);
				}
			}
			$this->scope = implode(",", $scopes);
		} else if(!is_null($scopes)){
			$this->scope = $scopes;
		}
	}

	/**
	 * This function either set the keys to the parameters of
	 * this function or to the values of the github config file
	 * @param  string $client_id     The client id api key
	 * @param  string $client_secret The api client secret key
	 * @since 1.0
	 * @access public
	 */
	public function client($client_id = NULL,$client_secret = NULL){
		if(!is_null($client_id)){
			$this->client_id = $client_id;
		} else if(is_string($this->_CI->config->item("github_client_id"))){
			$this->client_id = $this->_CI->config->item("github_client_id");
		}
		if(!is_null($client_secret)){
			$client_secret = $client_secret;
		} else if(is_string($this->_CI->config->item("github_client_secret"))){
			$this->client_secret = $this->_CI->config->item("github_client_secret");
		}
	}

	/**
	 * This function is used to set the redirect_uri for the api
	 * @param  string $redirect_uri The redirect_uri
	 * @since 1.0
	 * @access public
	 */
	public function redirect_uri($redirect_uri = NULL){
		if(!is_null($redirect_uri)){
			$this->redirect_uri = $redirect_uri;
		}
	}

	/**
	 * This function performs the auth request and redierects the user
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function auth(){
		if(!is_null($this->client_id)){
			$request_url = $this->_auth_url."authorize";
			$request_url .= "?client_id=".$this->client_id;
			if(!is_null($this->redirect_uri)){
				$request_url .= "&redirect_uri=".$this->redirect_uri;
			}
			if(!is_null($this->scope)){
				if(is_array($this->scope)){
					self::scope($this->scope);
				}
				$request_url .= "&scope=".$this->scope;
			}

			header("Location: ".$request_url);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs a request for the authed user
	 * @return boolean|object
	 * @since 1.0
	 * @access public
	 */
	public function user(){
		if(!is_null($this->access_token)){
			$request_url = $this->_api_url."user?access_token=".$this->access_token;
			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$request_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);

			$object = json_decode($result);
			if(!isset($object->message)){
				return $object;
			} else {
				$this->error_message = $object->message;
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs the callback function of the auth proccess
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function callback(){
		if(isset($_GET["code"]) && !is_null($this->client_id) && !is_null($this->client_secret)){
			$this->code = $_GET["code"];

			$request_url = $this->_auth_url."access_token";

			$request_url .= "?client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$this->code;
			if(!is_null($this->redirect_uri)){
				$request_url .= "&redirect_uri=".$this->redirect_uri;
			}

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$request_url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,"");
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);

			curl_close($ch);
			parse_str($result,$array);
			if(!isset($array["error"])){
				$this->access_token = $array["access_token"];
				$this->token_type = $array["token_type"];
				return TRUE;
			} else {
				$this->error_message = $array["error"];
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
?>