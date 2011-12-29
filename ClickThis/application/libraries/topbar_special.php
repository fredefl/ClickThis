<?php
include "topbarobject.php";
class Topbar_Special
{
	private $Topbar = s;
	//The Contructor
	public function Topbar()
	{
		$this->Topbar = new TopbarObject;
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
	
	//Create Topbar
	public function createTopbar($Topbar)
	{	
		$this->Topbar = $Topbar;
		//Call The create function
		echo self::outputHtml(self::create());
	}
	
	//Ob out
	public function outputHtml($Html)
	{	
		//echo $Html;
		return $Html;
	}
	
	//Create the Code
	private function create()
	{
		//Reset Html
		$Html = "";
		//Standard
		$Html .= "<div id='".$Topbar->_Id."'>";
		$Html .= "<div class='".$Topbar->_Class."'>";
		$Html .= "<ul>";
		########################################################
		foreach($this->_Map as $Name => $Array){
			if($Array[1] == "Parent"){
				$Html .= self::makeParents(self::makeChildrens($Topbar->_Map,$Name),$Name,$Array[0]);
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
}
?>