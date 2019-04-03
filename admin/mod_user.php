<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of User";

$key = $_GET["key"];
$pno = $_GET["pno"];
$locname = $_GET["locname"];
$fn = $_GET["fn"];
$cn= $_GET["cn"];
$utp = round($_GET["utp"], 0);
$sts = round($_GET["sts"], 0);
$sby = $_GET["sby"];
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

$query_sql = "select a.*";
$query_form = " from tbl_user as a";
$query_where = " where";

$query_form .= " LEFT JOIN tbl_company as b ON a.company_id = b.id";

if ($pno != ""){
 	$query_where .= " a.uid like '%".$cm->filtertext($pno)."%' and";
    $msg_1 .= 'Username: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($fn != ""){
 	$query_where .= " a.fname like '%".$cm->filtertext($fn)."%' and";
    $msg_1 .= 'First Name: <span class="fontcolor3">'.$cm->filtertextdisplay($fn, 1).'</span>, ';
	$extraqry .= "&fn=". $cm->filtertextdisplay($fn);
}

if ($cn != ""){
    $query_where .= " b.cname like '%".$cm->filtertext($cn)."%' and";
    $msg_1 .= 'Company Name: <span class="fontcolor3">'.$cm->filtertextdisplay($cn, 1).'</span>, ';
    $extraqry .= "&cn=". $cm->filtertextdisplay($cn);
}

if ($utp > 1){
    $query_where .= " a.type_id = '".$utp."' and";
    $ctnm = $cm->get_common_field_name("tbl_user_account_status", "name", $sts);
    $msg_1 .= 'Account Type: <span class="fontcolor3">'.$ctnm.'</span>, ';
    $extraqry .= "&sts=". $sts;
}

if ($sts > 0){
    $query_where .= " a.status_id = '".$sts."' and";
    $ctnm = $cm->get_common_field_name("tbl_user_account_status", "name", $sts);
    $msg_1 .= 'Account Status: <span class="fontcolor3">'.$ctnm.'</span>, ';
    $extraqry .= "&sts=". $sts;
}

if ($locname != ""){	
	$s_key_ar = preg_split("/ /", $cm->filtertextdisplay($locname));
	foreach($s_key_ar as $s_key_val){
		$query_where.=" a.keyterm like '%".$cm->filtertext($s_key_val)."%' and";
	}
	$extraqry .= "&locname=". $cm->filtertextdisplay($locname);
	$msg_1 .= 'Location: <span class="fontcolor3">'.$cm->filtertextdisplay($locname, 1).'</span>, ';
}

if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "asc"){
 	$ordd = "Ascending";
}else{
 	$ord = "desc";
 	$ordd = "Descending";
}

if ($sby == "u"){
 	$oby = "uid";
 	$msg_1 .= " [ order by Username ".$ordd." ]";
}elseif ($sby == "f"){
 	$oby = "fname";
 	$msg_1 .= " [ order by First Name ".$ordd." ]";
}elseif ($sby == "l"){
 	$oby = "lname";
 	$msg_1 .= " [ order by Last Name ".$ordd." ]";
}else{
 	$oby = "reg_date";
 	$msg_1 .= " [ order by Date of Registration ".$ordd." ]";
}

$query_where .= " a.id > 0 and";

$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");
$sql = $query_sql . $query_form . $query_where;
 
$sqlm = str_replace("select a.* from","select count(a.id) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql .= " order by ".$oby." ".$ord;
$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result);
  
