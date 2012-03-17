<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" href="css/jqtouch.css"/>
<link rel="stylesheet" href="css/theme.css"/>
<link rel="stylesheet" href="css/style.css"/>
<link rel="apple-touch-icon" href="images/ClickThis64.png" />
<meta charset="utf-8">
<title></title>
</head>
<body>
	<div id="jqt">
        <div id="home" class="current">
            <div class="toolbar">
                <h1><a href="http://illution.dk/ClickThisPrototype/home.html" title="Home" style="text-decoration:none; color:#FFF;">ClickThis</a></h1>
                <a class="button slideup" onClick="showAboutBox();" id="infoButton">About</a>
                <a class="back" id="backButton" data-href="home.html">Back</a>
            </div>
            <div id="notfound" class="Disabled">
                <ul class="rounded" >
                        <ul class="arrow">
            				<li><h1 style="color:#FFFFFF;" id="NotFoundText">User not found</h1></li>
                        </ul>
                </ul>            
            </div>
            <div id="user" class="Disabled">
                <div id="Avatar">
                    <a title="" id="linktag"><img  src="" height="256" width="256" id="profile_image" title="" alt="" /></a>
                </div>
                <div id="Profile">
                    <label for="Name">Name</label><br>
                    <input type="text" id="Name" value="" disabled /><br>
                    <label for="Email">Email</label><br>
                    <input type="text" id="Email" value=""  disabled/><br>
                    <label for="Email">Location</label><br>
                    <select id="Location" disabled>
                        <optgroup title="Europe" label="Europe">Europe</optgroup>
                            <option selected value="Denmark">Denmark</option>
                            <option value="England">England</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                        <optgroup title="Asia" label="Asia">Asia</optgroup>
                            <option value="China">China</option>
                    </select>
                </div>    
            </div>
            
            <!-- About Box -->
            <div id="aboutBox" class="Disabled">
            </div>
    	</div>    
    </div>    
	<script src="js/prefixfree.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script src="http://cdn.illution.dk/JS/jquery.hashchange.min.js"></script>
    <script src="js/clickthis.js"></script><script src="js/script.js"></script><!--<script src="http://debug.phonegap.com/target/target-script-min.js#illutionclickthisprototypetest"></script>-->
    <script type="text/javascript" src="js/user.js"></script>
</body>
</html>