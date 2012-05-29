<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This endpoint is used to generate a OAuth 2.0 access token
 * @package Authentication
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage OAuth
 * @category OAuth
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Access_Token extends CI_Controller {

	/**
	 * An error containing autentication errors
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $errors = NULL;

	/**
	 * The request code gotten from the Auth step
	 * @var string
	 * @since 1.o
	 * @access public
	 */
	public $code = NULL;

	/**
	 * The application client id
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The users refresh token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $refresh_token = NULL;

	/**
	 * The application secret key of the requesting application
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_secret = NULL;

	/**
	 * One of the accepted redirect uri's registred with the app
	 * @since 1.0
	 * @var string
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * The OAuth response type,
	 * "authorization_code", "refresh_token" or "http://oauth.net/grant_type/device/1.0"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $grant_type = NULL;

	/**
	 * The tokens access type
	 * offline or online
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_type = NULL;

	/**
	 * The id of the app that owns the access token that is going to be created
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $app_id = NULL;

	/**
	 * The user id that the access token gives access too
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $user_id = NULL;

	/**
	 * An array containing the access scopes of the access token
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $scope = NULL;

	/**
	 * The generated access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * This function is the constructor, it loads up some helpers and the api config
	 * @since 1.0
	 * @access public
	 */
	public function __construct () {
		parent::__construct();
		$this->load->config("api");
		$this->load->helper("rand");
		$this->load->model("oauth/client");
		$this->load->model("oauth/token");
		$this->load->model("oauth/device_token");
		$this->load->config("oauth");
	}

	/**
	 * This function is called when a user requests this endpoints
	 * @since 1.0
	 * @access public
	 */
	public function index () {
		if (strtolower($_SERVER['REQUEST_METHOD']) == "post") {
			if (self::_check_parameters(array("grant_type"))) {

				switch ($this->grant_type) {
					case 'authorization_code':
							self::_authorization_code();
						break;

					case 'refresh_token':
							self::_refresh_token();
						break;

					case 'http://oauth.net/grant_type/device/1.0':
							self::_device();
						break;
					
					default:
						$this->errors[] = "Not a valid grant type";
						self::_error();
						break;
				}
			} else {
				self::_error();
			}
		} else {
			$this->errors[] = "Only POST is allowed";
			self::_error();
			header("HTTP/1.1 405 Method Not Allowed");
		}
	}


	/**
	 * This function outputs errors
	 * @since 1.0
	 * @access private
	 */
	private function _error(){
		if(property_exists($this, "errors") && !is_null($this->errors)){
			$error = array(
				"errors" => $this->errors
			);
			echo json_encode($error);
		} else {
			$error = array(
				"error_code" => 500,
				"error_message" => "Internal Server Error" 
			);
			echo json_encode($error);
		}
	}

	/**
	 * This function gets the information, from the database on the request code
	 * @since 1.0
	 * @access private
	 */
	private function _request_code(){
		$this->token->get_information_by_request_code($this->code, $this->app_id, $this->user_id, $this->scope, $this->access_type);
	}

	/**
	 * This function returns if it's a valid request code that is being used
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _code(){
		return (!is_null($this->code) && $this->token->is_valid_request_code($this->code));
	}

	/**
	 * This function inserts and outputs the access token
	 * @param  string $refresh_token The refresh token, that are generated
	 * @since 1.0
	 * @access private
	 */
	private function _access_token($refresh_token = NULL){
		if ($this->token->create_token($this->access_token, $this->app_id, $this->user_id, $this->grant_type, $this->scope, $this->access_type, $this->refresh_token)) {
			$return  = array(
				"access_token" => $this->access_token, 
				"expires_in" => $this->config->item("oauth_access_token_time_alive"),
				"token_type" => "Bearer"
			);
			if ($this->access_type == "offline" && !is_null($this->refresh_token) && !is_null($refresh_token) && $this->refresh_token == $refresh_token){
				$return["refresh_token"] = $this->refresh_token;
			}
			echo json_encode($return);
		} else {
			$this->errors[] = "The deffined parameters is wrong";
			self::_error();
			header("HTTP/1.1 503 Service Unavailable");
		}
	}

	/**
	 * The function is handling the authorization code grant type
	 * @since 1.0
	 * @access private
	 */
	private function _authorization_code () {
		if (self::_check_parameters(array("client_id", "client_secret", "code", "redirect_uri")) && self::_client() && self::_redirect_uri() && self::_code()) {
			self::_request_code();
			$this->access_token = rand_character($this->config->item("oauth_access_token_length"));
			$refresh_token = rand_character(32);
			$this->refresh_token = $refresh_token;
			self::_remove_request_code();
			self::_access_token($refresh_token);
		} else {	
			$this->errors[] = "The request code is incorrect";
			self::_error();
		}
	}

	/**
	 * This function handles the refresh token grant type
	 * @since 1.0
	 * @access private
	 */
	private function _refresh_token () {
		if(self::_check_parameters(array("refresh_token", "client_id", "client_secret")) && self::_client() && $this->token->is_valid_refresh_token($this->refresh_token)){
			$this->access_token = rand_character($this->config->item("oauth_access_token_length"));
			$this->grant_type = "authorization_code";
			$this->token->get_information_by_refresh_token($this->refresh_token, $this->app_id, $this->user_id, $this->scope);
			self::_access_token();
		} else {
			self::_error();
		}
	}

	/**
	 * This function removes the used request code
	 * @since 1.0
	 * @access private
	 */
	private function _remove_request_code(){
		$this->token->remove_request_code($this->code);
	}

	/**
	 * This function removes the used device code
	 * @since 1.0
	 * @access private
	 */
	private function _remove_device_code(){
		$this->token->remove_device_code($this->code);
		$this->device_token->remove_last_request($this->code);
	}

	/**
	 * This function returns if it's a valid request code that is being used
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _valid_device_code(){
		return (!is_null($this->code) && $this->token->is_valid_device_code($this->code));
	}

	/**
	 * This function gets the information, from the database on the request code
	 * @since 1.0
	 * @access private
	 */
	private function _device_code(){
		$this->token->get_information_by_device_code($this->code, $this->app_id, $this->user_id, $this->scope);
	}

	/**
	 * This function handles the device grant type
	 * @since 1.0
	 * @access private
	 */
	private function _device () {
		if(self::_check_parameters(array("client_id", "client_secret", "code")) && self::_client()){
			if($this->token->device_code_exists( $this->code )){
				if($this->device_token->device_code_interval($this->code)){
					if(self::_valid_device_code()){
						self::_device_code();
						$this->access_type = "offline";
						$this->access_token = rand_character($this->config->item("oauth_access_token_length"));
						$refresh_token = rand_character($this->config->item("oauth_refresh_token_length"));
						$this->refresh_token = $refresh_token;
						self::_remove_device_code();
						self::_access_token();
					} else {
						$this->device_token->set_request_time($this->code);
						echo json_encode(array("error" => "authorization_pending"));
						die();
					}
				} else {
					echo json_encode(array("error" => "slow_down"));
					die();
				}
			} else {
				echo json_encode(array("error" => "not_found"));
				die();
			}
		} else {
			$this->errors[] = "The device_code is incorrect";
			self::_error();
		}
	}

	/**
	 * This function handles input security
	 * @param  string $input The input string
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _security ( $input ) {
		$output = htmlentities($input);
		$output = htmlspecialchars($input);
		$output = strip_tags($input);
		return $output;
	}

	/**
	 * This function ensures that the required parameters are set
	 * @param  array $parameters The parameters to check for
	 * @param array $optionals A list of optional parameters
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _check_parameters ($parameters = NULL,$optionals = NULL) {
		if (!is_null($parameters)) {
			foreach ($parameters as $parameter) {
				if (!isset($_GET[$parameter]) && !isset($_POST[$parameter])) {
					$this->errors[] = $parameter." is missing";
					return FALSE;
				} else {
					if(isset($_GET[$parameter]) && !empty($_GET[$parameter])){
						(property_exists($this, $parameter)) ? $this->{$parameter} = self::_security($_GET[$parameter]) : NULL;
					} else if(isset($_POST[$parameter]) && !empty($_POST[$parameter])){
						(property_exists($this, $parameter)) ? $this->{$parameter} = self::_security($_POST[$parameter]) : NULL;
					} else {
						$this->errors[] = $parameter." is incorrect";
						return FALSE;
					}
				}
			}
			if (!is_null($optionals)) {
				foreach ($optionals as $optional) {
					if (isset($_GET[$optional])) {
						(property_exists($this, $optional)) ? $this->{$optional} = $_GET[$optional] : NULL;
					}
				}
			}
			return TRUE;
		} else {
			$this->errors[] = "No parameters is deffined";
			return FALSE;
		}
	}

	/**
	 * This function checks if the client id is corrrect
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _client () {
		if(!is_null($this->client_id) && $this->client->validate($this->client_id,$this->client_secret,$app_id)){
			$this->app_id = $app_id;
			return TRUE;
		} else {
			$this->errors[] = "Client credidentials is wrong";
			return FALSE;
		}
	}

	/**
	 * This function matches if the redirect uri is correct
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _redirect_uri () {
		if (!is_null($this->app_id)) {
			$result = FALSE;
			$redirect_uris = explode(",", $this->client->auth_endpoint($this->app_id));
			foreach ($redirect_uris as $redirect_uri) {
				if (strpos(strtolower($this->redirect_uri), strtolower($redirect_uri)) !== false) {
					$result = TRUE;
				} 
			}
			if($result){
				return TRUE;
			} else {
				$this->errors[] = "Redirect uri mismatch";
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}