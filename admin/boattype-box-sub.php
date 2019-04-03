<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$int_page_id = round($_POST["int_page_id"], 0);
$status_id = 1;
$ms = round($_POST["ms"], 0);

$_SESSION["postmessage"] = "up";
$rback = "boattypebox.php";

// common update
$sql = "update tbl_boat_type_specific set name = '". $cm->filtertext($name) ."'
, int_page_id = '". $int_page_id ."' where id = '".$ms."'";
$db->mysqlquery($sql);
// end 

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;

//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $ms."boattypeboximage".$filename;
	
	$target_path_main = "../boattypeboximage/";

    //slider image
    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_boat_type_specific set imagepath = '".$cm->filtertext($filename1)."' where id = '".$ms."'";
	$db->mysqlquery($sql);
}
//end

header('Location:'.$rback);	
?>