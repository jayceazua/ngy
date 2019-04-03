<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Boat Type Boxes";

$sql = "select * from tbl_boat_type_specific order by id";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$_SESSION["bck_pg"] = "homepagebox.php";
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

		
<form method="post" action="boattypebox.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_boat_type_specific" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
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
                   <td class="displaytdheading" width="75%" align="left">Name</td>
                   <td class="displaytdheading" align="center">Image</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $name = $row['name'];                 
                 $imagepath = $row['imagepath'];
			 ?>     
			 <tr>  
                  <td class="displaytd1" align="center"><a href="add-boattype-box.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" width="" align="center"><?php if ($imagepath != ""){?><img src="../boattypeboximage/<?php echo $imagepath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>         
            </tr>			 
			 <?php
			  $rc_count++;
			 }
			 ?>			 
		   </table>	 
		   
		   </td>		   
		</tr>
	   </table>	
	  
</form>

<?php
include("foot.php");
?>