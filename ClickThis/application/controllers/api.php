<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller {

	/**
	 * This function is called if there's requested data about a series
	 * @param integer $Id The database id of the series to load, if the method is get
	 * @access public
	 * @since 1.0
	 */
	public function Series($Id = NULL){
		if(!is_null($Id)){
			self::Standard_API("Series",$Id);
		} else {
			if(isset($_GET["Creator"])){
				self::Search(array("Creator" => $_GET["Creator"]),"Series","Series");
			} else {
				self::Send_Response(400);
			}
		}
	}

	public function Series_Test($Id = NULL,$Override = true){
		$this->load->library("api_request");
		$this->load->library("Series");
		$this->api_request->Perform_Request();
		if(!is_null($Id)){
			$Series = new Series();
			switch ($this->api_request->Request_Method()) {
				case 'get':
					self::Standard_API("Series",$Id);
					break;
				
				case 'post':

					$Series->Import(self::EnsureCase($this->api_request->Request_Vars()));
					if($Series->Save() == true){
						echo json_encode($Series->Export(false));
					} else {
						self::Send_Response(400);
					}
					break;

				case 'put':
					$Decoded = json_decode($this->api_request->Request_Vars(),true);
					$Series->Load($Id);
					$Series->Import(self::EnsureCase($Decoded),$Override);
					$Series->Save();
					print_r($Series->Export());
					break;

				case 'delete':
					self::Delete("Series",$Id);
					break;
			}
		} else {
			/*$this->load->model("Api_Search");
			$Search = new Api_Search();
			$Search->Table = "Series";
			$Response = $Search->Search(array("Creator" => "21"),"Series",true);
			self::Send_Response(200,NULL,json_encode($Response));*/
			self::Search(array("Creator" => "21"),"Series","Series");
		}
	}

	##### Test #####
	private function EnsureCase($Array = NULL){
		if(!is_null($Array)){
			if(is_array($Array)){
				$Return = array();
				foreach ($Array as $Key => $Data) {
					$Return[ucfirst($Key)] = $Data;
				}
			} else if(gettype($Array) == "string"){
				$Return = ucfirst($Array);
			} else {
				return $Array;
			}
			return $Return;
		}
	}

	private function Search($Query = NULL,$Table = NULL,$ClassName = NULL,$Limit = NULL){
		if(!is_null($Query) && !is_null($Table)){
			$this->load->model("Api_Search");
			$Search = new Api_Search();
			if(!is_null($ClassName)){
				$Search->Table = $Table;
			}
			if(!is_null($Limit)){
				$Search->Limit = $Limit;
			}
			$Response = $Search->Search($Query,$ClassName,true);
			if($Response){
				self::Send_Response(200,NULL,json_encode($Response));
			} else {
				self::Send_Response(404);
			}
		}
	}

	private function Delete($Class = NULL,$Id = NULL){
		if(!is_null($Class) && !is_null($Id)){
			$this->load->library($Class);
			$Series = new $Class();
			$Series->load($Id);
			$Series->Delete(true);
			print_r($Series->Export());
		} else {
			self::Send_Response(400);
		}
	}

	################


	/**
	 * This function outputs the content if it exists or show an error code
	 * @param integer $Code         The HTTP status code to send
	 * @param string  $Content_Type The mime type of the content
	 * @param string  $Content      The content to show
	 * @since 1.0
	 * @access public
	 */
	private function Send_Response($Code = 200,$Content_Type = "application/json",$Content = NULL){
		if(is_null($Content_Type)){
			$Content_Type = "application/json";
		}
		if(is_null($Code)){
			$Code = 200;
		}
		$this->load->library("api_request");
		$Status_Header = 'HTTP/1.1 ' . $Code . ' ' . $this->api_request->Get_Message($Code); 
		header($Status_Header); 
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
	 * Thus function handles the standard API calls
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