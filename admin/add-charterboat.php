
<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Charter Boat";
$ms = round($_GET["id"], 0);
$status_id = 1;
$lengthm = '';

if ($ms > 0){
	$sql = "select * from tbl_boat_charter where id = :ms";	
	$pdo_param = array(
		array(
			"id" => "ms",
			"value" => $ms,
			"c" => "PARAM_INT"
		)				
	);
	
	$result = $db->pdo_select($sql, $pdo_param);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		
		$lengthm = $yachtclass->feet_to_meter($length) . ' M';
		$make_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $make_id);
		
		$link_name = "Modify Existing Charter Boat";
	}else{
		$ms = 0;
	}
}
$icclass = "leftlistingicon";
include("head.php");
?>
<form method="post" action="charterboat-sub.php" id="charterboat-ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    
    <div class="singleblock">
    	<div class="singleblock_heading"><span>Specification</span></div>
        <div class="singleblock_box">
        	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                    <td width="20%" align="left"><span class="fontcolor3">* </span>Boat Name:</td>
                    <td width="30%" align="left"><input type="text" value="<?php echo $boat_name; ?>" id="boat_name" name="boat_name" class="inputbox inputbox_size4" autocomplete="off" /></td>
                    <td width="20%" align="left"><span class="fontcolor3">* </span>Builder:</td>
                    <td width="30%" align="left">
                    	<input type="text" value="<?php echo $make_name; ?>" id="make_name" name="make_name" connectedfield="make_id" ckpage="1" class="azax_suggest azax_suggest1 inputbox inputbox_size4" autocomplete="off" /><div id="suggestsearch1" class="suggestsearch com_none"></div>
                        <input type="hidden" value="<?php echo $make_id; ?>" name="make_id" id="make_id" />
                    </td>
                </tr>
                
            	<tr>
                    <td width="" align="left"><span class="fontcolor3">* </span>Year:</td>
                    <td width="" align="left"><select name="year" id="year" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_year_combo($year);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Guest:</td>
                    <td width="" align="left"><select name="guest" id="guest" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($guest);
                            ?>
                        </select></td>
                </tr>
                
                <tr>
                    <td width="" align="left"><span class="fontcolor3">* </span>Cabin:</td>
                    <td width="" align="left"><select name="cabin" id="cabin" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($cabin);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Crew</td>
                    <td width="" align="left"><select name="crew" id="crew" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($crew);
                            ?>
                        </select></td>
                </tr>
                
                <tr>
                	<td width="" align="left"><span class="fontcolor3">* </span>Length [in Meter]:</td>
                    <td width="" align="left"><input type="text" id="length" name="length" value="<?php echo $length; ?>" class="meterconvert inputbox inputbox_size4_a" insplit="0" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> <span class="lengthm fontbold"><?php echo $lengthm; ?></span></td>
                   	<td width="" align="left"><span class="fontcolor3">* </span>Max Speed [in KT]:</td>
                    <td width="" align="left"><input type="text" id="max_speed" name="max_speed" value="<?php echo $max_speed; ?>" class="inputbox inputbox_size4" /></td>
                </tr>
                
                <tr>                	
                   	<td width="" align="left"><span class="fontcolor3">* </span>Category:</td>
                    <td width="" align="left">
                    	<select name="category_id" id="category_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_category_combo($category_id);
                            ?>
                        </select>
                    </td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Subtitle:</td>
                    <td width="" align="left"><input type="text" id="subtitle" name="subtitle" value="<?php echo $subtitle; ?>" class="inputbox inputbox_size4" /></td>
                </tr>
                
                <tr>
                	<td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Price Per Day [US$]:</td>
                    <td width="" align="left"><input type="text" id="price_perday" name="price_perday" value="<?php echo $price_perday; ?>" class="inputbox inputbox_size4" /><br />If left blank or enter 0, <strong>Enquire Now</strong> text will display.</td>
                   	<td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Price Per Week [US$]:</td>
                    <td width="" align="left"><input type="text" id="price_perweek" name="price_perweek" value="<?php echo $price_perweek; ?>" class="inputbox inputbox_size4" /><br />If left blank or enter 0, <strong>Enquire Now</strong> text will display.</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Description</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="center" class="tdpadding1">
                        <?php
                        $editorstylepath = "";
                        $editorextrastyle = "adminbodyclass text_area";
                        $cm->display_editor("description", $sBasePath, "100%", 350, $description, $editorstylepath, $editorextrastyle);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Tender And Toy</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="left" class="tdpadding1">
                        <?php echo $charterboatclass->get_all_tendertoy_checkbox($ms);?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Cruising Area</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="left" class="tdpadding1">
                        <?php echo $charterboatclass->get_all_cruisingarea_checkbox($ms);?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Destination / Itinerary</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="left" class="tdpadding1">
                        <?php echo $charterboatclass->get_all_destination_checkbox($ms);?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Section Background Image</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<?php
					for($bgk = 1; $bgk <= 5; $bgk++){
						$bg_section = ${"bg_section" . $bgk};
						if ($bg_section != ""){
				?>
                		<tr>
                             <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image - Section <?php echo $bgk; ?>:</td>
                             <td width="65%" align="left" valign="top" class="tdpadding1">
                             <img src="../charterboat/listings/<?php echo $ms; ?>/background/<?php echo $bg_section; ?>" border="0" style="max-width: 200px;" /><br />
                             <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','bg_section<?php echo $bgk; ?>','tbl_boat_charter','id','charterboat/<?php echo $ms; ?>/background')">Delete Image</a>
                             </td>
                        </tr>
                <?php			
						}else{
				?>
                		<tr>
                             <td width="35%" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Background Image - Section <?php echo $bgk; ?>:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                             <td width="65%" align="left" class="tdpadding1"><input type="file" id="bg_section<?php echo $bgk; ?>" name="bg_section<?php echo $bgk; ?>" class="inputbox inputbox_size4" /></td>
                        </tr>
                <?php			
						}
					}
				?>            
            </table>
        </div>
    </div>    
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Meta Information</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
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
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Display Status</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
                    <td width="65%" align="left" valign="top" class="tdpadding1">
                    <select name="status_id" id="status_id" class="htext">
                    <option value="">Select</option>
                    <?php
                    $adm->get_commonstatus_combo($status_id);
                    ?>
                    </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            <tr>
                <td width="" align="right">
                    <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
                </td>
            </tr>
        </table>
    </div>    
