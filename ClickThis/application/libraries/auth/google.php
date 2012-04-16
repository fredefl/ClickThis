<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used to authenticate with google
 * @package OAuth
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Google
 * @category Login
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Google{

	/**
	 * The google OAuth 2.0 url
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_api_url = "https://accounts.google.com/o/oauth2/";

	/**
	 * The url to be imploded in the scope parameter
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_scope_url = "https://www.googleapis.com/auth/";

	/**
	 * The response type "code" or "token"
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_response_type = "code";

	/**
	 * The client id for the google api
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_client_id = NULL;

	/**
	 * The requested scopes
	 * @var array
	 * @since 1.0
	 * @access private
	 */
	private $_scope = array();

	/**
	 * An optional scope
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_state = NULL;

	/**
	 * The url to redirect the user back too
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_redirect_uri = NULL;

	/**
	 * The access type for the response token/code
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_access_type = "online";

	/**
	 * If the user is going to be promted every time
	 * values are "auto" or "force"
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_approval_prompt = "auto";

	public function Google(){}

	public function parameters($parameters = NULL){
		if(!is_null($parameters) && is_array($parameters)){
			foreach ($parameters as $key => $value) {
				if(property_exists($this, "_".$key)){
					if($key != "scope"){
						$key = "_".$key;
						$this->{$key} = $value;
					} else {
						self::scopes($value);
					}
				}
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function is used to add scopes
	 * @param  array $scopes The requested api scopes
	 * @since 1.0
	 * @access public
	 */
	public function scopes($scopes = NULL){
		if(!is_null($scopes)){
			foreach ($scopes as $scope) {
				$this->_scope[] = $this->_scope_url.$scope;
			}
		}
	}

	/**
	 * This function validates if the
	 * @param  array $parameters The parameters to check
	 * @return boolean
	 */
	private function _check_parameters($parameters = NULL){
		if(!is_null($parameters) && is_array($parameters)){
			$return = true;
			foreach ($parameters as $key) {
				$key = "_".$key;
				if(!property_exists($this, $key) && is_null($key)){
					$return = false;
				}
			}
			return $return;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function is used to set the api client id
	 * @param  string $client_id The google api client id
	 * @since 1.0
	 * @access public
	 */
	public function client_id($client_id = NULL){
		if(!is_null($client_id)){
			$this->_client_id = $client_id;
		}
	}

	/**
	 * This function builds a url based on the method and parameters
	 * @param  string $method     The api method "auth" etc
	 * @param  array $parameters The parameters to use
	 * @return boolean|string
	 * @since 1.0
	 * @access private
	 */
	private function _build_url($method = "auth",$parameters = NULL){
		if(!is_null($parameters) && is_array($parameters)){
			$url = $this->_api_url.$method."?";
			foreach ($parameters as $key) {
				$property = "_".$key;
				if(property_exists($this,$property) && !is_null($this->{$property})){
					if(is_array($this->{$property})){
						$url .= implode(" ", $this->{$property})."&";
					} else {
						$url = $this->{$property}."&";
					}
				}
			}
			return $url;
		} else {
			return FALSE;
		}
	}

	public function auth(){
		if(self::_check_parameters(array("client_id","scope","response_type","redirect_uri"))){
			$request_url = self::_build_url("auth",array("client_id","scope","response_type","redirect_uri","access_type","approval_prompt")));
		} else {
			return false;
		}
	}

	public function auth_call
}