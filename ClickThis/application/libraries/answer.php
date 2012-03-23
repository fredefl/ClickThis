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
	 * The answer the user has given/the option id the user has selected
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Value = NULL;

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
	 * This property contains a pointer to Code Igniter
	 * @var object
	 * @since 1.0
	 * @access private
	 * @internal This is just a local container for Code Igniter
	 */
	private $_CI = NULL;
	
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
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		//$this->_INTERNAL_LOAD_FROM_CLASS = array("UserId" => "User","QuestionId" => "Question");	
	}
}
?>