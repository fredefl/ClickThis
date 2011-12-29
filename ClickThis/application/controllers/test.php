<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	
	public function index(){
		/*
		if(isset($_SESSION['GoogleLogin'])){
			echo "<pre>";
			print_r($_SESSION['GoogleLogin']);
			echo "</pre>";
		}
		else{
			echo "Error";	
		}
		*/
	}
	public function Series ($Page = 'Test') {
		if($Page == 'Test'){
			self::SeriesTest();
		}
		if($Page == 'Question'){
			self::QuestionTest();
		}
		if($Page == 'Option'){
			self::OptionTest();
		}
		if($Page == 'School'){
			self::SchoolTest();
		}
		if($Page == 'Pupil'){
			self::PupilTest();
		}
		if($Page == 'Answer'){
			self::AnswerTest();	
		}
		if($Page == 'Group'){
			self::GroupTest();
		}
		if($Page == 'State'){
			self::StateTest();
		}
		if($Page == 'User'){
			self::UserTest();
		}
		if($Page == 'Teacher'){
			self::TeacherTest();
		}
		if($Page == 'Country'){
			self::CountryTest();
		}
		if($Page == 'DidAnswer'){
			self::DidAnswerTest();
		}
	}
	
	private function SeriesTest () {
		$this->load->library("Series");	
		$Series = new Series();
		$Series->Load(1);
		echo "<pre>";
		print_r($Series->Debug());
		echo "</pre>";
		echo "<pre>";
		print_r($Series->Questions);
		echo "</pre>";
		$Series->Description = 'Llama';
		$Series->Save();
		$Series2 = new Series();
		$Series2->StartTime = time();
		$Series2->EndTime = time()+60*60*24;
		$Series2->Title = 'The Llama Crap';
		$Series2->Creator = 'Llama Script';
		$Series2->Type = 0;
		$Series2->Description = 'Some crappyyyyyy stuff...';
		$Series2->TargetGroup = 'Illution';
		$Series2->Save();
		
	}
	
	private function OptionTest(){
		$this->load->library('Option');
		$Option = new Option();
		//$Question->Import(array('SerieId' => 1));
		$Option->Load(1);
		echo "<pre>";
		$data = $Option->Export();
		print_r($data);
		echo "</pre>";
	}
	
	private function AnswerTest(){
		$this->load->library('Answer');
		$Answer = new Answer();
		$Answer->Load(1);
		self::Debug($Answer->Export());
	}
	
	private function SchoolTest(){
		$this->load->library('School');
		$School = new School();
	}
	
	private function CountryTest(){
		$this->load->library('Country');
		$Country = new Country();
	}
	
	private function DidAnswerTest(){
		$this->load->library('DidAnswer');
		$DidAnswer = new DidAnswer();
	}
	
	private function GroupTest(){
		$this->load->library('Group');
		$Group = new Group();
	}
	
	private function PupilTest(){
		$this->load->library('Pupil');
		$Pupil = new Pupil();
	}
	
	private function StateTest(){
		$this->load->library('State');
		$State = new State();
		$State->Import(array('Name' => 'Hovedstaden','Country' => 'Denmark'));
		echo "<pre>";
		print_r($State->Export());
		echo "</pre>";
	}
	
	private function TeacherTest(){
		$this->load->library('Teacher');
		$Teacher = new Teacher();
		$Teacher->Load(21);
		//$Teacher->Import(array('Unilogin' => 'boxx1524','School' => 'GVS','State' => 'Hovedstaden','Country' => 'Denmark','Name' => 'Bo Thomsen'));
		self::Debug($Teacher->Export());
	}
	
	private function Debug($Array){
		echo "<pre>";
		print_r($Array);
		echo "</pre>";
	}
	
	//Needs More Work
	private function UserTest(){
		$this->load->library('User');
		$User = new User();
		$User->Load(21);
		$User->Name = 'Llama';
		$User->Country = 'Denmark';
		$User->Save();
		self::Debug($User->Export());
	}
	
	private function QuestionTest(){
		$this->load->library('Question');
		$Question = new Question();
		//$Question->Import(array('SerieId' => 3,'Title' => 'llama','Type' => '4','ViewOrder' => '1','Options' => array('Fisk','And')));
		//$Question->Save();
		$Question->Create(array('SerieId' => 3,'Title' => 'llama','Type' => '4','ViewOrder' => '1','Options' => array('Fisk','And')),true);
		echo "<pre>";
		$data = $Question->Export();
		print_r($data);
		echo "</pre>";
	}

}

?>