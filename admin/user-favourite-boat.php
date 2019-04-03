<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$ms = round($_GET["id"], 0);

$sql = "select concat(fname, ' ', lname) as fullname from tbl_user where id = '". $ms ."'";
$result = $db->fetch_all_array($sql);
$found = count($result);

if ($found == 0){
	 $_SESSION["admin_sorry"] = "You have selected an invalid record.";
	 header('Location: sorry.php');
	 exit;
}

$row = $res_row = $result[0];
$fullname = $cm->filtertextdisplay($row["fullname"]);
$link_name = "Favourite Boats - " . $fullname;

$query_sql = "select distinct a.*";
$query_form = " from tbl_yacht as a,";
$query_where = " where";

$query_form .= " tbl_manufacturer as b,";
$query_where .= " b.id = a.manufacturer_id and";
		
$query_form .= " tbl_yacht_favorites as fv,";
$query_where .= " fv.yacht_id = a.id and fv.user_id = '". $ms ."' and";

$query_where .= " a.status_id IN (1,3) and a.display_upto >= CURDATE() and";

$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");

$sql = $query_sql . $query_form . $query_where;			
$sql = $sql." order by fv.reg_date desc";				
$result = $db->fetch_all_array($sql);
$found = count($result);

$_SESSION["bck_pg"] = "user-favourite-boat.php?id=" . $ms;
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

		
<form method="post" action="" name="ff" enctype="multipart/form-data">
    <table border="0" width="95%" cellspacing="0" cellpadding="0">		  
        <tr>
        	<td width="" colspan="2" height="20"><img border="0" src="images/sp.gif" alt="" /></td>
        </tr>
    </table>
	
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
               <tr>
                   <td class="displaytdheading" align="center">Del</td>
                   <td class="displaytdheading" width="70" width="" align="left">Listing #</td>
                   <td class="displaytdheading" width="" align="left">Manufacturer</td>
                   <td class="displaytdheading" width="" align="left">Model</td>
                   <td class="displaytdheading" width="" align="left">Year</td>
                   <td class="displaytdheading" width="" align="left">Price</td>
                   <td class="displaytdheading" width="" align="left">Length</td>
                   <td class="displaytdheading" width="100" align="left">Boat Type</td>
                   <td class="displaytdheading" align="center">Image</td>
                   <td class="displaytdheading" align="center">Status</td>
			 </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 $id = $row['id'];
                 $listing_no = $row['listing_no'];
                 //$broker_admin_id = $row['broker_admin_id'];
				 $company_id = $row['company_id'];
				 $location_id = $row['location_id'];
                 $broker_id = $row['broker_id'];
                 $manufacturer_id = $row['manufacturer_id'];
                 $model = $row['model'];
                 $year = $row['year'];
                 $price = $row['price'];
				 $price_tag_id = $row['price_tag_id'];
                 $status_id = $row['status_id'];
				 
				 $charter_id = $row['charter_id'];
				 $charter_price = $row['charter_price'];
				 $price_per_option_id = $row['price_per_option_id'];

                 $imgpath = $yachtclass->get_yacht_first_image($id);
                 $manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
                 //$broker_admin_name = $cm->get_common_field_name('tbl_user', 'uid', $broker_admin_id);
				 $company_name = $cm->get_common_field_name('tbl_company', 'cname', $company_id);
                 $broker_name = $cm->get_common_field_name('tbl_user', 'uid', $broker_id);

                 $type_name = $cm->display_multiplevl($id, 'tbl_yacht_type_assign', 'type_id', 'yacht_id', 'tbl_type');
                 $status_d = $cm->get_common_field_name('tbl_yacht_status', 'name', $status_id);
				 
				 $price_display = '$' . number_format($price,2);
				 if ($price_tag_id > 0){ 
				 	$price_tag_d = $cm->get_common_field_name('tbl_price_tag', 'name', $price_tag_id);
				 	$price_display = '<span class="fontcolor3">' . $price_display . '</span><br />' . $price_tag_d ;					
				 }
				 
				 //Dimensions & Weight
				 $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
				 $ex_result = $db->fetch_all_array($ex_sql);
				 $row = $ex_result[0];
				 foreach($row AS $key => $val){
					${$key} = htmlspecialchars($val);
				 }
			 ?>     
			 <tr id="row_<?php echo $rc_count; ?>"> 
                  <td class="displaytd1" align="center"><a class="deluserfav" yid="<?php echo $id; ?>" u="<?php echo $ms; ?>" c="<?php echo $rc_count; ?>" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" src="images/del.png"  class="imgcommon" /></a></td>
                  <td class="displaytd1" width="" align="left"><?php echo $listing_no; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $manufacturer_name; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $model; ?></td>
                  <td class="displaytd1" width="" align="left"><?php echo $year; ?></td>
                  <td class="displaytd1" width="" align="left">
				  	<?php echo $price_display; ?>
                    <?php
					   if ($charter_id > 1){
						   $price_per_option_name = $cm->get_common_field_name("tbl_price_per_option", "name", $price_per_option_id);
					?>
					<br /><br />
					<strong>Charter Price / <?php echo $price_per_option_name; ?>:</strong><br />
					$<?php echo number_format($charter_price,2); ?>
					<?php } ?>
                  </td>
                  <td class="displaytd1" width="" align="left"><?php echo $length; ?> ft</td>
                  <td class="displaytd1" width="" align="left"><?php echo $type_name; ?></td>
                  <td class="displaytd1" valign="top" width="" align="center"><?php if ($imgpath != ""){?><img src="../yachtimage/<?php echo $listing_no; ?>/big/<?php echo $imgpath; ?>" border="0" width="65" /><?php }else{ ?> - <?php } ?></td>
                  <td class="displaytd1" width="" align="left"><strong><?php echo $status_d; ?></strong></td>
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
				<td width="" height="20"><img border="0" src="images/sp.gif" alt="" /></td>
			</tr>
		</table>
</form>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("body").on("click", ".deluserfav", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var yid = $(this).attr("yid");
			var u = $(this).attr("u");
			var c = $(this).attr("c");
			
			//ajax process
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				yid:yid,
				u:u,
				inoption:1,
				az:39
			},
			function(content){
				$("#row_" + c).hide();
			});			
		}
	});
});
</script>

<?php
include("foot.php");
?>