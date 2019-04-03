<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Blog Category";
$ms = round($_GET["id"], 0);
$parentid = round($_GET["parentid"], 0);
$status_id = 1;

if ($ms > 0){
	$sql="select * from tbl_blog_category where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
			
		$link_name = "Modify Existing Blog Category";
	}else{
		$ms = 0;
	}
}

$icclass = "leftblogicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#category_ff").submit(function(){
		if(!validate_text(document.ff.name,1,"Please enter Name")){
			return false;
		}
		
		var found = "<?php echo $found;?>";
		found = eval(found);
		if (found > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
		}
		return true; 
	});
});
</script>

<form method="post" action="blog_category_sub.php" id="category_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $parentid; ?>" id="parentid" name="parentid" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
          <td width="35%" align="left"><span class="fontcolor3">* </span>Category Name:</td>
          <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="combobox_size6 htext">
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