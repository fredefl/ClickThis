<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class TokenInfo extends CI_Controller {

	/**
	 * The access token to validate
	 * @since 1.0
	 * @access public
	 * @var string
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
		$this->load->model("oauth/token");
		$this->load->model("oauth/client");
		$this->load->config("oauth");
	}

	/**
	 * This function checks if a input parameter is specified,
	 * if it is true is returned and the corresponding class property
	 * is filled with that valud
	 * @param  string $parameter The input parameter to check for
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _isset ( $parameter ) {
		if (isset($_GET[$parameter]) && !empty($_GET[$parameter])) {
			(property_exists($this, $parameter))? $this->{$parameter} = $_GET[$parameter]: NULL;
			return TRUE;
		} else if (isset($_POST[$parameter]) && !empty($_POST[$parameter])) {
			(property_exists($this, $parameter))? $this->{$parameter} = $_POST[$parameter]: NULL;
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function gets the token information or returns an error
	 * @since 1.0
	 * @access public
	 */
	public function index(){
		if(self::_isset("access_token") && $this->token->access_token_exists($this->access_token)){
			if($this->token->is_valid_access_token($this->access_token)){
				$this->token->token_info($this->access_token, $app_id, $user_id, $scope, $expires_in);
				$this->client->get_client_id($app_id, $client_id);
				$return = array(
					"client_id" => $client_id,
					"user_id" => $user_id,
					"scope" => $scope,
					"expires_in" => $expires_in
				);
				echo json_encode($return);
			} else {
				echo json_encode(array("invalid_token"));
			}
		} else {
			$error = array(
				"error" => "invalid_token"
			);
			echo json_encode($error);
			header("HTTP/1.1 400 Bad Request");
			die();
		}
	}
}