<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class School {

	//The Variables
	public $State = ""; //The State of the School written in plain text
	public $Country = ""; //The Country of the School written in plain text
	public $Name = ""; //The Name of the School written in plain text
	public $Id = 0; //The Database Id of the School
	public $Abbrevation = ""; //The Abrrevation of the School
	private $CI = ""; //An Instance of Code Igniter
	
	//The Constructor
	public function School () {
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
	}
	
	//Get School By Id
	public function getSchoolById(){
		$this->CI->load->model("Load_School");
		//Load School Data
		$this->CI->Load_School->getSchoolById($this);
	}
	
	//Get School By Name
	public function getSchoolbyName(){
		$this->CI->load->model("Load_School");
		$this->CI->Load_School->getSchoolByName($this);
	}
	
	//Get School by Abbrevation
	public function getSchoolByAbbrevation(){
		$this->CI->load->model("Load_School");
		$this->CI->Load_School->getSchoolByAbbrevation($this);
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
		$Output = array("State" => $this->State,"Country" => $this->Country,"Id" => $this->Id,"Name" => $this->Name,"Abbrevation" => $this->Abbrevation);
		return $Output;	
	}
	
	//Save
	public function Save(){
		
	}
	
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		self::getSchoolById();
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
	
	//Clear
	public function Clear(){
		
	}
}
?>