<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$description = $_POST["description"];
$link_type = round($_POST["link_type"], 0);
$page_url = $_POST["page_url"];
$int_page_sel = $_POST["int_page_sel"];
$status_id = 1;
$ms = round($_POST["ms"], 0);
$new_window = "n";

if ($link_type == 2){ 
	$int_page_sel_ar = explode("/!", $int_page_sel);
	$int_page_int = $int_page_sel_ar[0];	
	$int_page_tp = $int_page_sel_ar[1];
	
	$int_page_int_ar = explode("_", $int_page_int);
	if (count($int_page_int_ar) == 2){
		$int_page_id = $int_page_int_ar[0];
		$make_id = $int_page_int_ar[1];
	}else{
		$int_page_id = $int_page_int;
		$make_id = 0;
	}


	$page_url = "";
}else{
	 $int_page_id = 0;
	 $int_page_tp = "";
	 $make_id = 0;
}

$_SESSION["postmessage"] = "up";
$rback = "homepagebox.php";

// common update
$sql = "update tbl_homepage_box set name = '". $cm->filtertext($name) ."'
, description = '". $cm->filtertext($description) ."'
, link_type = '". $link_type ."'
, page_url = '". $cm->filtertext($page_url) ."'
, int_page_id = '". $int_page_id ."' 
, make_id = '". $make_id ."' 
, int_page_tp = '". $int_page_tp ."'
, new_window = '". $new_window ."' where id = '".$ms."'";
$db->mysqlquery($sql);
// end 

$imw = $cm->box1_width;
$imh = $cm->box1_height;

//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$filename_tmp = $_FILES['imgpath']['tmp_name'];
	$filename = $fle->uploadfilename($filename);	
	$filename1 = $ms."homeboximage".$filename;
	
	$target_path_main = "../homeboximage/";

    //slider image
    $target_path = $target_path_main;
    $r_width = $imw;
    $r_height = $imh;
    $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

	$fle->filedelete($filename_tmp);
	$sql = "update tbl_homepage_box set imagepath = '".$cm->filtertext($filename1)."' where id = '".$ms."'";
	$db->mysqlquery($sql);
}
//end

header('Location:'.$rback);	
?>