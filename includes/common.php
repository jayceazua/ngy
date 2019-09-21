<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
require_once("ycconfig.php");

require_once("classes/db.class.php");
require_once("classes/common.class.php");
require_once("classes/frontend.class.php");
require_once("classes/yacht.class.php");
require_once("classes/yacht.child.class.php");
require_once("classes/yacht.engine.class.php");
require_once("classes/yacht.pop.class.php");
require_once("classes/geocode.class.php");
require_once("classes/lead.class.php");
require_once("classes/blog.class.php");
require_once("classes/slider.class.php");
require_once("classes/shortcode.class.php");
require_once("classes/file.class.php");
require_once("classes/email.class.php");
require_once("classes/ym.class.php");
require_once("classes/make.class.php");
require_once("classes/model.class.php");
require_once("classes/boattype.class.php");
require_once("classes/creditapplication.class.php");
require_once("classes/template.class.php");
require_once("classes/dataexport.class.php");
require_once("classes/encodedecode.class.php");
require_once("classes/captcha.class.php");
require_once("classes/logoscroll.class.php");
require_once("classes/chart.class.php");
require_once("classes/emailcampaign.class.php");
require_once("classes/slideshow.class.php");
require_once("classes/boat.watcher.class.php");
require_once("classes/instagram.class.php");

$db = new Database(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);      //******************  Database class
$cm = new Commonclass();   //******************  Common class
$yachtclass = new Yachtclass();   //******************  Common class
$yachtchildclass = new Yachtclass_Child();   //******************  Yacht class child
$yachtengineclass = new Yachtclass_Engine();   //******************  Yacht class engine
$yachtpopclass = new Yachtclass_Pop();   //******************  Yacht class pop
$geo = new Geocode();   //******************  Geocoding class
$shortcodeclass = new Shortcodeclass();   //******************  Shortcode class
$frontend = new Frontendclass();   //******************  Frontend class
$leadclass = new Leadclass();   //******************  Lead class
$blogclass = new Blogclass();   //******************  Blog class
$sliderclass = new Sliderclass();   //******************  Slider class
$fle = new Fileclass();    //******************  File class
$sdeml = new Emailclass();   //******************  Email class
$ymclass = new Ymclass();   //******************  YM class
$makeclass = new Makeclass();   //******************  Make class
$modelclass = new Modelclass();   //******************  Model class
$boattypeclass = new BoatTypeclass();   //******************  Boat Type class
$creditappclass = new Creditapplicationclass();   //******************  Credit Application class
$templateclass = new Templateclass();   //******************  Template class
$dataexportclass = new Dataexportclass();   //******************  Data Export class
$edclass = new Encodedecodeclass();   //******************  Encode Decode class
$captchaclass = new Captchaclass();   //******************  Captcha class
$logoscrollclass = new Logoscrollclass();   //******************  Logo Scroll class
$chartclass = new Chartclass();    //******************  Chart class
$emailcampaignclass = new Emailcampaignclass();   //******************  Email Campaign class
$slideshowclass = new Slideshowclass();   //******************  Slideshow class
$boatwatcherclass = new Boatwatcherclass();    //******************  Boat Watcher class
$instagramclass = new Instagramclass();   //******************  Instagram class

/*SHORTCODE CREATION*/

//Brand List Boxes with logo
/*function display_logo_dislay_list_shortcode($argu = array()){
	global $logoscrollclass;
	return $logoscrollclass->logo_dislay_list($argu);
}
$shortcodeclass->add_shortcode( 'fcbrandexplist', 'display_logo_dislay_list_shortcode' );*/

//Only Brand List
/*function display_brand_list_shortcode($argu = array()){
	global $logoscrollclass;
	return $logoscrollclass->display_brand_list_with_img($argu);
}
$shortcodeclass->add_shortcode( 'fcsearchbybrand', 'display_brand_list_shortcode' );*/

