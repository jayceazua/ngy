<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Yacht Finder Sent Email List";

$chosenemail = $_GET["chosenemail"];
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

$query_sql = "select *";
$query_form = " from tbl_boat_watcher_email";
$query_where = " where";

if ($chosenemail != ""){
	$query_where .= " email like '%". $cm->filtertext($chosenemail) ."%' and";	
	$msg_1 .= 'Email: <span class="fontcolor3">'.$cm->filtertextdisplay($chosenemail, 1).'</span>, ';
    $extraqry .= "&chosenemail=". $cm->filtertextdisplay($chosenemail);
}

if ($msg_1 == ""){
	$msg_1 = "All Record"; 
}else{ 
	$msg_1 = rtrim($msg_1, ", "); 
}

$ord = "desc";
$oby = "send_date";

$query_where .= " id != '' and";

$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");
$sql = $query_sql . $query_form . $query_where;
 
$sqlm = str_replace("select * from","select count(id) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql .= " order by ".$oby." ".$ord;
$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result);
  
$extraqry .= "&sby=".$sby;
$extraqry .= "&ord=".$ord;

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "yacht_finder_email_list.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "yacht_finder_email_list.php";
}
//$_SESSION["from_list_user"] = 1;
$icclass = "leftusericon";
include("head.php");
?>

<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">
	  		 
		 <?php if ($_SESSION["postmessage"] == "dels"){ ?>
		 Record deleted successfully.		
		 <?php } ?> 
		
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<table border="0" width="95%" cellspacing="0" cellpadding="3" class="htext">
    <tr>
        <td width="100%" height="20"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
    <tr>
        <td width="100%" valign="top" align="center"><b><?php echo $msg_1; ?></b></td>
    </tr>
</table>

    
<form method="post" action="yacht_finder_email_list.php" name="ff" enctype="multipart/form-data">
<input type="hidden" value="tbl_boat_watcher_email" name="tblname" id="tblname" />
<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />  
        
<?php
$gotopagenm = "yacht_finder_email_list.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>
    
<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
  <tr>
   <td width="100%" align="center" class="tdouter">
   
   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
     <tr>    
         <td class="displaytdheading" align="center" width="5%">Del</td>
         <td class="displaytdheading" align="left" width="35%">Email</td>
         <td class="displaytdheading" align="left" width="20%">Sent Date</td>
         <td class="displaytdheading" align="center" width="15%">View</td>
     </tr>
     
     <?php
     $rc_count = 0;
     foreach($result as $row){
		$boatwatcheremailcode = $cm->filtertextdisplay($row["id"]);				
		$email = $cm->filtertextdisplay($row["email"]);
		$email_content = $cm->filtertextdisplay($row["email_content"]);				
		$send_date  = $row["send_date"];
        
     ?>     
     <tr class="row_<?php echo $rc_count; ?>">
         <td class="displaytd1" align="center"><a class="delyachtfinderemail" boatwatcheremailcode="<?php echo $boatwatcheremailcode; ?>" c="<?php echo $rc_count; ?>" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" src="images/del.png"  class="imgcommon" /></a></td>
         <td class="displaytd1 breakall" width="" align="left"><?php echo $email; ?></td>
         <td class="displaytd1" align="left"><?php echo $cm->display_date($send_date, 'y', 7); ?></td>
         <td class="displaytd1" width="" align="center"><a class="openpopup htext" data-type="iframe" href="boat-watcher-email-content.php?id=<?php echo $boatwatcheremailcode; ?>" title="View Details">Email Content</a></td>     
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
$gotopagenm = "yacht_finder_email_list.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>    

</form>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("body").on("click", ".delyachtfinderemail", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var boatwatcheremailcode = $(this).attr("boatwatcheremailcode");
				var c = $(this).attr("c");
			
			//ajax process
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				boatwatcheremailcode:boatwatcheremailcode,
				inoption:3,
				az:39
			},
			function(content){
				$(".row_" + c).hide();
			});			
		}
	});
});
</script>

<?php
include("foot.php");
?>