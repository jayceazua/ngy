<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$status_id = round($_POST["status_id"], 0);
$ms = round($_POST["ms"], 0);
$rank = 1;

if ($ms == 0){
	$sql = "insert into tbl_slider_category (name) values ('". $cm->filtertext($name) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_slider_category.php";
}else{
    $sql = "update tbl_slider_category set name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// common update
$sql = "update tbl_slider_category set status_id = '". $status_id ."'
, rank = '".$rank."' where id = '".$iiid."'";
$db->mysqlquery($sql);
// end 

header('Location:'.$rback);	
?>