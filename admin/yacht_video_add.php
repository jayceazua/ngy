<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$iiid = round($_POST["ms"], 0);
$yachtclass->insert_yacht_video_link($iiid);
header('Location: yacht_video.php?id='.$iiid);
?>