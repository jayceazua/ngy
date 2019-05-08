<?php
/**
Template Name: Two Column Left Sidebar
*/

$main_heading = "n";
$breadcrumb = 1;
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name);
include($bdr."includes/head.php");
?>
<div class="rightcontentbox2">
<?php if ($connected_manufacturer_id == 0 AND $display_page_heading == 1){ ?>
<h1 class="borderstyle1"><?php echo $frontend->head_title($link_name); ?></h1>
<?php
}else{
	$_SESSION["s_normal_pagination"] = 2;	
}

if ($f_pdata != ""){ echo $f_pdata; }
?>
</div>

<div class="leftcontentbox2 scrollcol" parentdiv="main">
<?php
echo $frontend->display_box_content($column_id);
?>
</div>
<?php
include($bdr."includes/foot.php")
?>