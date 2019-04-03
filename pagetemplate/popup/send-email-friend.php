<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$lno = round($_REQUEST['lno'], 0);
$result = $yachtclass->check_yacht_with_return($lno, 1);
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

$datastring = $cm->session_field_refer_friend();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
    ${$key} = $val;
}

if ($loggedin_member_id > 0 AND $email == ""){
    $user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
    $email = $user_det[0]['email'];
    $fullname = $user_det[0]['fullname'];
}

$details_url = $cm->get_page_url($id, "yacht");
$details_url = $cm->site_url . '/' . ltrim($details_url, $bdir);
if ($message == ""){
	$message = $db->total_record_count("select pdes as ttl from tbl_system_email where id = 11");
	$message = str_replace("#companyname#", $cm->sitename, $message);
	$message = str_replace("#linkurl#", $details_url, $message);	
	//$message = 'Take a look at this boat I found on '. $cm->sitename .'.' . "\n\n" . $details_url;	
}
$subject = $yachtclass->yacht_name($id);

include("head.php");
?>
<h1>Email A Friend</h1>
 <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="email_friend_ff" name="ff">
        <input type="hidden" value="<?php echo $lno; ?>" id="lno" name="lno" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="sendemailfriendsubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>
            
            <li>
                <p>From Email</p>
                <input type="text" id="femail" name="femail" value="<?php echo $email; ?>" class="input" />
            </li>
            <li>
                <p>From Name</p>
                <input type="text" id="fname" name="fname" value="<?php echo $fullname; ?>" class="input" />
            </li>            
            <li>
                <p>Send To Email</p>
                <input type="text" id="stemail" name="stemail" value="<?php echo $email; ?>" class="input" />
            </li>            
            
            <li>
                <p>Subject</p>
                <strong><?php echo $subject; ?></strong>
            </li>
            <li>
                <p>Message</p>
                <textarea name="message" id="message" rows="1" cols="1" class="comments"><?php echo $message;?></textarea>
            </li>
            <li>
                <p><input type="checkbox" id="sendmecopy" name="sendmecopy" value="1" <?php if ($sendmecopy == 1){?> checked="checked"<?php } ?> />&nbsp;&nbsp; Send me a copy&nbsp;</p>                    
            </li>
            <li><?php echo $captchaclass->call_captcha(); ?></li>
            <li>
                <button type="submit" class="button" value="Send">Send</button>
            </li>
        </ul>
    </form>
<?php
include("foot.php");
?>