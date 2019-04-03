<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$yachtclass->user_insert_update();
$_SESSION["postmessage"]= "Record updated successfully.";
header('Location: admin_details.php');
?>