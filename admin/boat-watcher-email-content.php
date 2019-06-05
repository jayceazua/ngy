<?php
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$id = $_GET["id"];
$returntext = $boatwatcherclass->display_boat_watcher_email_content($id);
include("popuppage/header.php");
?>

<div class="main_con">
<?php echo $returntext; ?>
</div>

<?php
include("popuppage/footer.php");
?>