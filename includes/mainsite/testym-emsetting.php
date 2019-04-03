<?php
include("../common.php");
$tokencode = $_POST["tokencode"];
$toemail = $cm->filtertextdisplay($_POST["toemail"]);
$ccemail = $cm->filtertextdisplay($_POST["ccemail"]);
$bccemail = $cm->filtertextdisplay($_POST["bccemail"]);
$ymclass->test_em_options($tokencode, $toemail, $ccemail, $bccemail);
?>