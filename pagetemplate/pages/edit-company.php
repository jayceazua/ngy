<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";

$yachtclass->check_user_permission(array(2, 3));
$com_id = $yachtclass->get_broker_company_id($loggedin_member_id);

$registerpg = "y";
$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

$sql = "select * from tbl_company where id = '". $cm->filtertext($com_id) ."'";
$result = $db->fetch_all_array($sql);

$row = $result[0];
$cname = htmlspecialchars($row['cname']);
$website_url = htmlspecialchars($row['website_url']);
$logo_imgpath = htmlspecialchars($row['logo_imgpath']);
$about_company = htmlspecialchars($row['about_company']);
$status_id = $row['status_id'];
	
if ($logo_imgpath != ""){
	$logo_img_u_css = ' com_none';
	$logo_img_d_css = '';
}

$link_name = $atm1 = "Edit Profile Of " . $cname;

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

include($bdr."includes/head.php");
?>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="company_ff" name="ff" enctype="multipart/form-data">
	<input class="finfo" id="email2" name="email2" type="text" />
    <input type="hidden" id="fcapi" name="fcapi" value="companysubmit" />   
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
                    <p>Company Name</span></p>                    
                    <input type="text" id="cname" name="cname" value="<?php echo $cname; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Website URL</p>
                    <input type="text" id="website_url" name="website_url" value="<?php echo $website_url; ?>" class="input" />
                </li>
                
                <li class="left">
                    <div class="iupload1<?php echo $logo_img_u_css; ?>">
                        <p>Select Logo Image [w: <?php echo $cm->logo_im_width; ?>px, h: <?php echo $cm->logo_im_height; ?>px]</p>
                        <input validval="<?php echo $cm->allow_image_ext; ?>" type="file" id="logo_imgpath" name="logo_imgpath" class="input" />
                        <p>[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</p>
                    </div>
                    <div class="idisplay1<?php echo $logo_img_d_css; ?>">
                        <p>Selected Logo Image</p>
                        <img src="<?php echo $cm->folder_for_seo; ?>userphoto/<?php echo $logo_imgpath; ?>" border="0" width="100" /><br />
                        <a class="deleteimage" targets="1" href="javascript:void(0);">Delete Image</a>
                    </div>
                </li>
            </ul>
            <ul class="form">                
                <li>
                    <p>About Company</p>
                    <textarea name="about_company" id="about_company" rows="1" cols="1" class="comments"><?php echo $about_company;?></textarea>
                </li>
                
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Industry Association</span></div>
        <div class="singleblock_box">
        	<?php
				echo $yachtclass->industryassociation_display_list($com_id, 1,1);			
			?>
        </div>
    </div>

    <div class="singleblock">
        <button type="submit" class="button" value="Submit">Submit</button>
    </div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$("#company_ff").submit(function(){
			var all_ok = "y";
			
			if (!field_validation_border("cname", 1, 1)){ all_ok = "n"; }
			
			if ($("#logo_imgpath").length > 0) {
				var validval = $("#logo_imgpath").attr("validval");
				if (!image_validation($("#logo_imgpath").val(), 'n', validval, 2)){
					$("#logo_imgpath").addClass("requiredfield");
					all_ok = "n";
				}
			}
			
			if (all_ok == "n"){            
				return false;
			}
			return true;
		});
		
		$("#company_id").change(function(){
		   opencompanylocatiob();
	 	});
	});    
</script>
<?php 
include($bdr."includes/foot.php");
?>