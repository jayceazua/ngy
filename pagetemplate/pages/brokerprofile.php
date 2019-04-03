<?php
$main_heading = "n";
$googlemap = 2;
$unm = $_REQUEST["unm"];
$insidedashboard = round($_REQUEST["insidedashboard"], 0);
$result = $yachtclass->check_user_exist($unm, 1);

//$profile_url = $cm->get_page_url($_SESSION["usernid"], 'driver');
$addressfull = '';
$row = $result[0];

$broker_profile_ar = json_decode($yachtchildclass->display_broker_profile_content(array("row" => $row)));
$brokername = $broker_profile_ar->brokername;

$breadcrumb = 1;
$html_start = '';
$html_end = '';
$inside_dashboard = 0;
if ($loggedin_member_id > 0 AND $insidedashboard == 1){
	$inside_dashboard = 1;
	$breadcrumb = 0;
}

if ($inside_dashboard == 1){
	if ($id == $loggedin_member_id){
		$ar_m1 = 2;
		$ar_m2 = 4;
	}else{
		$ar_m1 = 2;
		$ar_m2 = 1;
	}
	$isdashboard = 1;
	$breadcrumb = 0;
	$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => $ar_m1, "m2" => $ar_m2)));
	$html_start = $htmlstartend->htmlstart;
	$html_end = $htmlstartend->htmlend;
}else{

	if (isset($_SESSION["s_locationpage"]) AND $_SESSION["s_locationpage"] == 1){
		//location breadcrumb
		$pageid = 20;
		$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
		$parentpagear = (object)$parentpagear[0];

		$breadcrumb_extra[] = array(
					'a_title' => $parentpagear->name,
					'a_link' => $cm->get_page_url($pageid, 'page')
		);

		$locationofficear = $cm->get_table_fields('tbl_location_office', 'name, slug', $location_id);
		$locationname = $locationofficear[0]["name"];
		$locationslug = $locationofficear[0]["slug"];
		$breadcrumb_extra[] = array(
					'a_title' => $locationname,
					'a_link' => $cm->get_page_url($locationslug, 'locationprofile')
		);
		$breadcrumb_extra[] = array(
					'a_title' => $brokername,
					'a_link' => ''
		);
	}else{
		//normal breadcrumb	
		if (isset($_SESSION["s_brokerprofilepath"]) AND $_SESSION["s_brokerprofilepath"] > 0){
			$pageid = $_SESSION["s_brokerprofilepath"];
		}else{
			/*if ($support_crew == 1){
				$pageid = 9;
			}else{
				$pageid = 22;
			}*/
			$pageid = 15;
		}

		$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
		$parentpagear = (object)$parentpagear[0];

		$breadcrumb_extra[] = array(
					'a_title' => $parentpagear->name,
					'a_link' => $cm->get_page_url($pageid, 'page')
		);

		$breadcrumb_extra[] = array(
					'a_title' => $brokername,
					'a_link' => ''
		);	
	}

	$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
	$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);


	$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);
	$atm1 = "Profile Of " . $brokername;
}


$pageid = 0;
include($bdr."includes/head.php");
echo $html_start;
echo $broker_profile_ar->doc;
echo $html_end;
include($bdr."includes/foot.php");
?>