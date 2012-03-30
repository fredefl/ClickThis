<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Token extends CI_Controller {

	public function index(){
		if(isset($_SESSION["UserId"])){
			if(isset($_SESSION["clickthis_token"])){
				redirect("home");
			} else {
				$this->load->library("api_authentication");
				if($this->api_authentication->ClickThis_Token(3)){
					$_SESSION["clickthis_token"] = $this->api_authentication->Get("ClickThis_Token");
					redirect("home");
				} else {
					redirect("login");
				}
			}
		} else {
			redirect("login");
		}
	}
}