<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Illution - ClickThis - Token</title>
	<script type="text/javascript">var root = "<?php echo $base_url; ?>";</script>
</head>
<body>
	<script type="text/javascript">var killToken = 
	<?php 
		if(isset($DeleteToken)){
			echo $DeleteToken;
		} else {
			echo "false";
		} 
	?>;</script>
	<script type="text/javascript" src="<?php echo $cdn_url; ?>js/token.js"></script>
	<script type="text/javascript">setToken();</script>
</body>
</html>