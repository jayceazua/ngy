<?php
//parent page
$_SESSION["s_locationpage"] = 1;
$pageid = 20;
$parentpagear = $cm->get_table_fields('tbl_page', 'id, name, column_id', $pageid);
$parentpagear = (object)$parentpagear[0];

$startend = 0;
$main_heading = "n";
$googlemap = 0;
$transparentheader = 1;

$locnm = $_REQUEST["locnm"];
$result = $yachtchildclass->check_location_exist($locnm, 1, 0, 0);

$row = $result[0];
foreach($row AS $key => $val){
    /*if ($key != "descriptions"){
		${$key} = htmlspecialchars($val);
	}else{
		${$key} = $cm->filtertextdisplay($val);
	}*/
	${$key} = $cm->filtertextdisplay($val);
}

$addressfull = $yachtclass->com_address_format('', $city, $state, $state_id, $country_id, $zip, 0, 0);

$fax_text = '';
if ($fax != ""){
	$fax_text = '<br><strong>Fax: </strong>'. $fax .'';
}

$externallink = '';
if ($link_title != "" AND $link_url != ""){
	$externallink = '<p><a class="button" href="'. $link_url .'" target="_blank">'. $link_title .'</a></p>';
}

$appointment_time_text = '';
if ($appointment_time != ""){
	$appointment_time = nl2br($appointment_time);
	$appointment_time_text = '
	<h4>Opening Hours</h4>
	'. $appointment_time .'
	';
}

//Map display
if ($lat_val == 0 AND $lon_val == 0){
	$mapdisplay = '
	<div class="clearfixmain">
		<div class="no-found-map"><img alt="Location Map" src="'. $cm->site_url .'/images/location-map-bg.png"></div>
	</div>
	';
}else{	
	if ($lat_val == 0 AND $lon_val == 0){
		$latlonstring = $mapaddress;
	}else{
		$latlonstring = $lat_val . ", " . $lon_val;
	}
	$mapdisplay = '
	<div class="clearfixmain">
		<iframe class="" width="100%" height="300" id="gmap_canvas" src="https://www.google.com/maps/embed/v1/place?key='. $cm->googlemapkey .'&q='. $latlonstring .'&zoom=9" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
	</div>';
}
//end

$breadcrumb = 1;
$breadcrumb_extra[] = array(
            'a_title' => $parentpagear->name,
            'a_link' => $cm->get_page_url($pageid, 'page')
);
$breadcrumb_extra[] = array(
            'a_title' => $name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

$atm1 = $m1;
$atm2 = $m2;
$atm3 = $m3;

if ($atm1 == ""){
	$atm1 = $name;
}

//if ($logo_image == ""){ $logo_image = 'no.jpg'; }

$p = 1;
$retval = json_decode($yachtclass->broker_search_list($p, 1, 2)); // display only broker list
$foundm = $retval[0]->totalrec;
include($bdr."includes/head.php");

if ($logo_image != ""){
?>
<img class="full" src="<?php echo $cm->folder_for_seo; ?>locationimage/<?php echo $logo_image; ?>" alt="<?php echo $name; ?>" />
<?php	
}
?>

<div class="container text_area clearfixmain">
<?php
if (isset($breadcrumb) AND $breadcrumb == 1){
	//echo $frontend->page_brdcmp_array($brdcmp_array);
}
?>
	<div class="leftcontentbox">
		<div class="spacerbottom clearfixmain">
			<div class="homeleft">
				<h2><?php echo $name; ?></h2>
				<div class="clearfixmain">
					<?php echo $address; ?><br>
					<?php echo $addressfull; ?><br><br>
					<strong>Phone: </strong><a class="tel" href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
					<?php echo $fax_text; ?>
				</div>
			</div>
			<div class="homeright"><?php echo $appointment_time_text; ?></div>
		</div>
		
		<div class="locationprofiletab clearfixmain">
			<ul>
				<li><a locationtabid="1" href="javascript:void(0);" class="locationtab active">About</a></li>
				<li><a locationtabid="2" href="javascript:void(0);" class="locationtab">Map</a></li>
				<li><a locationtabid="3" href="javascript:void(0);" class="locationtab">Team</a></li>
			</ul>
		</div>
		
		<div class="locationprofiletabcontentmain clearfixmain">
			<div class="locationprofiletabcontent locationprofiletabcontent1 clearfixmain">
				<?php 
					echo $descriptions; 
					echo $externallink;			
				?>
			</div>
			
			<div class="locationprofiletabcontent locationprofiletabcontent2 com_none clearfixmain">
				<?php echo $mapdisplay; ?>
			</div>
			
			<div class="locationprofiletabcontent locationprofiletabcontent3 com_none clearfixmain">
				<?php
				echo $yachtchildclass->display_our_team(array("user_location_id" => $id));
				?>
			</div>
		</div>		
	</div>
	<div class="rightcontentbox scrollcol" parentdiv="main">
		<section class="section">
		<?php echo $yachtclass->display_company_location_details(array("company_id" => $company_id, "selectedlocation" => $id, "sidebar" => 1)); ?>
		</section>
	</div>
</div>
<script type="text/javascript">	
	$(document).ready(function(){
		$(".main").off("click", ".locationtab").on("click", ".locationtab", function(){			
			var locationtabid = parseInt($(this).attr("locationtabid"));
			
			$(".locationtab").removeClass("active");
			$(this).addClass("active");
			
			$(".locationprofiletabcontent").hide();
			$(".locationprofiletabcontent" + locationtabid).show();			
			$(document.body).trigger("sticky_kit:recalc");
		});
	});
</script>
<?php
include($bdr."includes/foot.php");
?>