<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores information about an educational institute
 * @package School
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage School
 * @category Education
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class School extends Std_Library{

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access private
	 * @since 1.0
	 */
	private $Database_Table = "Schools";

	/**
	 * The state the school is located in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * The country thes school is located in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	/**
	 * The name of the school
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * The database id of the school
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The abbrevation of the school
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Abbrevation = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @since 1.0
	 * @access private
	 * @var object
	 */
	private $_CI = NULL;
	
	/**
	 * This function is the constructor it creates a local instance of CodeIgniter
	 * @since 1.0
	 * @access public
	 */
	public function School () {
		$this->_CI =& get_instance();
	}
	
	/**
	 * [Save description]
	 */
	public function Save(){
		
	}
	
	/**
	 * This function loads data from the database and adds it to this class
	 * @param integer $Id The database id of the School
	 * @access public
	 * @since 1.0
	 */
	public function Load($Id = NULL){
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id)){
			$this->_CI->load->model("Load_School");
			$this->_CI->Load_School->getSchoolById($this);
		}
	}
	
	/**
	 * [Add description]
	 */
	public function Add(){
		
	}
	
	/**
	 * [Create description]
	 */
	public function Create(){
		
	}
}
?>