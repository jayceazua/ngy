<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$sectionid = round($_GET["sectionid"], 0);
$ms = round($_GET["id"], 0);
$status_id = 1;

$connected_to = 1;
$make_id = 0;
$page_id = 0;
$link_url = '';
$inside_top_nav = 0;

$logoconnectcss1 = '';
$logoconnectcss2 = ' com_none';
$logoconnectcss3 = ' com_none';

if ($sectionid == 2){
	$link_name = "Brand Box - Yacht Page";
}elseif ($sectionid == 3){
	$link_name = "Brand Box - Catamaran Page";
}else{
	$sectionid = 1;
	$link_name = "Brand Box - Home Page";
}
if ($ms > 0){

	$sql="select * from tbl_brand_specific where id = '". $cm->filtertext($ms) ."' and section_id = '". $sectionid ."'";
	$result = $db->fetch_all_array($sql);
	$found = count($result); 
	
	$row = $result[0];
	foreach($row AS $key => $val){
		${$key} = $cm->filtertextdisplay($val);	
	}
	
	if ($make_id > 0){
		$connected_to = 2;
		$logoconnectcss1 = ' com_none';
		$logoconnectcss2 = '';
		$logoconnectcss3 = ' com_none';
	}elseif ($page_id > 0){
		//default value
	}elseif ($link_url != ""){
		$connected_to = 3;
		$logoconnectcss1 = ' com_none';
		$logoconnectcss2 = ' com_none';
		$logoconnectcss3 = '';
	}else{
		$connected_to = 0;
		$logoconnectcss1 = ' com_none';
		$logoconnectcss2 = ' com_none';
		$logoconnectcss3 = ' com_none';
	}
	$link_name .= " - " . $name;
}else{
	$ms = 0;
}

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;
include("head.php");
?>

<form method="post" action="brand-box-sub.php" id="brand_box_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $sectionid; ?>" name="section_id" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
            <td width="35%" align="left"><span class="fontcolor3">* </span>Box Title:</td>
            <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
          
        <tr>
			<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Connect To:</td>
			<td width="" align="left" valign="top" class="tdpadding1">
			   <div class="formfield_left clearfixmain">
					<select name="connected_to" id="connected_to" class="htext combobox_size4">
						<option value="0"<?php if ($connected_to == 0){ echo ' selected="selected"'; }?>>None</option>
						<option value="1"<?php if ($connected_to == 1){ echo ' selected="selected"'; }?>>CMS Page</option>
						<option value="2"<?php if ($connected_to == 2){ echo ' selected="selected"'; }?>>Manufacture</option>
						<option value="3"<?php if ($connected_to == 3){ echo ' selected="selected"'; }?>>Link URL</option>					
					</select>
				</div>
				<div class="formfield_right clearfixmain">            	
					<div class="logoconnect logoconnect1<?php echo $logoconnectcss1; ?> clearfixmain">
						<select name="page_id" id="page_id" class="htext combobox_size4">
							<option value="">Select</option>
							<?php
							echo $adm->get_page_combo_direct($page_id);
							?>
						</select>
					</div>
					<div class="logoconnect logoconnect2<?php echo $logoconnectcss2; ?> clearfixmain">
						<select name="make_id" id="make_id" class="htext combobox_size4">
							<option value="">Select</option>
							<?php
							echo $yachtclass->get_manufacturer_combo($make_id, 0, 1);
							?>
						</select>
					</div>
					<div class="logoconnect logoconnect3<?php echo $logoconnectcss3; ?> clearfixmain">
						<input type="text" id="link_url" name="link_url" value="<?php echo $link_url; ?>" class="inputbox inputbox_size4" />
					</div>
				</div>
			</td>
		</tr> 
   
    <tr>
        <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Box Message:</td>
        <td width="" align="left" valign="top"><textarea name="description" id="description" rows="1" cols="1" class="textbox textbox_size6"><?php echo $description;?></textarea></td>
    </tr>
    
	<?php if ($imagepath != ""){ ?>
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Box Image:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <img src="../brandboximage/<?php echo $imagepath; ?>" border="0" width="100" /><br />
        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imagepath','tbl_brand_specific','id','brandboximage')">Delete Image</a>
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Box Image [w: <?php echo $imw; ?>px, h: <?php echo $imh ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox inputbox_size4" /></td>
    </tr>
    <?php } ?>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Box Image Alt Text:</td>
        <td width="" align="left"><input type="text" id="bgimagealt" name="bgimagealt" value="<?php echo $bgimagealt; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <?php if ($logoimage != ""){ ?>
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Logo Image:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <img src="../brandboximage/<?php echo $logoimage; ?>" border="0" width="100" /><br />
        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','logoimage','tbl_brand_specific','id','brandboximage')">Delete Image</a>
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Logo Image [w: <?php echo $cm->brand_box_logo_im_width; ?>px, h: <?php echo $cm->brand_box_logo_im_height ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext1; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="logoimage" name="logoimage" class="inputbox inputbox_size4" /></td>
    </tr>
    <?php } ?>
    
    <?php if ($ms > 0){ ?>
	<tr>
		<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
		<td width="" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
	</tr>
	<?php } ?>
   
    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left"><button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button></td>
    </tr>
    </table>
</form>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#brand_box_ff").submit(function(){
		var ms = $("#ms").val();
		ms = parseInt(ms);
	
		if(!validate_text(document.ff.name,1,"Please enter Box Title")){
			return false;
		}	 
		
		if ($("#imgpath").length > 0) {
			if (!image_validation(document.ff.imgpath.value, 'y', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}
		
		if ($("#logoimage").length > 0) {
			if (!image_validation(document.ff.logoimage.value, 'y', '<?php echo $cm->allow_image_ext1; ?>')){ return false; }
		}
		
		var connected_to = parseInt($("#connected_to").val());
		if (connected_to == 3){
			$("#page_id").val(0);
			$("#make_id").val(0);
		}else if (connected_to == 2){
			$("#page_id").val(0);
			$("#link_url").val("");
		}else if (connected_to == 1){
			$("#make_id").val(0);
			$("#link_url").val("");
		}else{
			$("#page_id").val(0);
			$("#make_id").val(0);
			$("#link_url").val("");
		}
		
		if (ms > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
		}
		
		return true; 
	});
	
	$("#connected_to").click(function(){
		var connected_to = parseInt($(this).val());
		$(".logoconnect").addClass("com_none");
		$(".logoconnect" + connected_to).removeClass("com_none");		
	});
   
});
</script>
<?php
include("foot.php");
?>