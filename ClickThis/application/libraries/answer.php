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
	public $id = NULL;

	/**
	 * The selected options
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $options = NULL;

	/**
	 * The database id, of the question that this answer contains too.
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $question_id = NULL;

	/**
	 * The id of the user that has answered if not anynomous
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $user_id = NULL; //If Not annonymouse the use this

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "answers";

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
	public function __construct(){
		parent::__construct();
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("user_id","question_id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS_ABORT_ON_NULL = true;
		$this->_INTERNAL_FORCE_ARRAY = array("options");
		$this->_INTERNAL_SAVE_LINK = array("options" => array("answer_id" => "id"));
		$this->_INTERNAL_PROPERTY_LINK = array("options" => array("values","option_id"));
		$this->_INTERNAL_LOAD_FROM_CLASS = array("options" => "Value");	
	}
}
?>