<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();

$id = round($_REQUEST['id'], 0);
$op = round($_REQUEST['op'], 0);
$addressfull = '';

if ($op == 1){
	$result = $yachtclass->check_company_exist($id, 2, 0, 1);
	$row = $result[0];
	foreach($row AS $key => $val){
		${$key} = htmlspecialchars($val);
	}
	$linkname = $cname;
	
	$com_ad_ar = $yachtclass->get_company_address_array($id);		
	$address = $com_ad_ar["address"];
	$city = $com_ad_ar["city"];
	$state = $com_ad_ar["state"];
	$state_id = $com_ad_ar["state_id"];
	$country_id = $com_ad_ar["country_id"];
	$zip = $com_ad_ar["zip"];
}else{
	$yachtclass->check_user_exist($id, 0, 1);
	$linkname = $yachtclass->yacht_name($yid);
	$broker_ad_ar = $yachtclass->get_broker_address_array($id);		
	$address = $broker_ad_ar["address"];
	$city = $broker_ad_ar["city"];
	$state = $broker_ad_ar["state"];
	$state_id = $broker_ad_ar["state_id"];
	$country_id = $broker_ad_ar["country_id"];
	$zip = $broker_ad_ar["zip"];
}

$addressfull = $yachtclass->com_address_format($address, $city, $state, $state_id, $country_id, $zip);

include("head.php");
?>
<h1><?php echo $linkname; ?></h1>
<h4><?php echo $addressfull; ?></h4>

<div class="map-container"><img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($addressfull);?>&zoom=15&size=750x500&maptype=roadmap&sensor=false&&markers=size:mid%7Ccolor:green%7C<?php echo urlencode($addressfull);?>"></div>
    
<?php
include("foot.php");
?>