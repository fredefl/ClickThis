<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is the didanswer class, it can load data about if a user has answered.
 * @package Survey
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage DidAnswer
 * @category Answers
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Make the Save,Add and Create functions and make a delete function for Database Delete
 */ 
class DidAnswer {
	
	/**
	 * The did answer database id
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = 0; 

	/**
	 * The id of the user that is loaded
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $UserId = 0;

	/**
	 * The id of the question that is answered
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $QuestionId = 0;

	/**
	 * A local Code Igniter instance
	 * @var object
	 * @access private
	 * @since 1.0
	 */
	private $CI = '';
	
	/**
	 * The constructor
	 * @access public
	 * @since 1.0
	 */
	public function DidAnswer () {
		$this->CI =& get_instance();
	}
	
	/**
	 * This function adds data from an array to the different local variables
	 * @param Array $Array An structured array in Name => format
	 * @access public
	 * @example
	 * Import(array("Id" => 1,"QuestionId" => 1,"UserId" => 1));
	 * @since 1.0
	 * @example
	 * Import(array("Id" => 1);
	 */
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	/**
	 * This function returns an array of the class data
	 * @access public
	 * @since 1.0
	 * @return Array The class data as a Name => Value structured array
	 */
	public function Export(){
		$Array = array('Id' => $this->Id, 'UserId' => $this->UserId,'QuestionId' => $this->QuestionId);
		return $Array;
	}
	
	/**
	 * This function loads data from the database and assign it the the corosponding local variables.
	 * @param integer $Id The database id to load
	 * @access public
	 * @since 1.0
	 */
	public function Load($Id){
		if(!$this->Id != 0 || $Id != 0){
			//Check if id is set
			if($this->Id == 0){
				$this->Id = $Id;
			}
			$this->CI->load->model('Load_DidAnswer');
			$this->CI->Load_DidAnswer->Load($this,$Id);
		}
	}
	
	/**
	 * [Save description]
	 * @access public
	 * @since 1.0
	 * @todo Make the function
	 */
	public function Save(){
		
	}
	
	/**
	 * This function delete's all data and if the Database flag is set to true then all database data is deleted too.
	 * @todo Add the Database delete functionality
	 * @param boolean $Database If this flag is set to true then database daa is deleted
	 * @access public
	 * @since 1.0
	 */
	public function Delete($Database = false){
		if($Database){
			
		}
		$this->Id = 0;
		$this->UserId = 0;
		$this->QuestionId = 0;
	}
	
	/**
	 * This function clears the internal data and set it back to the default value
	 * as default the id isn't cleared
	 * @access public
	 * @since 1.0
	 * @param boolean $Id If this is set to true then the Id is cleared too.
	 */
	public function Clear($Id = false){
		if($Id){
			$this->Id = 0;
		}
		$this->UserId = 0;
		$this->QuestionId = 0;
	}
	
	/**
	 * [Add description]
	 * @todo Make the function
	 * @since 1.0
	 * @access public
	 */
	public function Add(){
		
	}
	
	/**
	 * [Create description]
	 * @since 1.0
	 * @access public
	 * @todo Make the function code
	 */
	public function Create(){
		
	}
	
	/**
	 * This function reloades the class data from the database
	 * @since 1.0
	 * @access public
	 */
	public function Refresh(){
		if($this->Id != 0){
			self::Load($this->Id);	
		}
	}
}
?>