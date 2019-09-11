<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Useful Stats";

$sql = "select * from tbl_stats where status_id = 1 order by rank";
$result = $db->fetch_all_array($sql);
$found = count($result);

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#useful-stats-ff").submit(function(){
		
		var allok = 1;
		$(".valcheck").each(function(){
			var idd = $(this).attr('id');
			if(!validate_pnumeric(document.getElementById(idd),1,"Please enter valid Value")){
				allok = 0;		
				return false;			  
			}
		});
		
		if (allok == 1){
			return true;
		}else{
			return false;
		}
	});
});
</script>

<?php
if ($found > 0){
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

<form method="post" action="useful-stats-sub.php" id="useful-stats-ff" name="ff" enctype="multipart/form-data">
	<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    	<tr>
        	<td width="50%" align="left"><span class="subhead">Stat</span></td>
            <td width="25%" align="left"><span class="subhead">Current Value</span></td>
            <td width="25%" align="left"><span class="subhead">Max Value</span></td>
        </tr>
    
	<?php
	$counter = 0;
	foreach($result as $row){
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
	?>
    	<tr>
        	<td width="50%" align="left"><?php echo $name; ?><input type="hidden" value="<?php echo $id; ?>" id="id<?php echo $counter; ?>" name="id<?php echo $counter; ?>" /></td>
            <td width="25%" align="left"><input type="text" id="min_value<?php echo $counter; ?>" name="min_value<?php echo $counter; ?>" value="<?php echo $min_value; ?>" class="inputbox inputbox_size4 valcheck" /></td>
            <td width="25%" align="left"><input type="text" id="max_value<?php echo $counter; ?>" name="max_value<?php echo $counter; ?>" value="<?php echo $max_value; ?>" class="inputbox inputbox_size4 valcheck" /></td>
        </tr>
    <?php
		$counter++;
	}
	?>
    	<tr>
        	<td colspan="3" align="left"><button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button></td>
        </tr>
    </table>    
</form>

<?php
}
?>

<?php
include("foot.php");
?>