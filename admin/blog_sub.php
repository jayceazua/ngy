<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);
$blogclass->blog_insert_update();

if ($ms == 0){
	$_SESSION["postmessage"] = "nw"; 
    $rback = "mod_blog.php";
}else{
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>