<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ClickThis - Login</title>
	<link rel='stylesheet' href='<?php echo $cdn_url; ?>css/clickthislogin.css'>
    <link rel='stylesheet' href='<?php echo $cdn_url; ?>css/colorbox.css'>
</head>
<body>
	<div id="pageContainer">
		<!-- Tabs -->
		<ul id="tabs" class="clearfix">
			<li class="activeTab" id="signInTab">
				<div class="signInTabContent">
					<span>Already a member?</span>
					<h1>Sign in below</h1>
				</div>
				<span class="activeTabArrow"><!-- --></span>
			</li>
			<li class="inactiveTab" id="signUpTab">
				<div class="signUpTabContent">
					<span>Don't have an account?</span>
					<h1>Create one now</h1>
				</div>

				<span class="activeTabArrow"><!-- --></span>
			</li>
		</ul>
		<!-- Sign In Tab Content -->
		<div id="signIn" class="toggleTab">
			<form action="<?php echo $base_url; ?>verify/login" method="POST" class="cleanForm" id="normal">
            <input type="hidden" name="h" id="h"/>
			
				<fieldset>
					<p>
						<label for="login-username">Your Username:</label>
						<input type="text" id="login-username" name="login-username" value="" autofocus required />
					</p>
					
					<p>
						<label for="login-password">Your Password:</label>
						<input type="password" id="login-password" name="login-password" value="" required />
					</p>
					
					<div class="distanceLeft">
						<input type="checkbox" id="remember-me" name="remember-me" />
						<label for="remember-me">Remember me on this computer</label>
					</div>
					<input type="submit" value="Sign In" />
					<div class="formExtra">
						<p><strong>Trouble signing in?</strong></p>
						<p><a href="resetpassword">Recover your password</a> or <a href="#register" id="Create">Create an account</a></p>
					</div>

				</fieldset>
			
			</form>
		
		</div> <!-- end signIn -->

		<!-- Sign Up Tab Content -->
		<div id="signUp" class="clearfix toggleTab">
		
			<form action="<?php echo $base_url; ?>verify/register" method="POST" class="cleanForm" id="signUpForm" id="normalreg">
            <input type="hidden" name="hreg" id="hreg"/>
			
				<fieldset>
				
					<p>
						<label for="full-name">Full Name: <span class="requiredField">*</span></label>
						<input type="text" id="full-name" name="full-name" value="" autofocus required />
						<em>Enter your full name.</em>
					</p>

					<p>
						<label for="username">Username: <span class="requiredField">*</span></label>
						<input type="text" id="username" name="username" pattern="^[a-z0-9_-]{3,20}$" value="" required />
						<em>Between 3 and 20 characters, letters or numbers.</em>
					</p>
					
					<p>
						<label for="password">Password: <span class="requiredField">*</span></label>
						<input type="password" id="password" name="password" value="" pattern="^[a-zA-Z0-9]{6,12}$" required />
						<em>Between 6 and 12 characters, alphanumeric only.</em>
					</p>

					<p>
						<label for="repassword">Retype Password: <span class="requiredField">*</span></label>
						<input type="password" id="repassword" name="repassword" value="" pattern="^[a-zA-Z0-9]{6,12}$" required />
						<em>Between 6 and 12 characters, alphanumeric only.</em>
					</p>

					<p>
						<label for="email">Email Address: <span class="requiredField">*</span></label>
						<input type="email" id="email" name="email" value="" required />
						<em>Must be a valid email address. E.g. example@example.com</em>
					</p>
					
					<div class="distanceLeft">
						<input type="checkbox" id="terms" name="terms" />
						<label for="terms"><a href="#" class="tos">I have read and agree to the</a><a href="#" class="tos"> Terms of Service</a>.</label>
					</div>
				
					<input type="submit" value="Register" />

					<div class="formExtra">
						<p><strong>Note: </strong> Fields marked with <span class="requiredField">*</span> are required.</p>
					</div>

				</fieldset>
			
			</form>
		
		</div> <!-- end signUp -->
	
	</div> <!-- end pageContainer -->
    <!-- Terms of Service -->
    <div style='display:none'>
	<div id="termsofuse" style="overflow:auto; height:600px;">
    
    </div>
    </div>
	<!-- Include the JS files -->
	<script src="<?php echo $this->config->item("jquery_url"); ?>"></script>
	<script src="<?php echo $cdn_url; ?>js/h5f.js"></script>
	<script src="<?php echo $cdn_url; ?>js/clickthislogin.js"></script>
    <!--<script type="text/javascript" src="<?php echo $cdn_url; ?>js/jquery.jcryption.js" ></script>-->
     <script type="text/javascript" src="<?php echo $cdn_url; ?>js/jquery.ba-hashchange.min.js" ></script>
    <script type="text/javascript" src="<?php echo $cdn_url; ?>js/MD5.js"></script>
    <script type="text/javascript" src="<?php echo $cdn_url; ?>js/jquery.colorbox-min.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
		$(".tos").colorbox({width:"50%", inline:true, href:"#termsofuse"});
	});
    </script>
</body>
</html>