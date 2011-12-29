<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class User {
	
	/*###### VARIABLES ######*/
	private $CI = ''; //An instance of Codde Igniter
	
	/*#### USER INFORMATION ####*/
	public $Name = ''; //The Name of the User
	public $Id = 0; //The Database Id of the User
	public $Country = ''; //The Country of the User
	public $Email = ''; //The Email of the User
	public $Type = 0; //The Type of the User
	public $Method = ''; //The Method of the User to Store Other Logon Methods Then User etc Teacher,Pupil
	public $Status = 0; //The Status of the user activated or not 1/0
	public $TargetGroup = array(); //Array of the Users Groups
	public $Language = ''; //The Users language
	public $UserGroup = array(); //The Users Group etc Admin or User
	public $State = ''; // The State of the User
	public $Username = ''; //The Click This Username if the user has a registred Click This User
	public $TOPT = ''; //TOPT Token
	
	/*#### USER TOKENS ####*/
	public $Twitter_Id = ''; //A variable for storing the twitter id associated with the user if set
	public $Facebook_Id = ''; //A variable to store the associated Facebook code/id
	public $LinkedIn_Id = ''; //A variable to store the associated id from linked if the user has it set
	public $Google_Id = ''; //Stores the Google id if the user has it specified
	public $Illution_Id = ''; //Stores the Illution database Id for the user if the user has an Illution Account **DEPRECATED**
	public $OpenId_Token = ''; //Stores the Token to OpenId if the user has it specified
	public $ClickThis_Id = ''; // Stores the Id which links to the User datebase Id
	public $Flickr_Id = ''; // Stores the Id which links to the Users Flick Account
	public $Myspace_Id = ''; // Stores the Myspace id to the Users Myspace account
	public $Yahoo_Id = ''; // Stores the Yahoo id to the Users Yahoo account
	public $Wordpress_Id = ''; // Stores the Wordpress.com Id fpr the Users Wordpress.com Account
	
	//The Contructor
	public function User() {
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
	}
	
	//Import
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	//Export
	public function Export(){
		return array('Name' => $this->Name,'Id' => $this->Id,'Country' => $this->Country,'Email' => $this->Email,'Type' => $this->Type,'Method' => $this->Method,'Status' => $this->	Status,'TargetGroup' => $this->TargetGroup,'Language' => $this->Language,'UserGroup' => $this->UserGroup,'State' => $this->State,'Username' => $this->Username,'TOPT' => $this->TOPT	,'Twitter_Id' => $this->Twitter_Id,'Facebook_Id' => $this->Facebook_Id,'Linkedin_Id' => $this->LinkedIn_Id,'Google_Id' => $this->Google_Id,'Illution_Id' => $this->Illution_Id,'OpenId_Token' => $this->OpenId_Token,'ClickThis_Id' => $this->ClickThis_Id,'Flick_Id' => $this->Flickr_Id,'Myspace_Id' => $this->Myspace_Id,'Yahoo_Id' => $this->Yahoo_Id,'Wordpress_Id' => $this->Wordpress_Id);	
	}
	
	//Load
	public function Load($Id) {
		if($this->Id == 0){
			$this->Id = $Id;
		}
		$this->CI->load->model("Load_User");
		$this->CI->Load_User->LoadById($Id,$this);
	}
	
	//Save
	public function Save() {
		$this->CI->load->model('Save_User');
		if($this->Id != 0){
			$this->CI->Save_User->Save($this);
		}
		else{
			return 'No User Id Specified';	
		}
	}
	
	//Remove User Data
	private function RemoveUserData($Id = false){
		$this->ClickThis_Id = 0;
		$this->Country = '';
		$this->Email = '';
		$this->Type = 0;
		$this->Method = '';
		$this->Status = 0;
		$this->TargetGroup = array();
		$this->Language = '';
		$this->UserGroup = array();
		$this->State = '';
		$this->Username = '';
		$this->TOPT = '';
		$this->Twitter_Id = '';
		$this->Facebook_Id = '';
		$this->LinkedIn_Id = '';
		$this->Google_Id = '';
		$this->Illution_Id = '';
		$this->OpenId_Token = '';
		$this->ClickThis_Id = '';
		$this->Flickr_Id = '';
		$this->Myspace_Id = '';
		$this->Yahoo_Id = '';
		$this->Wordpress_Id = '';
		if($Id){
			$this->Id = 0;
		}
	}
	
	//Set Data Array
	private function SetDataArray($Array){
		self::Import($Array);
	}
	
	//Set Data Class
	private function SetDataClass(&$User){
		$this->ClickThis_Id = $User->ClickThis_Id;
		$this->Country = $User->Country;
		$this->Email = $User->Email;
		$this->Type = $User->Type;
		$this->Method = $User->Method;
		$this->Status = $User->Status;
		$this->TargetGroup = $User->TargetGroup;
		$this->Language = $User->Language;
		$this->UserGroup = $User->UserGroup;
		$this->State = $User->State;
		$this->Username = $User->Username;
		$this->TOPT = $User->TOPT;
		$this->Twitter_Id = $User->Twitter_Id;
		$this->Facebook_Id = $User->Facebook_Id;
		$this->LinkedIn_Id = $User->LinkedIn_Id;
		$this->Google_Id = $User->Google_Id;
		$this->Illution_Id = $User->Illution_Id;
		$this->OpenId_Token = $User->OpenId_Token;
		$this->ClickThis_Id = $User->ClickThis_Id;
		$this->Flickr_Id = $User->Flickr_Id;
		$this->Myspace_Id = $User->Myspace_Id;
		$this->Yahoo_Id = $User->Yahoo_Id;
		$this->Wordpress_Id = $User->Wordpress_Id;
		$this->Id = $User->Id;
	}
	
	//Remove Databse Data
	private function RemoveDatabaseData($Id){
		$this->CI->db->query("DELETE FROM Users WHERE Id='?'",array($Id));
	}
	
	//Clear
	public function Clear(){
		self::RemoveUserData(false);
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);
	}
	
	//Delete
	public function Delete($Database = false){
		if($Database){
			self::RemoveDatabaseData($this->Id);
			self::RemoveUserData(true);
		}
		else{
			self::RemoveUserData(false);
		}
	}
	
	//Add
	public function Add(&$User = NULL,$Array = NULL,$Database = false){
		if(!is_null($Answer)){
			self::SetDataClass($User);
		}
		else{
			if(!is_null($Array)){
				self::SetDataArray($Array);
			}
			else{
				return "Error Wrong Input";	
			}
		}
		if($Database){
			$this->CI->load->model('Save_User');
			$this->Id = $this->CI->Save_User->Create($this);
			return $this->Id;
		}
	}
	
	//Create
	public function Create($Array,$Database = false){
		self::SetDataArray($Array);
		if($Database){
			$this->CI->load->model('Save_User');
			$this->Id = $this->CI->Save_User->Create($this);
			return $this->Id;
		}
	}
	
	public function Login_Twitter($Id) {
		
	}
	
	public function Login_Facebook($Id) {
		
	}
	
	public function Login_LinkedIn($Id) {
		
	}
	
	public function Login_Google($Id) {
		
	}
	
	/*
	public function Login_($Id) {
		
	}
	*/
	
	public function Login_ClickThis($Id) {
		
	}
}
?>