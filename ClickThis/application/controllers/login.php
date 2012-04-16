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

######################################################Providers#########################################################

	public function googletest(){
		$this->load->config("google");
		$auth_url = "https://accounts.google.com/o/oauth2/auth";
		$scope_url = "https://www.googleapis.com/auth/";
		$parameters = array(
			"response_type" => "code",
			"client_id" => $this->config->item("google_client_id"),
			"redirect_uri" => "http://illution.dk/ClickThis/login/google/callback", //Load this from the config
			"scope" => array("userinfo.profile","userinfo.email"),
			"state" => "callback",
			"access_type" => "online",
			"approval_prompt" => "auto"
		);
		foreach ($parameters["scope"] as $key => $scope) {
			$parameters["scope"][$key] = $scope_url.$scope;
		}
		$request_url = $auth_url."?";
		foreach ($parameters as $key => $value) {
			if(is_array($value)){
				$request_url .= $key."=".implode(" ", $value)."&";
			} else {
				$request_url .= $key."=".$value."&";
			}
		}
		$request_url = rtrim($request_url,"&");
		header("Location: ".$request_url);
		/*// Request parameters
        $google_discover_url         = "https://accounts.google.com/o/openid2/auth";
        $openid_mode                 = "checkid_setup";
        $openid_ns                     = "http://specs.openid.net/auth/2.0";
        $openid_return_to             = "https://localhost/codeigniter/account/signin_return";
        $openid_assoc_handle         = "ABSmpf6DNMw";
        $openid_claimed_id             = "http://specs.openid.net/auth/2.0/identifier_select";
        $openid_identity             = "http://specs.openid.net/auth/2.0/identifier_select";
        $openid_realm                 = "http://illution.dk/ClickThis/";
        // PAPE extension
        $openid_ns_pape             = "http://specs.openid.net/extensions/pape/1.0";
        $openid_pape_max_auth_age     = "300"; // 5 mins
        // User interface extension
        $openid_ui_ns                 = "http://specs.openid.net/extensions/ui/1.0";
        $openid_ui_mode             = "popup";
        $openid_ui_icon             = "true";
        // Attribute exchange extension
        $openid_ns_ax                = "http://openid.net/srv/ax/1.0";
        $openid_ax_mode                = "fetch_request";
        $openid_ax_required            = "country,email,firstname,language,lastname";
        $openid_ax_type_country        = "http://axschema.org/contact/country/home";
        $openid_ax_type_email        = "http://axschema.org/contact/email";
        $openid_ax_type_firstname    = "http://axschema.org/namePerson/first";
        $openid_ax_type_language    = "http://axschema.org/pref/language";
        $openid_ax_type_lastname    = "http://axschema.org/namePerson/last";
        
        // Send discovery request to obtain the Google login authentication endpoint
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // See http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $google_discover_url);
        $content = curl_exec ($ch);
        curl_close ($ch);
        print_r($content);
        /*$xml = simplexml_load_string($content);
        $google_endpoint = (string)$xml->XRD->Service->URI;
        
        // Send login authentication request to the Google endpoint address
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); // See http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_URL, $google_endpoint.
            '?openid.mode='.$openid_mode.
            '&openid;.ns='.$openid_ns.
            '&openid;.return_to='.$openid_return_to.
            '&openid;.assoc_handle='.$openid_assoc_handle.
            '&openid;.claimed_id='.$openid_claimed_id.
            '&openid;.identity='.$openid_identity.
            '&openid;.realm='.$openid_realm.
            '&openid;.ns.pape='.$openid_ns_pape.
            '&openid;.pape.max_auth_age='.$openid_pape_max_auth_age.
            '&openid;.ui.ns='.$openid_ui_ns.
            '&openid;.ui.mode='.$openid_ui_mode.
            '&openid;.ui.icon='.$openid_ui_icon.
            '&openid;.ns.ax='.$openid_ns_ax.
            '&openid;.ax.mode='.$openid_ax_mode.
            '&openid;.ax.required='.$openid_ax_required.
            '&openid;.ax.type.country='.$openid_ax_type_country.
            '&openid;.ax.type.email='.$openid_ax_type_email.
            '&openid;.ax.type.firstname='.$openid_ax_type_firstname.
            '&openid;.ax.type.language='.$openid_ax_type_language.
            '&openid;.ax.type.lastname='.$openid_ax_type_lastname
        );
        $content = curl_exec ($ch);
        curl_close ($ch);
        print_r($content);*/
	}

