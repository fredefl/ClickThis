<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device extends CI_Controller {

	/**
	 * The requester client id
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The requesters client_secret key
	 * @var string
	 * @since 1.0o
	 * @access public
	 */
	public $client_secret = NULL;

	/**
	 * The id of the current logged in user
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $user_id = NULL;

	/**
	 * The id of the requesting app
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $app_id = NULL;

	/**
	 * The device code/request code
	 * that belongs to that user code
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public static $device_code = NULL;

	/**
	 * An array containing the requesting scopes
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public$scope = NULL;

	/**
	 * The submitted device code
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $code = NULL;

	/**
	 * This function is the constructor it loads the config files
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		$this->load->config("api");
		$this->load->config("oauth");
		$this->load->helper("rand");
		$this->load->model("oauth/client");
		$this->load->model("oauth/token");
		$this->load->model("oauth/device_token");
	}

	/**
	 * This function checks if a user id is specified and is correct
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _user_id(){
		$this->load->model("oauth/user_model");
		if (isset($_SESSION[$this->config->item("oauth_user_id_session_key")]) && !empty($_SESSION[$this->config->item("oauth_user_id_session_key")]) && $this->user_model->user_exists($_SESSION[$this->config->item("oauth_user_id_session_key")])) {
			$this->user_id = $_SESSION[$this->config->item("oauth_user_id_session_key")];
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function handles input security
	 * @param  string $input The input string
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _security($input){
		$output = htmlentities($input);
		$output = htmlspecialchars($input);
		$output = strip_tags($input);
		return $output;
	}

	/**
	 * This function ensures that the input is correct and then the create response function is called
	 * @since 1.0
	 * @access public
	 */
	public function code(){
		if (self::_check_parameters(array("client_id","client_secret","scope")) && self::_client() && self::_scopes()) {
			self::_create_response();
		} else {
			$error = array(
				"error" => "Bad Request"
			);
			echo json_encode($error);
			header("HTTP/1.1 400 Bad Request");
		}
	}

	/**
	 * This function creates the codes, inserts it and outputs it
	 * @since 1.0
	 * @access private
	 */
	private function _create_response(){
		$user_code = rand_character($this->config->item("oauth_device_user_code_length"));
		$device_code = rand_character($this->config->item("oauth_request_code_length"));
		$this->device_token->device_code($device_code, $user_code, $this->app_id, $this->scope);
		$response = array(
			"device_code" => $device_code,
			"user_code" => $user_code,
			"verification_url" => base_url()."device",
			"expires_in" => $this->config->item("oauth_device_auth_time_alive"),
			"interval" => $this->config->item("oauth_device_code_interval")
		);
		echo json_encode($response);
	}

	/**
	 * This function creates and shows the user the auth dialog
	 * @since 1.0
	 * @access private
	 */
	private function _dialog(){
		$_SESSION["auth_token"] = (string)$this->code;
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
			"scopes" => implode(";", $this->scope)
		);
		$this->load->view("auth_view",$data);
	}

	/**
	 * This function handles the device auth screen and the device user code screen
	 * @since 1.0
	 * @access public
	 */
	public function validate_code(){
		if(isset($_POST["auth"]) && isset($_POST["auth_token"]) && isset($_SESSION["auth_token"]) && $_POST["auth_token"] == $_SESSION["auth_token"] && self::_user_id() && $this->device_token->validate($_SESSION["auth_token"], $this->app_id, $this->scope)){
			if($_POST["auth"] == "auth"){
				$this->code = $_SESSION["auth_token"];
				$this->device_token->authenticate($this->code, $this->user_id, $this->app_id, $this->scope);
				header("Location: ".base_url().$this->config->item("front_page"));
			} else {
				$this->device_token->remove($_SESSION["auth_token"]);
				header("Location: ".base_url().$this->config->item("front_page"));
			}
		} else {
			if(self::_check_parameters(array("code")) && self::_user_id() && $this->device_token->validate($this->code, $this->app_id, $this->scope)) {
				if($this->token->is_authenticated($this->app_id, $this->user_id, $this->scope, "offline")){
					$this->device_token->accept_device_code($this->device_token->get_device_code($this->code), $this->user_id);
					header("Location: ".base_url().$this->config->item("front_page"));
				} else {
					self::_dialog();
				}
			} else {
				header("Location: ".base_url().$this->config->item("front_page"));
			}
		}
	}

	/**
	 * This function checks if a user is logged in, if a user is, 
	 * then the user is shown the device code screen.
	 * @since 1.0
	 * @access public
	 */
	public function enter_code(){
		if(self::_user_id()){
			$data = array(
				"base_url" => base_url(),
				"cdn_url" => $this->config->item("cdn_url")
			);
			$this->load->view("device_code_view",$data);
		} else {
			header("Location: ".base_url().$this->config->item("not_logged_in_page"));
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
			return FALSE;
		}
		$this->scope = explode(",", $this->scope);
		sort($this->scope);
		foreach ($this->scope as $key => $scope) {
			if (!in_array($scope, $this->config->item("oauth_auth_scopes"))) {
				unset($this->scope[$key]);
			}
		}
		return (count($this->scope) > 0);
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
					return FALSE;
				} else {
					if(isset($_GET[$parameter]) && !empty($_GET[$parameter])){
						(property_exists($this, $parameter)) ? $this->{$parameter} = self::_security($_GET[$parameter]) : NULL;
					} else if(isset($_POST[$parameter]) && !empty($_POST[$parameter])){
						(property_exists($this, $parameter)) ? $this->{$parameter} = self::_security($_POST[$parameter]) : NULL;
					} else {
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
			return FALSE;
		}
	}
}