<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);

$datastring = $cm->session_field_common_feedback();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
    ${$key} = $val;
}

if ($loggedin_member_id > 0 AND $email == ""){
    $user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email, phone', $loggedin_member_id);
    $email = $user_det[0]['email'];
    $fullname = $user_det[0]['fullname'];
	$phone = $user_det[0]['phone'];
	$company_name = $yachtclass->get_broker_company_name($loggedin_member_id);
}
include("head.php");
?>
<h1>Feedback</h1>

    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="common_feedback_ff" name="ff">
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="commonfeedbacksubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>

            <li class="left">
                <p>Your Name</p>
                <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" class="input" />
            </li>
            <li class="right">
                <p>Your Email Address</p>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="input" />
            </li>
            
            <li class="left">
                <p>Phone</p>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
            </li>
            <li class="right">
                <p>Company Name</p>
                <input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" class="input" />
            </li>
            <li>
                <p>Subject</p>
                <input type="text" id="subject" name="subject" value="<?php echo $subject; ?>" class="input" />
            </li>
            <li>
                <p>Message</p>
                <textarea name="message" id="message" rows="1" cols="1" class="comments"><?php echo $message;?></textarea>
            </li>
            <li>
                <button type="submit" class="button" value="Send">Send</button>
            </li>

        </ul>
    </form>
<?php
include("foot.php");
?>