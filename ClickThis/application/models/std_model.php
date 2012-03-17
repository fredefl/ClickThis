<?php
/**
 * This is the standard model, for normal data
 * @package Standard Model
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Std_Model
 * @category Models
 * @version 1.1
 * @author Illution <support@illution.dk>
 */ 
class Std_Model extends CI_Model{

	/**
	 * This property contains data to convert, 
	 * class property names to databse rows
	 * @var array
	 * @since 1.1
	 * @access private
	 */
	private $_INTERNAL_DATABASE_NAME_CONVERT = NULL;

	/**
	 * This property do the opposite as _INTERNAL_DATABASE_NAME_CONVERT
	 * @var array
	 * @see $_INTERNAL_DATABASE_NAME_CONVERT
	 * @access private
	 * @since 1.1
	 */
	private $_INTERNAL_ROW_NAME_CONVERT = NULL;

	/**
	 * This function is the constructor, it creates a local instance of CodeIgniter
	 * @access public
	 * @since 1.0
	 */
	public function __construct()
    {
		//$this->CI =& get_instance();
        parent::__construct();
    }

    /**
     * This function sets the _INTERNAL_DATABASE_NAME_CONVERT and the _INTERNAL_ROW_NAME_CONVERT property,
     * that is used to convert property names to database rows
     * @param array $Names The names convert array
     * @param string $Type The config option to set properties are "DATABASE_NAME_CONVERT" or "ROW_NAME_CONVERT"
     * @access public
     * @since 1.1
     */
    public function Set_Names($Names = NULL,$Type = "DATABASE_NAME_CONVERT"){
    	if(!is_null($Names) && !is_null($Type) && is_array($Names)){
    		switch($Type){
    			case "DATABASE_NAME_CONVERT":
		        	$this->_INTERNAL_DATABASE_NAME_CONVERT = $Names;
		        	$this->_INTERNAL_ROW_NAME_CONVERT = array();
		        	foreach($Names as $Key => $Value){
		        		$this->_INTERNAL_ROW_NAME_CONVERT[$Value] = $Key;
		        	}
		        break;

		        case "ROW_NAME_CONVERT":
		        	if(!is_null($this->_INTERNAL_ROW_NAME_CONVERT)){
		        		$this->_INTERNAL_ROW_NAME_CONVERT = array_merge($this->_INTERNAL_ROW_NAME_CONVERT,$Names);
		        	} else {
		        		$this->_INTERNAL_ROW_NAME_CONVERT = $Names;
		        	}
		        break;

		        default :

		        break;
        	}
        }
    }

    /**
	 * This function uses the internal _INTERNAL_DATABASE_NAME_CONVERT to convert the property names,
	 * to the database row names
	 * @param array $Data The exported data, from the class
	 * @access private
	 * @since 1.0
	 * @see _INTERNAL_DATABASE_NAME_CONVERT
	 * @internal This function is only used inside this model, to convert the exported data to the right format
	 * @return array The data with the right key names
	 */
	private function Convert_Properties_To_Database_Row($Data = NULL){
		if(!is_null($Data)){
			if(property_exists(get_class($this), "_INTERNAL_DATABASE_NAME_CONVERT") && !is_null($this->_INTERNAL_DATABASE_NAME_CONVERT)){
				$Array = array();
				foreach ($Data as $Key => $Value) {
					if(array_key_exists($Key,$this->_INTERNAL_DATABASE_NAME_CONVERT)){
						$Array[$this->_INTERNAL_DATABASE_NAME_CONVERT[$Key]] = $Value;
					} else {
						$Array[$Key] = $Value;
					}
				}
				return $Array;
			} else {
				return $Data;
			}	
		}
	}

	/**
	 * This function loads data from $Table, based on the query in $Link
	 * @param string||array $Table  The table(s) to search in 
	 * @param array $Link   An array with the query
	 * @example
	 * Link("Questions",array("Lmmaa" => "Duck",$this));
	 * @param object &$Class The class where the data is taken from
	 * @return array An array of the query result data
	 * @since 1.0
	 * @access public
	 */
	public function Link($Table = NULL,$Link = NULL,&$Class = NULL){
		if(!is_null($Table) && !is_null($Link) && !is_null($Class) && is_array($Link)){
			if(!is_array($Table)){
				$this->db->select('Id');
				$Query = $this->db->get_where($Table,$Link);
				$Result = $Query->result();
				return $Query->result();
			} else {
				$Result = array();
				foreach ($Table as $Name) {
					$this->db->select('Id');
					$Query = $this->db->get_where($Name,$Link);
					$Temp = $Query->result();
					$Result[] = $Temp[0];
				}
				if(count($Result) > 0){
					return $Result;
				}

			}
		}
	}

