<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Testimonial";
$ms = round($_GET["id"], 0);
$featured = 0;
$status_id = 1;

if ($ms > 0){
	$sql="select * from tbl_testimonial where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

		if ($reg_date == "0000-00-00"){ $reg_date = "";}else{ $reg_date = $cm->display_date($reg_date, 'y', 9);}
		$link_name = "Modify Existing Testimonial";
	}else{
		$ms = 0;
	}
}
$icclass = "lefttestimonialicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#testimonial_ff").submit(function(){
		if (!datefield_validation('reg_date', 'Post Date', 'y')){ return false; }
		if(!validate_text(document.ff.name,1,"Please enter Poster Name")){
			return false;
		}
		
		if ($("#imgpath").length > 0) {
			if (!image_validation(document.ff.imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}
		 	  
		return true; 
	});

});
</script>

<form method="post" action="testimonial_sub.php" id="testimonial_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    	<tr>
              <td width="35%" align="left"><span class="fontcolor3">* </span>Post Date[mm/dd/yyyy]:</td>
              <td width="65%" align="left"><input defaultdateset="" rangeyear="2010:<?php echo date("Y"); ?>" type="text" id="reg_date" name="reg_date" value="<?php echo $reg_date; ?>" class="date-field-b inputbox inputbox_size4_b" /></td>
        </tr>
        <tr>
          <td width="" align="left"><span class="fontcolor3">* </span>Poster Name:</td>
          <td width="" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size1" /></td>
        </tr>
        
        <tr>
          <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Company Name:</td>
          <td width="" align="left"><input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" class="inputbox inputbox_size1" /></td>
        </tr>
        
        <tr>
          <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Designation:</td>
          <td width="" align="left"><input type="text" id="designation" name="designation" value="<?php echo $designation; ?>" class="inputbox inputbox_size1" /></td>
        </tr>
        
        <tr>
            <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Website URL:</td>
            <td width="" align="left"><input type="text" id="website_url" name="website_url" value="<?php echo $website_url; ?>" class="inputbox inputbox_size1" /></td>
        </tr>
        
        <tr>
        	<td width="" align="left"><span class="fontcolor3">* </span>Ratings (5 = Excellent, 1 = Very Poor):</td>
            <td width="" align="left"><select name="rating" id="rating" class="combobox_size1 htext">
                <?php
                $yachtclass->get_common_number_combo($rating, 5);
                ?>
            </select></td>
        </tr>
        
        <?php if ($imgpath != ""){ ?>
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
            <td width="" align="left" valign="top" class="tdpadding1">
            <img src="../testimonialimage/<?php echo $imgpath; ?>" border="0" width="100" /><br />
            <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imgpath','tbl_testimonial','id','testimonialimage')">Delete Image</a>
            </td>
        </tr>
        <?php }else{ ?>
        <tr>
            <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Image [w: <?php echo $cm->testimonial_im_width; ?>px, h: <?php echo $cm->testimonial_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
            <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox inputbox_size4" /></td>
        </tr>
        <?php } ?>
        
        <tr>
            <td width="" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Testimonial Content</strong>:</td>
       </tr>
       <tr>
            <td width="" align="center" colspan="2" class="tdpadding1">
              <?php
                $editorstylepath = "";
                $editorextrastyle = "adminbodyclass text_area";
                $cm->display_editor("description", $sBasePath, "100%", 250, $description, $editorstylepath, $editorextrastyle);
              ?>
            </td>
       </tr>
       
       <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Associate Broker:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><select name="broker_id" id="broker_id" class="combobox_size4 htext">
                    <option value="">Select</option>
                    <?php
                    echo $yachtclass->get_all_broker_combo($broker_id);
                    ?>
                </select></td>
        </tr>
       
       <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Featured Testimonial?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="featured" name="featured" value="1" <?php if ($featured == 1){?> checked="checked"<?php } ?> /> Yes</td>
        </tr>
    
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="combobox_size6 htext">
                    <option value="">Select</option>
                    <?php
                    echo $adm->get_modulestatus_combo($status_id);
                    ?>
                </select></td>
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