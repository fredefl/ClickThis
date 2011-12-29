<?php
// Include Login
$this->load->library('loginclass');
// jCryption
if(isset($_GET["generateKeypair"])) 
	Login::SendKeypair();
else
	$result = Login::Decrypt();
// Start Minifier
ob_start("MinifyPage");
?>
<?php
// Get password
$passwordpost = $result["password"];
// Get username
$usernamepost = $result["username"];
// If both username and password exists
if ($usernamepost&&$passwordpost)
{
	$Result = Login::Check($usernamepost,$passwordpost);
	if($Result == 1)
	{
		session_regenerate_id();
		// We are in!
		$_SESSION['username']=$usernamepost;
		// Initialize returnurl
		$returnurl = "";
		// Add http:// if not existing
		if (strpos(" ".$result['r'] , "http://"))
			$returnurl = $result['r'];
		else 
			$returnurl = "http://".$result['r'];
		// It no url is specified, return to homepage
		if(!$result['r']){$returnurl='http://illution.dk';};
		?>
		<?php
		// ######### Tokens ################################
		// Add Hash
		$_SESSION['Hash'] = $result['h'];
		// GA - Undercover token
		$token = rand(10000000,99999999);
		$_SESSION['gatoken'] = $token;
		setcookie("__utmd",$token,0,"/",".illution.dk",false,false);
		// ######### End of Tokens ############################
	} 
	else if($Result == 0)
		$echoform = 1;
	else if($Result == 2)
		die("<span style='color:#FFFFFF;' >SQL Injection...</span>");
} else $echoform = 1;

if($echoform == 1)
{
	if(isset($_REQUEST['r']))
		$R = $_REQUEST['r'];
	else 
		$R=$result['r'];
	ob_start();
	?>
    <div style="font: 18px Calibri;color:#000000">
    <form id="normal" action='Login.php' method='POST'>
    <input type= 'hidden' name='r' value='<?php echo $R; ?>'>	  
    <input type="hidden" name='h' value='' id='h'>
    <table id="LoginTable"> 
    <tr>
    <td><span style='color:white'>Username:  </span></td><td><input type= 'text' id='username' name='username' style='height: 20px; width: 180px'></td>
    </tr>
    <tr>
    <td><span style='color:white'>Password:  </span></td><td><input type='password' name='password' id='password' style='height: 20px; width: 180px'></td>
    </tr>
    <tr>
    <td>
    <img id="dot" src="../images/.General/GreenDot.png" alt="Dot" height="32" width="32" style="margin-right:5px;">
    <img id="lock" src="../images/.General/Locked.png" alt="Lock" height="32" width="32" style="margin-left:5px;">
    </td><td><input title="Submit" name="submitButton" id="submitButton" type="submit" class="submit" value='Log in' style='height:32px;width:185px'/></td>
    </tr>
    </table>
    </form>
    </div>
    <?php
    ob_get_flush();
};
$this->load->library('getasset');
echo $this->getasset->GetLatestJQuery();
echo $this->getasset->GetCSS('login.css');
echo $this->getasset->GetJS('login.js');
echo $this->getasset->GetJS('jquery.jcryption.min.js');
echo $this->getasset->GetJS('MD5.js');
?>
<?php
// Minify Page
ob_end_flush();

?>