<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);
$yachtclass->user_insert_update();

$old_yw_broker_id = round($_POST["old_yw_broker_id"], 0);
$yw_broker_id = round($_POST["yw_broker_id"], 0);

if ($old_yw_broker_id != $yw_broker_id){
	if ($yw_broker_id > 0){
		$sql = "select id, company_id, location_id from tbl_user where yw_broker_id = '". $yw_broker_id ."'";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$row = $result[0];
			$broker_id = $row["id"];
			$company_id = $row["company_id"];
			$location_id = $row["location_id"];
			
			$sql_boat = "update tbl_yacht set company_id = '". $company_id ."', location_id = '". $location_id ."', broker_id = '". $broker_id ."' where yw_broker_id = '". $yw_broker_id ."'";
			$db->mysqlquery($sql_boat);
		}
	}
}

$dt = date("Y-m-j H:i:s");
if ($ms == 0){
    $_SESSION["postmessage"] = "nw"; 
    $rback = "mod_user.php";
}else{
    $_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}
if ($rback == ""){ $rback = "mod_user.php"; }
header('Location:'.$rback);
?>