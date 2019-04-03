<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "List Of URL Redirect";

$p = round($_GET["p"], 0);
$range_span = 11;
$small_range = 11;
$extraqry = "";
$dcon = round($cm->get_systemvar('MAXLN'), 0);
if ($dcon == 0){ $dcon = 25; }

if ($p < 1){ $p = 1; }
$page = ($p-1) * $dcon;
if ($page <= 0){ $page = 0; }

$sql = "select * from tbl_page_301 order by id";
$sqlm = str_replace("select * from","select count(*) as ttl from",$sql);
$foundm = $db->total_record_count($sqlm);

$sql = $sql." LIMIT ".$page.",".$dcon."";
$result = $db->fetch_all_array($sql);
$found = count($result); 

if ($admin_query_string != ""){
   $_SESSION["bck_pg"] = "mod-url-redirect.php?".$admin_query_string;
}else{
   $_SESSION["bck_pg"] = "mod-url-redirect.php";
}

$icclass = "leftsettingsicon";
include("head.php");
?>	

<table border="0" width="95%" cellspacing="0" cellpadding="0">
    <tr>
    	<td width="100%" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>

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

		
<form method="post" action="mod-url-redirect.php" name="ff" enctype="multipart/form-data">
		<input type="hidden" value="tbl_page_301" name="tblname" id="tblname" />
		<input type="hidden" value="<?php echo $found; ?>" name="t_found" id="t_found" />
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
            <tr>
                  <td width="330" align="left" valign="top"><a href="add-url-redirect.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
                  <td width="" align="right" valign="top">&nbsp;</td>
              </tr>	  
		  
              <tr>
               <td width="" colspan="2" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
             </tr>
		</table>	
        
        <?php
		$gotopagenm = "mod-url-redirect.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>			
		
		<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
		  <tr>
		   <td width="100%" align="center" class="tdouter">
		   
		   <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
                <tr>
                	<td class="displaytdheading" align="center">Del</td>
                    <td class="displaytdheading" align="center">Mod</td>
                    <td class="displaytdheading" width="40%" align="left">Old Url</td>
                    <td class="displaytdheading" width="40%" align="left">New Url</td>
                </tr>
             			 
			 <?php
			 $rc_count = 0;
             foreach($result as $row){
                 foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				 }
			 ?>  
             <tr>
             	<td class="displaytd1" align="center"><?php echo $adm->delete_record($id, 'page301'); ?></td>
                <td class="displaytd1" align="center"><a href="add-url-redirect.php?id=<?php echo $id; ?>" title="Modify Record"><img alt="Modify Record" title="Modify Record" src="images/mod.gif"  class="imgcommon" /></a></td>
                <td class="displaytd1" width="" align="left"><?php echo $oldurl; ?></td>
                <td class="displaytd1" width="" align="left"><?php echo $newurl; ?></td>
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
		$gotopagenm = "mod-url-redirect.php";
		$adm->pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry);
		?>
				
		<table border="0" width="95%" cellspacing="0" cellpadding="0">
            <tr>
                  <td width="330" align="left" valign="top"><a href="add-url-redirect.php" class="butta"><span class="addIcon butta-space">Add</span></a></td>
                  <td width="" align="right" valign="top">&nbsp;</td>
              </tr>
		</table>
</form>

<?php
include("foot.php");
?>