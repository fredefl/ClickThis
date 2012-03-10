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
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = NULL;

	/**
	 * This property is used to convert class property names,
	 * to database row names
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 * @internal This is an internal name convert table
	 */
	public static $_INTERNAL_DATABASE_NAME_CONVERT = NULL;

	/**
	 * This property can contain properties to be ignored when exporting
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_EXPORT_INGNORE = NULL;

	/**
	 * This property can contain properties to be ignored, when the database flag is true in export.
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_DATABASE_EXPORT_INGNORE = NULL;

	/**
	 * This property contain values for converting databse rows to class properties
	 * @var array
	 * @see $_INTERNAL_DATABASE_NAME_CONVERT
	 * @access public
	 * @static
	 * @since 1.0
	 */
	public static $_INTERNAL_ROW_NAME_CONVERT = NULL;

	/**
	 * This property contains the database model to use
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public static $_INTERNAL_DATABASE_MODEL = NULL;

	/**
	 * This property will contain a local instance of CodeIgniter,
	 * if the children set's it
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	/**
	 * This function is the constructor
	 * @access public
	 * @since 1.0
	 */
	public function Std_Library(){
	}

	/**
	 * This function sets the CodeIgniter isntance
	 * @param object &$CI The instance of CodeIgniter to use
	 * @access public
	 * @since 1.0
	 */
	public function Config(&$CI = NULL){
		if(!is_null($CI)){
			$this->_CI =& $CI;
		}
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
	 * This function loads data either by the $Id parameter or by the $Id property
	 * @param integer $Id The database id to load data from, this parameter is optional,
	 * if it's not deffined the $Id property value will be used
	 * @since 1.0
	 * @access public
	 */
	public function Load($Id = NULL) {
		if(!is_null($Id)){
			$this->Id = $Id;
		}
		if(!is_null($this->Id) && !is_null($this->_CI) && !is_null($this->_CI->_INTERNAL_DATABASE_MODEL)){
			$this->_CI->_INTERNAL_DATABASE_MODEL->Load($this->Id,$this);
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
	 * This function saves the local class data to the database row of the Id property
	 * @return string This function can return a error string
	 * @since 1.0
	 * @access public
	 */
	public function Save() {
		if(!is_null($this->Id) && !is_null($this->_CI) && !is_null($this->_CI->_INTERNAL_DATABASE_MODEL) ){
			$this->_CI->_INTERNAL_DATABASE_MODEL->Save($this);
		}
		else{
			return 'No User Id Specified';	
		}
	}

	/**
	 * This function returns all the class variable with name and values as an array
	 * @return array All the class vars and values
	 * @param boolean $Database If this flag is set to true, the data will be exported so the key names,
	 * fits the database row names
	 * @since 1.0
	 * @access public
	 * @return array The class data as an array
	 */
	public function Export ($Database = false) {
		if ($Database) {
			$Array = array();
			$Ignore = NULL;
			if(property_exists(get_class($this), "_INTERNAL_DATABASE_EXPORT_INGNORE")){
				$Ignore = $this->_INTERNAL_DATABASE_EXPORT_INGNORE;
			}
			//Loop through all class properties
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {

				//If the property is the CodeIgniter instance, the id or an internal property dont do anything
				if (!self::Ignore($Name,$Ignore)) {

					//If the class has an name convert table, check if the current property exists in it
					// , if it does use that as the array key
					if(property_exists(get_class($this), "_INTERNAL_DATABASE_NAME_CONVERT") 
						&& is_array($this->_INTERNAL_DATABASE_NAME_CONVERT) 
						&& array_key_exists($Name, $this->_INTERNAL_DATABASE_NAME_CONVERT)) {
						//If the data is an array implode it with a ";" sign else just assign it
						if(!is_null($this->{$Name}) && is_array($this->{$Name})){
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = implode(";",$this->{$Name});
						} else {
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = $this->{$Name};
						}
					} else {
						if(!is_null($this->{$Name}) && is_array($this->{$Name})){
							$Array[$Name] = implode(";",$this->{$Name});
						} else {
							$Array[$Name] = $this->{$Name};
						}
					}
				}
			}
		} 
		else {
			$Array = array();
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {
				if (!self::Ignore($Name)) {
					$Array[$Name] = $this->{$Name};
				}
			}
		}
		return $Array;
	}

	/**
	 * This function checks the local settings variable for export,
	 * to see if the $Key exists in one of them or if the Key contains the _INTERNAL keyword
	 * @param string||integer $Key         The key to check
	 * @param array $ExtraIgnore Some extra ignore rules if nessesary
	 * @return boolean if to be ignored true is returned else is false returned
	 * @access public
	 * @since 1.0
	 */
	public function Ignore($Key = NULL,$ExtraIgnore = NULL){
		if(!is_null($Key)){
			if(!strpos($Key, "INTERNAL_") === false){
				return true;
			} else {
				if(property_exists(get_class($this), "_INTERNAL_EXPORT_INGNORE")){
					if(in_array($Key,$this->_INTERNAL_EXPORT_INGNORE)){
						return true;
					} else {
						if(!is_null($ExtraIgnore) && in_array($Key, $ExtraIgnore)){
							return true;
						} else {
							return false;
						}
					}
				} else {
					if(!is_null($ExtraIgnore) && in_array($Key, $ExtraIgnore)){
						return true;
					} else {
						return false;
					}
				}
			}
		} else {
			return true;
		}
	}
	

	/**
	 * This function removes local data, set the id flag to true to remove the id too.
	 * @param boolean $Id If this is set to true then the id is cleared too
	 * @since 1.0
	 * @access private
	 */
	private function _RemoveUserData($Id = false){
		foreach(get_class_vars(get_class($this)) as $Name => $Value){
			if($Name != "CI" && $Name != "Database_Table" && strpos($Name, "INTERNAL_") === false){
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
					if(!is_null($Class->{$Key}) && $Class->{$Key} != "" && $Key != "CI" && strpos($Key, "INTERNAL_") === false){
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
				self::_RemoveUserData(true,$this->Database_Table);
			}
		}
		else{
			if(method_exists($this, "_RemoveUserData")){
				self::_RemoveUserData(false);
			}
		}
	}

	/**
	 * This function takes the data from the $Array parameter and adds it to the local data,
	 * and if the database flag is set the data will be saved too. 
	 * @param array  $Array    The data in Name => Value format
	 * @param boolean $Database If this flag is set too true the data is saved too
	 * @access public
	 * @since 1.0
	 */
	public function Create($Array =  NULL,$Database = false){
		if(!is_null($Array)){
			self::_SetDataArray($Array);
			if($Database && !is_null($this->_CI) && !is_null($this->_CI->_INTERNAL_DATABASE_MODEL)){
				$this->Id = $this->_CI->_INTERNAL_DATABASE_MODEL->Create($this);
				return $this->Id;
			}
		}
	}

	/**
	 * This function adds data, to this class either from a class or from an array,
	 * and if the Database flag is set to true the data will be saved to the database.
	 * @param object  &$Class   This parameter should contain a class that has the data to add deffined,
	 * with the same variable names, as this class. Not all variables need to be deffined only create them you need to.
	 * @param array  $Array    If this parameter is set and $Class is null the data from this parameter is used in Name => Value format
	 * @param boolean $Database If this flag is set to true, then the data will be saved in the database too
	 * @access public
	 * @since 1.0
	 */
	public function Add(&$Class = NULL,$Array = NULL, $Database = false){
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
		if($Database && !is_null($this->Id) && !is_null($this->_CI) && !is_null($this->_CI->_INTERNAL_DATABASE_MODEL)){
			$this->Id = $this->_CI->_INTERNAL_DATABASE_MODEL->Create($this);
			return $this->Id;
		}
	}
}