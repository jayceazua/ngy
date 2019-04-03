<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$googlemap = 0;
$isdashboard = 1;

$atm1 = $link_name = "Site Statistics";
$breadcrumb = 0;
$company_id = $yachtclass->get_broker_company_id($loggedin_member_id);

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 4, "m2" => 1, "link_name" => $link_name)));
$html_start2 = $htmlstartend->htmlstart2;
$html_end2 = $htmlstartend->htmlend2;
$ycappnotice = $htmlstartend->ycappnotice;

$currentyear = date("Y");
$startyear = $currentyear - 10;

$currentdate = date("Y-m-d");
$currentdate_display = $cm->display_date($currentdate, 'y', 9);

$startdate = date('Y-m-d', strtotime("-29 days"));
$startdate_display = $cm->display_date($startdate, 'y', 9);

include($bdr."includes/head.php");
?>
<?php echo $html_start2; ?>

<!--Search bar main-->
<div class="dashboard-contentcol-inner clearfixmain">
	<ul class="form">
   		<li><p><strong>General Selection</strong></p></li>
	</ul>	
    	<?php
		if ($loggedin_member_id == 1){
		?>
        <ul class="form">
			<li class="left">
				<div class="fieldlabel">Broker:</div>
				<div class="fieldval"><select name="chosanbrokerid" id="chosanbrokerid" class="select impressionviewlead">
					<option value="0">All</option>
					<?php
					echo $yachtclass->get_all_broker_combo(0, 0);
					?>               
				</select></div>
			</li>

			<li class="right">
				<div class="fieldlabel">Location:</div>
				<div class="fieldval"><input type="hidden" name="company_id" id="company_id" value="1">
				<select name="location_id" id="location_id" class="select impressionviewlead">
					<option value="0">All Locations</option>
					<?php echo $yachtclass->get_company_location_combo(0, $company_id); ?>
				</select></div>
			</li>        

			<li class="left">
				<div class="fieldlabel">Range:</div>
				<div class="fieldval clearfixmain">
					<div class="left-side clearfixmain">                            
						<input type="text" rangeyear="<?php echo $startyear;?>:<?php echo $currentyear;?>" id="fr_date" name="fr_date" placeholder="From" class="date-field-a input3 dateclear" value="<?php echo $startdate_display; ?>" />
					</div>
					<div class="right-side clearfixmain">                            
						<input type="text" rangeyear="<?php echo $startyear;?>:<?php echo $currentyear;?>" id="to_date" name="to_date" placeholder="To" class="date-field-a input3 dateclear" value="<?php echo $currentdate_display; ?>" />
					</div>
				</div>           
			</li>
			<li class="right">        	
				<div class="clearfixmain">
					Last:&nbsp;
					<input type="radio" value="1" name="fixed_date" class="radiobutton fixeddate" /> 7 days &nbsp;&nbsp;
					<input type="radio" value="2" name="fixed_date" class="radiobutton fixeddate" checked="checked" /> 30 days &nbsp;&nbsp;
					<input type="radio" value="3" name="fixed_date" class="radiobutton fixeddate" /> 90 days
				</div>            
			</li>
		</ul>
        <?php
		}else{
		?>
   		<ul class="form">
			<li class="left">
				<div class="fieldlabel">Range:</div>
				<div class="fieldval clearfixmain">
					<div class="left-side clearfixmain">                            
						<input type="text" rangeyear="<?php echo $startyear;?>:<?php echo $$currentyear;?>" id="fr_date" name="fr_date" placeholder="From" class="date-field-a input3" value="<?php echo $startdate_display; ?>" />
					</div>
					<div class="right-side clearfixmain">                            
						<input type="text" rangeyear="<?php echo $startyear;?>:<?php echo $$currentyear;?>" id="to_date" name="to_date" placeholder="To" class="date-field-a input3" value="<?php echo $currentdate_display; ?>" />
					</div>
				</div>        
			</li>
			<li class="right">
				<div class="fieldlabel">Location:</div>
				<div class="fieldval"><select targetcombo="boat_id" name="location_id" id="location_id" class="select impressionviewlead">
					<option value="0">All Locations</option>
					<?php echo $yachtclass->get_company_location_combo(0, $company_id, 0, $onlylocation); ?>
				</select>
				<input type="hidden" name="company_id" id="company_id" value="1">
				</div>
			</li>
			
			<li class="left">				
				<div class="clearfixmain">
					Last:&nbsp;
					<input type="radio" value="1" name="fixed_date" class="radiobutton fixeddate" /> 7 days &nbsp;&nbsp;
					<input type="radio" value="2" name="fixed_date" class="radiobutton fixeddate" checked="checked" /> 30 days &nbsp;&nbsp;
					<input type="radio" value="3" name="fixed_date" class="radiobutton fixeddate" /> 90 days
				</div>            
			</li>
		</ul>
        <?php
		}
		?>
		
	<ul class="form">
   		<li><p><strong>Boat Selection</strong></p></li>
		<?php
		if ($loggedin_member_id > 1){
		?>
		<li class="com_none">
			<input type="radio" value="1" name="onlymylistings" class="radiobutton" checked="checked" /> My Listings &nbsp;&nbsp;
			<input type="radio" value="0" name="onlymylistings" class="radiobutton" /> All Listings
		</li>
		<?php
		}
		?>
   		<li class="left">
        	<div class="fieldlabel">Boat:</div>
            <div class="fieldval allboat"><input type="hidden" name="boat_id" id="boat_id"><input type="text" id="keyterm" class="azax_auto_stat input" name="keyterm" ckpage="6" counter="0" autocomplete="off" placeholder="Year / Make / Model"></div>
        </li>
		<li class="right">- For multiple boat, please use below fields</li>
	</ul>
	<ul class="form">
   		<li class="left">
        	<div class="fieldlabel">Make:</div>
            <div class="fieldval">
            <input type="hidden" name="mid0" id="mid0">
            <input type="text" id="boat_make" name="boat_make" class="azax_auto input multiboat" ckpage="5" counter="0" autocomplete="off"></div>
        </li>
        <li class="right">
        	<div class="fieldlabel">Model:</div>
            <div class="fieldval">
            <input type="text" id="boat_model" name="boat_model" class="input multiboat"></div>
        </li>
        
        <li class="left">
        	<div class="fieldlabel">Year:</div>
            <div class="fieldval">
            <select class="select multiboat" id="boat_year" name="boat_year">
            	<option value="0" selected="selected">All</option>
            	<?php echo $yachtclass->get_year_combo(0, 1); ?>
			</select>
            </div>
        </li>
        <li class="right">
        	<div class="fieldlabel">Boat Type:</div>
            <div class="fieldval">
            <select class="select multiboat" id="boat_type" name="boat_type">
            	<option value="0" selected="selected">All</option>
            	<?php echo $yachtclass->get_type_combo_parent(0, 0, 0, 1); ?>
			</select>
            </div>
        </li>
	</ul>
	<ul class="form">
		<li>        
			<button type="button" class="button mainsubmit">Search</button>
			<a href="javascript:void(0);" title="PDF Preview" class="pdficon statprint" popurl="<?php echo $cm->folder_for_seo; ?>?fcapi=sitestatprint"><img src="<?php echo $cm->folder_for_seo; ?>images/pdf-icon.png" /></a>
        </li>
	</ul>
