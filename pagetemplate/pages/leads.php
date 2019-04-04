<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$display_brd_array = "n";
$main_heading = "n";
$isdashboard = 1;
$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));

$main_heading = "n";
$googlemap = 0;
$atm1 = $link_name = "Leads";

$breadcrumb = 0;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

$currentyear = date("Y");
$startyear = $currentyear - 10;

$currentdate = date("Y-m-d");
$currentdate_display = $cm->display_date($currentdate, 'y', 9);

$startdate = date('Y-m-d', strtotime("-29 days"));
$startdate_display = $cm->display_date($startdate, 'y', 9);

$p = 1;
$retval = json_decode($leadclass->form_lead_list($p));
$foundm = $retval[0]->totalrec;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 5, "m2" => 1, "link_name" => $link_name)));
$html_start2 = $htmlstartend->htmlstart2;
$html_end2 = $htmlstartend->htmlend2;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
echo $html_start2
?>
<div class="dashboard-contentcol-inner clearfixmain">
	<div class="recordsearchform clearfixmain">
		<form name="leadrec" id="leadrec" method="post" action="<?php echo $cm->folder_for_seo;?>">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="exportleads" />

		<ul class="form">
			<li class="left">
				<div class="fieldlabel">Date Range:</div>
				<div class="fieldval clearfixmain">
					<div class="left-side clearfixmain"><input defaultdateset="" rangeyear="<?php echo $startyear;?>:<?php echo $currentyear;?>" type="text" id="fdate" name="fdate" value="<?php echo $startdate_display; ?>" class="date-field-a input3 dateclear" placeholder="Date From" /></div>
					<div class="right-side clearfixmain"><input defaultdateset="" rangeyear="<?php echo $startyear;?>:<?php echo $currentyear;?>" type="text" id="tdate" name="tdate" value="<?php echo $currentdate_display; ?>" class="date-field-a input3 dateclear" placeholder="Date To" /></div>
				</div>
			</li>
			<li class="right">
				<div class="fieldlabel">Form Type:</div>
				<div class="fieldval"><select name="leadformtype" id="leadformtype" class="select">
						<option value="0">All</option>
						<?php
						echo $leadclass->get_lead_form_type_combo(0);
						?>               
				</select></div>
			</li>
			
			<li>        
				<button fsection="1" type="button" class="button filterrecord">Search</button>
				<a href="javascript:void(0);" title="Excel Export" class="pdficon exportrecord"><img src="<?php echo $cm->folder_for_seo; ?>images/excel-icon.png" /></a>
			</li>
		</ul>

		</form>
	</div>
</div>

<div class="spacertop clearfixmain">
	<div class="dashboard-contentcol-inner clearfixmain">
		<div class="recordcountdisplay clearfixmain"><div class="spinnersmall"><span class="reccounterupdate"><?php echo $foundm; ?></span> Result(s)</div></div>

		<div class="responsivetableholder clearfixmain">
			<div id="filtersection" class="mostviewed responsivetable clearfixmain">
				<?php
				echo $retval[0]->doc;
				?>
			</div>
		</div>
		<div class="mostviewed clearfixmain">
			<?php
			echo '<p class="t-center">'. $retval[0]->moreviewtext .'</p>';
			?>
		</div>
	</div>	
</div>
	
<script type="text/javascript">
    $(document).ready(function(){		
		$(".date-field-b").each(function(){		
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');	
	
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
	
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: bkfolder + 'images/jump_date.jpg',
				buttonImageOnly: true,
				gotoCurrent: true,
				dateFormat: "mm/dd/yy",						
			});		
		});
		
		
        $.fn.filtelist = function(p, pp){
            $(".spinnersmall").addClass("spinnersmallimg");   
			var fdate = $("#fdate").val();  
			var tdate = $("#tdate").val();   
			var leadformtype = $("#leadformtype").val();   
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    p:p,
					pp:pp,
					fdate:fdate,
					tdate:tdate,
					leadformtype:leadformtype,
                    az:34,
					inoption:1,
                    dataType: 'json'
                },
                function(data){				
                    data = $.parseJSON(data);
                    content = data[0].doc;
                    moreviewtext = data[0].moreviewtext;
                    if (content != ""){
                        totalrec = data[0].totalrec;
                        if (p == 1){
                            $("#filtersection").html(content);
                        }else{
                            $("#filtersection").append(content);
                        }
                    }else{
                        totalrec = 0;
                        $('#filtersection').html('No forms submitted.');
                    }
                    $(".t-center").html(moreviewtext);
                    $(".spinnersmall").removeClass("spinnersmallimg");
                    $(".recordcountdisplay span.reccounterupdate").html(totalrec);
                });
        }
		
		$(".main").on("click", ".filterrecord", function(){
			$(this).filtelist(1, 0);
		});
		
		$(".main").on("click", ".exportrecord", function(){
			$('#leadrec').submit();
		});

        $(".main").on("click", ".moreviewleads", function(){
            var p = $(this).attr("p");
            $(this).filtelist(p, 0);
        });
		
		//lead del
		$(".main").off("click", ".lead_del").on("click", ".lead_del", function(){
			var lead_id =  $(this).attr("lead_id");
			var p =  $(this).attr("p");
			var a = confirm("Are you sure?");
			if (a){
				//Process
				dispay_wait_msg("Please wait!!!!!");
				var b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					lead_id:lead_id,
					p:p,
					inoption:2,
					az:34,
					dataType: 'json'
				},
				function(data){
					data = $.parseJSON(data);
					returntext = data.returntext;
					
					if (returntext == "y"){
						$(this).filtelist(p, 1);
					}					
					hide_wait_msg();
				});
			}
		});
		
    });
</script>
<?php
echo $html_end2;
include($bdr."includes/foot.php");
?>