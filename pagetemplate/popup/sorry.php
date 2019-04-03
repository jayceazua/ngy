<?php
include("head.php");
?>
<script>
	$(document).ready(function() {
		setTimeout( function() {parent.$.colorbox.close(true); },3000);
	});
</script>
<div class="fullcol">
    <h2>Sorry</h2>
    <?php
    echo $_SESSION["ob"];
    ?>
</div> 
<?php
include("foot.php");
?>