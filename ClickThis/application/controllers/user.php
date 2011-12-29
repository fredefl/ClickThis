<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {
	
	//Standard Page
	public function index(){
		parent::__construct();
		if(!$this->agent->is_mobile($Page = "home")){
			redirect("user/desktop/".$Page);
		}
		else{
			redirect("user/mobile/".$Page);
		}
	}
	
	//Tablet
	public function tablet($page = "home"){
		$page = "tablet_".$page;
		if(method_exists($this,$page))
		{
			$this->$page();
		}
		else
		{
			show_error(404);	
		}
	}
	//Mobile
	public function mobile ($page = "home") {
		$page = "mobile_".$page;
		if(method_exists($this,$page))
		{
			$this->$page();
		}
		else
		{
			show_error(404);	
		}
	}
	
	//Dekstop Main Function
	public function desktop ($page = "home") {
		$page = "desktop_".$page;
		if(method_exists($this,$page))
		{
			$this->$page();
		}
		else
		{
			show_error(404);	
		}
	}
	
	//Mobile Welcome
	public function mobile_welcome(){
		$this->load->view("user_mobile_welcome");
	}
	
	//Desktop Question Welcome
	public function desktop_welcome(){
		$this->load->model("User_Welcome");
		$Series = $this->User_Welcome->Welcome(1);
		$User = $this->User_Welcome->User($Series->Creator);
		//Gets The Creator of This Series
		$Data["Creator"] = $User->Name;
		//Get The Description
		$Data["Description"] = $Series->Description;
		$this->load->view("user_desktop_welcome",$Data);
	}
	
	//Tablet Home
	public function tablet_home(){
		echo "Tablet - Home";	
	}
	
	//Mobile Home Screen
	public function mobile_home() {
		$this->load->view("user_mobile_home");
	}
	
	//Desktop Home Screen
	public function desktop_home () {
		$this->load->view("user_desktop_home");
	}
}