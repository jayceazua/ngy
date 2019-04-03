<?php
/**
Template Name: Full Screen
*/

$startend = 0;
$main_heading = "n";
$breadcrumb = 1;
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name);
include($bdr."includes/head.php");
echo $f_pdata;
include($bdr."includes/foot.php")
?>