<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Boat";

$lno = $_GET["lno"];
$comid = round($_GET["comid"], 0);
$brk = round($_GET["brk"], 0);
$makeid = round($_GET["makeid"], 0);
$mfnm = $_GET["mfnm"];
$pno = $_GET["pno"];
$yr = round($_GET["yr"], 0);
$tpid = round($_GET["tpid"], 0);
$stid = round($_GET["stid"], 0);
$ctg = round($_GET["ctg"], 0);

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

$query_sql = "select distinct a.*";
$query_form = " from tbl_yacht as a,";
$query_where = " where";

if ($lno != ""){
    $query_where .= " a.listing_no = '".$cm->filtertext($lno)."' and";
    $msg_1 .= 'Listing #: <span class="fontcolor3">'.$cm->filtertextdisplay($lno, 1).'</span>, ';
    $extraqry .= "&lno=". $cm->filtertextdisplay($lno);
}

if ($comid > 0 ){
    $query_where .= " a.company_id = '". $cm->filtertext($comid)."' and";
    $s_name = $cm->get_common_field_name('tbl_company', 'cname', $comid);
    $msg_1 .= 'Company: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&comid=". $cm->filtertextdisplay($comid);
}

if ($brk > 0 ){
    $query_where .= " a.broker_id = '". $cm->filtertext($brk)."' and";
    $s_name = $cm->get_common_field_name('tbl_user', 'uid', $brk);
    $msg_1 .= 'Broker: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&brk=". $cm->filtertextdisplay($brk);
}

if ($makeid > 0 ){
    $query_where .= " a.manufacturer_id = '". $cm->filtertext($makeid)."' and";
    $s_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
    $msg_1 .= 'Manufacturer: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&makeid=". $cm->filtertextdisplay($makeid);
}

if ($mfnm != ""){
    $query_form .= " tbl_manufacturer as c,";
    $query_where .= " c.id = a.manufacturer_id and c.name like '%".$cm->filtertext($mfnm)."%' and";
    $msg_1 .= 'Manufacturer: <span class="fontcolor3">'.$cm->filtertextdisplay($mfnm, 1).'</span>, ';
    $extraqry .= "&mfnm=". $cm->filtertextdisplay($mfnm);
}
 
if ($pno != ""){
    $query_where .= " a.model like '%".$cm->filtertext($pno)."%' and";
 	$msg_1 .= 'Model: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($yr > 0 ){
    $query_where .= " a.year = '". $cm->filtertext($yr)."' and";
    $msg_1 .= 'Year: <span class="fontcolor3">'.$yr.'</span>, ';
    $extraqry .= "&yr=". $cm->filtertextdisplay($yr);
}

if ($ctg > 0 ){
    $query_where .= " a.category_id = '". $cm->filtertext($ctg)."' and";
    $s_name = $cm->get_common_field_name('tbl_category', 'name', $ctg);
    $msg_1 .= 'Category: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&ctg=". $cm->filtertextdisplay($ctg);
}

if ($stid > 0 ){
    $query_where .= " a.state_id = '". $cm->filtertext($stid)."' and";
    $s_name = $cm->get_common_field_name('tbl_state', 'name', $stid);
    $msg_1 .= 'Sate: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&stid=". $cm->filtertextdisplay($stid);
}

if ($s > 0 ){
    $query_where .= " a.status_id = '". $cm->filtertext($s)."' and";
    $s_name = $cm->get_common_field_name('tbl_yacht_status', 'name', $s);
    $msg_1 .= 'Status: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&s=". $cm->filtertextdisplay($s);
}

if ($tpid > 0){
    $query_form .= " tbl_yacht_type_assign as b,";
    $query_where .= " a.id = b.yacht_id and b.type_id = '". $tpid ."' and";

    $s_name = $cm->get_common_field_name('tbl_type', 'name', $tpid);
    $msg_1 .= "Boat Type : <font color='#BD0000'>".$cm->filtertextdisplay($s_name)."</font>, ";
    $extraqry .= "&tpid=".$cm->filtertextdisplay($tpid);
}
 
if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "asc"){
    $ordd = "Ascending";
}else{
    $ord = "desc";
    $ordd = "Descending";
}

