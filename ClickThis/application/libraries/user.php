<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used for storing user data, such as Facebook id's etc
 * @package Login
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage User Class
 * @category User Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Documentation
 */ 
class User {
	
	/*#### VARIABLEs ####*/

	/**
	 * A local instance of Code Igniter
	 * @access private
	 * @since 1.0
	 * @var object
	 */
	private $CI = NULL;
	
	/*#### USER INFORMATION ####*/

	/**
	 * The name of the user
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = '';

	/**
	 * The database id of the user
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = 0;

	/**
	 * This variable holds the country of the user
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Country = '';

	/**
	 * This variable holds the email of the user if deffined
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Email = '';

	/**
	 * This contains the users type
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Type = 0;

	/**
	 * This holds the user's login method
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * "User"/Teacher/Pupil
	 */
	public $Method = '';

	/**
	 * This variable contains the users activation status,
	 * 1 means activated and0 means not activated yet
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Status = 0;

	/**
	 * This array contains the ClickThis subscription groups that the user is member of
	 * @var array
	 * @access public
	 * @since 1.0
	 */
	public $TargetGroup = array();

	/**
	 * This variable contains the users preferable language
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * "da_DK"
	 */
	public $Language = '';

	/**
	 * This array contains the user's privilige groups, etc admin, user
	 * @var array
	 */
	public $UserGroup = array();

	/**
	 * This variable contains the state that the user lives in
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * Virginia
	 */
	public $State = '';

	/**
	 * This variable contains the ClickThis username, if the user has deffined one
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Username = '';

	/**
	 * This variable contains the user's TOPT token, if the user has 2 step verification turned on.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $TOPT = '';
	
	/*#### USER TOKENS ####*/

	/**
	 * This variable stores the user's Twitter user id, if the user has a Twitter account linked.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Twitter_Id = '';

	/**
	 * This variable stores the user's Facebook Id/alias if the user has a Facebook account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Facebook_Id = '';

	/**
	 * This variable stores a id associated with the users Linked In,
	 * if it is linked.
	 * @var string
	 */
	public $LinkedIn_Id = '';

	/**
	 * This variable stores the Google Id/Google Email if the user has a Google account linked
	 * @var string
	 */
	public $Google_Id = '';

	/**
	 * Stores the Illution database Id for the user if the user has an Illution Account
	 * @var string
	 * @access public
	 * @since 1.0
	 * @deprecated This login method is deprecated as of version 1, it's only implemented for testing purposes
	 */
	public $Illution_Id = '';

	/**
	 * [$OpenId_Token description]
	 * @var string
	 */
	public $OpenId_Token = ''; //Stores the Token to OpenId if the user has it specified

	/**
	 * [$ClickThis_Id description]
	 * @var string
	 */
	public $ClickThis_Id = ''; // Stores the Id which links to the User datebase Id

	/**
	 * [$Flickr_Id description]
	 * @var string
	 */
	public $Flickr_Id = ''; // Stores the Id which links to the Users Flick Account

	/**
	 * [$Myspace_Id description]
	 * @var string
	 */
	public $Myspace_Id = ''; // Stores the Myspace id to the Users Myspace account

	/**
	 * [$Yahoo_Id description]
	 * @var string
	 */
	public $Yahoo_Id = ''; // Stores the Yahoo id to the Users Yahoo account

	/**
	 * [$Wordpress_Id description]
	 * @var string
	 */
	public $Wordpress_Id = ''; // Stores the Wordpress.com Id fpr the Users Wordpress.com Account
	
	/**
	 * [User description]
	 */
	public function User() {
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
	}
	
	/**
	 * [Import description]
	 * @param [type] $Array [description]
	 */
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	/**
	 * [Export description]
	 */
	public function Export(){
		return array('Name' => $this->Name,'Id' => $this->Id,'Country' => $this->Country,'Email' => $this->Email,'Type' => $this->Type,'Method' => $this->Method,'Status' => $this->Status,'TargetGroup' => $this->TargetGroup,'Language' => $this->Language,'UserGroup' => $this->UserGroup,'State' => $this->State,'Username' => $this->Username,'TOPT' => $this->TOPT	,'Twitter_Id' => $this->Twitter_Id,'Facebook_Id' => $this->Facebook_Id,'Linkedin_Id' => $this->LinkedIn_Id,'Google_Id' => $this->Google_Id,'Illution_Id' => $this->Illution_Id,'OpenId_Token' => $this->OpenId_Token,'ClickThis_Id' => $this->ClickThis_Id,'Flick_Id' => $this->Flickr_Id,'Myspace_Id' => $this->Myspace_Id,'Yahoo_Id' => $this->Yahoo_Id,'Wordpress_Id' => $this->Wordpress_Id);	
	}
	
	/**
	 * [Load description]
	 * @param [type] $Id [description]
	 */
	public function Load($Id) {
		if($this->Id == 0){
			$this->Id = $Id;
		}
		$this->CI->load->model("Load_User");
		$this->CI->Load_User->LoadById($Id,$this);
	}
	
	/**
	 * [Save description]
	 */
	public function Save() {
		$this->CI->load->model('Save_User');
		if($this->Id != 0){
			$this->CI->Save_User->Save($this);
		}
		else{
			return 'No User Id Specified';	
		}
	}
	
