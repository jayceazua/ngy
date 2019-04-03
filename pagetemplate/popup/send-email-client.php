<?php
$yachtclass->loggedin_broker_icon_permission(1);
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

$details_url = $cm->get_page_url($id, "yacht");
$details_url = $cm->site_url . '/' . ltrim($details_url, $bdir);
if ($message == ""){
	$message = 'Take a look at the attached boat listing.';	
}
$subject = $yachtclass->yacht_name($id);

include("head.php");
?>
<h1>Email Client: Custom Labeled</h1>
<p>Listing brochure includes your company logo, name, and direct contact information.</p>
 <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="email_client_ff" name="ff">
        <input type="hidden" value="<?php echo $lno; ?>" id="lno" name="lno" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="emailclientsubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>            
                       
            <li>
                <p>Client Email</p>
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
            <li>
                <button type="submit" class="button" value="Send">Send</button>
            </li>
        </ul>
    </form>
<?php
include("foot.php");
?>