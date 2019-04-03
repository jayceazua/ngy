<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";
$include_fck = 1;

$yachtclass->check_user_permission(array(2, 3));
$ms = round($_REQUEST['id'], 0);
$com_id = $yachtclass->get_broker_company_id($loggedin_member_id);
$status_id = 1;
$latloncss = ' com_none';
$registerpg = "y";

$datastring = $cm->session_field_location();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
 ${$key} = $val;
}

$logo_img_u_css = $user_img_u_css = '';
$logo_img_d_css = $user_img_d_css = ' com_none';

$link_name = $atm1 = "Add Office Location";

if ($ms > 0){
	$sql="select * from tbl_location_office where id = '". $cm->filtertext($ms) ."' and company_id = '". $com_id ."'"; 	
	$result = $db->fetch_all_array($sql);
    $found = count($result);
    if ($found > 0){
        $row = $result[0];
		foreach($row AS $key => $val){
			${$key} = $cm->filtertextdisplay($val);
		}
						
		if ($logo_image != ""){
			$logo_img_u_css = ' com_none';
			$logo_img_d_css = '';
		}		
		$latloncss = '';
        $link_name = $atm1 = "Modify Office Location";
    }else{
        $ms = 0;
    }
}
if ($status_id == 0){ $status_id = 1; }
if ($country_id == 0){ $country_id = 1; }
if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";
    $state_s2 = "com_none";
}

$breadcrumb = 1;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => 'Office Locations',
            'a_link' => $cm->folder_for_seo . 'office-locationlist/'
);
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