if ($sby == "pr"){
    $oby = "a.price";
    $msg_1 .= " [ order by Price ".$ordd." ]";
}elseif ($sby == "yr"){
    $oby = "a.year";
    $msg_1 .= " [ order by Year ".$ordd." ]";
}elseif ($sby == "da"){
    $oby = "a.reg_date";
    $msg_1 .= " [ order by Date Added ".$ordd." ]";
}else{
	$query_form .= " tbl_yacht_dimensions_weight as d,";
	$query_where .= " a.id = d.yacht_id and";
    $sby = "boatsize";
    $oby = "d.length";
    $msg_1 .= " [ order by Boat Size ".$ordd." ]";
}

$query_where .= " a.id > 0 order by ".$oby." ".$ord;
$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");

$sql = $query_sql . $query_form . $query_where;

$sqlm = str_replace("select distinct a.* from","select count(distinct a.id) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_yacht.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_yacht.php";
}
$icclass = "leftlistingicon";
include("head.php");
?>	
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#company_id").change(function(){
		   openbrokerforlocation(1);
	});
});
</script>
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
         
         <?php if ($_SESSION["postmessage"] == "csvup"){ ?>
		 Record added successfully.
		 <?php } ?>
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>


<form method="get" action="mod_yacht.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

    <tr>
        <td width="20%" align="left">Listing #:</td>
        <td width="28%" align="left"><input type="text" name="lno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Category:</td>
        <td width="28%" align="left"><select name="ctg" id="ctg" targetcombo="tpid" class="combobox_size4 htext catupdate">
                <option value="">Select</option>
                <?php
                $yachtclass->get_category_combo($ctg);
                ?>
            </select></td>
    </tr>

    <tr>
        <td width="" align="left">Manufacturer:</td>
        <td width="" align="left"><input type="text" id="mfnm" name="mfnm" ckpage="1" class="azax_suggest inputbox inputbox_size4" autocomplete="off" /><div id="suggestsearch" class="suggestsearch com_none"></div></td>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">Model:</td>
        <td width="" align="left"><input type="text" name="pno" class="inputbox inputbox_size4" /></td>
    </tr>

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
        <td width="" align="left">State:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
            <select id="stid" name="stid" class="combobox_size4 htext">
                <option value="">Select</option>
                <?php $yachtclass->get_state_combo($stid); ?>
            </select>
        </td>
    </tr>

    <tr>
        <td width="" align="left">Company:</td>
        <td width="" align="left"><select name="comid" id="company_id" class="combobox_size4 htext">
                <option value="">All</option>
                <?php echo $yachtclass->get_company_combo($comid); ?>
            </select></td>
        <td width="" align="left">&nbsp;</td>    
        <td width="" align="left">Assigned Broker/Agent:</td>
        <td width="" align="left"><select name="brk" id="broker_id" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
				echo $yachtclass->get_broker_combo_all($brk, $comid, 0);
				?>
            </select></td>
    </tr>

    <tr>
        <td width="" align="left">Boat Type:</td>
        <td width="" align="left">
            <select name="tpid" id="tpid" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
				echo $yachtclass->get_type_combo_parent($tpid, $ctg);
                ?>
            </select>
        </td>
		<td width="" align="left">&nbsp;</td>
        <td width="" align="left">Status:</td>
        <td width="" align="left">
            <select name="s" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
                $yachtclass->get_yachtstatus_combo($s);
                ?>
            </select>
        </td>
    </tr>

    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
          <select name="sby" class="combobox_size4 htext">
          	   <option value="boatsize" <?php if ($sby == "boatsize") { echo "selected"; } ?>>Boat Size - Length</option>
               <option value="pr" <?php if ($sby == "pr") { echo "selected"; } ?>>Price</option>
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
		
