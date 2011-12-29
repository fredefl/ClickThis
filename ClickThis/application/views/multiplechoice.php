<!DOCTYPE html>
<head>
	<?php echo GetAsset::GetJqueryUITheme('base'); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php echo GetAsset::GetMobileStylesheet('user'); ?>
	<title>CSS3MobileButtons</title>
</head> 
<body> 

	<div id="Page">
        <h1>Hvilke(n) sportsgren(e) dyrker du?</h1>
        <?php echo $Buttons ?>
        <div class="push"></div>
	</div>
    
    <div id="ByLine">
        <div id="Publisher">Llamaværnet</div>
        <div id="MadeBy">Illution 2011</div>
    </div>
	<?php echo GetAsset::GetJquery(); ?>
    <?php echo GetAsset::GetMobileScript('user'); ?>
</body> 
</html> 
