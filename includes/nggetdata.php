<?php
include("common.php");
$data = json_decode(file_get_contents("php://input"));
$az = $data->az;
$az = round($az, 0);

if ($az == 1){
	$category_id = $data->category_id;
	$guest = $data->guest;
	$cruisingarea_id = $data->cruisingarea_id;
	$lnmin = $data->lnmin;
	$lnmax = $data->lnmax;
	$cabin = $data->cabin;
	$crew = $data->crew;
	
	$param = array(
		"category_id" => $category_id,
		"guest" => $guest,
		"cruisingarea_id" => $cruisingarea_id,
		"lnmin" => $lnmin,
		"lnmax" => $lnmax,
		"cabin" => $cabin,
		"crew" => $crew,
	);
	echo $charterboatclass->charterboat_list($param);
}

if ($az == 2){
	$subsection = round($data->subsection, 0);
	if ($subsection == 1){
		//get category data
		echo $charterboatclass->get_category_list();
	}
}
?>