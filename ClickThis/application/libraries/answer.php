<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class loads up answers for a question
 * @package Surveys
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Answer
 * @category Surveys
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Std_Library settings
 */ 
class Answer extends Std_Library{
	
	/**
	 * The database id of this answer
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The selected options
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Options = NULL;

	/**
	 * The database id, of the question that this answer contains too.
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $QuestionId = NULL;

	/**
	 * [$UserId description]
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $UserId = NULL; //If Not annonymouse the use this

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Answers";

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
	 * This property is used to declare link's between other databases and a class property in this class
	 * @var array
	 * @since 1.0
	 * @access public
	 * @example
	 * array("Property" => array("Database table",array("Database row" => "Value or class property name")))
	 * $this->_INTERNAL_LINK_PROPERTIES = array("Questions" => array("Questions",array("SeriesId" => "Id")));
	 * @see Link
	 */
	public static $_INTERNAL_LINK_PROPERTIES = NULL;

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
	 * This property contains a pointer to Code Igniter
	 * @var object
	 * @since 1.0
	 * @access private
	 * @internal This is just a local container for Code Igniter
	 */
	private $_CI = NULL;

	/**
	 * This property is used to link data based on data in an array, and
	 * instead of using the id to load then you can specify a row to use to load from.
	 * @var array
	 * @since 1.1
	 * @access public
	 * @static
	 * @internal This is an internal settings var
	 * @example
	 * array("Property Name" => array("Table","Row"))
	 * @example
	 * $this->_INTERNAL_PROPERTY_LINK = array("Options" => array("Values","OptionId"));
	 */
	public static $_INTERNAL_PROPERTY_LINK = NULL;

	/**
	 * This property is used to abort the Dublicate check if
	 * one of the properties are empty.
	 * @var boolean
	 * @since 1.1
	 * @access public
	 * @static
	 * @internal This is an internal class setting
	 */
	public static $_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = NULL;

	/**
	 * This property is used to force a specific property to be an array
	 * @var array
	 * @static
	 * @access public
	 * @since 1.0
	 * @example
	 * $this->_INTERNAL_FORCE_ARRAY = array("Questions");
	 */
	public static $_INTERNAL_FORCE_ARRAY = NULL;

	/**
	 * This property is used to give a property of each childobject in a property a given value
	 * @var array
	 * @since 1.1
	 * @access public
	 * @static
	 * @internal This is a class settings property
	 * @example
	 * array("Class Property" => array("Property" => "Value or name of property of this class"));
	 */
	public static $_INTERNAL_SAVE_LINK = NULL;
	
	/**
	 * This is the constructor i sets a pointer to CodeOgniter and it sets some settings for the Std_Library
	 * @since 1.0
	 * @access public
	 */	
	public function Answer () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("UserId","QuestionId");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_FORCE_ARRAY = array("Options");
		$this->_INTERNAL_SAVE_LINK = array("Options" => array("AnswerId" => "Id"));
		//$this->_INTERNAL_LINK_PROPERTIES = array("Options" => array("Values",array("OptionId" => "Options")));
		$this->_INTERNAL_PROPERTY_LINK = array("Options" => array("Values","OptionId"));
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("Options" => "Value");	
	}
}
?>