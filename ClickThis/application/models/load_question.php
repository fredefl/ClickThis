<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Load_Question extends CI_Model{
	
	//The variables
	private $CI = ''; //An instance of Codde Igniter
	
	//The Constructor
	function __construct()
    {
		//Get The Local Instance
		$this->CI =& get_instance();
		//Load Library
		$this->CI->load->library("Option");
        // Call the Model constructor
        parent::__construct();
    }	
	
	//Load Question By Id
	public function LoadById($Id,&$Question){
		$Question->Id = $Id;
		//Query for results
		$Questions = $this->db->query("SELECT * FROM Questions WHERE Id='{$Id}'");
		//Loop through results
		foreach($Questions->result() as $Row){
			$Question->SerieId = $Row->SeriesId;
			$Question->Title = $Row->Title;
			$Question->Type = $Row->Type;
			$Question->ViewOrder = $Row->ViewOrder;
			$Question->Options = self::GetOptions($Id);
		}
	}
	
	//Get Options
	private function GetOptions($Id){
		$Output = array();
		//Query for options
		$Options = $this->db->query("SELECT * FROM Options WHERE QuestionId='{$Id}'");
		//Loop Through results
		foreach($Options->result() as $Row){
			$Option = new Option();
			$Option->Load($Row->Id);
			$Output[] = $Option;
		}
		//Return
		return $Output;	
	}
}
?>