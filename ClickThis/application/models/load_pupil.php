<?php
class Load_Pupil extends CI_Model{

	//The Variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		$this->CI =& get_instance();
        parent::__construct();
    }
	
	//Load
	public function Load($Id){
		
	}
}
?>