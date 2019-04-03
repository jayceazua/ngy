<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$parentid = round($_POST["parentid"], 0);
$page_type = round($_POST["page_type"], 0);
$link_type = round($_POST["link_type"], 0);
$name = $_POST["name"];
$extraclass = $_POST["extraclass"];
$link_required = $_POST["link_required"];
$pgnm = $_POST["pgnm"];
$disp_on = round($_POST["disp_on"], 0);
$templatefile = $_POST["templatefile"];
$column_id = round($_POST["column_id"], 0);
$status = $_POST["status"];

$page_url = $_POST["page_url"];
$int_page_sel = $_POST["int_page_sel"];
$new_window = $_POST["new_window"];

$file_data = $_POST["file_data"];
$display_enquire_form = round($_POST["display_enquire_form"], 0);
$only_menu = round($_POST["only_menu"], 0);
$slider_category_id = round($_POST["slider_category_id"], 0);

$connected_manufacturer_id = round($_POST["connected_manufacturer_id"], 0);
$connected_group_id = round($_POST["connected_group_id"], 0);
$connected_type_id = round($_POST["connected_type_id"], 0);

$custom_make_id = round($_POST["custom_make_id"], 0);
$custom_condition_id = round($_POST["custom_condition_id"], 0);
$custom_category_id = round($_POST["custom_category_id"], 0);
$custom_type_id = round($_POST["custom_type_id"], 0);
$custom_stateid = round($_POST["custom_stateid"], 0);
$custom_lnmin = round($_POST["custom_lnmin"], 0);
$custom_lnmax = round($_POST["custom_lnmax"], 0);
$custom_status_id = round($_POST["custom_status_id"], 0);
$custom_owned = round($_POST["custom_owned"], 0);
$nosearchcol = round($_POST["nosearchcol"], 0);

if ($custom_make_id > 0 OR $custom_condition_id > 0 OR $custom_category_id > 0 OR $custom_type_id > 0 OR $custom_stateid > 0 OR $custom_lnmin > 0 OR $custom_lnmax > 0 OR $custom_status_id > 0){
	$dyanamicheading = round($_POST["dyanamicheading"], 0);
	
	$sp_typeid = 0;
	if ($custom_owned == 2){
		$sp_typeid = 1;
	}elseif ($custom_owned == 3){
		$sp_typeid = 2;
		$custom_owned = 2;
	}else{
		if ($custom_type_id == $yachtclass->catamaran_id){
			$sp_typeid = 2;
		}else{
			$sp_typeid = 1;
		}
	}
	
	$custom_inventory_view = array(
		"custom_make_id" => $custom_make_id,
		"custom_condition_id" => $custom_condition_id,
		"custom_category_id" => $custom_category_id,
		"custom_type_id" => $custom_type_id,
		"custom_stateid" => $custom_stateid,
		"custom_lnmin" => $custom_lnmin,
		"custom_lnmax" => $custom_lnmax,
		"custom_status_id" => $custom_status_id,
		"custom_owned" => $custom_owned,
		"sp_typeid" => $sp_typeid,
		"nosearchcol" => $nosearchcol,
		"dyanamicheading" => $dyanamicheading
	);
	$custom_inventory_view = json_encode($custom_inventory_view);
}else{
	$dyanamicheading = 0;
	$custom_inventory_view = '';
}

//menu section
$submenusection = json_encode($_POST["section_type_id"]);

$m1 = $_POST["m1"];
$m2 = $_POST["m2"];
$m3 = $_POST["m3"];

$oldrank = round($_POST["oldrank"], 0);
$ms = round($_POST["ms"], 0);
if ($status != "y"){ $status = "n"; }
if ($link_required != "y"){ $link_required = "n"; }

//page_type_check
if ($page_type == 1 OR $page_type == 4 OR $page_type == 5){
 $link_type = 0;
 $int_page_id = 0;
 $int_page_tp = "";
 $page_url = "";
 $new_window = "n";
 $doc_name = "";
}

if ($page_type == 5 AND $link_required == "n"){ $pgnm = ""; }

if ($page_type == 2){
 $link_type = 0;
 $int_page_id = 0;
 $int_page_tp = "";
 $page_url = "";
 $new_window = "y";
 
 $breadcrumb = "n";
 $pgnm = "";
 
 $m1 = "";
 $m2 = "";
 $m3 = "";
  $only_menu = 0;
}

