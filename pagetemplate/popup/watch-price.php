<?php
$bdr = "../";
$pageid = 0;
$boat_id = round($_REQUEST['boat_id'], 0);
include("head.php");
echo $frontend->display_watch_price_form(array("boat_id" => $boat_id, "pgid" => 58, "frompopup" => 1));
include("foot.php");
?>