<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";
$include_fck = 1;

$yachtclass->check_user_permission(array(2, 3, 4));
$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
$cuser_type_id = $cuser_ar[0]["type_id"];
$com_id = $cuser_ar[0]["company_id"];
$loc_id = $cuser_ar[0]["location_id"];

$onlycompany = 1;
$onlylocation = 0;
if ($cuser_type_id == 1){ $onlycompany = 0; }

$ms = round($_REQUEST['id'], 0);
$registerpg = "y";

$datastring = $cm->session_field_user();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$old_status_id = 0;
$old_d_username = "";
$link_name = $atm1 = "Add Broker";
$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

$b_location = ' com_none';

if ($ms > 0){
    $sql = "select * from tbl_user where id = '". $cm->filtertext($ms) ."'";
    
	if ($cuser_type_id == 2 OR $cuser_type_id == 3){		
		$sql .= " and company_id = '". $com_id ."'";
	}
	
	if ($cuser_type_id == 4){		
		$sql .= " and location_id = '". $loc_id ."'";
	}
	
	$result = $db->fetch_all_array($sql);
    $found = count($result);
    if ($found > 0){
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
		$type_name = $cm->get_common_field_name('tbl_user_type', 'name', $type_id);
        $link_name = $atm1 = "Modify Broker";
    }else{
        $ms = 0;
    }
}

//if ($company_id == 0 OR $company_id == ""){ $company_id = $cuser_ar[0]["company_id"]; }
//if ($location_id == 0 OR $location_id == ""){ $location_id = $onlylocation = $cuser_ar[0]["location_id"]; }

if ($country_id == ""){ $country_id = 1; }
if ($type_id == ""){ $type_id = 5; }

if ($type_id == 3){
	//manager	
	$b_location = '';
}

if ($type_id == 4 OR $type_id == 5){
	//location admin	
	$b_location = '';
}

$breadcrumb = 1;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => 'Broker List',
            'a_link' => $cm->folder_for_seo . 'my-brokerlist/'
);
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

include($bdr."includes/head.php");
?>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="user_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
    <input type="hidden" value="<?php echo $com_id; ?>" name="company_id" id="company_id" />
    <input type="hidden" value="<?php echo $loggedin_member_id; ?>" name="parent_id" id="parent_id" />
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input class="finfo" id="email2" name="email2" type="text" />
    <input type="hidden" id="fcapi" name="fcapi" value="membersubmit" />

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
                <?php if ($ms == 0){?>
                <li class="left">
                    <p>Username [min 6 chars]<span title="" id="checkvaliddatares1" class="iconwh">&nbsp;</span></p>
                    <input type="text" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata input" />
                </li>
            </ul>
            <ul class="form">
                
                <?php }else{?>
                <li>
                    <p>Username&nbsp;&nbsp;&nbsp;</span> <strong><?php echo $d_username; ?></strong></p>                    
                    <input type="hidden" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata input" />
                </li>        
                <?php } ?>
                
                <li class="left">
                    <p>Password [min 6 chars]</p>
                    <input type="password" id="d_password" name="d_password" value="<?php echo $d_password; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Confirm Password</p>
                    <input type="password" id="cd_password" name="cd_password" value="<?php echo $d_password; ?>" class="input" />
                </li>
                
                <?php if ($cuser_type_id == 2){?>
					<li class="left">
						<p>Type</p>
						<select id="type_id" name="type_id" class="typesel my-dropdown2">
							<option value="">Select Type</option>
							<?php $yachtclass->get_user_type_combo($type_id, $cuser_type_id); ?>
						</select>
					</li>
                    
                    <li class="right b_location <?php echo $b_location; ?>" id="location_id_heading">
                        <p>Office Location</p>
                        <select id="location_id" name="location_id" class="my-dropdown2">
                            <option value="" addressval="">Select Location</option>
                            <?php echo $yachtclass->get_company_location_combo($location_id, $com_id, 0, $onlylocation); ?>
                        </select>
                    </li>
                <?php } ?>   
                
                <?php if ($cuser_type_id == 3){?>  
                    <li class="left">
						<p>Type</p>
						<select id="type_id" name="type_id" class="typesel my-dropdown2">
							<option value="">Select Type</option>
							<?php $yachtclass->get_user_type_combo($type_id, $cuser_type_id); ?>
						</select>
					</li>

                    <li class="right b_location <?php echo $b_location; ?>" id="location_id_heading">
                        <p>Office Location</p>
                        <select id="location_id" name="location_id" class="my-dropdown2">
                            <option value="" addressval="">Select Location</option>
                            <?php echo $yachtclass->get_company_location_combo($location_id, $com_id, 0, $onlylocation); ?>
                        </select>
                    </li>
                
                <?php } ?>
                
                <?php 
				if ($cuser_type_id == 4){
					$type_name = $cm->get_common_field_name('tbl_user_type', 'name', 5);	
					//$location_name = $cm->get_common_field_name('tbl_location_office', 'name', $loc_id);
					$broker_ad_ar = $yachtclass->get_broker_address_array($loggedin_member_id);		
					$address = $broker_ad_ar["address"];
					$city = $broker_ad_ar["city"];
					$state = $broker_ad_ar["state"];
					$state_id = $broker_ad_ar["state_id"];
					$country_id = $broker_ad_ar["country_id"];
					$zip = $broker_ad_ar["zip"];
					$phone = $broker_ad_ar["phone"];					
					$addressfull = $yachtclass->com_address_format('', $city, $state, $state_id, $country_id);					
				?>
                	
                    <li class="left">
                        <p>Type&nbsp;&nbsp;&nbsp;</span> <strong><?php echo $type_name; ?></strong></p>                    
                        <input type="hidden" id="type_id" name="type_id" value="5" />
                    </li>
                    
                    <li class="right">
                        <p>Location&nbsp;&nbsp;&nbsp;</span> <strong><?php echo $addressfull; ?></strong></p>                    
                        <input type="hidden" id="location_id" name="location_id" value="<?php echo $loc_id; ?>" />
                    </li>
                
                <?php } ?> 
                
                <li>
                    <p>Want to send login invitation? &nbsp;&nbsp; <input class="checkbox" type="checkbox" id="sendemailinfo" name="sendemailinfo" value="1" /></p>
                </li>                
                
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
                    <p>Mobile Phone</p>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
                </li>
                
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
            </ul>
        </div>
    </div>
    
    <div class="singleblock clearfixmain">
        <div class="singleblock_heading"><span>About Me</span></div>
        <div class="singleblock_box singleblock_box_h editorheight1 clearfixmain">
            <?php
            $editorstylepath = "";
            $editorextrastyle = "text_area";
            $editortoolbarset = "frontendbasic";
            $cm->display_editor("about_me", $sBasePath, "100%", "100%", $about_me, $editorstylepath, $editorextrastyle, $editortoolbarset);
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

    <div class="singleblock">
        <button type="submit" class="button" value="Submit">Submit</button>
    </div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$("#company_id").change(function(){
		   opencompanylocatiob();
	 	});
	});    
</script>
<?php 
include($bdr."includes/foot.php");
?>