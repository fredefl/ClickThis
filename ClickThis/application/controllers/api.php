<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @version 1.1
 */
class Api extends CI_Controller {

	/**
	 * This function is called if there's requested data about a series
	 * @param integer $Id The database id of the series to load, if the method is get
	 * @access public
	 * @since 1.0
	 */
	public function Series($Id = NULL){
		self::Standard_API("Series",$Id,"Series");
	}

	public function __construct(){
		parent::__construct();
		$this->load->library("api_request");
	}

	##### Test #####
	/**
	 * This function performs the PUT requests
	 * @param string  $ClassName The classname of the class to use
	 * @param integer  $Id        The id to update
	 * @param boolean $Override  If the existing data is going to be overwritten
	 * @since 1.1
	 * @access private
	 */
	private function _Update($ClassName = NULL,$Id = NULL,$Override = true){
		if(!is_null($ClassName) && !is_null($Id)){
			$this->load->library($ClassName);
			$Class = new $ClassName();
			$Class->Load($Id);
			$Decoded = json_decode($this->api_request->Request_Vars(),true);
			$Class->Import(self::EnsureCase($Decoded),$Override);
			if($Class->Save() == true){
				$Response = array();
				$Response["error_message"] = NULL;
				$Response["error_code"] = NULL;
				self::Send_Response(200,NULL,json_encode($Response));
			} else {
				self::Send_Response(400);
			}
		}
	}


	/**
	 * This function performs the POST request
	 * @param string $ClassName The name of the class to use
	 * @since 1.1
	 * @access private
	 */
	private function _Create($ClassName = NULL,$Data =NULL){
		if(!is_null($ClassName)){
			$this->api_request->Perform_Request();
			$this->load->library($ClassName);
			if(is_null($Data)){
				$this->api_request->Perform_Request();
				$Data = $this->api_request->Request_Data();
				if(isset($Data[$ClassName])){
					$Data = $Data[$ClassName];
				} else {
					self::Send_Response(400);
					return FALSE;
				}
			}
			$Class = new $ClassName();
			$Class->Import($Data);
			if($Class->Save() == true){
				$Response = array();
				$Response[$ClassName] = array("Id" => $Class->Id);
				$Response["error_message"] = NULL;
				$Response["error_code"] = NULL;
				self::Send_Response(200,NULL,json_encode($Response));
			} else {
				self::Send_Response(400);
			}
		}
	}

	/**
	 * [EnsureCase description]
	 * @param [type] $Array [description]
	 */
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

