<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Boat Type Box";
$ms = round($_GET["id"], 0);
$status_id = 1;
$new_window = "n";

$link_type = 2;
$cms_style3_b = "visibility: visible;";
$cms_style3_a = "display: none; visibility: hidden;";

if ($ms <= 0 OR $ms >= 11){ $ms = 1; }

$sql="select * from tbl_boat_type_specific where id = '". $cm->filtertext($ms) ."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 	

$row = $result[0];

foreach($row AS $key => $val){
	${$key} = $cm->filtertextdisplay($val);
}
		
$link_name .= " - " . $name;

$imw = $cm->boattype_box_im_width;
$imh = $cm->boattype_box_im_height;
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#boattype_box_ff").submit(function(){
		var ycdataid = parseInt($("#ycdataid").val());		
		if(!validate_text(document.ff.name,1,"Please enter Box Title")){
			return false;
		}	 
		
		if ($("#imgpath").length > 0) {
			if (!image_validation(document.ff.imgpath.value, 'y', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}
		
		if (ycdataid == 0){
			if(!validate_text(document.ff.int_page_sel,1,"Please select Page")){
				return false;
			}
		}
		
		return true; 
	});
   
});
</script>

<form method="post" action="boattype-box-sub.php" id="boattype_box_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $ycdataid; ?>" id="ycdataid" name="ycdataid" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
          <td width="35%" align="left"><span class="fontcolor3">* </span>Box Title:</td>
          <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr> 
    
		<?php if ($imagepath != ""){ ?>
            <tr>
             <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Image:</td>
             <td width="" align="left" valign="top" class="tdpadding1">
             <img src="../boattypeboximage/<?php echo $imagepath; ?>" border="0" width="100" /><br />
             <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imagepath','tbl_boat_type_specific','id','boattypeboximage')">Delete Image</a>
             </td>
        	</tr>
        <?php }else{ ?>
            <tr>
             <td width="" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Image [w: <?php echo $imw; ?>px, h: <?php echo $imh ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
             <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox" size="65" /></td>
            </tr>
        <?php } ?>

    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Link:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <?php
		if ($ycdataid > 0){
			$caturl = $cm->get_page_url($ycdataid, 'typybyid');
			echo '<strong>'. $caturl .'</strong>';
		}else{
		?>
        <select name="int_page_id" id="int_page_id" class="htext combobox_size4">
        <?php
		echo $adm->get_page_combo_direct($int_page_id);
		?>
        </select>
        <?php
		}
		?>
        </td>
    </tr>

    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left"><button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button></td>
    </tr>
    </table>
</form>
<?php
include("foot.php");
?>