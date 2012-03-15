<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores information about a pupil, which is studying on a education institute
 * @package School
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Pupil
 * @category Education
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Make the Save and Delete function interact with the users dba too.
 */ 
class Pupil extends Std_Library{
	
	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Pupils";

	/**
	 * This property is used to convert class property names,
	 * to database row names
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 * @internal This is an internal name convert table
	 */
	public static $_INTERNAL_DATABASE_NAME_CONVERT = NULL;

	/**
	 * This property can contain properties to be ignored when exporting
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_EXPORT_INGNORE = NULL;

	/**
	 * This property can contain properties to be ignored, when the database flag is true in export.
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_DATABASE_EXPORT_INGNORE = NULL;

	/**
	 * This property contain values for converting databse rows to class properties
	 * @var array
	 * @see $_INTERNAL_DATABASE_NAME_CONVERT
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_ROW_NAME_CONVERT = NULL;

	/**
	 * This property contains the database model to use
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public static $_INTERNAL_DATABASE_MODEL = NULL;


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

	### User Data ###

	/**
	 * The class/group the pupil is in, not to be confused with "Group"
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Class = NULL;

	/**
	 * The database id of the pupil/user
	 * @access public
	 * @since 1.0
	 * @var integer
	 */
	public $Id = NULL;

	/**
	 * The unilogin of the pupil, if the user has one
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Unilogin = NULL;

	/**
	 * The country that the user lives in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	/**
	 * The school the pupil is studying on
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $School = NULL;

	/**
	 * The state that the pupil lives in, this can be looked up, with the school too
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * The name of the pupil, stored in the users database
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * A locale instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 */
	private $_CI = NULL;

	/**
	 * The login method for the pupil
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Method = "Pupil";
	
	/**
	 * This function is the constructor, it creates a local instance of CodeIgniter
	 * @access public
	 * @since 1.0
	 */
	public function Pupil () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Method");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("School" => "School","State" => "State");
	}
}
?>