	/**
	 * This function checks if a row exists in the database
	 * @param integer $Id The database row id for the row to check for
	 * @param string $Table The database table to look up in
	 * @access private
	 * @since 1.0
	 * @return boolean The result, if the user doesn't exist or the input is wrong then FALSE is returned,
	 * else TRUE is returned.
	 */
	private function Exists($Id = NULL,$Table = NULL){
		if(!is_null($Id) && !is_null($Table)){
			$Query = $this->db->get_where($Table,array("Id" => $Id));
			if($Query->num_rows() == 0){
				return false;
			}
			else{
				return true;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * This function loads class data from the database table,
	 * and assign it to the object in $Class
	 * @param integer $Id    An optional database id for the row, if it's not deffined the $Class->Id will be used.
	 * @param object &$Class The class to assign the data too
	 * @return boolean If there's data available and it's loaded true is returned else is false returned
	 * @access public
	 * @since 1.0
	 */
	public function Load($Id = NULL,&$Class = NULL){
		if(!is_null($Class) && property_exists(get_class($Class), "Database_Table")){
			if(!is_null($Id)){
				$Class->Id = $Id;
			}
			if(!is_null($Class->Id) && self::Exists($Class->Id,$Class->Database_Table)){
				$ClassQuery = $this->db->get_where($Class->Database_Table,array("Id" => $Class->Id));
				foreach($ClassQuery->result() as $Row){
					foreach ($Row as $Key => $Value) {
						if(property_exists($this,"_INTERNAL_ROW_NAME_CONVERT") 
							&& is_array($this->_INTERNAL_ROW_NAME_CONVERT) 
							&& array_key_exists($Key, $this->_INTERNAL_ROW_NAME_CONVERT))
						{
							if(property_exists(get_class($Class), $this->_INTERNAL_ROW_NAME_CONVERT[$Key])){
								if(!is_null($Value) && !empty($Value) && $Value != ""){
									if(strpos($Value, ";") == true){
										$Class->{$this->_INTERNAL_ROW_NAME_CONVERT[$Key]} = explode(";", $Value);
									} else {
										$Class->{$this->_INTERNAL_ROW_NAME_CONVERT[$Key]} = $Value;
									}
								}
							}
						} else {
							if(property_exists(get_class($Class), $Key)){
								if(!is_null($Value) && !empty($Value) && $Value != ""){
									if(strpos($Value, ";") == true){
										$Class->{$Key} = explode(";", $Value);
									} else {
										$Class->{$Key} = $Value;
									}
								}
							}
						}
					}
				}
				return TRUE;
			} else {
				return FALSE;
			}
			
		} else {
			return false;
		}
	}

	public function Create(&$Class){
		if(method_exists($Class, "Export") && property_exists(get_class($Class), "Database_Table")){
			$data = $Class->Export(true);
			$this->CI->db->insert($Class->Database_Table, $data); 
			return $this->CI->db->insert_id();
		}
	}

	/**
	 * This function saves the class data to the server
	 * @param object &$Class The instance of the class, with the data to save
	 * @access public
	 * @since 1.0
	 * @return boolean If the operation was succes
	 */
	public function Save(&$Class){
		if( property_exists(get_class($Class), "Database_Table")){
			if($Class->Id != NULL && self::Exists($Class->Id,$Class->Database_Table)){
				$Data = $Class->Export(true);
				if(property_exists(get_class($Class), "Database_Table")){
					$this->db->update($Class->Database_Table, self::Convert_Properties_To_Database_Row($Data), array('Id' => $Class->Id));
					return true; //Maybe a check for mysql errors
				} else {
					return false;
				}
			}
			else{
				if(!self::Exists($Class->Id)){
					$Data = $Class->Export(true);
					if(property_exists(get_class($Class), "Database_Table")){
						$this->db->insert($Class->Database_Table, self::Convert_Properties_To_Database_Row($Data));
						$Class->Id = $this->db->insert_id();
						return true; //Maybe a check for mysql errors?
					}
				}
			}
		} else {
			return false;
		}
	}
}
?>