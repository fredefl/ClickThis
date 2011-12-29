<?php
class Save_Option extends CI_Model{
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		$this->CI =& get_instance(); //Get The Local instance of CodeIgniter
        parent::__construct();
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
	
	//Save
	public function Save(&$Class,$data = NULL,$Table){
		if(self::Exists($Class->Id,$Table)){
			$this->CI->db->update($Table,$data,array('Id' => $Class->Id));
		}
		else{
			$Query = $this->CI->db->insert($Table,$data);
			$Class->Id = $this->CI->db->insert_id();
		}
	}
	
	//Delete
	public function Delete($Id,$Table){
		$this->CI->db->query("DELETE FROM $Table WHERE Id='?'",array($Id));
	}
}
?>