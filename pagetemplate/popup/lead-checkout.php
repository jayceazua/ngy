<?php
$bdr = "../";
$pageid = 0;
$loggedin_member_id = $yachtclass->loggedin_member_id();
$id = round($_REQUEST['id'], 0);
$yid = round($_REQUEST['yid'], 0);
$servicerequest = round($_REQUEST["servicerequest"], 0);

if ($loggedin_member_id > 0){
    $user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
    $email = $user_det[0]['email'];
    $fullname = $user_det[0]['fullname'];
}
include("head.php");
echo $frontend->display_lead_checkout_form(array("brokerid" => $id, "boatid" => $yid, "servicerequest" => $servicerequest, "frompopup" => 1));
include("foot.php");
?>