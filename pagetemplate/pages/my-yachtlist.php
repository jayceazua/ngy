<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$isdashboard = 1;
$_SESSION["s_insidedb"] = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));

$atm1 = $link_name = "Inventory";
$breadcrumb = 0;
/*$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);*/
$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}

$currenturl = $_SERVER["REQUEST_URI"];
$_SESSION["listing_file_name"] = $currenturl;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 1, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");

$_SESSION["s_currenturl"] = '';
$yachtclass->remove_yach_search_var();
$sql = $yachtclass->create_yacht_sql(2);
$foundm = $yachtclass->total_yach_found($sql);

$param_page = array(
	"to_get_val" => $sql,
	"section_for" => 2
);
$to_check_val = $cm->insert_data_set($param_page);
$compareboat = 2;

echo $html_start;
?>
<div class="profile-main clearfixmain">
	<ul class="listmenu">
        <li class="left"><a href="<?php echo $cm->folder_for_seo; ?>add-boat/" class="icon-add">Add Boat</a></li>
        <li class="right t-right">
        	<input title="Select All Listings to Print" class="sidebyside selectallboatprint" type="checkbox" name="selectallboatprint" id="selectallboatprint" />
    
            <div class="inv-print clearfixmain">
                <a href="javascript:void(0);" class="stools openprintpop"><i class="fa fa-print" aria-hidden="true"></i><span>Print Listing(s)</span><i class="fa fa-caret-down" aria-hidden="true"></i></a>
            </div>
        </li>
    </ul>	
</div>
<div class="profile-main clearfixmain">	
    <div class="header-bottom-bg clearfixmain">
        <div class="header-bottom-inner clearfixmain">
            <div class="sch">
                <span>Inventory</span>
            </div>
            <div class="vp">
                <span>View options</span>
                <a href="javascript:void(0);" compareboat="<?php echo $compareboat; ?>" dval="1" dstat="1" title="Active List" class="boatlist"><img src="<?php echo $bdir; ?>images/active-icon.png" alt="Active List" /></a>
                <a href="javascript:void(0);" compareboat="<?php echo $compareboat; ?>" dval="1" dstat="2" title="Inactive List" class="boatlist"><img src="<?php echo $bdir; ?>images/inactive-icon.png" alt="Inactive List" /></a>
                <a href="javascript:void(0);" compareboat="<?php echo $compareboat; ?>" dval="1" dstat="4" title="Preview Mode List" class="boatlist"><img src="<?php echo $bdir; ?>images/preview-icon.png" alt="Preview Mode List" /></a>
                <a href="javascript:void(0);" compareboat="<?php echo $compareboat; ?>" dval="1" dstat="3" title="Expired List" class="boatlist"><img src="<?php echo $bdir; ?>images/expired-icon.png" alt="Expired List" /></a>
            </div>
            <div class="res">
            	<div class="spinnersmall"><span class="reccounterupdate"><?php echo $foundm; ?></span> result(s)</div>
                <div class="sorttool">
                	<?php 
						$sort_retval = json_decode($yachtclass->display_sort_option());
						$sortop = $sort_retval->sortop;
						$orderbyop = $sort_retval->orderbyop;	
						echo $sort_retval->doc;					
					?>                    
                </div>
            </div>            
        </div>
    </div>
</div>
<div class="boatlisting-detail clearfixmain">
    <div class="left-cell boatsearchcol scrollcol"  parentdiv="boatlisting-detail">
        <?php echo $yachtchildclass->yacht_search_column(array("searchoption" => 2, "dashboardinventory" => 1)); ?>
    </div>
    
    <div id="listingholdermain" class="right-cell clearfixmain" to_check_val="<?php echo $to_check_val; ?>">
        <?php
		$param = array(
			"compareboat" => $compareboat,
			"displayoption" => 1,
			"ajaxpagination" => 0,
			"dstat" => 0,
			"sortop" => $sortop,
			"orderbyop" => $orderbyop,
			"to_check_val" => $to_check_val
		);
        $retval = json_decode($yachtclass->display_yacht_listing(1, $param));
        echo $retval[0]->doc;
        ?>
    </div>
</div>

<?php echo $html_end; ?>

