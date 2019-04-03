<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");
$link_name = "Set User Rank";

$query_sql = "select distinct a.*";
$query_form = " from tbl_user as a,";
$query_where = " where";
//$query_where .= " a.status_id = 2 and a.front_display = 1 and (a.type_id = 2 OR a.type_id = 3 OR a.type_id = 4 OR a.type_id = 5 ) and";

$oby = "a.front_display desc, a.rank";

$query_where .= " a.id > 0 order by ".$oby;
$query_sql = rtrim($query_sql, ",");
$query_form = rtrim($query_form, ",");
$query_where = rtrim($query_where, "and");
$sql = $query_sql . $query_form . $query_where;
$result = $db->fetch_all_array($sql);
$found = count($result); 
$icclass = "leftusericon";
include("head.php");
?>	

<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">		 
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
      
<form method="post" action="portfolio_sub.php" id="portfolio_ff" name="ff" enctype="multipart/form-data">
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
            <td>
                <ul id="recordsortable" class="recordorder">
                <?php
                $rc_count = 0;
                foreach($result as $row){
                    $id = $row['id'];
                    $fname = $row['fname'];
					$lname = $row['lname']; 
					$front_display = $row['front_display'];                    
                    $categoryrank = $row['rank'];
					$member_image = $yachtclass->get_user_image($id);					
					$target_path_main = 'userphoto/big/';
        			$imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';
					
					$name = $fname . ' ' . $lname;
					
					$activeclass = '';
					if ($front_display == 1){ $activeclass = ' active'; }
                ?>
                    <li id="item-<?php echo $id; ?>">
                    <div class="top<?php echo $activeclass; ?>"><?php echo $imgpath_d; ?></div>
                    <div class="middle"><?php echo $name; ?></div>
                    <div class="bottom">Rank: <span><?php echo $categoryrank; ?></span></div>
                    </li>
                <?php	
                }				
                ?>
                </ul>
            </td>
        </tr>
    </table>
</form>	
		
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//sortable
	$( "#recordsortable" ).sortable({
		update: function (event, ui) {	
			var sortdata = $(this).sortable('serialize');
			b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{				
				data:sortdata,
				az:5
			});	
			
			var newrank = 1;
			$("#recordsortable li").each(function(){				
				$(".bottom span", this).html(newrank);
				newrank++;
			});
		}
	});
});
</script>

<?php
include("foot.php");
?>