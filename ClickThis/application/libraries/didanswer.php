<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is the didanswer class, it can load data about if a user has answered.
 * @package Survey
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage DidAnswer
 * @category Answers
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Port this to the Std_Library class
 */ 
class DidAnswer extends Std_Library{
	
	/**
	 * The did answer database id
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL; 

	/**
	 * The id of the user that is loaded
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $UserId = NULL;

	/**
	 * The id of the question that is answered
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $QuestionId = NULL;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "DidAnswer";

	/**
	 * The local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 * @internal This is a local instance of CodeIgniter it's only used in the class
	 */
	private $_CI = NULL;
	
	/**
	 * The constructor
	 * @access public
	 * @since 1.0
	 */
	public function DidAnswer () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("UserId","QuestionId");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
	}
}
?>