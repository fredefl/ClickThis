<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller {

	/**
	 * This function is called if there's requested data about a series
	 * @param integer $Id The database id of the series to load, if the method is get
	 * @access public
	 * @since 1.0
	 */
	public function Series($Id = NULL){
		self::Standard_API("Series",$Id);
	}

	/**
	 * This function outputs the content if it exists or show an error code
	 * @param integer $Code         The HTTP status code to send
	 * @param string  $Content_Type The mime type of the content
	 * @param string  $Content      The content to show
	 * @since 1.0
	 * @access public
	 */
	private function Send_Response($Code = 200,$Content_Type = "application/json",$Content = NULL){
		$this->load->library("api_request");
		$Status_Header = 'HTTP/1.1 ' . $Code . ' ' . $this->api_request->Get_Message($Code); 
		//header($Status_Header); 
		header('Content-type: ' . $Content_Type);
		if(is_null($Content)){
			$Error = array("error_message" => $this->api_request->Get_Message($Code),"error_code" => $Code);
			echo json_encode($Error);
		} else {
			echo $Content;
		}
	}

	public function User($Id = NULL){
		self::Standard_API("User",$Id);
	}

	public function Group($Id = NULL){
		self::Standard_API("Group",$Id);
	}

	public function Question($Id = NULL){
		self::Standard_API("Question",$Id);
	}

	public function Answer($Id = NULL){
		self::Standard_API("Answer",$Id);
	}

	public function Option($Id = NULL){
		self::Standard_API("Option",$Id);
	}

	public function DidAnswer($Id = NULL){
		self::Standard_API("DidAnswer",$Id);
	}

	public function State($Id = NULL){
		self::Standard_API("State",$Id);
	}

	public function Country($Id = NULL){
		self::Standard_API("Country",$Id);
	}

	public function Teacher($Id = NULL){
		self::Standard_API("Teacher",$Id);
	}

	public function Pupil($Id = NULL){
		self::Standard_API("Pupil",$Id);
	}

	public function School($Id = NULL){
		self::Standard_API("School",$Id);
	}


	/**
	 * [Standard_API description]
	 * @param string $Class The class name of the class to use
	 * @param integer $Id    The database id of the data to get
	 * @access private
	 * @since 1.0
	 */
	private function Standard_API($Class = NULL,$Id = NULL){
		if(!is_null($Id) && !is_null($Class)){
			$this->load->library("api_request");
			$this->load->library($Class);
			$Api_Request = new Api_Request();
			$Api_Request->Perform_Request();
			switch ($Api_Request->Request_Method()) {
				case 'get':
					self::Perform_Get_Operation($Class,$Id);
					break;
				
				/*case 'post':
					break;

				case 'delete':
					break;

				case 'put':
					break;*/
			}
		} else {
			self::Send_Response(400);
		}
	}

	/**
	 * This function performs the standard load data operations
	 * of this basic API
	 * @param string $Class_Name The class name of the class to load data with
	 * @param integer $Id         The database id
	 * @access private
	 * @since 1.0
	 */
	private function Perform_Get_Operation($Class_Name = NULL,$Id = NULL){
		$this->load->library("api_request");
		if(!is_null($Id)){
			$Class = new $Class_Name();
			if($Class->Load($Id)){
				self::Send_Response(200,"application/json",json_encode($Class->Export(false,true)));
			} else {
				self::Send_Response(404);
			}
		} else {
			self::Send_Response(400);
		}	
	}
}