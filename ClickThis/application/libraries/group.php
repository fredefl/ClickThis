<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores data about a group
 * @package Users
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Group
 * @category Group Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Group extends Std_Library{
	
	/**
	 * The database id of the group, if it's saved
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The name of the group
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * This property stores the id of the members of this grouo
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Members = NULL;

	/**
	 * This property stores the id's of the administrators of this group
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Administrators = NULL;

	/**
	 * This property stores the group's title, displayed on the group page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * This property stores a description of the group, displayed on the groups page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Description = NULL;

	/**
	 * This property stores the database id of the user that created this group
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Creator = NULL;

	#### Class Setttings ####

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Groups";

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
	 * This function is the constructor, it load's the model regarding this class,
	 * and it creates a local instance of CodeIgniter and place it ind the $this->_CI property
	 * @since 1.0
	 * @access public
	 */
	public function Group(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Name","Creator");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
	}
}
?>