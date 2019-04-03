<?php
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$googlemap = 2;
$cnm = $_REQUEST["cnm"];
$result = $yachtclass->check_company_exist($cnm, 1, 0, 0);

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

$addressfull = '';
$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = htmlspecialchars($val);
}

$link_name = $atm1 = "Listings Of " . $cname;
$_SESSION["s_currenturl"] = '';
$sql = $yachtclass->create_yacht_sql();
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

<div id="listingholdermain" class="profile-main clearfixmain" to_check_val="'. $to_check_val .'">
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