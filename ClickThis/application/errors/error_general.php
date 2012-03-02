<?php
$heading = str_replace("<p>","",str_replace("</p>","",$heading));
$message = str_replace("<p>","",str_replace("</p>","",$message));
//include 'http://illution.dk/Error.php?Heading='.urlencode($heading).'&Message='.urlencode($message).'&System=ClickThis';
echo "<strong>Heading</strong>:",$heading,"<br>";
echo "<strong>Message</strong>:",$message,"<br>";
die();
?>