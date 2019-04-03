<?php
$pageid = 0;
$yachtclass->remove_yach_search_var();
$main_heading = "y";
$link_name = $atm1 = 'Advanced Search';

$breadcrumb = 1;
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);
$post_url = $cm->get_page_url(2, "page");
$type_id = 0;
$displaysubcat = ' com_none';
include($bdr."includes/head.php");
echo $yachtchildclass->display_boat_advanced_search_form();
include($bdr."includes/foot.php");
?>