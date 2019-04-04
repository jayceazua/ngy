<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$adminpermission = 1;
include("pageset.php");
$link_name = "List Of Manufacturer";

$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$extraqry = "";
$dcon = round($cm->get_systemvar('MAXLN'), 0);
if ($dcon == 0){ $dcon = 25; }

if ($p < 1){ $p = 1; }
$page = ($p-1) * $dcon;
if ($page <= 0){ $page = 0; }

$query_sql = "select *";
$query_form = " from tbl_manufacturer,";
$query_where = " where";

$query_where .= " id IN (". $modelclass->availablemakeids .")";

$ord = "asc";
$oby = "name";

$query_where .= " order by ".$oby." ".$ord;
$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");

$sql = $query_sql . $query_form . $query_where;
$sqlm = str_replace("select * from","select count(distinct id) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = $_SESSION["path_pg1"] = "mod_manufacturer.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = $_SESSION["path_pg1"] = "mod_manufacturer.php";
}
$icclass = "leftlistingicon";
include("head.php");
?>
<!--
<form method="get" action="mod_manufacturer.php" name="f1">
    <table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

    <tr>
        <td width="20%" align="left">Name / Matching Keywords:</td>
        <td width="25%" align="left"><input type="text" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left" valign="top">Status:</td>
        <td width="25%" align="left">
        	<select name="s" class="combobox_size4 htext">
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
-->
<table border="0" width="95%" cellspacing="0" cellpadding="0">
    <tr>
    	<td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>
<form method="post" action="mod_manufacturer.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_manufacturer" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
        
        <?php
		$gotopagenm = "mod_manufacturer.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="75%" align="left">Title</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $name = $row['name'];
                 $logo_image = $row['logo_image'];
                 $status_id = $row['status_id'];
				 $mic = $row['mic'];
                 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 $categoryrank = $row['rank'];
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center">
                  <a href="mod-model.php?make_id=<?php echo $id; ?>" title="Manage Model Data"><img alt="Manage Model Data" title="Manage Model Data" src="images/2.gif"  class="imgcommon" /></a>
                  </td>
				  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
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
		$gotopagenm = "mod_manufacturer.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>		
</form>

<?php
include("foot.php");
?>