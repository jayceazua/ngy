<?php
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New FAQ";
$ms = round($_GET["id"], 0);
$status_id = 1;

if ($ms > 0){
	$sql="select * from tbl_faq where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		
		$link_name = "Modify Existing FAQ";
	}else{
		$ms = 0;
	}
}
$icclass = "leftfaqicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#faq_ff").submit(function(){
     if(!validate_text(document.ff.f_question,1,"Please enter Question")){
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

<form method="post" action="faq_sub.php" id="faq_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
      <td width="35%" align="left"><span class="fontcolor3">* </span>Question:</td>
      <td width="65%" align="left"><input type="text" id="f_question" name="f_question" value="<?php echo $f_question; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
       <tr>
            <td width="" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong>Answer</strong>:</td>
       </tr>
       <tr>
            <td width="" align="center" colspan="2" class="tdpadding1">
              <?php
                $editorstylepath = "";
                $editorextrastyle = "adminbodyclass text_area";
                $cm->display_editor("f_answer", $sBasePath, "100%", 350, $f_answer, $editorstylepath, $editorextrastyle);
              ?>
            </td>
       </tr>

    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext">
                <option value="">Select</option>
                <?php
                $adm->get_commonstatus_combo($status_id);
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
<?php
include("foot.php");
?>