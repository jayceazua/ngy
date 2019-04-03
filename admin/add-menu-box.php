<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Top Nav Menu Box";
$ms = round($_GET["id"], 0);
$status_id = 1;

$connected_to = 1;
$make_id = 0;
$page_id = 0;
$link_url = '';
$inside_top_nav = 0;

$connected_to2 = 1;
$make_id2 = 0;
$page_id2 = 0;
$link_url2 = '';

$logoconnectcss1 = '';
$logoconnectcss2 = ' com_none';
$logoconnectcss3 = ' com_none';

$logoconnectcss1_2 = '';
$logoconnectcss2_2 = ' com_none';
$logoconnectcss3_2 = ' com_none';

$sql="select * from tbl_menu_box where id = '". $cm->filtertext($ms) ."'";
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
	
	$connected_to2 = 2;
	$logoconnectcss1_2 = ' com_none';
	$logoconnectcss2_2 = '';
	$logoconnectcss3_2 = ' com_none';
}elseif ($page_id > 0){
	//default value
}elseif ($link_url != ""){
	$connected_to = 3;
	$logoconnectcss1 = ' com_none';
	$logoconnectcss2 = ' com_none';
	$logoconnectcss3 = '';
	
	$connected_to2 = 3;
	$logoconnectcss1_2 = ' com_none';
	$logoconnectcss2_2 = ' com_none';
	$logoconnectcss3_2 = '';
}else{
	$connected_to = 0;
	$logoconnectcss1 = ' com_none';
	$logoconnectcss2 = ' com_none';
	$logoconnectcss3 = ' com_none';
	
	$connected_to2 = 0;
	$logoconnectcss1_2 = ' com_none';
	$logoconnectcss2_2 = ' com_none';
	$logoconnectcss3_2 = ' com_none';
}
		
$link_name .= " - " . $name;

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;
include("head.php");
?>

<form method="post" action="menu-box-sub.php" id="brand_box_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
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
    
	<?php if ($imagepath != ""){ ?>
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <img src="../menuboximage/<?php echo $imagepath; ?>" border="0" width="100" /><br />
        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imagepath','tbl_menu_box','id','menuboximage')">Delete Image</a>
        </td>
    </tr>
    <?php }else{ ?>
    <tr>
        <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Image [w: <?php echo $imw; ?>px, h: <?php echo $imh ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
        <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox" size="65" /></td>
    </tr>
    <?php } ?>
    
    <tr>
			<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>View All Link:</td>
			<td width="" align="left" valign="top" class="tdpadding1">
			   <div class="formfield_left clearfixmain">
					<select name="connected_to2" id="connected_to2" class="htext combobox_size4">
						<option value="0"<?php if ($connected_to2 == 0){ echo ' selected="selected"'; }?>>None</option>
						<option value="1"<?php if ($connected_to2 == 1){ echo ' selected="selected"'; }?>>CMS Page</option>
						<option value="2"<?php if ($connected_to2 == 2){ echo ' selected="selected"'; }?>>Manufacture</option>
						<option value="3"<?php if ($connected_to2 == 3){ echo ' selected="selected"'; }?>>Link URL</option>					
					</select>
				</div>
				<div class="formfield_right clearfixmain">            	
					<div class="logoconnect_2 logoconnect1_2<?php echo $logoconnectcss1_2; ?> clearfixmain">
						<select name="page_id2" id="page_id2" class="htext combobox_size4">
							<option value="">Select</option>
							<?php
							echo $adm->get_page_combo_direct($page_id2);
							?>
						</select>
					</div>
					<div class="logoconnect_2 logoconnect2_2<?php echo $logoconnectcss2_2; ?> clearfixmain">
						<select name="make_id2" id="make_id2" class="htext combobox_size4">
							<option value="">Select</option>
							<?php
							echo $yachtclass->get_manufacturer_combo($make_id2, 0, 1);
							?>
						</select>
					</div>
					<div class="logoconnect_2 logoconnect3_2<?php echo $logoconnectcss3_2; ?> clearfixmain">
						<input type="text" id="link_url" name="link_url" value="<?php echo $link_url; ?>" class="inputbox inputbox_size4" />
					</div>
				</div>
			</td>
	</tr>
    
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
		
		var connected_to2 = parseInt($("#connected_to2").val());
		if (connected_to2 == 3){
			$("#page_id2").val(0);
			$("#make_id2").val(0);
		}else if (connected_to2 == 2){
			$("#page_id2").val(0);
			$("#link_url2").val("");
		}else if (connected_to2 == 1){
			$("#make_id2").val(0);
			$("#link_url2").val("");
		}else{
			$("#page_id2").val(0);
			$("#make_id2").val(0);
			$("#link_url2").val("");
		}
		
		if (ms > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
		}
		
		return true; 
	});
	
	$("#connected_to").change(function(){
		var connected_to = parseInt($(this).val());
		$(".logoconnect").addClass("com_none");
		$(".logoconnect" + connected_to).removeClass("com_none");		
	});
	
	$("#connected_to2").change(function(){
		var connected_to2 = parseInt($(this).val());
		$(".logoconnect_2").addClass("com_none");
		$(".logoconnect" + connected_to2 + "_2").removeClass("com_none");		
	});
   
});
</script>
<?php
include("foot.php");
?>