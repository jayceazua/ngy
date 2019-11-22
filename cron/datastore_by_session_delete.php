<?php
date_default_timezone_set('America/Chicago');
set_time_limit(0);
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
ini_set('memory_limit', '512M');
include("/stage/sites/n/ngyachting.com/htdocs__/includes/common.php");
//include("../includes/common.php");
$cm->remove_data_set();
?>
