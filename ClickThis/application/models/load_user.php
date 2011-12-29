<?php
class Load_User extends CI_Model{
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		$this->CI =& get_instance();
        parent::__construct();
    }
	
	//Load Users data by Id
	public function LoadById($Id,&$User){
		$UserQuery = $this->db->query('SELECT * FROM Users WHERE Id = ?',array($Id)
		);
		//Loop Through Data
		foreach($UserQuery->result() as $Row){
			$User->TargetGroup = explode(";",$Row->TargetGroup);  //UserGroup
			$User->Name = $Row->RealName; //Name
			$User->Id = $Row->Id; //Id
			$User->Country = $Row->Country; //Country
			$User->Email = $Row->Email; //Email
			$User->Type = $Row->Type; //Type
			$User->Method = $Row->Method; //Method
			$User->Status = $Row->Status; //Status
			$User->Language = $Row->Language; //Language
			$User->UserGroup = explode(";",$Row->UserGroup); //UserGroup
			$User->State = $Row->State; //State
			$User->Username = $Row->Username; //Username
			$User->TOPT = $Row->TOPT; //TOPT
			$User->Facebook_Id = $Row->Facebook; //Facebook_Id
			$User->Twitter_Id = $Row->Twitter; //Twitter_Id
			$User->LinkedIn_Id = $Row->LinkedIn; //LinkedIn_Id
			$User->Google_Id = $Row->Google; //Google_Id
			$User->Illution_Id = $Row->Userid; //Illution_Id
			$User->OpenId_Token = $Row->OpenId; //OpenId_Token
			$User->ClickThis_Id = $Row->Id; //ClickThis_Id
			$User->Flickr_Id = $Row->Flickr; //Flickr_Id
			$User->Myspace_Id = $Row->Myspace; //Myspace_Id
			$User->Yahoo_Id = $Row->Yahoo; //Yahoo_Id
			$User->Wordpress_Id = $Row->Wordpress; //Wordpress_Id
		}
	}
}
?>