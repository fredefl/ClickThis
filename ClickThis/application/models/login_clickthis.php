<?php
class Login_Clickthis extends CI_Model{
	
	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	private $Numrows = 0; //Number of Rows
	
	//The Constructor
	function __construct()
    {
		//Get The Local Instance
		$this->CI =& get_instance();
        // Call the Model constructor
        parent::__construct();
    }
	
	public function GetNumrows(){
		return $this->Numrows;	
	}
	
	public function Username($Username){
		$Query = $this->db->query("SELECT * FROM Users WHERE Username=?",array($Username));
		$this->Numrows = $Query->num_rows();
		foreach($Query->result_array() as $Row){
			if(isset($Row['Id'])){
				return $Row;
			}
			else{
				return 'Error';	
			}
		}
	}
}
?>