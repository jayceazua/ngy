<?php
include("includes/common.php");
$sql = "select * from tbl_yacht order by id";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
	$listing_no = $row['listing_no'];
	$source = "yachtimage/rawimage";
	$destination = "yachtimage/".$listing_no. "";
	$fle->copy_folder($source, $destination);
	
	echo '<p>'. $listing_no .'</p>';
}
?>