<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);
$adm->sub_admin_insert_update();

if ($ms == 0){
    $_SESSION["postmessage"] = "nw"; 
    $rback = "mod_sub_admin.php";
}else{
    $_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}
if ($rback == ""){ $rback = "mod_sub_admin.php"; }
header('Location:'.$rback);
?>