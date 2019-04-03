<?php
/**
Template Name: Home Page
*/
//$_SESSION["file_name"] = "";
$main_heading = "n";
include($bdr."includes/head.php");
$_SESSION["s_normal_pagination"] = 1;
?>

<?php if ($f_pdata != ""){ echo $f_pdata; } ?>
<div class="clearfix"></div>

<?php
include($bdr."includes/foot.php")
?>