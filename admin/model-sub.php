<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$returnar = $modelclass->model_insert_update();
$returnar = json_decode($returnar);
$ms = $returnar->ms;
$make_id = $returnar->make_id;

if ($ms == 0){
	$_SESSION["postmessage"] = "nw"; 
    $rback = "mod-model.php?make_id=" . $make_id;
}else{
	$_SESSION["postmessage"] = "up";
	$rback = $_SESSION["bck_pg"];
}
header('Location:'.$rback);
?>