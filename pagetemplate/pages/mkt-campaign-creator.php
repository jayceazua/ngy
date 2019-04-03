<?php
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$include_fck = 1;
$isdashboard = 1;

$result = $yachtclass->check_user_exist($loggedin_member_id, 2);
$atm1 = $link_name = "Campaign Creator";

$breadcrumb = 0;
$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 6, "m2" => 2, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;
$_SESSION["s_currenturl"] = '';
include($bdr."includes/head.php");
?>

<?php echo $html_start; ?>

<form method="post" id="campaign_ff" name="ff">
<input type="hidden" value="1" id="template_id0" name="template_id0" />
<input type="hidden" value="0" id="total_boat0" name="total_boat0" />
<input type="hidden" value="1" id="max_boat0" name="max_boat0" />
<div class="groupheadmain clearfixmain">
    <ul class="form">
        <li>
        	<div class="fieldlabel">Campaign Name:</div>
        	<div class="fieldval"><input type="text" id="group_name0" class="input" name="group_name0"></div>
        </li>
        <li>
        	<div class="fieldlabel">Choose Template:</div>
        	<div class="fieldval"><?php echo $emailcampaignclass->get_campaign_template_list(1, 0); ?></div>
        </li>
        <li>
        	<div class="fieldlabel fieldlabelempty">&nbsp;</div>
            <div class="fieldval"><button rowval="0" campaign_id="0" type="button" class="button addeditemailcampaign">Add</button></div>
        </li>
    </ul>
</div>
</form>

<div class="groupcontentmain clearfixmain">
	<?php echo $emailcampaignclass->display_email_campaign_group(); ?>
</div>

<?php echo $html_end; ?>

<div class="dbboatsearch-inline all-overlay custom-overlay">
	<div class="custom-overlay-container clearfixmain">
    	<div class="custom-overlay-close"><a href="javascript:void(0);" title="Close"><img src="<?php echo $cm->folder_for_seo; ?>images/close-icon.png" /></a></div>
    	<form id="secrhfilter" name="secrhfilter">
        <ul class="form">
        	<li class="left">
            	<p>Manufacturer / Model</p>
                <input id="keyterm" class="input" type="text" name="keyterm">
            </li>
            <li class="right">
            	<p>Boat Status</p>
                <select class="select" name="statusid" id="statusid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    $yachtclass->get_yachtstatus_combo($statusid);
                    ?>
                </select>
            </li>
        	            
            <li class="left">
                <p>Price($)</p>
                <div class="left-side"><input id="prmin" name="prmin" type="text" value="" class="input" /></div>
                <div class="right-side"><input id="prmax" name="prmax" type="text" value="" class="input" /></div>
            </li>
            <li class="right">
                <p>Length(ft)</p>
                <div class="left-side"><input id="lnmin" name="lnmin" type="text" value="" class="input" /></div>
                <div class="right-side"><input id="lnmax" name="lnmax" type="text" value="" class="input" /></div>
            </li>
            
            <li class="left">
                <p>Year</p>
                <div class="left-side">
                    <select class="select" id="yrmin" name="yrmin">
                        <option selected>Min</option>
                        <?php
                        echo $yachtclass->get_year_combo($yrmin, 1);
                        ?>
                    </select>
                    <div class="clear"></div>
                </div>
                <div class="right-side">
                    <select class="select" id="yrmax" name="yrmax">
                        <option  selected="selected">Max</option>
                        <?php
                        echo $yachtclass->get_year_combo($yrmax, 1);
                        ?>
                    </select>
                    <div class="clear"></div>
                </div>
            </li>
            <li class="right">
            	<p>Condition</p>
                <select class="select" name="conditionid" id="conditionid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_condition_combo($conditionid, 0, 1);
                    ?>
                </select>
            </li>
            
            <li class="left">
            	<p>Category</p>
                <select class="select" name="categoryid" id="categoryid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_category_combo($categoryid, 0, 1);
                    ?>
                </select>
            </li>
            <li class="right">
            	<p>Boat Type</p>
                <select class="select" name="typeid" id="typeid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_type_combo_parent($typeid, $categoryid, 0, 1);
                    ?>
                </select>
            </li>
            
            <li class="left">
            	<p>Engine Type</p>
                <select class="select" name="enginetypeid" id="enginetypeid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_engine_type_combo($enginetypeid, 0, 1);
                    ?>
                </select>
            </li>
            <li class="right">
            	<p>Drive Type</p>
                <select class="select" name="drivetypeid" id="drivetypeid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_drive_type_combo($drivetypeid, 0, 1);
                    ?>
                </select>
            </li>
            
            <li class="left">
            	<p>Fuel Type</p>
                <select class="select" name="fueltypeid" id="fueltypeid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_fuel_type_combo($categoryid, 0, 1);
                    ?>
                </select>
            </li>
            <li class="right">
            	<p>US State</p>
                <select class="select" name="stateid" id="stateid">
                    <option value="0" selected="selected">All</option>
                    <?php
                    echo $yachtclass->get_state_combo($stateid, 1, 1);
                    ?>
                </select>
            </li>
            
            <li><button type="button" class="button searchboatprocess">Search Boat</button></li>
        </ul>
        <input type="hidden" name="rowval" id="rowval" value="0" />
        <input type="hidden" name="campaign_id" id="campaign_id" value="0" />
        </form>
    </div>
