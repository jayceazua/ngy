<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of Email Campaign";

$pno = $_GET["pno"];
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

$sql = "select * from tbl_email_campaign where";

if ($pno != ""){
 	$sql .= " name like '%".$cm->filtertext($pno)."%' and";
 	$msg_1 .= 'Name: <span class="fontcolor3">'.$cm->filtertextdisplay($pno, 1).'</span>, ';
	$extraqry .= "&pno=". $cm->filtertextdisplay($pno);
}
 
if ($msg_1 == ""){ $msg_1 = "All Record"; }else{ $msg_1 = rtrim($msg_1, ", "); }

if ($ord == "desc"){
    $ordd = "Descending";
}else{
    $ord = "asc";
    $ordd = "Ascending";
}

$sby = "c";
$oby = "name";
$msg_1 .= " [ order by Name ".$ordd." ]";

$sql .= " id > 0 order by ".$oby." ".$ord;

$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod_email_campaign.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod_email_campaign.php";
}
$_SESSION["from_list_campaign"] = 1;

$icclass = "leftemailcampaignicon";
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

<form method="get" action="mod_email_campaign.php" name="f1">
	<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
    	<tr>
            <td width="20%" align="left">Name:</td>
            <td width="25%" align="left"><input type="text" name="pno" class="inputbox inputbox_size4" /></td>
            <td width="" align="left">&nbsp;</td>
            <td width="20%" align="left">&nbsp;</td>
            <td width="25%" align="left">&nbsp;</td>
        </tr>
        
        <tr>
          <td width="" align="left">Sort By:</td>
          <td width="" align="left">             
            <select name="sby" class="combobox_size4 htext">
                <option value="c" <?php if ($sby == "c") { echo "selected"; } ?>>Name</option>
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
		
<form method="post" action="mod_email_campaign.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_email_campaign" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
            <tr>
                  <td width="330" align="left" valign="top"><a href="add_email_campaign.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
                  <td width="" align="right" valign="top">&nbsp;</td>
              </tr>	  
		  
              <tr>
               <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
             </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod_email_campaign.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
                <tr>
                	<td class="displaytdheading" align="center">Del</td>
                    <td class="displaytdheading" align="center">Mod</td>
                    <td class="displaytdheading" width="70%" align="left">Name</td>
                </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
	             $name = $row['name'];
                 $status_id = $row['status_id']; 
				 
				 $status_d = $cm->get_common_field_name('tbl_common_status', 'name', $status_id);
                 if ($status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
			 ?>  
             <tr>
             	<td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'emailcampaign'); ?></td>
                <td class="displaytd1" align="center"><a href="add_email_campaign.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
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
		$gotopagenm = "mod_email_campaign.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>	
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
            <tr>
                  <td width="330" align="left" valign="top"><a href="add_email_campaign.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
                  <td width="" align="right" valign="top">&nbsp;</td>
              </tr>
		</table>
</form>

<?php
include("foot.php");
?>