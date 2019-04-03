<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$sql = "select id from tbl_yacht";
$result = $db->fetch_all_array($sql);

foreach($result as $row){
	$boatid = $row["id"];
	$latlonar = $geo->getLatLon($boatid, 1);
	$lat = $latlonar["lat"];
	$lon = $latlonar["lon"];
	
	$sqlu = "update tbl_yacht set lat_val = '". $cm->filtertext($lat)."'
	, lon_val = '". $cm->filtertext($lon)."' where id = '". $boatid ."'";
	$db->mysqlquery($sqlu);
	
	echo '<p>'. $sqlu .'</p>';
	
	
}

$_SESSION["postmessage"] = "up";
$rback = $_SESSION["bck_pg"];
//header('Location:'.$rback);
?>