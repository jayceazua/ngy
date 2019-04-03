<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Our Exlusive Brand Boxes";

$sql = "select * from tbl_brand_specific order by rank";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$_SESSION["bck_pg"] = "brandbox.php";
$icclass = "leftboxicon";
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

		
<form method="post" action="brandbox.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_brand_specific" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
        	<tr>
				<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
			</tr>
        	
         	<tr>
				<td width="" align="right" valign="top"><button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button></td>
          	</tr>
          		  
			<tr>
				<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
			</tr>
		</table>	
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="55%" align="left">Name</td>
                   <td class="displaytdheading" width="" align="center">Top Nav Under</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $name = $row['name'];                 
                 $imagepath = $row['imagepath'];
				 $categoryrank = $row['rank'];
				 $inside_top_nav = $row['inside_top_nav'];
				 
				 if ($inside_top_nav == 1){
					 $inside_top_nav_d = 'Yes'; 
					 $ch_opt_dh = 0;
				 }else{
					 $inside_top_nav_d = 'No';
					 $ch_opt_dh = 1; 
			     }
			 ?>     
			 <tr>  
                  <td class="displaytd1" align="center"><a href="add-brand-box.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'inside_top_nav', 'tbl_brand_specific', '<?php echo $ch_opt_dh; ?>', 'id')"><?php echo $inside_top_nav_d; ?></a></td>
                  <td class="displaytd1" width="" align="center"><?php if ($imagepath != ""){?><img src="../brandboximage/<?php echo $imagepath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" align="center" nowrap="nowrap"><input type="text" class="inputboxcenter1 inputbox_size3" name="sortorder<?php echo $rc_count; ?>" id="sortorder<?php echo $rc_count; ?>" value="<?php echo $categoryrank; ?>" maxlength="5" /><input type="hidden" value="<?php echo $id; ?>" name="id<?php echo $rc_count; ?>" id="id<?php echo $rc_count; ?>" /></td>        
            </tr>			 
			 <?php
			  $rc_count++;
			 }
			 ?>			 
		   </table>	 
		   
		   </td>		   
		</tr>
	   </table>
	   
	   <table border="0" width="95%" cellspacing="0" cellpadding="0">
        	<tr>
				<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
			</tr>
        	
         	<tr>
				<td width="" align="right" valign="top"><button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button></td>
          	</tr>
          		  
			<tr>
				<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
			</tr>
		</table>
	  
</form>

<?php
include("foot.php");
?>