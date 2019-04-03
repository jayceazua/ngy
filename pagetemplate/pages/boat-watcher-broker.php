<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$isdashboard = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5, 6));
$atm1 = $link_name = "Boat Watcher";

$currenturl = $_SERVER["REQUEST_URI"];
$_SESSION["listing_file_name"] = $currenturl;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 8, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

$usertype = $yachtclass->get_user_type($loggedin_member_id);
$company_id = $yachtclass->get_broker_company_id($loggedin_member_id);

$watcherparam = array(
	"boatwatchercode" => "",
	"name" => "",
	"email_to" => "",
	"searchfield" => "{}",
	"schedule_days" => 0,
	"schedule_date" => "",
	"counter" => 0,
	"usertype" => $usertype,
	"formtemplate" => 2
);

include($bdr."includes/head.php");
?>

<?php echo $html_start; ?>

<form method="post" id="boat_watcher_ff" name="ff">
<div class="groupheadmain clearfixmain">
   <?php echo $boatwatcherclass->boat_watcher_form($watcherparam); ?>
</div>
</form>

<div class="groupcontentmain clearfixmain">
	<?php
    echo $boatwatcherclass->display_boat_watcher(); 
	?>
</div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
	//add - edit
	$(".main").off("click", ".addeditboatwatcher").on("click", ".addeditboatwatcher", function(){
		var all_ok = "y";
		var setfocus = "n";
		var rowval = $(this).attr("rowval");
		var btype = parseInt($("#btype" + rowval).val());
		
		if (!field_validation_border("name" + rowval, 1, 1)){
			all_ok = "n";			
		}
		
		if (btype != 6){			
			if (!field_validation_border("email_to" + rowval, 1, 1)){
				all_ok = "n";			
			}
			
			
			if (all_ok == "n"){
				return false;
			}
		}
		
		//process
		$("#fc_msg").html("Please wait......");
		$("#fc_msg").show();		
		
		var ms =  $(this).attr("boatwatchercode");
		
		var name = $("#name" + rowval).val();
		
		var email_to = "";
		if ($("#email_to" + rowval).length > 0){
			email_to = $("#email_to" + rowval).val();
		}
		
		var schedule_days = 0;
		if ($("#schedule_days" + rowval).length > 0){
			schedule_days = $("#schedule_days" + rowval).val();
		}
		
		var schedule_days_old = 0;
		if ($("#schedule_days_old" + rowval).length > 0){
			schedule_days_old = $("#schedule_days_old"+ rowval).val();
		}
		
		var makeid = $("#mid" + rowval).val();
		var yrmin = $("#yrmin" + rowval).val();
		var yrmax = $("#yrmax" + rowval).val();
		var prmin = $("#prmin" + rowval).val();
		var prmax = $("#prmax" + rowval).val();
		var lnmin = $("#lnmin" + rowval).val();
		var lnmax = $("#lnmax" + rowval).val();
		var categoryid = $("#categoryid" + rowval).val();
		var conditionid = $("#conditionid" + rowval).val();
		var typeid = $("#typeid" + rowval).val();
		var enginetypeid = $("#enginetypeid" + rowval).val();
		var drivetypeid = $("#drivetypeid" + rowval).val();
		var fueltypeid = $("#fueltypeid" + rowval).val();
		var stateid = $("#stateid" + rowval).val();
				
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{
			name:name,
			email_to:email_to,
			schedule_days:schedule_days,
			schedule_days_old:schedule_days_old,
			
			makeid:makeid,
			yrmin:yrmin,
			yrmax:yrmax,			
			prmin:prmin,
			prmax:prmax,
			lnmin:lnmin,
			lnmax:lnmax,			
			categoryid:categoryid,
			conditionid:conditionid,
			typeid:typeid,
			enginetypeid:enginetypeid,			
			drivetypeid:drivetypeid,
			fueltypeid:fueltypeid,
			stateid:stateid,
			ms:ms,			
			inoption:1,
			az:156,
			dataType: "json"
		},
		function(data){
			data = $.parseJSON(data);
			returntext = data.returntext;
			$(".groupcontentmain").html(returntext);		
			
			if (rowval == 0){
				$("#mid" + rowval).val(0);
				$("#keyterm" + rowval).val("");
				$("#name" + rowval).val("");
				
				if (btype != 6){
					$("#email_to" + rowval).val("");
				}
				
			}
			$("#fc_msg").html("");
			$("#fc_msg").hide();
		});		
	});
	//end
	
	//edit open
	$(".main").off("click", ".group_edit").on("click", ".group_edit", function(){
		var rowval = $(this).attr("rowval");
		$(".edit_mode" + rowval).removeClass("com_none");
		$(".assign_model_holder").hide();
	});
	//end
	
	//cancel edit - close
	$(".main").off("click", ".update_cancel").on("click", ".update_cancel", function(){
		var rowval = $(this).attr("rowval");
		$(".edit_mode" + rowval).addClass("com_none");
	});
	//end
	
	//delete report
	$(".main").off("click", ".group_del").on("click", ".group_del", function(){		
		var boatwatchercode =  $(this).attr("boatwatchercode");
		
		var a = confirm("Are you sure?");
		if (a){
			//Process
			$("#fc_msg").html("Please wait......");
			$("#fc_msg").show();
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				boatwatchercode:boatwatchercode,
				inoption:2,
				az:156,
				dataType: 'json'
			},
			function(data){
				data = $.parseJSON(data);
				returntext = data.returntext;
				$(".groupcontentmain").html(returntext);
				$("#fc_msg").html("");
				$("#fc_msg").hide();
			});		
			//end
		}		
	});
	//end
});
</script>

<?php echo $html_end; ?>
<?php
include($bdr."includes/foot.php");
?>