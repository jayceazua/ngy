<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$email_subject = $_POST["email_subject"];
$file_data = $_POST["file_data"];
$file_data2 = $_POST["file_data2"];
$cc_email = $_POST["cc_email"];

$sql = "update tbl_system_email set email_subject = '".$cm->filtertext($email_subject)."'
, pdes = '".$cm->filtertext($file_data)."'
, pdes2 = '".$cm->filtertext($file_data2)."', cc_email = '".$cm->filtertext($cc_email)."' where id = '".$ms."'";
$db->mysqlquery($sql);

$_SESSION["stt"] = "y";
header('Location:'. $_SERVER['HTTP_REFERER']); 
?>