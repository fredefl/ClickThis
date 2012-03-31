<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis - Authenticate</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>assets/css/auth.css">
	<script type="text/javascript">var root = "<?php echo $base_url; ?>";</script>
</head>
<body>

	<div class="authenticate-container">
		<form name="myform" id="myform" method="post" action="">
			<input type="hidden" hidden value="<?php echo $base_url; ?>" id="base_url" name="base_url" />
			<input type="hidden" hidden name="auth" id="auth" hidden/>
			<div class="application-description-container">
				<p class="ask-for-permission">Do you wish to authenticate <strong><?php echo $app_name; ?></strong>?</p>
			</div> 
			<img src="<?php echo $app_image; ?>" height="98" width="98" class="application-image">
			<div class="button-container">
				<input type="button" id="authenticate" value="Authenticate" class="btn btn-primary">
				<input type="button" id="cancel" value="Cancel" class="btn">
			</div>
		</form>
	</div>

	<div class="application-tooltip">
		<table>
			<tr>
				<td><p class="application-tooltip-description"><?php echo $app_description; ?></p></td>
				<td><img src="<?php echo $app_image; ?>" class="application-tooltip-image" width="64" height="64"/></td>
			</tr>	
		</table>		
	</div>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $base_url; ?>assets/js/auth.js"></script>
</body>
</html>