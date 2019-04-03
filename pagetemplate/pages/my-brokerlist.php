<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;

$atm1 = $link_name = "Broker List";

$breadcrumb = 1;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

include($bdr."includes/head.php");
?>
<div class="profile-main">
	<ul class="listmenu">
        <li class="left"><a href="<?php echo $bdir; ?>add-broker/" class="icon-add">Add Broker</a></li>
        <li class="right"><div class="inv-print"><a href="<?php echo $cm->folder_for_seo; ?>print-inventory-broker/" class="stools printbroker" data-fancybox-type="iframe"><span>Print</span></a></div></li>
    </ul>	
    <div class="clear"></div>
</div>
<div class="common_header">
        <div class="header-bottom-inner">
            <div class="sch">
                <span><?php echo $link_name; ?></span>
            </div>
            <div class="vp">
                <ul class="form">
                    <li class="left">
                        <input type="text" id="unm" name="unm" class="input" placeholder="Username" />
                    </li>
                    <li class="left">
                        <input type="text" id="eml" name="eml" class="input" placeholder="Email" />
                    </li>
                    <li class="leftbutton">
                        <a class="filterrecord active" fsection="1" href="javascript:void(0);" title="Filter"><img src="<?php echo $cm->folder_for_seo;?>images/search.png" /></a>
                    </li>
                </ul>
            </div>
            <div class="res"><div class="spinnersmall">&nbsp;</div></div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
</div>

<div id="filtersection" class="mostviewed">
    <?php
	$collectoption = 3;
    $p = 1;
    $retval = json_decode($yachtclass->my_broker_list($p, $loggedin_member_id, $collectoption));
    echo $retval[0]->doc;
    ?>
    <div class="clear"></div>
</div>
<div class="mostviewed">
    <?php
    echo '<p class="t-center">'. $retval[0]->moreviewtext .'</p>';
    ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$.fn.filterbroker = function(p){
			$(".spinnersmall").addClass("spinnersmallimg");
			var unm = $("#unm").val();
			var eml = $("#eml").val();
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
				{
					p:p,
					collectoption:'<?php echo $collectoption; ?>',
					unm:unm,
					eml:eml,
					az:17,
					dataType: 'json'
				},
				function(data){
					data = $.parseJSON(data);
					content = data[0].doc;
					moreviewtext = data[0].moreviewtext;
					if (content != ""){
						if (p == 1){
							$("#filtersection").html(content);
						}else{
							$("#filtersection").append(content);
						}
					}else{
						$('#filtersection').html('Sorry. Record unavailable.');
					}
					$(".t-center").html(moreviewtext);
					$(".spinnersmall").removeClass("spinnersmallimg");
				});
		}

		$(".main").on("click", ".filterrecord", function(){
			$(this).filterbroker(1);
		});

		$(".main").on("click", ".morebroker", function(){
			var p = $(this).attr("p");
			$(this).filterbroker(p);
		});

		$(".main").on("click", ".removebroker", function(){
			var a = confirm("Are you sure you want to remove this Broker?");
			if (a){
				mbid = $(this).attr('mbid');
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						mbid:mbid,
						az:18,
						dataType: 'json'
					},
					function(data){
						data = $.parseJSON(data);
						content = data[0].retval;
						if (content == 'y'){
							window.location.reload();
						}else{
							optiontext = data[0].optiontext;
							alert(optiontext);
						}
					});
			}
		});
		
		//print broker
		$('.printbroker').colorbox({
			iframe:true,
			width: "90%",
        	height: "90%",
			maxWidth: 800,
        	maxHeight: 800,
			fixed:true,
			current:''
		});
	});
</script>
<?php
include($bdr."includes/foot.php");
?>