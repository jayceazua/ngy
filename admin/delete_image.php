<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$id = $_REQUEST["id"];
$tbl_field = $_REQUEST["tbl_field"];
$tbl_name = $_REQUEST["tbl_name"];
$wh_field = $_REQUEST["wh_field"];
$foldernm = $_REQUEST["foldernm"];

$flnm = $db->total_record_count("select ". $tbl_field ." as ttl from ". $tbl_name ." where ". $wh_field ." = '". $id ."'");
$sql = "update ". $tbl_name ." set ". $tbl_field ." = '' where ". $wh_field ." = '". $id ."'";
$db->mysqlquery($sql);
if ($flnm != ""){
	$t_img = "../".$foldernm."/" . $flnm;
	$fle->filedelete($t_img);
}
if ($tbl_name == "tbl_user"){ 
	if ($flnm != ""){
		$t_img = "../".$foldernm."/big/" . $flnm;
		$fle->filedelete($t_img);
		
		$t_img = "../".$foldernm."/original/" . $flnm;
		$fle->filedelete($t_img);
	} 
}
if ($tbl_name == "tbl_image_slider"){ 
	$original_img = "../".$foldernm."/original/" . $flnm;
	if (file_exists($original_img)){
		$fle->filedelete("../".$foldernm."/original/".$flnm);
	}
}
if ($tbl_name == "tbl_blog"){ 
	$t_img = "../".$foldernm."/thumb/" . $flnm;
	if (file_exists($t_img)){
		$fle->filedelete($t_img);
	}
}
if ($tbl_name == "tbl_page"){ 
	$menu_imgpath = "../".$foldernm."/big/" . $flnm;
	if (file_exists($original_img)){
		$fle->filedelete("../".$foldernm."/big/".$flnm);
	}
}
header('Location:'.$_SERVER["HTTP_REFERER"]);
?>