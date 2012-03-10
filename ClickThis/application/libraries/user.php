<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used for storing user data, such as Facebook id's etc
 * @package Login
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage User Class
 * @category User Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class User {

	/**
	 * A local instance of Code Igniter
	 * @access private
	 * @since 1.0
	 * @var object
	 * @internal This is an internal instance of CodeIgniter,
	 * and it's only used in this class
	 */
	private $CI = NULL;
	
	/*#### USER INFORMATION ####*/

	/**
	 * The name of the user
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * The database id of the user
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * This variable holds the country of the user
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Country = NULL;

	/**
	 * This variable holds the email of the user if deffined
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Email = NULL;

	/**
	 * This contains the users type
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Type = NULL;

	/**
	 * This holds the user's login method
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * "User"/Teacher/Pupil
	 */
	public $Method = NULL;

	/**
	 * This variable contains the users activation status,
	 * 1 means activated and0 means not activated yet
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Status = NULL;

	/**
	 * This array contains the ClickThis subscription groups that the user is member of
	 * @var array
	 * @access public
	 * @since 1.0
	 */
	public $TargetGroup = NULL;

	/**
	 * This variable contains the users preferable language
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * "da_DK"
	 */
	public $Language = NULL;

	/**
	 * This array contains the user's privilige groups, etc admin, user
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $UserGroup = NULL;

	/**
	 * This variable contains the state that the user lives in
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * Virginia
	 */
	public $State = NULL;

	/**
	 * This variable contains the ClickThis username, if the user has deffined one
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Username = NULL;

	/**
	 * This variable contains the user's TOPT token, if the user has 2 step verification turned on.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $TOPT = NULL;
	
	/*#### USER TOKENS ####*/

	/**
	 * This variable stores the user's Twitter user id, if the user has a Twitter account linked.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Twitter_Id = NULL;

	/**
	 * This variable stores the user's Facebook Id/alias if the user has a Facebook account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Facebook_Id = NULL;

	/**
	 * This variable stores a id associated with the users Linked In,
	 * if it is linked.
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $LinkedIn_Id = NULL;

	/**
	 * This variable stores the Google Id/Google Email if the user has a Google account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Google_Id = NULL;

	/**
	 * Stores the Illution database Id for the user if the user has an Illution Account
	 * @var string
	 * @access public
	 * @since 1.0
	 * @deprecated This login method is deprecated as of version 1, it's only implemented for testing purposes
	 */
	public $Illution_Id = NULL;

	/**
	 * This property stores the OpenId, user id if the user has one specified
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $OpenId_Token = NULL;

	/**
	 * This property stores the Users database id row value
	 * @var string
	 * @see $this->Id
	 * @since 1.0
	 * @access public
	 */
	public $ClickThis_Id = NULL;

	/**
	 * This property stores the users Flick user id/username if the user has one linked
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Flickr_Id = NULL;

	/**
	 * This property stores the Myspace user id/username if the user has a Myspace account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Myspace_Id = NULL;

	/**
	 * This property stores the Yahoo user name/id if the user has an Yahoo account linked
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Yahoo_Id = NULL;

	/**
	 * This property stores the wordpress.com user id if the user has one account linked
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Wordpress_Id = NULL;

	/**
	 * This property is used to convert class property names,
	 * to database row names
	 * @var array
	 * @access private
	 * @since 1.0
	 * @internal This is an internal name convert table
	 */
	private $_INTERNAL_DATABASE_NAME_CONVERT = NULL;

	private $_INTERNAL_EXPORT_INGNORE = NULL;
	
	/**
	 * This function is the constructor, it makes an instance of CodeIgniter and put it in the $this->CI property
	 * @since 1.0
	 * @access public
	 */
	public function User() {
		$this->CI =& get_instance();
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"RealName" => "Name",
			 "Facebook" => "Facebook_Id",
			"LinkedIn_Id" => "LinkedIn",
			"Google_Id" => "Google",
			"Illution_Id" => "Userid",
			"OpenId_Token" => "OpenId",
			"Flickr_Id" => "Flickr",
			"Myspace_Id" => "Myspace",
			"Yahoo_Id" => "Yahoo",
			"Wordpress_Id" => "Wordpress"
		);
		$this->_INTERNAL_EXPORT_INGNORE = array("ClickThis_Id","CI");
		$this->CI->load->model("Model_User");
		$this->CI->Model_User->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}
	
	/**
	 * This function imports data from an array with the same key name as the local property to import too.
	 * @param array $Array The data to import in Name => Value format
	 * @since 1.0
	 * @access public
	 */
	public function Import($Array = NULL){
		if(!is_null($Array)){
			foreach($Array as $Name => $Value){
				if(property_exists($this,$Name)){
					$this->$Name = $Value;	
				}
			}
		}
	}
	
	/**
	 * This function returns all the class variable with name and values as an array
	 * @return array All the class vars and values
	 * @param boolean $Database If this flag is set to true, the data will be exported so the key names,
	 * fits the database row names
	 * @since 1.0
	 * @access public
	 * @return array The class data as an array
	 */
	public function Export ($Database = false) {
		if ($Database) {
			$Array = array();

			//Loop through all class properties
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {

				//If the property is the CodeIgniter instance, the id or an internal property dont do anything
				//if ($Name != "CI" && strpos($Name, "INTERNAL_") === false && $Name != "Id") {
				if (!self::Ignore($Name,array("Id"))) {

					//If the class has an name convert table, check if the current property exists in it
					// , if it does use that as the array key
					if(property_exists(get_class($this), "_INTERNAL_DATABASE_NAME_CONVERT") 
						&& is_array($this->_INTERNAL_DATABASE_NAME_CONVERT) 
						&& array_key_exists($Name, $this->_INTERNAL_DATABASE_NAME_CONVERT)) {

						//If the data is an array implode it with a ";" sign else just assign it
						if(!is_null($this->{$Name}) && is_array($this->{$Name})){
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = implode(";",$this->{$Name});
						} else {
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = $this->{$Name};
						}
					} else {
						if(!is_null($this->{$Name}) && is_array($this->{$Name})){
							$Array[$Name] = implode(";",$this->{$Name});
						} else {
							$Array[$Name] = $this->{$Name};
						}
					}
				}
			}
		} 
		else {
			$Array = array();
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {
				//if ($Name != "CI" && strpos($Name, "INTERNAL_") === false) {
				if (!self::Ignore($Name)) {
					$Array[$Name] = $this->{$Name};
				}
			}
		}
		return $Array;
	}

	private function Ignore($Key = NULL,$ExtraIgnore = NULL){
		if(!is_null($Key)){
			if(!strpos($Key, "INTERNAL_") === false){
				return true;
			} else {
				if(property_exists(get_class($this), "_INTERNAL_EXPORT_INGNORE")){
					if(in_array($Key,$this->_INTERNAL_EXPORT_INGNORE)){
						return true;
					} else {
						if(!is_null($ExtraIgnore) && in_array($Key, $ExtraIgnore)){
							return true;
						} else {
							return false;
						}
					}
				} else {
					if(!is_null($ExtraIgnore) && in_array($Key, $ExtraIgnore)){
						return true;
					} else {
						return false;
					}
				}
			}
		} else {
			return true;
		}
	}
	
	/**
	 * This function loads data either by the $Id parameter or by the $Id property
	 * @param integer $Id The database id to load data from, this parameter is optional,
	 * if it's not deffined the $Id property value will be used
	 * @since 1.0
	 * @access public
	 */
	public function Load($Id = NULL) {
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id)){
			$this->CI->Model_User->Load($this->Id,$this);
		}
	}
	
	/**
	 * This function saves the local class data to the database row of the Id property
	 * @return string This function can return a error string
	 * @since 1.0
	 * @access public
	 */
	public function Save() {
		$this->CI->load->model('User');
		if(!is_null($this->Id)){
			$this->CI->Model_User->Save($this);
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
		foreach(get_class_vars(get_class($this)) as $Name => $Value){
			if($Name != "CI"){
				if($Name != "Id"){
					$this->{$Name} = NULL;
				}
				if($Id == true && $Name == "Id"){
					$this->{$Name} = NULL;
				}
			}
		}
	}
	
	/**
	 * This function takes an array and ads the data to the variable with the right key {Name},
	 * with the corrosponding data {Value}
	 * @param array $Array The data in Name => Value format to set
	 * @since 1.0
	 * @access private
	 */
	private function _SetDataArray($Array = NULL){
		if(!is_null($Array)){
			self::Import($Array);
		}
	}

	/**
	 * This function only sets the output if input exists
	 * @param object||string||number &$Input  The input data to check if exists
	 * @param object||string||number &$Output The output data to set if the input isset
	 * @since 1.0
	 * @access private
	 */
	private function _CheckData(&$Input = NULL,&$Output = NULL){
		if(!is_null($Input) && !is_null($Output)){
			if(isset($Input) && @!is_null($Input)){
				$Output = $Input;
			}
		}
	}
	
	/**
	 * This function adds data from an User class instance or a alike class to 
	 * @param object &$User An instance with the data to add deffined
	 * @access private
	 * @since 1.0
	 */
	private function _SetDataClass(&$User = NULL){
		if(!is_null($User)){
			foreach (get_object_vars($User) as $Key => $Value) {
				if(property_exists($this, $Key)){
					if(!is_null($User->{$Key}) && $User->{$Key} != "" && $Key != "CI"){
						$this->{$Key} = $User->{$Key};
					}
				}
			}
			
		}
	}
	
	/**
	 * This function removes data from the specified row in the $Id parameter
	 * @param integer $Id The database row id to remove
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveDatabaseData($Id = NULL){
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id)){
			$this->CI->db->delete("Users",array("Id" => $this->Id));
		}
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
		if(!is_null($this->Id)){
			self::Load($this->Id);
		}
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
		if($Database && !is_null($this->Id)){
			$this->Id = $this->CI->Model_User->Create($this);
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
	public function Create($Array =  NULL,$Database = false){
		if(!is_null($Array)){
			self::_SetDataArray($Array);
			if($Database){
				$this->Id = $this->CI->Model_User->Create($this);
				return $this->Id;
			}
		}
	}
}
?>