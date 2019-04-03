<?php
$display_brd_array = "n";
$main_heading = "n";
$googlemap = 0;

//querystring
$slug = $_REQUEST['slug'];
$tagslug = $_REQUEST['tagslug'];
$byear = round($_REQUEST['byear'], 0);
$bmonth = round($_REQUEST['bmonth'], 0);

$catid = 0;
if ($slug != ""){
	$result = $blogclass->check_blog_category_with_return($slug, 1);
	$row = $result[0];
	
	foreach($row AS $key => $val){
		${$key} = $cm->filtertextdisplay($val);	
	}
	$catid = $id;
}

$tagid = 0;
if ($tagslug != ""){
	$result = $blogclass->check_blog_tag_with_return($tagslug, 1);
	$row = $result[0];
	
	foreach($row AS $key => $val){
		${$key} = $cm->filtertextdisplay($val);	
	}
	$tagid = $id;
}

if ($byear > 0 OR $bmonth > 0){
	$archivetitle = '';
	if ($byear > 0){
		$archivetitle .= $byear . ', ';
	}
	
	if ($bmonth > 0){
		$archivetitle .= date('F', strtotime("2012-$bmonth-01")) . ', ';
	}
	
	$name = 'Archive of ' . rtrim($archivetitle, ', ');
}
$pageid = $lastpageid;


$breadcrumb = 1;
$breadcrumb_extra[] = array(
            'a_title' => $name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);
$link_name = $atm1 = $name;

include($bdr."includes/head.php");
?>

<div class="leftcontentbox"> 
    <h2><?php echo $link_name; ?></h2>     
    <?php echo $blogclass->blog_list_main(array('categoryid' => $catid, 'tagid' => $tagid, 'byear' => $byear, 'bmonth' => $bmonth)); ?>
</div>

<div class="rightcontentbox scrollcol" parentdiv="main">
<?php
echo $frontend->display_box_content(4);
?>
</div>
<?php
include($bdr."includes/foot.php");
?>