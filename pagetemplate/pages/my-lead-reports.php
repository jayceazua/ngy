<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$googlemap = 0;
$isdashboard = 1;

$atm1 = $link_name = "Lead Reports";
$breadcrumb = 0;

$lead_tool_user_det = $cm->get_table_fields('tbl_user_lead_settings', 'emails, timeperiods', $loggedin_member_id, 'user_id');
$emails = $lead_tool_user_det[0]['emails'];
$timeperiods = $lead_tool_user_det[0]['timeperiods'];

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 5, "m2" => 3, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
?>
<?php echo $html_start; ?>
<form method="post" id="brokersearch_ff" name="ff">
    <div class="singleblock clearfixmain">

        <ul class="form">
            <li><p><strong>Lead Reporting Tool</strong></p></li>
            
            <li class="left">
                <p>Email Addresses (seperated by comma and a space)</p>
                <textarea name="emails" id="emails" rows="1" cols="1" class="comments"><?php echo $emails;?></textarea>
            </li> 
            <li class="right">
                <p>Time Periods For Receiving Email</p>
                <input type="radio" id="timeperiods1" name="timeperiods" value="1" class="checkbox" <?php if ($timeperiods == 1){?>checked="checked"<?php } ?> /> Never<br />
                <input type="radio" id="timeperiods1" name="timeperiods" value="2" class="checkbox" <?php if ($timeperiods == 2){?>checked="checked"<?php } ?> /> Daily<br />
                <input type="radio" id="timeperiods2" name="timeperiods" value="3" class="checkbox" <?php if ($timeperiods == 3){?>checked="checked"<?php } ?> /> Weekly<br />
                <input type="radio" id="timeperiods3" name="timeperiods" value="4" class="checkbox" <?php if ($timeperiods == 4){?>checked="checked"<?php } ?> /> Monthly<br /><br />                    
                
                <a href="javascript:void(0);" class="updateleadset button left t-center">Save</a>
                <div class="spinnersmall nextline">&nbsp;</div>	
            </li>                
        </ul>
    </div>
</form>
<?php echo $html_end; ?>

<script type="text/javascript">
    $(document).ready(function(){
        		
		$(".main").on("click", ".updateleadset", function(){
			var all_ok = "y";
			var setfocus = 'n';
			
			var timeperiods = $('input[name=timeperiods]:radio:checked').val();
			if (timeperiods > 1){
				if (!field_validation_border("emails", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, 'emails');
				}
			}
			
			if (all_ok == "n"){
				return false;
			}
			
			var emails = $('#emails').val();
            $(".spinnersmall").html('&nbsp;');
            $(".spinnersmall").addClass("spinnersmallimg");
			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				emails:emails,
				timeperiods:timeperiods,
				az:35,
				dataType: 'json'
			},
			function(data){						
				data = $.parseJSON(data);
				optiontext = data[0].optiontext;					
				$(".spinnersmall").removeClass("spinnersmallimg");
				$(".spinnersmall").html(optiontext);
			});
        });
    });
</script>
<?php
include($bdr."includes/foot.php");
?>