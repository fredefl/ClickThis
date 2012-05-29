<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller {

	public function index(){
		$this->load->library("Answer");
		$Answer = new Answer();
		$Array = array("question_id" => 12, "options" => array(array("option_id" => 5,"value" => 1),array("option_id" => 6,"value" => 1)));
		$Answer->Import($Array);
		echo "<pre>";
		print_r($Answer->Export(false));
		echo "</pre>";
	}

	public function export(){
		/*$this->load->library("Series");
		$Series = new Series();
		$Series->Load(31);
		echo "<pre>";
		print_r($Series->Export(false,true));
		echo "</pre>";
		$this->load->library("Series");
		$Series = new Series();
		$Series->Load(31);
		$this->load->helper("array_xml");
		//header("Content-Type: application/xml");
		$xml = array_to_xml($Series->Export(false,true));
		$array = xml_import($xml);
		$Series2 = new Series();
		$Series2->Import(array("questions" => array(array("title" => "and"))));
		echo "<pre>";
		print_r($Series2->Export(false,true));
		echo "</pre>";*/
	}
}
?>