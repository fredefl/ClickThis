<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class load up data about a specific country
 * @package Languages and Countries
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Country
 * @category User Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Country extends Std_Library{

	/**
	 * The database id of the country
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $id = NULL;

	/**
	 * The languages of the country
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $languages = NULL;

	/**
	 * The calling code of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $calling_code = NULL;

	/**
	 * The countrys abbrevation
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $abbrevation = NULL;

	/**
	 * The name of the country
	 * @var string
	 */
	public $name = NULL;

	/**
	 * The upper case name of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $upper_name = NULL;

	/**
	 * A two digit code of the country
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $code = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "countries";
	
	/**
	 * This is the contructor
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"upper_name" => "upper_case_name",
			"name" => "lower_case_name"
		);
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("code","abbrevation");
	}	
}
?>