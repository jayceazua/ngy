<?php
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$include_fck = 1;
$yachtform = 1;
$enable_charter = 0;
$isdashboard = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
$cuser_type_id = $cuser_ar[0]["type_id"];
$company_id = $cuser_ar[0]["company_id"];
$location_id = $cuser_ar[0]["location_id"];
$onlycompany = 1;
$onlylocation = 0;

if ($cuser_type_id == 1){ 
	$onlycompany = 0;
}

if ($cuser_type_id == 5){ 
	$onlycompany = 1;
	$onlylocation = 1;
}

$lno = round($_REQUEST['lno'], 0);
$status_id = 1;
$sale_usa = 1;
$captains_cabin = 0;
$country_id = $yachtclass->get_user_country_id($loggedin_member_id);
$category_id = $manufacturer_id = $horsepower_combined = 0;
$lengthm = $loam = $beamm = $draftm = $bridge_clearancem = '-';
$sold_day_no = 0;
$sold_day_no_class = ' com_none';

$enable_custom_label = 0;
$custom_label_class = ' com_none';
$type_id = 0;
$no_fuel_tanks = $no_fresh_water_tanks = $no_holding_tanks = 1;
$price_tag_id = 0;
$yw_id = 0;

$charter_id = 1;
$charter_class = ' com_none';

$buttontext = "Add Boat";

$result = $yachtclass->check_yacht_with_return($lno, 2);
$found = count($result);

if  ($found > 0){
    $buttontext = "Save / Update";
    $row = $result[0];
    foreach($row AS $key => $val){
        ${$key} = $cm->filtertextdisplay($val);
    }
    $ms = $id;

    //Dimensions & Weight
    $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($ms) ."'";
    $ex_result = $db->fetch_all_array($ex_sql);
    $row = $ex_result[0];
    foreach($row AS $key => $val){
        ${$key} = htmlspecialchars($val);
    }

    $lengthm = $yachtclass->feet_to_meter($length) . ' M';
    $loam = $yachtclass->feet_to_meter($loa) . ' M';
    $beamm = $yachtclass->feet_to_meter($beam) . ' M';
    $draftm = $yachtclass->feet_to_meter($draft) . ' M';
    $bridge_clearancem = $yachtclass->feet_to_meter($bridge_clearance) . ' M';
	
	$loa_ft_inchs = $yachtclass->explode_feet_inchs($loa);
	$loa_ft = $loa_ft_inchs["ft"];
	$loa_in = $loa_ft_inchs["inchs"];
	
	$beam_ft_inchs = $yachtclass->explode_feet_inchs($beam);
	$beam_ft = $beam_ft_inchs["ft"];
	$beam_in = $beam_ft_inchs["inchs"];
	
	$draft_ft_inchs = $yachtclass->explode_feet_inchs($draft);
	$draft_ft = $draft_ft_inchs["ft"];
	$draft_in = $draft_ft_inchs["inchs"];
	
	$bridge_clearance_ft_inchs = $yachtclass->explode_feet_inchs($bridge_clearance);
	$bridge_clearance_ft = $bridge_clearance_ft_inchs["ft"];
	$bridge_clearance_in = $bridge_clearance_ft_inchs["inchs"];

    //Engine
    $ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($ms) ."'";
    $ex_result = $db->fetch_all_array($ex_sql);
    $row = $ex_result[0];
    foreach($row AS $key => $val){
        ${$key} = htmlspecialchars($val);
    }

    //Tank Capacities
    $ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($ms) ."'";
    $ex_result = $db->fetch_all_array($ex_sql);
    $row = $ex_result[0];
    foreach($row AS $key => $val){
        ${$key} = htmlspecialchars($val);
    }

    //Accommodations
    $ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($ms) ."'";
    $ex_result = $db->fetch_all_array($ex_sql);
    $row = $ex_result[0];
    foreach($row AS $key => $val){
        ${$key} = htmlspecialchars($val);
    }

    $type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $ms, 'yacht_id');
    $horsepower_combined = $engine_no * $horsepower_individual;

    $manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
    $engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
	
	if ($display_upto == $cm->default_future_date){
		$sold_day_no = 0;
	}else{
		$sold_day_no = $cm->difference_between_dates($sold_date, $display_upto);
	}
    
    $link_name = 'Modify Existing Boat';
	
	$statenm = $state;
	if ($country_id == 1){
		$statenm = $cm->get_common_field_name("tbl_state", "code", $state_id);
	}

	$meat_ar = array(
		"m1" => $m1,
		"m2" => $m2,
		"m3" => $m3,
		"manufacturer_name" => $manufacturer_name,
		"model" => $model,
		"year" => $year,
		"length" => $length,
		"overview" => $overview,
		"city" => $city,
		"state" => $statenm,
		"company_id" => $company_id
	);
	$final_meta = $yachtclass->collect_meta_info_boat($meat_ar);
	$m1 = $final_meta->m1;
	$m2 = $final_meta->m2;
	$m3 = $final_meta->m3;
}else{
    $ms = 0;
    $link_name = 'Add New Boat';
	$company_id = $cuser_ar[0]["company_id"];
    $location_id = $cuser_ar[0]["location_id"];	
}

