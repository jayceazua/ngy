<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$filename=$_GET["down"];
$name=$_GET["name"];

$filename = "email.csv";
$name = "email.csv";

$time=date("Y-m-j H:i:s");
$size=filesize($filename);
header("Content-Type: application/octet-stream"); 
header("Content-Length: $size"); 
 
header("Content-Disposition: attachment; filename=$name"); 
header("Content-Transfer-Encoding: binary"); 
$fd = fopen("$filename", "rb"); 

fpassthru($fd);	
fclose($fd);  //this line gives an error
?>