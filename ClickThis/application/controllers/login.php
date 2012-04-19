<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index() {
		// Load View
		if(!isset($_SESSION['UserId'])) {
			$this->load->view('login_view',array("base_url" => base_url(),"cdn_url" => $this->config->item("cdn_url")));
			$content = $this->output->get_output();
			$mini = $this->minify->Html($content);
			$this->output->set_output($mini);
		} else {
			redirect($this->config->item("front_page"));	
		}

	}

	/**
	 * This function logs a user in or creates a new user using Github login
	 * @param  string $page The current operation "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function github($page = "auth"){
		$this->load->library("auth/github");
		$this->load->model("login_model");
		$Github = new Github();
		$Github->client();
		$Github->scope(array("user"));
		if($page == "auth"){
			if($Github->auth()){

			} else{
				redirect($this->config->item("login_page"));
			}
		} else if($page == "callback"){
			if($Github->callback()){
				$Account = $Github->user();
				if($Account !== false){
					$Picture = "https://secure.gravatar.com/avatar/".$Account->gravatar_id;
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Github")){
							$this->login_model->Update($_SESSION["UserId"],"Github",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						if($this->login_model->Github($Account->name,$Account->id,$Account->email,$Picture,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * The google login method auth means that the auth request should
	 * be peformed and callback is after auth
	 * @param  string $page Auth or callback
	 * @since 1.0
	 * @access public
	 */
	public function google($page = "auth"){
		$this->load->library("auth/google");
		$this->load->model("login_model");
		$Google = new Google();
		$Google->client();
		$Google->redirect_uri(base_url()."login/google/callback");
		$Google->scopes(array("userinfo.profile","userinfo.email"));
		$Google->access_type("offline");
		if($page == "auth"){
			$Google->auth();
		} else if($page == "callback"){
			$Google->callback();
			$Account = $Google->account_data();
			if($Account !== false){
				if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
					if(!$this->login_model->User_Exists($Account->id,"Google") && !$this->login_model->User_Exists($Account->email,"Google")){
						$this->login_model->Update($_SESSION["UserId"],"Google",$Account->id);
						if(isset($_SESSION["auth_link_redirect"])){
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							redirect($this->config->item("front_page"));
						}
					} else {
						if(isset($_SESSION["auth_link_redirect"])){
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							$_SESSION["auth_error"] = "User exists";
 							redirect($this->config->item("front_page"));
						}
					}
				} else {
					if($this->login_model->Google($Account->name,$Account->email,$Account->picture,$Account->id,$Account->locale,$UserId)){
						if(!is_null($UserId)){
							$_SESSION["UserId"] = $UserId;
							redirect($this->config->item("front_page"));
						} else {
							redirect($this->config->item("login_page"));
						}
					} else {
						redirect($this->config->item("login_page"));
					}
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * This function logs a user in using Facebook
	 * @param  string $page The current page "auth" or "callback"
	 * @access public
	 * @since 1.0
	 */
	public function facebook($page = "auth"){
		$this->load->library("auth/facebook");
		$this->load->model("login_model");
		$Facebook = new Facebook();
		$Facebook->client();
		$Facebook->redirect_uri = "http://illution.dk/ClickThis/login/facebook/callback"; // Change to use a config file
		if($page == "auth"){
			$Facebook->auth();
		} else {
			$this->load->library('country');
			if($Facebook->callback()){
				$Account = $Facebook->user();
				if($Account !== false){
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Facebook")){
							$this->login_model->Update($_SESSION["UserId"],"Facebook",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						$LocaleArray = explode("_",$Account->locale);
						$Language = $LocaleArray[0];
						$CountryObject = new Country();
						$CountryObject->Find(array("Code" => strtoupper($LocaleArray[1])));
						if(property_exists($Account, "email")){
							$Email = $Account->email;
						} else {
							$Email = NULL;
						}
						if($this->login_model->Facebook($Account->id,$Account->name,$Email,$CountryObject->Name,$Language,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							}	
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}
	
	/**
	 * This function either creates a user with twitter
	 * link a twitter account to the current user 
	 * or logs the user in with the twitter account
	 * @param  string $page The current page "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function twitter($page = "auth"){
		$this->load->library("auth/twitter");
		$this->load->model("login_model");
		$Twitter = new Twitter();
		$Twitter->consumer();
		$Twitter->callback = "http://illution.dk/ClickThis/login/twitter/callback";
		if($page != "callback"){
			if($Twitter->auth() === false){
				header("Location: ".base_url().$this->config->item("login_page")."/twitter/auth");
			}
		} else if($page == "callback"){
			if($Twitter->callback()){
				$Account = $Twitter->user();
				if($Account !== false){
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Twitter")){
							$this->login_model->Update($_SESSION["UserId"],"Twitter",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						$this->load->library('country');
						$CountryObject = new Country();
						$CountryObject->Find(array("Code" => strtoupper($Account->lang)));
						if($this->login_model->Twitter($Account->name,$Account->id,$Account->profile_image_url,$Account->lang,$CountryObject->Name,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}
###############################Click This#######################################			
	public function topt_server(){
		date_default_timezone_set("UTC");
		$Settings = array(
			'Algorithm' => 'sha1',		// The hash algorithm
			'Digits' => '6', 			// The number of digits the output should contain
			'Key' => '12345678901234567890', 	// The secret key
			'Timestamp' => time(), 		// The current time
			'InitialTime' => '0', 		// I dunno
			'TimeStep' => '30', 		// Time in seconds a key should last
			'TimeWindowSize' => '1'		// I dunno	
		);
		
		$this->load->library("onetimepassword");
		// Echo out key
		$this->output->set_output("<meta http-equiv='refresh' content='5'/>TOTP one-time-password: <b>" . OneTimePassword::GetPassword($Settings) . "</b><br>");	
	}

	public function topt_client(){
		$this->load->view("topt");
	}
	
	public function clickthis($Page = NULL,$ErrorString = NULL){
		if(!is_null($Page)){
			switch($Page){
				case "register":{
					redirect("login/clickthis/#register");
				}
				default:{
					$Page = 'clickthis_'.$Page;
					if(function_exists($Page)){
						self::$Page();
					}
					else{
						show_error(404);	
					}
					break;	
				}
			}
		}
		else{
			$this->load->view('clickthis_login_view',array("base_url" => base_url(),"cdn_url" => $this->config->item("cdn_url")));	
		}
	}

	/**
	 * This function is called when the user clicks on the reset password button
	 * @since 1.1
	 * @access public
	 */
	public function ResetPassword(){
		$this->load->config("api");
		$this->load->view("reset_password_view",array("base_url" => base_url(),"api_url" => $this->config->item("api_host_url")));
	}
	
###############################LinkedIn####################################		
	
	//Check if the user is registred etc
	private function linkedin_login($Data){
		$_SESSION['LinkedInLoginId'] = $Data['id']; //Set Session Id Data
		$_SESSION['LinkedInLogin'] = $Data; //Set Session Data
		$Query = $this->db->where(array("LinkedIn" => $Data['id']))->select("Id,Status")->get("Users");
		$this->load->library('country');
		$CountryObject = new Country();
		$CountryObject->Find(array("Code" => strtoupper($Data['code'])));
		$Country = $CountryObject->Name;
		$NumRows = $Query->num_rows();
			if($NumRows) {
				// User exists!
				// Get user Id
				$Id = $Query->row(0)->Id;
				if($Query->row(0)->Status == 1){
					$_SESSION['UserId'] = $Id;
					// Redirect the user
					redirect('token');
				} else {
					redirect($this->confgi->item("login_page"));
				}
			} else {
				// User does not exist
				$Query = $this->db->query('INSERT INTO Users (RealName,UserGroup,LinkedIn,Country,Status) Values(?,?,?,?,?)', 
																					array(
																						$Data['name'],
																						'User',
																						$Data['id'],
																						$Country,
																						1
																					)	
				);
				$_SESSION['UserId'] = $this->db->insert_id(); 
				if(isset($_SESSION['UserId'])){
					redirect('token');
				}else{
					redirect('login/linkedin/callback');
				}
			}
	}
	
	//Authenticate With The users LinkedIn Account
	private function linkedin_auth(){
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		//include("linkedin.php");
		$this->load->helper('linkedin');
		$this->config->load('linkedin',TRUE);
		
		$config['base_url']             =   $this->config->item('linkedin_base_url', 'linkedin');
		$config['callback_url']         =   $this->config->item('linkedin_callback_url', 'linkedin');
		$config['linkedin_access']      =   $this->config->item('linkedin_api_key', 'linkedin');
		$config['linkedin_secret']      =   $this->config->item('linkedin_api_secret', 'linkedin');

		# First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
		$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		//$linkedin->debug = true;
	
		# Now we retrieve a request token. It will be set as $linkedin->request_token
		$linkedin->getRequestToken();
		$_SESSION['requestToken'] = serialize($linkedin->request_token);
	  
		# With a request token in hand, we can generate an authorization URL, which we'll direct the user to
		//echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
		header("Location: " . $linkedin->generateAuthorizeUrl());
	}
	
	//The Data Callback funtion from Auth
	private function linkedin_callback(){
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		//include("helpers/linkedin.php");
		$this->load->helper('linkedin');
		$this->config->load('linkedin',TRUE);
		
		$config['base_url']             =   $this->config->item('linkedin_base_url', 'linkedin');
		$config['callback_url']         =   $this->config->item('linkedin_callback_url', 'linkedin');
		$config['linkedin_access']      =   $this->config->item('linkedin_api_key', 'linkedin');
		$config['linkedin_secret']      =   $this->config->item('linkedin_api_secret', 'linkedin');
	   
		
		# First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
		$linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
		//$linkedin->debug = true;
	
	   if (isset($_REQUEST['oauth_verifier'])){
			$_SESSION['oauth_verifier']     = $_REQUEST['oauth_verifier'];
	
			$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$linkedin->getAccessToken($_REQUEST['oauth_verifier']);
	
			$_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
			header("Location: " . $config['callback_url']);
			exit;
	   }
	   else{
			$linkedin->request_token    =   unserialize($_SESSION['requestToken']);
			$linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
			$linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
	   }
	
	
		# You now have a $linkedin->access_token and can make calls on behalf of the current member
		$xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,location:(country:(code),name),industry)");
		$xml = simplexml_load_string($xml_response);
		function xml2array ( $xmlObject, $out = array () )
		{
				foreach ( (array) $xmlObject as $index => $node )
					$out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
		
				return $out;
		}
		$array = array();
		$array = xml2array($xml,$array);
		foreach($array['location'] as $Name => $Value){
			$array[$Name] = $Value;	
		}
		unset($array['location']);
		foreach($array['country'] as $Name => $Value){
			$array[$Name] = $Value;		
		}
		unset($array['country']);
		$array['country'] = $array['name'];
		unset($array['name']);
		$array['name'] = $array['first-name']." ".$array['last-name'];
		unset($array['last-name']);
		unset($array['first-name']);
		self::linkedin_login($array);
	}
	
	//The Loign Function
	public function linkedin($Parameters = 'auth'){
		//If theres is parameters like callback for login
		if(isset($Parameters)){
			//Check Paramters
			switch($Parameters){
				
				//Login Callback
				case "callback":
				{
					self::linkedin_callback();	
				}
				
				//Auth
				case "auth":
				{
					self::linkedin_auth();	
				}
			}
		}

	}
}
?>