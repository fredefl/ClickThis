<?php
class Load_School extends CI_Model {
	
	//The Constructor
	function __construct()
    {
        // Call the Model constructor
		
        parent::__construct();
    }	
	//Load By ID
	public function LoadByID($Id,&$School){
		$School->Id = $Id;
		//Query For The Reference Classes Id And Get all Data in the Database
		$Query = $this->db->query("SELECT * FROM Schools WHERE Id='".$Id."'");
		//Loop through resutl
		foreach($Query->result() as $Row){
			$School->Name = $Row->Name;
			$School->Abbrevation = $Row->Abbr;
			$School->Country = $Row->Country;
			$School->State = $Row->State;
		}
	}
	
	//Get School By Id
	public function getSchoolbyId(&$School){
		//Query For The Reference Classes Id And Get all Data in the Database
		$Query = $this->db->query("SELECT * FROM Schools WHERE Id='{$School->Id}'");
		//Loop through resutl
		foreach($Query->result() as $Row){
			$School->Name = $Row->Name;
			$School->Abbrevation = $Row->Abbr;
			$School->Country = $Row->Country;
			$School->State = $Row->State;
		}
	}
	
	//Get School By Name
	public function getSchoolByName(&$School){
		//Query For The Reference Classes Name And Get all Data in the Database
		$Query = $this->db->query("SELECT * FROM Schools WHERE Name='{$School->Name}'");
		//Loop through resutl
		foreach($Query->result() as $Row){
			$School->Id = $Row->Id;
			$School->Abbrevation = $Row->Abbr;
			$School->Country = $Row->Country;
			$School->State = $Row->State;
		}
	}
	
	//Get School By Abbrevation
	public function getSchoolByAbbrevation(&$School){
			//Query For The Reference Classes Abbrevation And Get all Data in the Database
		$Query = $this->db->query("SELECT * FROM Schools WHERE Abbr='{$School->Abbrevation}'");
		//Loop through resutl
		foreach($Query->result() as $Row){
			$School->Id = $Row->Id;
			$School->Name = $Row->Name;
			$School->Country = $Row->Country;
			$School->State = $Row->State;
		}
	}
}
?>