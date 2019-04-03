<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$include_fck = 1;
$registerpg = "y";
$isdashboard = 1;

$datastring = $cm->session_field_user();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$old_status_id = 0;
$old_d_username = "";
$ms = round($_SESSION["usernid"], 0);
$link_name = $atm1 = "Edit Profile";
$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

$sql = "select * from tbl_user where id = '". $cm->filtertext($ms) ."' and status_id = 2";
$result = $db->fetch_all_array($sql);

$row = $result[0];
$d_username = $old_d_username = htmlspecialchars($row['uid']);
$d_email = $old_d_email = htmlspecialchars($row['email']);
$d_password = htmlspecialchars($edclass->txt_decode($row['pwd']));

$title = htmlspecialchars($row['title']);
$fname = htmlspecialchars($row['fname']);	
$lname = htmlspecialchars($row['lname']); 
$phone = htmlspecialchars($row['phone']);
$office_phone_ext = htmlspecialchars($row['office_phone_ext']);
$about_me = $row['about_me']; 
$user_imgpath = htmlspecialchars($row['user_imgpath']);
$type_id = $row['type_id'];
$company_id = $row['company_id'];
$location_id = $row['location_id'];
$parent_id = $row['parent_id'];
$status_id = $old_status_id = $row['status_id'];
$display_title = $row['display_title'];

//social media
$facebook_url = htmlspecialchars($row['facebook_url']);
$twitter_url = htmlspecialchars($row['twitter_url']);
$linkedin_url = htmlspecialchars($row['linkedin_url']);
$blog_url = htmlspecialchars($row['blog_url']);
	
if ($user_imgpath != ""){
	$user_img_u_css = ' com_none';
	$user_img_d_css = '';
}

if ($country_id == ""){ $country_id = 1; }
if ($type_id == ""){ $type_id = 2; }
if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";  
    $state_s2 = "com_none";
}

if ($type_id == 2){
	$b_admin = '';
	$b_agent = ' com_none';
}

if ($type_id == 3){
	$b_agent = '';
	$b_admin = ' com_none';
}

if ($type_id == 4){
    $b_agent = ' com_none';
}

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

