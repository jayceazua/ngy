<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Credit Application List";

$apid = round($_GET["apid"], 0);
$fnm = $_GET["fnm"];
$eml = $_GET["eml"];
$regdate = $_GET["regdate"];
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

$query_sql = "select *";
$query_form = " from tbl_credit_application";
$query_where = " where";

if ($apid > 0){
    $query_where .= " application_type_id = '". $apid ."' and";

    $s_name = $cm->get_common_field_name('tbl_credit_application_type', 'name', $apid);
    $msg_1 .= 'Application Type: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&apid=".$cm->filtertextdisplay($apid);
}


if ($fnm != ""){
 	$query_where .= " first_name like '%". $cm->filtertext($fnm) ."%' and";
 	$msg_1 .= 'First Name: <span class="fontcolor3">'.$cm->filtertextdisplay($fnm, 1).'</span>, ';
	$extraqry .= "&fnm=". $cm->filtertextdisplay($fnm);
}

if ($eml != ""){
 	$query_where .= " email like '%". $cm->filtertext($eml) ."%' and";
 	$msg_1 .= 'Email Address: <span class="fontcolor3">'.$cm->filtertextdisplay($eml, 1).'</span>, ';
	$extraqry .= "&eml=". $cm->filtertextdisplay($eml);
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
    $oby = "first_name";
    $msg_1 .= " [ order by First Name ".$ordd." ]";
}else{
    $sby = "da";
    $oby = "reg_date";
    $msg_1 .= " [ order by Post Date ".$ordd." ]";
}

$query_where .= " id > 0 order by ".$oby." ".$ord;
$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");

$sql = $query_sql . $query_form . $query_where;
$sqlm = str_replace("select * from","select count(id) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "credit-application-list.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "credit-application-list.php";
}
$icclass = "leftcreditlisticon";
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

<form method="get" action="credit-application-list.php" name="f1">
<table border="0" width="95%" cellspacing="0" cellpadding="4" align="center" class="htext">
	<tr>
        <td width="20%" align="left">Application Type:</td>
        <td width="30%" align="left"><select id="apid" name="apid" class="combobox_size4 htext">
                <option value="">All</option>
                <?php
				echo $creditappclass->get_credit_application_type_combo($apid);
                ?>
            </select></td>
        
        <td width="20%" align="left">Date:</td>
        <td width="30%" align="left"><input defaultdateset="" rangeyear="2010:<?php echo date("Y"); ?>" type="text" id="regdate" name="regdate" value="" class="date-field-d inputbox inputbox_size4_b" /></td>
    </tr>

       
    <tr>
        <td width="" align="left">First Name:</td>
        <td width="" align="left"><input type="text" id="fnm" name="fnm" class="inputbox inputbox_size4" /></td>
        
        <td width="" align="left">Email Address:</td>
        <td width="" align="left"><input type="text" id="eml" name="eml" class="inputbox inputbox_size4" /></td>
    </tr>      

    <tr>
      <td width="" align="left">Sort By:</td>
      <td width="" align="left">
         <select name="sby" class="combobox_size4 htext">
               <option value="da" <?php if ($sby == "da") { echo "selected"; } ?>>Post Date</option>
         </select>
      </td>
      <td width="" align="left">Order By:</td>
      <td width="" align="left">
         <select name="ord" class="combobox_size4 htext">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
         </select>
      </td>
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
		
<form method="post" action="credit-application-list.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_credit_application" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
          <tr>
		   	  <td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
		 </tr>
		</table>	
        
        <?php
		$gotopagenm = "credit-application-list.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" align="center">View</td>
                   <td class="displaytdheading" width="22%" align="left">Name</td>
                   <td class="displaytdheading" width="" align="left">Email</td>
                   <td class="displaytdheading" align="left">App. Type</td>
                   <td class="displaytdheading" align="center">Date</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $application_type_id = $row['application_type_id'];
				 //$first_name = $edclass->text_decode($row['first_name']);
				 $first_name =$row['first_name'];
				 $last_name = $row['last_name'];
				 $email = $row['email'];
				 $reg_date = $row['reg_date'];
				 
                 $application_type_name = $cm->get_common_field_name('tbl_credit_application_type', 'name', $application_type_id);
				 $reg_date = $cm->display_date($reg_date, 'y', 9);
			 ?>     
			 <tr> 
                  <td valign="top" class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'creditapplication'); ?></td>
                  <td valign="top" class="displaytd1" align="center">
                  <a class="openpopup" data-type="iframe" href="credit-application-details.php?id=<?php echo $id; ?>" title="View Details"><img alt="View Details" title="View Details" src="images/mod.gif"  class="imgcommon" /></a>
                  <a href="create-credit-application-pdf.php?id=<?php echo $id; ?>" title="Create PDF Document"><img alt="Create PDF Document" src="images/pdficon.png"  class="imgcommon" /></a>
                  </td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $first_name; ?> <?php echo $last_name; ?></td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $email; ?></td>
                  <td valign="top" class="displaytd1" width="" align="left"><?php echo $application_type_name; ?></td>
                  <td valign="top" class="displaytd1" width="" align="center"><?php echo $reg_date; ?></td>
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
		$gotopagenm = "credit-application-list.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
</form>
<?php
include("foot.php");
?>