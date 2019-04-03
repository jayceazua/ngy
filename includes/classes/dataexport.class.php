<?php
class Dataexportclass {
	
	public function get_company_data(){	
		global $db;
		$sql = "select * from tbl_company";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$row = (object)$result[0];
			$id = $row->id;
			$slug = $row->slug;
			$parent_id = $row->parent_id;
			$cname = $row->cname;
			$website_url = $row->website_url;
			$logo_imgpath = $row->logo_imgpath;
			$about_company = $row->about_company;
			$enable_custom_label = $row->enable_custom_label;
			$status_id = $row->status_id;
			
			//industry associations 
			$ind_itemar = array();
			$sql_a = "select * from tbl_company_industry_associations order by rank";
			$result_a = $db->fetch_all_array($sql_a);
			foreach($result_a as $row_a){
				$row_a = (object)$row_a;
				$industry_associations_id = $row_a->industry_associations_id;
				$industry_associations_rank = $row_a->rank;
				
				$ind_itemar[] = array(
					'industry_associations_id' => $industry_associations_id,
					'industry_associations_rank' => $industry_associations_rank
				);
			}
			
			$dataarray = array(
				'counter' => 1,
				'company_id' => $id,
				'slug' => $slug,
				'parent_id' => $parent_id,
				'cname' => $cname,
				'website_url' => $website_url,
				'logo_imgpath' => $logo_imgpath,
				'about_company' => $about_company,
				'enable_custom_label' => $enable_custom_label,
				'status_id' => $status_id,
				'industry_associations' => $ind_itemar
			);
		}else{
			$dataarray = array(
				'counter' => 0
			);
		}		
		return $dataarray;
	}
	
	public function get_location_data(){
		global $db;
		$sql = "select * from tbl_location_office order by id";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$itemar = array();
			foreach($result as $row){
				$row = (object)$row;
				$id = $row->id;
				$company_id = $row->company_id;
				$name = $row->name;
				$address = $row->address;
				$city = $row->city;
				$state = $row->state;
				$state_id = $row->state_id;
				$country_id = $row->country_id;
				$zip = $row->zip;
				$phone = $row->phone;
				$fax = $row->fax;
				$lat_val = $row->lat_val;
				$lon_val = $row->lon_val;
				$time_zone_id = $row->time_zone_id;
				$language_id = $row->language_id;
				$currency_id = $row->currency_id;
				$unit_measure_id = $row->unit_measure_id;
				$default_location = $row->default_location;
				$parent_id = $row->parent_id;
				$status_id = $row->status_id;
				$reg_date = $row->reg_date;
				
				$itemar[] = array(
					'location_id' => $id,
					'company_id' => $company_id,
					'name' => $name,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'state_id' => $state_id,
					'country_id' => $country_id,
					'zip' => $zip,
					'phone' => $phone,
					'fax' => $fax,
					'lat_val' => $lat_val,
					'lon_val' => $lon_val,
					'time_zone_id' => $time_zone_id,
					'language_id' => $language_id,
					'currency_id' => $currency_id,
					'unit_measure_id' => $unit_measure_id,
					'default_location' => $default_location,
					'parent_id' => $parent_id,
					'status_id' => $status_id,
					'reg_date' => $reg_date
				);
			}
			
			$dataarray = array(
				'counter' => $found,
				'item' => $itemar
			);
		}else{
			$dataarray = array(
				'counter' => 0
			);
		}
		
		return $dataarray;
	}
	
	public function get_user_data(){
		global $db;
		$sql = "select * from tbl_user order by id";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$itemar = array();
			foreach($result as $row){
				$row = (object)$row;
				$id = $row->id;
				$uid = $row->uid;
				$email = $row->email;
				$title = $row->title;
				$fname = $row->fname;
				$lname = $row->lname;
				$address = $row->address;
				$city = $row->city;
				$state = $row->state;
				$state_id = $row->state_id;
				$country_id = $row->country_id;
				$zip = $row->zip;
				$phone = $row->phone;
				$about_me = $row->about_me;
				$user_imgpath = $row->user_imgpath;
				$status_id = $row->status_id;				
				$user_code = $row->user_code;
				
				$type_id = $row->type_id;
				$company_id = $row->company_id;
				$location_id = $row->location_id;
				$keyterm = $row->keyterm;
				$parent_id = $row->parent_id;
				$reg_date = $row->reg_date;
				$last_login = $row->last_login;							
				
				
				//industry associations 
				$ind_itemar = array();
				$sql_a = "select * from tbl_user_industry_associations where user_id = '". $id ."' order by rank";
				$result_a = $db->fetch_all_array($sql_a);
				foreach($result_a as $row_a){
					$row_a = (object)$row_a;
					$industry_associations_id = $row_a->industry_associations_id;
					$industry_associations_rank = $row_a->rank;
					
					$ind_itemar[] = array(
						'industry_associations_id' => $industry_associations_id,
						'industry_associations_rank' => $industry_associations_rank
					);
				}
				
				//certifications
				$cert_itemar = array();
				$sql_a = "select * from tbl_user_certification where user_id = '". $id ."' order by rank";
				$result_a = $db->fetch_all_array($sql_a);
				foreach($result_a as $row_a){
					$row_a = (object)$row_a;
					$certification_id = $row_a->certification_id;
					$certification_name = $row_a->certification_name;
					$certification_link_url = $row_a->certification_link_url;
					$certification_logo = $row_a->certification_logo;
					
					$cert_itemar[] = array(
						'certification_id' => $certification_id,
						'certification_name' => $certification_name,
						'certification_link_url' => $certification_link_url,
						'certification_logo' => $certification_logo,
					);
				}
				
				$itemar[] = array(
					'user_id' => $id,
					'd_username' => $uid,
					'd_email' => $email,
					'title' => $title,
					'fname' => $fname,
					'lname' => $lname,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'state_id' => $state_id,
					'country_id' => $country_id,
					'zip' => $zip,
					'phone' => $phone,
					'about_me' => $about_me,
					'user_imgpath' => $user_imgpath,
					'status_id' => $status_id,
					'user_code' => $user_code,
					'type_id' => $type_id,
					'company_id' => $company_id,
					'location_id' => $location_id,
					'keyterm' => $keyterm,
					'parent_id' => $parent_id,
					'reg_date' => $reg_date,
					'last_login' => $last_login,
					'industry_associations' => $ind_itemar,
					'certifications' => $cert_itemar
				);
			}
			
			$dataarray = array(
				'counter' => $found,
				'item' => $itemar
			);
		}else{
			$dataarray = array(
				'counter' => 0
			);
		}
		
		return $dataarray;
	}
	
	public function get_boat_data(){
		global $db, $cm;
		$sql = "select * from tbl_yacht order by id";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$k = 0;
			$itemar = array();
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				$itemar[$k] = $row;
				
				$type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $id, 'yacht_id');
				$itemar[$k]["type_id"] = $type_id;
				
				//Dimensions & Weight
				$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				$itemar[$k]["dimensions_wight"] = $row;
				
				
				//Engine
				$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				$itemar[$k]["engine"] = $row;
				
				//Tank Capacities
				$ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				$itemar[$k]["tank"] = $row;
				
				//Accommodations
				$ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				$itemar[$k]["accommodations"] = $row;
				
				//image
				$boat_image = array();
				$im_sql = "select * from tbl_yacht_photo where yacht_id  = '". $cm->filtertext($id) ."' order by rank";
				$im_result = $db->fetch_all_array($im_sql);
				foreach($im_result as $im_row){
					 $imgpath  = $im_row['imgpath'];
					 $im_title = $im_row['im_title'];
					 $im_descriptions = $im_row['im_descriptions'];
					 $im_status_id = $im_row['status_id'];
					 
					 $boat_image[] = array(
						'imgpath' => $imgpath,
						'im_title' => $im_title,
						'im_descriptions' => $im_descriptions,
						'im_status_id' => $im_status_id,
					);
				}
				$itemar[$k]["images"] = $boat_image;
				
				//video
				$boat_video = array();
				$im_sql = "select * from tbl_yacht_video where yacht_id  = '". $cm->filtertext($id) ."' order by rank";
				$im_result = $db->fetch_all_array($im_sql);
				foreach($im_result as $im_row){			
					 $video_type = $im_row['video_type'];                                
					 $name = $im_row['name'];
					 $videopath = $im_row['videopath'];
					 $link_url = $im_row['link_url'];
					 $video_id = $im_row['video_id'];
					 $status_id = $im_row['status_id'];
					 
					 $boat_video[] = array(
						'video_type' => $video_type,
						'video_name' => $name,
						'videopath' => $videopath,
						'link_url' => $link_url,
						'video_id' => $video_id,
						'status_id' => $status_id
					);
				}
				$itemar[$k]["videos"] = $boat_video;
				
				//attachment files
				$boat_attachment = array();
				$im_sql = "select * from tbl_yacht_file where yacht_id  = '". $cm->filtertext($id) ."' order by rank";
				$im_result = $db->fetch_all_array($im_sql);
				foreach($im_result as $im_row){
					 $title = $im_row['title'];
					 $filepath = $im_row['filepath'];
					 $originalname = $im_row['originalname'];
					 $status_id = $im_row['status_id'];
					 
					 $boat_attachment[] = array(
						'title' => $title,
						'filepath' => $filepath,
						'originalname' => $originalname,
						'status_id' => $status_id
					);
				}
				$itemar[$k]["attachmentfiles"] = $boat_attachment;
							
				$k++;
			}			
			
			$dataarray = array(
				'counter' => $found,
				'item' => $itemar
			);
		}else{
			$dataarray = array(
				'counter' => 0
			);
		}
		return $dataarray;
	}
	
	public function create_json_data(){
		if(($_REQUEST['fcapi'] == "importboatdata")){
			global $db, $cm, $yachtclass;
			
			$company_data = $this->get_company_data();
			$location_data = $this->get_location_data();
			$user_data = $this->get_user_data();
			$boat_data = $this->get_boat_data();
			
			$boatdata = array(
				'company_data' => $company_data,
				'location_data' => $location_data,
				'user_data' => $user_data,
				'boat_data' => $boat_data
			);
			
			echo json_encode($boatdata);			
			exit;
		}
	}
}
?>