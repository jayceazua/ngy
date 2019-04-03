<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$sql = "select * from tbl_inventory";
$result = $db->fetch_all_array($sql);
$found = count($result);
$rank = 1;
foreach($result as $row){
	$id = $row['id'];
	
	$sql_a = "update tbl_inventory set rank = '". $rank ."' where id = '". $id ."'";
	$db->mysqlquery($sql_a);
	$rank++;
}
?>