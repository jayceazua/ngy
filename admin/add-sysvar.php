<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "System Settings - Add";
$ms = round($_GET["id"], 0);
$category_id = round($_GET["category_id"], 0);

$code = $_SESSION["s_code"];
$name = $_SESSION["s_name"];
$pdes = $_SESSION["s_pdes"];
$field_value = $_SESSION["s_field_value"];
$rank = $_SESSION["s_rank"];

if ($ms > 0){
	$sql = "select * from tbl_systemvar where id = '".$ms."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 
	
	if ($found > 0){
		$row = $result[0];
		$id = $row['id'];	
		$category_id = $row['category_id'];		
		$code = htmlspecialchars($row['code']);
		$name = htmlspecialchars($row['name']);
		$pdes = htmlspecialchars($row['pdes']);	
		$field_value = htmlspecialchars($row['field_value']);								
		$rank = $row['rank'];
		
		$link_name = "System Settings - Modify"; 
	}else{
		$ms = 0;
	}
}
$categoryname = $cm->get_common_field_name('tbl_syscategory', 'name', $categoryid);
//$link_name .= " - " . $categoryname;
$icclass = "leftsettingsicon";
include("head.php");
?>

<script language="javascript" type="text/javascript">
  function check(){  
	 
	 if(!validate_text(document.ff.code,1,"Please enter Code")){
	    return false;
	 }
	 
	 if(!validate_text(document.ff.name,1,"Please enter Name")){
	    return false;
	 }
	 
	 if(!validate_text(document.ff.field_value,1,"Please enter Field Value")){
	    return false;
	 }
	 
	 if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
	     return false;
	 }
     
     return true;   
   }
</script>
	<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
    <tr>
     <td width="100%" align="center" class="tdpadding1"><span class="fontcolor3">

     <?php
     if ($_SESSION["postmessage"] == "blnk"){
     ?>
     Please enter CODE.
     <?php
     }
     ?>

     <?php
     if ($_SESSION["postmessage"] == "ext"){
     ?>
     Code <strong>'<?php echo $_SESSION["s_code"]; ?>'</strong> already exist, please try another code.
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

    <form method="post" action="sysvar-sub.php" name="ff" onsubmit="return check()" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $category_id; ?>" name="category_id" />
    <input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
    <input type="hidden" value="<?php echo $code; ?>" name="oldcode" />
    
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext"> 
          
           <?php
		   if ($ms > 0){
		   ?>
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Code [5 digit and Unique]:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><strong><?php echo $code; ?></strong><input type="hidden" value="<?php echo $code; ?>" name="code" /></td>
           </tr>
           <?php
		   }else{
		   ?>
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Code [5 digit and Unique]:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" name="code" class="inputbox inputbox_size4" value="<?php echo $code; ?>" /></td>
           </tr>
           <?php
		   }
		   ?>
           
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Name:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" name="name" class="inputbox inputbox_size1" value="<?php echo $name; ?>" /></td>
           </tr> 
           
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Description (Max. 255 characters):</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><textarea name="pdes" rows="1" cols="1" class="textbox textbox_size1"><?php echo $pdes; ?></textarea></td>
           </tr>
           
           <?php
		   if ($ms == 23){
		   ?>
           
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Default View:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1">
            <input class="radiobutton" type="radio" name="field_value" value="1" <?php if ($field_value == 1){?>checked="checked"<?php }?> />Grid View&nbsp;&nbsp;
            <input class="radiobutton" type="radio" name="field_value" value="2" <?php if ($field_value == 2){?>checked="checked"<?php }?> />List View
            </td>
           </tr>
           
           <?php
		   }else{
		   ?>
           
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Field Value:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><textarea name="field_value" rows="1" cols="1" class="textbox textbox_size1"><?php echo $field_value; ?></textarea></td>
           </tr> 
           <?php
		   }
		   ?>   
           
           <tr>
            <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
            <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
           </tr>      
           
           <tr>
              <td width="35%" align="left">&nbsp;</td>
              <td width="65%" align="left">
                  <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
              </td>
            </tr>
         </table>
   </form>
<?php
include("foot.php");
?>