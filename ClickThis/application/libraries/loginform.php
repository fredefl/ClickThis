<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Loginform
{
	//Variables
	private $CI = ''; //An instance of Code Igniter
	private $Errors = array(); //Error Array
	private $Id = 0; //The Users Id
	
	public function UserId(){
		if($this->Id != 0){
			return $this->Id;	
		}
	}
	
	public function Loginform(){
		$this->CI =& get_instance(); // Create an instance of CI
	}
	
	//Get Erros
	public function Error(){
		return $this->Errors;	
	}
	
	//Security
	public function Security($In){
		$Errors = array();
		$Out = '';
		$Out = $In;
		$Temp = $Out;
		
		//SQL Injection
		$Out =  mysql_real_escape_string($In);
		if($Out != $Temp){
			$this->Errors[] = 'SQL Injection';
		}
		//HTMl Entities
		$Temp = $Out;
		$Out = htmlentities($Out);
		if($Temp != $Out){
			$this->Errors[] = 'HTML Chars Detected';
		}
		//HTMl Specialchars
		$Temp = $Out;
		$Out = htmlspecialchars($Out);
		if($Out != $Temp){
			$this->Errors[] = 'HTML Special Chars Detected';
		}
		//HTMl Strip Tags
		$Temp = $Out;
		$Out = strip_tags($Out);
		if($Out != $Temp){
			$this->Errors[] = 'Programming Detected';
		}
		return $Out;
	}
	
	// Get Keypair
	/*
	Returns Array
	*/
	public function GetKeypair ()
	{	
		$Query = $this->CI->db->query("SELECT * FROM rsa1024 ORDER BY RAND() LIMIT 1");
		return $Query->result_array();
	}
	
	// Send Keypair
	/*
	Returns true :D
	*/
	public function SendKeypair ()
	{
		self::IncludejCryption();
		$keyLength = 1024;
		$jCryption = new jCryption();	
		$keys = self::GetKeypair();
		$keys = $keys[0];
		$_SESSION["e"] = array("int" => $keys["e"], "hex" => $jCryption->dec2string($keys["e"],16));
		$_SESSION["d"] = array("int" => $keys["d"], "hex" => $jCryption->dec2string($keys["d"],16));
		$_SESSION["n"] = array("int" => $keys["n"], "hex" => $jCryption->dec2string($keys["n"],16));
		die( '{"e":"'.$_SESSION["e"]["hex"].'","n":"'.$_SESSION["n"]["hex"].'","maxdigits":"'.intval($keyLength*2/16+3).'"}');
		return true;
	}
	
	// Check Credidentals
	/*
	Return Types:
	0 - Username/Password incorrect
	1 - Username/Password corrent, go ahead and login
	2 - SQL Injection detected, abort!!!
	*/
	public function Check ($UsernameIn, $PasswordIn)
	{
		$this->CI->load->model('Login_Clickthis');
		/* -------- Expirimental ----------- */
		$Username = self::Security($UsernameIn);
		$Password = self::Security($PasswordIn);
		/* --------------------------------- */
		if(!count($this->Errors) > 0){
			$Query = $this->CI->Login_Clickthis->Username($Username);
			$numrows = $this->CI->Login_Clickthis->GetNumrows();
			if($Query == 'Error'){
				$this->Errors[] = 'User not found';
			}
			// If there is rows
			if ($numrows != 0)
			{
				// code to login
				if(isset($Query))
				{
					$dbusername = $Query['Username'];	
					$dbpassword = $Query['Password'];	
				}
				if ($Username == $dbusername&&hash_hmac("sha512", $Password, "fqqC7bsU5zt5cGHzvtGN")==$dbpassword)
				{
					$this->Id = $Query['Id'];
				}
				else{
					$this->Errors[] = 'Operation Failed Username/Password';	
				}
			}
			else{
				$this->Errors[] = 'User not found';	
			}
		}
		// Return Result
		return $this->Errors;
	}
	
	// Decrypt
	public function Decrypt()
	{
		$keyLength = 1024;
		//include('jcryption.php');
		//$jCryption = new jCryption();
		$this->CI->load->library("jcryption");
		$var = $this->CI->jcryption->decrypt($_POST['jCryption'], $_SESSION["d"]["int"], $_SESSION["n"]["int"]);
		unset($_SESSION["e"]);
		unset($_SESSION["d"]);
		unset($_SESSION["n"]);
		parse_str($var,$result);	
		return $result;
	}
	
	// Include jCryption
	public function IncludejCryption()
	{
		require_once('jcryption.php');
	}
}
?>