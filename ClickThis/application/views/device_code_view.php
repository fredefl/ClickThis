<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis - Authenticate</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cdn_url; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $cdn_url; ?>css/auth.css">
	<script type="text/javascript">var root = "<?php echo $base_url; ?>";</script>
</head>
<body>

	&nbsp;An app is requesting permission<br> 
	&nbsp;to access your account data,<br>
	&nbsp;please enter the code that the app gave you.
	<div id="codebox">
		<form id="code" action="<?php echo $base_url; ?>oauth/device/validate_code" method="post">
			&nbsp;&nbsp;<input type="text" class="input" name="code">
			<input type="submit">
		</form>
	</div>

	<script type="text/javascript" src="<?php echo $this->config->item("jquery_url"); ?>"></script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>css/auth.css"></script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>css/device_code.css"></script>
</body>
</html>