</div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	//choose template
	$(".main").off("click", ".choosetemplate").on("click", ".choosetemplate", function(){
		var template_id = $(this).attr("template_id");
		var rowval = $(this).attr("rowval");		
		
		var boat_no = $(this).attr("boat_no");
		boat_no = parseInt(boat_no);
		
		var total_boat = $("#total_boat" + rowval).val();
		total_boat = parseInt(total_boat);

		if (total_boat > boat_no){			
			errormessagepop("<p>Your selected Template can support ony " + boat_no + " boat. Please remove boats from Assign List</p>");			
			return false;	
		}
		
		$("#template_id" + rowval).val(template_id);
		$("#max_boat" + rowval).val(boat_no);
			
		$("ul.emailtemplatelist" + rowval + " li").removeClass("active");
		$("ul.emailtemplatelist" + rowval + " li.templaterow" + template_id).addClass("active");		
	});
	//end
	
	//add - edit
	$(".main").off("click", ".addeditemailcampaign").on("click", ".addeditemailcampaign", function(){
		var all_ok = "y";
		var setfocus = "n";
		var rowval = $(this).attr("rowval");
		
		if (!field_validation_border("group_name" + rowval, 1, 1)){
			all_ok = "n";			
		}
		
		if (all_ok == "n"){
			return false;
		}

		//process
		dispay_wait_msg("Please wait!!!!!");
		
		var rowval = $(this).attr("rowval");
		var ms =  $(this).attr("campaign_id");
		
		var template_id = $("#template_id" + rowval).val();
		var group_name = $("#group_name" + rowval).val();
				
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{
			template_id:template_id,
			group_name:group_name,
			ms:ms,			
			inoption:1,
			az:153,
			dataType: "json"
		},
		function(data){
			data = $.parseJSON(data);
			returntext = data.returntext;
			infotext = data.infotext;
			
			ms = parseInt(ms);
			if (ms == 0){
				//$(".groupcontentmain").html("");
				//$(".groupcontentmain").html(returntext);
				$("#group_name" + rowval).val("");
			}
			
			if( typeof(CKEDITOR) !== "undefined" ){
				for(k in CKEDITOR.instances){
					var instance = CKEDITOR.instances[k];
					instance.destroy();
				}
			}
						
			$(".groupcontentmain").html(returntext);	
			hide_wait_msg();
			init_drag_drop();
		});
		
	});
	//end
		
	//edit open
	$(".main").off("click", ".group_edit").on("click", ".group_edit", function(){
		var rowval = $(this).attr("rowval");
		reset_all_tabs();
		$(".edit_mode" + rowval).removeClass("com_none");
		$(".groupopenclose").removeClass("close_group");
	});
	//end
	
	//cancel edit - close
	$(".main").off("click", ".update_cancel").on("click", ".update_cancel", function(){
		var rowval = $(this).attr("rowval");
		$(".edit_mode" + rowval).addClass("com_none");
	});
	//end
	
	//add notes open
	$(".main").off("click", ".add_notes").on("click", ".add_notes", function(){
		var rowval = $(this).attr("rowval");
		reset_all_tabs();
		$(".edit_mode_notes" + rowval).removeClass("com_none");
		$(".groupopenclose").removeClass("close_group");
	});
	//end
	
	//cancel add notes - close
	$(".main").off("click", ".update_cancel_notes").on("click", ".update_cancel_notes", function(){
		var rowval = $(this).attr("rowval");
		$(".edit_mode_notes" + rowval).addClass("com_none");
	});
	//end
	
	//update notes
	$(".main").off("click", ".add_notes_campaign").on("click", ".add_notes_campaign", function(){
		var rowval = $(this).attr("rowval");
		var oEditor = CKEDITOR.instances["descriptions" + rowval];
		message_val = oEditor.getData();
		
		//Process
		dispay_wait_msg("Please wait!!!!!");
		
		var campaign_id =  $(this).attr("campaign_id");
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{
			campaign_id:campaign_id,
			message_val:message_val,
			inoption:8,
			az:153,
			dataType: 'json'
		},
		function(data){						
			hide_wait_msg();
		});
		//end
	});
	//end
	
	//preview
	$(".main").off("click", ".campaign_preview").on("click", ".campaign_preview", function(){
		var rowval = $(this).attr("rowval");
		
		//Process
		dispay_wait_msg("Please wait!!!!!");
		
		var campaign_id =  $(this).attr("campaign_id");
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{
			campaign_id:campaign_id,
			modedisplay:2,
			inoption:9,
			az:153
		},
		function(data){
			reset_all_tabs();
			$(".campaign_preview_display" + rowval).html(data);
			$(".campaign_preview_display" + rowval).removeClass("com_none");
			$(".groupopenclose").removeClass("close_group");
							
			hide_wait_msg();
		});
		//end
	});
	//end	
	
	//html code
	$(".main").off("click", ".campaign_htmlcode").on("click", ".campaign_htmlcode", function(){
		var rowval = $(this).attr("rowval");
		
		//Process
		dispay_wait_msg("Please wait!!!!!");
		
		var campaign_id =  $(this).attr("campaign_id");
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{
			campaign_id:campaign_id,
			modedisplay:1,
			inoption:9,
			az:153
		},
		function(data){
			reset_all_tabs();
			$("#htmlcode" + rowval).val(data);
			$(".campaign_htmlcode_display" + rowval).removeClass("com_none");
			$(".groupopenclose").removeClass("close_group");
							
			hide_wait_msg();
		});
		//end
	});
	//end
	
	//select html code 
	$(".selecthtmlcode").click(function(){
		var rowval = $(this).attr("rowval");
		$("#htmlcode" + rowval).select();
		return false;
	});

	//copy html code
	$(".copyhtmlcode").click(function(){
		var rowval = $(this).attr("rowval");
		//copyToClipboard($("#htmlcode"+ rowval).val());
		$("#htmlcode" + rowval).select();
		document.execCommand("Copy");
		return false;
	});
	
	
	//delete group
	$(".main").off("click", ".group_del").on("click", ".group_del", function(){		
		var campaign_id =  $(this).attr("campaign_id");
		
		var a = confirm("Are you sure?");
		if (a){
			//Process
			dispay_wait_msg("Please wait!!!!!");
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				campaign_id:campaign_id,
				inoption:2,
				az:153,
				dataType: 'json'
			},
			function(data){
				data = $.parseJSON(data);
				returntext = data.returntext;
				
				for(k in CKEDITOR.instances){
					var instance = CKEDITOR.instances[k];
					instance.destroy();
				}
				
				$(".groupcontentmain").html(returntext);
				hide_wait_msg();
			});		
			//end
		}		
	});
	//end
	
	//open group inside
	$(".main").off("click", ".groupopenclose").on("click", ".groupopenclose", function(){
		var rowval = $(this).attr("rowval");
		reset_all_tabs();
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
			
			dispay_wait_msg("Please wait!!!!!");
			
			var campaign_id = $(this).attr("groupid");
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
            {
				campaign_id:campaign_id,
                inoption:3,
				az:153,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;
				validboat = data[0].found;
				
				$(".validboat" + rowval).html(validboat);
				$(".app_box1_" + rowval).html(mlistnormal);
                $(".app_box2_" + rowval).html(mlistassign);
                
				hide_wait_msg();
				reset_overlay(1);
            });			
			//end
						
			$(".assign_model_holder" + rowval).show(300);
			$("#campaign_id").val(campaign_id);
			$("#rowval").val(rowval);
		}		
	});
	//end
	
	//Search form Open
	$(".main").off("click", ".openboatsearchform").on("click", ".openboatsearchform", function(){
		var campaign_id = $(this).attr("campaign_id");
		var rowval = $(this).attr("rowval");
		
		$("#campaign_id").val(campaign_id);
		$("#rowval").val(rowval);
		$(".dbboatsearch-inline").show();
	});
	
	//Process Search
	$(".searchboatprocess").click(function(){		
		process_boat_search();
	});
	
	init_drag_drop();	
});

