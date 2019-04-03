<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$email_subject = $_POST["email_subject"];
$file_data = $_POST["file_data"];

$sql = "update tbl_user_account_status set email_subject = '".$cm->filtertext($email_subject)."'
, pdes = '".$cm->filtertext($file_data)."'
where id = '".$ms."'";
$db->mysqlquery($sql);

$_SESSION["stt"] = "y";
header('Location:'. $_SERVER['HTTP_REFERER']); 
?>