<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "System Settings";

$categoryid = round($_GET["categoryid"], 0);
$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$extraqry = "";
$dcon = round($cm->get_systemvar('MAXLN'), 0);
if ($dcon == 0){ $dcon = 25; }
if ($p < 1){ $p = 1; }
$page = ($p-1) * $dcon;
if ($page<=0){ $page = 0; }

if ($categoryid <= 0){ $categoryid = 1; }
$categoryname = $cm->get_common_field_name('tbl_syscategory', 'name', $categoryid);
$link_name .= " - " . $categoryname;

$sql = "select * from tbl_systemvar where category_id = '". $categoryid ."' and rank > 0 order by rank";
$sqlm = str_replace("select *","select count(*) as ttl",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result);

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
	$_SESSION["bck_pg"] = "mod-sysvar.php?".$admin_query_string;
}else{
	$_SESSION["bck_pg"] = "mod-sysvar.php";
}



$icclass = "leftsettingsicon";
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

		
<form method="post" action="mod-sysvar.php" name="ff" enctype="multipart/form-data">
<input type="hidden" value="tbl_systemvar" name="tblname" id="tblname" />
<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
<table border="0" width="95%" cellspacing="0" cellpadding="0">
    <tr>
    	<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
    <tr>
    	<td align="left" valign="top">
        <?php echo $adm->get_settings_category_tab($categoryid); ?>
        </td>
    </tr>
    <tr>
    	<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>

<?php
$gotopagenm = "mod-sysvar.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>

<table border="0" width="95%" cellspacing="0" cellpadding="0">
  <tr>
   <td width="100%" align="center" class="tdouter">

   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
     <tr>
      <td class="displaytdheading" align="center">Mod</td>
      <td class="displaytdheading" align="center">Code</td>
      <td class="displaytdheading" align="left">Name</td>
      <td class="displaytdheading" width="50%" align="left">Value</td>
      <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
     </tr>

     <?php
     $rc_count = 0;
     for ($k = 0; $k < $found; $k++){
     $row = $result[$k];
     $id = $row['id'];
     $categoryid = $row['category_id'];
     $code = $row['code'];
     $name = $row['name'];
     $field_value = nl2br(htmlentities($row['field_value'], ENT_QUOTES));
     $categoryrank = $row['rank'];
	 
	 if ($id == 23){
		 if ($field_value == 2){
			 $field_value = "List View";
		 }else{
			 $field_value = "Grid View";
		 }
	 }
     ?>
     <tr>
      <td class="displaytd1" align="center"><a href="add-sysvar.php?id=<?php echo $id; ?>&category_id=<?php echo $categoryid; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
      <td class="displaytd1" align="left"><?php echo $code; ?></td>
      <td class="displaytd1" align="left"><?php echo $name; ?></td>
      <td class="displaytd1" width="" align="left"><?php echo $field_value; ?></td>
      <td class="displaytd1" align="center" nowrap="nowrap"><input type="text" class="inputboxcenter1 inputbox_size3" name="sortorder<?php echo $rc_count; ?>" id="sortorder<?php echo $rc_count; ?>" value="<?php echo $categoryrank; ?>" maxlength="5" /><input type="hidden" value="<?php echo $id; ?>" name="id<?php echo $rc_count; ?>" id="id<?php echo $rc_count; ?>" /></td>
     </tr>
     <?php $rc_count++; } ?>
    </table>

   </td>
</tr>
</table>

<table border="0" width="95%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
 </tr>
  <tr>
    <td width="330" align="left" valign="top">&nbsp;</td>
      <td width="" align="right" valign="top">
          <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
      </td>
  </tr>
  <tr>
    <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
 </tr>
</table>

<?php
$gotopagenm = "mod-sysvar.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>
</form>

<table border="0" width="95%" cellspacing="0" cellpadding="0">
 <tr>
  <td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
 </tr>
</table>
<?php
include("foot.php");
?>