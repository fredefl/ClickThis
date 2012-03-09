<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores data about a group
 * @package Users
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage Group
 * @category Group Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Group{
	
	/**
	 * The database id of the group, if it's saved
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Id = NULL;

	/**
	 * The name of the group
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Name = NULL;

	/**
	 * This property stores the id of the members of this grouo
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Members = NULL;

	/**
	 * This property stores the id's of the administrators of this group
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Administrators = NULL;

	/**
	 * This property stores the group's title, displayed on the group page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * This property stores a description of the group, displayed on the groups page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Description = NULL;

	/**
	 * This property stores the database id of the user that created this group
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Creator = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;
	
	/**
	 * This function is the constructor, it load's the model regarding this class,
	 * and it creates a local instance of CodeIgniter and place it ind the $this->_CI property
	 * @since 1.0
	 * @access public
	 */
	public function Group(){
		$this->_CI =& get_instance();
		$this->_CI->load->model("Load_Groups");
	}
	
	/**
	 * This function imports data to the class from an array
	 * @param array $Array The data to import with the key as the property to add the value too
	 * @since 1.0
	 * @access public
	 */
	public function Import($Array = NULL){
		if(!is_null($Array)){
			foreach($Array as $Name => $Value){
				if(property_exists($this,$Name)){
					$this->$Name = $Value;	
				}
			}
		}
	}
	
	/**
	 * This function exports all the class data as an array
	 * @param boolean $Database If this flag is set to true, then the id property woudn't be included
	 * @access public
	 * @since 1.0
	 * @return array The exported data
	 */
	public function Export($Database = false){
		$Array = array();
		foreach(get_class_vars(get_class($this)) as $Name => $Value){
			if($Name != "_CI"){
				if($Name != "Id"){
					$Array[$Name] = $this->{$Name};
				}
				if($Name == "Id" && $Database == false){
					$Array[$Name] = $this->{$Name};
				}
			}
		}
		return $Array;
	}

	/**
	 * This function removes local data, set the id flag to true to remove the id too.
	 * @param boolean $Id If this is set to true then the id is cleared too
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveUserData($Id = false){
		foreach(get_class_vars(get_class($this)) as $Name => $Value){
			if($Name != "CI"){
				if($Name != "Id"){
					$this->{$Name} = NULL;
				}
				if($Id == true && $Name == "Id"){
					$this->{$Name} = NULL;
				}
			}
		}
	}
	
	/**
	 * This function loads data from the database
	 * @param integer $Id An optional id parameter, containing the database id of a group
	 * @access public
	 * @since 1.0
	 */
	public function Load($Id = NULL){
		//Check if id is set
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id)){
			$this->_CI->Load_Groups->LoadById($this->Id,$this);
		}
	}
	
	/**
	 * This function saves the local class data to the database
	 * @access public
	 * @since 1.0
	 */
	public function Save(){
		$this->_CI->load->model('Save_Group');
		if(!is_null($this->Id)){
			$this->_CI->Save_Group->Save($this,self::Export(false),'Groups');
		}
	}
	
	/**
	 * This function takes keys from the input and adds it to this class
	 * @param object $Group The class to set the data from
	 * @since 1.0
	 * @access private
	 */
	private function _SetDataClass($Group = NULL){
		if(!is_null($Group)){
			foreach (get_object_vars($Group) as $Key => $Value) {
				if(property_exists($this, $Key)){
					if(!is_null($Group->{$Key}) && $Group->{$Key} != "" && $Key != "_CI"){
						$this->{$Key} = $Group->{$Key};
					}
				}
			}
			
		}
	}
	
	/**
	 * This function imports data from an array
	 * @param array $Array The array data to set
	 * @see self::Import
	 * @since 1.0
	 * @access private
	 */
	private function _SetDataArray($Array = NULL){
		if(!is_null($Array)){
			self::Import($Array);
		}
	}
	
	/**
	 * This function refresh the data from the database
	 * @see self::Load
	 * @access public
	 * @since 1.0
	 */
	public function Refresh(){
		if(!is_null($this->Id)){
			self::Load($this->Id);
		}
	}
	
	/**
	 * This function clears the local data
	 * @param boolean $Id If this flag is true, then the id will be cleard too
	 * @since 1.0
	 * @access public
	 */
	public function Clear(){
		self::_RemoveUserData(false);
	}
	
	/**
	 * This function removes the database row of the spe_CIfied id
	 * @param integer $Id The database id to clear
	 * @since 1.0
	 * @access private
	 */
	private function _ClearDatabase($Id = NULL){
		if(!is_null($Id)){
			$this->_CI->load->model('Save_Group');
			$this->_CI->Save_Group->Delete($Id,'Groups');
		}
	}
	
	/**
	 * This function clears data and if Database is set to true, the data will also be removed from the database
	 * @param boolean $Database [If this flag is true the data will be deleted from the database too
	 * @since 1.0
	 * @access public
	 */
	public function Delete($Database = false){
		self::Clear();
		if($Database){
			self::_ClearDatabase($this->Id);		
		}
	}
	
	/**
	 * This class adds data to this class object
	 * @param object  $Class    The data to add, from a class with the same property names as this class
	 * @param array  $Array    An array that has the same key names as this class property names.
	 * @param boolean $Database If this flag is set to true the data will be saved when added
	 * @since 1.0
	 * @return integer If the database flag is selected and data is succesfully added the database id,
	 * will be returned  or if an error occur the error message will be returned
	 * @access public
	 */
	public function Add($Class = NULL,$Array = NULL,$Database = false){
		if(!is_null($Class)){
			self::_SetDataClass($Class);
		}
		else{
			if(!is_null($Array)){
				self::SetDataArray($Array);
			}
			else{
				return "Error Wrong Input";	
			}
		}
		if(!is_null($this->Id)){
			if($Database){
				self::Save();
				return $this->Id;
			}
		}
	}
	
	/**
	 * This function imports data to this class and it can save it too
	 * @param array  $Array    The data to create a new object with
	 * @param boolean $Database If this flag is set to true the data will be saved too
	 * @return integer If the database flag is set to true, then the new id is returned
	 * @since 1.0
	 * @access public
	 */
	public function Create($Array = NULL,$Database = false){
		if(!is_null($Array)){
			self::SetDataArray($Array);
			if($Database){
				self::Save();
				return $this->Id;
			}
		}
	}
}
?>