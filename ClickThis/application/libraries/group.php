<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class stores data about a group
 * @package Users
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Group
 * @category Group Data
 * @version 1.0
 * @author Illution <support@illution.dk>
 */ 
class Group extends Std_Library{
	
	/**
	 * The database id of the group, if it's saved
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $id = NULL;

	/**
	 * The name of the group
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $name = NULL;

	/**
	 * This property stores the id of the members of this grouo
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $members = NULL;

	/**
	 * This property stores the id's of the administrators of this group
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $administrators = NULL;

	/**
	 * This property stores the group's title, displayed on the group page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $title = NULL;

	/**
	 * This property stores a description of the group, displayed on the groups page
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $description = NULL;

	/**
	 * This property stores the database id of the user that created this group
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $creator = NULL;

	#### Class Setttings ####

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "groups";
	
	/**
	 * This function is the constructor, it load's the model regarding this class,
	 * and it creates a local instance of CodeIgniter and place it ind the $this->_CI property
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Name","Creator");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("id");
		$this->_INTERNAL_SIMPLE_LOAD = array("creator" => true);
		$this->_INTERNAL_LOAD_FROM_CLASS = array("creator" => "User");
	}
}
?>