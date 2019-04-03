<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";
$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$googlemap = 0;

$slug = $_REQUEST['slug'];
$result = $yachtclass->check_announcement_with_return($slug, 1);
$row = $result[0];

foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}
$reg_date_d = $cm->display_date($reg_date, 'y', 9);
$atm1 = $link_name = $name;

for ($chk = 0; $chk < $category_id_holder_cnt; $chk++){
  $brdcmp_array[$arry_cnt]["a_title"] = $category_id_holder[$chk]["name"];	
  $brdcmp_array[$arry_cnt]["a_link"] = $category_id_holder[$chk]["linkurl"];		  
  $arry_cnt++;		  
}

$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
$brdcmp_array[$arry_cnt]["a_link"] = "";
$arry_cnt++;

include($bdr."includes/head.php");
?>

<div class="divrow2">
    <p><strong><?php echo $reg_date_d; ?></strong><br /><?php echo $pdes; ?></p>
    <div class="clear"></div>
</div>
<?php
include($bdr."includes/foot.php");
?>