<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Email Campaign";
$ms = round($_GET["id"], 0);
$template_id = 1;

if ($ms > 0){
	$sql="select * from tbl_email_campaign where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
			
		$link_name = "Modify Existing Email Campaign";
	}else{
		$ms = 0;
	}
}

$icclass = "leftemailcampaignicon";
include("head.php");
?>

<table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
    <tr>
        <td height="10"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<?php echo $emailcampaignclass->display_tab_button($ms); ?>

<div class="tabcontent tabcontent1">
    
    <table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
            <td width="100%" align="center" valign="top" class="box_border">
                <form method="post" action="email_campaign_sub.php" id="campaign_ff" name="ff" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
                <input type="hidden" value="<?php echo $template_id; ?>" id="template_id" name="template_id" />
                <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    <?php
                    if ($ms > 0){
                    ?>
                    <tr>
                        <td width="15%" align="left">Campaign Name:</td>
                        <td width="75%" align="left"><input  type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td> 
                        <td width="10%" align="left"><button type="button" class="butta updateform"><span class="saveIcon butta-space">Update</span></button></td>                   
                    </tr>
                    
                    <tr>
                        <td colspan="3">
                        <h3>Choose Template</h3>
                        <?php echo $emailcampaignclass->get_campaign_template_list($template_id); ?>
                        </td>
                    </tr>
                    <?php
                    }else{
                    ?>
                    <tr>
                        <td width="20%" align="left">Campaign Name:</td>
                        <td width="80%" align="left"><input  type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>                    
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                        <h3>Choose Template</h3>
                        <?php echo $emailcampaignclass->get_campaign_template_list($template_id); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td width="" align="left" colspan="2"><button type="button" class="butta addform"><span class="saveIcon butta-space">Add</span></button></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>            
                </form>
            </td>
        </tr>
    </table>         
    <?php
    if ($ms > 0){
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
                    <input type="hidden" name="campaignid" id="campaignid" value="<?php echo $ms; ?>" />
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
    
    <div class="boatholder grouplist noborder">
    <?php echo $emailcampaignclass->display_boat_assigned_section($ms); ?>
    </div>
    
    <table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
            <td width="100%" align="center" valign="top" class="box_border">
                <form>            	
                    <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                        <tr>
                            <td width="50%" align="left"><strong>Descriptions</strong></td>
                            <td width="50%" align="right"><button type="button" class="butta updateform"><span class="saveIcon butta-space">Update</span></button></td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">                        	
                                <?php
                                $editorstylepath = "";
                                $editorextrastyle = "adminbodyclass text_area";
                                $cm->display_editor("descriptions", $sBasePath, "100%", 250, $descriptions, $editorstylepath, $editorextrastyle);
                                ?>
                            </td>                        
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
        <tr>
            <td height="10"><img src="images/sp.gif" border="0"></td>
        </tr>
    </table>
    <?php
        $search_ar = array(
            "template_id" => $template_id,
            "option_chosen" => 1
        );
        echo $emailcampaignclass->page_psecific_value($search_ar);
    }
    ?>
    	
	<div class="clearfix"></div>
</div>

<div class="tabcontent tabcontent2 com_none">	
    <div class="clearfix"></div>
</div>

<div class="tabcontent tabcontent3 com_none">
	<table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
        <tr>
            <td width="100%" align="center" valign="top" class="box_border" colspan="2"><textarea name="htmlcode" id="htmlcode" class="textbox textbox_size7"></textarea></td>
        </tr>
        
        <tr>
            <td width="50%" align="left" valign="top"><button type="button" class="butta selecthtmlcode"><span class="boatmergeIcon butta-space">Select All</span></button></td>
            <td width="50%" align="right" valign="top"><button type="button" class="butta copyhtmlcode"><span class="uploadIcon butta-space">Copy To Clipboard</span></button></td>
        </tr>
        
    </table>        
    <div class="clearfix"></div>
</div>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
	//tab sectopn
	$(".toptablink").click(function(){
		var tabid = $(this).attr("tabid");
		$(".toptablink").removeClass("active");
		$(this).addClass("active");
		
		$(".tabcontent").addClass("com_none");
		$(".tabcontent" + tabid).removeClass("com_none");
		
		tabid = parseInt(tabid);
		if (tabid > 1){
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			//ajax call
			var campaign_id = $(this).attr("campaignid");
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				campaign_id:campaign_id,
				tabid:tabid,
				inoption:6,
				az:9
			},
			function(data){
				if (tabid == 2){
					$(".tabcontent2").html(data);
				}
				
				if (tabid == 3){
					$("#htmlcode").val(data);
				}
				$("#progressBar").dialog("close");
			});
		}
		
	});
		
	//Main form
	$(".addform").click(function(){
		 if (campaign_form_check()){
			 $("#campaign_ff").submit();
		 }else{
			 return;
		 }
	});
	
	//update form button
	$(".updateform").click(function(){
		if (campaign_form_check()){
			campaign_update();
		}
	});
	
	//choose template
	$(".choosetemplate").click(function(){
		var template_id = $(this).attr("template_id");
		$("#template_id").val(template_id);
		
		var boat_no = $(this).attr("boat_no");
		boat_no = parseInt(boat_no);
		
		var choset_boat_no = $(".app_box2 .divrow").length;
		if (choset_boat_no > boat_no){
			$(".waitdiv").show();
			$(".waitmessage").html("<p>Your selected Template can support ony " + boat_no + " boat. Please remove boats from Assign List</p>");
			messagedivhide();
			return false;	
		}

		
		$("ul.templatelist li").removeClass("active");
		$("ul.templatelist li.templaterow" + template_id).addClass("active");
		
		var campaign_id = $("#ms").val();
		campaign_id = parseInt(campaign_id);
		if (campaign_id > 0){
			campaign_update();
		}
	});
	
	//search available list
	$(".boatassign").click(function(){		
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		var keyterm = $('#keyterm').val();
		var status_id = $('#status_id').val();
		var campaign_id = $('#campaignid').val();
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			campaign_id:campaign_id,
			status_id:status_id,
			keyterm:keyterm,
			inoption:4,
			az:9,
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
		var boat_no = 1;
		if ($(".pagespecificoption").length > 0){
			boat_no = $(".pagespecificoption").attr("boat_no");
			boat_no = parseInt(boat_no);
		}
		
		var choset_boat_no = $(".app_box2 .divrow").length;
		if (choset_boat_no >= boat_no){
			//$(".waitdiv").show();
			//$(".waitmessage").html("<p>You can only choose " + boat_no + " boats.</p>");
			//messagedivhide();
			//$(this).draggable('disable');
			//return;
		}else{
			//$(this).draggable('enable');
		}
		
		var sortablediv =  ".app_box2_" + $(this).parent().attr("rowval");
        $( this ).draggable({
			connectToSortable: sortablediv,
            scroll: false,
            helper: "clone",
            cursor: 'move',
            revert: 'invalid',
			start: function (event, ui) {
				var is_chrome = /chrome/i.test( navigator.userAgent );
                if(! is_chrome)$(ui.helper).css("margin-left", event.clientX - $(event.target).offset().left);
                if(! is_chrome)$(ui.helper).css("margin-top", event.clientY - $(event.target).offset().top);
            },
			drag: function(event, ui) {
				if (choset_boat_no >= boat_no){
					$(".waitdiv").show();
					$(".waitmessage").html("<p>You can only choose " + boat_no + " boats.</p>");
					messagedivhide();
					return false;
				}
			}
        });
    });
	
	$('.app_box2').droppable({
        accept: '.drg',
        activeClass: 'drp',
        drop: function(event, ui) {
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			var boat_id = $(ui.draggable).attr("boat_id");
			var campaign_id = $(ui.draggable).attr("campaign_id");
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				boat_id:boat_id,
				campaign_id:campaign_id,
                inoption:1,
				az:9,
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
				az:9
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
			var campaign_id = $(ui.draggable).attr("campaign_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
            {
				boat_id:boat_id,
				campaign_id:campaign_id,
                inoption:3,
				az:9,
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
				$(".app_box2_" + rowval).html(mlistassign);
				$('#status_id').val('');
                $("#progressBar").dialog("close");
            });
		}
    });
	
	//select html code 
	$(".selecthtmlcode").click(function(){
		$("#htmlcode").select();
		return false;
	});
	
	//copy html code
	$(".copyhtmlcode").click(function(){
		copyToClipboard(document.getElementById("htmlcode"));
		return false;
	});
});

