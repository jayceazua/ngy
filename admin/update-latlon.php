<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$id = round($_REQUEST["id"], 0);

$latlonar = $geo->getLatLon($id, 1);
$lat = $latlonar["lat"];
$lon = $latlonar["lon"];

$sql = "update tbl_yacht set lat_val = '". $cm->filtertext($lat)."'
, lon_val = '". $cm->filtertext($lon)."' where id = '". $id ."'";
$db->mysqlquery($sql);

$_SESSION["postmessage"] = "up";
$rback = $_SESSION["bck_pg"];
header('Location:'.$rback);
?>