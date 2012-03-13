<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Series extends Std_Library{

	/**
	 * The database id of the series
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Id = NULL;

	/*
	 * An array of the questions of the Series, in objects
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Questions = NULL;

	/**
	 * The start time of the series, when it's going to be published
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $StartTime = NULL;

	/**
	 * The time that the series will end
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $EndTime = NULL;

	/**
	 * The surveys title
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * The database id of the creator
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Creator = NULL;

	/**
	 * If the series is anonymous or not
	 * @var integer
	 * @since 1,0
	 * @access public
	 */
	public $Type = NULL;

	/**
	 * The series description, will be shown in the series view
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Description = NULL; 

	/**
	 * The groups that is going to be able see the series
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $TargetGroup = NULL;

	/**
	 * This property can be used to share a series with only peoples instead
	 * of groups or just some extra people that isn't in the TargetGroups
	 * @var array
	 * @since 1.0
	 * @todo Add this property to the Database
	 * @access public
	 */
	public $TargetPeople = NULL;

	/**
	 * The text to show in the beginning of the series
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $StartText = NULL;

	/**
	 * The text to show in the end of the survey
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $EndText = NULL;

	/**
	 * If the sruvey is going to be shared public, private or with link.
	 * 0 is not shared, 1 is public, 2 is private and 3 is with link
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $ShareType = NULL;

	/**
	 * The local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 * @internal This is a local instance of CodeIgniter it's only used in the class
	 */
	private $_CI = NULL;

	/**
	 * The constructor, it sets some settings for the std_library to work
	 */
	public function Series () {
		$this->_CI =& get_instance();
		//$this->CI->load->library("Question");
		
	}
	
	/*public function Load ($Id) {
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
	}*/
	
	/*public function Debug () {
		$Array = array();
		$Array['StartTime'] = $this->StartTime;
		$Array['EndTime'] = $this->EndTime; 
		$Array['Title'] = $this->Title;
		$Array['Creator'] = $this->Creator;
		$Array['Type'] = $this->Type;
		$Array['Description'] = $this->Description;
		$Array['TargetGroup'] = $this->TargetGroup;
		return $Array;	
	}*/
}
?>