<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$slideshow_id = round($_GET["slideshow_id"], 0);
$result = $slideshowclass->check_slideshow_with_return($slideshow_id, 0, 1);
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

$link_name = "Assign Boat to ". $name ." Slideshow";
$icclass = "leftslideshowicon";
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
            	<input type="hidden" name="slideshowid" id="slideshowid" value="<?php echo $slideshow_id; ?>" />
                <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    <tr>
                        <td width="10%" align="left">Keyword:</td>
                        <td width="60%" align="left"><input type="text" id="keyterm" name="keyterm" value="" class="inputbox inputbox_size4" placeholder="Condition, Type, Make, Model, Year, Length, Broker" /></td>
                        <td width="20%" align="left">
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

<div class="grouplist noborder">
<?php echo $slideshowclass->display_boat_assigned_section($slideshow_id); ?>
</div>
<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".boatassign").click(function(){
		
		//search available list
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		var keyterm = $('#keyterm').val();
		var slideshow_id = $('#slideshowid').val();
		var status_id = $('#status_id').val();
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			slideshow_id:slideshow_id,
			status_id:status_id,
			keyterm:keyterm,
			inoption:4,
			az:7,
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
	
	//model drag drop
	$(".whitetd").on("mouseover", ".app_box1 .drg", function(){
		var sortablediv =  ".app_box2_" + $(this).parent().attr("rowval");
        $( this ).draggable({
			connectToSortable: sortablediv,
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
			var boat_id = $(ui.draggable).attr("boat_id");
			var slideshow_id = $(ui.draggable).attr("slideshow_id");
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				boat_id:boat_id,
				slideshow_id:slideshow_id,
                inoption:1,
				az:7,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;

                $("#progressBar").dialog("close");
            });
			
		}
    });
	
	//assigned section sortable
	var dropid = '';
	$( ".app_box2" ).sortable({
		items: ".divrow",
		update: function (event, ui) {
			if (dropid != ""){
				ui.item.attr('id',dropid);
				dropid = '';
			}
			var sortdata = $(this).sortable('serialize');	
			b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{				
				data:sortdata,
				inoption:2,
				az:7
			});				
		},
		receive: function (event, ui) {
			//$(this).append (ui.item);
			dropid = ui.sender.attr('id');
			ui.item.hide(1000); // remove original item
		}
	});
	
	//remove assign model	
	$('.app_box1').droppable({
        accept: '.drp',
        activeClass: 'drg',
        drop: function(event, ui) {
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			var boat_id = $(ui.draggable).attr("boat_id");
			var slideshow_id = $(ui.draggable).attr("slideshow_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				boat_id:boat_id,
				slideshow_id:slideshow_id,
                inoption:3,
				az:7,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;

                /*$(".app_box2_" + rowval).html(mlistassign);
                if (displayopt == 1){
                    $(".app_box1_" + rowval).html(mlistnormal);
                }*/
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