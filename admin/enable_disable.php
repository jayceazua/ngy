<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$id = $_REQUEST["id"];
$tbl_field = $_REQUEST["tbl_field"];
$tbl_name = $_REQUEST["tbl_name"];
$ch_opt = $_REQUEST["ch_opt"];
$wh_field = $_REQUEST["wh_field"];
$where_to_go = $_REQUEST["where_to_go"];

if ($tbl_name == "tbl_user" AND $tbl_field == "status_id"){
    $old_value = $db->total_record_count("select status_id as ttl from ". $tbl_name ." where id = '". $id ."'");
    $em_id = $ch_opt;
}

$sql = "update ". $tbl_name ." set ". $tbl_field ." = '". $ch_opt ."' where ". $wh_field ." = '". $id ."'";
$db->mysqlquery($sql);

if ($tbl_name == "tbl_user" AND $tbl_field == "admin_access" AND $id == $loggedin_member_id AND $ch_opt == 0){
	session_destroy();
	header('Location: index.php');
	exit;
}

if ($tbl_name == "tbl_yacht"){
	if ($ch_opt == 1){
		$sql = "update ". $tbl_name ." set sold_date = '0000-00-00', display_upto = '". $cm->default_future_date ."' where id = '". $id ."'";
		$db->mysqlquery($sql);
	}
}

if ($tbl_name == "tbl_user"){
    //if ($old_value != $new_value){
        //$yachtclass->send_user_email($id, $em_id);
    //}
}

if ($tbl_name == "tbl_sub_admin"){
	$em_id = $ch_opt;
    $yachtclass->send_user_email($id, $em_id, 1);
}

if ($tbl_name == "tbl_company" AND $tbl_field == "status_id"){	
	if ($ch_opt == 1){
		//active
		$yachtclass->update_location_status(1, 'company_id', $id, 2);
		$yachtclass->update_user_status(2, 'company_id', $id, 3);
		$yachtclass->update_yacht_status(1, 'company_id', $id, 2);
	}
	
	if ($ch_opt == 2){
		//inactive
		$yachtclass->update_location_status(2, 'company_id', $id, 1);
		$yachtclass->update_user_status(3, 'company_id', $id, 2);
		$yachtclass->update_yacht_status(2, 'company_id', $id, 1);
	}	
}

if ($tbl_name == "tbl_location_office" AND $tbl_field == "status_id"){	
	if ($ch_opt == 1){
		//active		
		$yachtclass->update_user_status(2, 'location_id', $id, 3);
		$yachtclass->update_yacht_status(1, 'location_id', $id, 2);
	}
	
	if ($ch_opt == 2){
		//inactive		
		$yachtclass->update_user_status(3, 'location_id', $id, 2);
		$yachtclass->update_yacht_status(2, 'location_id', $id, 1);
	}	
}

if ($tbl_name == "tbl_brand_specific"){
	if ($ch_opt == 1){
		$sql = "update tbl_brand_specific set inside_top_nav = 0 where id != '".$id."'";
		$db->mysqlquery($sql);
	}
}

if ($where_to_go == 2){
header('Location:'.$_SERVER["HTTP_REFERER"]);
}else{
header('Location:'.$_SESSION["bck_pg"]);
}
?>