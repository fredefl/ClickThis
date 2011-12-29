<?php
class Load_Answers extends CI_Model {
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		//Get The Local Instance
		$this->CI =& get_instance();
        // Call the Model constructor
        parent::__construct();
    }
	
	public function LoadById($Id,&$Answer){
		$Answer->Id = $Id;
		$Query = $this->CI->db->query("SELECT * FROM Answers WHERE Id = ?",array($Id));
		//Loop Through Result
		foreach($Query->result() as $Row){
			$Answer->Value = $Row->Value;
			$Answer->UserId = $Row->UserId;
			$Answer->QuestionId = $Row->QuestionId;
		}
	}
}
?>