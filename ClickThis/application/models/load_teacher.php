<?php
class Load_Teacher extends CI_Model{
	
	//The Constructor
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	//Load By Id Need to make a load from Teachers DB too
	public function Id(&$Teacher){
		//Query for Id
		$Query = $this->db->query("SELECT * FROM Users WHERE Id='{$Teacher->Id}' AND Method='Teacher'");
		//Loop Through Reseult
		foreach($Query->result() as $Row){
			$Teacher->Unilogin = $Row->Username;
			$Teacher->School = $Row->School;
			$Teacher->State = $Row->State;
			$Teacher->Country = $Row->Country;
			$Teacher->Name = $Row->RealName;	
		}
	}
	
	//Load By Unilogin Deprecated
	public function Unilogin(&$Teacher){
		//Query for Unilogin
		$Query = $this->db->query("SELECT * FROM Users WHERE Username='{$Teacher->Unilogin}' AND Method='Teacher'");
		//Loop Through Reseult
		foreach($Query->result() as $Row){
			$Teacher->Id = $Row->Id;
			$Teacher->School = $Row->School;
			$Teacher->State = $Row->State;
			$Teacher->Country = $Row->Country;
			$Teacher->Name = $Row->Name;	
		}
	}
}
?>