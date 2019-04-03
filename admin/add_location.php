<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Location";
$company_id = round($_GET["cid"], 0);
$ms = round($_GET["id"], 0);

$resultcom = $yachtclass->check_company_exist($company_id, 2, 1, 0);
$rowcom = $resultcom[0];
$companyname = $rowcom['cname'];

$datastring = $cm->session_field_location();
$return_ar = $cm->collect_session_for_form($datastring);

foreach($return_ar AS $key => $val){
	${$key} = $val;
}

$status_id = 1;
$default_location = 0;
$yw_broker_id = "";
$latloncss = ' com_none';

$appointment_time_display = 0;
$located_at_display = 0;

if ($ms > 0){
	$sql="select * from tbl_location_office where id = '". $cm->filtertext($ms) ."' and company_id = '". $company_id ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		if ($yw_broker_id == 0){ $yw_broker_id = ""; }
		$latloncss = '';
		$link_name = "Modify Existing Location";
	}else{
		$ms = 0;
	}
}

if ($country_id == 0){ $country_id = 1; }
if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";
    $state_s2 = "com_none";
}

$link_name .= ' - ' . $companyname;
$icclass = "leftusericon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#location_ff").submit(function(){
      if(!validate_text(document.ff.name,1,"Please enter Office Name")){
		  return false;
      }
	  
	  /*if(!validate_text(document.ff.located_at,1,"Please enter Located At")){
          return false;
      }*/
	  
	  if(!validate_text(document.ff.address,1,"Please enter Address")){
          return false;
      }

      if(!validate_text(document.ff.city,1,"Please enter City")){
          return false;
      }
	  
	  if(!validate_text(document.ff.country_id,1,"Please select your Country")){
          return false;
      }
	  
	  if($('#country_id').val() == 1){
          if(!validate_text(document.ff.state_id,1,"Please select State")){
              return false;
          }
      }else{
          if(!validate_text(document.ff.state,1,"Please enter your State")){
              return false;
          }
      }
	  
	  if(!validate_text(document.ff.zip,1,"Please enter Postal Code")){
          return false;
      }

      if(!validate_text(document.ff.phone,1,"Please enter Phone Number")){
          return false;
      }
	  
	  //image
      if ($("#logo_image").length > 0) {
          if (!image_validation(document.ff.logo_image.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
      }
	  
	  //external link
	  var link_title = $('#link_title').val();
	  var link_url = $('#link_url').val();
	  
	  if ((link_title != "") || (link_url != "")){
		  if(!validate_text(document.ff.link_title,1,"Please enter Link Text")){
			  return false;
		  }
		  
		  if(!validate_text(document.ff.link_url,1,"Please enter Link URL")){
			  return false;
		  }
	  }
	  
	  //preferences
	  if(!validate_text(document.ff.currency_id,1,"Please select Currency")){
          return false;
      }	
	  
	  if(!validate_text(document.ff.unit_measure_id,1,"Please select Unit of Measure")){
          return false;
      }  
	  //end	 
	  
	  if(!validate_text(document.ff.status_id,1,"Please select Display Status")){
          return false;
      } 
	  
	  if (ms > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
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

<form method="post" action="location_sub.php" id="location_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $company_id; ?>" name="company_id" />
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <input type="hidden" value="<?php echo $yw_broker_id; ?>" name="old_yw_broker_id" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    	<tr>
			  <td width="" align="left" colspan="2">All fields marked with <span class="mandt_color">*</span> are mandatory.</td>
		    </tr>
            
    	<tr>
            <td width="" align="left"><span class="mandt_color">* </span>Office Name:</td>
            <td width="" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Address:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="address" name="address" value="<?php echo $address; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>City:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="city" name="city" value="<?php echo $city; ?>" class="inputbox inputbox_size4" /></td>
            </tr>

            <tr>
                <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Country:</td>
                <td width="" align="left" class="tdpadding1"><select id="country_id" name="country_id" refextra="" class="countrycls_state combobox_size4 htext">
                        <option value="">Select</option>
                        <?php $yachtclass->get_country_combo($country_id); ?>
                    </select></td>
            </tr>

            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>State:</td>
                <td width="" align="left" valign="top" class="tdpadding1">
                    <div id="sps2" class="<?php echo $state_s2; ?>">
                        <select id="state_id" name="state_id" class="combobox_size4 htext">
                            <option value="">Select State</option>
                            <?php $yachtclass->get_state_combo($state_id); ?>
                        </select>
                    </div>
                    <div id="sps1" class="<?php echo $state_s1; ?> ">
                        <input type="text" id="state" name="state" class="inputbox inputbox_size4" value="<?php echo $state; ?>" />
                    </div>
                </td>
            </tr>

            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Postal Code:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="zip" name="zip" value="<?php echo $zip; ?>" class="inputbox inputbox_size4" /></td>
            </tr>

            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Phone Number:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="inputbox inputbox_size4" /></td>
            </tr>  
            
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Fax Number:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="fax" name="fax" value="<?php echo $fax; ?>" class="inputbox inputbox_size4" /></td>
            </tr>
            
            <?php			
			if ($located_at_display == 1){
			?>
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>At:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="located_at" name="located_at" value="<?php echo $located_at; ?>" class="inputbox inputbox_size4" /></td>
            </tr>
            <?php
			}
			?>
            
            <?php
			if ($appointment_time_display == 1){
			?>
            <tr>
                <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Opening Hours:</td>
                <td width="" align="left" valign="top"><textarea name="appointment_time" id="appointment_time" rows="1" cols="1" class="textbox textbox_size4"><?php echo $appointment_time;?></textarea></td>
            </tr>
            <?php
			}
			?>
            
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">Description</span></td>
            </tr>
            <tr>
                <td width="" align="center" colspan="2">
                    <?php
                    $editorstylepath = "";
                    $editorextrastyle = "adminbodyclass text_area";
                    $cm->display_editor("descriptions", $sBasePath, "100%", 300, $descriptions, $editorstylepath, $editorextrastyle);
                    ?>
                </td>
            </tr>
            
            <?php if ($logo_image != ""){ ?>
                <tr>
                    <td align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Profile Image:</td>
                    <td align="left" valign="top" class="tdpadding1">
                        <img src="../locationimage/<?php echo $logo_image; ?>" border="0" width="100" /><br />
                        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','logo_image','tbl_location_office','id','locationimage')">Delete Image</a>
                    </td>
                </tr>
            <?php }else{ ?>
                <tr>
                    <td align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Image [w: <?php echo $cm->location_im_width; ?>px, h: <?php echo $cm->location_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                    <td align="left" class="tdpadding1"><input type="file" id="logo_image" name="logo_image" class="inputbox inputbox_size4" size="65" /></td>
                </tr>
            <?php } ?>
            
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">External Link</span></td>
            </tr>
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link Text:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="link_title" name="link_title" value="<?php echo $link_title; ?>" class="inputbox inputbox_size4" /></td>
            </tr>

            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link URL:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="link_url" name="link_url" value="<?php echo $link_url; ?>" class="inputbox inputbox_size4" /></td>
            </tr>           
                       
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">Preferences</span></td>
            </tr> 
            
            <tr>
            	 <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Select as Primary Location?</td>
                 <td width="" align="left"><input type="checkbox" id="default_location" name="default_location" value="1" <?php if ($default_location == 1){?> checked="checked"<?php } ?> /></td>            
            </tr>
            
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Currency:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><select name="currency_id" id="currency_id" class="combobox_size1 htext">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_currency_combo($currency_id);
                        ?>
                    </select></td>
            </tr>
            
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Unit of Measure:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><select name="unit_measure_id" id="unit_measure_id" class="combobox_size1 htext">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_unit_measure_combo($unit_measure_id);
                        ?>
                    </select></td>
            </tr>
            
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">Latitude and Longitude</span></td>
            </tr>

			<?php
			if ($ms > 0){
			?>
			<tr>
                <td width="" colspan="2" align="left">&nbsp;&nbsp;Latitude: <span class="fontbold"><?php echo $lat_val; ?></span>, Longitude <span class="fontbold"><?php echo $lon_val; ?></span></td>
            </tr>			
			<?php
			}
			?>

			<tr>
               <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Enter Latitude and Longitude manually?</td>
               <td width="" align="left"><input type="checkbox" class="latlonmanual" id="lat_lon_manual" name="lat_lon_manual" value="1" <?php if ($ms > 0){?> checked="checked"<?php } ?>  /> Yes</td>
            </tr>
            
            <tr class="latlonfield<?php echo $latloncss; ?>">
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Latitude:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="lat_val" name="lat_val" value="<?php echo $lat_val; ?>" class="inputbox inputbox_size4" /></td>
            </tr>

			<tr class="latlonfield<?php echo $latloncss; ?>">
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Longitude</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="lon_val" name="lon_val" value="<?php echo $lon_val; ?>" class="inputbox inputbox_size4" /></td>
            </tr>
            
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">Admin Only</span></td>
            </tr>

			<tr>
               <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Connected YW Broker ID:</td>
               <td width="" align="left"><input type="text" id="yw_broker_id" name="yw_broker_id" value="<?php echo $yw_broker_id; ?>" class="inputbox inputbox_size1" /></td>
            </tr>

            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="combobox_size1 htext">
                        <option value="">Select</option>
                        <?php
                        $adm->get_commonstatus_combo($status_id);
                        ?>
                    </select></td>
            </tr>
            
            <?php if ($ms > 0){ ?>
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size4" value="<?php echo $rank; ?>" /></td>
            </tr>
    		<?php } ?>
            
            <tr>
                <td width="" colspan="2" align="left"><span class="fontbold fontuppercase">Meta Information</span></td>
            </tr>
            
            <tr>
                <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Page Title:</td>
                <td width="" align="left"><input type="text" name="m1" id="m1" value="<?php echo $m1;?>" class="inputbox inputbox_size4" /></td>
            </tr>
            
            <tr>
                <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Description:</td>
                <td width="" align="left" valign="top"><textarea name="m2" id="m2" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m2;?></textarea></td>
            </tr>
            
            <tr>
                <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Keywords:</td>
                <td width="" align="left" valign="top"><textarea name="m3" id="m3" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m3;?></textarea></td>
            </tr>

            <tr>
                <td width="" align="left">&nbsp;</td>
                <td width="" align="left">
                    <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
                </td>
            </tr>
    </table>
</form>
<?php
include("foot.php");
?>