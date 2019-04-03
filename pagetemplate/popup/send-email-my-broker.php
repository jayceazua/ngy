<?php
$yachtclass->loggedin_consumer_icon_permission(1);
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

$m = $yachtclass->yacht_name($id);
$datastring = $cm->session_field_contact_my_broker();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
    ${$key} = $val;
}

if ($subject == ""){
    $subject = $m;
}

$details_url = $cm->get_page_url($id, "yacht");
$details_url = $cm->site_url . '/' . ltrim($details_url, $bdir);
if ($message == ""){
	$message = 'Take a look at this boat I found on YachtMarketer.' . "\n\n" . $details_url;	
}
include("head.php");
?>

    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="contact_my_broker_ff" name="ff">
        <input type="hidden" value="<?php echo $lno; ?>" id="lno" name="lno" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="sendemailmybrokersubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>
           
            <li>
                <p>Subject</p>
                <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" class="input" />
            </li>
            <li>
                <p>Message</p>
                <textarea name="message" id="message" rows="1" cols="1" class="comments"><?php echo $message;?></textarea>
            </li>
            <li>
                <p><input type="checkbox" id="sendmecopy" name="sendmecopy" value="1" <?php if ($sendmecopy == 1){?> checked="checked"<?php } ?> />&nbsp;&nbsp; Send me a copy&nbsp;</p>                    
            </li>
            <li>
                <button type="submit" class="button" value="Send">Send</button>
            </li>

        </ul>
    </form>
<?php
include("foot.php");
?>