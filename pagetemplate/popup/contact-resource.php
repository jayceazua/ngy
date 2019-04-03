<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();

$id = round($_REQUEST['id'], 0);
$result = $yachtclass->check_resource_with_return($id, 1);
$row = $result[0];
$company_name = $row["company_name"];

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
<h1>Contact <?php echo $company_name; ?></h1>
    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="contact_roker_ff" name="ff">
        <input type="hidden" value="<?php echo $id; ?>" id="id" name="id" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="contactresourcesubmit" />
        <ul class="form">
            <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                <li>
                    <div class="errormessage"><?php echo $_SESSION["fr_postmessage"]; ?></div>
                </li>
            <?php $_SESSION["fr_postmessage"] = ""; } ?>

            <li>
                <p>Name</p>
                <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" class="input" />
            </li>
            <li>
                <p>Email Address</p>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="input" />
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