<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Series {

	//Variables
	public $Id = 0; //The Database Id of The Series
	public $Questions = array(); //Array of Questions
	public $StartTime = 0; //The Series Start Time
	public $EndTime = 0; //The Series End Time
	public $Title = ''; //The Series Title
	public $Creator = ''; //The Creator of the Series
	public $Type = 0; //Anonymous or not
	public $Description = ''; //The description of the series 
	public $TargetGroup = ''; // The Group that should recieve the Series
	private $CI = ''; //Instance of CodeIgniter

	
	//The Contructor
	public function Series () {
		// Constructor
		// Create an instance of CI
		$this->CI =& get_instance();
		// Load Question Library
		$this->CI->load->library("Question");
	}
	
	public function Load ($Id) {
		// Query Database
		$Query = $this->CI->db->query("Select * From Series Where Id='$Id'");
		// Get Row
		$Row = $Query->row();
		// Get Variables
		$this->Id = $Row->Id;
		$this->StartTime = $Row->StartTime;
		$this->EndTime = $Row->EndTime;
		$this->Title = $Row->Title;
		$this->Creator = $Row->Creator;
		$this->Type = $Row->Type;
		$this->Description = $Row->Description;
		$this->TargetGroup = $Row->TargetGroup;
		$this->LoadQuestions();
	}
	
	private function LoadQuestions () {
		$Query = $this->CI->db->query("Select (Id) From Questions Where SeriesId = ?", array($this->Id));
		
		foreach ($Query->result() as $Row) {
			$Question = new Question();
			$Question->Load($Row->Id);
			$this->Questions[] = $Question;
		}
	}
	
	public function Save () {
		
		if($this->Id <> 0) {
			$this->CI->db->query("Update Series SET Id = ?, StartTime = ?, EndTime = ?, Title = ?, Creator = ?, Type = ?, Description = ?, TargetGroup = ? Where Id = ?",
								array(	
										$this->Id,
										$this->StartTime,
										$this->EndTime, 
										$this->Title, 
										$this->Creator,
										$this->Type,
										$this->Description,
										$this->TargetGroup,
										$this->Id
									)
							
			);	
		} else {
			$this->CI->db->query("Insert Into Series (StartTime,EndTime,Title,Creator,Type,Description,TargetGroup) Values(?,?,?,?,?,?,?)",
								array(	
										$this->StartTime, 
										$this->EndTime, 
										$this->Title, 
										$this->Creator,
										$this->Type,
										$this->Description,
										$this->TargetGroup
									)
							
			);	

		}
	}
	
	public function Debug () {
		$Array = array();
		$Array['StartTime'] = $this->StartTime;
		$Array['EndTime'] = $this->EndTime; 
		$Array['Title'] = $this->Title;
		$Array['Creator'] = $this->Creator;
		$Array['Type'] = $this->Type;
		$Array['Description'] = $this->Description;
		$Array['TargetGroup'] = $this->TargetGroup;
		return $Array;	
	}
	
}
?>