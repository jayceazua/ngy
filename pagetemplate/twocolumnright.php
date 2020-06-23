<?php
/**
Template Name: Two Column Right Sidebar
*/

$main_heading = "n";
$breadcrumb = 1;
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name);
include($bdr."includes/head.php");

if ($connected_manufacturer_id == 0 AND $display_page_heading == 1){
	$title_text = '<h1 class="borderstyle1">'. $frontend->head_title($link_name) .'</h1>';
}else{
	$title_text = '';
	$_SESSION["s_normal_pagination"] = 2;
}

$left_box_content = '
<div class="leftcontentbox">
';

if ($f_pdata != ""){
	$left_box_content .= $f_pdata;
}

$left_box_content .= '
</div>
';

$right_box_content = '
<div class="rightcontentbox scrollcol" parentdiv="main">'. $frontend->display_box_content($column_id) .'</div>
';

if ($cm->isMobileDevice() AND $pageid == 165){
	echo $title_text . $right_box_content . $left_box_content;
}else{
	echo $title_text . $left_box_content . $right_box_content;
}
include($bdr."includes/foot.php")
?>