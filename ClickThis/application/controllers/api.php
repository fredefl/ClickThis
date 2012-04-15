<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @version 1.1
 */
class Api extends CI_Controller {

	/**
	 * This array will contain the occured errors of the api
	 * @var array
	 * @since 1.1
	 * @access private
	 */
	private $_Errors = NULL;

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
		$this->load->library("api_response/std_api_response");
		$this->load->library("api_authentication");
		$this->load->helper("array_xml");
		$this->load->config("api");
	}

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
				self::Send_Response(200,NULL,$Response);
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
				self::Send_Response(200,NULL,$Response);
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
	 * @param array $Reason An array of error reasons
	 * @since 1.0
	 * @access public
	 */
	private function Send_Response($Code = 200,$Content_Type = NULL,$Content = NULL,$Reason = NULL){
		self::_Send_Response($Code,$Content_Type,$Content,$Reason);
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
						self::Send_Response($Code,NULL,$Response);
						return;
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
					($User->CheckProfileImage())? $User->ProfileImage = $User->ProfileImage: $User->ProfileImage = "http://gravatar.com/avatar?s=256";
					$Return["User"] = $User->Export(false,true);
					$Return["error_message"] = NULL;
					$Return["error_code"] = NULL;
					self::Send_Response(200,NULL,$Return);
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
					self::Send_Response(200,NULL,$Return);
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
	 * This function checks if search get parameters is set
	 * @since 1.1
	 * @access private
	 * @return boolean
	 */
	private function _Is_Get_Set(){
		$NotAllowed = $this->config->item("api_search_not_allowed");
		if(isset($_GET)){
			$Exists = FALSE;
			foreach ($_GET as $Key => $Value) {
				if(!in_array($Key, $NotAllowed)){
					$Exists = TRUE;
				}
			}
			return $Exists;
		} else {
			return FALSE;
		}
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
			if(!is_null($Id) || $Api_Request->Request_Method() == "post" || !self::_Is_Get_Set()){
				switch ($Api_Request->Request_Method()) {
					case 'get':
						if($Return === false){
							self::Perform_Get_Operation($Class,$Id);
						} else {
							return self::Perform_Get_Operation($Class,$Id,$ArrayName,true);
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
					case 'patch':

						break;

					case 'options':

						break;
				}
			} else {
				if(isset($_GET) && !empty($_GET) && $Api_Request->Request_Method() == "get" && !is_null($Table)){
					$Query = array();
					$NotAllowed = array("redirect","consumer_key","consumer_secret","access_token","access_secret","token","request_code","request_token","request_secret");
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
			$Class->Load($Id);
			if($Class->Load($Id)){
				if($Return === false){
					$Data = array();
					$Data[$Class_Name] = $Class->Export(false,true);
					$Data["error_message"] = NULL;
					$Data["error_code"] = NULL;
					self::Send_Response(200,$this->api_request->Format(),$Data);
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

	/**
	 * This function outputs the content if it exists or show an error code
	 * @param integer $Code         The HTTP status code to send
	 * @param string  $Content_Type The mime type of the content
	 * @param string  $Content      The content to show
	 * @param array $Reason An array of error reasons
	 * @since 1.1
	 * @access public
	 */
	private function _Send_Response($Code = 200,$Content_Type = NULL,$Content = NULL,$Reason = NULL){
		if(is_null($Content_Type)){
			$Content_Type = $this->api_request->Format();
		}
		if(is_null($Content_Type)){
			$Content_Type = "application/json";
		}
		$Content_Type = $this->api_request->Content_Type($Content_Type);
		if(is_null($Code)){
			$Code = 200;
		}
		$Status_Header = 'HTTP/1.1 ' . $Code . ' ' . $this->api_request->Get_Message($Code); 
		header($Status_Header); 
		header("Content-Language:en");
		header("Age: 1");
		header("Expires: 100");
		header("Cache-Control : 100");
		header("Content-Location: ".$this->config->item("api_host_url"));
		header('Content-type: ' . $Content_Type);
		header("Date:".time());
		header("Access-Control-Allow-Origin: *");
		header("X-Content-Type-Options: nosniff");
		header("X-XSS-Protection: 1; mode=block");
		header('Allow: ' . implode(", ", array("POST","GET","PUT","DELETE","HEAD","PATCH","OPTIONS")), true, 200);
		if(is_null($Content) && $Code != 200){
			$Error = array("error_message" => $this->api_request->Get_Message($Code),"error_code" => $Code);
			if(!is_null($Reason) && is_array($Reason)){
				$Error["error_reason"] = $Reason;
			}
			if($Content_Type == "application/xml"){
				$Content = array_to_xml($Error);
			} else {
				$Content = json_encode($Error);
			}
			ob_start();
			$Len = ob_get_length()+5;
			header("Content-MD5:".md5($Content));
			header("Content-Length:".$Len);
			echo $Content;
			exit();
		} else {
			if($Code == 200){
				$Content["error_message"] = NULL;
				$Content["error_code"] = NULL;
			}
			if($Content_Type == "application/xml"){
				$Content = array_to_xml($Content);
			} else {
				$Content = json_encode($Content);
			}
			header("Content-MD5:".md5($Content));
			header("Content-Length:".strlen($Content));
			echo $Content;
		}
	}

	/**
	 * This function sends a HTTP header
	 * @param integer $Code The HTTP status code to send
	 * @param string $Header The header to send
	 * @since 1.1
	 * @access private
	 */
	private function _Send_Header($Code = NULL,$Header = NULL){
		if(!is_null($Code)){
			$Status_Header = 'HTTP/1.1 ' . $Code . ' ' . $this->api_request->Get_Message($Code); 
			header($Status_Header); 
		} else {
			if(!is_null($Header)){
				header($Header);
			}
		}
	}

	public function UserAuth($Id = NULL){
		self::_Autherized_Api_Request("User",$Id);
	}

	/**
	 * This function performs a api request that uses the new token security system
	 * @param string $LibraryName The library to use etc App,User
	 * @param integer $Id          The id to load if any
	 * @since 1.1
	 * @access private
	 */
	private function _Autherized_Api_Request($LibraryName = NULL,$Id = NULL){
		//If authenticated or not
		if(self::_Authenticate($Secret_Access,$Write_Access,$Reason)){
			if(!is_null($LibraryName)){
				//Load Up the library
				$this->load->library("api_response/".strtolower($LibraryName."_Response"));
				$ClassName = $LibraryName."_Response";
				$Object = new $ClassName();
				$Object->WriteAccess = $Write_Access;
				$Object->UserId = $this->api_authentication->Get("User_Id");
				$Object->Level = $this->api_authentication->Get("Level");

				//If request or search
				if(!is_null($Id) || in_array($this->api_request->Request_Method(), array("post","put","delete","patch","head"))){
					switch ($this->api_request->Request_Method()) {
							case 'get':
								if($Object->Read($Id)){
									self::_Send_Response(200,NULL,$Object->Response);
								} else {
									self::_Send_Response(401,NULL,NULL);
								}
								break;
							
							case 'post':
								if($Object->Create($this->api_request->Request_Data())){
									self::_Send_Response(200,NULL,$Object->Response);
								} else {
									self::_Send_Response(401,NULL,NULL);
								}
								break;

							case 'delete':
								if($Object->Delete($Id)){
									self::_Send_Response(200,NULL,$Object->Response);
								} else {
									self::_Send_Response(401,NULL,NULL);
								}
								break;

							case 'put':
								if($Object->Overwrite($Id,$this->api_request->Request_Data())){
									self::_Send_Response(200,NULL,$Object->Response);
								} else {
									self::_Send_Response(401,NULL,NULL);
								}
								break;

							case 'patch':
								if($Object->Update($Id,$this->api_request->Request_Data())){
									self::_Send_Response(200,NULL,$Object->Response);
								} else {
									self::_Send_Response(401,NULL,NULL);
								}
								break;

							case "head":
								self::_Send_Header(202);
								break;	

							case "options":
								
								break;

							default:
								self::_Send_Response(400,NULL,NULL);
								break;	
					}
				} else {
					if($Object->Search($this->api_request->Request_Data())){
						self::_Send_Response(200,NULL,$Object->Response);
					} else {
						self::_Send_Response(401,NULL,NULL);
					}
				}
			} else {
				self::Send_Response(400);
			}
		} else {
			self::_Send_Response(401,NULL,NULL,$Reason);
		}
	}


	/**
	 * This function performs a check if the specified token(s) is valid
	 * and if the have secret and write access
	 * @param pointer|boolean &$Secret_Access If the token has access to change password and username
	 * @param pointer|boolean $Write_Access If the token has wrtie access
	 * @param array &$Reason        If errors occur then this will contain the errors
	 * @return boolean
	 * @since 1.0
	 * @access private
	 */
	private function _Authenticate(&$Secret_Access = NULL,&$Write_Access = NULL,&$Reason = NULL){
		if($this->api_authentication->Authenticate()){
			$Secret_Access = $this->api_authentication->Get("Secret_Access");
			$Write_Access = $this->api_authentication->Get("Write_Access");
			return TRUE;
		} else {
			$Reason = $this->api_authentication->Get("Errors");
			return FALSE;
		}
	}

	### Authentication ###

	/**
	 * This function assemblies a redirect url, used if the user aren't logged in
	 * @return string The redirect url
	 * @access private
	 * @since 1.1
	 */
	private function _Create_Redirect(){
		$String = uri_string()."?";
		$Parameters = array();
		foreach ($_GET as $Key => $Value) {
			$Parameters[] = $Key."=".$Value;
		}
		return $String.implode("&", $Parameters);
	}

	/**
	 * This function handles the Authentication errors and redirect the user to the right place
	 * @param string $Redirect The redirect url or NULL
	 * @param array $Reason   An array of reasons
	 * @param integer $Code     The HTTP status code of the error
	 * @since 1.1
	 * @access private
	 */
	private  function _Api_Handle_Error($Redirect = NULL,$Reason = NULL,$Code = NULL){
		if(!is_null($Reason) && !is_null($Code)){
			if(!is_null($Redirect)){
				$Message = $this->api_request->Get_Message($Code);
				$Reason = implode(";",$Reason);
				$Status_Header = 'HTTP/1.1 ' . $Code . ' ' . $Message; 
				header($Status_Header); 
				header("Location:".$Redirect."?error_code=".$Code."&"."error_message=".$Message."&error_reason=".$Reason);
			} else {
				header("Location:".base_url());
			}
		} else {
			if(!is_null($Redirect)){
				$Error_Code = 503;
				$Error_Message = $this->api_request->Get_Message(503);
				$Error_Reason = "Sorry and error occured while performing your request.";
				$Status_Header = 'HTTP/1.1 ' . 503 . ' ' . $Error_Message; 
				header($Status_Header); 
				header("Location:".$Redirect."?error_code=".$Error_Code."&"."error_message=".$Error_Message."&error_reason=".$Error_Reason);
			} else {
				header("Location:".base_url());
			}
		}
	}

	/**
	 * This function performs the request, and redirects the user to the Authentication screen,
	 * if the input is valid.
	 * @since 1.1
	 * @access public
	 */
	public function Auth(){
		$this->load->library("app");
		if(isset($_SESSION["UserId"]) && !empty($_SESSION["UserId"])){
			if($this->api_authentication->AuthDialog()){
				$App = new App();
				$App->Load($this->api_authentication->Get("App_Id"));
				$this->load->view("auth_view",array(
					"base_url" => base_url(),
					"app_description" => $App->Description,
					"app_image" => $App->Image,
					"app_name" => $App->Name,
					"cdn_url" => $this->config->item("cdn_url")
				));	
			} else {
				self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),400);
			}
		} else {
			$_SESSION["redirect"] = self::_Create_Redirect();
			redirect($this->config->item("login_page"));
		} 
	}

	/**
	 * This function performs the request, when the user has been by the Authentication screen
	 * @since 1.1
	 * @access public
	 */
	public function Authenticated(){
		$this->api_authentication->Base_Url(base_url());
		if($this->api_authentication->Auth()){
			if(!is_null($this->api_authentication->Get("Request_Code"))){
				if(!is_null($this->api_authentication->Get("Redirect_Url"))){
					$Redirect = $this->api_authentication->Get("Redirect_Url");
					$Request_Code = $this->api_authentication->Get("Request_Code");
					header("Location:".$Redirect."?request_code=".$Request_Code);
				} else {
					header("Location:".base_url());
				}
			} else {
				self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),503);
			}
		} else {
			self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),401);
		}
	}

	/**
	 * This function logs the user out
	 * @since 1.1
	 * @access public
	 */
	public function Logout(){
		if(isset($_SESSION) && isset($_SESSION["UserId"])){
			setcookie("token","",0,"/",$this->config->item("base_url"));
			$Token = NULL;
			if(isset($_SESSION["clickthis_token"])){
				$Token = $_SESSION["clickthis_token"];
			}
			if($this->api_authentication->Logout($Token)){
				$this->load->view("token_view",array("base_url" => base_url(),"DeleteToken" => "true","cdn_url" => $this->config->item("cdn_url")));
			}
			session_unset();
			session_destroy();
		} else {
			redirect($this->config->item("login_page"));
			die();
		}
	}

	/**
	 * This function performs some security functions on
	 * the input
	 * @param string $Input The input to check
	 * @since 1.1
	 * @access private
	 * @return boolean
	 */
	private function _Security($Input = NULL){
		if(!is_null($Input) && $Input != ""){
			$Output = htmlentities($Input);
			if($Output !== $Input){
				return FALSE;
			}
			$Output = htmlspecialchars($Input);
			if($Output !== $Input){
				return FALSE;
			}
			$Output = strip_tags($Input);
			if($Output !== $Input){
				return FALSE;
			}
			$Output = mysql_real_escape_string($Input);
			if($Output !== $Input){
				return FALSE;
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function activates a user based on the specified activation token
	 * @param string $Token The activation token
	 * @access public
	 * @since 1.1
	 */
	public function Activate($Token = NULL){
		if(!is_null($Token)){
			$this->load->model("api_register");
			if($this->api_register->Activate($Token,$UserId)){
				echo "Success your account is activated";
				//Show success view
			} else {
				//Show error view
				echo "Sorry the token isn't correct";
				die();
			}
		} else {
			redirect("register");
			die();
		}
	}

	/**
	 * This function uses jCryption to descrypt data
	 * @param string $Data    The data to descrypt
	 * @param integer $D       The d rsa key
	 * @param integer $N       The n rsa key
	 * @param pointer|array &$Return The variable to store the decrypted data in
	 * @return boolean
	 * @since 1.1
	 * @access private
	 */
	private function _Decrypt($Data = NULL,$D = NULL,$N = NULL,&$Return = NULL){
		if(!is_null($Data) && !is_null($D) && !is_null($N)){
			$this->load->library("jcryption");
			$jCryption = new jCryption();
			$Decrypted = $jCryption->decrypt($Data,$D,$N);
			parse_str($Decrypted,$Parsed);	
			$Return = $Parsed;
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * This function decrypts the input and matches it with the database data.
	 * If the user exists and the username and password are correct.
	 * @since 1.1
	 * @access public
	 */
	public function Login(){
		if(isset($_POST['jCryption']) && isset($_SESSION["d"]["int"]) && isset($_SESSION["n"]["int"])){
			$this->load->model("api_login");
			if(self::_Decrypt($_POST['jCryption'], $_SESSION["d"]["int"], $_SESSION["n"]["int"],$Data)){
				unset($_SESSION["e"]);
				unset($_SESSION["d"]);
				unset($_SESSION["n"]);
				if(self::_Security($Data['login-username']) && self::_Security($Data['login-password'])){
					$UsernameIn = $Data['login-username'];
					$PasswordIn = $Data['login-password'];
					if($this->api_login->Username($UsernameIn,$Row)){
						if($UsernameIn === $Row["Username"] && hash_hmac("sha512", $PasswordIn, $this->config->item("api_hash_hmac")) === $Row["Password"] && $Row["Status"] == 1){
							$_SESSION["UserId"] = $Row["Id"];
							redirect($this->config->item("token"));
						} else {
							redirect($this->config->item("login_page"));
						}
					} else {
						redirect($this->config->item("login_page"));
					}
				} else {
					redirect($this->config->item("login_page"));
				}

			} else {
				redirect($this->config->item("login_page"));
			}
		} else {
			redirect($this->config->item("login_page"));
		}
	}

	/**
	 * This function gets a random rsa key from the database,
	 * and returns it as the specified format
	 * @since 1.1
	 * @access public
	 */
	public function Keypair(){
		$this->load->model("api_login");
		$Keypair = $this->api_login->Keypair();
		$this->load->library("jcryption");
		$jCryption = new jCryption();
		$_SESSION["e"] = array("int" => $Keypair["e"], "hex" => $jCryption->dec2string($Keypair["e"],16));
		$_SESSION["d"] = array("int" => $Keypair["d"], "hex" => $jCryption->dec2string($Keypair["d"],16));
		$_SESSION["n"] = array("int" => $Keypair["n"], "hex" => $jCryption->dec2string($Keypair["n"],16));
		$Return = array(
			"e" => $_SESSION["e"]["hex"],
			"n" => $_SESSION["n"]["hex"],
			"maxdigits" => intval($this->config->item("api_rsa_key_length")*2/16+3)
		);
		$this->api_request->Perform_Request();
		self::_Send_Response(200,$this->api_request->Format(),$Return);
		die();
	}

	/**
	 * This function resends a users activation email
	 * @param integer $UserId The user id of the user to resend the email too
	 * @since 1.1
	 * @access public
	 */
	public function ResendEmail($UserId = NULL){
		$this->load->model("api_register");
		if($this->api_register->ResendEmail($UserId)){
			echo "Success, check your email for an activation email, ".'<a href="'.base_url().'resend/email/'.$UserId.'">Resend Email</a>';
		} else {
			//Show error view
			echo "Sorry the user isn't existing";
			die();
		}
	}

	/**
	 * This function checks the input and registers the user
	 * @access public
	 * @since 1.0
	 * @todo Instead of redirecting send a header with No Access
	 */
	public function Register(){
		if($this->input->post("username",TRUE) && $this->input->post("terms",TRUE) == "on" && $this->input->post("full-name",TRUE) != ""){
			$Rows = array("username","password","email","full-name","repassword");
			$Data = array();
			foreach ($Rows as $Row) {
				if($this->input->post($Row,TRUE) && self::_Security($this->input->post($Row,TRUE))){
					$Data[ucfirst($Row)] = $this->input->post($Row,TRUE);
				} else {
					redirect("register");
					die();
				}
			}
			$this->load->model("api_register");
			if($Data["Password"] !== $Data["Repassword"] || strlen($Data["Password"]) < 6){
				redirect("register");
				die();
			}
			$Data["Password"] = hash_hmac("sha512", $Data["Password"], $this->config->item("api_hash_hmac"));
			if($this->api_register->User_Exists($Data["Username"],$Data["Email"])){
				redirect("register");
				die();
			}
			if($this->api_register->Register($Data["Username"],$Data["Password"],$Data["Full-name"],$Data["Email"],$UserId)){
				//Load success view
				echo "Success, check your email for an activation email, ".'<a href="'.base_url().'resend/email/'.$UserId.'">Resend Email</a>';
			} else {
				redirect("register");
				die();
			}
		} else {
			redirect("register");
			die();
		}
	}

	/**
	 * This function is used to generate ClickThis tokens
	 * @access public
	 * @since 1.1
	 */
	public function Token(){
		$this->load->library('user_agent');
		if(isset($_SESSION["UserId"])){
			if(isset($_SESSION["clickthis_token"])){
				redirect("home");
			} else {
				$Level = 3;
				if($this->agent->is_mobile() || (isset($_SESSION["platform"]) && $_SESSION["platform"] == "mobile")){
					$Level = 2;
				}
				if($this->api_authentication->ClickThis_Token($Level)){
					$_SESSION["clickthis_token"] = $this->api_authentication->Get("ClickThis_Token");
					header("Location:".base_url()."token/set"."?token=".$_SESSION["clickthis_token"]);
				} else {
					redirect($this->config->item("login_page"));
				}
			}
		} else {
			redirect($this->config->item("login_page"));
		}
	}

	/**
	 * This function creates a new token for the user
	 * @since 1.1
	 * @access public
	 */
	public function Token_Regenerate(){
		if($this->api_authentication->ClickThis_Token(3)){
				$_SESSION["clickthis_token"] = $this->api_authentication->Get("ClickThis_Token");
				header("Location:".base_url()."token/set"."?token=".$_SESSION["clickthis_token"]);
			} else {
				redirect($this->config->item("login_page"));
		}
	}

	public function ResetPassword($Token = NULL){
		$this->load->model("api_resetpassword");
		if(!is_null($Token) && $this->api_resetpassword->Is_Valid_Token($Token) && $this->input->post("password",TRUE) && $this->input->post("repassword",TRUE)){
			if(self::_Security($this->input->post("repassword",TRUE)) && self::_Security($this->input->post("repassword",TRUE))){
				$PasswordIn = $this->input->post("repassword",TRUE);
				$RePasswordIn = $this->input->post("repassword",TRUE);
				if($PasswordIn === $RePasswordIn){
					if($this->api_resetpassword->ChangePassword($PasswordIn,$Token)){
						echo "Your password has been changed ".'<a href="'.base_url().$this->config->item("login_page").'">go back</a>';
					} else {
						redirect($this->config->item("login_page"));
						die();
					}
				} else {
					redirect($this->config->item("login_page"));
					die();
				}
			} else {
				redirect($this->config->item("login_page"));
				die();
			}
		} else {
			redirect($this->config->item("login_page"));
			die();
		}
	}

	/**
	 * This function checks the token and loads the change password view
	 * @param string $Token The change password token
	 * @access public
	 * @since 1.1
	 */
	public function ResetPasswordForm($Token = NULL){
		$this->load->model("api_resetpassword");
		if(!is_null($Token) && $this->api_resetpassword->Is_Valid_Token($Token)){
			$this->load->view("reset_password_endpoint_view",array(
				"base_url" => base_url(),
				"token" => $Token
			));
		} else {
			redirect($this->config->item("login_page"));
			die();
		}
	}

	/**
	 * This function resends the reset password email
	 * @param string $Email The email to resend it too
	 * @access public
	 * @since 1.1
	 */
	public function ResetPasswordResendEmail($Email = NULL){
		if(!is_null($Email)){
			$this->load->model("api_resetpassword");
			$Email = urldecode($Email);
			if($this->api_resetpassword->User_Exists($Email) && $this->api_resetpassword->TokenExists($Email)){
				if($this->api_resetpassword->Reset_Password_Token($Email)){
					redirect($this->config->item("login_page"));
					die();
				} else {
					redirect($this->config->item("login_page"));
					die();
				}
			} else {
				redirect($this->config->item("login_page"));
				die();
			}
		} else {
			redirect($this->config->item("login_page"));
			die();
		}
	}

	/**
	 * This function sends a reset password email to the user if it exists
	 * @since 1.1
	 * @access public
	 */
	public function ResetPasswordEMail(){
		if($this->input->post("email",TRUE) && self::_Security($this->input->post("email",TRUE))){
			$this->load->model("api_resetpassword");
			$Email = $this->input->post("email",TRUE);
			if($this->api_resetpassword->User_Exists($Email) && !$this->api_resetpassword->TokenExists($Email)){
				if($this->api_resetpassword->Reset_Password_Token($Email)){
					echo "Your email has been send, ".'<a href="'.base_url()."reset/password/resend/email/". urlencode($Email).'">Resend email</a>';
				} else {
					redirect($this->config->item("login_page"));
					die();
				}
			} else {
				redirect($this->config->item("login_page"));
				die();
			}
		} else {
			redirect($this->config->item("login_page"));
			die();
		}
	}

	/**
	 * This function is used to do basic HTTP Authentication
	 * @since 1.1
	 * @access public
	 */
	public function Authenticate(){
		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$this->load->model("api_login");
			if(self::_Security($_SERVER['PHP_AUTH_USER']) && self::_Security($_SERVER['PHP_AUTH_PW'])){
				$UsernameIn = $_SERVER['PHP_AUTH_USER'];
				$PasswordIn = $_SERVER['PHP_AUTH_PW'];
				if($this->api_login->Username($UsernameIn,$Row)){
					if($UsernameIn === $Row["Username"] && hash_hmac("sha512", $PasswordIn, $this->config->item("api_hash_hmac")) === $Row["Password"] && $Row["Status"] == 1){
						$_SESSION["UserId"] = $Row["Id"];
						header('HTTP/1.0 202 Accepted');
						redirect("token");
						die();
					} else {
						header('WWW-Authenticate: Basic realm="OAuth"');
			    		header('HTTP/1.0 401 Unauthorized');
			    		die();
					}
				} else {
					header('WWW-Authenticate: Basic realm="OAuth"');
				    header('HTTP/1.0 401 Unauthorized');
				    die();
				}
			} else {
			   	header('WWW-Authenticate: Basic realm="OAuth"');
			    header('HTTP/1.0 401 Unauthorized');
			    die();
			}
		} else {
		   	header('WWW-Authenticate: Basic realm="OAuth"');
		    header('HTTP/1.0 401 Unauthorized');
		    die();
		}
	}

	/**
	 * This function loads the view to set the token
	 * @since 1.1
	 * @access public
	 */
	public function Set_Token(){
		setcookie("token",$_SESSION["clickthis_token"],0,"/",$this->config->item("base_url"));
		$this->load->view("token_view",array("base_url" => base_url(),"cdn_url" => $this->config->item("cdn_url")));
	}

	/**
	 * This function generates a pair of request tokens,
	 * and send the respone to the requester
	 * @since 1.1
	 * @access public
	 */
	public function Request_Token(){		
		if($this->api_authentication->Request_Token()){
			if(!is_null($this->api_authentication->Get("Request_Token")) && !is_null($this->api_authentication->Get("Request_Token_Secret"))){
				if(!is_null($this->api_authentication->Get("Redirect_Url"))){
					$Redirect = $this->api_authentication->Get("Redirect_Url");
					$Secure = $this->api_authentication->Get("Request_Token_Secret");
					$Token = $this->api_authentication->Get("Request_Token");
					header("Location:".$Redirect."?request_token=".$Token."&request_secret=".$Secure);
				} else {
					header("Location:".base_url());
				}
			} else {
				self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),503);	
			}
		} else {
			self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),400);
		}
	}

	/**
	 * This function generates a pair of access tokens,
	 * and redirect the requester to the right place
	 * @since 1.1
	 * @access public
	 */
	public function Access_Token(){	
		if($this->api_authentication->Access_Token()){
			if(!is_null($this->api_authentication->Get("Access_Token")) && !is_null($this->api_authentication->Get("Access_Token_Secret"))){
				if(!is_null($this->api_authentication->Get("Redirect_Url"))){
					$Token = $this->api_authentication->Get("Access_Token");
					$Secret = $this->api_authentication->Get("Access_Token_Secret");
					$Redirect =  $this->api_authentication->Get("Redirect_Url");
					header("Location:".$Redirect."?access_token=".$Token."&access_secret=".$Secret);
				} else {
					header("Location:".base_url());
				}
			} else {
				self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),503);
			}
		} else {
			self::_Api_Handle_Error($this->api_authentication->Get("Redirect_Url"),$this->api_authentication->Get("Errors"),400);
		}
	}
}