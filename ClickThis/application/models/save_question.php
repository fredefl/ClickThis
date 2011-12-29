<?php
class Save_Question extends CI_Model{
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		$this->CI =& get_instance(); //Get The Local instance of CodeIgniter
        parent::__construct();
    }
	
	//Save
	public function Save(&$Class,$data,$Table){
		if(self::Exists($Class->Id,$Table)){ //Update Old
			$this->CI->db->update($Table,$data,array('Id' => $Class->Id));
		}
		else{ //Create New Question
			$Query = $this->CI->db->insert($Table,$data);
			$Class->Id = $this->CI->db->insert_id();
		}
	}
	
	//Exists
	private function Exists($Id,$Table){
		if(isset($Id)){
			$Query = $this->CI->db->query("SELECT * FROM $Table WHERE Id='?'",array($Id));
			if($Query->num_rows() == 0){
				return false;
			}
			else{
				return true;	
			}
		}
		else{
			return false;	
		}
	}
	
	//Delete
	public function Delete($Id,$Table){
		$this->CI->db->query("DELETE FROM $Table WHERE Id='?'",array($Id));
	}
}
?>