<?php
//Initial
$pageid = 0;
$main_heading = "n";
$googlemap = 2;

$cnm = $_REQUEST["cnm"];
$result = $yachtclass->check_company_exist($cnm, 1, 0, 0);

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

$atm1 = "Company Profile";
$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}

$total_y = $yachtclass->get_total_yacht_by_company($id);
$com_inv_url = $cm->get_page_url($id, 'companyinv');

if ($logo_imgpath == ""){
	$logo_imgpath = 'no.png';
}

$usertype = $yachtclass->get_user_type($loggedin_member_id);
$u_company_id = $yachtclass->get_broker_company_id($loggedin_member_id);
$editlink = 0;
if ($usertype == 2 OR $usertype == 3){
	$editlink = 1;
}

$html_start = '';
$html_end = '';
$u_company_id = $yachtclass->get_broker_company_id($loggedin_member_id);
if ($loggedin_member_id > 0 AND $u_company_id == $id){
	$isdashboard = 1;
	$breadcrumb = 0;

	$usertype = $yachtclass->get_user_type($loggedin_member_id);	
	$editlink = 0;
	/*if ($usertype == 1 OR $usertype == 2 OR $usertype == 3){
		$editlink = 1;
	}*/
	
	$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 2, "m2" => 1)));
	$html_start = $htmlstartend->htmlstart;
	$html_end = $htmlstartend->htmlend;
	$ycappnotice = $htmlstartend->ycappnotice;
	$ycappnotice = '
	<div class="profile-main clearfixmain">
	'. $ycappnotice .'
	</div>
	';
	$_SESSION["s_insidedb"] = 1;
	
}else{
	$ycappnotice = '';
	$editlink = 0;
	$breadcrumb = 1;
	$isdashboard = 0;
	$breadcrumb_extra[] = array(
				'a_title' => $cname,
				'a_link' => ''
	);
	
	$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
	$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);
	$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $parentpagear->name, $breadcrumb_extra);
}

include($bdr."includes/head.php");
?>
<?php echo $html_start; ?>

<div class="profile-main">
    <div class="mainleft">
        <img src="<?php echo $bdir; ?>userphoto/<?php echo $logo_imgpath; ?>" alt="<?php echo $companyname; ?>" />
    </div>
    <div class="mainright">        
        <div class="meta">
        <h2><?php echo $cname; ?></h2>            
        </div>            
        <?php echo $yachtclass->display_company_location_details(); ?>
        
        <div class="clear extrapara"></div>
        <p><?php echo $about_company; ?></p>
        <?php if ($u_company_id == $id AND $loggedin_member_id > 0 AND $editlink == 1){?>
        <ul class="listmenu">
            <li class="left"><a href="<?php echo $bdir; ?>editcompanyprofile/" class="icon-editcompany">Edit Company Profile</a></li>
        </ul>  
        <?php } ?>
    </div>
    <div class="clear"></div>
</div>
<?php
	echo $yachtclass->display_industry_associations($id, 1); 
?>

<div class="browseby-head clearfixmain">
    <ul class="browseby-tab">				
        <li class="browsetab browsetab1 active" cdiv="1"><a href="javascript:void(0);">View our Brokers</a></li>
        <li class="browsetab browsetab2" cdiv="2"><a href="javascript:void(0);">View our Inventory</a></li>
        <li class="browsetab browsetab3" cdiv="3"><a href="javascript:void(0);">Our Location(s)</a></li>
    </ul>
</div>

<div idb="<?php echo $isdashboard; ?>" cnm="<?php echo $cnm; ?>" companyid="<?php echo $id; ?>" class="company_data_holder clearfixmain">
	<?php
    $postfields = array(            
        "isdashboard" => $isdashboard
    );
    echo $yachtchildclass->company_profile_broker_list($postfields);
    ?>
</div>



<?php echo $html_end; ?>

<script type="text/javascript">
    $(document).ready(function(){
        
		/*------------TAB--------------*/
		$.fn.tabcontentchange = function(cdiv){
			var idb = $(".company_data_holder").attr("idb");
			var cnm = $(".company_data_holder").attr("cnm");
			var companyid = $(".company_data_holder").attr("companyid");
			
			$(".browsetab").removeClass("active");
			$(".browsetab" + cdiv).addClass("active");
			
			dispay_wait_msg("Please wait!!!!!");
			
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{                   
				cnm:cnm,
				companyid:companyid,
				isdashboard:idb,
				inoption:cdiv,
				az:148                    
			},
			function(data){
				$(".company_data_holder").html(data);
				hide_wait_msg();
			});
		}
		
		$(".main").off("click", ".browsetab").on("click", ".browsetab", function(){			
			var cdiv = parseInt($(this).attr("cdiv"));
			//$(".browsetab").removeClass("active");
			//$(this).addClass("active");
			$(this).tabcontentchange(cdiv);
		});
		
		$(".main").off("click", ".browsetaboutside").on("click", ".browsetaboutside", function(){
			var cdiv = parseInt($(this).attr("cdiv"));
			$(this).tabcontentchange(cdiv);
			
			$("html, body").animate({
				scrollTop: $(".company_data_holder").offset().top
			}, 400);
		});
		/*------------TAB END--------------*/
		
		/*------------BROKER--------------*/
		
        $.fn.filterbroker = function(p){
            $(".spinnersmall").addClass("spinnersmallimg");
            var cnm = '<?php echo $cnm; ?>';
			var dval = $(".vp a.active").attr("dval");
			var idb = $(".vp a.active").attr("idb");
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    p:p,
					dval:dval,
					idb:idb,
                    cnm:cnm,
                    az:20,
                    dataType: 'json'
                },
                function(data){
                    data = $.parseJSON(data);
                    content = data[0].doc;
                    moreviewtext = data[0].moreviewtext;
					displayoption = data[0].displayoption;
					
					if (displayoption == 3){
						//map view
						totalrec = data[0].totalrec;
						$("#filtersection").html(content);
						var mapdataar = data[0].mapdoc;											
						listingMap(mapdataar);
					}else{
					
						if (content != ""){
							totalrec = data[0].totalrec;
							if (p == 1){
								$("#filtersection").html(content);
							}else{
								$("#brokerlistingholder").append(content);
							}
						}else{
							totalrec = 0;
							$('#filtersection').html('Sorry. Record unavailable.');
						}
					}
					
                    $(".t-center").html(moreviewtext);
                    $(".spinnersmall").removeClass("spinnersmallimg");
                    $(".res span.reccounterupdate").html(totalrec);
                });
        }

        $(".main").off("click", ".filterrecord").on("click", ".filterrecord", function(){
            $(this).filterbroker(1);
        });

        $(".main").off("click", ".morebroker").on("click", ".morebroker", function(){
            var p = $(this).attr("p");
            $(this).filterbroker(p);
        });
		
		//display change
		$(".main").off("click", ".brokerchange").on("click", ".brokerchange", function(){
			var dval = $(this).attr("dval");
			dval = parseInt(dval);
			
			if (dval == 3){
				$(".mostviewedpagination").addClass("com_none");
			}else{
				$(".mostviewedpagination").removeClass("com_none");
			}
			
			$(".brokerchange").removeClass("active");
			$(this).addClass("active");
			$(this).filterbroker(1);
		});
		/*------------BROKER END--------------*/
    });
</script>

<?php
include($bdr."includes/foot.php");
?>