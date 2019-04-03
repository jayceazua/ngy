<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");
$categoryid = 1;
$link_name = "Featured Yacht Settings";

$icclass = "leftlistingicon";
include("head.php");
?>
<table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
    <tr>
        <td height="20"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td width="100%" align="center" valign="top" class="box_border">
            <form method="post" id="boatassign_ff" name="ff">
            <input type="hidden" value="<?php echo $categoryid; ?>" name="categoryid" id="categoryid" />
                <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    <tr>
                        <td width="10%" align="left">Keyword:</td>
                        <td width="29%" align="left"><input type="text" id="keyterm" name="keyterm" value="" class="inputbox inputbox_size4" placeholder="Type, Make, Model, Year, Length, Broker" /></td>
                        <td width="17%" align="left">
                        <select id="condition_id" name="condition_id" class="combobox_size4 htext">
                            <option value="">All Condition</option>
                            <?php $yachtclass->get_condition_combo(); ?>
                        </select>
                        </td>
                        <td width="17%" align="left">
                        <select id="custom_owned" name="custom_owned" class="combobox_size4 htext">
                            <?php
                               echo $yachtclass->get_boat_listing_type_combo_short(0);
                            ?>
                        </select>
                        </td>
                        <td width="17%" align="left">
                        <select id="status_id" name="status_id" class="combobox_size4 htext">
                            <option value="">All Boats</option>
                            <?php $yachtclass->get_yachtstatus_combo(); ?>
                        </select>
                        </td>
                        <td width="10%" align="left"><button type="submit" class="boatassign butta"><span class="searchIcon butta-space">Search</span></button></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td height="10"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<p><strong>Note:</strong> <span class="boatinactivenote">Red - Inactive</span>, <span class="boatsoldnote">Pink - Sold</span></p>

<div class="grouplist noborder">
<?php echo $yachtclass->display_boat_assigned_section_featured(array("categoryid" => $categoryid)); ?>
</div>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//search available list
    $(".boatassign").click(function(){		
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		var keyterm = $('#keyterm').val();
		var categoryid = $('#categoryid').val();
		var condition_id = $('#condition_id').val();
		var custom_owned = $('#custom_owned').val();
		var status_id = $('#status_id').val();		
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			categoryid:categoryid,
			condition_id:condition_id,
			custom_owned:custom_owned,
			status_id:status_id,
			keyterm:keyterm,
			inoption:10,
			az:27,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);			
			ylistnormal = data.ylistnormal;
			$(".app_box1").html(ylistnormal);
			$("#progressBar").dialog("close");
		});
	
		return false;
	});

	//boat drag drop
	$(".whitetd").on("mouseover", ".app_box1 .drg", function(){
        $( this ).draggable({
            scroll: false,
            helper: "clone",
            cursor: 'move',
            revert: 'invalid'
        });
    });

    $('.app_box2').droppable({
        accept: '.drg',
        activeClass: 'drp',
        drop: function(event, ui) {
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			
			var categoryid = $('#categoryid').val();
			var boat_id = $(ui.draggable).attr("id");
			var rowval = $(this).attr("rowval");
			var fea_day_no = $("#fea_day_no" + boat_id).val();
			
			var display_home = 0;
			if($("#display_home" + boat_id).is(':checked')){
				display_home = 1;
			}
				
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				categoryid:categoryid,
				boat_id:boat_id,
				fea_day_no:fea_day_no,
				display_home:display_home,
                inoption:8,
				az:27,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;
	
				$(".app_box2_" + rowval).html(mlistassign);
                $("#progressBar").dialog("close");
            });
			
		}
    });
	
	//remove assign boat
    $(".whitetd").on("mouseover", ".app_box2 .drp", function(){
        $( this ).draggable({
            scroll: false,
            helper: "clone",
            cursor: 'move',
            revert: 'invalid'
        });
    });

    $('.app_box1').droppable({
        accept: '.drp',
        activeClass: 'drg',
        drop: function(event, ui) {
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			
			var categoryid = $('#categoryid').val();
			var boat_id = $(ui.draggable).attr("id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
						
			var keyterm = $('#keyterm').val();
			var condition_id = $('#condition_id').val();
			var custom_owned = $('#custom_owned').val();
			var status_id = $('#status_id').val();
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				categoryid:categoryid,
				boat_id:boat_id,
				keyterm:keyterm,
				condition_id:condition_id,
				custom_owned:custom_owned,
				status_id:status_id,
                inoption:9,
				az:27,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;

				$(".app_box1_" + rowval).html(mlistnormal);
                $("#progressBar").dialog("close");
            });
		}
    });
});
</script>

<?php
include("foot.php");
?>