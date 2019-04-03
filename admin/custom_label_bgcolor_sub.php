<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$sql = "select count(*) as ttl from tbl_custom_label";
$counter = $db->total_record_count($sql);

$sql = "delete from tbl_custom_label_options";
$db->mysqlquery($sql);

for ($k = 0; $k <= $counter; $k++){
	$custom_label_id = round($_POST["custom_label_id" . $k], 0);
	$custom_label_bgcolor = $_POST["custom_label_bgcolor" . $k];
	$custom_label_textcolor = $_POST["custom_label_textcolor" . $k];
	$sql = "insert into tbl_custom_label_options (custom_label_id, custom_label_bgcolor, custom_label_textcolor) values ('". $cm->filtertext($custom_label_id) ."', '". $cm->filtertext($custom_label_bgcolor) ."', '". $cm->filtertext($custom_label_textcolor) ."')";
	$iiid = $db->mysqlquery_ret($sql);
}

$_SESSION["postmessage"] = "up";
$rback = "custom_label_bgcolor.php";
header('Location:'.$rback);	
?>