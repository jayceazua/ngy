<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$sectionid = round($_GET["sectionid"], 0);
$link_name = "Add New Logo";

if ($sectionid == 2){
	$link_name .= " - For Pre-owned Sailboats and Catamarans";
}else{
	$sectionid = 1;
	$link_name .= " - For Pre-Owned Yachts";
}

$ms = round($_GET["id"], 0);
$crop_option = 0;
$status_id = 1;

$connected_to = 1;
$make_id = 0;
$page_id = 0;
$logoconnectcss1 = '';
$logoconnectcss2 = ' com_none';

if ($ms > 0){
	$sql="select * from tbl_logo_scroll where id = '". $cm->filtertext($ms) ."' and section_id = '". $sectionid ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		
		/*if ($page_id > 0){
			$connected_to = 1;
			$logoconnectcss1 = '';
			$logoconnectcss2 = ' com_none';
		}*/
		
		if ($make_id > 0){
			$connected_to = 2;
			$logoconnectcss1 = ' com_none';
			$logoconnectcss2 = '';
		}
		
		$link_name = "Modify Existing Logo";
	}else{
		$ms = 0;
	}
}
$icclass = "leftlogoscrollicon";
include("head.php");
?>
<form method="post" action="make-logo-sub.php" id="logo_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $sectionid; ?>" name="section_id" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">

    <tr>
        <td width="35%" align="left"><span class="fontcolor3">* </span>Name:</td>
        <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Connect To:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
           <div class="formfield_left clearfixmain">
				<select name="connected_to" id="connected_to" class="htext combobox_size4">
					<option value="1"<?php if ($connected_to == 1){ echo ' selected="selected"'; }?>>CMS Page</option>
					<option value="2"<?php if ($connected_to == 2){ echo ' selected="selected"'; }?>>Manufacture</option>					
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
            </div>
        </td>
    </tr>
    
    <?php if ($imgpath != ""){ ?>
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <img src="../logoscrollimage/<?php echo $imgpath; ?>" border="0" width="100" /><br />
        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imgpath','tbl_logo_scroll','id','logoscrollimage')">Delete Image</a>
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Image [w: <?php echo $cm->make_logo_scroll_im_width; ?>px, h: <?php echo $cm->make_logo_scroll_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox inputbox_size4" /></td>
    </tr>        
    <?php } ?>
    
	<tr>
		<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
		<td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext combobox_size1">
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
	$("#logo_ff").submit(function(){
		if(!validate_text(document.ff.name,1,"Please enter Name")){
			return false;
		}
		
		if ($("#imgpath").length > 0) {
			if (!image_validation(document.ff.imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}		
		
		var ms = $("#ms").val();
		ms = parseInt(ms);
		if (ms > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
		}

		var connected_to = parseInt($("#connected_to").val());
		if (connected_to == 2){
			$("#page_id").val(0);
		}else{
			$("#make_id").val(0);
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