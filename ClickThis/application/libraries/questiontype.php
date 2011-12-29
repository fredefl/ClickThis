<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class QuestionType {
	public $None = 0; //The Not Specified
	public $SingleChoice = 1; //The Single Choice Variable
	public $MultipleChoice = 2; //The Multiple Choice Varibale
	public $TextBox = 3; //The Text Area Choice Variable
	public $NumberRange = 4; //The Number Range Selection
	
	//The Constructor
	public function QuestionType (){
		
	}
}
?>