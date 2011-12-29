<?php 
class Load_DidAnswer extends CI_Model {
	
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
	
	//Load
	public function Load(&$DidAnswer,$Id){
		$DidAnswer->Id = $Id;
		$Query = $this->CI->db->query("SELECT * FROM DidAnswer WHERE Id='?'",array($Id));
		foreach($Query->result() as $Row){
			$DidAnswer->UserId = $Row->UserId;
			$DidAnswer->QuestionId = $Row->QuestionId;
		}
	}
}
?>