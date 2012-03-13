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
	 * The answer the user has given/the option id the user has selected
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Value = NULL;

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

	/**
	 * The database id of this answer
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

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
		
	}
	
	//Load
	/*public function Load($Id){
		$this->CI->load->model("Load_Answers"); //Load Model
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		//Get Data
		$this->CI->Load_Answers->LoadById($Id,$this); //Load Data
	}
	
	//Export As An Array
	public function Export(){
		$Array = array('Value' => $this->Value,'QuestionId' => $this->QuestionId,'UserId' => $this->UserId,'Id' => $this->Id); //Data
		return $Array; //Return
	}
	
	//Save
	public function Save(){
		$this->CI->load->model('Save_Answer'); //Load Model
		$this->CI->Save_Answer->Save($this); //Save The Data
	}
	
	//Remove All User Data
	private function RemoveUserData($Id = false){
		$this->UserId = 0;
		$this->Value = 0;
		$this->QuestionId = 0;
		if($Id){
			$this->Id = 0;
		}
	}
	
	//Remove Data From Database
	private function RemoveDatabaseData($Id = 0){
		$this->CI->db->query("DELETE FROM Answers WHERE Id='?'",array($Id));
	}
	
	//Clear Data From Database
	private function ClearDatabase($Id = 0){
		$this->CI->db->query("UPDATE Answers SET
			UserId='',Value='',QuestionId='' 
			WHERE Id='?'
		 ",array($Id));	
	}
	
	//Delete
	public function Delete($Database = false){
		if($Database){
			self::ClearDatabase($this->Id);
			self::RemoveUserData(true);
			
		}
		else{
			self::RemoveUserData(false);
		}
	}
	
	//Clear
	public function Clear(){
		self::RemoveUserData(false);	
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);
	}
	
	//Set Data By Class
	private function SetDataClass($Answer){
			$this->Id = $Answer->Id;
			$this->UserId = $Answer->UserId;
			$this->Value = $Answer->Value;
			$this->QuestionId = $Answer->QuestionId;
	}
	
	//Set Data By Array
	private function SetDataArray($Array){
			$this->UserId = $Array['UserId'];
			$this->Id =	$Array['Id'];
			$this->Value = $Array['Value'];
			$this->QuestionId = $Array['QuestionId'];
	}
	
	//Add
	public function Add($Answer = NULL,$Array = NULL,$Database = false){
		if(!is_null($Answer)){
			self::SetDataClass($Answer);
		}
		else{
			if(!is_null($Array)){
				self::SetDataArray($Array);
			}
			else{
				return "Error Wrong Input";	
			}
		}
		if($Database){
			$this->CI->load->model('Save_Answer'); //Load Model
			$this->Id = $this->CI->Save_Answer->Create($this); //Save The Data and Get the Returned Id
			return $this->Id;
		}
	}
	
	//Create
	public function Create($Array,$Database = false){
		self::SetDataArray($Array);
		if($Database){
			$this->CI->load->model('Save_Answer'); //Load Model
			$this->Id = $this->CI->Save_Answer->Create($this); //Save The Data and Get the Returned Id
			return $this->Id;
		}
	}*/
}
?>