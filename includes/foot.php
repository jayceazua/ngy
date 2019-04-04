<?php
if ($nohead == 1 OR $nohead == 3){
?>
	<?php if (isset($display_boat_disclaimer) AND $display_boat_disclaimer == 1){?>
		<div class="disclaimer_div">
			<?php
				echo $dctxt_boat;
				if ($yw_id > 0){
					echo '<span class="yw">('. $yw_id .')</span>';
				}
			?>
		</div>
		<?php } ?>

		<?php if (isset($display_yachtworld_disclaimer) AND $display_yachtworld_disclaimer == 1){?>
		<div class="disclaimer_div">
			<?php
				echo $cm->get_systemvar('YWTXT');
				if ($yw_id > 0){
					echo '<span class="yw">('. $yw_id .')</span>';
				}
			?>
		</div>
		<?php } ?>

		<?php if (isset($display_resource_disclaimer) AND $display_resource_disclaimer == 1){?>
		<div class="disclaimer_div"><?php echo $dctxt_resource; ?></div>
		<?php } ?>
    <div class="clearfix"></div>
</div>
</div> 
<?php
}else{
?>
	
	
		<?php if (isset($display_boat_disclaimer) AND $display_boat_disclaimer == 1){?>
        <div class="container clearfixmain">
		<div class="disclaimer_div">
			<?php
				echo $dctxt_boat;
				if ($yw_id > 0){
					echo '<span class="yw">('. $yw_id .')</span>';
				}
			?>
		</div>
        </div>
		<?php } ?>

		<?php if (isset($display_yachtworld_disclaimer) AND $display_yachtworld_disclaimer == 1){?>
        <div class="container clearfixmain">
		<div class="disclaimer_div">
			<?php
				echo $cm->get_systemvar('YWTXT');
				if ($yw_id > 0){
					echo '<span class="yw">('. $yw_id .')</span>';
				}
			?>
		</div>
        </div>
		<?php } ?>

		<?php if (isset($display_resource_disclaimer) AND $display_resource_disclaimer == 1){?>
        <div class="container clearfixmain">
		<div class="disclaimer_div"><?php echo $dctxt_resource; ?></div>
        </div>
		<?php } ?>

		<div class="clearfix"></div>
    <?php 
	if ($startend == 1){
	?>    
	</div> <!--container End-->
    <?php
	}
	?>
	
</div> <!--main End-->
    
<!--Footer Start-->
<?php
//echo $yachtclass->display_company_location_details_footer(array("outsidefooter" => 1));
//echo $frontend->display_mailchimp_form(array("s" => 1));
echo $frontend->get_footer(array("loggedin_member_id" => $loggedin_member_id));
echo $yachtchildclass->sliding_advanced_search_form($isdashboard, $pageid);
echo $frontend->contact_page_pop();
echo $frontend->header_search_content();
echo $frontend->display_sudden_popup();
?>
<!--Footer End-->
<?php
}
?>

<div class="fcajaxloadedcontent-inline all-overlay custom-overlay-common">
    <div class="custom-overlay-container clearfixmain">
        <div class="modal-dialog clearfixmain">
            <div class="custom-overlay-close"><a href="javascript:void(0);" title="Close"><img alt="Close" src="<?php echo $cm->folder_for_seo; ?>images/inactive-icon.png" /></a></div>
            <div class="fcajaxloadedcontent"></div>
        </div>
    </div>
</div>

<a href="javascript:void(0);" class="BackToTop">Top</a>

   
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery-ui.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.smartmenus.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.menu.settings.js"></script>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/html5.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/waypoints.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.counterup.js"></script>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.ui.touch-punch.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.timepicker.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.cookie.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.cycle2.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.cycle2.carousel.js"></script>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/slick.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.fancybox.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.sticky-kit.min.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.previewer.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/parallax.min.js"></script>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/validator.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.extra.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/creditapplication.js"></script>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/call.captcha.js"></script>
<script defer="defer" src="https://www.google.com/recaptcha/api.js?onload=fcCaptchaCallback&render=explicit"></script>


<?php if (isset($yachtform) AND $yachtform == 1){?>
	<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/yachtform.js"></script>
<?php } ?>

<?php if (isset($multiuploadim) AND $multiuploadim == 1){?>
	<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/multiupload.js"></script>
<?php } ?>

<?php if (isset($googlemap) AND ($googlemap == 1 OR $googlemap == 2)){?>
	<script defer="defer" type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $cm->googlemapkey; ?>&#038;sensor=false&#038;ver=3.0"></script>
	<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/markerclusterer_packed.js"></script>
	<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/infobox_packed.js"></script>
<?php } ?>
<?php if (isset($googlemap) AND $googlemap == 2){?>
	<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/maplisting.js"></script>
<?php } ?>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/abclient.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/abjquery.js"></script>

<?php
$google_analytics = $cm->get_systemvar('GLANY');
echo $google_analytics;
?>

<script type="text/javascript">
var airbrake = new airbrakeJs.Client({projectId: 117837, projectKey: 'de3d05543b28193078e680a0e845ae4a'});
if (window.jQuery) 
  airbrakeJs.instrumentation.jquery(airbrake, jQuery);

function ___log__error(aMsg, aUrl, aLine, aCol, aErr) {
 airbrake.notify({
	error:       aErr,
	context:     { script: 'script-name', url : aUrl, line: aLine, col: aCol },
	session:     { userid: 'userid-if-any' }
  });
   return false;
};
//window.onerror = ___log__error;
</script>

<div id="fc_msg" class="errormessagepop">
<?php if ($_SESSION["fr_postmessage"] != ""){ 
	echo $_SESSION["fr_postmessage"]; 
?>
<script language="javascript">
 $('#fc_msg').show();
 setTimeout(function() {
	$('#fc_msg').fadeOut('slow');
}, 3000);
</script>
<?php	
	$_SESSION["fr_postmessage"] = '';
} 
?>
</div>
<?php
$google_remarketing_tag = $cm->get_systemvar('GORTC');
echo $google_remarketing_tag;

//$live_chat_code = $cm->get_systemvar('LVCHC');
//echo $live_chat_code;

$call_tracking_code = $cm->get_systemvar('CTSCD');
echo $call_tracking_code;
?>
</body>
</html>
<?php $db->close(); ?>