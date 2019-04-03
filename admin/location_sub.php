<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);
$company_id = round($_POST["company_id"], 0);
$yachtclass->location_insert_update();

$old_yw_broker_id = round($_POST["old_yw_broker_id"], 0);
$yw_broker_id = round($_POST["yw_broker_id"], 0);
if ($old_yw_broker_id != $yw_broker_id AND $yw_broker_id > 0){
	$sql_boat = "update tbl_yacht set location_id = '". $location_id ."', broker_id = 1 where yw_broker_id = '". $yw_broker_id ."'";
	$db->mysqlquery($sql_boat);
}

$dt = date("Y-m-j H:i:s");
if ($ms == 0){
    $_SESSION["postmessage"] = "nw"; 
    $rback = "mod_location.php?cid=" . $company_id;
}else{
    $_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>