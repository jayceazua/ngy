<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
include("/stage/sites/n/ngyachting.com/htdocs__/includes/common.php");
$boatwatcherclass->run_boat_watcher();
?>
