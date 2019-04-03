<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$ms = round($_POST["ms"], 0);

//image section - edit
$yachtclass->edit_yacht_attachment_file();
//end

header('Location: yacht_attachment.php?id='.$ms);
?>