function campaign_form_check(){
	var name_id = "name";
	if(!validate_text(document.getElementById(name_id),1,"Please enter Name")){
		return false;
	}	
	return true;
}

function campaign_update(){
	if (campaign_form_check()){
		
		//processform
		var name = $("#name").val();
		var template_id = $("#template_id").val();
		var ms = $("#ms").val();
		
		var oEditor = CKEDITOR.instances["descriptions"];
		message_val = oEditor.getData();
		
		var fd = new FormData();
		fd.append("ms", ms);
		fd.append("name", name);
		fd.append("template_id", template_id);
		fd.append("descriptions", message_val);
		fd.append("inoption", 5);
		fd.append("az", 9);
		
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		
		//call ajax
		var b_sURL = "onlyadminajax.php";
		$.ajax({
			url : b_sURL,
			type: "POST",
			data : fd,
			processData: false,
			contentType: false,
			success:function(data, textStatus, jqXHR){				
				data = $.parseJSON(data);
				boat_no = data.boat_no;
				infotext = data.infotext;
				$(".canchoose").html(infotext);
				$( ".pagespecificoption" ).attr( "boat_no", boat_no );
				$("#progressBar").dialog("close");
			}
		});
		
	}else{
		return;
	}
}
</script>

<?php
include("foot.php");
?>