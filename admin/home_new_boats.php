<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Assign New Boats for Home Page";

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//assignboat
	$(".assignboat").click(function(){
		var boat_id = $(this).val();
		var manufacturer_id = $(this).attr("manufacturer_id");
		
		if($(this).is(':checked')){
			var home_page_new_boats = 1;
		}else{
			var home_page_new_boats = 0;
		}
		
		//overlay
		$(".waitdiv").show();
		$(".waitmessage").html('<p>Updating. Please wait....</p>');
		
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{			
			boat_id:boat_id,
			manufacturer_id:manufacturer_id,
			home_page_new_boats:home_page_new_boats,
			op:1,
			az:6,
		},
		function(){			
			//$(".waitmessage").html('<p>Done</p>');
			$(".waitdiv").hide();
			//messagedivhide();
		});
	});
});
</script>

<table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
    <tr>
        <td height="20"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
	<tr>
    	<td>
        	<?php 
				//echo $yachtchildclass->assign_new_boats(); 
				echo $makeclass->assign_new_boats_yc();
			?>
        </td>
    </tr>
</table>

<?php
include("foot.php");
?>