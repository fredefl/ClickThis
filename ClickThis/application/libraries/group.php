<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Group{
	
	//The Varibales
	public $Id = 0; //The Database id of the Group in the Database
	public $Name = ''; //The Name of the group
	public $Members = array(); //An array of Members
	public $Administrators = array(); //An array of Administrators
	public $Title = ''; //The Title of the Group used on the Group page
	public $Description = ''; //The Description of the Group used on the Group page
	public $Creator = 0; //The Database Id of the Creator of the Group used to show the Creator on the Group Page
	private $CI = ''; //Instance of CodeIgniter
	
	//The Constructor
	public function Group(){
		// Create an instance of CI
		$this->CI =& get_instance();
		$this->CI->load->model("Load_Groups");
	}
	
	//Import
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	//Export
	public function Export($Database = false){
		if(!$Database){
			$data = array(
				'Id' => $this->Id,
				'Name' => $this->Name,
				'Members' => $this->Members,
				'Administrators' => $this->Administrators,
				'Title' => $this->Title,
				'Description' => $this->Description,
				'Creator' => $this->Creator
			);
		}
		else{
			$data = array(
				'Name' => $this->Name,
				'Members' => implode(';',$this->Members),
				'Administrators' => implode(';',$this->Administrators),
				'Title' => $this->Title,
				'Description' => $this->Description,
				'Creator' => $this->Creator
			);	
		}
		return $data;
	}
	
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		$this->CI->Load_Groups->LoadById($Id,$this);;
	}
	
	//Save
	public function Save(){
		$this->CI->load->model('Save_Group');
		$this->CI->Save_Group->Save($this,self::Export(false),'Groups');
	}
	
	//SetDataClass
	public function SetDataClass($Group){
		$this->Administrators = @$Group->Administrators;
		$this->Creator = @$Group->Creator;
		$this->Description = @$Group->Description;
		if(isset($Group->Id)){
			$this->Id = @$Group->Id;
		}
		$this->Members = @$Group->Members;
		$this->Name = @$Group->Name;
		$this->Title = @$Group->Title;
	}
	
	//SetDataArray
	public function SerDataArray($Array){
		$this->Administrators = @$Array['Administrators'];
		$this->Creator = @$Array['Creator'];
		$this->Description = @$Array['Description'];
		if(isset($Array['Id'])){
			$this->Id = @$Array['Id'];
		}
		$this->Members = @$Array['Members'];
		$this->Name = @$Array['Name'];
		$this->Title = @$Array['Title'];
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);
	}
	
	//Clear
	public function Clear(){
		$this->Administrators = array();
		$this->Creator = '';
		$this->Description = '';
		$this->Id = 0;
		$this->Members = array();
		$this->Name = '';
		$this->Title = '';
	}
	
	//Clear Databse
	private function ClearDatabase($Id = 0){
		$this->CI->load->model('Save_Group');
		$this->CI->Save_Group->Delete($Id,'Groups');
	}
	
	//Delete
	public function Delete($Database = false){
		self::Clear();
		if($Database){
			self::ClearDatabase($this->Id);		
		}
	}
	
	//Add
	public function Add($Class,$Database = false){
		if(!is_null($Class)){
			self::SetDataClass($Class);
		}
		else{
			if(!is_null($Array)){
				self::SetDataArray($Array);
			}
			else{
				return "Error Wrong Input";	
			}
		}
		if($Database){
			self::Save();
			return $this->Id;
		}
	}
	
	//Create
	public function Create($Array,$Database = false){
		self::SetDataArray($Array);
		if($Database){
			self::Save();
			return $this->Id;
		}
	}
}
?>