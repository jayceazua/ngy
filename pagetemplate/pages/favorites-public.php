<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 2;

$unm = $_REQUEST["unm"];
$yachtclass->can_view_fav_list($unm);
$result = $yachtclass->check_user_exist($unm, 1);

$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}
$_SESSION["s_currenturl"] = '';
$atm1 = $link_name = "Favorite Listings Of ". $fname . " ". $lname;

$sql = $yachtclass->create_yacht_sql(5);
$foundm = $yachtclass->total_yach_found($sql);
$param_page = array(
	"to_get_val" => $sql,
	"section_for" => 2
);
$to_check_val = $cm->insert_data_set($param_page);

$compareboat = 0;
include($bdr."includes/head.php");
?>
<div class="profile-main clearfixmain">
    <div class="header-bottom-bg clearfixmain">
        <div class="header-bottom-inner clearfixmain">
            <div class="sch">
                <span><?php echo $link_name; ?></span>
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
include($bdr."includes/foot.php");
?>