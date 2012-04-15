<?php
class User_Response extends Std_Api_Response{
	
	private $_CI = NULL;

	/**
	 * The library/class to use when accessing data
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	public $Library = "User";

	public static $_INTERNAL_USER_INFLUENCE = NULL;

	public static $_INTERNAL_USER_INFLUENCE_FIELDS = NULL;

	public static $_INTERNAL_USER_INFLUENCE_OPERATIONS = NULL;

	public static $_INTERNAL_NO_CHANGE = NULL;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function User_Response(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_USER_INFLUENCE = true;
		$this->_INTERNAL_USER_INFLUENCE_FIELDS = array("Id");
		$this->_INTERNAL_USER_INFLUENCE_OPERATIONS = array("WRITE","UPDATE","DELETE","READ");
		$this->_INTERNAL_NO_CHANGE = array("Id","Password","Username");
	}
}
?>