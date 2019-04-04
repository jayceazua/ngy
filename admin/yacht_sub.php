<?php
$bdr = "../";
include("common.php");
$adm->admin_login();

$ms = round($_POST["ms"], 0);
$p_ar = $_POST;
foreach($p_ar AS $key => $val){
    ${$key} = $val;
    if ($key != "ms" AND $key != "rank" AND $key != "oldrank"){

        if ($key == "price" OR $key == "charter_price" OR $key == "cruise_speed" OR $key == "max_speed" OR $key == "length"){
            ${$key} = round(${$key}, 2);
        }elseif ($key == "broker_id"
            OR $key == "co_broker_id"
            OR $key == "company_id"
			OR $key == "location_id"
			OR $key == "manufacturer_id"
            OR $key == "year"
            OR $key == "category_id"
            OR $key == "condition_id"
            OR $key == "state_id"
            OR $key == "country_id"
            OR $key == "flag_country_id"
            OR $key == "hull_material_id"
            OR $key == "hull_type_id"
            OR $key == "status_id"
            OR $key == "sale_usa"
			OR $key == "charter_id"            
            OR $key == "loa_ft"
			OR $key == "loa_in"
            OR $key == "beam_ft"
			OR $key == "beam_in"
            OR $key == "draft_ft"
			OR $key == "draft_in"
            OR $key == "bridge_clearance_ft"
			OR $key == "bridge_clearance_in"
            OR $key == "dry_weight"
            OR $key == "engine_make_id"
            OR $key == "engine_no"
            OR $key == "hours"
            OR $key == "engine_type_id"
            OR $key == "drive_type_id"
            OR $key == "fuel_type_id"
            OR $key == "speed_unit"
            OR $key == "en_range"
            OR $key == "horsepower_individual"
			OR $key == "joystick_control"
            OR $key == "fuel_tanks"
			OR $key == "no_fuel_tanks"
            OR $key == "fresh_water_tanks"
			OR $key == "no_fresh_water_tanks"
            OR $key == "holding_tanks"
			OR $key == "no_holding_tanks"
            OR $key == "total_cabins"
            OR $key == "total_berths"
            OR $key == "total_sleeps"
            OR $key == "total_heads"
            OR $key == "captains_cabin"
            OR $key == "crew_cabins"
            OR $key == "crew_berths"
            OR $key == "crew_sleeps"
            OR $key == "crew_heads"
            OR $key == "sold_day_no"
			OR $key == "custom_label_id"
			OR $key == "show_price"
			OR $key == "price_tag_id" 
			OR $key == "price_per_option_id" 
			OR $key == "yc_mm" 
        ){
            ${$key} = round(${$key}, 0);
        }else{
            //no format
        }
    }
}

$m1 = $_POST["m1"];
$m2 = $_POST["m2"];
$m3 = $_POST["m3"];

$loa = $yachtclass->implode_feet_inchs($loa_ft, $loa_in);
$beam = $yachtclass->implode_feet_inchs($beam_ft, $beam_in);
$draft = $yachtclass->implode_feet_inchs($draft_ft, $draft_in);
$bridge_clearance = $yachtclass->implode_feet_inchs($bridge_clearance_ft, $bridge_clearance_in);

if ($speed_unit == 2){
	$cruise_speed = round($cruise_speed / $yachtclass->mph_to_kts, 2);
	$max_speed = round($max_speed / $yachtclass->mph_to_kts, 2);
}

if ($country_id == 1){
    $state = "";
}else{
    $state_id = 0;
}

if ($link_url != ""){
    $video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($link_url));
}

$dt = date("Y-m-d H:i:s");
if ($ms == 0){
	$sql = "insert into tbl_yacht (company_id, reg_date) values ('". $cm->filtertext($company_id) ."', '". $dt ."')";
	$iiid = $db->mysqlquery_ret($sql);

    $listing_no = $yachtclass->listing_start + $iiid;
    $sql = "update tbl_yacht set listing_no = '". $listing_no ."' where id = '". $iiid ."'";
    $db->mysqlquery($sql);

    $yachtclass->add_delete_yacht_extra_info($iiid, 1);
	
	//create folder
	$source = "../yachtimage/rawimage";
	$destination = "../yachtimage/".$listing_no;
	$fle->copy_folder($source, $destination);

	$_SESSION["postmessage"] = "nw"; 
	$rback = "mod_yacht.php";
	
	if ($autosave == 1){
		$status_id = 4;
	}
}else{
	$rank = round($_POST["rank"], 0);
    $sql = "update tbl_yacht set company_id = '". $cm->filtertext($company_id) ."' where id = '".$ms."'";
	$db->mysqlquery($sql);
	$iiid = $ms;
	$_SESSION["postmessage"] = "up";
    $rback = $_SESSION["bck_pg"];
}

$model_slug = $cm->create_slug($model);

// common update
$sql = "update tbl_yacht set location_id = '". $location_id ."'
, broker_id = '". $broker_id ."'
, manufacturer_id = '". $manufacturer_id ."'
, model = '". $cm->filtertext($model) ."'
, model_slug = '". $cm->filtertext($model_slug) ."'
, year = '". $year ."'
, category_id = '". $category_id ."'
, condition_id = '". $condition_id ."'
, price = '". $price ."'
, price_tag_id = '". $price_tag_id ."'

, address = '". $cm->filtertext($address) ."'
, city = '". $cm->filtertext($city) ."'
, state = '". $cm->filtertext($state) ."'
, state_id = '". $state_id ."'
, country_id = '". $country_id ."'
, zip = '". $cm->filtertext($zip) ."'
, sale_usa = '". $sale_usa ."'
, flag_country_id = '". $flag_country_id ."'

