<?php  
// Standard inclusions   
include("../common.php");
$frontend->go_to_login(1);

//Set data
$datastring = $_REQUEST["d"];
$labelstring = $_REQUEST["la"];
$cfgstring = $_REQUEST["c"];
$chartstyle = $_REQUEST["chartstyle"];
$chartclass->process_chart($datastring, $labelstring, $cfgstring, $chartstyle);
?>
 