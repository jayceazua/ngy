<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$reg_date = $_POST["reg_date"];
$name = $_POST["name"];
$company_name = $_POST["company_name"];
$designation = $_POST["designation"];
$website_url = $_POST["website_url"];
$description = $_POST["description"];
$broker_id = round($_POST["broker_id"], 0);
$featured = round($_POST["featured"], 0);
$status_id = round($_POST["status_id"], 0);
$rating = round($_POST["rating"], 0);
$ms = round($_POST["ms"], 0);
$website_url = $cm->format_url_txt($website_url);

if ($featured != 1){ $featured = 0; }
$reg_date_a = $cm->set_date_format($reg_date);

if ($ms == 0){	
	$sql = "insert into tbl_testimonial (name) values ('". $cm->filtertext($name) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_testimonial.php";
}else{
    $sql = "update tbl_testimonial set name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// common update
$small_description = $cm->get_sort_content_description($description, 450);
$sql = "update tbl_testimonial set description = '". $cm->filtertext($description) ."'
, small_description = '". $cm->filtertext($small_description) ."'
, company_name = '". $cm->filtertext($company_name) ."'
, designation = '". $cm->filtertext($designation) ."'
, website_url = '". $cm->filtertext($website_url) ."'
, featured = '". $featured ."'
, broker_id = '". $broker_id ."'
, status_id = '". $status_id ."'
, reg_date = '". $cm->filtertext($reg_date_a) ."'
, rating = '". $rating ."' where id = '". $iiid ."'";
$db->mysqlquery($sql);
// end 

//image upload
$filename = $_FILES['imgpath']['name'] ;
if ($filename != ""){
	$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
	if ($wh_ok == "y"){
		$filename_tmp = $_FILES['imgpath']['tmp_name'];
		$filename = $fle->uploadfilename($filename);	
		$filename1 = $iiid."testimonial".$filename;
		
		$target_path_main = "../testimonialimage/";
	
		//client image
		$target_path = $target_path_main;
		$r_width = $cm->testimonial_im_width;
		$r_height = $cm->testimonial_im_height;
		$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
	
		$fle->filedelete($filename_tmp);
		$sql = "update tbl_testimonial set imgpath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
		$db->mysqlquery($sql);
	}
}
//end

header('Location:'.$rback);	
?>