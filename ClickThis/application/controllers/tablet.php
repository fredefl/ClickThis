<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tablet extends CI_Controller {

	private $_Platform = "tablet";

	public function index () {

	}

	public function Survey ($Id = NULL,$Question = NULL) {
		// The survey id is set
		if(!is_null($Id)){
			//An question isn't set, go to question 1
			if(is_null($Question)){
				$Question = 1;
				echo "Id : ",$Id,"<br>";
				echo "Question : ",$Question,"<br>";
			} else { //A question is set go to it
				echo "Id : ",$Id,"<br>";
				echo "Question : ",$Question," and it's set by the url","<br>";
			}
		} else { //No survey is deffined go to home
			redirect($this->_Platform."/home");
		}
	}

	public function Home () {
		echo "You are at home"," and you are using a tablet";
	}

	public function User ($Id = NULL) {
		//User id is deffined
		if(!is_null($Id)){
			echo "User Id : ",$Id;
		} else {
			redirect($this->_Platform."/home");
		}
	}

	public function Profile () {

	}

	public function Settings () {

	}
}