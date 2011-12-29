<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends CI_Controller {
	
	//Standard Page
	public function index(){
		if(!$this->agent->is_mobile($Page = "Home")){
			redirect("admin/desktop/".$Page);
		}
		else{
			redirect("admin/mobile/".$Page);
		}
	}
	
	//Desktop Function
	public function desktop($Page = "Home")
	{
		$Page = "desktop_".$Page;
		if(method_exists($this,$Page))
		{
			$this->$Page();
		}
		else
		{
			show_error(404);	
		}

	}
	
	//Load Topbar
	private function Topbar()
	{
		$this->load->library('Topbar');
		Topbar::setProperties(array(
		array("Logout" => "http://illution.dk/do/Logout.php"),array("Surveys" => "/ClickThis/admin/desktop/Surveys",array("Create" => "/ClickThis/admin/desktop/Create","Edit" => "/ClickThis/admin/desktop/Edit","All" => "/ClickThis/admin/desktop/All"))
		));
	}
	
	//Desktop Surceys
	public function desktop_Surveys()
	{
		$this->Topbar();
		$this->load->view("admin_desktop_home");
	}
	
	//Desktop Create
	public function desktop_Create()
	{
		$this->Topbar();
		$this->load->view("admin_desktop_create");
	}
	
	//Desktop Edit
	public function desktop_Edit()
	{
		$this->Topbar();
		$this->load->view("admin_desktop_home");
	}
	
	//Desktop All
	public function desktop_All()
	{
		$this->Topbar();
		$this->load->view("admin_desktop_home");
	}
	
	//Desktop Home
	public function desktop_Home()
	{
		/*$this->load->library('Topbar');
		Topbar::setProperties(array(
		array("Log Out" => "http://illuiton.dk/do/Logout.php"),array("Surveys" => "Surveys",array("Create" => "Create","Edit" => "Edit","All" => "All"))
		));*/
		$this->Topbar();
		$this->load->view("admin_desktop_home");
		/*if($this->agent->is_mobile())
		{
			Topbar::getStylesheet("mobile");
		}
		else
		{
			//Topbar::getStylesheet("desktop");
			Topbar::getStylesheet(null);
		}*/
		
	}
	
	//Mobile
	public function mobile($Page = "Home")
	{
		$Page = "mobile_".$Page;
		if(method_exists($this,$Page))
		{
			$this->$Page();
		}
		else
		{
			show_error(404);	
		}
	}
	
	//Mobile Home Page
	public function mobile_Home()
	{
		$this->load->view("admin_mobile_home");
		
	}
}