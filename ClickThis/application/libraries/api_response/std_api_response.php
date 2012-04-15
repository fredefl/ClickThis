<?php
/**
 * 
 */
class Std_Api_Response{
	
	/**
	 * The response send to the client
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Response = NULL;

	/**
	 * The object of the current data
	 * @var object
	 * @since 1.0
	 * @access public
	 */
	public $ResponseObject = NULL;

	/**
	 * The api level of the current token
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Level = NULL;

	/**
	 * The extra sections that are allowed
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Sections = NULL;

	/**
	 * This property will contains a flag if the current token hash write access
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $WriteAccess = NULL;

	/**
	 * This property will contain a flag if the current token has secret access
	 * @var boolean
	 * @since 1.0
	 * @access public
	 */
	public $SecretAccess = NULL;

	/**
	 * The id of the current user
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $UserId = NULL;

	### Class Settings ###
	/**
	 * This property contains the access levels for the
	 * different properties of the current library
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_READ_ACCESS_LEVELS = NULL;

	/**
	 * This property contains a set of access levels for,
	 * the different properties in this class,
	 * if the property isn't deffined in this table then
	 * the standard is used taken from config/api.php
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_WRITE_ACCESS_LEVELS = NULL;

	/**
	 * This property contains an access level
	 * array map, of required access levels for
	 * the delete operation
	 * @var array
	 * @static
	 * @since 1.0
	 * @access public
	 */
	public static $_INTERNAL_DELETE_ACCESS_LEVELS = NULL;

	/**
	 * A map of properties with the levels
	 * that is required for performing
	 * the update operation for the property
	 * else is the standard level used.
	 * @var [type]
	 */
	public static $_INTERNAL_UPDATE_ACCESS_LEVELS = NULL;

	/**
	 * The fields that can't be changed, with edit operations
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_NO_CHANGE = NULL;

	/**
	 * If a check for user influence is needed
	 * @var boolean
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_USER_INFLUENCE = NULL;

	/**
	 * The properties that is user influence fields
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_USER_INFLUENCE_FIELDS = NULL;

	/**
	 * The operations that requires user influence
	 * @var array
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static $_INTERNAL_USER_INFLUENCE_OPERATIONS = NULL;

	/**
	 * A local instance of CodeIgniter
	 * @var object
	 * @since 1.0
	 * @access private
	 */
	private $_CI = NULL;

	public function __construct(){}

	/**
	 * This function sets the CodeIgniter isntance
	 * @param object &$CI The instance of CodeIgniter to use
	 * @access public
	 * @since 1.0
	 */
	public function Config(&$CI = NULL){
		if(!is_null($CI)){
			$this->_CI =& $CI;
			$this->_CI->load->config("api");
		}
	}

