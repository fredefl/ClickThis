<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class DidAnswer {
	
	//The Variables
	public $Id = 0; //The Id of the DidAnswer
	public $UserId = 0; //The UserId in the Database
	public $QuestionId = 0; //The QuestionId of The DidAnswer
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	public function DidAnswer () {
		$this->CI =& get_instance(); // Create an instance of CI
	}
	
	//Import
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	//Export
	public function Export(){
		$Array = array('Id' => $this->Id, 'UserId' => $this->UserId,'QuestionId' => $this->QuestionId);
		return $Array;
	}
	
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		$this->CI->load->model('Load_DidAnswer');
		$this->CI->Load_DidAnswer->Load($this,$Id);
	}
	
	//Save
	public function Save(){
		
	}
	
	//Delete
	public function Delete($Database = false){
		if($Database){
			
		}
		$this->Id = 0;
		$this->UserId = 0;
		$this->QuestionId = 0;
	}
	
	//Clear
	public function Clear($Id = false){
		if($Id){
			$this->Id = 0;
		}
		$this->UserId = 0;
		$this->QuestionId = 0;
	}
	
	//Add
	public function Add(){
		
	}
	
	//Create
	public function Create(){
		
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);	
	}
}
?>