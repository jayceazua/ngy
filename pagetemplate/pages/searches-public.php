<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;

$unm = $_REQUEST["unm"];
$yachtclass->can_view_saved_search($unm);
$result = $yachtclass->check_user_exist($unm, 1);

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}
$_SESSION["s_currenturl"] = '';
$atm1 = $link_name = "Saved Search By ". $fname . " ". $lname;

$sql = "select * from tbl_yacht_save_search where user_id = '". $id ."'";
$sqlm = str_replace("select *","select count(*) as ttl",$sql);
$foundm = $db->total_record_count($sqlm);
$sql .= " order by reg_date desc";
$result = $db->fetch_all_array($sql);
include($bdr."includes/head.php");
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

<?php
include($bdr."includes/foot.php");
?>