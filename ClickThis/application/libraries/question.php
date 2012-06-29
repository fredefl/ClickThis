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
	public $series_id = NULL;

	/**
	 * This property contains the title/question
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $title = NULL;

	/**
	 * This property contains the database id of the question
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $id = NULL;

	/**
	 * This property will contain an array of the options for the question
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $options = NULL;

	/**
	 * Hold the type of the question multiple Choice or Single Choice
	 * 1 = multiplechoice, 2 = single choice
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $type = NULL;

	/**
	 * This property contains the view order of the question, in the series
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $view_order = NULL;

	/**
	 * This property determine if the user
	 * is going to be forced to answer a question
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $force_answer = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "questions";
	
	/**
	 * This is the constructor it do some settings for the Model and std library.
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("title","series_id");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id","options","answers");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("options" => "option","answers" => "answer");
		$this->_INTERNAL_LINK_PROPERTIES = array("options" => array("options",array("question_id" => "id")));
		$this->_INTERNAL_FORCE_ARRAY = array("options");
		$this->_INTERNAL_CONVERT_TO_BOOLEAN = array("force_answer");
	}
}
?>