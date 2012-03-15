<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class loads up the questions
 * @package Surveys
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Question
 * @category Surveys
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Load up the options and answers if available
 */ 
class Question extends Std_Library{
	
	/**
	 * The id of the series, the question belongs too
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $SerieId = NULL;

	/**
	 * This property contains the title/question
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * This property contains the database id of the question
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Id = NULL;

	/**
	 * This property will contain an array of the options for the question
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Options = NULL;

	/**
	 * Hold the type of the question multiple Choice or Single Choice
	 * 1 = multiplechoice, 2 = single choice
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Type = NULL;

	/**
	 * This property contains the view order of the question, in the series
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $ViewOrder = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Questions";

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
	 * This is an internal property containing a CodeIgniter pointer
	 * @var string
	 * @internal This is a pointer to CodeIgniter
	 * @access public
	 * @since 1.0
	 */
	private $_CI = NULL;
	
	/**
	 * This is the constructor it do some settings for the Model and std library.
	 * @since 1.0
	 * @access public
	 */
	public function Question () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"SerieId" => "SeriesId"
		);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);	
	}
}
?>