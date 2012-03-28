<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class will contain the Answer Value
 * @package Answer
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Value
 * @category Surveys
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Value extends Std_Library{

	/**
	 * The database id of this Value
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Id = NULL;

	/**
	 * The database id of the selected option
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $OptionId = NULL;

	/**
	 * The selected value
	 * @access public
	 * @since 1.0
	 * @var string|integer
	 */
	public $Value = NULL;

	/**
	 * The id of the answer that this value is a child of
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $AnswerId = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Values";

	/**
	 * This property contains a pointer to Code Igniter
	 * @var object
	 * @since 1.0
	 * @access private
	 * @internal This is just a local container for Code Igniter
	 */
	private $_CI = NULL;

	public function Value(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI","Id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("OptionId","AnswerId");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
	}
}
?>