<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used for storing user data, such as Facebook id's etc
 * @package Libraries
 * @license http://creativecommons.org/licenses/by/3.0/ Creative Commons 3.0
 * @subpackage Std Data Library Template
 * @category Library template
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Add the Save,Load, Add and Create functions and models
 */ 
class Std_Library{

	/**
	 * This function is the constructor
	 * @access public
	 * @since 1.0
	 */
	public function Std_Library(){
		
	}

	/**
	 * This function clears the local class data
	 * @access public
	 * @since 1.0
	 */
	public function Clear(){
		if(method_exists($this,"_RemoveUserData")){
			self::_RemoveUserData(false);
		}
	}

	/**
	 * This function imports data from an array with the same key name as the local property to import too.
	 * @param array $Array The data to import in Name => Value format
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
			if($Name != "_CI" && $Name != "CI"){
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
	 * This function takes an array and ads the data to the variable with the right key {Name},
	 * with the corrosponding data {Value}
	 * @param array $Array The data in Name => Value format to set
	 * @since 1.0
	 * @access private
	 */
	private function _SetDataArray($Array = NULL){
		if(!is_null($Array)){
			if(method_exists($this,"Import")){
				self::Import($Array);
			}
		}
	}

	/**
	 * This function removes data from the specified row in the $Id parameter
	 * @param integer $Id The database row id to remove
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveDatabaseData($Id = NULL,$Table = NULL){
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id) && !is_null($Table)){
			if(property_exists(get_class($this), "CI") && property_exists(get_class($this), "_CI")){
				$this->CI->db->delete($Table,array("Id" => $this->Id));
			}
		}
	}

	/**
	 * This function only sets the output if input exists
	 * @param object||string||number &$Input  The input data to check if exists
	 * @param object||string||number &$Output The output data to set if the input isset
	 * @since 1.0
	 * @access private
	 */
	private function _CheckData(&$Input = NULL,&$Output = NULL){
		if(!is_null($Input) && !is_null($Output)){
			if(isset($Input) && @!is_null($Input)){
				$Output = $Input;
			}
		}
	}

	/**
	 * This function adds data from an class that has the same property names as the data you wish to add
	 * @param object $Class An instance of the object you wish to set
	 * @access private
	 * @since 1.0
	 */
	private function _SetDataClass(&$Class = NULL){
		if(!is_null($Class)){
			foreach (get_object_vars($Class) as $Key => $Value) {
				if(property_exists(get_class($this), $Key)){
					if(!is_null($Class->{$Key}) && $Class->{$Key} != "" && $Key != "CI"){
						$this->{$Key} = $Class->{$Key};
					}
				}
			}
			
		}
	}

	/**
	 * This function refresh the class data from the database
	 * @see self::Load
	 * @since 1.0
	 * @access public
	 */
	public function Refresh(){
		if(property_exists($this, "Id")){
			if(!is_null($this->Id)){
				if(method_exists($this, "Load")){
					self::Load($this->Id);
				}
			}
		}
	}

	/**
	 * This function delete's data local in the class, but if selected it can also delete the data from the database
	 * @param boolean $Database If this flag is set too true, the database data will be deleted too
	 * @since 1.0
	 * @access public
	 */
	public function Delete($Database = false){
		if($Database){
			if(method_exists($this, "_RemoveDatabaseData") && property_exists(get_class($this), "Id")){
				self::_RemoveDatabaseData($this->Id);
			}
			if(method_exists($this, "_RemoveUserData")){
				self::_RemoveUserData(true);
			}
		}
		else{
			if(method_exists($this, "_RemoveUserData")){
				self::_RemoveUserData(false);
			}
		}
	}
}