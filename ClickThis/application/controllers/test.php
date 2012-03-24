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
		if($Page == 'Class'){
			self::ClassTest();
		}
	}

	private function ClassTest(){

	}
	
	private function SeriesTest () {

		//Needs more test
		$this->load->library("Series");	
		$Series = new Series();
		$Series->Load(10);
		self::Debug($Series->Export());
		echo "########################################################";
		//self::Debug($Series->Questions[4]->Export(true));
		/*echo "<pre>";
		print_r($Series->Debug());
		echo "</pre>";
		echo "<pre>";
		print_r($Series->Questions);
		echo "</pre>";
		$Series->Description = 'Llama';
		$Series->Save();
		$Series2 = new Series();
		$Series2->StartTime = time();
		$Series2->EndTime = time() + 60 * 60 * 24;
		$Series2->Title = 'The Llama Crap';
		$Series2->Creator = 'Llama Script';
		$Series2->Type = 0;
		$Series2->Description = 'Some crappyyyyyy stuff...';
		$Series2->TargetGroup = 'Illution';
		$Series2->Save();*/
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
		$School->Load(1);
		self::Debug($School->Export(false));
	}
	
	private function CountryTest(){
		$this->load->library('Country');
		$Country = new Country();
		$Country->Load(1);
		self::Debug($Country->Export(false));
	}
	
	private function DidAnswerTest(){
		/*$this->load->library('DidAnswer');
		$DidAnswer = new DidAnswer();*/
	}
	
	private function GroupTest(){
		$this->load->library('Group');
		$Group = new Group();
		$Group->Load(1);
		self::Debug($Group->Export());
	}
	
	private function PupilTest(){
		$this->load->library('Pupil');
		$Pupil = new Pupil();	
		$Pupil2 = new Pupil();

		echo "Load (1) and Save","<br>";
		$Pupil->Load(1);
		$Pupil->Save();
		$Pupil2->Load(2);

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		echo "Clear","<br>";
		$Pupil->Clear();

		$Pupil2 = new Pupil();

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		echo "Create","<br>";
		$Pupil->Create(array("Class" => "9U"));

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		echo "Import","<br>";
		$Pupil->Import(array("Unilogin" => "Llama","Class" => "9U","Name" => "Llama"));

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		$Pupil2->School = "Anskole";
		$Pupil2->Class = "8U";
		$Pupil->Country = "Denmark";

		echo "Normal Export of Pupil2";
		self::Debug($Pupil2->Export(false));

		echo "Adding","<br>";
		$Pupil->Add($Pupil2);

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

		echo "Refreshing","<br>";
		$Pupil->Refresh();

		echo "Normal Export";
		self::Debug($Pupil->Export(false));

		echo "Database Export";
		self::Debug($Pupil->Export(true));

	}
	
	private function StateTest(){
		$this->load->library('State');
		$State = new State();
		$State2 = new State();
		//$State->Import(array('Name' => 'Hovedstaden','Country' => 'Denmark'));
		//$State2->Import(array('Name' => 'Aarhus','Country' => 'Denmark'));
		$State->Load(1);
		$State2->Load(2);
		echo "<pre>";
		print_r($State->Export());
		print_r($State2->Export());
		echo "</pre>";
	}
	
	private function TeacherTest(){
		/*$this->load->library('Teacher');
		$Teacher = new Teacher();
		$Teacher->Load(21);
		//$Teacher->Import(array('Unilogin' => 'boxx1524','School' => 'GVS','State' => 'Hovedstaden','Country' => 'Denmark','Name' => 'Bo Thomsen'));
		self::Debug($Teacher->Export());*/
	}
	
	private function Debug($Array){
		echo "<pre>";
		print_r($Array);
		echo "</pre>";
	}
	
	//Needs More Work
	private function UserTest(){
		$this->load->library('User');
		//$this->load->library('Teacher');
		$User = new User();
		/*$User->Load(21);
		$User->Name = 'Llama';
		$User->Country = 'Denmark';
		$Data = new Teacher();
		$Data->Country = "Denmark";
		//$User->Add($Data);
		$User->Save();*/
		$User->Import(array("Facebook_Id" => 1,"TOPT" => 1),false,true);
		self::Debug($User->Export(false));
	}
	
	private function QuestionTest(){
		$this->load->library('Question');
		$Question = new Question();
		//$Question->Import(array('SerieId' => 3,'Title' => 'llama','Type' => '4','ViewOrder' => '1','Options' => array('Fisk','And')));
		//$Question->Save();
		//$Question->Create(array('SerieId' => 3,'Title' => 'llama','Type' => '4','ViewOrder' => '1','Options' => array('Fisk','And')));
		$Question->Import(array("SeriesId" => 10,"Title" => "Who is this?","Type" => 1,"ViewOrder" => "5"));
		echo "<pre>";
		$Question->Save();
		$data = $Question->Export();
		print_r($data);
		echo "</pre>";
	}

}

?>