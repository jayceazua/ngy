<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$parent_id = round($_POST["parent_id"], 0);
$ms = round($_POST["ms"], 0);

if ($parent_id == 0){ $page_level = 1; }else{ $page_level = $db->total_record_count("select page_level as ttl from tbl_page where id = '". $parent_id ."'") + 1; }
$sql = "update tbl_page set parent_id = '". $parent_id ."', page_level = '". $page_level ."'  where id = '". $ms ."'";
$db->mysqlquery($sql);
header('Location: ' . $_SESSION["bck_pg"]);
?>