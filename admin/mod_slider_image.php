<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Slider Image";

$cid = round($_GET["cid"], 0);
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

$sql = "select * from tbl_image_slider where";
 
if ($cid > 0){
	$sql .= " category_id = '". $cm->filtertext($cid)."' and";
    $cat_name = $cm->get_common_field_name('tbl_slider_category', 'name', $cid);
    $msg_1 .= 'Category: <span class="fontcolor3">'.$cat_name.'</span>, ';
    $extraqry .= "&cid=". $cm->filtertextdisplay($cid);
}

if ($pno!=""){
 	$sql .= " name like '%".$cm->filtertext($pno)."%' and";
 	$msg_1 .= 'Title: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($s > 0 ){
    if ($s != 2){ $s = 1; }
    $sql .= " status_id = '". $cm->filtertext($s)."' and";
    $s_name = $cm->get_common_field_name('tbl_common_status', 'name', $s);
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
    $msg_1 .= " [ order by Title ".$ordd." ]";
}else{
    $sby = "r";
    $oby = "rank";
    $msg_1 .= " [ order by Rank ".$ordd." ]";
}
$sql .= " id > 0 order by ".$oby." ".$ord;

$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_slider_image.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_slider_image.php";
}
$_SESSION["from_list_slider"] = 1;
$icclass = "leftslidericon";
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
         
         <?php if ($_SESSION["postmessage"] == "csvup"){ ?>
		 Record added successfully.
		 <?php } ?>
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<form method="get" action="mod_slider_image.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    <tr>
        <td width="20%" align="left">Title:</td>
        <td width="28%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Category:</td>
        <td width="28%" align="left"><select name="cid" class="combobox_size4 htext">
                    <option value="">All</option>
                    <?php
                    echo $adm->get_slider_category_combo($cid);
                    ?>
                </select></td>
    </tr>
    
    <tr>
        <td width="" align="left">Status:</td>
        <td width="" align="left"><select name="s" class="combobox_size4 htext">
                    <option value="">All</option>
                    <?php
                    $adm->get_commonstatus_combo($s);
                    ?>
                </select></td>
        <td width="" align="left">&nbsp;</td>        
        <td>&nbsp;</td> 
        <td>&nbsp;</td>       
    </tr>
    
    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
         <select name="sby" class="combobox_size4 htext">
               <option value="c" <?php if ($sby == "c") { echo "selected"; } ?>>Title</option>
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
		
		
<form method="post" action="mod_slider_image.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_image_slider" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_slider_image.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">
                  <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
              </td>
          </tr>
          <tr>
		   <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod_slider_image.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="40%" align="left">Title</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="left">Category</td>
                   <td class="displaytdheading" align="center">Status</td>
                   <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
				 $category_id = $row['category_id'];
                 $name = $row['name'];
                 $imgpath = $row['imgpath'];
                 $status_id = $row['status_id'];
                 $categoryrank = $row['rank'];
				 $video_type = $row['video_type'];
				 $category_name = $cm->get_common_field_name('tbl_slider_category', 'name', $category_id);
				 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'sliderimage'); ?></td>
                  <td class="displaytd1" align="center"><a href="add_slider_image.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <?php
				  if ($video_type == 1){
				  ?>
                  <td class="displaytd1" width="" align="center"><?php if ($imgpath != ""){?><img src="../sliderimage/<?php echo $imgpath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>
                  <?php
				  }elseif ($video_type == 2){
				  ?>
                  <td class="displaytd1" width="" align="center">YouTube Video</td>
                  <?php
				  }elseif ($video_type == 3){
				  ?>
                  <td class="displaytd1" width="" align="center">Vimeo Video</td>
                  <?php
				  }elseif ($video_type == 4){
				  ?>
                  <td class="displaytd1" width="" align="center">MP4 Video</td>
                  <?php
				  }
				  ?>
                  <td class="displaytd1" width="" align="left"><?php echo $category_name; ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_image_slider', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
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
		$gotopagenm = "mod_slider_image.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_slider_image.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
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