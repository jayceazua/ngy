<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$returnval = $charterboatclass->charterboat_insert_update();
$returnval = json_decode($returnval);
$ms = $returnval->ms;
$whedit = $returnval->whedit;

if ($whedit == 0){
	$_SESSION["postmessage"] = "nw"; 
    $rback = "mod-charterboat.php";
}else{
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>