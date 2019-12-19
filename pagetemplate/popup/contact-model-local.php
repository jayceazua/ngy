<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$m = round($_REQUEST["m"], 0);
$model_name = $modelclass->get_model_name($m);
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

include("head.php");
?>
<h2><?php echo $model_name; ?></h2>
<p>Please tell us everything you want us to know about your yacht and tender needs.</p>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="contact_roker_ff" name="ff">
    <input type="hidden" value="<?php echo $m; ?>" id="yid" name="yid" />
    <input class="finfo" id="email2" name="email2" type="text" />
    <input type="hidden" id="fcapi" name="fcapi" value="contactmodellocalsubmit" />
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
            <textarea name="message" id="message" rows="1" cols="1" class="comments"><?php echo $message;?></textarea>
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