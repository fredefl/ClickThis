<?php
class Load_Groups extends CI_Model {
	
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
	public function LoadById($Id,&$Group){
		$Group->Id = $Id;
			
	}
}
?>