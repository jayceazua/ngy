<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);
include("head.php");
?>
<div class="mostviewed bottomspace">
<a href="javascript:void(0);" class="button printbutton">Print</a>
</div>

<?php echo $yachtchildclass->get_print_inventory_broker(); ?>
<?php
include("foot.php");
?>