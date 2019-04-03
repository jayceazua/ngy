<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);
$yachtclass->edit_company_profile();
$_SESSION["postmessage"] = "up";
$rback = $_SESSION["bck_pg"];
header('Location:'.$rback);
?>