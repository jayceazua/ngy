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
	$max_speed_min = $data->max_speed_min;
	$max_speed_max = $data->max_speed_max;
	
	$param = array(
		"category_id" => $category_id,
		"guest" => $guest,
		"cruisingarea_id" => $cruisingarea_id,
		"lnmin" => $lnmin,
		"lnmax" => $lnmax,
		"cabin" => $cabin,
		"crew" => $crew,
		"max_speed_min" => $max_speed_min,
		"max_speed_max" => $max_speed_max,
	);
	echo $charterboatclass->charterboat_list($param);
}

if ($az == 2){
	$subsection = round($data->subsection, 0);
	if ($subsection == 1){
		//get boat name data
		$boat_name = $data->boat_name;
		echo $charterboatclass->get_boatname_list($boat_name);
	}
	
	if ($subsection == 2){
		//get category data
		echo $charterboatclass->get_category_list();
	}
}

if ($az == 3){
	$slug = $data->slug;
	
	$param = array(
		"checkval" => $slug,
		"checkopt" => 1,
	);
	echo $charterboatclass->charterboat_details($param);
}
?>