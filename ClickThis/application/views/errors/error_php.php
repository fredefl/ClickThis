<?php
$severity = str_replace("<p>","",str_replace("</p>","",$severity));
$message = str_replace("<p>","",str_replace("</p>","",$message));
$filepath = str_replace("<p>","",str_replace("</p>","",$filepath));
$line = str_replace("<p>","",str_replace("</p>","",$line));
echo $message,"|",$severity,"|",$filepath,"|",$line;
die();
?>