###############################Google#################################	
	/*public function google($Page = NULL) {
		if(is_null($Page)){
			// If you have logged in with Google
			if(isset($_SESSION['GoogleLogin'])) {
				// Proceed
				// Get user data
				$GoogleLoginData = $_SESSION['GoogleLogin'];
				// Find out if the user exists in the database
				$Query = $this->db->select("Id,Status")->where(array("Google" => $GoogleLoginData['Email']))->get("Users");
				$NumRows = $Query->num_rows();
				// Check for user existance
				if($NumRows) {
					// User exists!
					// Get user Id
					$Id = $Query->row(0)->Id;
					// Set the users Id in a session
					if($Query->row(0)->Status == 1){
						$_SESSION['UserId'] = $Id;
						// Redirect the user
						redirect('token');
					} else {
						redirect($this->confgi->item("login_page"));
					}
				} else {
					// User does not exist
					$Query = $this->db->query('Insert Into Users (RealName,UserGroup,Google,Status,Email) Values(?,?,?,?,?)', array(
																							$GoogleLoginData['Name'],
																							'User',
																							$GoogleLoginData['Email'],
																							1,
																							$GoogleLoginData['Email']
																							)
					
					);
					redirect('login/google');
				}
			} else {
				// Show an error
				$this->output->set_output("Error");
			}
		}
		else{
			switch($Page){
				case "login":
					self::google_login();
				break;
				case "register":
					self::google_register();
				break;
				case "userstatus":
					self::google_userstatus();
				break;
				case "callback":
					self::google_callback();
				break;
				default:
					self::google_login();
				break;
			}
		}
	}*/

	public function google($page = "callback"){
		$this->load->config("google");
		if($page === "callback" && isset($_GET["code"])){
			$fields = array(
	            'code'=> $_GET["code"],
	            'client_id'=> $this->config->item("google_client_id"),
	            'client_secret'=> $this->config->item("google_client_secret"),
	            'redirect_uri'=> "http://illution.dk/ClickThis/login/google/callback",
	            'grant_type'=> "authorization_code",
        	);

        	$fields_string = "";
        	$url = "https://accounts.google.com/o/oauth2/token";

			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }

			$fields_string = rtrim($fields_string,"&");
			//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
			curl_setopt($ch,CURLOPT_POST,count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

			//execute post
			$result = curl_exec($ch);

			$data = json_decode($result);

			curl_close($ch);
			self::_google_get_information($data["access_token"]);
		}
	}

	private function _google_get_information($access_token){
		    $url = "https://www.googleapis.com/oauth2/v1/userinfo";

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$access_token));

			$result = curl_exec($ch);
			//print_r($result);
			curl_close($ch);
	}
	
###############################Facebook####################################	
	public function facebook(){
		$this->load->model('Facebook_model');
		$fb_data = $this->session->userdata('fb_data');
		if(!$fb_data['me']) {
			// Not logged in
			header("Location: {$fb_data['loginUrl']}");
		} else {
				// If logged in
				$data = array(
							'fb_data' => $fb_data,
							);
			//$this->load->view('facebook_test', $data);
			$Locale = array();
			$Locale = explode("_",$fb_data['me']['locale']);
			$this->load->library('country');
			$CountryObject = new Country();
			$CountryObject->Find(array("Code" => strtoupper($Locale[1])));
			$Country = $CountryObject->Name;
			$Language = $Locale[0].$Locale[1];
			
			// Find out if the user exists in the database
			$Query = $this->db->select("Id,Status")->where(array("Facebook" => $fb_data['me']['id']))->limit(1)->get("Users");
			$NumRows = $Query->num_rows();
			// Check for user existance
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
				$Query = $this->db->query('Insert Into Users (RealName,UserGroup,Facebook,Status) Values(?,?,?,?)', array(
																						$fb_data['me']['name'],
																						'User',
																						$fb_data['me']['id'],
																						1
																						));
				redirect('login/facebook');
			}
		}
	}

