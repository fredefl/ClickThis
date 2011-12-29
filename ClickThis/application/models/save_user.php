<?php
class Save_User extends CI_Model{
	
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
		$Query = $this->CI->db->query("SELECT * FROM Users WHERE Id='".$Id."'");
		if($Query->num_rows() == 0){
			return false;
		}
		else{
			return true;
		}
	}

	private function GenerateArray(&$User){
		$data = array(
		'Username' => $User->Username,
		'RealName' => $User->Name,
		'Country' => $User->Country,
		'Email' => $User->Email,
		'Type' => $User->Type,
		'Method' => $User->Method,
		'Status' => $User->Status,
		'TargetGroup' => implode(';',$User->TargetGroup),
		'Language' => $User->Language,
		'UserGroup' => implode(';',$User->UserGroup),
		'TOPT' => $User->TOPT,
		'Twitter' => $User->Twitter_Id,
		'Facebook' => $User->Facebook_Id,
		'LinkedIn' => $User->LinkedIn_Id,
		'Google' => $User->Google_Id,
		'Userid' => $User->Illution_Id,
		'OpenId' => $User->OpenId_Token,
		'Flickr' => $User->Flickr_Id,
		'Myspace' => $User->Myspace_Id,
		'Yahoo' => $User->Yahoo_Id,
		'Wordpress' => $User->Wordpress_Id
		);
		return $data;
	}
	
	public function Create(&$User){
		$data = self::GenerateArray($User);
		$this->CI->db->insert('Users', $data); 
		return $this->CI->db->insert_id();
	}
	
	public function Save(&$User){
		if($User->Id != 0){
			$data = self::GenerateArray($User);
			$this->CI->db->update('Users', $data, array('Id' => $User->Id));
		}
		else{
			if(!self::Exists($User->Id)){
				$data = self::GenerateArray($User);
				$this->CI->db->insert('Users', $data);
				$User->Id = $this->CI->db->insert_id();
			}
		}
	}
}
?>