//Home Boxes
/*function display_homebox_list_shortcode($argu = array()){
	global $frontend;
	return $frontend->homebox_list($argu);
}
$shortcodeclass->add_shortcode( 'fchomebox', 'display_homebox_list_shortcode' );*/

//Brand Box
function display_brand_box_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_brand_box($argu);
}
$shortcodeclass->add_shortcode( 'fcbrandbox', 'display_brand_box_shortcode' );

//Max Price Boat
/*function display_max_price_boat_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_max_price_boat($argu);
}
$shortcodeclass->add_shortcode( 'fcmaxpriceboat', 'display_max_price_boat_shortcode' );*/

//Featured Boat
function display_featured_yacht_slider_shortcode($argu = array()){
	global $yachtclass;
	return $yachtclass->display_featured_yacht_slider($argu);
}
$shortcodeclass->add_shortcode( 'fcfeaturedboat', 'display_featured_yacht_slider_shortcode' );

//Latest Boat
function display_latest_boat_shortcode($argu = array()){
	global $yachtclass;
	return $yachtclass->display_latest_boat($argu);
}
$shortcodeclass->add_shortcode( 'fclatestboats', 'display_latest_boat_shortcode' );

//In Stock Boat
function display_instock_boat_shortcode(){
	global $yachtclass;
	return $yachtclass->display_instock_boat();
}
$shortcodeclass->add_shortcode( 'fcinstockboat', 'display_instock_boat_shortcode' );

//Featured Testimonial
function featured_testimonial_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_featured_testimonial($argu);
}
$shortcodeclass->add_shortcode( 'featuredtestimonial', 'featured_testimonial_shortcode' );

//Testimonial list
function testimonial_list_shortcode($argu = array()){
	global $frontend;
	return $frontend->testimonial_list_main($argu);
}
$shortcodeclass->add_shortcode( 'fctestimonial', 'testimonial_list_shortcode' );

//Testimonial slider - page
function display_testimonial_slider_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_testimonial_slider($argu);
}
$shortcodeclass->add_shortcode( 'fctestimonialslider', 'display_testimonial_slider_shortcode' );

//Company Location
function company_location_shortcode($argu = array()){
	global $yachtclass;
	return $yachtclass->display_company_location_details($argu);
}
$shortcodeclass->add_shortcode( 'company_location', 'company_location_shortcode' );

//Company Location Map
function company_location_map_shortcode(){
	global $yachtchildclass;
	return $yachtchildclass->display_location_map_view();
}
$shortcodeclass->add_shortcode( 'company_location_map', 'company_location_map_shortcode' );

//news/blog list
function blog_list_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->blog_list_main($argu);
}
$shortcodeclass->add_shortcode( 'fcbloglist', 'blog_list_shortcode' );

//featured blog
function display_featured_blog_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->display_featured_blog($argu);
}
$shortcodeclass->add_shortcode( 'fcfeaturedblog', 'display_featured_blog_shortcode' );

//Latest News and Event
function display_latest_news_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->display_latest_news($argu);
}
$shortcodeclass->add_shortcode( 'fclatestnewsevents', 'display_latest_news_shortcode' );

//Display Button
function display_button_shortcode($argu = array()){
	global $frontend;	
	return $frontend->display_button($argu);
}
$shortcodeclass->add_shortcode( 'displaybutton', 'display_button_shortcode' );

//Join mailing list
function display_join_our_mailing_list_shortcode($argu = array()){
	global $frontend;	
	return $frontend->display_join_our_mailing_list($argu);
}
$shortcodeclass->add_shortcode( 'joinourmailinglist', 'display_join_our_mailing_list_shortcode' );

//MailChimp mailing list form
function display_mailchimp_form_shortcode($argu = array()){
	global $frontend;	
	return $frontend->display_mailchimp_form($argu);
}
$shortcodeclass->add_shortcode( 'mailchimpform', 'display_mailchimp_form_shortcode' );

