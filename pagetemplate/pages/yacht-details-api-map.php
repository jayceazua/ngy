<?php
$main_heading = "n";
$top_mini_search = 0;
$googlemap = 1;
$addfav = 1;
$display_boat_disclaimer = 0;
$display_yachtworld_disclaimer = 0;
$display_yachtworld_disclaimer_text = '';
$slickslider = 1;
$startend = 0;

$lno = round($_REQUEST['lno'], 0);
if ($lno == 0){
	$result = $yachtclass->check_yacht_with_return($lno, 3);
}else{
	$result = $yachtclass->check_yacht_with_return($lno, 1);
}

$row = $res_row = $result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}
$yacht_title = $yachtclass->yacht_name($id);

$b_ar = array(
	"boatid" => $id, 
	"makeid" => $manufacturer_id, 
	"ownboat" => $ownboat, 
	"feed_id" => $feed_id, 
	"getdet" => 0
);
$fullurl = $cm->site_url . $yachtclass->get_boat_details_url($b_ar);

if ($yw_id > 0 AND $ownboat == 0){
	$display_boat_disclaimer = 0;
	$display_yachtworld_disclaimer = 0;
	
	$display_yachtworld_disclaimer_text = '
	<div class="disclaimer_div"><br>
		'. $cm->get_systemvar('YWTXT') .'
		<span class="yw">('. $yw_id .')</span>
	</div>
	';
}

$addressfull = $yachtclass->get_yacht_address($id);
$type_name = $cm->display_multiplevl($id, 'tbl_yacht_type_assign', 'type_id', 'yacht_id', 'tbl_type');
$type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $id, 'yacht_id');

//Dimensions & Weight
$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
$ex_result = $db->fetch_all_array($ex_sql);
$row = $ex_result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

//Engine
$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
$ex_result = $db->fetch_all_array($ex_sql);
$row = $ex_result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

//Tank Capacities
$ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($id) ."'";
$ex_result = $db->fetch_all_array($ex_sql);
$row = $ex_result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

//Accommodations
$ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($id) ."'";
$ex_result = $db->fetch_all_array($ex_sql);
$row = $ex_result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

$flag_country_name = $cm->get_common_field_name('tbl_country', 'code', $flag_country_id);
$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
$category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
$condition_name = $cm->get_common_field_name('tbl_condition', 'name', $condition_id);
$hull_material_name = $cm->get_common_field_name('tbl_hull_material', 'name', $hull_material_id);
$hull_type_name = $cm->get_common_field_name('tbl_hull_type', 'name', $hull_type_id);
$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);

$firstimage = $yachtclass->get_yacht_first_image($id);

//Fav
if ($loggedin_member_id > 0){
	$check_favorites = $yachtclass->check_yacht_favorites($id);
	if ($check_favorites > 0){
		$favtext = '<a id="favlist-'. $id .'" yid="'. $id .'" rtsection="0" href="javascript:void(0);" class="yachtfv removefavboat" title="Your favorite. Remove?">Your Favorite</a>';
	}else{
		$favtext = '<a id="favlist-'. $id .'" yid="'. $id .'" rtsection="1" href="javascript:void(0);" class="yachtfv addfavboat" title="Add to favorites">Favorite</a>';
	}
}else{
	$favtext = '<a id="favlist-'. $id .'" href="'. $cm->folder_for_seo .'pop-login/?chkid='. $id .'" class="loginpop addfavboat" title="Add to favorites">Favorite</a>';
}
//end

$yachtclass->update_yacht_view($id);
$_SESSION["visited_boat"] = $id;
$_SESSION["visited_boat_page"] = 1;

//Similar Yacht field pick
$from_length_ck = $length - 20;
$to_length_ck = $length + 20;

$from_price_ck = $price - 500000;
$to_price_ck = $price + 500000;

if ($ownboat == 1){
	if ($type_id == $yachtclass->catamaran_id){
		$sp_typeid = 2;
	}else{
		$sp_typeid = 1;
	}
}else{
	if ($feed_id == $yachtclass->catamaran_feed_id){
		$sp_typeid = 2;
		$column_id = 7;
	}else{
		$sp_typeid = 1;
		$column_id = 6;
	}
}

$similar_yacht_param = array(
	"lnmin" => $from_length_ck,
	"lnmax" => $to_length_ck,
	"prmin" => $from_price_ck,
	"prmax" => $to_price_ck,
	"categoryid" => $category_id,
	"owned" => $ownboat,
	"sp_typeid" => $sp_typeid,
	"similaryacht_type_filter" => $type_id,
	"currentboat" => $id,
	"template" => 0
);

$similar_yacht_text = $yachtchildclass->display_similar_yacht($similar_yacht_param);

