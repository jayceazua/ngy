<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$counter = round($_POST["counter"], 0);
for ($k = 0; $k < $counter; $k++){
	$manufacturer_id = round($_POST["manufacturer_id" . $k], 0);
	$catalog_link = $_POST["catalog_link" . $k];
	$catalog_link = $cm->format_url_txt($catalog_link);
	
	$sql = "delete from tbl_manufacturer_catalog where manufacturer_id = '". $manufacturer_id ."'";
	$db->mysqlquery($sql);
	
	$sql = "insert into tbl_manufacturer_catalog (manufacturer_id, catalog_link) values ('". $manufacturer_id ."', '". $cm->filtertext($catalog_link) ."')";
	$db->mysqlquery($sql);
}

$_SESSION["postmessage"] = "up";
$rback = "make-catalog.php";
header('Location:'.$rback);	
?>