//Sell your boat
function sell_your_boat_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->sell_your_boat_form($argu);
}
$shortcodeclass->add_shortcode( 'fcsellyourboat', 'sell_your_boat_form_shortcode' );

//We Buy Boats
function we_buy_boat_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->we_buy_boat_form($argu);
}
$shortcodeclass->add_shortcode( 'fcwebuyboat', 'we_buy_boat_form_shortcode' );

//contact form
function display_contact_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_contact_form($argu);
}
$shortcodeclass->add_shortcode( 'fccontactform', 'display_contact_form_shortcode' );

//boat finder form
function display_boat_finder_form_shortcode(){
	global $frontend;	
	return $frontend->display_boat_finder_form(1);
}
$shortcodeclass->add_shortcode( 'boatfinder', 'display_boat_finder_form_shortcode' );

//testimonial form
function share_testimonial_form_shortcode(){
	global $frontend;
	return $frontend->share_testimonial_form();
}
$shortcodeclass->add_shortcode( 'fcsharetestimonial', 'share_testimonial_form_shortcode' );

// Service Request Form
function display_service_request_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_service_request_form($argu);
}
$shortcodeclass->add_shortcode( 'fcservicerequestform', 'display_service_request_form_shortcode' );

// Parts Request Form
function display_parts_request_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_parts_request_form($argu);
}
$shortcodeclass->add_shortcode( 'fcpartsrequestform', 'display_parts_request_form_shortcode' );

// Finance Form
function display_finance_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_finance_form($argu);
}
$shortcodeclass->add_shortcode( 'fcfinanceform', 'display_finance_form_shortcode' );

// Buyer Services form
function display_buyer_services_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_buyer_services_form($argu);
}
$shortcodeclass->add_shortcode( 'fcbuyerservicesform', 'display_buyer_services_form_shortcode' );

// Seller Services form
function display_seller_services_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_seller_services_form($argu);
}
$shortcodeclass->add_shortcode( 'fcsellerservicesform', 'display_seller_services_form_shortcode' );

// We Can Sell Your Yacht form
function display_we_can_sell_your_yacht_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_we_can_sell_your_yacht_form($argu);
}
$shortcodeclass->add_shortcode( 'fcwecansellyouryachtform', 'display_we_can_sell_your_yacht_form_shortcode' );

//Trade-In Evaluation form
/*function display_tradein_evaluation_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_tradein_evaluation_form($argu);
}
$shortcodeclass->add_shortcode( 'fctradeinevaluation', 'display_tradein_evaluation_form_shortcode' );*/

//Trade-In Welcome form
/*function display_tradein_welcome_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_tradein_welcome_form($argu);
}
$shortcodeclass->add_shortcode( 'fctradeinwelcomeform', 'display_tradein_welcome_form_shortcode' );*/

//Talk To A Specialist form
/*function display_talk_to_specialist_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_talk_to_specialist_form($argu);
}
$shortcodeclass->add_shortcode( 'fctalktospecialistform', 'display_talk_to_specialist_form_shortcode' );*/

//Ask For Brochure form
/*function display_ask_for_brochure_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_ask_for_brochure_form($argu);
}
$shortcodeclass->add_shortcode( 'fcaskforbrochureform', 'display_ask_for_brochure_form_shortcode' );*/

//Boat Watcher form
function display_boat_watcher_form_shortcode($argu = array()){
	global $boatwatcherclass;
	return $boatwatcherclass->boat_watcher_form($argu);
}
$shortcodeclass->add_shortcode( 'fcboatwatcherform', 'display_boat_watcher_form_shortcode' );

//credit application form
function display_online_credit_application_form_shortcode(){
	global $creditappclass;	
	return $creditappclass->online_credit_application_form(1);
}
$shortcodeclass->add_shortcode( 'fconlinecreditapplication', 'display_online_credit_application_form_shortcode' );

