<div class="clear"></div>
</div>
<?php
$google_analytics = $cm->get_systemvar('GLANY');
echo $google_analytics;

$google_remarketing_tag = $cm->get_systemvar('GORTC');
echo $google_remarketing_tag;

$facebook_pixel_code = $cm->get_systemvar('FBPXC');
echo $facebook_pixel_code;

$call_tracking_code = $cm->get_systemvar('CTSCD');
echo $call_tracking_code;
?>
</body>
</html>
<?php
$db->close();
$db->pdo_close(); 
?>