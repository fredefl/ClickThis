<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used for storing user data, such as Facebook id's etc
 * @package Login
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage User Class
 * @category User Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Make functions to get profile pictures
 */ 
class User extends Std_Library{

	/**
	 * A local instance of Code Igniter
	 * @access public
	 * @since 1.0
	 * @var object
	 * @internal This is an internal instance of CodeIgniter,
	 * and it's only used in this class
	 */
	private $_CI = NULL;
	
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
	 * This property will contain a link to the users profile image
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $ProfileImage = NULL;

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

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Users";
	
	/**
	 * This function is the constructor, it makes an instance of CodeIgniter and put it in the $this->CI property
	 * @since 1.0
	 * @access public
	 */
	public function User() {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"Name" => "RealName",
			"Facebook_Id" => "Facebook",
			"LinkedIn_Id" => "LinkedIn",
			"Google_Id" => "Google",
			"Illution_Id" => "Userid",
			"OpenId_Token" => "OpenId",
			"Flickr_Id" => "Flickr",
			"Myspace_Id" => "Myspace",
			"Yahoo_Id" => "Yahoo",
			"Wordpress_Id" => "Wordpress",
			"Twitter_Id" => "Twitter"
		);
		$this->_INTERNAL_SECURE_EXPORT_IGNORE = array(
			"ClickThis_Id",
			"OpenId_Token",
			"Illution_Id",
			"Wordpress_Id",
			"Yahoo_Id",
			"Myspace_Id",
			"Flickr_Id",
			"Google_Id",
			"LinkedIn_Id",
			"Facebook_Id",
			"Twitter_Id",
			"TOPT",
			"Username",
			"State",
			"Method",
			"Status",
			"TargetGroup",
			"UserGroup",
			"Type"	
		);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Name","Email","Country","Username","UserGroup","Facebook_Id","Google_Id","LinkedIn_Id","Twitter_Id");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_FORCE_ARRAY = array("UserGroup","TargetGroup");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","ClickThis_Id");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("TargetGroup" => "Group");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}

	/**
	 * This function checks if the users profile image is set
	 */
	public function CheckProfileImage(){
		if(is_null($this->ProfileImage)){
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
?>