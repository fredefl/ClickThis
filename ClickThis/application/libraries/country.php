<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Country {

	//The Variables
	public $Id = 0; //The Database Id of the Country
	public $Name = ""; //The Name of the Country in plain text
	public $Language = ""; //The Language of the Country
	
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
		
	}
	
	//The Constructor
	public function Country () {
		
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
	
}
?>