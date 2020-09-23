<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$description = $_POST["description"];

$make_id = round($_POST["make_id"], 0);
$page_id = round($_POST["page_id"], 0);
$link_url = $_POST["link_url"];
$bgimagealt = $_POST["bgimagealt"];

$status_id = 1;
$oldrank = round($_POST["oldrank"], 0);
$rank = round($_POST["rank"], 0);
$inside_top_nav = round($_POST["inside_top_nav"], 0);
$section_id = round($_POST["section_id"], 0);
$ms = round($_POST["ms"], 0);

$link_url = $cm->format_url_txt($link_url);

if ($ms == 0){
	$rank = $db->total_record_count("select max(rank) as ttl from tbl_brand_specific where section_id = '". $section_id ."'") + 1;
	$sql = "insert into tbl_brand_specific (name) values ('". $cm->filtertext($name) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	
	$_SESSION["postmessage"] = "nw"; 
    $rback = "brandbox.php?sectionid=" . $section_id;
}else{
	$sql = "update tbl_brand_specific set name = '". $cm->filtertext($name) ."' where id = '". $ms. "'";
	$db->mysqlquery($sql);
	$rank = round($_POST["rank"], 0);			
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}

// common update
$sql = "update tbl_brand_specific set description = '". $cm->filtertext($description) ."'
, bgimagealt = '". $cm->filtertext($bgimagealt) ."'
, page_id = '". $page_id ."'
, make_id = '". $make_id ."'
, link_url = '". $cm->filtertext($link_url) ."'
, rank = '".$rank."'
, inside_top_nav = '". $inside_top_nav ."'
, section_id = '". $section_id ."' where id = '". $iiid ."'";
$db->mysqlquery($sql);
// end 

if ($inside_top_nav == 1){
	$sql = "update tbl_brand_specific set inside_top_nav = 0 where id != '". $iiid ."'";
	$db->mysqlquery($sql);
}

//box image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $iiid."brandboximage".$filename;
	
	$target_path_main = YCROOTPATH . "brandboximage/";

	$imw = $cm->boattype_box_im_width;
	$imh = $cm->boattype_box_im_height;

    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_brand_specific set imagepath = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
	$db->mysqlquery($sql);
}
//end

//logo image upload
$filename = $_FILES['logoimage']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['logoimage']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $iiid."brandlogoimage".$filename;
	
	$target_path_main = YCROOTPATH . "brandboximage/";

	$imw = $cm->brand_box_logo_im_width;
	$imh = $cm->brand_box_logo_im_height;

    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_brand_specific set logoimage = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
	$db->mysqlquery($sql);
}
//end

// update the rank
$tablenm = "tbl_brand_specific";
$wherecls = " id != '".$iiid."' and section_id = '". $section_id ."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

header('Location:'.$rback);	
?>