<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Option {
	
	//The Variables
	private $CI = ''; //An instance of Code Igniter
	public $Id = 0; // Option ID
	public $Title = ""; //Option Title
	public $OptionType = ""; //The Question type etc checkbox
	public $QuestionId = ""; //The id of the parrent Question
	public $ViewOrder = 0; //Order
	
	//The Constructor
	public function Option () {
		//Make Instance of CodeIgniter
		$this->CI =& get_instance();
		//Load load_options model
		$this->CI->load->model("Load_Options");
	}
	
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		//Call Model to load option data
		$this->CI->Load_Options->LoadById($Id,$this);
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
			$this->Id = 0;
			$this->Title = '';
			$this->OptionType = "";
			$this->QuestionId = 0;
			$this->ViewOrder = 0;
	}
	
	//Export
	public function Export($Database = false){
		if(!$Database){
			$data = array(
				'Id' => $this->Id,
				'Title' => $this->Title,
				'OptionType' => $this->OptionType,
				'QuestionId' => $this->QuestionId,
				'ViewOrder' => $this->ViewOrder
			);
		}
		else{
			$data = array(
				'Title' => $this->Title,
				'Type' => $this->OptionType,
				'QuestionId' => $this->QuestionId,
				'ViewOrder' => $this->ViewOrder
			);	
		}
		return $data;
	}
	
	//Save
	public function Save(){
		$this->CI->load->model('Save_Option');
		$this->CI->Save_Option->Save($this,self::Export(false),'Options');
	}
	
	//Refresh
	public function Refresh(){
		self::Load($this->Id);
	}
	
	//Delete
	public function Delete($Database = false){
		self::Clear();
		if($Database){
			self::ClearDatabase($this->Id);		
		}
	}
	
	//Clear Databse
	private function ClearDatabase($Id = 0){
		$this->CI->load->model('Save_Option');
		$this->CI->Save_Option->Delete($Id,'Options');
	}
	
	//SetDataClass
	public function SetDataClass($Class){
		if(isset($Class->Id)){
			$this->Id = $Class->Id;
		}
		$this->Title = $Class->Id;
		$this->OptionType = $Class->OptionType;
		$this->QuestionId = $Class->QuestionId;
		$this->ViewOrder = $Class->ViewOrder;
	}
	
	//SetDataArray
	public function SetDataArray($Array){
		if(isset($Array['Id'])){
			$this->Id = $Array['Id'];
		}
		$this->Title = $Array['Title'];
		$this->OptionType = $Array['OptionType'];
		$this->QuestionId = $Array['QuestionId'];
		$this->ViewOrder = $Array['ViewOrder'];
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