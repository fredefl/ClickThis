<?php
class Facebook_test extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->model('Facebook_model');
	}
	
	function index()
	{
		$fb_data = $this->session->userdata('fb_data');
		if(!$fb_data['me']) {
			// Not logged in
			header("Location: {$fb_data['loginUrl']}");
		} else {
			// If logged in
			$data = array(
						'fb_data' => $fb_data,
						);
			
			$this->load->view('facebook_test', $data);
		}
	}
	
}
?>