$normal_pagination = 1;
if ($charter_id == 2){
	$pageid = $s_pageid = 4;
}else{
	//$pageid = $s_pageid = $_SESSION["conditional_page_id"];
	$pageid = $s_pageid = $cm->get_data_set(array("section_for" => 1));
}
if (isset($_SESSION["s_normal_pagination"]) AND $_SESSION["s_normal_pagination"] == 2){
	$pageid = $cm->get_common_field_name('tbl_page', 'id', $manufacturer_id, 'connected_manufacturer_id');
	if ($pageid == 0){		
		$pageid = $s_pageid;
	}else{
		$normal_pagination = 0;
	}	
}

$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
$parentpagear = (object)$parentpagear[0];
$link_name = $parentpagear->name;

$top_parentpage_category = $cm->collect_top_parentpage_category($pageid);
$get_connected_to_otherpage = $frontend->get_connected_to_otherpage($pageid);

$html_start = "";
$html_end = "";
if (isset($_SESSION["s_insidedb"]) AND $_SESSION["s_insidedb"] == 1){	
	$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 1, "link_name" => '')));
	$html_start = $htmlstartend->htmlstart;
	$html_end = $htmlstartend->htmlend;
	$ycappnotice = $htmlstartend->ycappnotice;
	$breadcrumb = 0;
	$isdashboard = 1;
	$main_heading = "n";
}else{

	$breadcrumb = 1;
	if ($normal_pagination == 1){
		/*$breadcrumb_extra[] = array(
				'a_title' => $category_name,
				'a_link' => $cm->get_page_url($category_name, 'category')
		);

		$breadcrumb_extra[] = array(
				'a_title' => $type_name,
				'a_link' => $cm->get_page_url($type_name, 'type')
		);

		$breadcrumb_extra[] = array(
				'a_title' => $yacht_title,
				'a_link' => ''
		);*/

		$breadcrumb_extra[] = array(
				'a_title' => $yacht_title,
				'a_link' => ''
		);

		$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);
	}else{
		if ($condition_name != ""){
			$manufacturerarslug = $cm->get_common_field_name('tbl_manufacturer', 'slug', $manufacturer_id);
			$conditionslug = strtolower($condition_name);
			$inv_url_format = 'make/' . $manufacturerarslug . '/condition/' . $conditionslug;
			$pagename = $cm->serach_url_filtertext($inv_url_format);
			$ret_url = $cm->folder_for_seo . $pagename . "/";

			$breadcrumb_extra[] = array(
					'a_title' => $condition_name . " Boats",
					'a_link' => $ret_url
			);
		}

		$breadcrumb_extra[] = array(
				'a_title' => $yacht_title,
				'a_link' => ''
		);
		$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra);
	}
}
//Meta
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
$atm1 = $final_meta->m1;
$atm2 = $final_meta->m2;
$atm3 = $final_meta->m3;

$content = $atm2;
$twittercontent = "View this ". $yacht_title ." for sale at ". $cm->sitename ." in " . $addressfull . ".\n" . $fullurl;
$imagelink =  $cm->site_url . '/yachtimage/'. $listing_no .'/big/' . $firstimage;
$opengraphmeta = $cm->meta_open_graph($yacht_title, $content, $imagelink, $fullurl);

$pageid = 0;
include($bdr."includes/head.php");
echo $html_start;

