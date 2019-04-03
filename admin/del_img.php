<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$path = $_REQUEST ["path"];
unlink ("../cmsfile/contentimages/".$path);
header('location: '. $_SERVER['HTTP_REFERER']);
?>