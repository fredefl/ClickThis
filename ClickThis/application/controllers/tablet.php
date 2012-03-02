<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tablet extends CI_Controller {

	public function index () {

	}

	public function Survey ($Id = NULL,$Question = NULL) {
		echo "Id : ",$Id,"<br>";
		echo "Question : ",$Question,"<br>";
	}

	public function Home(){
		echo "You are at home"," and you are using a tablet";;
	}
}