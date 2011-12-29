<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ClickThis - Login</title>
	<link rel='stylesheet' href='http://illution.dk/ClickThis/assets/css/clickthislogin.css'>
    <link rel='stylesheet' href='http://illution.dk/ClickThis/assets/css/colorbox.css'>
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
			<form action="http://illution.dk/ClickThis/login/clickthis/login" method="POST" class="cleanForm" id="normal">
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
                    <img id="dot" src="http://illution.dk/ClickThis/assets/images/ClickThisLogin/RedDot.png" alt="Dot">
            		<img id="lock" src="http://illution.dk/ClickThis/assets/images/ClickThisLogin/Locked.png" alt="Lock">
					<div class="formExtra">
						<p><strong>Trouble signing in?</strong></p>
						<p><a href="#recover">Recover your password</a> or <a href="#register" id="Create">Create an account</a></p>
					</div>

				</fieldset>
			
			</form>
		
		</div> <!-- end signIn -->

		<!-- Sign Up Tab Content -->
		<div id="signUp" class="clearfix toggleTab">
		
			<form action="#" method="POST" class="cleanForm" id="signUpForm" id="normalreg">
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
						<label for="email">Email Address: <span class="requiredField">*</span></label>
						<input type="email" id="email" name="email" value="" required />
						<em>Must be a valid email address. E.g. adi@envato.com</em>
					</p>

					<p>
						<label for="phone">Phone Number:</label>
						<input type="tel" id="phone" name="phone" value="" pattern="^[0-9-]{10,}$" />
						<em>10 or more characters, numbers and dashes only. E.g. 1-800-1111</em>
					</p>
					
					<div class="distanceLeft">
						<input type="checkbox" id="terms" name="terms" />
						<label for="terms"><a href="#" class="tos">I have read and agree to the</a><a href="#"> Terms of Service</a>.</label>
					</div>
				
					<input type="submit" value="Register" />
                     <img id="dotreg" src="http://illution.dk/ClickThis/assets/images/ClickThisLogin/RedDot.png" alt="Dot">
            		<img id="lockreg" src="http://illution.dk/ClickThis/assets/images/ClickThisLogin/Locked.png" alt="Lock">

					<div class="formExtra">
						<p><strong>Note: </strong> Fields marked with <span class="requiredField">*</span> are required.</p>
					</div>

				</fieldset>
			
			</form>
			
			<!-- Sidebar -->
			<div id="sidebar">
				<h3>Benefits for signing up</h3>
				
				<ul>
					<li>24/7 support from our team</li>
					<li>Another great benefit</li>
					<li>We're in the cloud, so accessing your data will be 10x faster</li>
					<li>We use the latest technology on the market today</li>
				</ul>
			</div> <!-- end sidebar -->
		
		</div> <!-- end signUp -->
	
	</div> <!-- end pageContainer -->
    <!-- Terms of Service -->
    <div style='display:none'>
	<div id="termsofuse" style="overflow:auto; height:600px;">
    
    </div>
    </div>
	<!-- Include the JS files -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
	<script src="http://illution.dk/ClickThis/assets/js/h5f.js"></script>
	<script src="http://illution.dk/ClickThis/assets/js/clickthislogin.js"></script>
    <script type="text/javascript" src="http://illution.dk/ClickThis/assets/js/jquery.jcryption.js" ></script>
     <script type="text/javascript" src="http://illution.dk/ClickThis/assets/js/jquery.ba-hashchange.min.js" ></script>
    <script type="text/javascript" src="http://illution.dk/ClickThis/assets/js/MD5.js"></script>
    <script type="text/javascript" src="http://illution.dk/ClickThis/assets/js/jquery.colorbox-min.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
		$(".tos").colorbox({width:"50%", inline:true, href:"#termsofuse"});
	});
    $(function() {
        $("#normal").jCryption({
            beforeEncryption:function() {
                document.getElementById("lock").src = "http://illution.dk/ClickThis/assets/images/ClickThisLogin/LoadingSmall.gif";
                var D="";D+=screen.height;D+=screen.width;D+=navigator.userAgent;D+=navigator.cookieEnabled;D+=window.screen.colorDepth;D+=navigator.language;D=MD5(D);
                document.getElementById('h').value = D;
                return true;
            }
        });
		$("#normalreg").jCryption({
            beforeEncryption:function() {
                document.getElementById("lockreg").src = "http://illution.dk/ClickThis/assets/images/ClickThisLogin/LoadingSmall.gif";
                var D="";D+=screen.height;D+=screen.width;D+=navigator.userAgent;D+=navigator.cookieEnabled;D+=window.screen.colorDepth;D+=navigator.language;D=MD5(D);
                document.getElementById('hreg').value = D;
                return true;
            }
        });
    });
    function dot()
    {
        Dot = document.getElementById("dot");
		Dotreg = document.getElementById("dotreg");
        Dot.heigth=32; Dot.width=32;
		Dotreg.heigth=32; Dotreg.width=32;
        if(navigator.cookieEnabled == true)
        {
            Dot.src = 'http://illution.dk/ClickThis/assets/images/ClickThisLogin/GreenDot.png';
            Dot.title = 'Cookies are activated!';
            Dot.alt = 'Cookies are activated!';
			Dotreg.src = 'http://illution.dk/ClickThis/assets/images/ClickThisLogin/GreenDot.png';
            Dotreg.title = 'Cookies are activated!';
            Dotreg.alt = 'Cookies are activated!';
        }
        else
        {
            Dot.src = 'http://illution.dk/ClickThis/assets/images/ClickThisLogin/RedDot.png';
            Dot.title = 'Cookies are deactivated!';
            Dot.alt = 'Cookies are deactivated!';
			Dotreg.src = 'http://illution.dk/ClickThis/assets/images/ClickThisLogin/RedDot.png';
            Dotreg.title = 'Cookies are deactivated!';
            Dotreg.alt = 'Cookies are deactivated!';
        };
    };
    dot();
    </script>
</body>
</html>