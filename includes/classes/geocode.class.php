<?php
class Geocode {
	
	public function getLatLon($check_id, $getoption){
		global $yachtclass, $cm;
				
		//set lat and long array
		$latlon = array();
		
		if ($getoption == 2){
			//location
			$location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone', $check_id);		       
        	$address = $location_ar[0]["address"];
			$city = $location_ar[0]["city"];
			$state = $location_ar[0]["state"];
			$state_id = $location_ar[0]["state_id"];
			$country_id = $location_ar[0]["country_id"];
			$zip = $location_ar[0]["zip"];					
			$addressfull = $yachtclass->com_address_format($address, $city, $state, $state_id, $country_id, $zip);
		}else{
			//yacht address
			$addressfull = $yachtclass->get_yacht_address($check_id, 1);
			
			//pull city, state, zip based on boat id
			$row = $this->yachtLocationInfo($check_id);
			$city = trim($row["city"]);
			$state = trim($row["state"]);
			$zip = trim($row["zip"]);
			
			$yacht_ar = $cm->get_table_fields('tbl_yacht', 'country_id', $check_id);
			$country_id = $yacht_ar[0]['country_id'];
		}
		
		if ($country_id == 1){
		
			//pull lat and long from USPS before checking Google API
			if($zip != ""){
				//pull lat and long from USPS from zip
				$zipLatLong = $this->uspsLatLongPullZip($zip);

				//split out lat and long
				$latLongResults = explode('/',$zipLatLong);
				$latlon["lat"] = $latLongResults[0];
				$latlon["lon"] = $latLongResults[1];
			}elseif($city != "" && $state != ""){
				//pull lat and long from USPS from city and state
				$csLatLong = $this->uspsLatLongPullCS($city,$state);

				//split out lat and long
				$latLongResults = explode('/',$csLatLong);
				$latlon["lat"] = $latLongResults[0];
				$latlon["lon"] = $latLongResults[1];

			}elseif($city != ""){
				//pull lat and long from USPS from city and state
				$csLatLong = $this->uspsLatLongPullC($city);

				//split out lat and long
				$latLongResults = explode('/',$csLatLong);
				$latlon["lat"] = $latLongResults[0];
				$latlon["lon"] = $latLongResults[1];

			}else{
				$latlon["lat"] = 0;
				$latlon["lon"] = 0;	
			}
			
		}else{
			$latlon["lat"] = 0;
			$latlon["lon"] = 0;
		}
		
		//if USPS doesn't have city, state or zip have Google API do the lookup
		if ($latlon["lat"] == 0 && $latlon["lon"] == 0) {	
			
			$prepAddr = str_replace(' ','+',$addressfull);

			$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&key=AIzaSyCA0FeNzWGdbVBHvBr8dszp672DERvZxfU');
			$output= json_decode($geocode);
			$lat = $output->results[0]->geometry->location->lat;
			$long = $output->results[0]->geometry->location->lng;

			/*$prepAddr = str_replace("#", "", $prepAddr);
			$geocode = file_get_contents('https://www.mapquestapi.com/geocoding/v1/address?key=Fmjtd%7Cluu8290a2l%2C2s%3Do5-947lhr&location='.$prepAddr);
			$output = json_decode($geocode);
			$lat = $output->results[0]->locations[0]->latLng->lat;
			$long = $output->results[0]->locations[0]->latLng->lng;*/

			$latlon["lat"] = $lat;
			$latlon["lon"] = $long;
		}
       
        return $latlon;
    }
	
	function uspsLatLongPullCS($city, $state){
		global $db, $cm;
		
		if($city != "" && $state !=""){
			//pull lat and long from USPS table based on city and state
			$sql = "SELECT `latitude`, `longitude` FROM `tbl_usps_towns` WHERE city = '". $cm->filtertext($city) ."' AND state_long = '". $cm->filtertext($state) ."' LIMIT 1";
            $results = $db->fetch_all_array($sql);
            if(count($results) != 0){
				$row = $results[0];
				$lat_lon = $row['latitude'].'/'.$row['longitude'];
			}else{
				$lat_lon = '0/0';
			}	
		}else{
			$lat_lon = '0/0';	
		}
		
		return $lat_lon;		
	}

	function uspsLatLongPullC($city){
			global $db, $cm;

			if($city != ""){
				//pull lat and long from USPS table based on city and state
				$sql = "SELECT `latitude`, `longitude` FROM `tbl_usps_towns` WHERE city LIKE '%". $cm->filtertext($city) ."%' LIMIT 1";
				$results = $db->fetch_all_array($sql);
				if(count($results) != 0){
						$row = $results[0];
						$lat_lon = $row['latitude'].'/'.$row['longitude'];
				}else{
						$lat_lon = '0/0';
				}  
			}else{
					$lat_lon = '0/0';
			}

			return $lat_lon;

	}
	
	function uspsLatLongPullZip($zip){
		global $db, $cm;
		
		if ($zip != ""){
			//pull lat and long from USPS table based on zip
			$sql = "SELECT `latitude`, `longitude` FROM `tbl_usps_towns` WHERE zip = '". $zip ."' LIMIT 1";
            $results = $db->fetch_all_array($sql);
			if(count($results) != 0){
					$row = $results[0];
					$lat_lon = $row['latitude'].'/'.$row['longitude'];
			}else{
					$lat_lon = '0/0';
			}
	
		}else{
			$lat_lon = '0/0';
		
		}
		
		return $lat_lon;
		
	}
	
	public function yachtLocationInfo($yacht_id){
		global $cm,$db;
		
        $sql = "select a.city, b.name AS state, a.zip from tbl_yacht a LEFT JOIN tbl_state b ON a.state_id=b.id WHERE a.id = ".$cm->filtertext($yacht_id);
		$results = $db->fetch_all_array($sql);
		$row = $results[0];
		
        return $row;
  }
	
}
?>