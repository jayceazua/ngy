<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$agent_name = $_POST["agent_name"];
$agent_email = $_POST["agent_email"];
$agent_phone = $_POST["agent_phone"];
$agent_fax = $_POST["agent_fax"];
$company_name = $_POST["company_name"];
$company_email = $_POST["company_email"];

$cc_email = $_POST["cc_email"];
$siteadmin = round($_POST["siteadmin"], 0);
$othersend = round($_POST["othersend"], 0);
$email_subject = $_POST["email_subject"];
$file_data = $_POST["file_data"];

$sql = "update tbl_brokerage_services_email set agent_name = '".$cm->filtertext($agent_name)."'
, agent_email = '".$cm->filtertext($agent_email)."'
, agent_phone = '".$cm->filtertext($agent_phone)."'
, agent_fax = '".$cm->filtertext($agent_fax)."'
, company_name = '".$cm->filtertext($company_name)."'
, company_email = '".$cm->filtertext($company_email)."'
, cc_email = '".$cm->filtertext($cc_email)."'
, siteadmin = '".$cm->filtertext($siteadmin)."'
, othersend = '".$cm->filtertext($othersend)."'
, email_subject = '".$cm->filtertext($email_subject)."'
, pdes = '".$cm->filtertext($file_data)."'
where id = '".$ms."'";
$db->mysqlquery($sql);

$_SESSION["stt"] = "y";
header('Location:'. $_SERVER['HTTP_REFERER']); 
?>