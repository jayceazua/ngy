<?php
$r = round($_REQUEST["r"], 0);
$p = round($_REQUEST["p"], 0);
include("head.php");
?>
<div class="fc-formsubmit-container2">
    <i class="far fa-check-circle"><span class="com_none">Success</span></i>
    
    <h2>Thank You</h2>
    <p>Your Enquiry has been sent.<br />We will get back to you asap.</p>
    <hr/>
	<p>If you would like to improve your browsing experience and save your preferences, <a href="<?php echo $cm->get_page_url(0, "register"); ?>">sign up</a> or <a href="<?php echo $cm->get_page_url(0, "login"); ?>">log in</a> to your account.</p>
</div>
<?php
include("foot.php");
?>