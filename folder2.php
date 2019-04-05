<?php
include("includes/common.php");
$sql = "select * from tbl_model order by id";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
	$iiid = $row['id'];
	$source = "models/rawfolder";
	$destination = "models/".$iiid;
	$fle->copy_folder($source, $destination);
	
	echo '<p>'. $iiid .'</p>';
}
?>