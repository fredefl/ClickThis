<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="stylesheet" type="text/css" href="../css/jqtouch.css"/>
<link rel="stylesheet" type="text/css" href="../css/jqt/theme.css"/>
<link rel="stylesheet" type="text/css" href="../css/style.css"/>
<link rel="apple-touch-icon" href="../images/ClickThis64.png" />
<title>ClickThis Prototype - Flow Diagram</title>
</head>

<body>
	<input type="hidden" id="CurrentPage" value="home" />
	<div id="jqt">
        <div id="home" class="current">
            <div class="toolbar">
                <h1>ClickThis</h1>
                <a class="button slideup" id="infoButton" href="#about">About</a>
            </div>
            <div id="Content">
  
            </div>
            
            <!-- Welcome screen of sports survey -->
            <div class="Disabled" id="welcome_sport">
                 <ul class="rounded" >
                 	<!--<h1 id="Header">Hello</h1>-->
                    <ul class="arrow">
                        <li class="forward"><a href="../multiplechoice.html">Begin the survey</a></li>
                    </ul>
                 </ul>
                 <ul class="rounded" >
                 	<li>&copy; Illution, Survey By: Bo Thomsen</li>
                 </ul>   
            </div>
            
            <!-- Welcome screen of Vehicle survey -->
            <div class="Disabled" id="welcome_cars">
                 <ul class="rounded" >
                 	<!--<h1 id="Header">Hello</h1>-->
                    <ul class="arrow">
                        <li class="forward"><a href="../singlechoice.html#question_1">Begin the survey</a></li>
                    </ul>
                 </ul>
                 <ul class="rounded" >
                 	<li>&copy; Illution, Survey By: Car Magazine</li>
                 </ul>   
            </div>
            
            <!-- The Thanks page for cars -->
            <div class="Disabled" id="thanks_cars">
                 <ul class="rounded" >
                 	<!--<h1 id="Header">Hello</h1>-->
                    <ul class="arrow">
                        <li class="forward"><a href="#user">End the survey</a></li>
                    </ul>
                 </ul>
                 <ul class="rounded" >
                 	<li>&copy; Illution, Survey By: Car Magazine</li>
                 </ul>   
            </div>
            
            <!-- The users unanswered surveys -->
            <div id="user" class="Disabled">
                <ul class="rounded" >
                    <ul class="arrow">
                        <li class="forward"><a href="#welcome_sport">Which sport(s) do you play, and why?</a><small class="counter"><a href="user.php?user_id=3">Bo Thomsen</a></small></li>
                        <li class="forward"><a href="#welcome_cars">What is the best car?</a><small class="counter"><a href="user.php?user_id=32">Car Magazine</a></small></li>
                    </ul>
                </ul>
            </div>  
        </div>
        
        <div>
        
        </div>
    </div>
    
	<script src="../js/prefixfree.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script src="http://cdn.illution.dk/JS/jquery.hashchange.min.js"></script>
    <script src="../js/script.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>