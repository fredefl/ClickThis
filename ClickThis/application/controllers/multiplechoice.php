<?php
class Multiplechoice extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		// Load the Mobilbe Buttons library
		$this->load->library('MobileButtons');
		$this->load->library('GetAsset');
		// Initialize variable
		$Buttons = '';
		// Create buttons, one by one
		$Buttons .= MobileButtons::NewButton(1,0,'green','Atletik',true,false);
		$Buttons .= MobileButtons::NewButton(2,0,'green','Badminton',true,false);
		$Buttons .= MobileButtons::NewButton(3,0,'green','Fodbold',true,false);
		$Buttons .= MobileButtons::NewButton(4,0,'green','Håndbold',true,false);
		$Buttons .= MobileButtons::NewButton(5,0,'green','Skydning',true,false);
		$Buttons .= MobileButtons::NewButton(6,0,'green','Svømning',true,false);
		$Buttons .= MobileButtons::NewButton(7,0,'green','Tennis',true,false);
		$Buttons .= MobileButtons::NewButton(8,0,'green','Volleyball',true,false);
		$Buttons .= MobileButtons::NewButton(9,0,'gold','Andre',true,false);
		$Buttons .= MobileButtons::NewButton(10,0,'red','Ingen',true,true);
		$Buttons .= MobileButtons::NewSubmitButton('orange','Submit!');
		
		$data = array(
				'Buttons' => $Buttons
		);
		
		
		$this->load->view('multiplechoice',$data);
	}
		
}