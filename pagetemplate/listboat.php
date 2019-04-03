<?php
/**
Template Name: Boat Listing
*/

$top_mini_search = 2;
$main_heading = "n";
$googlemap = 2;
$breadcrumb = 1;
$_SESSION["s_normal_pagination"] = 1;
$breadcrumb_extra = array();

$rawtemplate = round($_REQUEST["rawtemplate"], 0);
$freshstart = round($_REQUEST["freshstart"], 0);
if ($rawtemplate == 1){
	// Not from shortcode
	$_SESSION["shortcode_used"] = 0;
	$apinoselection = 0;
	$pageid = 2;
	
	//query string
	$mfslug = $_REQUEST["mfslug"];
	$categorynm = $_REQUEST["categorynm"];
	$typename = $_REQUEST["typename"];
	$conditionname = $_REQUEST["conditionname"];
	$frm = round($_REQUEST["frm"], 0);
	$owned = round($_REQUEST["owned"], 0);
	//end

	if ($freshstart == 1){
		$yachtclass->remove_yach_search_var(); 
	}
	
	if ($frm == 1){
		$_SESSION["s_normal_pagination"] = 2;
		$makeid = $cm->get_common_field_name('tbl_manufacturer', 'id', $mfslug, 'slug');
		$pageid = $cm->get_common_field_name('tbl_page', 'id', $makeid, 'connected_manufacturer_id');
	}else{
		if ($owned > 0){
			$apinoselection = 1;
			$formpostar = json_decode($yachtchildclass->get_advanced_search_post_url());
			if ($owned == 1){
				$pageid = $formpostar->our_page_id;
			}else{
				$pageid = $formpostar->co_broker_page_id;
			}
		}
	}
	
	$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
	$parentpagear = (object)$parentpagear[0];
	$link_name = $parentpagear->name;
	
	$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
	$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);
	
	if ($categorynm != "" OR $typename != "" OR $conditionname != ""){
		if ($categorynm != ""){
			$breadcrumb_extra[] = array(
					'a_title' => $categorynm,
					'a_link' => ''
			);		
		}
		
		if ($typename != ""){
			$breadcrumb_extra[] = array(
					'a_title' => $cm->get_common_field_name('tbl_type', 'name', $typename, 'slug'),
					'a_link' => ''
			);		
		}
		
		if ($conditionname != ""){
			$breadcrumb_extra[] = array(
					'a_title' => $conditionname . " Boats",
					'a_link' => ''
			);
		}
	}
	
	$boat_check_sql = json_decode($yachtchildclass->get_boat_check_sql());
	$sql = $boat_check_sql->sql;
	$to_check_val = $boat_check_sql->to_check_val;
}

$_SESSION["conditional_page_id"] = $pageid;

$param_page = array(
	"to_get_val" => $pageid,
	"section_for" => 1
);
$cm->insert_data_set($param_page);

$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);

include($bdr."includes/head.php");
$compareboat = 0;
if ($compareboat == 1){
	echo '
	<div class="left-cell-half"><h1>'. $frontend->head_title($link_name) .'</h1></div>
	<div class="right-cell-half compare-boat-main t-right clearfixmain">
		<div maxboat="'. $cm->maxboatcompare .'" class="compare-boat-holder">
		<button pg="'. $cm->get_page_url(0, 'compareboat') .'" class="button compareboatbutton">Compare Boat</button>
		<input type="hidden" value="," id="chosenboat" name="chosenboat" />
		</div>    
	</div>
	<div class="clearfix"></div>
	';
}else{
	echo '<h1 class="borderstyle1">'. $frontend->head_title($link_name) .'</h1>';
}

if ($rawtemplate == 1){
	$foundm = $yachtclass->total_yach_found($sql);	
	$boat_top_text = $yachtchildclass->display_boat_top_text(array("foundm" => $foundm));
	
	$dval = $yachtclass->get_selected_display_option($foundm);		
	$sort_retval = json_decode($yachtclass->get_selected_sort_option($listsort));						
	$sortop = $sort_retval->sortop;
	$orderbyop = $sort_retval->orderbyop;	
	
	$param = array(
		"searchtemplate" => 1,
		"searchoption" => 1,
		"rawtemplate" => 1,
		"apinoselection" => $apinoselection,
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

}else{
	echo $f_pdata;;
}
?>
<?php
include($bdr."includes/foot.php");
?>