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
	//Standard Page
	public function index()
	{
		$this->load->view('home_view');
		/*
		if(isset($_GET["Page"]))
		{
			$Page = $_GET["Page"];
		}
		
		if(!$this->_Developer($_SESSION['UserId']))
		{
			//$this->load->view('home_view');
		}
		else
		{
			if(isset($Page))
			{
				switch($Page)
				{
					case "Home";
					{
						$this->load->view('home_view');
						break;
					}
					default;
					{
						echo "DumA";
						break;	
					}
				}
			}
			else
			{
				echo "No Page Specified";
			}
		}
		*/
		
	}
	//Test
	public function Main()
	{
		//
		show_error(501);
	}

}
?>