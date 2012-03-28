<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used to handle app data
 * @package API
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage App
 * @category ClickThis Apps
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class App extends Std_Library{

	/**
	 * The database id of the App
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Id = NULL;

	/**
	 * The contact email of the app support
	 * @since 1.0
	 * @access public
	 * @var string
	 */
	public $Email = NULL;

	/**
	 * The website of the app, if any
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Website = NULL;

	/**
	 * The standard Auth endpoint url
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $AuthenticationEndpoint = NULL;

	/**
	 * The consumer key of the app,
	 * this is removed in export
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $ConsumerKey = NULL;

	/**
	 * The consumer secret of the app,
	 * this should be removed in export because it's private data
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $ConsumerSecret = NULL;

	/**
	 * The name of the app
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * A short app description
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Description = NULL;

	/**
	 * An application icon, if specified for the app.
	 * Shown in the app auth screen.
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Image = NULL;

	/**
	 * The database id the user,
	 * owning the application. 
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $UserId = NULL;

	#### Class settings ####

		/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Apps";

	/**
	 * This property is used to deffine a set of rows that is gonna be
	 * unique for this row of data
	 * @var array
	 * @access public
	 * @since 1.1
	 * @static
	 * @internal This is a internal settings variable
	 * @example
	 * array("SeriesId","Title");
	 */
	public static $_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = NULL;

	/**
	 * This property will contain a local instance of CodeIgniter,
	 * if the children set's it
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This property can contain properties to be ignored when exporting
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_EXPORT_INGNORE = NULL;

	/**
	 * This property is used to define class properties that should be filled with objects,
	 * with the data that the property contains
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 * @internal This is a class setting variable
	 */
	public static $_INTERNAL_LOAD_FROM_CLASS = NULL;

	/**
	 * This property can contain properties to be ignored, when the database flag is true in export.
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_DATABASE_EXPORT_INGNORE = NULL;

	/**
	 * This property contains the database model to use
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public static $_INTERNAL_DATABASE_MODEL = NULL;

	/**
	 * This property is used to determine what properties is going to be ignored,
	 * if the secrure parameter is turned on in the export function
	 * @var array
	 * @since 1.0
	 * @static
	 * @access public
	 * @example
	 * $this->_INTERNAL_LINK_PROPERTIES = array("Email,"Google_Id");
	 */
	public static $_INTERNAL_SECURE_EXPORT_IGNORE = NULL;

	/**
	 * The constructor of the app class
	 */
	public function App(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Name","UserId");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","_CI","Database_Table");
		$this->_INTERNAL_SECURE_EXPORT_IGNORE = array("AuthenticationEndpoint","ConsumerSecret","ConsumerKey");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("UserId" => "User");
	}

	/**
	 * This function generates the consumer keys
	 * @param integer $ConsumerLength       The length of the consumer key
	 * @param integer $ConsumerSecretLength The length of the consumer secret key
	 */
	public function Consumer($ConsumerLength = 64,$ConsumerSecretLength = 128){
		$this->ConsumerKey = self::_Rand_Str($ConsumerLength);
		$this->ConsumerSecret = self::_Rand_Str($ConsumerSecretLength);
	}

	/**
	 * This function generates a random string
	 * @param  integer $Length The length of the random string
	 * @param  string  $Chars  The Charset to use
	 * @return string
	 * @author Kyle Florence <kyle.florence@gmail.com>
	 * @since 1.0
	 * @access private
	 */
	private function _Rand_Str($Length = 32, $Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
	    $Chars_Length = (strlen($Chars) - 1);
	    $String = $Chars{rand(0, $Chars_Length)};
	    for ($I = 1; $I < $Length; $I = strlen($String))
	    {
	        $R = $Chars{rand(0, $Chars_Length)};
	        if ($R != $String{$I - 1}) $String .=  $R;
	    }
	    return $String;
	}
}