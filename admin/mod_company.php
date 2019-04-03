<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Company Profile";

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

$sql = "select * from tbl_company where";
if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "desc"){
 	$ordd = "Descending";
}else{
 	$ord = "asc";
	$ordd = "Ascending";
 	
}

$oby = "cname";
$msg_1 .= " [ order by Name ".$ordd." ]";


$sql .= " id > 0 order by ".$oby." ".$ord;
$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result);
  
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_company.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_company.php";
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

    <form method="get" action="mod_company.php" name="f1">
    <table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    <tr>        
        <td width="20%" align="left">Order By:</td>
        <td width="30%" align="left">
            <select name="ord" class="combobox_size4 htext">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </td>
        <td width="20%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
    </tr>

    <tr>
        <td width="" align="left" colspan="4"><button type="submit" class="butta"><span class="searchIcon butta-space">Search</span></button></td>
    </tr>
    </table>
    </form>

    <table border="0" width="95%" cellspacing="0" cellpadding="3" class="htext">
        <tr>
            <td width="100%" valign="top" align="center"><b><?php echo $msg_1; ?></b></td>
        </tr>
    </table>

    
    <form method="post" action="mod_company.php" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="tbl_company" name="tblname" id="tblname" />
    <input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
    <table border="0" width="95%" cellspacing="0" cellpadding="0">
    	<tr>
        	<td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		</tr>
    </table> 
        
    <?php
	$gotopagenm = "mod_company.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>
    
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
      <tr>
       <td width="100%" align="center" class="tdouter">
       
       <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
         <tr>
             <td class="displaytdheading" align="center">Mod</td>             
             <td class="displaytdheading" align="left" width="30%">Name</td>
             <td class="displaytdheading" align="left" width="20%">URL</td>
             <td class="displaytdheading" align="left" width="">Location</td>
             <td class="displaytdheading" align="center">Total Broker</td>
             <td class="displaytdheading" align="center">Custom Label</td>
             <td class="displaytdheading" align="center">Status</td>
         </tr>
         
         <?php
         $rc_count = 0;
         foreach($result as $row){
             $id = $row['id'];             
       		 $cname = $row['cname'];
			 $website_url = $row['website_url'];
			 $enable_custom_label = $row['enable_custom_label'];
			 $status_id = $row['status_id'];
			 $total_company_broker = $yachtclass->total_company_broker($id);
			 $total_company_location = $yachtclass->total_company_location($id);
			 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
             if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 if ($enable_custom_label == 1){ $cb_opt = 0; $enable_custom_label_d = "Enabled"; }else{ $cb_opt = 1; $enable_custom_label_d = "Disabled"; }
         ?>     
         <tr>
             <td class="displaytd1" align="center">
             	<a href="add_company.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
                <a href="mod_location.php?cid=<?php echo $id; ?>" title="Company Location"><img alt="Company Location" title="Company Location" src="images/locationicon.png"  class="imgcommon" /></a>
             </td>
             <td class="displaytd1" width="" align="left"><?php echo $cname; ?></td>
             <td class="displaytd1" width="" align="left"><?php echo $website_url; ?></td>
             <td class="displaytd1" width="" align="center"><a class="htext" href="add_location.php?cid=<?php echo $id; ?>"><?php echo $total_company_location; ?></a></td>
             <td class="displaytd1" width="" align="center"><?php echo $total_company_broker; ?></td>
             <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'enable_custom_label', 'tbl_company', '<?php echo $cb_opt; ?>', 'id')"><?php echo $enable_custom_label_d; ?></a></td>
             <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_company', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
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
	$gotopagenm = "mod_company.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>   
   </form>

<?php
include("foot.php");
?>