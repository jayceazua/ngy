<?php
class Chartclass {
	
	//create graph
	public function display_graph($fsection, $searchopt, $argu = array(), $comparelast = 0, $w = 600, $h = 300, $fsectionid = 0){
		global $db, $cm, $yachtclass, $yachtchildclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
        $datastring = '';
		$chartstyle = 1;
		$xaxislabel = '';
		$extra_return = array();
		
		//main array
		$dataarray = array();
		
		//label array
		$labelarray = array();		
		
		//Different parameter		
		$yr = round($argu["yr"], 0);
		$company_id = round($argu["company_id"], 0);
		$location_id = round($argu["location_id"], 0);
		$chosanbrokerid = round($argu["chosanbrokerid"], 0);
		$onlymylistings = round($argu["onlymylistings"], 0);
		
		$boat_id = round($argu["boat_id"], 0);
		$boat_make = round($argu["boat_make"], 0);
		$boat_model = $argu["boat_model"];
		$boat_year = round($argu["boat_year"], 0);
		$boat_type = round($argu["boat_type"], 0);
		
		$fr_date = $argu["fr_date"];
		$to_date = $argu["to_date"];
		
		$im_view_lead = round($argu["im_view_lead"], 0);
		$wh_print_view = round($argu["wh_print_view"]);
		$activity_report = round($argu["activity_report"]);
		$currenttime = $argu["currenttime"];
		
		//common part
		$currentyear = date("Y");
		$currentdate = date("Y-m-d");
		$filesavepointer = $searchopt;
		//end		
				
		//yearly
		if ($fsection == 1){
			
			if ($chosanbrokerid > 0){
				$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname', $chosanbrokerid);
				$fname = $broker_ar[0]["fname"];
				$lname = $broker_ar[0]["lname"];
				$brokername = $fname .' '. $lname;
				$search_text .= $brokername . ' | ';
			}
			
			if ($location_id > 0){								
				$search_text .= $yachtchildclass->get_location_office_address($location_id) . ' | ';
			}
			
			if ($boat_id > 0){
				$search_text .= $yachtclass->yacht_name($boat_id) . ' | ';
			}
			
			if ($boat_make > 0){
				$boat_make_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $boat_make);
				$search_text .= $boat_make_name . ' | ';
			}
			
			if ($boat_model != ""){
				$search_text .= $cm->filtertextdisplay($boat_model) . ' | ';
			}
			
			if ($boat_year > 0){
				$search_text .= $boat_year . ' | ';
			}
			
			if ($boat_type > 0){
				$boat_type_name = $cm->get_common_field_name('tbl_type', 'name', $boat_type);
				$search_text .= $boat_type_name . ' | ';
			}
			
			if ($fr_date == "" AND $to_date == ""){					
				$start_pointer = $currentyear - 10;
				$end_pointer = $currentyear;
				$incr = 1;
				$checktype = 1;
			}else{
				
				if ($fr_date == ""){
					$start_pointer = time() - (3600 * 24 * 365 * 10);
				}else{
					$fr_date_a = $cm->set_date_format($fr_date) . " 00:00:00";
					$start_pointer = strtotime($fr_date_a);
				}
				
				if ($to_date == ""){
					$end_pointer = time();
				}else{
					$to_date_a = $cm->set_date_format($to_date) . " 23:59:59";
					$end_pointer = strtotime($to_date_a);
				}
	
				$diff_in_day = ($end_pointer - $start_pointer) / (3600 * 24);					
				
				if ($diff_in_day <= 31){
					$incr = 3600 * 24;
					$checktype = 3;
					
					$first_m = date("m", $start_pointer);
					$second_m = date("m", $end_pointer);
					
					if ($first_m != $second_m){
						$xaxislabel = date("M", $start_pointer) . ' - ' . date("M", $end_pointer);
					}
					
				}elseif ($diff_in_day > 31 AND $diff_in_day <= 365){
					$incr = 3600 * 24 * 30;
					$checktype = 2;
				}else{
					$incr = 3600 * 24 * 365;
					$checktype = 4;
				}
				
				$search_text .= date("m/d/Y", $start_pointer) . ' - ' . date("m/d/Y", $end_pointer) . ' | ';				
			}		
			
			//Impression - View - Lead
			if ($searchopt == 1){				
				$all_total = 0;
				if ($activity_report == 1){
					$filesavepointer = $searchopt . "-" . $im_view_lead;
				}
				
				//Impression
				if ($im_view_lead == 1){
					for ($k = $start_pointer; $k <= $end_pointer; $k+=$incr){					
					
						$query_sql = "select sum(a.total_view) as total_view,";
						$query_form = " from tbl_yacht_view as a,";
						$query_where = " where";
						
						$query_form .= " tbl_yacht as b,";
						$query_where .= " a.yacht_id = b.id and";
						
						if ($loggedin_member_id == 1){
							if ($company_id > 0){
								$query_where .= " b.company_id = '". $company_id ."' and";						
							}

							if ($chosanbrokerid > 0){
								$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
							}
						}else{						
							$query_where .= " b.company_id = '". $company_id ."' and";
							if ($onlymylistings == 1){
								$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
							}
						}
										
						if ($location_id > 0){
							$query_where .= " b.location_id = '". $location_id ."' and";						
						}
						
						if ($boat_id > 0){
							$query_where .= " a.yacht_id = '". $boat_id ."' and";
						}
						
						if ($boat_make > 0){
							$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
						}
						
						if ($boat_model != ""){
							$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
						}
						
						if ($boat_year > 0){
							$query_where .= " b.year = '". $boat_year ."' and";
						}
						
						if ($boat_type > 0){
							$query_form .= " tbl_yacht_type_assign as tp,";
							$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
						}
						
						if ($checktype == 3){
							//date wise
							$query_where .= " dayofmonth(a.reg_date) = '". date("d", $k) ."'  and";
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";						
							$arraypointer = date("j", $k);
							
						}elseif ($checktype == 2){
							//month wise
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("M", $k);
						}elseif ($checktype == 4){
							//Year wise
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("Y", $k);
						}else{
							//Year wise
							$query_where .= " year(a.reg_date) = '". $k ."'  and";
							$arraypointer = $k;
						}					
						
						$query_where .= " b.status_id = 1 and";
						$query_where .= " a.view_type = 2 and";
						
						$query_sql = rtrim($query_sql, ",");
						$query_form = rtrim($query_form, ",");
						$query_where = rtrim($query_where, "and");
						
						$sql = $query_sql . $query_form . $query_where;
						$ssql = $sql;
						$result = $db->fetch_all_array($sql);
						
						$total["$arraypointer"] = 0;
						$ini_ttl = 0;
						foreach($result as $row){
							$total_view = $row['total_view'];
							$ini_ttl = $ini_ttl + $total_view;
							$all_total = $all_total + $total_view;
						}
						$total["$arraypointer"]+=$ini_ttl;					
					}
					
					$total_count_title = "Total Impressions";
					$avg_count_title = "Avg Impressions / Day";
				}
				//end
				
				//Views
				if ($im_view_lead == 2){
					for ($k = $start_pointer; $k <= $end_pointer; $k+=$incr){					
					
						$query_sql = "select sum(a.total_view) as total_view,";
						$query_form = " from tbl_yacht_view as a,";
						$query_where = " where";
						
						$query_form .= " tbl_yacht as b,";
						$query_where .= " a.yacht_id = b.id and";
						
						if ($loggedin_member_id == 1){
							if ($company_id > 0){
								$query_where .= " b.company_id = '". $company_id ."' and";						
							}

							if ($chosanbrokerid > 0){
								$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
							}
						}else{						
							$query_where .= " b.company_id = '". $company_id ."' and";
							if ($onlymylistings == 1){
								$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
							}
						}
										
						if ($location_id > 0){
							$query_where .= " b.location_id = '". $location_id ."' and";						
						}
						
						if ($boat_id > 0){
							$query_where .= " a.yacht_id = '". $boat_id ."' and";
						}
						
						if ($boat_make > 0){
							$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
						}
						
						if ($boat_model != ""){
							$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
						}
						
						if ($boat_year > 0){
							$query_where .= " b.year = '". $boat_year ."' and";
						}
						
						if ($boat_type > 0){
							$query_form .= " tbl_yacht_type_assign as tp,";
							$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
						}
						
						if ($checktype == 3){
							//date wise
							$query_where .= " dayofmonth(a.reg_date) = '". date("d", $k) ."'  and";
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";						
							$arraypointer = date("j", $k);
							
						}elseif ($checktype == 2){
							//month wise
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("M", $k);
						}elseif ($checktype == 4){
							//Year wise
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("Y", $k);
						}else{
							//Year wise
							$query_where .= " year(a.reg_date) = '". $k ."'  and";
							$arraypointer = $k;
						}					
						
						$query_where .= " b.status_id = 1 and";
						$query_where .= " a.view_type = 1 and";
						
						$query_sql = rtrim($query_sql, ",");
						$query_form = rtrim($query_form, ",");
						$query_where = rtrim($query_where, "and");
						
						$sql = $query_sql . $query_form . $query_where;
						$result = $db->fetch_all_array($sql);
						
						$total["$arraypointer"] = 0;
						$ini_ttl = 0;
						foreach($result as $row){
							$total_view = $row['total_view'];
							$ini_ttl = $ini_ttl + $total_view;
							$all_total = $all_total + $total_view;
						}
						$total["$arraypointer"]+=$ini_ttl;					
					}
					
					$total_count_title = "Total Views";
					$avg_count_title = "Avg Views / Day";
				}
				//end
				
				//Leads
				if ($im_view_lead == 3){
					for ($k = $start_pointer; $k <= $end_pointer; $k+=$incr){					
					
						$query_sql = "select count(a.id) as total_lead,";
						$query_form = " from tbl_form_lead as a,";
						$query_where = " where";
						
						$query_form .= " tbl_yacht as b,";
						$query_where .= " a.yacht_id = b.id and";
						
						if ($loggedin_member_id == 1){
							if ($company_id > 0){
								$query_where .= " b.company_id = '". $company_id ."' and";						
							}

							if ($chosanbrokerid > 0){
								$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
							}
						}else{						
							$query_where .= " b.company_id = '". $company_id ."' and";
							if ($onlymylistings == 1){
								$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
							}
						}
										
						if ($location_id > 0){
							$query_where .= " b.location_id = '". $location_id ."' and";						
						}
						
						if ($boat_id > 0){
							$query_where .= " a.yacht_id = '". $boat_id ."' and";
						}
						
						if ($boat_make > 0){
							$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
						}
						
						if ($boat_model != ""){
							$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
						}
						
						if ($boat_year > 0){
							$query_where .= " b.year = '". $boat_year ."' and";
						}
						
						if ($boat_type > 0){
							$query_form .= " tbl_yacht_type_assign as tp,";
							$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
						}
						
						if ($checktype == 3){
							//date wise
							$query_where .= " dayofmonth(a.reg_date) = '". date("d", $k) ."'  and";
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";						
							$arraypointer = date("j", $k);
							
						}elseif ($checktype == 2){
							//month wise
							$query_where .= " month(a.reg_date) = '". date("m", $k) ."'  and";
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("M", $k);
						}elseif ($checktype == 4){
							//Year wise
							$query_where .= " year(a.reg_date) = '". date("Y", $k) ."'  and";
							$arraypointer = date("Y", $k);
						}else{
							//Year wise
							$query_where .= " year(a.reg_date) = '". $k ."'  and";
							$arraypointer = $k;
						}					
						
						$query_where .= " b.status_id = 1 and";
						
						$query_sql = rtrim($query_sql, ",");
						$query_form = rtrim($query_form, ",");
						$query_where = rtrim($query_where, "and");
						
						$sql = $query_sql . $query_form . $query_where;
						$result = $db->fetch_all_array($sql);
						
						$total["$arraypointer"] = 0;
						$ini_ttl = 0;
						foreach($result as $row){
							$total_lead = $row['total_lead'];
							$ini_ttl = $ini_ttl + $total_lead;
							$all_total = $all_total + $total_lead;
						}
						$total["$arraypointer"]+=$ini_ttl;					
					}
					
					$total_count_title = "Total Leads";
					$avg_count_title = "Avg Leads / Day";
				}
				//end
				
				$dataarray[$company_id] = $total;
				
				$w = 1100;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ',Counter,'. $w .','. $h .',6,4,4,0,' . $xaxislabel;
				
				if ($search_text != ""){
					$search_text = 'Search Results for ' . rtrim($search_text, " | ");
				}else{
					$search_text = "All Data";
				}
				
				$avg_count = round($all_total / $diff_in_day, 2);
				
				$extra_return = array(
					"search_text" => $search_text,
					"total_count_title" => $total_count_title,
					"total_count" => $all_total,
					"avg_count_title" => $avg_count_title,
					"avg_count" => $avg_count
				);
			}
			//end	
			
			
			//Views By Boat Length
			if ($searchopt == 2){
				$total = array();
				
				$sql = "select * from tbl_length_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$total["$name"] = 0;
					
					
					$query_sql = "select sum(a.total_view) as total_view,";
					$query_form = " from tbl_yacht_view as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($from_length > 0){
						$query_where .= " c.length >= '". $from_length ."' and";
					}
					
					if ($to_length > 0){
						$query_where .= " c.length <= '". $to_length ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					$query_where .= " a.view_type = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_view = $row['total_view'];
						$ini_ttl = $ini_ttl + $total_view;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,0';
				$chartstyle = 2;
			}
			//end
			
			//Leads By Boat Length
			if ($searchopt == 3){
				$total = array();
				
				$sql = "select * from tbl_length_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$total["$name"] = 0;
					
					
					$query_sql = "select count(a.id) as total_leads,";
					$query_form = " from tbl_form_lead as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($from_length > 0){
						$query_where .= " c.length >= '". $from_length ."' and";
					}
					
					if ($to_length > 0){
						$query_where .= " c.length <= '". $to_length ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_leads = $row['total_leads'];
						$ini_ttl = $ini_ttl + $total_leads;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,0';
				$chartstyle = 2;
			}
			//end
			
			//Views By Boat Value
			if ($searchopt == 4){
				$total = array();
				
				$sql = "select * from tbl_price_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$total["$name"] = 0;
					
					
					$query_sql = "select sum(a.total_view) as total_view,";
					$query_form = " from tbl_yacht_view as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($from_price > 0){
						$query_where .= " b.price >= '". $from_price ."' and";
					}
					
					if ($to_price > 0){
						$query_where .= " b.price < '". $to_price ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					$query_where .= " a.view_type = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_view = $row['total_view'];
						$ini_ttl = $ini_ttl + $total_view;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,20';
				$chartstyle = 2;
			}
			//end
			
			//Leads By Boat Value
			if ($searchopt == 5){
				$total = array();
				
				$sql = "select * from tbl_price_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$total["$name"] = 0;
					
					
					$query_sql = "select count(a.id) as total_leads,";
					$query_form = " from tbl_form_lead as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($from_price > 0){
						$query_where .= " b.price >= '". $from_price ."' and";
					}
					
					if ($to_price > 0){
						$query_where .= " b.price < '". $to_price ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_leads = $row['total_leads'];
						$ini_ttl = $ini_ttl + $total_leads;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,20';
				$chartstyle = 2;
			}
			//end
			
			//Views By Boat Age
			if ($searchopt == 6){
				$total = array();
				
				$sql = "select * from tbl_age_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$end_age_year = $currentyear - $from_age;
					$start_age_year = $currentyear - $to_age;
					
					$total["$name"] = 0;
					
					
					$query_sql = "select sum(a.total_view) as total_view,";
					$query_form = " from tbl_yacht_view as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($start_age_year > 0){
						$query_where .= " b.year >= '". $start_age_year ."' and";
					}
					
					if ($end_age_year > 0){
						$query_where .= " b.year <= '". $end_age_year ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					$query_where .= " a.view_type = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_view = $row['total_view'];
						$ini_ttl = $ini_ttl + $total_view;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,20';
				$chartstyle = 2;
			}
			//end
			
			//Leads By Boat Age
			if ($searchopt == 7){
				$total = array();
				
				$sql = "select * from tbl_age_segments where status_id = 1 order by rank";
				$result = $db->fetch_all_array($sql);				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$end_age_year = $currentyear - $from_age;
					$start_age_year = $currentyear - $to_age;
					
					$total["$name"] = 0;
					
					
					$query_sql = "select count(a.id) as total_leads,";
					$query_form = " from tbl_form_lead as a,";
					$query_where = " where";
					
					$query_form .= " tbl_yacht as b,";
					$query_where .= " a.yacht_id = b.id and";
					
					$query_form .= " tbl_yacht_dimensions_weight as c,";
					$query_where .= " a.yacht_id = c.yacht_id and";
					
					if ($loggedin_member_id == 1){
						if ($company_id > 0){
							$query_where .= " b.company_id = '". $company_id ."' and";						
						}

						if ($chosanbrokerid > 0){
							$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";
						}
					}else{						
						$query_where .= " b.company_id = '". $company_id ."' and";
						if ($onlymylistings == 1){
							$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
						}
					}
									
					if ($location_id > 0){
						$query_where .= " b.location_id = '". $location_id ."' and";						
					}
					
					if ($boat_id > 0){
						$query_where .= " a.yacht_id = '". $boat_id ."' and";
					}
					
					if ($boat_make > 0){
						$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
					}

					if ($boat_model != ""){
						$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
					}

					if ($boat_year > 0){
						$query_where .= " b.year = '". $boat_year ."' and";
					}

					if ($boat_type > 0){
						$query_form .= " tbl_yacht_type_assign as tp,";
						$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
					}
					
					if ($fr_date != ""){
						$fr_date_a = $cm->set_date_format($fr_date);
						$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
					}
					if ($to_date != ""){
						$to_date_a = $cm->set_date_format($to_date);
						$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
					}
					
					if ($start_age_year > 0){
						$query_where .= " b.year >= '". $start_age_year ."' and";
					}
					
					if ($end_age_year > 0){
						$query_where .= " b.year <= '". $end_age_year ."' and";
					}				
					
					$query_where .= " b.status_id = 1 and";
					
					$query_sql = rtrim($query_sql, ",");
					$query_form = rtrim($query_form, ",");
					$query_where = rtrim($query_where, "and");
					
					$sql = $query_sql . $query_form . $query_where;
					$result = $db->fetch_all_array($sql);
					$ini_ttl = 0;
					foreach($result as $row){
						
						$total_leads = $row['total_leads'];
						$ini_ttl = $ini_ttl + $total_leads;
                    }
					$total["$name"]+=$ini_ttl;				
				}
				
				$dataarray[$company_id] = $total;
				
				$w = 700;
				$h = 350;
				$cfgstring = 'Graph For ' . $yr . ','. $name_length_display .'US$ M,'. $w .','. $h .',6,4,4,20';
				$chartstyle = 2;
			}
			//end
		}
		
		if ($wh_print_view == 1){
			$doc = $this->process_chart(json_encode($dataarray), json_encode($labelarray), $cfgstring, $chartstyle, $filesavepointer, $currenttime);
		}else{				
			$doc = '<img src="' . $cm->folder_for_seo .'includes/graph/?d='. urlencode(json_encode($dataarray)) .'&la='. urlencode(json_encode($labelarray)) .'&c=' . $cfgstring .'&chartstyle=' . $chartstyle .'" alt="" />';
			//$doc = '<img src="' . $this->process_chart(urlencode(json_encode($dataarray)), urlencode(json_encode($labelarray)), $cfgstring, $chartstyle) .'" alt="" />';					
		}
		
		$returnval = array(
			'doc' => $doc,
			'extra_return' => $extra_return
		);

        return json_encode($returnval);
	}
	
	//Process Chart
	public function process_chart($datastring, $labelstring, $cfgstring, $chartstyle, $filesavepointer = 0, $currenttime = 0){
		global $cm;
		if(!class_exists('pData')){
			include($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/pChart/pData.class");
		}
		
		if(!class_exists('pChart')){
			include($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/pChart/pChart.class");
		}
		
		$cfgstring = explode(',', $cfgstring);
		
		// Dataset definition 
		$DataSet = new pData;
		
		// Initialise the graph
		$mw = $cfgstring[2];
		$mh = $cfgstring[3];
		$Test = new pChart($mw,$mh);
		
		//manupulation
		$maindata_ar = json_decode($datastring);
		$counter = 1;
		foreach($maindata_ar as $key => $value){
			$data_ar = $value;
			$datayear = $key;
			$data = array();
			$datacol = array();
			foreach($data_ar as $key => $value){
				$data[] = $value;
				$datacol[] = $key;
			}
			
			$DataSet->AddPoint($data,"Serie" . $counter); 
			$DataSet->AddPoint($datacol,"Dcol" . $counter); 
			$DataSet->AddSerie("Serie" . $counter);
			$DataSet->SetSerieName($datayear,"Serie" . $counter);
			$counter++;
		}
		
		$width_sort = 75;
		$height_sort = 30;
		 
		$DataSet->SetAbsciseLabelSerie("Dcol1");
		$DataSet->SetYAxisName($cfgstring[1]);
		if ($cfgstring[8] != ""){
			$DataSet->SetXAxisName($cfgstring[8]);
			$height_sort = 55;
		}
		
		$sw = $mw - $width_sort + 35;
		$sh = $mh - $height_sort;
		
		$xaxis_pointer_degree = 0;
		
		$Test->clearShadow();
		
		//BAR GRAPH
		if ($chartstyle == 1){	
			$Test->setFontProperties($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/Fonts/tahoma.ttf",12);
			$Test->setGraphArea($width_sort,$height_sort,$sw,$sh);
			$Test->drawBackground(255,255,255); 
			$Test->drawGraphArea(255,255,255,FALSE);
			$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,50,50,50,TRUE,$xaxis_pointer_degree,0,TRUE);   
			$Test->setColorPalette(0,0,156,255);
			$Test->setColorPalette(1,121,121,121);
			
			// Draw the 0 line
			$Test->setFontProperties($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/Fonts/tahoma.ttf",6);
			$Test->drawTreshold(0,143,55,72,TRUE,TRUE);
			
			// Draw the bar graph  
			$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
			
			$Test->setFontProperties($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/Fonts/tahoma.ttf",8);
			$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),array("Serie1","Serie2"));
			
			//Draw pointer label if any
			$labeldata_ar = json_decode($labelstring);
			foreach($labeldata_ar as $key => $value){
				$seriename = $key;
				$data_ar = $value;
				foreach($data_ar as $key => $value){
					if ($value > 0){
						$displaylabel = '# ' . $value;
						$Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),$seriename,$key,$displaylabel,255,255,255);
					}
				}
			}
			
			if ($counter > 2){
				$Test->drawLegend(($mw - 200),0,$DataSet->GetDataDescription(),255,255,255);
			}
		}
		
		//PIE CHART
		if ($chartstyle == 2){
			$drawPieLegend_x = 600;
			$PieLegend_set = $cfgstring[7];
			$drawPieLegend_x = $drawPieLegend_x - $PieLegend_set;
			
			$palette_shade = round($cfgstring[9], 0);
			if ($palette_shade == 0){
				$palette_shade = 5;
			}
			
			$Test->setFontProperties($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/Fonts/tahoma.ttf",10);
			$Test->drawBackground(255,255,255); 
			$Test->drawGraphArea(255,255,255,FALSE);
			
			$Test->createColorGradientPalette(0,156,255,230,13,32,$palette_shade);
			$Test->AntialiasQuality = 0;
			
			$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),270,175,215,PIE_PERCENTAGE,FALSE,50,20,5);
			
			$Test->setFontProperties($_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "includes/graph/Fonts/tahoma.ttf",11);
			$Test->drawPieLegend($drawPieLegend_x,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
		}
		
		if ($filesavepointer > 0){
			//temp save
			$filename = "f" . session_id() . $currenttime . "_" . $filesavepointer . ".png";
			$savepath = $_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "chartimg/" . $filename;
			$Test->render($savepath);
			return $filename;
		}else{
			//direct outout
			$Test->Stroke();	
		}
	}
}
?>