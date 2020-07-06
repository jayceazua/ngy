<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$charterboatclass->insert_charterboat_image();
	exit;
}
?>