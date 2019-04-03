<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);

//image section - edit
$yachtclass->edit_yacht_video();
//end

header('Location: yacht_video.php?id='.$ms);
?>