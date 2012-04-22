<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used to authenticate with Instagram and get basic profile infomation
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Instagram
 * @category Authentication
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Instagram{

	/**
	 * The url to the instagram OAuth endpoint
	 * @since 1.0
	 * @access private
	 * @var string
	 */
	private $_oauth_url = "https://api.instagram.com/oauth/";

	/**
	 * The Instagram client id api key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_id = NULL;

	/**
	 * The Instagram client secret api key
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $client_secret = NULL;

	/**
	 * The redirect uri, for your application callback
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $redirect_uri = NULL;

	/**
	 * The type of the response, "code" or "token"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $response_type = "code";

	/**
	 * The scopes that your access token,
	 * will be able to view
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $scope = NULL;

	/**
	 * This paramter can contain "touch",
	 * if it does Instagram will show the user an
	 * mobile optimized version of their auth screen
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $display = NULL;

	/**
	 * When the callback has been succesfull,
	 * this will contain the user object,
	 * taken from the callback steo
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public $user = NULL;

	/**
	 * If an error occur this parameter will contain the error,
	 * etc "access_denied"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $error = NULL;

	/**
	 * If an error occur this will contain the reason why the error occured
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $error_reason = NULL;

	/**
	 * If an error occur this will contain the description,
	 * of the error reason
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $error_description = NULL;

	/**
	 * The response code from the auth step
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $code = NULL;

	/**
	 * The type of access token you wish,
	 * this parameter is only if the api changes,
	 * right now the only values is "authorization_code"
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $grant_type = "authorization_code";

	/**
	 * When the callback function has returned succeslfull then this
	 * property will contain the Instagram api access token
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $access_token = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Instagram(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("instagram");
		$this->_CI->load->helper("array_data");
	}

	/**
	 * This function is use to set the client parameters,
	 * if the parameters isn't set then the Client keys are loaded
	 * from the instagram config file
	 * @param  string $client_id     The instagram public api key
	 * @param  string $client_secret The instagram api secret key
	 * @since 1.0
	 * @access public
	 */
	public function client($client_id = NULL,$client_secret = NULL){
		if(!is_null($client_id)){
			$this->client_id = $client_id;
		} else if($this->_CI->config->item("instagram_client_id")){
			$this->client_id = $this->_CI->config->item("instagram_client_id");
		}
		if($client_secret){
			$this->client_secret = $client_secret;
		} else if(is_string($this->_CI->config->item("instagram_client_secret"))){
			$this->client_secret = $this->_CI->config->item("instagram_client_secret");
		}
	}

	/**
	 * Use this function to set the scope parameter.
	 * This function validates if your requested scopes
	 * are available and it implodes the parameter as a string,
	 * imploded with a "+" sign.
	 * @param  array $scopes The scope you wish to request
	 * @since 1.0
	 * @access public
	 */
	public function scope($scopes = NULL){
		if(!is_null($scopes) && is_array($scopes)){
			$allowed_scopes = array("basic","comments","relationships","likes");
			foreach ($scopes as $scope) {
				if(!in_array($scope, $allowed_scopes)){
					unset($scopes[$scope]);
				}
			}
			if(count($scopes) > 0){
				$this->scope = implode("+", $scopes);
			}
		}
	}

	/**
	 * This function build the authentication url and redirects the user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function auth(){
		if(!is_null($this->client_id)){
			$request_url = $this->_oauth_url."authorize/?client_id=".$this->client_id;
			if(!is_null($this->redirect_uri)){
				$request_url .= "&redirect_uri=".$this->redirect_uri;
			}
			if(!is_null($this->response_type)){
				$request_url .= "&response_type=".$this->response_type;
			}
			if(!is_null($this->scope)){
				if(is_array($this->scope)){
					self::scope($this->scope);
				}
				$request_url .= "&scope=".$this->scope;
			}
			if(!is_null($this->display)){
				$request_url .= "&display=".$this->display;
			}
			header("Location: ".$request_url);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function performs the auth callback step,
	 * it request an access token which if success will
	 * be filled into $this->access_token and the user object if success
	 * will be filled into $this->user
	 * @return boolean
	 * @since 1.0
	 * @access public
	 */
	public function callback(){
		if(isset($_GET["code"]) && !is_null($this->client_id) && !is_null($this->client_secret)){
			$this->code = $_GET["code"];
			$request_url = $this->_oauth_url."access_token";

			$params = array(
				"client_id" => $this->client_id,
				"client_secret" => $this->client_secret,
				"grant_type" => $this->grant_type,
				"code" => $this->code
			);
			if(!is_null($this->redirect_uri)){
				$params["redirect_uri"] = $this->redirect_uri;
			}

			$post_string = assoc_implode("=","&",$params);

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$request_url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);

			curl_close($ch);
			
			$object = json_decode($result);
			if(isset($object->access_token)){
				$this->access_token = $object->access_token;
				$this->user = $object->user;
				return TRUE;
			} else {
				if(isset($object->error_type)){
					$this->error = $object->code;
					$this->error_reason = $object->error_type;
					$this->error_description = $object->error_message;
				}
				return FALSE;
			}
		} else {
			if(isset($_GET["error"])){
				$this->error = $_GET["error"];
				$this->error_reason = $_GET["error_reason"];
				$this->error_description = $_GET["error_description"];
			}
			return FALSE;
		}
	}
}