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
	 * The optional state parameter
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $state = NULL;

	/**
	 * This function is the constructor, it loads up some helpers and the api config
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		$this->load->helper("array_xml");
		$this->load->config("api");
	}

	/**
	 * This function ensures that the required parameters are set
	 * @param  array $parameters The parameters to check for
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _check_parameters($parameters = NULL){
		if(!is_null($parameters)){
			foreach ($parameters as $parameter) {
				if(!isset($_GET[$parameter]) && !isset($_POST[$parameter])){
					$this->errors[] = $parameter." is missing";
					return FALSE;
				} else {
					(property_exists($this, $parameter)) ? $this->{$parameter} = $_GET[$parameter] : NULL;
				}
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function index(){
		if(self::_check_parameters(array("client_id","response_type","redirect_uri"))){

		} else {
			print_r($this->errors);
		}
	}

	private function _response_code(){

	}

	private function _response_token(){

	}
}