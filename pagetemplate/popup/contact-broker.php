<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();

$id = round($_REQUEST['id'], 0);
$yid = round($_REQUEST['yid'], 0);
$yachtclass->check_user_exist($id, 0, 1);

$m = $yachtclass->yacht_name($yid);

$datastring = $cm->session_field_contact_broker();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
    ${$key} = $val;
}

if ($loggedin_member_id > 0 AND $email == ""){
    $user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
    $email = $user_det[0]['email'];
    $fullname = $user_det[0]['fullname'];
}

if ($subject == ""){
    $subject = $m;
}

$default_message = '';
include("head.php");
?>
    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="contact_roker_ff" name="ff">
        <input type="hidden" value="<?php echo $id; ?>" id="id" name="id" />
        <input type="hidden" value="<?php echo $yid; ?>" id="yid" name="yid" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="contactbrokersubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>

            <li>
                <p>Your Name</p>
                <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" class="input" />
            </li>
            <li>
                <p>Your Email Address</p>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="input" />
            </li>
			<li>
                <p>Your Phone Number</p>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
            </li>
            <li>
                <p>Subject</p>
                <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" class="input" />
            </li>
            <li>
                <p>Message</p>
                <textarea name="message" id="message" rows="1" cols="1" class="comments" placeholder="<?php echo $default_message; ?>"><?php echo $message;?></textarea>
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