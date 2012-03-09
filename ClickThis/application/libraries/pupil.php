<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores information about a pupil, which is studying on a education institute
 * @package School
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage Pupil
 * @category Education
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Make the Save,Add,Create and Load function and make more documentatin
 */ 
class Pupil extends Std_Library{
	
	/**
	 * The class/group the pupil is in, not to be confused with "Group"
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Class = NULL;

	/**
	 * The database id of the pupil/user
	 * @access public
	 * @since 1.0
	 * @var integer
	 */
	public $Id = NULL;

	/**
	 * The unilogin of the pupil, if the user has one
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Unilogin = NULL;

	/**
	 * The country that the user lives in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Country = NULL;

	/**
	 * The school the pupil is studying on
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $School = "";

	/**
	 * The state that the pupil lives in, this can be looked up, with the school too
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * The name of the pupil, stored in the users database
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * A locale instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 */
	private $CI = '';
	
	/**
	 * This function is the constructor, it creates a local instance of CodeIgniter
	 * @access public
	 * @since 1.0
	 */
	public function Pupil () {
		$this->CI =& get_instance();
	}

	/**
	 * This function exports all the class data as an array,
	 * if the database flag is set to true, then the data will be returned so it fits the database,
	 * row names.
	 * @param boolean $Database If this flag is set to true, then the id property woudn't be included
	 * @access public
	 * @since 1.0
	 * @return array The exported data
	 */
	public function Export($Database = false){
		$Array = array();
		$Names = array("Name" => "RealName","Unilogin" => "Username");
		foreach(get_class_vars(get_class($this)) as $Name => $Value){
			if($Name != "_CI" && $Name != "CI"){
				if($Database){
					if($Name != "Id"){
						if(array_key_exists($Name, $Names)){
							$Array[$Names[$Name]] = $this->{$Name};	
						} else {
							$Array[$Name] = $this->{$Name};
						}
					}	
				} else {
					$Array[$Name] = $this->{$Name};
				}
			}
		}
		if($Database){
			$Array["Method"] = "Pupil";
		}
		return $Array;
	}
	
	/**
	 * [Load description]
	 * @param integer $Id [description]
	 */
	public function Load($Id = 0){
		if($Id != 0){
			$this->Id = $Id;
		}
	}
	
	/**
	 * [Save description]
	 */
	public function Save(){
		$this->CI->load->model('Save_Pupil');
		$this->CI->Save_Option->Save($this,self::Export(false),'Users');
	}
	
	/**
	 * [Add description]
	 * @param [type]  $Class    [description]
	 * @param boolean $Database [description]
	 */
	public function Add($Class = NULL,$Database = false){
		if(!is_null($Class)){
			self::_SetDataClass($Class);
		}
		else{
			if(!is_null($Array)){
				self::_SetDataArray($Array);
			}
			else{
				return "Error Wrong Input";	
			}
		}
		if($Database){
			self::Save();
			return $this->Id;
		}
	}
	
	/**
	 * [Create description]
	 * @param [type]  $Array    [description]
	 * @param boolean $Database [description]
	 */
	public function Create($Array = NULL,$Database = false){
		if(!is_null($Array)){
			self::_SetDataArray($Array);
			if($Database){
				self::Save();
				return $this->Id;
			}
		}
	}
}
?>