<?php
//include("../editor/fckeditor.php") ;
//$sBasePath = "../editor/";
include("../ckeditor/ckeditor.php");
include_once '../ckeditor/ckfinder/ckfinder.php';
$sBasePath = "../ckeditor/";
$bdir = $bdr;

$newpagename = basename($_SERVER["PHP_SELF"]);
$admin_query_string = $_SERVER['QUERY_STRING'];
$ref_page = $_SERVER["HTTP_REFERER"];

if ($newpagename != "index.php" AND $newpagename != "thanks.php" AND $newpagename != "sorry.php"){  
  if ($query_string != ""){
     $_SESSION["ad_file_name"] = $newpagename."?".$query_string;
  }else{
     $_SESSION["ad_file_name"] = $newpagename;
  }	 
}
if ($call_function == "a"){ $adm->admin_login(); }
if ($call_function == "b"){ $adm->go_to_admin_account(); }

//default meta information
if ($def_meta_collect == "y"){
	$d_m1 = $db->total_record_count("select m1 as ttl from tbl_tag where id = 1");
	$d_m2 = $db->total_record_count("select m2 as ttl from tbl_tag where id = 1");
	$d_m3 = $db->total_record_count("select m3 as ttl from tbl_tag where id = 1");
	
	//Make Meta
	$default_meta = $makeclass->default_meta_info_make();
	$mk_m1 = $default_meta->m1;
	$mk_m2 = $default_meta->m2;
	$mk_m3 = $default_meta->m3;
}
//end 
?>