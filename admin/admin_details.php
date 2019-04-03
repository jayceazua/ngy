<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$link_name="Admin Details";
$sql="select uid, pwd, email from tbl_user where id = 1";
$result = $db->fetch_all_array($sql);
$found = count($result); 
$row = $result[0];
$id = $row['id'];				
$admin_uid = $old_d_username = htmlspecialchars($row['uid']);
$admin_pwd = htmlspecialchars($edclass->txt_decode($row['pwd']));
$admin_eml = $old_d_email = htmlspecialchars($row['email']);
$icclass = "leftsettingsicon";
include("head.php");
?>	
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#ad_ff").submit(function(){
            if(!validate_text(document.ff.d_username,1,"Please enter Admin Username")){
                return false;
            }

            if(!validate_text(document.ff.d_password,1,"Please enter Admin Password")){
                return false;
            }

            return true;
        });
    });
</script>

<?php if ($_SESSION["postmessage"] != ""){ ?>
    <table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
        <tr>
            <td width="100%" align="center"><span class="fontcolor3">
                 <?php echo $_SESSION["postmessage"]; ?>
           </span></td>
        </tr>
    </table>
<?php $_SESSION["postmessage"] = ""; } ?>

<form method="post" action="admin_sub.php" id="ad_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="1" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $old_d_username; ?>" name="old_d_username" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="old_d_email" />
    <input type="hidden" value="<?php echo $old_d_email; ?>" name="d_email" />
    <input type="hidden" value="<?php echo $old_status_id; ?>" name="old_status_id" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
          <td width="35%" align="left">Username:</td>
          <td width="65%" align="left"><input type="text" id="d_username" name="d_username" value="<?php echo $admin_uid; ?>" class="inputbox inputbox_size1" /></td>
        </tr>

        <tr>
          <td width="35%" align="left">Password:</td>
          <td width="65%" align="left"><input type="password" id="d_password" name="d_password" value="<?php echo $admin_pwd; ?>" class="inputbox inputbox_size1" /></td>
        </tr>

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