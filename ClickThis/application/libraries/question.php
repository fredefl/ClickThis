<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Question {
	
	//Variables
	public $SerieId = 0; //The of the series the Question is from 
	public $Title = ''; //Question Title
	public $Id = 0; // Question ID
	public $Options = array(); // Array of Options
	public $Type = 0; //Hold the type of the question multiple Choice or Single Choice
	private $CI = ''; //An instance of Codde Igniter
	public $ViewOrder = 0; //ViewOrder In series
	
	// Constructor
	public function Question () {
		//Get the current instance of Code igniter
		$this->CI =& get_instance();
		//Load Option Library
		$this->CI->load->library("Option");
	}
		
	//Load
	public function Load($Id){
		//Check if id is set
		if($this->Id == 0){
			$this->Id = $Id;
		}
		//Load Model
		$this->CI->load->model("Load_Question");
		//Load Questions from Id
		$this->CI->Load_Question->LoadById($Id,$this);
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
		$this->SerieId = 0;
		$this->Title = '';
		$this->Id = 0;
		$this->Options = array();
		$this->Type = 0;
		$this->ViewOrder = 0;
	}
	
	//Export
	public function Export($Database = false){
		if(!$Database){
			$data = array(
				'SerieId' => $this->SerieId,
				'Title' => $this->Title,
				'Id' => $this->Id,
				'Options' => $this->Options,
				'Type' => $this->Type,
				'ViewOrder' => $this->ViewOrder
			);
		}
		else{
			$data = array(
				'SeriesId' => $this->SerieId,
				'Title' => $this->Title,
				'Options' => implode(';',$this->Options),
				'Type' => $this->Type,
				'ViewOrder' => $this->ViewOrder
			);
		}
		return $data;
	}
	
	//Save
	public function Save(){
		$this->CI->load->model('Save_Question');
		$this->CI->Save_Question->Save($this,self::Export(true),'Questions');
	}
	
	//Refresh
	public function Refresh(){
		if($this->Id != 0){
			self::Load($this->Id);
		}
	}
	
	//Clear Databse
	private function ClearDatabase($Id = 0){
		$this->CI->load->model('Save_Question');
		$this->CI->Save_Question->Delete($Id,'Questions');
	}
	
	//Delete
	public function Delete($Database){
		if($Database){
			self::ClearDatabase($this->Id);		
		}
		self::Clear();
	}
	
	//SetDataArray
	private function SetDataArray($Array){
		  $this->Options = $Array['Options'];
		  if(isset($Array['Id'])){
		  	$this->Id = $Array['Id'];
		  }
		  $this->SerieId = $Array['SerieId'];
		  $this->Title = $Array['Title'];
		  $this->Type = $Array['Type'];
		  $this->ViewOrder = $Array['ViewOrder'];
	}
	
	//SetDataClass
	public function SetDataClass($Question){
		$this->Options = $Question->Options;
		if(isset($Question->Id)){
			$this->Id = $Question->Id;
		}
		$this->SerieId = $Question->SerieId;
		$this->Title = $Question->Title;
		$this->Type = $Question->Type;
		$this->ViewOrder = $Question->ViewOrder;	
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