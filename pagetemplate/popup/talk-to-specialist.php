<?php
$bdr = "../";
$pageid = 0;
$loggedin_member_id = $yachtclass->loggedin_member_id();
$make_id = round($_REQUEST['make_id'], 0);

if ($loggedin_member_id > 0){
    $user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
    $email = $user_det[0]['email'];
    $fullname = $user_det[0]['fullname'];
}
include("head.php");
echo $frontend->display_talk_to_specialist_form(array("make_id" => $make_id, "shortversion" => 3, "frompopup" => 1));
include("foot.php");
?>