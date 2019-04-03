<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$m1 = $_POST["m1"];
$m2 = $_POST["m2"];
$m3 = $_POST["m3"];

$sql = "update tbl_tag set m1 = '". $cm->filtertext($m1)."'
, m2 = '".$cm->filtertext($m2)."'
, m3 = '".$cm->filtertext($m3)."'
where id = 1";	
$db->mysqlquery($sql);
$_SESSION["postmessage"]= "up";
header('location: '. $_SERVER['HTTP_REFERER']);
?>