<form method="post" action="mod_yacht.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_yacht" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_yacht.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">
                  <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
              </td>
          </tr>
          <tr>
		   <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod_yacht.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" width="70" align="center">Mod</td>
                   <td class="displaytdheading" width="70" width="" align="left">Listing #</td>
                   <td class="displaytdheading" width="" align="left">Manufacturer</td>
                   <td class="displaytdheading" width="" align="left">Model</td>
                   <td class="displaytdheading" width="" align="left">Year</td>
                   <td class="displaytdheading" width="" align="left">Price</td>
                   <td class="displaytdheading" width="" align="left">Length</td>
                   <td class="displaytdheading" width="100" align="left">Boat Type</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" width="" align="left">Company</td>
                   <td class="displaytdheading" align="center">Status</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $listing_no = $row['listing_no'];
                 //$broker_admin_id = $row['broker_admin_id'];
				 $company_id = $row['company_id'];
				 $location_id = $row['location_id'];
                 $broker_id = $row['broker_id'];
                 $manufacturer_id = $row['manufacturer_id'];
                 $model = $row['model'];
                 $year = $row['year'];
                 $price = $row['price'];
				 $price_tag_id = $row['price_tag_id'];
                 $status_id = $row['status_id'];
				 
				 $charter_id = $row['charter_id'];
				 $charter_price = $row['charter_price'];
				 $price_per_option_id = $row['price_per_option_id'];

                 $imgpath = $yachtclass->get_yacht_first_image($id);
                 $manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
                 //$broker_admin_name = $cm->get_common_field_name('tbl_user', 'uid', $broker_admin_id);
				 $company_name = $cm->get_common_field_name('tbl_company', 'cname', $company_id);
                 $broker_name = $cm->get_common_field_name('tbl_user', 'uid', $broker_id);

                 $type_name = $cm->display_multiplevl($id, 'tbl_yacht_type_assign', 'type_id', 'yacht_id', 'tbl_type');
                 $status_d = $cm->get_common_field_name('tbl_yacht_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
				 
				 $price_display = '$' . number_format($price,2);
				 if ($price_tag_id > 0){ 
				 	$price_tag_d = $cm->get_common_field_name('tbl_price_tag', 'name', $price_tag_id);
				 	$price_display = '<span class="fontcolor3">' . $price_display . '</span><br />' . $price_tag_d ;					
				 }
				 
				 //Dimensions & Weight
				 $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
				 $ex_result = $db->fetch_all_array($ex_sql);
				 $row = $ex_result[0];
				 foreach($row AS $key => $val){
					${$key} = htmlspecialchars($val);
				 }
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'yacht'); ?></td>
                  <td class="displaytd1" align="center">
                      <a href="add_yacht.php?id=<?php echo $id; ?>" title="Modify Record"><img title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
                      <a href="yacht_image.php?id=<?php echo $id; ?>" title="Manage Images"><img title="Manage Images" src="images/image-list.png"  class="imgcommon" /></a>
                      <a href="yacht_video.php?id=<?php echo $id; ?>" title="Manage Videos"><img title="Manage Videos" src="images/video-icon.png"  class="imgcommon" /></a><br />
                      <a href="yacht_attachment.php?id=<?php echo $id; ?>" title="Manage Attachment"><img title="Manage Attachment" src="images/attachment-icon.png"  class="imgcommon" /></a>
                      <a href="update-latlon.php?id=<?php echo $id; ?>" title="Update Lat-Lon"><img title="Update Lat-Lon" src="images/globe.png"  class="imgcommon" /></a>
                      <a href="add_yacht.php?copyid=<?php echo $id; ?>" title="Copy Inventory"><img title="Copy Inventory" src="images/move.png"  class="imgcommon" /></a>
                  </td>
                  <td class="displaytd1" width="" align="left"><?php echo $listing_no; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $manufacturer_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $model; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $year; ?></td>
                  <td class="displaytd1" width="" align="left">
				  	<?php echo $price_display; ?>
                    <?php
					   if ($charter_id > 1){
						   $price_per_option_name = $cm->get_common_field_name("tbl_price_per_option", "name", $price_per_option_id);
					?>
					<br /><br />
					<strong>Charter Price / <?php echo $price_per_option_name; ?>:</strong><br />
					$<?php echo number_format($charter_price,2); ?>
					<?php } ?>
                  </td>
                  <td class="displaytd1" width="" align="left"><?php echo $length; ?> ft</td>
                  <td class="displaytd1" width="" align="left"><?php echo $type_name; ?></td>
                  <td class="displaytd1" valign="top" width="" align="center"><?php if ($imgpath != ""){?><img src="../yachtimage/<?php echo $listing_no; ?>/big/<?php echo $imgpath; ?>" border="0" width="65" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" width="" align="left">
                      Co.: <?php echo $company_name; ?><br />
                      Broker: <?php echo $broker_name; ?><br />
                  </td>

                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_yacht', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>

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
		$gotopagenm = "mod_yacht.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_yacht.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">
                  <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
              </td>
		  </tr>
		</table>
</form>

<?php
include("foot.php");
?>