//boat insurance form
function boat_insurance_form_shortcode(){
	global $frontend;
	return $frontend->boat_insurance_form();
}
$shortcodeclass->add_shortcode( 'fcboatinsurance', 'boat_insurance_form_shortcode' );

//boat transport form
function display_boat_transpost_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_boat_transpost_form($argu);
}
$shortcodeclass->add_shortcode( 'fcboattransport', 'display_boat_transpost_form_shortcode' );

//Boat Worth
function boat_worth_form_shortcode($argu = array()){
	global $frontend;
	return $frontend->boat_worth_form($argu);
}
$shortcodeclass->add_shortcode( 'fcboatworthform', 'boat_worth_form_shortcode' );

//make profile - image and description
function display_make_profile_shortcode($argu = array()){
	global $makeclass;
	return $makeclass->display_make_profile($argu);
}
$shortcodeclass->add_shortcode( 'fcmakeprofile', 'display_make_profile_shortcode' );

//Model group list based on make - YC
function display_make_group_profile_yc_shortcode($argu = array()){
	global $makeclass;
	return $makeclass->display_make_group_profile_yc($argu);
}
$shortcodeclass->add_shortcode( 'fcpredesignmakegroup', 'display_make_group_profile_yc_shortcode' );

//boat list based on make - YC
function display_make_boat_profile_yc_shortcode($argu = array()){
	global $makeclass;
	return $makeclass->display_make_boat_profile_yc($argu);
}
$shortcodeclass->add_shortcode( 'fcpredesignmake', 'display_make_boat_profile_yc_shortcode' );

//boat list based on make - local inventory
function display_make_boat_profile_local_shortcode($argu = array()){
	global $makeclass;
	return $makeclass->display_make_boat_profile_local_main($argu);
}
$shortcodeclass->add_shortcode( 'fclocalboat', 'display_make_boat_profile_local_shortcode' );

//Boat Type profile - image and description
function display_boat_type_profile_shortcode($argu = array()){
	global $boattypeclass;
	return $boattypeclass->display_boat_type_profile($argu);
}
$shortcodeclass->add_shortcode( 'fcboattypeprofile', 'display_boat_type_profile_shortcode' );

//boat list based on Boat Type - local inventory
function display_boat_type_boat_profile_local_shortcode($argu = array()){
	global $boattypeclass;
	return $boattypeclass->display_boat_type_boat_profile_local_main($argu);
}
$shortcodeclass->add_shortcode( 'fclocalboattype', 'display_boat_type_boat_profile_local_shortcode' );

//Local boat model list
function display_boat_model_list_shortcode($argu = array()){
	global $modelclass;
	return $modelclass->display_boat_model_list($argu);
}
$shortcodeclass->add_shortcode( 'fcboatmodellist', 'display_boat_model_list_shortcode' );

//Local boat model list by category
function display_boat_model_list_by_category_shortcode($argu = array()){
	global $modelclass;
	return $modelclass->display_boat_model_list_by_category($argu);
}
$shortcodeclass->add_shortcode( 'fcboatmodellistbycat', 'display_boat_model_list_by_category_shortcode' );

//Local boat model find
function display_find_right_model_main_shortcode($argu = array()){
	global $modelclass;
	return $modelclass->display_find_right_model_main($argu);
}
$shortcodeclass->add_shortcode( 'fcfindyourrightmodel', 'display_find_right_model_main_shortcode' );

//Most viewed/popular boats
function display_most_popular_boat_list_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->most_popular_boat_list($argu);
}
$shortcodeclass->add_shortcode( 'fcpopularboatlist', 'display_most_popular_boat_list_shortcode' );

//our team
function display_our_team_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_our_team($argu);
}
$shortcodeclass->add_shortcode( 'fcourteam', 'display_our_team_shortcode' );

//our team homelist
function display_our_team_homelist_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_our_team_homelist($argu);
}
$shortcodeclass->add_shortcode( 'homelistfcourteam', 'display_our_team_homelist_shortcode' );

