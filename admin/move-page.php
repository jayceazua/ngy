<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = $atm1 = "Move Page";

$add_edit_message = "Move";
$ms = round($_GET["id"], 0);

$sql = "select parent_id, name from tbl_page where id = '".$ms."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 

if ($found == 0){
  $_SESSION["admin_sorry"] = "You have selected an invalid page";
  header('location: sorry.php');
}

$row = $result[0];
$parent_id = $row['parent_id'];	
$name = $row['name'];
$icclass = "leftpageicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
  function check(){  
     
	 if(!validate_text(document.ff.parent_id,1,"Please select Move To Option")){
	    return false;
	 } 
	  
     return true;   
   }     
</script>

	<form method="post" action="move-page-sub.php" name="ff" onsubmit="return check()" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
	
	<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext"> 
                            
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Moving Page:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><strong><?php echo $name; ?></strong></td>
           </tr> 
		   
		   <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Move To:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><select name="parent_id" id="parent_id" class="htext">   
    <option value="">Select Page</option>   
    <?php
	if ($parent_id > 0){
	?> 
    <option value="0">Move to Top Lavel</option>
    <?php
	}
	?>          
    <?php
							echo $adm->get_page_combo_direct($page_id);
							?>           
   </select></td>
           </tr> 
		   
		   <tr>
              <td width="35%" align="left">&nbsp;</td>
              <td width="65%" align="left"><button type="submit" class="butta"><span class="moveIcon butta-space"><?php echo $add_edit_message; ?></span></button></td>
            </tr>                          
    </table>
		 		 
	</form>

<?php
include("foot.php");
?>