if ($id == $loggedin_member_id){
	$ar_m1 = 2;
	$ar_m2 = 4;
}else{
	$ar_m1 = 2;
	$ar_m2 = 1;
}

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => $ar_m1, "m2" => $ar_m2, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
echo $html_start;
?>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="user_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
    <input type="hidden" value="<?php echo $type_id; ?>" name="type_id" />
    <input type="hidden" value="<?php echo $company_id; ?>" name="company_id" />    
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
	<input type="hidden" value="0" name="sendemailinfo" />
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

    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>Login Information</span></div>
        <div class="singleblock_box singleblock_box_h clearfixmain">
            <ul class="form">
                <li>
                    <p>Username&nbsp;&nbsp;&nbsp;</span> <strong><?php echo $d_username; ?></strong></p>                    
                    <input type="hidden" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata input" />
                </li>
                <li class="left">
                    <p>Password [min 6 chars]</p>
                    <input type="password" id="d_password" name="d_password" value="<?php echo $d_password; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Confirm Password</p>
                    <input type="password" id="cd_password" name="cd_password" value="<?php echo $d_password; ?>" class="input" />
                </li>
                <?php if ($type_id == 2 OR $type_id == 3){?>
                <li class="left" id="location_id_heading">
                    <p>Office Location</p>
                    <select id="location_id<?php if ($type_id == 2){?>_m<?php } ?>" name="location_id<?php if ($type_id == 2){?>_m<?php } ?>" name="location_id<?php if ($type_id == 2){?>_m<?php } ?>" name="location_id<?php if ($type_id == 2){?>_m<?php } ?>" class="my-dropdown2">
                        <option value="" addressval="">Select Location</option>
                        <?php echo $yachtclass->get_company_location_combo($location_id, $company_id); ?>
                    </select>                    
                </li>
                <?php }else{ ?>
                <input type="hidden" value="<?php echo $location_id; ?>" name="location_id" />
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>Personal Information</span></div>
        <div class="singleblock_box singleblock_box_h clearfixmain">
            <ul class="form">
                <li class="left">
                    <p>First Name</p>
                    <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Last Name</p>
                    <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" class="input" />
                </li>

              
                <li class="left">
                    <p>Email Address<span title="" id="checkvaliddatares2" class="iconwh">&nbsp;</span></p>
                    <input type="text" id="d_email" name="d_email" value="<?php echo $d_email; ?>" currentval="<?php echo $old_d_email; ?>" fieldopt="2" class="checkvaliddata input" />
                </li>
                <li class="right">
                    <p>Mobile No</p>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
                </li>
                
                <?php
				if ($type_id == 6){
				?>                
  
                <li class="left">
                    <p>Office Phone Ext</p>
                    <input type="text" id="office_phone_ext" name="office_phone_ext" value="<?php echo $office_phone_ext; ?>" class="input" />
                </li>              
                <li class="right">
                    <div class="iupload2<?php echo $user_img_u_css; ?>">
                        <p>Select Profile Image [w: <?php echo $cm->user_im_width; ?>px, h: <?php echo $cm->user_im_height; ?>px]</p>
                        <input validval="<?php echo $cm->allow_image_ext; ?>" type="file" id="user_imgpath" name="user_imgpath" class="input" />
                        <p>[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</p>
                    </div>
                    <div class="idisplay2<?php echo $user_img_d_css; ?>">
                        <p>Selected Profile Image</p>
                        <img src="<?php echo $cm->folder_for_seo; ?>userphoto/<?php echo $user_imgpath; ?>" border="0" width="100" /><br />
                        <a class="deleteimage" targets="2" href="javascript:void(0);">Delete Image</a>
                    </div>
                </li>
                
                <?php
				}else{
				?>
                                
                <li class="left">
                    <p>Title</p>
                    <input type="text" id="title" name="title" value="<?php echo $title; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Office Phone Ext</p>
                    <input type="text" id="office_phone_ext" name="office_phone_ext" value="<?php echo $office_phone_ext; ?>" class="input" />
                </li>
                
                <li class="left">
                    <p>Want to display Title on Profile page? &nbsp;&nbsp; <input class="checkbox" type="checkbox" id="display_title" name="display_title" value="1" <?php if ($display_title == 1){?> checked="checked"<?php } ?> /></p>
                </li>            
                <li class="right">
                    <div class="iupload2<?php echo $user_img_u_css; ?>">
                        <p>Select Profile Image [w: <?php echo $cm->user_im_width; ?>px, h: <?php echo $cm->user_im_height; ?>px]</p>
                        <input validval="<?php echo $cm->allow_image_ext; ?>" type="file" id="user_imgpath" name="user_imgpath" class="input" />
                        <p>[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</p>
                    </div>
                    <div class="idisplay2<?php echo $user_img_d_css; ?>">
                        <p>Selected Profile Image</p>
                        <img src="<?php echo $cm->folder_for_seo; ?>userphoto/<?php echo $user_imgpath; ?>" border="0" width="100" /><br />
                        <a class="deleteimage" targets="2" href="javascript:void(0);">Delete Image</a>
                    </div>
                </li>
                
                <?php
				}
				?>
            </ul>
        </div>
    </div>    
    
    <?php
	if ($type_id != 6){
	?>
    
    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>About Me</span></div>
        <div class="singleblock_box singleblock_box_h editorheight1 clearfixmain">
            <?php
            $editorstylepath = "";
            $editorextrastyle = "text_area";
            $editortoolbarset = "frontendbasic";
            $cm->display_editor("about_me", $sBasePath, "99%", "100%", $about_me, $editorstylepath, $editorextrastyle, $editortoolbarset);
            ?>
        </div>
    </div>
    
    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>Social Media Links</span></div>
        <div class="singleblock_box singleblock_box_h clearfixmain">
            <ul class="form">
                <li class="left">
                    <p>Facebok URL</p>
                    <input type="text" id="facebook_url" name="facebook_url" value="<?php echo $facebook_url; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Twitter URL</p>
                    <input type="text" id="twitter_url" name="twitter_url" value="<?php echo $twitter_url; ?>" class="input" />
                </li>

              
                <li class="left">
                    <p>LinkedIn URL</p>
                    <input type="text" id="linkedin_url" name="linkedin_url" value="<?php echo $linkedin_url; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Blog URL</p>
                    <input type="text" id="blog_url" name="blog_url" value="<?php echo $blog_url; ?>" class="input" />
                </li>               
            </ul>
        </div>
    </div>
    <?php
	}
	?>
    
    <?php if ($type_id != 6){?>
    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>Industry Association</span></div>
        <div class="singleblock_box singleblock_box_h clearfixmain">
        	<?php
				echo $yachtclass->industryassociation_display_list($ms, 2,1);			
			?>
        </div>
    </div>
    
    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>Certification</span></div>
        <div class="singleblock_box singleblock_box_h clearfixmain">
        	<?php
				echo $yachtclass->certification_display_list($ms, 2,1);			
			?>
        </div>
    </div>
    <?php } ?>

    <div class="singleblock">
        <button type="submit" class="button" value="Submit">Submit</button>
    </div>
</form>
<?php
echo $html_end;
include($bdr."includes/foot.php");
?>