<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class stores data about a state
 * @package School Data
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage State
 * @category School Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 * 
 */  
class State extends Std_Library {

	/**
	 * The database id of the state
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The name of the state, in plain text
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Name = NULL;

	/**
	 * The country the state is located in, in plain text
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "States";

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
	 * A local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 */
	private $_CI = NULL;
	
	/**
	 * This function is the constructor, it create's a local instance of CodeIgniter
	 * @since 1.0
	 * @access public
	 */
	public function State () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("Country" => "Country");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
	}
}
?>