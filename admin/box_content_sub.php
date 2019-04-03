<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$caption = $_POST["caption"];
$pdes = $_POST["pdes"];
$ms = round($_POST["s"], 0);

$_SESSION["postmessage"] = "up";
// common update
$sql="update tbl_box_content set caption = '". $cm->filtertext($caption)."'
, pdes = '". $cm->filtertext($pdes)."'
where id = '".$ms."'";
$db->mysqlquery($sql);
// end 
header('Location:'. $_SERVER['HTTP_REFERER']);	
?>