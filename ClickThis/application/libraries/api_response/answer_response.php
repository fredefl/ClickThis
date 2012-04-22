<?php
class Answer_Response extends Std_Api_Response{
	
	private $_CI = NULL;

	/**
	 * The library/class to use when accessing data
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	public $Library = "Answer";

	public static $_INTERNAL_USER_INFLUENCE = NULL;

	public static $_INTERNAL_USER_INFLUENCE_FIELDS = NULL;

	public static $_INTERNAL_USER_INFLUENCE_OPERATIONS = NULL;

	public static $_INTERNAL_NO_CHANGE = NULL;

	public static $_INTERNAL_USERID_REQUIRED = false;

	/**
	 * The constructor
	 * @since 1.0
	 * @access public
	 */
	public function Answer_Response(){
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_USER_INFLUENCE = true;
		$this->_INTERNAL_USER_INFLUENCE_FIELDS = array("UserId");
		$this->_INTERNAL_USER_INFLUENCE_OPERATIONS = array("ALL");
		$this->_INTERNAL_NO_CHANGE = array("UserId","Id");
	}

	/**
	 * This function overwrites the normal behaviur of Api_Response Create,
	 * it creates a DidAnswer object and fill it with the correct data, from the answer data
	 * @param array $Data   The data to create a answer based on
	 * @param integer $UserId The user id of the user that is creating this data
	 * @since 1.0
	 * @access public
	 */
	public function Create($Data = NULL,$UserId = NULL){
		if(!is_null($UserId) && $this->Level < $this->_CI->config->item("api_write_access_token_max")+1 && $this->WriteAccess){
			$this->_CI->load->library("DidAnswer");
			if(isset($Data["UserId"]) && $Data["UserId"] != "" && $UserId != $Data["UserId"]){
				return FALSE;
			}
			if(isset($Data["QuestionId"])){
				$DidAnswer = new DidAnswer();
				$DidAnswer->UserId = $UserId;
				$DidAnswer->QuestionId = $Data["QuestionId"];
				$DidAnswer->Save();
			}

		}
		return parent::Create($Data);
	}
}
?>