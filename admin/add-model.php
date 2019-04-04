<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$make_id = round($_GET["make_id"], 0);
$result = $yachtclass->check_manufacturer_exist($make_id, 0, 1, 0);
$row = $result[0];
$makename = $cm->filtertextdisplay($row["name"]);


$link_name = "Add New Model Data For " . $makename;
$ms = round($_GET["id"], 0);
$status_id = 1;

if ($ms > 0){
	$sql="select * from tbl_model where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		
		$link_name = "Modify Existing Model Data For " . $name;
	}else{
		$ms = 0;
	}
}
$icclass = "leftlistingicon";
include("head.php");
?>
<form method="post" action="model-sub.php" id="model-ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $make_id; ?>" name="make_id" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">

    <tr>
        <td width="35%" align="left"><span class="fontcolor3">* </span>Model Name:</td>
        <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">* </span>Type:</td>
        <td width="" align="left">
        	<select name="category_id" id="category_id" class="combobox_size4 htext">
                <option value="">Select</option>
                <?php
                echo $modelclass->get_model_category_combo($category_id);
                ?>
            </select>
        </td>
    </tr>    
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Length - ft:</td>
        <td width="" align="left"><input type="text" id="length" name="length" value="<?php echo $length; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Max Beam - ft:</td>
        <td width="" align="left"><input type="text" id="max_beam" name="max_beam" value="<?php echo $max_beam; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Draft - ft:</td>
        <td width="" align="left"><input type="text" id="draft" name="draft" value="<?php echo $draft; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Construction:</td>
        <td width="" align="left"><input type="text" id="construction" name="construction" value="<?php echo $construction; ?>" class="inputbox inputbox_size4" /></td>
    </tr>  
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Engines:</td>
        <td width="" align="left"><input type="text" id="engines" name="engines" value="<?php echo $engines; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Cruising Speed:</td>
        <td width="" align="left"><input type="text" id="cruising_speed" name="cruising_speed" value="<?php echo $cruising_speed; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Top Speed:</td>
        <td width="" align="left"><input type="text" id="top_speed" name="top_speed" value="<?php echo $top_speed; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Fuel Capacity:</td>
        <td width="" align="left"><input type="text" id="fuel_capacity" name="fuel_capacity" value="<?php echo $fuel_capacity; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Water Capacity:</td>
        <td width="" align="left"><input type="text" id="water_capacity" name="water_capacity" value="<?php echo $water_capacity; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Mainsail:</td>
        <td width="" align="left"><input type="text" id="mainsail" name="mainsail" value="<?php echo $mainsail; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Genoa:</td>
        <td width="" align="left"><input type="text" id="genoa" name="genoa" value="<?php echo $genoa; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Living Space:</td>
        <td width="" align="left"><input type="text" id="living_space" name="living_space" value="<?php echo $living_space; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Guests:</td>
        <td width="" align="left"><input type="text" id="guests" name="guests" value="<?php echo $guests; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Category:</td>
        <td width="" align="left"><input type="text" id="category" name="category" value="<?php echo $category; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Naval Architecture:</td>
        <td width="" align="left"><input type="text" id="naval_architecture" name="naval_architecture" value="<?php echo $naval_architecture; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Design:</td>
        <td width="" align="left"><input type="text" id="design" name="design" value="<?php echo $design; ?>" class="inputbox inputbox_size4" /></td>
    </tr> 
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Total Cabins:</td>
        <td width="" align="left"><select name="total_cabin" id="total_cabin" class="combobox_size4 htext">
                <option value="">Select</option>
                <?php
                echo $modelclass->get_common_number_combo($total_cabin);
                ?>
            </select></td>
    </tr> 
        
    <tr>
    	<td width="" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Model Content</strong>:</td>
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
		<?php if ($videofilepath != ""){ ?>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>MP4 Video:</td>
        <td width="" align="left" class="tdpadding1"><a class="htext" target="_blank" href="../models/<?php echo $ms; ?>/modelvideo/<?php echo $videofilepath; ?>"><strong>Download Video</strong></a>&nbsp;&nbsp;<a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','videofilepath','tbl_model','id','models/<?php echo $ms; ?>/modelvideo')">Delete Video</a></td>
        <?php }else{ ?>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select MP4 Video File:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_video_ext; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="videofilepath" name="videofilepath" class="inputbox inputbox_size4" /></td>
        <?php } ?>
    </tr>
    
    <tr>
		<?php if ($brochurefilepath != ""){ ?>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Brochure File:</td>
        <td width="" align="left" class="tdpadding1"><a class="htext" target="_blank" href="../models/<?php echo $ms; ?>/modelbrochure/<?php echo $brochurefilepath; ?>"><strong>Download Brochure</strong></a>&nbsp;&nbsp;<a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','brochurefilepath','tbl_model','id','models/<?php echo $ms; ?>/modelbrochure')">Delete Brochure</a></td>
        <?php }else{ ?>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Brochure File:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_attachment_ext; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="brochurefilepath" name="brochurefilepath" class="inputbox inputbox_size4" /></td>
        <?php } ?>
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
            <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Admin Only:</strong></td>
       </tr>
       
       <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext combobox_size4">
                    <option value="">Select</option>
                    <?php
                    echo $adm->get_commonstatus_combo($status_id);
                    ?>
                </select></td>
        </tr>
        
		<?php if ($ms > 0){ ?>
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
        </tr>
        <?php } ?>
        
        <tr>
            <td width="" align="left">&nbsp;</td>
            <td width="" align="left">
                <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
            </td>
        </tr>
    </table>
</form>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#model-ff").submit(function(){
		var ms = $("#ms").val();
		ms = parseInt(ms);
		
		if(!validate_text(document.ff.name,1,"Please enter Model Name")){
			return false;
		}
						
		if(!validate_text(document.ff.category_id,1,"Please select Type")){
			return false;
		}
		
		if(!validate_numeric(document.ff.length,0,"Please enter Length - Ft")){
			return false;
		}
		
		if(!validate_numeric(document.ff.max_beam,0,"Please enter Max Beam - Ft")){
			return false;
		}
		
		if(!validate_numeric(document.ff.draft,0,"Please enter Draft - Ft")){
			return false;
		}		
		
		
		if ($("#videofilepath").length > 0) {
			if (!file_validation(document.ff.videofilepath.value, 'n', '<?php echo $cm->allow_video_ext; ?>')){ return false; }
		}
		
		if ($("#brochurefilepath").length > 0) {
			if (!file_validation(document.ff.brochurefilepath.value, 'n', '<?php echo $cm->allow_attachment_ext; ?>')){ return false; }
		}
		
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
});
</script>
<?php
include("foot.php");
?>