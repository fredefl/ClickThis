<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library authenticates with Facebook and
 * it has a function to get user info too
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Facebook
 * @category Third Party
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Facebook{

	/**
	 * This is the local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The url to the Facebook OAuth api
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_oauth_url = "https://www.facebook.com/dialog/oauth/";

	/**
	 * The url to the Facebook Graph API OAuth endpoint
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_graph_oauth_url = "https://graph.facebook.com/oauth/";

	/**
	 * The url to the Facebook Graph API
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_graph_url = "https://graph.facebook.com/";

	/**
	 * The Facebook client id, taken from the apps page
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The request fields
	 * for the user request
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $fields = NULL;

	/**
	 * The client secret api key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public  $client_secret = NULL;

	/**
	 * The app redirect url
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * The random generated state value
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $state = NULL;

	/**
	 * If the api call fails this will be set
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $error_message = NULL;

	/**
	 * The scope string, use the scope function to set it
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $scope = NULL;

	/**
	 * The dialog display type "page","popup" or "touch"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $display = NULL;

	/**
	 * The reponse type "token" or "code"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $response_type = NULL;

	/**
	 * This array contains a list of all available permissions
	 * @var array
	 * @since 1.0
	 * @access private
	 */
	private $_permissions = array();

	/**
	 * The returned authentification code
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $code = NULL;

	/**
	 * The time to live for the token,
	 * sent from Facebook
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $expires = NULL;

	/**
	 * This will be set if thr request was successfull
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * The app name in lower case, used for
	 * some permissions
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $app_namespace = NULL;

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Facebook(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("api");
		$this->_CI->load->helper("string");
		$this->_CI->load->config("facebook");
		if(is_string($this->_CI->config->item("facebook_permission"))){
			$this->_permissions = $this->_CI->config->item("facebook_permission");
		}
	}

	/**
	 * This function is used to set the fields
	 * @param  array $fields An array of the requested fields
	 */
	public function fields($fields = NULL){
		if(!is_null($fields) && is_array($fields)){
			$string = implode(",", $fields);
			$string = rtrim($string,",");
			$this->fields = string;
		} else if(!is_null($fields)){
			$this->fields = $fields;
		}
	}

	/**
	 * If the session state parameter is set then the request state is
	 * checked else is a state value generated
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _state(){
		if(isset($_SESSION["state"]) && $_SESSION["state"] != ""){
			if(isset($_REQUEST["state"]) && $_SESSION["state"] == $_REQUEST["state"]){
				$this->state = $_REQUEST["state"];
				return TRUE;
			} else {
				//The request state isn't sent over
				return TRUE;
			}
		} else {
			$this->state = Rand_Str(16);
			$_SESSION["state"] = $this->state;
			return FALSE;
		}
	}

	/**
	 * This function is used to set the scopes parameter easily
	 * @param  array  $scopes    The scopes to add
	 * @param  boolean $overwrite If the existing data is going to be overwritten
	 * @since 1.0
	 * @access public
	 */
	public function scope($scopes = NULL,$overwrite = false){
		if(!is_null($scopes) && is_array($scopes)){
			foreach ($scopes as $scope) {
				if(!is_null($this->_permissions) && !in_array($scope, $this->_permissions) && strpos($scope, "{APP_NAMESPACE}") === false){
					unset($scopes[$scope]);
				}
			}
			if(!is_null($this->app_namespace)){
				$scope = str_replace("{APP_NAMESPACE}", strtolower($this->app_namespace), $scope);
			}
			if(!is_null($this->scope) && !$overwrite){
				$this->scope .= ",".implode(",", $scopes);
			} else {
				$this->scope = implode(",", $scopes);
			}
		}
	}

	/**
	 * This function is used to set the client id's or if not 
	 * one parameter isn't spepcified this function tries to load it
	 * from the Facebook config file
	 * @param  string $client_id     The Facebook app id
	 * @param  string $client_secret The facebook app secret
	 * @since 1.0
	 * @access public
	 */
	public function client($client_id = NULL,$client_secret = NULL){
		if(!is_null($client_id)){
			$this->client_id = $client_id;
		} else {
			if(is_string($this->_CI->config->item("facebook_client_id"))){
				$this->client_id = $this->_CI->config->item("facebook_client_id");
			}
		}
		if(!is_null($client_secret)){
			$this->client_secret = $client_secret;
		} else {
			if(is_string($this->_CI->config->item("facebook_client_secret"))){
				$this->client_secret = $this->_CI->config->item("facebook_client_secret");
			}
		}
	}

	/**
	 * This function performs the auth api action
	 * @since 1.0	
	 * @access public
	 * @todo Make a build_url function
	 */
	public function auth(){
		self::_state();
		$dialog_url = $this->_oauth_url;
		if(!is_null($this->redirect_uri)){
			$redirect_uri = urlencode($this->redirect_uri);
		} else {
			return FALSE;
		}
		if(is_array($this->scope)){
			$scope = implode(",", $this->scope);
		} else {
			$scope = $this->scope;
		}
		$dialog_url .= "?client_id=".$this->client_id."&redirect_uri=".$redirect_uri."&state=".$this->state;
		if(!is_null($this->display)){
			$dialog_url .= "&display=".$this->display;
		}
		if(!is_null($this->response_type)){
			$dialog_url .= "&response_type=".$this->response_type;
		}
		if(!is_null($this->scope) && count($this->scope) > 0){
			$dialog_url .= "&scope=".$this->scope;
		}
		header("Location: ".$dialog_url);
	}

	/**
	 * This function gets the access token from the
	 * Facebook api based on the $_GET["code"]
	 * returned from the auth request
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	public function callback(){
		if(isset($_GET["code"]) && self::_state()){
			$this->code = $_GET["code"];
			if(!is_null($this->redirect_uri)){
				$redirect_uri = urlencode($this->redirect_uri);
			} else {
				return FALSE;
			}	
			$request_url = $this->_graph_oauth_url."access_token";

			$request_string = "client_id=".$this->client_id."&redirect_uri=".$this->redirect_uri."&client_secret=".$this->client_secret."&code=".$this->code;

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$request_url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$request_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);

			curl_close($ch);

			$json = json_decode($result);
			if(is_null($json)){
				$params = NULL;
				parse_str($result, $params);
				$this->access_token = $params["access_token"];
				$this->expires = $params["expires"];
				return TRUE;
			} else {
				$this->error_message = $json->error->message;
				return FALSE;
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the normal facebook user data
	 * @return object|boolean
	 * @since 1.0
	 * @access public
	 */
	public function user(){
		if(!is_null($this->access_token)){
			$request_url = $this->_graph_url."me/?access_token=".$this->access_token;

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$request_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);
			curl_close($ch);

			$object = json_decode($result);
			if(!is_null($object) && is_object($object) && !property_exists($object, "error")){
				return $object;
			} else {
				$this->error_message = $object->error->message;
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}