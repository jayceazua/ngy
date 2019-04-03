<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$slideshowclass->slideshow_insert_update();

if ($ms == 0){
	$_SESSION["postmessage"] = "nw"; 
    $rback = "mod_slideshow.php";
}else{
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>