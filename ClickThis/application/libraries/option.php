<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Yhis class stores an option for a question
 * @package Survey
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Option
 * @category Survey
 * @version 1.1
 * @author Illution <support@illution.dk>
 */ 
class Option extends Std_Library{

	/**
	 * The database id of the option
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The option title/option
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * The option type etc Multiple choice,single choice, form field
	 * @example
	 * 	Multiple Choice = 1
	 *	Single Choice = 2
	 *	Text Field Multiplechoice = 3
	 *  Text Field single choice = 4
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $OptionType = NULL;

	/**
	 * The id of the parent question
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $QuestionId = NULL;

	/**
	 * The view order of the option etc 5
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $ViewOrder = NULL;

	/**
	 * The button color
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $Color = NULL;

	/**
	 * The size of the option
	 * @var integer
	 * @since 1.1
	 * @access public
	 */
	public $Size = NULL;

	/**
	 * If the current user has selected this option
	 * then this will contain the value object
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public $Value = 0;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Options";

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
	 * This property is used to deffine a set of rows that is gonna be
	 * unique for this row of data
	 * @var array
	 * @access public
	 * @since 1.1
	 * @static
	 * @internal This is a internal settings variable
	 * @example
	 * array("SeriesId","Title");
	 */
	public static $_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = NULL;

	/**
	 * A local pointer to CodeIgniter
	 * @var object
	 * @internal This is a local pointer to the CodeIgniter object, it's only used internaly
	 * @access public
	 * @since 1.0
	 */
	private $_CI = NULL;
	
	/**
	 * The constructor
	 * @access public
	 * @since 1.0
	 */
	public function Option () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("QuestionId","Title");
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array("OptionType" => "Type");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Value");
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}

	/**
	 * This function adds the user id property
	 * to the Load function so it's possible to
	 * load the answers for a specific question
	 * @param integer  $Id    The id of the question to load
	 * @param boolean $Simple If the Simple load property should be turned on
	 * @param integer  $UserId The current user
	 * @since 1.0
	 * @access public
	 */
	public function Load($Id = NULL,$Simple = false,$UserId = NULL){
		$Return = parent::Load($Id,$Simple);
		$this->_CI->load->library("Answer");
		$this->_CI->load->library("Value");
		$Answer = new Answer();
		if(!is_null($UserId) && $UserId != "" && is_integer($UserId)){
			$Query = array(
				"UserId" => $UserId,
				"QuestionId" => $this->QuestionId
			);
			$Query = $this->_CI->db->select("Id")->where($Query)->like("Options",";".$this->Id.";")->get($Answer->Database_Table);
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$Value = new Value();
				if($Value->Find(array("OptionId" => $this->Id,"AnswerId" => $Row->Id))){
					if((int)$Value->Value !== 0){
						$this->Value = $Value->Value;
					}
				}
			}
		}
		return $Return;
	}
}
?>