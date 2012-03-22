<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores information about a teacher
 * @package School
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Teacher
 * @category Education
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Teacher {
	
	/**
	 * The Unilogin of the teacher, if it exists
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Unilogin = NULL;

	/**
	 * The educational instridute where the teacher teaches
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $School = NULL;

	/**
	 * The state where the teacher lives
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * The country of the Teacher
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	/**
	 * The database id of the teacher
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The name of the teacher
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Name = NULL;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Teachers";

	/**
	 * This property contains the database model to use
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public static $_INTERNAL_DATABASE_MODEL = NULL;

	/**
	 * This property can contain properties to be ignored, when the database flag is true in export.
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_DATABASE_EXPORT_INGNORE = NULL;

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
	 * The local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 * @internal This is a local instance of CodeIgniter it's only used in the class
	 */
	private $_CI = NULL;
	
	public function Teacher (){
		$this->_CI =& get_instance();
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_LOAD_FROM_CLASS = array("Country" => "Country","State" => "State","School" => "School");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Questions");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
	}
}
?>