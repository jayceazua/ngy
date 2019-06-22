<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$t_found = round($_POST["t_found"], 0);
$tblname = $_POST["tblname"];

$wh_field = "id";

for ($k = 0; $k < $t_found; $k++){
 $id = $_POST["id" . $k];
 $sortorder = round($_POST["sortorder" . $k], 0);
 
 if ($sortorder > 0){
   $sql = "update ". $tblname ." set rank = '". $sortorder ."' where ". $wh_field ." = '". $id ."'";
   $db->mysqlquery($sql);	
 }
}
$_SESSION["postmessage"] = "stordr";
header('Location:'.$_SESSION["bck_pg"]);
?>