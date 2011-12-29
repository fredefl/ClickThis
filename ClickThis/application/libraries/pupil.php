<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Pupil {
	
	//The Variables
	public $Class = ""; //The Class/Group of The Pupil
	public $Id = 0; //The Database id of the Pupil
	public $Unilogin = ""; //The Unilogin of the Pupil
	public $Country = ""; //The Country of the Pupil
	public $School = ""; //The School of the Pupil
	public $State = ""; //The State of the Pupil
	public $Name = ""; //The Name of the Pupil
	private $CI = ''; //Instance of CodeIgniter
	
	//The Constructor
	public function Pupil () {
		//Make Instance of CodeIgniter
		$this->CI =& get_instance();
	}
	
	//Import
	public function Import($Array){
		foreach($Array as $Name => $Value){
			if(property_exists($this,$Name)){
				$this->$Name = $Value;	
			}
		}
	}
	
	//Clear
	public function Clear(){
		$this->Class = '';
		$this->Id = 0;
		$this->Unilogin = '';
		$this->Country = '';
		$this->School = '';
		$this->State = '';
		$this->Name = '';
	}
	
	//Export
	public function Export($Database = false){

		if(!$Database){
			$data = array(
				'Id' => $this->Id,
				'Class' => $this->Class,
				'Unilogin' => $this->Unilogin,
				'Country' => $this->Country,
				'School' => $this->School,
				'State' => $this->State,
				'Name' => $this->Name 
			);
		}
		else{
			$data = array(
				'Class' => $this->Class,
				'Username' => $this->Unilogin,
				'Country' => $this->Country,
				'School' => $this->School,
				'State' => $this->State,
				'RealName' => $this->Name,
				'Method' => 'Pupil'
			);	
		}
		return $data;
	}
	
	//Load
	public function Load($Id = 0){
		if($Id != 0){
			$this->Id = $Id;
		}
	}
	
	//SetDataArray
	private function SetDataArray($Array){
		if(isset($Array['Id'])){
			$this->Id = $Array['Id'];
		}
		$this->Class = $Array['Class'];
		$this->Country = $Array['Country'];
		$this->School = $Array['School'];
		$this->State = $Array['State'];
		$this->Name = $Array['Name'];
		if(isset($Array['Unilogin'])){
			$this->Unilogin = $Array['Unilogin'];
		}
	}
	
	//SetDataClass
	private function SetDataClass($Class){
		if(isset($Class->Id)){
			$this->Id = $Class->Id;
		}
		if($Class->Unilogin){
			$this->Unilogin = $Class->Unilogin;
		}
		$this->Class = $Class->Class;
		$this->Country = $Class->Country;
		$this->Name = $Class->Name;
		$this->School = $Class->School;
		$this->State = $Class->State;
	}
	
	//ClearDatabase
	private function ClearDatabase($Id = 0){
		if(isset($Id)){
			$this->CI->load->model('Save_Pupil');
			$this->CI->Save_Pupil->Delete($Id,'Users');
		}
	}
	
	//Save
	public function Save(){
		$this->CI->load->model('Save_Pupil');
		$this->CI->Save_Option->Save($this,self::Export(false),'Users');
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);
	}
	
	//Delete
	public function Delete(){
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