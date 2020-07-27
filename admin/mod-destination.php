<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$link_name = "List Of Destination";
$pno = $_GET["pno"];
$s = round($_GET["s"], 0);
$sby = $_GET["sby"];
$ord = $_GET["ord"];

$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$extraqry = "";
$dcon = round($cm->get_systemvar('MAXLN'), 0);
if ($dcon == 0){ $dcon = 25; }

if ($p < 1){ $p = 1; }
$page = ($p-1) * $dcon;
if ($page <= 0){ $page = 0; }

$pdo_param = array();

$sql = "select * from tbl_destination where";
 
if ($pno!=""){
	$sql .= " name like :pno and";
	$pdo_param[] = array(
		"id" => "pno",
		"value" => "%". $pno ."%",
		"c" => "PARAM_STR"
	);
 	
	$msg_1 .= 'Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($s > 0 ){
	$sql .= " status_id =:s and";
	$pdo_param[] = array(
		"id" => "s",
		"value" => $s,
		"c" => "PARAM_INT"
	);    
	
	$s_name = $cm->get_common_field_name_pdo('tbl_common_status', 'name', $s);
    $msg_1 .= 'Status: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&s=". $cm->filtertextdisplay($s);
}

if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "desc"){
    $ordd = "Descending";
}else{
    $ord = "asc";
    $ordd = "Ascending";
}

if ($sby == "c"){
    $oby = "name";
    $msg_1 .= " [ order by Name ".$ordd." ]";
}else{
    $sby = "r";
    $oby = "rank";
    $msg_1 .= " [ order by Rank ".$ordd." ]";
}
$sql .= " id > 0 order by ".$oby." ".$ord;

$sqlm = str_replace("select * from","select count(*) as ttl from", $sql);
$foundm = $db->pdo_get_single_value($sqlm, $pdo_param);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->pdo_select($sql, $pdo_param);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod-destination.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod-destination.php";
}

$icclass = "leftlistingicon";
include("head.php");
?>	

<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">
	     <?php if ($_SESSION["postmessage"] == "nw"){ ?>
		 Record added successfully.
		 <?php } ?> 
		 
		 <?php if ($_SESSION["postmessage"] == "up"){ ?>
		 Record updated successfully.
		 <?php } ?> 
		 
		 <?php if ($_SESSION["postmessage"] == "dels"){ ?>
		 Record deleted successfully.		
		 <?php } ?>
		 
		 <?php if ($_SESSION["postmessage"] == "stordr"){ ?>
		 New Sort Order for record(s) saved successfully.
		 <?php } ?>
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<form method="get" action="mod-destination.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    <tr>
        <td width="20%" align="left">Name:</td>
        <td width="25%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Status:</td>
        <td width="25%" align="left"><select name="s" class="combobox_size4 htext">
                    <option value="">All</option>
                    <?php
                    $adm->get_commonstatus_combo($s);
                    ?>
                </select></td>
    </tr>
    
    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
         <select name="sby" class="combobox_size4 htext">
               <option value="c" <?php if ($sby == "c") { echo "selected"; } ?>>Name</option>
               <option value="r" <?php if ($sby == "r") { echo "selected"; } ?>>Rank</option>
         </select>
      </td>
      <td width="" align="left">&nbsp;</td>
      <td width="" align="left">Order By:</td>
      <td width="" align="left">
         <select name="ord" class="combobox_size4 htext">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
         </select>
      </td>
    </tr>

    <tr>
        <td width="" align="left" colspan="5"><button type="submit" class="butta"><span class="searchIcon butta-space">Search</span></button></td>
    </tr>
</table>
</form>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="htext">
    <tr>
        <td width="100%" valign="top" align="center"><b><?php echo $msg_1; ?></b></td>
    </tr>
</table>
		
<form method="post" action="mod-destination.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_destination" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $sectionid; ?>" name="sectionid" id="sectionid" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add-destination.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top"><button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button></td>
          </tr>
          <tr>
				<td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod-destination.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="40%" align="left">Name</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="center">Status</td>
                   <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $name = $row['name'];
                 $imagepath = $row['imagepath'];
                 $status_id = $row['status_id'];
                 $categoryrank = $row['rank'];
				 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'destination'); ?></td>
                  <td class="displaytd1" align="center"><a href="add-destination.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" width="" align="center"><?php if ($imagepath != ""){?><img src="../charterboat/destination/<?php echo $imagepath; ?>" border="0" width="50" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_destination', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
                  <td class="displaytd1" align="center" nowrap="nowrap"><input type="text" class="inputboxcenter1 inputbox_size3" name="sortorder<?php echo $rc_count; ?>" id="sortorder<?php echo $rc_count; ?>" value="<?php echo $categoryrank; ?>" maxlength="5" /><input type="hidden" value="<?php echo $id; ?>" name="id<?php echo $rc_count; ?>" id="id<?php echo $rc_count; ?>" /></td>
            </tr>			 
			 <?php
			  $rc_count++;
			 }
			 ?>			 
		   </table>	 
		   
		   </td>		   
		</tr>
	   </table>	
	   
	   <table border="0" width="95%" cellspacing="0" cellpadding="0">
		 <tr>
		  <td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
	  </table>
	  
	  <?php
		$gotopagenm = "mod-destination.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add-destination.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">
                  <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
              </td>
		  </tr>
		</table>
</form>
	  
	  		
<table border="0" width="95%" cellspacing="0" cellpadding="0">
     <tr>
        <td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
     </tr>
</table>
<?php
include("foot.php");
?>