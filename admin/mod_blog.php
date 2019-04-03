<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Blog";

$ctid = round($_GET["ctid"], 0);
$pno = $_GET["pno"];
$regdate = $_GET["regdate"];
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
$query_form = " from tbl_blog as a,";
$query_where = " where";

if ($ctid > 0){
    //$query_form .= " tbl_blog_category_assign as b,";
    $query_where .= " a.category_id = '". $ctid ."' and";

    $s_name = $cm->get_common_field_name('tbl_blog_category', 'name', $ctid);
    $msg_1 .= 'Category: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&ctid=".$cm->filtertextdisplay($ctid);
}

if ($pno != ""){
 	$query_where .= " a.name like '%".$cm->filtertext($pno)."%' and";
 	$msg_1 .= 'Post Title: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($s > 0 ){
    if ($s != 2){ $s = 1; }
    $query_where .= " a.status_id = '". $cm->filtertext($s)."' and";
    $s_name = $cm->get_common_field_name('tbl_common_status', 'name', $s);
    $msg_1 .= 'Status: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&s=". $cm->filtertextdisplay($s);
} 

if ($regdate != ""){
	$regdatea = $cm->set_date_format($regdate);
 	$query_where .= " reg_date = '". $cm->filtertext($regdatea). "' and";
	$regdateb = $cm->display_date($regdate, 'y', 9);
 	$msg_1 .= 'Date: <span class="fontcolor3">'.$cm->filtertextdisplay($regdateb, 1).'</span>, ';
	$extraqry .= "&regdate=". $cm->filtertextdisplay($regdate);
}
 
if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "asc"){
    $ordd = "Ascending";
}else{
    $ord = "desc";
    $ordd = "Descending";
}

if ($sby == "n"){
    $oby = "name";
    $msg_1 .= " [ order by Post Title ".$ordd." ]";
}else{
    $sby = "da";
    $oby = "reg_date";
    $msg_1 .= " [ order by Post Date ".$ordd." ]";
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
   $_SESSION["bck_pg"] = "mod_blog.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_blog.php";
}
$icclass = "leftblogicon";
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

<form method="get" action="mod_blog.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

       
    <tr>
        <td width="20%" align="left">Post Title:</td>
        <td width="25%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Category:</td>
        <td width="25%" align="left">
            <select id="ctid" name="ctid" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
				echo $adm->get_blog_category_combo($ctid);
                ?>
            </select>
        </td>
    </tr>
    
    <tr>
    		<td width="" align="left">Date:</td>
        	<td width="" align="left"><input defaultdateset="" rangeyear="2010:<?php echo (date("Y") + 1); ?>" type="text" id="regdate" name="regdate" value="" class="date-field-d inputbox inputbox_size4_b" /></td>
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
               <option value="da" <?php if ($sby == "da") { echo "selected"; } ?>>Post Date</option>
               <option value="n" <?php if ($sby == "n") { echo "selected"; } ?>>Post Title</option>
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
		
<form method="post" action="mod_blog.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_blog" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_blog.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">&nbsp;</td>
          </tr>
          <tr>
		   	  <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod_blog.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="22%" align="left">Title</td>
                   <td class="displaytdheading" align="left">Category</td>
                   <td class="displaytdheading" align="left" width="20%">Tag</td>
                   <td class="displaytdheading" align="center" width="20%">Date</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="center">Featured</td>
                   <td class="displaytdheading" align="center">Status</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $category_id = $row['category_id'];
				 $poster_id = $row['poster_id'];
				 $name = $row['name'];
				 $reg_date = $row['reg_date'];
                 $status_id = $row['status_id'];
				 $imgpath = $row['blog_image'];
				 $display_date = $row['display_date'];
				 $featured_post = $row['featured_post'];
				 
                 $category_name = $cm->get_common_field_name('tbl_blog_category', 'name', $category_id);
				 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
				 if ($featured_post == 1){ $featured_post_d = 'Yes'; $ch_opt_dh = 0; }else{ $featured_post_d = 'No';  $ch_opt_dh = 1; }
				 $reg_date = $cm->display_date($reg_date, 'y', 9);
				 
				 $tagname = $cm->display_multiplevl($id, 'tbl_blog_tag_assign', 'tag_id', 'blog_id', 'tbl_blog_tag');				 
				 //$total_comment = $adm->get_total_blog_comment($id);
				 
				 $extraclass = "";
				 if ($display_date == 0){
					 $extraclass = " inputdiff1";
				 }
			 ?>     
			 <tr> 
                  <td valign="top" class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'blog'); ?></td>
                  <td valign="top" class="displaytd1" align="center"><a href="add_blog.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $category_name; ?></td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $tagname; ?></td>
                  <td valign="top" class="displaytd1" width="" align="center">
                  <input defaultdateset="" rangeyear="2010:<?php echo (date("Y") + 1); ?>" type="text" id="reg_date<?php echo $id; ?>" name="reg_date<?php echo $id; ?>" value="<?php echo $reg_date; ?>" class="date-field-d inputbox<?php echo $extraclass; ?> inputbox_size4_b" />&nbsp;<a class="updaterec" updateid="<?php echo $id; ?>" href="javascript:void(0);" title="Modify Date"><img alt="Modify Date" src="images/correct.png"  class="imgcommon" /></a>
                  <span class="updatemessage<?php echo $id; ?> fontcolor3 com_block"></span>
                  </td>
                  <td valign="top" class="displaytd1" width="" align="center"><?php if ($imgpath != ""){?><img src="../blogimage/<?php echo $imgpath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'featured_post', 'tbl_blog', '<?php echo $ch_opt_dh; ?>', 'id')"><?php echo $featured_post_d; ?></a></td>
                  <td valign="top" class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_blog', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>                  
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
		$gotopagenm = "mod_blog.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_blog.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">&nbsp;</td>
		  </tr>
		</table>
</form>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".updaterec").click(function(){
		var updateid = $(this).attr('updateid');
		var reg_date = $("#reg_date" + updateid).val();
		b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			updateid:updateid,
			reg_date:reg_date,
			inoption:2,
			az:4
		},
		function(content){
			$(".updatemessage" + updateid).html("Success");
		});
	});
});
</script>
<?php
include("foot.php");
?>