<?php
include("../common.php");
$tokencode = $_POST["tokencode"];
$sql = "update tbl_mainsite set tokencode = '". $cm->filtertext($tokencode)."' where id = 1";
$db->mysqlquery($sql);
?>