	/**
	 * This function performs the DELETE operations
	 * @param string $ClassName The name of the class to use
	 * @param integer $Id    The id of the data to delete
	 * @since 1.1
	 * @access private
	 */
	private function _Delete($ClassName = NULL,$Id = NULL){
		if(!is_null($Class) && !is_null($Id)){
			$this->load->library($ClassName);
			$Class = new $ClassName();
			$Class->load($Id);
			$Class->Delete(true);
			print_r($Class->Export());
		} else {
			self::Send_Response(400);
		}
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
		if(is_null($Content_Type)){
			$Content_Type = "application/json";
		}
		if(is_null($Code)){
			$Code = 200;
		}
		
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

	/**
	 * This function is used to search for more all,
	 * objects that mactches the specified pattern
	 * @param array $Query     The search query
	 * @example
	 * Search(array("RealName" => "Bo"),"Users","User",10);
	 * @param string $Table     The database table to search in
	 * @param string $ClassName An optional class to load the data up with
	 * @param integer $Limit     The max count of results
	 * @access private
	 * @since 1.0
	 */
	private function Search($Query = NULL,$Table = NULL,$ClassName = NULL,$Limit = NULL,$ArrayName = NULL,$Return = false){
		if(!is_null($Query) && !is_null($Table)){
			$this->load->model("Api_Search");
			
			$Search = new Api_Search();
			if(!is_null($ClassName)){
				$Search->Table = $Table;
			}
			if(!is_null($Limit)){
				$Search->Limit = $Limit;
			}
			if(is_null($ArrayName)){
				$ArrayName = $ClassName;
			}
			$Content = $Search->Search($Query,$ClassName,true,false,true);
			if(!$Content){
				$Code = 404;
				$Response[$ArrayName] = array();
				$Response["error_message"] = $this->api_request->Get_Message($Code);
				$Response["error_code"] = $Code;
			} else {
				$Code = 200;
				$Response[$ArrayName] = $Content;
				$Response["error_message"] = NULL;
				$Response["error_code"] = NULL;
			}
			if(!$Return){
				if($Response){
						self::Send_Response($Code,NULL,json_encode($Response));
				} else {
					self::Send_Response($Code);
				}
			} else {
				if(!$Content){
					return FALSE;
				} else {
					return $Content;
				}
			}
		} else {
			$Code = 400;
			self::Send_Response($Code);
		}
	}

	/**
	 * [User description]
	 * @param [type] $Id [description]
	 */
	public function User($Id = NULL){
		$Data = self::Standard_API("User",$Id,"Users","Users",true);
		if($Data === false){
			self::Send_Response(404);
		} else {
			if(!is_null($Data)){
				if(!is_null($Id)){
					$User = $Data;
					($User->CheckProfileImage())? NULL: $User->ProfileImage = "http://gravatar.com/avatar?s=256";
					/*$Data["User"] = $User->Export();
					$Data["error_message"] = NULL;
					$Data["error_code"] = NULL;*/
					$Return = $User->Export(false,true);
					self::Send_Response(200,NULL,json_encode($Return));
				} else {
					$Users = array();
					foreach ($Data as $User) {
						if(is_null($User["ProfileImage"])){
							$User["ProfileImage"] = "http://gravatar.com/avatar?s=256";
							$Users[] = $User;
						} else {
							$Users[] = $User;
						}
					}
					$Return = array();
					$Return["Users"] = $Users;
					$Return["error_message"] = NULL;
					$Return["error_code"] = NULL;
					self::Send_Response(200,NULL,json_encode($Return));
				}
			}
		}
	}

	public function Group($Id = NULL){
		self::Standard_API("Group",$Id,"Groups");
	}

	public function Question($Id = NULL){
		self::Standard_API("Question",$Id,"Questions");
	}

	public function Answer($Id = NULL){
		self::Standard_API("Answer",$Id,"Answers");
		/*if($this->api_request->Request_Method() != "post"){
			self::Standard_API("Answer",$Id,"Answers");
		} else {
			if(isset($_POST)){
				self::
			} else {
				self::Send_Response(400);
			}
		}*/
	}

	public function Option($Id = NULL){
		self::Standard_API("Option",$Id,"Options");
	}

	public function DidAnswer($Id = NULL){
		self::Standard_API("DidAnswer",$Id,"DidAnswer","DidAnswers");
	}

	public function State($Id = NULL){
		self::Standard_API("State",$Id,"States");
	}

	public function Country($Id = NULL){
		self::Standard_API("Country",$Id,"Countries");
	}

	public function Teacher($Id = NULL){
		self::Standard_API("Teacher",$Id,"Teachers");
	}

	public function Pupil($Id = NULL){
		self::Standard_API("Pupil",$Id,"Pupils");
	}

	public function School($Id = NULL){
		self::Standard_API("School",$Id,"Schools");
	}

	public function App($Id = NULL){
		self::Standard_API("App",$Id,"Apps");
	}


	/**
	 * Thus function handles the standard API calls
	 * @param string $Class The class name of the class to use
	 * @param integer $Id    The database id of the data to get
	 * @param string $Table The database table used in search
	 * @access private
	 * @since 1.0
	 */
	private function Standard_API($Class = NULL,$Id = NULL,$Table = NULL,$ArrayName = NULL,$Return = false){
		if(!is_null($Class)){
			
			$this->load->library($Class);
			$Api_Request = new Api_Request();
			$Api_Request->Perform_Request();
			if(!is_null($Id) || $Api_Request->Request_Method() == "post"){
				switch ($Api_Request->Request_Method()) {
					case 'get':
						if($Return === false){
							self::Perform_Get_Operation($Class,$Id);
						} else {
							return self::Perform_Get_Operation($Class,$Id,true);
						}
						break;
					
					case 'post':
						self::_Create($Class);
						break;

					case 'delete':
						self::_Delete($ClassName,$Id);
						break;

					case 'put':
						self::_Update($Class,$Id);
						break;
				}
			} else {
				if(isset($_GET) && !empty($_GET) && $Api_Request->Request_Method() == "get" && !is_null($Table)){
					$Query = array();
					$NotAllowed = array("redirect","consumer_key","consumer_secret","access_token","access_token_secret");
					$Limit = 10;
					foreach ($_GET as $Key => $Value) {
						if(!is_null($Value) && $Value != "" && !in_array($Key, $NotAllowed)){
							if($Key != "Limit"){
								$Query[$Key] = $Value;
							} else {
								$Limit = $Value;
							}
						}
					}
					if(count($Query) > 0){
						if(!$Return){
							self::Search($Query,$Table,$Class,$Limit,$ArrayName);
						} else {
							return self::Search($Query,$Table,$Class,$Limit,$ArrayName,true);
						}
					} else {
						self::Send_Response(400);
					}
				} else {
					self::Send_Response(400);
				}
			}
		} else {
			self::Send_Response(400);
		}
	}

	/**
	 * This function performs the standard load data operations
	 * of this basic API()
	 * @param string $Class_Name The class name of the class to load data with
	 * @param integer $Id         The database id
	 * @access private
	 * @since 1.0
	 */
	private function Perform_Get_Operation($Class_Name = NULL,$Id = NULL,$ArrayName = NULL,$Return = false){
		
		if(!is_null($Id)){
			$Class = new $Class_Name();
			if($Class->Load($Id)){
				if($Return === false){
					$Data[$Class_Name] = $Class->Export(false,true);
					$Data["error_message"] = NULL;
					$Data["error_code"] = NULL;
					self::Send_Response(200,"application/json",json_encode($Data));
				} else {
					return $Class;
				}
			} else {
				self::Send_Response(404);
			}
		} else {
			self::Send_Response(400);
		}	
	}

	/* Authentication */
	public function Auth(){
		$this->load->library("api_authentication");
		if($this->api_authentication->AuthDialog()){
			$this->load->view("auth_view",array("base_url" => base_url()));	
		} else {
			print_r($this->api_authentication->Get("Errors"));
		}
	}

	public function Authenticated(){
		$this->load->library("api_authentication");
		$this->api_authentication->Base_Url(base_url());
		if($this->api_authentication->Auth()){
			if(!is_null($this->api_authentication->Get("Request_Code"))){
				echo $this->api_authentication->Get("Request_Code");
			} else {
				print_r($this->api_authentication->Get("Errors"));
			}
		} else {
			print_r($this->api_authentication->Get("Errors"));
		}
	}

	public function Request_Token(){
		$this->load->library("api_authentication");
		if($this->api_authentication->Request_Token()){
			//Redirect to _RedirecT_Url with the new request tokens as GET parameters
			if(!is_null($this->api_authentication->Get("Redirect_Url"))){
				echo $this->api_authentication->Get("Request_Token"); 
			} else {
				header("Location:".base_url());
			}
		} else {
			print_r($this->api_authentication->Get("Errors"));
		}
	}

	public function Access_Token(){
		$this->load->library("api_authentication");
		if($this->api_authentication->Access_Token()){

		} else {
			print_r($this->api_authentication->Get("Errors"));
		}
	}
}