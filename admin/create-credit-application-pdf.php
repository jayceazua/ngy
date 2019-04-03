<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$id = round($_REQUEST['id'], 0);
$creditappclass->create_credit_application_pdf($id, '../');
?>