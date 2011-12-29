<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Teacher {
	
	//The Variables
	public $Unilogin = ""; //The Unilogin of the Teacher
	public $School = ""; //The School of the Teacher written in plain text
	public $State = ""; //The State of the Teacher written in text
	public $Country = ""; //The Country of the Teacher written in text
	public $Id = 0; //The Database Id of the Teacher
	public $Name = ""; //The Name of the Teacher
	private $CI = ""; //An Instance of Code Igniter
	
	//The Constructor
	public function Teacher (){
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
	}
	
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		$this->CI->load->model("Load_Teacher"); //Load Model
		$this->CI->Load_Teacher->Id($this); //Load Data
	}
	
	//Import
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	//Clear
	public function Clear(){
		
	}
	
	//Export
	public function Export(){
		$data = array(
			'Unilogin' => $this->Unilogin,
			'School' => $this->School,
			'State' => $this->State,
			'Country' => $this->Country,
			'Id' => $this->Id,
			'Name' => $this->Name
		);
		return $data;
	}
	
	//Save
	public function Save(){
		
	}
	
	//Refresh
	public function Refresh(){
		
	}
	
	//Delete
	public function Delete(){
		
	}
	
	//Add
	public function Add(){
		
	}
	
	//Create
	public function Create(){
		
	}
}
?>