<?php
$main_heading = "n";
$top_mini_search = 0;
$googlemap = 1;
$addfav = 1;
$display_boat_disclaimer = 1;
$display_yachtworld_disclaimer = 0;

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
$fullurl = $cm->site_url . $cm->get_page_url($id, "yacht");

if ($yw_id > 0 AND $ownboat == 0){
	$display_boat_disclaimer = 0;
	$display_yachtworld_disclaimer = 1;
}

$addressfull = $yachtclass->get_yacht_address($id);
$type_name = $cm->display_multiplevl($id, 'tbl_yacht_type_assign', 'type_id', 'yacht_id', 'tbl_type');

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
/*$check_favorites = $yachtclass->check_yacht_favorites($id);

if ($check_favorites > 0){
    $addremoveclass = 'remove-fav';
    $check_favorites_txt = 'Your favorite. Remove?';
}else{
    $addremoveclass = 'add-fav';
    $check_favorites_txt = 'Add to favorites';
}*/

$yachtclass->update_yacht_view($id);
$_SESSION["visited_boat"] = $id;

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
		<section class="section sectionbg sectionbgtop price topprice clearfixmain">
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
			<div class="pricediv charterprice<?php echo $charterclass; ?>">
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
			<div class="clear"></div>
		</section>    	
	</div>


    <div class="left-cell">
		<?php echo $yachtclass->display_yacht_slider($id);?>
        <?php echo $yachtclass->loggedin_broker_icon(array("boat_id" => $id, "listing_no" => $listing_no, "location_id" => $location_id, "broker_id" => $broker_id, "is_mobile" => 0)); ?>

        <div class="social righticoncol clearfixmain">
            <ul>            
                <li><a href="<?php echo $cm->folder_for_seo;?>?fcapi=createyachtpdf&lno=<?php echo $listing_no; ?>" title="Create a Brochure" class="boatpdfnormal" data-fancybox-type="iframe"><img src="<?php echo $bdir;?>images/print.png" alt=""></a></li>
                <?php echo $cm->facebook_share_url($yacht_title, $content, $fullurl); ?>
                <?php echo $cm->googleplus_share_url($fullurl); ?>
                <?php echo $cm->twitter_share_url($yacht_title, $twittercontent, $fullurl); ?>
                <?php echo $cm->linkedin_share_url($yacht_title, $content, $fullurl); ?>
                <?php echo $cm->pinterest_share_url($yacht_title, $listing_no, $content, $firstimage, $fullurl); ?>
                <li><a href="<?php echo $cm->folder_for_seo;?>pop-send-email-friend/?lno=<?php echo $listing_no; ?>" title="Email A Friend" class="referfriend" data-fancybox-type="iframe"><img src="<?php echo $bdir;?>images/sendemail.png" alt=""></a></li>                        
            </ul>
        </div>
        <div class="clear"></div>
        <?php echo $yachtclass->yacht_broker_email_phone_button(array("boat_id" => $id, "broker_id" => $broker_id, "is_mobile" => 1)); ?>
    
        
        <div class="customboatviewcontent clearfixmain">
        	<div class="clearfixmain"><a href="javascript:void(0);" ctabid="1" class="customboattab active"><h2 class="borderstyle1">Overview</h2></a></div>
			<div class="customboattabcontent clearfixmain" id="ctab1"><?php echo $overview; ?></div>
      
      		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="2" class="customboattab"><h2 class="borderstyle1">Basic Information</h2></a></div>
       		<div class="customboattabcontent com_none clearfixmain" id="ctab2">
       			<div class="homeleft clearfixmain">
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
								<div class="labeltitle">Available for sale in U.S. waters</div>
								<div class="labelvalue"><?php echo $cm->set_yesyno_field($sale_usa); ?></div>
							</li>
						</ul>
									
					</div>
					
				</div>
				
				<div class="homeright clearfixmain">
					<div class="boattabspecification clearfixmain">
						<ul>
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
						</ul>
					</div>	
				</div>
			</div>
      		
      		<?php
			if ($length > 0 OR $loa > 0 OR $beam > 0 OR $draft > 0 OR $bridge_clearance > 0 OR $dry_weight > 0){
			?>
      		<div class="clearfixmain"><a href="javascript:void(0);" ctabid="3" class="customboattab"><h2 class="borderstyle1">Dimensions &amp; Weight</h2></a></div>
      		<?php
			}
			?>
       		
       </div>
        
    </div>

    <div class="right-cell scrollcol" parentdiv="product-detail">
        <section class="section sectionbg broker-wrap clearfixmain">
            <?php echo $yachtclass->display_yacht_broker_info($company_id, $location_id, $broker_id, $id, $condition_id); ?>
        </section>         
        <?php echo $yachtclass->yacht_featured_small(); ?>        
    </div>

    </div>
    <script>
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
		
		$(document).ready(function(){
			
			$(".main").off("click", ".customboattab").on("click", ".customboattab", function(){
				var ctabid = $(this).attr("ctabid");
				$(this).toggleClass("active");
				$("#ctab" + ctabid).toggle();
			});
			
			var lat = <?php echo $lat_val; ?>;
			var lon = <?php echo $lon_val; ?>;
			
			if(lat == 0 && lon == 0){
					//hide map and subsitute coming soon map
					$('#map').css({ height:0, 'visibility': 'hidden' });
					$(".map-container").append('<div class="no-found-map"><img src="<?php echo  $cm->site_url; ?>/images/location-map-bg.png"></div>');
					$(".map-container").css({ "height": "auto", "padding-bottom":0, "overflow": "auto" });
			}else{
				listingMap(lat,lon);
				google.maps.event.addDomListener(window, "resize", function() {
					listingmap.setCenter(mapCenter); 
				});
			}

			$(".ygdchange").click(function(){
				var dval = $(this).attr("dval");
				$(".ygdchange").removeClass("active");
				$(this).addClass("active");
				
				if (dval == 2){
					$(".product-gallery").addClass("listview");
				}else{
					$(".product-gallery").removeClass("listview");
				}	
				$(document.body).trigger("sticky_kit:recalc");			
			});	
			
			reload_boat_gallery_view();		
			
		});
		
		$(window).resize(function(){
			reload_boat_gallery_view();
		});
		
		function reload_boat_gallery_view(){
			if($(window).width() <= 800){
				$(".ygdchange").removeClass("active");
				$(".ygdchange.list").addClass("active");
				$(".product-gallery").addClass("listview");
			}
		}
    </script>
<?php
echo $html_end;
include($bdr."includes/foot.php");
?>