<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Model Search Firlds";
$sql = "select * from tbl_model_search_fields where id = 1";
$result = $db->fetch_all_array($sql);
$row = $result[0];
foreach($row AS $key => $val){
	${$key} = $cm->filtertextdisplay($val);
}
$fieldlists_ar = json_decode($fieldlists);
$icclass = "leftlistingicon";
include("head.php");
?>
<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">	    		 
		 <?php if ($_SESSION["postmessage"] == "up"){ ?>
		 Record updated successfully.
		 <?php } ?>
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<form method="post" action="model-search-fields-sub.php" id="modelsearchfields" name="ff" enctype="multipart/form-data">
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">    	
        <?php
		$search_fields_list_ar = json_decode($modelclass->search_fields_ar());
		
		foreach($search_fields_list_ar as $key => $search_fields_row){
			$search_field_id = $search_fields_row->id;
			$search_field_name = $search_fields_row->name;
			
			$selected = '';
			foreach($fieldlists_ar as $obj){
				if ($obj == $search_field_id){
					$selected = ' checked="checked"';
					break;
				}
			}
		?>
		<tr>
			<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span><?php echo $search_field_name; ?>:</td>
			<td width="65%" align="left" valign="top" class="tdpadding1"><input type="checkbox" id="search_fields<?php echo $key; ?>" name="search_fields[]" value="<?php echo $search_field_id; ?>"<?php echo $selected; ?> /> Activate</td>
		</tr>
		<?php
		}
		?>
        
        <tr>
            <td width="" align="left">&nbsp;</td>
            <td width="" align="left">
                <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
            </td>
        </tr>
    </table>
</form>
<?php
include("foot.php");
?>