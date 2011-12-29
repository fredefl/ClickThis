<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OneTimePasswordTest extends CI_Controller {
	
	public function index(){
		// Some stuff....
	}
	
	public function php(){
		date_default_timezone_set("UTC");
		$Settings = array(
			'Algorithm' => 'sha1',		// The hash algorithm
			'Digits' => '6', 			// The number of digits the output should contain
			'Key' => '12345678901234567890', 	// The secret key
			'Timestamp' => time(), 		// The current time
			'InitialTime' => '0', 		// I dunno
			'TimeStep' => '30', 		// Time in seconds a key should last
			'TimeWindowSize' => '1'		// I dunno	
		);
		
		$this->load->library("onetimepassword");
		// Echo out key
		$this->output->set_output("<meta http-equiv='refresh' content='5'/>TOTP one-time-password: <b>" . OneTimePassword::GetPassword($Settings) . "</b><br>");	
	}
	
	public function js() {
		$this->load->view("topt");
	}


}

?>