<div class="print-inline all-overlay custom-overlay">
	<div class="custom-overlay-container clearfixmain">
    	<div class="custom-overlay-close"><a href="javascript:void(0);" title="Close"><img src="<?php echo $cm->folder_for_seo; ?>images/inactive-icon.png" /></a></div>
    
    	<form id="printlist" name="printlist">
        	<div class="printlist-section-heading">Select Template</div>
            
            <div class="printlist-template-selection clearfixmain">
            	<ul>
                	<li><input class="radiobutton radiolist" type="radio" name="printoption" id="printoption1" value="1" checked="checked" /><label for="printoption1">List View<img src="<?php echo $cm->folder_for_seo; ?>images/printtemplate/1.jpg" /></label></li>
                    <li><input class="radiobutton radiolist" type="radio" name="printoption" id="printoption2" value="2" /><label for="printoption2">One Listing Page<img src="<?php echo $cm->folder_for_seo; ?>images/printtemplate/2.jpg" /></label></li>         
                    <li><input class="radiobutton radiolist" type="radio" name="printoption" id="printoption3" value="3" /><label for="printoption3">Two Listing Page<img src="<?php echo $cm->folder_for_seo; ?>images/printtemplate/3.jpg" /></label></li>
                    <li><input class="radiobutton radiolist" type="radio" name="printoption" id="printoption4" value="4" /><label for="printoption4">Two Listing 1:1<img src="<?php echo $cm->folder_for_seo; ?>images/printtemplate/4.jpg" /></label></li>
                    <li><input class="radiobutton radiolist" type="radio" name="printoption" id="printoption5" value="5" /><label for="printoption5">Full Listing<img src="<?php echo $cm->folder_for_seo; ?>images/printtemplate/5.jpg" /></label></li>
                </ul>
            </div>
            
            <div class="printlist-section-heading">Broker Display Options</div>
            
            <div class="printlist-broker-selection clearfixmain">
            	<ul>
                	<li><input class="radiobutton radiobutton0" type="radio" name="include_broker" id="include_broker1" value="0" checked="checked" /><label for="include_broker1"><span></span>None</label></li>
                    <li><input class="radiobutton radiobutton1" type="radio" name="include_broker" id="include_broker2" value="1" /><label for="include_broker2"><span></span>My Profile Only</label></li>
                    <li><input class="radiobutton radiobutton2" type="radio" name="include_broker" id="include_broker3" value="2" disabled="disabled" /><label for="include_broker3"><span></span>Listing Brokers</label></li>
                    <li><input class="radiobutton radiobutton3" type="radio" name="include_broker" id="include_broker4" value="3" /><label for="include_broker4"><span></span>Company Only</label></li>
                </ul>
            </div>
            
            <div class="printlist-error clearfixmain">Please select boat for print</div>
            <div class="printlist-message clearfixmain">You will receive an email with attachment PDF soon</div>
            <div class="printlist-submit clearfixmain"><input class="button printbtn" popurl="<?php echo $cm->folder_for_seo; ?>?fcapi=dashboardprintboats" type="button" name="printbtn" id="printbtn" value="Preview" /></div>            
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		$.fn.resetfilter = function(){			
			$('.my-dropdown2').val(0);
			$('#mfcname').val('');
			$('#brokername').val('');
			$('input[name="allmy"][value="1"]').prop('checked', true);
			$('.brokersearchdiv').addClass('com_none');
		}
		
        $(".main").on("click", ".boatlist", function(){
			
			if ($(this).hasClass("inactive")){
				return false;
			}
			
			$(this).resetfilter();
            $(".spinnersmall").addClass("spinnersmallimg");
            var dstat = $(this).attr('dstat');

            $(".boatlist").removeClass('active');
            $(this).addClass('active');

			var sortfieldoption = $(this).getsortfields();
			var sortop = sortfieldoption[0];
			var orderbyop = sortfieldoption[1];
			
			var compareboat = 0;
			if ($('.vp a').attr('compareboat') !== undefined) {
				compareboat = $(".vp a").attr('compareboat');
			}
			
			var to_check_val = $("#listingholdermain").attr("to_check_val");

            var b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
            {
                compareboat:compareboat,
				dstat:dstat,
				sortop:sortop,
				orderbyop:orderbyop,
				to_check_val:to_check_val,
				qreset:1,
                az:12,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                content = data[0].doc;
                displayoption = data[0].displayoption;

                if (content != ""){
                    totalrec = data[0].totalrec;
                    $("#listingholdermain").html(content);
                }else{
                    totalrec = 0;
                    $('#listingholdermain').html('Sorry. Record unavailable.');
                }

                $(".spinnersmall").removeClass("spinnersmallimg");
                $(".res span.reccounterupdate").html(totalrec);
                if($(window).width() <= 685){
                    //$(".ad-search-con").slideToggle();
                }
            });
        });
		
		$(".allmylisting").change(function(){
			var sel_option_id = $(this).val();
	
			if (sel_option_id == 1){
				$('.brokersearchdiv').addClass('com_none');
				$(".boatlist").removeClass('inactive');
			}else{
				$('.brokersearchdiv').removeClass('com_none');
				$('#brokername').val('');

				$(".boatlist").removeClass('active');
				$(".boatlist").addClass('inactive');
			}		
		});		
		
		//print section				
		$(".radiolist").click(function(){
			var printoption = $('input[name=printoption]:radio:checked').val();
			if (printoption == 1){
				$('.radiobutton0').prop("checked","checked");
				$('.radiobutton2').prop("disabled","disabled");
			}else{
				$('.radiobutton2').prop("disabled","");
			}
		});
		
		$(".selectallboatprint").click(function(){			
			if ($(this).is(':checked')){
				$('.printboatcheckbox').prop( "checked", true );
				$(this).attr( 'title',"Remove All Selection");				
			}else{
				$('.printboatcheckbox').prop( "checked", false );
				$(this).attr( 'title',"Select All Listings to Print");
			}
		});
		
		$(".printbtn").click(function(){
			var popurl = $(this).attr('popurl');
			var printoption = $('input[name=printoption]:radio:checked').val();
			var include_broker = $('input[name=include_broker]:radio:checked').val();
			
			var sortfieldoption = $(this).getsortfields();
			var sortop = sortfieldoption[0];
			var orderbyop = sortfieldoption[1];
			
			//check print boat selected
			var boatselected = '';
			$('.main .printboatcheckbox').each(function(i){
				if ($(this).is(':checked')){
					var cbox_value = $(this).val();
					boatselected = boatselected  + cbox_value + ',';
				}
			});
			
			if (boatselected == ''){
				//errormessagepop("Please select boat for print");
				$(".printlist-message").hide();
				$(".printlist-error").show();
				return false;
			}			
			
			//$(".custom-overlay").hide();
			popurl = popurl + '&printoption=' + printoption + '&include_broker=' + include_broker + '&sortop=' + sortop + '&orderbyop=' + orderbyop + '&boatselected=' + boatselected;
			$.fancybox.open({
				src: popurl,
				type: 'iframe',
				toolbar  : false,
				smallBtn : true,
				iframe : {
					preload : false,
					css : {
						width  : "90%",
						"max-width": "800px"
					}
				}
			});
			
			return false;
		});		

	});
</script>
<?php
include($bdr."includes/foot.php");
?>