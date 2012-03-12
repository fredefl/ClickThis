<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used for storing user data, such as Facebook id's etc
 * @package Libraries
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Std Data Library Template
 * @category Library template
 * @version 1.1
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
	 * @example
	 * $_INTERNAL_DATABASE_NAME_CONVERT = array("Facebook_Id" =>"Facebook");
	 */
	public static $_INTERNAL_DATABASE_NAME_CONVERT = NULL;

	/**
	 * This property can contain properties to be ignored when exporting
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 * @internal This is an class variable used for ignoring variables in export
	 * @example
	 * $_INTERNAL_EXPORT_INGNORE = array("CI","_CI");
	 */
	public static $_INTERNAL_EXPORT_INGNORE = NULL;

	/**
	 * This property can contain properties to be ignored, when the database flag is true in export.
	 * @var array
	 * @access public
	 * @static
	 * @since 1.0
	 * @internal This is an internal ignoring list for export with the database flag turned on
	 * @example
	 * $_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id");
	 */
	public static $_INTERNAL_DATABASE_EXPORT_INGNORE = NULL;

	/**
	 * This property contain values for converting databse rows to class properties
	 * @var array
	 * @see $_INTERNAL_DATABASE_NAME_CONVERT
	 * @access public
	 * @static
	 * @since 1.0
	 * @internal This is an internal databse column to class property convert table
	 * @example
	 * $_INTERNAL_ROW_NAME_CONVERT = array("Facebook" => "Facebook_Id");
	 */
	public static $_INTERNAL_ROW_NAME_CONVERT = NULL;

	/**
	 * This property contains the database model to use
	 * @var object
	 * @since 1.0
	 * @access public
	 * @static
	 * @example
	 * $this->_CI->load->model("Model_User","_INTERNAL_DATABASE_MODEL");
	 * @internal This property holds the CodeIgniter database model, 
	 * for importing and saving data for the class
	 */
	public static $_INTERNAL_DATABASE_MODEL = NULL;

	/**
	 * This property is used to define class properties that should be filled with objects,
	 * with the data that the property contains
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 * @internal This is a class setting variable
	 */
	public static $_INTERNAL_LOAD_FROM_CLASS = NULL;

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

		//If some properties is going to be filled with data containing a class
		if(property_exists($this, "_INTERNAL_LOAD_FROM_CLASS")  && !is_null($this->_INTERNAL_LOAD_FROM_CLASS) && is_array($this->_INTERNAL_LOAD_FROM_CLASS)){
			if(!is_null($this->_INTERNAL_LOAD_FROM_CLASS)){
				foreach ($this->_INTERNAL_LOAD_FROM_CLASS as $Key => $Value) {
					if(property_exists($this, $Key)){
						if(property_exists($this, "_CI") && !is_null($this->_CI)){
							@$this->_CI->load->library($Value);
						}
						if(is_array($this->{$Key}) && count($this->{$Key}) > 0){
							$Temp = array();
							foreach ($this->{$Key} as $Name) {
								if(class_exists($Value)){
									$Object = new $Value();
									$Object->Load($Name);
									$Temp[] = $Object;
								}
							}
							if(count($Temp) > 0){
								$this->{$Key} = $Temp;
							}
						} else {
							if(!is_null($this->{$Key})){
								if(class_exists($Value)){
									$Object = new $Value();
									$Object->Load($this->{$Key});
									$this->{$Key} = $Object;
								}
							}
						}
					}
				}
			}
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
		if(!is_null($this->_CI) && !is_null($this->_CI->_INTERNAL_DATABASE_MODEL) ){
			$this->_CI->_INTERNAL_DATABASE_MODEL->Save($this);
		} else {
			return false;
		}
	}

	/**
	 * This function converts a object to an array if there's more objects in the input or just a string if there's only one
	 * @param object||array $Data The object to convert to a string or array
	 * @return array||string This output will either be the id of the object or an array with the id's
	 * @access private
	 * @since 1.1
	 */
	private function Convert_From_Object($Data = NULL){
		$Return = NULL;
		if(!is_null($Data)){
			if(is_array($Data) && count($Data) > 0){
				$Temp = array();
				foreach ($Data as $K => $Object) {
					if(!is_null($Object)){
						if(property_exists($Object, "Id")){
							$Temp[] = $Object->Id;
						}
					}
				}
				if(count($Temp) > 0){
					$Return = $Temp;
				}
			} else {
				if(property_exists($Data, "Id")){
					$Return = $Data->Id;
				}
			}
			if(!is_null($Return)){
				return $Return;
			}
		}
	}

	/**
	 * This function checks if the input $Data is containing an object,
	 * either inside an array or just as the value
	 * @param object||array||boolean||string|integer $Data The data to check
	 * @since 1.1
	 * @access private
	 * @return boolean The check result
	 */
	private function Contains_Object($Data = NULL){
		if(!is_null($Data)){
			if(is_array($Data)){
				foreach ($Data as $Key => $Value) {
					if(is_object($Key) || is_object($Value)){
						return true;
					} else {
						return false;
					}
				}
			} else {
				return (is_object($Data)) ? true : false;
			}
		} else {
			return false;
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
			if(property_exists($this, "_INTERNAL_DATABASE_EXPORT_INGNORE")){
				if(!is_null($this->_INTERNAL_DATABASE_EXPORT_INGNORE)){
					$Ignore = $this->_INTERNAL_DATABASE_EXPORT_INGNORE;
				}
			}
			//Loop through all class properties
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {

				//If the property is the CodeIgniter instance, the id or an internal property dont do anything
				if (!self::Ignore($Name,$Ignore) && !is_null($this->{$Name})) {
					$Data = $this->{$Name};
					/*if(property_exists($this,"_INTERNAL_LOAD_FROM_CLASS") && !is_null($this->_INTERNAL_LOAD_FROM_CLASS) && array_key_exists($Name, $this->_INTERNAL_LOAD_FROM_CLASS)){
						$Data = self::Convert_From_Object($Data);
					}*/
					if(self::Contains_Object($Data)){
						$Data = self::Convert_From_Object($Data);
					}

					//If the class has an name convert table, check if the current property exists in it
					// , if it does use that as the array key
					if(property_exists($this, "_INTERNAL_DATABASE_NAME_CONVERT") 
						&& is_array($this->_INTERNAL_DATABASE_NAME_CONVERT) 
						&& array_key_exists($Name, $this->_INTERNAL_DATABASE_NAME_CONVERT)
						&& !is_null($this->_INTERNAL_DATABASE_NAME_CONVERT)) {
						//If the data is an array implode it with a ";" sign else just assign it
						if(!is_null($Data) && is_array($Data)){
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = implode(";",$Data);
						} else {
							$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Name]] = $Data;
						}
					} else {
						if(!is_null($Data) && is_array($Data) && !self::Contains_Object($Data)){
							$Array[$Name] = implode(";",$Data);
						} else {
							$Array[$Name] = $Data;
						}
					}
				}
			}
		} 
		else {
			$Array = array();
			foreach (get_class_vars(get_class($this)) as $Name => $Value) {
				if (!self::Ignore($Name)) {
					if(self::Contains_Object($this->{$Name})){
						if(is_array($this->{$Name})){
							$TempArray = array();
							foreach ($this->{$Name} as $Object) {
								if(method_exists($Object, "Export")){
									$TempArray[] = $Object->Export();
								}
							}
							if(count($TempArray) > 0){
								$Array[$Name] = $TempArray;
							} else {
								$Array[$Name] = $this->{$Name};
							}
						} else {
							if(method_exists($this->{$Name}, "Export")){
								$Array[$Name] = $this->{$Name}->Export();
							}
						}
					} else {
						$Array[$Name] = $this->{$Name};
					}
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
			if($Name != "CI" && $Name != "_CI" && $Name != "Database_Table" && strpos($Name, "INTERNAL_") === false){
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
					echo "Refresh";
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