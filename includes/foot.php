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
				echo $disclaimer_text;
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
				echo $disclaimer_text;
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
//echo $frontend->display_sudden_popup();
echo $frontend->display_formsubmit_popup();
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
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/parallax.min2.js"></script>

<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/validator.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.extra.js"></script>
<script defer="defer" type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/creditapplication.js"></script>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/wow.min.js"></script>

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

<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/abclient.js"></script>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/abjquery.js"></script>

<?php
$google_analytics = $cm->get_systemvar('GLANY');
echo $google_analytics;
?>

<div class="cookie-options-hook cookie-hidden">
    <a href="javascript:void(0);" class="hook-link">
        <div class="triangle"></div>
        <div class="triangle-int">C</div>
    </a>
</div>
<div class="cookie-message cookie-hide">
    <div class="cookie-header">Cookie Control <span class="close" id="close">X</span></div>
    <div class="cookie-message-container">
        <p class="cookie-message__text">We have placed cookies on your device to help make this website better.</p>
        <p class="cookie-message__text">You can use this tool to change your cookie settings. Otherwise, we’ll assume you’re OK to continue.</p>

        <div class="extra-info cookie-hide">
            <p class="cookie-message__text">Some of the <a href="http://www.boatsgroup.com/cookies-policy/" target="_blank">cookies we use</a> are essential for the site to work.</p>
            <p class="cookie-message__text">We also use some non-essential cookies to collect information for making reports and to help us improve the site. The cookies collect information in an anonymous form.</p>
            <p class="cookie-message__text">To control third party cookies, you can also <a href="http://www.boatsgroup.com/cookies-policy/" target="_blank" id="adjust-browser">adjust your browser settings</a>.</p>
            <div class="cookie-message__button-wrapper" id="cookies-off">
                <button class="cookie-message__button" >Turn cookies Off</button>
            </div>
            <div class="cookie-message__button-wrapper cookie-hide" id="cookies-on">
                <button class="cookie-message__button_red">Turn cookies On</button>
            </div>
        </div>

        <div class="cookie-message__button-wrapper">
            <button class="cookie-message__button" id="fine">I'm fine with this</button>
        </div>
        <a class="cookie-message__link" id="info" href="javascript:void(0);">Information and Settings</a>
        <a class="cookie-message__link cookie-hide" id="less" href="javascript:void(0);">read less</a>
        <a class="cookie-message__link cookie-link" href="http://www.boatsgroup.com/cookies-policy/" target="_blank">About our cookies</a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/dmm-analytics.min.js"></script>
<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/cookie-popup.min.js"></script>

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

$facebook_pixel_code = $cm->get_systemvar('FBPXC');
echo $facebook_pixel_code;

//$live_chat_code = $cm->get_systemvar('LVCHC');
//echo $live_chat_code;

$call_tracking_code = $cm->get_systemvar('CTSCD');
echo $call_tracking_code;
?>
</body>
</html>
<?php
$db->close();
$db->pdo_close(); 
?>