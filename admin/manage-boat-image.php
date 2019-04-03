<?php
include("common.php");

$p = round($_REQUEST["p"], 0);
$dcon = 10;
$page = ($p - 1) * $dcon;

$sql = "select id, listing_no from tbl_yacht order by id  LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
	$id = $row['id'];
	$listing_no = $row['listing_no'];
	
	//create folder
	$source = "../yachtimage/rawimage";
	$destination = "../yachtimage1/".$listing_no;
	$fle->copy_folder($source, $destination);
	
	//collect images
	$sql_im = "select imgpath from tbl_yacht_photo where yacht_id = '". $id ."'";
	$result_im = $db->fetch_all_array($sql_im);
	foreach($result_im as $row_im){
		$imgpath = $row_im['imgpath'];
		if ($imgpath != ""){
			
			$file = "../yachtimage/" . $imgpath;
			$newfile = "../yachtimage1/" .$listing_no . "/" . $imgpath;
			copy($file, $newfile);
			
			$file = "../yachtimage/big/" . $imgpath;
			$newfile = "../yachtimage1/" .$listing_no . "/big/" . $imgpath;
			copy($file, $newfile);
			
			$file = "../yachtimage/bigger/" . $imgpath;
			$newfile = "../yachtimage1/" .$listing_no . "/bigger/" . $imgpath;
			copy($file, $newfile);
			
			$file = "../yachtimage/slider/" . $imgpath;
			$newfile = "../yachtimage1/" .$listing_no . "/slider/" . $imgpath;
			copy($file, $newfile);
		}
	}
}
?>