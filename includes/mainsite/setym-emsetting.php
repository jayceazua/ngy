<?php
include("../common.php");
$tokencode = $_POST["tokencode"];
$retval = $ymclass->get_em_options($tokencode);
echo $retval;
?>