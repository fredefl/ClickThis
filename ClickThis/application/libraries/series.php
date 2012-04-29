<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * This class is used to load up the data about a series
 * @package Surveys
 * @license http://illution.dk/copyright © Illution 2012
 * @subpackage Series
 * @category Surveys
 * @version 1.0
 * @author Illution <support@illution.dk>
 * @todo Add the possibility to link between databases, so the series class
 * can load up the questions and groups
 */ 
class Series extends Std_Library{

	/**
	 * The database id of the series
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $Id = NULL;

	/**
	 * An array of the questions of the Series, in objects
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $Questions = NULL;

	/**
	 * The start time of the series, when it's going to be published
	 * @var integer
	 * @since 1.0
	 * @access public
	 */
	public $StartTime = NULL;

	/**
	 * The time that the series will end
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $EndTime = NULL;

	/**
	 * The surveys title
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Title = NULL;

	/**
	 * The database id of the creator
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $Creator = NULL;

	/**
	 * The state of the series,
	 * etc published = 1, done = 2
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $State = NULL;

	/**
	 * If the series is anonymous or not
	 * @var integer
	 * @since 1,0
	 * @access public
	 */
	public $Type = NULL;

	/**
	 * The series description, will be shown in the series view
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Description = NULL; 

	/**
	 * The groups that is going to be able see the series
	 * @var array
	 * @since 1.0
	 * @access public
	 */
	public $TargetGroup = NULL;

	/**
	 * This property can be used to share a series with only peoples instead
	 * of groups or just some extra people that isn't in the TargetGroups
	 * @var array
	 * @since 1.0
	 * @todo Add this property to the Database
	 * @access public
	 */
	public $TargetPeople = NULL;

	/**
	 * The text to show in the beginning of the series
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $StartText = NULL;

	/**
	 * The text to show in the end of the survey
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $EndText = NULL;

	/**
	 * If the sruvey is going to be shared public, private or with link.
	 * 0 is not shared, 1 is public, 2 is private and 3 is with link
	 * @var integer
	 * @access public
	 * @since 1.0
	 */
	public $ShareType = NULL;

	### Class Settings ###

	/**
	 * This variable stores the database table for the class
	 * @var string
	 * @access public
	 * @since 1.0
	 */
	public $Database_Table = "Series";

	/**
	 * The local instance of CodeIgniter
	 * @var object
	 * @access private
	 * @since 1.0
	 * @internal This is a local instance of CodeIgniter it's only used in the class
	 */
	private $_CI = NULL;

	/**
	 * The constructor, it sets some settings for the std_library to work
	 */
	public function Series () {
		$this->_CI =& get_instance();
		self::Config($this->_CI);
		$this->_INTERNAL_DATABASE_NAME_CONVERT = array(
			"Type" => "SeriesType"
		);
		$this->_INTERNAL_NOT_ALLOWED_DUBLICATE_ROWS = array("Creator","EndTime","StartTime","Title");
		$this->_INTERNAL_EXPORT_INGNORE = array("CI","Database_Table","_CI");
		$this->_INTERNAL_DATABASE_EXPORT_INGNORE = array("Id","Questions");
		$this->_INTERNAL_SIMPLE_LOAD = array("Creator" => true);
		$this->_INTERNAL_LOAD_FROM_CLASS = array(
			"TargetGroup" => "Group",
			"TargetPeople" => "User",
			"Questions" => "Question",
			"Creator" => "User"
		);
		$this->_INTERNAL_FORCE_ARRAY = array(
			"Questions",
			"TargetPeople",
			"TargetGroup"
		);
		$this->_INTERNAL_LINK_PROPERTIES = array("Questions" => array("Questions",array("SeriesId" => "Id")));
		$this->_CI->load->model("Std_Model","_INTERNAL_DATABASE_MODEL");
		$this->_CI->_INTERNAL_DATABASE_MODEL->Set_Names($this->_INTERNAL_DATABASE_NAME_CONVERT);
	}
}
?>