###############################Twitter#####################################	
	
	public function twitter_auth(){
			// It really is best to auto-load this library!
			$this->load->library('tweet');
			$this->load->library('dataconverter');;
			
			// Enabling debug will show you any errors in the calls you're making, e.g:
			$this->tweet->enable_debug(TRUE);
			
			// If you already have a token saved for your user
			// (In a db for example) - See line #37
			// 
			// You can set these tokens before calling logged_in to try using the existing tokens.
			// $tokens = array('oauth_token' => 'foo', 'oauth_token_secret' => 'bar');
			// $this->tweet->set_tokens($tokens);
			
			
			if ( !$this->tweet->logged_in() )
			{
				// This is where the url will go to after auth.
				// ( Callback url )
				
				$this->tweet->set_callback(site_url('login/twitter/callback'));
				
				// Send the user off for login!
				$this->tweet->login();
			}
			else
			{
				// You can get the tokens for the active logged in user:
				// $tokens = $this->tweet->get_tokens();
				$tokens = $this->tweet->get_tokens();
				
				$user = $this->tweet->call('get', 'account/verify_credentials');
				$userarray = $this->dataconverter->object_to_array($user);
				// 
				// These can be saved in a db alongside a user record
				// if you already have your own auth system.
			}
	}
	
	public function twitter_login($Data){
		$_SESSION['TwitterLoginId'] = $Data['Id'];
		$_SESSION['TwitterLogin'] = $Data;
		$Query = $this->db->select("Id,Status")->where(array("Twitter" => $Data['Id']))->get("Users");
		$NumRows = $Query->num_rows();
		$this->load->library('country');
		$CountryObject = new Country();
		$CountryObject->Find(array("Code" => strtoupper($Data['Language'])));
		$Country = $CountryObject->Name;
		$Data['Country'] = $Country;
		if($NumRows){
			//The User Exists
			//Get The Id
			$Id = $Query->row(0)->Id;
			if($Query->row(0)->Status == 1){
				$_SESSION['UserId'] = $Id;
				// Redirect the user
				redirect('token');
			} else {
				redirect($this->confgi->item("login_page"));
			}
		}
		else{
			//The User doesn't exist now we are going to create him
			$Query = $this->db->query('INSERT INTO Users (RealName,UserGroup,Twitter,Country,Language) Values(?,?,?,?,?,?)',array(
				$Data['Name'],
				'User',
				$Data['Id'],
				$Data['Country'],
				$Data['Language'],
				1
			));
			$_SESSION['UserId'] = $this->db->insert_id(); 
			if(isset($_SESSION['UserId'])){
				redirect('token');
			}else{
				redirect('login/twitter/callback');
			}
		}
	}
	
	public function twitter_callback(){
			// It really is best to auto-load this library!
			$this->load->library('tweet');
			$this->load->library('dataconverter');
			$tokens = $this->tweet->get_tokens();
			
			// $user = $this->tweet->call('get', 'account/verify_credentiaaaaaaaaals');
			// 
			// Will throw an error with a stacktrace.
			
			$user = $this->tweet->call('get', 'account/verify_credentials');
			$userarray = $this->dataconverter->object_to_array($user);
			$UserData = array();
			//Set Only the needed data to an specific array
			$UserData['Language'] = $userarray['lang'];
			$UserData['Id'] = $userarray['id_str'];
			$UserData['Name'] = $userarray['name'];
			$UserData['ScreenName'] = $userarray['screen_name'];
			//Set the User Id and Click This Information
			self::twitter_login($UserData);
	}
	
	public function twitter($Parameters = 'auth'){
		if(isset($Parameters)){
			switch($Parameters){
				case "auth":{
					self::twitter_auth();
				}
				case "callback":{
					self::twitter_callback();	
				}
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
	
	//The Debug Output Function To test Callback functionality
	function linkedin_debug($Text){
		echo "<pre>";
		print_r($Text);
		echo "</pre>";
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