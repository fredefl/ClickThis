<?php
class Load_Options extends CI_Model{
	
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
	
	//Load By Id
	public function LoadById($Id,&$Option){
		$Option->Id = $Id;
		//Get Data
		$Query = $this->CI->db->query("SELECT * FROM Options WHERE QuestionId='{$Id}'");
		//Loop
		foreach($Query->result() as $Row){
			$Option->QuestionId = $Row->QuestionId;
			$Option->OptionType = $Row->Type;
			$Option->Title = $Row->Title;
			$Option->ViewOrder = $Row->ViewOrder;
		}
	}
}
?>