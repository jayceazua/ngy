<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Blog";
$ms = round($_GET["id"], 0);
$category_id = 1;
$sub_category_id = 0;

$status_id = 1;
$crop_option = 0;
$image_display_post = 1;
$reg_date = $cm->display_date(time(), 'n', 9);
$display_date = 0;
$featured_post = 0;

if ($ms > 0){
	$sql="select * from tbl_blog where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

		if ($reg_date == "0000-00-00"){ $reg_date = "";}else{ $reg_date = $cm->display_date($reg_date, 'y', 9);}
		$link_name = "Modify Existing Blog";
	}else{
		$ms = 0;
	}
}
$icclass = "leftblogicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#blog_ff").submit(function(){
		if (!datefield_validation('reg_date', 'Post Date', 'y')){ return false; }
		
		if(!validate_text(document.ff.category_id,1,"Please select Blog Category")){
			return false;
		}	
		
		if(!validate_text(document.ff.name,1,"Please enter Blog Title")){
			return false;
		} 
		
		if ($("#imgpath").length > 0) {
			if (!image_validation(document.ff.imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}		
				
		return true; 
	});
});
</script>

<form method="post" action="blog_sub.php" id="blog_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    	<tr>
              <td width="35%" align="left"><span class="fontcolor3">* </span>Post Date[mm/dd/yyyy]:</td>
              <td width="65%" align="left"><input defaultdateset="" rangeyear="2010:<?php echo (date("Y") + 1); ?>" type="text" id="reg_date" name="reg_date" value="<?php echo $reg_date; ?>" class="date-field-d inputbox inputbox_size4_b" /></td>
        </tr>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display Post Date?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="display_date" name="display_date" value="1" <?php if ($display_date == 1){?> checked="checked"<?php } ?> /> Yes</td>
        </tr>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Featured Post?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="featured_post" name="featured_post" value="1" <?php if ($featured_post == 1){?> checked="checked"<?php } ?> /> Yes</td>
        </tr>
        
        <tr>
          <td width="" align="left"><span class="fontcolor3">* </span>Select Category:</td>
          <td width="" align="left"><select name="category_id" id="category_id" class="combobox_size4 htext">
            <option value="">Select</option>
            <?php
            echo $adm->get_blog_category_combo($category_id);
            ?>
        </select></td>
        </tr>
        
        <tr>
          <td width="" align="left"><span class="fontcolor3">* </span>Blog Title:</td>
          <td width="" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <tr>
            <td width="" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Blog Content</strong>:</td>
       </tr>
       <tr>
            <td width="" align="center" colspan="2" class="tdpadding1">
              <?php
                $editorstylepath = "";
                $editorextrastyle = "adminbodyclass text_area";
                $cm->display_editor("description", $sBasePath, "100%", 450, $description, $editorstylepath, $editorextrastyle);
              ?>
            </td>
       </tr>
       
       <tr>
              <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Excerpt:</td>
              <td width="" align="left" valign="top"><textarea name="small_description" id="small_description" rows="1" cols="1" class="textbox textbox_size6"><?php echo $small_description;?></textarea> </td>
       </tr>
       
        <tr>
            <td width="" align="left" valign="top"><font class="htext"><span class="fontcolor3">&nbsp;&nbsp;</span>Assign Tag(s) :</font></td>
            <td width="" align="left" valign="top">
                <div id="catholderbox" class="commonborder">    
                <?php echo $blogclass->display_smart_tag_selection($ms, 1); ?>
                </div>
            </td>
        </tr>
       
        <?php if ($blog_image != ""){ ?>
        <tr>
             <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
             <td width="" align="left" valign="top" class="tdpadding1">
             <img src="../blogimage/<?php echo $blog_image; ?>" border="0" width="100" /><br />
             <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','blog_image','tbl_blog','id','blogimage')">Delete Image</a>
             <input type="hidden" value="<?php echo $crop_option; ?>" id="crop_option" name="crop_option" />
             </td>
        </tr>
        <?php }else{ ?>
        <tr>
             <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Image [w: <?php echo $cm->blog_im_width; ?>px, h: <?php echo $cm->blog_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
             <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Hard crop image?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="crop_option" name="crop_option" value="1" <?php if ($crop_option == 1){?> checked="checked"<?php } ?> /> Yes</td>
        </tr>
        <?php } ?>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display uploaded image in Post Details page?</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="image_display_post" name="image_display_post" value="1" <?php if ($image_display_post == 1){?> checked="checked"<?php } ?> /> Yes</td>
        </tr>
       
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="combobox_size4 htext">
                    <option value="">Select</option>
                    <?php
                    $adm->get_commonstatus_combo($status_id);
                    ?>
                </select></td>
        </tr>
        
        <tr>
            <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Meta Information:</strong></td>
        </tr>
        
        <tr>
          <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Page Title:</td>
          <td width="" align="left"><input type="text" name="m1" id="m1" value="<?php echo $m1;?>" class="inputbox inputbox_size4" /></td>
       </tr>
    
       <tr>
              <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Description:</td>
              <td width="" align="left" valign="top"><textarea name="m2" id="m2" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m2;?></textarea> </td>
       </tr>
    
       <tr>
              <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Keywords:</td>
              <td width="" align="left" valign="top"><textarea name="m3" id="m3" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m3;?></textarea> </td>
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