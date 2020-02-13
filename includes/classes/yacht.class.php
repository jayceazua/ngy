<?php
class Yachtclass {
    //variables
    public $listing_start = 1000;
    public $y_start_year = 1913;
    public $y_end_year = 0;
    public $ft_to_meter = 0.3048;
	public $ft_to_inchs = 12;
	public $mph_to_kts = 0.869;
    public $max_engine = 4;
	public $list_filter_small = 10;
	public $catamaran_id = 9;
	public $centerconsole_id = 10;
	public $sportfishing_id = 33;
	public $convertible_id = 62;
	public $motoryacht_id = 26;
	public $yacht_feed_id = "iqfvvoqavhlo";
	public $catamaran_feed_id = "90sxtvkwmutb";
	public $catamaran_feed_id2 = "9695221a8b884112b1a4dd39fbaf3c";
	public $mostviewdno = 30;
	public $mostviewdday = 30;
    //end

    public function __construct() {
        $this->y_end_year = date("Y");
		$currentmonth = date("m");
		if ($currentmonth >= 5){
			$this->y_end_year++;
		}
    }
	
	//common
	public function delete_any_files($fimg1, $fpath){
        global $fle;
        if ($fimg1 != ""){
            $fle->filedelete("../". $fpath ."/" . $fimg1);
        }
    }
	
	public function display_smart_keyword_selection($ms, $chkoption, $tablewidth = '100%'){
		global $db, $cm;
		
		if ($chkoption == "resourcemanufacturer"){
          $maintbl = "tbl_manufacturer";
          $checktbl = "tbl_resource_manufacturer";
          $checkfield = "manufacturer_id";
          $mainfield = "resource_id";
          $textbox_name = "key_name";
          $ckpage = 1; 
          $heading_cap = "Manufacturers";
      }
	  
	  $k_sql = "select distinct a.id, a.name from ". $maintbl ." as a, ". $checktbl ." as b where a.id = b.". $checkfield ." and b.". $mainfield." = '". $ms ."' and a.status_id = 1 order by a.name";
      $k_result = $db->fetch_all_array($k_sql);
      $k_found = count($k_result);
	?>
    	<table width="<?php echo $tablewidth; ?>" align="center" border="0" cellspacing="0" cellpadding="0">
        	<tr>
                <td>
                <div class="keywords">
                	<input type="text" whadd="1" ckpage="<?php echo $ckpage; ?>" id="<?php echo $textbox_name; ?>" name="<?php echo $textbox_name; ?>" class="azax_suggest azax_suggest1 inputbox inputbox_size4" autocomplete="off" />
                    <div id="suggestsearch1" class="suggestsearch com_none"></div>
                    <input fieldnm="<?php echo $textbox_name; ?>" type="button" class="portkey_add submit" value="Add" />
                </div>
                </td>
             </tr>
             
             <tr>
                <td>                            
                <em>Separate <?php echo $heading_cap; ?> with commas</em>        
                <div class="separate-keywords">
                <?php
				if ($k_found > 0){
                $name_added = ',';
				echo '<ul>';
                foreach($k_result as $k_row){
                    $k_id = $k_row["id"];
                    $k_name = $k_row["name"];
                    $name_added .= $k_name . ',';
                ?>    
                <li><a href="javascript:void(0);" class="k_dellink" fieldnm="<?php echo $textbox_name; ?>" vname="<?php echo $k_name;?>"><?php echo $k_name;?></a></li> 
                <?php } 
					echo '</ul>';				
				}
				?>
                </div>
                <input type="hidden" id="<?php echo $textbox_name; ?>_added" name="<?php echo $textbox_name; ?>_added" value="<?php echo $name_added; ?>" />
                </td>
              </tr>
        </table>
    <?php	  
	}
	
	public function get_total_manufacturer(){
	  global $db;
	  $total_manufacturer = $db->total_record_count("select count(*) as ttl from tbl_manufacturer where status_id = 1");
	  return $total_manufacturer;
    }
	
	//company
	public function get_all_broker_combo($broker_id){
        global $db;
        $returntxt = '';
        $vsql = "select id, fname, lname from tbl_user where status_id = 2 and support_crew = 0 order by fname";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['fname'] . ' ' . $vrow['lname'];	
			$bck = '';
			if ($broker_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function total_company_broker($company_id, $status_id = 0){
		global $db;
		$sqltext = "select count(*) as ttl from tbl_user where company_id = '". $company_id ."'";
		if ($status_id > 0){
			$sqltext .= " and status_id = '". $status_id ."'";
		}
		$totalbroker = $db->total_record_count($sqltext);
		return $totalbroker;		
	}
	
	public function total_company_location($company_id, $status_id = 0){
		global $db;
		$sqltext = "select count(*) as ttl from tbl_location_office where company_id = '". $company_id ."'";
		if ($status_id > 0){
			$sqltext .= " and status_id = '". $status_id ."'";
		}
		$totallocation = $db->total_record_count($sqltext);
		return $totallocation;		
	}
	
	public function total_location_broker($location_id, $status_id = 0){
		global $db;
		$sqltext = "select count(*) as ttl from tbl_user where location_id = '". $location_id ."'";
		if ($status_id > 0){
			$sqltext .= " and status_id = '". $status_id ."'";
		}
		$totalbroker = $db->total_record_count($sqltext);
		return $totalbroker;		
	}	
	
	public function edit_company_profile($frontfrom = 0){
		global $db, $cm, $fle;		
		$cname = $_POST["cname"];
		$website_url = $_POST["website_url"];
		$about_company = $_POST["about_company"];		
		
		if ($frontfrom == 0){
			//backend
			$ms = round($_POST["ms"], 0);
			$enable_custom_label = round($_POST["enable_custom_label"], 0);	
			$status_id = round($_POST["status_id"], 0);	
			$old_status_id = $cm->get_common_field_name('tbl_company', 'status_id', $ms);		
		}else{
			//frontend
			$logmember = $this->loggedin_member_id();
			$ms = $this->get_broker_company_id($logmember);
			$status_id = $old_status_id = $cm->get_common_field_name('tbl_company', 'status_id', $ms);
			$enable_custom_label = $cm->get_common_field_name('tbl_company', 'enable_custom_label', $ms);
		}
		
		$website_url = $cm->format_url_txt($website_url);
		$iiid = $ms;
		$slug = $cm->create_slug($cname);
				
		$sql = "update tbl_company set slug = '". $cm->filtertext($slug) ."'
		, cname = '". $cm->filtertext($cname) ."'
        , website_url = '". $cm->filtertext($website_url) ."'
        , about_company = '". $cm->filtertext($about_company) ."'
		, enable_custom_label = '". $enable_custom_label ."'
        , status_id = '". $status_id ."'  where id = '". $iiid ."'";
        $db->mysqlquery($sql);
		
		//Industry Association assign
		$this->industry_associations_assign($iiid, 1);
		//end
		
		//logo image upload
        $filename = $_FILES['logo_imgpath']['name'] ;
        if ($filename != ""){
            $wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
            if ($wh_ok == "y"){
                $filename_tmp = $_FILES['logo_imgpath']['tmp_name'];
                $filename = $fle->uploadfilename($filename);
                $filename1 = $iiid."logo".$filename;

                $target_path_main = "userphoto/";
                if ($frontfrom == 0){
                    $target_path_main = "../" . $target_path_main;
                }

                //image
                $r_width = $cm->logo_im_width;
                $r_height = $cm->logo_im_height;
                $target_path = $target_path_main;
                $fle->new_image($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

                $fle->filedelete($filename_tmp);
                $sql = "update tbl_company set logo_imgpath = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
                $db->mysqlquery($sql);
            }
        }
        //end
		
		if ($status_id != $old_status_id){
			if ($status_id == 1){
				//active
				$this->update_location_status(1, 'company_id', $iiid, 2);
				$this->update_user_status(2, 'company_id', $iiid, 3);
				$this->update_yacht_status(1, 'company_id', $iiid, 2);
			}
			
			if ($status_id == 2){
				//inactive
				$this->update_location_status(2, 'company_id', $iiid, 1);
				$this->update_user_status(3, 'company_id', $iiid, 2);
				$this->update_yacht_status(2, 'company_id', $iiid, 1);
			}			
		}
		
		if ($frontfrom == 1){
			global $frontend;
			$_SESSION["thnk"] = $frontend->display_message(29);
			header('Location: '. $cm->site_url .'/thankyou/');
			exit;
		}
	}
	
	public function submit_company_form(){
		if(($_POST['fcapi'] == "companysubmit")){
			$this->check_user_permission(array(2, 3));
			$this->edit_company_profile(1);
			exit;
		}
	}
	
	public function location_insert_update($frontfrom = 0){
		global $db, $cm, $geo, $fle;
		$loggedin_member_id = $this->loggedin_member_id();
		$company_id = round($_POST["company_id"], 0);
		$ms = round($_POST["ms"], 0);
		
		$name = $_POST["name"];
		$located_at = $_POST["located_at"];
		$address = $_POST["address"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $state_id = round($_POST["state_id"], 0);
        $country_id = round($_POST["country_id"], 0);
        $zip = $_POST["zip"];
        $phone = $_POST["phone"];
		$fax = $_POST["fax"];
		$appointment_time = $_POST["appointment_time"];
		$descriptions = $_POST["descriptions"];
		
		$time_zone_id = round($_POST["time_zone_id"], 0);
		$language_id = round($_POST["language_id"], 0);
		$currency_id = round($_POST["currency_id"], 0);
		$unit_measure_id = round($_POST["unit_measure_id"], 0);
		$default_location = round($_POST["default_location"], 0);
		$status_id = round($_POST["status_id"], 0);
		$lat_lon_manual = round($_POST["lat_lon_manual"], 0);
		
		$link_title = $_POST["link_title"];
		$link_url = $_POST["link_url"];
		$link_url = $cm->format_url_txt($link_url);
		
		$oldrank = round($_POST["oldrank"], 0);
		
		$m1 = $_POST["m1"];
		$m2 = $_POST["m2"];
		$m3 = $_POST["m3"];
		
		if ($default_location != 1){ $default_location = 0; }
		if ($country_id == 1){
            $state = "";
        }else{
            $state_id = 0;
        }
		
		$sesinitial = '';
		if ($frontfrom == 0){
			//backend
			$yw_broker_id = round($_POST["yw_broker_id"], 0);
			$red_pg = "add_location.php?cid=". $company_id ."&id=" . $ms;			
		}else{
			//frontend
			$email2 = $_POST["email2"];
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			
			$company_id = $this->get_broker_company_id($loggedin_member_id);
			$sesinitial = 'fr_';
			
			if ($ms > 0){
				$yw_broker_id = $cm->get_common_field_name('tbl_location_office', 'yw_broker_id', $ms);	
			}			
		}
		

        $datastring = '';
        if ($ms == 0){
            //create session for the posted data
            $datastring .= $cm->session_field_user();
            $cm->session_field_location($datastring, $_POST);
        }
		
		$tblnm = "tbl_location_office";
		$cm->field_validation($name, '', 'Office Name', $red_pg, '', '', 1, $sesinitial);
		$cm->field_validation($address, '', 'Address', $red_pg, '', '', 1, $sesinitial);
		$cm->field_validation($city, '', 'City', $red_pg, '', '', 1, $sesinitial);
		if ($country_id == 1){
			$cm->field_validation($state_id, '', 'State', $red_pg, '', '', 2, $sesinitial);
		}else{
			$cm->field_validation($state, '', 'County/Sate', $red_pg, '', '', 1, $sesinitial);
		}
		$cm->field_validation($zip, '', 'Postal Code', $red_pg, '', '', 1, $sesinitial);
		$cm->field_validation($phone, '', 'Phone Number', $red_pg, '', '', 1, $sesinitial);
		
		if ($link_title != "" AND $link_url != ""){
			$cm->field_validation($link_title, '', 'Link Text', $red_pg, '', '', 1, $sesinitial);
			$cm->field_validation($link_url, '', 'Link URL', $red_pg, '', '', 1, $sesinitial);
		}
		
		$cm->field_validation($currency_id, '', 'Currency', $red_pg, '', '', 2, $sesinitial);
		$cm->field_validation($unit_measure_id, '', 'Currency', $red_pg, '', '', 2, $sesinitial);
		$cm->field_validation($status_id, '', 'Unit of Measure', $red_pg, '', '', 2, $sesinitial);
		
		$old_status_id = $cm->get_common_field_name('tbl_company', 'status_id', $ms);
		if ($default_location == 1){
			$sql = "update tbl_location_office set default_location = 0 where company_id = '". $company_id ."'";
            $db->mysqlquery($sql);
		}
		
		$dt = date("Y-m-d H:i:s");		
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_location_office") + 1;
			$sql = "insert into tbl_location_office (company_id, reg_date) values ('". $company_id ."', '". $dt ."')";
            $iiid = $db->mysqlquery_ret($sql);
            $cm->session_field_location($datastring);			
		}else{
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_location_office set company_id = '". $company_id ."' where id = '". $ms ."'";
            $db->mysqlquery($sql);
            $iiid = $ms;			
		}
		
		// common update
		$slug = $cm->create_slug($name);
		$sql = "update tbl_location_office set name = '". $cm->filtertext($name) ."'        
        , located_at = '". $cm->filtertext($located_at) ."'
		, address = '". $cm->filtertext($address) ."'
        , city = '". $cm->filtertext($city) ."'
        , state = '". $cm->filtertext($state) ."'
        , state_id = '". $state_id ."'
        , country_id = '". $country_id ."'
        , zip = '". $cm->filtertext($zip) ."'
        , phone = '". $cm->filtertext($phone) ."'
		, fax = '". $cm->filtertext($fax) ."'
		, appointment_time = '". $cm->filtertext($appointment_time)."'
		, descriptions = '". $cm->filtertext($descriptions)."'
		
		, time_zone_id = '". $time_zone_id ."'
		, language_id = '". $language_id ."'
		, currency_id = '". $currency_id ."'
		, unit_measure_id = '". $unit_measure_id ."'
		, default_location = '". $default_location ."'
		
		, link_title = '". $cm->filtertext($link_title)."'
		, link_url = '". $cm->filtertext($link_url)."'
		
        , status_id = '". $status_id ."'
		, rank = '".$rank."'
		, slug = '". $cm->filtertext($slug) ."'
		, yw_broker_id = '". $cm->filtertext($yw_broker_id) ."'
		, m1 = '". $cm->filtertext($m1) ."'
		, m2 = '". $cm->filtertext($m2) ."'
		, m3 = '". $cm->filtertext($m3) ."' where id = '". $iiid ."'";
        $db->mysqlquery($sql);
		
		if ($lat_lon_manual == 1){
			$lat = $_POST["lat_val"];
			$lon = $_POST["lon_val"];
		}else{		
			$latlonar = $geo->getLatLon($iiid, 2);
			$lat = $latlonar["lat"];
			$lon = $latlonar["lon"];
		}
				
		$sql = "update tbl_location_office set lat_val = '". $cm->filtertext($lat)."'
		, lon_val = '". $cm->filtertext($lon)."' where id = '". $iiid ."'";
        $db->mysqlquery($sql);		
        // end
		
		//update keyterm
		$keyterm = $this->com_address_format($address, $city, $state, $state_id, $country_id, $zip);
		if ($default_location == 1){	
			//master broker admin and company manager
			$sql = "update tbl_user set keyterm = '". $cm->filtertext($keyterm) ."' where company_id = '". $company_id ."' and (type_id = 2 OR type_id = 3)";
			$db->mysqlquery($sql);
		}else{
			//Location Admin and agent
			$sql = "update tbl_user set keyterm = '". $cm->filtertext($keyterm) ."' where company_id = '". $company_id ."' and (type_id = 4 OR type_id = 5)";
			$db->mysqlquery($sql);
		}		
		//end
		
		//image upload
		$filename = $_FILES['logo_image']['name'];
		if ($filename != ""){
            $wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
            if ($wh_ok == "y"){
                $filename_tmp = $_FILES['logo_image']['tmp_name'];
                $filename = $fle->uploadfilename($filename);
                $filename1 = $iiid."locationimage".$filename;

                $target_path_main = "locationimage/";
                if ($frontfrom == 0){
                    $target_path_main = "../" . $target_path_main;
                }
            
                //big image
                $r_width = $cm->location_im_width;
                $r_height = $cm->location_im_height;
                $target_path = $target_path_main;
                $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

                $fle->filedelete($filename_tmp);
                $sql = "update tbl_location_office set logo_image = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
                $db->mysqlquery($sql);
            }
        }
		
		
		//status update
		if ($status_id != $old_status_id){
			if ($status_id == 1){
				//active		
				$this->update_user_status(2, 'location_id', $iiid, 3);
				$this->update_yacht_status(1, 'location_id', $iiid, 2);
			}
			
			if ($status_id == 2){
				//inactive		
				$this->update_user_status(3, 'location_id', $iiid, 2);
				$this->update_yacht_status(2, 'location_id', $iiid, 1);
			}			
		}
		
		if ($frontfrom == 1){
			global $frontend;
			if ($ms > 0){
				//update
				$_SESSION["thnk"] = $frontend->display_message(31);
			}else{
				//insert
				$_SESSION["thnk"] = $frontend->display_message(30);
			}
			header('Location: '. $cm->site_url .'/thankyou/');
			exit;
		}				
	}
	
	public function submit_office_location_form(){
		if(($_POST['fcapi'] == "locationsubmit")){
			$this->check_user_permission(array(2, 3));
			$mbid= round($_POST["ms"], 0);
			$this->can_access_location($mbid);
			$this->location_insert_update(1);
			exit;
		}
	}
	
	public function delete_company($company_id){
        global $db;
        
		$sql = "select id from tbl_user where company_id = '". $company_id ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$userid = $row["id"];
			$this->delete_user($userid);
		}
		
		$fimg1 = $db->total_record_count("select logo_imgpath as ttl from tbl_company where id = '". $company_id ."'");
		if ($fimg1 != ""){
			$fle->filedelete("../userphoto/".$fimg1);
		}
		
		$sql = "select id from tbl_yacht where company_id = '". $company_id ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$yid = $row["id"];
			$this->delete_yacht($yid);
		}
		
		$sql = "delete from tbl_company where id = '". $company_id ."'";
		$db->mysqlquery($sql);
		
		$sql = "delete from tbl_location_office where company_id = '". $company_id ."'";
		$db->mysqlquery($sql);        
    }
	
	public function delete_companylocation($location_id){
        global $db, $fle;
        
		$sql = "select id from tbl_user where location_id = '". $location_id ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$userid = $row["id"];
			$this->delete_user($userid);
		}
		
		$sql = "select id from tbl_yacht where location_id = '". $location_id ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$yid = $row["id"];
			$this->delete_yacht($yid);
		}
		
		$fimg1 = $db->total_record_count("select logo_image as ttl from tbl_location_office where id = '". $location_id ."'");
		if ($fimg1 != ""){
			$fle->filedelete("../locationimage/".$fimg1);
		}
		
		$sql = "delete from tbl_location_office where id = '". $location_id ."'";
		$db->mysqlquery($sql);        
    }
	
	public function check_company_exist($checkvalue, $optn = 1, $adminfrom = 0, $frompopup = 0){
		global $db, $cm;
		if ($optn == 1){
            $sql = "select * from tbl_company where slug = '". $cm->filtertext($checkvalue) ."'";
        }else{
            $sql = "select * from tbl_company where id = '". $cm->filtertext($checkvalue) ."'";
        }
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found == 0){
			if ($adminfrom == 1){
				$_SESSION["admin_sorry"] = "ERROR! Invalid company.";
				header('Location: sorry.php');
				exit;				
			}else{
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(15);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;				
			}	
        }
        return $result;		
	}
	
	public function update_location_status($status_id, $field_name, $field_value, $filter_value){
		global $db;		
		$sql = "update tbl_location_office set status_id = '". $status_id ."' where ". $field_name ." = '". $field_value ."' and status_id = '". $filter_value ."'";
		$db->mysqlquery($sql);		
	}
	
	public function update_user_status($status_id, $field_name, $field_value, $filter_value){
		global $db;		
		$sql = "update tbl_user set status_id = '". $status_id ."' where ". $field_name ." = '". $field_value ."' and status_id = '". $filter_value ."'";
		$db->mysqlquery($sql);		
	}
	
	public function update_yacht_status($status_id, $field_name, $field_value, $filter_value){
		global $db;		
		$sql = "update tbl_yacht set status_id = '". $status_id ."' where ". $field_name ." = '". $field_value ."' and status_id = '". $filter_value ."'";
		$db->mysqlquery($sql);		
	}
	
	public function get_company_default_location($company_id){
		global $db;
		$sqltext = "select id as ttl from tbl_location_office where company_id = '". $company_id ."' and default_location = 1";
		$def_location_id = $db->total_record_count($sqltext);
		return $def_location_id;
	}
	
	public function get_company_address_array($company_id, $location_id = 0){
        global $cm;	
		if ($location_id == 0){
			$location_id = $this->get_company_default_location($company_id);
		}		
		$location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone, lat_val, lon_val', $location_id);		       
        return $location_ar[0];
    }
	
	public function display_company_location_details($argu = array()){
		global $db, $cm;
		
		//param
		$company_id = round($argu["company_id"], 0);
		$mapinclude = round($argu["mapinclude"], 0);
		$sidebar = round($argu["sidebar"], 0);
		//end
		
		if ($company_id == 0){ $company_id = 1; }
		
		$phone_tollfree_text = '';
		$phone_tollfree = $cm->get_systemvar('STPH3');
		if ($phone_tollfree != "#"){
			$phone_tollfree_text = '<div class="locphone"><a class="tel" href="tel:'. $phone_tollfree .'">'. $phone_tollfree .' (Toll Free)</a></div>';
		}
		
		$returntext = '';
		$sql = "select * from tbl_location_office where company_id = '". $company_id ."' and status_id = 1 order by default_location desc, rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			
			if ($sidebar == 1){
				$returntext .= '
				<h3>Our Locations :</h3>
				<div class="locationholdersidebar clearfixmain">
				';
			}else{
				$returntext .= '<div class="locationholder clearfixmain">';
			}
			
			$returntext .= '<ul class="locationlist">';
			
			foreach($result as $row){				
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
				$location_page_url = $cm->get_page_url($slug, 'locationprofile');
				
				$located_at_text = '';
				if ($located_at != ""){
					$located_at_text = '<div class="locaddress">Located @ '. $located_at .'</div>';	
				}
				
				$fax_text = '';
				if ($fax != ""){
					$fax_text = '<div class="locfax"><a class="tel" href="tel:'. $fax .'">'. $fax .'</a></div>';
				}
				
				$maptext = '';
				if ($mapinclude == 1){
					$mapaddress = $address . ', ' . $addressfull;
					$maptext = '<div class="locationmapwrapper"><iframe width="100%" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q='. $mapaddress .'&key='. $cm->googlemapkey .'" allowfullscreen></iframe></div>';
				}
						
				$returntext .= '
				<li>
					<h4><a href="'. $location_page_url .'">'. $name .'</a></h4>
					'. $located_at_text .'
					<div class="locaddress">'. $address .'</div>
					<div class="locaddress">'. $addressfull .'</div>
					<div class="locphone"><a class="tel" href="tel:'. $phone .'">'. $phone .'</a></div>
					'. $fax_text .'
					'. $phone_tollfree_text .'
					'. $maptext .'
				</li>
				';
			}
			$returntext .= '</ul></div>';
		}		
		return $returntext;
	}
	
	public function display_company_location_details_new($company_id){
		global $db, $cm;
		$returntext = '';
		$sql = "select * from tbl_location_office where company_id = '". $company_id ."' and status_id = 1 order by default_location desc, rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$returntext .= '
			<ul class="locationlist">
			';
			foreach($result as $row){
				
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
				$location_page_url = $cm->get_page_url($slug, 'locationprofile');
				$returntext .= '
				<li>
					<h3>'. $name .'</h3>
					'. $address .'<br />
					'. $addressfull .'<br />
					'. $phone .'
				</li>
				';
			}
			$returntext .= '
			</ul>
			<div class="clearfix"></div>
			';
		}
		
		return $returntext;
	}
	
	//location map view	
	public function display_company_location_details_with_map($argu = array()){
		global $db, $cm;
		
		$location_details = $this->display_company_location_details($argu);
		$location_map = $this->display_location_map_view($argu);
		
		$returntext = '
		<div class="pagecolumnmain clearfixmain">
			<div class="left-cell-half">'. $location_details .'</div>
			<div class="right-cell-half">
				<div class="mapholdercustom clearfixmain">'. $location_map .'</div>
			</div>
		</div>
		';
		
		return $returntext;
	}
	
	//display location - footer
	public function display_company_location_details_footer($param = array()){
		global $db, $cm;
		$returntext = '';
		$startcontainer = '';
		$endcontainer = '';
		
		//param
		$outsidefooter = round($param["outsidefooter"]);
		//end
		
		if ($outsidefooter == 1){
			$companyname = $cm->get_systemvar('COMNM');
			$startcontainer = '
			<div class="footerlocation clearfixmain">
			<div class="container clearfixmain">
			<div class="footer_logo"><img src="'. $cm->folder_for_seo .'images/logo.png" alt="'. $companyname .'" title="'. $companyname .'" /></div>
			';
			
			$endcontainer = '
			</div>
			</div>
			';
		}
		
		$sql = "select * from tbl_location_office where company_id = 1 and status_id = 1 order by default_location desc, rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){			
			
			$returntext .= $startcontainer . '
			<ul class="footerlocationlist">
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay(($val));
				}
				
				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
				$location_page_url = $cm->get_page_url($slug, 'locationprofile');
				
				$faxtext = '';
				if ($fax != ""){
					$faxtext = '<div class="locationphone">Fax: <span>'. $fax .'</span></div>';
				}
				
				$located_at_text = '';
				if ($located_at != ""){
					$located_at_text = '<br>at ' . $located_at;
				}
				
				$appointment_time_text = '';
				if ($appointment_time != ""){
					$appointment_time_text = '<div class="appointmenttime">'. nl2br($appointment_time) .'</div>';
				}
				
				$returntext .= '<li>
				<h3>'. $name .'</h3>
				<div class="locationaddress">'. $address .'</div>
				<div class="locationaddress">'. $addressfull .'</div>
				<div class="locationphone firstrow">T: <span>'. $phone .'</span>'. $located_at_text .'</div>
				'. $faxtext .'
				'. $appointment_time_text .'
				</li>';				
			}
			
			$returntext .= '
			</ul>
			' . $endcontainer;			
		}
		
		return $returntext;
	}
	//end

    //user section
    public function check_backend_admin_login(){
        if (!isset($_SESSION["asuc"]) OR $_SESSION["asuc"] != "true"){
            $returnval[] = array(
                'retval' => 'n'
            );
            echo json_encode($returnval);
            exit;
        }
    }

    public function set_remember_login($log_remember_me, $t1, $t2){
        global $cm;
        if ($log_remember_me == "y"){
            $expiretime = time() + 2592000;
            setcookie("cookie_user_id", $t1, $expiretime, $cm->folder_for_seo);
            setcookie("cookie_user_password", $t2, $expiretime, $cm->folder_for_seo);
        }else{
            $expiretime = time() - 3600;
            setcookie ("cookie_user_id", "", $expiretime);
            setcookie ("cookie_user_password", "", $expiretime);
        }
    }

    public function user_login($loginoption = 0, $adminfrom = 0, $frompopup = 0){
        global $db, $cm, $edclass;
        $t1 = $_POST["t1"];
        $t2 = $_POST["t2"];
        $cd = $_REQUEST["cd"];
        if ($frompopup == 1){
            $chkid = round($_POST["chkid"], 0);
            $popopt = round($_POST["popopt"], 0);
        }
        if ($loginoption == 1){
            $sql = "select id, uid, fname, type_id, admin_access from tbl_user where user_code = '" . $cm->filtertext($cd) . "' and status_id = 1";
        }else{
            $sql = "select id, uid, fname, type_id, admin_access from tbl_user where uid = '" . $cm->filtertext($t1) . "' and pwd = '". $cm->filtertext($edclass->txt_encode($t2)) ."' and status_id = 2";
        }
        if ($adminfrom == 1){
            //$sql .= " and type_id = 2";
			$sql .= " and admin_access = 1";
        }	
	
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
            $row = $result[0];
            $_SESSION["usernid"] = $row['id'];
            $_SESSION["cr_uid"] = $row['uid'];
            $_SESSION["cr_user_name"] = $row['fname'];
            $_SESSION["cr_type_id"] = $row['type_id'];
			$_SESSION["cr_admin_access"] = $row['admin_access'];
            $_SESSION["suc"] = "true";
            //if ($_SESSION["cr_type_id"] == 2){
			if ($_SESSION["cr_admin_access"] == 1){	
                $_SESSION["asuc"] = "true";
                $_SESSION["sesid"] = session_id();
            }

            if ($adminfrom == 1){

                $remember_me = $_POST["remember_me"];
                if ($remember_me == "y"){
                    setcookie("cookie_member_id",$_SESSION['adminid'],time()+2592000);
                }else{
                    setcookie ("cookie_member_id","", time()-3600);
                }

                $currenttimecollect = time();
                $diff = $currenttimecollect - (24 * 3600);

                $sql = "delete from tbl_session where ses_en <= '". $diff ."'";
                $db->mysqlquery($sql);
                header('Location: adminhome.php');

            }else{

                if ($loginoption == 1){
                    global $frontend;
                    $sql = "update tbl_user set status_id = 2 where user_code = '" . $cm->filtertext($cd) . "'";
                    $db->mysqlquery($sql);

                    $this->send_user_email($_SESSION["usernid"], 2);
                    $_SESSION["thnk"] = $frontend->display_message(5, 'index.php');
 					$gotopage = $cm->get_page_url('', 'thankyou');
					header('Location: '. $gotopage);
                }else{
                    $log_remember_me = $_POST["log_remember_me"];
                    $this->set_remember_login($log_remember_me, $_SESSION["cr_uid"], $row['pwd']);

                    if ($frompopup == 1){
                        if ($chkid > 0){
                            $this->user_yacht_favorites($chkid, 1, 1);
                            global $frontend;
                            $_SESSION["thnk"] = $frontend->display_message(17);
                            header('Location: '. $cm->folder_for_seo .'popthankyou/?r=1');
                        }

                    }else{
                        $redirect_url = $_SESSION["file_name"];
	                    $_SESSION["file_name"] = '';
                        if ($redirect_url == ''){
                            $redirect_url = $cm->folder_for_seo .'dashboard/';
                        }
                        header('Location: '. $redirect_url);
                    }
                }
            }

        }else{
            if ($adminfrom == 1){

                $_SESSION["pass"] = "ww";
                header('Location: index.php');

            }else{

                if ($loginoption == 1){
                    global $frontend;
                    $_SESSION["ob"] = $frontend->display_message(6);
                    header('Location: '. $cm->folder_for_seo .'sorry/');
                }else{
                    $_SESSION["fr_postmessage"] = "wwr";
                    if ($frompopup == 1){
                        header('Location: '. $cm->folder_for_seo .'pop-login/?chkid=' . $chkid . '&popopt=' . $popopt);
                    }else{
                        header('Location: '. $cm->folder_for_seo .'login/');
                    }
                }
            }
        }
    }	

    public function user_insert_update($frontfrom = 0){
        global $db, $cm, $edclass, $fle;
        $d_username = $_POST["d_username"];
        $d_email = $_POST["d_email"];
        $d_password = $_POST["d_password"];
		
		$title = $_POST["title"];
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
		
		//address
        $address = $_POST["address"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $state_id = round($_POST["state_id"], 0);
        $country_id = round($_POST["country_id"], 0);
        $zip = $_POST["zip"];
        $phone = $_POST["phone"];
		$office_phone_ext = $_POST["office_phone_ext"];
		//end
		
		//company info - master broker		
		$cname = $_POST["cname"];
		$website_url = $_POST["website_url"];
		//end
		
		//company and location
		$company_id = round($_POST["company_id"], 0);
		$location_id = round($_POST["location_id"], 0);
		$location_id_m = round($_POST["location_id_m"], 0);		
		//end
		
		$type_id = round($_POST["type_id"], 0);		
        $about_me = $_POST["about_me"];        

        $ms = round($_POST["ms"], 0);
        $old_d_username = $_POST["old_d_username"];
        $old_d_email = $_POST["old_d_email"];
		$sendemailinfo = round($_POST["sendemailinfo"], 0);
		
		$website_url = $cm->format_url_txt($website_url);
		$display_title = round($_POST["display_title"], 0);
		
		//social media
		$facebook_url = $_POST["facebook_url"];
		$twitter_url = $_POST["twitter_url"];
		$linkedin_url = $_POST["linkedin_url"];
		$youtube_url = $_POST["youtube_url"];
		$googleplus_url = $_POST["googleplus_url"];
		$instagram_url = $_POST["instagram_url"];
		$pinterest_url = $_POST["pinterest_url"];
		$blog_url = $_POST["blog_url"];
		
		$facebook_url = $cm->format_url_txt($facebook_url);
		$twitter_url = $cm->format_url_txt($twitter_url);
		$linkedin_url = $cm->format_url_txt($linkedin_url);
		$youtube_url = $cm->format_url_txt($youtube_url);
		$googleplus_url = $cm->format_url_txt($googleplus_url);
		$instagram_url = $cm->format_url_txt($instagram_url);
		$pinterest_url = $cm->format_url_txt($pinterest_url);
		$blog_url = $cm->format_url_txt($blog_url);

        if ($country_id == 1){
            $state = "";
        }else{
            $state_id = 0;
        }

        if ($frontfrom == 0){
            //backend
            //$type_id = round($_POST["type_id"], 0);
			$yw_broker_id = round($_POST["yw_broker_id"], 0);
            $parent_id = round($_POST["parent_id"], 0);
			$front_display = round($_POST["front_display"], 0);
			$admin_access = round($_POST["admin_access"], 0);
			$yw_agent = round($_POST["yw_agent"], 0);
			$display_listings = round($_POST["display_listings"], 0);
	        $status_id = round($_POST["status_id"], 0);
            $old_status_id = round($_POST["old_status_id"], 0);
			$support_crew = round($_POST["support_crew"], 0);
			$marketing_staff = round($_POST["marketing_staff"], 0);
			$sub_group_id = round($_POST["sub_group_id"], 0);
			
			if ($ms > 0){
				$red_pg = "add_user.php?id=" . $ms;
			}else{
				$type_id = 6;
				$red_pg = $cm->get_page_url(0, "register");
			}
        }else{
            //frontend
			$email2 = $_POST["email2"];
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			
            $logmember = $this->loggedin_member_id();
            $parent_id = round($_POST["parent_id"], 0);
			
			if ($type_id == 3 OR $type_id == 4){                    
				$company_id = $this->get_broker_company_id($logmember);
			}							
            
            if ($ms == 0){
				$red_pg = "register/";
                $status_id = 1;
				$tmessageid = 4;
				$front_display = 0;
				$admin_access = 0;
                //$type_id = round($_POST["type_id"], 0);
                if ($type_id == 2){
                    $status_id = 5;
					$tmessageid = 32;
                }
				
				if ($type_id == 3){	
                    $red_pg = "add-broker/";
                    $tmessageid = 26;
                }  
				
				if ($type_id == 4){	
                    $red_pg = "add-broker/";
                    $tmessageid = 26;
                }              
                
				if ($type_id == 5){	
                    $red_pg = "add-broker/";
                    $tmessageid = 26;
                }
            }else{
				$red_pg = "editprofile/";				
				$user_colleced_fields = $cm->get_table_fields("tbl_user", "yw_broker_id, status_id, front_display, admin_access, yw_agent, display_listings, support_crew, marketing_staff, sub_group_id", $ms);
				$user_colleced_fields = $user_colleced_fields[0];
				$yw_broker_id = $user_colleced_fields['yw_broker_id'];           
                $status_id = $user_colleced_fields['status_id'];
				$front_display = $user_colleced_fields['front_display'];
				$admin_access = $user_colleced_fields['admin_access'];
				$yw_agent = $user_colleced_fields['yw_agent'];
				$display_listings = $user_colleced_fields[ 'display_listings'];
				$support_crew = $user_colleced_fields['support_crew'];
				$marketing_staff = $user_colleced_fields['marketing_staff'];
				$sub_group_id = $user_colleced_fields['sub_group_id'];			
				
				$usertype = $this->get_user_type($logmember);
                $tmessageid = 7;
				
				if ($ms != $logmember){
					
					if ($type_id == 3){	
						$red_pg = "edit-brokerlist/" . $ms ."/";
						$tmessageid = 27;
					}	
					
					if ($type_id == 4){	
						$red_pg = "edit-brokerlist/" . $ms ."/";
						$tmessageid = 27;
					}
					
					if ($type_id == 5){
						$red_pg = "edit-brokerlist/" . $ms ."/";
						$tmessageid = 27;
					}				
				}else{
					$type_id = $cm->get_common_field_name('tbl_user', 'type_id', $ms);	
				}
            }
        }

        /*if ($ms == 1){
            $red_pg = "admin_details.php";
        }else{
            if ($frontfrom == 0){
                if ($ms > 1){
                    $red_pg = "add_user.php?id=" . $ms;
                }else{
                    $red_pg = "add_user.php";
                }
            }
        }*/

        $sesinitial = '';
        if ($frontfrom == 1){
            $sesinitial = 'fr_';
        }

        $datastring = '';
        if ($ms == 0){
            //create session for the posted data
            $datastring .= $cm->session_field_user();
            $cm->create_session_for_form($datastring, $_POST);
        }

        $tblnm = "tbl_user";
        $cm->field_validation($d_username, $old_d_username, 'Username', $red_pg, $tblnm, 'uid', 1, $sesinitial);
        if ($support_crew == 0){
        	$cm->field_validation($d_email, $old_d_email, 'Email Address', $red_pg, $tblnm, 'email', 1, $sesinitial);
		}
        $cm->field_validation($d_password, '', 'Password', $red_pg, '', '', 1, $sesinitial);

        if ($ms != 1){

            $cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, $sesinitial);
            $cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, $sesinitial);
			
            //$cm->field_validation($address, '', 'Address', $red_pg, '', '', 1, $sesinitial);
            //$cm->field_validation($city, '', 'City', $red_pg, '', '', 1, $sesinitial);
			
			/*
            if ($country_id == 1){
                $cm->field_validation($state_id, '', 'State', $red_pg, '', '', 2, $sesinitial);
            }else{
                $cm->field_validation($state, '', 'County/Sate', $red_pg, '', '', 1, $sesinitial);
            }
			*/

            /*$cm->field_validation($zip, '', 'Zipcode', $red_pg, '', '', 1, $sesinitial);*/
            //$cm->field_validation($phone, '', 'Mobile Phone', $red_pg, '', '', 1, $sesinitial);
        }
		
		if ($frontfrom == 1){
			if ($ms == 0){
				//captcha
				global $captchaclass;
				$captchaclass->validate_captcha($red_pg);
			}
		}

        $dt = date("Y-m-j H:i:s");

        if ($ms == 0){
			
			if ($type_id == 2){
				//add company if it is master broker admin
					$cm->field_validation($cname, '', 'Company Name', $red_pg, '', '', 1, $sesinitial);	
					$slug = $cm->create_slug($cname);
					$sql = "insert into tbl_company (slug, cname, website_url) values ('". $cm->filtertext($slug) ."', '". $cm->filtertext($cname) ."','". $cm->filtertext($website_url) ."')";
					$company_id = $db->mysqlquery_ret($sql);				
				//end		
			}
			
            $user_code = $cm->campaignid(25);
            $sql = "insert into tbl_user (uid, user_code, reg_date) values ('". $cm->filtertext($d_username) ."', '". $user_code ."', '". $dt ."')";
            $iiid = $db->mysqlquery_ret($sql);
            $cm->delete_session_for_form($datastring);
			
			if ($type_id < 6){
				$sql = "insert into tbl_user_lead_settings (user_id, timeperiods) values ('". $iiid ."', 1)";
				$db->mysqlquery($sql);
			}
			
			//update boat watcher based on reg email
			$sql_u = "update tbl_boat_watcher_broker set broker_id = '". $iiid ."'  where email_to = '". $cm->filtertext($d_email) ."'";
			$db->mysqlquery($sql_u);
			
        }else{
			if ($type_id == 2 AND $frontfrom == 0){
				//update company
				$cm->field_validation($cname, '', 'Company Name', $red_pg, '', '', 1, $sesinitial);
				$slug = $cm->create_slug($cname);
				$company_id = $cm->get_common_field_name('tbl_user', 'company_id', $ms);
				$sql = "update tbl_company set slug = '". $cm->filtertext($slug) ."', cname = '". $cm->filtertext($cname) ."', website_url = '". $cm->filtertext($website_url) ."' where id = '". $company_id ."'";
            	$db->mysqlquery($sql);
				//end
			}
			
            $sql = "update tbl_user set uid = '". $cm->filtertext($d_username) ."' where id = '". $ms ."'";
            $db->mysqlquery($sql);
            $iiid = $ms;
        }
		
		if ($type_id == 6){
			$keyterm = '';
			$location_id = 0;
		}else{
			if ($type_id == 2){
				$location_id = $location_id_m;			
				//$location_id_check = $this->get_company_default_location($company_id);
			}
			
			$location_id_check = $location_id;
			$location_ar = $cm->get_table_fields('tbl_location_office','address, city, state_id, country_id, zip', $location_id_check);		
			$keyterm = $this->com_address_format($location_ar[0]["address"], $location_ar[0]["city"], $location_ar[0]["state"], $location_ar[0]["state_id"], $location_ar[0]["country_id"], $location_ar[0]["zip"]);			
		}
		
		if ($support_crew == 1){
			if ($d_email == ""){
				$d_email = 'testessexboatworks'. $iiid .'@essexboatworks.com';
			}
		}

        // common update
        $sql = "update tbl_user set email = '". $cm->filtertext($d_email) ."'
        , pwd = '". $cm->filtertext($edclass->txt_encode($d_password)) ."'
		, title = '". $cm->filtertext($title) ."'
        , fname = '". $cm->filtertext($fname) ."'
        , lname = '". $cm->filtertext($lname) ."'
        , address = '". $cm->filtertext($address) ."'
        , city = '". $cm->filtertext($city) ."'
        , state = '". $cm->filtertext($state) ."'
        , state_id = '". $state_id ."'
        , country_id = '". $country_id ."'
        , zip = '". $cm->filtertext($zip) ."'
        , phone = '". $cm->filtertext($phone) ."'
		, office_phone_ext = '". $cm->filtertext($office_phone_ext) ."'
        , about_me = '". $cm->filtertext($about_me) ."'
        , status_id = '". $status_id ."'        
        , type_id = '". $type_id ."'
		, company_id = '". $company_id ."'
		, location_id = '". $location_id ."'
		, keyterm = '". $cm->filtertext($keyterm) ."'
		, front_display = '". $front_display ."'
        , parent_id = '". $parent_id ."'
		, admin_access = '". $admin_access ."'
		, yw_agent = '". $yw_agent ."'
		, display_title = '". $display_title ."'
		, display_listings = '". $display_listings ."'
		, facebook_url = '". $cm->filtertext($facebook_url) ."'
		, twitter_url = '". $cm->filtertext($twitter_url) ."'
		, linkedin_url = '". $cm->filtertext($linkedin_url) ."'
		, youtube_url = '". $cm->filtertext($youtube_url) ."'
		, googleplus_url = '". $cm->filtertext($googleplus_url) ."'
		, instagram_url = '". $cm->filtertext($instagram_url) ."'
		, pinterest_url = '". $cm->filtertext($pinterest_url) ."'
		, blog_url = '". $cm->filtertext($blog_url) ."'
		, yw_broker_id = '". $cm->filtertext($yw_broker_id) ."'
		, support_crew = '". $cm->filtertext($support_crew) ."'
		, marketing_staff = '". $cm->filtertext($marketing_staff) ."'
		, sub_group_id = '". $sub_group_id ."' where id = '". $iiid ."'";
        $db->mysqlquery($sql);
        // end
		
		if ($type_id != 6){
			//Industry Association assign
			$this->industry_associations_assign($iiid, 2);
			
			//Certification assign
			$this->certification_assign($iiid, 2, $frontfrom);
		}
		//end

        //user image upload
        $filename = $_FILES['user_imgpath']['name'] ;
        if ($filename != ""){
            $wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
            if ($wh_ok == "y"){
                $filename_tmp = $_FILES['user_imgpath']['tmp_name'];
                $filename = $fle->uploadfilename($filename);
                $filename1 = $iiid."profile".$filename;

                $target_path_main = "userphoto/";
                if ($frontfrom == 0){
                    $target_path_main = "../" . $target_path_main;
                }

                //thumbnail image
                $r_width = $cm->user_im_width_t;
                $r_height = $cm->user_im_height_t;
                $target_path = $target_path_main;
                $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

                //big image
                $r_width = $cm->user_im_width;
                $r_height = $cm->user_im_height;
                $target_path = $target_path_main . "big/";
                $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				
				//square image
                $r_width = $cm->user_im_width_sq;
                $r_height = $cm->user_im_height_sq;
                $target_path = $target_path_main . "square/";
                $fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				
				//original image store
				$target_path = $target_path_main . 'original/';
				$target_path = $target_path . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['user_imgpath']['tmp_name'], $target_path);
				
                //$fle->filedelete($filename_tmp);
                $sql = "update tbl_user set user_imgpath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
                $db->mysqlquery($sql);
            }
        }
        //end

        //send email
        if ($frontfrom == 0){
            if ($old_status_id != $status_id){
                if ($ms != 1){
					if ($sendemailinfo == 1){
                    	$this->send_user_email($iiid, $status_id);
					}
                }
            }
        }else{
            if ($ms == 0){
				if ($sendemailinfo == 1){
                	$this->send_user_email($iiid, $status_id);
				}
            }
        }

        //end

        if ($frontfrom == 1){
            global $frontend;
            if ($ms > 0){
                // for update record
                if ($tmessageid == 7){
                    $_SESSION["cr_user_name"] = $cm->filtertextdisplay($fname);
                    $_SESSION["cr_uid"] = $cm->filtertextdisplay($d_username);
                }
                $_SESSION["thnk"] = $frontend->display_message($tmessageid);
                header('Location: '. $cm->site_url .'/thankyou/');
            }else{
                $_SESSION["thnk"] = $frontend->display_message($tmessageid);
                header('Location: '. $cm->site_url .'/thankyou/');
            }
        }
    }
	
	//display back-end tab
	public function display_user_tab_button($ms){
		$returntext = '';
		
		if ($ms > 0){
			
			if (isset($_SESSION["from_list_user"]) AND $_SESSION["from_list_user"] == 1){
				$backlink = $_SESSION["bck_pg"];
			}else{
				$backlink = "mod_user.php";
			}
			
			$returntext = '
			<table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
				<tr>
					<td align="left" valign="top">
						<ul class="syscategory">
							<li><a tabid="1" class="toptablink toptablink1 active" href="add_user.php?id='. $ms .'">Form</a></li>
							<li><a sliderid="'. $ms .'" tabid="2" class="toptablink toptablink2" href="javascript:void(0);">Crop Image</a></li>
							<li><a tabid="3" class="toptablink" href="'. $backlink .'">Back To List</a></li>
						</ul>
					</td>
				</tr>
				<tr>
					<td width="" height="20"><img border="0" src="images/sp.gif" alt="" /></td>
				</tr>
			</table>
			';
		}
		
		return $returntext;
	}
	//end
	
	//user profile image crop option
	public function display_user_image_crop_option($user_id){
		global $db, $cm, $adm, $fle;
		
		$rw = $cm->user_im_width;
		$rh = $cm->user_im_height;
		$iprop = round($rw / $rh, 2);
			
		$imgpath = $cm->get_common_field_name("tbl_user", "user_imgpath", $user_id);
		if ($imgpath != ""){
			$returntext = '
			
			<div class="cropimageholder nospace">
				<div class="cropimagesection box_border">
					<div class="box_heading">Original Image</div>
					<div class="bottomspacer1 clearfixmain">
						<input type="radio" class="radiobutton fullwidthcrop" name="whfullwidth" value="0" checked="checked"> Both width and height crop &nbsp;&nbsp;&nbsp;
						<input type="radio" class="radiobutton fullwidthcrop" name="whfullwidth" value="1"> Only height crop
					</div>
					<img iwidth="'. $rw .'" iprop="'. $iprop .'" id="myImage" class="myImage" src="'. $cm->folder_for_seo . 'userphoto/original/'. $imgpath .'?v='. time() .'" />
				</div>
				
				<div class="outputsection box_border">
					<div class="box_heading">Currently Saved</div>
					<div class="imageoutput"><img src="'. $cm->folder_for_seo . 'userphoto/big/'. $imgpath .'" /></div>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="saveholder nospace">
			<button user_id="'. $user_id .'" type="button" class="butta saveimage"><span class="saveIcon butta-space">Save Image</span></button>
			</div>
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				
				var x1 = 0,
					y1 = 0,
					tw = 0,
					th = 0;
					
					var mw = 100;
					var mh = 0;
					var x2 = 0;
					var y2 = 0;
					
				
				function get_image_aspect_ratio(){
					var img = $("#myImage");
					var wo = img.prop("naturalWidth");
					var wd = img.prop("width");
					var wrt = wo / wd;
					return [wrt, wd];
				}
				
				function setinitialization(){
					var manageoption = get_image_aspect_ratio();
					var wrt = manageoption[0];
					var wd = manageoption[1];
					
					var iprop = $("#myImage").attr("iprop");
					var iwidth = $("#myImage").attr("iwidth");
					mh = mw / iprop;
					
					var selected_opt = parseInt($("input[name=whfullwidth]:radio:checked").val());						
					
					if (selected_opt == 1){
						x2 = wd;
					}else{
						x2 = iwidth / wrt ;
					}
				
					var iheight = x2 / iprop;
					
					y2 = iheight;
					return [x2, y2];
				}
				
				function setValue(img, selection) {
					if (!selection.width || !selection.height){
						return;
					}
					
					var manageoption = get_image_aspect_ratio();
					var wrt = manageoption[0];
					
					x1 = selection.x1;
					y1 = selection.y1;
					tw = selection.width;
					th = selection.height;		
					
					x1 = parseInt(x1) * wrt; 
					y1 = parseInt(y1) * wrt; 
					tw = parseInt(tw) * wrt; 
					th = parseInt(th) * wrt;	
				}
				
				$("#myImage").on("load",function(){
				
					var croplastpointar = setinitialization();				

					var imas = $("#myImage").imgAreaSelect({		
							handles: true,
							fadeSpeed: 200,
							x1: x1,
							y1: y1,
							x2: croplastpointar[0],
							y2: croplastpointar[1],
							minWidth: mw,
							minHeight: mh,
							persistent: true,
							instance: true,
							resizable: false,
							onSelectEnd: setValue
					});
					
					$(".whitetd").off("click", ".fullwidthcrop").on("click", ".fullwidthcrop", function(){
						croplastpointar = setinitialization();
						imas.setSelection(0, 0, croplastpointar[0], croplastpointar[1]);
						imas.update();
					});					
					
				});
				
				$(".whitetd").off("click", ".saveimage").on("click", ".saveimage", function(){
					var user_id = $(this).attr("user_id");
					
					var b_sURL = "onlyadminajax.php";
					$.post(b_sURL,
					{
						x1:x1,
						y1:y1,
						tw:tw,
						th:th,
						user_id:user_id,
						az:10,
						inoption:10
					},
					function(content){
						$(".imageoutput").html(content);
						
						$(".waitdiv").show();
						$(".waitmessage").html("<p>Image cropped.</p>");
						messagedivhide();
					});
				});
				
			});
			</script>
			';
			
		}else{
			$returntext = '<p>You have not uploaded image for this User.</p>';	
		}
		
		echo $returntext;
	}
	//end
	
	//process crop
	function user_process_crop($user_id){
		global $db, $cm, $adm, $fle;
		
		$x1 = round($_POST["x1"], 0);
		$y1 = round($_POST["y1"], 0);
		$w = round($_POST["tw"], 0);
		$h = round($_POST["th"], 0);
		
		$imgpath = $cm->get_common_field_name("tbl_user", "user_imgpath", $user_id);
		$imgpath_new = $fle->create_different_file_name($imgpath);
		
		$source_path = "../userphoto/original/" . $imgpath;
		$source_path_rename = "../userphoto/original/" . $imgpath_new;
		
		$x = @getimagesize($source_path);
		$source_width = $x[0];
		$source_height = $x[1];
		$source_type = $x[2];
		
		$rw = $cm->user_im_width;
		$rh = $cm->user_im_height;
		
		$wratio = ($rw/$w); 
		$hratio = ($rh/$h); 
		$newW = ceil($w * $wratio);
		$newH = ceil($h * $hratio);
		$newimg = imagecreatetruecolor($newW,$newH);
		
		if ($source_type == 1){
			$source = @ImageCreateFromGIF ($source_path);
		}elseif ($source_type == 2){
			$source = @ImageCreateFromJPEG ($source_path);
		}elseif ($source_type == 3){
			$source = @ImageCreateFromPNG ($source_path);
		}else{
			$source = false;
		}		

		$path = "../userphoto/";
		
		$bigpath = $path . "big/";
		imagecopyresampled($newimg,$source,0,0,$x1,$y1,$newW,$newH,$w,$h);
		imagejpeg($newimg,$bigpath.$imgpath_new, 80);
		
		//rename original file
		$fle->rename_existing_file($source_path, $source_path_rename);
		
		//remove existing file
		$fle->filedelete($path.$imgpath);
		$fle->filedelete($bigpath.$imgpath);
		
		//thumbnail image
		$filename_tmp = $bigpath . $imgpath_new;
		$r_width = $cm->user_im_width_t;
		$r_height = $cm->user_im_height_t;
		$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $path, $cm->filtertextdisplay($imgpath_new));
		
		//update filename
		$sql = "update tbl_user set user_imgpath = '".$cm->filtertext($imgpath_new)."' where id = '". $user_id ."'";
		$db->mysqlquery($sql);
		
		//output file		
		echo '<img src="'. $cm->folder_for_seo .'userphoto/big/'. $imgpath_new .'?x='.time().'" />';
		exit;
	}
	
	//lead tool
	public function broker_lead_reporting_tool(){
		global $db, $cm, $sdeml;
		$loggedin_member_id = $this->loggedin_member_id();
		$emails = $_REQUEST["emails"];
		$timeperiods = round($_REQUEST["timeperiods"], 0);
		
		$sql = "update tbl_user_lead_settings set emails = '". $cm->filtertext($emails) ."', timeperiods = '". $timeperiods ."' where user_id = '". $loggedin_member_id ."'";
		$db->mysqlquery($sql);
		
		$optiontext = 'Preferences modified';
		$returnval[] = array(            
            'optiontext' => $optiontext
        );
		return json_encode($returnval);
	}

    //delete user
    public function delete_user($user_id){
        global $db;
        if ($user_id > 1){
            $this->delete_user_images($user_id);
            $sql = "delete from tbl_user where id = '". $user_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_user where parent_id = '". $user_id ."'";
            $db->mysqlquery($sql);
			
			$sql = "delete from tbl_user_to_broker where user_id = '". $user_id ."'";
            $db->mysqlquery($sql);
			
			$sql = "delete from tbl_user_to_broker where broker_id = '". $user_id ."'";
            $db->mysqlquery($sql);
			
			$sql = "delete from tbl_user_industry_associations where user_id = '". $user_id ."'";
    		$db->mysqlquery($sql);
			
			$sql = "delete from tbl_user_certification where user_id = '". $user_id ."'";
    		$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht set broker_id = 1 where broker_id = '". $user_id ."'";
    		$db->mysqlquery($sql);
			
			$sql = "delete from tbl_user_lead_settings where user_id = '". $user_id ."'";
            $db->mysqlquery($sql);
			
			$sql = "delete from tbl_boat_slideshow where broker_id = '". $user_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "delete from tbl_email_campaign where broker_id = '". $user_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "delete from tbl_boat_watcher_broker where broker_id = '". $user_id ."'";
			$db->mysqlquery($sql);
        }
    }	
    //end

    //delete user images
    public function delete_user_images($user_id){
        global $db, $fle;
        $sql = "select user_imgpath from tbl_user where id = '". $user_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg2 = $row['user_imgpath'];            
            if ($fimg2 != ""){
                $fle->filedelete("../userphoto/" . $fimg2);
                $fle->filedelete("../userphoto/big/" . $fimg2);
				$fle->filedelete("../userphoto/square/" . $fimg2);
				$fle->filedelete("../userphoto/original/" . $fimg2);
            }
        }

        $sql = "select user_imgpath from tbl_user where parent_id = '". $user_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){            
            $fimg2 = $row['user_imgpath'];
			if ($fimg2 != ""){
                $fle->filedelete("../userphoto/" . $fimg2);
                $fle->filedelete("../userphoto/big/" . $fimg2);
				$fle->filedelete("../userphoto/square/" . $fimg2);
				$fle->filedelete("../userphoto/original/" . $fimg2);
            }
        }
    }
    //end
	
	public function submit_member_form(){
		if(($_POST['fcapi'] == "membersubmit")){
			$mbid= round($_POST["ms"], 0);
			$this->can_access_user($mbid);
			$this->user_insert_update(1);
			exit;
		}
	}
	
	public function submit_user_form(){
		if(($_POST['fcapi'] == "usersubmit")){
			$this->user_insert_update(1);
			exit;
		}
	}

    public function check_field_validation($fieldopt, $selvalue, $oselvalue){
        global $db, $cm;
        if ($fieldopt == 1){
            //username validation
            $tbl_name = "tbl_user";
            $fieldname = "Username";
            $checkfield = "uid";
        }

        if ($fieldopt == 2){
            //email validation
            $tbl_name = "tbl_user";
            $fieldname = "Email Address";
            $checkfield = "email";
        }

        $iffound = 0;
        if ($oselvalue != $selvalue){
            $sqltext = "select count(*) as ttl from ". $tbl_name ." where ". $checkfield ." = '". $cm->filtertext($selvalue) ."'";
            $iffound = $db->total_record_count($sqltext);
        }

        if ($iffound > 0){
            $ajclass = 'incorrectIcon';
            $doc = $fieldname . ' already exists.';
        }else{
            $ajclass = 'correctIcon';
            $doc = $fieldname . ' is valid.';
        }

        $returnval[] = array(
            'ajclass' => $ajclass,
            'doc' => $doc
        );
        return json_encode($returnval);
    }
	
	public function get_commonstatus_combo($status_id = 0){
        global $db;
        $vsql = "select id, name from tbl_common_status where status = 'y' order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
        ?>
            <option value="<?php echo $c_id;?>"<?php if ($status_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
   }

    public function get_user_account_combo($status_id = 0){
        global $db;
        $vsql = "select id, name from tbl_user_account_status where status = 'y' and main_ac_status = 'y' order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $vid = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $vid; ?>"<?php if ($status_id == $vid){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }

    public function get_user_account_combo_mini($status_id = 0, $type_id = 0){
        global $db;
        if ($type_id > 0){
            $vsql = "select id, name from tbl_user_account_status where";

            if ($type_id == 2){
                $vsql .= " id != 1 and";
            }

            if ($type_id == 3 OR $type_id == 4){
                $vsql .= " id != 5 and";
            }

            $vsql .= " status = 'y' and main_ac_status = 'y' order by rank";
            $vresult = $db->fetch_all_array($vsql);
            $vfound = count($vresult);
            for ($xk = 0; $xk < $vfound; $xk++){
                $vrow = $vresult[$xk];
                $vid = $vrow['id'];
                $cname = $vrow['name'];
                ?>
                <option value="<?php echo $vid; ?>"<?php if ($status_id == $vid){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
            <?php
            }
        }else{
            ?>
            <option value="0">Select User Type</option>
            <?php
        }
    }

    public function get_user_type_combo($type_id = 0, $displayop = 0){
        global $db;
        $vsql = "select id, name from tbl_user_type";		
			
		if ($displayop == 1 OR $displayop == 2){
			//master admin	
			//$vsql .= " where id > 2 and id < 6";
			$vsql .= " where id > 2";
		}
		
		if ($displayop == 3){
			//Manager admin	
			//$vsql .= " where id > 3 and id < 6";
			$vsql .= " where id > 3";
		}
		
		$vsql .= " order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $vid = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $vid; ?>"<?php if ($type_id == $vid){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }
	
	public function main_state_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "All - Worldwide");
		$val_ar[] = array("name" => "US Only");
		$val_ar[] = array("name" => "Outside of US");
		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_state_main_combo($statemain){
		global $db;
		$val_ar = json_decode($this->main_state_val());		
		$returntext = '';
  
        foreach($val_ar as $key => $val_row){
            $cname = $val_row->name;
			$bck = '';
			if ($statemain == $key){
				$bck = ' selected="selected"';	
			}			
			$returntext .= '<option value="'. $key .'"'. $bck .'>'. $cname .'</option>';
        }		
		return $returntext;
	}

    public function get_state_combo($state_id, $returnoption = 0){
        global $db;
		$returntxt = '';
        $vsql = "select id, name from tbl_state order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($state_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';    
        }
		
		if ($returnoption == 1){
			return $returntxt;
		}else{
			echo $returntxt;
		}
    }

    public function get_country_combo($country_id, $returnoption = 0){
        global $db;
		$returntxt = '';
        $vsql = "select id, name from tbl_country where status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($country_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';    
        }
		
		if ($returnoption == 1){
			return $returntxt;
		}else{
			echo $returntxt;
		}
    }

    public function get_broker_admin($user_id){
        global $db;
        $vsql = "select id, uid from tbl_user where type_id = 2 order by uid";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $c_id = $vrow['id'];
            $cname = $vrow['uid'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($user_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }
	
	public function get_company_combo($company_id, $only = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, cname, enable_custom_label from tbl_company where";
		if ($only == 1){
			$vsql .= " id = '". $company_id ."' and";
		}
        $vsql .= " status_id = 1 order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['cname'];
			$enable_custom_label = $vrow['enable_custom_label'];
            $bck = '';
			if ($company_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option cb="'. $enable_custom_label .'" value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function get_company_location_combo($location_id, $company_id, $azop = 0, $only = 0){
        global $db;
        $returntxt = '';
		$returnarray = array();
        $vsql = "select id, name, address, city, state, state_id, country_id, zip from tbl_location_office where company_id = '". $company_id ."' and";
        if ($only == 1){
			$vsql .= " id = '". $location_id ."' and";
		}
		$vsql .= " status_id = 1 order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$address = $vrow['address'];
			$city = $vrow['city'];
			$state = $vrow['state'];
			$state_id = $vrow['state_id'];
			$country_id = $vrow['country_id'];
			$zip = $vrow['zip'];
			$location_address = $this->com_address_format('', $city, $state, $state_id, $country_id, '');			
			$cname .= ' - ' . $location_address;
			$attrval = $address . ',:' . $city . ',:' . $state . ',:' . $state_id . ',:' . $country_id . ',:' . $zip;
			
			if ($azop == 0){
				$bck = '';
				if ($location_id == $c_id){
					$bck = ' selected="selected"';	
				}				
				$returntxt .= '<option addressval="'. $attrval .'" value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id,
					'attrval' => $attrval
				);
			}            
        }
		
		if ($azop == 0){
			return $returntxt;
		}else{
			$returnval[] = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}
    }

    public function get_user_sub_group_combo($sub_group_id = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_user_sub_group where";
        $vsql .= " status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            $bck = '';
			if ($sub_group_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function send_user_email($user_id, $send_ml_id, $mail_section = 0){
		if ($send_ml_id != 3){
			global $db, $cm, $edclass, $sdeml;
			$news_footer_u = '';
			if ($mail_section == 1){
				$vsql = "select uid, pwd, name, email, status_id from tbl_sub_admin where id = '". $user_id ."'";
				$vresult = $db->fetch_all_array($vsql);
				$vrow = $vresult[0];
				$u_uid = $vrow['uid'];
				$u_pwd = $edclass->txt_decode($vrow['pwd']);
				$user_code = "";
				$name = $vrow['name'];
				$email = $vrow['email'];
				$status_id = $vrow['status_id'];
				
				$msg = $cm->get_common_field_name('tbl_subadmin_account_status', 'pdes', $send_ml_id);
				$mail_subject = $cm->get_common_field_name('tbl_subadmin_account_status', 'email_subject', $send_ml_id);
			}else{
				$vsql = "select uid, pwd, concat(fname, ' ', lname) as name, email, status_id, user_code from tbl_user where id = '". $user_id ."'";
				$vresult = $db->fetch_all_array($vsql);
				$vrow = $vresult[0];
				$u_uid = $vrow['uid'];
				$u_pwd = $edclass->txt_decode($vrow['pwd']);
				$user_code = $vrow['user_code'];
				$name = $vrow['name'];
				$email = $vrow['email'];
				$status_id = $vrow['status_id'];
				
				$msg = $cm->get_common_field_name('tbl_user_account_status', 'pdes', $send_ml_id);
				$mail_subject = $cm->get_common_field_name('tbl_user_account_status', 'email_subject', $send_ml_id);
			}
			
			$activationlinkurl = $cm->site_url."/?fcapi=accountactivation&cd=".$user_code;
			$activationlink = '<a href="'. $activationlinkurl .'">'. $activationlinkurl .'</a>';
			
			$companyname = $cm->sitename;
			$reset_link = $cm->site_url."/reset-password/". $user_code ."/";
			$resetpassword = '<a href="'. $reset_link .'">'. $reset_link .'</a>';
	
			$msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
			$msg = str_replace("#uname#", $u_uid, $msg);
			$msg = str_replace("#password#", $u_pwd, $msg);
			$msg = str_replace("#activationlink#", $activationlink, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$msg = str_replace("#resetpassword#",$resetpassword,$msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
	
			if ($status_id == 1){ $mail_bcc = ""; }else{ $mail_bcc = $cm->admin_email_to(); }
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		}
    }
	
	public function member_account_activation(){
		if(($_REQUEST['fcapi'] == "accountactivation")){			
			$this->user_login(1);
			exit;
		}
	}

    public function loggedin_member_id(){
        if (isset($_SESSION["suc"]) AND $_SESSION["suc"] == "true"){
            $loggedinmember = $_SESSION["usernid"];
        }else{
            $loggedinmember = 0;
        }
        return $loggedinmember;
    }

    public function member_field($field_name, $memberid){
        global $cm;
        $returntxt = $cm->get_common_field_name('tbl_user', $field_name, $memberid);
        return $returntxt;
    }

    public function forgot_password_check($posteduid){
        global $db, $cm, $frontend;
        $sql = "select id, user_code from tbl_user where uid = '". $cm->filtertext($posteduid) ."' and status_id = 2";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
            $row = $result[0];
            $ms = $row['id'];
            $user_code = $row['user_code'];
            $_SESSION["reset_p_id"] = $ms;
            $this->send_user_email($ms, 4);
            $returntxt = $frontend->display_message(8);
        }else{
            $returntxt = $frontend->display_message(12);
        }

        $returnval[] = array(
            'doc' => $returntxt
        );
        echo json_encode($returnval);
    }


    public function resetpassword_check($cd, $ms = 0){
        global $db, $cm, $frontend;
        if (!isset($_SESSION["reset_p_id"]) OR $_SESSION["reset_p_id"] == ""){
            $_SESSION["ob"] = $this->display_message(9);
            header('Location: '. $cm->site_url .'/sorry.php');
            exit;
        }

        $sql = "select id, uid, fname, lname from tbl_user where user_code = '" . $cm->filtertext($cd) . "'";
        if ($ms > 0){
            $sql .= " and id = '". $ms ."'";
        }
        $sql .= " and status_id = 2";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $_SESSION["ob"] = $frontend->display_message(11);
            header('Location: '. $cm->site_url .'/sorry/');
            exit;
        }
        return $result;
    }
	
	public function user_reset_password(){
		if(($_REQUEST['fcapi'] == "userresetp")){
			global $db, $cm, $frontend, $edclass;
						
			$frontend->go_to_account();
			$d_password = $_POST["d_password"];
			$cd = $_POST["cd"];
			$ms = round($_POST["ms"], 0);
			
			$red_pg = $cm->site_url."/reset-password/". $cm->filtertextdisplay($cd) ."/";
			$cm->field_validation($d_password, '', 'Password', $red_pg, '', '', 1, 'fr_');
			
			$result = $this->resetpassword_check($cd, $ms);
			
			$sql = "update tbl_user set pwd = '". $cm->filtertext($edclass->txt_encode($d_password)) ."' where id = '". $ms ."'";
			$db->mysqlquery($sql);
			
			unset($_SESSION["reset_p_id"]);
			$_SESSION["thnk"] = $frontend->display_message(10);
			$cm->thankyouredirect(10);
			exit;			
		}
	}

    public function get_user_image($userid, $noimg = 0){
        $poster_im = $this->member_field('user_imgpath', $userid);
        if ($poster_im == "" AND $noimg == 0){
            $poster_im = 'no.png';
        }
        return $poster_im;
    }

    public function delete_images($passed_id, $delop = 0){
        global $db, $cm;
        $extrasql = '';
		$id = $passed_id;
        if ($delop == 1){
            $tbl_field = "logo_imgpath";
            $tbl_name = "tbl_company";
            $wh_field = "id";
            $foldernm = "userphoto";
			$id = $this->get_broker_company_id($passed_id);
        }

        if ($delop == 2){
            $tbl_field = "user_imgpath";
            $tbl_name = "tbl_user";
            $wh_field = "id";
            $foldernm = "userphoto";
        }
		
		if ($delop == 3){
            $tbl_field = "logo_image";
            $tbl_name = "tbl_location_office";
            $wh_field = "id";
            $foldernm = "locationimage";
        }

        $flnm = $db->total_record_count("select ". $tbl_field ." as ttl from ". $tbl_name ." where ". $wh_field ." = '". $id ."'" . $extrasql . "");
        $sql = "update ". $tbl_name ." set ". $tbl_field ." = '' where ". $wh_field ." = '". $id ."'";
        $db->mysqlquery($sql);

        if ($flnm != ""){
            unlink ("../" . $foldernm."/".$flnm);
            if ($delop == 2){
                unlink ("../" . $foldernm."/big/".$flnm);
            }
        }

        $returnval[] = array(
            'retval' => 'y'
        );
        echo json_encode($returnval);
    }

    public function check_user_exist($cuser_id, $optn = 1, $checkusr = 0, $onlybadmin = 0){
        global $db, $cm;
        if ($optn == 1){
            $sql = "select * from tbl_user where uid = '". $cm->filtertext($cuser_id) ."' and status_id = 2";
        }else{
            $sql = "select * from tbl_user where id = '". $cm->filtertext($cuser_id) ."' and status_id = 2";
        }

        if ($onlybadmin == 1){
            $sql .= " and type_id = 2";
        }

        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            global $frontend;
            $_SESSION["ob"] = $frontend->display_message(15);
            if ($checkusr == 2){
                //frontend popup
                $redpage = $cm->get_page_url(0, "popsorry");
            }else{
                //frontend normal
                $redpage = $cm->get_page_url(0, "sorry");
            }
            header('Location: '. $redpage);
            exit;
        }
        return $result;
    }

    public function get_user_type($cuser_id){
        global $cm;
        $type_id = $cm->get_common_field_name('tbl_user', 'type_id', $cuser_id);
        return $type_id;
    }
	
	public function check_user_admin_permission($company_id, $location_id, $userid){
		global $db;		
		$type_id = $this->get_user_type($userid);
		switch($type_id){
			case 2:
                //master admin
                $sqltext = "select count(*) as ttl from tbl_user where id = '". $userid ."' and type_id = 2 and company_id = '". $company_id ."'";
				$returnvalue = $db->total_record_count($sqltext);
                break;
				
			case 3:
                //manager admin
                $sqltext = "select count(*) as ttl from tbl_user where id = '". $userid ."' and type_id = 3 and company_id = '". $company_id ."'";
				$returnvalue = $db->total_record_count($sqltext);
                break;	
				
			case 4:
                //location admin
                $sqltext = "select count(*) as ttl from tbl_user where id = '". $userid ."' and type_id = 4 and company_id = '". $company_id ."' and location_id = '". $location_id ."'";
				$returnvalue = $db->total_record_count($sqltext);
                break;	
				
			default:
                $returnvalue = 0;
                break;
		}				
		return $returnvalue;	
	}

    public function get_user_country_id($cuser_id){
        global $cm;
        $country_id = $cm->get_common_field_name('tbl_user', 'country_id', $cuser_id);
        return $country_id;
    }

    public function check_user_permission($permissiontype, $az = 0){
        global $cm;
        $loggedin_member_id = $this->loggedin_member_id();
        $usertype = $this->get_user_type($loggedin_member_id);
        if (!(in_array($usertype, $permissiontype))) {
            if ($az == 0){
                global $frontend;
                $_SESSION["ob"] = $frontend->display_message(25);
                $redpage = 'sorry/';
                header('Location: '. $cm->folder_for_seo . $redpage);
                exit;
            }else{
                exit;
            }
        }
    }

    public function collect_broker_company_details($cuser_id){
		global $cm;
		$company_id = $this->get_broker_company_id($cuser_id);
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
		return $company_ar;
	}
	
	public function get_broker_company_name($cuser_id){
		$company_ar = $this->collect_broker_company_details($cuser_id);
        $cname = $company_ar[0]["cname"];
		return $cname;
	}

    public function get_broker_company_details($cuser_id){
        global $db, $cm;
        $returntxt = '';
				
        $company_ar = $this->collect_broker_company_details($cuser_id);
        $cname = $company_ar[0]["cname"];
        $logo_imgpath = $company_ar[0]["logo_imgpath"];

        $returntxt .= '<h1>'. $cname .'</h1>';
        if ($logo_imgpath != ""){
            $returntxt .= '<img src="'. $cm->folder_for_seo .'userphoto/'. $logo_imgpath .'" alt="">';
        }

        return $returntxt;
    }

    public function com_address_format($address, $city, $state, $state_id, $country_id, $zip = "", $broption = 0){
        global $db, $cm;

        $addressfull = '';
        $country_code = $cm->get_common_field_name('tbl_country', 'code', $country_id);
        if ($country_id == 1){
            $state_name = $cm->get_common_field_name('tbl_state', 'code', $state_id);
        }else{
            $state_name = $state;
        }

        if ($address != ""){
            $addressfull .= $address . ', ';
        }

        if ($city != ""){
            $addressfull .= $city . ', ';
        }

        if ($state_name != ""){
            $addressfull .= $state_name . ', ';
        }

        if ($country_code != ""){
			$before_breake = '';
			if ($broption == 1){
				$before_breake = '<br />';	
			}
            $addressfull .= $before_breake . $country_code . ', ';
        }
		
		if ($zip != ""){
			$addressfull .= $zip . ', ';
		}

        $addressfull = rtrim ($addressfull, ', ');
        return $addressfull;
    }

    public function display_user_info_short($cuser_id, $displayoption = 0){
        global $db, $cm;

        $cuser_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone, company_id, location_id', $cuser_id);
        $fname = $cuser_ar[0]["fname"];
        $lname = $cuser_ar[0]["lname"];
		$phone = $cuser_ar[0]["phone"];
		
		$broker_ad_ar = $this->get_broker_address_array($cuser_id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"];
        
        $member_image = $this->get_user_image($cuser_id);
        $total_y = $this->get_total_yacht_by_broker(array("broker_id" => $cuser_id, "status_id" => 1));
        $addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);

        $profile_url = $cm->get_page_url($cuser_id, 'user');
		
		//Google event tracking
		$brokername = $fname .' '. $lname;
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);

        $returntxt = '
        <div class="left">
            <img src="'. $cm->folder_for_seo .'userphoto/'. $member_image .'" alt="">
        </div>
        <div class="right">
			<h3 class="heading">'. $brokername .'</h3>
			<div>'. $addressfull .'</div>';
			if ($phone != ""){ $returntxt .= '<div class="mobile"><a class="tel" href="tel:'. $phone .'"><span>'. $phone .'</span></a></div>'; }
            $returntxt .= '
			<div class="phone"><a class="tel" href="tel:'. $officephone .'"><span>'. $officephone .'</span></a></div>
			<div>'. $total_y .' Listing(s)</div>
			<div><a href="'. $profile_url .'">View Profile</a></div>
			';			
        if ($displayoption == 1){ 
				$returntxt .= '<div><a '. $gaeventtracking .' href="javascript:void(0);" data-src="'. $cm->folder_for_seo.'contact-broker/?id='. $cuser_id .'" class="contactbroker button contact" data-type="iframe"><span>Contact<span></a></div>';
		}
        $returntxt .= '</div>
        ';		
        return $returntxt;
    }
	
	public function display_user_info_dashboard($cuser_id, $displayoption = 0){
        global $db, $cm;

        $cuser_ar = $cm->get_table_fields('tbl_user', 'email, fname, lname, phone, company_id, location_id, type_id', $cuser_id);
        $fname = $cuser_ar[0]["fname"];
        $lname = $cuser_ar[0]["lname"];
		$phone = $cuser_ar[0]["phone"];
		$email = $cuser_ar[0]["email"];
		//$work_phone = $cuser_ar[0]["work_phone"];
		$work_phone = '';
		$type_id = $cuser_ar[0]["type_id"];
		
		$member_image = $this->get_user_image($cuser_id);
		$phonetext = '';
		if ($phone != ""){ 
			$phonetext = '<div class="userphone"><a class="tel" href="tel:'. $phone .'"><i class="fa fa-mobile" aria-hidden="true"></i>'. $phone .'</a></div>';
		}
		
		$work_phone_text = '';
		if ($work_phone != ""){ 
			$work_phone_text = '<div class="userphone"><a class="tel" href="tel:'. $work_phone .'"><i class="fa fa-phone" aria-hidden="true"></i>'. $work_phone .'</a></div>';
		}
		
		if ($type_id == 6){
			$returntxt = '
			<div class="userinfoleft">			
				<ul class="userdetails">
					<li><img src="'. $cm->folder_for_seo .'userphoto/big/'. $member_image .'" alt=""></li>
					<li>
						<h3>'. $fname .' '. $lname .'</h3>
						<div class="userphone"><a href="mailto:'. $email .'"><i class="fa fa-at" aria-hidden="true"></i>'. $email .'</a></div>			
						'. $phonetext .'
						'. $work_phone_text .'
					</li>
				</ul>
				
			</div>
			<div class="userinforight clearfixmain">
				<a href="'. $cm->get_page_url(0, "editprofile") .'" class="button">Edit Profile</a>
			</div>
			';
		}else{
		
			$company_ar = $this->collect_broker_company_details($cuser_id);
			$logo_imgpath = $company_ar[0]["logo_imgpath"];
			$logo_imgpath_text = '&nbsp;';
			if ($logo_imgpath != ""){
				$logo_imgpath_text = '<div class="usercompanylogo"><img src="'. $cm->folder_for_seo .'userphoto/'. $logo_imgpath .'" alt=""></div>';
			}
			
			$broker_ad_ar = $this->get_broker_address_array($cuser_id);		
			$address = $broker_ad_ar["address"];
			$city = $broker_ad_ar["city"];
			$state = $broker_ad_ar["state"];
			$state_id = $broker_ad_ar["state_id"];
			$country_id = $broker_ad_ar["country_id"];
			$zip = $broker_ad_ar["zip"];
			$officephone = $broker_ad_ar["phone"];
			
			
			$total_y = $this->get_total_yacht_by_broker(array("broker_id" => $cuser_id, "status_id" => 1));
			$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);	
			$profile_url = $cm->get_page_url($cuser_id, 'userinsidedb');
			
			$returntxt = '
			<div class="userinfoleft">			
				<ul class="userdetails">
					<li><img src="'. $cm->folder_for_seo .'userphoto/big/'. $member_image .'" alt=""></li>
					<li>
						<h3>'. $fname .' '. $lname .'</h3>
						<div class="totallisting">'. $total_y .' Listing(s)</div>
						'. $phonetext .'
						'. $work_phone_text .'						
						<div class="userbutton"><a href="'. $profile_url .'" class="button">Profile</a></div>
					</li>
				</ul>
				
			</div>
			<div class="userinforight clearfixmain">
				'. $logo_imgpath_text .'
			</div>
			';	
		}
        return $returntxt;
    }
	
	public function display_consumer_user_brokerinfo_short($cuser_id){
        global $db, $cm;
		$sql = "select * from tbl_user_to_broker where user_id = '". $cuser_id ."'";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		$returntxt = '<h2 class="heading">My Broker</h2>';
		if ($found > 0){
			$row = $result[0];
			$selected_broker_id = $row["broker_id"];
			$save_search = $row["save_search"];
			$fav_list = $row["fav_list"];
			$save_search_d = '';
			$fav_list_d = '';
			if ($save_search == 1){ $save_search_d = ' checked="checked"'; }
			if ($fav_list == 1){ $fav_list_d = ' checked="checked"'; }
			$returntxt .= '<a href="javascript:void(0);" class="delmybroker" title="Remove Broker"><img src="'. $cm->folder_for_seo .'images/deletebig.png" alt="Remove Broker" /></a>';
			$returntxt .= $this->display_user_info_short($selected_broker_id, 1);
			$returntxt .= '
			<div class="clear"></div>
			<div class="left"><strong>Preferences</strong></div>
			<div class="right">
			<form id="mybrokeropt" name="mybrokeropt">
			<input type="checkbox" name="save_search" id="save_search" class="checkbox" value="1"'. $save_search_d .'> Share saved searches with My Broker<br />
			<input type="checkbox" name="fav_list" id="fav_list" class="checkbox" value="1"'. $fav_list_d .'> Share favorites with My Broker<br />
			<a href="javascript:void(0);" class="brokerpref button contact left t-center">Update</a>			
			<div class="spinnersmall nextline">&nbsp;</div>	
			</form>
			</div>			
			<div class="clear"></div>		
			';
		}else{
			$returntxt .= 'Want to connect a Broker? <a href="'. $cm->folder_for_seo .'select-broker/">Click Now</a>';
		}
        return $returntxt;
    }
	
	public function delete_user_yacht_favorites($yid, $user_id){
		global $db, $cm;
		$sql = "delete from tbl_yacht_favorites where user_id = '". $user_id ."' and yacht_id = '". $yid ."'";
        $db->mysqlquery($sql);
	}
	
    public function user_yacht_favorites($yid, $favopt, $opt = 0){
        global $db, $cm;
        $loggedin_member_id = $this->loggedin_member_id();
        $details_url = $cm->get_page_url($yid, "yacht");
        if ($favopt == 1){
            $check_favorites = $this->check_yacht_favorites($yid);
            if ($check_favorites == 0){
                $sql = "insert into tbl_yacht_favorites (user_id, yacht_id, reg_date) values ('". $loggedin_member_id ."', '". $yid ."', '". date("Y-m-d H:i:s") ."')";
                $db->mysqlquery($sql);
            }
            $sval = 'a';
			$optiontext = '<a id="favlist-'. $yid .'" yid="'. $yid .'" rtsection="0" href="javascript:void(0);" class="yachtfv removefavboat" title="Your favorite. Remove?"><i class="fas fa-star"></i></a>';
        }else{
			$this->delete_user_yacht_favorites($yid, $loggedin_member_id);
            $sval = 'd';
            $optiontext = '<a id="favlist-'. $yid .'" yid="'. $yid .'" rtsection="1" href="javascript:void(0);" class="yachtfv addfavboat" title="Add to favorites"><i class="far fa-star"></i></a>';
        }

        if ($opt == 0){
            $returnval[] = array(
                'retval' => $sval,
                'optiontext' => $optiontext
            );
            echo json_encode($returnval);
        }
    }

    public function check_yacht_favorites($yid){
        global $db;
        $loggedin_member_id = $this->loggedin_member_id();
        $sqltext = "select count(*) as ttl from tbl_yacht_favorites where user_id = '". $loggedin_member_id ."' and yacht_id = '". $yid ."'";
        $if_found = $db->total_record_count($sqltext);
        return $if_found;
    }

    public function user_yacht_delete($yid){
        global $db, $cm;
        $loggedin_member_id = $this->loggedin_member_id();
		$yacht_ar = $cm->get_table_fields('tbl_yacht', 'company_id, location_id, broker_id');
		$company_id = $yacht_ar[0]['company_id'];
		$location_id = $yacht_ar[0]['location_id'];
		$yachtuser = $yacht_ar[0]['broker_id'];
		
		$adminedit = $this->check_user_admin_permission($company_id, $location_id, $loggedin_member_id);		
        if ($adminedit == 1 OR $loggedin_member_id == $yachtuser OR $loggedin_member_id == 1){
            //can delete
            $sval = 'y';
            $optiontext = 'success';
            $this->delete_yacht($yid);
        }else{
            //cant delete
            $sval = 'n';
            $optiontext = 'Error! No permission.';
        }

        $returnval[] = array(
            'retval' => $sval,
            'optiontext' => $optiontext
        );
        echo json_encode($returnval);
    }
	
	public function get_broker_company_id($broker_id){
        global $cm;
        $company_id = $cm->get_common_field_name('tbl_user', 'company_id', $broker_id);        
        return $company_id;
    }
	
	public function get_broker_location_id($broker_id){
        global $cm;
        $location_id = $cm->get_common_field_name('tbl_user', 'location_id', $broker_id);        
        return $location_id;
    }
	
	public function get_location_address_array($location_id){
		global $cm;
		$location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone, lat_val, lon_val', $location_id);		       
        return $location_ar[0];
	}
	
	public function get_broker_address_array($broker_id){
        global $cm;		
		$type_id = $this->get_user_type($broker_id);
		$company_id = $this->get_broker_company_id($broker_id);		
		/*if ($type_id == 2){
			$location_id = $this->get_company_default_location($company_id);
		}*/
		
		if ($type_id == 2 OR $type_id == 3 OR $type_id == 4 OR $type_id == 5){
			$location_id = $this->get_broker_location_id($broker_id);
		}
		
		return $this->get_location_address_array($location_id);
    }
	
	public function can_access_user($id, $frompopup = 0){
		global $db, $cm;
		if ($id > 0){
			$loggedin_member_id = $this->loggedin_member_id();
			$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
			$cuser_type_id = $cuser_ar[0]["type_id"];
			$company_id = $cuser_ar[0]["company_id"];
			$location_id = $cuser_ar[0]["location_id"];
			
			$sqltext = "select count(*) as ttl from tbl_user where id = '". $id ."' and";
			if ($cuser_type_id == 2 OR $cuser_type_id == 3){
				//master admin and manager
				$sqltext .= " company_id = '". $company_id ."'";
			}
			
			if ($cuser_type_id == 4){
				//location admin
				$sqltext .= " company_id = '". $company_id ."' and location_id = '". $location_id ."'";
			}
			$rec_found = $db->total_record_count($sqltext);
			if ($rec_found == 0){
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;
			}
		}
	}
	
	public function can_access_yacht($yid, $frompopup = 0){
		global $db, $cm;
		if ($yid > 0){
			$loggedin_member_id = $this->loggedin_member_id();
			$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
			$cuser_type_id = $cuser_ar[0]["type_id"];
			$company_id = $cuser_ar[0]["company_id"];
			$location_id = $cuser_ar[0]["location_id"];

			if ($cuser_type_id == 1){
				$rec_found = 1;
			}elseif ($cuser_type_id == 6){
				$rec_found = 0;
			}else{			
				$sqltext = "select count(*) as ttl from tbl_yacht where id = '". $yid ."' and";
				if ($cuser_type_id == 2 OR $cuser_type_id == 3){
					//master admin and manager
					$sqltext .= " company_id = '". $company_id ."'";
				}
				
				if ($cuser_type_id == 4){
					//location admin
					$sqltext .= " company_id = '". $company_id ."' and location_id = '". $location_id ."'";
				}
				
				if ($cuser_type_id == 5){
					//agent
					$sqltext .= " company_id = '". $company_id ."' and location_id = '". $location_id ."' and broker_id = '". $loggedin_member_id ."'";
				}			
				$rec_found = $db->total_record_count($sqltext);
			}

			if ($rec_found == 0){
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;
			}
		}
	}
	
	public function can_access_location($id, $frompopup = 0){
		global $db, $cm;
		if ($id > 0){
			$loggedin_member_id = $this->loggedin_member_id();
			$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
			$cuser_type_id = $cuser_ar[0]["type_id"];
			$company_id = $cuser_ar[0]["company_id"];
			$location_id = $cuser_ar[0]["location_id"];
			
			$sqltext = "select count(*) as ttl from tbl_location_office where id = '". $id ."' and";
			if ($cuser_type_id == 2 OR $cuser_type_id == 3){
				//master admin and manager
				$sqltext .= " company_id = '". $company_id ."'";
			}
			
			if ($cuser_type_id == 4){
				//location admin
				$sqltext .= " company_id = '". $company_id ."' and location_id = '". $location_id ."'";
			}
			$rec_found = $db->total_record_count($sqltext);
			if ($rec_found == 0){
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;
			}
		}
	}
	
	//social media - user
	public function get_user_social_media($user_id){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select facebook_url, twitter_url, linkedin_url, youtube_url, googleplus_url, instagram_url, pinterest_url, blog_url from tbl_user where id = '". $cm->filtertext($user_id) ."'";
		$result = $db->fetch_all_array($sql);
		$row = $result[0];
		
		$socilatext = '';
		$facebook_url = htmlspecialchars($row['facebook_url']);
		$twitter_url = htmlspecialchars($row['twitter_url']);
		$linkedin_url = htmlspecialchars($row['linkedin_url']);
		$youtube_url = htmlspecialchars($row['youtube_url']);
		$googleplus_url = htmlspecialchars($row['googleplus_url']);
		$instagram_url = htmlspecialchars($row['instagram_url']);
		$pinterest_url = htmlspecialchars($row['pinterest_url']);
		$blog_url = htmlspecialchars($row['blog_url']);
		
		if ($facebook_url != ""){
			$socilatext .= '<a href="'. $facebook_url .'" target="_blank" title="Facebook"><i class="fab fa-facebook-square fa-fw"></i><span class="com_none">Facebook</span></a>';
		}
		if ($twitter_url != ""){
			$socilatext .= '<a href="'. $twitter_url .'" target="_blank" title="Twitter"><i class="fab fa-twitter-square fa-fw"></i><span class="com_none">Twitter</span></a>';
		}
		if ($linkedin_url != ""){
			$socilatext .= '<a href="'. $linkedin_url .'" target="_blank" title="LinkedIn"><i class="fab fa-linkedin fa-fw"></i><span class="com_none">Linkedin</span></a>';
		}
		if ($youtube_url != ""){
			$socilatext .= '<a href="'. $youtube_url .'" target="_blank" title="YouTube"><i class="fab fa-youtube-square fa-fw"></i><span class="com_none">YouTube</span></a>';
		}
		if ($googleplus_url != ""){
			$socilatext .= '<a href="'. $googleplus_url .'" target="_blank" title="Google Plus"><i class="fab fa-google-plus-square fa-fw"></i><span class="com_none">Google Plus</span></a>';
		}
		if ($instagram_url != ""){
			$socilatext .= '<a href="'. $instagram_url .'" target="_blank" title="Instagram"><i class="fab fa-instagram fa-fw"></i><span class="com_none">Instagram</span></a>';
		}
		if ($pinterest_url != ""){
			$socilatext .= '<a href="'. $pinterest_url .'" target="_blank" title="Pinterest"><i class="fab fa-pinterest-square fa-fw"></i><span class="com_none">Pinterest</span></a>';
		}
		if ($blog_url != ""){
			$socilatext .= '<a href="'. $blog_url .'" target="_blank" title="Blog"><i class="fas fa-rss-square fa-fw"></i><span class="com_none">Blog</span></a>';
		}
		
		if ($socilatext != ""){
			$returntext .= '<div class="commonsocial">'. $socilatext .'</div>';
		}		
		return $returntext;
	}
    //user section end
	
	//google event tracking code
	public function google_event_tracking_code($option, $tracklabel){
		global $cm;
		$gaeventtracking = '';
		$gaacc = $cm->get_systemvar("GAACC");
		if ($gaacc != ""){
			if ($option == "broker"){
				$category = 'Button';
				$action = 'Contact Broker';
			}
			$gaeventtracking = 'onClick="_gaq.push([\'_trackEvent\', \''. $category .'\', \''. $action .'\', \''. $tracklabel .'\']);" ';
		}
		return $gaeventtracking;
	}	

    //yacht section
	public function default_meta_info_boat(){
          global $db;
          $bsql = "select * from tbl_tag_boat";
          $bresult = $db->fetch_all_array($bsql);
          $brow = $bresult[0];
          return (object)$brow;
    }
	
	public function collect_meta_info_boat($param = array()){
		global $cm;
		
		//param	
		$m1 = $param["m1"];
		$m2 = $param["m2"];
		$m3 = $param["m3"];
		$manufacturer_name = $param["manufacturer_name"];
		$model = $param["model"];
		$year = $param["year"];
		$length = $param["length"];
		$overview = $param["overview"];
		$city = $param["city"];
		$state = $param["state"];
		$vessel_name = $param["vessel_name"];
		$company_id = round($param["company_id"], 0);
		//end
		
		$default_meta = $this->default_meta_info_boat();
		$length = $this->display_yacht_number_field($length, 8);
		$company_ar = $cm->get_table_fields('tbl_company', 'cname', $company_id);
        $cname = $company_ar[0]["cname"];
		
		if ($city == "Unknown"){
			$city = "";
		}
		
		if ($m1 == ""){ 		
			$m1 = $default_meta->m1;
			$m1 = str_replace("#year#", $year, $m1);
			$m1 = str_replace("#make#", $manufacturer_name, $m1);
			$m1 = str_replace("#model#", $model, $m1);
			$m1 = str_replace("#length#", $length, $m1);
			$m1 = str_replace("#companyname#", $cname, $m1);
			$m1 = str_replace("#city#", $city, $m1);
			$m1 = str_replace("#state#", $state, $m1);
			$m1 = str_replace("#vesselname#", $vessel_name, $m1);
		}
		if ($m2 == ""){
			$m2 = $default_meta->m2;
			if ($m2 == ""){
				$m2 = $cm->get_sort_content_description($overview, 150);
			}else{
				$m2 = str_replace("#year#", $year, $m2);
				$m2 = str_replace("#make#", $manufacturer_name, $m2);
				$m2 = str_replace("#model#", $model, $m2);
				$m2 = str_replace("#length#", $length, $m2);
				$m2 = str_replace("#companyname#", $cname, $m2);
				$m2 = str_replace("#city#", $city, $m2);
				$m2 = str_replace("#state#", $state, $m2);
				$m2 = str_replace("#vesselname#", $vessel_name, $m2);
			}
		}
		if ($m3 == ""){ 
			$m3 = $default_meta->m3;
			$m3 = str_replace("#year#", $year, $m3);
			$m3 = str_replace("#make#", $manufacturer_name, $m3);
			$m3 = str_replace("#model#", $model, $m3);
			$m3 = str_replace("#length#", $length, $m3);
			$m3 = str_replace("#companyname#", $cname, $m3);
			$m3 = str_replace("#city#", $city, $m3);
			$m3 = str_replace("#state#", $state, $m3);
			$m3 = str_replace("#vesselname#", $vessel_name, $m3);
		}
		
		$final_meta = array("m1" => $m1, "m2" => $m2, "m3" => $m3);
		return (object)$final_meta;
	}
	
	public function insert_yacht_video_link($yacht_id){
		global $db, $cm;
		$i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_video where yacht_id = '". $yacht_id ."'") + 1;
		$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);
		
		$name = $_POST["name"];
		$link_url = $_POST["link_url"];
		$video_type = round($_POST["video_type"], 0);
		
		if ($link_url != "" AND $video_type > 0){
			$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($link_url));
			$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, name, link_url, video_id, rank, status_id) values ('". $i_iiid ."', '". $yacht_id ."', '". $video_type ."', '". $cm->filtertext($name) ."', '". $cm->filtertext($link_url) ."', '". $cm->filtertext($video_id)."', '". $i_rank ."', 1)";
			$db->mysqlquery($sql);	
		}
	}
	
	public function insert_yacht_video_file($yacht_id, $frontfrom = 0){
		global $db, $cm, $fle;
		$filename = $_FILES['file']['name'];
		$wh_ok = $fle->check_file_ext($cm->allow_video_ext, $filename);
		if ($wh_ok == "y"){
			$i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_video where yacht_id = '". $yacht_id ."'") + 1;
			$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);			
			
			$video_type = 2;			
			$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, rank, status_id) values ('". $i_iiid ."', '". $yacht_id ."', '". $video_type ."', '". $i_rank ."', 1)";
			$db->mysqlquery($sql);
			
			$filename_tmp = $_FILES['file']['tmp_name'];
			$filename = $fle->uploadfilename($filename);
			$filename1 = $i_iiid."yacht".$filename;
			
			$target_path_main = "yachtvideo/";
			if ($frontfrom == 0){
            	$target_path_main = "../" . $target_path_main;
			}
			
			$target_path = $target_path_main . $cm->filtertextdisplay($filename1);
			$fle->fileupload($_FILES['file']['tmp_name'], $target_path);
			
			$sql = "update tbl_yacht_video set videopath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
        	$db->mysqlquery($sql);
			echo($_POST['index']);
		}
	}
	
	public function insert_yacht_attachment_file($yacht_id, $frontfrom = 0){
		global $db, $cm, $fle;
		$filename = $_FILES['file']['name'];
		$wh_ok = $fle->check_file_ext($cm->allow_attachment_ext, $filename);
		if ($wh_ok == "y"){
			$i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_file where yacht_id = '". $yacht_id ."'") + 1;
			//$i_iiid = $cm->get_unq_code("tbl_yacht_file", "id", 10);			
			$i_iiid = $cm->campaignid(30) . $yacht_id;
			$status_id = 1;
			
			$sql = "insert into tbl_yacht_file (id, 
			yacht_id, 
			status_id,
			rank) values ('". $cm->filtertext($i_iiid) ."', 
			'". $yacht_id ."', 
			'". $status_id ."', 
			'". $i_rank ."')";
			$db->mysqlquery($sql);
			
			
			$filename_tmp = $_FILES['file']['tmp_name'];
			$filename = $fle->uploadfilename($filename);
			$filename1 = $i_iiid."yacht".$filename;
				
			$target_path_main = "yachtfiles/";
			if ($frontfrom == 0){
            	$target_path_main = "../" . $target_path_main;
			}
			
			$target_path = $target_path_main . $cm->filtertextdisplay($filename1);
			$fle->fileupload($_FILES['file']['tmp_name'], $target_path);
			
			$sql = "update tbl_yacht_file set filepath = '".$cm->filtertext($filename1)."', originalname = '".$cm->filtertext($filename)."' where id = '". $i_iiid ."'";
        	$db->mysqlquery($sql);
			echo($_POST['index']);
		}
	}
	
	public function edit_yacht_video(){
        global $db, $cm;
        $im_thefilecount = round($_POST["im_thefilecount"], 0);
        for ($k = 0; $k < $im_thefilecount; $k++){
            $im_id = $_POST["id" . $k];
            $im_title = $_POST["im_title" . $k];            
            $sortorder = round($_POST["sortorder" . $k], 0);
            $sql = "update tbl_yacht_video set name = '". $cm->filtertext($im_title) ."'            
            , rank = '".$sortorder."' where id = '". $im_id ."'";
            $db->mysqlquery($sql);
        }
    }
	
	public function edit_yacht_attachment_file(){
        global $db, $cm;
        $im_thefilecount = round($_POST["im_thefilecount"], 0);
        for ($k = 0; $k < $im_thefilecount; $k++){
            $im_id = $_POST["id" . $k];
            $im_title = $_POST["im_title" . $k];
            $sortorder = round($_POST["sortorder" . $k], 0);
            $sql = "update tbl_yacht_file set title = '". $cm->filtertext($im_title) ."'
            , rank = '".$sortorder."' where id = '". $im_id ."'";
            $db->mysqlquery($sql);
        }
    }
	
	public function update_yacht_attachment_file_rank(){
		   global $db, $cm;
		   parse_str($_POST['data'], $recOrder);
		   $i = 1;
		   foreach ($recOrder['item'] as $value) {
			   $sql = "update tbl_yacht_file set rank = '". $i ."' where id = '". $value ."'";
			   $db->mysqlquery($sql);
			   $i++;			
		   }
	}
	
	public function delete_yacht_attachment_file($imid){
		   global $db, $cm;
		   $fimg1 = $db->total_record_count("select filepath as ttl from tbl_yacht_file where id = '". $cm->filtertext($imid) ."'");
		   $this->delete_any_files($fimg1, 'yachtfiles');
		   
		   $sql = "delete from tbl_yacht_file where id = '". $cm->filtertext($imid) ."'";
		   $db->mysqlquery($sql);
		   echo 'y';
	}
	
	public function count_attachment_file($yacht_id){
		global $db;
		$sql = "select count(*) as ttl from tbl_yacht_file where yacht_id  = '". $yacht_id ."'";
		$linkfound = $db->total_record_count($sql);
		return $linkfound;
	}
	
	public function display_attachment_file($yacht_id){
		global $db, $cm;
		$returntext = '';
		$el_sql = "select * from tbl_yacht_file where yacht_id = '". $yacht_id ."' order by rank";
        $el_result = $db->fetch_all_array($el_sql);
        $el_found = count($el_result);
		if ($el_found > 0){
			$returntext .= '
			<h3 class="subtitle">Attachment</h3>
			<ul>
			';
			foreach($el_result as $el_row){	
				$fileid  = $el_row['id'];				
				$title  = $el_row['title'];
				$filepath = $el_row['filepath'];
				$originalname = $el_row['originalname'];
				if ($title == ""){ $title = "Attachment"; }
				$returntext .= '
				<li>
				<a href="'. $cm->site_url .'/?fcapi=downloadfiles&fileid='. $fileid .'&opt=1">'. $title .'</a>
				</li>
				';				
			}
			$returntext .= '
			</ul>
			';
		}		
		return $returntext;		
	}
	
    public function feet_to_meter($feetval){
        $convertval = $this->ft_to_meter;
        $newval = $feetval * $convertval;
        $newval = round($newval, 2);
        return $newval;
    }
	
	public function explode_feet_inchs($feetval){
        $convertval = $this->ft_to_inchs;
		$intpart = (int)$feetval;
		$dpart = $feetval - $intpart;
        $dpart = $dpart * $convertval;
        $dpart = round($dpart);
        $ftinchs = array(
            'ft' => $intpart,
            'inchs' => $dpart
        );
		return $ftinchs;
    }
	
	public function implode_feet_inchs($ft, $inchs){
        $convertval = $this->ft_to_inchs;
		$fullinchs = ($ft * $convertval) + $inchs;
		$fullft = $fullinchs / $convertval;
        $fullft = round($fullft, 2);
		return $fullft;
    }

    public function get_yachtstatus_combo($status_id = 0, $azop = 0){
        global $db;
        $vsql = "select id, name from tbl_yacht_status where status = 'y' order by id";
        $vresult = $db->fetch_all_array($vsql);        		
		foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($status_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
	public function get_yachtstatus_combo_wthout_preview($status_id = 0){
        global $db;
        $vsql = "select id, name from tbl_yacht_status where id != 4 and status = 'y' order by id";
        $vresult = $db->fetch_all_array($vsql);        		
		foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($status_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function boat_listing_type_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "");
		$val_ar[] = array("name" => "Our Listings");
		$val_ar[] = array("name" => "Co-Brokerage - Yacht Feed");
		$val_ar[] = array("name" => "Co-Brokerage - Catamaran Feed");
		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}	
	
	public function get_boat_listing_type_combo($custom_owned = 1){
        global $db;
		$val_ar = json_decode($this->boat_listing_type_val());
		$returntext = '';
		
		foreach($val_ar as $key => $val_row){
			if ($key > 0){
				$cname = $val_row->name;
				$bck = '';
				if ($custom_owned == $key){
					$bck = ' selected="selected"';	
				}			
				$returntext .= '<option value="'. $key .'"'. $bck .'>'. $cname .'</option>';
			}
        }
		
		return $returntext;
    }
	
	public function boat_listing_type_val_short(){	
		$val_ar = array();
		$val_ar[] = array("name" => "");
		$val_ar[] = array("name" => "Our Listings - API");
		$val_ar[] = array("name" => "Co-Brokerage - API");
		$val_ar[] = array("name" => "Our Listings - MANUAL");
		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_boat_listing_type_combo_short($custom_owned = 1){
        global $db;
		$val_ar = json_decode($this->boat_listing_type_val_short());
		$returntext = '';
		
		foreach($val_ar as $key => $val_row){
			if ($key > 0){
				$cname = $val_row->name;
				$bck = '';
				if ($custom_owned == $key){
					$bck = ' selected="selected"';	
				}			
				$returntext .= '<option value="'. $key .'"'. $bck .'>'. $cname .'</option>';
			}
        }
		
		return $returntext;
    }

    public function get_manufacturer_combo($manufacturer_id, $frontfrom = 0){
        global $db;
        $vsql = "select id, name from tbl_manufacturer where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
        ?>
            <option value="<?php echo $c_id; ?>"<?php if ($manufacturer_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }

    public function get_year_combo($year, $azop = 0){
		$returntext = '';
        for ($xk = $this->y_end_year; $xk >= $this->y_start_year; $xk--){
			$bck = '';
			if ($year == $xk){
				$bck = ' selected="selected"';	
			}
            $returntext .= '<option value="'. $xk .'"'. $bck .'>'. $xk .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_category_combo($type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_category where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_condition_combo($conrition_id = 0, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_condition where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($conrition_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_hull_material_combo($hull_material_id, $frontfrom = 0){
        global $db;
        $vsql = "select id, name from tbl_hull_material where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($hull_material_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }

    public function get_hull_type_combo($hull_type_id, $frontfrom = 0){
        global $db;
        $vsql = "select id, name from tbl_hull_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($hull_type_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }

    public function get_engine_make_combo($engine_make_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_engine_make where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($engine_make_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_engine_type_combo($engine_type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_engine_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($engine_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_drive_type_combo($drive_type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_drive_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($drive_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

    public function get_fuel_type_combo($fuel_type_id, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_fuel_type where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($fuel_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }

	public function get_type_combo_parent_old_code($type_id, $category_id = 0, $azop = 0, $frontfrom = 0){
        global $db;
		$returntxt = '';
		$returnarray = array();
		
		$query_sql = "select distinct a.*";
		$query_form = " from tbl_type as a,";
		$query_where = " where";
		$query_where .= " a.parent_id = 0 and";
		
		if ($category_id > 0){
			$query_form .= " tbl_type_category_assign as b,";
    		$query_where .= " a.id = b.type_id and b.category_id = '". $category_id ."' and";
		}
		
        if ($frontfrom == 1){
            $query_where .= " status_id = 1 and";
        }
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$vsql = $query_sql . $query_form . $query_where;
        $vsql .= " order by rank, name";
        $vresult = $db->fetch_all_array($vsql);		
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			if ($azop == 0){
				$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id
				);				
			}            
        }
		
		if ($azop == 0){
			return $returntxt;
		}else{
			$returnval[] = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}
    }
	public function get_type_combo_parent($type_id, $category_id = 0, $azop = 0, $frontfrom = 0){
        global $db;
		$returntxt = '';
		$returnarray = array();
		
		//Special Type - Comes first
		$query_sql = "select *";
		$query_form = " from tbl_type,";
		$query_where = " where";
		$query_where .= " parent_id = 0 and";
		
		$query_where .= " id IN (select ycdataid from tbl_boat_type_specific) and";
		
		if ($frontfrom == 1){
            $query_where .= " status_id = 1 and";
        }
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$vsql = $query_sql . $query_form . $query_where;
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);		
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			if ($azop == 0){
				$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id
				);				
			}            
        }
		//end
		
		//Other Types
		$query_sql = "select *";
		$query_form = " from tbl_type,";
		$query_where = " where";
		$query_where .= " parent_id = 0 and";
		
		$query_where .= " id NOT IN (select ycdataid from tbl_boat_type_specific) and";
		
		if ($frontfrom == 1){
            $query_where .= " status_id = 1 and";
        }
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$vsql = $query_sql . $query_form . $query_where;
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
		$vfound = count($vresult);
		
		if ($vfound > 0){
			$returntxt .= '<option disabled>──────────</option>';
		}
		
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($type_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			if ($azop == 0){
				$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id
				);				
			}            
        }
		//end
		
		if ($azop == 0){
			return $returntxt;
		}else{
			$returnval[] = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}
    }

    public function get_type_combo_child($type_id, $frontfrom = 0){
        global $db;
        $vsql = "select id, name from tbl_type where parent_id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($category_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
    }

    public function get_type_combo($type_id, $type_parent_id, $onlytop = 0, $frontfrom = 0, $returnoption = 0){
        global $db;
		$returntext = '';
        $ssql = "select id, parent_id, name, cat_level from tbl_type where parent_id = '". $type_parent_id ."'";
        if ($frontfrom == 1){
            $ssql .= " and status_id = 1";
        }
        $ssql .= " order by name";
        $sresult = $db->fetch_all_array($ssql);
        $sfound = count($sresult);
        foreach($sresult as $srow){
            $c_id = $srow['id'];
            $c_parent_id = $srow['parent_id'];
            $cname = $srow['name'];
            $cat_level = $srow['cat_level'];
            $b_level = ($cat_level - 1);
            $extra_sps = "";
            for ($ss = 0; $ss < $b_level; $ss++){ $extra_sps .= "&nbsp;&nbsp;&nbsp;"; }
			
			$vck = '';
			if ($category_id == $c_id){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $c_id . '"'. $vck .'>'. $extra_sps . $cname .'</option>';
		
            if ($onlytop == 0){
                $returntext .= $this->get_type_combo($type_id, $c_id, $onlytop, $frontfrom, 1);
            }
        }
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
    public function get_common_number_combo($numberval, $upto = 100, $returnoption = 0){
		$returntext = '';
		for ($xk = 1; $xk <= $upto; $xk++){
			$vck = '';
			if ($numberval == $xk){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $xk . '"'. $vck .'>'. $xk .'</option>';
		}
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
	public function set_sold_boat_days_val(){
		$returnar = array();		
		$returnar[] = array(
            'name' => 'Always Display',
            'solddateval' => 0
        );
		$returnar[] = array(
            'name' => '30 days',
            'solddateval' => 30
        );
		$returnar[] = array(
            'name' => '60 days',
            'solddateval' => 60
        );
		$returnar[] = array(
            'name' => '90 days',
            'solddateval' => 90
        );
		$returnar[] = array(
            'name' => '180 days',
            'solddateval' => 180
        );
		$returnar[] = array(
            'name' => '365 days',
            'solddateval' => 365
        );
		$returnar[] = array(
            'name' => '2 Year',
            'solddateval' => 730
        );
		return json_encode($returnar);
	}
	public function get_sold_boat_days_combo($numberval){
		$solddatear = $this->set_sold_boat_days_val();
		$solddatear = json_decode($solddatear);
		
		$returntext = '';
		foreach($solddatear as $solddatear_row){
			$name = $solddatear_row->name;
			$solddateval = $solddatear_row->solddateval;
			
			$vck = '';
			if ($numberval == $solddateval){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $solddateval . '"'. $vck .'>'. $name .'</option>';
		}
		
		return $returntext;
	}
	
	public function get_common_percent_combo($numberval, $upto = 50, $start = 5, $incr = 5, $returnoption = 0){
		$returntext = '';
		for ($xk = $start; $xk <= $upto; $xk+=$incr){
			$vck = '';
			if ($numberval == $xk){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $xk . '"'. $vck .'>'. $xk .' %</option>';
		}
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	
	public function get_broker_combo_all($broker_id, $company_id, $azop = 0){
		global $db, $cm;
		$returntxt = '';
		$returnarray = array();
		
		$vsql = "select id, fname, lname, type_id from tbl_user where company_id = '". $company_id ."'";	
		$vsql .= " and status_id = 2 and (type_id = 2 OR type_id = 3 OR type_id = 4 OR type_id = 5) order by type_id, uid";
		$vresult = $db->fetch_all_array($vsql);
		
		$check_type_id = 0;
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$type_id = $vrow['type_id'];
			$cname = $vrow['fname'] . ' ' . $vrow['lname'];				
			
			if ($check_type_id != $type_id){				
				$typenm = $cm->get_common_field_name('tbl_user_type', 'name', $type_id);				
			}
			
			$cname .= ' - ' . $typenm;
			
			$bck = '';
			if ($broker_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			if ($azop == 0){
				$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id
				);				
			}						
			$check_type_id = $type_id;		
		}
		
		if ($azop == 0){
			return $returntxt;
		}else{
			$returnval[] = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}
	}

    public function get_broker_combo($broker_id, $company_id, $location_id, $azop = 0){
        global $db, $cm;
		$returntxt = '';
		$returnarray = array();
		$msql = "select id, fname, lname, type_id from tbl_user where company_id = '". $company_id ."'";	
		
		$vsql = $msql;	
		$vsql .= " and status_id = 2 and (type_id = 2 OR type_id = 3) order by type_id, uid";
		$vresult = $db->fetch_all_array($vsql);
		$check_type_id = 0;
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$type_id = $vrow['type_id'];
			$cname = $vrow['fname'] . ' ' . $vrow['lname'];				
			
			if ($check_type_id != $type_id){				
				$typenm = $cm->get_common_field_name('tbl_user_type', 'name', $type_id);				
			}
			
			$cname .= ' - ' . $typenm;
			
			$bck = '';
			if ($broker_id == $c_id){
				$bck = ' selected="selected"';	
			}
			
			if ($azop == 0){
				$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
			}else{
				$returnarray[] = array(
					'text' => $cname,
					'textval' => $c_id
				);				
			}						
			$check_type_id = $type_id;		
		}
		
		if ($location_id > 0){
		
			$vsql = $msql;
			$vsql .= " and location_id = '". $location_id ."'";	
			$vsql .= " and status_id = 2 and (type_id = 4 OR type_id = 5) order by type_id, uid";
			$vresult = $db->fetch_all_array($vsql);		
			$check_type_id = 0;
			foreach($vresult as $vrow){
				$c_id = $vrow['id'];
				$type_id = $vrow['type_id'];
				$cname = $vrow['fname'] . ' ' . $vrow['lname'];		
				
				if ($check_type_id != $type_id){				
					$typenm = $cm->get_common_field_name('tbl_user_type', 'name', $type_id);				
				}
				
				$cname .= ' - ' . $typenm;
				
				$bck = '';
				if ($broker_id == $c_id){
					$bck = ' selected="selected"';	
				}
				
				if ($azop == 0){
					$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';				
				}else{
					$returnarray[] = array(
						'text' => $cname,
						'textval' => $c_id
					);				
				}						
				$check_type_id = $type_id;		
			}	
		}
			
		if ($azop == 0){
			return $returntxt;
		}else{
			$returnval[] = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}
		
    }

    public function get_currency_combo($currency_id, $frontfrom = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, currency, currency_code, convert_value from tbl_currency where status_id = 1";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['currency'];
            $convert_value = $vrow['convert_value'];
			$bck = '';
			if ($currency_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $convert_value .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function get_unit_measure_combo($unit_measure_id, $frontfrom = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_unit_measure where status_id = 1";        
        $vsql .= " order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($unit_measure_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function get_custom_label_combo($custom_label_id = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_custom_label where status_id = 1";        
        $vsql .= " order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($custom_label_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
   }
   
   public function get_charter_combo($charter = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_charter where";		
        $vsql .= " status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            $bck = '';
			if ($charter == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
   
   public function get_price_tag_combo($price_tag_id){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_price_tag where status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];	
			$bck = '';
			if ($price_tag_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }

    public function get_yacht_address($yacht_id, $opt = 0){
        global $cm;
        $addressfull = '';
        $yacht_ar = $cm->get_table_fields('tbl_yacht', 'address, city, state_id, country_id, zip', $yacht_id);
        $address = $yacht_ar[0]['address'];
        $country_id = $yacht_ar[0]['country_id'];
        $city = $yacht_ar[0]['city'];

        $country_code = $cm->get_common_field_name('tbl_country', 'code', $country_id);
        $zip = $yacht_ar[0]['zip'];

        if ($country_id == 1){
            $state_name = $cm->get_common_field_name('tbl_state', 'code', $yacht_ar[0]['state_id']);
        }else{
            $state_name = $cm->get_common_field_name('tbl_yacht', 'state', $yacht_id);
        }

        if ($address != "" AND $opt == 1){
            $addressfull .= $address . ', ';
        }

        if ($city != ""){
            $addressfull .= $city . ', ';
        }

        if ($state_name != ""){
            $addressfull .= $state_name . ', ';
        }

        if ($zip != "" AND $opt == 1){
            $addressfull .= $zip . ', ';
        }

        if ($country_code != "" AND $opt != 2){
            $addressfull .= $country_code . ', ';
        }
        $addressfull = rtrim ($addressfull, ', ');
        return $addressfull;
    }

    public function get_lat_lon($check_id, $getoption){
		global $cm;
		if ($getoption == 2){
			//location			
			$location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone', $check_id);		       
        	$address = $location_ar[0]["address"];
			$city = $location_ar[0]["city"];
			$state = $location_ar[0]["state"];
			$state_id = $location_ar[0]["state_id"];
			$country_id = $location_ar[0]["country_id"];
			$zip = $location_ar[0]["zip"];					
			$addressfull = $this->com_address_format($address, $city, $state, $state_id, $country_id,$zip);
		}else{
			//yacht
			$addressfull = $this->get_yacht_address($check_id, 1);
		}      
        $prepAddr = str_replace(' ','+',$addressfull);

		$geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$prepAddr.'&key=' . $cm->geocodingkey);
        $output= json_decode($geocode);
        $lat = $output->results[0]->geometry->location->lat;
        $long = $output->results[0]->geometry->location->lng;

        $latlon = array();
        $latlon["lat"] = $lat;
        $latlon["lon"] = $long;
        return $latlon;
    }

    public function get_total_rec_table($tablenm, $extraq = ""){
        global $db;
        $total_rec = $db->total_record_count("select count(*) as ttl from ". $tablenm ." ". $extraq ."");
        return $total_rec;
    }

    public function yacht_type_assign($yacht_id){
        global $db;
        $sql = "delete from tbl_yacht_type_assign where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);

        $type_id = round($_POST["type_id"], 0);
        if ($type_id > 0){
            $sql = "insert into tbl_yacht_type_assign (yacht_id, type_id) values ('". $yacht_id ."', '". $type_id ."')";
            $db->mysqlquery($sql);
        }
    }

    public function yacht_engine_assign($yacht_id){
        global $db;
        $total_rec = $this->get_total_rec_table('tbl_engine');
        $max_engine = $this->max_engine;

        $sql = "delete from tbl_yacht_engine_assign where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);

        $k = 0;
        for ($i = 0; $i < $total_rec; $i++){
            $engine_id = round($_POST["engine_id".$i], 0);
            if ($engine_id > 0){
                if ($k < $max_engine){
                    $sql = "insert into tbl_yacht_engine_assign (yacht_id, engine_id) values ('". $yacht_id ."', '". $engine_id ."')";
                    $db->mysqlquery($sql);
                }
                $k++;
            }
        }
    }

	public function yacht_external_link_assign($yacht_id){
		global $db, $cm;
		$total_external_link = round($_POST["total_external_link"], 0);		
		$sql = "delete from  tbl_yacht_external_link where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
		
		$external_link_rank = 1;
		for ($i = 1; $i <= $total_external_link; $i++){
			$ex_link_title = $_POST["ex_link_title" . $i];
			$ex_link_url = $_POST["ex_link_url" . $i];
			$ex_link_description = $_POST["ex_link_description" . $i];
			if ($ex_link_title != "" AND $ex_link_url != ""){
				$ex_link_url = $cm->format_url_txt($ex_link_url);
				$sql = "insert into tbl_yacht_external_link (yacht_id, name, link_url, link_description, rank) values ('". $yacht_id ."', '". $cm->filtertext($ex_link_title) ."', '". $cm->filtertext($ex_link_url) ."', '". $cm->filtertext($ex_link_description) ."', '". $external_link_rank ."')";
                $db->mysqlquery($sql);
				$external_link_rank++;
			}
		}
	}
	
	public function yacht_external_link_display_list_main($yacht_id, $frontfrom = 0){
        global $db, $cm;
        $returntext = '';
        $el_sql = "select * from tbl_yacht_external_link where yacht_id  = '". $yacht_id ."' order by rank";
        $el_result = $db->fetch_all_array($el_sql);
        $el_found = count($el_result);
		$rc_count = 1;
        if ($el_found > 0){			
			foreach($el_result as $el_row){					
				$link_title  = $el_row['name'];
				$link_url = $el_row['link_url'];
				$link_description = $el_row['link_description'];
				$link_rank = $el_row['rank'];
				if ($frontfrom == 0){
					$delpath = '';
					$returntext .= '
					<tr class="rowind'. $rc_count .'">
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Title:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_title'. $rc_count .'" name="ex_link_title'. $rc_count .'" value="'. $link_title .'" class="inputbox inputbox_size4" /></td>
						
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>URL:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_url'. $rc_count .'" name="ex_link_url'. $rc_count .'" value="'. $link_url .'" class="inputbox inputbox_size4" /></td>
						
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Description:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_description'. $rc_count .'" name="ex_link_description'. $rc_count .'" value="'. $link_description .'" class="inputbox inputbox_size4" /></td>
						<td width="25" align="left" valign="top" class="tdpadding1"><a class="ex_link_del" isdb="1" yval="'. $rc_count .'" yid="'. $yacht_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a></td>
					</tr>
				';
				}else{
					$delpath = $cm->folder_for_seo;
					$returntext .= '
					<li class="left rowind'. $rc_count .'">
						<p>Title</p>
						<input type="text" id="ex_link_title'. $rc_count .'" name="ex_link_title'. $rc_count .'" value="'. $link_title .'" class="input" />
					</li>
					
					<li class="right rowind'. $rc_count .'">
						<p>URL</p>
						<input type="text" id="ex_link_url'. $rc_count .'" name="ex_link_url'. $rc_count .'" value="'. $link_url .'" class="input" />
					</li>
					
					<li class="rowind'. $rc_count .'">
						<p>Description</p>
						<input type="text" id="ex_link_description'. $rc_count .'" name="ex_link_description'. $rc_count .'" value="'. $link_description .'" class="input" />
					</li>
					
					<li class="rowind'. $rc_count .'">
						<a class="ex_link_del" isdb="1" yval="'. $rc_count .'" yid="'. $yacht_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a>
					</li>
					';
				}
				$rc_count++;
			}            
        }
		
		$returnval = array(
            'displaytext' => $returntext,
			'totalrecord' => $rc_count
        );
        return $returnval;
    }
	
	public function yacht_external_link_display_list($yacht_id, $frontfrom = 0){
		$returntext = '';
		$yacht_external_link_details = $this->yacht_external_link_display_list_main($yacht_id, $frontfrom);
		$total_external_link = $yacht_external_link_details['totalrecord'];
		if ($frontfrom == 0){
			$returntext .= '
				<table id="rowholder" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
				'. $yacht_external_link_details['displaytext'] .'';
				
				if ($total_external_link == 1){
					$returntext .= '
					<tr class="rowind1">
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Title:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_title1" name="ex_link_title1" value="" class="inputbox inputbox_size4" /></td>
						
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>URL:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_url1" name="ex_link_url1" value="" class="inputbox inputbox_size4" /></td>
						
						<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Description:</td>
						<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_description1" name="ex_link_description1" value="" class="inputbox inputbox_size4" /></td>
						<td width="25" align="left" valign="top" class="tdpadding1">&nbsp;&nbsp;</td>
					</tr>
					';
				}
				
			$returntext .= '
				</table>
			';
			
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                	<td width="" align="left" valign="top" class="tdpadding1"><button type="button" class="addrow butta"><span class="addIcon butta-space">Add New</span></button></td>
                </tr>
            </table>
			<input type="hidden" value="'. $total_external_link .'" id="total_external_link" name="total_external_link" />
			';
			
		}else{
			$returntext .= '
			<ul id="rowholder" class="form">
			'. $yacht_external_link_details['displaytext'] .'';
			if ($total_external_link == 1){
				$returntext .= '
				<li class="left">
                    <p>Title</p>
                    <input type="text" id="ex_link_title1" name="ex_link_title1" value="" class="input" />
                </li>
				
				<li class="right">
                    <p>URL</p>
                    <input type="text" id="ex_link_url1" name="ex_link_url1" value="" class="input" />
                </li>
				
				<li>
                    <p>Description</p>
                    <input type="text" id="ex_link_description1" name="ex_link_description1" value="" class="input" />
                </li>
				';
			}
			$returntext .= '				
			</ul>
			<a href="javascript:void(0);" class="addrow icon-add">Add New Link</a>
			<input type="hidden" value="'. $total_external_link .'" id="total_external_link" name="total_external_link" />
			';
		}		
		return $returntext;
	}
	
	public function delete_boat_external_link($yacht_id, $del_pointer){
		global $db;
		$sql = "delete from tbl_yacht_external_link where yacht_id = '". $yacht_id ."' and rank = '". $del_pointer ."'";
        $db->mysqlquery($sql);
	}
	
	public function count_yacht_external_link($yacht_id){
		global $db;
		$sql = "select count(*) as ttl from tbl_yacht_external_link where yacht_id  = '". $yacht_id ."'";
		$linkfound = $db->total_record_count($sql);
		return $linkfound;
	}
	
	public function display_yacht_external_link($yacht_id){
		global $db, $cm;		
		$returntext = '';
		$el_sql = "select * from tbl_yacht_external_link where yacht_id  = '". $yacht_id ."' order by rank";
        $el_result = $db->fetch_all_array($el_sql);
        $el_found = count($el_result);
		if ($el_found > 0){
			$returntext .= '
			<h3 class="subtitle">External Links</h3>
			<ul>
			';
			foreach($el_result as $el_row){					
				$link_title  = $el_row['name'];
				$link_url = $el_row['link_url'];
				$link_description = $el_row['link_description'];
				if ($link_description != ""){ $link_description = '<p>'. $link_description .'</p>'; }
				$returntext .= '
				<li>
				<a href="'. $link_url .'" target="_blank">'. $link_title .'</a>'. $link_description .'
				</li>
				';				
			}
			$returntext .= '
			</ul>
			';
		}		
		return $returntext;		
	}

    public function edit_yacht_image(){
        global $db, $cm;
        $im_thefilecount = round($_POST["im_thefilecount"], 0);
        for ($k = 0; $k < $im_thefilecount; $k++){
            $im_id = $_POST["id" . $k];
            $im_title = $_POST["im_title" . $k];
            $im_descriptions = $_POST["im_descriptions" . $k];
            $sortorder = round($_POST["sortorder" . $k], 0);
            $sql = "update tbl_yacht_photo set im_title = '". $cm->filtertext($im_title) ."'
            , im_descriptions = '". $cm->filtertext($im_descriptions) ."'
            , rank = '".$sortorder."' where id = '". $im_id ."'";
            $db->mysqlquery($sql);
        }
    }
	
	public function update_yacht_image_rank(){
		   global $db, $cm;
		   parse_str($_POST['data'], $recOrder);
		   $i = 1;
		   foreach ($recOrder['item'] as $value) {
			   $sql = "update tbl_yacht_photo set rank = '". $i ."' where id = '". $value ."'";
			   $db->mysqlquery($sql);
			   $i++;			
		   }
	}

    public function add_yacht_image($iiid, $frontfrom = 0){
        global $db, $cm, $fle;
		$crop_option = round($_POST["crop_option"], 0);
		$rotateimage = round($_POST["rotateimage"], 0);
        if(isset($_FILES['imgpath']['name']) && $_FILES['imgpath']['name'] != ''){
            $totlaImage = count($_FILES['imgpath']['name']);
            for($i = 0; $i < $totlaImage; $i++){
                $filename = $_FILES['imgpath']['name'][$i] ;
                $wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
                if ($wh_ok == "y"){
					$listing_no = $this->get_yacht_no($iiid);
                    $i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_photo where yacht_id = '". $iiid ."'") + 1;
                    $i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
                    $sql = "insert into tbl_yacht_photo (id, yacht_id, rank, status_id) values ('". $i_iiid ."', '". $iiid ."', '". $i_rank ."', 1)";
                    $db->mysqlquery($sql);

                    $filename_tmp = $_FILES['imgpath']['tmp_name'][$i];
                    $filename = $fle->uploadfilename($filename);
                    $filename1 = $i_iiid."yacht".$filename;

                    $target_path_main = "yachtimage/" . $listing_no . "/";
                    if ($frontfrom == 0){
                        $target_path_main = "../" . $target_path_main;
                    }

                    //thumbnail image
                    $r_width = $cm->yacht_im_width_t;
                    $r_height = $cm->yacht_im_height_t;
                    $target_path = $target_path_main;
                    if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}

                    //big image
                    $r_width = $cm->yacht_im_width_b;
                    $r_height = $cm->yacht_im_height_b;
                    $target_path = $target_path_main . "big/";
                    if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}

                    //bigger image
                    $r_width = $cm->yacht_im_width;
                    $r_height = $cm->yacht_im_height;
                    $target_path = $target_path_main . "bigger/";
                    if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
					
					//slider image
                    $r_width = $cm->yacht_im_width_sl;
                    $r_height = $cm->yacht_im_height_sl;
                    $target_path = $target_path_main . "slider/";
					if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
					
					//original image store
					$target_path = $target_path_main . 'original/';
					$target_path = $target_path . $cm->filtertextdisplay($filename1);
					$fle->fileupload($filename_tmp, $target_path);
					
					//rotate original image
					if ($rotateimage > 0){
						$im = @ImageCreateFromJPEG ($target_path);
						$im = imagerotate($im, $rotateimage, 0);
						@ImageJPEG ($im, $target_path, 100);
					}

                    //$fle->filedelete($filename_tmp);
                    $sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
                    $db->mysqlquery($sql);
                }
            }
        }
    }
	
	public function rotate_boat_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$crop_option = round($_POST["hardcrop"], 0);
		$rotateimage = round($_POST["v"], 0);
		$frontfrom = round($_POST["frontfrom"], 0);
		
		$yachtdet = $cm->get_table_fields('tbl_yacht_photo', 'yacht_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$filename1 = $oldfilename = $yachtdet->imgpath;
		$yacht_id = $yachtdet->yacht_id;
		$listing_no = $this->get_yacht_no($yacht_id);		
		
		$target_path_main = "yachtimage/" . $listing_no . "/";
		if ($frontfrom == 0){
			$target_path_main = "../" . $target_path_main;
		}
		
		$filename_tmp = $target_path_main . "original/" . $filename1;
		$filename1 = $fle->create_different_file_name($filename1);
		$original_filename_rename = $target_path_main . "original/" . $filename1;
		
		//thumbnail image
		$r_width = $cm->yacht_im_width_t;
		$r_height = $cm->yacht_im_height_t;
		$target_path = $target_path_main;
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}

		//big image
		$r_width = $cm->yacht_im_width_b;
		$r_height = $cm->yacht_im_height_b;
		$target_path = $target_path_main . "big/";
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}

		//bigger image
		$r_width = $cm->yacht_im_width;
		$r_height = $cm->yacht_im_height;
		$target_path = $target_path_main . "bigger/";
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		//slider image
		$r_width = $cm->yacht_im_width_sl;
		$r_height = $cm->yacht_im_height_sl;
		$target_path = $target_path_main . "slider/";
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		//rotate original image
		if ($rotateimage > 0){
			$im = @ImageCreateFromJPEG ($filename_tmp);
			$im = imagerotate($im, $rotateimage, 0);
			@ImageJPEG ($im, $filename_tmp, 100);
		}
		
		//rename original image
		$fle->rename_existing_file($filename_tmp, $original_filename_rename);
		
		//remove existing file
		$this->delete_yacht_image($oldfilename, $yacht_id);
		
		//update filename
		$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $cm->filtertext($imid) ."'";
        $db->mysqlquery($sql);
		
		//output image
		$imgpath_d = $target_path_main . "" . $filename1 . "?t=" . time();
		return $imgpath_d;
	}

    public function add_delete_yacht_extra_info($yacht_id, $workoption = 1){
        global $db;

        if ($workoption == 1){
            $sql = "insert into tbl_yacht_dimensions_weight (yacht_id) values ('". $yacht_id ."')";
            $db->mysqlquery($sql);

            $sql = "insert into tbl_yacht_engine (yacht_id) values ('". $yacht_id ."')";
            $db->mysqlquery($sql);

            $sql = "insert into tbl_yacht_tank (yacht_id) values ('". $yacht_id ."')";
            $db->mysqlquery($sql);

            $sql = "insert into tbl_yacht_accommodation (yacht_id) values ('". $yacht_id ."')";
            $db->mysqlquery($sql);

            $sql = "insert into tbl_yacht_keywords (yacht_id) values ('". $yacht_id ."')";
            $db->mysqlquery($sql);
        }else{
            $sql = "delete from tbl_yacht_dimensions_weight where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_yacht_engine where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_yacht_tank where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_yacht_accommodation where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_yacht_keywords where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

            $sql = "delete from tbl_yacht_featured where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);

			$sql = "delete from tbl_yacht_external_link where yacht_id = '". $yacht_id ."'";
            $db->mysqlquery($sql);
        }
    }

    public function update_sold_yacht_display_date($yacht_id, $sold_day_no, $set_sold_date = ''){
        global $db, $cm;
        $yacht_ar = $cm->get_table_fields('tbl_yacht', 'status_id, sold_date', $yacht_id);
        $status_id = $yacht_ar[0]['status_id'];
        $sold_date = $yacht_ar[0]['sold_date'];

        if ($status_id == 3){
			if ($set_sold_date != ""){
				$sold_date = $set_sold_date;
			}else{
				if ($sold_date == "0000-00-00" OR $sold_date == ""){
					$sold_date = date("Y-m-d");
				}
			}
            $sold_date_n = strtotime($sold_date);
			if ($sold_day_no > 0){
				$display_upto_n = $sold_date_n + ($sold_day_no * 24 * 3600);
				$display_upto = date("Y-m-d", $display_upto_n);
			}else{
				 $display_upto = $cm->default_future_date;
			}
        }else{
            $sold_date = "";
            $display_upto = $cm->default_future_date;
        }

        $sql = "update tbl_yacht set sold_date = '". $sold_date ."'
        , display_upto = '". $display_upto ."' where id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
    }

    public function delete_yacht($yacht_id){
        global $db, $fle;
		$listing_no = $this->get_yacht_no($yacht_id);
		$this->delete_yacht_image_all($yacht_id); 
		$this->delete_yacht_video_all($yacht_id);
		$this->delete_yacht_attachment_all($yacht_id);
        $this->add_delete_yacht_extra_info($yacht_id, 2);		
		
		if ($listing_no != ""){
			$folderpath = "../yachtimage/" . $listing_no;
			$fle->remove_folder($folderpath);
		}
		
		$sql = "delete from tbl_yacht_engine_details where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
		
		$sql = "delete from tbl_form_lead where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
		
		$sql = "delete from tbl_yacht_view where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
		
		$sql = "delete from tbl_boat_slideshow_assign where boat_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
		
		$sql = "delete from tbl_email_campaign_boat_assign where boat_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);

		$sql = "delete from tbl_yacht where id = '". $yacht_id ."'";
        $db->mysqlquery($sql);		
    }

    public function delete_yacht_image($fimg1, $yacht_id){
        global $cm, $fle;
        if ($fimg1 != ""){
			$listing_no = $this->get_yacht_no($yacht_id);			
            $fle->filedelete("../yachtimage/". $listing_no ."/" . $fimg1);
            $fle->filedelete("../yachtimage/". $listing_no ."/big/" . $fimg1);
            $fle->filedelete("../yachtimage/". $listing_no ."/bigger/" . $fimg1);
			$fle->filedelete("../yachtimage/". $listing_no ."/slider/" . $fimg1);
			
			$original_img = "../yachtimage/". $listing_no ."/original/" . $fimg1;
			if (file_exists($original_img)){
				$fle->filedelete($original_img);
			}
        }
    }
	
	public function remove_original_boat_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$imid = $cm->filtertext($imid);
	
		$yachtdet = $cm->get_table_fields('tbl_yacht_photo', 'yacht_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$fimg1 = $yachtdet->imgpath;
		$yacht_id = $yachtdet->yacht_id;
		$listing_no = $this->get_yacht_no($yacht_id);
		$original_img = "../yachtimage/". $listing_no ."/original/" . $fimg1;
		$fle->filedelete($original_img);
		
		$sql = "update tbl_yacht_photo set keep_original = 0 where id = '".$imid."'";
		$db->mysqlquery($sql);
	}
	
	public function delete_yacht_image_all($boat_id){
		global $db;
		$sql = "select imgpath from tbl_yacht_photo where yacht_id = '". $boat_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['imgpath'];
            $this->delete_yacht_image($fimg1, $boat_id);
        }
		
		$sql = "delete from tbl_yacht_photo where yacht_id = '". $boat_id ."'";
        $db->mysqlquery($sql);
	}
	
	public function delete_yacht_video_all($boat_id){
		global $db;		
		$sql = "select videopath from tbl_yacht_video where yacht_id = '". $boat_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['videopath'];
            $this->delete_yacht_video($fimg1);
        }
		
		$sql = "delete from tbl_yacht_video where yacht_id = '". $boat_id ."'";
        $db->mysqlquery($sql);
	}
	
	public function delete_yacht_video($fimg1){
        global $fle;
        if ($fimg1 != ""){
            $fle->filedelete("../yachtvideo/" . $fimg1);
        }
    }
	
	public function delete_yacht_attachment_all($boat_id){
		global $db;		
		$sql = "select filepath from tbl_yacht_file where yacht_id = '". $boat_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['filepath'];
            $this->delete_any_files($fimg1, 'yachtfiles');
        }
		
		$sql = "delete from tbl_yacht_file where yacht_id = '". $boat_id ."'";
        $db->mysqlquery($sql);
	}

    public function get_yacht_first_image($check_id, $picktitle = 0){
        global $db, $cm;
		
		if ($picktitle == 1){			
			$sql = "select im_descriptions, imgpath from tbl_yacht_photo where yacht_id = '". $check_id ."' and imgpath != '' and status_id = 1 order by rank limit 0,1";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];
				$im_descriptions = $cm->filtertextdisplay($row["im_descriptions"]);
				$imgpath = $cm->filtertextdisplay($row["imgpath"]);
			}else{
				$im_descriptions = '';
				$imgpath = "no.jpg";
			}
			
			if ($im_descriptions == ""){
				$im_descriptions = $this->yacht_name($check_id);
			}
			
			$boatimg_data_ar = array(
				"alttag" => $im_descriptions,
				"imgpath" => $imgpath
			);
			return json_encode($boatimg_data_ar);
		
		}else{
			$imgpath = $db->total_record_count("select imgpath as ttl from tbl_yacht_photo where yacht_id = '". $check_id ."' and imgpath != '' and status_id = 1 order by rank limit 0,1");
			if ($imgpath == ""){ $imgpath = "no.jpg"; }
			return $imgpath;
		}
    }
	
	//get manual boat id's assigned as home page featured boat
	public function get_home_featured_boat_ids(){
		 global $db, $cm;
		 $fea_boat_ids_ar = array();
		 
		 $sql = "select yacht_id from tbl_yacht_featured where featured_upto >= CURDATE() and display_home = 1 and wh_manual_boat = 1 order by yacht_id";
		 $result = $db->fetch_all_array($sql);
		 $found = count($result);
		 if ($found > 0){
			 foreach($result as $row){
				  $fea_boat_ids_ar[] = $row["yacht_id"];
			 }
		 }else{
			 $fea_boat_ids_ar[] = 0;
		 }
		 
		 $fea_boat_ids = implode(", ", $fea_boat_ids_ar);
		 return $fea_boat_ids;
	}
	
	//manage featured
    public function featured_boat_section_list($opt = 1, $categoryid = 1){
        global $db, $cm;
        $returntext = '';
        $dragdropclass = '';

        if ($opt == 2){
            //featured list
            $dragdropclass = 'drp';
      		$query_sql = "select a.*, b.featured_upto, b.display_home,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
			
			$query_form .= " tbl_yacht_featured as b,";
			$query_where .= " a.id = b.yacht_id and";
				
			//$query_form .= " tbl_yacht_dimensions_weight as c,";
			//$query_where .= " a.id = c.yacht_id and";
			$query_where .= " a.status_id IN (1,3) and";
			
			/*if ($categoryid > 0){
				$query_where .= " b.categoryid = '". $categoryid ."' and";
			}*/
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by b.display_home desc, b.featured_upto desc";
		
		}else{
            //normal list not added under featured list			
			$keyterm = $_REQUEST["keyterm"];
			$condition_id = round($_REQUEST["condition_id"], 0);
			$custom_owned = round($_REQUEST["custom_owned"], 0);
			$status_id = round($_REQUEST["status_id"], 0);
			
			$dragdropclass = 'drg';
            $query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";

            $query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";			
			$query_where .= ' a.manufacturer_id > 0 and';
	            
			if ($condition_id > 0){
				$query_where .= " a.condition_id = '". $condition_id ."' and";
			}
			
			if ($status_id > 0){
				$query_where .= " a.status_id = '". $status_id ."' and";
			}
			
			if ($custom_owned == 2){
				$query_where .= "  a.yw_id > 0 and a.ownboat = 0 and";
			}elseif ($custom_owned == 3){
				$query_where .= " a.yw_id = 0 and a.ownboat = 1 and";
			}else{
				$query_where .= " a.yw_id > 0 and a.ownboat = 1 and";
			}
			
			$query_where .= ' a.id NOT IN (select yacht_id from tbl_yacht_featured) and';
			
			if ($keyterm != ""){
                $keyterm  = str_replace(' in ', ' ', $keyterm);
                $keyterm  = str_replace(',', ' ', $keyterm);
                $s_key_ar = preg_split("/ /", $cm->filtertextdisplay($keyterm));
				
				$query_form .= " tbl_yacht_keywords as sch,";
				$query_where .= " a.id = sch.yacht_id and";
                foreach($s_key_ar as $s_key_val){
                    $query_where.=" sch.keywords like '%".$cm->filtertext($s_key_val)."%' and";
                }
            }
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by c.length";
        }

        $result = $db->fetch_all_array($sql);
        $found = count($result);
		//$returntext .= '<p>'. $sql .'</p>';

        foreach($result as $row){            			
			$boat_id = $row["id"];
			$listing_no = $row["listing_no"];
			$boat_status_id = $row["status_id"];
            $boatname = $this->yacht_name($boat_id);
            $imgpath = $this->get_yacht_first_image($boat_id);
            $target_path_main = 'yachtimage/' . $listing_no . '/';
            $imgpath_d = '<img src="../'. $target_path_main . $imgpath .'" border="0" />';		
			$status_d = $cm->get_common_field_name('tbl_yacht_status', 'name', $boat_status_id);
			
			$rowextraclass = '';
			if ($boat_status_id == 2 OR $boat_status_id == 4){
				$rowextraclass = ' boatinactive';
			}
			
			if ($boat_status_id == 3){
				$rowextraclass = ' boatsold';
			}
			
			$returntext .= '
			<div id="'. $boat_id .'" class="'. $dragdropclass .' divrow'. $rowextraclass .'" title="'. $status_d .'">
				<div class="imgholder">'. $imgpath_d .'</div>
				<div class="ytitle2">'. $boatname .'</div>
            ';

            if ($opt == 2){
                $expired_message = '';
                $featured_upto = $row["featured_upto"];
				$display_home = $row["display_home"];
				if ($featured_upto == $cm->default_future_date){
					$featured_upto_d = "<strong>Until Sold</strong>";					
				}else{
					$featured_upto_d = $cm->display_date($featured_upto, 'y', 9);
					if (strtotime($featured_upto) < strtotime(date("Y-m-d"))){
						$expired_message = '<br /><span class="smallinfo">Expired</span>';
					}					
				}
				
				$display_home_text = $cm->set_yesyno_field($display_home);
                
                $returntext .= '<div class="options">
					'. $featured_upto_d . $expired_message .'
					
					<div class="spacer2 clearfixmain">
					<strong>Home?</strong> '. $display_home_text .'
					</div>				
				</div>';
            }else{
                $returntext .= '
                <div class="options">
                <select id="fea_day_no'. $boat_id .'" name="fea_day_no'. $boat_id .'" class="htext">
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="21">21 days</option>
                    <option value="28">28 days</option>
                    <option value="35">35 days</option>
					<option value="0">Until Sold</option>
                </select>
				<div class="spacer2 clearfixmain">
				<strong>Home?</strong> <input type="checkbox" class="checkbox" id="display_home'. $boat_id .'" name="display_home'. $boat_id .'">
                </div>
				</div>
                ';
            }

            $returntext .= '<div class="clearfix"></div>
                </div>
            ';
        }
        return $returntext;
    }
	
	public function display_boat_assigned_section_featured($param = array()){
		global $db, $cm;
		$returntext = '';
		$categoryid = round($param["categoryid"], 0);
		
		$rc_count = 1;
		$returntext .= '
		<div class="assign_section_holder assign_section_holder'. $rc_count .' nospace">
			<div class="boxleft box_border">
				<div class="box_heading">Available List</div>
				<div class="box_div app_box1 app_box1_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->featured_boat_section_list(1, $categoryid) .'
				</div>
			</div>
			
			<div class="boxright box_border">
				<div class="box_heading">Assign List</div>
				<div class="box_div app_box2 app_box2_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->featured_boat_section_list(2, $categoryid) .'
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		';
		
		return $returntext;
	}

    public function boat_add_featured($param = array()){
        global $db, $cm;
		
		$boat_id = $param["boat_id"];
		$fea_day_no = $param["fea_day_no"];
		$categoryid = $param["categoryid"];
		$display_home = $param["display_home"];
		
		$this->boat_remove_featured($boat_id);
		
        $fea_id = time() .  $cm->campaignid(5);
		if ($fea_day_no == 0){
			//untill sold
			$featured_upto = $cm->default_future_date;			
		}else{
			$featured_upto_n = time() + ($fea_day_no * 24 * 3600);
        	$featured_upto = date("Y-m-d", $featured_upto_n);			
		}
		
		$type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $boat_id, 'yacht_id');
			
		$boat_ar = $cm->get_table_fields('tbl_yacht', 'ownboat, yw_id, feed_id', $boat_id);
		$boat_ar = (object)$boat_ar[0];
		$ownboat = $boat_ar->ownboat;
		$yw_id = $boat_ar->yw_id;
		$feed_id = $boat_ar->feed_id;
		
		$wh_manual_boat = 0;
		if ($ownboat == 1 AND $yw_id == 0){
			$wh_manual_boat = 1;
		}

		if ($ownboat == 1){
			if ($type_id == $this->catamaran_id OR $feed_id == $this->catamaran_feed_id2){
				$categoryid_front = 2;
			}else{
				$categoryid_front = 1;
			}
		}else{
			if ($feed_id == $this->catamaran_feed_id){
				$categoryid_front = 2;
			}else{
				$categoryid_front = 1;
			}
		}

        $sql = "insert into tbl_yacht_featured (id, yacht_id, featured_upto, categoryid, categoryid_front, display_home, wh_manual_boat) values ('". $cm->filtertext($fea_id) ."', '". $boat_id ."', '". $featured_upto ."', '". $categoryid ."', '". $categoryid_front ."', '". $display_home ."', '". $wh_manual_boat ."')";
        $db->mysqlquery($sql);
    }

    public function boat_remove_featured($boat_id){
        global $db;
        $sql = "delete from tbl_yacht_featured where yacht_id = '". $boat_id ."'";
        $db->mysqlquery($sql);
    }

    public function boat_featured_ajax_call($displayopt){
		$categoryid = round($_REQUEST["categoryid"], 0);
        $mlistnormal = $this->featured_boat_section_list(1, $categoryid);
        $mlistassign = $this->featured_boat_section_list(2, $categoryid);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal,
            'mlistassign' => $mlistassign
        );
        return json_encode($returnval);
    }

    public function boat_search_for_featured_ajax(){
		$categoryid = round($_REQUEST["categoryid"], 0);
        $ylistnormal = $this->featured_boat_section_list(1, $categoryid);
        $returnval = array(
            'ylistnormal' => $ylistnormal,
        );
        return json_encode($returnval);
    }

    public function remove_sold_yacht_from_featured($yid, $status_id){
        if ($status_id == 3){
            $this->boat_remove_featured($yid);
        }
    }
	//end
	
	//manage trade in
	public function tradein_boat_section_list($opt = 1){
        global $db, $cm;
        $returntext = '';
        $dragdropclass = '';

        if ($opt == 2){
            //trade in list
            $dragdropclass = 'drp';
			
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
				
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";
			$query_where .= " a.status_id IN (1,3) and a.display_upto >= CURDATE() and a.trade_in = 1 and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by c.length";			
        }else{
            //available list
			$keyterm = $_REQUEST["keyterm"];
			$condition_id = round($_REQUEST["condition_id"], 0);
			$status_id = round($_REQUEST["status_id"], 0);
			
			$dragdropclass = 'drg';
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";			
			
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";
						
			if ($condition_id > 0){
				$query_where .= " a.condition_id = '". $condition_id ."' and";
			}
			
			if ($status_id > 0){
				$query_where .= " a.status_id = '". $status_id ."' and";
			}
			
			$query_where .= " a.display_upto >= CURDATE() and a.trade_in = 0 and";
			
			if ($keyterm != ""){
                $keyterm  = str_replace(' in ', ' ', $keyterm);
                $keyterm  = str_replace(',', ' ', $keyterm);
                $s_key_ar = preg_split("/ /", $cm->filtertextdisplay($keyterm));
				
				$query_form .= " tbl_yacht_keywords as sch,";
				$query_where .= " a.id = sch.yacht_id and";
                foreach($s_key_ar as $s_key_val){
                    $query_where.=" sch.keywords like '%".$cm->filtertext($s_key_val)."%' and";
                }
            }
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by c.length";
        }

        $result = $db->fetch_all_array($sql);
        $found = count($result);

        foreach($result as $row){
            $boat_id = $row["id"];
			$listing_no = $row["listing_no"];
			$boat_status_id = $row["status_id"];
            $boatname = $this->yacht_name($boat_id);
            $imgpath = $this->get_yacht_first_image($boat_id);
            $target_path_main = 'yachtimage/' . $listing_no . '/';
            $imgpath_d = '<img src="../'. $target_path_main . $imgpath .'" border="0" />';
			$status_d = $cm->get_common_field_name('tbl_yacht_status', 'name', $boat_status_id);
			
			$rowextraclass = '';
			if ($boat_status_id == 2 OR $boat_status_id == 4){
				$rowextraclass = ' boatinactive';
			}
			
			if ($boat_status_id == 3){
				$rowextraclass = ' boatsold';
			}
		
			$returntext .= '
			 	<div id="'. $boat_id .'" class="'. $dragdropclass .' divrow'. $rowextraclass .'" title="'. $status_d .'">
					<div class="imgholder">'. $imgpath_d .'</div>
					<div class="ytitle">'. $boatname .'</div>
					<div class="clearfix"></div>
				</div>
			 ';
        }
        return $returntext;
    }
	
	public function display_boat_assigned_section_trade_in(){
		global $db, $cm;
		$returntext = '';
		
		$rc_count = 1;
		$returntext .= '
		<div class="assign_section_holder assign_section_holder'. $rc_count .' nospace">
			<div class="boxleft box_border">
				<div class="box_heading">Available List</div>
				<div class="box_div app_box1 app_box1_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->tradein_boat_section_list(1) .'
				</div>
			</div>
			
			<div class="boxright box_border">
				<div class="box_heading">Assign List</div>
				<div class="box_div app_box2 app_box2_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->tradein_boat_section_list(2) .'
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		';
		
		return $returntext;
	}
	
	public function boat_search_for_trade_in_ajax(){
        $ylistnormal = $this->tradein_boat_section_list(1);
        $returnval = array(
            'ylistnormal' => $ylistnormal,
        );
        return json_encode($returnval);
    }
	
	public function boat_add_trade_in($boat_id){
        global $db, $cm;
		$sql = "update tbl_yacht set trade_in = 1 where id = '". $boat_id ."'";		
		$db->mysqlquery($sql);
    }
	
	public function boat_trade_in_list_ajax_call($displayopt){
		global $cm;
	
		$mlistnormal = $this->tradein_boat_section_list(1);
        $mlistassign = $this->tradein_boat_section_list(2);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal,
            'mlistassign' => $mlistassign
        );
        return json_encode($returnval);
    }
	
	public function boat_remove_trade_in($boat_id){
        global $db;
        $sql = "update tbl_yacht set trade_in = 0 where id = '". $boat_id ."'";	
        $db->mysqlquery($sql);
    }
	//end
	
	//manage in-stock boat
	public function in_stock_boat_section_list($opt = 1){
        global $db, $cm;
        $returntext = '';
        $dragdropclass = '';

        if ($opt == 2){
            //trade in list
            $dragdropclass = 'drp';
			
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
				
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";
			$query_where .= " a.status_id IN (1,3) and a.display_upto >= CURDATE() and a.boat_in_stock = 1 and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by c.length";			
        }else{
            //available list
			$keyterm = $_REQUEST["keyterm"];
			$condition_id = round($_REQUEST["condition_id"], 0);
			$status_id = round($_REQUEST["status_id"], 0);
			
			$dragdropclass = 'drg';
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";			
			
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";
						
			if ($condition_id > 0){
				$query_where .= " a.condition_id = '". $condition_id ."' and";
			}
			
			if ($status_id > 0){
				$query_where .= " a.status_id = '". $status_id ."' and";
			}
			
			$query_where .= " a.display_upto >= CURDATE() and a.boat_in_stock = 0 and";
			
			if ($keyterm != ""){
                $keyterm  = str_replace(' in ', ' ', $keyterm);
                $keyterm  = str_replace(',', ' ', $keyterm);
                $s_key_ar = preg_split("/ /", $cm->filtertextdisplay($keyterm));
				
				$query_form .= " tbl_yacht_keywords as sch,";
				$query_where .= " a.id = sch.yacht_id and";
                foreach($s_key_ar as $s_key_val){
                    $query_where.=" sch.keywords like '%".$cm->filtertext($s_key_val)."%' and";
                }
            }
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by c.length";
        }

        $result = $db->fetch_all_array($sql);
        $found = count($result);

        foreach($result as $row){
            $boat_id = $row["id"];
			$listing_no = $row["listing_no"];
			$boat_status_id = $row["status_id"];
            $boatname = $this->yacht_name($boat_id);
            $imgpath = $this->get_yacht_first_image($boat_id);
            $target_path_main = 'yachtimage/' . $listing_no . '/';
            $imgpath_d = '<img src="../'. $target_path_main . $imgpath .'" border="0" />';
			
			$rowextraclass = '';
			if ($boat_status_id == 2 OR $boat_status_id == 4){
				$rowextraclass = ' boatinactive';
			}
		
			$returntext .= '
			 	<div id="'. $boat_id .'" class="'. $dragdropclass .' divrow'. $rowextraclass .'">
					<div class="imgholder">'. $imgpath_d .'</div>
					<div class="ytitle">'. $boatname .'</div>
					<div class="clearfix"></div>
				</div>
			 ';
        }
        return $returntext;
    }
	
	public function display_boat_assigned_section_in_stock(){
		global $db, $cm;
		$returntext = '';
		
		$rc_count = 1;
		$returntext .= '
		<div class="assign_section_holder assign_section_holder'. $rc_count .' nospace">
			<div class="boxleft box_border">
				<div class="box_heading">Available List</div>
				<div class="box_div app_box1 app_box1_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->in_stock_boat_section_list(1) .'
				</div>
			</div>
			
			<div class="boxright box_border">
				<div class="box_heading">Assign List</div>
				<div class="box_div app_box2 app_box2_'. $rc_count .'" rowval="'. $rc_count .'">
				'. $this->in_stock_boat_section_list(2) .'
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		';
		
		return $returntext;
	}
	
	public function boat_search_for_in_stock_ajax(){
        $ylistnormal = $this->in_stock_boat_section_list(1);
        $returnval = array(
            'ylistnormal' => $ylistnormal,
        );
        return json_encode($returnval);
    }
	
	public function boat_add_in_stock($boat_id){
        global $db, $cm;
		$sql = "update tbl_yacht set boat_in_stock = 1 where id = '". $boat_id ."'";		
		$db->mysqlquery($sql);
    }
	
	public function boat_in_stock_list_ajax_call($displayopt){
		global $cm;
	
		$mlistnormal = $this->in_stock_boat_section_list(1);
        $mlistassign = $this->in_stock_boat_section_list(2);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal,
            'mlistassign' => $mlistassign
        );
        return json_encode($returnval);
    }
	
	public function boat_remove_in_stock($boat_id){
        global $db;
        $sql = "update tbl_yacht set boat_in_stock = 0 where id = '". $boat_id ."'";	
        $db->mysqlquery($sql);
    }
	//end
	
    public function check_yacht($yacht_id, $opt = 0){
        global $db, $cm;
        $if_found = $db->total_record_count("select count(*) as ttl from tbl_yacht where id = '". $yacht_id ."'");
        if ($if_found == 0){
            if ($opt == 1){
                $cm->sorryredirect(14);
            }else{
                $_SESSION["admin_sorry"] = "You have selected an invalid record.";
                header('Location: sorry.php');
                exit;
            }
        }
    }

	public function yacht_total_image_list($ms){
		global $db, $cm;
		$sql = "select count(*) as ttl from tbl_yacht_photo where yacht_id  = '". $ms ."'";
		$total_image = $db->total_record_count($sql);
		return $total_image;
	}

	public function yacht_image_display_list_short($ms){
		if ($ms > 0){
			$total_image = $this->yacht_total_image_list($ms);		
			$returntext = '
			<p>
				Total Image(s): '. $total_image .'<br />
				<a class="htext" href="yacht_image.php?id='. $ms .'" title="Manage Images">Open Image List</a>
			</p>
			<input type="hidden" id="im_thefilecount" name="im_thefilecount" value="'. $total_image .'"/>
			';
		}else{
			$returntext = '';
		}

		return $returntext;
	}

    public function yacht_image_display_list($ms, $frontfrom = 0){
        global $db, $cm;
        $returntext = '';
        $im_found = 0;
        $im_sql = "select * from tbl_yacht_photo where yacht_id  = '". $ms ."' order by rank";
        $im_result = $db->fetch_all_array($im_sql);
        $im_found = count($im_result);
        if ($im_found > 0){
            if ($frontfrom == 0){
            $returntext .= '
                <table border="0" width="100%" cellspacing="0" cellpadding="0" class="htext">
                        <tr>
                            <td class="tdouter">
                               <ul id="recordsortable" class="imagedisplay gridview">';
            }else{
                $returntext .= '<ul id="recordsortable" class="imagedisplay gridview">';
            }

                            $rc_count = 0;
                            foreach($im_result as $im_row){
                                $im_id = $im_row['id'];
                                $imgpath  = $im_row['imgpath'];
                                $im_title = $im_row['im_title'];
                                $im_descriptions = $im_row['im_descriptions'];
                                $im_status_id = $im_row['status_id'];
                                $im_rank = $im_row['rank'];
								$keep_original = $im_row['keep_original'];
                                //$im_status_d = $cm->get_common_field_name('tbl_common_status', 'name', $im_status_id);
                                //if ($im_status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }
								$listing_no = $this->get_yacht_no($ms);

                                $imgpath_d = '-';
                                if ($imgpath != ""){
                                    $target_path_main = 'yachtimage/' . $listing_no . '/';
                                    if ($frontfrom == 0){
                                        $target_path_main = "../" . $target_path_main;
                                        $delpath = '';
                                    }else{
                                        $target_path_main = $cm->folder_for_seo . $target_path_main;
                                        $delpath = $cm->folder_for_seo;
                                    }
                                    $imgpath_d = '<img class="imglist'. $rc_count .'" src="'. $target_path_main . $imgpath .'" border="0" width="100" />';
                                }								
								
								if ($keep_original == 1){
									$hidden_class = '';
									$img_inactive_class = '';
									$original_img_del_text = '<li><a class="delete_original delete_original'. $rc_count .'" c="'. $rc_count .'" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Original Image"><img src="'. $delpath .'images/image-delete.png" alt="Delete Original Image" /></a></li>';
								}else{
									$hidden_class = ' com_none';
									$original_img_del_text = '';
									$img_inactive_class = ' img_inactive';
								}

                                if ($frontfrom == 0){
                                    $returntext .= '
                                    <li id="item-'. $im_id .'">
                                        <div class="imgholder">
										<div class="imgrotatemain">
											<ul>
												<li>'. $original_img_del_text .'</li>
												<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class . '" ko="'. $keep_original .'" v="90" c="'. $rc_count .'" yval="'. $im_id .'" href="javascript:void(0);" title="Rotate ACW"><img src="'. $delpath .'images/rotate_acw.png" alt="Rotate ACW" /></a></li>
												<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class .'" ko="'. $keep_original .'" v="270" c="'. $rc_count .'" yval="'. $im_id .'" href="javascript:void(0);" title="Rotate CW"><img src="'. $delpath .'images/rotate_cw.png" alt="Rotate CW" /></a></li>
												<li><input class="checkbox '. $hidden_class .'" type="checkbox" id="crop_option'. $rc_count .'" name="crop_option'. $rc_count .'" value="1" title="Hard Crop" /></li>
											</ul>
										</div>
										'. $imgpath_d .'
										</div>
										<div class="imgrank">'. $im_rank .'</div>										
                                        
										<div class="editable">
                                            <div class="caption">Title</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Description</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_descriptions'. $rc_count .'" id="im_descriptions'. $rc_count .'" value="'. $im_descriptions .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Rank</div>
                                            <div class="captionfield"><input type="text" class="input sortv" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" /></div>
                                            <input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
                                        </div>
                                        <div class="options">
                                            <a class="delyachtimg" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img src="'. $delpath .'images/del.png" alt="Remove Image" /></a>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                ';
                                }else{
                                    $returntext .= '
                                    <li id="item-'. $im_id .'">
                                        <div class="imgholder">
										<div class="imgrotatemain">
											<ul>
												<li>'. $original_img_del_text .'</li>
												<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class . '" ko="'. $keep_original .'" v="90" c="'. $rc_count .'" yval="'. $im_id .'" href="javascript:void(0);" title="Rotate ACW"><img src="'. $delpath .'images/rotate_acw.png" alt="Rotate ACW" /></a></li>
												<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class .'" ko="'. $keep_original .'" v="270" c="'. $rc_count .'" yval="'. $im_id .'" href="javascript:void(0);" title="Rotate CW"><img src="'. $delpath .'images/rotate_cw.png" alt="Rotate CW" /></a></li>
												<li><input class="checkbox'. $hidden_class .'" type="checkbox" id="crop_option'. $rc_count .'" name="crop_option'. $rc_count .'" value="1" title="Hard Crop" /></li>
											</ul>
										</div>
										'. $imgpath_d .'
										</div>
										<div class="imgrank">'. $im_rank .'</div>
										
                                        <div class="editable">
                                            <div class="caption">Title</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Description</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_descriptions'. $rc_count .'" id="im_descriptions'. $rc_count .'" value="'. $im_descriptions .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Rank</div>
                                            <div class="captionfield"><input type="text" class="input sortv" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" /></div>
                                            <input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
                                        </div>
                                        <div class="options">
                                            <a class="delyachtimg" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img src="'. $delpath .'images/del.png" alt="Remove Image" /></a>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                    ';
                                }

                                $rc_count++;
                            }
            if ($frontfrom == 0){
            $returntext .= '    </ul>
                            </td>
                        </tr>
                    </table>
            ';
            }else{
            $returntext .= '
            </ul>
			<div class="clear"></div>
            ';
            }
            $returntext .= '<input type="hidden" id="im_thefilecount" name="im_thefilecount" value="'. $im_found .'"/>';
        }

        return $returntext;
    }
	
	public function yacht_video_display_list($ms, $frontfrom = 0){
        global $db, $cm;
        $returntext = '';
        $im_found = 0;
        $im_sql = "select * from tbl_yacht_video where yacht_id  = '". $ms ."' order by rank";
        $im_result = $db->fetch_all_array($im_sql);
        $im_found = count($im_result);
        if ($im_found > 0){
            if ($frontfrom == 0){
            $returntext .= '
                <table border="0" width="100%" cellspacing="0" cellpadding="0" class="htext">
                        <tr>
                            <td class="tdouter">
                                <table border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
                                    <tr>                                        
                                        <td class="displaytdheading" align="left" nowrap="nowrap">Title</td>
										<td class="displaytdheading" align="center">Type</td>                                        
                                        <td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
                                        <td class="displaytdheading" align="center" nowrap="nowrap">Del</td>
                                    </tr>';
            }else{
                $returntext .= '<ul class="imagedisplay">';
            }

                            $rc_count = 0;
                            foreach($im_result as $im_row){
                                $im_id = $im_row['id'];
								$video_type = $im_row['video_type'];                                
                                $im_title = $im_row['name'];
								$link_url = $im_row['link_url'];
                                $im_status_id = $im_row['status_id'];
                                $im_rank = $im_row['rank'];
								
								if ($video_type == 1){
									$video_type_d = '<a class="htext" href="'. $link_url .'" target="_blank">YouTube</a>';
								}elseif ($video_type == 3){
									$video_type_d = '<a class="htext" href="'. $link_url .'" target="_blank">Vimeo</a>';
								}elseif ($video_type == 4){
									$video_type_d = 'External Link';
								}elseif ($video_type == 5){
									$video_type_d = 'External Link';
								}else{
									$video_type_d = 'Uploaded';
								}
								
								if ($frontfrom == 0){									
									$delpath = '';
								}else{									
									$delpath = $cm->folder_for_seo;
								}

                                if ($frontfrom == 0){
                                    $returntext .= '
                                    <tr class="i'. $im_id .'">                                         
                                         <td class="displaytd1" align="left" nowrap="nowrap">
                                            <input type="text" class="input inputbox inputbox_size4" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" />
                                         </td>
                                         <td class="displaytd1" align="center" nowrap="nowrap">'. $video_type_d .'</td>
                                         <td class="displaytd1" align="center" nowrap="nowrap">
                                            <input type="text" class="inputbox butt_size1" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" />
                                            <input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
                                         </td>
                                         <td class="displaytd1" align="center"><a class="delyachtimg" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a></td>
                                    </tr>
                                ';
                                }else{
                                    $returntext .= '
                                    <li class="i'. $im_id .'">
                                        <div class="imgholder">'. $video_type_d .'</div>
                                        <div class="editable">
                                            <div class="caption">Title</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Rank</div>
                                            <div class="captionfield"><input type="text" class="input" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" /></div>
                                            <input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
                                        </div>
                                        <div class="options">
                                            <a class="delyachtimg" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img src="'. $delpath .'images/del.png" alt="Remove Image" /></a>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                    ';
                                }
                                $rc_count++;
                            }
            if ($frontfrom == 0){
            $returntext .= '    </table>
                            </td>
                        </tr>
                    </table>
            ';
            }else{
            $returntext .= '
            </ul>
            ';
            }
            $returntext .= '<input type="hidden" id="im_thefilecount" name="im_thefilecount" value="'. $im_found .'"/>';
        }

        return $returntext;
    }
	
	public function yacht_attachment_display_list($ms, $frontfrom = 0){
        global $db, $cm;
        $returntext = '';
        $im_found = 0;
        $im_sql = "select * from tbl_yacht_file where yacht_id  = '". $ms ."' order by rank";
        $im_result = $db->fetch_all_array($im_sql);
        $im_found = count($im_result);
        if ($im_found > 0){
            if ($frontfrom == 0){
            $returntext .= '
                <table border="0" width="100%" cellspacing="0" cellpadding="0" class="htext">
                        <tr>
                            <td class="tdouter">
                                <table id="recordsortable" border="0" width="100%" cellspacing="1" cellpadding="4" class="htext">
                                    <tr>
										<td class="displaytdheading" align="left" nowrap="nowrap" width="250">Title</td>
										<td class="displaytdheading" align="left" nowrap="nowrap">File</td>
										<td class="displaytdheading" align="center" nowrap="nowrap">Sort Order</td>
										<td class="displaytdheading" align="center" nowrap="nowrap">Del</td>
									</tr>
									<tbody>';
            }else{
                $returntext .= '<ul id="recordsortable" class="imagedisplay">';
            }

                            $rc_count = 0;
                            foreach($im_result as $im_row){
                                $im_id = $im_row['id'];
								$im_title = $im_row['title'];
								$im_originalname = $im_row['originalname'];
								$im_rank = $im_row['rank'];
																
								if ($frontfrom == 0){									
									$delpath = '';
								}else{									
									$delpath = $cm->folder_for_seo;
								}

                                if ($frontfrom == 0){                                    
									$returntext .= '
									<tr id="item-'. $im_id .'">
										<td class="displaytd1" width="" align="left"><input type="text" class="input inputbox inputbox_size4" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" /></td>
										<td class="displaytd1" width="" align="left"><a class="htext" href="filedownload.php?fileid='. $im_id .'&opt=1">'. $im_originalname .'</a></td>
										<td class="displaytd1" align="center" nowrap="nowrap">
											<input type="text" class="inputbox butt_size1 sortv" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" />
											<input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
										 </td>
										 <td class="displaytd1" align="center"><a class="delfiles" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a></td>
									</tr>
									';
                                }else{
                                    $returntext .= '
                                    <li id="item-'. $im_id .'">
                                        <div class="imgholder"><a href="'. $cm->site_url .'/?fcapi=downloadfiles&fileid='. $im_id .'&opt=1">'. $im_originalname .'</a></div>
                                        <div class="editable">
                                            <div class="caption">Title</div>
                                            <div class="captionfield"><input type="text" class="input" name="im_title'. $rc_count .'" id="im_title'. $rc_count .'" value="'. $im_title .'" /></div>
                                            <div class="clear extraspace"></div>

                                            <div class="caption">Rank</div>
                                            <div class="captionfield"><input type="text" class="input sortv" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" /></div>
                                            <input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
                                        </div>
                                        <div class="options">
                                            <a class="delfiles" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img src="'. $delpath .'images/del.png" alt="Remove Image" /></a>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                    ';
                                }
                                $rc_count++;
                            }
            if ($frontfrom == 0){
            $returntext .= '
								</tbody></table>
                            </td>
                        </tr>
                    </table>
            ';
            }else{
            $returntext .= '
            </ul>
            ';
            }
            $returntext .= '<input type="hidden" id="im_thefilecount" name="im_thefilecount" value="'. $im_found .'"/>';
        }

        return $returntext;
    }

    public function yacht_name($yacht_id, $format = 0){
        global $db, $cm;
        $yacht_ar = $cm->get_table_fields('tbl_yacht', 'manufacturer_id, model, model_slug, year, state_id', $yacht_id);
        $manufacturer_id = $yacht_ar[0]['manufacturer_id'];		
		$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'slug, name', $manufacturer_id);
		$manufacturerarslug = $manufacturerar[0]["slug"];
		$manufacturer_name = $manufacturerar[0]["name"];
		
		if ($format == 1){
			//for url format
			$yacht_name = $cm->filtertextdisplay($yacht_ar[0]['year']) . '/' .$manufacturerarslug . '/'. $cm->filtertextdisplay($yacht_ar[0]['model_slug']);
			
		}else{
			$yacht_name = $cm->filtertextdisplay($yacht_ar[0]['year']) . ' ' .$manufacturer_name . ' '. $cm->filtertextdisplay($yacht_ar[0]['model']);
			/*
			$state_name = $cm->get_common_field_name('tbl_state', 'code', $yacht_ar[0]['state_id']);
			if ($state_name != ""){
				$yacht_name .=  ' ' . $state_name;
			}
			*/
		}        
        return $yacht_name;
    }
	
	public function create_boat_slug($yacht_id){
        global $db, $cm;
        $yacht_ar = $cm->get_table_fields('tbl_yacht', 'manufacturer_id, model, model_slug, year, city, state, state_id, country_id, vessel_name', $yacht_id);
        $manufacturer_id = $yacht_ar[0]['manufacturer_id'];
		$city = $yacht_ar[0]['city'];
		$state = $yacht_ar[0]['state'];
		$state_id = $yacht_ar[0]['state_id'];
		$country_id = $yacht_ar[0]['country_id'];
		$vessel_name = $yacht_ar[0]['vessel_name'];
			
		$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'slug, name', $manufacturer_id);
		$manufacturerarslug = $manufacturerar[0]["slug"];
		$manufacturer_name = $manufacturerar[0]["name"];
		
		if ($vessel_name != ""){
			$vessel_name = 	"-" . $cm->create_slug($vessel_name);
		}
		
		$locationtext = '';
		if ($country_id == 1){
			$state = $cm->get_common_field_name('tbl_state', 'name', $state_id);
		}
		
		if ($city != "" AND strtolower($city) != "unknown"){
			$locationtext .= " " . $city;
		}
		if ($state != ""){
			$locationtext .= " " . $state;
		}
		//$locationtext = rtrim($locationtext, " ");		
		if ($locationtext != ""){
			$locationtext = $cm->create_slug($locationtext);
			$locationtext =	"-" . $locationtext;
		}
		
		$boat_slug = $cm->filtertextdisplay($yacht_ar[0]['year']) . "-" . $manufacturerarslug . "-" . $cm->filtertextdisplay($yacht_ar[0]['model_slug']) . $vessel_name . $locationtext . "-for-sale";
        return $boat_slug;
    }

    public function add_yacht_keywords($yacht_id){
        global $db, $cm;
        $keywords = '';
        $yacht_name = $this->yacht_name($yacht_id);
        $yacht_ar = $cm->get_table_fields('tbl_yacht', 'listing_no, company_id, broker_id, category_id, condition_id, address, city,state, state_id, country_id, zip, hull_material_id, hull_type_id, hull_color, hull_no, designer', $yacht_id);
        $yacht_ar = $yacht_ar[0];
		foreach($yacht_ar AS $key => $val){
			${$key} = $cm->filtertextdisplay($val);
		}		

        $country_ar = $cm->get_table_fields('tbl_country', 'name, code', $country_id);
        $country_code = $country_ar[0]['code'];
        $country_name = $country_ar[0]['name'];

        if ($country_id == 1){
            $state_ar = $cm->get_table_fields('tbl_state', 'name, code', $state_id);
            $state_code = $state_ar[0]['code'];
            $state_name = $state_ar[0]['name'];
            $state = $state_name . ' [' . $state_code . ']';
        }
		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname', $company_id);
        $companyname = $company_ar[0]["cname"];
		
		$broker_ar = $cm->get_table_fields('tbl_user', 'uid, concat(fname, \' \', lname) as name', $broker_id);
		$b_uid = $broker_ar[0]["uid"];
		$b_name = $broker_ar[0]["name"];
		

        //Dimensions & Weight
        $ex_sql = "select length from tbl_yacht_dimensions_weight where yacht_id = '". $yacht_id ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertext($val);			
        }
		
		//Engine
		$key_ar3 = array();
		$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $yacht_id ."'";
		$ex_result = $db->fetch_all_array($ex_sql);
		$row = $ex_result[0];
		foreach($row AS $key => $val){
			${$key} = $cm->filtertext($val);
		}


		$type_name = $cm->display_multiplevl($yacht_id, 'tbl_yacht_type_assign', 'type_id', 'yacht_id', 'tbl_type');
		$category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
		$condition_name = $cm->get_common_field_name('tbl_condition', 'name', $condition_id);
		$hull_material_name = $cm->get_common_field_name('tbl_hull_material', 'name', $hull_material_id);
		$hull_type_name = $cm->get_common_field_name('tbl_hull_type', 'name', $hull_type_id);
		$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
		$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
		$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
		$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);

		$key_ar1 = array();
		$key_ar1[] = $listing_no;
		$key_ar1[] = $yacht_name;
		$key_ar1[] = $address;
		$key_ar1[] = $city;
		$key_ar1[] = $state;
		$key_ar1[] = $country_name;
		$key_ar1[] = $country_code;
		$key_ar1[] = $zip;
		$key_ar1[] = $type_name;
		$key_ar1[] = $category_name;
		$key_ar1[] = $condition_name;
		$key_ar1[] = $hull_material_name;
		$key_ar1[] = $hull_type_name;
		$key_ar1[] = $engine_make_name;
		$key_ar1[] = $engine_type_name;
		$key_ar1[] = $drive_type_name;
		$key_ar1[] = $fuel_type_name;
		$key_ar1[] = $hull_color;
		$key_ar1[] = $hull_no;
		$key_ar1[] = $designer;
		$key_ar1[] = $length;
		$key_ar1[] = $companyname;
		$key_ar1[] = $b_uid;
		$key_ar1[] = $b_name;
		$keywords = json_encode($key_ar1);
		

        //$keywords = $listing_no . ' ' . $yacht_name . ' ' . $address . ' ' . $city . ' ' . $state . ' ' .$country_name . ' [' . $country_code . '] ' . $zip . ' ' . $length . ' ' . $companyname . ' ' . $b_uid . ' ' . $b_name;

        $sql = "update tbl_yacht_keywords set keywords = '". $cm->filtertext($keywords) ."' where yacht_id = '". $yacht_id ."'";
        $db->mysqlquery($sql);
    }
	
	public function update_yacht_view($yacht_id, $view_type = 1){
        global $db, $cm;
        $dt = date("Y-m-d");
		$sql = "select count(*) as ttl from tbl_yacht_view where yacht_id = '". $yacht_id ."' and reg_date = '". $dt ."' and view_type = '". $view_type ."'";
        $ifound = $db->total_record_count($sql);

        if ($ifound > 0){
            $sql = "update tbl_yacht_view set total_view = (total_view + 1) where yacht_id = '". $yacht_id ."' and reg_date = '". $dt ."' and view_type = '". $view_type ."'";
            $db->mysqlquery($sql);
        }else{
            $sql = "insert into tbl_yacht_view (yacht_id
                                               , total_view
                                               , reg_date
											   , view_type) values ('". $yacht_id ."'
                                               , '1'
                                               , '". $dt ."'
											   , '". $view_type ."')";
            $db->mysqlquery($sql);
        }
    }

    public function get_yacht_no($yacht_id){
        global $cm;
        $listing_no = $cm->get_common_field_name('tbl_yacht', 'listing_no', $yacht_id);
        return $listing_no;
    }

    public function get_yacht_id($listing_no){
        global $cm;
        $yacht_id = $cm->get_common_field_name('tbl_yacht', 'id', $listing_no, 'listing_no');
        return $yacht_id;
    }
	
	public function get_price_per_option_combo($price_per_option_id = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_price_per_option where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];

            $bck = '';
			if ($price_per_option_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'> / '. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function yacht_price_display($price, $price_tag_id = 0, $charter_id = 0, $charter_price = 0, $price_per_option_id = 0 , $charter = 0){
		global $cm;
		if ($charter_id == 2 OR $charter == 1){
			$price_per_option_name = $cm->get_common_field_name("tbl_price_per_option", "name", $price_per_option_id);
			$price_display = '$'. $cm->price_format($charter_price) . ' / ' . $price_per_option_name;
		}else{		
			$price_display = '$' . $cm->price_format($price);
			if ($price_tag_id > 0){
				$price_display = $cm->get_common_field_name('tbl_price_tag', 'name', $price_tag_id);
			}
		}
		return $price_display;
	}
	
	public function get_yacht_price($yacht_id){
		global $cm;
		$ret_ar = $cm->get_table_fields('tbl_yacht', 'price, price_tag_id, charter_id, charter_price, price_per_option_id', $yacht_id);
		$ret_ar = (object)$ret_ar[0];
		$price = $ret_ar->price;
		$price_tag_id = $ret_ar->price_tag_id;
		$charter_id = $ret_ar->charter_id;
		$charter_price = $ret_ar->charter_price;
		$price_per_option_id = $ret_ar->price_per_option_id;
		$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
		return $price_display;
	}

    public function min_max_value($fieldoption, $nextoption = 0){
        global $db, $cm;
        $minmax = array();
        if ($fieldoption == 1){
            //price
			if ($nextoption == 1){
				//charter price
				$sql = "select min(charter_price) as minval, max(charter_price) as maxval from tbl_yacht where (charter_id = 2 OR charter_id = 3)";
				$result = $db->fetch_all_array($sql);
				$minmax["min"] = round($result[0]["minval"], 0);
				$minmax["max"] = round($result[0]["maxval"], 0);
			}else{
				//sale price
				$ret_ar = $cm->get_table_fields('tbl_yacht', 'min(price) as minval, max(price) as maxval', 1, 1);
				$minmax["min"] = round($ret_ar[0]["minval"], 0);
				$minmax["max"] = round($ret_ar[0]["maxval"], 0);
			}
        }

        if ($fieldoption == 2){
            //length
            $ret_ar = $cm->get_table_fields('tbl_yacht_dimensions_weight', 'min(length) as minval, max(length) as maxval', 1, 1);
            $minmax["min"] = round($ret_ar[0]["minval"], 0);
            $minmax["max"] = round($ret_ar[0]["maxval"], 0);
        }
        return $minmax;
    }

    public function remove_yach_search_var(){
		global $db, $cm;
		$sessionid = session_id();
		$to_check_val = $_SERVER['HTTP_REFERER'];
		
		$sql = "delete from tbl_datastore_by_session where sessionid = '". $sessionid ."' and to_check_val = '". $cm->filtertext($to_check_val) ."' and section_for = 2";
		$db->mysqlquery($sql);
		
        $_SESSION["created_sql"] = '';
        $_SESSION["s_currenturl"] = '';
        $_SESSION["created_search"] = array();
		
		unset($_SESSION["created_displayoption"]);
		unset($_SESSION["created_sortop"]);
		unset($_SESSION["created_orderbyop"]);
    }

    public function display_yacht_type_list($limitval = 0){
        global $db, $cm;
        $returntext = '';

		$sql = "select id, name, imgpath from tbl_type where parent_id = 0 and display_home = 1 and status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
            $returntext .= '
            <section class="categorylist">
			<div class="singleblock bottom">
				<div class="singleblock_heading_full">Browse By Boat Type</div>
				<div class="singleblock_box">
            <ul class="cat-list">
            ';

            foreach($result as $row){
                $c_id = $row['id'];
                $cname = $row['name'];
                $imgpath = $row['imgpath'];
                if ($imgpath == ""){ $imgpath = "no.jpg"; }
                $caturl = $cm->get_page_url($cname, 'type');
                $returntext .= '
                <li>
                    <h3><a href="'. $caturl .'">'. $cname .'</a></h3>
                    <div class="thumb"><a href="'. $caturl .'"><img src="'. $cm->folder_for_seo .'typeimage/'. $imgpath .'" alt=""></a></div>
                </li>
                ';
            }
            $returntext .= '</ul>';
			//if ($limitval > 0){
					$returntext .= '<div class="categorybutton"><a class="sliderdetailsbutton" href="'. $cm->folder_for_seo .'advanced-search/">See More Search Options</a></div>';
			//}
					$returntext .= '<div class="clear"></div>
					</div></div>
                </section>
            ';
        }
        return $returntext;
    }
	
	public function get_custom_label_name($custom_label_id){
		global $cm;
		$custom_label_txt = $cm->get_common_field_name('tbl_custom_label', 'name', $custom_label_id);	
		return $custom_label_txt;
	}
	
	public function get_boat_details_url($param = array()){
		global $db, $cm;
		
		//param
		$default_param = array("boatid" => 0, "makeid" => 0, "ownboat" => 0, "feed_id" => "", "getdet" => 0);
		$param = array_merge($default_param, $param);
		
		$boatid = round($param["boatid"], 0);
		$makeid = round($param["makeid"], 0);
		$ownboat = round($param["ownboat"], 0);
		$feed_id = $param["feed_id"];
		$getdet = round($param["getdet"], 0);
		//end
		
		if ($getdet == 1){
			$b_ar = $cm->get_table_fields('tbl_yacht', 'manufacturer_id, ownboat, feed_id', $boatid);
			$b_ar = $b_ar[0];
			$makeid = $b_ar["manufacturer_id"];
			$ownboat = $b_ar["ownboat"];
			$feed_id = $b_ar["feed_id"];
		}
		$type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $boatid, 'yacht_id');
		if ($ownboat == 1){
			if ($type_id == $this->catamaran_id OR $feed_id == $this->catamaran_feed_id2){
				$details_url = $cm->get_page_url($boatid, "catamaransales");
			}else{
				$details_url = $cm->get_page_url($boatid, "yachtsale");
			}
		}else{
			if ($feed_id == $this->yacht_feed_id){
				$details_url = $cm->get_page_url($boatid, "yachtsale");
			}elseif ($feed_id == $this->catamaran_feed_id){
				$details_url = $cm->get_page_url($boatid, "catamaransales");
			}else{
				$details_url = $cm->get_page_url($boatid, "yacht");
			}
		}
		
		return $details_url;
	}

    public function display_yacht_map_view($result, $embedlink = 0, $boat_custom_ar = array()){
        global $db, $cm;
        $iounter = 0;
        $mapdataar = array();
        foreach($result as $row){
            foreach($row AS $key => $val){
                ${$key} = $cm->filtertextdisplay($val);
            }
            $addressfull = $this->get_yacht_address($id);
			
            $boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
			$ppath = $boatimg_data_ar->imgpath;
			$imgalt = $boatimg_data_ar->alttag;
            //$details_url = $cm->get_page_url($id, "yacht");
			
			$b_ar = array(
				"boatid" => $id, 
				"makeid" => $manufacturer_id, 
				"ownboat" => $ownboat, 
				"feed_id" => $feed_id, 
				"getdet" => 0
			);
			$details_url = $this->get_boat_details_url($b_ar);
			
			if (round($lat_val) == 0 OR round($lon_val) == 0){
				continue;
			}
			
			if ($embedlink == 1){
				$details_url .= "embed/";
			}
			
			$customcode = $boat_custom_ar["customcode"];
			$extratag = $boat_custom_ar["extratag"];
			if ($customcode != ""){
				$details_url .= $extratag .$customcode . "/";
			}

            $contentval = '
            <div class="listing-map-label listing-status-for-sale">
                <img alt="'. $imgalt .'" class="listing-thumbnail" src="'. $cm->folder_for_seo . 'yachtimage/'. $listing_no .'/' . $ppath .'">
                <a href="'. $details_url .'">
                    <img alt="'. $imgalt .'" class="listing-thumbnail-big" src="'. $cm->folder_for_seo . 'yachtimage/'. $listing_no .'/' . $ppath .'">
                </a>
                <div class="map-label-content">
                    <span class="listing-address"><a href="'. $details_url .'">'. $addressfull .'</a></span>
                    <span class="listing-price">$'. $cm->price_format($price) . '</span>
                </div>
            </div>';
            $mapdataar[] = array(
                'contentval' => $contentval,
                'lat' => $lat_val,
                'lon' => $lon_val
            );
            $iounter++;
        }

        return $mapdataar;
    }

    public function display_yacht($row, $displayoption = 1, $extraclass = "", $compareboat = 0, $charter, $embedlink = 0, $boat_custom_ar = array()){
        global $db, $cm;
        $loggedin_member_id = $this->loggedin_member_id();
        $returntxt = '';
		
		//compare boat checkbox
		$compareboat_text = '';

        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        //Dimensions & Weight
        $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        $manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
        $addressfull = $this->get_yacht_address($id);
        //$ppath = $this->get_yacht_first_image($id);
		$boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
		$ppath = $boatimg_data_ar->imgpath;
		$imgalt = $boatimg_data_ar->alttag;
        if ($status_id == 4){
			$details_url = $cm->get_page_url($id, "previewboat");
		}else{
			$b_ar = array(
				"boatid" => $id, 
				"makeid" => $manufacturer_id, 
				"ownboat" => $ownboat, 
				"feed_id" => $feed_id, 
				"getdet" => 0
			);
			$details_url = $this->get_boat_details_url($b_ar);
		
			if ($embedlink == 1){
				$details_url .= "embed/";
			}
			
			$customcode = $boat_custom_ar["customcode"];
			$extratag = $boat_custom_ar["extratag"];
			if ($customcode != ""){
				$details_url .= $extratag .$customcode . "/";
			}
		}

        $imagefolder = 'yachtimage/'. $listing_no .'/big/';    

        $custom_label_txt = '';
		$custom_label_extra_class = '';
        if ($status_id == 3){
			$custom_label_txt = '<div class="sold"><div>Sold</div></div>';
        }else{
			if ($custom_label_id > 0){
				$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
				$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
				$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
				$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
				$clabel = $this->get_custom_label_name($custom_label_id);
				$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
			}
		}		
		$adminedit = $this->check_user_admin_permission($company_id, $location_id, $loggedin_member_id);
		$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id, $charter);
        		
		$optiontext = '';
		if ($loggedin_member_id > 0){			
			$check_favorites = $this->check_yacht_favorites($id);
			if ($check_favorites > 0){
				$favtext = '<a id="favlist-'. $id .'" yid="'. $id .'" rtsection="0" href="javascript:void(0);" class="yachtfv removefavboat" title="Your favorite. Remove?"><span class="com_none">Remove</span></a>';
			}else{
				$favtext = '<a id="favlist-'. $id .'" yid="'. $id .'" rtsection="1" href="javascript:void(0);" class="yachtfv addfavboat" title="Add to favorites"><span class="com_none">Favorites</span></a>';
			}
			
			/*if ($check_favorites > 0){
				$optiontext .= '<span id="favlist-'. $id .'"><a href="'. $details_url .'" class="" yid="'. $id .'" rtsection="0" title="Your favorite. Remove?"><img src="'. $cm->folder_for_seo .'images/fav2.png" alt="Your favorite. Remove?" /></a></span>';
			}else{
				$optiontext .= '<span id="favlist-'. $id .'"><a href="javascript:void(0);" class="yachtfv" yid="'. $id .'" rtsection="1" title="Add to favorites"><img src="'. $cm->folder_for_seo .'images/fav.png" alt="Add to favorites" /></a></span>';
			}*/

			if ($adminedit == 1 OR $loggedin_member_id == $broker_id OR $loggedin_member_id == 1){
				$optiontext .= '<span id="editlist-'. $id .'"><a href="'. $cm->folder_for_seo .'edit-boat/'. $listing_no .'" class="yachtfv" rtsection="3" title="Edit Listing"><img src="'. $cm->folder_for_seo .'images/editbig.png" alt="Edit Listing" /></a></span>';
				$optiontext .= '<span id="imlist-'. $id .'"><a href="'. $cm->folder_for_seo .'boat-image/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Image"><img src="'. $cm->folder_for_seo .'images/imagebig.png" alt="Manage Image" /></a></span>';
				$optiontext .= '<span id="vdlist-'. $id .'"><a href="'. $cm->folder_for_seo .'boat-video/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Video"><img src="'. $cm->folder_for_seo .'images/videobig.png" alt="Manage Video" /></a></span>';
				$optiontext .= '<span id="atlist-'. $id .'"><a href="'. $cm->folder_for_seo .'boat-attachment/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Attachment"><img src="'. $cm->folder_for_seo .'images/attachment-icon.png" alt="Manage Attachment" /></a></span>';
				$optiontext .= '<span id="dellist-'. $id .'"><a fromdet="1" yid="'. $id .'" href="javascript:void(0);" class="yachtd" rtsection="4" title="Remove Listing"><img src="'. $cm->folder_for_seo .'images/deletebig.png" alt="Remove Listing" /></a></span>';
			}
			$optiontext = '
			<dl>
			  <dd class="options">'. $optiontext .'</dd>
			</dl>';	
		}else{
			$favtext = '<a id="favlist-'. $id .'" href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'pop-login/?chkid='. $id .'" class="loginpop addfavboat" data-type="iframe" title="Add to favorites"><span class="com_none">Favorites</span></a>';
		}
		
		$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
		$fname = $broker_ar[0]["fname"];
		$lname = $broker_ar[0]["lname"];
		$brokername = $fname .' '. $lname;
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
		$c_buttontext .= '<a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-lead-checkout") .'?id='. $broker_id . '&yid='. $id . '&servicerequest=1" class="contactbroker button buttonemail" data-type="iframe">Contact</a>';
		
		$totalboatview = $this->get_total_view_boat(array("boatid" => $id, "daysint" => $this->mostviewdday));
		
		$returntxt .= '
        <li '. $extraclass .'>
        ';
				
				if ($displayoption == 2){
					//LIST VIEW
					
					if ($compareboat == 1){
						$compareboat_text = '<input type="checkbox" class="checkbox compareboatcheckbox cb'. $listing_no .'" name="compareboat" value="'. $listing_no .'" title="Compare Boat" /> <strong>Compare</strong>';
					}
					
					if ($compareboat == 2){
						$compareboat_text = '<input type="checkbox" class="checkbox printboatcheckbox cb'. $listing_no .'" name="compareboat" value="'. $listing_no .'" title="Select To Print" /> <strong>Print</strong>';
					}
					
					$manufacturer_name = '<span title="'. $manufacturer_name .'" class="tooltip">'  . $manufacturer_name . '</span>';
					
					//Engine
					$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
					$ex_result = $db->fetch_all_array($ex_sql);
					$row = $ex_result[0];
					foreach($row AS $key => $val){
						${$key} = htmlspecialchars($val);
					}
					$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
					$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
					$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
					$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);
					
					$company_name = $cm->get_common_field_name('tbl_company', 'cname', $company_id);
					$boatname = $year .' '. $manufacturer_name .' '. $model;
					
					$returntxt .= '
					<div class="product clearfixmain">
					'. $custom_label_txt .'
					<div class="thumb"><a href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a></div>
					<div class="meta clearfixmain">
						<dl>
						'. $compareboat_text .'
						</dl>
						<dl class="make">
							<dt>Make:</dt>
							<dd>'. $manufacturer_name .'</dd>
						</dl>
						<dl>
							<dt>Model:</dt>
							<dd>'. $model .'</dd>
						</dl>
						<dl>
							<dt>Year:</dt>
							<dd>'. $year .'</dd>
						</dl>
						<dl>
							<dt>Length:</dt>
							<dd>'. $length .' ft</dd>
						</dl>
						<dl>
							<dt>Location:</dt>
							<dd>'. $addressfull .'</dd>
						</dl>
						<dl>
							<dt>Engine Make:</dt>
							<dd>'. $engine_make_name .'</dd>
						</dl>
						<dl>
							<dt>Engines:</dt>
							<dd>'. $this->display_yacht_number_field($engine_no) .'</dd>
						</dl>
						<dl>
							<dt>Fuel Type:</dt>
							<dd>'. $fuel_type_name .'</dd>
						</dl>
						<dl>
							<dt>Engine Type:</dt>
							<dd>'. $engine_type_name .'</dd>
						</dl>
						<dl>
							<dt>Drive Type:</dt>
							<dd>'. $drive_type_name .'</dd>
						</dl>
						<dl>
							<dt>Listing company:</dt>
							<dd>'. $company_name .'</dd>
						</dl>                    

						<dl class="price">
							<dt>Price:</dt>
							<dd>'. $price_display . '</dd>
						</dl>

						<dl>					
							<a href="'. $details_url .'" class="button">View Details</a>
						</dl>
						
						<!--<dl>					
							<div class="boatmostview clearfixmain">
							Last '. $this->mostviewdday .' days view: '. $totalboatview .'
							</div>
						</dl>-->
						
						'. $optiontext .'
						</div>
					</div>
					';
				}else{
					//GRID VIEW						
					if ($compareboat == 1){
						$compareboat_text = '<input type="checkbox" class="checkbox compareboatcheckbox cb'. $listing_no .'" name="compareboat" value="'. $listing_no .'" title="Compare Boat" />';
					}
					
					if ($compareboat == 2){
						$compareboat_text = '<input type="checkbox" class="checkbox printboatcheckbox cb'. $listing_no .'" name="compareboat" value="'. $listing_no .'" title="Select To Print" />';
					}
					
					$boatname = $year .' '. $manufacturer_name .' '. $model;
					
					$returntxt .= '					
					<div class="product clearfixmain">
						'. $custom_label_txt .'
						<div class="thumb"><a href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a></div>
						<div class="meta clearfixmain">
							<div class="clearfixmain">
								<h3>'. $compareboat_text .'<span title="'. $boatname .'" class="tooltip">'. $boatname .'</span></h3>
								<dl class="boataddress">'. $addressfull .'</dl>
								<dl class="boatprice">'. $price_display . '</dl>
							</div>
							<div class="boatbutton2col clearfixmain">
								<ul>
									<li>'. $favtext .'</li>
									<li><a href="'. $details_url .'" class="button">Details</a></li>									
								</ul>
							</div>
							<!--<div class="boatmostview clearfixmain">
							Last '. $this->mostviewdday .' days view: '. $totalboatview .'
							</div>-->
							'. $optiontext .'
						</div>
					</div>
					';
				}
		
        $returntxt .= '</li>';				
        return $returntxt;
    }
	
	public function check_yacht_featured($boat_id){
        global $db;        
        $sqltext = "select count(*) as ttl from tbl_yacht_featured where yacht_id = '". $boat_id ."' and featured_upto >= CURDATE()";
        $if_found = $db->total_record_count($sqltext);
        return $if_found;
    }
	
	public function display_featured_yacht_slider_old(){
        global $db, $cm;
		$returntext = '';
		$imgwrapperstart = $imgwrapperend = '';
		$infowrapperstart = $infowrapperend = '';
		$_SESSION["conditional_page_id"] = $cm->get_page_id_by_slug($cm->format_page_slug());
		
		//collect other featured boat
        $query_sql = "select a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
        $query_where .= " a.status_id IN (1,3) and";

        $query_form .= " tbl_yacht_featured as b,";
        $query_where .= " a.id = b.yacht_id and b.featured_upto >= CURDATE() and a.display_upto >= CURDATE() and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by rand()";
		
		$sql = $sql." limit 0, 4";
		$result = $db->fetch_all_array($sql);
		
        $found = count($result);
        if ($found > 0){
				
			$infowrapperstart = '<div class="infowrapper">';
			$infowrapperend = '<div class="clearfix"></div></div>';
			
            $returntext .= '             				
            <ul class="featuredboat" id="home-featured"><!--
            ';
			
			$counter = 0;	
            foreach($result as $row){
                foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}

                $addressfull = $this->get_yacht_address($id, 2);
                $name = $this->yacht_name($id);
				
				$boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;
				
                $details_url = $cm->get_page_url($id, "yacht");
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$addressfull = $this->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $this->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
					
				$returntext .= '
				 --><li><div class="clearfixmain">
				  '. $custom_label_txt .'	
				  '. $imgwrapperstart .'<a class="imgbox" href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a>'. $imgwrapperend .'				  
				  '. $infowrapperstart .'
				  <span class="info">'. $name .'</span>
				  <span class="info2">'. $addressfull .'</span>
				  <span class="price">'. $price_display .'</span>
				  '. $infowrapperend .'
				</div></li><!--
				';				
				$counter++;			 
            }

            $returntext .= '
            --></ul>			
			<div class="clearfix"></div>			
			';
        }
		return $returntext;
    }
	
	public function display_featured_yacht_slider($param = array()){
        global $db, $cm;
		$returntext = '';
		$time_value = 5000;
		
		//param
		$default_param = array("innerpage" => 0);
		$param = array_merge($default_param, $param);
		
		$innerpage = round($param["innerpage"], 0);
		//end		
		
		$_SESSION["conditional_page_id"] = $cm->get_page_id_by_slug($cm->format_page_slug());
		
		//collect other featured boat
        $query_sql = "select a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
        $query_where .= " a.status_id IN (1,3) and";

        $query_form .= " tbl_yacht_featured as b,";
        $query_where .= " a.id = b.yacht_id and b.featured_upto >= CURDATE() and b.display_home = 1 and a.display_upto >= CURDATE() and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by rand()";

		//$sql = $sql." limit 0, 1";
		$result = $db->fetch_all_array($sql);		
        $found = count($result);
        if ($found > 0){
			$startcontent = '';
			$endcontent = '';
			if ($innerpage == 0){
				$startcontent = '
				<div class="featuredyachtsnew clearfixmain">
					<div class="container clearfixmain">
				';
				$endcontent = '
					</div>
				</div>
				';
			}
					
            $returntext .= $startcontent . '
			<div class="owl-carousel" id="featuredboat">';
			
			$counter = 0;	
            foreach($result as $row){
                foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}

                $addressfull = $this->get_yacht_address($id, 2);
                $name = $this->yacht_name($id);
				
				$boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;
				
                //$details_url = $cm->get_page_url($id, "yacht");
				$b_ar = array(
					"boatid" => $id, 
					"makeid" => $manufacturer_id, 
					"ownboat" => $ownboat, 
					"feed_id" => $feed_id, 
					"getdet" => 0
				);
				$details_url = $this->get_boat_details_url($b_ar);
				
				$imagefolder = 'yachtimage/'. $listing_no .'/bigger/';
				$addressfull = $this->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $this->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
				$overview = strip_tags($overview);
				$overviewtext = $cm->fc_word_count($overview, 25) . "...";
				
				$returntext .= '<ul>				
					<li>
						<div class="featuredboatinfo clearfixmain">
							<h2 class="singlelinebottom30">This Month\'s Hot Deals</h2>
							<div class="info">'. $name .'</div>
							<div class="price">'. $price_display .'</div>
							<div class="info2">'. $addressfull .'</div>
							<div class="overviewtext clearfixmain">'. $overviewtext .'</div>							
							<div class="detailsbutton"><a class="button arrow" href="'. $details_url .'">Details</a></div>
						</div>
					</li>
					<li>'. $custom_label_txt .'<a href="'. $details_url .'"><img class="full" src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a></li>	
				</ul>';
				$counter++;			 
            }
			
			$nextprev_text = '';
			if($counter > 1){
				$nextprev_text = '
				<div class="featuredboat_prev"><img alt="Previous" src="'. $cm->folder_for_seo .'images/prev2.png"></div>
				<div class="featuredboat_next"><img alt="Next" src="'. $cm->folder_for_seo .'images/next2.png"></div>
				';
			}

            $returntext .= '</div>' . $nextprev_text . $endcontent;
			
			$returntext .= '
			<script language="javascript">
			$(document).ready(function(){
				var owl_featuredboat = $("#featuredboat");
				
				owl_featuredboat.owlCarousel({
					items: 1,
					merge: true,
					video: false,
					loop: true,
					autoplay: true,
					autoplayHoverPause: true,
					center              :true,
					stagePadding		:0,
					autoplayTimeout: '. $time_value .',
					animateOut: \'fadeOut\',
					nav: false,
					navText: ["<span class=\"featuredboatprev\">P</span>","<span class=\"featuredboatnext\">N</span>"],
					dots: false,
					margin: 0
				});
				
				// Custom Navigation Events
				$(".featuredboat_next").click(function(){
					owl_featuredboat.trigger("next.owl.carousel");
				});
				$(".featuredboat_prev").click(function(){
					owl_featuredboat.trigger("prev.owl.carousel");
				});
			});
			</script>
			';
			
        }
		return $returntext;
    }
	
	public function display_newlist_yacht_slider(){
        global $db, $cm;
		$returntext = '';
        $query_sql = "select *,";
        $query_form = " from tbl_yacht,";
        $query_where = " where";

        $query_where .= " status_id IN (1,3) and";
        $query_where .= " reg_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) and display_upto >= CURDATE() and";
		
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by rand()";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
            $returntext .= '				
                <div class="featured-boat-listings">  
				<div class="widgeticon"><img src="'. $cm->folder_for_seo .'images/newlist-icon.png" title="Newly Listed" alt=""></div>              
				<div class="widget eqht">
				<h2 class="featuredboat-icon"> Newly Listed</h2>
                <ul class="newboat">
                ';

            foreach($result as $row){
                $id = $row['id'];
				$listing_no = $row['listing_no'];
                $company_id = $row['company_id'];
                $price = $row['price'];
				$price_tag_id = $row['price_tag_id'];
                $status_id = $row['status_id'];
				$custom_label_id = $row['custom_label_id'];
				$charter_id = $row['charter_id'];
				$charter_price = $row['charter_price'];
				$price_per_option_id = $row['price_per_option_id'];

                $addressfull = $this->get_yacht_address($id, 2);
                $name = $this->yacht_name($id);
                $ppath = $this->get_yacht_first_image($id);
                $details_url = $cm->get_page_url($id, "yacht");
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
                    $custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
                }else{
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];						
						
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $this->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
				$returntext .= '
				<li>
				  '. $custom_label_txt .'	
				  <a href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt=""></a>				  
				  <span class="info">'. $name .'</span>
				  <span class="price">'. $price_display .'</span>
				</li>
				';                
            }

            $returntext .= '
            </ul>
			<div class="clearfix"></div>
			</div>
			<div class="widget-bottom">
				<div class="floatleft"><div id="fb_pager2" class="fb_pager"></div></div>
		  	</div>
			</div>
            ';
        }
		return $returntext;
    }
	
	public function display_instock_boat($argu = array()){
        global $db, $cm;
		$returntext = '';
		$imgwrapperstart = $imgwrapperend = '';
		$infowrapperstart = $infowrapperend = '';
		$_SESSION["conditional_page_id"] = $cm->get_page_id_by_slug($cm->format_page_slug());
		
		//collect other featured boat
        $query_sql = "select a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
        $query_where .= " a.status_id IN (1,3) and";
        $query_where .= " a.display_upto >= CURDATE() and a.boat_in_stock = 1";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by rand()";
		
		$sql = $sql." limit 0, 4";
		$result = $db->fetch_all_array($sql);
		
        $found = count($result);
        if ($found > 0){
				
			$infowrapperstart = '<div class="infowrapper">';
			$infowrapperend = '<div class="clearfix"></div></div>';			
            $returntext .= '
            <ul class="instockboat"><!--
            ';
			
			$counter = 0;	
            foreach($result as $row){
                foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}

                $addressfull = $this->get_yacht_address($id, 2);
                $name = $this->yacht_name($id);
                $ppath = $this->get_yacht_first_image($id);
                $details_url = $cm->get_page_url($id, "yacht");
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$addressfull = $this->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $this->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);					
				$returntext .= '
				 --><li><div class="clearfixmain">
				  '. $custom_label_txt .'	
				  '. $imgwrapperstart .'<a class="imgbox" href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt=""></a>'. $imgwrapperend .'				  
				  '. $infowrapperstart .'
				  <span class="info">'. $name .'</span>
				  <span class="info2">'. $addressfull .'</span>
				  <span class="price">'. $price_display .'</span>
				  '. $infowrapperend .'
				</div></li><!--
				';
				$counter++;			 
            }

            $returntext .= '
            --></ul>
			<div class="clearfix"></div>			
			';
        }
		return $returntext;
    }
	
	public function display_latest_boat($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$default_param = array("categoryid" => 0, "ownboat" => 1);
		$param = array_merge($default_param, $param);
		
		$ownboat = round($param["ownboat"], 0);
		$categoryid = round($param["categoryid"], 0);
		//end
		
		//create sql
		$query_sql = "select *,";
        $query_form = " from tbl_yacht,";
        $query_where = " where";
		
		$query_where .= " manufacturer_id > 0 and";
		
		if ($categoryid > 0){
			$query_where .= " category_id = '". $categoryid ."' and";
		}
		
		if ($owned == 1){
			$query_where .= " ownboat = 1 and";
		}else{
			$query_where .= " yw_id > 0 and ownboat = 0 and";
		}
		
        $query_where .= " status_id IN (1,3) and";
		$query_where .= " display_upto >= CURDATE() and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by reg_date desc";
		$sql = $sql." limit 0, 4";
		//end
		
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){

            $returntext .= '             				
            <ul class="latestboat"><!--
            ';
			
			$counter = 0;	
            foreach($result as $row){
                foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}

                $addressfull = $this->get_yacht_address($id, 2);
                $name = $this->yacht_name($id);
				
				$boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;
				
                $details_url = $cm->get_page_url($id, "yacht");
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$addressfull = $this->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $this->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
				$overview = strip_tags($overview);
				$overviewtext = $cm->fc_word_count($overview, 11) . "...";
				$overviewtext2 = $cm->fc_word_count($overview, 22) . "...";
				
				$returntext .= '--><li><a href="'. $details_url .'">
				<article class="caption">
					<img alt="'. $imgalt .'" class="caption__media" src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" />
					<div class="caption__overlay">
						<div class="caption__overlay__title">'. $name .'</div>
						<div class="caption__overlay__content">
							<p class="overviewbig clearfixmain">'. $overviewtext2 .'</p>
							<p class="overviewsmall clearfixmain">'. $overviewtext .'</p>
							<span class="price">'. $price_display .'</span>
							<span class="fullspecs" href="'. $details_url .'">Full Specs</span>
						</div>
					</div>
				</article>	
				</a></li><!--';
				
				$counter++;			 
            }

            $returntext .= '
            --></ul>
			';
		}
		
		return $returntext;
	}
	
	public function get_selected_display_option($countboat = 0){
		$returntext = 1;
		if (isset($_SESSION["created_displayoption"]) AND $_SESSION["created_displayoption"] != ''){
			$returntext = $_SESSION["created_displayoption"];
		}
		return $returntext;
	}
	
	public function display_view_option($countboat = 0, $compareboat = 0){
		$dval = $this->get_selected_display_option($countboat);
		$active1 = $active2 = $active3 = '';
		if ($dval == 3){ 
			$active3 = ' active';
		}elseif ($dval == 2){ 
			$active2 = ' active';
		}else{
			$active1 = ' active';
		}
		
		$returntext = '
		<span>View options</span>
		<a href="javascript:void(0);" compareboat="'. $compareboat .'" dval="1" title="Grid view" class="ydchange icon grid'. $active1 .'">Grid view</a>
		<a href="javascript:void(0);" compareboat="'. $compareboat .'" dval="2" title="List view" class="ydchange icon list'. $active2 .'">List view</a>
		<a href="javascript:void(0);" compareboat="'. $compareboat .'" dval="3" title="Map view" class="ydchange icon map'. $active3 .'">Map view</a>
		';
		
		$returnval = array(
            'doc' => $returntext,
			'dval' => $dval
        );
        return json_encode($returnval);
	}
	
	public function get_selected_sort_option($listsort = 0, $listorder = 2){
		if ($listsort > 0){
			$sortop = $listsort;
			$orderbyop = $listorder;
		}else{
			$sortop = 2;
			$orderbyop = 2;
			if (isset($_SESSION["created_sortop"]) AND $_SESSION["created_sortop"] != ''){
				$sortop = $_SESSION["created_sortop"];
			}

			if (isset($_SESSION["created_orderbyop"]) AND $_SESSION["created_orderbyop"] != ''){
				$orderbyop = $_SESSION["created_orderbyop"];
			}
		}
		$returnval = array(
			'sortop' => $sortop,
			'orderbyop' => $orderbyop
		);
		return json_encode($returnval);				
	}
	
	public function display_sort_option($listsort = 0, $listorder = 2){
		$sortoptionar = json_decode($this->get_selected_sort_option($listsort, $listorder));
		$sortop = $sortoptionar->sortop;
		$orderbyop = $sortoptionar->orderbyop;

		if ($orderbyop == 1){
			$orderby = ' asc';
		}else{
			$orderby = ' desc';
		}
		
		$mostview_sort = '';	
		$active1 = $active2 = $active3 = $active4 = $active10 = '';
		if ($sortop == 10){ 
			$active10 = $orderby. ' active';
			$mostview_sort = '<a href="javascript:void(0);" sortop="10" class="sortrecord'. $active10 .'">Viewed</a>';
		}elseif ($sortop == 4){ 
			$active4 = $orderby. ' active';
		}elseif ($sortop == 3){ 
			$active3 = $orderby. ' active';
		}elseif ($sortop == 2){ 
			$active2 = $orderby. ' active';
		}else{
			$active1 = $orderby. ' active';
		}
		
		$returntext = '
		Sort By: <a href="javascript:void(0);" sortop="1" class="sortrecord'. $active1 .'">Price</a>
		<a href="javascript:void(0);" sortop="2" class="sortrecord'. $active2 .'">Length</a>
		<a href="javascript:void(0);" sortop="3" class="sortrecord'. $active3 .'">Year</a>
		<a href="javascript:void(0);" sortop="4" class="sortrecord'. $active4 .'">Make</a>
		'. $mostview_sort .'
		';
		
		$returnval = array(
            'doc' => $returntext,
			'sortop' => $sortop,
			'orderbyop' => $orderbyop
        );
        return json_encode($returnval);
	}

    public function create_yacht_sql($sqloption = 0, $dstat = 0, $argu = array()){
        global $db, $cm;
        $searchitem = array();
		$ifshortcode = count($argu);
		$impression_icr = 1;
		
		$homd_assigned_featured_boats = $this->get_home_featured_boat_ids();
		
        if ($sqloption == 1){
            //My Favorite Listings
            $loggedin_member_id = $this->loggedin_member_id();
        }elseif ($sqloption == 4){
            //Boat List Matched With Resource
            $resource_id = round($_REQUEST["id"], 0);
        }else{
            $searchno = $_REQUEST["searchno"];
            if ($searchno != ""){
                //Saved Search
                $searchfield = $cm->get_common_field_name('tbl_yacht_save_search', 'searchfield', $searchno, 'searchno');
                $searchfield = json_decode($searchfield);
				$makeid = $searchfield->s_makeid;
                $mfcname = $searchfield->s_mfcname;
				$mfslug = $searchfield->s_mfslug;
                $modelname = $searchfield->s_modelname;
                $prmin = $searchfield->s_prmin;
                $prmax = $searchfield->s_prmax;
                $lnmin = $searchfield->s_lnmin;
                $lnmax = $searchfield->s_lnmax;
                $yrmin = $searchfield->s_yrmin;
                $yrmax = $searchfield->s_yrmax;
                $yearvl = $searchfield->s_yearvl;
                $conditionid = $searchfield->s_conditionid;
				$conditionname = $searchfield->s_conditionname;
                $typeid = $searchfield->s_typeid;
				$typename = $searchfield->s_typename;
                $categoryid = $searchfield->s_categoryid;
                $enginetypeid = $searchfield->s_enginetypeid;
                $drivetypeid = $searchfield->s_drivetypeid;
                $fueltypeid = $searchfield->s_fueltypeid;
                $stateid = $searchfield->s_stateid;
				$countryid = $searchfield->s_countryid;
                $countryname = $searchfield->s_countryname;
                $statename = $searchfield->s_statename;
                $keyterm = $searchfield->s_keyterm;
                $categorynm = $searchfield->s_categorynm;
                $unm = $searchfield->s_unm;
				$owned = $searchfield->s_owned;
				$regshowid = $searchfield->s_regshowid;
				$featured = $searchfield->s_featured;
				$feacat = $searchfield->s_feacat;
				$charter = $searchfield->s_charter;
				$boatstatus = $searchfield->s_boatstatus;
				$tradein = $searchfield->s_tradein;
				$uptoday = $searchfield->s_uptoday;
				$brokerslug = $searchfield->s_brokerslug;
				$sp_typeid = $searchfield->s_sp_typeid;
				$similaryacht_type_filter = $searchfield->s_similaryacht_type_filter;
				$mostviewed = $searchfield->s_mostviewed;
            }else{
				if ($ifshortcode > 0){
					//General Search - from shortcode
					$makeid = round($argu["makeid"], 0);
					$conditionid = round($argu["conditionid"], 0);
					$typeid = round($argu["typeid"], 0);
					$categoryid = round($argu["categoryid"], 0);
					$featured = round($argu["featured"], 0);
					$feacat = round($argu["feacat"], 0);
					$boatstatus = round($argu["boatstatus"], 0);
					$tradein = round($argu["tradein"], 0);
					$charter = round($argu["charter"], 0);
					$uptoday = round($argu["uptoday"], 0);
					$owned = round($argu["owned"], 0);
					
					$prmin =  round($argu["prmin"], 0);
					$prmax =  round($argu["prmax"], 0);
					$lnmin =  round($argu["lnmin"], 0);
					$lnmax =  round($argu["lnmax"], 0);
					$yrmin =  round($argu["yrmin"], 0);
					$yrmax =  round($argu["yrmax"], 0);
					$stateid =  round($argu["stateid"], 0);
					$countryid =  round($argu["countryid"], 0);
					
					$enginetypeid = round($argu["enginetypeid"], 0);
					$drivetypeid = round($argu["drivetypeid"], 0);
					$fueltypeid = round($argu["fueltypeid"], 0);
					
					$keyterm = $argu["keyterm"];
					$mfcname = $argu["mfcname"];
					$brokerslug = $argu["brokerslug"];
					$sp_typeid = round($argu["sp_typeid"], 0);
					$similaryacht_type_filter = round($argu["similaryacht_type_filter"], 0);
					$mostviewed = round($argu["mostviewed"], 0);		
					$chartercheck = 1;
				}else{
					//General Search - from query string
					$makeid = round($_REQUEST["makeid"], 0);
					$mfcname = $_REQUEST["mfcname"];
					$mfslug = $_REQUEST["mfslug"];
					$modelname = $_REQUEST["modelname"];
					$prmin = round($_REQUEST["prmin"], 0);
					$prmax = round($_REQUEST["prmax"], 0);
					$lnmin = round($_REQUEST["lnmin"], 0);
					$lnmax = round($_REQUEST["lnmax"], 0);
					$yrmin = round($_REQUEST["yrmin"], 0);
					$yrmax = round($_REQUEST["yrmax"], 0);
					$yearvl = round($_REQUEST["yearvl"], 0);
					$conditionid = round($_REQUEST["conditionid"], 0);	
					$conditionname = $_REQUEST["conditionname"];
					$typeid = round($_REQUEST["typeid"], 0);
					$stypeid = round($_REQUEST["stypeid"], 0);
					$typename = $_REQUEST["typename"];
					$categoryid = round($_REQUEST["categoryid"], 0);
					$enginetypeid = round($_REQUEST["enginetypeid"], 0);
					$drivetypeid = round($_REQUEST["drivetypeid"], 0);
					$fueltypeid = round($_REQUEST["fueltypeid"], 0);
					$stateid = round($_REQUEST["stateid"], 0);
					$countryid = round($_REQUEST["countryid"], 0);
					$countryname = $_REQUEST["countryname"];
					$statename = $_REQUEST["statename"];
					$keyterm = $_REQUEST["keyterm"];
					$categorynm = $_REQUEST["categorynm"];
					$boatstatus = round($_REQUEST["boatstatus"], 0);
					$tradein = round($_REQUEST["tradein"], 0);
					$uptoday = round($_REQUEST["uptoday"], 0);
					
					$owned = round($_REQUEST["owned"], 0);
					$regshowid = round($_REQUEST["regshowid"], 0);
					$featured = round($_REQUEST["featured"], 0);
					$feacat = round($_REQUEST["feacat"], 0);
					$charter = round($_REQUEST["charter"], 0);
					
					$allmy = round($_REQUEST["allmy"], 0);
					$brokername = $_REQUEST["brokername"];
					$brokerslug = $_REQUEST["brokerslug"];
					$dashboard_ex_sold = round($_REQUEST["dashboard_ex_sold"], 0);
					$sp_typeid = round($_REQUEST["sp_typeid"], 0);
					$similaryacht_type_filter = round($_REQUEST["similaryacht_type_filter"], 0);
					$mostviewed = round($_REQUEST["mostviewed"], 0);
									
					$active_sold = 0;
					$chartercheck = 1;
					if ($sqloption == 2 OR $allmy > 0){
						//My Boat List
						$unm = $_SESSION["cr_uid"];
						$chartercheck = 0;
						if ($dashboard_ex_sold == 0){
							$active_sold = 1;
						}
						$impression_icr = 0;
					}
					$directunm = $_REQUEST["unm"];
					
					$cnm = $_REQUEST["cnm"];
					if ($stypeid > 0){ $typeid = $stypeid; }
				}
            }
        }

        $query_sql = "select distinct a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		$query_group_by = "";
		
		if ($sqloption == 4){
			//Boat List Matched With Resource
			$query_or = "";
			$res_ar = $cm->get_table_fields('tbl_resource', 'country_id, fr_year, to_year, type_id, condition_id, fr_price, to_price, category_id', $resource_id);
			$res_row = $res_ar[0];
			$rescountry_id = $res_row["country_id"];
			$fr_year = $res_row["fr_year"];
			$to_year = $res_row["to_year"];
			$typeid = $res_row["type_id"];
			$condition_id = $res_row["condition_id"];
			$fr_price = $res_row["fr_price"];
			$to_price = $res_row["to_price"];
			$category_id = $res_row["category_id"];
			
			//manufacturer
			$mf_sql = "";
			$chc_sql = "select manufacturer_id from tbl_resource_manufacturer where resource_id = '". $resource_id ."'";
			$chc_result = $db->fetch_all_array($chc_sql);
			$chc_found = count($chc_result);
			if ($chc_found > 0){
				foreach( $chc_result as $chc_row ){
					$chc_id = $chc_row['manufacturer_id'];
					$mf_sql .= $chc_id . ", ";					
				}
				
				$mf_sql = rtrim($mf_sql, ", ");
			}
			
			//state
			$st_sql = "";
			$state_name_oth = "";
			if ($rescountry_id == 1){				
				$chc_sql = "select state_id from tbl_resource_state where resource_id = '". $resource_id ."'";
				$chc_result = $db->fetch_all_array($chc_sql);
				$chc_found = count($chc_result);
				if ($chc_found > 0){
					foreach( $chc_result as $chc_row ){
						$chc_id = $chc_row['state_id'];
						$st_sql .= $chc_id . ", ";					
					}
					
					$st_sql = rtrim($st_sql, ", ");
				}
			}else{
				$state_name_oth = $cm->get_common_field_name('tbl_resource_state', 'state', $resource_id, 'resource_id');
			}
			
			if ($fr_year > 0 AND $to_year > 0){
				$query_where .= " (a.year >= '". $fr_year ."' and a.year <= '". $to_year ."') and";
			}else{
				if ($fr_year > 0){
					$query_or .= " a.year >= '". $fr_year ."' and";				
				}
				
				if ($to_year > 0){
					$query_or .= " a.year <= '". $to_year ."' and";				
				}
			}
			
			if ($category_id > 0){
				$query_or .= " a.category_id = '". $category_id ."' and";
			}
			
			if ($conditionid > 0){
				$query_or .= " a.condition_id = '". $conditionid ."' and";
			}
			
			if ($fr_price > 0 AND $to_price > 0){
				$query_or .= " a.price >= '". $fr_price ."' and a.price <= '". $to_price ."' and";
			}else{
				if ($fr_price > 0){
					$query_or .= " a.price >= '". $fr_price ."' and";
				}
				
				if ($to_price > 0){
					$query_or .= " a.price <= '". $to_price ."' and";
				}
			}			
			
			if ($typeid > 0){
				$query_form .= " tbl_yacht_type_assign as ct,";
				$query_or .= " a.id = ct.yacht_id and ct.type_id = '". $typeid ."' and";
			}
			
			if ($mf_sql != ""){
				$query_or .= " a.manufacturer_id IN (". $mf_sql .") and";
			}
			
			if ($st_sql != ""){
				$query_or .= " a.state_id IN (". $st_sql .") and";
			}
			
			if ($state_name_oth != ""){
				$query_or .= " a.state = '". $cm->filtertext($state_name_oth) ."' and";
			}
			
			if ($query_or != ""){
				//$query_or = rtrim($query_or, "or");
			  	//$query_or = " (" . $query_or . ") and";	
			}else{
				$query_or = ' a.id = 0 and';
			}
			
			$query_where .= $query_or;
			
		}elseif ($sqloption == 5){
			//broker to see client fav list
			$client_id = $cm->get_common_field_name('tbl_user', 'id', $unm, 'uid');
			$query_form .= " tbl_yacht_favorites as fv,";
			$query_where .= " fv.yacht_id = a.id and fv.user_id = '". $client_id ."' and";
		}else{
			
			if ($sqloption == 1){
				$query_form .= " tbl_yacht_favorites as fv,";
				$query_where .= " fv.yacht_id = a.id and fv.user_id = '". $loggedin_member_id ."' and";
				$active_sold = 1;
			}
			
			if ($brokerslug != ""){						
				$cuser_ar = $cm->get_table_fields('tbl_user', 'id, type_id, company_id, location_id', $brokerslug, 'uid');
				$cuser_id = $cuser_ar[0]["id"];
				$cuser_type_id = $cuser_ar[0]["type_id"];				
				//$query_where .= " a.broker_id IN (". $cuser_id .", 1) and";
				$query_where .= " a.broker_id = '". $cuser_id ."' and";
				$query_where .= " a.ownboat = 1 and";		
				$searchitem["s_brokerslug"] = $cm->filtertextdisplay($brokerslug);
			}
			
			if ($directunm != ""){						
				$cuser_ar = $cm->get_table_fields('tbl_user', 'id, type_id, company_id, location_id', $directunm, 'uid');
				$cuser_id = $cuser_ar[0]["id"];
				$cuser_type_id = $cuser_ar[0]["type_id"];			
				if ($cuser_type_id > 1){
					$query_where .= " a.broker_id = '". $cuser_id ."' and a.ownboat = 1 and";
					//$active_sold = 1;
				}
			}
	
			if ($unm != ""){			
				$cuser_ar = $cm->get_table_fields('tbl_user', 'id, type_id, company_id, location_id', $unm, 'uid');
				$cuser_id = $cuser_ar[0]["id"];
				$cuser_type_id = $cuser_ar[0]["type_id"];			
				if ($cuser_type_id > 1){				
					$cuser_company_id = $cuser_ar[0]["company_id"];
					$cuser_location_id = $cuser_ar[0]["location_id"];
					
					/*if ($cuser_type_id == 2 OR $cuser_type_id == 3 OR $allmy == 2){
						$checkf = 'company_id';
						$checkv = $cuser_company_id;
					}elseif ($cuser_type_id == 4){
						$checkf = 'location_id';
						$checkv = $cuser_location_id;
					}else{				
						$checkf = 'broker_id';
						$checkv = $cuser_id;
					}*/
					
					if ($allmy == 2){
						$checkf = 'company_id';
						$checkv = $cuser_company_id;
					}else{				
						$checkf = 'broker_id';
						$checkv = $cuser_id;
					}
					
					$query_where .= " a.". $checkf ." = '". $checkv ."' and";
				}
			}
			
			if ($brokername != "" AND $allmy == 2){	
				$cuser_ar = $cm->get_table_fields('tbl_user', 'id, type_id, company_id, location_id', $brokername, 'concat(fname, \' \', lname)');
				$cuser_id = $cuser_ar[0]["id"];
				$cuser_type_id = $cuser_ar[0]["type_id"];
				
				/*if ($cuser_type_id == 4){
					//location
					$cuser_location_id = $cuser_ar[0]["location_id"];
					$query_where .= " a.location_id = '". $cuser_location_id ."' and";
				}
				
				if ($cuser_type_id == 5 OR $cuser_type_id == 0){
					//agent
					$query_form .= " tbl_user as user,";
					$query_where .= " user.company_id = a.company_id and";
					$query_where .= " user.id = a.broker_id and concat(user.fname, ' ', user.lname) like '%". $cm->filtertext($brokername). "%' and";
				}*/
				
				if ($cuser_type_id > 2){				
					$checkf = 'broker_id';
					$checkv = $cuser_id;
					$query_where .= " a.". $checkf ." = '". $checkv ."' and";
				}
			}
			
			if ($cnm != ""){
				$com_id = $cm->get_common_field_name('tbl_company', 'id', $cnm, 'slug');
				$query_where .= " a.company_id = '". $com_id ."' and";
			}
			
			$query_form .= " tbl_manufacturer as b,";
			$query_where .= " b.id = a.manufacturer_id and";	
			
			if ($makeid > 0){
				$query_where .= " a.manufacturer_id = '". $makeid ."' and";
				$searchitem["s_makeid"] = $makeid;
			}
	
			if ($mfcname != ""){
				$query_where .= "  b.name like '". $cm->filtertext($mfcname). "%' and";
				$searchitem["s_mfcname"] = $cm->filtertextdisplay($mfcname);
			}
			
			if ($mfslug != ""){
				$query_where .= " b.slug = '". $cm->filtertext($mfslug). "' and";
				$searchitem["s_mfslug"] = $cm->filtertextdisplay($mfslug);
			}
	
			if ($modelname != ""){
				//$query_where .= " a.model like '".$cm->filtertext($modelname)."%' and";
				$query_where .= " a.model_slug = '".$cm->filtertext($modelname)."' and";
				$searchitem["s_modelname"] = $cm->filtertextdisplay($modelname);
			}
	
			if ($yrmin > 0){
				$query_where .= " a.year >= '". $yrmin ."' and";
				$searchitem["s_yrmin"] = $yrmin;
			}
			if ($yrmax > 0){
				$query_where .= " a.year <= '". $yrmax ."' and";
				$searchitem["s_yrmax"] = $yrmax;
			}
			if ($yearvl > 0){
				$query_where .= " a.year = '". $yearvl ."' and";
				$searchitem["s_yrmin"] = $yearvl;
				$searchitem["s_yrmax"] = $yearvl;
			}
	
			if ($keyterm != ""){
				$keyterm  = str_replace(' in ', ' ', $keyterm);
				$keyterm  = str_replace(',', ' ', $keyterm);
				$s_key_ar = preg_split("/ /", $cm->filtertextdisplay($keyterm));
				$query_form .= " tbl_yacht_keywords as sch,";
				$query_where .= " sch.yacht_id = a.id and";
	
				foreach($s_key_ar as $s_key_val){
					$query_where.=" sch.keywords like '%".$cm->filtertext($s_key_val)."%' and";
				}
	
				//$query_where .= "  (a.model like '".$cm->filtertext($keyterm)."%' or a.year = '". $cm->filtertext($keyterm). "' or b.name like '". $cm->filtertext($keyterm). "%') and";
			}
			
			if ($categorynm != ""){
				$categoryid = $cm->get_common_field_name('tbl_category', 'id', $categorynm, 'name');            
				$searchitem["s_categorynm"] = $cm->filtertextdisplay($categorynm);			
			}
	
			if ($categoryid > 0){
				$query_where .= " a.category_id = '". $categoryid ."' and";
				$searchitem["s_categoryid"] = $categoryid;
			}
			
			if ($conditionname != ""){
				$conditionid = $cm->get_common_field_name('tbl_condition', 'id', $conditionname, 'name');            
				$searchitem["s_conditionname"] = $cm->filtertextdisplay($conditionname);			
			}
	
			if ($conditionid > 0){
				$query_where .= " a.condition_id = '". $conditionid ."' and";
				$searchitem["s_conditionid"] = $conditionid;
			}
	
			if ($prmin > 0){
				$query_where .= " a.price >= '". $prmin ."' and";
				$searchitem["s_prmin"] = $prmin;
			}
			if ($prmax > 0){
				$query_where .= " a.price <= '". $prmax ."' and";
				$searchitem["s_prmax"] = $prmax;
			}
	
			if ($statename != ""){
				$sqltxt = "select id as ttl from tbl_state where (name = '". $cm->filtertext($statename) ."' or code = '". $cm->filtertext($statename) ."')";
				$stateid = $db->total_record_count($sqltxt);
				
				if ($stateid == 0){
					$query_where .= " a.state = '". $cm->filtertext($statename) ."' and";
				}
				
				$searchitem["s_statename"] = $cm->filtertextdisplay($statename);
			}
	
			if ($stateid > 0){
				$countryid = 1;
				$query_where .= " a.state_id = '". $stateid ."' and";
				$searchitem["s_stateid"] = $stateid;
			}
	
			if ($countryname != ""){
				$sqltxt = "select id as ttl from tbl_country where (name = '". $cm->filtertext($countryname) ."' or code = '". $cm->filtertext($countryname) ."')";
				$countrynid = $db->total_record_count($sqltxt);
				$query_where .= " a.country_id = '". $countrynid ."' and";
				$searchitem["s_countryname"] = $cm->filtertextdisplay($countryname);
			}
			
			if ($countryid > 0){
				$query_where .= " a.country_id = '". $countryid ."' and";
				$searchitem["s_countryid"] = $countryid;
			}
			
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";	
			if ($lnmin > 0 OR $lnmax > 0){
				//$query_form .= " tbl_yacht_dimensions_weight as c,";
				//$query_where .= " a.id = c.yacht_id and";
			}
			if ($lnmin > 0){
				$query_where .= " c.length >= '". $lnmin ."' and";
				$searchitem["s_lnmin"] = $lnmin;
			}
			if ($lnmax > 0){
				$query_where .= " c.length <= '". $lnmax ."' and";
				$searchitem["s_lnmax"] = $lnmax;
			}

			if ($typename != ""){
				$sqltxt = "select id as ttl from tbl_type where slug = '". $cm->filtertext($typename) ."' and status_id = 1";
				$typeid = $db->total_record_count($sqltxt);				
				if ($typeid == 0){ $typeid = 9999999; }
				$searchitem["s_typename"] = $cm->filtertextdisplay($typename);
			}
	
			if ($enginetypeid > 0 OR $drivetypeid > 0 OR $fueltypeid > 0){
				$query_form .= " tbl_yacht_engine as e,";
				$query_where .= " a.id = e.yacht_id and";
			}
			if ($enginetypeid > 0){
				$query_where .= "  e.engine_type_id = '". $enginetypeid . "' and";
				$searchitem["s_enginetypeid"] = $enginetypeid;
			}
			if ($drivetypeid > 0){
				$query_where .= "  e.drive_type_id = '". $drivetypeid . "' and";
				$searchitem["s_drivetypeid"] = $drivetypeid;
			}
			if ($fueltypeid > 0){
				$query_where .= "  e.fuel_type_id = '". $fueltypeid . "' and";
				$searchitem["s_fueltypeid"] = $fueltypeid;
			}
			if ($owned > 0){
				
				if ($typeid > 0 ){
					$searchitem["s_typeid"] = $typeid;
	
					$typesql = "";
					$type_sql = $cm->all_child_type($typeid, $typesql);
					$type_sql = $typeid . ", " . $type_sql;
					$type_sql = rtrim($type_sql, ", ");
		
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id IN (". $type_sql .") and";
				}else{
					if ($owned == 1){
						if ($sp_typeid == 1){
							$query_form .= " tbl_yacht_type_assign as d,";
							$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->catamaran_id .") and";
						}elseif ($sp_typeid == 2){
							$query_form .= " tbl_yacht_type_assign as d,";
							$query_where .= " a.id = d.yacht_id and (d.type_id IN (". $this->catamaran_id .")  OR a.feed_id = '". $this->catamaran_feed_id2 ."') and";
						}
					}elseif ($owned == 2){
						if ($similaryacht_type_filter > 0){
							if ($similaryacht_type_filter == $this->centerconsole_id ){
								//only center console
								$query_form .= " tbl_yacht_type_assign as d,";
								$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->centerconsole_id .") and";
								
							}elseif ($similaryacht_type_filter == $this->sportfishing_id ){
								//only sport fishing
								$query_form .= " tbl_yacht_type_assign as d,";
								$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->sportfishing_id .") and";
								
							}elseif ($similaryacht_type_filter == $this->convertible_id ){
								//only convertible
								$query_form .= " tbl_yacht_type_assign as d,";
								$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->convertible_id .") and";
								
							}elseif ($similaryacht_type_filter == $this->motoryacht_id ){
								//Motor Yacht - exclude Sportfishing and CONVERTIBLE BOAT
								$query_form .= " tbl_yacht_type_assign as d,";
								$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->sportfishing_id .", ". $this->convertible_id .") and";
								
							}else{
								//all except center console
								$query_form .= " tbl_yacht_type_assign as d,";
								$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->centerconsole_id .") and";
							}
						}
					}
				}
				
				if ($owned == 1){
					//$query_where .= "  a.yw_id > 0 and a.ownboat = 1 and";
					$query_where .= "  a.ownboat = 1 and";					
				}
				if ($owned == 2){
					$query_where .= "  a.yw_id > 0 and a.ownboat = 0 and";
					
					if ($sp_typeid == 1){
						$query_where .= "  a.feed_id = '". $this->yacht_feed_id."' and";
					}elseif ($sp_typeid == 2){
						$query_where .= "  a.feed_id = '". $this->catamaran_feed_id."' and";
					}				
				}
				
			}else{
				if ($typeid > 0 ){
					$searchitem["s_typeid"] = $typeid;
		
					$typesql = "";
					$type_sql = $cm->all_child_type($typeid, $typesql);
					$type_sql = $typeid . ", " . $type_sql;
					$type_sql = rtrim($type_sql, ", ");
		
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id IN (". $type_sql .") and";
				}else{
					if ($sp_typeid == 1){
						$query_form .= " tbl_yacht_type_assign as d,";
						$query_where .= " a.id = d.yacht_id and ((d.type_id NOT IN (". $this->catamaran_id .")  and a.ownboat = 1) OR a.feed_id = '". $this->yacht_feed_id."') and";
					}elseif ($sp_typeid == 2){
						$query_form .= " tbl_yacht_type_assign as d,";
						$query_where .= " a.id = d.yacht_id and ((d.type_id IN (". $this->catamaran_id .")  and a.ownboat = 1) OR a.feed_id IN ('". $this->catamaran_feed_id."','". $this->catamaran_feed_id2 ."')) and";
					}
				}
			}
			
			$searchitem["s_owned"] = $owned;
			$searchitem["s_sp_typeid"] = $sp_typeid;
			$searchitem["s_similaryacht_type_filter"] = $similaryacht_type_filter;
			
			
			if ($featured > 0){
				$query_form .= " tbl_yacht_featured as g,";
				$query_where .= " a.id = g.yacht_id and";
				$query_where .= "  g.featured_upto >= CURDATE() and";
				$searchitem["s_featured"] = $featured;
				$active_sold = 1;
				
				if ($feacat > 0){
					//$query_where .= "  g.categoryid = '". $feacat ."' and";
					//$query_where .= "  g.categoryid IN (". $feacat .", 3) and";
					$query_where .= "  g.categoryid_front = '". $feacat ."' and";
					$searchitem["s_feacat"] = $feacat;
				}
			}else{
				//homd_assigned_featured_boats
				//$query_where .= " a.id NOT IN (". $homd_assigned_featured_boats .") and";
			}
			
			if ($chartercheck == 1){
				if ($charter == 1){
					$query_where .= " a.charter_id IN (2, 3) and";
					$searchitem["s_charter"] = 1;
				}elseif ($charter == 0){
					$query_where .= " a.charter_id IN (1, 3) and";
					$searchitem["s_charter"] = 0;
				}
			}
			
			if ($tradein > 0){
				$query_where .= "  a.trade_in = 1 and";
				$searchitem["s_tradein"] = $tradein;
			}
		}        

        if ($dstat > 1){
            $query_where .= " a.status_id = '". $dstat ."' and";
            if ($dstat == 3){ //sold expired list
                $query_where .= " a.display_upto < CURDATE() and";
            }
        }else{
			if ($boatstatus > 0){
				if ($uptoday > 0){
					$query_where .= " a.status_id = '". $boatstatus ."' and a.sold_date >= DATE_SUB(CURDATE(), INTERVAL ". $uptoday ." DAY) and";
				}else{
					$query_where .= " a.status_id = '". $boatstatus ."' and a.display_upto >= CURDATE() and";
				}
			}else{
				if ($active_sold == 1){
            		$query_where .= " a.status_id IN (1,3) and a.display_upto >= CURDATE() and";
				}else{
					$query_where .= " a.status_id = 1 and";
				}
			}
			$searchitem["s_boatstatus"] = $boatstatus;
        }
		
		if ($mostviewed > 0){
			$query_sql .= " sum(mv.total_view) as total_view_boat,";
			$query_form .= " tbl_yacht_view as mv,";
			$query_where .= " a.id = mv.yacht_id and mv.reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $mostviewed ." DAY) and mv.view_type = 1 and";
			$query_group_by = " GROUP BY a.id";	
			$searchitem["s_mostviewed"] = $mostviewed;		
		}
		
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where . $query_group_by;
        $_SESSION["created_sql"] = $sql;
        $_SESSION["created_search"] = $searchitem;
		$_SESSION["created_impression_icr"] = $impression_icr;
        return $sql;
    }

    public function total_yach_found($sql){
        global $db;
		$extra_field = '';
		if (strpos($sql, ', sum(mv.total_view) as total_view_boat') !== false){
			$extra_field = ', sum(mv.total_view) as total_view_boat';
		}
		
		if (strpos($sql, 'GROUP BY a.id') !== false){
			$sql = str_replace("GROUP BY a.id", "", $sql);
		}	
		
        $sqlm = str_replace("select distinct a.*" . $extra_field ,"select count(distinct a.id) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }

	public function display_yacht_listing($p, $param = array()){	
        global $db, $cm;
		
		//param
		$default_param = array("compareboat" => 0, "displayoption" => 1, "ajaxpagination" => 0, "dstat" => 0, "sortop" => 0, "orderbyop" => 0, "to_check_val" => '', "qreset" => 0, "mostviewed" => 0, "sp_typeid" => 0);
		$param = array_merge($default_param, $param);
		
		$compareboat = $param["compareboat"];
		$displayoption = $param["displayoption"];
		$ajaxpagination = $param["ajaxpagination"];
		$dstat = $param["dstat"];
		$sortop = $param["sortop"];
		$orderbyop = $param["orderbyop"];
		$to_check_val = $param["to_check_val"];
		$qreset = $param["qreset"];
		$mostviewed_sh = $param["mostviewed"];
		$sp_typeid = $param["sp_typeid"];
		//end
		
        $returntxt = '';
		$nohead = round($_REQUEST["nohead"], 0);
		$charter = round($_REQUEST["charter"], 0);
		$filterdisplay = round($_REQUEST["filterdisplay"], 0);
		$mostviewed_qr = round($_REQUEST["mostviewed"], 0);
		
		$boat_custom_ar = array();
		$boat_custom_ar["sp_typeid"] = $sp_typeid;
		
		$_SESSION["created_displayoption"] = $displayoption;
		$_SESSION["created_sortop"] = $sortop;
		$_SESSION["created_orderbyop"] = $orderbyop;
		
		/*if ($mostviewed_sh > 0 OR $mostviewed_qr > 0){
			$dcon = $this->mostviewdno;
		}else{
			$dcon = $cm->pagination_record;
		}*/
		$dcon = $cm->pagination_record;
        
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }
		
		if ($sortop == 1){
			if ($charter == 1){
				$sortfield = 'a.charter_price';
			}else{
				$sortfield = 'a.price_tag_id, a.price';
			}
		}elseif ($sortop == 3){
			$sortfield = 'a.year';
		}elseif ($sortop == 4){
			$sortfield = 'b.name';
		}elseif ($sortop == 10){
			$sortfield = 'total_view_boat';
		}else{
			$sortfield = 'c.length';
		}
		
		if ($orderbyop == 1){
			$orderby = '';
		}else{
			$orderby = ' desc';
		}

        $sorting_sql = $sortfield . $orderby;
        $limitsql = " LIMIT ". $page .", ". $dcon;

        /*if (isset($_SESSION["created_sql"]) AND $_SESSION["created_sql"] != ""){
            $sql = $_SESSION["created_sql"];
        }else{
            if ($dstat > 0){
                //broker boat list
                $sql = $this->create_yacht_sql(2, $dstat);
            }else{
                //normal search
                $sql = $this->create_yacht_sql();
            }
        }*/
		
		if ($qreset == 1){
			if ($dstat > 0){
                //broker boat list
                $sql = $this->create_yacht_sql(2, $dstat);
            }else{
                //normal search
                $sql = $this->create_yacht_sql();
            }
			
			$param_page = array(
				"to_check_val" => $to_check_val,
				"to_get_val" => $sql,
				"section_for" => 2
			);
			$cm->insert_data_set($param_page);
		}else{
			$sql = $cm->get_data_set(array("to_check_val" => $to_check_val, "section_for" => 2));
		}

        $foundm = $this->total_yach_found($sql);
		if ($mostviewed_sh > 0 OR $mostviewed_qr > 0){
			if ($foundm > $this->mostviewdno){
				//$foundm = $this->mostviewdno;
			}
		}
		$gen_sql = $sql;

        if ($displayoption == 1 OR $displayoption == 2){
            //list view or grid view - pagination required
            $sql = $sql." order by ". $sorting_sql . $limitsql;
			
        }else{
            //map view
            $returntxt .= '<div id="map"></div>';
        }

        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
		//$returntxt .= $sql;
        if ($found > 0){
            if ($displayoption == 3){
                //map view
                $mapdataar = $this->display_yacht_map_view($result, $nohead, $boat_custom_ar);
            }else{
                //list view or grid view
                $class_ex = ' gridview-new';
                $imoption = 0;
                if ($displayoption == 2){
                    $class_ex = ' list-view';
                }

                $extraclass = 'class="no-transition hidden-listing"';
                if ($ajaxpagination == 0){
                    $extraclass = '';
                $returntxt .= '
                    <ul id="listingholder" class="product-list'. $class_ex .'">
                    ';
                }

                foreach($result as $row){
					$returntxt .= $this->display_yacht($row, $displayoption, $extraclass, $compareboat, $charter, $nohead, $boat_custom_ar);
					if ($_SESSION["created_impression_icr"] == 1){
						$this->update_yacht_view($row["id"], 2);
					}
                }

                $p++;
                if ($remaining > $dcon){
                    $button_no = $dcon;
                }else{
                    $button_no = $remaining;
                }

                if ($ajaxpagination == 0){

                    $returntxt .= '
                    </ul>
                    <div class="clear"></div>
                    ';

                    if ($remaining > 0){

                        $returntxt .= '
                        <p class="t-center"><a href="javascript:void(0);" p="'. $p .'" class="moreyacht button loding"><span>Load <recno>'. $button_no .'</recno> more listings</span></a></p>
                        ';
                    }

                }
            }
            /*
            if ($ajaxpagination == 1){

                $returnval[] = array(
                    'pg' => $p,
                    'button_no' => $button_no,
                    'doc' => $returntxt
                );

                return json_encode($returnval);
            }else{
                return $returntxt;
            }
            */
			
			$categorylist = '';			
			$conditionlist = '';
			$boattypelist = $boattypelistfull = '';
			$makelist = $makelistfull = '';
			$modellist = $modellistfull = '';
			$countrylist = $countrylistfull = '';
			$statelist = $statelistfull = '';
			if ($filterdisplay == 1){
				global $yachtchildclass;
				$categorylist = $yachtchildclass->get_category_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_categoryid"], "small_list" => 1));				
				$conditionlist = $yachtchildclass->get_condition_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_conditionid"], "small_list" => 1));
				$boattypelist = $yachtchildclass->get_boattype_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_typeid"], "small_list" => 1));
				$boattypelistfull = $yachtchildclass->get_boattype_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_typeid"], "small_list" => 0));
				$makelist = $yachtchildclass->get_make_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_mfcname"], "small_list" => 1));
				$makelistfull = $yachtchildclass->get_make_list_filter(array("sql" => $gen_sql, "activeval" => $_SESSION["created_search"]["s_mfcname"], "small_list" => 0));
				
				if ($_SESSION["created_search"]["s_mfcname"] != ""){
					$modellist = $yachtchildclass->get_model_list_filter(array("sql" => $gen_sql, "mfcname" => $_SESSION["created_search"]["s_mfcname"], "activeval" => $_SESSION["created_search"]["s_modelname"], "small_list" => 1));
					$modellistfull = $yachtchildclass->get_model_list_filter(array("sql" => $gen_sql, "mfcname" => $_SESSION["created_search"]["s_mfcname"], "activeval" => $_SESSION["created_search"]["s_modelname"], "small_list" => 0));
				}				
				
				$countryid = $_SESSION["created_search"]["s_countryid"];
				$countrylist = $yachtchildclass->get_country_list_filter(array("sql" => $gen_sql, "activeval" => $countryid, "small_list" => 1));
				$countrylistfull = $yachtchildclass->get_country_list_filter(array("sql" => $gen_sql, "activeval" => $countryid, "small_list" => 0));
								
				if ($countryid > 0){
					
					if ($countryid == 1){
						$activeval = $_SESSION["created_search"]["s_stateid"];
					}else{
						$activeval = $_SESSION["created_search"]["s_statename"];
					}
					
					$statelist = $yachtchildclass->get_state_list_filter(array("sql" => $gen_sql, "countryid" => $countryid, "activeval" => $activeval, "small_list" => 1));
					$statelistfull = $yachtchildclass->get_state_list_filter(array("sql" => $gen_sql, "countryid" => $countryid, "activeval" => $activeval, "small_list" => 0));
				}
			}

            $returnval[] = array(
                'pg' => $p,
                'button_no' => $button_no,
                'totalrec' => $foundm,
                'displayoption' => $displayoption,
                'doc' => $returntxt,
                'mapdoc' => $mapdataar,
				'categorylist' => $categorylist,				
				'conditionlist' => $conditionlist,
				'boattypelist' => $boattypelist,
				'boattypelistfull' => $boattypelistfull,
				'makelist' => $makelist,
				'makelistfull' => $makelistfull,
				'modellist' => $modellist,
				'modellistfull' => $modellistfull,
				'countrylist' => $countrylist,
				'countrylistfull' => $countrylistfull,
				'statelist' => $statelist,
				'statelistfull' => $statelistfull
            );

        }else{
			global $frontend;
			$returntxt = '<script src="https://www.google.com/recaptcha/api.js" async defer></script><p>'. $cm->get_systemvar('BTNFD') .'</p>'. $frontend->display_boat_finder_form(1);
            $returnval[] = array(
                'pg' => 1,
                'button_no' => 0,
                'totalrec' => 0,
                'displayoption' => $displayoption,
                'doc' => $returntxt,
                'mapdoc' => array(),
				'categorylist' => '',				
				'conditionlist' => '',
				'boattypelist' => '',
				'boattypelistfull' => '',
				'makelist' => '',
				'makelistfull' => '',
				'modellist' => '',
				'modellistfull' => '',
				'countrylist' => '',
				'countrylistfull' => '',
				'statelist' => '',
				'statelistfull' => ''
            );
        }

        return json_encode($returnval);
    }

    public function check_yacht_with_return($checkval, $checkopt = 0){
        global $db, $cm, $frontend;
        $loggedin_member_id = $this->loggedin_member_id();
        if ($checkopt == 1 OR $checkopt == 2){
            //$checkfield = 'listing_no';
			$sql = "select * from tbl_yacht where listing_no = '". $cm->filtertext($checkval) ."' and";
        }elseif ($checkopt == 3){
			$y = round($_REQUEST['y'], 0);
			$mf = $_REQUEST['mf'];
			$md = $_REQUEST['md'];
			$lnum = round($_REQUEST['lnum'], 0);
			$boatslug = $_REQUEST['boatslug'];
			
			if ($boatslug != ""){
				$sql = "select * from tbl_yacht where listing_no = '". $cm->filtertext($lnum) ."' and boat_slug = '". $cm->filtertext($boatslug) ."' and";
			}else{
				$makeid = $cm->get_common_field_name('tbl_manufacturer', 'id', $mf, 'slug');
            	$sql = "select * from tbl_yacht where listing_no = '". $cm->filtertext($lnum) ."' and manufacturer_id = '". $cm->filtertext($makeid) ."' and model_slug = '". $cm->filtertext($md) ."' and year = '". $cm->filtertext($y) ."' and";
			}			
			
        }else{
            //$checkfield = 'id';
			$sql = "select * from tbl_yacht where id = '". $cm->filtertext($checkval) ."' and";
        }
        
        if ($checkopt == 2){
            //check edit form
            if ($loggedin_member_id == 1){
                //no code for admin
            }else{				
				$cuser_type_id = $this->get_user_type($loggedin_member_id);
				if ($cuser_type_id == 2 OR $cuser_type_id == 3){
					$com_id = $this->get_broker_company_id($loggedin_member_id);
					$sql .= " company_id = '". $com_id ."'";
				}
				
				if ($cuser_type_id == 4){
					$loc_id = $this->get_broker_company_id($loggedin_member_id);
					$sql .= " location_id = '". $loc_id ."'";
				}
				
				if ($cuser_type_id == 5){
					$sql .= " broker_id = '". $loggedin_member_id ."'";
				}
            }
        }else{
            $sql .= " status_id IN (1,3)";
        }
        $sql = rtrim($sql, ' and');

        $result = $db->fetch_all_array($sql);
        if ($checkopt != 2){
            $found = count($result);
            if ($found == 0){
                $cm->sorryredirect(14);
            }else{
                // check whether expired
                $row = $result[0];
                $status_id = $row["status_id"];

                if ($status_id == 3){
                    $company_id = $row["company_id"];
					$location_id = $row["location_id"];
                    $broker_id = $row["broker_id"];
					$adminedit = $this->check_user_admin_permission($company_id, $location_id, $loggedin_member_id);
                    if ($adminedit != 1 AND $loggedin_member_id != $broker_id AND $loggedin_member_id != 1){
						
						$cur_dt = strtotime(date("Y-m-d"));
                        $display_upto = $row["display_upto"];

						if ($display_upto = $cm->default_future_date){
							$display_upto_n = $cur_dt + 1;
						}else{
							$display_upto_n = strtotime($display_upto);
						}
						
                        if ($display_upto_n < $cur_dt){
                            $cm->sorryredirect(14);
                        }
                    }
                }
            }
        }
        return $result;
    }
	
	public function display_yacht_slider_full_old($yacht_id){
        global $db, $cm;
        $returntxt = '';
		$gallerylink = 0;

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank limit 0, 10";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			if ($found > 1){
				$gallerylink = 1;
			}
			$listing_no = $this->get_yacht_no($yacht_id);
			$yacht_title = $this->yacht_name($yacht_id);
            $status_id = $cm->get_common_field_name('tbl_yacht', 'status_id', $yacht_id);
			$custom_label_id = $cm->get_common_field_name('tbl_yacht', 'custom_label_id', $yacht_id);
			$custom_label_txt = '';
			$custom_label_extra_class = '';
            if ($status_id == 3){
				$custom_label_txt = '<div class="sold"><div>Sold</div></div>';
            }else{
				if ($custom_label_id > 0){
					$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
					$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
					$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
					
					$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
					$clabel = $this->get_custom_label_name($custom_label_id);
					$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
				}
			}

            $returntxt .= '
            <div class="product-slider-wrap clearfixmain">
                '. $custom_label_txt .'
                <ul class="product-slider">
                ';
            foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $row['im_descriptions'];
                $imgpath = $row['imgpath'];
				if ($im_title == ""){
					$im_title = $yacht_title;
				}
                $returntxt .= '<li><a class="fc-slick-pop-open" href="javascript:void(0);"><img src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'" /></a></li>';
            }

            $returntxt .= '
                </ul>
				<a class="prev" href="#" title="Previous">Prev</a><a class="next" href="#" title="Next">Next</a>
				<div id="pager" class="pager"></div>
            </div>
            ';
			
			/*if ($gallerylink == 1){
				if ($cm->isMobileDevice()){
					$returntxt .= '
					<div class="clearfixmain">
						<a class="button buttonfullcenter fc-slick-pop-open" href="javascript:void(0);">View Gallery</a>
					</div>
					';
				}
			}*/
        }
        return $returntxt;
    }
	
	public function display_yacht_slider_full($yacht_id){
        global $db, $cm;
        $returntxt = '';
		$gallerylink = 0;

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			if ($found > 1){
				$gallerylink = 1;
			}
			$listing_no = $this->get_yacht_no($yacht_id);
			$yacht_title = $this->yacht_name($yacht_id);
            $status_id = $cm->get_common_field_name('tbl_yacht', 'status_id', $yacht_id);
			$custom_label_id = $cm->get_common_field_name('tbl_yacht', 'custom_label_id', $yacht_id);
			$custom_label_txt = '';
			$custom_label_extra_class = '';
            if ($status_id == 3){
				$custom_label_txt = '<div class="sold"><div>Sold</div></div>';
            }else{
				if ($custom_label_id > 0){
					$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
					$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
					$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
					
					$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
					$clabel = $this->get_custom_label_name($custom_label_id);
					$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
				}
			}

            $returntxt .= '
            <div class="product-slider-wrap clearfixmain">
                '. $custom_label_txt .'
                <ul class="product-slider">
                ';
            foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $row['im_descriptions'];
                $imgpath = $row['imgpath'];
				if ($im_title == ""){
					$im_title = $yacht_title;
				}
                $returntxt .= '<li><a class="fancybox" data-fancybox="gallery" data-caption="'. $im_title .'" href="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'"><img src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'" /></a></li>';
            }

            $returntxt .= '
                </ul>
				<a class="prev" href="#" title="Previous">Prev</a><a class="next" href="#" title="Next">Next</a>
            </div>
            ';
			
			/*if ($gallerylink == 1){
				if ($cm->isMobileDevice()){
					$returntxt .= '
					<div class="clearfixmain">
						<a class="button buttonfullcenter fc-slick-pop-open" href="javascript:void(0);">View Gallery</a>
					</div>
					';
				}
			}*/
        }
        return $returntxt;
    }
	
	public function display_yacht_slider_slick($yacht_id, $extraclass = 'pop'){
        global $db, $cm;
        $returntxt = '';
		$thumbnailtext = '';
		$gallery_4pic = array();

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 1){
			$sliderclassmain = 'slider-for-' . $extraclass;
			$sliderclassnav = 'slider-nav-' . $extraclass;
			
			$listing_no = $this->get_yacht_no($yacht_id);
            $status_id = $cm->get_common_field_name('tbl_yacht', 'status_id', $yacht_id);
			$custom_label_id = $cm->get_common_field_name('tbl_yacht', 'custom_label_id', $yacht_id);
			$custom_label_txt = '';
			$custom_label_extra_class = '';
            if ($status_id == 3){
				$custom_label_txt = '<div class="sold"><div>Sold</div></div>';
            }else{
				if ($custom_label_id > 0){
					$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
					$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
					$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
					
					$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
					$clabel = $this->get_custom_label_name($custom_label_id);
					$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
				}
			}

            $returntxt .= '
            <div class="fc_slick_slider clearfixmain">
                '. $custom_label_txt .'
                <div class="slider-for '. $sliderclassmain .'">
                ';
				
				$counter_sl = 0;
				foreach($result as $row){
					$im_title = $row['im_title'];
					$im_descriptions = $row['im_descriptions'];
					$imgpath = $row['imgpath'];
					$returntxt .= '<div><img src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'" /></div>';
					$thumbnailtext .= '<div><img src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/big/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'" /></div>';
					
					if ($counter_sl < 4){
						$gallery_4pic[] = $imgpath;
					}
					
					$counter_sl++;
				}

            $returntxt .= '
                </div>
				
				<div class="slider-nav '. $sliderclassnav .'">'. $thumbnailtext .'</div>
            </div>';
			
			$returntxt .= '
			<script>
			jQuery(document).ready(function(){
				jQuery(".'. $sliderclassmain .'").slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: true,

					fade: true,
					asNavFor: ".'. $sliderclassnav .'"
				});
				jQuery(".'. $sliderclassmain .'").bind("touchstart", function(){ console.log("touchstart") });
				jQuery(".'. $sliderclassnav .'").slick({
					slidesToShow: 8,
					slidesToScroll: 1,
					asNavFor: ".'. $sliderclassmain .'",
					dots: false,
					arrows: false,	
					centerMode: true,
					focusOnSelect: true,
					responsive: [
						{
						  breakpoint: 1400,
						  settings: {
							slidesToShow: 12,
						  }
						},{
						  breakpoint: 1024,
						  settings: {
							slidesToShow: 8,
						  }
						},
						{
						  breakpoint: 600,
						  settings: {
							slidesToShow: 4,
						  }
						},
						{
						  breakpoint: 480,
						  settings: {
							slidesToShow: 2,
						  }
						}
					  ]
				});
			});
			</script>
			';
        }
        //return $returntxt;
		
		$returnar = array(
			"found" => $found,
			"returntxt" => $returntxt,
			"gallery_4pic" => $gallery_4pic,
			"listing_no" => $listing_no
		);
		
		return json_encode($returnar);
    }
	
	public function display_yacht_slider_slick_main($yacht_id){
		 global $db, $cm;
		 $slider_ar = json_decode($this->display_yacht_slider_slick($yacht_id, 'main'));
		 return $slider_ar->returntxt;
	}
	
	public function display_yacht_slider_slick_pop_old($yacht_id){
		 global $db, $cm;		 
		 $gallery_text = '';
		 
		 $slider_ar = json_decode($this->display_yacht_slider_slick($yacht_id));
		 $found = $slider_ar->found;
		 $returntxt = $slider_ar->returntxt;
		 
		 if ($found > 1){
			 $gallery_4pic = $slider_ar->gallery_4pic;
			 $listing_no = $slider_ar->listing_no;
			 
			$gallery_text = '
			<section class="section2 clearfixmain">
				<h3 class="singlelinebottom30">Gallery</h3>
				<div class="twocolumnlist clearfixmain">
				<ul> 
			';
			
			foreach($gallery_4pic as $gallery_4pic_row){
				$gallery_text .= '<li><a class="fc-slick-pop-open" href="javascript:void(0);"><img alt="Open Gallery" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/'. $gallery_4pic_row .'" /></a></li>';
			}
			
			$gallery_text .= ' 
				</ul>
				</div>        
			</section>
			 ';
			 
			$returntxt = '
			<div id="overlay2" class="animated hide">
				<a class="fc-close-contact" c="overlay2" href="javascript:void(0);"><i class="fas fa-times"></i><span class="com_none">Close</span></a>
				<div class="fc_slick_slider_top clearfixmain">
				'. $returntxt .'
				</div>
			</div>
			
			<script>
				$(document).ready(function(){
					$(".fc-slick-pop-open").click(function(){
						$("#overlay2").fadeIn(300);
						$(".slider-for-pop")[0].slick.refresh();
						$(".slider-nav-pop")[0].slick.refresh();
					});
					
					$(".fc-close-contact").click(function(){
						var c = $(this).attr("c");
						$("#" + c).fadeOut(300);
					});
				});
			</script>
			';
		 }
		 
		 $returnar = array(
		 	"gallery_text" => $gallery_text,
			"returntxt" => $returntxt
		 );
		 
		 return json_encode($returnar);
	}
	
	public function display_yacht_slider_slick_pop($yacht_id){
		 global $db, $cm;		 
		 $gallery_text = '';
		 
		 $slider_ar = json_decode($this->display_yacht_slider_slick($yacht_id));
		 $found = $slider_ar->found;
		 $returntxt = $slider_ar->returntxt;
		 
		 if ($found > 1){
			 $gallery_4pic = $slider_ar->gallery_4pic;
			 $listing_no = $slider_ar->listing_no;
			 
			$gallery_text = '
			<section class="section2 clearfixmain">
				<h3 class="singlelinebottom30">Gallery</h3>
				<div class="twocolumnlist clearfixmain">
				<ul> 
			';
			
			foreach($gallery_4pic as $gallery_4pic_row){
				$gallery_text .= '<li><a class="fancybox-externallink" href="javascript:void(0);"><img alt="Open Gallery" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/'. $gallery_4pic_row .'" /></a></li>';
			}
			
			$gallery_text .= ' 
				</ul>
				</div>        
			</section>
			 ';
			 
			$gallery_text .= '
			<script>
				$(document).ready(function(){
					$(".fancybox-externallink").click(function(){
						$.fancybox.open( $(".fancybox"), {
							transitionEffect: "fade",
							loop: true,
							hash: false,
							thumbs : {
								autoStart : true,
								axis      : "x"
							}
						});
					});
				});
			</script>
			';
		 }
		 
		 $returnar = array(
		 	"gallery_text" => $gallery_text,
			"returntxt" => ''
		 );
		 
		 return json_encode($returnar);
	}
	
	 public function display_yacht_slider($yacht_id){
        global $db, $cm;
        $returntxt = '';

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);		 
        if ($found > 0){
			$listing_no = $this->get_yacht_no($yacht_id);
			$yacht_title = $this->yacht_name($yacht_id);
            $status_id = $cm->get_common_field_name('tbl_yacht', 'status_id', $yacht_id);
			$custom_label_id = $cm->get_common_field_name('tbl_yacht', 'custom_label_id', $yacht_id);
			$custom_label_txt = '';
			$custom_label_extra_class = '';
            if ($status_id == 3){
				$custom_label_txt = '<div class="sold"><div>Sold</div></div>';
            }else{
				if ($custom_label_id > 0){
					$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
					$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
					$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
					
					$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
					$clabel = $this->get_custom_label_name($custom_label_id);
					$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
				}
			}
			
			$firstimage = '';
			$firstimagealttag = '';
			$carousel_slide_text = '
			<div class="product-carousel-slider-main clearfixmain">
			<div class="product-carousel-slider owl-carousel clearfixmain">
			';
			
			$c = 0;
			foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $cm->filtertextdisplay($row['im_descriptions']);
                $imgpath = $row['imgpath'];
				
				if ($im_title == ""){
					$im_title = $yacht_title;
				}
				
				if ($c == 0){
					$firstimage = $imgpath;
					$firstimagealttag = $im_title;
				}
				$carousel_slide_text .= '<div><a class="fancybox" data-fancybox="gallery"  href="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'"><img alt="'. $im_title .'" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/big/'. $imgpath .'" /></a></div>';
				$c++;
			}
	
			$carousel_slide_text .= '			
			</div>			
			</div>
			';
			
			$returntxt = '
			<div class="product-slider-wrap clearfixmain">
				'. $custom_label_txt .'
				<img alt="'. $firstimagealttag .'" class="full" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $firstimage .'">
			</div>
			'. $carousel_slide_text .'
			';
			
			$returntxt .= '
			<script type="text/javascript">
			$(document).ready(function(){
				var product_carousel_slider_owl = $(".product-carousel-slider");
				product_carousel_slider_owl.owlCarousel({
					margin: 10,
					nav: true,
					navText: ["<span class=\"prevControl\"></span>","<span class=\"nextControl\"></span>"],
					dots: false,	
					loop: false,
					responsive: {
						0: {
							items: 2
						},
						600: {
							items: 3
						},
						1000: {
							items: 5
						},
						1400: {
							items: 7
						}
					}
				});
				
				// Custom Navigation Events
				  /*$(".nextControl").click(function(){
					product_carousel_slider_owl.trigger(\'next.owl.carousel\');
				  })
				  $(".prevControl").click(function(){
					product_carousel_slider_owl.trigger(\'prev.owl.carousel\');
				  })*/
			});
			</script>
			';
        }
        return $returntxt;
    }

    public function display_yacht_gallery($yacht_id){
       
        $returntxt = '';

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			$listing_no = $this->get_yacht_no($yacht_id);
			$yacht_title = $this->yacht_name($yacht_id);
            $returntxt .= '
            <h3 class="title">Gallery</h3>
            <div class="con clearfixmain">
				<ul class="galleryviewoption">
					<li>View Option</li>
					<li><a href="javascript:void(0);" dval="1" title="Grid view" class="ygdchange icon grid active">Grid view</a></li>
					<li><a href="javascript:void(0);" dval="2" title="List view" class="ygdchange icon list">List view</a></li>
				</ul>
                <ul class="product-gallery">
            ';
            foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $row['im_descriptions'];
                $imgpath = $row['imgpath'];
				if ($im_title == ""){
					$im_title = $yacht_title;
				}
                $returntxt .= '<li><a class="fancybox" data-fancybox="gallery"  href="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'"><img alt="'. $im_title .'" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" /></a></li>';
            }

            $returntxt .= '
                </ul>
            </div>
            ';
        }

        return $returntxt;
    }
	
	public function display_yacht_gallery2($yacht_id){
        global $db, $cm;
        $returntxt = '';

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			$listing_no = $this->get_yacht_no($yacht_id);
			$yacht_title = $this->yacht_name($yacht_id);
            $returntxt .= '
			<div class="clearfixmain"><a href="javascript:void(0);" ctabid="8" class="customboattab">Gallery</a></div>
			<div id="ctab8" class="customboattabcontent com_none clearfixmain">
            <div class="fourcolumnlist clearfixmain">				
                <ul>
            ';
            foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $row['im_descriptions'];
                $imgpath = $row['imgpath'];
				if ($im_title == ""){
					$im_title = $yacht_title;
				}
                $returntxt .= '<li><a class="fancybox" data-fancybox="gallery"  href="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'"><img alt="'. $im_title .'" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" /></a></li>';
            }

            $returntxt .= '
                </ul>
            </div>
			</div>
            ';
        }

        return $returntxt;
    }
	
	public function display_yacht_gallery3($yacht_id){
        global $db, $cm;
        $returntxt = '';

        $sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			$listing_no = $this->get_yacht_no($yacht_id);
            $returntxt .= '
			<h2 class="singlelinebottom">Gallery</h2>
			<div class="customboattabcontent clearfixmain">
            <div class="fourcolumnlist clearfixmain">				
                <ul>
            ';
            foreach($result as $row){
                $im_title = $row['im_title'];
                $im_descriptions = $row['im_descriptions'];
                $imgpath = $row['imgpath'];

                $returntxt .= '<li><a class="fancybox" data-fancybox="gallery"  href="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" title="'. $im_title .'" alt="'. $im_title .'"><img alt="'. $im_title .'" src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/bigger/'. $imgpath .'" /></a></li>';
            }

            $returntxt .= '
                </ul>
            </div>
			</div>
            ';
        }

        return $returntxt;
    }

    public function display_yacht_number_field($field, $type = 0, $extraoption = 0){
        $returntxt = '';
        if ($field > 0){
            switch($type){
                case 1:
                    //ft and meter
					$meterunit = ' meter';
					if ($extraoption == 2){
						$meterunit = ' m';
					}
					
					$fieldm = floatval($this->feet_to_meter($field)) . $meterunit;
					if ($extraoption == 1){
						$ft_inchs = $this->explode_feet_inchs($field);
						$ft = $ft_inchs["ft"];
						$inchs = $ft_inchs["inchs"];	
						$ftin = $ft . ' ft ';		
						if ($inchs > 0){
							$ftin .= $inchs . ' in ';
						}
					}else{
						$ftin = floatval($field) . ' ft ';
					}
					$returntxt = $ftin . '- ' . $fieldm;
                    break;

                case 2:
                    //lbs
                    $returntxt = $field . ' lbs';
                    break;

                case 3:
                    //hp Individual
                    $returntxt = $field . ' (Individual)';
                    break;

                case 4:
                    //tank
                    $returntxt = $field . ' gallons';
                    break;

                case 5:
                    //speed
                    $returntxt = $field . ' MPH';
                    break;

                case 6:
                    //range - KM
                    $returntxt = $field . ' KM';
                    break;
					
				case 7:
                    //range - MI
                    $returntxt = $field . ' MI';
                    break;	

                default:
                    $returntxt = $field;
                    break;
            }

        }else{
            $returntxt = '-';
        }
        return $returntxt;
    }

    public function display_yacht_hp($engine_no, $horsepower_individual){
        $returntxt = $this->display_yacht_number_field($horsepower_individual, 3);
        if ($engine_no > 0 AND $horsepower_individual > 0){
            $horsepower_combined = $engine_no * $horsepower_individual;
            $returntxt .= ', '. $horsepower_combined . ' (combined)';
        }
        return $returntxt;
    }
	
	public function display_yacht_tank_cap($unitval, $total_unit){		
		if ($unitval > 0 AND $total_unit > 0){
			$returntxt = $this->display_yacht_number_field($unitval, 4) . ' - ' . $total_unit . ' tank(s)';
		}else{
			$returntxt = '-';
		}
		return $returntxt;
	}
	
	public function get_total_yacht_by_company($company_id){
		global $db;
		$sqltext = "select count(*) as ttl from tbl_yacht where";
		$sqltext .= " company_id = '". $company_id ."' and";
		$sqltext .= " status_id IN (1,3) and display_upto >= CURDATE()";
		$total_y = $db->total_record_count($sqltext);
        return $total_y;
	}

    public function get_total_yacht_by_broker($param = array()){
        global $db, $cm;
		
		//param
		$default_param = array("status_id" => 0, "sold_expired" => 0, "includermaster" => 0);
		$param = array_merge($default_param, $param);
	
		$broker_id = round($param["broker_id"], 0);
		$status_id = round($param["status_id"], 0);
		$sold_expired = round($param["sold_expired"], 0);
		$includermaster = round($param["includermaster"], 0);
		//end
		
		$sqltext = "select count(*) as ttl from tbl_yacht where";
		$sqltext .= " manufacturer_id > 0 and";
		
		if ($includermaster == 1){
			$sqltext .= " broker_id IN (". $broker_id .", 1) and ownboat = 1 and";
		}else{
			$sqltext .= " broker_id = '". $broker_id ."' and ownboat = 1 and";
		}			
		
		if ($status_id > 0){
			$sqltext .= " status_id = '". $status_id ."'";
			if ($sold_expired == 1){
				$sqltext .= " and display_upto >= CURDATE()";
			}
		}else{			
			$sqltext .= " status_id IN (1,3) and display_upto >= CURDATE()";	
		}
		$total_y = $db->total_record_count($sqltext);
        return $total_y;
    }

    public function display_yacht_broker_info($param = array()){
        global $db, $cm;
        
		//param
		$default_param = array("company_id" => 0, "location_id" => 0, "broker_id" => 0, "yacht_id" => 0, "manufacturer_id" => 0, "condition_id" => 0, "ownboat" => 1, "template" => 0);
		$param = array_merge($default_param, $param);
	
		$company_id = round($param["company_id"], 0);
		$broker_id = round($param["broker_id"], 0);
		$location_id = round($param["location_id"], 0);
		$yacht_id = round($param["yacht_id"], 0);
		$manufacturer_id = round($param["manufacturer_id"], 0);
		$condition_id = round($param["condition_id"], 0);
		$ownboat = round($param["ownboat"], 0);
		$template = round($param["template"], 0);
		//end
		
		$loggedin_member_id = $this->loggedin_member_id();
		$adminedit = $this->check_user_admin_permission($company_id, $location_id, $loggedin_member_id);
				
        $company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
        $cname = $company_ar[0]["cname"];
        $logo_imgpath = $company_ar[0]["logo_imgpath"];        
        $profile_url = $cm->get_page_url($broker_id, 'user');		
		
		if ($broker_id == 1){			
			if ($template == 1){
				$broker_photo_text = '<div class="brokerphoto"><img src="'. $cm->folder_for_seo .'images/logo-color.png" alt=""></div>';
			}else{
				$broker_photo_text = '<div class="brokerphoto"><img src="'. $cm->folder_for_seo .'images/logo.png" alt=""></div>';
			}			
			
			$brokername = $cm->sitename;
			
			$location_ad_ar = $this->get_location_address_array($location_id);
			$address = $location_ad_ar["address"];
			$city = $location_ad_ar["city"];
			$state = $location_ad_ar["state"];
			$state_id = $location_ad_ar["state_id"];
			$country_id = $location_ad_ar["country_id"];
			$officephone = $location_ad_ar["phone"];
			$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, '');
			//$addressfull = $address . '<br>' . $addressfull;
			
			$broker_text = '
			<div class="broker clearfixmain">
				'. $broker_photo_text .'
			</div>
			';
			
			$contact_button_text = 'Contact';
			
			//google tracking
			$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
			$brokercontactinfo = '
			<div class="brkbox3 clearfixmain">
				<p><a class="phone" href="tel:'. $officephone .'">Call Now</a></p>
				<p><a class="contactbroker email" href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $yacht_id .'" data-type="iframe">Email</a></p>
			</div>
			';
		}else{
			$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
			$fname = $broker_ar[0]["fname"];
			$lname = $broker_ar[0]["lname"];
			$phone = $broker_ar[0]["phone"];
			
			$broker_ad_ar = $this->get_broker_address_array($broker_id);		
			$officephone = $broker_ad_ar["phone"];
			
			$member_image = $this->get_user_image($broker_id);
			$total_y = $this->get_total_yacht_by_broker(array("broker_id" => $broker_id, "status_id" => 1));
			
			$brokername = $fname .' '. $lname;
			$broker_photo_text = '<div class="brokerphoto"><img class="img_cropped_rounded2" src="'. $cm->folder_for_seo .'userphoto/square/'. $member_image .'" alt="'. $brokername .'"></div>';
			/*$broker_text = '
			<div class="broker clearfixmain">				
				'. $broker_photo_text .'
				<h4>'. $brokername .' <span>'. $total_y .' Listing(s)</span></h4>
			</div>
			';*/
			$broker_text = '
			<div class="broker clearfixmain">				
				'. $broker_photo_text .'
				<h4>'. $brokername .'</h4>
			</div>
			';
			
			$contact_button_text = 'Email Broker';
			
			//google tracking
			$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
			
			/*$phonetext = '';
			if ($phone != ""){ 
				$phonetext .= '<a class="tel brokermobilephone" href="tel:'. $phone .'">'. $phone .'</a><br>'; 
			}
			$phonetext .= '<a class="tel brokerofficephone" href="tel:'. $officephone .'">'. $officephone .'</a>';
			*/
			if ($phone != ""){ 
				$callnow = $phone;
			}else{
				$callnow = $officephone;
			}			
			
			$brokercontactinfo = '
			<div class="brkbox3 clearfixmain">
				<p><a class="phone" href="tel:'. $callnow .'">Call Now</a></p>
				<p><a class="contactbroker email" href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $yacht_id .'" data-type="iframe">Email</a></p>
				<p><a class="active" href="'. $profile_url .'">View Profile</a></p>
			</div>
			';
			/*
			$brokercontactinfo = '
			<div class="brokercontactinfo clearfixmain">
				<ul>
				 	<li>
					<a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $yacht_id .'" class="contactbroker brokeemailcontact" data-type="iframe"><span>'. $contact_button_text .'</span></a><br>
					'. $phonetext .'
					</li>
					<li><a class="button" href="'. $profile_url .'">View Profile</a></li>
				</ul>
			</div>
			';*/
		}
		
		if ($ownboat == 1){
			if ($template == 1){
				$presented_by = '<h3 class="singlelinebottom"><span>Presented</span> by</h3>';
			}else{
				$presented_by = '<h3>Presented by</h3>';
			}
		}else{
			$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
			$feed_id = $cm->get_common_field_name('tbl_yacht', 'feed_id', $yacht_id);
			
			if ($feed_id == $this->catamaran_feed_id){
				$manufacturer_name_display = $manufacturer_name . " Catamarans";
			}else{
				if (strpos($manufacturer_name, 'Yachts') !== false){
					$manufacturer_name_display = $manufacturer_name;
				}else{
					$manufacturer_name_display = $manufacturer_name .' Yachts';
				}
			}
			
			$presented_by = '<p>Need more information? Please contact your <strong>'. $manufacturer_name_display .'</strong> expert</p><hr>';
		}
		
		
		
        $returntxt = '
        '. $presented_by .'
        '. $broker_text .'
		'. $brokercontactinfo .'
        ';

        if ($loggedin_member_id > 0){         
			if ($adminedit == 1 OR $loggedin_member_id == $broker_id OR $loggedin_member_id == 1){
                $listing_no = $this->get_yacht_no($yacht_id);
                $returntxt .= '<div class="editingoption editingoption2 clearfixmain">';
                $returntxt .= '<span id="editlist-'. $yacht_id .'"><a href="'. $cm->folder_for_seo .'edit-boat/'. $listing_no .'" class="yachtfv" rtsection="3" title="Edit Listing"><img src="'. $cm->folder_for_seo .'images/editbig.png" alt="Edit Listing" /></a></span>';
                $returntxt .= '<span id="imlist-'. $yacht_id .'"><a href="'. $cm->folder_for_seo .'boat-image/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Image"><img src="'. $cm->folder_for_seo .'images/imagebig.png" alt="Manage Image" /></a></span>';
				$returntxt .= '<span id="vdlist-'. $yacht_id .'"><a href="'. $cm->folder_for_seo .'boat-video/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Video"><img src="'. $cm->folder_for_seo .'images/videobig.png" alt="Manage Video" /></a></span>';
				$returntxt .= '<span id="atlist-'. $yacht_id .'"><a href="'. $cm->folder_for_seo .'boat-attachment/'. $listing_no .'" class="yachtfv" rtsection="3" title="Manage Attachment"><img src="'. $cm->folder_for_seo .'images/attachment-icon.png" alt="Manage Attachment" /></a></span>';
                $returntxt .= '<span id="dellist-'. $yacht_id .'"><a fromdet="2" yid="'. $yacht_id .'" href="javascript:void(0);" class="yachtd" rtsection="4" title="Remove Listing"><img src="'. $cm->folder_for_seo .'images/deletebig.png" alt="Remove Listing" /></a></span>';
                $returntxt .= '</div>';
            }
        }
        return $returntxt;
    }
	
	public function display_yacht_broker_info_general($modelid, $manufacturer_id){
        global $db, $cm;
		
		$location_ad_ar = $this->get_location_address_array(1);
		$address = $location_ad_ar["address"];
		$city = $location_ad_ar["city"];
		$state = $location_ad_ar["state"];
		$state_id = $location_ad_ar["state_id"];
		$country_id = $location_ad_ar["country_id"];
		$officephone = $location_ad_ar["phone"];
		$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, '');
		$addressfull = $address . '<br>' . $addressfull;
		
		//button urls
		$boat_button_text = '';
		$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'slug', $manufacturer_id);
		$manufacturerarslug = $manufacturerar[0]["slug"];
		$condition_raw = "1,2";
		$condition_ar = explode(",", $condition_raw);
		foreach ($condition_ar as $condition_row){
				
			if ($condition_row == 1){
				$button_text = 'New Inventory in Stock';
				$conditionslug = 'new';					
			}
			
			if ($condition_row == 2){
				$button_text = 'Used Boats';	
				$conditionslug = 'used';				
			}
			
			$inv_url_format = 'make/' . $manufacturerarslug . '/condition/' . $conditionslug;
			$pagename = $cm->serach_url_filtertext($inv_url_format);
			$ret_url = $cm->folder_for_seo . $pagename . "/";
			
			$boat_button_text .= '<div class="cb cb2"><a href="'. $ret_url . '" class="button contact">'. $button_text .'</a></div>';
		}
				
		//phone
		$phone_copy = $cm->get_systemvar('PCLNW');
		$brokername = $cm->sitename;
        $returntxt = '
        <h3>Presented by :</h3>
        <div class="broker clearfixmain">
            <img src="'. $cm->folder_for_seo .'images/logo-color.png" alt="'. $brokername .'">
			<div class="brokermeta clearfixmain">
				<div class="locationaddress">'. $addressfull .'</div>					
			</div>
        </div>
        ';
		
        $returntxt .= '
		<div class="ph"><a class="tel" href="tel:'. $phone_copy .'"><span>'. $phone_copy .'</span></a></div>
		<div class="cb cb2"><a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-model/?m='. $modelid . '" class="contactbroker button contact" data-type="iframe"><span>Contact Us</span></a></div>
		'. $boat_button_text .'
        ';
		
        return $returntxt;
    }
	
	public function display_yacht_broker_info_blog($param = array()){
		global $db, $cm;		
        
		//param
		$default_param = array("company_id" => 0, "location_id" => 0, "broker_id" => 0);
		$param = array_merge($default_param, $param);
	
		$company_id = round($param["company_id"], 0);
		$broker_id = round($param["broker_id"], 0);
		$location_id = round($param["location_id"], 0);
		//end
		
		$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $broker_id);
		$fname = $broker_ar[0]["fname"];
		$lname = $broker_ar[0]["lname"];
		$email = $broker_ar[0]["email"];
		$phone = $broker_ar[0]["phone"];
		
		$broker_ad_ar = $this->get_broker_address_array($broker_id);		
		$officephone = $broker_ad_ar["phone"];
		
		$member_image = $this->get_user_image($broker_id);
		$total_y = $this->get_total_yacht_by_broker(array("broker_id" => $broker_id, "status_id" => 1));
		$brokername = $fname .' '. $lname;
		
		$profile_url = $cm->get_page_url($broker_id, 'user');
		$brokerboat_url = $cm->get_page_url($broker_id, 'brokerboat');
		
		$phonetext = '';
		if ($phone != ""){ 
			$phonetext .= '<p><strong>Cell</strong>: '. $phone .'</p>'; 
			$callnow = $phone;
		}else{
			$callnow = $officephone;
		}
		
		$show_listing_text = '';
		$show_listing_button = '';
		$view_profile_button = '';
		if ($broker_id > 1){
			$show_listing_text = '<span><a href="'. $brokerboat_url .'">'. $total_y .' Listing(S)</a></span>';
			$show_listing_button = '<p><a href="'. $brokerboat_url .'">Show Listings</a></p>';
			$view_profile_button = '<p><a class="active" href="'. $profile_url .'">View Profile</a></p>';
		}
		
		$returntxt = '
		<div class="brkbox">
			<div class="brkbox1"><img class="round" src="'. $cm->folder_for_seo .'userphoto/square/'. $member_image .'" alt="'. $brokername .'"></div>
			<div class="brkbox2">
				<h4>'. $brokername .' &nbsp; '. $show_listing_text .'</h4>
				<p><strong>Email</strong>: '. $email .'</p>
				'. $phonetext .'
				<p><strong>Office</strong>: '. $officephone .'</p>
			</div>
			<div class="brkbox3">
				<p><a class="phone" href="tel:'. $callnow .'">Call Now</a></p>
				<p><a class="contactbroker email" href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '" data-type="iframe">Email</a></p>
				'. $show_listing_button .'
				'. $view_profile_button.'
			</div>
		 </div>
		';
		
		return $returntxt;
	}

    public function yacht_featured_small($s = 0){
        global $db, $cm;
        $returntxt = '';

        $query_sql = "select a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";

        $query_where .= " a.status_id IN (1,3) and";

        $query_form .= " tbl_yacht_featured as b,";
        $query_where .= " a.id = b.yacht_id and b.featured_upto >= CURDATE() and a.display_upto >= CURDATE() and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by rand()";
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
			if ($s == 1){
				$returntxt .= '
				<div class="widgetsidebar">
				<h3>Featured Listings</h3>				
				';
			}else{
				$returntxt .= '
				<section class="section sectionbg2">
				<h3>Featured Listings:</h3>				
				';
			}
			
			
            $returntxt .= '           
            <div class="featured-slider-wrap">
                <ul class="cycle-slideshow featured-slider"
				data-cycle-slides="> li" 
				data-cycle-auto-height="calc"				
				data-cycle-fx="fade" 
				data-cycle-timeout="6000" 
				data-cycle-prev=".featured-slider-wrap .prevControl"
				data-cycle-next=".featured-slider-wrap .nextControl" 
				data-cycle-pager=".featured-slider-wrap .pager" 
				>
            ';

            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay($val);
                }

				$yacht_title = $this->yacht_name($id);
                $addressfull = $this->get_yacht_address($id);
                $details_url = $cm->get_page_url($id, "yacht");
                $imagefolder = 'yachtimage/' . $listing_no . '/big/';
				
				$boatimg_data_ar = json_decode($this->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;

  				$custom_label_txt = '';
				$custom_label_extra_class = '';
                if ($status_id == 3){
                    $custom_label_txt = '<span class="soldtext">Sold</span>';
                }else{
					$custom_label_bgcolor = $cm->get_common_field_name("tbl_custom_label_options", "custom_label_bgcolor", $custom_label_id, "custom_label_id");
					$custom_label_extra_class = ' style="color: #'. $custom_label_bgcolor .';"';				
					$custom_label_txt = '<span class="custom_label_txt"'. $custom_label_extra_class .'>' . $this->get_custom_label_name($custom_label_id) .'</span>';
				}
				$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
				
                $returntxt .= '
                <li>
                    <div class="thumb"><a href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'" width=100% /></a></div>
                    <div class="con">
                        '. $addressfull .'
                        <span class="price">'. $price_display .'</span>
                        '. $custom_label_txt .'
                    </div>
                </li>
                ';
            }

            $returntxt .= '
                </ul>
				<div class="clearfix"></div>
				<div class="pager"></div>
				<span class="prevControl"></span>
				<span class="nextControl"></span>
            </div>            
            ';
			
			if ($s == 1){
				$returntxt .= '
				</div>
				';
			}else{
				$returntxt .= '
				</section>
				';
			}
        }

        return $returntxt;
	}

    public function yacht_save_search_insert($name){
        global $db, $cm;
        $returntxt = 'n';
        $loggedin_member_id = $this->loggedin_member_id();
        if (isset($_SESSION["created_search"]) AND is_array($_SESSION["created_search"]) AND count($_SESSION["created_search"]) > 0){
            $dt = date("Y-m-d H:i:s");
            $jsn_data = json_encode($_SESSION["created_search"]);
            $searchno = time() .  $cm->campaignid(5);
            $sql = "insert into tbl_yacht_save_search (searchno
                                                       , user_id
                                                       , name
                                                       , searchfield
                                                       , reg_date) values ('". $cm->filtertext($searchno) ."'
                                                       , '". $loggedin_member_id ."'
                                                       , '". $cm->filtertext($name) ."'
                                                       , '". $cm->filtertext($jsn_data) ."'
                                                       , '". $dt ."')";
            $db->mysqlquery($sql);
            $returntxt = 'y';
        }

        return $returntxt;
    }

    public function check_save_search($svid){
        global $db, $cm, $frontend;
        $loggedin_member_id = $this->loggedin_member_id();
        $sql = "select * from tbl_yacht_save_search where searchno = '". $cm->filtertext($svid) ."'  and user_id = '". $loggedin_member_id ."'";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $_SESSION["ob"] = $frontend->display_message(20);
            $redpage = $cm->get_page_url(0, "popsorry");
            header('Location: '. $redpage);
            exit;
        }
        return $result;
    }

    public function display_user_save_search($result){
        global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
        $returntext = '';
        $found = count($result);
        if ($found > 0){
            $returntext .= '
            <div class="savesearch-main">
            <ul class="savesearch">';
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = htmlspecialchars($val);
                }
                $openurl = $cm->get_page_url($searchno, 'savesearch');
                $returntext .= '
                <li id="listrow'. $searchno .'">
                    <div class="heading"><a href="'. $openurl .'">'. $name .'</a></div>
                    <div class="options">
                    <a href="'. $openurl .'" title="Open Search"><img src="'. $cm->folder_for_seo .'images/open-icon.png" alt="Open Search" /></a>
                    ';
			if ($loggedin_member_id == $user_id){
                $returntext .= '
				<a svid="'. $searchno .'" href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'emailsearch/?id='. $searchno .'" class="emailsearch" title="Email Search"  data-type="iframe"><img src="'. $cm->folder_for_seo .'images/email.png" alt="Email Search" /></a>
				<a svid="'. $searchno .'" href="javascript:void(0);" class="removesearch" title="Remove Search"><img src="'. $cm->folder_for_seo .'images/del.png" alt="Remove Search" /></a>
				';
			}
                $returntext .= '</div>
                    <div class="clear"></div>
                </li>
                ';
            }
            $returntext .= '
            </ul>
            </div>
            ';
        }
        return $returntext;
    }

    public function user_yacht_remove_search($svid){
        global $db, $cm;
        $loggedin_member_id = $this->loggedin_member_id();
        $addeduser = $cm->get_common_field_name('tbl_yacht_save_search', 'user_id', $svid, 'searchno' );

        if ($loggedin_member_id == $addeduser){
            $sql = "delete from tbl_yacht_save_search where searchno = '". $cm->filtertext($svid) ."' and user_id = '". $loggedin_member_id ."'";
            $db->mysqlquery($sql);
            $sqltext = "select count(*) as ttl from tbl_yacht_save_search where user_id = '". $loggedin_member_id ."'";
            $totalrecord = $db->total_record_count($sqltext);

            $returnval[] = array(
                'totalrecord' => $totalrecord,
                'deleted' => 'y'
            );
        }else{
            $returnval[] = array(
                'totalrecord' => 0,
                'deleted' => 'n'
            );
        }
        return json_encode($returnval);
    }

    public function add_yacht_contact_message($yacht_id, $message){
        global $db, $cm;
        $dt = date("Y-m-d H:i:s");
        $contactid = time() .  $cm->campaignid(5);
        $sql = "insert into tbl_yacht_contact (id
                                               , yacht_id
                                               , message
                                               , reg_date) values ('". $cm->filtertext($contactid) ."'
                                               , '". $yacht_id ."'
                                               , '". $cm->filtertext($message) ."'
                                               , '". $dt ."')";
        $db->mysqlquery($sql);
    }
	
	public function get_total_view_boat($param = array()){
		global $db, $cm;
		
		//param
		$boatid = round($param["boatid"], 0);
		$daysint = round($param["daysint"], 0);
		//end
		
		//$sql = "SELECT count(*) as ttl FROM `tbl_yacht_view` where yacht_id = '". $boatid ."' and reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $daysint ." DAY)";
		$sql = "SELECT sum(total_view) as ttl FROM `tbl_yacht_view` where yacht_id = '". $boatid ."' and reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $daysint ." DAY) and view_type = 1";
		$totalboatview = $db->total_record_count($sql);
		return $totalboatview;
	}

    public function most_viewed_yacht_sql($broker_id, $mn, $yr, $nodays = 0){
        global $db, $cm;
		$company_id = $this->get_broker_company_id($broker_id);		
        $sql = "select distinct a.id, a.listing_no, sum(b.total_view) as total_view_lead from tbl_yacht as a, tbl_yacht_view as b where a.id = b.yacht_id and";

        if ($mn > 0){
            $sql .= " month(b.reg_date) = '". $mn ."' and";
        }

        if ($yr > 0){
            $sql .= " year(b.reg_date) = '". $yr ."' and";
        }
		
		if ($nodays > 0){
            $sql .= " b.reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $nodays ." DAY) and";
        }

        if ($company_id > 0){
            $sql .= " a.company_id = '". $company_id ."' and";
        }

		if ($broker_id > 1){
            $sql .= " a.broker_id = '". $broker_id ."' and";
        }
		
		$sql .= " a.status_id = 1 and";		
		$sql .= " b.view_type = 1";   
		$sql .= " GROUP BY a.id";
        return $sql;
    }

    public function total_most_viewed_lead_yacht_found_old($broker_id){
        global $db;
        $company_id = $this->get_broker_company_id($broker_id);
        $sqltext = "select count(*) as ttl from tbl_yacht where";
        if ($company_id > 0){
            $sqltext .= " company_id = '". $company_id ."' and";
        }
        $sqltext .= " (status_id = 1 or status_id = 3)";
        $foundm = $db->total_record_count($sqltext);
        return $foundm;
    }
	
	public function total_most_viewed_lead_yacht_found($sql, $view_type = 1){
        global $db;		
        
		if ($view_type == 2){
			$sqlm = str_replace("select distinct a.id, a.listing_no, count(b.yacht_id) as total_view_lead","select count(distinct a.id) as ttl",$sql);
		}else{
			$sqlm = str_replace("select distinct a.id, a.listing_no, sum(b.total_view) as total_view_lead","select count(distinct a.id) as ttl",$sql);
		}
		$sqlm = str_replace(" GROUP BY a.id", "", $sqlm);
		$foundm = $db->total_record_count($sqlm);
		return $foundm;
    }

    public function most_viewed_yacht($p, $broker_id, $mn, $yr, $optn = 0, $nodays = 0){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        if ($optn == 1){
            $limitsql = " LIMIT 0, 3";
        }else{
            $dcon = $cm->pagination_record_list;
            $page = ($p - 1) * $dcon;
            if ($page <= 0){ $page = 0; }
            $limitsql = " LIMIT ". $page .", ". $dcon;
        }

        $sorting_sql = "total_view_lead desc";
        $sql = $this->most_viewed_yacht_sql($broker_id, $mn, $yr, $nodays);
        $foundm = $this->total_most_viewed_lead_yacht_found($sql, 1);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
        if ($found > 0){
            foreach($result as $row){
                $returntext .= $this->most_view_lead_boat_display($row);
            }
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" fsection="1" nodays="'. $nodays .'" p="'. $p .'" class="moreviewlead button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
    }

    public function most_leads_yacht_sql($broker_id, $mn, $yr, $nodays = 0){
        global $db, $cm;
        $company_id = $this->get_broker_company_id($broker_id);	
		$sql = "select distinct a.id, a.listing_no, count(b.yacht_id) as total_view_lead from tbl_yacht as a, tbl_form_lead as b where a.id = b.yacht_id and";

        if ($mn > 0){
            $sql .= " month(b.reg_date) = '". $mn ."' and";
        }

        if ($yr > 0){
            $sql .= " year(b.reg_date) = '". $yr ."' and";
        }
		
		if ($nodays > 0){
            $sql .= " b.reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $nodays ." DAY) and";
        }

        if ($company_id > 0){
            $sql .= " a.company_id = '". $company_id ."' and";
        }

		if ($broker_id > 1){
            $sql .= " a.broker_id = '". $broker_id ."' and";
        }
		
		$sql .= " a.status_id = 1 GROUP BY a.id";
        return $sql;
        return $sql;
    }

    public function most_leads_yacht($p, $broker_id, $mn, $yr, $optn = 0, $nodays = 0){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        if ($optn == 1){
            $limitsql = " LIMIT 0, 3";
        }else{
            $dcon = $cm->pagination_record_list;
            $page = ($p - 1) * $dcon;
            if ($page <= 0){ $page = 0; }
            $limitsql = " LIMIT ". $page .", ". $dcon;
        }

        $sorting_sql = "total_view_lead desc";
        $sql = $this->most_leads_yacht_sql($broker_id, $mn, $yr, $nodays);
        $foundm = $this->total_most_viewed_lead_yacht_found($sql, 2);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
        if ($found > 0){
            foreach($result as $row){
                $returntext .= $this->most_view_lead_boat_display($row);
            }
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" fsection="2" nodays="'. $nodays .'" p="'. $p .'" class="moreviewlead button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
    }
	
	public function most_view_lead_boat_display($row, $wh_print_view = 0){
		global $db, $cm;
		
		$yid = $row["id"];
		$listing_no = $row["listing_no"];
		$total_view_lead = round($row["total_view_lead"]);
		$yacht_title = $this->yacht_name($yid);
		$imgpath = $this->get_yacht_first_image($yid);
		$target_path_main = 'yachtimage/' . $listing_no . '/';
		$imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $imgpath .'" border="0" />';
		$imgpath_view = '<img class="view" src="'. $cm->folder_for_seo . 'images/eye-icon.png" border="0" />';
		$details_url = $cm->get_page_url($yid, "yacht");
		
		if ($wh_print_view == 1){
			//Print View
			$returntext = '
			<div style="border-top: 1px solid #e8e8e8; width: 100%; padding: 0 0 3px 0; margin-bottom: 3px; text-align: left; clear: both;">
				<div style="float: left; width: 17%; padding: 1% 1%; line-height: 0;"><a href="'. $details_url .'">'. $imgpath_d .'</a></div>
				<div style="float: left; width: 60%; padding: 2% 2%;"><a style="font-size: 13px; font-weight: bold; font-family: Arial; text-decoration: none; color: #000;" href="'. $details_url .'">'. $yacht_title .'</a></div>
				<div style="float: left; width: 10%; padding: 2% 1%; text-align: center; font-weight: bold; font-family: Arial;">'. $total_view_lead .'</div>
			</div>
			';
		}else{
			//Page View
			$returntext = '
			<div class="divrow clearfixmain">
				<div class="stat-imgholder"><a href="'. $details_url .'">'. $imgpath_d .'</a></div>
				<div class="stat-ytitle"><a href="'. $details_url .'">'. $yacht_title .'</a></div>
				<div class="stat-totalview">'. $total_view_lead .'</div>
			</div>
			';
		}
		
		return $returntext;
	}

    public function display_graph($fsection, $searchopt, $yid, $mn, $yr){
        global $db, $cm;
        $datastring = '';

        $cdate = $yr . '-' . $mn . '-1';
        $tday = date("t", strtotime($cdate));
        $cur_month_name = date("F", strtotime($cdate));

        if ($fsection == 1){
            //viewed
            if ($searchopt == 1){
                for ($val = 1; $val <= $tday; $val++){
                    $total[$val] = 0;
                }

                for ($k = 1; $k <= $tday; $k++){

                    $npsql = "select sum(total_view) as total_view from tbl_yacht_view where yacht_id = '". $yid ."' and month(reg_date)=". $mn ." and year(reg_date)=". $yr ." and dayofmonth(reg_date)=". $k ." group by yacht_id";
                    $npresult = $db->fetch_all_array($npsql);
                    $npfound = count($npresult);

                    $ini_ttl = 0;
                    foreach($npresult as $nprow){
                        $total_view =$nprow['total_view'];
                        $ini_ttl = $ini_ttl + $total_view;
                    }
                    $extra_pp = $ini_ttl;
                    $total[$k]+=$extra_pp;
                }

                $cfgstring = 'Graph For '. $cur_month_name . ' ' . $yr . ',525,350';
            }

            if ($searchopt == 2){
                for ($val = 1; $val <= 12; $val++){
                    $total[$val] = 0;
                }

                for ($k = 1; $k <= 12; $k++){

                    $npsql = "select sum(total_view) as total_view from tbl_yacht_view where yacht_id = '". $yid ."' and year(reg_date)=". $yr ." and month(reg_date)=". $k ." group by yacht_id";
                    $npresult = $db->fetch_all_array($npsql);
                    $npfound = count($npresult);

                    $ini_ttl = 0;
                    foreach($npresult as $nprow){
                        $total_view =$nprow['total_view'];
                        $ini_ttl = $ini_ttl + $total_view;
                    }
                    $extra_pp = $ini_ttl;
                    $total[$k]+=$extra_pp;
                }

                $cfgstring = 'Graph For ' . $yr . ',525,350';
            }
        }

        if ($fsection == 2){
            //leads
            if ($searchopt == 1){
                for ($val = 1; $val <= $tday; $val++){
                    $total[$val] = 0;
                }

                for ($k = 1; $k <= $tday; $k++){

                    $npsql = "select count(yacht_id) as total_leads from tbl_yacht_contact where yacht_id = '". $yid ."' and month(reg_date)=". $mn ." and year(reg_date)=". $yr ." and dayofmonth(reg_date)=". $k ." group by yacht_id";
                    $npresult = $db->fetch_all_array($npsql);
                    $npfound = count($npresult);

                    $ini_ttl = 0;
                    foreach($npresult as $nprow){
                        $total_leads =$nprow['total_leads'];
                        $ini_ttl = $ini_ttl + $total_leads;
                    }
                    $extra_pp = $ini_ttl;
                    $total[$k]+=$extra_pp;
                }

                $cfgstring = 'Graph For '. $cur_month_name . ' ' . $yr . ',525,350';
            }

            if ($searchopt == 2){
                for ($val = 1; $val <= 12; $val++){
                    $total[$val] = 0;
                }

                for ($k = 1; $k <= 12; $k++){

                    $npsql = "select count(yacht_id) as total_leads from tbl_yacht_contact where yacht_id = '". $yid ."' and year(reg_date)=". $yr ." and month(reg_date)=". $k ." group by yacht_id";
                    $npresult = $db->fetch_all_array($npsql);
                    $npfound = count($npresult);

                    $ini_ttl = 0;
                    foreach($npresult as $nprow){
                        $total_leads =$nprow['total_leads'];
                        $ini_ttl = $ini_ttl + $total_leads;
                    }
                    $extra_pp = $ini_ttl;
                    $total[$k]+=$extra_pp;
                }

                $cfgstring = 'Graph For ' . $yr . ',525,350';
            }
        }

        $datastring = implode(",", $total);
        $doc = '<img src="' . $cm->folder_for_seo .'includes/graph/creategraph.php?d='. $datastring .'&c=' . $cfgstring .'" alt="" />';


        $returnval[] = array(
            'doc' => $doc
        );

        return json_encode($returnval);
    }

    public function my_broker_list_sql($log_user_id, $collectoption){
        global $cm;
		
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $log_user_id);		
		$cuser_type_id = $cuser_ar[0]["type_id"];
		$com_id = $cuser_ar[0]["company_id"];
		
        $unm = $_REQUEST["unm"];
        $eml = $_REQUEST["eml"];

        $query_sql = "select *";
        $query_form = " from tbl_user,";
        $query_where = " where";

        if ($unm != ""){
            $query_where .= "  uid like '". $cm->filtertext($unm). "%' and";
        }

        if ($eml != ""){
            $query_where .= "  email like '". $cm->filtertext($eml). "%' and";
        }

		if ($cuser_type_id == 1){
			//Main Admin - All user except main admin
			$query_where .= " id > 1 and";
		}
		
		if ($cuser_type_id == 2){
			//Master Admin - All user of the company - Manager, Location Admin and Broker
			$query_where .= " (type_id = 3 OR type_id = 4 OR type_id = 5) and";
			$query_where .= " company_id = '". $com_id ."' and";
		}
		
		if ($cuser_type_id == 3){
			//Manager - Collect all Location Admin and Broker of the company
			$query_where .= " (type_id = 4 OR type_id = 5) and";
			$query_where .= " company_id = '". $com_id ."' and";
		}
		
		if ($cuser_type_id == 4){
			//Location Admin - Collect all Broker of the company for a location
			$query_where .= " type_id = 5 and";
			$loc_id = $cuser_ar[0]["location_id"];
			$query_where .= " company_id = '". $com_id ."' and";
			$query_where .= " location_id = '". $loc_id ."' and";
		}		
        
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
    }

    public function total_my_broker_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }

    public function my_broker_list($p, $log_user_id, $collectoption){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        $dcon = $cm->pagination_record_list;
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }

        $sorting_sql = "uid";
        $limitsql = " LIMIT ". $page .", ". $dcon;
        $sql = $this->my_broker_list_sql($log_user_id, $collectoption);
		
        $foundm = $this->total_my_broker_found($sql);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);

        if ($found > 0){
            if ($p == 1){
                $returntext .= '
                <div class="divrow thd">
                    <div class="uimg">Image</div>
                    <div class="uname">Username</div>
                    <div class="fname">Name</div>
                    <div class="email">Email</div>
                    <div class="uoptions">Options</div>
                    <div class="clear"></div>
                </div>
            ';
            }

            foreach($result as $row){
                $id = $row["id"];
                $b_uid = $row["uid"];
                $b_fname = $row["fname"];
                $b_lname = $row["lname"];
                $b_email = $row["email"];
				$b_location_id = $row["location_id"];
                $member_image = $this->get_user_image($id);
                $target_path_main = 'userphoto/big/';
                $imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';
				$addressfull = '';
				if ($b_location_id > 0){
					//$b_location_nm = $cm->get_common_field_name('', 'name', $b_location_id);
					$broker_ad_ar = $this->get_broker_address_array($id);		
					$address = $broker_ad_ar["address"];
					$city = $broker_ad_ar["city"];
					$state = $broker_ad_ar["state"];
					$state_id = $broker_ad_ar["state_id"];
					$country_id = $broker_ad_ar["country_id"];
					$zip = $broker_ad_ar["zip"];
					$phone = $broker_ad_ar["phone"];					
					$addressfull = '<br />' . $this->com_address_format('', $city, $state, $state_id, $country_id);	
				}
				
				if ($collectoption == 1){
					//manager
					$details_url = $cm->get_page_url($id, "managersub");
				}
				
				if ($collectoption == 2){
					//location admin
					$details_url = $cm->get_page_url($id, "locationsub");
				}
				
				if ($collectoption == 3){
					//broker/agent
					$details_url = $cm->get_page_url($id, "brokersub");
				}

                $returntext .= '
                <div class="divrow">
                    <div class="uimg"><a href="'. $details_url .'">'. $imgpath_d .'</a></div>
                    <div class="uname">'. $b_uid .'</div>
                    <div class="fname">'. $b_fname .' '. $b_lname . $addressfull .'</div>
                    <div class="email">'. $b_email .'</div>
                    <div class="uoptions">
                    <a href="'. $details_url .'" title="Edit Broker"><img src="'. $cm->folder_for_seo .'images/edit-icon.png" alt="Edit Broker" /></a>
                    <a mbid="'. $id .'" href="javascript:void(0);" class="removebroker" title="Remove Broker"><img src="'. $cm->folder_for_seo .'images/del.png" alt="Remove Broker" /></a>
                    </div>
                    <div class="clear"></div>
                </div>
            ';
            }
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="morebroker button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }

        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
    }

    public function user_broker_delete($log_id, $id){
        global $db, $cm;
		
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $log_id);		
		$cuser_type_id = $cuser_ar[0]["type_id"];
		$company_id = $cuser_ar[0]["company_id"];
		$location_id = $cuser_ar[0]["location_id"];
		
		if ($cuser_type_id == 1){
			//super admin
			$sql = "delete from tbl_user where id = '". $id ."'";
			$db->mysqlquery($sql);
		}
		
		if ($cuser_type_id == 2 OR $cuser_type_id == 3){
			//master admin and manager
			$sql = "delete from tbl_user where id = '". $id ."' and company_id = '". $company_id ."'";
			$db->mysqlquery($sql);
		}
		
		if ($cuser_type_id == 4){
			//location admin
			$sql = "delete from tbl_user where id = '". $id ."' and company_id = '". $company_id ."' and location_id = '". $location_id ."'";
			$db->mysqlquery($sql);
		}
        
        $sval = 'y';
        $optiontext = 'success';
        $returnval[] = array(
            'retval' => $sval,
            'optiontext' => $optiontext
        );
        echo json_encode($returnval);
    }

    public function create_yacht_pdf_html_old($result, $changebroker = 0){
        global $db, $cm;
        $returntxt = '';
        $row = $result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        $yacht_title = $this->yacht_name($id);
        $addressfull = $this->get_yacht_address($id);
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

        $ppath = $this->get_yacht_first_image($id);
        $imagefolder = 'yachtimage/'. $listing_no .'/bigger/';

        $photo_txt = '';
        $sqlp = "select * from tbl_yacht_photo where yacht_id = '". $id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sqlp);
		
		$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);

        $defaultheading = ' font-size: 16px; font-family: arial; color:#4c4c4c; text-align:left; text-decoration: none; text-transform:uppercase;';
        $defaultfontcss = ' font-size: 13px; font-family: arial; color:#4c4c4c; text-align:left; text-decoration: none;';

        $photo_txt .= '<p style="page-break-before:always;"></p>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <p style="padding: 5px 10px 5px 0px;'. $defaultheading .'">Gallery</p>
                    </td>
                </tr>
            </table>
        <div style="width: 100%;">';
        foreach($result as $row){
            $imgpath  = $row['imgpath'];
            $photo_txt .= '<div style="float:left; padding: 0px 5px 10px 5px; margin: 0px; width: 100px; height: 67px;"><img src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/'. $imgpath .'" alt="" /></div>';
        }
        $photo_txt .= '</div>';        
		
		if ($changebroker == 1){
			$broker_id = $this->loggedin_member_id();
			$company_id = $this->get_broker_company_id($broker_id);
		}
		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
        $cname = $company_ar[0]["cname"];
        /*$logo_imgpath = $company_ar[0]["logo_imgpath"];
		
		if ($logo_imgpath != ""){
            $logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="" style="max-width: 300px;"><br>';
        }
		*/
		$logo_imgpath = '<img src="'. $cm->site_url .'/images/logo-color.png" alt="" style="max-width: 200px;"><br>';

        $broker_ar = $cm->get_table_fields('tbl_user', 'email, fname, lname, phone', $broker_id);
        $fname = $broker_ar[0]["fname"];
        $lname = $broker_ar[0]["lname"];
		$phone = $broker_ar[0]["phone"];
		$brokeremail = $broker_ar[0]["email"];
		
		$broker_ad_ar = $this->get_broker_address_array($broker_id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"];
        
        $member_image = $this->get_user_image($broker_id, 1);
        $total_y = $this->get_total_yacht_by_broker(array("broker_id" => $broker_id, "status_id" => 1));

        $b_addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
		
		$brokername = $fname .' '. $lname;
		$broker_name_text = '';
		
		if ($broker_id > 1){
			$broker_name_text = '<span style="font-weight: bold; '. $defaultheading .'">'. $brokername .'</span><br>';
		}
		
        $broker_info_txt = '		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td class="companylogo" width="200" align="left" valign="top" style="border-top: 1px solid #dedcdc; border-bottom: 1px solid #dedcdc; padding: 10px;'. $defaultfontcss .'">
				'. $logo_imgpath .'				
				</td>

				<td width="475" align="right" valign="top" style="border-top: 1px solid #dedcdc; border-bottom: 1px solid #dedcdc; padding: 10px;'. $defaultfontcss .'">				
				'. $broker_name_text .'
				<strong>'. $cname .'</strong><br>				
				'. $address .'<br>
				'. $b_addressfull .'<br>
				Office: '. $officephone .'<br>';
				if ($phone != ""){ $broker_info_txt .= 'Mobile: '. $phone .'<br>'; }				
				$broker_info_txt .=  $brokeremail .'
				</td>';
				
				if ($member_image != ""){
					$broker_info_txt .= '
					<td width="" align="right" valign="top" style="border-top: 1px solid #dedcdc; border-bottom: 1px solid #dedcdc; padding: 10px;'. $defaultfontcss .'">
					<img src="'. $cm->site_url .'/userphoto/'. $member_image .'" alt=""><br>				
					</td>
					';
				}				
		$broker_info_txt .= '</tr>
		</table>
        ';

        $returntxt .= '
        '. $broker_info_txt .'
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3" align="center" valign="top" style="padding: 4px 5px 5px 0px; text-align:center; color:#4c4c4c; font-size: 40px; font-family: Tahoma, Geneva, sans-serif;">'. $yacht_title .'</td>
                </tr>

                <tr>
                    <td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 5px 5px 5px 0px; text-align:center; color:#4c4c4c; font-size: 13px; font-family: Arial, Geneva, sans-serif;">
                    Boat Type: '. $type_name .'
                    </td>

                    <td align="center" valign="top" style="text-align:center; color:#4c4c4c; padding: 5px 5px 5px 0px; text-align:center; color:#4c4c4c; font-size: 13px; font-family: Arial, Geneva, sans-serif;">
                    Address: '. $addressfull .'
                    </td>

                    <td align="right" valign="top" style="text-align:right; color:#4c4c4c; padding: 5px 5px 5px 0px; text-align:center; color:#00afef; font-size: 13px; font-family: Arial, Geneva, sans-serif;">
                    Price: '. $price_display .'
                    </td>
                </tr>
            </table>

            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                 <tr>
                    <td align="center" valign="top"><img src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></td>
                 </tr>
            </table>
			<div style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
			<p style="padding: 5px 10px 5px 0px;'. $defaultheading .'">Overview</p>
			'. $overview .'
			</div>

            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <p style="padding: 5px 10px 5px 0px;'. $defaultheading .'">Specifications</p>
                    <div class="con specifications">
                            <h3>Basic Information</h3>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Manufacturer:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $manufacturer_name .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Vessel Name:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $vessel_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Model:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $model .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Boat Type:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $type_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Year:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $year .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Hull Material:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $hull_material_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Category:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $category_name .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Hull Type:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $hull_type_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Condition:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $condition_name .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Hull Color:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $hull_color .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Location:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $addressfull .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Designer:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $designer .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Available for sale in U.S. waters:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $cm->set_yesyno_field($sale_usa) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Flag of Registry:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $flag_country_name .'</td>
                                </tr>
                            </table>
                    </div>
                </tr>

                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <div class="con specifications">
                            <h3>Dimensions &amp; Weight</h3>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Length:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($length, 1) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Draft - max:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($draft, 1, 1) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">LOA:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($loa, 1, 1) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Bridge Clearance:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($bridge_clearance, 1, 1) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Beam:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($beam, 1, 1) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Dry Weight:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($dry_weight, 1) .'</td>
                                </tr>
                            </table>
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <div class="con specifications">
                            <h3>Engine</h3>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Make:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $engine_make_name .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Engine Type:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $engine_type_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Model:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $engine_model .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Drive Type:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $drive_type_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Engine(s):</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($engine_no) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Fuel Type:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $fuel_type_name .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Hours:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($hours) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Horsepower:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Cruise Speed:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Max Speed:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($max_speed, 5) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Range:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($en_range, 7) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Joystick Control:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $cm->set_yesyno_field($joystick_control) .'</td>
                                </tr>
                            </table>
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <div class="con specifications">
                            <h3>Tank Capacities</h3>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Fuel Tank:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Holding Tank:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_tank_cap($holding_tanks, $no_holding_tanks) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Fresh Water Tank:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">&nbsp;</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">&nbsp;</td>
                                </tr>
                            </table>
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <div class="con specifications">
                            <h3>Accommodations</h3>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Total Cabins:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($total_cabins) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Crew Cabins:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($crew_cabins) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Total Berths:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($total_berths) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Crew Berths:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($crew_berths) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Total Sleeps:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($total_sleeps) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Crew Sleeps:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($crew_sleeps) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Total Heads:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($total_heads) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Crew Heads:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $this->display_yacht_number_field($crew_heads) .'</td>
                                </tr>

                                <tr>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">Captains Cabin:</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">'. $cm->set_yesyno_field($captains_cabin) .'</td>

                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">&nbsp;</td>
                                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="25%">&nbsp;</td>
                                </tr>
                            </table>
                    </td>
                </tr>
            </table>
			<div style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
			<p style="padding: 5px 10px 5px 0px;'. $defaultheading .'">Descriptions</p>
			'. $descriptions .'
			</div>
			<div style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
			'. $this->display_yacht_external_link($id) .'
			</div>
            '. $photo_txt .'

            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'">
                    <p style="padding: 5px 0px 5px 0px;'. $defaultheading .'">Location Map</p>
                    <p style="padding: 5px 0px 5px 0px;"><img style="width: 100%;" src="https://maps.googleapis.com/maps/api/staticmap?center='. urlencode($addressfull) .'&zoom=9&size=750x300&maptype=roadmap&sensor=false&&markers=size:mid%7Ccolor:green%7C'. urlencode($addressfull) .'&key='. $cm->googlemapkey .'"></p>
                    </td>
                </tr>
            </table>
            '. $broker_info_txt .'
        ';

        return $returntxt;
    }
	
	public function create_yacht_pdf_html($result, $changebroker = 0){
		global $db, $cm;
        $returntxt = '';
		
		//some default css
		$defaultheading = ' font-size: 16px; font-family: arial; color:#4c4c4c; text-align:left; text-decoration: none; text-transform:uppercase;';
        $defaultfontcss = ' font-size: 13px; font-family: arial; color:#4c4c4c; text-align:left; text-decoration: none;';
		$defaultfontcss2 = ' font-size: 14px; font-weight:bold; font-family: arial; color:#4c4c4c; text-align:left; text-decoration: none;';
		
		$tabletopspace = 'margin-top: 30px;';
		$tabletopspace2 = 'margin-top: 15px;';
		$tdheadingonly = 'color: #0a1b40; font-family: Arial, Tahoma; font-size: 16px; font-weight: bold; text-decoration: none;text-transform:uppercase;';
		$tdheading = 'border: 1px solid #000; border-bottom: none; border-right: none; background-color: #fff; color: #000000; font-family: Arial, Tahoma; font-size: 13px; font-weight: bold; text-decoration: none;';
		$tdrow = 'border: 1px solid #000; border-bottom: none; border-right: none; background-color: #fff; color: #000000; font-family: Arial, Tahoma; font-size: 13px; font-weight: normal; text-decoration: none;';
		$tdrowbottom = 'border-bottom: 1px solid #000;';
		$tdrowright = 'border-right: 1px solid #000;';
		
		
		$invheading = "margin-top: 2px;";
		$invheadingtd = "color: #0a1b40; font-weight: bold; font-size: 16px; font-family: Arial;";
		$invheadingtd2 = "background-color: #4f5660; padding: 1px 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 16px; font-family: Arial;";	
		$invheadingtdrow2 = "background-color: #fff; padding: 1px; color: #4c4c4c; font-size: 12px; font-family: Arial; line-height: 12px;";
		
		
		//collect boat info
        $row = $result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        $yacht_title = $this->yacht_name($id);
        $addressfull = $this->get_yacht_address($id);
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
		
		//Module name
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
		
		//Boat price text
		$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
		
		//Image
        $ppath = $this->get_yacht_first_image($id);
        $imagefolder = 'yachtimage/'. $listing_no .'/bigger/';
		$logo_imgpath = '<img src="'. $cm->site_url .'/images/logo-color.png" alt="" style="max-width: 200px;">';
		
		//Company - broker
		if ($changebroker == 1){
			$broker_id = $this->loggedin_member_id();
			$company_id = $this->get_broker_company_id($broker_id);
		}
		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
        $cname = $company_ar[0]["cname"];
		
		$broker_ar = $cm->get_table_fields('tbl_user', 'email, fname, lname, phone', $broker_id);
        $fname = $broker_ar[0]["fname"];
        $lname = $broker_ar[0]["lname"];
		$phone = $broker_ar[0]["phone"];
		$brokeremail = $broker_ar[0]["email"];
		
		$broker_ad_ar = $this->get_broker_address_array($broker_id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"];
        
        $member_image = $this->get_user_image($broker_id, 1);
        $total_y = $this->get_total_yacht_by_broker(array("broker_id" => $broker_id, "status_id" => 1));

        $b_addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
		
		$brokername = $fname .' '. $lname;
		$broker_name_text = '';
		$company_info_text = '';
		$member_image_text = '';
		
		if ($broker_id > 1){
			$broker_name_text = '<div style="margin-bottom: 10px; font-weight: bold; '. $defaultheading .'">'. $brokername .'</div>';
			$comoany_address = nl2br($cm->get_systemvar('STADD'));
		
			$company_info_text = '
			<tr>
				<td width="" align="center" valign="top" style="padding: 15px 0px;"><img src="'. $cm->site_url .'/images/logo-color.png" alt="" style="width:150px;"></td>
			</tr>
			<tr>
				<td align="center" valign="top" style="'. $defaultfontcss2 .'">'. $comoany_address .'</td>
			</tr>
			';
			
			if ($member_image != ""){
				$member_image_text .= '
				<tr>
					<td width="" align="center" valign="top" style="padding-bottom: 15px;"><img src="'. $cm->site_url .'/userphoto/big/'. $member_image .'" alt="" style="width:100%"></td>
				</tr>
				';
			}
		}else{
			$member_image_text .= '
			<tr>
				<td width="" align="center" valign="top" style="padding-bottom: 15px;"><img src="'. $cm->site_url .'/images/logo-color.png" alt="" style="width:100%"></td>
			</tr>
			';
		}
		
		$broker_address_full = $broker_name_text . $address . '<br>' . $b_addressfull . '<br>';
		$broker_address_full .= 'Office: '. $officephone .'<br>';
		if ($phone != ""){ 
			$broker_address_full .= 'Mobile: '. $phone .'<br>'; 
		}
		$broker_address_full .= 'Email: '. $brokeremail; 		
		
		//boat phoro
		$photo_txt = '';
        $sqlp = "select * from tbl_yacht_photo where yacht_id = '". $id ."' and imgpath != '' and status_id = 1 order by rank";
        $resultp = $db->fetch_all_array($sqlp);
		$photo_txt .= '<p style="page-break-before:always;"></p>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Gallery</td>
			</tr>
		</table>
        <div style="display: flex; flex-wrap: wrap; justify-content:space-between; width: 100%;'. $tabletopspace2 .'">
		';
		
        foreach($resultp as $rowp){
            $imgpath  = $rowp['imgpath'];
            //$photo_txt .= '<div style="float:left; padding: 0px 5px 10px 5px; margin: 0px; width: 330px; height: 221px;"><img src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/big/'. $imgpath .'" alt="" /></div>';
			$photo_txt .= '<div style="margin: 10px 0 0 0px; width: 49%"><img style="width:100%;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/big/'. $imgpath .'" alt="" /></div>';		
        }
        $photo_txt .= '</div>'; 
		
		//set file name
		$pdffilename = $manufacturer_name . '-' . $model . '-' . $year;
		if ($vessel_name != ""){
			$pdffilename .= '-' . $vessel_name;
			$yacht_title .= ' ' . $vessel_name;
		}
		$pdffilename .= '.pdf';
		
		//Header PDF - all pages
		/*$headertext = '
		<table width="100%" style="padding: 20px 0; border-bottom: 1px solid #dedcdc; vertical-align: bottom; font-family: serif; font-size: 40px; color: #0a1b40;">
			<tr>
				<td width="200" align="left" valign="middle">'. $logo_imgpath .'</td>
				<td align="center" align="right" valign="middle" style="text-align: right; color:#0a1b40; font-size: 24px; font-family: Tahoma, Geneva, sans-serif;">'. $yacht_title .'</td>
			</tr>
		</table>
		';*/

		$headertext = '';
		
		
		$returntxt .= '
		<table width="100%" style="padding: 0 0 20px 0; margin-bottom: 20px; border-bottom: 1px solid #dedcdc; vertical-align: bottom; font-family: serif; font-size: 40px; color: #0a1b40;">
			<tr>
				<td width="200" align="left" valign="middle">'. $logo_imgpath .'</td>
				<td align="center" align="right" valign="middle" style="text-align: right; color:#0a1b40; font-size: 24px; font-family: Tahoma, Geneva, sans-serif;">'. $yacht_title .'</td>
			</tr>
		</table>

		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			 <tr>
				<td align="center" valign="top"><img src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></td>
			 </tr>
			 <tr>
				<td align="center" valign="top" height="30"><img src="'. $cm->site_url . '/images/sp.png" alt=""></td>
			 </tr>
		</table>
		';
		
		$returntxt .= '
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			 <tr>
				<td width="48%" align="center" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">								
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Listing #:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $listing_no .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Manufacturer:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $manufacturer_name .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Model:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $model .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Year:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $year .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Boat Type:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $type_name .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Address:</td>
							<td align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $addressfull .'</td>
						</tr>
						
						<tr>
							<td width="200" align="left" valign="top" style="text-align:left; color:#4c4c4c; padding: 6px 10px 6px 0; font-size: 16px; font-weight:bold; font-family: Arial, Geneva, sans-serif;">Price:</td>
							<td align="left" valign="top" style="text-align:left; color:#2bbed3; padding: 6px 0 6px 0; font-size: 16px; font-family: Arial, Geneva, sans-serif;">'. $price_display .'</td>
						</tr>						
					</table>
				</td>
				
				<td align="center" valign="top"><img src="'. $cm->site_url . '/images/sp.png" alt=""></td>
				
				<td width="48%" align="center" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="0">	
				 		'. $member_image_text .'
						
						<tr>
							<td align="center" valign="top" style="'. $defaultfontcss2 .'">'. $broker_address_full .'</td>
						</tr>
						
						'. $company_info_text .'
				 	</table>
				</td>
			 </tr>
		</table>
		';
		
		$returntxt .= '
		<p style="page-break-before:always;"></p>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Basic Information</td>
			</tr>
		</table>
		<table style="'. $tabletopspace2 .'" border="0" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				 <td align="left" valign="top" style="'. $tdrow .'" width="25%">Manufacturer:</td>
				 <td align="left" valign="top" style="'. $tdrow .'" width="25%">'. $manufacturer_name .'</td>
			   
				 <td align="left" valign="top" style="'. $tdrow .'" width="25%">Vessel Name:</td>
				 <td align="left" valign="top" style="'. $tdrow . $tdrowright .'" width="25%">'. $vessel_name .'</td>
			</tr>
			
			<tr>
				 <td align="left" valign="top" style="'. $tdrow .'">Model:</td>
				 <td align="left" valign="top" style="'. $tdrow .'">'. $model .'</td>
			   
				 <td align="left" valign="top" style="'. $tdrow .'">Boat Type:</td>
				 <td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $type_name .'</td>
			</tr>
			
			<tr>
				 <td align="left" valign="top" style="'. $tdrow .'">Year:</td>
				 <td align="left" valign="top" style="'. $tdrow .'">'. $year .'</td>
			   
				 <td align="left" valign="top" style="'. $tdrow .'">Hull Material:</td>
				 <td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $hull_material_name .'</td>
			</tr>
			
			<tr>
				 <td align="left" valign="top" style="'. $tdrow .'">Category:</td>
				 <td align="left" valign="top" style="'. $tdrow .'">'. $category_name .'</td>
			   
				 <td align="left" valign="top" style="'. $tdrow .'">Hull Type:</td>
				 <td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $hull_type_name .'</td>
			</tr>
			
			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Condition:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $condition_name .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Hull Color:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $hull_color .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Location:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $addressfull .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Designer:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $designer .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Available for sale in U.S. waters:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">'. $cm->set_yesyno_field($sale_usa) .'</td>

				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Flag of Registry:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom . $tdrowright .'">'. $flag_country_name .'</td>
			</tr>
		</table>
		';
		
		$returntxt .= '
		<table style="'. $tabletopspace .'" border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Dimensions &amp; Weight</td>
			</tr>
		</table>
		<table style="'. $tabletopspace2 .'" border="0" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Length:</td>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">'. $this->display_yacht_number_field($length, 1) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Draft - max:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'" width="25%">'. $this->display_yacht_number_field($draft, 1, 1) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">LOA:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($loa, 1, 1) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Bridge Clearance:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_number_field($bridge_clearance, 1, 1) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Beam:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">'. $this->display_yacht_number_field($beam, 1, 1) .'</td>

				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Dry Weight:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom . $tdrowright .'">'. $this->display_yacht_number_field($dry_weight, 2) .'</td>
			</tr>
		</table>
		';
		
		$returntxt .= '
		<table style="'. $tabletopspace .'" border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Engine</td>
			</tr>
		</table>
		<table style="'. $tabletopspace2 .'" border="0" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Make:</td>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">'. $engine_make_name .'</td>

				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Engine Type:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'" width="25%">'. $engine_type_name .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Model:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $engine_model .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Drive Type:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $drive_type_name .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Engine(s):</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($engine_no) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Fuel Type:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $fuel_type_name .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Hours:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($hours) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Horsepower:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Cruise Speed:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Max Speed:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_number_field($max_speed, 5) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Range:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">'. $this->display_yacht_number_field($en_range, 7) .'</td>

				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Joystick Control:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom . $tdrowright .'">'. $cm->set_yesyno_field($joystick_control) .'</td>
			</tr>
		</table>
		';
		
		$returntxt .= '
		<table style="'. $tabletopspace .'" border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Tank Capacities</td>
			</tr>
		</table>
		<table style="'. $tabletopspace2 .'" border="0" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Fuel Tank:</td>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">'. $this->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Holding Tank:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'" width="25%">'. $this->display_yacht_tank_cap($holding_tanks, $no_holding_tanks) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Fresh Water Tank:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">'. $this->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks) .'</td>

				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">&nbsp;</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom . $tdrowright .'">&nbsp;</td>
			</tr>
		</table>
		';
		
		$returntxt .= '
		<table style="'. $tabletopspace .'" border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td style="width:100%; '. $tdheadingonly .'" align="left">Accommodations</td>
			</tr>
		</table>
		<table style="'. $tabletopspace2 .'" border="0" width="100%" cellspacing="0" cellpadding="4">
			<tr>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Total Cabins:</td>
				<td align="left" valign="top" style="'. $tdrow .'" width="25%">'. $this->display_yacht_number_field($total_cabins) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'" width="25%">Crew Cabins:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'" width="25%">'. $this->display_yacht_number_field($crew_cabins) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Total Berths:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($total_berths) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Crew Berths:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_number_field($crew_berths) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Total Sleeps:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($total_sleeps) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Crew Sleeps:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_number_field($crew_sleeps) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow .'">Total Heads:</td>
				<td align="left" valign="top" style="'. $tdrow .'">'. $this->display_yacht_number_field($total_heads) .'</td>

				<td align="left" valign="top" style="'. $tdrow .'">Crew Heads:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowright .'">'. $this->display_yacht_number_field($crew_heads) .'</td>
			</tr>

			<tr>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">Captains Cabin:</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'" >'. $cm->set_yesyno_field($captains_cabin) .'</td>

				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom .'">&nbsp;</td>
				<td align="left" valign="top" style="'. $tdrow . $tdrowbottom . $tdrowright .'">&nbsp;</td>
			</tr>
		</table>
		';
		
		$returntxt .= '
		<p style="page-break-before:always;"></p>
		<div style="'. $defaultfontcss .'">
		<p style="'. $tdheadingonly .'">Overview</p>
		'. $overview .'
		</div>		
		';
		
		$returntxt .= '			
		<div style="padding: 0px;'. $defaultfontcss .'">
		<p style="'. $tdheadingonly .'">Descriptions</p>		
		'. $descriptions .'
		</div>		
		';
		
		$returntxt .= $photo_txt;
		
		if ($lat_val == 0 AND $lon_val == 0){
			//--
		}else{

			$returntxt .= '
			<p style="page-break-before:always;"></p>
			<table style="'. $tabletopspace .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="width:100%; '. $tdheadingonly .'" align="left">Location Map</td>
				</tr>
			</table>
			<p style="padding: 15px 0px 0px 0px;"><img style="width: 100%;" src="https://maps.googleapis.com/maps/api/staticmap?center='. urlencode($addressfull) .'&zoom=9&size=750x300&maptype=roadmap&sensor=false&&markers=size:mid%7Ccolor:green%7C'. urlencode($addressfull) .'&key='. $cm->googlemapkey .'"></p>		
			';
		}

		//echo $returntxt;
		//exit;
		
		
		//new code
		$returntxt = '
		<!DOCTYPE HTML>
		<html lang="en">
		<head>
		<meta name="viewport" content="width=device-width; initial-scale=1.0;" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Print Details</title>
		<style>
		body
		{
			width:100%;
			margin:0;
			padding:0;
			font-family: Arial
			font-size: 13px;
		}
		
		@page {
		 margin: 0px 10px;
		}
		
		.printbutton{text-align:center; margin: 5px 5px 10px 5px;}
		.printbutton a{background-color:#003868;color:#fff; font-family: Arial; font-size: 14px; border:none; text-decoration:none;text-transform:uppercase; padding:6px 18px;cursor: pointer; width:100%; max-width: 100px;}
		@media print {
			.printbutton {display: none;}
		}
		</style>
		<body>
		<div class="printbutton"><a href="javascript:print();">Print</a></div>
		<table border="0" style="width:90%; max-width: 900px;" align="center" cellspacing="0" cellpadding="0">
			<tr><td>'. $returntxt .'</td></tr>
		</table>
		</body>
		</html>
		';
		//end		
		
		$return_ar = array(
			"headertext" => $headertext,
			"pdffilename" => $pdffilename,
			"returntxt" => $returntxt
		);	
		return json_encode($return_ar);
				
	}
	
	public function yacht_broker_email_phone_button($param = array()){
		global $cm;
		
		//param
		$boat_id = round($param["boat_id"], 0);
		$location_id = round($param["location_id"], 0);
		$broker_id = round($param["broker_id"], 0);
		$is_mobile_display = round($param["is_mobile"], 0);
		//end
		
		if ($broker_id == 1){
			$brokername = $cm->sitename;
			$location_ad_ar = $this->get_location_address_array($location_id);		
			$phone = $location_ad_ar["phone"];
		}else{
			$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
			$fname = $broker_ar[0]["fname"];
			$lname = $broker_ar[0]["lname"];
			$phone = $broker_ar[0]["phone"];
			
			if ($phone == ""){
				$broker_ad_ar = $this->get_broker_address_array($broker_id);		
				$phone = $broker_ad_ar["phone"];
			}
			
			$brokername = $fname .' '. $lname;
		}
		
		if ($is_mobile_display == 1){
			$desktop_mobile_class = "brokeremailphonemobile";
		}else{
			$desktop_mobile_class = "brokeremailphonedesktop";
		}
			
		//google tracking
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
		
		$returntext = '
		<div class="'. $desktop_mobile_class .' clearfixmain">
			<ul>
				<li><a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $boat_id . '" class="contactbroker button boatbuttonemail" data-type="iframe">Email Broker</a></li>
				<li><a href="tel:'. $phone .'" class="tel button boatbuttonphone">Call Broker</a></li>
			</ul>
		</div>
		';
		
		return $returntext;
	}
	
	public function yacht_button_set1($param = array()){
		global $cm;
		
		//param
		$boat_id = round($param["boat_id"], 0);
		$location_id = round($param["location_id"], 0);
		$broker_id = round($param["broker_id"], 0);
		//end
		
		if ($broker_id == 1){
			$brokername = $cm->sitename;
			$location_ad_ar = $this->get_location_address_array($location_id);		
			$phone = $location_ad_ar["phone"];
		}else{
			$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
			$fname = $broker_ar[0]["fname"];
			$lname = $broker_ar[0]["lname"];
			$phone = $broker_ar[0]["phone"];
			
			if ($phone == ""){
				$broker_ad_ar = $this->get_broker_address_array($broker_id);		
				$phone = $broker_ad_ar["phone"];
			}
			
			$brokername = $fname .' '. $lname;
		}
			
		//google tracking
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
		
		$returntext = '
		<li><a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-lead-checkout") .'?id='. $broker_id . '&yid='. $boat_id . '&servicerequest=1" class="contactbroker button boatbuttonemail" data-type="iframe">Email Broker</a></li>
		<li><a href="tel:'. $phone .'" class="tel button boatbuttonphone">Call Broker</a></li>
		<li><a href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-lead-checkout") .'?yid='. $boat_id . '&servicerequest=2" class="contactbroker button boatbuttonfinanced" data-type="iframe">Get Financed</a></li>			
		';
		
		return $returntext;
	}
	public function yacht_button_set2($param = array()){
		global $cm;
		
		//param
		$boat_id = round($param["boat_id"], 0);
		//end

		$returntext = '
		<li><a href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-lead-checkout") .'?yid='. $boat_id . '&servicerequest=3" class="contactbroker button boatbuttonpricedown" data-type="iframe">Email Me When Price Drop</a></li>
		<li><a href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-lead-checkout") .'?yid='. $boat_id . '&servicerequest=4" class="contactbroker button boatbuttonreview" data-type="iframe">Send Me Reviews</a></li>			
		';
		
		return $returntext;
	}
	
	public function yacht_button_set3($param = array()){
		global $cm;
		
		//param
		$boat_id = round($param["boat_id"], 0);
		$location_id = round($param["location_id"], 0);
		$broker_id = round($param["broker_id"], 0);
		$template = round($param["template"], 0);
		//end
		
		if ($broker_id == 1){
			$brokername = $cm->sitename;
			$location_ad_ar = $this->get_location_address_array($location_id);		
			$phone = $location_ad_ar["phone"];
		}else{
			$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
			$fname = $broker_ar[0]["fname"];
			$lname = $broker_ar[0]["lname"];
			$phone = $broker_ar[0]["phone"];
			
			if ($phone == ""){
				$broker_ad_ar = $this->get_broker_address_array($broker_id);		
				$phone = $broker_ad_ar["phone"];
			}
			
			$brokername = $fname .' '. $lname;
		}
			
		//google tracking
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);		
		
		if ($template == 1){
			$returntext = '
			<div class="spacertop"><a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $boat_id . '" class="contactbroker button boatbuttonemail" data-type="iframe">Send Inquiry</a></div>
			<div class="spacertop clearfixmain"><a href="'. $cm->get_page_url(0, 'pop-watch-price') .'?boat_id='. $boat_id .'" title="Watch Price" data-type="iframe" class="commonpop button boatbuttonwatchprice">Watch Price</a></div>
			';
		}else{
			$returntext = '<li><a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $boat_id . '" class="contactbroker button boatbuttonemail" data-type="iframe">Send Inquiry</a></li>';
		}		
		
		return $returntext;
	}
	
	public function loggedin_broker_icon_permission($frompopup = 0){
		global $cm;
		$accessopt = 0;
		$loggedin_member_id = $this->loggedin_member_id();
		
		if ($loggedin_member_id > 0){
			$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id', $loggedin_member_id);		
			$cuser_type_id = $cuser_ar[0]["type_id"];
			
			if ($cuser_type_id == 6){
				$iffound = $cm->get_common_field_name('tbl_user_to_broker', 'count(*)', $loggedin_member_id, 'user_id');
				if ($iffound > 0){
					$accessopt = 2;
				}
			}else{
				$accessopt = 1;
			}
		}
				
		if ($frompopup == 1){
			if ($accessopt == 0){
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				$redpage = $cm->get_page_url(0, "popsorry");
				header('Location: '. $redpage);
				exit;
			}
		}else{
			return $accessopt;
		}
		
	}
	
	public function loggedin_broker_icon($param = array()){
		global $cm;
		$accessopt = $this->loggedin_broker_icon_permission();
		$email_phone = $this->yacht_broker_email_phone_button($param);
		
		$listing_no = round($param["listing_no"], 0);
		
		$returntext = '
		<div class="social lefticoncol clearfixmain">
			'. $email_phone.'
		';		
		
		if ($accessopt == 1){
			$returntext .= '
			<div class="spacertop2 clearfixmain">
			<ul>
				<li class="title">Broker Features: </li>
				<li><a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'pop-send-email-client/?lno='. $listing_no .'" title="Email To Client" class="emailclient" data-type="iframe"><img src="'. $cm->folder_for_seo .'images/emailclient.png" alt=""></a></li>
			</ul>
			</div>
			';
		}elseif ($accessopt == 2){
			$returntext .= '
			<div class="spacertop2 clearfixmain">
				<ul>
					<li class="title">Email To My Broker: </li>
					<li><a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'pop-send-email-my-broker/?lno='. $listing_no .'" title="Email To My Broker" class="emailclient" data-type="iframe"><img src="'. $cm->folder_for_seo .'images/emailmybroker.png" alt=""></a></li>
				</ul>
			</div>
			';
		}
		
		$returntext .= '</div>';
		return $returntext;
	}
	
	public function loggedin_consumer_icon_permission($frompopup = 0){
		global $cm;
		$accessopt = 0;
		$loggedin_member_id = $this->loggedin_member_id();
		if ($loggedin_member_id > 0){
			$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id', $loggedin_member_id);		
			$cuser_type_id = $cuser_ar[0]["type_id"];
			if ($cuser_type_id == 6){
				$iffound = $cm->get_common_field_name('tbl_user_to_broker', 'count(*)', $loggedin_member_id, 'user_id');
				if ($iffound > 0){ $accessopt = 1; }
			}
		}
				
		if ($frompopup == 1){
			if ($accessopt == 0){
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				$redpage = $cm->get_page_url(0, "popsorry");
				header('Location: '. $redpage);
				exit;
			}
		}else{
			return $accessopt;
		}
	}
	
	public function loggedin_consumer_icon($listing_no){
		global $cm;
		$returntext = '';
		$accessopt = $this->loggedin_consumer_icon_permission();
		if ($accessopt == 1){
			$returntext = '
			<div class="social lefticoncol clearfixmain">
				<ul>
				    <li class="title">Email To My Broker: </li>
					<li><a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'pop-send-email-my-broker/?lno='. $listing_no .'" title="Email To My Broker" class="emailclient" data-type="iframe"><img src="'. $cm->folder_for_seo .'images/emailmybroker.png" alt=""></a></li>
				</ul>	
			</div>
			';
		}		
		return $returntext;		
	}
	
	public function display_yacht_video($yacht_id){
		global $db, $cm;
		$returntext = '';
		$sql = "select id, name, video_type from tbl_yacht_video where yacht_id = '". $yacht_id ."' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '<h2 class="singlelinebottom">Video</h2><div class="customboattabcontent clearfixmain"><ul class="video">';
			foreach($result as $row){
				$videoid = $row['id'];
				$video_title = $row['name'];
				$video_type = $row['video_type'];
				$videocontent = $cm->play_video($videoid, 725, 442);
				$titledisplay = '';
				if ($video_title != ""){
					$titledisplay = '<h3>'. $video_title .'</h3>';
				}
				
				$extraclass = '';
				if ($video_type == 2){
					$extraclass = ' vnospace';
				}
				$returntext .= '<li>'. $titledisplay .'<div class="video-container'. $extraclass .'">' . $videocontent . '</div></li>';
			}
			$returntext .= '</ul></div>';
		}
		return $returntext;
	}
	
	public function display_yacht_video2($yacht_id){
		global $db, $cm;
		$returntext = '';
		$sql = "select id, name, video_type from tbl_yacht_video where yacht_id = '". $yacht_id ."' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '
			<div class="clearfixmain"><a href="javascript:void(0);" ctabid="8" class="customboattab">Video</a></div>
			<div id="ctab8" class="customboattabcontent com_none clearfixmain">
			<ul class="video">';
			foreach($result as $row){
				$videoid = $row['id'];
				$video_title = $row['name'];
				$video_type = $row['video_type'];
				$videocontent = $cm->play_video($videoid, 725, 442);
				$titledisplay = '';
				if ($video_title != ""){
					$titledisplay = '<h3>'. $video_title .'</h3>';
				}
				
				$extraclass = '';
				if ($video_type == 2){
					$extraclass = ' vnospace';
				}
				$returntext .= '<li>'. $titledisplay .'<div class="video-container'. $extraclass .'">' . $videocontent . '</div></li>';
			}
			$returntext .= '</ul></div>';
		}
		return $returntext;
	}
    //yacht section end
	
	//location office list
	public function my_location_list_sql(){
        global $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id', $loggedin_member_id);		
		$cuser_type_id = $cuser_ar[0]["type_id"];
		$com_id = $cuser_ar[0]["company_id"];		
		
        $onm = $_REQUEST["onm"];
        $postcode = $_REQUEST["postcode"];

        $query_sql = "select *";
        $query_form = " from tbl_location_office,";
        $query_where = " where";
		
		$query_where .= "  company_id = '". $com_id . "' and";

        if ($onm != ""){
            $query_where .= "  name like '". $cm->filtertext($onm). "%' and";
        }

        if ($postcode != ""){
            $query_where .= "  zip like '". $cm->filtertext($postcode). "%' and";
        }		
		
        
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
    }
	
	public function total_my_location_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function my_location_list($p){
		global $db, $cm;
        $returntext = '';
        $moreviewtext = '';
		
		$dcon = $cm->pagination_record_list;
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }

        $sorting_sql = "id";
        $limitsql = " LIMIT ". $page .", ". $dcon;
        $sql = $this->my_location_list_sql($log_user_id);

        $foundm = $this->total_my_location_found($sql);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
		
		if ($found > 0){
            if ($p == 1){
                $returntext .= '
                <div class="divrow thd">
                    <div class="officename">Office Name</div>
                    <div class="locationaddress">Location</div>
                    <div class="primary">Primary</div>
                    <div class="uoptions">Options</div>
                    <div class="clear"></div>
                </div>
            ';
            }

            foreach($result as $row){
                $id = $row["id"];
                $name = $row["name"];
                $address = $row["address"];
                $city = $row["city"];
                $state = $row["state"];
				$state_id = $row["state_id"];
				$country_id = $row["country_id"];
				$zip = $row["zip"];
				$phone = $row["phone"];
				$default_location = $row['default_location'];
				$default_location_d = $cm->set_yesyno_field($default_location);
				
				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
				$details_url = $cm->get_page_url($id, "locationofficesub");
				
                $returntext .= '
                <div class="divrow">
                    <div class="officename"><a href="'. $details_url .'">'. $name .'</a></div>
                    <div class="locationaddress">'. $addressfull .'</div>                    
                    <div class="primary">'. $default_location_d .'</div>
                    <div class="uoptions">
                    <a href="'. $details_url .'" title="Edit Office Location"><img src="'. $cm->folder_for_seo .'images/edit-icon.png" alt="Edit Office Location" /></a>
                    <a mbid="'. $id .'" href="javascript:void(0);" class="removeofficelocation" title="Remove Office Location"><img src="'. $cm->folder_for_seo .'images/del.png" alt="Remove Broker" /></a>
                    </div>
                    <div class="clear"></div>
                </div>
            ';
            }
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="morebroker button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
	}
	
	public function location_office_delete($log_id, $id){
        global $db, $cm;
		
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $log_id);		
		$cuser_type_id = $cuser_ar[0]["type_id"];
		$company_id = $cuser_ar[0]["company_id"];
		$location_id = $cuser_ar[0]["location_id"];
		
		if ($cuser_type_id == 1){
			//super admin
			$sql = "delete from tbl_location_office where id = '". $id ."'";
			$db->mysqlquery($sql);
		}
		
		if ($cuser_type_id == 2 OR $cuser_type_id == 3){
			//master admin and manager
			$sql = "delete from tbl_location_office where id = '". $id ."' and company_id = '". $company_id ."'";
			$db->mysqlquery($sql);
		}	
        
        $sval = 'y';
        $optiontext = 'success';
        $returnval[] = array(
            'retval' => $sval,
            'optiontext' => $optiontext
        );
        echo json_encode($returnval);
    }
	//end

    //announcement
    public function announcement_sql(){
        $query_sql = "select *";
        $query_form = " from tbl_announcement,";
        $query_where = " where";

        $query_where .= " status_id = 1 and";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
    }

    public function total_announcement_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }

    public function announcement_list($p){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        if ($p > 0){
            $dcon = $cm->pagination_record_list;
            $page = ($p - 1) * $dcon;
            if ($page <= 0){ $page = 0; }
            $limitsql = " LIMIT ". $page .", ". $dcon;
        }else{
            $limitsql = " LIMIT 0, 3";
        }

        $sorting_sql = "id desc";
        $sql = $this->announcement_sql();
        $foundm = $this->total_announcement_found($sql);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
        if ($found > 0){
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = htmlspecialchars($val);
                }
                $reg_date_d = $cm->display_date($reg_date, 'y', 9);
                $details_url = $cm->get_page_url($slug, "announcement");
                $returntext .= '
                <div class="divrow2">
                    <h3><a href="'. $details_url .'">'. $name .'</a></h3>
                    <p><strong>'. $reg_date_d . '</strong><br />' . $sdes .'...<br /><a href="'. $details_url .'">more</a></p>
                    <div class="clear"></div>
                </div>
                ';
            }

            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="moreannouncement button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
    }

    public function check_announcement_with_return($checkval, $checkopt = 0){
        global $db, $cm, $frontend;
        $sql = "select * from tbl_announcement where slug = '". $cm->filtertext($checkval) ."' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $cm->sorryredirect(28);
        }
        return $result;
    }
    //announcement end

    //broker search
    public function broker_search_sql($brokeronly = 0){
        global $db, $cm;        
		$company_id = round($_REQUEST["company_id"], 0);
		$comname = $_REQUEST["comname"];
        $brokername = $_REQUEST["brokername"];
        $cnm = $_REQUEST["cnm"];
		$locnm = $_REQUEST["locnm"];
        //$baddress = $_REQUEST["baddress"];
        $bcity = $_REQUEST["bcity"];
        $bcountry_id = round($_REQUEST["bcountry_id"], 0);
        $bstate_id = round($_REQUEST["bstate_id"], 0);
        $bstate = $_REQUEST["bstate"];
        $bzip = $_REQUEST["bzip"];
        $unm = $_REQUEST["unm"];
		//$brokeronly = round($_REQUEST["brokeronly"], 0);
		
		if ($brokername != "" OR $unm != "" OR $cnm != "" OR $brokeronly > 0){
			//broker list
			$displayrec = 1;
			$query_sql = "select distinct a.*";
			$query_form = " from tbl_user as a";
			$query_where = " where";
			
			$query_form .= ", tbl_location_office as b";
			$query_where .= " a.company_id = b.company_id and ( a.location_id = b.id OR a.location_id = 0 ) and";
			
			//$query_form .= " LEFT JOIN tbl_location_office as b ON a.company_id = b.company_id";
			
			if ($unm != ""){
				$sqltxt = "select id as ttl from tbl_user where uid = '". $cm->filtertext($unm) ."'";
				$userd = $db->total_record_count($sqltxt);
				//$query_where .= " parent_id = '". $userd ."' and";
				$displayrec = 1;
			}
			
			if ($brokername != ""){
				$query_where .= "  concat(a.fname, ' ', a.lname) like '%". $cm->filtertext($brokername). "%' and";
				$displayrec = 1;
			}
			
			if ($cnm != ""){
				$com_id = $cm->get_common_field_name('tbl_company', 'id', $cnm, 'slug');
				$query_where .= "  a.company_id = '". $com_id. "' and";
				$displayrec = 1;
			}
			
			if ($comname != ""){
				$query_where .= "  a.cname like '". $cm->filtertext($comname). "%' and";				
			}
			
			if ($locnm != ""){
				$query_where .= "  b.slug like '". $cm->filtertext($locnm). "%' and";				
			}
						
			if ($bcity != ""){
				$query_where .= "  b.city like '". $cm->filtertext($bcity). "%' and";
			}
			
			if ($bstate != ""){				
				$query_where .= "  b.state like '". $cm->filtertext($bstate). "%' and";
			}
	
			if ($bstate_id > 0){
				$query_where .= " b.state_id = '". $bstate_id ."' and";
			}
			
			if ($bcountry_id > 0){
				$query_where .= " b.country_id = '". $bcountry_id ."' and";
			}
			
			if ($bzip != ""){
				$query_where .= "  b.zip like '". $cm->filtertext($bzip). "%' and";
			}
			
			$query_where .= " a.status_id = 2 and a.front_display = 1 and a.type_id IN (2,3,4,5) and";

		}else{
			//company list
			$displayrec = 0;
			
			$query_sql = "select a.*, b.id as location_id,";
			$query_form = " from tbl_company as a ";
			$query_where = " where";
			
			$query_form .= " LEFT JOIN tbl_location_office as b ON a.id = b.company_id";
			
			if ($comname != ""){
				$query_where .= "  a.cname like '". $cm->filtertext($comname). "%' and";				
			}
						
			if ($bcity != ""){
				$query_where .= "  b.city like '". $cm->filtertext($bcity). "%' and";
			}
			
			if ($bstate != ""){				
				$query_where .= "  b.state like '". $cm->filtertext($bstate). "%' and";
			}
	
			if ($bstate_id > 0){
				$query_where .= " b.state_id = '". $bstate_id ."' and";
			}
			
			if ($bcountry_id > 0){
				$query_where .= " b.country_id = '". $bcountry_id ."' and";
			}
			
			if ($bzip != ""){
				$query_where .= "  b.zip like '". $cm->filtertext($bzip). "%' and";
			}
			
			$query_where .= " a.status_id = 1 and";
		}

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
	    $returnvalu = array(
            'sql' => $sql,
            'displayrec' => $displayrec
        );

        return $returnvalu;
    }

    public function total_broker_search_found($sql, $displayrec = 0){
        global $db;
		if ($displayrec == 1){
			$sqlm = str_replace("select distinct a.*","select count(distinct a.id) as ttl",$sql);
		}else{
			$sqlm = str_replace("select a.*","select count(a.id) as ttl",$sql);
		}        
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function display_broker_company_map_view($result){
        global $db, $cm;
        $iounter = 0;
        $mapdataar = array();
        foreach($result as $row){
            foreach($row AS $key => $val){
                ${$key} = htmlspecialchars($val);
            }
			
			$com_ad_ar = $this->get_company_address_array($id, $location_id);		
			$address = $com_ad_ar["address"];
			$city = $com_ad_ar["city"];
			$state = $com_ad_ar["state"];
			$state_id = $com_ad_ar["state_id"];
			$country_id = $com_ad_ar["country_id"];
			$zip = $com_ad_ar["zip"];			
            $addressfull = $this->com_address_format($address, $city, $state, $state_id, $country_id, $zip);
			
			$lat_val = $com_ad_ar["lat_val"];
			$lon_val = $com_ad_ar["lon_val"];
			
			if ($logo_imgpath == ""){ $logo_imgpath = 'no.png'; }			
            $details_url = $cm->get_page_url($id, 'comprofile');

            $contentval = '
            <div class="listing-map-label listing-status-for-sale">
                <img class="listing-thumbnail wp-post-image" src="'. $cm->folder_for_seo . 'userphoto/' . $logo_imgpath .'">
                <a href="'. $details_url .'">
                    <img class="listing-thumbnail-big wp-post-image" src="'. $cm->folder_for_seo . 'userphoto/' . $logo_imgpath .'">
                </a>
                <div class="map-label-content">
                    <span class="listing-address"><a href="'. $details_url .'">'. $addressfull .'</a></span>
                    <span class="listing-price">123</span>
                </div>
            </div>';
            $mapdataar[] = array(
                'contentval' => $contentval,
                'lat' => $lat_val,
                'lon' => $lon_val
            );
            $iounter++;
        }
        return $mapdataar;
    }

    public function display_broker_company($row){
        global $db, $cm;
        $returntext = '';

        foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
        }
		
		$website_url_d = '';
		if ($website_url != ""){
			$website_url_d = '<a href="'. $website_url .'" target="_blank">'. $website_url .'</a>';	
		}

        if ($logo_imgpath == ""){
            $logo_imgpath = 'no.png';
        }
		
		$com_ad_ar = $this->get_company_address_array($id, $location_id);		
		$address = $com_ad_ar["address"];
		$city = $com_ad_ar["city"];
		$state = $com_ad_ar["state"];
		$state_id = $com_ad_ar["state_id"];
		$country_id = $com_ad_ar["country_id"];
		$zip = $com_ad_ar["zip"];
		$phone = $com_ad_ar["phone"];		
		
		$addressfull = '';
		$maplink = '';		
		
		//$location_id = round($this->get_company_default_location($id), 0);
		if ($location_id > 0){
			$addressfull = '<div>' . $this->com_address_format('', $city, $state, $state_id, $country_id) . '</div>';
			$maplink = '<div class="map"><a href="javascript:void(0);" data-src="' .$cm->folder_for_seo .'company-broker-map?id='. $id .'&op=1" class="mappopup" data-type="iframe">View in Map</a></div>';
		}
		
		$total_y = $this->get_total_yacht_by_company($id);
				
        $logo_imgpath = '<img src="'. $cm->folder_for_seo .'userphoto/'. $logo_imgpath .'" alt="">';
        $profile_url = $cm->get_page_url($id, 'comprofile');
        $returntext .= '
                <div class="divrow4">
                    <div class="uimg"><a href="'. $profile_url .'">'. $logo_imgpath .'</a></div>
                    <div class="txtcon">
                        <h3><a href="'. $profile_url .'">'. $cname .'</a></h3>
                        '. $addressfull .'
						'. $website_url_d .'
						'. $maplink .'
                    </div>
                    <div class="options">
                        <p><a href="'. $profile_url .'" class="button contact">Contact</a></p>
                        <p>'. $total_y .' Listing(s)</p>
                    </div>
                    <div class="clear"></div>
                </div>
            ';
        return $returntext;
    }
	
	public function display_broker_ind_map_view($result){
        global $db, $cm;
        $iounter = 0;
        $mapdataar = array();
        foreach($result as $row){
            foreach($row AS $key => $val){
                ${$key} = htmlspecialchars($val);
            }
			
			$broker_ad_ar = $this->get_broker_address_array($id);		
			$address = $broker_ad_ar["address"];
			$city = $broker_ad_ar["city"];
			$state = $broker_ad_ar["state"];
			$state_id = $broker_ad_ar["state_id"];
			$country_id = $broker_ad_ar["country_id"];
			$zip = $broker_ad_ar["zip"];			
            $addressfull = $this->com_address_format($address, $city, $state, $state_id, $country_id, $zip);
			
			$lat_val = $broker_ad_ar["lat_val"];
			$lon_val = $broker_ad_ar["lon_val"];
			$brokername = $fname .' '. $lname;
			$member_image = $this->get_user_image($id);		
            $details_url = $cm->get_page_url($id, 'user');

            $contentval = '
            <div class="listing-map-label listing-status-for-sale">
                <img alt="'. $brokername .'" class="listing-thumbnail" src="'. $cm->folder_for_seo . 'userphoto/' . $member_image .'">
                <a href="'. $details_url .'">
                    <img alt="'. $brokername .'" class="listing-thumbnail-big" src="'. $cm->folder_for_seo . 'userphoto/' . $logo_imgpath .'">
                </a>
                <div class="map-label-content">
                    <span class="listing-address"><a href="'. $details_url .'">'. $addressfull .'</a></span>
                </div>
            </div>';
            $mapdataar[] = array(
                'contentval' => $contentval,
                'lat' => $lat_val,
                'lon' => $lon_val
            );
            $iounter++;
        }
        return $mapdataar;
    }

    public function display_broker_ind($row, $brokeronly = 0, $default_view = 2, $isdashboard = 0){
        global $db, $cm;
        $returntext = '';

        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
          
		$broker_ad_ar = $this->get_broker_address_array($id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"]; 
					    
        $addressfull = $this->com_address_format($address, $city, $state, $state_id, $country_id);
		$maplink = '<div class="map"><a href="javascript:void(0);" data-src="' .$cm->folder_for_seo .'company-broker-map/?id='. $id .'&op=2" class="mappopup" data-type="iframe">View in Map</a></div>';
       
        $total_y = $this->get_total_yacht_by_broker(array("broker_id" => $id, "status_id" => 1));
		
		if ($isdashboard == 1){
			$profile_url = $cm->get_page_url($id, 'userinsidedb');
		}else{
			$profile_url = $cm->get_page_url($id, 'user');
		}
		
		//Google event tracking
		$brokername = $fname .' '. $lname;
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
		$contactbuttontext = '';
		$totalboattext = '';
		$aboutmetext = '';
		
		//image
		$member_image = $this->get_user_image($id);
        $target_path_main = 'userphoto/big/';
        $imgpath_d = '<img alt="'. $brokername .'" src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';
		//end

        if ($default_view == 1){
			//grid
			if ($title == ""){ $title = "&nbsp;"; }
			$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
			
			$mobiletext = '';
			if ($phone != ""){ 
				$mobiletext = '<div class="ourteamphone opensans"><a class="tel" href="tel:'. $phone .'"><i class="fa fa-mobile broker-icons" aria-hidden="true"></i> '. $phone .'</a></div>'; 
			}
			
			if ($support_crew == 0){
				$contactbuttontext = '<a '. $gaeventtracking .' href="javascript:void(0);" data-src="'. $cm->folder_for_seo.'contact-broker/?id='. $id .'" class="contactbroker button contact" data-type="iframe">Contact</a>';
			}
			
			if ($about_me != ""){
				$about_me = $cm->fc_word_count($about_me, 50);
				$aboutmetext = '<div class="ourteam_aboutme">'. $about_me .'</div>';
			}
			
			$office_phone_ext_text = '';
			if ($office_phone_ext != ""){
				$office_phone_ext_text = ' Ex: ' . $office_phone_ext;
			}
			
			$returntext .= '
			<li>
				<div class="thumb"><a href="'. $profile_url .'">'. $imgpath_d .'</a></div>
				<h4><a href="'. $profile_url .'">'. $brokername .'</a></h4>
				<h5>'. $title . '</h5>
				<div class="locationaddress"><i class="fa fa-map-marker broker-icons" aria-hidden="true"></i> '. $addressfull .'</div>				
				'. $mobiletext .'
				<div class="ourteamphone"><a class="tel" href="tel:'. $officephone .'"><i class="fa fa-phone broker-icons" aria-hidden="true"></i> '. $officephone . $office_phone_ext_text .'</a></div>				
				<div class="teamoptions">
					<a href="'. $profile_url .'" class="button contact">Profile</a>
					'. $contactbuttontext .'
				</div>
			</li>
			';			
		}else{
			//list
			$titletextdisplay = '';
			if ($title != ""){ $titletextdisplay = ' - <span>'. $title . '</span>'; }
			if ($support_crew == 0){
				$totalboattext = '<p>'. $total_y .' Listing(s)</p>';
				$contactbuttontext = '<p><a '. $gaeventtracking .' href="'. $cm->folder_for_seo.'contact-broker/?id='. $id .'" class="contactbroker button contact" data-type="iframe">Contact</a></p>';
			}
			
			$addressfull = $this->com_address_format($address, $city, $state, $state_id, $country_id);
			$returntext .= '<li>
					<div class="divrow3">
						<div class="uimg">'. $imgpath_d .'</div>
						<div class="txtcon">
							<h3>'. $brokername . $titletextdisplay .'</h3>
							<div>'. $addressfull .'</div>';
			if ($phone != ""){ $returntext .= '<div class="mobile"><a class="tel" href="tel:'. $phone .'"><span>'. $phone .'</span></a></div>'; }		
			$returntext .= '<div class="phone"><a class="tel" href="tel:'. $officephone .'"><span>'. $officephone .'</span></a></div>
							'. $maplink .'
							'. $this->combined_associations_certifications($id, 2) .'					
						</div>
						<div class="options">
							<p><a href="'. $profile_url .'" class="button contact">Profile</a></p>
							'. $contactbuttontext;
			if ($brokeronly == 1){				
							$returntext .= '<p><a href="'. $cm->folder_for_seo.'select-broker-sub.php?id='. $id .'" class="button selectbroker">Select Broker</a></p>';
			}
							$returntext .= $totalboattext . $this->get_user_social_media($id) .'
						</div>
						<div class="clear"></div>
					</div>
					</li>
				';
		}
        return $returntext;
    }

    public function broker_search_list($p, $argu = array()){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';
			
		$displayoption = round($argu["displayoption"], 0);
		$brokeronly = round($argu["brokeronly"], 0);
		$isdashboard = round($argu["isdashboard"], 0);
        $dcon = $cm->pagination_record_list;
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }        

        $returnvalu_ar = $this->broker_search_sql($brokeronly);
        $sql = $returnvalu_ar['sql'];		
        $displayrec = $returnvalu_ar['displayrec'];		
        $foundm = $this->total_broker_search_found($sql, $displayrec);
		
		$limitsql = " LIMIT ". $page .", ". $dcon;
		if ($displayrec == 0){
			$sorting_sql = "a.cname";
		}else{
			//$sorting_sql = "type_id, id desc";
			$sorting_sql = "rank";
		}  
		
		if ($displayoption == 3){
             //map view
            $returntext .= '<div id="map"></div>';
        }else{
           //list-grid view - pagination required
            $sql = $sql." order by ". $sorting_sql . $limitsql;
        }      
        	
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		$remaining = $foundm - ($p * $dcon);
        if ($found > 0){

            if ($displayrec == 0){
                //company listing
				if ($displayoption == 2){
					//map view
					$mapdataar = $this->display_broker_company_map_view($result);
				}else{
					//list view
					foreach($result as $row){
						$returntext .= $this->display_broker_company($row, $displayoption);
					}
				}
                
            }else{
                //only individual broker
				if ($displayoption == 2){
					//map view
					$mapdataar = $this->display_broker_ind_map_view($result);
				}else{
					//list view
					$returntext .= '					
					<ul class="ourteam-list'. $defalut_view_class .'">
					';
					foreach($result as $row){
						$returntext .= $this->display_broker_ind($row, $brokeronly, $isdashboard);
					}
					
					$returntxt .= '
					</ul>
					<div class="clearfix"></div>
					';
				}
            }
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="morebroker button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }else{
			$mapdataar = array();
		}

        $returnval[] = array(
            'doc' => $returntext,
            'totalrec' => $foundm,
            'moreviewtext' => $moreviewtext,
			'displayoption' => $displayoption,
			'mapdoc' => $mapdataar
        );
        return json_encode($returnval);
    }
    //broker search end
	
	//consumer user: select broker
	public function assign_user_to_broker(){
		global $db, $cm, $frontend, $sdeml;
		$loggedin_member_id = $this->loggedin_member_id();
		$selectbrokerid= round($_REQUEST["id"], 0);
		$iffound = $cm->get_common_field_name('tbl_user_to_broker', 'count(*)', $loggedin_member_id, 'user_id'); 
		if ($iffound > 0 OR $selectbrokerid == 0){
			//already added one broker
			$_SESSION["ob"] = $frontend->display_message(34);
			header('Location: '. $cm->site_url .'/sorry/');
			exit;
		}else{
			//add now
			$sql = "insert into tbl_user_to_broker (user_id, broker_id) values ('". $loggedin_member_id ."', '". $selectbrokerid ."')";
			$db->mysqlquery($sql);
			
			$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $selectbrokerid);
			$b_email = $user_det[0]['email'];
			$fullname = $user_det[0]['fullname'];
			
			$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname', $loggedin_member_id);			
			$consumer = $user_det[0]['fullname'];
			
			//send email
			$send_ml_id = 5;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$companyname = $cm->sitename;
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $msg);
			$msg = str_replace("#consumer#", $cm->filtertextdisplay($consumer), $msg);			
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($b_email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$_SESSION["thnk"] = $frontend->display_message(33, $fullname);
			header('Location: '. $cm->site_url .'/thankyou/');
			exit;
		}
	}
	
	public function my_broker_preferences(){
		global $db, $cm, $sdeml;
		$loggedin_member_id = $this->loggedin_member_id();
		$savesearch = round($_REQUEST["savesearch"], 0);
		$favlist = round($_REQUEST["favlist"], 0);
		
		$sql = "update tbl_user_to_broker set save_search = '". $savesearch ."', fav_list = '". $favlist ."' where user_id = '". $loggedin_member_id ."'";
		$db->mysqlquery($sql);
		
		//send email
		$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $selectbrokerid);
		$b_email = $user_det[0]['email'];
		$fullname = $user_det[0]['fullname'];
		
		$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname', $loggedin_member_id);			
		$consumer = $user_det[0]['fullname'];
		
		$send_ml_id = 6;
		$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		$companyname = $cm->sitename;
		
		$msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $msg);
		$msg = str_replace("#consumer#", $cm->filtertextdisplay($consumer), $msg);			
		$msg = str_replace("#companyname#", $companyname, $msg);
		$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
		
		$mail_fm = $cm->admin_email();
		$mail_to = $cm->filtertextdisplay($b_email);
		$mail_cc = "";
		$mail_reply = "";
		$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		
		$optiontext = 'Preferences modified';
		$returnval[] = array(            
            'optiontext' => $optiontext
        );
		return json_encode($returnval);
	}
	
	public function my_broker_remove(){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		$sql = "delete from tbl_user_to_broker where user_id = '". $loggedin_member_id ."'";
		$db->mysqlquery($sql);
		$optiontext = $this->display_consumer_user_brokerinfo_short($loggedin_member_id);		
		$returnval[] = array(            
            'optiontext' => $optiontext
        );
		return json_encode($returnval);
	}
	
	function display_my_client_list($row){
		 global $db, $cm;
		 $returntext = '';
		 foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
         }
		 $member_image = $this->get_user_image($id);
		 $target_path_main = 'userphoto/big/';
		 $imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';		 
		 
		 if ($logoimage == ""){ $logoimage = "no.jpg"; }
		 $details_url = $cm->get_page_url($id, 'user');
		 
		 if ($save_search == 1){
			 $saveurl = $cm->get_page_url($id, 'savesearchclient');
			 $save_search_display = '<a class="active" href="'. $saveurl .'" target="_blank" title="Open Save Search List"><img src="'. $cm->folder_for_seo .'images/search.png" alt="" /></a>';
		 }else{
			 $save_search_display = '<a href="javascript:void(0);" title="Save Search List Not Shared"><img src="'. $cm->folder_for_seo .'images/search.png" alt="" /></a>';
		 }
		 
		 if ($fav_list == 1){
			 $favurl = $cm->get_page_url($id, 'clientfavorites');
			 $fav_list_display = '<a class="active" href="'. $favurl .'" target="_blank" title="Open Favorite List"><img src="'. $cm->folder_for_seo .'images/heart-icon.png" alt="" /></a>';
		 }else{
			 $fav_list_display = '<a href="javascript:void(0);" title="Favorite List Not Shared"><img src="'. $cm->folder_for_seo .'images/heart-icon.png" alt="" /></a>';
		 }
		
		$returntext .= '
		<li>			
			<div class="thumb"><a href="'. $details_url .'">'. $imgpath_d .'</a></div>
			<div class="info"><a href="'. $details_url .'">'. $fname .' '. $lname .'</a></div>
			<div class="options">
				<span>'. $save_search_display .'</span>
				<span>'. $fav_list_display .'</span>
			</div>
		</li>
		';		
		return $returntext;
	 }
	
	public function my_client_sql(){
        global $db, $cm;        
		$loggedin_member_id = $this->loggedin_member_id();		
					
		$query_sql = "select distinct a.*, b.*,";
		$query_form = " from tbl_user_to_broker as a,";
		$query_where = " where";
		
		$query_form .= " tbl_user as b,";
		$query_where .= " a.user_id = b.id and";
		$query_where .= " a.broker_id = '". $loggedin_member_id ."' and";		
		$query_where .= " b.status_id = 2 and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $returnvalu = array(
            'sql' => $sql
        );

        return $returnvalu;
    }
	
	public function total_my_client_found($sql){
        global $db;
        $sqlm = str_replace("select distinct a.*","select count(distinct a.user_id) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function my_client_list($p, $ajaxpagination = 0){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        //$dcon = $cm->pagination_record_list;
		$dcon = 20;
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }        

        $returnvalu_ar = $this->my_client_sql();
        $sql = $returnvalu_ar['sql'];
        $foundm = $this->total_my_client_found($sql);
		
		$limitsql = " LIMIT ". $page .", ". $dcon;
		$sorting_sql = " b.id";        

        $sql = $sql." order by ". $sorting_sql . $limitsql;		
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		$remaining = $foundm - ($p * $dcon);
        if ($found > 0){

            if ($ajaxpagination == 0){ $returntext .= '<ul class="gal-list">'; }
			foreach($result as $row){
				$returntext .= $this->display_my_client_list($row);
			}
			if ($ajaxpagination == 0){ $returntext .= '</ul><div class="clear"></div>'; }
			
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="morebrokerlist button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'totalrec' => $foundm,
            'moreviewtext' => $moreviewtext,
			'search_filter' => $returnvalu_ar['search_filter']
        );
        return json_encode($returnval);
    }
	
	public function can_view_saved_search($unm){
		global $db, $cm, $frontend;
		$loggedin_member_id = $this->loggedin_member_id();
		$client_id = $cm->get_common_field_name('tbl_user', 'id', $unm, 'uid');
		$sqltext = "select count(*) as ttl from tbl_user_to_broker where user_id = '". $client_id ."' and broker_id = '". $loggedin_member_id ."' and save_search = 1";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound == 0){            
            $_SESSION["ob"] = $frontend->display_message(25);
			$redpage = 'sorry/';            
            header('Location: '. $cm->folder_for_seo . $redpage);
            exit;
        }
	}
	
	public function can_view_fav_list($unm){
		global $db, $cm, $frontend;
		$loggedin_member_id = $this->loggedin_member_id();
		$client_id = $cm->get_common_field_name('tbl_user', 'id', $unm, 'uid');
		$sqltext = "select count(*) as ttl from tbl_user_to_broker where user_id = '". $client_id ."' and broker_id = '". $loggedin_member_id ."' and fav_list = 1";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound == 0){            
            $_SESSION["ob"] = $frontend->display_message(25);
			$redpage = 'sorry/';            
            header('Location: '. $cm->folder_for_seo . $redpage);
            exit;
        }
	}
	//consumer user: select broker end
	
	//resource start
	public function delete_resource($type_id){
        global $db;
        $sql = "select id, logoimage from tbl_resource where resource_type_id = '". $type_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $resource_id = $row['resource_id'];
			$fimg1 = $row['logoimage'];
            $this->delete_resource_image($fimg1);
			
			$sql = "delete from tbl_resource_manufacturer where resource_id = '". $resource_id ."'";
    		$db->mysqlquery($sql);
			
			$sql = "delete from tbl_resource_state where resource_id = '". $resource_id ."'";
    		$db->mysqlquery($sql);
        }

        $sql = "delete from tbl_resource where resource_type_id = '". $type_id ."'";
    	$db->mysqlquery($sql);        
    }

    public function delete_resource_image($fimg1){
        global $fle;
        if ($fimg1 != ""){
            $fle->filedelete("../resourceimage/" . $fimg1);
        }
    }	
	
	public function get_resource_type_combo($resource_type_id, $frontfrom = 0){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_resource_type where status_id = 1";        
        $vsql .= " order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			$bck = '';
			if ($resource_type_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function resource_manufacturer_assign($iiid){
	  global $db, $cm;
	  $total_manufacturer = $this->get_total_manufacturer();
      
      $sql = "delete from tbl_resource_manufacturer where resource_id = '". $iiid ."'";
      $db->mysqlquery($sql);
      
      $key_name = $_POST["key_name"];      
      $key_name = ltrim($key_name, ',');
      $key_name = rtrim($key_name, ',');
      
      $key_name_added = $_POST["key_name_added"];      
      $key_name_added = ltrim($key_name_added, ',');
      $key_name_added = rtrim($key_name_added, ',');
      
      $key_name_ar = explode(',', $key_name);
      $key_name_added_ar = explode(',', $key_name_added); 
           
      $total_key_name_ar = array_merge($key_name_ar, $key_name_added_ar);
      $total_key_name_ar = array_unique($total_key_name_ar);
      
	  $ak = 0;      
      foreach($total_key_name_ar as $total_key_name_vl){          
          if ($total_key_name_vl != ""){
			  /*
              $if_found = $db->total_record_count("select count(*) as ttl from tbl_resource_manufacturer where name = '". $cm->rep($total_key_name_vl) ."'");   
              if ($if_found == 0){
                 $addedbyid = $cm->get_poster_id(); 
                 $rank = $db->total_record_count("select max(rank) as ttl from tbl_portal_event_tag") + 1;
                 $sql = "insert into tbl_portal_event_tag (name, added_by) values ('". $cm->rep($total_tag_name_vl) ."', '". $addedbyid ."')";
                 $tag_id = $db->mysqlquery_ret($sql); 
                 
                 $sql = "update tbl_portal_event_tag set rank = '".$rank."'
                 , status = 'y'             
                 , m1 = '". $cm->rep($total_tag_name_vl) ."' where id = '".$category_id."'";
                 $db->mysqlquery($sql);                 
              }else{
                  $tag_id = $db->total_record_count("select id as ttl from tbl_portal_event_tag where name = '". $cm->rep($total_tag_name_vl) ."'");
              }
              */
			  
			  $manufacturer_id = $db->total_record_count("select id as ttl from tbl_manufacturer where name = '". $cm->filtertext($total_key_name_vl) ."'");
			  if ($manufacturer_id > 0){
				  $sql = "insert into tbl_resource_manufacturer (resource_id, manufacturer_id) values ('". $iiid ."', '". $manufacturer_id ."')";
              	  $db->mysqlquery($sql);
				  $ak = 1;
			  }
          }
      }	
	  
	  return $ak; 
    }
	
	public function display_resource_manufacturer($id, $linktopage = ''){
	  global $cm;	  
	  $tagname = $cm->display_multiplevl($id, "tbl_resource_manufacturer", "manufacturer_id", "resource_id", "tbl_manufacturer", $linktopage);
	  return $tagname;
    }
	
	public function resource_state_assign($resource_id){
        global $db, $cm;
		$country_id = $cm->get_common_field_name('tbl_resource', 'country_id', $resource_id);
		$sql = "delete from tbl_resource_state where resource_id = '". $resource_id ."'";
        $db->mysqlquery($sql);
		$ak = 0;
		
		if ($country_id == 1){
			$total_rec = $this->get_total_rec_table('tbl_state');
			for ($i = 0; $i < $total_rec; $i++){
				$state_id = $_POST["res_state_id".$i];
				if ($state_id > 0){
					$sql = "insert into tbl_resource_state (resource_id, state_id) values ('". $resource_id ."', '". $state_id ."')";
					$db->mysqlquery($sql);
					$ak = 1;
				}
			}
		}else{
			$res_state = $_POST["res_state"];
			$sql = "insert into tbl_resource_state (resource_id, state) values ('". $resource_id ."', '". $cm->filtertext($res_state) ."')";
			$db->mysqlquery($sql);
			$ak = 1;
		}
		return $ak;
     }
	
	 function display_resource_state_allocation($resource_id, $state_s2, $state_s1){
		 global $db, $cm;
		 $res_state = $cm->get_common_field_name('tbl_resource_state', 'state', $resource_id, 'resource_id');
	 ?>
     	<div id="sps2a" class="<?php echo $state_s2; ?>">
            <div class="choose_category choose_categorybox">
                <?php $cm->display_multi_checkbox($resource_id, 'state'); ?>
            </div>                            
        </div>
       <div id="sps1a" class="<?php echo $state_s1; ?> ">
            <input type="text" id="res_state" name="res_state" class="inputbox inputbox_size4" value="<?php echo $res_state; ?>" />
        </div>     
     <?php		 
	 }
	 
	 function resource_form_page_url($resource_id, $yacht_id){
		 global $cm;
		 $lno = round($_REQUEST['lno'], 0);
		 if ($lno > 0){
			 $listing_no = $lno;
		 }else{
			 $listing_no = $this->get_yacht_no($yacht_id);
		 }
		 
		 $form_page_url = $cm->folder_for_seo . 'resourceform/' . $resource_id . '/' .$listing_no . '/';
		 return $form_page_url;
	 }
	 
	 function display_resource_list($row, $clicklinkoption = 0, $yacht_id = 0){
		 global $db, $cm;
		 $returntext = '';
		 
		 foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
         }
		 
		 $addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, '', 1);
		 if ($logoimage == ""){ $logoimage = "no.jpg"; }
		 
		 if ($clicklinkoption == 1){
			 //$details_url = $cm->get_page_url($id, "resourceslistings");
			 $details_url = $this->resource_form_page_url($id, $yacht_id);
		 }else{
			 $details_url = $cm->get_page_url($id, "resourceprofile");
		 }
		 
		$returntext .= '
		<li>			
			<div class="thumb"><a href="'. $details_url .'"><img src="'. $cm->folder_for_seo .'resourceimage/'. $logoimage .'" alt=""></a></div>
			<div class="info"><a href="'. $details_url .'">'. $company_name .'</a><br />'. $addressfull .'</div>
		</li>
		';
		
		return $returntext;
	 }
	 
	 public function display_yacht_resource_sql($resourcetypeid, $res_row){
		 global $db, $cm;
		 $yacht_id = $res_row["id"];
		 $category_id = $res_row["category_id"];
		 $manufacturer_id = $res_row["manufacturer_id"];
		 $condition_id = $res_row["condition_id"];
		 $year = $res_row["year"];
		 $type_id = $res_row["type_id"];
		 $price = $res_row["price"];
		 $state_id = $res_row["state_id"];
		 $state = $res_row["state"];
		 $country_id = $res_row["country_id"];		 		 
		 
		 $query_sql = "select distinct a.*,";
		 $query_form = " from  tbl_resource as a";
		 $query_where = " where";
		 		 
		 $query_where .= " a.assigned = 1 and";
		 $query_where .= " a.resource_type_id = '". $resourcetypeid ."' and";
		 //year
		 $query_where .= "
		 IF (a.fr_year > 0 AND a.to_year > 0, (a.fr_year <= '". $year ."' and a.to_year >= '". $year ."'), 
		 IF (a.fr_year > 0 AND a.to_year = 0, a.fr_year <= '". $year ."', 
		 IF (a.fr_year = 0 AND a.to_year > 0, a.to_year >= '". $year ."', (a.fr_year = 0 and a.to_year = 0))
		 )
		 ) and
		 ";
		 	 
		 //$query_where .= " IF (a.fr_year > 0, a.fr_year >= '". $year ."', NULL ) or";
		 //$query_where .= " IF (a.to_year > 0, a.to_year <= '". $year ."', NULL ) or";
		 
		 //type
		 $query_where .= " IF (a.type_id > 0, a.type_id = '". $type_id ."', a.type_id = 0 ) and";
		 
		 //condition
		 $query_where .= " IF (a.condition_id  > 0, a.condition_id  = '". $condition_id ."', a.condition_id = 0 ) and";
		 
		 //price
		 $query_where .= "
		 IF (a.fr_price > 0 AND a.to_price > 0, (a.fr_price <= '". $price ."' and a.to_price >= '". $price ."'), 
		 IF (a.fr_price > 0 AND a.to_price = 0, a.fr_price <= '". $price ."', 
		 IF (a.fr_price = 0 AND a.to_price > 0, a.to_price >= '". $price ."', (a.fr_price = 0 and a.to_price = 0))
		 )
		 ) and
		 ";
		 
		 //$query_where .= " IF (a.fr_price > 0, a.fr_price >= '". $price ."', NULL ) or";
		 //$query_where .= " IF (a.to_price > 0, a.to_price <= '". $price ."', NULL ) or";
		 
		 //category
		 $query_where .= " IF (a.category_id  > 0, a.category_id  = '". $category_id ."', a.category_id = 0 ) and";
		 
		 //manufacturer		 
		 $query_form .= " LEFT JOIN tbl_resource_manufacturer as b ON a.id = b.resource_id";
		 $query_where .= " IF (b.manufacturer_id > 0, b.manufacturer_id = '". $manufacturer_id ."', b.manufacturer_id IS NULL ) and";
		 
		 //state		 
		 $query_form .= " LEFT JOIN tbl_resource_state as c ON a.id = c.resource_id";
		 if ($country_id == 1){
			 $query_where .= " IF (c.state_id > 0, c.state_id = '". $state_id ."', c.state_id IS NULL ) and";
		 }else{
			 $query_where .= " IF (c.state != '', c.state = '". $cm->filtertext($state) ."', c.state IS NULL ) and";
		 }
		 //$query_where = rtrim($query_where, "and");
		 
		 $query_where .= " a.status_id = 1 and";		 
		 $query_sql = rtrim($query_sql, ",");
         $query_form = rtrim($query_form, ",");
         $query_where = rtrim($query_where, "and");		 
		 $query_where = rtrim($query_where, ", "); 

         $sql = $query_sql . $query_form . $query_where;
		 $sql = $sql." order by a.rank";
         //echo $sql . '<br />';
		 return $sql;		 
	 }
	 
	 public function display_yacht_resource($yacht_id, $res_row){
		 global $db, $cm;
         $returntxt = '';
		 if ($yacht_id > 0){
			 $yacht_ar = $cm->get_table_fields('tbl_yacht', 'manufacturer_id, year, category_id, condition_id, price, state_id, state, country_id', $yacht_id);
			 $res_row = $yacht_ar[0];
			 $res_row["id"] = $yacht_id;
		 }else{
			 $yacht_id = $res_row["id"];
		 }
		 $type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $yacht_id, 'yacht_id');
		 $res_row["type_id"] = $type_id;
		 $yacht_title = $this->yacht_name($yacht_id);
		 
		 $returntxt .= '
		 <h2>Resources And Services For This Boat</h2>
		 <h3 class="resourcesubhead">Boat: '. $yacht_title .'</h3>		 
		 ';
		 
		 //display resource data based on type
		 $sqlres = "select id, name, description_ad, display_customer_form from tbl_resource_type where status_id = 1 order by rank";
		 $resultres = $db->fetch_all_array($sqlres);
		 foreach($resultres as $rowres){
			 $resourcetypeid = $rowres['id'];
			 $resourcename = $rowres['name'];			 
			 $sql = $this->display_yacht_resource_sql($resourcetypeid, $res_row);			 
			 $result = $db->fetch_all_array($sql);
         	 $found = count($result);
			 
			 $returntxt .= '			 
			 <span class="resourcesubtitle">'. $resourcename .'</span>
			 ';
			 
			 if ($found > 0){
				 $clicklinkoption = 0;
				 $display_customer_form = $rowres['display_customer_form'];
				 if ($display_customer_form == 1){ $clicklinkoption = 1; }
				 $returntxt .= '				 
				 <ul class="gal-list">
				 ';
				 foreach($result as $row){
					$returntxt .= $this->display_resource_list($row, $clicklinkoption, $yacht_id);
				 }
				 $returntxt .= '</ul><div class="clear"></div>';
			 }else{
				 $resource_description_ad = $rowres['description_ad'];
				 $returntxt .= $resource_description_ad;
			 }
		 }
		 
		 return $returntxt;
	 }
	 
	 public function check_resource_with_return($checkval, $checkopt = 0){
        global $db, $cm, $frontend;
        $sql = "select * from tbl_resource where id = '". $cm->filtertext($checkval) ."' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $_SESSION["ob"] = $frontend->display_message(28);            			
			if ($checkopt == 1){
                //frontend popup
                $redpage = $cm->get_page_url(0, "popsorry");
            }else{
                //frontend normal
                $redpage = $cm->get_page_url(0, "sorry");
            }
            header('Location: '. $redpage);
            exit;			
        }
        return $result;
    }
	
	public function resource_search_sql(){
        global $db, $cm;        
		$rtp = round($_REQUEST["rtp"], 0);
		$comname = $_REQUEST["comname"];
        
        $bcountry_id = round($_REQUEST["bcountry_id"], 0);
        $bstate_id = round($_REQUEST["bstate_id"], 0);
        $bstate = $_REQUEST["bstate"];
		$typename = $_REQUEST["typename"];
		
		$displayrec = 0;
		$searchfilter = array();
			
		$query_sql = "select distinct a.*,";
		$query_form = " from tbl_resource as a ";
		$query_where = " where";
		
		if ($typename != ""){
			$rtp = $cm->get_common_field_name('tbl_resource_type', 'id', $typename, 'name');
		}
		
		if ($rtp > 0){
			$query_where .= " a.resource_type_id = '". $rtp ."' and";
			$searchfilter["rtp"] = $rtp;
		}
		
		if ($comname != ""){
			$query_where .= "  a.company_name like '". $cm->filtertext($comname). "%' and";				
		}
				
		if ($bstate != ""){				
			$query_where .= "  a.state like '". $cm->filtertext($bstate). "%' and";
		}

		if ($bstate_id > 0){
			$query_where .= " a.state_id = '". $bstate_id ."' and";
		}
		
		if ($bcountry_id > 0){
			$query_where .= " a.country_id = '". $bcountry_id ."' and";
		}
		
		if ($bzip != ""){
			$query_where .= "  a.zip like '". $cm->filtertext($bzip). "%' and";
		}
		
		$query_where .= " a.status_id = 1 and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $returnvalu = array(
            'sql' => $sql,
			'search_filter' => $searchfilter,
            'displayrec' => $displayrec
        );

        return $returnvalu;
    }

    public function total_resource_search_found($sql){
        global $db;
        $sqlm = str_replace("select distinct a.*","select count(distinct a.id) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function resource_search_list($p, $ajaxpagination = 0){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

        $dcon = $cm->pagination_record_list;
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }        

        $returnvalu_ar = $this->resource_search_sql();
        $sql = $returnvalu_ar['sql'];
        $foundm = $this->total_resource_search_found($sql);
		
		$limitsql = " LIMIT ". $page .", ". $dcon;
		$sorting_sql = " a.rank";        

        $sql = $sql." order by ". $sorting_sql . $limitsql;		
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		$remaining = $foundm - ($p * $dcon);
        if ($found > 0){

            if ($ajaxpagination == 0){ $returntext .= '<ul class="gal-list">'; }
			foreach($result as $row){
				$returntext .= $this->display_resource_list($row);
			}
			if ($ajaxpagination == 0){ $returntext .= '</ul><div class="clear"></div>'; }
			
            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" class="moreresource button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'totalrec' => $foundm,
            'moreviewtext' => $moreviewtext,
			'search_filter' => $returnvalu_ar['search_filter']
        );
        return json_encode($returnval);
    }
	
	public function check_resourcetype_with_return($checkval, $checkopt = 0){
        global $db, $cm, $frontend;
        $sql = "select * from tbl_resource_type where id = '". $cm->filtertext($checkval) ."' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $_SESSION["ob"] = $frontend->display_message(28);            			
			if ($checkopt == 1){
                //frontend popup
                $redpage = $cm->get_page_url(0, "popsorry");
            }else{
                //frontend normal
                $redpage = $cm->get_page_url(0, "sorry");
            }
            header('Location: '. $redpage);
            exit;			
        }
        return $result;
    }
	
	public function check_resourcetype_enabled_form($checkval, $checkopt = 0){
		global $db, $cm, $frontend;
        $sql = "select id from tbl_resource_type where id = '". $cm->filtertext($checkval) ."' and display_customer_form = 1 and status_id = 1";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found == 0){
            $_SESSION["ob"] = $frontend->display_message(28);            			
			if ($checkopt == 1){
                //frontend popup
                $redpage = $cm->get_page_url(0, "popsorry");
            }else{
                //frontend normal
                $redpage = $cm->get_page_url(0, "sorry");
            }
            header('Location: '. $redpage);
            exit;			
        }
        //return $result;
	}
	//resource end
	
	//resource form	
	public function get_boating_courses_combo($boating_courses_completed, $frontfrom = 0, $azop = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_boating_courses where id > 0";
        if ($frontfrom == 1){
            $vsql .= " and status_id = 1";
        }
        $vsql .= " order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($boating_courses_completed == $c_id){
				$bck = ' selected="selected"';	
			}
			
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		if ($azop == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
    }
	//resource end
	
	//Industry Association
	public function get_industry_associations_combo($industry_associations_id, $frontfrom = 0){
        global $db;
		$returntxt = '';		
        $vsql = "select id, name from tbl_industry_associations";	
        if ($frontfrom == 1){
            $vsql .= " where status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);		
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($industry_associations_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		return $returntxt;
    }
	
	public function get_industryassociation_box_details($industry_associations_id){		
		$industryassociation_data = $this->get_industry_associations_combo($industry_associations_id);	
		$returnval[] = array(
			'industryassociation_data' => $industryassociation_data
		);
		return json_encode($returnval);
	}
	
	//--------(section = 1<company>, section = 2<broker>)----
	public function industryassociation_section($section){
		if ($section == 2){
			$assign_table = 'tbl_user_industry_associations';
			$assign_field = 'user_id';
		}else{
			$assign_table = 'tbl_company_industry_associations';
			$assign_field = 'company_id';
		}
		
		$returnval = array(
            'assign_table' => $assign_table,
			'assign_field' => $assign_field
        );
        return $returnval;
	}
	
	public function industryassociation_display_list_main($connect_id, $section, $frontfrom = 0){
		global $db, $cm;
        $returntext = '';
		
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		
		$sql_c = "select id, industry_associations_id, rank from ". $assign_table ." where ". $assign_field ." = '". $connect_id ."' order by rank";
		$result_c = $db->fetch_all_array($sql_c);
		$found_c = count($result_c);
		$rc_count = 1;
		if ($found_c > 0){						
			foreach($result_c as $row_c){
				$asso_id  = $row_c['id'];
				$industry_associations_id  = $row_c['industry_associations_id'];				
				$asso_rank  = $row_c['rank'];
				$industry_associations_name = $cm->get_common_field_name('tbl_industry_associations', 'name', $industry_associations_id);
				if ($frontfrom == 0){
					$delpath = '';
					$returntext .= '
						<li id="item-'. $asso_id .'" class="assorowind'. $rc_count .'">
							<div class="left-col1 sorticon displayasinfo'. $rc_count .'"><span class="destitle'. $rc_count .'">'. $industry_associations_name .'</span></div>
							<div class="right-col1 displayasinfo'. $rc_count .'">
								<a class="asso_del" isdb="1" yval="'. $rc_count .'" asso_id="'. $asso_id .'" section="'. $section .'" yid="'. $connect_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a>
							</div>
							<div class="clearfix"></div>
						</li>
					';
				}else{
					$delpath = $cm->folder_for_seo;
					$returntext .= '
						<li id="item-'. $asso_id .'" class="assorowind'. $rc_count .'">
							<div class="left-col1 sorticon displayasinfo'. $rc_count .'"><span class="destitle'. $rc_count .'">'. $industry_associations_name .'</span></div>
							<div class="right-col1 displayasinfo'. $rc_count .'">
								<a class="asso_del" isdb="1" yval="'. $rc_count .'" asso_id="'. $asso_id .'" section="'. $section .'" yid="'. $connect_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a>
							</div>
							
							<div class="displayasedit'. $rc_count .' com_none">
							<strong>Please wait...</strong>
							</div>
							<div class="clearfix"></div>
						</li>
					';
				}
				$rc_count++;
			}
			
			if ($frontfrom == 0){
				$returntext = '
					<tr><td colspan="3">
					<ul id="assosortable" class="form" yid="'. $connect_id .'" section="'. $section .'">
					'. $returntext .'
					</ul>					
					</td></tr>
				';
			}else{
				$returntext = '
					<li>
					<ul id="assosortable" class="formin" yid="'. $connect_id .'" section="'. $section .'">
					'. $returntext .'
					</ul>					
					</li>
				';
			}
			
		}
		$returnval = array(
            'displaytext' => $returntext,
			'totalrecord' => $rc_count
        );
        return $returnval;
	}
	
	public function industryassociation_display_list($connect_id, $section, $frontfrom = 0){
		global $cm;
		$returntext = '';		
		$yacht_description_details = $this->industryassociation_display_list_main($connect_id, $section, $frontfrom);
		$total_associations = $yacht_description_details['totalrecord'];			
		if ($frontfrom == 0){			
			$returntext .= '
				<table id="assoholder" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
				'. $yacht_description_details['displaytext'] .'';
				
				if ($total_associations == 1){
					$retval = json_decode($this->get_industryassociation_box_details(0));
					$returntext .= '
					<tr class="assorowind1">
						<td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Industry Association:</td>
						<td width="" align="left"><select name="industry_associations_id1" id="industry_associations_id1" class="combobox_size4 htext">
						<option value="">Select</option>
						'. $retval[0]->industryassociation_data .'
						</select>
						</td>
						<td width="25" align="left" valign="top" class="tdpadding1">&nbsp;&nbsp;</td>
					</tr>
					';
				}				
			$returntext .= '
				</table>
			';
			
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                	<td width="" align="left" valign="top" class="tdpadding1"><button type="button" class="addrowasso butta"><span class="addIcon butta-space">Add New</span></button></td>
                </tr>
            </table>
			<input type="hidden" value="'. $total_associations .'" id="total_associations" name="total_associations" />
			';
			
		}else{
			$returntext .= '
			<ul id="assoholder" class="form">
			'. $yacht_description_details['displaytext'] .'';
			
			if ($total_associations == 1){
				$retval = json_decode($this->get_industryassociation_box_details(0));
				$returntext .= '
				<li class="left assorowind1">
                    <p>Select Industry Association</p>
                    <select name="industry_associations_id1" id="industry_associations_id1" class="my-dropdown2">
					<option value="">Select</option>
					'. $retval[0]->industryassociation_data .'
					</select>
                </li>	
				<li class="assorowind1"></li>							
				';
			}
			$returntext .= '				
			</ul>
			<a href="javascript:void(0);" class="addrowasso icon-add">Add New Industry Associations</a>
			<input type="hidden" value="'. $total_associations .'" id="total_associations" name="total_associations" />
			';
		}		
		return $returntext;
	}
	
	public function industry_associations_assign($connect_id, $section){
		global $db, $cm;
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
					
		$total_associations = round($_POST["total_associations"], 0);
		$associations_rank = $db->total_record_count("select max(rank) as ttl from ". $assign_table ." where ". $assign_field ." = '". $connect_id ."'") + 1;
		for ($i = 1; $i <= $total_associations; $i++){
			$industry_associations_id = round($_POST["industry_associations_id" . $i], 0);					
			if ($industry_associations_id > 0){		
				$asso_id = $cm->campaignid(35) . $connect_id;		
				$sql = "insert into ". $assign_table ." (id, ". $assign_field .", industry_associations_id, rank) values ('". $cm->filtertext($asso_id) ."', '". $connect_id ."', '". $industry_associations_id ."', '". $associations_rank ."')";
                $db->mysqlquery($sql);
				$associations_rank++;
			}
		}
	}
	
	public function delete_industryassociation($asso_id, $connect_id, $section){
		global $db, $cm;
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		$sql = "delete from ". $assign_table ." where id = '". $cm->filtertext($asso_id) ."' and ". $assign_field ." = '". $connect_id ."'";
        $db->mysqlquery($sql);
	}
	
	public function industryassociation_box_sort($connect_id, $section){
		global $db;
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		parse_str($_POST['data'], $recOrder);
		$i = 1;
		foreach ($recOrder['item'] as $value) {
			$sql = "update ". $assign_table ." set rank = '". $i ."' where id = '". $value ."' and ". $assign_field ." = '". $connect_id ."'";
        	$db->mysqlquery($sql);
			$i++;			
		}
	}
	
	public function display_industry_associations($connect_id, $section, $undercol = 0){
		global $db, $cm;
		$returntext = '';
		$maindiclass = 'profile-main';
		if ($undercol == 1){
			$maindiclass = 'memberof';
		}
		
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		$ia_sql = "select a.name, a.link_url, a.logo_image from tbl_industry_associations as a, ". $assign_table ." as b where a.id = b.industry_associations_id and b.". $assign_field ." = '". $connect_id ."' and a.status_id = 1";
		$ia_result = $db->fetch_all_array($ia_sql);
        $ia_found = count($ia_result);
		if ($ia_found > 0){
			$fol_path = $cm->get_data_web_url() . '/';
			$returntext .= '<div class="'. $maindiclass .'">
			<h2>Member Of</h2>
			<ul class="logo-list">';
			foreach($ia_result as $ia_row){
				$name = $ia_row['name'];
				$link_url = $ia_row['link_url'];
				$logo_image  = $ia_row['logo_image'];
				if ($logo_image == ""){ 
					$logo_image = "no.jpg"; 
					$fol_path = $cm->folder_for_seo;
				}
				
				$litext = '<img src="'. $fol_path .'associationsimage/'. $logo_image .'" title="'. $name .'" alt="'. $name .'">';
				if ($link_url != ""){
					$litext = '<a href="'. $link_url .'" target="_blank">'. $litext .'</a>';
				}
				$returntext .= '
				<li>'. $litext .'</li>			
				';
			}
			$returntext .= '</ul><div class="clear"></div></div>';
		}
		return $returntext;
	}
	//end
	
	//certification
	public function get_certification_combo($certification_id, $frontfrom = 0){
        global $db;
		$returntxt = '';		
        $vsql = "select id, name from tbl_certification";	
        if ($frontfrom == 1){
            $vsql .= " where status_id = 1";
        }
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);		
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($certification_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		return $returntxt;
    }
	
	public function get_certification_box_details($certification_id){		
		$certification_data = $this->get_certification_combo($certification_id);	
		$returnval[] = array(
			'certification_data' => $certification_data
		);
		return json_encode($returnval);
	}
	
	//--------(section = 1<company>, section = 2<broker>)----
	public function certification_section($section){
		if ($section == 2){
			$assign_table = 'tbl_user_certification';
			$assign_field = 'user_id';
		}else{
			$assign_table = 'tbl_company_certification';
			$assign_field = 'company_id';
		}
		
		$returnval = array(
            'assign_table' => $assign_table,
			'assign_field' => $assign_field
        );
        return $returnval;
	}
	
	public function certification_display_list_main($connect_id, $section, $frontfrom = 0){
		global $db, $cm;
        $returntext = '';
		
		$industryassociation_ar = $this->certification_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		
		$sql_c = "select id, certification_id, certification_name, rank from ". $assign_table ." where ". $assign_field ." = '". $connect_id ."' order by rank";
		$result_c = $db->fetch_all_array($sql_c);
		$found_c = count($result_c);
		$rc_count = 1;
		if ($found_c > 0){						
			foreach($result_c as $row_c){
				$cert_id  = $row_c['id'];
				$certification_id = $row_c['certification_id'];
				$certification_name = $row_c['certification_name'];				
				$cert_rank  = $row_c['rank'];
				
				if ($certification_id > 0){ $certification_name = $cm->get_common_field_name('tbl_certification', 'name', $certification_id); }
				if ($frontfrom == 0){
					$delpath = '';
					$returntext .= '
						<li id="item-'. $cert_id .'" class="certrowind'. $rc_count .'">
							<div class="left-col1 sorticon displayasinfo'. $rc_count .'"><span class="destitle'. $rc_count .'">'. $certification_name .'</span></div>
							<div class="right-col1 displayasinfo'. $rc_count .'">
								<a class="cert_del" isdb="1" yval="'. $rc_count .'" cert_id="'. $cert_id .'" section="'. $section .'" yid="'. $connect_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a>
							</div>
							<div class="clearfix"></div>
						</li>
					';
				}else{
					$delpath = $cm->folder_for_seo;
					$returntext .= '
						<li id="item-'. $cert_id .'" class="certrowind'. $rc_count .'">
							<div class="left-col1 sorticon displayasinfo'. $rc_count .'"><span class="destitle'. $rc_count .'">'. $certification_name .'</span></div>
							<div class="right-col1 displayasinfo'. $rc_count .'">
								<a class="cert_del" isdb="1" yval="'. $rc_count .'" cert_id="'. $cert_id .'" section="'. $section .'" yid="'. $connect_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="'. $delpath .'images/del.png" /></a>
							</div>
							<div class="clearfix"></div>
						</li>
					';
				}
				$rc_count++;
			}
			
			if ($frontfrom == 0){
				$returntext = '
					<tr><td colspan="3">
					<ul id="certsortable" class="form" yid="'. $connect_id .'" section="'. $section .'">
					'. $returntext .'
					</ul>					
					</td></tr>
				';
			}else{
				$returntext = '
					<li>
					<ul id="certsortable" class="formin" yid="'. $connect_id .'" section="'. $section .'">
					'. $returntext .'
					</ul>					
					</li>
				';
			}
			
		}
		$returnval = array(
            'displaytext' => $returntext,
			'totalrecord' => $rc_count
        );
        return $returnval;
	}
	
	public function certification_display_list($connect_id, $section, $frontfrom = 0){
		global $cm;
		$returntext = '';		
		$certification_details = $this->certification_display_list_main($connect_id, $section, $frontfrom);
		$total_certification = $certification_details['totalrecord'];			
		if ($frontfrom == 0){			
			$returntext .= '
				<table id="certholder" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
				'. $certification_details['displaytext'] .'';
				
				if ($total_certification == 1){
					$retval = json_decode($this->get_certification_box_details(0));
					$returntext .= '
					<tr class="certrowind1">
						<td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Certificate:</td>
						<td width="" align="left"><select name="certification_id1" id="certification_id1" class="combobox_size4 htext">
						<option value="">Select</option>
						'. $retval[0]->certification_data .'
						</select>
						</td>
					</tr>
					
					<tr class="certrowind1">
						<td width="" align="left" valign="top" class="tdpadding1" colspan="2"><span class="fontcolor3">&nbsp;&nbsp;</span><strong>OR</strong></td>
					</tr>
					
					<tr class="certrowind1">
						<td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Certificate Name:</td>
						<td width="" align="left"><input type="text" id="certification_name1" name="certification_name1" value="" class="inputbox inputbox_size4" /></td>
					</tr>
					<tr class="certrowind1">
						<td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Link URL:</td>
						<td width="" align="left"><input type="text" id="certification_link_url1" name="certification_link_url1" value="" class="inputbox inputbox_size4" /></td>
					</tr>
					<tr class="certrowind1">
						<td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Logo Image [w: '. $cm->crt_im_width .'px, h: '.  $cm->crt_im_height .'px]:</td>
						<td width="" align="left"><input type="file" id="logo_image1" name="logo_image1" class="inputbox filebox_size4" /><br />[Allowed file types: '. $cm->allow_image_ext .']</td>
					</tr>
					';
				}				
			$returntext .= '
				</table>
			';
			
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                	<td width="" align="left" valign="top" class="tdpadding1"><button type="button" class="addrowcert butta" valimg="w: '. $cm->crt_im_width .'px, h: '.  $cm->crt_im_height .'px" alltype="Allowed file types: '. $cm->allow_image_ext .'"><span class="addIcon butta-space">Add New</span></button></td>
                </tr>
            </table>
			<input type="hidden" value="'. $total_certification .'" id="total_certification" name="total_certification" />
			';
			
		}else{
			$returntext .= '
			<ul id="certholder" class="form">
			'. $certification_details['displaytext'] .'';		
			if ($total_certification == 1){
				$retval = json_decode($this->get_certification_box_details(0));
				$returntext .= '
				<li class="left certrowind1">
                    <p>Select Certificate</p>
                    <select name="certification_id1" id="certification_id1" class="my-dropdown2">
					<option value="">Select</option>
					'. $retval[0]->certification_data .'
					</select>
                </li>	
				<li class="certrowind1"><strong>OR</strong></li>
					
				<li class="left certrowind1">
                    <p>Certificate Name</p>
                    <input type="text" id="certification_name1" name="certification_name1" value="" class="input" />
                </li>
				<li class="right certrowind1">
                    <p>Link URL</p>
                    <input type="text" id="certification_link_url1" name="certification_link_url1" value="" class="input" />
                </li>	
				
				<li class="certrowind1">
                    <p>Logo Image [w: '. $cm->crt_im_width .'px, h: '.  $cm->crt_im_height .'px]</p>
                    <input type="file" id="logo_image1" name="logo_image1" value="" class="input" />
					<p>[Allowed file types: '. $cm->allow_image_ext .']</p>
                </li>					
				';
			}
			$returntext .= '				
			</ul>
			<a href="javascript:void(0);" class="addrowcert icon-add" valimg="w: '. $cm->crt_im_width .'px, h: '.  $cm->crt_im_height .'px" alltype="Allowed file types: '. $cm->allow_image_ext .'">Add New Certificate</a>
			<input type="hidden" value="'. $total_certification .'" id="total_certification" name="total_certification" />
			';
		}		
		return $returntext;
	}
	
	public function certification_assign($connect_id, $section, $frontfrom = 0){
		global $db, $cm, $fle;
		$certification_ar = $this->certification_section($section);
		$assign_table = $certification_ar['assign_table'];
		$assign_field = $certification_ar['assign_field'];
					
		$total_certification = round($_POST["total_certification"], 0);
		$certification_rank = $db->total_record_count("select max(rank) as ttl from ". $assign_table ." where ". $assign_field ." = '". $connect_id ."'") + 1;
		for ($i = 1; $i <= $total_certification; $i++){
			$certification_id = round($_POST["certification_id" . $i], 0);	
			$certification_name = $_POST["certification_name" . $i];
			$certification_link_url = $_POST["certification_link_url" . $i];	
			$filename = $_FILES['logo_image' . $i]['name'] ;
			
			$certification_check = 0;
			if ($certification_id > 0){
				$certification_name = '';
				$certification_check = 1;
			}else{
				if ($certification_name != "" OR $filename != ""){
					$certification_check = 1;
				}
			}
						
			if ($certification_check == 1){	
				$certification_link_url = $cm->format_url_txt($certification_link_url);	
				$cert_id = $cm->campaignid(35) . $connect_id;		
				$sql = "insert into ". $assign_table ." (id, ". $assign_field .", certification_id, certification_name, certification_link_url, rank) values ('". $cm->filtertext($cert_id) ."', '". $connect_id ."', '". $certification_id ."', '". $cm->filtertext($certification_name) ."', '". $cm->filtertext($certification_link_url) ."', '". $certification_rank ."')";
                $db->mysqlquery($sql);
				$certification_rank++;
				
				//logo upload
				if ($filename != ""){
					$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
					if ($wh_ok == "y"){
						$filename_tmp = $_FILES['logo_image' . $i]['tmp_name'];
						$filename = $fle->uploadfilename($filename);
						$filename1 = $cert_id."certificationlogo".$filename;
						
						$target_path_main = "certificationimage/";
						if ($frontfrom == 0){
							$target_path_main = "../" . $target_path_main;
						}						
						
						$r_width = $cm->crt_im_width;
						$r_height = $cm->crt_im_height;
						$target_path = $target_path_main;
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
						
						$fle->filedelete($filename_tmp);
						$sql = "update ". $assign_table ." set certification_logo = '".$cm->filtertext($filename1)."' where id = '". $cm->filtertext($cert_id) ."'";
						$db->mysqlquery($sql);
					}
				}
			}
			
		}
	}
	
	public function delete_certification($cert_id, $connect_id, $section, $frontfrom = 0){
		global $db, $cm, $fle;
		$certification_ar = $this->certification_section($section);
		$assign_table = $certification_ar['assign_table'];
		$assign_field = $certification_ar['assign_field'];
		
		$fimg1 = $db->total_record_count("select certification_logo as ttl from ". $assign_table ." where id = '". $cm->filtertext($cert_id) ."' and ". $assign_field ." = '". $connect_id ."'");
		if ($fimg1 != ""){
			$depth = "../";
			if ($frontfrom == 0){ $depth = "../"; }
			$fle->filedelete($depth . "certificationimage/".$fimg1);
		}
		
		$sql = "delete from ". $assign_table ." where id = '". $cm->filtertext($cert_id) ."' and ". $assign_field ." = '". $connect_id ."'";
        $db->mysqlquery($sql);
	}
	
	public function certification_box_sort($connect_id, $section){
		global $db;
		$certification_ar = $this->certification_section($section);
		$assign_table = $certification_ar['assign_table'];
		$assign_field = $certification_ar['assign_field'];
		
		parse_str($_POST['data'], $recOrder);
		$i = 1;
		foreach ($recOrder['item'] as $value) {
			$sql = "update ". $assign_table ." set rank = '". $i ."' where id = '". $value ."' and ". $assign_field ." = '". $connect_id ."'";
        	$db->mysqlquery($sql);
			$i++;			
		}
	}
	
	public function display_certification($connect_id, $section, $undercol = 0){
		global $db, $cm;
		$returntext = '';
		$maindiclass = 'profile-main';
		if ($undercol == 1){
			$maindiclass = 'memberof';
		}
		
		$certification_ar = $this->certification_section($section);
		$assign_table = $certification_ar['assign_table'];
		$assign_field = $certification_ar['assign_field'];
		
		$ia_sql = "select a.certification_name, a.certification_link_url, a.certification_logo, b.id, b.name, b.link_url, b.logo_image from ". $assign_table ." as a LEFT JOIN
		tbl_certification as b ON a.certification_id = b.id where a.". $assign_field ." = '". $connect_id ."' and (b.status_id = 1 OR b.status_id IS NULL)";
		$ia_result = $db->fetch_all_array($ia_sql);
        $ia_found = count($ia_result);
		if ($ia_found > 0){
			$def_fol_path = $cm->folder_for_seo;
			$returntext .= '<div class="'. $maindiclass .'">
			<h2>Certification</h2>
			<ul class="logo-list">';
			foreach($ia_result as $ia_row){
				$certification_id = $ia_row['id'];
				if ($certification_id > 0){
					$certification_name = $ia_row['name'];
					$link_url = $ia_row['link_url'];
					$logo_image  = $ia_row['logo_image'];
					$fol_path = $cm->get_data_web_url() . '/';
				}else{
					$certification_name = $ia_row['certification_name'];
					$link_url = $ia_row['certification_link_url'];
					$logo_image  = $ia_row['certification_logo'];
					$fol_path = $def_fol_path;
				}
				
				if ($logo_image == ""){ 
					$fol_path = $def_fol_path;
					$logo_image = "no.jpg"; 
				}
				
				$litext = '<img src="'. $fol_path .'certificationimage/'. $logo_image .'" title="'. $certification_name .'" alt="'. $certification_name .'">';
				if ($link_url != ""){
					$litext = '<a href="'. $link_url .'" target="_blank">'. $litext .'</a>';
				}
				
				$returntext .= '
				<li>			
					'. $litext .'
				</li>
				';
			}
			$returntext .= '</ul><div class="clear"></div></div>';
		}
		return $returntext;
	}
	
	//manufacturer profile
	public function check_manufacturer_exist($checkvalue, $optn = 1, $adminfrom = 0, $frompopup = 0){
		global $db, $cm;
		if ($optn == 1){
            $sql = "select * from tbl_manufacturer where slug = '". $cm->filtertext($checkvalue) ."'";
        }else{
            $sql = "select * from tbl_manufacturer where id = '". $cm->filtertext($checkvalue) ."'";
        }
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found == 0){
			if ($adminfrom == 1){
				$_SESSION["admin_sorry"] = "ERROR! Invalid Manufacturer.";
				header('Location: sorry.php');
				exit;				
			}else{
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;				
			}	
        }
        return $result;		
	}
	//end
	
	public function get_total_yacht_by_manufacturer($manufacturer_id){
		global $db;
		$sqltext = "select count(*) as ttl from tbl_yacht where";
		$sqltext .= " manufacturer_id = '". $manufacturer_id ."' and";
		$sqltext .= " status_id IN (1,3) and display_upto >= CURDATE()";
		$total_y = $db->total_record_count($sqltext);
        return $total_y;
	}
	
	//industry associations and certification logo combined
	public function combined_associations_certifications($connect_id, $section){
		global $db, $cm;
		$returntext = '';
		$def_fol_path = $fol_path = $cm->get_data_web_url() . '/';
		
		//industry associations
		$industryassociation_txt = '';
		$industryassociation_ar = $this->industryassociation_section($section);
		$assign_table = $industryassociation_ar['assign_table'];
		$assign_field = $industryassociation_ar['assign_field'];
		
		$ia_sql = "select a.name, a.link_url, a.logo_image from tbl_industry_associations as a, ". $assign_table ." as b where a.id = b.industry_associations_id and b.". $assign_field ." = '". $connect_id ."' and a.status_id = 1";
		$ia_result = $db->fetch_all_array($ia_sql);
        $ia_found = count($ia_result);
		
		foreach($ia_result as $ia_row){
			$name = $ia_row['name'];
			$link_url = $ia_row['link_url'];
			$logo_image  = $ia_row['logo_image'];
			if ($logo_image == ""){ 
				$logo_image = "no.jpg"; 
				$fol_path = $cm->folder_for_seo;
			}
			
			$litext = '<img src="'. $fol_path .'associationsimage/'. $logo_image .'" title="'. $name .'" alt="'. $name .'">';
			if ($link_url != ""){
				$litext = '<a href="'. $link_url .'" target="_blank">'. $litext .'</a>';
			}
			$returntext .= '
			<li>'. $litext .'</li>			
			';
		}
		
		//certification
		$certification_txt = '';
		$certification_ar = $this->certification_section($section);
		$assign_table = $certification_ar['assign_table'];
		$assign_field = $certification_ar['assign_field'];
		
		$ia_sql = "select a.certification_name, a.certification_link_url, a.certification_logo, b.id, b.name, b.link_url, b.logo_image from ". $assign_table ." as a LEFT JOIN
		tbl_certification as b ON a.certification_id = b.id where a.". $assign_field ." = '". $connect_id ."' and (b.status_id = 1 OR b.status_id IS NULL)";
		$ia_result = $db->fetch_all_array($ia_sql);
        $ia_found = count($ia_result);
		
		foreach($ia_result as $ia_row){
			$certification_id = $ia_row['id'];
			if ($certification_id > 0){
				$certification_name = $ia_row['name'];
				$link_url = $ia_row['link_url'];
				$logo_image  = $ia_row['logo_image'];
				$fol_path = $cm->get_data_web_url() . '/';
			}else{
				$certification_name = $ia_row['certification_name'];
				$link_url = $ia_row['certification_link_url'];
				$logo_image  = $ia_row['certification_logo'];
				$fol_path = $def_fol_path;
			}
			
			if ($logo_image == ""){ 
				$fol_path = $def_fol_path;
				$logo_image = "no.jpg"; 
			}
			
			$litext = '<img src="'. $fol_path .'certificationimage/'. $logo_image .'" title="'. $certification_name .'" alt="'. $certification_name .'">';
			if ($link_url != ""){
				$litext = '<a href="'. $link_url .'" target="_blank">'. $litext .'</a>';
			}
			
			$returntext .= '
			<li>			
				'. $litext .'
			</li>
			';
		}
		
		if ($returntext != ""){
			$returntext = '<div class="listrow"><ul class="logo-list">' . $returntext . '</ul><div class="clear"></div></div>';
		}		
		return $returntext;
	}
	
	//Front-end boat form submir
	public function submit_boat_form(){
		if(($_POST['fcapi'] == "boatsubmit")){
			global $db, $cm, $frontend;
			$frontend->go_to_login();
			$mbid= round($_POST["ms"], 0);
			$this->can_access_yacht($mbid);
			$this->boat_insert_update();
			exit;
		}
	}
	
	public function boat_insert_update(){
		global $db, $cm, $edclass, $geo, $fle;
		$loggedin_member_id = $this->loggedin_member_id();
		
		$email2 = $_POST["email2"];
		if ($email2 != ""){
			header('Location: '. $cm->site_url .'');
			exit;
		}
		
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
					OR $key == "featured"
					OR $key == "sold_day_no"
					OR $key == "custom_label_id"
					OR $key == "show_price"
					OR $key == "price_tag_id" 
					OR $key == "price_per_option_id" 
					OR $key == "lat_lon_manual"		
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
		
		$loa = $this->implode_feet_inchs($loa_ft, $loa_in);
		$beam = $this->implode_feet_inchs($beam_ft, $beam_in);
		$draft = $this->implode_feet_inchs($draft_ft, $draft_in);
		$bridge_clearance = $this->implode_feet_inchs($bridge_clearance_ft, $bridge_clearance_in);
		
		if ($speed_unit == 2){
			$cruise_speed = round($cruise_speed / $this->mph_to_kts, 2);
			$max_speed = round($max_speed / $this->mph_to_kts, 2);
		}
		
		if ($country_id == 1){
			$state = "";
		}else{
			$state_id = 0;
		}
		
		if ($link_url != ""){
			$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($link_url));
		}
		
		if ($featured != 1){ $featured = 0; }
		$dt = date("Y-m-d H:i:s");
		
		if ($ms == 0){
			$sql = "insert into tbl_yacht (company_id, reg_date) values ('". $cm->filtertext($company_id) ."', '". $dt ."')";
			$iiid = $db->mysqlquery_ret($sql);
		
			$listing_no = $this->listing_start + $iiid;
			$sql = "update tbl_yacht set listing_no = '". $listing_no ."' where id = '". $iiid ."'";
			$db->mysqlquery($sql);
		
			$this->add_delete_yacht_extra_info($iiid, 1);
			
			//create folder
			$source = "yachtimage/rawimage";
			$destination = "yachtimage/".$listing_no;
			$fle->copy_folder($source, $destination);
			
			$messageid = 23;
		
		}else{
			$listing_no = $this->get_yacht_no($ms);
			$this->check_yacht_with_return($listing_no, 2);
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_yacht set company_id = '". $cm->filtertext($company_id) ."' where id = '".$ms."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
			$messageid = 24;    
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
		, charter_descriptions = '". $cm->filtertext($charter_descriptions)."' where id = '". $iiid ."'";
		$db->mysqlquery($sql);
		
		//lat-lon	
		if ($lat_lon_manual == 1){
			$lat = $_POST["lat_val"];
			$lon = $_POST["lon_val"];
		}else{		
			$latlonar = $geo->getLatLon($iiid, 1);
			$lat = $latlonar["lat"];
			$lon = $latlonar["lon"];
		}
		
		//meta
		//if ($m1 == ""){ $m1 = $this->yacht_name($iiid); }
		//if ($m2 == ""){ $m2 = $cm->get_sort_content_description($overview, 350); }
		
		//boat slug
		$boat_slug = $this->create_boat_slug($iiid);
		
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
		
		$this->update_sold_yacht_display_date($iiid, $sold_day_no);
		$this->add_yacht_keywords($iiid);
		$this->remove_sold_yacht_from_featured($iiid, $status_id);
		//end
		
		//type assign
		$this->yacht_type_assign($iiid);
		//end
		
		//engine assign
		$this->yacht_engine_assign($iiid);
		//end
		
		//external link assign
		$this->yacht_external_link_assign($iiid);
		//end
		
		//image section - edit
		$this->edit_yacht_image();
		//end
		
		//engine details assign
		global $yachtengineclass;
		$yachtengineclass->engine_details_assign($iiid);
		//end
		
		//brochure upload
		$filename = $_FILES['brochure_file']['name'];
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_attachment_ext, $filename);
			if ($wh_ok == "y"){
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."brochure".$filename;
				$target_path = "brochurefile/";
				$target_path = $target_path . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['brochure_file']['tmp_name'], $target_path);
		
				$sql = "update tbl_yacht set brochure_file = '". $cm->filtertext($filename1)."' where id = '". $iiid ."'";
				$db->mysqlquery($sql);
			}
		}
		//end
		
		//update field for boat finder
		$this->update_field_for_boat_finder(array("boatid" => $iiid));
		//end
		
		global $frontend;
		$_SESSION["thnk"] = $frontend->display_message($messageid, $listing_no);
		header('Location: '. $cm->site_url .'/thankyou/');
		exit;		
	}
	
	//update field for Boat Finder
	public function update_field_for_boat_finder($param = array()){
		global $db, $cm;
		$currentdate = date("Y-m-d H:i:s");
		
		//param
		$boatid = round($param["boatid"], 0);
		$addedit = round($param["addedit"], 0);
		//end
		
		$addressfull = $this->get_yacht_address($boatid, 1);
		$price = $cm->get_common_field_name('tbl_yacht', 'price', $boatid);
		$updatedstring_added = $cm->get_common_field_name('tbl_yacht', 'updatedstring', $boatid);
		
		$updatedstring_raw = $price . ', ' . $addressfull;
		$updatedstring = md5($updatedstring_raw);
		
		if ($updatedstring_added != $updatedstring){
			$sql = "update tbl_yacht set update_for_yf = '". $currentdate ."', updatedstring = '". $updatedstring ."' where id = '". $boatid ."'";
			$db->mysqlquery($sql);
		}
	}
	
	//Drag-drop boat image upload
	public function submit_boat_image_drag_drop(){
		if(($_REQUEST['fcapi'] == "mupload")){
			global $db, $cm, $frontend, $fle;
			$frontend->go_to_login();
			$iiid = round($_POST["ms"], 0);
			$crop_option = round($_POST["crop_option"], 0);
			$rotateimage = round($_POST["rotateimage"], 0);
			if($_SERVER['REQUEST_METHOD'] == "POST"){
			
				$filename = $_FILES['file']['name'];
				$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
				if ($wh_ok == "y"){
					$listing_no = $this->get_yacht_no($iiid);
					$i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_photo where yacht_id = '". $iiid ."'") + 1;
					$i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
					$sql = "insert into tbl_yacht_photo (id, yacht_id, rank, status_id) values ('". $i_iiid ."', '". $iiid ."', '". $i_rank ."', 1)";
					$db->mysqlquery($sql);
			
					$filename_tmp = $_FILES['file']['tmp_name'];
					$filename = $fle->uploadfilename($filename);
					$filename1 = $i_iiid."yacht".$filename;
			
					$target_path_main = "yachtimage/" . $listing_no . "/";
			
					//thumbnail image
					$r_width = $cm->yacht_im_width_t;
					$r_height = $cm->yacht_im_height_t;
					$target_path = $target_path_main;
					if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
			
					//big image
					$r_width = $cm->yacht_im_width_b;
					$r_height = $cm->yacht_im_height_b;
					$target_path = $target_path_main . "big/";
					if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
			
					//bigger image
					$r_width = $cm->yacht_im_width;
					$r_height = $cm->yacht_im_height;
					$target_path = $target_path_main . "bigger/";
					if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
					
					//slider image
					$r_width = $cm->yacht_im_width_sl;
					$r_height = $cm->yacht_im_height_sl;
					$target_path = $target_path_main . "slider/";
					if ($crop_option == 1){
						$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}else{
						$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
					}
					
					//original image store
					$target_path = $target_path_main . 'original/';
					$target_path = $target_path . $cm->filtertextdisplay($filename1);
					$fle->fileupload($filename_tmp, $target_path);
					
					//rotate original image
					if ($rotateimage > 0){
						$im = @ImageCreateFromJPEG ($target_path);
						$im = imagerotate($im, $rotateimage, 0);
						@ImageJPEG ($im, $target_path, 100);
					}
			
					//$fle->filedelete($filename_tmp);
					$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
					$db->mysqlquery($sql);
					echo($_POST['index']);
				}
				exit;
			}
		}
	}
	
	//Drag-drop boat video upload
	public function submit_boat_video_drag_drop(){
		if(($_REQUEST['fcapi'] == "videoupload")){
			global $cm, $frontend;
			$frontend->go_to_login();
			$iiid = round($_POST["ms"], 0);
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->insert_yacht_video_file($iiid, 1);
				exit;
			}
		}
	}
	
	//Drag-drop boat attachment upload
	public function submit_boat_attachment_drag_drop(){
		if(($_REQUEST['fcapi'] == "attachemntfileupload")){
			global $cm, $frontend;
			$frontend->go_to_login();
			$iiid = round($_POST["ms"], 0);
			if($_SERVER['REQUEST_METHOD'] == "POST"){
				$this->insert_yacht_attachment_file($iiid, 1);
				exit;
			}
		}
	}
	
	//submit boat image form
	public function submit_boat_image_form(){
		if(($_POST['fcapi'] == "boatimagesave")){
			global $cm, $frontend;
			$frontend->go_to_login();
			$ms = round($_POST["ms"], 0);
			$this->can_access_yacht($ms);
			$this->edit_yacht_image();
			$listing_no = $this->get_yacht_no($ms);
			header('Location: '. $cm->folder_for_seo .'boat-image/'. $listing_no .'/');
			exit;
		}
	}
	
	//submit boat video form
	public function submit_boat_video_form(){
		if(($_POST['fcapi'] == "boatvideosave")){
			global $cm, $frontend;
			$frontend->go_to_login();
			$ms = round($_POST["ms"], 0);
			$this->can_access_yacht($ms);
			$this->edit_yacht_video();
			$listing_no = $this->get_yacht_no($ms);
			header('Location: '. $cm->folder_for_seo .'boat-video/'. $listing_no .'/');
			exit;
		}
	}
	
	//add boat video form
	public function add_boat_video_form(){
		if(($_POST['fcapi'] == "boatvideoadd")){
			global $cm, $frontend;
			$iiid = round($_POST["ms"], 0);
			$this->can_access_yacht($iiid);
			$this->insert_yacht_video_link($iiid);
			$listing_no = $this->get_yacht_no($iiid);
			header('Location: '. $cm->folder_for_seo .'boat-video/'. $listing_no .'/');
			exit;
		}
	}
	
	//submit boat attachment form
	public function submit_boat_attachment_form(){
		if(($_POST['fcapi'] == "boatattachmentsave")){
			global $cm, $frontend;
			$ms = round($_POST["ms"], 0);
			$this->can_access_yacht($ms);
			$this->edit_yacht_attachment_file();
			$listing_no = $this->get_yacht_no($ms);
			header('Location: '. $cm->folder_for_seo .'boat-attachment/'. $listing_no .'/');
			exit;
		}
	}
	
	//boat pdf creation - call
	public function boat_pdf_create_main(){
		if(($_REQUEST['fcapi'] == "createyachtpdf")){
			global $cm;
			$lno = round($_REQUEST['lno'], 0);
			$result = $this->check_yacht_with_return($lno, 1);
						
			/*
			//$html = $this->create_yacht_pdf_html($result);
			
			$pdf_content_ar = $this->create_yacht_pdf_html($result);
			$pdf_content_ar = json_decode($pdf_content_ar);
			
			$headertext = $pdf_content_ar->headertext;
			$html = $pdf_content_ar->returntxt;
			$pdffilename = $pdf_content_ar->pdffilename;
			
			//$filename = "Yacht-" . $lno . ".pdf";
			$cm->generate_pdf('', $html, $headertext, $pdffilename, 'I');
			exit;
			*/
			
			$pdf_content_ar = $this->create_yacht_pdf_html($result);			
			$pdf_content_ar = json_decode($pdf_content_ar);
			$html = $pdf_content_ar->returntxt;
			echo $html;
			exit;
			
		}
	}
	
	//Member Account Login
	public function member_account_login(){
		if(($_REQUEST['fcapi'] == "accountlogin")){
			$frompopup = round($_POST["frompopup"], 0);
			$this->user_login(0, 0, $frompopup);
			exit;
		}
	}
	
	//Boat Clear Search
	public function boat_clear_search(){
		if(($_REQUEST['fcapi'] == "clearfilters")){
			global $db, $cm;
			$this->remove_yach_search_var();
			header('Location: '. $cm->get_page_url(2, "page"));
			exit;
		}
	}
	
	//format brand name (add Yacht tag if not exist)
	public function format_brand_name($manufacturerarname){
		if (strpos($manufacturerarname, 'Yachts') === false){
			$manufacturerarname .= " Yachts";
		}
		return $manufacturerarname;
	}
}
?>