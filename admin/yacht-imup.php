<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$sql = "select imgpath from tbl_yacht_photo";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
    $imgpath = $filename1 = $row["imgpath"];
	$filename_tmp = "../yachtimage/bigger/" . $imgpath;
	
	$r_width = $cm->yacht_im_width_sl;
	$r_height = $cm->yacht_im_height_sl;
	$target_path = "../yachtimage/slider/";
	$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
}
?>