<?php
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$id = round($_GET["id"], 0);
$returntext = $creditappclass->create_credit_application_content($id);
include("popuppage/header.php");
?>

<div class="main_con">
<?php echo $returntext; ?>
</div>

<?php
include("popuppage/footer.php");
?>