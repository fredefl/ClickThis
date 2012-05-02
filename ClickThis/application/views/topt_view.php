<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis - Two step verification</title>
</head>
<body>
	<form action="<?php echo $base_url; ?>login/check_topt" method="post">
		<input type="text" name="key">
		<input type="submit">
	</form>
	<script type="text/javascript">setToken();</script>
</body>
</html>