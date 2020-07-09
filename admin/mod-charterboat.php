<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$link_name = "List Of Charter Boat";
$pno = $_GET["pno"];
$mfnm = $_GET["mfnm"];
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

$query_sql = "select distinct a.*";
$query_form = " from tbl_boat_charter as a,";
$query_where = " where";
 
if ($pno!=""){
	$query_where .= " a.boat_name like :pno and";
	$pdo_param[] = array(
		"id" => "pno",
		"value" => "%". $pno ."%",
		"c" => "PARAM_STR"
	);
 	
	$msg_1 .= 'Boat Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($mfnm != ""){
    $query_form .= " tbl_manufacturer as c,";
    $query_where .= " c.id = a.make_id and c.name like :mfnm and";
	$pdo_param[] = array(
		"id" => "mfnm",
		"value" => "%". $mfnm ."%",
		"c" => "PARAM_STR"
	);
    $msg_1 .= 'Builder: <span class="fontcolor3">'.$cm->filtertextdisplay($mfnm, 1).'</span>, ';
    $extraqry .= "&mfnm=". $cm->filtertextdisplay($mfnm);
}

if ($s > 0 ){
	$query_where .= " a.status_id =:s and";
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

if ($sby == "prd"){
    $oby = "a.priceperday";
    $msg_1 .= " [ order by Price Per Day ".$ordd." ]";
}elseif ($sby == "yr"){
    $oby = "a.priceperweek";
    $msg_1 .= " [ order by Price Per Week ".$ordd." ]";
}elseif ($sby == "yr"){
    $oby = "a.year";
    $msg_1 .= " [ order by Year ".$ordd." ]";
}elseif ($sby == "da"){
    $oby = "a.reg_date";
    $msg_1 .= " [ order by Date Added ".$ordd." ]";
}else{
    $sby = "boatsize";
    $oby = "a.length";
    $msg_1 .= " [ order by Boat Size ".$ordd." ]";
}
$query_where .= " a.id > 0 order by ".$oby." ".$ord;

$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");
$sql = $query_sql . $query_form . $query_where;

$sqlm = str_replace("select a.* from","select count(*) as ttl from", $sql);
$foundm = $db->pdo_get_single_value($sqlm, $pdo_param);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->pdo_select($sql, $pdo_param);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod-charterboat.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod-charterboat.php";
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

<form method="get" action="mod-charterboat.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    <tr>
        <td width="20%" align="left">Boat Name:</td>
        <td width="25%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Builder:</td>
        <td width="25%" align="left"><input type="text" id="mfnm" name="mfnm" ckpage="1" class="azax_suggest inputbox inputbox_size4" autocomplete="off" /><div id="suggestsearch" class="suggestsearch com_none"></div></td>
    <tr>  
    
    <tr>
        <td width="" align="left">Year:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
            <select name="yr" id="yr" class="combobox_size4 htext">
            <option value="">Select</option>
            <?php
            $yachtclass->get_year_combo($yr);
            ?>
            </select>
        </td>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">Status:</td>
        <td width="" align="left">
        	<select name="s" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
                $adm->get_commonstatus_combo($s);
                ?>
            </select>
        </td>
    </tr>
        
    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
         <select name="sby" class="combobox_size4 htext">
               <option value="boatsize" <?php if ($sby == "boatsize") { echo "selected"; } ?>>Length</option>
               <option value="prd" <?php if ($sby == "prd") { echo "selected"; } ?>>Price Per Day</option>
               <option value="prp" <?php if ($sby == "prp") { echo "selected"; } ?>>Price Per Week</option>
               <option value="yr" <?php if ($sby == "yr") { echo "selected"; } ?>>Year</option>
               <option value="da" <?php if ($sby == "da") { echo "selected"; } ?>>Date Added</option>
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
		
<form method="post" action="mod-charterboat.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_boat_charter" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $sectionid; ?>" name="sectionid" id="sectionid" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
        <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
            <tr>
            	<td width="" align="left" valign="top"><a href="add-charterboat.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
            </tr>
            <tr>
            	<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
            </tr>
        </table>	
        
        <?php
		$gotopagenm = "mod-charterboat.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" width="70" align="center">Mod</td>
                   <td class="displaytdheading" width="" align="left">Name</td>
                   <td class="displaytdheading" width="" align="left">Builder</td>
                   <td class="displaytdheading" width="" align="left">Category</td>
                   <td class="displaytdheading" width="" align="left">Year</td>
                   <td class="displaytdheading" width="" align="left">Length</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="center">Status</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $boat_name = $row['boat_name'];
				 $make_id = $row['make_id'];
				 $category_id = $row['category_id'];
				 $year = $row['year'];
				 $length = $row['length'];
                 $status_id = $row['status_id'];
				 
				 $status_d = $cm->get_common_field_name_pdo('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
				 
				 $make_name = $cm->get_common_field_name_pdo('tbl_manufacturer', 'name', $make_id);
				 $category_name = $cm->get_common_field_name_pdo('tbl_category', 'name', $category_id);
				 $imgpath = $charterboatclass->get_charterboat_first_image($id);
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'charterboat'); ?></td>
                  <td class="displaytd1" align="center">
                  <a href="add-charterboat.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
                  <a href="charterboat-image.php?id=<?php echo $id; ?>" title="Manage Images"><img title="Manage Images" src="images/image-list.png"  class="imgcommon" /></a>
                  </td>
                  <td class="displaytd1" width="" align="left"><?php echo $boat_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $make_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $category_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $year; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $length; ?> M</td>
                  <td class="displaytd1" valign="top" width="" align="center"><?php if ($imgpath != ""){?><img src="../charterboat/listings/<?php echo $id; ?>/thumbnail/<?php echo $imgpath; ?>" border="0" width="65" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_boat_charter', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
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
    $gotopagenm = "mod-charterboat.php";
    $adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
    ?>
				
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
        <tr>
        	<td width="330" align="left" valign="top"><a href="add-charterboat.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>              
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