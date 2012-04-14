<html>
<head>

</head>
<body>
	<?php
		if(!isset($_SESSION["UserId"])){	
	?>
		<a href="login">Login</a>
	<?php
		} else {
	?>
		<a href="logout">Logout</a>
	<?php
		}
	?>
</body>
</html>