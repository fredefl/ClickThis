<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ClickThis - Reset password</title>
</head>
<body>
	<form id="resetpassword" method="post" action="<?php echo $base_url; ?>reset/password">
		Enter your email : <input type="email" name="email" ><input type="submit"> or <a href="<?php echo $base_url; ?>login">Cancel</a>
	</form>
	
</body>
</html>