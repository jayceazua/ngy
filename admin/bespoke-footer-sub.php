<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$name = $_POST["name"];
$include_reglink = round($_POST["include_reglink"], 0);
$status_id = round($_POST["status_id"], 0);
$oldrank = round($_POST["oldrank"], 0);
$ms = round($_POST["ms"], 0);

if ($ms == 0){
	$rank = $db->total_record_count("select max(rank) as ttl from tbl_bespoke_footer") + 1;
	$sql = "insert into tbl_bespoke_footer (name) values ('". $cm->filtertext($name) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod-bespoke-footer.php";
}else{
	$rank = round($_POST["rank"], 0);
    $sql = "update tbl_bespoke_footer set name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// common update
$sql = "update tbl_bespoke_footer set include_reglink = '". $include_reglink ."'
, status_id = '". $status_id ."'
, rank = '".$rank."' where id = '".$iiid."'";
$db->mysqlquery($sql);
// end 

// update the rank
$tablenm = "tbl_bespoke_footer";
$wherecls = " id != '".$iiid."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

$adm->bespoke_footer_assign( $iiid );

header('Location:'.$rback);	
?>