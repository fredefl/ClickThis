<?php
/**
 * 
 */
class Std_Api_Response{
	
	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The api level of the current token
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Level = NULL;

	/**
	 * The extra sections that are allowed
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Sections = NULL;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function Std_Api_Response(){
		$this->_CI =& get_instance();
		$this->_CI->load->config("api");
	}

	/**
	 * [Create description]
	 */
	public function Create($Data = NULL){
		if(!is_null($Data) && $Level < $this->_CI->config->item("api_write_access_token_max")+1){

		} else {
			return FALSE;
		}
	}

	/**
	 * [Update description]
	 */
	public function Update(){

	}

	/**
	 * [Overwrite description]
	 */
	public function Overwrite(){

	}

	/**
	 * [Read description]
	 */
	public function Read(){

	}

	/**
	 * [Delete description]
	 */
	public function Delete(){

	}
}
?>