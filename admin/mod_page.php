<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Pages";

$parentid = round($_REQUEST["parentid"], 0); 
$key=$_GET["key"];
$pno=$_GET["pno"];
$s=$_GET["s"];
$sby=$_GET["sby"];
$ord=$_GET["ord"];
$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$extraqry = "";
$dcon = 25;
if ($p < 1){ $p = 1; }
if ($q < 1){ $q = 1; }
$page = ($p-1) * $dcon;
if ($page<=0){ $page = 0; }
$extraqry .= "&parentid=".$parentid;

 $sql = "select * from tbl_page where parent_id = '". $parentid ."' and";
 
 if ($key!=""){
    $sql .= " name like '".$cm->filtertext($key)."%' and";
    $msg_1 .= 'Name: <span class="fontcolor3">'.$cm->filtertextdisplay($key, 1).'</span>, ';
    $extraqry .= "&key=". $cm->filtertextdisplay($key);
 }
 
 if ($pno!=""){
    $sql .= " name like '%".$cm->filtertext($pno)."%' and";
    $msg_1 .= 'Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
    $extraqry .= "&pno=". $cm->filtertextdisplay($pno);
 }

 if ($s!=""){
    $sql .= " status = '". $cm->filtertext($s)."' and";
    if ($s == "y"){ $mxx = "Active"; }else{ $mxx = "Inactive"; }    
    $msg_1 .= 'Status: <span class="fontcolor3">'.$mxx.'</span>, ';
    $extraqry .= "&s=". $cm->filtertextdisplay($s);
 }
 
if ($ord == "desc"){
    $ordd = "Descending";
}else{
    $ord = "asc";
    $ordd = "Ascending";
}
 
 if ($msg_1 == ""){
    $msg_1 = "All Record";
 }else{
    $msg_1 = rtrim($msg_1, ", ");
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
 //$sql .= " id > 0 order by rank";

 $sqlm = str_replace("select * from tbl_page","select count(*) as ttl from tbl_page",$sql);
 $foundm = $db->total_record_count($sqlm);

 $sql = $sql." LIMIT ".$page.",".$dcon."";
 $result = $db->fetch_all_array($sql);
 $found = count($result);  

 $extraqry .= "&sby=".$sby;
 $extraqry .= "&ord=".$ord;
 
 if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_page.php?".$admin_query_string;
 }else{
   $_SESSION["bck_pg"] = "mod_page.php";
 }
$icclass = "leftpageicon";
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
    
<form method="get" action="mod_page.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    <tr>
        <td width="20%" align="left">Name:</td>
        <td width="28%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Status:</td>
        <td width="28%" align="left"><select name="s" class="combobox_size4 htext">
                  <option value="">All</option>
                  <option value="y">Active</option>
                  <option value="n">Inactive</option>
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

