<?php
$frontend->go_to_account();
$pageid = 0;
$registerpg = "y";
$main_heading = "y";
$datastring = $cm->session_field_user();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$old_status_id = 0;
$old_d_username = "";
$ms = round($_SESSION["usernid"], 0);
$link_name = $atm1 = "Member Sign Up";
$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

if ($country_id == ""){ $country_id = 1; }
if ($type_id == ""){ $type_id = 6; }
if ($country_id == 1 OR $country_id == 2){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";  
    $state_s2 = "com_none";
}

if ($type_id == 2){
	$b_admin = '';
	$b_consumer = ' com_none';
}

if ($type_id == 6){
	$b_consumer = '';
	$b_admin = ' com_none';
}

$breadcrumb = 1;
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

$broker_box = $cm->get_common_field_name('tbl_box_content', 'pdes', 1);
$consumer_box = $cm->get_common_field_name('tbl_box_content', 'pdes', 2);
include($bdr."includes/head.php");
?>
<div class="registration-detail clearfixmain">
    	<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="user_ff" name="ff" enctype="multipart/form-data">
        <input type="hidden" value="" name="old_d_username" />
        <input type="hidden" value="" name="old_d_email" />
        <input type="hidden" value="0" name="old_status_id" />
        <input type="hidden" value="0" name="ms" />
        <input type="hidden" value="6" name="type_id" id="type_id" />
        <input type="hidden" value="1" name="sendemailinfo" />
        <input class="finfo" id="email2" name="email2" type="text" />
        <input type="hidden" id="fcapi" name="fcapi" value="usersubmit" />
    
        <?php if ($_SESSION["fr_postmessage"] != ""){ ?>
        <div class="singleblock">
            <ul class="form">
                <li>
                    <div class="errormessage">
                        <?php echo $_SESSION["fr_postmessage"]; ?>
                    </div>
                </li>
            </ul>
        </div>
        <?php $_SESSION["fr_postmessage"] = ""; } ?>
    
        <div class="singleblock">        
            <div class="singleblock_box">
                <ul class="form">
                    <li class="left">
                        <p>Username<span title="" id="checkvaliddatares1" class="iconwh">&nbsp;</span></p>
                        <input type="text" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata input" />
                    </li>
                    <li class="right">
                        <p>Email Address<span title="" id="checkvaliddatares2" class="iconwh">&nbsp;</span></p>
                        <input type="text" id="d_email" name="d_email" value="<?php echo $d_email; ?>" currentval="<?php echo $old_d_email; ?>" fieldopt="2" class="checkvaliddata input" />
                    </li>
                </ul>
                <ul class="form">
                    <li class="left">
                        <p>Password [min 6 chars]</p>
                        <input type="password" id="d_password" name="d_password" value="<?php echo $d_password; ?>" class="input" />
                    </li>    
                    <li class="right">
                        <p>Confirm Password</p>
                        <input type="password" id="cd_password" name="cd_password" value="<?php echo $d_password; ?>" class="input" />
                    </li>
                    
                    
                    <li class="left">
                        <p>First Name</p>
                        <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" class="input" />
                    </li>
                    <li class="right">
                        <p>Last Name</p>
                        <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" class="input" />
                    </li>
                    
                    <li class="left">
                        <p>Mobile Phone</p>
                        <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
                    </li>
                    
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    
        <div class="singleblock b_admin<?php echo $b_admin; ?>">
            <div class="singleblock_heading"><span>Company Information</span></div>
            <div class="singleblock_box">
                <ul class="form">
                    <li class="left">
                        <p>Company Name</p>
                        <input type="text" id="cname" name="cname" value="<?php echo $cname; ?>" class="input" />
                    </li>
                    <li class="right">
                        <p>Website URL</p>
                        <input type="text" id="website_url" name="website_url" value="<?php echo $website_url; ?>" class="input" />
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>  
    	
        <div class="singleblock">
        <?php
		echo $captchaclass->call_captcha();
		?>
        </div>
    
        <div class="singleblock">
            <input type="submit" value="Submit" class="button" />
        </div>
    </form> 
</div>
<?php 
include($bdr."includes/foot.php");
?>