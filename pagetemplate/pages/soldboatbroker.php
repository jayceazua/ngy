<?php
$main_heading = "n";
$googlemap = 2;
$unm = $_REQUEST["unm"];
$result = $yachtclass->check_user_exist($unm, 1);

//$profile_url = $cm->get_page_url($_SESSION["usernid"], 'driver');
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
				'a_title' => "Sold Listings",
				'a_link' => ''
	);	
}

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);


$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);
$atm1 = "Profile Of " . $brokername;

if ($display_title == 1){
	$brokername .= ' - ' . $title;
}

if ($type_id == 1){
	$sectiontitle = "Sold Boat";
}else{
	$sectiontitle = "Sold Boats by " . $fname . " " .  $lname;
}
$atm1 = $sectiontitle;

include($bdr."includes/head.php");
$_SESSION["s_currenturl"] = '';
$sql = $yachtclass->create_yacht_sql();
$foundm = $yachtclass->total_yach_found($sql);

$param_page = array(
	"to_get_val" => $sql,
	"section_for" => 2
);
$to_check_val = $cm->insert_data_set($param_page);
$compareboat = 0;
if ($foundm > 0){	
?>
<div class="clear"></div>

<div class="profile-main clearfixmain">
    <div class="header-bottom-bg clearfixmain">
        <div class="header-bottom-inner clearfixmain">
            <div class="sch">
                <span><?php echo $sectiontitle; ?></span>
            </div>
            <div class="vp">
                <?php 
					$option_retval = json_decode($yachtclass->display_view_option());
					$dval = $option_retval->dval;	
					echo $option_retval->doc;					
				?>
            </div>
            <div class="res"><div class="spinnersmall"><span class="reccounterupdate"><?php echo $foundm; ?></span> result(s)</div></div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div id="listingholdermain" class="profile-main clearfixmain" to_check_val="<?php echo $to_check_val; ?>">
    <?php
	$param = array(
		"compareboat" => $compareboat,
		"displayoption" => $dval,
		"to_check_val" => $to_check_val
	);
    $retval = json_decode($yachtclass->display_yacht_listing(1, $param));
    echo $retval[0]->doc;
    ?>
</div>

<?php 
} 
?>
<?php
include($bdr."includes/foot.php");
?>