</div>
<!--Search bar main end-->

<!--Results-->

<div class="dashboardtab1 clearfixmain">
    <div class="contentwaitmsg contentwaitmsg1"><img src="<?php echo $cm->folder_for_seo; ?>images/ajax-loader.gif" /></div>
    <div class="sitestattabcontent_top"></div>
</div>

<!--Results end-->

<?php echo $html_end2; ?>

<script type="text/javascript">
    $(document).ready(function(){
		//auto date choose
		$(".fixeddate").click(function(){
			var selected_last = $(this).val();			
			var todate = new Date();
			var fromdate = new Date();
			selected_last = parseInt(selected_last);
			
			if (selected_last == 3){
				x = -90;
			}else if (selected_last == 2){
				x = -29;
			}else{
				x = -6;
			}
			fromdate.setDate(fromdate.getDate() + x);			
			
			$('#fr_date').datepicker("setDate", fromdate);
			$('#to_date').datepicker("setDate", todate);
		});
		
		//uncheck last date selection radio
		$(".dateclear").change(function(){
			$(".fixeddate").removeAttr("checked");
		});
		
		//location change for company
		if ($("#company_id").length > 0){
			$("#company_id").change(function(){
				opencompanylocatiob(1);
			});
		}
		
		//boat change for location
		/*if ($("#location_id").length > 0){
			$("#location_id").change(function(){
				opencompanylocationboat($(this), 1);
			});
		}*/

		//boat change for location
		/*if ($("#chosanbrokerid").length > 0){
			$("#chosanbrokerid").change(function(){
				var chosanbrokerid = parseInt($(this).val());
				var targetcombo = $(this).attr("targetcombo");
				if (chosanbrokerid > 0){
					$(".allboat").addClass("com_none");
					$(".userboat").removeClass("com_none");
					$("#keyterm").val('');
					openuserboat(chosanbrokerid, targetcombo);
				}else{
					$(".allboat").removeClass("com_none");
					$(".userboat").addClass("com_none");
					$("#boat_id").val(0);
					$("#boat_id1").val(0);
				}								
			});
		}*/
		
		if ($(".azax_auto_stat").length > 0) {
			var counter;
			$(".azax_auto_stat").autocomplete({
				minLength: 2,
				cache: false,
				source: function(request, response){
					var b_sURL = bkfolder + "includes/ajax.php";
					var ckpage = this.element.attr("ckpage");
					var company_id = $("#company_id").val();
					var location_id = $("#location_id").val();
					
					var chosanbrokerid = 0;
					if ($("#chosanbrokerid").length > 0){
						chosanbrokerid = $("#chosanbrokerid").val();
					}

					var onlymylistings = 0;
					var onlymylistings_check = $(':radio[name=onlymylistings]').length;
					if (onlymylistings_check > 0){
						onlymylistings = $('input[name=onlymylistings]:radio:checked').val();
					}
					
					$.post(b_sURL, 
					{
							keyterm:request.term,
							company_id:company_id,
							location_id:location_id,
							chosanbrokerid:chosanbrokerid,
							onlymylistings:onlymylistings,
							opt:ckpage,
							az:3,
							dataType: "json"
					}, 
					function(data){
						data = $.parseJSON(data);
						response( data );
					});
				},
				select: function( event, ui ) {
					$( "#boat_id" ).val( ui.item.id );
					$( "#keyterm" ).val( ui.item.value );
					
					$( "#mid0" ).val(0);
					$( "#boat_make" ).val("");
					$( "#boat_model" ).val("");
					$( "#boat_year" ).val(0);
					$( "#boat_type" ).val(0);
					return false;
				}
			});
		}
		
		$(".main").off("change", ".multiboat").on("change", ".multiboat", function(){
			$( "#boat_id" ).val(0);
			$( "#keyterm" ).val("");
		});
		
		//Top search
		$(".main").off("click", ".mainsubmit").on("click", ".mainsubmit", function(){
			call_site_stat_search(1, 0);
		});
		
		$(".main").off("change", "#im_view_lead").on("change", "#im_view_lead", function(){
			call_site_stat_search(2, 0);
		});
		
		//pdf print
		$(".main").off("click", ".statprint").on("click", ".statprint", function(){
			var popurl = $(this).attr('popurl');
			
			var company_id = 0;
			if ($("#company_id").length > 0){
				var company_id = $("#company_id").val();
			}

			var chosanbrokerid = 0;
			if ($("#chosanbrokerid").length > 0){
				var chosanbrokerid = $("#chosanbrokerid").val();
			}
			
			var keyterm = $( "#keyterm" ).val();
			if (keyterm == ""){
				$("#boat_id").val(0);
			}
			
			var location_id = $("#location_id").val();
			var fr_date = $("#fr_date").val();
			var to_date = $("#to_date").val();
			
			var boat_id = $("#boat_id").val();
			var mid0 = $("#mid0").val();
			var boat_model = $("#boat_model").val();
			var boat_year = $("#boat_year").val();
			var boat_type = $("#boat_type").val();

			var onlymylistings = 0;
			var onlymylistings_check = $(':radio[name=onlymylistings]').length;
			if (onlymylistings_check > 0){
				onlymylistings = $('input[name=onlymylistings]:radio:checked').val();
			}
			
			var im_view_lead = 1;
			if ($("#im_view_lead").length > 0){
				im_view_lead = $("#im_view_lead").val();
			}
			
			popurl = popurl + '&company_id=' + company_id + '&location_id=' + location_id + '&chosanbrokerid=' + chosanbrokerid + '&onlymylistings=' + onlymylistings + '&boat_id=' + boat_id + '&boat_make=' + mid0 + '&boat_model=' + boat_model + '&boat_year=' + boat_year + '&boat_type=' + boat_type + '&fr_date=' + fr_date + '&to_date=' + to_date + '&im_view_lead=' + im_view_lead;
			$.colorbox({
				href : popurl,
				iframe:true,
				width: "90%",
				height: "90%",
				maxWidth: 900,
				fixed:true,
				current:''
			});
		});
		
		//onload
		call_site_stat_search(1,1);
		
    });
	
	function call_site_stat_search(inoption, initialcall){
		
		if (initialcall == 0){
			//$("#fc_msg").html("Please wait....");
			//$("#fc_msg").show();
		}
		
		var company_id = 0;
		if ($("#company_id").length > 0){
			var company_id = $("#company_id").val();
		}

		var chosanbrokerid = 0;
		if ($("#chosanbrokerid").length > 0){
			var chosanbrokerid = $("#chosanbrokerid").val();
		}

		var keyterm = $( "#keyterm" ).val();
		if (keyterm == ""){
			$("#boat_id").val(0);
		}

		var location_id = $("#location_id").val();
		var fr_date = $("#fr_date").val();
		var to_date = $("#to_date").val();
		
		var boat_id = $("#boat_id").val();
		var mid0 = $("#mid0").val();
		var boat_model = $("#boat_model").val();
		var boat_year = $("#boat_year").val();
		var boat_type = $("#boat_type").val();

		var onlymylistings = 0;
		var onlymylistings_check = $(':radio[name=onlymylistings]').length;
		if (onlymylistings_check > 0){
			onlymylistings = $('input[name=onlymylistings]:radio:checked').val();
		}

		var im_view_lead = 1;
		if ($("#im_view_lead").length > 0){
			im_view_lead = $("#im_view_lead").val();
		}
		
		var az = 154;
		
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{			
			company_id:company_id,
			location_id:location_id,
			chosanbrokerid:chosanbrokerid,
			onlymylistings:onlymylistings,
			boat_id:boat_id,
			boat_make:mid0,
			boat_model:boat_model,
			boat_year:boat_year,
			boat_type:boat_type,
			fr_date:fr_date,
			to_date:to_date,
			im_view_lead:im_view_lead,
			inoption:inoption,			
			az:az,
			dataType: "json"
		},
		function(data){
			data = $.parseJSON(data);
			content = data.doc;
			
			if (inoption == 2){
				search_text = data.extra_return.search_text;
				total_count_title = data.extra_return.total_count_title;
				total_count = data.extra_return.total_count;
				avg_count_title = data.extra_return.avg_count_title;
				avg_count = data.extra_return.avg_count;
				
				$("#large_chart_stat").html(content);
				$(".charthead1 h4").html(search_text);
				$(".charthead1_1 h5").html(total_count_title);
				$(".boxvalue1_1").html(total_count);
				$(".charthead1_2 h5").html(avg_count_title);
				$(".boxvalue1_2").html(avg_count);			
			}else{
				$(".sitestattabcontent_top").html(content);
			}
			
			$(".sitestattabcontent_top img").on("load", function(){
				if (initialcall == 0){
					//$("#fc_msg").hide();
				}
				
				if (initialcall == 1){
					$(".contentwaitmsg1").hide();
				}
			});			
		});
	}
</script>
<?php
include($bdr."includes/foot.php");
?>