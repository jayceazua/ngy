<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$returnval = $logoscrollclass->logo_insert_update();
$returnval = json_decode($returnval);
$ms = $returnval->ms;
$section_id = $returnval->section_id;
$whedit = $returnval->whedit;

if ($whedit == 0){
	$_SESSION["postmessage"] = "nw"; 
    $rback = "mod-make-logo.php?sectionid=" . $section_id;
}else{
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>