if ($page_type == 3){
 $doc_name = ""; 
 $breadcrumb = "n";
 $template_id = 0;
 $banner_id = 0;
 $group_id = 0;
 $file_data = "";
 $left_menu = 0; 
 $right_menu = 0;
 $pgnm = "";
 
 $m1 = "";
 $m2 = "";
 $m3 = "";
  $only_menu = 0;
 
 if ($link_type == 2){ 
 $int_page_sel_ar = explode("/!", $int_page_sel);
 $int_page_id = $int_page_sel_ar[0];
 $int_page_tp = $int_page_sel_ar[1];
 $page_url = "";
 }else{
   $int_page_id = 0;
   $int_page_tp = "";
 }
 if ($new_window != "y"){ $new_window = "n"; }
}
//end
if ($parentid == 0){ $page_level = 1; }else{ $page_level = $db->total_record_count("select page_level as ttl from tbl_page where id = '". $parentid ."'") + 1; }
if ($ms == 0){
    $rank = $db->total_record_count("select max(rank) as ttl from tbl_page where parent_id = '". $parentid ."'") + 1;
    $sql = "insert into tbl_page (parent_id) values ('". $parentid ."')";
    $iiid = $db->mysqlquery_ret($sql); 
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_page.php?parentid=" . $parentid;
}else{
    $rank = round($_POST["rank"], 0);
	if ($ms == 1){ $site_id = 0; }
    $sql = "update tbl_page set parent_id = '". $parentid ."' where id='".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
} 

//common update
$sql = "update tbl_page set page_type = '". $page_type ."' 
, page_level = '". $page_level ."'
, column_id = '". $column_id ."'
, name = '". $cm->filtertext($name) ."'
, file_data = '". $cm->filtertext($file_data) ."'
, display_enquire_form = '". $display_enquire_form ."'

, pgnm = '". $cm->filtertext($pgnm) ."'
, link_required = '". $link_required ."'

, link_type = '". $link_type ."'
, page_url = '". $cm->filtertext($page_url) ."'
, int_page_id = '". $int_page_id ."' 
, int_page_tp = '". $int_page_tp ."' 
, new_window = '". $new_window ."'

, rank='".$rank."' 
, status = '". $status ."' 
, disp_on = '". $disp_on ."'
, templatefile = '". $cm->filtertext($templatefile) ."'
, only_menu = '". $only_menu ."'
, slider_category_id = '". $slider_category_id ."'

, connected_manufacturer_id = '". $connected_manufacturer_id ."'
, connected_group_id = '". $connected_group_id ."'
, connected_type_id = '". $connected_type_id ."'
, custom_inventory_view = '". $cm->filtertext($custom_inventory_view) ."'

, extraclass = '". $cm->filtertext($extraclass) ."'
, submenusection = '". $cm->filtertext($submenusection) ."'

, m1 = '". $cm->filtertext($m1) ."'
, m2 = '". $cm->filtertext($m2) ."'
, m3 = '". $cm->filtertext($m3) ."'
where id = '".$iiid."'";
$db->mysqlquery($sql);

//document upload if any
if ($page_type == 2){
	$filename = $_FILES['myfile']['name'];
	if ($filename != ""){
	    $wh_ok = $fle->check_file_ext($cm->allow_attachment_ext, $filename);
        if ($wh_ok == "y"){
    		$filename = $fle->uploadfilename($filename);	
    		$filename1 = $iiid."-doc-".$filename;
    		$target_path = "../docfile/";		
    		$target_path = $target_path . $cm->filtertextdisplay($filename1);
    		$fle->fileupload($_FILES['myfile']['tmp_name'], $target_path);
    				
    		$sql = "update tbl_page set doc_name = '". $cm->filtertext($filename1)."' where id = '".$iiid."'";
    		$db->mysqlquery($sql);
        }		
   }
}
//end


//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $ms."menuimage".$filename;
	
	$target_path_main = "../menuimage/";

    //small image
    $target_path = $target_path_main;
    $r_width = $cm->menu_im_width;
    $r_height = $cm->menu_im_height;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_page set menu_imgpath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
	$db->mysqlquery($sql);
}
//end

// update the rank
$tablenm = "tbl_page";
$wherecls = " id != '".$iiid."' and parent_id = '". $parentid ."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

// write to .htaccess file
//$adm->seo_url_settings();

$_SESSION["postmessage"] = "up";
header('Location:'.$rback);
?>