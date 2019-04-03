<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$iiid = round($_POST["ms"], 0);

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $yachtclass->insert_yacht_video_file($iiid);
	exit;
}
?>