	/**
	 * This function removes local data, set the id flag to true to remove the id too.
	 * @param boolean $Id If this is set to true then the id is cleared too
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveUserData($Id = false){
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
	
	/**
	 * This function takes an array and ads the data to the variable with the right key {Name},
	 * with the corrosponding data {Value}
	 * @param array $Array The data in Name => Value format to set
	 * @since 1.0
	 * @access private
	 */
	private function _SetDataArray($Array){
		self::Import($Array);
	}

	/**
	 * This function only sets the output if input exists
	 * @param object||string||number &$Input  The input data to check if exists
	 * @param object||string||number &$Output The output data to set if the input isset
	 */
	private function _CheckData(&$Input,&$Output){
		if(isset($Input) && @!is_null($Input)){
			$Output = $Input;
		}
	}
	
	/**
	 * This function adds data from an User class instance or a alike class to 
	 * @param object &$User An instance with the data to add deffined
	 * @access private
	 * @since 1.0
	 */
	private function _SetDataClass(&$User){
		self::_CheckData($User->ClickThis_Id,$this->ClickThis_Id);
		self::_CheckData($User->Country,$this->Country);
		self::_CheckData($User->Email,$this->Email);
		self::_CheckData($User->Type,$this->Type);
		self::_CheckData($User->Method,$this->Method);
		self::_CheckData($User->Status,$this->Status);
		self::_CheckData($User->TargetGroup,$this->TargetGroup);
		self::_CheckData($User->Language,$this->Language);
		self::_CheckData($User->UserGroup,$this->UserGroup);
		self::_CheckData($User->State,$this->State);
		self::_CheckData($User->Username,$this->Username);
		self::_CheckData($User->TOPT,$this->TOPT);
		self::_CheckData($User->Twitter_Id,$this->Twitter_Id);
		self::_CheckData($User->Facebook_Id,$this->Facebook_Id);
		self::_CheckData($User->LinkedIn_Id,$this->LinkedIn_Id);
		self::_CheckData($User->Google_Id,$this->Google_Id);
		self::_CheckData($User->Illution_Id,$this->Illution_Id);
		self::_CheckData($User->OpenId_Token,$this->OpenId_Token);
		self::_CheckData($User->ClickThis_Id,$this->ClickThis_Id);
		self::_CheckData($User->Flickr_Id,$this->Flickr_Id);
		self::_CheckData($User->Myspace_Id,$this->Myspace_Id);
		self::_CheckData($User->Yahoo_Id,$this->Yahoo_Id);
		self::_CheckData($User->Wordpress_Id,$this->Wordpress_Id);
		self::_CheckData($User->Id,$this->Id);
	}
	
	/**
	 * [_RemoveDatabaseData description]
	 * @param [type] $Id [description]
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveDatabaseData($Id){
		$this->CI->db->delete("Users",array("Id" => $Id));
	}
	
	/**
	 * This function clears the local class data
	 * @access public
	 * @since 1.0
	 */
	public function Clear(){
		self::_RemoveUserData(false);
	}
	
	/**
	 * This function refresh the class data from the database
	 * @see self::Load
	 * @since 1.0
	 * @access public
	 */
	public function Refresh(){
		self::Load($this->Id);
	}
	
	/**
	 * This function delete's data local in the class, but if selected it can also delete the data from the database
	 * @param boolean $Database If this flag is set too true, the database data will be deleted too
	 * @since 1.0
	 * @access public
	 */
	public function Delete($Database = false){
		if($Database){
			self::_RemoveDatabaseData($this->Id);
			self::_RemoveUserData(true);
		}
		else{
			self::_RemoveUserData(false);
		}
	}
	
	/**
	 * This function adds data, to this class either from a class or from an array,
	 * and if the Database flag is set to yrue the data will be saved to the database.
	 * @param object  &$User    This parameter should contain a class that has the data to add deffined,
	 * with the same variable names, as this class. Not all variables need to be deffined only create them you need to.
	 * @param array  $Array    If this parameter is set and $User is null the data from this parameter is used in Name => Value format
	 * @param boolean $Database If this flag is set to true, then the data will be saved in the database
	 * @since 1.0
	 * @access public
	 */
	public function Add(&$User = NULL,$Array = NULL,$Database = false){
		if(!is_null($User)){
			self::_SetDataClass($User);
		}
		else{
			if(!is_null($Array)){
				self::_SetDataArray($Array);
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
	
	/**
	 * This function takes the data from the $Array parameter and adds it to the local data,
	 * and if the database flag is set the data will be saved too. 
	 * @param array  $Array    The data in Name => Value format
	 * @param boolean $Database If this flag is set too true the data is saved too
	 * @access public
	 * @since 1.0
	 */
	public function Create($Array,$Database = false){
		self::_SetDataArray($Array);
		if($Database){
			$this->CI->load->model('Save_User');
			$this->Id = $this->CI->Save_User->Create($this);
			return $this->Id;
		}
	}
	
	/**
	 * [Login_Twitter description]
	 * @param [type] $Id [description]
	 */
	public function Login_Twitter($Id) {
		
	}
	
	/**
	 * [Login_Facebook description]
	 * @param [type] $Id [description]
	 */
	public function Login_Facebook($Id) {
		
	}
	
	/**
	 * [Login_LinkedIn description]
	 * @param [type] $Id [description]
	 */
	public function Login_LinkedIn($Id) {
		
	}
	
	/**
	 * [Login_Google description]
	 * @param [type] $Id [description]
	 */
	public function Login_Google($Id) {
		
	}
	
	/**
	 * [Login_ClickThis description]
	 * @param [type] $Id [description]
	 */
	public function Login_ClickThis($Id) {
		
	}
}
?>