</form>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#charterboat-ff").submit(function(){
		var ms = parseInt($("#ms").val());
		
		//basic information
		if(!validate_text(document.ff.boat_name,1,"Please enter Boat Name")){
			return false;
		}
		
		var make_id = $('#make_id').val();
		if (make_id == 0){
			alert ("Please select Builder");
			$('.make_name').focus();
			return false;
		}
		
		if(!validate_text(document.ff.year,1,"Please select Year")){
			return false;
		}
		
		if(!validate_text(document.ff.guest,1,"Please select Guest #")){
			return false;
		}
		
		if(!validate_text(document.ff.cabin,1,"Please select Cabin #")){
			return false;
		}
		
		if(!validate_text(document.ff.crew,1,"Please select Crew #")){
			return false;
		}
		
		if(!validate_pnumeric(document.ff.length,1,"Please enter Length")){
			return false;
		}
		
		if(!validate_pnumeric(document.ff.max_speed,1,"Please enter Max Speed")){
			return false;
		}
		
		if(!validate_numeric(document.ff.price_perday,0,"Please enter Price Per Day")){
			return false;
		}
		
		if(!validate_numeric(document.ff.price_perweek,0,"Please enter Price Per Week")){
			return false;
		}
		
		if(!validate_text(document.ff.category_id,1,"Please select Category")){
			return false;
		}
		
		if(!validate_text(document.ff.subtitle,1,"Please enter Subtitle")){
			return false;
		}
		
		for (var imk = 1; imk <= 5; imk++){
			if ($("#bg_section" + imk).length > 0) {
				if (!image_validation($("#bg_section" + imk).val(), 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
			}
		}		
		
		if(!validate_text(document.ff.status_id,1,"Please enter Display Status")){
			return false;
		}
			
		return true; 
	});
	
	$(".meterconvert").keyup(function(){
		var insplit = $(this).attr('insplit');        		
        var convertval = $(this).attr('convertval');		
		if (insplit == 1){
			var converttarget = $(this).attr('converttarget');
			var ft_val = number_round($('#' + converttarget + '_ft').val());
			var in_val = number_round($('#' + converttarget + '_in').val());
			var cval = ft_val+ (in_val/12);
		}else{
			var converttarget = $(this).attr('id');
			var cval = $(this).val();
		}
		
        var meter_value = cval * convertval;
        meter_value = number_round(meter_value);
        $('.' + converttarget + 'm').html(meter_value + ' M');
    });
});
</script>
<?php
include("foot.php");
?>