$wh_featured = $yachtclass->check_yacht_featured($id);
if ($yw_id > 0 AND $ownboat == 0 AND $wh_featured == 0){
	//Co-Brokerage Boat	
?>
<div class="container clearfixmain">
<?php
echo $frontend->page_brdcmp_array($brdcmp_array);
?>
<div class="product-detail clearfixmain">

	<div class="left-cell">
		<div class="product-detail-header">
			<h1 class="title"><?php echo $yacht_title; ?></h1>
			<ul class="listing-meta">
				<?php if ($length > 0){?><li class="ft"><?php echo floatval($length); ?> ft</li><?php } ?>
				<?php if ($type_name != ""){?><li class="cat"><?php echo $type_name;?></li><?php } ?>
				<li class="loc"><?php echo $addressfull; ?></li>
			</ul>
		</div>
	</div>

	<div class="right-cell">
		<section class="section sectionbg3 sectionbgtop price topprice clearfixmain">
			<?php if ($charter_id == 1 OR $charter_id == 3){?>
			<div class="pricediv saleprice clearfixmain">
				<h3>Yacht price</h3>
				<?php
				if ($price_tag_id > 0){
					$price_display_message = $cm->get_common_field_name('tbl_price_tag', 'name', $price_tag_id);
				?>
				<div class="full"><?php echo $price_display_message; ?></div>
				<?php }else{ ?>
				<div class="left"><span id="pricechange">$<?php echo $cm->price_format($price); ?></span></div>
                <div class="right">
                    <select tdiv="" setpr="<?php echo round($price, 0); ?>" class="my-dropdown2" id="currency_id" name="currency_id">
                        <?php echo $yachtclass->get_currency_combo(0, 1); ?>
                    </select>
                </div>               
				<?php 
					}

					$charterclass = '';
					if ($charter_id == 3){
						$charterclass = " com_none";
				?>
				<div class="clear"></div>
				<p><a href="javascript:void(0);" class="pricetoggle" dateref="charterprice">View Charter Price</a></p>
				<?php 
					} 
				?>
			</div>
			<?php } ?>

			<?php 
				if ($charter_id == 2 OR $charter_id == 3){
					$price_per_option_name = $cm->get_common_field_name("tbl_price_per_option", "name", $price_per_option_id);
			?>
			<div class="pricediv charterprice<?php echo $charterclass; ?> clearfixmain">
				<h3>Charter price :</h3>
				<div class="left"><span id="pricechangecharter">$<?php echo $cm->price_format($charter_price); ?></span> / <?php echo $price_per_option_name; ?></div>
				<div class="right">
					<select tdiv="charter" setpr="<?php echo round($charter_price, 0); ?>" class="my-dropdown2" id="currency_idcharter" name="currency_idcharter">
						<?php echo $yachtclass->get_currency_combo(0, 1); ?>
					</select>
				</div>
				<?php 					
					if ($charter_id == 3){						
				?>
				<div class="clear"></div>
				<p><a href="javascript:void(0);" class="pricetoggle" dateref="saleprice">View Sale Price</a></p>
				<?php 
					} 
				?>
			</div>
			<?php } ?>
		</section>    	
	</div>


	<div class="left-cell">
		<?php echo $yachtclass->display_yacht_slider_full($id);?>
     	
		<div class="boatbuttonset3 clearfixmain">
			<ul>
				<?php echo $yachtclass->yacht_button_set3(array("boat_id" => $id, "listing_no" => $listing_no, "location_id" => $location_id, "broker_id" => $broker_id, "template" => 0)); ?>
				<li>
                	<ul class="boat_details_api_page_icons">
                    	<li><a class="boat_download" href="<?php echo $cm->folder_for_seo;?>?fcapi=createyachtpdf&lno=<?php echo $listing_no; ?>" target="_blank">Download</a></li>
                    	<li><a class="boat_share" href="javascript:void(0);">Share</a>
                            <div class="boat_details_share_icons animated fast fadeInUp">
                                <span>
                                    <?php echo $cm->facebook_share_url(array("title" => $yacht_title, "content" => $content, "fullurl" => $fullurl, "template" => 2)); ?>
									<?php echo $cm->googleplus_share_url(array("title" => $yacht_title, "content" => $content, "fullurl" => $fullurl, "template" => 2)); ?>
                                    <?php echo $cm->twitter_share_url(array("title" => $yacht_title, "content" => $twittercontent, "fullurl" => $fullurl, "template" => 2)); ?>
                                    <?php echo $cm->linkedin_share_url(array("title" => $yacht_title, "content" => $twittercontent, "fullurl" => $fullurl, "template" => 2)); ?>
                                    <?php echo $cm->pinterest_share_url(array("title" => $yacht_title, "listing_no" => $listing_no, "content" => $twittercontent, "image" => $firstimage, "fullurl" => $fullurl, "template" => 2)); ?>
                                </span>
                            </div>
                        </li>
                        <li><a class="boat_friend referfriend" href="<?php echo $cm->folder_for_seo;?>pop-send-email-friend/?lno=<?php echo $listing_no; ?>" title="Email A Friend">Email a Friend</a></li>                    
                    	<li><?php echo $favtext; ?></li>
                    </ul>         
				</li>
			
			</ul>
		</div>
             
        <div class="customboatviewcontent clearfixmain">
        	<?php
			if ($overview != ""){
			?>
        	<h2 class="singlelinebottom">Overview</h2>
        	<div class="customboattabcontent clearfixmain"><?php echo $overview; ?></div>
        	<?php
			}
			?>
            
            <h2 class="singlelinebottom">Highlights</h2>
            <div class="customboattabcontent clearfixmain">
        		<div class="boattabspecification clearfixmain">
        			<ul>
							<li class="clearfixmain">
								<div class="labeltitle">Manufacturer</div>
								<div class="labelvalue"><?php echo $manufacturer_name; ?></div>
							</li>				
							<li class="clearfixmain">
								<div class="labeltitle">Model</div>
								<div class="labelvalue"><?php echo $model; ?></div>
							</li>				
							<li class="clearfixmain">
								<div class="labeltitle">Year</div>
								<div class="labelvalue"><?php echo $year; ?></div>
							</li>
							
							<li class="clearfixmain">
								<div class="labeltitle">LOA</div>
								<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($loa, 1, 1); ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Beam</div>
								<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($beam, 1, 1); ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Draft - max</div>
								<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($draft, 1, 1); ?></div>
							</li>
							
							<li class="clearfixmain">
								<div class="labeltitle">Engine Brand</div>
								<div class="labelvalue"><?php echo $engine_make_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Engine Hours</div>
								<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($hours); ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Number of Rooms</div>
								<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($total_cabins); ?></div>
							</li>							
						</ul>
				</div>
			</div>
       	
       		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="1" class="customboattab">Basic Information</a></div>
        	<div id="ctab1" class="customboattabcontent com_none clearfixmain">
        		<div class="boattabspecification clearfixmain">
        			<ul>
							<!--<li class="clearfixmain">
								<div class="labeltitle">Manufacturer</div>
								<div class="labelvalue"><?php echo $manufacturer_name; ?></div>
							</li>				
							<li class="clearfixmain">
								<div class="labeltitle">Model</div>
								<div class="labelvalue"><?php echo $model; ?></div>
							</li>				
							<li class="clearfixmain">
								<div class="labeltitle">Year</div>
								<div class="labelvalue"><?php echo $year; ?></div>
							</li>-->
							
							<li class="clearfixmain">
								<div class="labeltitle">Category</div>
								<div class="labelvalue"><?php echo $category_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Condition</div>
								<div class="labelvalue"><?php echo $condition_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Location</div>
								<div class="labelvalue"><?php echo $addressfull; ?></div>
							</li>
							
							<li class="clearfixmain">
								<div class="labeltitle">Vessel Name</div>
								<div class="labelvalue"><?php echo $vessel_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Boat Type</div>
								<div class="labelvalue"><?php echo $type_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Hull Material</div>
								<div class="labelvalue"><?php echo $hull_material_name; ?></div>
							</li>

							<li class="clearfixmain">
								<div class="labeltitle">Hull Type</div>
								<div class="labelvalue"><?php echo $hull_type_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Hull Color</div>
								<div class="labelvalue"><?php echo $hull_color; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">HIN:</div>
								<div class="labelvalue"><?php echo $hull_no; ?></div>
							</li>

							<li class="clearfixmain">
								<div class="labeltitle">Designer:</div>
								<div class="labelvalue"><?php echo $designer; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Flag of Registry</div>
								<div class="labelvalue"><?php echo $flag_country_name; ?></div>
							</li>
							<li class="clearfixmain">
								<div class="labeltitle">Available for sale in U.S. waters</div>
								<div class="labelvalue"><?php echo $cm->set_yesyno_field($sale_usa); ?></div>
							</li>
						</ul>
				</div>
			</div>
    
    		<?php
			if ($length > 0 OR $loa > 0 OR $beam > 0 OR $draft > 0 OR $bridge_clearance > 0 OR $dry_weight > 0){
			?>     
     		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="2" class="customboattab">Dimensions & Weight</a></div>
      		<div id="ctab2" class="customboattabcontent com_none clearfixmain">
      			<div class="boattabspecification clearfixmain">
        			<ul>
        				<li class="clearfixmain">
							<div class="labeltitle">Length</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($length, 1); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">LOA:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($loa, 1, 1); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Beam:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($beam, 1, 1); ?></div>
						</li>
						
						<li class="clearfixmain">
							<div class="labeltitle">Draft - max:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($draft, 1, 1); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Bridge Clearance:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($bridge_clearance, 1, 1); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Dry Weight:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($dry_weight, 2); ?></div>
						</li>  				
					</ul>
				</div>
			</div>       	
       		<?php
			}
			?>
       	
       		<?php
			if ($engine_make_name != "" OR $engine_model != "" OR $engine_no > 0 OR $hours > 0 OR $cruise_speed > 0 OR $en_range > 0 OR $engine_type_name != "" OR $drive_type_name != "" OR $fuel_type_name != "" OR $max_speed > 0){
			?>
       		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="3" class="customboattab">Engine</a></div>
       		<div id="ctab3" class="customboattabcontent com_none clearfixmain">
      			<div class="boattabspecification clearfixmain">
        			<ul>
        				<li class="clearfixmain">
							<div class="labeltitle">Make:</div>
							<div class="labelvalue"><?php echo $engine_make_name; ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Model:</div>
							<div class="labelvalue"><?php echo $engine_model; ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Engine(s):</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($engine_no); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Hours:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($hours); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Cruise Speed:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($cruise_speed, 5); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Range:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($en_range, 7); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Joystick Control:</div>
							<div class="labelvalue"><?php echo $cm->set_yesyno_field($joystick_control); ?></div>
						</li>
						
						 <li class="clearfixmain">
							<div class="labeltitle">Engine Type:</div>
							<div class="labelvalue"><?php echo $engine_type_name; ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Drive Type:</div>
							<div class="labelvalue"><?php echo $drive_type_name; ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Fuel Type:</div>
							<div class="labelvalue"><?php echo $fuel_type_name; ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Horsepower:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_hp($engine_no, $horsepower_individual); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Max Speed:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($max_speed, 5); ?></div>
						</li>
						
						<?php echo $yachtengineclass->display_engine_details($id, 1); ?>				
					</ul>					
				</div>
			</div>
       		<?php
			}
			?>
       	
       		<?php
			if (($fuel_tanks > 0 AND $no_fuel_tanks > 0) OR ($fresh_water_tanks > 0 AND $no_fresh_water_tanks > 0) OR ($holding_tanks > 0 AND $no_holding_tanks > 0)){
			?>
       		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="4" class="customboattab">Tank Capacities</a></div>
       		<div id="ctab4" class="customboattabcontent com_none clearfixmain">
      			<div class="boattabspecification clearfixmain">
        			<ul>
        				<li class="clearfixmain">
							<div class="labeltitle">Fuel Tank:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Fresh Water Tank:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Holding Tank:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_tank_cap($holding_tanks, $no_holding_tanks); ?></div>
						</li>		
					</ul>					
				</div>
			</div>
       		<?php
			}
			?>
       	
       		<?php
			if ($total_cabins > 0 OR $total_berths > 0 OR $total_sleeps > 0 OR $total_heads > 0 OR $crew_cabins > 0 OR $crew_berths > 0 OR $crew_sleeps > 0 OR $crew_heads > 0){
			?>
       		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="5" class="customboattab">Accommodations</a></div>
       		<div id="ctab5" class="customboattabcontent com_none clearfixmain">
      			<div class="boattabspecification clearfixmain">
        			<ul>
        				<li class="clearfixmain">
							<div class="labeltitle">Total Cabins:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($total_cabins); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Total Berths:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($total_berths); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Total Sleeps:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($total_sleeps); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Total Heads:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($total_heads); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Captains Cabin:</div>
							<div class="labelvalue"><?php echo $cm->set_yesyno_field($captains_cabin); ?></div>
						</li>
						
						<li class="clearfixmain">
							<div class="labeltitle">Crew Cabins:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($crew_cabins); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Crew Berths:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($crew_berths); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Crew Sleeps:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($crew_sleeps); ?></div>
						</li>
						<li class="clearfixmain">
							<div class="labeltitle">Crew Heads:</div>
							<div class="labelvalue"><?php echo $yachtclass->display_yacht_number_field($crew_heads); ?></div>
						</li>							
					</ul>					
				</div>
			</div>
       		<?php
			}
			?>
       	
       		<?php
			if ($descriptions != ""){
			?>
			<div class="clearfixmain"><a href="javascript:void(0);" ctabid="6" class="customboattab">Descriptions</a></div>
			<div id="ctab6" class="customboattabcontent com_none clearfixmain">
			<?php echo $descriptions; ?>
            <?php echo $display_yachtworld_disclaimer_text; ?>
            </div>
			<?php
			}
			?>        	
        	
         	<?php if ($charter_id == 2 OR $charter_id == 3){
				if ($charter_descriptions != ""){
			?>
         	<div class="clearfixmain"><a href="javascript:void(0);" ctabid="7" class="customboattab">Charter</a></div>
         	<div id="ctab7" class="customboattabcontent com_none clearfixmain"><?php echo $charter_descriptions; ?></div>
         	<?php
				}
			}			
			?>
     		
      		<?php echo $yachtclass->display_yacht_video2($id);?>
             
            <h2 class="singlelinebottom">Location</h2>
        	<div class="customboattabcontent clearfixmain">
            	<div class="maphold map-containerx">
					<div id="map"></div>
				</div>
            </div>
       </div>
        
    </div>

    <div class="right-cell scrollcol" parentdiv="product-detail">
        <section class="section sectionbg3 broker-wrap clearfixmain">
            <?php echo $yachtclass->display_yacht_broker_info($company_id, $location_id, $broker_id, $id, $condition_id); ?>
        </section>
        
        <?php
		$gallery_ar = json_decode($yachtclass->display_yacht_slider_slick_pop($id));
		$pop_slider_text = $gallery_ar->returntxt;
		echo $gallery_ar->gallery_text;
		?>
          
        <?php		
		echo $frontend->display_box_content($column_id);
		?>    
    </div>

</div>

<?php 
echo $similar_yacht_text; 
echo $pop_slider_text;
?>
</div>
<?php
}else{
	//Owned Boat
	$boatpricedisplay = '';
	$boatpricedisplaycharter = '';
	$charterclass = '';
	$pricetoggletext = '';
	if ($charter_id == 1 OR $charter_id == 3){
		if ($price_tag_id > 0){
			$boatpricedisplay = $cm->get_common_field_name('tbl_price_tag', 'name', $price_tag_id);
		}else{
			$boatpricedisplay = "$" . $cm->price_format($price);
		}
		
		
		if ($charter_id == 3){
			$charterclass = " com_none";
			$pricetoggletext = '<span><a href="javascript:void(0);" class="pricetoggle" dateref="charterprice">View Charter Price</a></span>';
		}
		
		$boatpricedisplay = '<div class="boatprice pricediv saleprice">'. $boatpricedisplay . $pricetoggletext .'</div>';
		
	}
	
	if ($charter_id == 2 OR $charter_id == 3){
		$price_per_option_name = $cm->get_common_field_name("tbl_price_per_option", "name", $price_per_option_id);
		
		if ($charter_id == 3){
			$pricetoggletext = '<span><a href="javascript:void(0);" class="pricetoggle" dateref="saleprice">View Sale Price</a></span>';
		}
		
		$boatpricedisplaycharter = '<div class="boatprice pricediv charterprice'. $charterclass .'">$' . $cm->price_format($charter_price) . ' / ' . $price_per_option_name . $pricetoggletext . '</div>';
	}
?>
<?php echo $yachtclass->display_yacht_slider_slick_main($id);?>
<div class="container clearfixmain">

    <div class="product-detail-header clearfixmain">
        <h1 class="title"><?php echo $yacht_title; ?></h1>
        <div class="boataddress"><?php echo $addressfull; ?></div>
        <?php echo $boatpricedisplay; ?>
        <?php echo $boatpricedisplaycharter; ?>
        
        <div class="boatfavcontainer"><?php echo $favtext; ?></div>
        
        <?php echo $yachtclass->yacht_button_set3(array("boat_id" => $id, "listing_no" => $listing_no, "location_id" => $location_id, "broker_id" => $broker_id, "template" => 1)); ?>
    </div>
    
    <div class="boat_single_image clearfixmain"><img class="full" src="<?php echo $cm->folder_for_seo; ?>yachtimage/<?php echo $listing_no; ?>/slider/<?php echo $firstimage; ?>"></div>
        
        
    <div class="product-detail clearfixmain">
        
        <div class="left-cell2 scrollcol2" parentdiv="product-detail">
        	<div class="boattabcontainer clearfixmain">
                <div class="clearfixmain"><a href="javascript:void(0);" ctabid="1" class="customboattab active">Basic Information</a></div>
                <div id="ctab1" class="customboattabcontent clearfixmain">
                    <div class="boattabspecification2 clearfixmain">
                        <ul>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Manufacturer</div>
                                    <div class="labelvalue2"><?php echo $manufacturer_name; ?></div>
                                </li>				
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Model</div>
                                    <div class="labelvalue2"><?php echo $model; ?></div>
                                </li>				
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Year</div>
                                    <div class="labelvalue2"><?php echo $year; ?></div>
                                </li>
                                
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Category</div>
                                    <div class="labelvalue2"><?php echo $category_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Condition</div>
                                    <div class="labelvalue2"><?php echo $condition_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Location</div>
                                    <div class="labelvalue2"><?php echo $addressfull; ?></div>
                                </li>
                                
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Vessel Name</div>
                                    <div class="labelvalue2"><?php echo $vessel_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Boat Type</div>
                                    <div class="labelvalue2"><?php echo $type_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Hull Material</div>
                                    <div class="labelvalue2"><?php echo $hull_material_name; ?></div>
                                </li>
    
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Hull Type</div>
                                    <div class="labelvalue2"><?php echo $hull_type_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Hull Color</div>
                                    <div class="labelvalue2"><?php echo $hull_color; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">HIN:</div>
                                    <div class="labelvalue2"><?php echo $hull_no; ?></div>
                                </li>
    
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Designer:</div>
                                    <div class="labelvalue2"><?php echo $designer; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Flag of Registry</div>
                                    <div class="labelvalue2"><?php echo $flag_country_name; ?></div>
                                </li>
                                <li class="clearfixmain">
                                    <div class="labeltitle2">Available for sale in U.S. waters</div>
                                    <div class="labelvalue2"><?php echo $cm->set_yesyno_field($sale_usa); ?></div>
                                </li>
                            </ul>
                    </div>
                </div>
            </div>
        
        	<?php
			if ($length > 0 OR $loa > 0 OR $beam > 0 OR $draft > 0 OR $bridge_clearance > 0 OR $dry_weight > 0){
			?>
            <div class="boattabcontainer clearfixmain">    
                <div class="clearfixmain"><a href="javascript:void(0);" ctabid="2" class="customboattab">Dimensions & Weight</a></div>
                <div id="ctab2" class="customboattabcontent com_none clearfixmain">
                    <div class="boattabspecification2 clearfixmain">
                        <ul>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Length</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($length, 1); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">LOA:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($loa, 1, 1); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Beam:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($beam, 1, 1); ?></div>
                            </li>
                            
                            <li class="clearfixmain">
                                <div class="labeltitle2">Draft - max:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($draft, 1, 1); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Bridge Clearance:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($bridge_clearance, 1, 1); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Dry Weight:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($dry_weight, 2); ?></div>
                            </li>  				
                        </ul>
                    </div>
                </div>
            </div>    	
       		<?php
			}
			?>
            
            <?php
			if ($engine_make_name != "" OR $engine_model != "" OR $engine_no > 0 OR $hours > 0 OR $cruise_speed > 0 OR $en_range > 0 OR $engine_type_name != "" OR $drive_type_name != "" OR $fuel_type_name != "" OR $max_speed > 0){
			?>
            <div class="boattabcontainer clearfixmain">
                <div class="clearfixmain"><a href="javascript:void(0);" ctabid="3" class="customboattab">Engine</a></div>
                <div id="ctab3" class="customboattabcontent com_none clearfixmain">
                    <div class="boattabspecification2 clearfixmain">
                        <ul>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Make:</div>
                                <div class="labelvalue2"><?php echo $engine_make_name; ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Model:</div>
                                <div class="labelvalue2"><?php echo $engine_model; ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Engine(s):</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($engine_no); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Hours:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($hours); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Cruise Speed:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($cruise_speed, 5); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Range:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($en_range, 7); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Joystick Control:</div>
                                <div class="labelvalue2"><?php echo $cm->set_yesyno_field($joystick_control); ?></div>
                            </li>
                            
                             <li class="clearfixmain">
                                <div class="labeltitle2">Engine Type:</div>
                                <div class="labelvalue2"><?php echo $engine_type_name; ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Drive Type:</div>
                                <div class="labelvalue2"><?php echo $drive_type_name; ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Fuel Type:</div>
                                <div class="labelvalue2"><?php echo $fuel_type_name; ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Horsepower:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_hp($engine_no, $horsepower_individual); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Max Speed:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($max_speed, 5); ?></div>
                            </li>
                            
                            <?php echo $yachtengineclass->display_engine_details($id, 1, 2); ?>				
                        </ul>					
                    </div>
                </div>
            </div>
       		<?php
			}
			?>
            
            <?php
			if (($fuel_tanks > 0 AND $no_fuel_tanks > 0) OR ($fresh_water_tanks > 0 AND $no_fresh_water_tanks > 0) OR ($holding_tanks > 0 AND $no_holding_tanks > 0)){
			?>
            <div class="boattabcontainer clearfixmain">
                <div class="clearfixmain"><a href="javascript:void(0);" ctabid="4" class="customboattab">Tank Capacities</a></div>
                <div id="ctab4" class="customboattabcontent com_none clearfixmain">
                    <div class="boattabspecification2 clearfixmain">
                        <ul>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Fuel Tank:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Fresh Water Tank:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Holding Tank:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_tank_cap($holding_tanks, $no_holding_tanks); ?></div>
                            </li>		
                        </ul>					
                    </div>
                </div>
            </div>    
       		<?php
			}
			?>
            
            <?php
			if ($total_cabins > 0 OR $total_berths > 0 OR $total_sleeps > 0 OR $total_heads > 0 OR $crew_cabins > 0 OR $crew_berths > 0 OR $crew_sleeps > 0 OR $crew_heads > 0){
			?>
            <div class="boattabcontainer clearfixmain">
                <div class="clearfixmain"><a href="javascript:void(0);" ctabid="5" class="customboattab">Accommodations</a></div>
                <div id="ctab5" class="customboattabcontent com_none clearfixmain">
                    <div class="boattabspecification2 clearfixmain">
                        <ul>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Total Cabins:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($total_cabins); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Total Berths:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($total_berths); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Total Sleeps:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($total_sleeps); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Total Heads:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($total_heads); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Captains Cabin:</div>
                                <div class="labelvalue2"><?php echo $cm->set_yesyno_field($captains_cabin); ?></div>
                            </li>
                            
                            <li class="clearfixmain">
                                <div class="labeltitle2">Crew Cabins:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($crew_cabins); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Crew Berths:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($crew_berths); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Crew Sleeps:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($crew_sleeps); ?></div>
                            </li>
                            <li class="clearfixmain">
                                <div class="labeltitle2">Crew Heads:</div>
                                <div class="labelvalue2"><?php echo $yachtclass->display_yacht_number_field($crew_heads); ?></div>
                            </li>							
                        </ul>					
                    </div>
                </div>
            </div>
       		<?php
			}
			?>
        
        </div>
        <div class="center-cell2">
        
            <?php
            if ($overview != ""){
            ?>
            <h2 class="singlelinebottom">Overview</h2>
            <div class="customboattabcontent clearfixmain"><?php echo $overview; ?></div>
            <?php
            }
            ?>
            
            <?php
            if ($descriptions != ""){
            ?>
            <h2 class="singlelinebottom">Descriptions</h2>		
            <div class="customboattabcontent clearfixmain">
			<?php echo $descriptions; ?>
			<?php echo $display_yachtworld_disclaimer_text; ?>
			</div>
            <?php
            }
            ?>
            
            <?php if ($charter_id == 2 OR $charter_id == 3){
				if ($charter_descriptions != ""){
			?>
            <h2 class="singlelinebottom">Charter</h2>
         	<div class="customboattabcontent clearfixmain"><?php echo $charter_descriptions; ?></div>
         	<?php
				}
			}			
			?>
            
            <?php echo $yachtclass->display_yacht_video($id);?>        
        </div> 
        <div class="right-cell2 scrollcol" parentdiv="product-detail">
            <section class="section sectionbg4 broker-wrap clearfixmain">
                <?php echo $yachtclass->display_yacht_broker_info($company_id, $location_id, $broker_id, $id, $condition_id, 1); ?>
            </section>
            
            <?php
            $column_id = 8;
            echo $frontend->display_box_content($column_id);
            ?>
        </div>
    </div>
    
    
    <div class="boatbuttonset4 clearfixmain">
        <ul class="boat_details_page_icons">
            <li><a class="boat_download" href="<?php echo $cm->folder_for_seo;?>?fcapi=createyachtpdf&lno=<?php echo $listing_no; ?>" target="_blank">Download</a></li>
            <li><a class="boat_share" href="javascript:void(0);">Share</a>
                <div class="boat_details_share_icons animated fast fadeInUp">
                    <span>
                        <?php echo $cm->facebook_share_url(array("title" => $yacht_title, "content" => $content, "fullurl" => $fullurl, "template" => 2)); ?>
                        <?php echo $cm->googleplus_share_url(array("title" => $yacht_title, "content" => $content, "fullurl" => $fullurl, "template" => 2)); ?>
                        <?php echo $cm->twitter_share_url(array("title" => $yacht_title, "content" => $twittercontent, "fullurl" => $fullurl, "template" => 2)); ?>
                        <?php echo $cm->linkedin_share_url(array("title" => $yacht_title, "content" => $twittercontent, "fullurl" => $fullurl, "template" => 2)); ?>
                        <?php echo $cm->pinterest_share_url(array("title" => $yacht_title, "listing_no" => $listing_no, "content" => $twittercontent, "image" => $firstimage, "fullurl" => $fullurl, "template" => 2)); ?>
                    </span>
                </div>
            </li>
            <li><a class="boat_friend referfriend" href="<?php echo $cm->folder_for_seo;?>pop-send-email-friend/?lno=<?php echo $listing_no; ?>" title="Email A Friend">Email a Friend</a></li>                    
        </ul>
    </div>
    
    <div class="customboattabcontent clearfixmain">
        <div class="maphold map-containera">
            <div id="map" class="mapgray"></div>
        </div>
    </div>
    
    <?php echo $similar_yacht_text; ?>    
    
</div>






<?php
}
?> 
    
