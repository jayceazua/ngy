<?php
/**
Template Name: Single Column
*/

$main_heading = "n";
$breadcrumb = 1;
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name);
include($bdr."includes/head.php");

if ($connected_manufacturer_id == 0){
	echo '<h1 class="borderstyle1">'. $frontend->head_title($link_name) .'</h1>';
}
if ($f_pdata != ""){ echo $f_pdata; }

include($bdr."includes/foot.php")
?>