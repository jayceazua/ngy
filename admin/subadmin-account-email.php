<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "y";
include("pageset.php");

$s = round($_GET ['s'], 0);
if ($s <= 0){ $s = 1; }

$sql = "select * from tbl_subadmin_account_status where id = '". $s ."'";
$result = $db->fetch_all_array($sql);
$found = count($result); 
$row = $result[0];
$file_id = $row['id'];
$file_data = $row['pdes'];
$link_name = $row['name'];
$email_subject = htmlspecialchars($row['email_subject']);
$link_name = 'Email Content - ' . $link_name;
$icclass = "leftemailicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#eml_ff").submit(function(){
            if(!validate_text(document.ff.email_subject,1,"Please enter Email Subject")){
                return false;
            }
            if (!editor_validation('file_data', 'Email Content')){ return false; }

            return true;
        });
    });
</script>

          <?php if ($_SESSION["stt"] == "y"){ ?>
            <table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
             <tr>
               <td width="100%" align="center"><span class="fontcolor3">Record Saved</span></td>
             </tr>
            </table>    
          <?php $_SESSION["stt"] = ""; } ?>
	
         <form method="post" action="subadmin-account-email-sub.php" id="eml_ff" name="ff" enctype="multipart/form-data">
             <input type="hidden" value="<?php echo $file_id; ?>" name="ms" />
             <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                  <td width="35%" align="left"><span class="mandt_color">* </span>Email Subject:</td>
                  <td width="65%" align="left"><input type="text" name="email_subject" value="<?php echo $email_subject; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                  <td width="100%" align="left" colspan="2"><span class="mandt_color">* </span>Email Content:</td>
                </tr>

                <tr>
                    <td width="100%" align="center" colspan="2">
                    <?php
                    $editorstylepath = "";
                    $editorextrastyle = "adminbodyclass_white text_area_white";
                    $cm->display_editor("file_data", $sBasePath, "100%", 400, $file_data, $editorstylepath, $editorextrastyle);
                    ?>
                    </td>
                </tr>

                  <tr>
                  <td width="35%" align="left">&nbsp;</td>
                  <td width="65%" align="right">
                      <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    </td>
                  </tr>
              </table>
          </form>
<?php
include("foot.php");
?>