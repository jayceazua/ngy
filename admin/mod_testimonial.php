<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Testimonial";

$pno = $_GET["pno"];
$brokerid = round($_GET["brokerid"], 0);
$dhome = round($_GET["dhome"], 0);
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

$sql = "select * from tbl_testimonial where";
 
if ($pno != ""){
 	$sql .= " name like '%".$cm->filtertext($pno)."%' and";
 	$msg_1 .= 'Poster Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}

if ($brokerid > 0){
	$sql .= " broker_id = '". $cm->filtertext($brokerid)."' and";
	$s_name = $cm->get_common_field_name('tbl_user', 'concat(fname, \' \', lname)', $brokerid);
    $msg_1 .= 'Broker: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&brokerid=". $cm->filtertextdisplay($brokerid);
}

if ($dhome == 1 ){    
    $sql .= " featured = 1 and";    
    $msg_1 .= '<span class="fontcolor3">Featured</span>, ';
    $extraqry .= "&dhome=". $cm->filtertextdisplay($dhome);
}

if ($s > 0 ){
    $sql .= " status_id = '". $cm->filtertext($s)."' and";
    $s_name = $cm->get_common_field_name('tbl_module_status', 'name', $s);
    $msg_1 .= 'Status: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&s=". $cm->filtertextdisplay($s);
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
    $msg_1 .= " [ order by Poster Name ".$ordd." ]";
}else{
    $sby = "da";
    $oby = "reg_date";
    $msg_1 .= " [ order by Post Date ".$ordd." ]";
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
   $_SESSION["bck_pg"] = "mod_testimonial.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_testimonial.php";
}
$icclass = "lefttestimonialicon";
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

<form method="get" action="mod_testimonial.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">

       
    <tr>
        <td width="20%" align="left">Poster Name:</td>
        <td width="28%" align="left"><input type="text" id="pno" name="pno" class="inputbox inputbox_size4" /></td>
        <td width="" align="left">&nbsp;</td>
        <td width="20%" align="left">Associate Broker:</td>
        <td width="28%" align="left">
            <select name="brokerid" id="brokerid" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
                echo $yachtclass->get_all_broker_combo($brokerid);
                ?>
            </select>
        </td>
    </tr>
    
    <tr>
        	<td width="" align="left">Featured:</td>
            <td width="" align="left">
                <input class="checkbox" type="checkbox" id="dhome" name="dhome" value="1" <?php if ($dhome == 1){?> checked="checked"<?php } ?> /> Yes
            </td>
            <td width="" align="left">&nbsp;</td>
            <td width="" align="left">Status:</td>
            <td width="" align="left">
            <select name="s" class="combobox_size4 htext">
                    <option value="">All</option>
                    <?php
                    echo $adm->get_modulestatus_combo($s);
                    ?>
                </select>
            </td>
        </tr>

    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
         <select name="sby" class="combobox_size4 htext">
               <option value="da" <?php if ($sby == "da") { echo "selected"; } ?>>Post Date</option>
               <option value="n" <?php if ($sby == "n") { echo "selected"; } ?>>Poster Name</option>
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
		
<form method="post" action="mod_testimonial.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_testimonial" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_testimonial.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
              <td width="" align="right" valign="top">&nbsp;</td>
          </tr>
          <tr>
		   	  <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod_testimonial.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">Mod</td>
                   <td class="displaytdheading" width="20%" align="left">Name</td>
                   <td class="displaytdheading" align="left">Company</td>
                   <td class="displaytdheading" align="left">Designation</td>
                   <td class="displaytdheading" align="center">Date</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="left">Broker</td>
                   <td class="displaytdheading" align="center">Featured</td>                   
                   <td class="displaytdheading" align="center">Status</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $name = $row['name'];
				 $company_name = $row['company_name'];
                 $designation = $row['designation'];
				 $imgpath = $row['imgpath'];
				 $featured = $row['featured'];
				 $broker_id = $row['broker_id'];
				 $reg_date = $row['reg_date'];
                 $status_id = $row['status_id'];
                 $status_d = $cm->get_common_field_name('tbl_module_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
				 if ($featured == 1){ $display_home_d = 'Yes'; $ch_opt_dh = 0; }else{ $display_home_d = 'No';  $ch_opt_dh = 1; }
				 //$reg_date_d = $cm->display_date($reg_date, 'y', 6);
				 $reg_date = $cm->display_date($reg_date, 'y', 9);
				 $broker_name = $cm->get_common_field_name('tbl_user', 'concat(fname, \' \', lname)', $broker_id);
			 ?>     
			 <tr> 
                  <td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'testimonial'); ?></td>
                  <td class="displaytd1" align="center"><a href="add_testimonial.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $company_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $designation; ?></td>
                  <td class="displaytd1" width="" align="center">
                  <input defaultdateset="" rangeyear="2010:<?php echo date("Y"); ?>" type="text" id="reg_date<?php echo $id; ?>" name="reg_date<?php echo $id; ?>" value="<?php echo $reg_date; ?>" class="date-field-c inputbox inputbox_size4_b" />&nbsp;<a class="updaterec" updateid="<?php echo $id; ?>" href="javascript:void(0);" title="Modify Date"><img alt="Modify Date" src="images/correct.png"  class="imgcommon" /></a>
                  <span class="updatemessage<?php echo $id; ?> fontcolor3 com_block"></span>
                  </td>
                  <td class="displaytd1" width="" align="center"><?php if ($imgpath != ""){?><img src="../testimonialimage/<?php echo $imgpath; ?>" border="0" width="100" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $broker_name; ?></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'featured', 'tbl_testimonial', '<?php echo $ch_opt_dh; ?>', 'id')"><?php echo $display_home_d; ?></a></td>
                  <td class="displaytd1" align="center"><a class="htext" href="javascript:void(0);" onclick="javascript:enable_disable('<?php echo $id; ?>', 'status_id', 'tbl_testimonial', '<?php echo $ch_opt; ?>', 'id')"><?php echo $status_d; ?></a></td>                  
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
		$gotopagenm = "mod_testimonial.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		      <td width="330" align="left" valign="top"><a href="add_testimonial.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
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
			inoption:1,
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