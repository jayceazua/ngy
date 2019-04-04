<?php
$startend = 0;
$slug = $_REQUEST['slug'];
$model_content_ar = json_decode($modelclass->boat_model_details(array("checkval" => $slug, "checkopt" => 1)));
$modelcontent = $model_content_ar->modelcontent;
$model_image = $model_content_ar->model_image;
$model_name = $model_content_ar->model_name;
$detailsurl = $model_content_ar->detailsurl;
$make_id = $model_content_ar->make_id;
$model_id = $model_content_ar->model_id;
$m1 = $model_content_ar->m1;
$m2 = $model_content_ar->m2;
$m3 = $model_content_ar->m3;

$atm1 = $m1;
$atm2 = $m2;
$atm3 = $m3;

//parent page
$pageid = $cm->get_page_id_by_shortcode("[fcboatmodellist makeid=". $make_id ."]");

$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
$parentpagear = (object)$parentpagear[0];
$link_name = $parentpagear->name;

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);
//end

$imagelink = '';
if ($model_image != ""){ 
	$imagefolder = 'models/'. $model_id . '/modelimage/bigger/';	
	$imagelink = $cm->site_url . '/' . $imagefolder . $model_image;
}

$fullurl = $cm->site_url . $detailsurl;


$opengraphmeta = $cm->meta_open_graph($name, $atm2, $imagelink, $fullurl);
include($bdr."includes/head.php");
echo $modelcontent;
include($bdr."includes/foot.php");
?>