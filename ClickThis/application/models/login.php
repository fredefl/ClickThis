<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Login extends CI_Model{

	/**
	 * This is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function Login(){
		$this->load->config("api");
	}

	public function Google($Name = NULL,$Email = NULL,$Picture = NULL,$Id = NULL,$Locale = NULL){

	}

	public function User_Exists($Id = NULL,$Provider = NULL){
		if(!is_null($Id)){
			switch ($Provider) {
				case 'clickthis':
					break;
				
				case 'facebook':

					break;

				case 'google':

					break;

				case 'twitter':

					break;

				default:
					# code...
					break;
			}
		} else {
			return FALSE;
		}
	}
}
