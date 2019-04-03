<?php
$chkid = round($_REQUEST["chkid"], 0);
$popopt = round($_REQUEST["popopt"], 0);
include("head.php");
?>
    <div class="login-page">
        <div class="left-side">
            <h1>Sign-In</h1>
            <form id="login_ff" name="m_lgn" action="<?php echo $cm->folder_for_seo ;?>" method="post">
                <input type="hidden" id="chkid" name="chkid" value="<?php echo $chkid; ?>" />
                <input type="hidden" id="popopt" name="popopt" value="<?php echo $popopt; ?>" />
                <input type="hidden" id="frompopup" name="frompopup" value="1" />
                <input type="hidden" id="fcapi" name="fcapi" value="accountlogin" />
                <ul class="form">
                    <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
                        <li>
                            <div class="errormessage">
                                Sorry. Invalid username/password
                            </div>
                        </li>
                        <?php $_SESSION["fr_postmessage"] = ""; } ?>
                    <li>
                        <p>Username</p>
                        <input type="text" id="t1" name="t1" value="<?php echo $cook_t1; ?>" class="input" />
                    </li>
                    <li>
                        <p>Password</p>
                        <input type="password" id="t2" name="t2" value="<?php echo $cook_t2; ?>" class="input" />
                    </li>
                    <li>
                        <div class="forgot"><a class="fpassword" href="javascript:void(0);">Forgot Password?</a></div>
                    </li>
                    <li class="align-right">
                        <div class="left"><input type="checkbox" value="y" id="log_remember_me" name="log_remember_me" /> Remember me on this computer</div>  <input type="submit" value="Login" class="button" />
                    </li>
                    
                    <li>
                        <div class="forgot"><a href="<?php echo $cm->get_page_url(0, "register"); ?>" target="_blank"><strong>New to <?php echo $cm->sitename; ?>? Register</strong></a></div>
                    </li>
                </ul>
            </form>
            <div class="forgotpassword_info"></div>
        </div>
    </div>
<?php
include("foot.php");
?>