<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Default Title And Meta Tag For Make";

$sql = "select * from tbl_tag_make where id = '1'";	
$result = $db->fetch_all_array($sql);
$found = count($result); 
$row = $result[0];
$m1 = $row['m1'];
$m2 = $row['m2'];
$m3 = $row['m3'];
$icclass = "leftsettingsicon";
include("head.php");
?>
<table cellspacing="0" cellpadding="0" border="0" width="95%">
  <tr>
    <td class="whitetd" align="center" width="100%">
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

        <form method="post" action="tmt-make-sub.php" name="ff">

            <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
             
                <tr>
                  <td width="35%" class="tdalign1" valign="top">Page Title:</td>
                  <td width="65%" class="tdalign1" valign="top"><textarea name="m1" id="m1" rows="1" cols="1" class="textbox textbox_size1"><?php echo $m1; ?></textarea></td>
                </tr>

                <tr>
                  <td width="" class="tdalign1" valign="top">Meta Descriptions:</td>
                  <td width="" class="tdalign1" valign="top"><textarea name="m2" id="m2" rows="1" cols="1" class="textbox textbox_size1"><?php echo $m2; ?></textarea></td>
                </tr>

                <tr>
                  <td width="" class="tdalign1" valign="top">Meta Keywords:</td>
                  <td width="" class="tdalign1" valign="top"><textarea name="m3" id="m3" rows="1" cols="1" class="textbox textbox_size1"><?php echo $m3; ?></textarea></td>
                </tr>

                <tr>
                    <td width="" align="left">&nbsp;</td>
                    <td width="" align="left">
                        <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    </td>
                </tr>
           </table>
        </form>
	</td>
  </tr>
</table>  	

<?php
include("foot.php");
?>
