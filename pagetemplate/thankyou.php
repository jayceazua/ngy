<?php
/**
Template Name: Thank You
*/

$main_heading = "n";
$breadcrumb = 0;
//$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name);
include($bdr."includes/head.php");
//echo '<h1 class="borderstyle1">Thank You</h1>';

if (isset($_SESSION["s_pgid"]) AND $_SESSION["s_pgid"] == $pageid){

	if ($f_pdata != ""){ 
		echo $f_pdata; 
	}

}

include($bdr."includes/foot.php")
?>