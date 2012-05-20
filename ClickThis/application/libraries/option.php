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
	public $id = NULL;

	/**
	 * The option title/option
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $title = NULL;

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
	public $option_type = NULL;

	/**
	 * The id of the parent question
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $question_id = NULL;

	/**
	 * The view order of the option etc 5
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $view_order = NULL;

	/**
	 * The button color
	 * @var string
	 * @since 1.0
	 * @access public
	 */
	public $color = NULL;

	/**
	 * The size of the option
	 * @var integer
	 * @since 1.1
	 * @access public
	 */
	public $size = NULL;

	/**
	 * If the current user has selected this option
	 * then this will contain the value object
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public $value = 0;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "options";

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
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("question_id","title");
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array("option_type" => "type");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id","value");
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
				"user_id" => $UserId,
				"question_id" => $this->question_id
			);
			$Query = $this->_CI->db->select("id")->where($Query)->like("options",";".$this->id.";")->get($Answer->Database_Table);
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				$Value = new Value();
				if($Value->Find(array("option_id" => $this->id,"answer_id" => $Row->id))){
					if((int)$Value->value !== 0){
						$this->value = $Value->value;
					}
				}
			}
		}
		return $Return;
	}
}
?>