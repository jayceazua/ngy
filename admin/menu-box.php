<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Top Nav Menu Boxes";

$sql = "select * from tbl_menu_box order by rank";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$_SESSION["bck_pg"] = "menu-box.php";
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

		
<form method="post" action="menu-box.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_menu_box" name="tblname" id="tblname" />
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
                   <td class="displaytdheading" width="" align="left">Box For</td>
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
				 
				 if ($id == 2){
					 $box_title = "Catamaran Box";
				 }else{
					 $box_title = "Yacht Box";
				 }
			 ?>     
			 <tr>  
                  <td class="displaytd1" align="center"><a href="add-menu-box.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $box_title; ?></td>
                  <td class="displaytd1" width="" align="center"><?php if ($imagepath != ""){?><img src="../menuboximage/<?php echo $imagepath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>
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