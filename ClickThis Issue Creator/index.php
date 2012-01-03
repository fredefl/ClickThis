<?php
print_r($_POST);
if($_POST['title']){
	require_once 'Github/Autoloader.php';
	Github_Autoloader::register();
	$Github = new Github_Client();
	$Github->authenticate("fredefl","3f7ed211500b2b50749d34cf2a9fc32b");
	$Result = $Github->getIssueApi()->open('fredefl', 'ClickThis', $_POST['title'], $_POST['description']);
	$Id = $Result['number'];
	$Github->getIssueApi()->addLabel('fredefl', 'ClickThis','User',$Id);
?>
<h1>Thanks for your feedback</h1>
<a href="http://illution.dk/ClickThisPrototype">Click here to go back</a>
<?php
} else {
?>
<h1>You did not write anything...</h1>
<a href="http://illution.dk/ClickThisPrototype">Click here to go back</a>
<?php
}
?>