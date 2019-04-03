<?php
include("../common.php");
$tokencode = $_POST["tokencode"];
$setvalue = $_POST["setvalue"];
$setid = round($_POST["setid"], 0);
$ymclass->update_em_options($tokencode, $setvalue, $setid);
?>