	/**
	 * This function creates data in the data using the specified library
	 * @since 1.0
	 * @access public
	 * @return boolean If the operation was a success
	 * @param array $Data The data to create
	 */
	public function Create($Data = NULL){
		if(!is_null($Data) && $this->Level < $this->_CI->config->item("api_write_access_token_max")+1 && $this->WriteAccess){
			$Query = array();
			foreach ($Data as $Key => $Value) {
				if(self::_Has_Access($Key,"WRITE")){
					$Query[$Key] = $Value;
				}
			}
			if(count($Query) > 0 && self::_Check_For_User_Influence("WRITE",$Query)){
				if(property_exists($this, "Library") && !is_null($this->Library)){
					$this->_CI->load->library(strtolower($this->Library));
					$Object = new $this->Library();
					if(!is_null($Object)){
						$Object->Import($Query,false,!$this->SecretAccess);
						$Object->Save();
						$this->ResponseObject = $Object;
						$this->Response[$this->Library][]["Id"] = $Object->Id;
						return TRUE;
					} else {
						return FALSE;
					}
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a given property exists in one of the internal settings variables
	 * @param string $Field    The field to check for
	 * @param string $Property The settings property
	 * @param pointer &$Value   A pointer to store the value in
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Settings_Property($Field = NULL,$Property = NULL,&$Value = NULL){
		if(!is_null($Property) && property_exists($this, $Property) && isset($this->{$Property}) && is_array($this->{$Property})){
			if(array_key_exists($Field, $this->{$Property})){
				$Value = $this->{$Property}[$Field];
				return TRUE;
			} else if(in_array($Field, $this->{$Property})){
				$Value = TRUE;
				return TRUE;
			} else if(in_array("ALL", $this->{$Property}) || array_key_exists("ALL", $this->{$Property})){
				return TRUE;
			} else {
				return FALSE;
			}
		} else{
			return FALSE;
		}
	}

	/**
	 * This function checks if the token gives access to do the specific operation
	 * on a class property
	 * @param string $Field     The field to search for
	 * @param string $Operation The operation, the current operations are "READ","WRITE","DELETE","UPDATE"
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Has_Access($Field = NULL,$Operation = NULL){
		if(!is_null($Field) && !is_null($Operation) && self::_No_Change($Field,$Operation)){
			if(self::_Settings_Property($Field,"_INTERNAL_".$Operation."_ACCESS_LEVELS",$Value)){
				if($this->Level < $Value+1){
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a key is in the no change array
	 * @param string $Field The field to check
	 * @param string $Operation The current operation
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _No_Change($Field = NULL,$Operation = "READ"){
		if(!is_null($Field)){
			if(property_exists($this, "_INTERNAL_NO_CHANGE") && isset($this->_INTERNAL_NO_CHANGE) && is_array($this->_INTERNAL_NO_CHANGE) && in_array($Field, $this->_INTERNAL_NO_CHANGE) && $Operation != "DELETE"){
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if a specific field contains the user id
	 * @param string|array $Data  The data to check in
	 * @param String $Field The field to check for
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Contains_UserId($Data = NULL,$Field = NULL){
		if(!is_null($Data) && !is_null($Field) && !is_null($this->UserId)){
			if(isset($Data[$Field])){
				if(is_array($Data[$Field])){
					return in_array($this->UserId, $Data[$Field]);
				} else {
					if($Data[$Field] == $this->UserId){
						return TRUE;
					} else {
						return FALSE;
					}
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function checks if the field is in the user influence settings
	 * variables and if they are it checks if it has user influence/has access to the content.
	 * @param string $Field     The field to check
	 * @param stirng $Operation The operation to perform
	 * @param string|array $Data      The data to check in
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Check_For_User_Influence($Operation = NULL,$Data = NULL){
		if(!is_null($Operation) && !is_null($Data) && is_array($Data)){
			if(self::_Settings_Property($Operation,"_INTERNAL_USER_INFLUENCE_OPERATIONS",$Value)){
				$Return = FALSE;
				$Other = FALSE;
				$Respond = FALSE;
				foreach ($Data as $Field => $FieldValue) {
					if(self::_Settings_Property($Field,"_INTERNAL_USER_INFLUENCE_FIELDS",$Value)){
						$Contains = self::_Contains_UserId($Data,$Field);
						if($Contains === TRUE){
							$Return = TRUE;
						}
						$Respond = TRUE;
					} else {
						$Other = TRUE;
					}
				} 
				if($Respond == FALSE && $Other == true){
					$Return = TRUE;
				}
				return $Return;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function merges the old data with the new
	 * @since 1.0
	 * @access public
	 * @param integer $Id The id of the object to update
	 * @param array $Input The data to patch with
	 * @return boolean
	 */
	public function Update($Id = NULL,$Input = NULL){
		return self::_Update($Id,$Input,false);
	}

	/**
	 * This function is used for PUT request,
	 * and it overwrites existing data
	 * in the editied fields
	 * @since 1.0
	 * @access public
	 * @return boolean
	 * @param integer $Id The id of the object to update
	 * @param array $Input The data to patch with
	 */
	public function Overwrite($Id = NULL,$Input = NULL){
		return self::_Update($Id,$Input,true);
	}

	/**
	 * This function patches or replace data in a object
	 * @param integer  $Id        The id of the object to update
	 * @param array  $Input     The data to update with
	 * @param boolean $Overwrite If overwrite or meger
	 * @since 1.0
	 * @access private
	 * @return boolean
	 */
	private function _Update($Id = NULL,$Input = NULL,$Overwrite = FALSE){
		if(!is_null($Id) && !is_null($Input) && $this->Level < $this->_CI->config->item("api_write_access_token_max")+1){
			$this->_CI->load->library(strtolower($this->Library));
			$Object = new $this->Library();
			if(!is_null($Object)){
				$Object->Load($Id);
				$Data = array();
				//Consider a $Object->Clear() here if overwrite is true
				foreach ($Input as $Key => $Value) {
					if(self::_Has_Access($Key,"UPDATE")){
						$Query[$Key] = $Value;
					}
				}
				$Object->Import($Data,$Overwrite,!$this->SecretAccess);
				$Data = $Object->Export(false,!$this->SecretAccess);
				$Query = array();
				foreach ($Data as $Key => $Value) {
					if(self::_Has_Access($Key,"UPDATE")){
						$Query[$Key] = $Value;
					}
				}
				if(self::_Check_For_User_Influence("UPDATE",$Query) && count($Query) > 0){
					$Object->Save();
					$this->Response[$this->Library][] = $Query;
					$this->ResponseObject = $Object;
					return TRUE;
				} else {
					return FALSE;
				}
			} else {

				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function loads an object based on the input id,
	 * and then is the ouput checked for user influence
	 * @param integer $Id The id of the object to load
	 * @return boolean
	 * @since 100
	 * @access public
	 */
	public function Read($Id = NULL){
		if(!is_null($Id) && $this->Level < $this->_CI->config->item("api_read_access_token_max")+1){
			$this->_CI->load->library(strtolower($this->Library));
			$Object = new $this->Library();
			if(!is_null($Object)){
				$Object->Load($Id);
				$Data = $Object->Export(false,!$this->SecretAccess);
				$Query = array();
				foreach ($Data as $Key => $Value) {
					if(self::_Has_Access($Key,"READ")){
						$Query[$Key] = $Value;
					}
				}
				if(self::_Check_For_User_Influence("READ",$Query) && count($Query) > 0){
					$this->Response[$this->Library][] = $Query;
					$this->ResponseObject = $Object;
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * The 
	 * @param integer $Id The id to delete
	 */
	public function Delete($Id = NULL){
		if(!is_null($Id) && $this->Level < $this->_CI->config->item("api_delete_access_token_max")+1){
			$this->_CI->load->library(strtolower($this->Library));
			$Object = new $this->Library();
			if(!is_null($Object)){
				$Object->Load($Id);
				$Data = $Object->Export(false,!$this->SecretAccess);
				$Query = array();
				foreach ($Data as $Key => $Value) {
					if(self::_Has_Access($Key,"DELETE")){
						$Query[$Key] = $Value;
					}
				}
				if(self::_Check_For_User_Influence("DELETE",$Query) && count($Query) > 0){
					$Object->Delete(true);
					$this->ResponseObject = $Object;
					$this->Response[$this->Library] = array();
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function Search($Query = NULL){

	}
}
?>