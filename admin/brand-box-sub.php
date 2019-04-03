<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$description = $_POST["description"];

$make_id = round($_POST["make_id"], 0);
$page_id = round($_POST["page_id"], 0);
$link_url = $_POST["link_url"];

$status_id = 1;
$oldrank = round($_POST["oldrank"], 0);
$rank = round($_POST["rank"], 0);
$inside_top_nav = round($_POST["inside_top_nav"], 0);
$ms = round($_POST["ms"], 0);

$link_url = $cm->format_url_txt($link_url);
$_SESSION["postmessage"] = "up";
$rback = "brandbox.php";

// common update
$sql = "update tbl_brand_specific set name = '". $cm->filtertext($name) ."'
, description = '". $cm->filtertext($description) ."'
, page_id = '". $page_id ."'
, make_id = '". $make_id ."'
, link_url = '". $cm->filtertext($link_url) ."'
, rank = '".$rank."'
, inside_top_nav = '".$inside_top_nav."' where id = '".$ms."'";
$db->mysqlquery($sql);
// end 

if ($inside_top_nav == 1){
	$sql = "update tbl_brand_specific set inside_top_nav = 0 where id != '".$ms."'";
	$db->mysqlquery($sql);
}

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;

//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $ms."brandboximage".$filename;
	
	$target_path_main = "../brandboximage/";

    //slider image
    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_brand_specific set imagepath = '".$cm->filtertext($filename1)."' where id = '".$ms."'";
	$db->mysqlquery($sql);
}
//end

// update the rank
$tablenm = "tbl_brand_specific";
$wherecls = " id != '".$ms."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

header('Location:'.$rback);	
?>