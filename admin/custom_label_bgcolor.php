<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Custom Label Background and Text Color";
$sql = "select * from tbl_custom_label order by rank";
$result = $db->fetch_all_array($sql);
$found = count($result);
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

<form method="post" action="custom_label_bgcolor_sub.php" id="custom_ff" name="ff" enctype="multipart/form-data">
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    	<?php
		$counter = 0;
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $id, "custom_label_id");
			$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
			$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
			
			if ($custom_label_bgcolor == ""){
				$custom_label_bgcolor = '0879dc';
			}
			if ($custom_label_textcolor == ""){
				$custom_label_textcolor = 'ffffff';
			}
		?>
        
        <tr>
            <td width="35%" align="left"><strong><?php echo $name; ?>:</strong></td>
            <td width="65%" align="left">
            	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="htext">
                	<tr>
                    	<td width="20%" align="left">Background:</td>
                        <td width="25%" align="left"><input type="text" id="custom_label_bgcolor<?php echo $counter;?>" name="custom_label_bgcolor<?php echo $counter;?>" value="<?php echo $custom_label_bgcolor; ?>" class="inputbox inputbox_size4 jscolor" /></td>
                        <td width="" align="left">&nbsp;</td>
                        <td width="20%" align="left">Text:</td>
                        <td width="25%" align="left"><input type="text" id="custom_label_textcolor<?php echo $counter;?>" name="custom_label_textcolor<?php echo $counter;?>" value="<?php echo $custom_label_textcolor; ?>" class="inputbox inputbox_size4 jscolor" /></td>
                    </tr>
                </table>
            
           		<input type="hidden" value="<?php echo $id; ?>" name="custom_label_id<?php echo $counter;?>" name="custom_label_id<?php echo $counter;?>" />
            	
            </td>
        </tr>
        
        <?php
			$counter++;
		}
		?>      
      
        <tr>           
            <td width="" align="left" colspan="2"><button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button></td>
        </tr>
    </table>
</form>
<?php
include("foot.php");
?>