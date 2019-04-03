<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";

$yachtclass->check_user_permission(array(2));
$ms = round($_REQUEST['id'], 0);
$com_id = $yachtclass->get_broker_company_id($loggedin_member_id);

$registerpg = "y";
$datastring = $cm->session_field_user();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$old_status_id = 0;
$old_d_username = "";
$link_name = $atm1 = "Add Manager";
$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

if ($ms > 0){
    $sql = "select * from tbl_user where id = '". $cm->filtertext($ms) ."'";
	$sql .= " and company_id = '". $com_id ."'";
    	
	$result = $db->fetch_all_array($sql);
    $found = count($result);
    if ($found > 0){
        $row = $result[0];
		$d_username = $old_d_username = htmlspecialchars($row['uid']);
		$d_email = $old_d_email = htmlspecialchars($row['email']);
		$d_password = htmlspecialchars($edclass->txt_decode($row['pwd']));
		
		$fname = htmlspecialchars($row['fname']);	
		$lname = htmlspecialchars($row['lname']); 
		$about_me = htmlspecialchars($row['about_me']);    
		$user_imgpath = htmlspecialchars($row['user_imgpath']);
		$type_id = $row['type_id'];
		$company_id = $row['company_id'];
		$location_id = $row['location_id'];
		$parent_id = $row['parent_id'];
		$status_id = $old_status_id = $row['status_id'];
		
        if ($user_imgpath != ""){
            $user_img_u_css = ' com_none';
            $user_img_d_css = '';
        }
        $link_name = $atm1 = "Modify Manager";
    }else{
        $ms = 0;
    }
}

if ($country_id == ""){ $country_id = 1; }

if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";  
    $state_s2 = "com_none";
}

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

include($bdr."includes/head.php");
?>
<form method="post" action="<?php echo $bdir; ?>manager-sub.php" id="user_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
    <input type="hidden" value="3" name="type_id" id="type_id" />
    <input type="hidden" value="<?php echo $loggedin_member_id; ?>" name="parent_id" id="parent_id" />
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />

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
        <div class="singleblock_heading"><span>Login Information</span></div>
        <div class="singleblock_box">
            <ul class="form">
                <?php if ($ms == 0){?>
                <li class="left">
                    <p>Username [min 6 chars]<span title="" id="checkvaliddatares1" class="iconwh">&nbsp;</span></p>
                    <input type="text" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata input" />
                </li>
                <li class="right manageright">&nbsp;</li>
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
            </ul>
            <div class="clear"></div>
        </div>
    </div>    

    <div class="singleblock">
        <div class="singleblock_heading"><span>Personal Information</span></div>
        <div class="singleblock_box">
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

                <li>
                    <p>About Me</p>
                    <textarea name="about_me" id="about_me" rows="1" cols="1" class="comments"><?php echo $about_me;?></textarea>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <button type="submit" class="button" value="Submit">Submit</button>
    </div>
</form>
<?php 
include($bdr."includes/foot.php");
?>