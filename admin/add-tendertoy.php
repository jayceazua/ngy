<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Tender and Toy";
$ms = round($_GET["id"], 0);
$featured = 0;
$status_id = 1;

if ($ms > 0){
	$sql = "select * from tbl_tendertoy where id = :ms";	
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
		$link_name = "Modify Existing Tender and Toy";
	}else{
		$ms = 0;
	}
}
$icclass = "leftlistingicon";
include("head.php");
?>
<form method="post" action="tendertoy-sub.php" id="tendertoy-ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">    
        <tr>
            <td width="35%" align="left"><span class="fontcolor3">* </span>Name:</td>
            <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
        </tr>
        
        <?php
        if ($iconpath != ""){
		?>
        <tr>
             <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Icon:</td>
             <td width="" align="left" valign="top" class="tdpadding1">
             <img src="../charterboat/tendertoy/<?php echo $iconpath; ?>" border="0" /><br />
             <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','iconpath','tbl_tendertoy','id','charterboat/tendertoy')">Delete Image</a>
             </td>
        </tr>
        <?php
        }else{
		?>
        <tr>
             <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Icon Image:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
             <td width="" align="left" class="tdpadding1"><input type="file" id="iconpath" name="iconpath" class="inputbox inputbox_size4" /></td>
        </tr>
        <?php
        }
		?>
        
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
            <td width="" align="left" valign="top" class="tdpadding1">
            <select name="status_id" id="status_id" class="htext">
            <option value="">Select</option>
            <?php
            $adm->get_commonstatus_combo($status_id);
            ?>
            </select>
            </td>
        </tr>
        
		<?php
        if ($ms > 0){
		?>
        <tr>
            <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
            <td width="" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size4" value="<?php echo $rank; ?>" /></td>
        </tr>
        <?php
        }
		?>        
        
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
	$("#tendertoy-ff").submit(function(){
		var ms = parseInt($("#ms").val());
		if(!validate_text(document.ff.name,1,"Please enter Name")){
			return false;
		}
		
		if ($("#iconpath").length > 0) {
			if (!image_validation(document.ff.iconpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}
		
		if(!validate_text(document.ff.status_id,1,"Please enter Display Status")){
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