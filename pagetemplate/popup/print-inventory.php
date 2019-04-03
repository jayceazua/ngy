<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);
$yachtclass->loggedin_broker_icon_permission(1);

$printoption = round($_REQUEST['printoption'], 0);
$include_broker = round($_REQUEST['include_broker'], 0);
$sortop = round($_REQUEST['sortop'], 0);
$orderbyop = round($_REQUEST['orderbyop'], 0);
include("head.php");
?>
<div class="mostviewed bottomspace">
<a href="javascript:void(0);" class="button printbutton">Print</a>
</div>

<?php echo $yachtchildclass->get_print_inventory($printoption, $include_broker, $sortop, $orderbyop); ?>
<?php
include("foot.php");
?>