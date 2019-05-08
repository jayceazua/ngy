<?php
$newpagename = basename($_SERVER["PHP_SELF"]);
$bdir = $cm->folder_for_seo;
$collectno = 1;
$query_string = $_SERVER['QUERY_STRING'];
$currenpageturl = $_SERVER["REQUEST_URI"];

$pattern = '~\b(js|popuppage|css|images|yachtimage|cmsfile|contentimages|sorry|thanks|login|register)\b~i';
$inv_page = preg_match($pattern, $newpagename);
$inv_page2 = preg_match($pattern, $currenpageturl);
if ($inv_page == 0 AND $inv_page2 == 0){ $_SESSION["file_name"] = $currenpageturl; }

if ($query_string != ""){ $bookmark_url = $site_name."/".$newpagename."?".$query_string; }else{ $bookmark_url = $site_name."/".$newpagename; }
if ($call_function == "a"){ $frontend->go_to_login(); }
if ($call_function == "b"){ $frontend->go_to_account(); }

//default meta
$brow = $frontend->default_meta_info();
$tm1 = $brow["m1"];
$tm2 = $brow["m2"];
$tm3 = $brow["m3"];

//page info
$slider_make_id = 0;
$display_make_name = 0;
if ($pageid > 0){
    $brow = $frontend->default_page_info($pageid);
    $page_parent_id = $brow["parent_id"];
	$column_id = $brow["column_id"];
    $link_name = $brow["name"];
    $f_pdata = $brow["file_data"];
	$templatefile = $brow["templatefile"];
	$connected_manufacturer_id = $slider_make_id = $brow["connected_manufacturer_id"];
	$custom_inventory_view = $brow["custom_inventory_view"];
	$display_page_heading = $brow["display_page_heading"];
    $atm1 = $brow["m1"];
    $atm2 = $brow["m2"];
    $atm3 = $brow["m3"];
	
	$f_pdata = $cm->passed_content_for_readmore_block($f_pdata);
	$f_pdata = $cm->passed_content_for_shortcode($f_pdata);
	
	if ($connected_manufacturer_id > 0){
		$final_meta = $makeclass->collect_meta_info_make($atm1, $atm2, $atm3, $connected_manufacturer_id);
		$atm1 = $final_meta->m1;
		$atm2 = $final_meta->m2;
		$atm3 = $final_meta->m3;
	}else{
		if ($custom_inventory_view != ""){
			$custom_inventory_view_ar = json_decode($custom_inventory_view);
			$slider_make_id = $custom_inventory_view_ar->custom_make_id;
			$dyanamicheading = $custom_inventory_view_ar->dyanamicheading;
			$display_make_name = 1;
			
			if ($dyanamicheading == 1){
				$custom_make_id = $custom_inventory_view_ar->custom_make_id;
				$custom_condition_id = $custom_inventory_view_ar->custom_condition_id;
				$custom_stateid = $custom_inventory_view_ar->custom_stateid;
				
				$param_heading = array(
					"custom_make_id" => $custom_make_id,
					"custom_condition_id" => $custom_condition_id,
					"custom_stateid" => $custom_stateid,
				);
				
				$link_name = $yachtchildclass->display_custom_page_heading($param_heading);
			}
		}
	}
	
	$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
	$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);
}

//some common info
$companyname = $cm->get_systemvar('COMNM');
$phone_copy = $cm->get_systemvar('PCLNW');
$phone_copy2 = $cm->get_systemvar('STPH2');
$phone_copy3 = $cm->get_systemvar('STPH3');
$fax_copy = $cm->get_systemvar('COFAX');
$comoany_address = $cm->get_systemvar('STADD');
$comoany_address_footer =  preg_replace('/, /', ' &#8226;<br />', $comoany_address, 1);
$dctxt_boat = $cm->get_systemvar('DCTXT');
$dctxt_resource = $cm->get_systemvar('RDTXT');
$googlesiteverification = $cm->get_systemvar('GASVC');

$fburl = $cm->get_systemvar('SCFBU');
$twurl = $cm->get_systemvar('SCTWU');
$gpurl = $cm->get_systemvar('SCGPU');
$lnurl = $cm->get_systemvar('SCLNU');
$yturl = $cm->get_systemvar('SCYTU');

$category_id_holder_cnt = 0;
$category_id_holder = array();
$retbackpg = $cm->get_page_url($pageid, "page");
$_SESSION["s_backpage"] = $retbackpg;
if (!(isset($lastpagecheck))){
	$_SESSION["s_backpageid"] = $pageid;
}

$loggedin_member_id = $yachtclass->loggedin_member_id();
$post_url = $cm->get_page_url(2, "page");

$loggedinclass = '';
if ($loggedin_member_id > 0){
	$loggedinclass = ' loggedinclass';	
}
?>