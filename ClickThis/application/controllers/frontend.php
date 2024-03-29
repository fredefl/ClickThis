<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Frontend extends CI_Controller {

	/**
	 * The current device of the device
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Platform = NULL;

	/**
	 * This function determine what type of page
	 * is going to be showed to the user.
	 * @param  string $Platform An optional platform to force a
	 * specific platform for the user
	 * @param  array  $Params   An array of extra parameters send to the child function
	 * @return function
	 * @since 1.0
	 * @access public
	 */
	public function _remap($Platform = NULL,$Params = array()) {
		if(is_null($Platform) || $Platform === "index"){
			$this->load->view("intro_view");
			return;
		} else {
			if(!in_array(strtolower($Platform), array("mobile","desktop"))){
				$Platform = 'desktop';
			}
		}
		if(isset($_SESSION["platform"])){
			if(!in_array(strtolower($_SESSION["platform"]), array("mobile","desktop"))){
				$Platform = 'desktop';
			} else {
				$Platform = $_SESSION["platform"];
			}
		}
		$this->_Platform = $Platform;
		if(method_exists($this, "_".ucfirst($Platform))){
			return call_user_func_array(array($this, "_".ucfirst($Platform)), $Params);
		} else {
			$this->load->view("intro_view");
			return;
		}
	}

	/**
	 * This function minifies all output send from this controller
	 * @since 1.1
	 * @access public
	 */
	public function _output(){
		$content = $this->output->get_output();
		$mini = $this->minify->Html($content);
		$this->output->set_output($mini);
		echo $this->output->get_output();
	}

	/**
	 * This function detects the users platform
	 * @since 1.0
	 * @access private
	 * @return string
	 */
	private function _Detect_Platform(){
		$this->load->library('user_agent');
    	if($this->agent->is_mobile()){
    		$Platform = 'mobile';
    	} else {
			$Platform = 'desktop';
    	}
    	return $Platform;
	}

	private function _Mobile() {
		$this->load->view("mobile_view");
	}

	private function _Desktop() {
		echo $_SESSION["UserId"];
		$this->load->view("desktop_view");
	}
}
?>