<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Answer {
	
	//The Variables
	public $Value = 0; //The Value of the Answer
	public $QuestionId = 0; //The id of the Question the answer is for
	public $UserId = 0; //If Not annonymouse the use this
	public $Id = 0; //The Database id of the Answer
	private $CI = ''; //Instance of CodeIgniter
	
	//The Constructor	
	public function Answer () {
		$this->CI =& get_instance(); // Create an instance of CI
		
	}
	
	//Load
	public function Load($Id){
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
	}
}
?>