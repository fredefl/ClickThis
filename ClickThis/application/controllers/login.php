<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
http://illution.dk/ClickThis/termsofservice/ v|
http://illution.dk/ClickThis/privacy/ v|
http://illution.dk/ClickThis/google/callback
http://illution.dk/ClickThis/openid/realm
http://illution.dk/ClickThis/google/userstatus
http://illution.dk/ClickThis/google/login
http://illution.dk/ClickThis/google/register
http://illution.dk/ClickThis/logout v| 
*/

class Login extends CI_Controller {

	public function index() {
		// Load View
		if(!isset($_SESSION['UserId'])) {
			$this->load->view('login_view');
			$content = $this->output->get_output();
			$mini = $this->minify->Html($content);
			$this->output->set_output($mini);
		} else {
			redirect('home');	
		}

	}
	public function url() {
		
	}

######################################################Providers#########################################################
###############################OpenId#################################	
	public function openid($Page){
		if(!is_null($Page)){
			if(method_exists($this,"openid_".$Page)){
				$Function = "openid_".$Page;
				self::$Function();
			}
			else{
				show_error(404);	
			}
		}
	}
	private function openid_realm(){
		
	}
	
	private function openid_login(){
		
	}
	
	private function openid_register(){
		
	}
	
	private function openid_logout(){
		
	}
	
