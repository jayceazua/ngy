<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$description = $_POST["description"];

$make_id = round($_POST["make_id"], 0);
$page_id = round($_POST["page_id"], 0);
$link_url = $_POST["link_url"];

$make_id2 = round($_POST["make_id2"], 0);
$page_id2 = round($_POST["page_id2"], 0);
$link_url2 = $_POST["link_url2"];

$status_id = 1;
$oldrank = round($_POST["oldrank"], 0);
$rank = round($_POST["rank"], 0);
$ms = round($_POST["ms"], 0);

$link_url = $cm->format_url_txt($link_url);
$_SESSION["postmessage"] = "up";
$rback = "menu-box.php";

// common update
$sql = "update tbl_menu_box set name = '". $cm->filtertext($name) ."'
, description = '". $cm->filtertext($description) ."'
, page_id = '". $page_id ."'
, make_id = '". $make_id ."'
, link_url = '". $cm->filtertext($link_url) ."'

, page_id2 = '". $page_id2 ."'
, make_id2 = '". $make_id2 ."'
, link_url2 = '". $cm->filtertext($link_url2) ."'

, rank = '".$rank."' where id = '".$ms."'";
$db->mysqlquery($sql);
// end 

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;

//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $ms."menuboximage".$filename;
	
	$target_path_main = "../menuboximage/";

    //slider image
    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_menu_box set imagepath = '".$cm->filtertext($filename1)."' where id = '".$ms."'";
	$db->mysqlquery($sql);
}
//end

// update the rank
$tablenm = "tbl_menu_box";
$wherecls = " id != '".$ms."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

header('Location:'.$rback);	
?>