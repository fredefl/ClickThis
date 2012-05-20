<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis - Authenticate</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cdn_url; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $cdn_url; ?>css/auth.css">
	<script type="text/javascript">var root = "<?php echo $base_url; ?>";</script>
	<script type="text/javascript">var scopes = "<?php echo $scopes; ?>";</script>
</head>
<body>

	<div class="authenticate-container">
		<form name="myform" id="myform" method="post" action="">
			<input type="hidden" hidden value="<?php echo $base_url; ?>" id="base_url" name="base_url" />
			<input type="hidden" hidden name="auth" id="auth" hidden/>
			<input type="hidden" hidden name="auth_token" value="<?php echo $auth_token; ?>" hidden/>
			<div class="application-description-container">
				<p class="ask-for-permission">Do you wish to authenticate <strong><a href="<?php echo $app_url; ?>"><?php echo $app_name; ?></a></strong>?</p>
			</div> 
			<div id="scopes">

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

	<script type="text/javascript" src="<?php echo $this->config->item("jquery_url"); ?>"></script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>js/auth.js"></script>
</body>
</html>