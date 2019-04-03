<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 2;
$isdashboard = 1;

$result = $yachtclass->check_user_exist($loggedin_member_id, 2);

$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}

$atm1 = $link_name = "My Favorite Listings";

$breadcrumb = 0;
/*$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);*/

$_SESSION["s_currenturl"] = '';
$sql = $yachtclass->create_yacht_sql(1);
$foundm = $yachtclass->total_yach_found($sql);

$param_page = array(
	"to_get_val" => $sql,
	"section_for" => 2
);
$to_check_val = $cm->insert_data_set($param_page);
$compareboat = 0;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 3, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
echo $html_start;
?>

<div class="profile-main clearfixmain">
    <div class="header-bottom-bg clearfixmain">
        <div class="header-bottom-inner clearfixmain">
            <div class="sch">
                <span>My Favorite Listings</span>
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
    <div class="clear"></div>
</div>
<?php echo $html_end; ?>
<?php
include($bdr."includes/foot.php");
?>