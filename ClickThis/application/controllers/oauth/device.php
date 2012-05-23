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
	 * The id of the requesting app
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $app_id = NULL;

	/**
	 * An array containing the requesting scopes
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $scope = NULL;

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
		$this->load->model("oauth/device_token");
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
		$user_code = rand_character(6);
		$device_code = rand_character(32);
		$this->device_token->device_code($device_code, $user_code, $this->app_id, $this->scope);
		$response = array(
			"device_code" => $device_code,
			"user_code" => $user_code,
			"verification_url" => base_url()."device",
			"expires_in" => $this->config->item("oauth_device_auth_time_alive"),
			"interval" => 5
		);
		echo json_encode($response);
	}

	public function enter_code(){

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