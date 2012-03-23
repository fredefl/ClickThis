<?php
class clickthis_security
{
	//Variables
	public $Pages = array('login','login/google','login/linkedin','login/twitter','login/facebook','login/linkedin/callback','login/myspace'); //None redirect pages
	public $Keywords = array('api','login','social','register'); //None redirect Keywords
	public $Developers = array(1 => 1, 2 => 1,'Bo Thomsen' => 1); //The Database ids of Developers
	public $Offset = 0; //The Text offset when using Keyword array
	public $IllutionLogin = 1; //Illtion Login of or on
	public $UseFolder = '/ClickThis/'; //The Use folder the standard is '/ClickThis/' but ifother is specified in config['folder'] the that will be standard
	public $CI = '';  //An Instance of Code Igniter
	public $Redirect = 'login'; //The Site to be redirected to if not logged in
	public $DevMode = 1; //A local variable to store the config for developer mode
	
	//Check To see if the User is Logged In, Illution login is only for Developers and will soon be marked as deprecated on Click This
	public function IsLoggedIn(){
		if(isset($_SESSION['UserId'])||(isset($_SESSION["username"])&&$this->IllutionLogin == 1)){
			return true;	
		}
		else{
			return false;	
		}
	}
	
	//Login
	public function Login(){
		if(!isset($_SESSION['UserId'])){
			redirect($this->Redirect);
		}
	}
	
	//Logout
	public function Logout(){
		if(isset($_SESSION['UserId'])){
			$_SESSION['UserId'] = NULL;
		}
		if(isset($_SESSION['username'])){
			$_SESSION['username'] = NULL;
		}
		session_destroy();
		session_unset();
		unset($_SESSION);
		redirect($this->Redirect);
		die();
	}
	
	//Check To see if the User is Logged In and if that is true gets the user data, Illution login is only for Developers and will soon be marked as deprecated on Click This
	public function UserData(){
		if(isset($_SESSION['UserId'])){
			return $_SESSION['UserId'];
		}
		if(isset($_SESSION["username"])&&$this->IllutionLogin == 1){
			return $_SESSION["username"];
		}
	}
	
	//Check For Predefined Pages and Keywords and Callkeywords if the function is true redirection shoudn't be made
	private function loginpage($Page,$String,$Offset = 0){
		$Keyword = false;
		foreach($this->Keywords as $Value){
			if(substr($Page,$this->Offset,strlen($Value))==$Value){
			$Keyword = true;
		}
		}
		if(substr($Page,$Offset,strlen($String))==$String){
			$Keyword = true;
		}
		if($Keyword===true||in_array($Page,$this->Pages)){
			return true;
		}
		else
		{
			return false;
		}
	}
		
	//Get Config
	public function GetConfig($Item = NULL,$File = 'config'){
		switch($File){
			case 'config':{ //Standrad
				$Item = $this->CI->config->item($Item);
			}
		}
		//Return
		return $Item;
	}
		
	//Contructor
	public function __construct()
	{
		//An Instance of Code Igniter
		$this->CI =& get_instance();
		
		//Folder
		$this->UseFolder = $this->CI->config->item('folder');
		
		//Login Page
		if($this->CI->config->item('login_page') != ''){
			$this->Redirect = $this->CI->config->item('login_page');
		}
		//Pages
		if(count($this->CI->config->item('pages')) == 0){
			$this->Pages = $this->CI->config->item('pages');
		}
		
		//Keywords
		if(count($this->GetConfig('keywords'))){
			$this->Keywords = $this->GetConfig('keywords');
		}
		
		//Developers
		if(count($this->GetConfig('developers'))){
			$this->Developers = $this->GetConfig('developers');
		}
		
		//Get Current Page
		$Page = str_replace($this->UseFolder,"",$_SERVER['REQUEST_URI']);
		//Start Session
		session_start();
		
		//Check for Security
		if(!self::loginpage($Page,'login')){
			if(!self::IsLoggedIn())
			{
				$_SESSION["redirect"] = current_url();
				redirect($this->Redirect);
				die();
			}
		}
		
		//Check To see if the user is logged in if the user is and if the user is not Developer show The 418 - Im a Teapot Error
		if(self::IsLoggedIn()){
			if(!self::Developer(self::UserData())){
				if($this->DevMode == 1){
					show_error(418);
					die();
				}
				else{
					//Llama	
				}
			}
		}		
	}
	
	//If the user is marked as Developer
	public function Developer($User)
	{
		//List of Developers
		if(isset($this->Developers[$User]))
		{
			return true;
		}else
		{
			return false;	
		}
	}
}
?>