, vessel_name = '". $cm->filtertext($vessel_name) ."'
, hull_material_id = '". $hull_material_id ."'
, hull_type_id = '". $hull_type_id ."'
, hull_color = '". $cm->filtertext($hull_color) ."'
, hull_no = '". $cm->filtertext($hull_no) ."'
, designer = '". $cm->filtertext($designer) ."'

, overview = '". $cm->filtertext($overview) ."'
, descriptions = '". $cm->filtertext($descriptions) ."'

, link_url = '". $cm->filtertext($link_url)."'
, video_id = '". $cm->filtertext($video_id)."'
, custom_label_id = '". $cm->filtertext($custom_label_id)."'

, status_id = '". $status_id ."'
, charter_id = '". $charter_id ."'
, charter_price = '". $charter_price ."'
, price_per_option_id = '". $price_per_option_id ."'
, charter_descriptions = '". $cm->filtertext($charter_descriptions)."'
, last_updated = '". $dt ."' where id = '". $iiid ."'";
$db->mysqlquery($sql);

//lat-lon
$latlonar = $geo->getLatLon($iiid, 1);
$lat = $latlonar["lat"];
$lon = $latlonar["lon"];

//meta
//if ($m1 == ""){ $m1 = $yachtclass->yacht_name($iiid); }
//if ($m2 == ""){ $m2 = $cm->get_sort_content_description($overview, 350); }

//boat slug
$boat_slug = $yachtclass->create_boat_slug($iiid);

//2nd update
$sql = "update tbl_yacht set lat_val = '". $cm->filtertext($lat)."'
, lon_val = '". $cm->filtertext($lon)."'
, boat_slug = '". $cm->filtertext($boat_slug) ."'
, m1 = '". $cm->filtertext($m1) ."'
, m2 = '". $cm->filtertext($m2) ."'
, m3 = '". $cm->filtertext($m3) ."' where id = '". $iiid ."'";
$db->mysqlquery($sql);

$sql = "update tbl_yacht_dimensions_weight set length = '". $length ."'
, loa = '". $loa ."'
, beam = '". $beam ."'
, draft = '". $draft ."'
, bridge_clearance = '". $bridge_clearance ."'
, dry_weight = '". $dry_weight ."' where yacht_id = '". $iiid ."'";
$db->mysqlquery($sql);

$sql = "update tbl_yacht_engine set engine_make_id = '". $engine_make_id ."'
, engine_model = '". $cm->filtertext($engine_model) ."'
, engine_no = '". $engine_no ."'
, hours = '". $hours ."'
, engine_type_id = '". $engine_type_id ."'
, drive_type_id = '". $drive_type_id ."'
, fuel_type_id = '". $fuel_type_id ."'
, cruise_speed = '". $cruise_speed ."'
, max_speed = '". $max_speed ."'
, en_range = '". $en_range ."'
, horsepower_individual = '". $horsepower_individual ."'
, joystick_control = '". $joystick_control ."' where yacht_id = '". $iiid ."'";
$db->mysqlquery($sql);

$sql = "update tbl_yacht_tank set fuel_tanks = '". $fuel_tanks ."'
, no_fuel_tanks = '". $no_fuel_tanks ."'
, fresh_water_tanks = '". $fresh_water_tanks ."'
, no_fresh_water_tanks = '". $no_fresh_water_tanks ."'
, holding_tanks = '". $holding_tanks ."'
, no_holding_tanks = '". $no_holding_tanks ."' where yacht_id = '". $iiid ."'";
$db->mysqlquery($sql);

$sql = "update tbl_yacht_accommodation set total_cabins = '". $total_cabins ."'
, total_berths = '". $total_berths ."'
, total_sleeps = '". $total_sleeps ."'
, total_heads = '". $total_heads ."'
, captains_cabin = '". $captains_cabin ."'
, crew_cabins = '". $crew_cabins ."'
, crew_berths = '". $crew_berths ."'
, crew_sleeps = '". $crew_sleeps ."'
, crew_heads = '". $crew_heads ."' where yacht_id = '". $iiid ."'";
$db->mysqlquery($sql);

$yachtclass->update_sold_yacht_display_date($iiid, $sold_day_no);
$yachtclass->add_yacht_keywords($iiid);
$yachtclass->remove_sold_yacht_from_featured($iiid, $status_id);
// end

//type assign
$yachtclass->yacht_type_assign($iiid);
//end

//engine assign
$yachtclass->yacht_engine_assign($iiid);
//end

//external link assign
$yachtclass->yacht_external_link_assign($iiid);
//end

//image section - edit
$yachtclass->edit_yacht_image();
//end

//image upload
$yachtclass->add_yacht_image($iiid);
//end

//engine details assign
$yachtengineclass->engine_details_assign($iiid);
//end

//brochure upload
$filename = $_FILES['brochure_file']['name'];
if ($filename != ""){
    $wh_ok = $fle->check_file_ext($cm->allow_attachment_ext, $filename);
    if ($wh_ok == "y"){
        $filename = $fle->uploadfilename($filename);
        $filename1 = $iiid."brochure".$filename;
        $target_path = "../brochurefile/";
        $target_path = $target_path . $cm->filtertextdisplay($filename1);
        $fle->fileupload($_FILES['brochure_file']['tmp_name'], $target_path);

        $sql = "update tbl_yacht set brochure_file = '". $cm->filtertext($filename1)."' where id = '". $iiid ."'";
        $db->mysqlquery($sql);
    }
}
//end

//update field for boat finder
$yachtclass->update_field_for_boat_finder(array("boatid" => $iiid));
//end

//COPY MEDIA FROM YC DATA
if ($yc_mm > 0){
	$yachtchildclass->import_yc_mm_media($iiid, $yc_mm);
}
//END

if ($autosave == 1){
	echo $iiid;
}else{
	header('Location:'.$rback);
}
?>