<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$adminpermission = 1;
include("pageset.php");
$link_name = "List Of Model";
$make_id = round($_GET["make_id"], 0);
$result = $yachtclass->check_manufacturer_exist($make_id, 0, 1, 0);
$row = $result[0];
foreach($row AS $key => $val){
    if ($key != "description"){
		${$key} = htmlspecialchars($val);
	}else{
		${$key} = $cm->filtertextdisplay($val);
	}
}

$link_name .= " For " . $name;

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
$query_form = " from tbl_model";
$query_where = " where";

$query_where .= " make_id = '". $make_id ."' and";
$extraqry .= "&make_id=". $cm->filtertextdisplay($make_id);

$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");
$sql = $query_sql . $query_form . $query_where;

$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql .= " order by rank";
$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = $_SESSION["path_pg2"] = "mod-model.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = $_SESSION["path_pg2"] = "mod-model.php?make_id=" . $make_id;
}
if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }
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
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<?php
//echo $modelclass->manufacturer_module_crumb_page_display($manufacturer_id, 1, 2);
?>		
		
<form method="post" action="mod-model.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_model" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
        
        <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
          <tr>
		      <td width="330" align="left" valign="top"><a href="add-model.php?make_id=<?php echo $make_id; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top"><button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button></td>
          </tr>
          <tr>
		   <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>		
        
        <?php
		$gotopagenm = "mod-model.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
             <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="40%" align="left">Model</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="left">Type</td>
                   <td class="displaytdheading" align="center">Status</td>
                   <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
				 $category_id = $row['category_id'];
                 $name = $row['name'];
                 $status_id = $row['status_id'];
                 $categoryrank = $row['rank']; 
				 
				 $cat_name = $cm->get_common_field_name('tbl_model_category', 'name', $category_id); 
				 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; } 
				 
				 $imgpath = $modelclass->get_model_first_image($id);    
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'model'); ?></td>
                  <td class="displaytd1" align="center">
                  <a href="add-model.php?make_id=<?php echo $make_id; ?>&id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
                  <a href="model-image.php?make_id=<?php echo $make_id; ?>&photocategoryid=1&id=<?php echo $id; ?>" title="Manage Images"><img title="Manage Images" src="images/image-list.png"  class="imgcommon" /></a>
                  </td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" width="" align="center"><?php if ($imgpath != ""){?><img src="../models/<?php echo $id; ?>/modelimage/<?php echo $imgpath; ?>" border="0" width="65" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $cat_name; ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_model', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
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
		$gotopagenm = "mod-model.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add-model.php?make_id=<?php echo $make_id; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top"><button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button></td>
          </tr>
		</table>
</form>

<?php
include("foot.php");
?>