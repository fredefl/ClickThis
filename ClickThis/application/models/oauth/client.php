<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Client extends CI_Model{

	public function Client(){
		$this->load->config();
	}

	public function validate($client_id = NULL,$client_secret = NULL){
		if(!is_null($client_id)){
			$query = array(
				"client_id" => $client_id
			);
			if(!is_null($client_secret)){
				$query["client_secret"] = $client_secret;
			}
			$this->db>
		} else {
			return FALSE;
		}
	}
}