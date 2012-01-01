<?php
$names = array('32' => 'Car Magazine','3' => 'Bo Thomsen');
if(isset($_GET['user_id'])){
	$user = $_GET['user_id'];
	$userid = 'user_'.$user;
	if(isset($names[$user])){
		$name = $names[$user];
	}
	else{
		$name = $user;	
	}
}
else{
	$name = 'User not found';
	$userid = 'notfound';	
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="css/jqtouch.css"/>
<link rel="stylesheet" type="text/css" href="css/jqt/theme.css"/>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="apple-touch-icon" href="images/ClickThis64.png" />
<meta charset="utf-8">
<title><?php echo $name; ?></title>
</head>
<body>
	<input type="hidden" id="user" value="<?php echo $userid; ?>" />
	<div id="jqt">
        <div id="home" class="current">
            <div class="toolbar">
                <h1><a href="http://illution.dk/ClickThisPrototype/home.html" title="Home" style="text-decoration:none; color:#FFF;">ClickThis</a></h1>
                <a class="button slideup" id="infoButton" href="#about">About</a>
            </div>
            <div id="notfound" class="Disabled">
                <ul class="rounded" >
                        <ul class="arrow">
            				<li><h1 style="color:#FFFFFF;">User not found</h1></li>
                        </ul>
                </ul>            
            </div>
            <div id="user_3" class="Disabled">
                <div id="Avatar">
                    <a title="Bo Thomsen" href="#"><img src="http://www.gravatar.com/avatar/dc07576afa6b5b172a378d6f5eb05f5f?s=256" title="Bo Thomsen" alt="Bo Thomsen" /></a>
                </div>
                <div id="Profile">
                    <label for="Name">Name</label><br>
                    <input type="text" id="Name" value="Bo Thomsen" disabled /><br>
                    <label for="Email">Email</label><br>
                    <input type="text" id="Email" value="bo@illution.dk"  disabled/><br>
                    <label for="Email">Location</label><br>
                    <select id="Location" disabled>
                        <optgroup title="Europe" label="Europe">Europe</optgroup>
                            <option selected value="dk">Denmark</option>
                            <option value="en">England</option>
                            <option value="de">Germany</option>
                            <option value="fr">France</option>
                        <optgroup title="Asia" label="Asia">Asia</optgroup>
                            <option value="cn">China</option>
                    </select>
                </div>    
            </div>
            <div id="user_32" class="Disabled">
                <div id="Avatar">
                    <a title="Car Magazine" href="#2"><img src="images/Grey Bugatti Veyron.png" title="Car Magazine" alt="Car Magazine" /></a>
                </div>
                <div id="Profile">
                    <label for="Name">Name</label><br>
                    <input type="text" id="Name" value="Car Magazine"  disabled/><br>
                    <label for="Email">Email</label><br>
                    <input type="text" id="Email" value="info@carmagazine.co.uk" disabled/><br>
                    <label for="Email">Location</label><br>
                    <select id="Location" disabled>
                        <optgroup title="Europe" label="Europe">Europe</optgroup>
                            <option value="dk">Denmark</option>
                            <option selected value="en">England</option>
                            <option value="de">Germany</option>
                            <option value="fr">France</option>
                        <optgroup title="Asia" label="Asia">Asia</optgroup>
                            <option value="cn">China</option>
                    </select>
                </div>
            </div>
    	</div>    
    </div>    
	<script src="js/prefixfree.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://cdn.illution.dk/JS/jquery.hashchange.min.js"></script>
    <script src="js/script.js"></script>
    <script type="text/javascript" src="js/user.js"></script>
</body>
</html>