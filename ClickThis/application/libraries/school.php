<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores information about an educational institute
 * @package School
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage School
 * @category Education
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class School extends Std_Library{

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Schools";

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
	 * The state the school is located in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * The country thes school is located in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	//public $Country = NULL;

	/**
	 * The name of the school
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * The database id of the school
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The abbrevation of the school
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Abbrevation = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @since 1.0
	 * @access private
	 * @var object
	 */
	private $_CI = NULL;
	
	/**
	 * This function is the constructor it creates a local instance of CodeIgniter
	 * @since 1.0
	 * @access public
	 */
	public function School () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"Abbrevation" => "Abbr"
		);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Abbrevation","Name","Country");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Method");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("State" => "State");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}
}
?>