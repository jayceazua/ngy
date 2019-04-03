<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$companyid = round($_GET["cid"], 0);
$resultcom = $yachtclass->check_company_exist($companyid, 2, 1, 0);
$rowcom = $resultcom[0];
$companyname = $rowcom['cname'];

$link_name = "List Of Location - " . $companyname;

$key = $_GET["key"];
$pno = $_GET["pno"];
$fn = $_GET["fn"];
$ord = $_GET["ord"];

$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$dcon = round($cm->get_systemvar('MAXLN'), 0);
if ($dcon == 0){ $dcon = 25; }

if ($p < 1){ $p = 1; }
$page = ($p-1) * $dcon;
if ($page <= 0){ $page = 0; }

$extraqry = "";
$msg_1 = "";

$sql = "select * from tbl_location_office where";

if ($pno != ""){
 	$sql .= " name like '%".$cm->filtertext($pno)."%' and";
    $msg_1 .= 'Office Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "desc"){
 	$ordd = "Descending";
}else{
 	$ord = "asc";
	$ordd = "Ascending";
 	
}

$oby = "rank";
$msg_1 .= " [ order by Rank ".$ordd." ]";

$sql .= "  company_id = '". $companyid ."' order by ".$oby." ".$ord;
$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result);
  
$extraqry .= "&cid=".$companyid;
$extraqry .= "&ord=".$ord;
if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_location.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_location.php";
}
$icclass = "leftusericon";
include("head.php");
?>


    <?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">	     		 
		 <?php if ($_SESSION["postmessage"] == "up"){ ?>
		 Record updated successfully.
		 <?php } ?> 
		 
		 <?php if ($_SESSION["postmessage"] == "dels"){ ?>
		 Record deleted successfully.		
		 <?php } ?>
	   </span></td>
	 </tr>
	</table>    
	<?php $_SESSION["postmessage"] = ""; } ?>

    <form method="get" action="mod_location.php" name="f1">
    <table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

    <tr>
        <td width="20%" align="left">Office Name:</td>
        <td width="28%" align="left"><input type="text" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Order By:</td>
        <td width="28%" align="left">
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

    
    <form method="post" action="mod_location.php" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="tbl_location_office" name="tblname" id="tblname" />
    <input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
    <table border="0" width="95%" cellspacing="0" cellpadding="0">
    	<tr>
          <td width="330" align="left" valign="top"><a href="add_location.php?cid=<?php echo $companyid; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
          <td width="" align="right" valign="top">
              <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
          </td>
        </tr>
          
    	<tr>
        	<td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		</tr>
    </table> 
        
    <?php
	$gotopagenm = "mod_location.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>
    
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
      <tr>
       <td width="100%" align="center" class="tdouter">
       
       <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
         <tr>    
             <td class="displaytdheading" align="center">Del</td>
             <td class="displaytdheading" align="center">Mod</td>             
             <td class="displaytdheading" align="left" width="30%">Name</td>             
             <td class="displaytdheading" align="left" width="25%">Location</td>
             <td class="displaytdheading" align="center" width="">Primary</td>             
             <td class="displaytdheading" align="center">Total Broker</td>
             <td class="displaytdheading" align="center">Status</td>
             <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
         </tr>
         
         <?php
         $rc_count = 0;
         foreach($result as $row){
             $id = $row['id'];
			 $company_id = $row['company_id'];             
       		 $name = $row['name'];
			 
			 $address = $row['address'];
			 $city = $row['city'];
			 $state = $row['state'];
			 $state_id = $row['state_id'];
			 $country_id = $row['country_id'];
			 $zip = $row['zip'];
			 $default_location = $row['default_location'];
			 
			 $status_id = $row['status_id'];
			 $location_address = $yachtclass->com_address_format($address, $city, $state, $state_id, $country_id, $zip);
			 $total_location_broker = $yachtclass->total_location_broker($id);
			 
			 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
             if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 
			 $default_location_d = $cm->set_yesyno_field($default_location);
			 if ($default_location == 1){ $dch_opt = 0; }else{ $dch_opt = 1; }
			 $categoryrank = $row['rank'];
         ?>     
         <tr>
             <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'companylocation'); ?></td>
             <td class="displaytd1" align="center">
             	<a href="add_location.php?cid=<?php echo $company_id; ?>&id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
             </td>
             <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
             <td class="displaytd1" width="" align="left"><?php echo $location_address; ?></td>
             <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'default_location', 'tbl_location_office', '<?php echo $dch_opt; ?>', 'id')"><?php echo $default_location_d; ?></a></td>
             <td class="displaytd1" width="" align="center"><?php echo $total_location_broker; ?></td>
             <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_location_office', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
             <td class="displaytd1" align="center" nowrap="nowrap">
             <input type="text" class="inputboxcenter1 inputbox_size3" name="sortorder<?php echo $rc_count; ?>" id="sortorder<?php echo $rc_count; ?>" value="<?php echo $categoryrank; ?>" maxlength="5" />
             <input type="hidden" value="<?php echo $id; ?>" name="id<?php echo $rc_count; ?>" id="id<?php echo $rc_count; ?>" />
             </td>
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
	$gotopagenm = "mod_location.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>   
    
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
      <tr>
          <td width="330" align="left" valign="top"><a href="add_location.php?cid=<?php echo $companyid; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
          <td width="" align="right" valign="top">
              <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
          </td>
        </tr>
    </table>
   </form>
<?php
include("foot.php");
?>