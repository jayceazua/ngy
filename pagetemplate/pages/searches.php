<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$isdashboard = 1;

$result = $yachtclass->check_user_exist($loggedin_member_id, 2);
$atm1 = $link_name = "My Saved Search";

$breadcrumb = 0;
/*$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);
*/
$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}

$_SESSION["s_currenturl"] = '';

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 4, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

$sql = "select * from tbl_yacht_save_search where user_id = '". $loggedin_member_id ."'";
$sqlm = str_replace("select *","select count(*) as ttl",$sql);
$foundm = $db->total_record_count($sqlm);
$sql .= " order by reg_date desc";
$result = $db->fetch_all_array($sql);
include($bdr."includes/head.php");
echo $html_start;
?>

<div class="profile-main">
    <div class="header-bottom-bg">
        <div class="header-bottom-inner">
            <div class="sch">
                <span><?php echo $link_name; ?></span>
            </div>
            <div class="vp">
                <span id="svtotal"><?php echo $foundm; ?></span> result(s)
            </div>
            <div class="res">&nbsp;</div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div id="listingholdermain" class="profile-main">
    <?php
    echo $yachtclass->display_user_save_search($result);
    ?>
    <div class="clear"></div>
</div>
<?php echo $html_end; ?>
<?php
include($bdr."includes/foot.php");
?>