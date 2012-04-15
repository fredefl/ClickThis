<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ClickThis - Reset password</title>
</head>
<body>
	<form id="resetpassword" method="post" action="<?php echo $base_url; ?>reset/password/endpoint/<?php echo $token; ?>">
		<label for="password">Your new password</label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="password" name="password" id="password"><br>

		<label for="repassword">Retype your new password</label>
		<input type="password" name="repassword" id="repassword"><br>

		<input type="submit">
	</form>
	
</body>
</html>