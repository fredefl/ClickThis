<?php
class Save_Answer extends CI_Model{
	
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
	
	//If Exists
	private function Exists($Id){
		$Query = $this->CI->db->query("SELECT * FROM Answers WHERE Id='".$Id."'");
		if($Query->num_rows() == 0){
			return false;
		}
		else{
			return true;
		}
	}
	
	//Save
	public function Save(&$Answer){
		if(self::Exists($Answer->Id)){
			$Query = $this->CI->db->query("UPDATE Answers SET UserId='?',Value='?',QuestionId='?' WHERE Id='?'",array(
				$Answer->UserId,
				$Answer->Value,
				$Answer->QuestionId,
				$Answer->Id		
			));
		}
		else{
			$data = array(
				'UserId' => $Answer->UserId,
				'Value' => $Answer->Value,
				'QuestionId' => $Answer->QuestionId
			);
			$this->CI->db->insert('Answers', $data); 
		}
	}
	
	//Save
	public function Create(&$Answer){
		$data = array(
			'UserId' => $Answer->UserId,
			'Value' => $Answer->Value,
			'QuestionId' => $Answer->QuestionId
		);
		$this->CI->db->insert('Answers', $data); 
		return $this->CI->db->insert_id();
	}
}
?>