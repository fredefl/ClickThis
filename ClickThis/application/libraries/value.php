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
	public $id = NULL;

	/**
	 * The database id of the selected option
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $option_id = NULL;

	/**
	 * The selected value
	 * @access public
	 * @since 1.0
	 * @var string|integer
	 */
	public $value = NULL;

	/**
	 * The id of the answer that this value is a child of
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $answer_id = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "values";

	public function __construct(){
		parent::__construct();
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI","Id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("option_id","answer_id");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id");
	}
}
?>