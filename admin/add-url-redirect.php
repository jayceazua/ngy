<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New URL Redirect";
$ms = round($_GET["id"], 0);

if ($ms > 0){
	$sql="select * from tbl_page_301 where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
			
		$link_name = "Modify Existing URL Redirect";
	}else{
		$ms = 0;
	}
}

$icclass = "leftsettingsicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#urlred_ff").submit(function(){
		if(!validate_text(document.ff.oldurl,1,"Please enter OLD URL/PATH")){
			return false;
		}
		
		if(!validate_text(document.ff.newurl,1,"Please enter NEW URL/PATH")){
			return false;
		}
		
		return true; 
	});
});
</script>

<form method="post" action="url-redirect-sub.php" id="urlred_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
              <td width="35%" align="left"><span class="fontcolor3">* </span>Old Url/Path:</td>
              <td width="65%" align="left">
              <input type="text" id="oldurl" name="oldurl" value="<?php echo $oldurl; ?>" class="inputbox inputbox_size4" /><br />
              Ex: /path1/path2/ OR /path1.php
              </td>
        </tr>
        
        <tr>
              <td width="" align="left"><span class="fontcolor3">* </span>New Url/Path:</td>
              <td width="" align="left">
              <input type="text" id="newurl" name="newurl" value="<?php echo $newurl; ?>" class="inputbox inputbox_size4" /><br />
              Ex: /newpath1/ OR /newpath1/newpath2/
              </td>
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