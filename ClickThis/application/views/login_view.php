<?php if(isset($_SESSION['username'])) {
	redirect("home");
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>ClickThis - Login</title>
<style type="text/css">
.box {
	width:430px;
	height:300px;
	position:absolute;
	left:50%;
	top:50%;
	margin:-140px 0 0 -215px;
	
	-webkit-border-radius:10px;
	-moz-border-radius:10px;
	-o-border-radius:10px;
	border-radius:10px;
	
	-webkit-user-select:none;
	
	background: #bababa; /* Old browsers */
	background: -moz-linear-gradient(top, #bababa 1%, #6d6d6d 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#bababa), color-stop(100%,#6d6d6d)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #bababa 1%,#6d6d6d 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #bababa 1%,#6d6d6d 100%); /* Opera11.10+ */
	background: -ms-linear-gradient(top, #bababa 1%,#6d6d6d 100%); /* IE10+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bababa', endColorstr='#6d6d6d',GradientType=0 ); /* IE6-9 */
	background: linear-gradient(top, #bababa 1%,#6d6d6d 100%); /* W3C */
}
body {
	background: url(http://illution.dk/include/Errors/images/Back.png) repeat; 
	width:100%; 
	height:100%;
}
</style>
</head>
<body>
<div class="box">
<table cellspacing="10">
<tr>
<td><a href="http://illution.dk/GoogleLogin"><img src="http://illution.dk/ClickThis/assets/images/LoginIcons/Google.png"></a></td>
<td><a href="<?php echo base_url();?>login/clickthis"><img src="http://illution.dk/ClickThis/assets/images/LoginIcons/ClickThis.png"></a></td>
<td></td>
</tr>
<tr>
<td><a href="<?php echo base_url();?>login/facebook/auth"><img src="http://illution.dk/ClickThis/assets/images/LoginIcons/Facebook.png"></a></td>
<td><a href="<?php echo base_url();?>login/twitter/auth"><img src="http://illution.dk/ClickThis/assets/images/LoginIcons/Twitter.png"></a></td>

<td><a href="http://illution.dk/ClickThis/login/linkedin">
<img src="http://illution.dk/ClickThis/assets/images/LoginIcons/LinkedIn.png"></a></td>

</tr>
</table>
</div>
</body>
</html>