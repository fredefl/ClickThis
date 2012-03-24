<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	//If Developer
	private function _Developer($User)
	{
		//List of Developers
		$Developers = array(1 => 1,2 => 1);
		if(isset($Developers[$User]))
		{
			return true;
		}else
		{
			return false;	
		}
	}


	public function index()
	{
		if(isset($_SESSION["redirect"])){
			$redirect = $_SESSION["redirect"];
			unset($_SESSION["redirect"]);
			redirect($redirect);
		}
		$this->load->view('home_view');
		
	}
}
?>