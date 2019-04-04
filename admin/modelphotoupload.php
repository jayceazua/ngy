<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

if($_SERVER['REQUEST_METHOD'] == "POST"){
	$modelclass->insert_model_image();
	exit;
}
?>