function reset_all_tabs(){
	$(".edit_mode_notes").addClass("com_none");
	$(".campaign_preview_display").addClass("com_none");
	$(".campaign_htmlcode_display").addClass("com_none");
	$(".edit_mode").addClass("com_none");
	$(".assign_model_holder").hide();
	
	$(".campaign_preview_display").html("");
	$(".htmlcode").val("");
}

function process_boat_search(){
	dispay_wait_msg("Please wait!!!!!");
		
	var keyterm = $('#keyterm').val();
	var statusid = $("#statusid").val();
	var prmin = $("#prmin").val();
	var prmax = $("#prmax").val();
	var lnmin = $("#lnmin").val();
	var lnmax = $("#lnmax").val();
	var yrmin = $("#yrmin").val();
	var yrmax = $("#yrmax").val();
	
	var conditionid = $("#conditionid").val();
	var typeid = $("#typeid").val();
	var categoryid = $("#categoryid").val();
	var enginetypeid = $("#enginetypeid").val();
	var drivetypeid = $("#drivetypeid").val();
	var fueltypeid = $("#fueltypeid").val();
	var stateid = $("#stateid").val();		
	
	var campaign_id = $("#campaign_id").val();
	var rowval = $("#rowval").val();
	
	var b_sURL = bkfolder + "includes/ajax.php";
	$.post(b_sURL,
	{
		campaign_id:campaign_id,
		keyterm:keyterm,
		statusid:statusid,
		prmin:prmin,
		prmax:prmax,
		lnmin:lnmin,
		lnmax:lnmax,
		yrmin:yrmin,
		yrmax:yrmax,
		conditionid:conditionid,
		typeid:typeid,
		categoryid:categoryid,
		enginetypeid:enginetypeid,
		drivetypeid:drivetypeid,
		fueltypeid:fueltypeid,
		stateid:stateid,
		inoption:7,
		az:153,
		dataType: 'json'
	},
	function(data){
		data = $.parseJSON(data);
		mlistnormal = data[0].mlistnormal;
		validboat = data[0].found;
				
		$(".validboat" + rowval).html(validboat);
		$(".app_box1_" + rowval).html(mlistnormal);
		hide_wait_msg();
		reset_overlay(0);
	});
}

