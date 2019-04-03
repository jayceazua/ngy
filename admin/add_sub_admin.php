<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Sub-Admin";
$ms = round($_GET["id"], 0);
$old_status_id = 0;
$old_d_username = $old_d_email = "";

if ($ms > 0){
	$sql="select * from tbl_sub_admin where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		$d_username = $old_d_username = htmlspecialchars($row['uid']);
    	$d_email = $old_d_email = htmlspecialchars($row['email']);
		$d_password = htmlspecialchars($edclass->txt_decode($row['pwd']));
		$name = htmlspecialchars($row['name']);
		$phone = htmlspecialchars($row['phone']);
		$status_id = $old_status_id = $row['status_id'];
		
		$link_name = "Modify Existing Sub-Admin";
	}else{
		$ms = 0;
	}
}
$icclass = "leftsubadminicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#sub_admin_ff").submit(function(){
	  //Login details
      if (!username_validation()){ return false; }
      if (!password_validation()){ return false; } 
      //end
	  
	  if(!validate_text(document.ff.name,1,"Please enter Name")){
        return false;
      }
	  
	  if(!validate_email(document.ff.d_email,1,"Please enter Email Address")){
          return false;
      }
	  
	  if(!validate_text(document.ff.phone,0,"Please enter Mobile Phone")){
          return false;
      }
	  	  	 	  
      return true; 
 });

});
</script>

<form method="post" action="sub_admin_sub.php" id="sub_admin_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
    <p>All fields marked with <span class="mandt_color">*</span> are mandatory.</p>
    <div class="singleblock">
        <div class="singleblock_heading"><span>Login Information</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Username [min 6 chars]:</td>
                    <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" id="d_username" name="d_username" value="<?php echo $d_username; ?>" currentval="<?php echo $old_d_username; ?>" fieldopt="1" class="checkvaliddata inputbox inputbox_size1" /> <span title="" id="checkvaliddatares1" class="butta-space">&nbsp;</span></td>
                </tr>
    
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Password [min 6 chars]:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="password" id="d_password" name="d_password" value="<?php echo $d_password; ?>" class="inputbox inputbox_size1" /></td>
                </tr>
    
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Confirm Password:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="password" id="cd_password" name="cd_password" value="<?php echo $d_password; ?>" class="inputbox inputbox_size1" /></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        	<div class="singleblock_heading"><span>General Information</span></div>
        	<div class="singleblock_box"> 
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	
                    <tr>
                       <td width="35%" align="left"><span class="mandt_color">* </span>Name:</td>
                       <td width="65%" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size1" /></td>
                    </tr> 
                    
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Email Address:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="d_email" name="d_email" value="<?php echo $d_email; ?>" currentval="<?php echo $old_d_email; ?>" fieldopt="2" class="checkvaliddata inputbox inputbox_size1" /> <span title="" id="checkvaliddatares2" class="butta-space">&nbsp;</span></td>
                    </tr>                 
                    
                    <tr>
                      <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Mobile Phone:</td>
                      <td width="" align="left"><input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" class="inputbox inputbox_size1" /></td>
                    </tr>
                </table>
            </div>
       </div>
       
       <div class="singleblock">
        	<div class="singleblock_heading"><span>Admin Only</span></div>
        	<div class="singleblock_box">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                      <td width="35%" align="left"><span class="mandt_color">* </span>Account Status:</td>
                      <td width="65%" align="left">                      
                      <select id="status_id" name="status_id" class="htext">
                          <?php echo $adm->get_subadmin_account_combo($status_id); ?>
                      </select>
                      </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="singleblock">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="" align="right">
                        <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                        <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
                    </td>
                </tr>
            </table>
        </div>    
</form>
<?php
include("foot.php");
?>