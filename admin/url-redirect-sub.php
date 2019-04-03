<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$oldurl = $_POST["oldurl"];
$newurl = $_POST["newurl"];

$oldurl = $cm->validate_string_withslash($oldurl);
$newurl = $cm->validate_string_withslash($newurl);

if ($ms == 0){
	$sql = "insert into tbl_page_301 (oldurl, newurl) values ('". $cm->filtertext($oldurl) ."', '". $cm->filtertext($newurl) ."')";
	$db->mysqlquery($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod-url-redirect.php";
}else{
    $sql = "update tbl_page_301 set oldurl = '". $cm->filtertext($oldurl) ."', newurl = '". $cm->filtertext($newurl) ."' where id = '". $ms ."'";
	$db->mysqlquery($sql);
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// write to .htaccess file
//$adm->seo_url_settings();

header('Location:'.$rback);
exit;
?>