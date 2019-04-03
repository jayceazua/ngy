<?php
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
	$blog_image_text = '
	<div class="clearfix spacerbottom"><img class="full" src="'. $cm->folder_for_seo . $imagefolder . $blog_image .'" alt=""></div>
	';
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
	$reg_date_d = $cm->display_date($reg_date, 'y', 9);
	$reg_date_display = '<span class="postdate">'. $reg_date_d .'</span>';
}

$f_pdata = '
<div class="editordivrow noborder clearfixmain">
	'. $blog_image_text .'
    
	<div class="blogmeta">
	'. $reg_date_display .'
	<span class="postcategory"><a href="'. $catlinkurl .'">'. $categoryname .'</a></span>
	'. $tagname .'
	</div>
	
    <div class="blog">'. $description .'</div>
	<a href="javascript:void(0);" class="button backbtn">Back</a>
	'. $blogclass->blog_share_button($name, $small_description, $fullurl) .'
</div>
';

$blogclass->update_blog_view($id);
$opengraphmeta = $cm->meta_open_graph($name, $small_description, $imagelink, $fullurl);
include($bdr."includes/head.php");
?>

<div class="fullcol">
	<?php echo $f_pdata; ?>
</div>
<?php
include($bdr."includes/foot.php");
?>