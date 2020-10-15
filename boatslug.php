<?php
include("includes/common.php");
$sql = "select * from tbl_yacht where boat_slug is NULL  order by id";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
	$boatid = $row['id'];
	//$boat_slug_x = $row['boat_slug'];
	
	//if ($boat_slug_x == ""){
		$boat_slug = $yachtclass->create_boat_slug($boatid);	
		$sql2 = "update tbl_yacht set boat_slug = '". $cm->filtertext($boat_slug) ."' where id = '". $boatid ."'";
		$db->mysqlquery($sql2);
		
		echo "<p>". $sql2 ."</p>";
	//}
}
?>