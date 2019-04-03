<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$sql = "select * from tbl_yacht";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
    foreach($row AS $key => $val){
		${$key} = $cm->filtertextdisplay($val);
	}
	
	$m1 = $yachtclass->yacht_name($id);
	$m2 = $cm->get_sort_content_description($overview, 350);
	
	$sqla = "update tbl_yacht set m1 = '". $cm->filtertext($m1) ."'
	, m2 = '". $cm->filtertext($m2) ."'
	, m3 = '". $cm->filtertext($m3) ."' where id = '". $id ."'";
	$db->mysqlquery($sqla);
}
?>