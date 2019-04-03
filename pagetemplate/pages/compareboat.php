<?php
$pageid = 0;
$top_mini_search = 0;
$display_brd_array = "y";
$main_heading = "n";
$googlemap = 0;
$chosenboat = $_REQUEST["chosenboat"];
$atm1 = "Compare Boats";
include($bdr."includes/head.php");
?>
<h1><?php echo $atm1; ?></h1>
<div id="listingholdermain" class="profile-main">
<?php echo $yachtchildclass->display_boat_compare($chosenboat); ?>
</div>

<?php
include($bdr."includes/foot.php");
?>