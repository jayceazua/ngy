<?php
include("../common.php");
$tokencode = $_POST["tokencode"];
$retval = $ymclass->check_ym_login_step1($tokencode);
echo $retval;
?>