<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
$adminpermission = 1;
include("pageset.php");
$link_name = "Manage Model Group For Boat";

$icclass = "leftlistingicon";
include("head.php");
?>

<table border="0" width="95%" cellspacing="0" cellpadding="0">		  
    <tr>
    	<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>

<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td width="100%" align="center" valign="top" class="box_border">
            <form method="post" id="model_group_ff" name="ff">
            	<input type="hidden" value="0" id="ms" name="ms" />
                <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    <tr>
                        <td width="20%" align="left">Model Group Name:</td>
                        <td width="70%" align="left"><input type="text" id="group_name" name="group_name" value="" class="inputbox inputbox_size4" /></td>
                        <td width="10%" align="left"><button type="button" class="butta"><span class="addIcon butta-space addmodelgroup">Add</span></button></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td height="10"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>


<div class="grouplist">
<?php echo $adm->display_boat_model_group($manufacturer_id); ?>
</div>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".addmodelgroup").click(function(){
		if(!validate_text(document.ff.group_name,1,"Please enter Name")){
			return false;
		}
		
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		
		//ajax call
		var manufacturer_id = $("#manufacturer_id").val();
		var group_name = $("#group_name").val();
		var ms =  $("#ms").val();
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			group_name:group_name,
			manufacturer_id:manufacturer_id,
			ms:ms,
			inoption:1,
			az:13,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			returntext = data.returntext;
			$(".grouplist").html(returntext);
			$("#progressBar").dialog("close");
			$("#group_name").val('');
		});
		//end		
	});
	
	//sortable - Group
	$(".whitetd").on("mouseover", "#group_sortable", function(){
		$( this ).sortable({
			cancel: ".groupshortcode",
			update: function (event, ui) {
				var manufacturer_id = $("#group_sortable").attr('manufacturerid');
				var sortdata = $(this).sortable('serialize');
				
				var b_sURL = "onlyadminajax.php";
				$.post(b_sURL,
				{
					manufacturer_id:manufacturer_id,
					data:sortdata,
					inoption:2,
					az:13
				});			
			}
		});
	});
	//end
	
	//edit-group
	$(".whitetd").on("click", ".group_edit", function(){
		var rowval = $(this).attr("rowval");
		$(".normal_mode" + rowval).addClass("com_none");
		$(".edit_mode" + rowval).removeClass("com_none");
	});
	//end
	
	//cancel edit group
	$(".whitetd").on("click", ".update_cancel", function(){
		var rowval = $(this).attr("rowval");
		$(".edit_mode" + rowval).addClass("com_none");
		$(".normal_mode" + rowval).removeClass("com_none");
	});
	//end
	
	//update group name
	$(".whitetd").on("click", ".updategroup", function(){
		var rowval = $(this).attr("rowval");
		
		var group_name = $("#group_name" + rowval).val();
		if (group_name == ""){
			$("#group_name" + rowval).focus();
			alert ("Please enter Group Name");
			return false;
		}
		
		//ajax call
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		var manufacturer_id = $(this).attr("manufacturer_id");
		var ms =  $(this).attr("group_id");
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			group_name:group_name,
			manufacturer_id:manufacturer_id,
			ms:ms,
			inoption:1,
			az:13,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			returntext = data.returntext;
			$(".grouplist").html(returntext);
			$("#progressBar").dialog("close");
		});
		//end
	});
	//end
	
	//delete group
	$(".whitetd").on("click", ".group_del", function(){
		var manufacturer_id = $(this).attr("manufacturer_id");
		var group_id =  $(this).attr("group_id");
		
		var a = confirm("Are you sure?");
		if (a){
			//ajax call
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				manufacturer_id:manufacturer_id,
				group_id:group_id,
				inoption:3,
				az:13,
				dataType: 'json'
			},
			function(data){
				data = $.parseJSON(data);
				returntext = data.returntext;
				$(".grouplist").html(returntext);
				$("#progressBar").dialog("close");
			});
		
			//end
		}
		
	});
	//end
	
	//open group inside
	$(".whitetd").on("click", ".groupopenclose", function(){
		var rowval = $(this).attr("rowval");
		if($(this).hasClass("close_group")){
			//inside open - need to close
			$(this).removeClass("close_group");	
			$('.assign_model_holder' + rowval).hide(300);		
		}else{
			//inside closed - need to open			
			$(".groupopenclose").removeClass("close_group");			
			$('.app_box1').html('');
			$('.app_box2').html('');
			$('.assign_model_holder').hide(100);
			
			$(this).addClass("close_group");
			
			//getting the model data
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			var manufacturer_id = $(this).attr("manufacturerid");
			var group_id = $(this).attr("groupid");
			var view_id = $(this).attr("viewid");
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
                manufacturer_id:manufacturer_id,
				group_id:group_id,
				view_id:view_id,
                inoption:4,
				az:13,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;
				
				$(".app_box1_" + rowval).html(mlistnormal);
                $(".app_box2_" + rowval).html(mlistassign);
                $("#progressBar").dialog("close");
            });
			
			//end
						
			$('.assign_model_holder' + rowval).show(300);
		}		
	});
	//end
	
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
			var manufacturer_id = $(ui.draggable).attr("manufacturer_id");
			var group_id = $(ui.draggable).attr("group_id");
			var view_id = $(ui.draggable).attr("view_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
                manufacturer_id:manufacturer_id,
                boat_id:boat_id,
				group_id:group_id,
				view_id:view_id,
                inoption:5,
				az:13,
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
                $("#progressBar").dialog("close");
            });
			
		}
    });
	
	//assigned model sortable
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
				inoption:6,
				az:13
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
			var manufacturer_id = $(ui.draggable).attr("manufacturer_id");
			var group_id = $(ui.draggable).attr("group_id");
			var view_id = $(ui.draggable).attr("view_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
                manufacturer_id:manufacturer_id,
                boat_id:boat_id,
				group_id:group_id,
				view_id:view_id,
                inoption:7,
				az:13,
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