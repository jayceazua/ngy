<?php
$main_heading = "n";
$googlemap = 2;
$brokerslug = $_REQUEST["brokerslug"];
$result = $yachtclass->check_user_exist($brokerslug, 1);
$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
	if ($key != "about_me"){
		${$key} = htmlspecialchars($val);
	}else{
		${$key} = $cm->filtertextdisplay($val);
	}	
}

$brokername = $fname .' '. $lname;
$gaeventtracking = $yachtclass->google_event_tracking_code('broker', $brokername);

$breadcrumb = 1;
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
	if (isset($_SESSION["s_brokerprofilepath"])){
		$pageid = $_SESSION["s_brokerprofilepath"];
	}else{
		/*if ($support_crew == 1){
			$pageid = 9;
		}else{
			$pageid = 22;
		}*/
		$pageid = 15;
	}
	
	$profile_url = $cm->get_page_url($id, 'user');

	$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
	$parentpagear = (object)$parentpagear[0];
	
	$breadcrumb_extra[] = array(
				'a_title' => $parentpagear->name,
				'a_link' => $cm->get_page_url($pageid, 'page')
	);
	
	$breadcrumb_extra[] = array(
				'a_title' => $brokername,
				'a_link' => $profile_url
	);
	
	$breadcrumb_extra[] = array(
				'a_title' => "Listings",
				'a_link' => ''
	);	
}

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);


$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);
$atm1 = "Listings Of " . $brokername;

include($bdr."includes/head.php");
$_SESSION["s_currenturl"] = '';

$sql = $yachtclass->create_yacht_sql();
$foundm = $yachtclass->total_yach_found($sql);

$param_page = array(
	"to_get_val" => $sql,
	"section_for" => 2
);
$to_check_val = $cm->insert_data_set($param_page);
$boat_top_text = $yachtchildclass->display_boat_top_text(array("foundm" => $foundm));

$dval = $yachtclass->get_selected_display_option($foundm);		
$sort_retval = json_decode($yachtclass->get_selected_sort_option($listsort));						
$sortop = $sort_retval->sortop;
$orderbyop = $sort_retval->orderbyop;
$compareboat = 0;

$param = array(
	"searchtemplate" => 1,
	"searchoption" => 1,
	"rawtemplate" => 1,
	"apinoselection" => 1,
	"gen_sql" => $sql
);
$leftsearchcol = '<div class="left-cell boatsearchcol scrollcol"  parentdiv="boatlisting-detail">'. $yachtchildclass->yacht_search_column($param) .'</div>';

$param = array(
	"compareboat" => $compareboat,
	"displayoption" => $dval,
	"ajaxpagination" => 0,
	"dstat" => 0,
	"sortop" => $sortop,
	"orderbyop" => $orderbyop,
	"to_check_val" => $to_check_val
);
$retval = json_decode($yachtclass->display_yacht_listing(1, $param));

echo '<div class="boatlistingmain clearfixmain">
	' .$boat_top_text . '
	<div class="boatlisting-detail clearfixmain">
		'. $leftsearchcol .'
		<div id="listingholdermain" class="right-cell" to_check_val="'. $to_check_val .'">
			'. $retval[0]->doc .'
		</div>
	</div>
</div>	
';
?>
<?php
include($bdr."includes/foot.php");
?>