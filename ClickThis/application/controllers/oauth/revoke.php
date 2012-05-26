<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This endpoint is used to revoke the applications access to a user
 * @package Authentication
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage OAuth
 * @category OAuth
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Revoke extends CI_Controller {

	/**
	 * The refresh token to identify the app to revoke
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $refresh_token = NULL;

	/**
	 * The access token to identify what app tor ervoke
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * The id of the user that the token gives access too
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $user_id = NULL;

	/**
	 * The id of the app that owns the token to revoke
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $app_id = NULL;	

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
	 * This function revokes the user access by a access token
	 * @since 1.0
	 * @access private
	 */
	private function _revoke_by_access_token(){
		$this->token->deautherize_app($this->access_token, NULL);
	}

	/**
	 * This function revokes the user access by a refresh token
	 * @since 1.0
	 * @access private
	 */
	private function _revoke_by_refresh_token(){
		$this->token->deautherize_app(NULL, $this->refresh_token);
	}

	/**
	 * This funciton is called when a request is send to this endpoint,
	 * it validates the input and revokes the token
	 * @since 1.0
	 * @access public
	 */
	public function index () {
		if(self::_isset("access_token")){
			if($this->token->is_valid_access_token($this->access_token)){
				self::_revoke_by_access_token();
			} else {
				if($this->token->access_token_exists($this->access_token)){
					echo json_encode(array("error" => "too_old"));
					die();
				} else {
					echo json_encode(array("error" => "invalid_token"));
					die();
				}
			}
		} else if (self::_isset("refresh_token")) {
			if($this->token->is_valid_refresh_token($this->refresh_token)){
				self::_revoke_by_refresh_token();
			} else {
				echo json_encode(array("error" => "invalid_token"));
				die();
			}
		} else {
			$error = array(
				"error" => "bad_request"
			);
			echo json_encode($error);
			header("HTTP/1.1 400 Bad Request");
			die();
		}
		echo json_encode(array("status" => "ok"));
		header("HTTP/1.1 200 OK");
	}
}