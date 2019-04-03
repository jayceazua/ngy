<?php
include("common.php");

$sql = "delete from tbl_session where ses_id = '". $_SESSION["sesid"] ."'";
$db->mysqlquery($sql);

session_destroy();
$_SESSION["logg"] = "sss";
header('Location: index.php');
?>
