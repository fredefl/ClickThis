<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends CI_Controller {

	/**
	 * The requesters client id
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * An array containing all the errors occured
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $errors = NULL;

	/**
	 * The HTTP error code to send
	 * @since 1.0
	 * @access public
	 * @var integer
	 */
	public $error_code = 200;

	/**
	 * The optional state parameter
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $state = NULL;

	/**
	 * The response type "token" or "code"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $response_type = NULL;

	/**
	 * The redirect url of the client after authentication
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * The access scope the requester requests
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $scope = NULL;

	/**
	 * If the refresh token can regenerate a access token, while the user
	 * is not within the authentication flow
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_type = "online";

	/**
	 * The id of the application which is autherizing
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $app_id = NULL;

	/**
	 * The id of the current logged in user
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $user_id = NULL;

	/**
	 * If this is set too auto and the user has authenticated
	 * with the same scopes before, then the auth screen is passed
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $approval_prompt = "auto";

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
					(property_exists($this, $parameter)) ? $this->{$parameter} = $_GET[$parameter] : NULL;
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
			return FALSE;
		}
	}

	/**
	 * This function checks if the requested scopes are available
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _scopes () {
		if(is_null($this->scope)){
			$this->errors[] = "No scopes selected";
			return FALSE;
		}
		$this->scope = explode(",", $this->scope);
		sort($this->scope);
		foreach ($this->scope as $key => $scope) {
			if (!in_array($scope, $this->config->item("oauth_auth_scopes"))) {
				unset($this->scope[$key]);
			}
		}
		if (count($this->scope) > 0) {
			return TRUE;
		} else {
			$this->errors[] = "Scopes are mismatching";
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
			$redirect_uri = $this->client->auth_endpoint($this->app_id);
			if (strpos(strtolower($this->redirect_uri), strtolower($redirect_uri)) !== false) {
				return TRUE;
			} else {
				$this->errors[] = "Redirect uri mismatch";
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if the client id is corrrect
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _client_id () {
		if(!is_null($this->client_id) && $this->client->validate($this->client_id,NULL,$app_id)){
			$this->app_id = $app_id;
			return TRUE;
		} else {
			$this->errors[] = "Client id mismatch";
			return FALSE;
		}
	}

	/**
	 * This function checks if a user id is specified and is correct
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _user_id(){
		/*$this->load->model("oauth/user");
		if (isset($_SESSION[$this->config->item("oauth_user_id_session_key")]) && !empty($_SESSION[$this->config->item("oauth_user_id_session_key")]) && $this->user->user_exists($_SESSION[$this->config->item("oauth_user_id_session_key")])) {
			$this->user_id = $_SESSION[$this->config->item("oauth_user_id_session_key")];
			return TRUE;
		} else {
			return FALSE;
		}*/
		$this->user_id = 1;
		return TRUE;
	}

	/**
	 * This function is called when a user enters the authentication page
	 * @since 1.0
	 * @access public
	 */
	public function index () {
		$this->load->model("oauth/token");
		if (self::_check_parameters(array("client_id","response_type","redirect_uri","scope"),array("access_type","state","approval_prompt")) && self::_scopes() && self::_client_id() && self::_redirect_uri() && self::_user_id()) {
			if (isset($_POST["auth"]) && isset($_POST["auth_token"]) && isset($_SESSION["auth_token"]) && $_POST["auth_token"] == $_SESSION["auth_token"]) {
				unset($_SESSION["auth_token"]);
				if($_POST["auth"] == "auth"){
					self::_autheticate();
				} else {
					self::_user_canceled();
				}
			} else {
				if (($this->approval_prompt == "auto" && !$this->token->is_authenticated($this->app_id, $this->user_id, $this->scope, $this->access_type)) || $this->approval_prompt == "force") {
					self::_dialog();
				} else {
					self::_autheticate();
				}
			}
		} else {

			//Add error redirect
			print_r($this->errors);
		}
	}

	/**
	 * This function is called when the user cancelled the authentication 
	 * process
	 * @since 1.0
	 * @access private
	 */
	private function _user_canceled(){
		header("Location: ".$this->redirect_uri."#error=access_denied");
	}

	/**
	 * This function is called when the user has authenticated an app
	 * @since 1.0
	 * @access private
	 */
	private function _autheticate () {
		$this->token->authenticate($this->app_id, $this->user_id, $this->scope, $this->access_type);
		if ($this->response_type == "token") {
			self::_response_token();
		} else if ($this->response_type == "code") {
			self::_request_code();
		}
	}

	/**
	 * This function gathers a description on each of the
	 * selected scopes
	 * @return array
	 * @since 1.0
	 * @access private
	 */
	private function _scopes_description(){
		$return = array();
		$scopes = $this->config->item("oauth_scope_description");
		foreach ($this->scope as $scope) {
			if(array_key_exists($scope, $this->config->item("oauth_scope_description"))){
				$return[] = $scopes[$scope];
			}
		}
		return $return;
	}

	/**
	 * This function shows the authentication dialog
	 * @since 1.0
	 * @access private
	 */
	private function _dialog( ) {
		$_SESSION["auth_token"] = rand_character(10);
		$this->load->library("App");
		$App = new App();
		$App->Load($this->app_id);
		$data = array(
			"base_url" => base_url(),
			"auth_token" => $_SESSION["auth_token"],
			"cdn_url" => $this->config->item("cdn_url"),
			"app_image" => $App->icon_url,
			"app_description" => $App->description,
			"app_name" => $App->name,
			"app_url" => $App->app_url,
			"scopes" => implode(";", self::_scopes_description())
		);
		$this->load->view("auth_view",$data);
	}

	/**
	 * This function generates a request code and insert it,
	 * and redirects the user
	 * @since 1.0
	 * @access private
	 */
	private function _request_code () {
		$this->load->model("oauth/token");
		$code = rand_character(16);
		if ($this->token->request_code($code, $this->app_id, $this->user_id, $this->scope, $this->access_type)) {
			$url = $this->redirect_uri."?code=".$code;
			if(!is_null($this->state)){
				$url .= "&state=".$this->state;
			}
			header("Location: ". $url);
		} else {

			//Add error redirect
			$this->errors[] = "Something is not matching";
			print_r($this->errors);
		}
	}

	/**
	 * This function creates and inserts the newly created access token
	 * and redirects the user
	 * @since 1.0
	 * @access private
	 */
	private function _response_token () {
		$this->load->model("oauth/token");
		$access_token = rand_character(32);
		if ($this->token->create_token($access_token, $this->app_id, $this->user_id, "token", $this->scope, "online", $refresh_token)){
			$url = $this->redirect_uri . "#access_token=".$access_token."&token_type=Bearer&expires_in=".$this->config->item("oauth_access_token_time_alive");
			if(!is_null($this->state)){
				$url .= "&state=".$this->state;
			}
			header("Location: " . $url);
		} else {

			//Add error redirect
			$this->errors[] = "Something is not matching";
			print_r($this->errors);
		}
	}
}