<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This library authenticates with Foursquare and
 * it has a function to get user info too
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Foursquare
 * @category Third Party
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Foursquare{

	/**
	 * This is a local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The foursquare OAuth 2.0 endpoint
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_oauth_url = "https://foursquare.com/oauth2/";

	/**
	 * The foursquare API url
	 * @var string
	 * @since 1.0
	 * @access private
	 */
	private $_api_url = "https://api.foursquare.com/v2/";

	/**
	 * The foursqusare API key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The foursquare API secret key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_secret = NULL;

	/**
	 * The redirect uri send to the foursquare api
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * If the response should be "code" or "token"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $response_type = "code";

	/**
	 * The response code from the auth step
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $code = NULL;

	/**
	 * If this parameter is set to true, then 
	 * file_get_contents is used else is cURL used
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $web_request = TRUE;

	/**
	 * The auth display type, values are "webpopup","touch" and "wap"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $display = NULL;

	/**
	 * The returned access token from the callback step
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * The type of access token to recive
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $grant_type = "authorization_code";

	/**
	 * This is the constructor it loads up some helpers and the config
	 * @since 1.0
	 * @access public
	 */
	public function Foursquare(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("api");
		$this->_CI->load->helper("string");
		$this->_CI->load->config("foursquare");
	}

	/**
	 * This function loads the API keys either from the function parameters 
	 * or from the foursquare config file.
	 * @param  string $client_id     The foursquare API key
	 * @param  string $client_secret The foursquare API secret key
	 * @since 1.0
	 * @access public
	 */
	public function client($client_id = NULL,$client_secret = NULL){
		if(!is_null($client_id)){
			$this->client_id = $client_id;
		} else if(is_string($this->_CI->config->item("foursquare_api_key"))){
			$this->client_id = $this->_CI->config->item("foursquare_api_key");
		}
		if(!is_null($client_secret)){
			$this->client_secret = $client_secret;
		} else if(is_string($this->_CI->config->item("foursquare_api_secret"))){
			$this->client_secret = $this->_CI->config->item("foursquare_api_secret");
		}
	}

	/**
	 * This function builds the auth url and redirects the user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function auth(){
		if(!is_null($this->client_id) && !is_null($this->redirect_uri)){
			$request_url = $this->_oauth_url."authenticate?client_id=".$this->client_id."&response_type=".$this->response_type."&redirect_uri=".$this->redirect_uri;
			if(!is_null($this->display)){
				$request_url .= "&display=".$this->display;
			}
			header("Location: ".$request_url);
			return true;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function builds a url based on the parameter input,
	 * the parameter values is taken from this classes properties
	 * @param  array $params An array of the class properties to use
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _buildUrl($params = NULL){
		if(!is_null($params) && is_array($params)){
			$return = "";
			foreach ($params as $key) {
				if(property_exists($this, $key) && !is_null($this->{$key}) && !is_null($key)){
					$return .= $key."=".$this->{$key}."&";
				}
			}
			return rtrim($return,"&");
		} else {
			return "";
		}
	}

	/**
	 * This function gets the access token based on the returned code
	 * from the auth step
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function callback(){
		if(isset($_GET["code"]) && !is_null($this->client_id) && !is_null($this->client_secret) && !is_null($this->redirect_uri)){
			date_default_timezone_set("UTC");
			$this->code = $_GET["code"];
			$request_url = $this->_oauth_url."access_token?".self::_buildUrl(array("client_id","client_secret","grant_type","redirect_uri","code"));
			if($this->web_request){
				$response = self::_webRequest($request_url);
			} else {
				$response = self::_curlRequest($request_url);
			}
			$object = json_decode($response);
			if(isset($object->access_token)){
				$this->access_token = $object->access_token;
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs a get request using file_get_contents
	 * @param  string $url The url to request too
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _webRequest($url){
		$result = file_get_contents($url);
		return $result;
	}

	/**
	 * This function performs a get request using cURL
	 * @return string
	 * @since 1.0
	 * @access private
	 */
	private function _curlRequest($url){
		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/**
	 * This function returns the user information on the authed user
	 * @since 1.0
	 * @access public
	 * @return boolean|object
	 */
	public function user(){
		if(!is_null($this->access_token)){
			$request_url = $this->_api_url."users/self?oauth_token=".$this->access_token."&v=".date("Ymd");
			if($this->web_request){
				$response = self::_webRequest($request_url);
			} else {
				$response = self::_curlRequest($request_url);
			}
			$object = json_decode($response);
			if(isset($object->response->user)){
				return $object->response->user;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}