	private function openid_callback(){
		
	}

###############################Google#################################	
	public function google($Page = NULL) {
		if(is_null($Page)){
			// If you have logged in with Google
			if(isset($_SESSION['GoogleLogin'])) {
				// Proceed
				// Get user data
				$GoogleLoginData = $_SESSION['GoogleLogin'];
				// Find out if the user exists in the database
				$Query = $this->db->query('Select (Id) From Users Where Google = ?', array($GoogleLoginData['Email']));
				$NumRows = $Query->num_rows();
				// Check for user existance
				if($NumRows) {
					// User exists!
					// Get user Id
					$Id = $Query->row(0)->Id;
					// Set the users Id in a session
					$_SESSION['UserId'] = $Id;
					// Redirect the user
					redirect('home');
				} else {
					// User does not exist
					$Query = $this->db->query('Insert Into Users (RealName,UserGroup,Google) Values(?,?,?)', array(
																							$GoogleLoginData['Name'],
																							'User',
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
	}
	
	private function google_callback() {

	}
	
	private function google_userstatus() {

	}
	
	private function google_login() {

	}
	
	private function google_register() {

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
			$this->load->view('facebook_test', $data);
			$this->load->library('countrycode');
			$Locale = array();
			$Locale = explode("_",$fb_data['me']['locale']);
			$Country = $Country = $this->countrycode->country_code_to_country($Locale[1]);
			$Language = $Locale[0].$Locale[1];
			
			// Find out if the user exists in the database
			$Query = $this->db->query('Select (Id) From Users Where Facebook = ?', array($fb_data['me']['id']));
			$NumRows = $Query->num_rows();
			// Check for user existance
			if($NumRows) {
				// User exists!
				// Get user Id
				$Id = $Query->row(0)->Id;
				// Set the users Id in a session
				$_SESSION['UserId'] = $Id;
				// Redirect the user
				redirect('home');
			} else {
				// User does not exist
				$Query = $this->db->query('Insert Into Users (RealName,UserGroup,Facebook) Values(?,?,?)', array(
																						$fb_data['me']['name'],
																						'User',
																						$fb_data['me']['id']
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
		$Query = $this->db->query('Select (Id) From Users Where Twitter = ?', array($Data['Id']));
		$NumRows = $Query->num_rows();
		$this->load->library('countrycode');
		$Country = $this->countrycode->country_code_to_country($Data['Language']);
		$Data['Country'] = $Country;
		if($NumRows){
			//The User Exists
			//Get The Id
			$Id = $Query->row(0)->Id;
			// Set the users Id in a session
			$_SESSION['UserId'] = $Id;
			// Redirect the user
			redirect('home');	
		}
		else{
			//The User doesn't exist now we are going to create him
			$Query = $this->db->query('INSERT INTO Users (RealName,UserGroup,Twitter,Country,Language) Values(?,?,?,?,?)',array(
				$Data['Name'],
				'User',
				$Data['Id'],
				$Data['Country'],
				$Data['Language']
			));
			$_SESSION['UserId'] = $this->db->insert_id(); 
			if(isset($_SESSION['UserId'])){
				redirect('home');
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
	private function POSTData($Post){
		$this->load->library('loginform');
		return $this->loginform->Security($_POST[$Post]);
	}
	
	public function clickthis_generatekeypair(){
		$this->load->library('loginform');
		$this->loginform->SendKeypair();
	}
	
	public function clickthis_login1(){
		echo "<pre>";
		print_r($_REQUEST);
		echo "<pre>";
	}
	
	public function clickthis_login(){
		if(isset($_POST['jCryption'])){
			$this->load->library('loginform');
			$Data = $this->loginform->Decrypt();
			#################GET POST DATA###############################
			$UsernameIn = $this->loginform->Security($Data['login-username']);
			$PasswordIn = $this->loginform->Security($Data['login-password']);
			##############################################################
			$Error = $this->loginform->Check($UsernameIn,$PasswordIn);
			if($this->loginform->UserId() != 0){
				$_SESSION['UserId'] = $this->loginform->UserId();
			}
			else{
				$Error[] = 'User Not Found';	
			}
			if(!count($Error)){
				redirect('home');
			}
			else{
				redirect('login/clickthis/loginerror/'.$Error[0]);	
			}
		}
		else{
			show_error(403);	
		}
	}
	
	public function clickthis_register(){
		redirect('register');
	}
	
	public function clickthis($Page = NULL,$ErrorString = NULL){
		if(!is_null($Page)){
			switch($Page){
				case 'loginerror':{
					$data['errortitle'] = urldecode($ErrorString);
					$this->load->view('clickthis_login_view',$data);	
					break;
				}
				case "login":{
					self::clickthis_login();
					break;
				}
				case "login1":{
					self::clickthis_login1();
					break;	
				}
				case "register":{
					self::clickthis_register();
				}
				case "generatekeypair":{
					self::clickthis_generatekeypair();	
					break;
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
			$this->load->view('clickthis_login_view');	
		}
	}
	
###############################Yahoo#######################################	
	
	public function yahoo(){

	}

###############################Youtube#####################################	
	
	public function youtube(){
		
	}
	
###############################Wordpress###################################		
	
	public function wordpress(){
		
	}
	
###############################WindowsLive#################################		
	
	public function windowslive(){
		
	}

###############################Paypal######################################	
	
	public function paypal(){
		
	}
	
###############################Myspace######################################		
	
	public function myspace(){
		
	}
	
###############################Myspace#####################################		
	
	public function myopenid(){
		
	}

###############################LiveJournal#################################	
	
	public function livejournal(){
		
	}
	
###############################LinkedIn####################################		
	
	//Check if the user is registred etc
	private function linkedin_login($Data){
		$_SESSION['LinkedInLoginId'] = $Data['id']; //Set Session Id Data
		$_SESSION['LinkedInLogin'] = $Data; //Set Session Data
		$Query = $this->db->query('Select (Id) From Users Where LinkedIn = ?', array($Data['id']));
		$this->load->library('countrycode');
		$Country = $this->countrycode->country_code_to_country($Data['code']);
		$NumRows = $Query->num_rows();
			if($NumRows) {
				// User exists!
				// Get user Id
				$Id = $Query->row(0)->Id;
				// Set the users Id in a session
				$_SESSION['UserId'] = $Id;
				// Redirect the user
				redirect('home');
			} else {
				// User does not exist
				$Query = $this->db->query('INSERT INTO Users (RealName,UserGroup,LinkedIn,Country) Values(?,?,?,?)', 
																					array(
																						$Data['name'],
																						'User',
																						$Data['id'],
																						$Country
																					)	
				);
				$_SESSION['UserId'] = $this->db->insert_id(); 
				if(isset($_SESSION['UserId'])){
					redirect('home');
				}else{
					redirect('login/linkedin/callback');
				}
			}
	}
	
	//Authenticate With The users LinkedIn Account
	private function linkedin_auth(){
		error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
		include("linkedin.php");
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
		include("linkedin.php");
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
	
###############################Hyves#######################################		
	
	public function hyves(){
		
	}

###############################Foursquare#################################	
	
	public function foursquare(){
		
	}
	
###############################Tumblr#####################################	
	
	private function tumblr_auth(){
		
	}
	
	private function tumblr_callback(){
			
	}
	
	public function tumblr($Parameters = 'auth'){
		//If theres is parameters like callback for login
		if(isset($Parameters)){
			//Check Paramters
			switch($Parameters){
				
				//Login Callback
				case "callback":
				{
					self::tumblr_callback();	
				}
				//Auth
				case "auth":{
					self::tumblr_auth();
				}
			}
		}
		
	}

###############################Flickr#####################################	
	
	public function flickr(){
		
	}
	
###############################Aol########################################		
	
	public function aol(){
		
	}
	
###############################Blogger####################################		
	
	public function blogger(){
		
	}
	
##############################################################################################################################	
	
	public function logout() {
		unset($_SESSION);
		session_destroy();
		redirect('login');	
		
	}
}
?>