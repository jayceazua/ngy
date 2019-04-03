<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$f_question = $_POST["f_question"];
$f_answer = $_POST["f_answer"];
$status_id = round($_POST["status_id"], 0);
$oldrank = round($_POST["oldrank"], 0);
$ms = round($_POST["ms"], 0);

if ($ms == 0){
	$rank = $db->total_record_count("select max(rank) as ttl from tbl_faq") + 1;
	$sql = "insert into tbl_faq (f_question) values ('". $cm->filtertext($f_question) ."')";
	$iiid = $db->mysqlquery_ret($sql);
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_faq.php";
}else{
	$rank = round($_POST["rank"], 0);
    $sql = "update tbl_faq set f_question = '". $cm->filtertext($f_question) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// common update
$sql = "update tbl_faq set f_answer = '". $cm->filtertext($f_answer) ."'
, status_id = '". $status_id ."'
, rank = '".$rank."' where id = '". $iiid ."'";
$db->mysqlquery($sql);
// end 

// update the rank
$tablenm = "tbl_faq";
$wherecls = " id != '".$iiid."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

header('Location:'.$rback);	
?>