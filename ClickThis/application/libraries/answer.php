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
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("UserId","QuestionId");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_FORCE_ARRAY = array("Options");
		$this->_INTERNAL_SAVE_LINK = array("Options" => array("AnswerId" => "Id"));
		$this->_INTERNAL_PROPERTY_LINK = array("Options" => array("Values","OptionId"));
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("Options" => "Value");	
	}
}
?>