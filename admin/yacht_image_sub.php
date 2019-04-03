<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);

//image section - edit
$yachtclass->edit_yacht_image();
//end

header('Location: yacht_image.php?id='.$ms);
?>