<?php
$heading = str_replace("<p>","",str_replace("</p>","",$heading));
$message = str_replace("<p>","",str_replace("</p>","",$message));
?>
<html>
	<head>
		<style type="text/css">
			.icon{

			}
			.container{
				margin-left:auto;
				margin-right:auto;
				width:400px;
				height:50px;
			}
			body{
				width:99%;
				height:99%;
				overflow: hidden;
				background-color: #DDD;
			}
			.heading{
				font: 200% "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;
			}
			.message{
				font: 90% "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<p class="heading"><?php echo $heading; ?></p>
			<p class="message"><?php echo $message; ?></p>
		</div>
	</body>
</html>
<?php
die();
?>