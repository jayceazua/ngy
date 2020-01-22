<?php
$startend = 0;
$slug = $_REQUEST['slug'];
$result = $blogclass->check_blog_with_return($slug, 1);
$row = $result[0];

foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);	
}

$atm1 = $m1;
$atm2 = $m2;
$atm3 = $m3;

//parent page
$lastpagecheck = 1;
if ($lastpageid == 1){
	$lastpageid = $cm->get_page_id_by_shortcode("[fcbloglist categoryid=". $category_id ."]");
}
$pageid = $lastpageid;
$_SESSION["s_backpageid"] = $pageid;
$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
$parentpagear = (object)$parentpagear[0];
$link_name = $parentpagear->name;

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);

$display_brd_array = "n";
$main_heading = "y";
$googlemap = 0;

$breadcrumb = 1;
$breadcrumb_extra[] = array(
            'a_title' => $name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);
$link_name = $name;

$categoryarray = $blogclass->blog_category_fields($category_id);
$categoryname = $categoryarray->name;
$catlinkurl = $categoryarray->catlinkurl;

$blog_image_text = '';
$imagelink = '';
if ($blog_image != ""){ 
	$imagefolder = 'blogimage/';
	if ($image_display_post == 1){	
		$blog_image_text = '<div class="blog-image-holder wow fadeInUp" data-wow-duration="1.2s"><span class="catname">'. $categoryname .'</span><img src="'. $cm->folder_for_seo . $imagefolder . $blog_image .'" alt="'. $$name .'"></div>';
	}
	$imagelink = $cm->site_url . '/' . $imagefolder . $blog_image;
}

$fullurl = $cm->site_url . $cm->get_page_url($slug, "blog");

$tagname = $cm->display_multiplevl($id, 'tbl_blog_tag_assign', 'tag_id', 'blog_id', 'tbl_blog_tag', 1);
if ($tagname != ""){
	$tagname = '<span class="posttag">'. $tagname .'</span>';
}

$reg_date_display = '';
if ($display_date == 1){
	$reg_date_d = $cm->display_date($reg_date, 'y', 17);
	$reg_date_display = '<span class="ng-date">'. $reg_date_d .'</span>';
}

$blogclass->update_blog_view($id);
$opengraphmeta = $cm->meta_open_graph($name, $small_description, $imagelink, $fullurl);
include($bdr."includes/head.php");
?>

<div class="blog-container">
	<h1 class="t-center"><?php echo $name; ?></h1>
    <?php echo $blog_image_text; ?>
    <h6>
    <?php echo $reg_date_display; ?>
    <?php echo $blogclass->blog_share_button($name, $small_description, $fullurl, 3); ?>
    </h6>
    <hr />
    <?php echo $description; ?>
    <hr />
    <a class="bckn backbtn" href="javascript:void(0);">Back to news</a>
    <?php
	$paramar = array("company_id" => $company_id, "location_id" => $location_id, "broker_id" => $poster_id);
	echo $yachtclass->display_yacht_broker_info_blog($paramar);
	?>
</div>

<?php
	$latestar = array("categoryid" => 1, "template" => 5);
	echo $blogclass->display_latest_news($latestar);
?>
<?php
include($bdr."includes/foot.php");
?>