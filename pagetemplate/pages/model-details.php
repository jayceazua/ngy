<?php
$y = round($_REQUEST['y'], 0);
$mf = $_REQUEST['mf'];
$md = $_REQUEST['md'];

$retval = json_decode($ymclass->get_manufacturer_model_details($y, $mf, $md));
$foundm = $retval[0]->foundm;
$makeid = $retval[0]->makeid;
$modelid = $retval[0]->modelid;
$modelgroupid = $retval[0]->modelgroupid;
$model_template_id = $retval[0]->template_id;

//$pageid = $cm->get_common_field_name('tbl_page', 'id', $makeid, 'connected_manufacturer_id');
$pageid = $yachtchildclass->get_page_id_for_yc_model(array("makeid" => $makeid, "modelgroupid" => $modelgroupid));

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);

$main_heading = "n";
$top_mini_search = 0;
$googlemap = 0;
$addfav = 0;
$display_boat_disclaimer = 0;
$display_yachtworld_disclaimer = 0;

$broker_id = 1;
$company_id = 1;
$location_id = 1;

$breadcrumb = 1;
if ($pageid == 0){
	$link_name = '';
	$breadcrumb_extra[] = array(
				'a_title' => $mf,
				'a_link' => $cm->get_page_url($mf, 'manufacturerprofile')
	);
}else{
	$parentpagear = $cm->get_table_fields('tbl_page', 'name', $pageid);
	$parentpagear = (object)$parentpagear[0];
	$link_name = $parentpagear->name;
}

$breadcrumb_extra[] = array(
            'a_title' => $retval[0]->modelname,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);
$opengraphmeta = $cm->meta_open_graph($retval[0]->boat_title, $retval[0]->sharedescriptions, $retval[0]->shareimage, $retval[0]->fullurl);
include($bdr."includes/head.php");
?>

<?php
if ($model_template_id == 5){
?>
<div class="model_details_holder clearfixmain">
	<?php
	if ($foundm > 0){
		echo $retval[0]->doc;
		
		echo '
		<div class="model-button clearfixmain">
			<ul>
				<li><a href="'. $cm->get_page_url(0, "pop-trade-in-welcome") .'" class="button afterarrow buttonstyle2 commonpop" data-fancybox-type="iframe">Trade-in Welcome</a></li>
				<li><a href="'. $cm->get_page_url(0, "pop-talk-to-specialist") .'?make_id='. $makeid .'" class="button afterarrow commonpop" data-fancybox-type="iframe">Talk to a Specialist</a></li>
			</ul>
		</div>
		';
	}
	?>
</div>
<?php
}else{
?>

<div class="product-detail clearfixmain">
	<?php echo $retval[0]->headingtext; ?>
    <div class="clear"></div>
    <div class="left-cell">
    	<?php echo $retval[0]->doc; ?>
    </div>
    <div class="right-cell scrollcol" parentdiv="product-detail">
    	<section class="section broker-wrap">
            <?php echo $yachtclass->display_yacht_broker_info_general($modelid, $makeid); ?>
        </section>        
        <?php echo $yachtclass->yacht_featured_small(); ?>
    </div>
</div>

<?php
}
?>
<?php
include($bdr."includes/foot.php");
?>