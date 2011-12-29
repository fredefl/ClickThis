<?php
class User_Welcome extends CI_Model {

	 function __construct()
    {
        // Call the Model constructor
		
        parent::__construct();
    }
	//User Model
	public function Welcome($Id){
		$Query = $this->db->query("SELECT * FROM Series WHERE Id=$Id");	
		foreach($Query->result() as $Row){
			
		}		
		return $Row;
	}
	
	//User
	public function User($Id){
		$Query = $this->db->query("SELECT * FROM Users WHERE Userid=$Id");
		foreach($Query->result() as $User){
			
		}	
		return $User;
	}
}
?>