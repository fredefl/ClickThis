<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class stores data about a state
 * @package School Data
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage State
 * @category School Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 * 
 */  
class State extends Std_Library {

	/**
	 * The database id of the state
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The name of the state, in plain text
	 * @access public
	 * @since 1.0
	 * @var string
	 */
	public $Name = NULL;

	/**
	 * The country the state is located in, in plain text
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 */
	private $CI = NULL;
	
	/**
	 * This function is the constructor, it create's a local instance of CodeIgniter
	 */
	public function State () {
		$this->CI =& get_instance();
	}
	
	/**
	 * [Load description]
	 * @access public
	 * @since 1.0
	 */
	public function Load(){
		
	}
	
	/**
	 * [Save description]
	 * @access public
	 * @since 1.0
	 */
	public function Save(){
		
	}
	
	/**
	 * [Add description]
	 * @access public
	 * @since 1.0
	 */
	public function Add(){
		
	}
	
	/**
	 * [Create description]
	 * @access public
	 * @since 1.0
	 */
	public function Create(){
		
	}
}
?>