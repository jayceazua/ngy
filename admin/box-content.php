<?php
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$s = round($_GET["s"], 0);	
//if ($s < 1 OR $s > 5){ $s = 1;}			

$sql = "select * from tbl_box_content where id = '". $cm->filtertext($s)."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 
$row = $result[0];
$name = htmlspecialchars($row['name']);	
$caption = htmlspecialchars($row['caption']);		
$pdes = $row['pdes'];
$link_name = $name;
$icclass = "leftboxicon";
include("head.php");
?>
	<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">
	     <?php
		 if ($_SESSION["postmessage"] == "nw"){
		 ?>
		 Record added successfully.
		 <?php
		 }
		 ?> 
		 
		 <?php
		 if ($_SESSION["postmessage"] == "up"){
		 ?>
		 Record updated successfully.
		 <?php
		 }
		 ?> 
		 
		 <?php
		 if ($_SESSION["postmessage"] == "dels"){
		 ?>
		 Record deleted successfully.
		
		 <?php
		 }
		 ?>
		 
		 <?php
		 if ($_SESSION["postmessage"] == "stordr"){
		 ?>
		 New Sort Order for record(s) saved successfully.
		 <?php
		 }
		 ?> 
	   </span></td>
	 </tr>
	</table>    
	<?php $_SESSION["postmessage"] = ""; } ?>
	

            <form method="post" action="box_content_sub.php" id="box_ff" name="ff"  enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $s; ?>" name="s">
            <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
			
		   <tr>
            <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;&nbsp;<strong>Box Content</strong>:</td>            
           </tr> 
		   
		   <tr>              
            <td width="100%" align="center" colspan="2" class="tdpadding1">
            <?php 
			$editorstylepath = "";
            if ($s == 1){
                $editorextrastyle = "adminbodyclass main text_area home-tiitle";
            }

            if ($s == 2){
                $editorextrastyle = "adminbodyclass main text_area services";
            }

			$cm->display_editor("pdes", $sBasePath, "100%", 400, $pdes, $editorstylepath, $editorextrastyle);
			?>            
            </td>
           </tr>
		
            <tr>
              <td width="35%" align="left">&nbsp;</td>
              <td width="65%" align="right"><button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button></td>
            </tr>
            </table>
           </form>

<?php
include("foot.php");
?>