<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class State {

	//The Variables
	public $Id = 0; //The Database Id of the State
	public $Name = ""; //The Name of the State written in plain text
	public $Country = ""; //The Country of the State written in plain text
	private $CI = ""; //An Instance of Code Igniter
	
	//The Constructor
	public function State () {
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
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
		$data = array(
			'Id' => $this->Id,
			'Name' => $this->Name,
			'Country' => $this->Country 
		);
		return $data;
	}
	
	//Load
	public function Load(){
		
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
	
	//Clear
	public function Clear(){
		
	}
	
}
?>