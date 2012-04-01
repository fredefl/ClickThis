<?php
class DidAnswer_Response extends Std_Api_Response{
	
	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * The library/class to use when accessing data
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	public $Library = "DidAnswer";

	public static $_INTERNAL_USER_INFLUENCE = NULL;

	public static $_INTERNAL_USER_INFLUENCE_FIELDS = NULL;

	public static $_INTERNAL_USER_INFLUENCE_OPERATIONS = NULL;

	public static $_INTERNAL_NO_CHANGE = NULL;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function DidAnswer_Response(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_USER_INFLUENCE = true;
		$this->_INTERNAL_USER_INFLUENCE_FIELDS = array("UserId");
		$this->_INTERNAL_USER_INFLUENCE_OPERATIONS = array("ALL");
		$this->_INTERNAL_NO_CHANGE = array("UserId");
	}
}
?>