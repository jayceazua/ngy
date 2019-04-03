<?php
include("common.php");
$adm->admin_login(); 

$category_id = round($_POST["category_id"], 0);
$code = $_POST["code"];
$name = $_POST["name"];
$pdes = $_POST["pdes"];
$field_value = $_POST["field_value"];
$rank = round($_POST["rank"], 0);

$ms = round($_POST["ms"], 0);
$oldrank = round($_POST["oldrank"], 0);
$oldcode = $_POST["oldcode"];

if ($ms == 0){
  //create session for the posted data
  $_SESSION["s_category_id"] = $category_id;
  $_SESSION["s_code"] = $cm->filtertextdisplay($code);
  $_SESSION["s_name"] = $cm->filtertextdisplay($name);
  $_SESSION["s_pdes"] = $cm->filtertextdisplay($pdes);
  $_SESSION["s_field_value"] = $cm->filtertextdisplay($field_value);
  $_SESSION["s_rank"] = $rank;
  $red_pg = "add-sysvar.php?category_id=" . $category_id;
}

if (trim($code) == ""){
    $_SESSION["postmessage"] = "blnk";
	header('Location: '.$red_pg);
    exit;
}

if ($oldcode != $code){
	$found = $db->total_record_count("select count(*) as ttl from tbl_systemvar where code = '" . $cm->filtertext($code) . "'");
	if ($found > 0){
	  $_SESSION["postmessage"] = "ext"; 
	  header('Location: '.$red_pg);
	  exit;
	}
}

if ($ms == 0){
// for insert record	
	$sql = "insert into tbl_systemvar (category_id) values ('". $category_id ."')";
    $iiid = $db->mysqlquery_ret($sql); 
	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod-sysvar.php?categoryid=" . $category_id;
	
	$_SESSION["s_category_id"] = "";
    $_SESSION["s_code"] = "";
    $_SESSION["s_name"] = "";
    $_SESSION["s_pdes"] = "";
    $_SESSION["s_field_value"] = "";
    $_SESSION["s_rank"] = "";
}else{
// for update record	
	$sql = "update tbl_systemvar set category_id = '". $category_id ."' where id='".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

// common update
   $sql = "update tbl_systemvar set 
    code='".$cm->filtertext($code)."',
	name='".$cm->filtertext($name)."',
   	pdes='".$cm->filtertext($pdes)."',
	field_value='".$cm->filtertext($field_value)."',
	rank='".$rank."' where id='".$iiid."'";
	$db->mysqlquery($sql);
// end 

// update the rank
$tablenm = "tbl_systemvar";
$wherecls = " id != '".$iiid."' and category_id = '". $category_id ."'";
$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
//end

header('Location:'.$rback);
?>