//our team random - innder page
function display_our_team_random_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_our_team_random($argu);
}
$shortcodeclass->add_shortcode( 'fcourteamrandom', 'display_our_team_random_shortcode' );

//broker individual profile
function display_broker_profile_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_broker_profile($argu);
}
$shortcodeclass->add_shortcode( 'fcbrokerprofile', 'display_broker_profile_shortcode' );

//Boat List - normal
function display_boat_list_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_boat_list($argu);
}
$shortcodeclass->add_shortcode( 'fcboatlist', 'display_boat_list_shortcode' );

//search boat by category and brand - home page
function boat_search_by_cat_make_shortcode(){
	global $yachtchildclass;
	return $yachtchildclass->boat_search_by_cat_make();
}
$shortcodeclass->add_shortcode( 'fcboatsearchcatmake', 'boat_search_by_cat_make_shortcode' );

//search boat by Boat Type
function boat_search_by_boat_type_shortcode(){
	global $yachtchildclass;
	return $yachtchildclass->boat_search_by_boat_type();
}
$shortcodeclass->add_shortcode( 'fcboatsearchboattype', 'boat_search_by_boat_type_shortcode' );

//Homepage map and locations
function display_location_homepage_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_location_homepage($argu);
}
$shortcodeclass->add_shortcode( 'fclocationlist', 'display_location_homepage_shortcode' );

//loan calculator
function display_loan_amount_form_shortcode(){
	global $frontend;	
	return $frontend->display_loan_amount_form();
}
$shortcodeclass->add_shortcode( 'fcloancalculator', 'display_loan_amount_form_shortcode' );

//monthly payment calculator
function display_monthly_payment_form_shortcode(){
	global $frontend;	
	return $frontend->display_monthly_payment_form();
}
$shortcodeclass->add_shortcode( 'fcmonthlypaymentcalculator', 'display_monthly_payment_form_shortcode' );

//Advanced Search form - boat
function display_boat_advanced_search_form_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_boat_advanced_search_form($argu);
}
$shortcodeclass->add_shortcode( 'fcboatadvancedsearch', 'display_boat_advanced_search_form_shortcode' );

//boat searh inside content
function display_content_boat_advanced_search_form_small_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_content_boat_advanced_search_form_small($argu);
}
$shortcodeclass->add_shortcode( 'fcboatsearchform', 'display_content_boat_advanced_search_form_small_shortcode' );

//Few stat
function display_general_stat_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->display_sold_boat_stat($argu);
}
$shortcodeclass->add_shortcode( 'fcgeneralstat', 'display_general_stat_shortcode' );

//instagram_feed
/*function display_instagram_feed_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_instagram_feed($argu);
}
$shortcodeclass->add_shortcode( 'fcinstagramfeed', 'display_instagram_feed_shortcode' );*/

//instagram_feed home
function display_instagram_feed_home_shortcode($argu = array()){
	global $instagramclass;
	return $instagramclass->display_instagram_feed_home($argu);
}
$shortcodeclass->add_shortcode( 'fcinstagramfeedshort', 'display_instagram_feed_home_shortcode' );

/*left/right col*/

//Featured Boat - Sidebar
function sidebar_featured_boat_shortcode($argu = array()){
	global $yachtchildclass;
	return $yachtchildclass->yacht_featured_small(1);
}
$shortcodeclass->add_shortcode( 'siderbarfeaturedboat', 'sidebar_featured_boat_shortcode' );

//blog category list - left/right col
function blog_category_list_col_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->blog_category_list_col($argu);
}
$shortcodeclass->add_shortcode( 'fcblogcategorycol', 'blog_category_list_col_shortcode' );

//blog archive list - left/right col
function blog_archive_list_col_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->blog_archive_list_col($argu);
}
$shortcodeclass->add_shortcode( 'fcbloarchivecol', 'blog_archive_list_col_shortcode' );

