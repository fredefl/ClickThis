<?php
	/*$Array = array(array('Google','Twitter'),array('Facebook','ClickThis'));
	echo json_encode($Array);*/
	/*if(isset($_POST)){
		/*$Data = $_POST;
		foreach($Data as $Name => $Value){
			echo '|'.$Name.'|'.$Value;
		}
		print_r($_POST);		
	}*/
	print_r(json_decode($_GET['data']));
?>