<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Set Brand Logo Display Rank";
$icclass = "leftlogoscrollicon";
include("head.php");
?>
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
	<tr>
    	<td>&nbsp;</td>
        <td align="center" valign="middle" width="200">Set Rank Quickly:</td>
        <td align="center" valign="middle" width="200">
        <select id="set_rank_quick" name="set_rank_quick" class="combobox_size4 htext">
        	<option value="0">Choose</option>
            <option value="1">Name: A-Z</option>
            <option value="2">Name: Z-A</option>
        </select>
        </td>
        <td>&nbsp;</td>
    </tr>
	<tr>
    	<td colspan="4">
        	<div class="sortsection">
				<?php echo $logoscrollclass->set_logo_display_rank(); ?>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//set rank quickly
	$("#set_rank_quick").change(function(){
		var choseroption = $(this).val();
		choseroption = parseInt(choseroption);
		if (choseroption == 0){
			alert ("Please choose option");
			$(this).select();
			return false;
		}
		
		var b_sURL = "onlyadminajax.php";		
		$.post(b_sURL,
		{
			choseroption:choseroption,
			op:4,
			az:5,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			content = data.doc;
			if (content != ''){
				$(".sortsection").html(content);					
			}
		});
	});
	
	//sortable
	$( "#recordsortable" ).sortable({
		update: function (event, ui) {	
			var sortdata = $(this).sortable('serialize');
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{				
				data:sortdata,
				op:5,
				az:5
			});	
			
			var newrank = 1;
			$("li", $(this)).each(function(){				
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