//most read blog
function blog_most_read_shortcode($argu = array()){
	global $blogclass;
	return $blogclass->display_mostread_blog($argu);
}
$shortcodeclass->add_shortcode( 'fcblogmostread', 'blog_most_read_shortcode' );

//FAQ list
function faq_list_shortcode($argu = array()){
	global $frontend;
	$retval = json_decode($frontend->faq_list($argu));
	return $retval->doc;
}
$shortcodeclass->add_shortcode( 'fcfaqlist', 'faq_list_shortcode' );

//FAQ list
function useful_stats_shortcode($argu = array()){
	global $frontend;
	return $frontend->display_useful_stats($argu);
}
$shortcodeclass->add_shortcode( 'fcusefulstats', 'useful_stats_shortcode' );

//some initialized function
if(($_REQUEST['fcapi'] != "")){
	$cm->downloadfiles();
	$yachtclass->member_account_activation();
	$yachtclass->member_account_login();
	$yachtclass->user_reset_password();
	$yachtclass->submit_office_location_form();
	$yachtclass->submit_member_form();
	$yachtclass->submit_user_form();
	$yachtclass->submit_company_form();
	$yachtclass->submit_boat_form();
	$yachtclass->submit_boat_image_drag_drop();
	$yachtclass->submit_boat_video_drag_drop();
	$yachtclass->submit_boat_attachment_drag_drop();
	$yachtclass->submit_boat_image_form();
	$yachtclass->submit_boat_video_form();
	$yachtclass->add_boat_video_form();
	$yachtclass->submit_boat_attachment_form();
	$yachtclass->boat_pdf_create_main();
	$yachtclass->boat_clear_search();
	
	$yachtchildclass->dashboard_print_boats();
	$yachtchildclass->dashboard_site_stat_print();
	$yachtchildclass->update_boat_slug_feed();
	
	$yachtpopclass->submit_common_feedback_form();
	$yachtpopclass->submit_contact_broker_form();
	$yachtpopclass->submit_contact_model_form();
	//$yachtpopclass->submit_contact_resource_form();
	$yachtpopclass->submit_email_search_form();
	$yachtpopclass->submit_save_search_form();
	$yachtpopclass->submit_email_client_form();
	$yachtpopclass->submit_email_friend_form();
	$yachtpopclass->submit_send_email_my_broker_form();
	$yachtpopclass->submit_contact_local_model_form();
	
	$frontend->member_get_logout();
	$frontend->submit_contact_form();
	$frontend->submit_boat_finder_form();
	$frontend->submit_join_our_mailing_list_form();
	//$frontend->submit_tradein_evaluation_form();
	//$frontend->submit_create_your_listing_form();
	$frontend->submit_sell_your_boat_form();
	$frontend->submit_boat_worth_form();
	$frontend->submit_testimonial_form();
	//$frontend->submit_boat_insurance_form();
	//$frontend->submit_boat_transpost_form();
	//$frontend->submit_tradein_welcome_form();
	$frontend->submit_talk_to_specialist_form();
	$frontend->submit_ask_for_brochure_form();
	//$frontend->submit_service_request_form();
	//$frontend->submit_parts_request_form();
	//$frontend->submit_finance_form();
	//$frontend->submit_we_buy_boat_form();
	$frontend->submit_buyer_services_form();
	$frontend->submit_seller_services_form();
	//$frontend->submit_lead_checkout_form();
	$frontend->submit_increase_yacht_value_form();
	$frontend->submit_boat_show_registration_form();
	$frontend->submit_open_yacht_days_form();
	$frontend->submit_chartering_your_yacht_form();
	$frontend->submit_watch_price_form();
	$frontend->submit_we_can_sell_your_yacht_form();
	
	$creditappclass->submit_online_credit_application_form();

	$dataexportclass->create_json_data();
	$leadclass->export_leads();
	$boatwatcherclass->submit_boat_watcher_form();
	$boatwatcherclass->boat_watcher_unsubscribe();
}
?>