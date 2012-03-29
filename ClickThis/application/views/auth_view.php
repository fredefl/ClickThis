<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis</title>
</head>
<body>
	<form action="" method="post" name="myform" id="myform">
		<input type="hidden" hidden value="<?php echo $base_url; ?>" id="base_url" name="base_url" />
		<input type="text" name="auth" id="auth" hidden/>
		<input type="button" id="authenticate" value="Authenticate" />
		<input type="button" id="cancel" value="Cancel" />
	</form>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>assets/js/auth.js"></script>
</body>
</html>