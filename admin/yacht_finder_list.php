<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Yacht Finder List";

$chosenuser = round($_GET["chosenuser"], 0);
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
$query_form = " from tbl_boat_watcher_broker";
$query_where = " where";

if ($chosenuser > 0){
	$query_where .= " broker_id = '". $chosenuser ."' and";
	$s_name = $cm->get_common_field_name('tbl_user', 'concat(fname, \' \', lname)', $chosenuser);
	$msg_1 .= 'User: <span class="fontcolor3">'.$s_name.'</span>, ';
    $extraqry .= "&chosenuser=". $chosenuser;
}

if ($msg_1 == ""){
	$msg_1 = "All Record"; 
}else{ 
	$msg_1 = rtrim($msg_1, ", "); 
}

$ord = "desc";
$oby = "reg_date";

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
   $_SESSION["bck_pg"] = "yacht_finder_list.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "yacht_finder_list.php";
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

    
<form method="post" action="yacht_finder_list.php" name="ff" enctype="multipart/form-data">
<input type="hidden" value="tbl_user" name="tblname" id="tblname" />
<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />  
        
<?php
$gotopagenm = "yacht_finder_list.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>
    
<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
  <tr>
   <td width="100%" align="center" class="tdouter">
   
   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
     <tr>    
         <td class="displaytdheading" align="center" width="5%">Del</td>
         <td class="displaytdheading" align="left" width="25%">Name</td>
         <td class="displaytdheading" align="left" width="35%">Email</td>
         <td class="displaytdheading" align="left" width="20%">Reg. Date</td>
         <td class="displaytdheading" align="left" width="15%">Alerts</td>
     </tr>
     
     <?php
     $rc_count = 0;
     foreach($result as $row){
		$boatwatchercode = $cm->filtertextdisplay($row["id"]);				
		$name = $cm->filtertextdisplay($row["name"]);
		$reg_name = $cm->filtertextdisplay($row["reg_name"]);				
		$email_to = $cm->filtertextdisplay($row["email_to"]);
		$searchfield = $cm->filtertextdisplay($row["searchfield"]);				
		$schedule_days = $row["schedule_days"];
		$schedule_date = $row["schedule_date"];
		$reg_date  = $row["reg_date"];
		
		$alert_name = $boatwatcherclass->get_days_frequency_single($schedule_days);
		
		//search field
		$searchfield = json_decode($searchfield);
		$makeid = $searchfield->makeid;
		$yrmin = $searchfield->yrmin;
		$yrmax = $searchfield->yrmax;
		$prmin = $searchfield->prmin;
		$prmax = $searchfield->prmax;
		$lnmin = $searchfield->lnmin;
		$lnmax = $searchfield->lnmax;
		$categoryid = $searchfield->categoryid;	
		$conditionid = $searchfield->conditionid;				
		$typeid = $searchfield->typeid;
		$enginetypeid = $searchfield->enginetypeid;
		$drivetypeid = $searchfield->drivetypeid;
		$fueltypeid = $searchfield->fueltypeid;
		$stateid = $searchfield->stateid;
		$regionselect = $searchfield->regionselect;
		
		$searchfield_text = '';
		if ($makeid > 0){
			$make_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Make:</div>
				<div class="rightfield">'. $make_name .'</div>
			</li>
			';
		}
		
		if ($yrmin > 0){
			$searchfield_text .= '
			<li>
				<div class="leftfield">Year Min:</div>
				<div class="rightfield">'. $yrmin .'</div>
			</li>
			';
		}
		
		if ($yrmax > 0){
			$searchfield_text .= '
			<li>
				<div class="leftfield">Year Max:</div>
				<div class="rightfield">'. $yrmax .'</div>
			</li>
			';
		}
		
		if ($categoryid > 0){
			$category_name = $cm->get_common_field_name('tbl_category', 'name', $categoryid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Category:</div>
				<div class="rightfield">'. $category_name .'</div>
			</li>
			';
		}
		
		if ($conditionid > 0){
			$condition_name = $cm->get_common_field_name('tbl_condition', 'name', $conditionid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Condition:</div>
				<div class="rightfield">'. $condition_name .'</div>
			</li>
			';
		}
		
		if ($prmin > 0){
			$searchfield_text .= '
			<li>
				<div class="leftfield">Price Min:</div>
				<div class="rightfield">'. $prmin .'</div>
			</li>
			';
		}
		
		if ($prmax > 0){
			$query_where .= " a.price <= '". $prmax ."' and";
			$searchfield_text .= '
			<li>
				<div class="leftfield">Price Max:</div>
				<div class="rightfield">'. $prmax .'</div>
			</li>
			';
		}
		
		if ($lnmin > 0){
			$searchfield_text .= '
			<li>
				<div class="leftfield">Length Min:</div>
				<div class="rightfield">'. $lnmin .'</div>
			</li>
			';
		}
		
		if ($lnmax > 0){
			$searchfield_text .= '
			<li>
				<div class="leftfield">Length Max:</div>
				<div class="rightfield">'. $lnmax .'</div>
			</li>
			';
		}
		
		if ($typeid > 0 ){
			$type_name = $cm->get_common_field_name('tbl_type', 'name', $typeid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Class:</div>
				<div class="rightfield">'. $type_name .'</div>
			</li>
			';
		}
		
		if ($enginetypeid > 0){
			$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $enginetypeid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Engine Class:</div>
				<div class="rightfield">'. $engine_type_name .'</div>
			</li>
			';
		}
		
		if ($drivetypeid > 0){
			$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drivetypeid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Drive Type:</div>
				<div class="rightfield">'. $drive_type_name .'</div>
			</li>
			';
		}
		if ($fueltypeid > 0){
			$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fueltypeid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">Fuel Type:</div>
				<div class="rightfield">'. $fuel_type_name .'</div>
			</li>
			';
		}
		
		if ($stateid > 0){
			$state_name = $cm->get_common_field_name('tbl_state', 'code', $stateid);
			$searchfield_text .= '
			<li>
				<div class="leftfield">US State:</div>
				<div class="rightfield">'. $state_name .'</div>
			</li>
			';
		}
		//end
        
     ?>     
     <tr class="row_<?php echo $rc_count; ?>">
         <td class="displaytd1" align="center"><a class="delyachtfinder" boatwatchercode="<?php echo $boatwatchercode; ?>" c="<?php echo $rc_count; ?>" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" src="images/del.png"  class="imgcommon" /></a></td>
     	 <td class="displaytd1 breakall" width="" align="left"><?php echo $reg_name; ?></td>
         <td class="displaytd1 breakall" width="" align="left"><?php echo $email_to; ?></td>
         <td class="displaytd1" align="left"><?php echo $cm->display_date($reg_date, 'y', 7); ?></td>
         <td class="displaytd1" width="" align="left"><?php echo $alert_name; ?></td>     
     </tr>
     <tr class="row_<?php echo $rc_count; ?>">        
         <td class="displaytd1" width="" align="left" colspan="5">
         	<div class="threecolumnlist sp1 clearfixmain">
            	<h4>Search Criteria</h4>
            	<ul>
  				<?php echo $searchfield_text; ?>
            	</ul>
            </div>
         	
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
$gotopagenm = "yacht_finder_list.php";
$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
?>    

</form>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("body").on("click", ".delyachtfinder", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var boatwatchercode = $(this).attr("boatwatchercode");
				var c = $(this).attr("c");
			
			//ajax process
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				boatwatchercode:boatwatchercode,
				inoption:2,
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