$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_user.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_user.php";
}
//$_SESSION["from_list_user"] = 1;
$icclass = "leftusericon";
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

    <form method="get" action="mod_user.php" name="f1">
    <table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

    <tr>
        <td width="20%" align="left">Username:</td>
        <td width="28%" align="left"><input type="text" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left" valign="top">Location:</td>
        <td width="28%" align="left"><input type="text" name="locname" class="inputbox inputbox_size4" placeholder="Address, City, State, Country, Post Code" /></td>        
    </tr>

    <tr>
        <td width="" align="left">Account Type:</td>
        <td width="" align="left"><select id="utp" name="utp" class="combobox_size4 htext">
                <option value="">All</option>
                <?php echo $yachtclass->get_user_type_combo($utp, 1); ?>
            </select></td>
		<td width="" align="left">&nbsp;</td>
        <td width="" align="left">First Name:</td>
        <td width="" align="left"><input type="text" name="fn" class="inputbox inputbox_size4" /></td>
    </tr>

    <tr>
        <td width="" align="left">Account Status:</td>
        <td width="" align="left"><select id="sts" name="sts" class="combobox_size4 htext">
                <option value="">All</option>
                <?php echo $yachtclass->get_user_account_combo($sts); ?>
            </select></td>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">&nbsp;</td>
    </tr>

    <tr>
        <td width="" align="left">Sort By:</td>
        <td width="" align="left">
            <select name="sby" class="combobox_size4 htext">
                <option value="u">Username</option>
                <option value="f">First Name</option>
                <option value="l">Last Name</option>
                <option value="d">Date Of Registration</option>
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

    
    <form method="post" action="mod_user.php" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="tbl_user" name="tblname" id="tblname" />
    <input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="330" align="left" valign="top"><a href="add_user.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
		   <td width="" align="right" valign="top">&nbsp;</td>
		  </tr>
          <tr>
		   <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
    </table> 
        
    <?php
	$gotopagenm = "mod_user.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>
    
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
      <tr>
       <td width="100%" align="center" class="tdouter">
       
       <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
         <tr>    
             <td class="displaytdheading" align="center">Del</td>
             <td class="displaytdheading" align="center">Mod</td>
             <td class="displaytdheading" align="left">Username</td>
             <td class="displaytdheading" align="left">Name</td>
             <td class="displaytdheading" align="left">Type</td>
             <td class="displaytdheading" align="left">Reg. date</td>
             <td class="displaytdheading" align="center">Front</td>
             <td class="displaytdheading" align="center">Backend</td>
             <td class="displaytdheading" align="center">Status</td>
         </tr>
         
         <?php
         $rc_count = 0;
         foreach($result as $row){
             $id = $row['id'];
             $u_uid = $row['uid'];
             //$u_pwd = $edclass->txt_decode($row['pwd']);
             $u_fname = $row['fname'];
             $u_lname = $row['lname'];
             $reg_date = $row['reg_date'];
             $type_id = $row['type_id'];
             $u_fullname = $u_fname . "&nbsp;" . $u_lname;

             $status_id = $row['status_id'];
			 $company_id = $row['company_id'];
			 $front_display = $row['front_display'];
			 $admin_access = $row['admin_access'];
			 
             $status_nm = $cm->get_common_field_name("tbl_user_account_status", "name", $status_id);
             if ($status_id == 2){ $ch_opt = 3; }else{ $ch_opt = 2; }

             $type_nm = $cm->get_common_field_name("tbl_user_type", "name", $type_id);
			 $company_nm = $cm->get_common_field_name("tbl_company", "cname", $company_id);
					 
			 if ($front_display == 1){ $front_display_d = 'Yes'; $ch_opt_dh = 0; }else{ $front_display_d = 'No';  $ch_opt_dh = 1; }
			 if ($admin_access == 1){ $admin_access_d = 'Yes'; $ch_opt_ad = 0; }else{ $admin_access_d = 'No';  $ch_opt_ad = 1; }
			 $defaultuser = 0;
			 if ($id == 1){ $defaultuser = 1; } 
         ?>     
         <tr>
         	 <?php if ($defaultuser == 1){ ?>
             <td class="displaytd1" align="center"><?php echo $adm->prevent_delete_record('User'); ?></td>
             <?php }else{ ?>
             <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'user'); ?></td>
             <?php } ?>             
             <td class="displaytd1" align="center">
             <a href="add_user.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a>
             <a href="user-image.php?id=<?php echo $id; ?>" title="User Image"><img alt="User Image" title="User Image" src="images/photo.png"  class="imgcommon" /></a><br />
             <a href="user-favourite-boat.php?id=<?php echo $id; ?>" title="Favourite Boats"><img alt="Favourite Boats" title="Favourite Boats" src="images/favourite-icon.png"  class="imgcommon" /></a>
             <a href="yacht_finder_list.php?chosenuser=<?php echo $id; ?>" title="Yacht Finder List"><img alt="Yacht Finder List" title="Yacht Finder List" src="images/search.png"  class="imgcommon" /></a>
             </td>
             
             <td class="displaytd1" align="left"><?php echo $u_uid; ?></td>
             <td class="displaytd1" align="left"><?php echo $u_fullname; ?></td>
             <td class="displaytd1" align="left"><?php echo $type_nm; ?></td>
             <td class="displaytd1" align="left"><?php echo $cm->display_date($reg_date, 'y', 7); ?></td>
             
             <?php if ($defaultuser == 1){ ?>
             <td class="displaytd1" align="center" valign="top"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'front_display', 'tbl_user', '<?php echo $ch_opt_dh; ?>', 'id')"><?php echo $front_display_d; ?></a></td>
             <td class="displaytd1" align="center" valign="top"><?php echo $admin_access_d; ?></td>
             <td class="displaytd1" align="center" valign="top"><?php echo $status_nm; ?></td>
             <?php
             }else{
				 if ($type_id == 6){
			 ?>
             	<td class="displaytd1" align="center" valign="top">-</td>
             	<td class="displaytd1" align="center" valign="top">-</td>
                <td class="displaytd1" align="center" valign="top"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_user', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_nm; ?></a></td>
             <?php		 
				 }else{
			 ?>
             <td class="displaytd1" align="center" valign="top"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'front_display', 'tbl_user', '<?php echo $ch_opt_dh; ?>', 'id')"><?php echo $front_display_d; ?></a></td>
             <td class="displaytd1" align="center" valign="top"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'admin_access', 'tbl_user', '<?php echo $ch_opt_ad; ?>', 'id')"><?php echo $admin_access_d; ?></a></td>
             <td class="displaytd1" align="center" valign="top"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_user', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_nm; ?></a></td>
             <?php
				 }
             } 
			 ?>
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
	$gotopagenm = "mod_user.php";
	$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
	?>
    
   <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
      <tr>
       <td width="330" align="left" valign="top"><a href="add_user.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
       <td width="" align="right" valign="top">&nbsp;</td>
      </tr>
    </table> 
   </form>

<?php
include("foot.php");
?>