include($bdr."includes/head.php");
?>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="location_ff" name="ff" enctype="multipart/form-data">    
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input class="finfo" id="email2" name="email2" type="text" />
    <input type="hidden" id="fcapi" name="fcapi" value="locationsubmit" />

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
                    <p>Office Name</p>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Address</p>
                    <input type="text" id="address" name="address" value="<?php echo $address; ?>" class="input" />
                </li>                 
                
                <li class="left">
                    <p>City</p>
                    <input type="text" id="city" name="city" value="<?php echo $city; ?>" class="input" />
                </li>
                <li class="right" id="country_id_heading">
                    <p>Select Country</p>
                    <select id="country_id" name="country_id" refextra="" class="countrycls_state my-dropdown2">
                        <option value="">Select</option>
                        <?php $yachtclass->get_country_combo($country_id); ?>
                    </select>
                </li>
                
                <li class="left">
                    <p>State</p>
                    <div id="sps2" class="<?php echo $state_s2; ?>">
                        <select id="state_id" name="state_id" class="my-dropdown2">
                            <option value="">Select State</option>
                            <?php $yachtclass->get_state_combo($state_id); ?>
                        </select>
                    </div><div id="sps1" class="<?php echo $state_s1; ?> ">
                        <input type="text" id="state" name="state" value="<?php echo $state; ?>" class="input" />
                    </div>
                </li>                
                <li class="right">
                    <p>Postal Code</p>
                    <input type="text" id="zip" name="zip" value="<?php echo $zip; ?>" class="input" />
                </li>
                
                <li class="left">
                    <p>Phone Number</p>
                    <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Fax Number</p>
                    <input type="text" id="fax" name="fax" value="<?php echo $fax; ?>" class="input" />
                </li>                
                               
                <li class="left">
                    <div class="iupload3<?php echo $logo_img_u_css; ?>">
                        <p>Select Image [w: <?php echo $cm->location_im_width; ?>px, h: <?php echo $cm->location_im_height; ?>px]</p>
                        <input validval="<?php echo $cm->allow_image_ext; ?>" type="file" id="logo_image" name="logo_image" class="input" />
                        <p>[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</p>
                    </div>
                    <div class="idisplay3<?php echo $logo_img_d_css; ?>">
                        <p>Selected Image</p>
                        <img src="<?php echo $cm->folder_for_seo; ?>locationimage/<?php echo $logo_image; ?>" border="0" width="100" /><br />
                        <a class="deleteimage" targets="3" href="javascript:void(0);">Delete Image</a>
                    </div>
                </li>
                
                
                <li><p><strong>Description</strong></p></li>
                <li>
                <div class="singleblock_box editorheight1">
                <?php
				$editorstylepath = "";
				$editorextrastyle = "text_area";
				$editortoolbarset = "frontendbasic";
				$cm->display_editor("descriptions", $sBasePath, "100%", "100%", $descriptions, $editorstylepath, $editorextrastyle, $editortoolbarset);
				?>
                </div>
                </li>
                
                <li><p><strong>External Link</strong></p></li>
                <li class="left">
                    <p>Link Text</p>
                    <input type="text" id="link_title" name="link_title" value="<?php echo $link_title; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Link URL</p>
                    <input type="text" id="link_url" name="link_url" value="<?php echo $link_url; ?>" class="input" />
                </li>
                
                <li><p><strong>Preferences</strong></p></li>
                <li>
                    <p>Select as Primary Location? &nbsp;&nbsp; <input type="checkbox" id="default_location" name="default_location" value="1" <?php if ($default_location == 1){?> checked="checked"<?php } ?> /></p>                    
                </li>
                
                <li class="left" id="currency_id_heading">
                    <p>Currency</p>
                    <select name="currency_id" id="currency_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_currency_combo($currency_id);
                        ?>
                    </select>
                </li>
                <li class="right" id="unit_measure_id_heading">
                    <p>Unit of Measure</p>
                    <select name="unit_measure_id" id="unit_measure_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_unit_measure_combo($unit_measure_id);
                        ?>
                    </select>
                </li>
                
                <li><p><strong>Latitude and Longitude</strong></p></li>
				<?php
				if ($ms > 0){
				?>
				<li>Latitude: <span class="fontbold"><?php echo $lat_val; ?></span>, Longitude <span class="fontbold"><?php echo $lon_val; ?></span></li>
				<?php
				}
				?>

				<li><p>Enter Latitude and Longitude manually? &nbsp;&nbsp; <input type="checkbox" class="latlonmanual" id="lat_lon_manual" name="lat_lon_manual" value="1" <?php if ($ms > 0){?> checked="checked"<?php } ?>  /> Yes</p></li>
                <li class="left latlonfield<?php echo $latloncss; ?>">
					<p>Latitude:</p>
					<input type="text" id="lat_val" name="lat_val" value="<?php echo $lat_val; ?>" class="input" />
				</li>
				<li class="right latlonfield<?php echo $latloncss; ?>">
					<p>Longitude</p>
					<input type="text" id="lon_val" name="lon_val" value="<?php echo $lon_val; ?>" class="input" />
				</li>
                
                <li class="left" id="status_id_heading">
                    <p>Display Status</p>
                    <select name="status_id" id="status_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_commonstatus_combo($status_id);
                        ?>
                    </select>
                </li>
            </ul>
            
            <ul class="form">
            	<li><p><strong>Meta Information</strong></p></li>
                <li>
                	<p>Page Title</p>
                    <input type="text" id="m1" name="m1" value="<?php echo $m1; ?>" class="input" />
                </li>
                
                <li>
                	<p>Meta Description</p>
                    <textarea name="m2" id="m2" rows="1" cols="1" class="comments"><?php echo $m2;?></textarea>
                </li>
                
                <li>
                	<p>Meta Keywords</p>
                    <textarea name="m3" id="m3" rows="1" cols="1" class="comments"><?php echo $m3;?></textarea>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div> 

    <div class="singleblock">
        <button type="submit" class="button" value="Submit">Submit</button>
    </div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		$("#location_ff").submit(function(){
			
			var all_ok = "y";
			
			if (!field_validation_border("name", 1, 1)){ all_ok = "n"; }
			if (!field_validation_border("address", 1, 1)){ all_ok = "n"; }
			if (!field_validation_border("city", 1, 1)){ all_ok = "n"; }
			if (!field_validation_border("country_id", 3, 1)){ all_ok = "n"; }
			
			if($('#country_id').val() == 1){
				if (!field_validation_border("state_id", 3, 1)){ all_ok = "n"; }
			}else{
				if (!field_validation_border("state", 1, 1)){ all_ok = "n"; }
			}
			
			if (!field_validation_border("phone", 1, 1)){ all_ok = "n"; }
			if (!field_validation_border("zip", 1, 1)){ all_ok = "n"; }
			if (!field_validation_border("phone", 1, 1)){ all_ok = "n"; }
			//if (!field_validation_border("located_at", 1, 1)){ all_ok = "n"; }
			
			if ($("#logo_image").length > 0) {
				var validval = $("#logo_image").attr("validval");
				if (!image_validation($("#logo_image").val(), 'n', validval, 2)){
					$("#logo_image").addClass("requiredfield");
					all_ok = "n";
				}
			}
			
			//external link
			var link_title = $('#link_title').val();
			var link_url = $('#link_url').val();
			  
			if ((link_title != "") || (link_url != "")){
				  if (!field_validation_border("link_title", 1, 1)){ all_ok = "n"; }
				  if (!field_validation_border("link_url", 1, 1)){ all_ok = "n"; }
			}
			
			//preferences
			if (!field_validation_border("currency_id", 3, 1)){ all_ok = "n"; }
			if (!field_validation_border("unit_measure_id", 3, 1)){ all_ok = "n"; }			
			//end
			
			if (!field_validation_border("status_id", 3, 1)){ all_ok = "n"; }
			
			if (all_ok == "n"){            
				return false;
			}
			return true;
			
		});
		
		//lat-lon manual entry
		$(".latlonmanual").click(function(){   
			if($(this).is(':checked')){
				$(".latlonfield").show();
			}else{
				$(".latlonfield").hide();
			}
		}); 
		//end
	});    
</script>
<?php 
include($bdr."includes/foot.php");
?>