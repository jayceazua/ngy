<?php
$pageid = 0;

$display_brd_array = "n";
$main_heading = "n";
$googlemap = 0;

$slug = $_REQUEST['slug'];
$result = $slideshowclass->check_slideshow_with_return($slug, 1);
$row = $result[0];

foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);	
}

$atm1 = $m1;
$atm2 = $m2;
$atm3 = $m3;

$breadcrumb = 0;
$link_name = $name;
include($bdr."includes/head.php");
?>

<div class="fullcol">
	<?php echo $slideshowclass->display_boat_slideshow($id); ?>
</div>
<?php
include($bdr."includes/foot.php");
?>