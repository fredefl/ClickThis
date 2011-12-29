<?php
class Load_User extends CI_Model{
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		$this->CI =& get_instance(); //Get The Local instance of CodeIgniter
        parent::__construct();
    }
	
	//Exists
	private function Exists(&$Teacher){
		if(!$Teacher->Id == 0){
			$Query = $this->CI->db->query("SELECT * FROM Users WHERE Id='?' AND Method='Teacher'",array($Teacher->Id));
			if($Query->num_rows() == 0){
				return false;
			}
			else{
				return true;	
			}
		}
		else{
			$Query = $this->CI->db->query("SELECT * FROM Users WHERE Username='?' AND Method='Teacher'",array($Teacher->Unilogin));
			if($Query->num_rows() == 0){
				return false;
			}
			else{
				return true;	
			}
		}
	}
	
	//Save
	public function Save(&$Teacher){
		if(self::Exists($Teacher)){
			$this->CI->db->query("UPDATE Users SET Username='?',School='?',State='?',Country='?',Name='?' WHERE (Id='?' OR Username='?') AND Method='Teacher'",array(
				$Teacher->Unilogin,
				$Teacher->School,
				$Teacher->State,
				$Teacher->Country,
				$Teacher->Name,
				$Teacher->Id,
				$Teacher->Unilogin
			));
		}
		/*else{
			$this->CI->db->query("INSERT INTO Users (Username,School,State,Country,Name) VALUES(?,?,?,?,?)",array(
				$Teacher->Unilogin,
				$Teacher->School,
				$Teacher->State,
				$Teacher->Country,
				$Teacher->Name
			));
		}*/
	}
}
?>