//Reset form and close overlay
function reset_overlay(resetform){
	$(".custom-overlay").hide(250);
	//$("#campaign_id").val(0);
	//$("#rowval").val(0);
	
	if (resetform == 1){
		$("#keyterm").val('');
		$("#statusid").val(0);	
		$("#prmin").val(0);
		$("#prmax").val(0);
		$("#lnmin").val(0);
		$("#lnmax").val(0);
		$("#yrmin").val(0);
		$("#yrmax").val(0);
		$("#conditionid").val(0);
		$("#typeid").val(0);
		$("#categoryid").val(0);
		$("#enginetypeid").val(0);
		$("#drivetypeid").val(0);
		$("#fueltypeid").val(0);
		$("#stateid").val(0);
	}
}

//init drag drop
function init_drag_drop(){
	//boat drag drop
	$(".main").off("mouseover", ".app_box1 .drg").on("mouseover", ".app_box1 .drg", function(){
		var sortablediv =  ".app_box2_" + $(this).parent().attr("rowval");
        var boat_no = $("#max_boat" + $(this).parent().attr("rowval")).val();
		boat_no = parseInt(boat_no);

		var total_boat = $("#total_boat" + $(this).parent().attr("rowval")).val();
		total_boat = parseInt(total_boat);
		
		if (total_boat >= boat_no){					
			//errormessagepop("You can only choose " + boat_no + " boats.");
			return false;
		}
		
		$( this ).draggable({
			connectToSortable: sortablediv,
            scroll: false,
            helper: "clone",
            cursor: 'move',
            revert: 'invalid',
			cursorAt: { top: 0, left: 0 }
        });
    });
	
	$('.app_box2').droppable({
        accept: '.drg',
        activeClass: 'drp',
        drop: function(event, ui) {
			dispay_wait_msg("Please wait!!!!!");
			
			var boat_id = $(ui.draggable).attr("boat_id");
			var campaign_id = $(ui.draggable).attr("campaign_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			//ui.draggable.hide(1000);			
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
            {
                boat_id:boat_id,
				campaign_id:campaign_id,
                inoption:4,
				az:153,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;
				total_boat = data[0].total_boat;
				validboat = data[0].found;
				
				$(".validboat" + rowval).html(validboat);

                /*$(".app_box2_" + rowval).html(mlistassign);
                if (displayopt == 1){
                    $(".app_box1_" + rowval).html(mlistnormal);
                }*/
				$("#total_boat" + rowval).val(total_boat);
                hide_wait_msg();
            });
			
			//$(ui.helper).remove(); //destroy clone
            //$(ui.draggable).remove(); //remove from list
			
		}
    });
	
	//assigned model sortable
	var dropid = '';
	$( ".app_box2" ).sortable({
		items: ".boatrow",
		update: function (event, ui) {
			if (dropid != ""){
				ui.item.attr('id',dropid);
				dropid = '';
			}
			var sortdata = $(this).sortable('serialize');
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{				
				data:sortdata,
				inoption:5,
				az:153
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
			dispay_wait_msg("Please wait!!!!!");
			
			var boat_id = $(ui.draggable).attr("boat_id");
			var campaign_id = $(ui.draggable).attr("campaign_id");
			var rowval = $(this).attr("rowval");			
					
			ui.draggable.removeClass('drg');
            ui.draggable.addClass('drp');
			ui.draggable.hide(1000);
			
			var keyterm = $('#keyterm').val();
			var statusid = $("#statusid").val();
			var prmin = $("#prmin").val();
			var prmax = $("#prmax").val();
			var lnmin = $("#lnmin").val();
			var lnmax = $("#lnmax").val();
			var yrmin = $("#yrmin").val();
			var yrmax = $("#yrmax").val();
			
			var conditionid = $("#conditionid").val();
			var typeid = $("#typeid").val();
			var categoryid = $("#categoryid").val();
			var enginetypeid = $("#enginetypeid").val();
			var drivetypeid = $("#drivetypeid").val();
			var fueltypeid = $("#fueltypeid").val();
			var stateid = $("#stateid").val();
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
            {
                boat_id:boat_id,
				campaign_id:campaign_id,
				keyterm:keyterm,
				statusid:statusid,
				prmin:prmin,
				prmax:prmax,
				lnmin:lnmin,
				lnmax:lnmax,
				yrmin:yrmin,
				yrmax:yrmax,
				conditionid:conditionid,
				typeid:typeid,
				categoryid:categoryid,
				enginetypeid:enginetypeid,
				drivetypeid:drivetypeid,
				fueltypeid:fueltypeid,
				stateid:stateid,
                inoption:6,
				az:153,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                displayopt = data[0].displayopt;
                mlistnormal = data[0].mlistnormal;
                mlistassign = data[0].mlistassign;
				total_boat = data[0].total_boat;
				validboat = data[0].found;
				
				$(".validboat" + rowval).html(validboat);

                /*$(".app_box2_" + rowval).html(mlistassign);
                if (displayopt == 1){
                    $(".app_box1_" + rowval).html(mlistnormal);
                }*/
				$(".app_box1_" + rowval).html(mlistnormal);
				$("#total_boat" + rowval).val(total_boat);
                hide_wait_msg();
            });
		}
    });
}
</script>
<?php
include($bdr."includes/foot.php");
?>