<?php
if ($parentid > 0){
?>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
 <tr>
   <td align="left">
    <div class="main_con">
      <strong>Page Category Path: </strong>

      <?php
        $brdcmp_array = array();
        $arry_cnt = 0;
        $brdcmp_array[$arry_cnt]["a_title"] = "Top Level Page";
        $brdcmp_array[$arry_cnt]["a_link"] = "mod_page.php";
        $arry_cnt++;

        $catholder_ar = array();
        $child_category_ar = $cm->collect_parent_page_category($parentid, $catholder_ar, 0);
        $child_category_ar_cnt = count($child_category_ar);

        for ($chk = 0; $chk < $child_category_ar_cnt; $chk++){
          $brdcmp_array[$arry_cnt]["a_title"] = $child_category_ar[$chk]["name"];
          $brdcmp_array[$arry_cnt]["a_link"] = $child_category_ar[$chk]["linkurl"];
          $arry_cnt++;
        }

        $cur_nm = $db->total_record_count("select name as ttl from tbl_page where id = '". $parentid."'");
        $brdcmp_array[$arry_cnt]["a_title"] = $cur_nm;
        $brdcmp_array[$arry_cnt]["a_link"] = "";
        $arry_cnt++;
        echo $adm->page_brdcmp_array($brdcmp_array, $fontcls);
      ?>
    </div>
  </td>
 </tr>
</table>
<?php
}
?>


        <form method="post" action="mod_page.php" name="ff" enctype="multipart/form-data">
        <input type="hidden" value="tbl_page" name="tblname" id="tblname" />
        <input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
        <table border="0" width="95%" cellspacing="0" cellpadding="0">
          <tr>
           <td width="330" align="left" valign="top"><a href="add_page.php?parentid=<?php echo $parentid; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
           <td width="" align="right" valign="top">
               <button type="button" class="butta" onclick="javascript:re_sort_order();"><span class="saveIcon butta-space">Save Sort Order</span></button>
           </td>
          </tr>
          <tr>
           <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
         </tr>
        </table>

        <?php
        $gotopagenm = "mod_page.php";
        $adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
        ?>

        <table border="0" width="95%" cellspacing="0" cellpadding="0">
          <tr>
           <td width="100%" align="center" class="tdouter">
               <table border="0" width="100%" cellspacing="1" cellpadding="0">
                    <tr>
                        <td class="displaytdheading" align="center">Del</td>
                        <td class="displaytdheading" align="center">Mod</td>
                        <td class="displaytdheading" width="30%" align="left">Page Heading</td>
                        <td class="displaytdheading" align="center">Type</td>
                        <td class="displaytdheading" width="30%" align="center">Page Name</td>
                        <td class="displaytdheading" align="center">Move</td>
                        <td class="displaytdheading" align="center">Status</td>
                        <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
                    </tr>

                <?php
                $rc_count = 0;
                foreach($result as $row){
                    $id = $row['id'];
                    $page_type = $row['page_type'];
                    $distributor_only = $row['distributor_only'];
                    $name = $row['name'];
                    $status = $row['status'];
                    $categoryrank = $row['rank'];
                    $defaultpage = $row['defaultpage'];
                    $pgnm = $row['pgnm'];
					$only_menu = $row['only_menu'];
                    if ($status == "y"){ $status_d = "Active"; $ch_opt = "n"; }else{ $status_d = "Inactive"; $ch_opt = "y"; }

                    $page_type_nm = $db->total_record_count("select name as ttl from tbl_page_type where id = '". $page_type ."'");                    
                    $if_sub = $cm->count_sub_pages($id);
					
					if ($only_menu == 1){
						$p_url = "-";
					}else{
						$p_url = $cm->get_page_url($id, "page");
						$p_url = str_replace("../", "", $p_url);
					}

                    if ($if_sub > 0){
                        $name_display =  '<strong>' . $name .'</strong> (see subpages)';
                    }else{
                        $name_display =  $name;
                    }
                 ?>
                     <tr>
                          <?php if ($defaultpage > 0){ ?>
                          <td class="displaytd1" align="center"><?php echo $adm->prevent_delete_record('Page'); ?></td>
                          <?php }else{ ?>
                          <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'page'); ?></td>
                          <?php } ?>

                          <td class="displaytd1" align="center"><a href="add_page.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                          <?php if ($id == 1){  ?>
                          <td class="displaytd1" width="" align="left"><?php echo $name_display; ?></td>
                          <?php }else{ ?>
                          <td class="displaytd1" width="" align="left"><a class="htext" href="mod_page.php?parentid=<?php echo $id; ?>"><?php echo $name_display; ?></a></td>
                          <?php } ?>
                          <td class="displaytd1" align="center"><img alt="<?php echo $page_type_nm; ?>" title="<?php echo $page_type_nm; ?>" src="images/<?php echo $page_type; ?>.png" border="0" /></td>
                          <td class="displaytd1" align="center"><?php echo $p_url; ?></td>

                          <?php if ($id == 1){  ?>
                          <td class="displaytd1" width="" align="center">-</td>
                          <?php }else{ ?>
                          <td class="displaytd1" align="center"><?php if ($p_url != ""){ ?><a class="htext" href="move-page.php?id=<?php echo $id; ?>"><img alt="Move" title="Move" src="images/move.png" border="0" /></a><?php } ?></td>
                          <?php } ?>
                          <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status', 'tbl_page', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>
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
        $gotopagenm = "mod_page.php";
        $adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
      ?>


        <table border="0" width="95%" cellspacing="0" cellpadding="0">
          <tr>
           <td width="330" align="left" valign="top"><a href="add_page.php?parentid=<?php echo $parentid; ?>" class="butta"><span class="addIcon butta-space">Add</span></a></td>
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

      <table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
         <tr>
          <td width="" align="center"><img border="0" src="images/1.png" alt="" align="absbottom" /> CMS Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img border="0" src="images/2.png" alt="" align="absbottom" /> Document&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img border="0" src="images/3.png" alt="" align="absbottom" /> Link/URL</td>
         </tr>
         <tr>
          <td width="100%" height="6"><img border="0" src="../images/spacer.gif" alt="" /></td>
         </tr>
         <tr>
          <td width="" align="center"><strong>NOTE:</strong> Click Page Heading to create child page.</td>
         </tr>
      </table>

      <table border="0" width="95%" cellspacing="0" cellpadding="0">
         <tr>
          <td width="100%" height="10"><img border="0" src="../images/spacer.gif" alt="" /></td>
         </tr>
      </table> 
<?php
include("foot.php");
?>