if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";
    $state_s2 = "com_none";
}

if ($status_id == 3){
    $sold_day_no_class = "";
}

$enable_custom_label = $cm->get_common_field_name('tbl_company', 'enable_custom_label', $company_id);
if ($enable_custom_label == 1){
	$custom_label_class = '';
}

if ($charter_id == 2 OR $charter_id == 3){
	$charter_class = '';
}

//$company_name = $cm->get_common_field_name('tbl_company', 'cname', $company_id);
//$location_name = $cm->get_common_field_name('tbl_location_office', 'cname', $location_id);

$atm1 = $link_name;

$breadcrumb = 0;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => 'Inventory',
            'a_link' => $cm->folder_for_seo . 'my-boatlist/'
);
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 1, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
echo $html_start;
?>
<form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="yacht_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />
    <input type="hidden" value="<?php echo $yachtclass->max_engine; ?>" name="max_engine" id="max_engine" />
    <input class="finfo" id="email2" name="email2" type="text" />
    <input type="hidden" id="fcapi" name="fcapi" value="boatsubmit" />
    
    <div class="singleblocktop">
        <button type="submit" class="button" value="<?php echo $buttontext; ?>"><?php echo $buttontext; ?></button>
    </div>
    <div class="singleblock">
        <div class="singleblock_heading"><span>Basic Information</span></div>
        <div class="singleblock_box singleblock_box_h">
            <?php if ($ms > 0){?>
            <ul class="form">
                <li class="left">
                	<p>Listing No: <?php echo $listing_no; ?></p>
                </li>
                <?php if ($yw_id > 0){?>
                <li class="right">
                	<p>YachtWorld ID: <?php echo $yw_id; ?></p>
                </li>
                <?php } ?>
            </ul>    
            <?php }?>
            
            <ul class="form">
                <li class="left">
                    <p>Manufacturer <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="manufacturer_name" name="manufacturer_name" value="<?php echo $manufacturer_name; ?>" connectedfield="manufacturer_id" targetdiv="1" ckpage="1" autocomplete="off" class="azax_suggest azax_suggest1 input" />
                    <input type="hidden" value="<?php echo $manufacturer_id; ?>" name="manufacturer_id" id="manufacturer_id" />
                    <div id="suggestsearch1" class="suggestsearch com_none"></div>
                </li>
                <li class="right">
                    <p>Model <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="model" name="model" value="<?php echo $model; ?>" class="input" />
                </li>

                <li class="left" id="year_heading">
                    <p>Year <span class="requiredfieldindicate">*</span></p>
                    <select class="my-dropdown2" id="year" name="year">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_year_combo($year);
                        ?>
                    </select>
                </li>
                <li class="right" id="category_id_heading">
                    <p>Category <span class="requiredfieldindicate">*</span></p>
                    <select class="my-dropdown2 catupdate" targetcombo="type_id" name="category_id" id="category_id">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_category_combo($category_id);
                        ?>
                    </select>
                </li>

                <li class="left" id="condition_id_heading">
                    <p>Condition <span class="requiredfieldindicate">*</span></p>
                    <select class="my-dropdown2" name="condition_id" id="condition_id">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_condition_combo($condition_id);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Price [$] <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="price" name="price" value="<?php echo $price; ?>" class="input" />
                </li>

                <li class="left">
                    <p>Vessel Name</p>
                    <input type="text" id="vessel_name" name="vessel_name" value="<?php echo $vessel_name; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Hull Material</p>
                    <select class="my-dropdown2" name="hull_material_id" id="hull_material_id">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_hull_material_combo($hull_material_id);
                        ?>
                    </select>
                </li>

                <li class="left">
                    <p>Hull Type</p>
                    <select class="my-dropdown2" name="hull_type_id" id="hull_type_id">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_hull_type_combo($hull_type_id);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Hull Color</p>
                    <input type="text" id="hull_color" name="hull_color" value="<?php echo $hull_color; ?>" class="input" />
                </li>
                
                <li class="left">
                    <p>HIN</p>
                    <input type="text" id="hull_no" name="hull_no" value="<?php echo $hull_no; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Boat Type</p>
                    <select class="my-dropdown2" name="type_id" id="type_id">
                        <option value="">Select</option>
                        <?php
						echo $yachtclass->get_type_combo_parent($type_id, $category_id, 0, 1);
                        ?>
                    </select>
                </li>
                
                <li class="left">
                    <p>Designer</p>
                    <input type="text" id="designer" name="designer" value="<?php echo $designer; ?>" class="input" />
                </li>
                
                <li><p class="subhead">Boat Price Display</p></li>
                <li class="left">
                	<p>Do Not Show Price?&nbsp;&nbsp;&nbsp;<input type="checkbox" id="show_price" name="show_price" value="1" class="checkbox" <?php if ($price_tag_id > 0){?>checked="checked"<?php } ?> /></p>
                </li>
                <li class="right">
                    <p>Message to Display</p>
                    <select class="my-dropdown2" name="price_tag_id" id="price_tag_id">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_price_tag_combo($price_tag_id);
                        ?>
                    </select>
                </li>
                
                <li><p class="subhead">Company Information</p></li>                
                <li class="left" id="company_id_heading">
                    <p>Company <span class="requiredfieldindicate">*</span></p>
                    <select id="company_id" name="company_id" class="my-dropdown2">
                    	<?php if ($cuser_type_id == 1){?>
                        <option value="">Select Company</option>
                        <?php } ?>
                        <?php echo $yachtclass->get_company_combo($company_id, $onlycompany); ?>
                    </select>
                </li>
                <li class="right" id="location_id_heading">
                    <p>Office Location <span class="requiredfieldindicate">*</span></p>
                    <select id="location_id" name="location_id" class="my-dropdown2">
                        <option value="" addressval="">Select Location</option>
                        <?php echo $yachtclass->get_company_location_combo($location_id, $company_id, 0, $onlylocation); ?>
                    </select>
                </li>
                <?php if ($cuser_type_id == 1 OR $cuser_type_id == 2 OR $cuser_type_id == 3 OR $cuser_type_id == 4){?>
                <li class="left" id="broker_id_heading">
                    <p>Broker/Agent <span class="requiredfieldindicate">*</span></p>
                    <select name="broker_id" id="broker_id" class="my-dropdown2 brokercombo">
                        <option value="">Select Broker/Agent</option>
                        <?php
                        echo $yachtclass->get_broker_combo($broker_id, $company_id, $location_id);
                        ?>
                    </select>
                </li>
                <?php }else{?>
                <input type="hidden" id="broker_id" name="broker_id" value="<?php echo $loggedin_member_id; ?>" />                         
                <?php } ?>

                <li><p class="subhead">Boat Location</p></li>
                <li>
                    <p>Same as Office Location &nbsp;&nbsp; <input type="checkbox" id="same_as_location" name="same_as_location" value="1" class="checkbox" /></p>                    
                </li>
                
                <li class="left">
                    <p>Address</p>
                    <input type="text" id="address" name="address" value="<?php echo $address; ?>" class="input" />
                </li>
                <li class="right">
                    <p>City <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="city" name="city" value="<?php echo $city; ?>" class="input" />
                </li>

                <li class="left" id="country_id_heading">
                    <p>Country <span class="requiredfieldindicate">*</span></p>
                    <select id="country_id" name="country_id" refextra="" class="countrycls_state my-dropdown2">
                        <option value="">Select</option>
                        <?php $yachtclass->get_country_combo($country_id); ?>
                    </select>
                </li>
                <li class="right">
                    <p>State <span class="requiredfieldindicate">*</span></p>
                    <div id="sps2" class="<?php echo $state_s2; ?>">
                        <select id="state_id" name="state_id" class="my-dropdown2">
                            <option value="">Select State</option>
                            <?php $yachtclass->get_state_combo($state_id); ?>
                        </select>
                    </div>
                    <div id="sps1" class="<?php echo $state_s1; ?> ">
                        <input type="text" id="state" name="state" value="<?php echo $state; ?>"  class="input" />
                    </div>
                </li>

                <li class="left">
                    <p>Zipcode</p>
                    <input type="text" id="zip" name="zip" value="<?php echo $zip; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Flag of Registry</p>
                    <select id="flag_country_id" name="flag_country_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php $yachtclass->get_country_combo($flag_country_id); ?>
                    </select>
                </li>

                <li class="left">
                    Available for sale in U.S. waters? &nbsp;&nbsp;&nbsp;
                    <input type="radio" id="sale_usa1" name="sale_usa" value="1" <?php if ($sale_usa == 1){?> checked="checked"<?php } ?> /> Yes &nbsp;&nbsp;
                    <input type="radio" id="sale_usa2" name="sale_usa" value="0" <?php if ($sale_usa == 0){?> checked="checked"<?php } ?> /> No
                </li>                
            </ul>
            
            <?php
			if ($enable_charter == 1){
			?>
            <ul class="form">
            	<li><p class="subhead">Charter Designation</p></li>
                <li class="left">
                    <p>Choose Option <span class="requiredfieldindicate">*</span></p>
                    <select id="charter_id" name="charter_id" class="my-dropdown2">
                        <?php echo $yachtclass->get_charter_combo($charter_id); ?>
                    </select>
                </li>
                <li class="right charterclass<?php echo $charter_class; ?>">
                	<p>Charter Price [$] <span class="requiredfieldindicate">*</span></p>
                    <div class="leftfield">                    
                    <input type="text" id="charter_price" name="charter_price" value="<?php echo $charter_price; ?>" class="input" />
                    </div>
                    <div class="rightfield">
                    <select id="price_per_option_id" name="price_per_option_id" class="my-dropdown2">
						 <?php echo $yachtclass->get_price_per_option_combo($price_per_option_id); ?>
                    </select>
                    </div>
                    <div class="clearfix"></div>
                </li>
            </ul>
            <?php
			}else{
			?>
            <input type="hidden" value="1" id="charter_id" name="charter_id" />
            <?php
			}
			?>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Dimensions & Weight</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
                <li class="left">
                	<div class="col2">
                        <p>Length [in Ft.]</p>
                        <input type="text" id="length" name="length" value="<?php echo $length; ?>" insplit="0" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    <div class="col2 autotitle"><p><span class="lengthm fontbold"><?php echo $lengthm; ?></span></p></div>                    
                </li>
                <li class="right">
                	<div class="col3">
                        <p>LOA [in Ft.]</p>
                        <input type="text" id="loa_ft" name="loa_ft" value="<?php echo $loa_ft; ?>" insplit="1" converttarget="loa" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    
                    <div class="col3">
                        <p>LOA [in Inchs.]</p>
                        <input type="text" id="loa_in" name="loa_in" value="<?php echo $loa_in; ?>" insplit="1" converttarget="loa" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    
                    <div class="col3 autotitle"><p><span class="loam fontbold"><?php echo $loam; ?></span></p></div>
                </li>

                <li class="left">
                	<div class="col3">
                        <p>Beam [in Ft.]</p>
                        <input type="text" id="beam_ft" name="beam_ft" value="<?php echo $beam_ft; ?>" insplit="1" converttarget="beam" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    
                    <div class="col3">
                        <p>Beam [in Inchs.]</p>
                        <input type="text" id="beam_in" name="beam_in" value="<?php echo $beam_in; ?>" insplit="1" converttarget="beam" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    <div class="col3 autotitle"><p><span class="beamm fontbold"><?php echo $beamm; ?></span></p></div>
                </li>
                <li class="right">
                	<div class="col3">
                        <p>Draft - max [in Ft.]</p>
                        <input type="text" id="draft_ft" name="draft_ft" value="<?php echo $draft_ft; ?>" insplit="1" converttarget="draft" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    
                    <div class="col3">
                        <p>Draft - max [in Inchs.]</p>
                        <input type="text" id="draft_in" name="draft_in" value="<?php echo $draft_in; ?>" insplit="1" converttarget="draft" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    <div class="col3 autotitle"><p><span class="draftm fontbold"><?php echo $draftm; ?></span></p></div>
                </li>

                <li class="left">
                	<div class="col3">
                        <p>Bridge Clearance [in Ft.]</p>
                        <input type="text" id="bridge_clearance_ft" name="bridge_clearance_ft" value="<?php echo $bridge_clearance_ft; ?>" insplit="1" converttarget="bridge_clearance" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    <div class="col3">
                        <p>Bridge Clearance [in Inchs.]</p>
                        <input type="text" id="bridge_clearance_in" name="bridge_clearance_in" value="<?php echo $bridge_clearance_in; ?>" insplit="1" converttarget="bridge_clearance" convertval="<?php echo $yachtclass->ft_to_meter; ?>" class="meterconvert input" />
                    </div>
                    <div class="col3 autotitle"><p><span class="bridge_clearancem fontbold"><?php echo $bridge_clearancem; ?></span></p></div>
                </li>
                <li class="right">
                    <p>Dry Weight [in lbs.]</p>
                    <input type="text" id="dry_weight" name="dry_weight" value="<?php echo $dry_weight; ?>" class="input" />
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Engine</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
                <li class="left">
                    <p>Engine Make</p>
                    <input type="text" id="engine_make_name" name="engine_make_name" value="<?php echo $engine_make_name; ?>" connectedfield="engine_make_id" targetdiv="3" ckpage="3" autocomplete="off" class="azax_suggest azax_suggest3 input" />
                    <input type="hidden" value="<?php echo $engine_make_id; ?>" name="engine_make_id" id="engine_make_id" />
                    <div id="suggestsearch3" class="suggestsearch com_none"></div>
                </li>
                <li class="right">
                    <p>Engin Model</p>
                    <input type="text" id="engine_model" name="engine_model" value="<?php echo $engine_model; ?>" class="input" />
                </li>

                <li class="left">
                    <p>Engine(s)</p>
                    <select name="engine_no" id="engine_no" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($engine_no, 4);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Hours</p>
                    <input type="text" id="hours" name="hours" value="<?php echo $hours; ?>" class="input" />
                </li>

                <li class="left">
                    <p>Engine Type</p>
                    <select name="engine_type_id" id="engine_type_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_engine_type_combo($engine_type_id);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Drive Type</p>
                    <select name="drive_type_id" id="drive_type_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_drive_type_combo($drive_type_id);
                        ?>
                    </select>
                </li>

                <li class="left">
                    <p>Fuel Type</p>
                    <select name="fuel_type_id" id="fuel_type_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_fuel_type_combo($fuel_type_id);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Horsepower Individual</p>
                    <input type="text" id="horsepower_individual" name="horsepower_individual" value="<?php echo $horsepower_individual; ?>" class="input" />
                </li>

                <li class="left">
                    <p>Joystick Control&nbsp;&nbsp;<input type="checkbox" id="joystick_control" name="joystick_control" value="1" <?php if ($joystick_control == 1){?> checked="checked"<?php } ?> class="checkbox" /></p>
                </li>
                <li class="right">
                    Horsepower Combined &nbsp;&nbsp;&nbsp;
                    <span class="horsepower_combined_v fontbold"><?php echo $horsepower_combined; ?></span>
                </li>
             </ul>
             
             <?php echo $yachtengineclass->display_engine_details_form($ms, 1); ?>
            
             <ul class="form">
                <li>
                <strong>Speed and Distance</strong>&nbsp;&nbsp;Unit:&nbsp;&nbsp;
                <input convertval="<?php echo $yachtclass->mph_to_kts; ?>" whchecked="1" class="ktsconvert" type="radio" id="speed_unit1" name="speed_unit" value="1" checked="checked" /> MPH
                <input convertval="<?php echo $yachtclass->mph_to_kts; ?>" whchecked="0" class="ktsconvert" type="radio" id="speed_unit2" name="speed_unit" value="2" /> KTS
                </li>
                <li class="left">
                    <p>Cruise Speed</p>
                    <input type="text" id="cruise_speed" name="cruise_speed" value="<?php echo $cruise_speed; ?>" class="input" />
                </li>
                <li class="right">
                    <p>Max Speed</p>
                    <input type="text" id="max_speed" name="max_speed" value="<?php echo $max_speed; ?>" class="input" />
                </li>

                <li class="left">
                    <p>Range [MI]</p>
                    <input type="text" id="en_range" name="en_range" value="<?php echo $en_range; ?>" class="input" />
                </li>
                <li class="right">
                    &nbsp;
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Tank Capacities</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
                <li class="left">
                    <p>Fuel Tank Total Gallons</p>
                    <input type="text" id="fuel_tanks" name="fuel_tanks" value="<?php echo $fuel_tanks; ?>" class="input" />
                </li>
                <li class="right">
                    <p>No of Fuel Tanks</p>
                    <select name="no_fuel_tanks" id="no_fuel_tanks" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_fuel_tanks);
                        ?>
                    </select>
                </li>
                
                <li class="left">
                    <p>Fresh Water Tank Total Gallons</p>
                    <input type="text" id="fresh_water_tanks" name="fresh_water_tanks" value="<?php echo $fresh_water_tanks; ?>" class="input" />
                </li>
                <li class="right">
                    <p>No of Fresh Water Tanks</p>
                    <select name="no_fresh_water_tanks" id="no_fresh_water_tanks" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_fresh_water_tanks);
                        ?>
                    </select>
                </li>

                <li class="left">
                    <p>Holding Tank Total Gallons</p>
                    <input type="text" id="holding_tanks" name="holding_tanks" value="<?php echo $holding_tanks; ?>" class="input" />
                </li>
                <li class="right">
                    <p>No of Holding Tank Tanks</p>
                    <select name="no_holding_tanks" id="no_holding_tanks" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_holding_tanks);
                        ?>
                    </select>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Accommodations</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
                <li class="left">
                    <p>Total Cabins</p>
                    <select name="total_cabins" id="total_cabins" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($total_cabins);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Total Berths</p>
                    <select name="total_berths" id="total_berths" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($total_berths);
                        ?>
                    </select>
                </li>

                <li class="left">
                    <p>Total Sleeps</p>
                    <select name="total_sleeps" id="total_sleeps" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($total_sleeps);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Total Heads</p>
                    <select name="total_heads" id="total_heads" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($total_heads);
                        ?>
                    </select>
                </li>

                <li>
                    Captains Cabin &nbsp;&nbsp;&nbsp;
                    <input type="radio" id="captains_cabin1" name="captains_cabin" value="1" <?php if ($captains_cabin == 1){?> checked="checked"<?php } ?> /> Yes&nbsp;&nbsp;
                    <input type="radio" id="captains_cabin2" name="captains_cabin" value="0" <?php if ($captains_cabin == 0){?> checked="checked"<?php } ?> /> No
                </li>

                <li class="left">
                    <p>Crew Cabins</p>
                    <select name="crew_cabins" id="crew_cabins" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($crew_cabins);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Crew Berths</p>
                    <select name="crew_berths" id="crew_berths" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($crew_berths);
                        ?>
                    </select>
                </li>

                <li class="left">
                    <p>Crew Sleeps</p>
                    <select name="crew_sleeps" id="crew_sleeps" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($crew_sleeps);
                        ?>
                    </select>
                </li>
                <li class="right">
                    <p>Crew Heads</p>
                    <select name="crew_heads" id="crew_heads" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($crew_heads);
                        ?>
                    </select>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Main Description</span></div>
        <div class="singleblock_box editorheight1">
            <?php
            $editorstylepath = "";
            $editorextrastyle = "text_area";
            $editortoolbarset = "frontendbasic";
            $cm->display_editor("overview", $sBasePath, "99%", "100%", $overview, $editorstylepath, $editorextrastyle, $editortoolbarset);
            ?>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Descriptions / Features</span></div>
        <div class="singleblock_box editorheight2">
            <?php
            $editorstylepath = "";
            $editorextrastyle = "text_area";
            $editortoolbarset = "frontendbasic";
            $cm->display_editor("descriptions", $sBasePath, "99%", 300, $descriptions, $editorstylepath, $editorextrastyle, $editortoolbarset);
            ?>
        </div>
    </div>
    
    <?php
	if ($enable_charter == 1){
	?>
    <div class="singleblock charterclass<?php echo $charter_class; ?>">
        <div class="singleblock_heading"><span>Charter Description</span></div>
        <div class="singleblock_box editorheight1">
            <?php
            $editorstylepath = "";
            $editorextrastyle = "text_area";
            $editortoolbarset = "frontendbasic";
            $cm->display_editor("charter_descriptions", $sBasePath, "99%", "100%", $charter_descriptions, $editorstylepath, $editorextrastyle, $editortoolbarset);
            ?>
        </div>
    </div>
    <?php
	}
	?>

	<div class="singleblock">
        <div class="singleblock_heading"><span>External Links</span></div>
        <div class="singleblock_box singleblock_box_h">
            <?php echo $yachtclass->yacht_external_link_display_list($ms, 1); ?>    
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Display Information</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
            	<li class="left">
                	<div class="enablecustomlabel<?php echo $custom_label_class; ?>">
                        <p>Select Custom Label</p>
                        <select id="custom_label_id" name="custom_label_id" class="my-dropdown2">
                            <option value="">Select Custom Label</option>
                            <?php echo $yachtclass->get_custom_label_combo($custom_label_id); ?>
                        </select>
                    </div>
                </li>
                <li class="right" id="status_id_heading">
                    <p>Display Status <span class="requiredfieldindicate">*</span></p>
                    <select name="status_id" id="status_id" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_yachtstatus_combo($status_id);
                        ?>
                    </select>
                </li>
                <li class="left" id="sold_day_no_heading">
                    <div class="solddaynoclass<?php echo $sold_day_no_class; ?>">
                    <p># of days boat is still shown on site <span class="requiredfieldindicate">*</span></p>
                    <select name="sold_day_no" id="sold_day_no" class="my-dropdown2">
                        <option value="">Select</option>
                        <?php
                        echo $yachtclass->get_sold_boat_days_combo($sold_day_no);
                        ?>
                    </select>
                    </div>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Meta Information</span></div>
        <div class="singleblock_box singleblock_box_h">
            <ul class="form">
            	<li>
                	<p>Page Title</p>
                    <input type="text" id="m1" name="m1" value="<?php echo $m1; ?>" class="input" />
                </li>
                
                <li>
                	<p>Meta Description</p>
                    <textarea name="m2" id="m2" rows="1" cols="1" class="comments"><?php echo $m2;?></textarea>
                </li>
                
                <li>
                	<p>Meta Keywords</p>
                    <textarea name="m3" id="m3" rows="1" cols="1" class="comments"><?php echo $m3;?></textarea>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
    </div>

    <div class="singleblock">
        <button type="submit" class="button" value="<?php echo $buttontext; ?>"><?php echo $buttontext; ?></button>
    </div>
</form>

<?php
echo $html_end;
include($bdr."includes/foot.php");
?>