<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class load up data about a specific country
 * @package Languages and Countries
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Country
 * @category User Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Country extends Std_Library{

	/**
	 * The database id of the country
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The languages of the country
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Languages = NULL;

	/**
	 * The calling code of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $CallingCode = NULL;

	/**
	 * The countrys abbrevation
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Abbrevation = NULL;

	/**
	 * The name of the country
	 * @var string
	 */
	public $Name = NULL;

	/**
	 * The upper case name of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $UName = NULL;

	/**
	 * A two digit code of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Code = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Countries";

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
	 * This is an internal property containing a CodeIgniter pointer
	 * @var object
	 * @internal This is a pointer to CodeIgniter
	 * @access public
	 * @since 1.0
	 */
	private $_CI = NULL;
	
	/**
	 * This is the contructor
	 * @since 1.0
	 * @access public
	 */
	public function Country(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"UName" => "UpperCaseName",
			"Name" => "LowerCaseName"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Code","Abbrevation");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}	
}
?>