<script language="javascript">
	//map
	var mapCenter; 
	var listingmap;
	var marker;
	
	function listingMap( lat, lng ) {

		google.maps.visualRefresh = true;
		var location = new google.maps.LatLng( lat, lng );

		var mapOptions = {
			zoom				: 9,
			center				: location,
			mapTypeId			: google.maps.MapTypeId.ROADMAP,
			streetViewControl	: true,
			scrollwheel			: false
		};

		listingmap = new google.maps.Map( document.getElementById( 'map' ), mapOptions );
		//google.maps.event.trigger( listingmap, 'resize' );

		marker = new google.maps.Marker({
			map				: listingmap,
			draggable		: false,
			flat			: true,
			position		: location,
			icon			: '<?php echo $cm->folder_for_seo; ?>images/map-marker1.png'
		});

		marker.infoWindow = new InfoBox({
			maxWidth				: 220,
			pixelOffset				: new google.maps.Size( -29, -6 ),
			boxStyle				: {
				width	: '50px',
				height	: '50px'
			},
			alignBottom				: true,
			closeBoxURL				: '',
			enableEventPropagation	: true,
			disableAutoPan			: true,
			zIndex					: 1,
			content					: '<div class="listing-map-label listing-status-for-sale"><img width="100" height="66" src="<?php echo $cm->folder_for_seo; ?>yachtimage/<?php echo $listing_no; ?>/<?php echo $firstimage; ?>" class="listing-thumbnail wp-post-image" alt="" /></div>'
		});

		marker.infoWindow.open( listingmap, marker );
		mapCenter  = listingmap.getCenter();
	}
	
	var lat = <?php echo $lat_val; ?>;
	var lon = <?php echo $lon_val; ?>;
	
	
	$(document).ready(function(){			
		//create local session storage for boat search to track back page search
		sessionStorage.keepsearchsession = 1;
		
		//Map Call
		if(lat == 0 && lon == 0){
				//hide map and subsitute coming soon map
				$('#map').css({ height:0, 'visibility': 'hidden' });
				$(".maphold").append('<div class="no-found-map mapgray"><img src="<?php echo  $cm->site_url; ?>/images/location-map-bg.png"></div>');
				$(".maphold").css({ "height": "auto", "padding-bottom":0, "overflow": "auto" });
		}else{
			listingMap(lat,lon);
			google.maps.event.addDomListener(window, "resize", function() {
				listingmap.setCenter(mapCenter); 
			});
		}
		
		//tab
		$(".main").off("click", ".customboattab").on("click", ".customboattab", function(){
			var ctabid = parseInt($(this).attr("ctabid"));
			$(this).toggleClass("active");
			$("#ctab" + ctabid).toggle();
			$(document.body).trigger("sticky_kit:recalc");
		});
	});
	
</script>
<?php
echo $html_end;
include($bdr."includes/foot.php");
?>