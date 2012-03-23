<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This model searches for data, etc users and load em up with etc a class
 * @package API
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Search
 * @category Model
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Api_Search extends CI_Model{

	/**
	 * Local instance of Code Ingiter
	 * @since 1.0
	 * @access private
	 * @var object
	 */
	private $_CI = NULL;

	/**
	 * The search limit etc 10
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Limit = 10;

	/**
	 * The table to search in
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Table = NULL;

	/**
	 * The constructor
	 * @access public
	 * @since 1.0
	 */
	public function __construct(){
		$this->_CI =& get_instance();
        parent::__construct();
    }	

    /**
     * This function is used to search in the database for multiple objects,
     * and add them to an array
     * @param string|array  $Query     The search query
     * @param string  $ClassName An optional class name, if deffined it will be used to load up data,
     * @param boolean $Export    If is set to true, then the Export function on the object is used
     * @param boolean $Database  The export database parameter on the export function
     * @param boolean $Secure    The export secure paramter on the export function
     * @return boolean|array FALSE is returned if it failed
     * @access public
     * @since 1.0
     */
    public function Search($Query = NULL,$ClassName = NULL,$Export = false,$Database = false,$Secure = false){
    	if(!is_null($this->Table) && !is_null($Query)){
    		if(is_null($this->Limit)){
    			$Limit = 10;
    		}
    		if(is_null($ClassName)){
    			$Return = array();
	    		$Raw = $this->db->limit($this->Limit)->get_where($this->Table, $Query);
	    		foreach ($Raw->result() as $Row) {
	    			$Temp = array();
	    			foreach ($Row as $Key => $Value) {
	    				$Temp[$Key] = $Value;
	    			}
	    			$Return[] = $Temp;
	    		}
    		} else {
    			$Return = array();
    			$Raw = $this->db->limit($this->Limit)->select("Id")->get_where($this->Table, $Query);
    			foreach ($Raw->result() as $Row) {
    				if(!is_null($Row) && property_exists($Row, "Id")){
    					$this->_CI->load->library($ClassName);
    					$Class = new $ClassName();
    					$Temp = array();
    					if(!is_null($Class)){
    						if(method_exists($Class, "Load")){
    							$Class->Load($Row->Id);
    							if($Export && method_exists($Class, "Export")){
    								$Temp[] = $Class->Export($Database,$Secure);
    							} else {
    								$Temp[] = $Class;
    							}
    						}
    					}
    					if(count($Temp) > 0){
    						$Return[] = $Temp;
    					}
    				} else {
    					return FALSE;
    				}
    			}
    		}
    		if(count($Return) > 0){
    			return $Return;
    		} else {
    			return FALSE;
    		}
    	} else {
    		return FALSE;
    	}
    }

}