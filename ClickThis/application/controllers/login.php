<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

	/**
	 * This function is the standard login function,
	 * it shows the page wth the provider images
	 * @since 1.0
	 * @access public
	 */
	public function index() {
		if(!isset($_SESSION['UserId'])) {
			$this->load->view('login_view',array("base_url" => base_url(),"cdn_url" => $this->config->item("cdn_url")));
		} else if($_SESSION["check_topt"] !== true || isset($_SESSION['UserId'])){
			redirect($this->config->item("front_page"));	
		} else {
			redirect("login/two_step");
		}
	}

	/**
	 * This function is called each time a user is going to have the login screen shown
	 * @since 1.0
	 * @access public
	 */
	public function __construct(){
		parent::__construct();
		if(!isset($_SESSION["two_step_confirmed"]) || $_SESSION["two_step_confirmed"] !== "yes" || (isset($_SESSION["no_topt"]) && $_SESSION["no_topt"] !== true)){
			$_SESSION["check_topt"] = true;
		}
 	}

 	/**
 	 * This function loads the topt view if the user is going to have it shown
 	 * @since 1.0
 	 * @access public
 	 */
 	public function two_step(){
 		if(isset($_SESSION["UserId"]) && $_SESSION["check_topt"] === true){
 			$this->load->view("topt_view",array("base_url" => base_url()));
 		} else if(!isset($_SESSION["UserId"])){
 			redirect("login");
 		} else {
 			redirect($this->config->item("front_page"));
 		}
 	}

 	/**
 	 * This function checks if the posted TOPT key is correct
 	 * @since 1.0
 	 * @access public
 	 */
 	public function check_topt(){
 		if(isset($_SESSION["UserId"]) && $_SESSION["check_topt"] === true){
 			if($this->input->post("key",TRUE) && (isset($_SESSION["two_step_tries"]) && $_SESSION["two_step_tries"] < 3 || !isset($_SESSION["two_step_tries"]))){
 				$Key = $this->input->post("key",TRUE);
 				if($Key == self::_Get_TOPT()){
 					$_SESSION["check_topt"] = false;
 					$_SESSION["two_step_confirmed"] = "yes";
 					redirect($this->config->item("front_page"));
 				} else {
 					if(isset($_SESSION["two_step_tries"])){
 						$_SESSION["two_step_tries"] = (int)$_SESSION["two_step_tries"] + 1;
 						redirect("login/two_step");
 					} else {
 						$_SESSION["two_step_tries"] = 1;
 					}
 					redirect("two_step");
 				}
 			} else {
 				unset($_SESSION["UserId"]);
 				unset($_SESSION["two_step_tries"]);
 				unset($_SESSION["check_topt"]);
 				redirect("login");
 			}
 		} else if(!isset($_SESSION["UserId"])){
 			redirect("login");
 		} else {
 			redirect($this->config->item("front_page"));
 		}
 	}

 	/**
 	 * This function get the users current TOPT key
 	 * @since 1.0
 	 * @access private
 	 */
 	private function _Get_TOPT(){
 		$Query = $this->db->where(array("Id" => $_SESSION["UserId"]))->get($this->config->item("api_users_table"));
		if($Query->num_rows() > 0){
			$Row = current($Query->result());
			if(!is_null($Row->topt) && $Row->topt != ""){
				$this->load->library("onetimepassword");
				date_default_timezone_set("UTC");
				$Settings = array(
					'Algorithm' => $this->config->item("api_topt_algorithm"),
					'Digits' => $this->config->item("api_topt_digist"),
					'Key' => $Row->topt,
					'Timestamp' => time(),
					'InitialTime' => '0',
					'TimeStep' => $this->config->item("api_topt_timealive"),
					'TimeWindowSize' => '1'
				);
				$Topt = OneTimePassword::GetPassword($Settings,$TimeLeft);
				return $Topt;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
 	}


	/**
	 * This function minifies all output send from this controller
	 * @since 1.1
	 * @access public
	 */
	public function _output(){
		$content = $this->output->get_output();
		$mini = $this->minify->Html($content);
		$this->output->set_output($mini);
		echo $this->output->get_output();
	}
		
	/**
	 * This function is called when the ClickThis login screen is going to be shown
	 * @param  string $Page        If register or login
	 * @since 1.0
	 * @access public
	 */
	public function clickthis($Page = NULL){
		if(!is_null($Page)){
			switch($Page){
				case "register":{
					redirect("login/clickthis/#register");
				}
			}
		}
		else{
			$this->load->view('clickthis_login_view',array("base_url" => base_url(),"cdn_url" => $this->config->item("cdn_url")));	
		}
	}

	/**
	 * This function logs a user in or creates a new user using Github login
	 * @param  string $page The current operation "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function github($page = "auth"){
		$this->load->library("auth/github");
		$this->load->model("login_model");
		$Github = new Github();
		$Github->client();
		$Github->scope(array("user"));
		if($page == "auth"){
			if($Github->auth()){

			} else{
				redirect($this->config->item("login_page"));
			}
		} else if($page == "callback"){
			if($Github->callback()){
				$Account = $Github->user();
				if($Account !== false){
					$Picture = "https://secure.gravatar.com/avatar/".$Account->gravatar_id;
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Github")){
							$this->login_model->Update($_SESSION["UserId"],"Github",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						if($this->login_model->Github($Account->name,$Account->id,$Account->email,$Picture,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * The google login method auth means that the auth request should
	 * be peformed and callback is after auth
	 * @param  string $page Auth or callback
	 * @since 1.0
	 * @access public
	 */
	public function google($page = "auth"){
		$this->load->library("auth/google");
		$this->load->model("login_model");
		$Google = new Google();
		$Google->client();
		$Google->redirect_uri(base_url()."login/google/callback");
		$Google->scopes(array("userinfo.profile","userinfo.email"));
		$Google->access_type("offline");
		if($page == "auth"){
			$Google->auth();
		} else if($page == "callback"){
			$Google->callback();
			$Account = $Google->account_data();
			if($Account !== false){
				if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
					if(!$this->login_model->User_Exists($Account->id,"Google") && !$this->login_model->User_Exists($Account->email,"Google")){
						$this->login_model->Update($_SESSION["UserId"],"Google",$Account->id);
						if(isset($_SESSION["auth_link_redirect"])){
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							redirect($this->config->item("front_page"));
						}
					} else {
						if(isset($_SESSION["auth_link_redirect"])){
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							$_SESSION["auth_error"] = "User exists";
 							redirect($this->config->item("front_page"));
						}
					}
				} else {
					if($this->login_model->Google($Account->name,$Account->email,$Account->picture,$Account->id,$Account->locale,$UserId)){
						if(!is_null($UserId)){
							$_SESSION["UserId"] = $UserId;
							redirect($this->config->item("front_page"));
						} else {
							redirect($this->config->item("login_page"));
						}
					} else {
						redirect($this->config->item("login_page"));
					}
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * This function logs a user in using Facebook
	 * @param  string $page The current page "auth" or "callback"
	 * @access public
	 * @since 1.0
	 */
	public function facebook($page = "auth"){
		$this->load->library("auth/facebook");
		$this->load->model("login_model");
		$Facebook = new Facebook();
		$Facebook->client();
		$Facebook->redirect_uri = base_url()."login/facebook/callback";
		if($page == "auth"){
			$Facebook->auth();
		} else {
			$this->load->library('country');
			if($Facebook->callback()){
				$Account = $Facebook->user();
				if($Account !== false){
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Facebook")){
							$this->login_model->Update($_SESSION["UserId"],"Facebook",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								$_SESSION["auth_error"] = "User exists";
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						$LocaleArray = explode("_",$Account->locale);
						$Language = $LocaleArray[0];
						$CountryObject = new Country();
						$CountryObject->Find(array("Code" => strtoupper($LocaleArray[1])));
						if(property_exists($Account, "email")){
							$Email = $Account->email;
						} else {
							$Email = NULL;
						}
						if($this->login_model->Facebook($Account->id,$Account->name,$Email,$CountryObject->name,$Language,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							}	
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}
	
	/**
	 * This function either creates a user with twitter
	 * link a twitter account to the current user 
	 * or logs the user in with the twitter account
	 * @param  string $page The current page "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function twitter($page = "auth"){
		$this->load->library("auth/twitter");
		$this->load->model("login_model");
		$Twitter = new Twitter();
		$Twitter->consumer();
		$Twitter->callback = base_url()."login/twitter/callback";
		if($page != "callback"){
			if($Twitter->auth() === false){
				header("Location: ".base_url().$this->config->item("login_page")."/twitter/auth");
			}
		} else if($page == "callback"){
			if($Twitter->callback()){
				$Account = $Twitter->user();
				if($Account !== false){
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"Twitter")){
							$this->login_model->Update($_SESSION["UserId"],"Twitter",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								$_SESSION["auth_error"] = "User exists";
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						$this->load->library('country');
						$CountryObject = new Country();
						$CountryObject->Find(array("Code" => strtoupper($Account->lang)));
						if($this->login_model->Twitter($Account->name,$Account->id,$Account->profile_image_url,$Account->lang,$CountryObject->name,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * This function authenticate a user with LinkedIn
	 * and either logs the user ind based on that information
	 * or creates an account based on that
	 * @param  string $page The current operation "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function linkedin($page = "auth"){
		$this->load->library("auth/linkedin");
		$this->load->model("login_model");
		$LinkedIn = new LinkedIn();
		$LinkedIn->consumer();
		if($page !== "callback"){
			if($LinkedIn->login() === false){
				header("Location: ".base_url().$this->config->item("login_page")."/linkedin/auth");
			}
		} else {
			if($LinkedIn->callback()){
				$Account = $LinkedIn->user(array("id","first-name","last-name","picture-url","location:(country:(code))","languages"));
				if($Account !== false){
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){
						if(!$this->login_model->User_Exists($Account->id,"LinkedIn")){
							$this->login_model->Update($_SESSION["UserId"],"LinkedIn",$Account->id);
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								$_SESSION["auth_error"] = "User exists";
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {
						$this->load->library('country');
						$CountryObject = new Country();
						$CountryObject->Find(array("Code" => strtoupper($Account->location->country->code)));
						$Name = $Account->{'first-name'}." ".$Account->{'last-name'};
						if($this->login_model->LinkedIn($Name,$Account->id,$Account->{'picture-url'},$CountryObject->name,$UserId)){
							if(!is_null($UserId)){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * This function is called when the user clicks on the reset password button
	 * @since 1.1
	 * @access public
	 */
	public function ResetPassword(){
		$this->load->config("api");
		$this->load->view("reset_password_view",array("base_url" => base_url(),"api_url" => $this->config->item("api_host_url")));
	}

	/**
	 * This function lets the user login with Instagram
	 * @param string $page The current operation "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function Instagram($page = "auth"){
		$this->load->library("auth/instagram");
		$this->load->model("login_model");
		$Instagram = new Instagram();
		$Instagram->client();
		$Instagram->redirect_uri = base_url()."login/instagram/callback";
		$Instagram->scope(array("basic"));
		if($page !== "callback"){
			if(!$Instagram->auth()){
				redirect($this->config->item("login_page"));
			}
		} else {
			if($Instagram->callback()){
				$Account = $Instagram->user;

				//User is logged in link the instagram account
				if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){

					//No user with that instatgram id is existing
					if(!$this->login_model->User_Exists($Account->id,"Instagram")){

						//Update the user with the Instagram Id
						$this->login_model->Update($_SESSION["UserId"],"Instagram",$Account->id);

						//Redirect the user to either the front page or to a specified callback url
						if(isset($_SESSION["auth_link_redirect"])){
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							redirect($this->config->item("front_page"));
						}

					//Redirect the user, to either the front page or to a specifed callback
					} else {
						if(isset($_SESSION["auth_link_redirect"])){
							$_SESSION["auth_error"] = "User exists";
							redirect($_SESSION["auth_link_redirect"]);
						} else {
							$_SESSION["auth_error"] = "User exists";
 							redirect($this->config->item("front_page"));
						}
					}
				} else {

					//Get the user of the linked user or of the newly created user
					if($this->login_model->Instagram($Account->{'full_name'},$Account->id,$Account->{'profile_picture'},$UserId)){

						//The user id was set, set the $_SESSION["UserId"] to that user id, so the user now is logged in
						if(!is_null($UserId)){
							$_SESSION["UserId"] = $UserId;
							redirect($this->config->item("front_page"));

						//No user was found or an error occured, redirect the user to the login page
						} else {
							redirect($this->config->item("login_page"));
						}
					} else {
						redirect($this->config->item("login_page"));
					}
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}

	/**
	 * This function let the users login and link their account with Foursquare
	 * @param string $page The current operation "auth" or "callback"
	 * @since 1.0
	 * @access public
	 */
	public function Foursquare($page = "auth"){
		$this->load->library("auth/foursquare");
		$this->load->model("login_model");
		$Foursquare = new Foursquare();
		$Foursquare->client();
		$Foursquare->redirect_uri = base_url()."login/foursquare/callback";
		$Foursquare->web_request = false;
		if($page !== "callback"){
			if(!$Foursquare->auth()){
				redirect($this->config->item("login_page"));
			}
		} else {
			if($Foursquare->callback()){
				$Account = $Foursquare->user();
				if($Account !== false){
					$Email = $Account->contact->email;
					$Name = $Account->firstName." ".$Account->lastName;
					$Id = $Account->id;
					$Picture = $Account->photo;

					//If the user is logged in and exists link the accounts else create a new account
					if(isset($_SESSION["UserId"]) && $this->login_model->User_Exists($_SESSION["UserId"],"Id")){

						//if no user with that foursquare id is existing link the accounts else return error
						if(!$this->login_model->User_Exists($Id,"Foursquare")){

							//Link the users account with foursquare
							$this->login_model->Update($_SESSION["UserId"],"Foursquare",$Id);

							//Redirect the user to either the front page or to a specified callback url
							if(isset($_SESSION["auth_link_redirect"])){
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								redirect($this->config->item("front_page"));
							}
						} else {
							if(isset($_SESSION["auth_link_redirect"])){
								$_SESSION["auth_error"] = "User exists";
								redirect($_SESSION["auth_link_redirect"]);
							} else {
								$_SESSION["auth_error"] = "User exists";
	 							redirect($this->config->item("front_page"));
							}
						}
					} else {

						//If the creation of the account was success log the user in else redirect to the login page
						if($this->login_model->Foursquare($Name,$Id,$Picture,$Email,$UserId)){
							//if the user id is set, set the $_SESSION["UserId"] to that user id, so the user now is logged in else redirec to login page
							if(!is_null($UserId) && $UserId != ""){
								$_SESSION["UserId"] = $UserId;
								redirect($this->config->item("front_page"));

							//No user was found or an error occured, redirect the user to the login page
							} else {
								redirect($this->config->item("login_page"));
							}
						} else {
							redirect($this->config->item("login_page"));
						}
					}
				} else {
					redirect($this->config->item("login_page"));
				}
			} else {
				redirect($this->config->item("login_page"));
			}
		}
	}
}
?>