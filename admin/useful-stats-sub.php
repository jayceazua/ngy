<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$sql = "select count(*) as ttl from tbl_stats where status_id = 1";
$totalrec = $db->total_record_count($sql);

for ($k = 0; $k < $totalrec; $k++){
	$id = round($_POST["id" . $k], 0);
	$min_value = round($_POST["min_value" . $k], 0);
	$max_value = round($_POST["max_value" . $k], 0);
	$status_id = 1;
	$rank = $k + 1;
	
	$sql = "update tbl_stats set min_value = '". $min_value ."'
	, max_value = '". $max_value ."'
	, status_id = '". $status_id ."'
	, rank = '". $rank ."' where id = '". $id ."'";
	$db->mysqlquery($sql);
}

$_SESSION["postmessage"] = "up";
$rback = "useful-stats.php";
header('Location:'.$rback);
exit;
?>