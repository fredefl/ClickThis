<?php
/**
 * This library ensures that the user is logged in at the sections where it's needed
 * @package Security
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage LoginSecurity
 * @category Library
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class clickthis_security {	
	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This property stores the folder of ClickThis
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Folder =  NULL;

	/**
	 * This property stores the current page
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $_Page = NULL;

	/**
	 * This user id of the current user, if any
	 * @var integer
	 * @since 1.0
	 * @access private
	 */
	private $_UserId = NULL;

	/**
	 * This function checks if the user is logged in and exists
	 * @since 1.0
	 * @access public
	 * @return boolean
	 */
	public function IsLoggedIn() {
		if (isset($_SESSION['UserId']) && $this->_CI->api_auth->User_Exists($_SESSION['UserId'])) {
			$this->_UserId = $_SESSION['UserId'];
			return true;	
		} else {
			if(isset($_SESSION['UserId'])){
				unset($_SESSION['UserId']);
			}
			return false;	
		}
	}
		
	/**
	 * This function is the constructor
	 * @since 1.0
	 * @access public
	 */
	public function __construct() {
		$this->_CI =& get_instance();
		$this->_CI->load->model("api_auth");
		
		$this->_Folder = $this->_CI->config->item('folder');
		$this->_Page = str_replace($this->_Folder,"",$_SERVER['REQUEST_URI']);

		session_start();
		self::_Check_Security();
	}

	/**
	 * This function matches the current url and rerouted url,
	 * with the keywords array
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Keywords(){
		$Keywords = $this->_CI->config->item("keywords");
		$Result = FALSE;
		foreach ($Keywords as $Keyword) {
			if(strpos($this->_CI->uri->ruri_string(), $Keyword) !== false){
				$Result = TRUE;
			}

			if(strpos($this->_CI->uri->uri_string(), $Keyword) !== false){
				$Result = TRUE;
			}	
		}
		return $Result;
	}

	/**
	 * This function matches the current page and rerouted page
	 * with the pages array
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Pages(){
		$Pages = $this->_CI->config->item("pages");
		$Result = FALSE;
		foreach ($Pages as $Page) {
			if($this->_CI->uri->ruri_string() === $Page){
				$Result = TRUE;
			}

			if($this->_CI->uri->uri_string() === $Page){
				$Result = TRUE;
			}
		}
		return $Result;
	}

	/**
	 * This function checks if the user is logged in, and if he/she isn't,
	 * the user is redirected to the front page
	 * @since 1.0
	 * @access private
	 */
	private function _Check_Security(){
		if(!self::IsLoggedIn() && is_null($this->_UserId)){
			if(!self::_Pages() && !self::_Keywords()){
				redirect($this->_CI->config->item("not_logged_in_page"));
				die();	
			}
		} else if(self::IsLoggedIn() && !self::_Pages() && !self::_Keywords()){
			if(!strpos($this->_CI->uri->ruri_string(), $this->_CI->config->item("login_page")) && $_SESSION["check_topt"] === true && self::Uses_Two_Step() === true){
				redirect("login/two_step");
				return;
			}
		}
		self::_Ensure_TOPT();
		self::_Ensure_Token();
	}

	/**
	 * This function checks if the current user uses two step varifiction
	 * @since 1.0
	 * @access private
	 */
	public function Uses_Two_Step(){
		$Query = $this->_CI->db->select("Id,TOPT,TwoStep")->where(array("Id" => $_SESSION["UserId"]))->get($this->_CI->config->item("api_users_table"));
		if($Query->num_rows() > 0){
			$Row = current($Query->result());
			if($Row->TOPT !== "" && !is_null($Row->TOPT) && ($Row->TwoStep === 1 || $Row->TwoStep === "1")){
				return TRUE;
			}
		} 
		$_SESSION["check_topt"] = false;
		$_SESSION["no_topt"] = TRUE;
		return FALSE;
	}

	/**
	 * This function ensures that the user has a generated token ready
	 * @since 1.0
	 * @access private
	 */
	private function _Ensure_Token(){
		if(self::IsLoggedIn() && !self::_Pages() && !self::_Keywords() && (!isset($_SESSION["clickthis_token"]) || isset($_SESSION["clickthis_token"]) && $_SESSION["clickthis_token"] === "")){
			unset($_SESSION["clickthis_token"]);
			redirect("token");
		}
	}

	/**
	 * This function generates a TOPT secret if the user hasn't got one
	 * @since 1.0
	 * @access private
	 */
	private function _Ensure_TOPT(){
		$this->_CI->load->helper("rand");
		$this->_CI->load->config("api");
		$key = rand_number(32);
		if(isset($_SESSION["UserId"])){
			$Query = $this->_CI->db->select("Id,TOPT")->where(array("Id" => $_SESSION["UserId"]))->get($this->_CI->config->item("api_users_table"));
			if($Query->num_rows() > 0){
				$Row = current($Query->result());
				if($Row->TOPT === "" || is_null($Row->TOPT)){
					$this->_CI->db->where(array("Id" => $_SESSION["UserId"]))->update($this->_CI->config->item("api_users_table"),array("TOPT" => $key));
				}
			}
		}

	}
}
?>