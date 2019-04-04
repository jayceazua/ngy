<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$modelclass->search_field_update();
$_SESSION["postmessage"] = "up";
header('Location:model-search-fields.php');
?>