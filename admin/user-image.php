<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "User Image";
$ms = round($_GET["id"], 0);

$sql = "select * from tbl_user where id = '". $cm->filtertext($ms) ."'";
$result = $db->fetch_all_array($sql);
$found = count($result);

if ($found == 0){
	 $_SESSION["admin_sorry"] = "You have selected an invalid record.";
	 header('Location: sorry.php');
	 exit;
}

$row = $res_row = $result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

$icclass = "leftusericon";
include("head.php");
?>
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td align="left">
            <h2><?php echo $fname; ?> <?php echo $lname; ?></h2>
        </td>
    </tr>
</table>

<div class="tabcontent tabcontent2 clearfixmain">
	<?php echo $yachtclass->display_user_image_crop_option($ms); ?>
</div>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
});
</script>
<?php
include("foot.php");
?>