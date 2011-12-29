<?php
class Save_Group extends CI_Model{
	
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
	public function Save(&$Group,$data = NULL){
		if(is_null($data)){
			$data = array(
				'Name' => $Group->Name,
				'Members' => $Group->Members,
				'Title' => $Group->Title,
				'Description' => $Group->Description,
				'Creator' => $Group->Creator,
				'Administrators' => $Group->Administrators ,
			);
		}
		if(self::Exists($Group->Id,'Groups')){
			$this->CI->db->update('Groups',$data,array('Id' => $Group->Id));
		}
		else{
			$Query = $this->CI->db->insert('Groups',$data);
			$Group->Id = $this->CI->db->insert_id();
		}
	}
	
	//Delete
	public function Delete($Id,$Table){
		$this->CI->db->query("DELETE FROM $Table WHERE Id='?'",array($Id));
	}
}
?>