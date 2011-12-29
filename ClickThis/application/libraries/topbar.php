<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Topbar
{
	//Private Member Fields
	private $_MenuItems = array();
	private $_Color = array();
	private $_Background = null;
	private $_Height = null;
	private $_Id = null;
	private $_Map = array();
	private $_Class = null;
	//The Contructor
	public function Topbar()
	{
	}
	
	//StyleSheet
	
	public function getStylesheet($Device = null,$Baseurl,$Service = "admin"){
		if(!is_null($Device)){
			strtolower($Device);
			
			return '<link rel="stylesheet" href="'.$Baseurl.'assets/'.$Service.'_'.$Device.'_style.php"/>';
		}
		else{
			return '<link rel="stylesheet" href="'.$Baseurl.'assets/'.$Service.'_mobile_style.php"/>';
		}
		
	}
	
	//Debug
	public function Debug(){
		echo "MenuItems:";
		print_r($this->_MenuItems);
		echo ",";
		echo "Color:";
		print_r($this->_Color);
		echo ",";
		echo "Background:".$this->_Background,",";
		echo "Height:".$this->_Height,",";	
	}
	
	//Create Topbar
	public function createTopbar($Id = null,$Class = null)
	{
		//Reset
		$this->_Id = "chat_box";
		$this->_Class = "menu";
		$this->_Map = array();
		//Check Inputs
		if(!is_null($Id)){
			$this->_Id = $Id;
		}
		if(!is_null($Class)){
			$this->_Class = $Class;
		}
		//Call The create function
		echo self::outputHtml(self::create($this->_MenuItems));
	}
	
	//Get Previus
	private function getPrevius($Array,$Current){
		//Make Variables
		$Previus = "";
		foreach($Array as $Name => $Arr){
			if($Name == $Current){
				return $Name;
			}
			$Previus = $Name;
		}
		if($Previus == ""){
			return false;	
		}
	}
	
	//Sub
	private function subArray($Array,$Parent){
		foreach($Array as $Type => $Link){
			if(!is_array($Link)){
				$this->_Map[$Type] = array($Link,$Parent);
			}
			else{
				$SubParent = self::getPrevius($Array,$Type);
				
				foreach($Link as $Name => $Value){
					if(is_array($Value)){
						self::subArray($Value,$Name);	
					}
					else{
						$this->_Map[$Name] = array($Value,$SubParent);
					}
					//array($Value,$SubParent);
				}
			}
			
		}
	}
	//Maparray
	private function mapArray($MenuItems){
		foreach($MenuItems as $Item){
			foreach($Item as $Name => $Value){
				if(!is_array($Value)){
					$Parent = $Name;
					$this->_Map[$Name] = array($Value,"Parent");;
				}
				else{
					self::subArray($Value,$Parent);
				}
			}
		}
	
	}
	
	//Make Childrens
	private function makeChildrens($Array,$Parent){
		//Create Variables
		$Html = "";
		################################################################
		$Html .= "<ul>";	
		foreach($Array as $Name => $Arr){
			if($Arr[1] == $Parent){	
					
				$Html .= self::makeParents(self::makeChildrens($Array,$Name),$Name,$Arr[0]);		
			}
		}
		$Html .= "</ul>";
		################################################################
		//Return
		return $Html;
	}
	
	//Make Parents
	private function makeParents($Childrens,$Name,$Link){
		//Create Variables
		$Html = "";
		#################################################################
		$Html .= "<li>";
		$Html .= '<a href="'.$Link.'">'.$Name.'</a>';
		$Html .= $Childrens;
		$Html .= "</li>";
		#################################################################
		//Return
		return $Html;
	}

	//Map Elements
	private function map($MenuItems){
		self::mapArray($MenuItems);
		//Return Output
	}
	
	//Create the Code
	private function create($MenuItems)
	{
		//Reset Html
		$Html = "";
		//Standard
		$Html .= "<div id='".$this->_Id."'>";
		$Html .= "<div class='".$this->_Class."'>";
		$Html .= "<ul>";
		########################################################
		self::map($MenuItems);
		foreach($this->_Map as $Name => $Array){
			if($Array[1] == "Parent"){
				$Html .= self::makeParents(self::makeChildrens($this->_Map,$Name),$Name,$Array[0]);
			}
		}
		########################################################
		//Standard End
		$Html .= "</ul>";
		$Html .= "</div>";
		$Html .= "</div>";

		//Outputs The Html
		return $Html;
	}
	
	//Ob out
	public function outputHtml($Html)
	{	
		//echo $Html;
		return $Html;
	}
			
	//Set Start Properties
	public function setProperties($MenuItems = null,$Color = null,$Background = null,$Height = null){
		//Background
		if(is_null($Background)){
			$this->_Background = null;
		}
		else{
			$this->_Background = $Background;	
		}
		//Height
		if(is_null($Height)){
			$this->_Height = null;
		}
		else{
			$this->_Height = $Height;	
		}
		//Color
		if(is_null($Color)){
			$this->_Color = null;
		}
		else{
			$this->_Color = $Color;
		}
		//MenuItems
		if(is_null($MenuItems)){
			$this->_MenuItems = null;
		}
		else{
			$this->_MenuItems = $MenuItems;
		}
	}
	//Get Color
	public function getColor(){
		if(!is_null($this->_Color)){
		return $this->_Color;	
		}
		else{
			return false;
		}
	}
	
	//Get Height
	public function getHeight(){
		if(!is_null($this->_Height)){
			return $this->_Height;
		}
		else{
			return false;	
		}
	}
	
	//Get Background
	public function getBackground(){
		if(!is_null($this->_Background)){
			return $this->_Background;	
		}
		else{
			return false;	
		}
	}
	
	//Get Menu Items
	public function getMenuItems(){
		if(!is_null($this->_MenuItems)){
			return $this->_MenuItems;
		}
		else{
			return false;	
		}
	}
	
	//Set Background
	public function setBackground($Value){
			if(!is_null($Value))
			{
				$this->_Background = $Value;
			}
			else{
				return false;	
			}
	}
	
	//Set MenuItems
	public function setMenuItems($Value){
			if(!is_null($Value))
			{
				$this->_MenuItems = $Value;
			}
			else{
				return false;	
			}	
	}
	
	//Set Color
	public function setColor($Value){
			if(!is_null($Value))
			{
				$this->_Color = $Value;
			}
			else{
				return false;	
			}	
	}
	
	//Set Height
	public function setHeight($Value){
		if(!is_null($Value)){
			$this->_Height = $Value;
		}
		else{
			return false;
		}
	}
}
	
?>