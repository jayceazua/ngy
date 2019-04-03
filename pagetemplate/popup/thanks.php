<?php
$r = round($_REQUEST["r"], 0);
include("head.php");
?>
<script>
	$(document).ready(function() {
		setTimeout( function() {
			parent.$.colorbox.close(true);
		},3000);
		
		//if (r == 1){
			window.parent.location.reload();
		//}
	});
</script>
<div class="fullcol">
    <h2>Thank You</h2>
    <?php
    echo $_SESSION["thnk"];
    ?>
</div> 
<?php
include("foot.php");
?>