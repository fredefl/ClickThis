<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class loads up the questions
 * @package Surveys
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Question
 * @category Surveys
 * @version 1.1
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
	public $SeriesId = NULL;

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

	/**
	 * This property determine if the user
	 * is going to be forced to answer a question
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $ForceAnswer = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Questions";

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
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Title","SeriesId");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Options","Answers");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("Options" => "Option","Answers" => "Answer");
		$this->_INTERNAL_LINK_PROPERTIES = array("Options" => array("Options",array("QuestionId" => "Id")));
		$this->_INTERNAL_FORCE_ARRAY = array("Options");
		$this->_INTERNAL_CONVERT_TO_BOOLEAN = array("ForceAnswer");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
	}
}
?>