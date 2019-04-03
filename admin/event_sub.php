<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$reg_date = $_POST["reg_date"];
$name = $_POST["name"];
$description = $_POST["description"];
$small_description = $_POST["small_description"];
$status_id = round($_POST["status_id"], 0);
$ms = round($_POST["ms"], 0);
$reg_date_a = $cm->set_date_format($reg_date);

if ($ms == 0){
	$sql = "insert into tbl_event (name) values ('". $cm->filtertext($name) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_event.php";
}else{
    $sql = "update tbl_event set name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}
$slug = $cm->create_slug($name);
if ($small_description == ""){ $small_description = $cm->get_sort_content_description($description, 250); }
// common update
$sql = "update tbl_event set slug = '". $cm->filtertext($slug) ."'
, description = '". $cm->filtertext($description) ."'
, small_description = '". $cm->filtertext($small_description) ."'
, status_id = '". $status_id ."'
, reg_date = '". $cm->filtertext($reg_date_a) ."' where id = '". $iiid ."'";
$db->mysqlquery($sql);
// end 

//blog image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
	if ($wh_ok == "y"){
		$filename_tmp = $_FILES['imgpath']['tmp_name'];
		$filename = $fle->uploadfilename($filename);
		$filename1 = $iiid."event".$filename;

		$target_path_main = "blogimage/";
		$target_path_main = "../" . $target_path_main;
		
		//image
		$r_width = $cm->blog_im_width;
		$r_height = $cm->blog_im_height;
		$target_path = $target_path_main;
		$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

		$fle->filedelete($filename_tmp);
		$sql = "update tbl_event set blog_image = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
		$db->mysqlquery($sql);
	}
}
//end

header('Location:'.$rback);	
?>