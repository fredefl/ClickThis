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
	public $name = NULL;

	/**
	 * The database id of the user
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $id = NULL;

	/**
	 * This variable holds the country of the user
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $country = NULL;

	/**
	 * This property will contain a link to the users profile image
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $profile_image = NULL;

	/**
	 * This variable holds the email of the user if deffined
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $email = NULL;

	/**
	 * This contains the users type
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $type = NULL;

	/**
	 * This variable contains the users activation status,
	 * 1 means activated and0 means not activated yet
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $status = NULL;

	/**
	 * This array contains the ClickThis subscription groups that the user is member of
	 * @var array
	 * @access public
	 * @since 1.0
	 */
	public $target_group = NULL;

	/**
	 * This variable contains the users preferable language
	 * @var string
	 * @access public
	 * @since 1.0
	 * @example
	 * "da_DK"
	 */
	public $language = NULL;

	/**
	 * This array contains the user's privilige groups, etc admin, user
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $user_group = NULL;

	/**
	 * This variable contains the ClickThis username, if the user has deffined one
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $username = NULL;

	/**
	 * This variable contains the user's TOPT token, if the user has 2 step verification turned on.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $topt = NULL;
	
	/*#### USER TOKENS ####*/

	/**
	 * This variable stores the user's Twitter user id, if the user has a Twitter account linked.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $twitter_id = NULL;

	/**
	 * This variable stores the user's Facebook Id/alias if the user has a Facebook account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $facebook_id = NULL;

	/**
	 * This variable stores a id associated with the users Linked In,
	 * if it is linked.
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $linkedin_id = NULL;

	/**
	 * This variable stores the Google Id/Google Email if the user has a Google account linked
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $google_id = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "users";
	
	/**
	 * This function is the constructor, it makes an instance of CodeIgniter and put it in the $this->CI property
	 * @since 1.0
	 * @access public
	 */
	public function User() {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"name" => "real_name",
			"facebook_id" => "facebook",
			"linkedin_id" => "linkedin",
			"google_Id" => "google",
			"twitter_id" => "twitter"
		);
		$this->_INTERNAL_SECURE_EXPORT_IGNORE = array(
			"google_id",
			"linkedin_id",
			"facebook_id",
			"twitter_id",
			"topt",
			"username",
			"status",
			"target_group",
			"user_group",
			"type"	
		);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("name","email","country","username","user_group","facebook_id","google_id","linkedin_id","twitter_id");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_FORCE_ARRAY = array("user_group","target_group");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("target_group" => "Group");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}

	/**
	 * This function checks if the users profile image is set
	 */
	public function CheckProfileImage(){
		if(is_null($this->profile_image)){
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
?>