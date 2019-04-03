<?php
class Slideshowclass {
	//slideshow design combo
	public function get_slideshow_design_combo($design_id = 0){
		global $db;
		$returntxt = '';
		$vsql = "select id, name from tbl_boat_slideshow_design where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];
			
			$bck = '';
			if ($design_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';    
		}		
		return $returntxt;
	}
	//end
	
	//Mkt menu list
	public function get_mkt_menu_list(){
		global $db, $cm;
		
		$menu_list = array();
		$menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Custom Slideshow',
			'linkurl' => $cm->folder_for_seo . 'mkt-custom-slideshow/'
		);
		
		$menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Campaign Creator',
			'linkurl' => $cm->folder_for_seo . 'mkt-campaign-creator/'
		);
		
		return json_encode($menu_list);
	}
	//end
	
	//Display mkt menu
	public function display_mkt_menu_list($chosenmenu = array()){
		global $db, $cm, $yachtclass;	
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$usertype = $yachtclass->get_user_type($loggedin_member_id);
		
		$menu1 = round($chosenmenu["m1"], 0);
		
		$menu_list = $this->get_mkt_menu_list();
		$menu_list = json_decode($menu_list);
		
		$returntext = '<ul class="mkttlist">';
		
		$counter = 0;
		foreach($menu_list as $menu_row){
			$menu_id = $menu_row->id;
			$access_type_ar = $menu_row->type;
			$name = $menu_row->name;
			$linkurl = $menu_row->linkurl;
			
			$p_menu_class = '';
			if (in_array($usertype, $access_type_ar)) {
				if ($menu1 == $menu_id){
					$p_menu_class .= 'active ';					
				}
				$p_menu_class = rtrim($p_menu_class, " ");
				
				$returntext .= '<li><a counter="'. $counter .'" class="'. $p_menu_class .'" href="'. $linkurl .'">'. $name .'</a></li>';				
				$counter++;
			}			
		}
		
		$returntext .= '</ul>';		
		return $returntext;
	}
	//end
	
	//manage Group
	public function manage_boat_custom_slideshow_group($postfields = array()){
		global $db, $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$slideshow_id = $postfields["slideshow_id"];
		$group_name = $postfields["group_name"];
		$design_id = round($postfields["design_id"], 0);
		
		if ($slideshow_id == 0){
			//insert
			$group_code = $cm->get_unq_code("tbl_boat_slideshow", "group_code", 10);		
			$sql = "insert into tbl_boat_slideshow (broker_id, group_code) values ('". $loggedin_member_id ."', '". $cm->filtertext($group_code) ."')";
			$slideshow_id = $db->mysqlquery_ret($sql);			
		}
		
		//common update
		$slug = $cm->create_slug($group_name);
		$status_id = 1;
		$sql = "update tbl_boat_slideshow set slug = '". $cm->filtertext($slug) ."'
		, name = '". $cm->filtertext($group_name) ."'
		, design_id = '". $design_id ."'
		, status_id = '". $status_id ."'
		, m1 = '". $cm->filtertext($m1) ."'
		, m2 = '". $cm->filtertext($m2) ."'
		, m3 = '". $cm->filtertext($m3) ."' where id = '".$slideshow_id."'";
		$db->mysqlquery($sql);
		
		//display group
		$returntext = $this->display_boat_custom_slideshow_group();
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//group boat list
	public function custom_slideshow_group_boat_list($opt, $slideshow_id){
		global $db, $cm, $yachtclass;
		$returntext = '';
		$dragdropclass = '';
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		if ($opt == 2){
			//assigned list
			$dragdropclass = 'drp';
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
			
			$query_form .= " tbl_boat_slideshow_assign as b,";			
			$query_where .= " a.id = b.boat_id and b.slideshow_id = '". $slideshow_id ."' and";
						
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by b.rank";
		}else{
			//available list
			$keyterm = $_REQUEST["keyterm"];
			$statusid = round($_REQUEST["statusid"], 0);
			$prmin = round($_REQUEST["prmin"], 0);
			$prmax = round($_REQUEST["prmax"], 0);
			$lnmin = round($_REQUEST["lnmin"], 0);
			$lnmax = round($_REQUEST["lnmax"], 0);
			$yrmin = round($_REQUEST["yrmin"], 0);
			$yrmax = round($_REQUEST["yrmax"], 0);
			$conditionid = round($_REQUEST["conditionid"], 0);
			$typeid = round($_REQUEST["typeid"], 0);
			$categoryid = round($_REQUEST["categoryid"], 0);				
			$enginetypeid = round($_REQUEST["enginetypeid"], 0);
			$drivetypeid = round($_REQUEST["drivetypeid"], 0);
			$fueltypeid = round($_REQUEST["fueltypeid"], 0);
			$stateid = round($_REQUEST["stateid"], 0);
			
			$dragdropclass = 'drg';
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a";
			$query_where = " where";			

			if ($loggedin_member_id > 1){
				$query_where .= " a.broker_id = '". $loggedin_member_id ."' and";
			}
			$query_where .= " a.manufacturer_id > 0 and";
			
			if ($yrmin > 0){
				$query_where .= " a.year >= '". $yrmin ."' and";
			}
			if ($yrmax > 0){
				$query_where .= " a.year <= '". $yrmax ."' and";
			}
			
			if ($categoryid > 0){
				$query_where .= " a.category_id = '". $categoryid ."' and";
			}
			
			if ($conditionid > 0){
				$query_where .= " a.condition_id = '". $conditionid ."' and";
			}
	
			if ($prmin > 0){
				$query_where .= " a.price >= '". $prmin ."' and";
			}
			if ($prmax > 0){
				$query_where .= " a.price <= '". $prmax ."' and";
			}
			
			if ($stateid > 0){
				$query_where .= " a.state_id = '". $stateid ."' and";
			}
			
			$query_form .= " INNER JOIN tbl_yacht_dimensions_weight as c ON a.id = c.yacht_id";
			
			if ($lnmin > 0){
				$query_where .= " c.length >= '". $lnmin ."' and";
			}
			if ($lnmax > 0){
				$query_where .= " c.length <= '". $lnmax ."' and";
			}
			
			if ($typeid > 0 ){
				$searchitem["s_typeid"] = $typeid;
	
				$typesql = "";
				$type_sql = $cm->all_child_type($typeid, $typesql);
				$type_sql = $typeid . ", " . $type_sql;
				$type_sql = rtrim($type_sql, ", ");
	
				$query_form .= " INNER JOIN tbl_yacht_type_assign as d ON a.id = d.yacht_id";
				$query_where .= " d.type_id IN (". $type_sql .") and";
			}
			
			if ($enginetypeid > 0 OR $drivetypeid > 0 OR $fueltypeid > 0){
				$query_form .= " INNER JOIN tbl_yacht_engine as e ON a.id = e.yacht_id";
			}
			if ($enginetypeid > 0){
				$query_where .= "  e.engine_type_id = '". $enginetypeid . "' and";
			}
			if ($drivetypeid > 0){
				$query_where .= "  e.drive_type_id = '". $drivetypeid . "' and";
			}
			if ($fueltypeid > 0){
				$query_where .= "  e.fuel_type_id = '". $fueltypeid . "' and";
			}
			
			if ($statusid > 0){
				$query_where .= " a.status_id = '". $statusid ."' and";
			}
			$query_where .= ' a.display_upto >= CURDATE() and';
			
			if ($keyterm != ""){
                $keyterm  = str_replace(' in ', ' ', $keyterm);
                $keyterm  = str_replace(',', ' ', $keyterm);
                $s_key_ar = preg_split("/ /", $cm->filtertextdisplay($keyterm));
				
				$query_form .= " INNER JOIN tbl_yacht_keywords as sch ON a.id = sch.yacht_id";
                foreach($s_key_ar as $s_key_val){
                    $query_where.=" sch.keywords like '%".$cm->filtertext($s_key_val)."%' and";
                }
            }
			
			$query_form .= " LEFT JOIN (select boat_id from tbl_boat_slideshow_assign where slideshow_id = '". $slideshow_id ."' ) as b ON a.id = b.boat_id";
            $query_where .= " b.boat_id IS NULL and";
            $query_where .= ' a.status_id IN (1,3) and a.display_upto >= CURDATE() and';
			
			$query_form = rtrim($query_form, ",");
			$query_sql = rtrim($query_sql, ",");			
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by a.year";
		}
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			//$returntext .= '<ul class="group-boat-list">';
			foreach($result as $row){
				$boatid = $row["id"];
				$listing_no = $row["listing_no"];
				$yacht_title = $yachtclass->yacht_name($boatid);
				$imgpath = $yachtclass->get_yacht_first_image($boatid);
				$imagefolder = 'yachtimage/' . $listing_no . '/';
				
				$imgpath_d = '<img src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" border="0" />';
				$update_data = $boatid . '!#!' . $slideshow_id;
				
				 $returntext .= '
				 <div id="item-'. $update_data .'" boat_id="'. $boatid .'" slideshow_id="'. $slideshow_id .'" class="'. $dragdropclass .' boatrow">
				 	<div class="boatgroupimage">'. $imgpath_d .'</div>
					<div class="boattitle">'. $yacht_title .'</div>
					<div class="clearfix"></div>
				 </div>				
				 ';
			}
			//$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	//end
	
	//group boat list ajax call
	public function custom_slideshow_group_boat_list_ajax_call($displayopt, $slideshow_id){
        $mlistnormal = $this->custom_slideshow_group_boat_list(1, $slideshow_id);
        $mlistassign = $this->custom_slideshow_group_boat_list(2, $slideshow_id);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal,
            'mlistassign' => $mlistassign
        );
        return json_encode($returnval);
    }
	
	//Display Group
	public function display_boat_custom_slideshow_group(){
		global $db, $cm, $yachtclass;
		$returntext = '';
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$sql = "select * from tbl_boat_slideshow where broker_id = '". $loggedin_member_id ."' order by id desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$counter = 1;
			$returntext .= '
			<ul class="boatgroup">
			';
			
			foreach($result as $row){				
				$slideshow_id = $cm->filtertextdisplay($row["id"]);
				$group_name = $cm->filtertextdisplay($row["name"]);
				$design_id = $cm->filtertextdisplay($row["design_id"]);
				$group_code = $cm->filtertextdisplay($row["group_code"]);
				
				$group_view_url = $cm->get_page_url($group_code, "customboatslideshow");
				$returntext .= '
				<li id="item-'. $slideshow_id .'" class="customgroup_ind'. $counter .'">
					<div class="group-left-col">
						<div class="normal_mode normal_mode'. $counter .'">	
							<a class="viewmode" href="'. $group_view_url .'" target="_blank" title="Open Group"><img alt="Open Group" title="Open Group" src="'. $cm->folder_for_seo .'images/opengroup.png" /></a>						
							<a class="group_edit viewmode" rowval="'. $counter .'" slideshow_id="'. $slideshow_id .'" href="javascript:void(0);" title="Edit Group"><img alt="Edit Group" title="Edit Group" src="'. $cm->folder_for_seo .'images/edit.png" /></a>
							<a class="group_del viewmode" rowval="'. $counter .'" slideshow_id="'. $slideshow_id .'" href="javascript:void(0);" title="Delete Group"><img alt="Delete Group" title="Delete Group" src="'. $cm->folder_for_seo .'images/del.png" /></a>
							<a class="groupopenclose open_group" rowval="'. $counter .'" groupid="'. $slideshow_id .'" href="javascript:void(0);" title="Expand Group">open</a>
						</div>						
					</div>
					<div class="group-right-col">						
						<span class="viewmodeoption groupname groupname'. $counter .'">'. $group_name .'</span>
					</div>
					<div class="clearfix"></div>
					
					<div class="edit_mode edit_mode'. $counter .' clearfixmain com_none">						
						<ul class="form">
							<li>
								<div class="fieldlabel">Name:</div>
								<div class="fieldval"><input type="text" id="group_name'. $counter .'" name="group_name'. $counter .'" value="'. $group_name .'" class="input" /></div>
							</li>
							<li>
								<div class="fieldlabel">Select Design:</div>
								<div class="fieldval"><select name="design_id'. $counter .'" id="design_id'. $counter .'" class="select">
								'. $this->get_slideshow_design_combo($design_id) .'
								</select></div>
							</li>
							<li>
								<div class="fieldlabel fieldlabelempty">&nbsp;</div>
								<div class="fieldval"><div class="inlineblock"><button rowval="'. $counter .'" slideshow_id="'. $slideshow_id .'" type="button" class="button updategroup">Update</button></div><div class="inlineblock"><a class="update_cancel" rowval="'. $counter .'" slideshow_id="'. $slideshow_id .'" manufacturer_id="'. $manufacturer_id .'" href="javascript:void(0);" title="Cancel"><img alt="Cancel" title="Cancel" src="'. $cm->folder_for_seo .'images/close-icon.png" /></a></div></div>
							</li>
						</ul>
					</div>
					
					<div class="assign_model_holder assign_model_holder'. $counter .' clearfixmain">
						<div class="drag-boxleft box_border">
							<div class="box_heading">Available Boats <span class="custom-group-boat-search"><a class="viewmode openboatsearchform" rowval="'. $counter .'" slideshow_id="'. $slideshow_id .'" href="javascript:void(0);" title="Search Available Boat"><img alt="Search Available Boat" title="Search Available Boat" src="'. $cm->folder_for_seo .'images/search.png" /></a></span></div>
							<div class="box_div app_box1 app_box1_'. $counter .'" rowval="'. $counter .'"></div>
						</div>
						
						<div class="drop-boxright box_border">
							<div class="box_heading">Assign Boats</div>
							<div class="box_div app_box2 app_box2_'. $counter .'" rowval="'. $counter .'"></div>
						</div>						
					</div>
				</li>
				';				
				$counter++;
			}
			
			$returntext .= '
			</ul>			
			';
		}else{
			$returntext = 'Group(s) yet to create.';
		}		
		return $returntext;
	}
	
	//group delete
	public function boat_custom_slideshow_group_delete($slideshow_id){
		global $db, $cm;
		
		$sql = "delete from tbl_boat_slideshow where id = '". $slideshow_id ."'";
    	$db->mysqlquery($sql);
		
		$sql = "delete from tbl_boat_slideshow_assign where slideshow_id = '". $slideshow_id ."'";
    	$db->mysqlquery($sql);
		
		//display group
		$returntext = $this->display_boat_custom_slideshow_group();
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//boat assign to group
	public function boat_add_custom_slideshow_group($boat_id, $slideshow_id){
        global $db, $cm;		
		$sql = "insert into tbl_boat_slideshow_assign (boat_id, slideshow_id, rank) values ('". $boat_id ."', '". $slideshow_id ."', 1)";
		$db->mysqlquery($sql);
    }
	//end
	
	//boat assign sort	
	public function update_custom_slideshow_group_boat_list_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $value_ar = explode("!#!", $value);
		   $boat_id = round($value_ar[0]);
		   $slideshow_id = round($value_ar[1]);
		   
		   $sql = "update tbl_boat_slideshow_assign set rank = '". $i ."' where boat_id = '". $boat_id ."' and slideshow_id = '". $slideshow_id ."'";
		   $db->mysqlquery($sql);         
		   $i++;	
	   }
	}
	//end
	
	//boat remove from group
	public function boat_remove_custom_slideshow_group($boat_id, $slideshow_id){
        global $db;
        $sql = "delete from tbl_boat_slideshow_assign where boat_id = '". $boat_id ."' and slideshow_id = '". $slideshow_id ."'";
        $db->mysqlquery($sql);
    }
	//end
	
	//boat search
	public function boat_custom_search_slideshow_ajax($slideshow_id){
		
		$mlistnormal = $this->custom_slideshow_group_boat_list(1, $slideshow_id);
        $returnval[] = array(
            'mlistnormal' => $mlistnormal
        );
        return json_encode($returnval);
    }
	//end
	
	/*----------- BOAT SLIDESHOW DISPLAY --------------*/
	//check slideshow exist
	public function get_slideshow_broker_id($slideshow_id){
		global $cm;
		$broker_id = $cm->get_common_field_name("tbl_boat_slideshow", "broker_id", $slideshow_id);

		return $broker_id;
	}
	
	public function check_slideshow_with_return($checkval, $checkopt = 0){
		global $db, $cm, $frontend;
		
		if ($checkopt == 1){
			$checkfield = 'group_code';
		}else{
			$checkfield = 'id';
		}
		
		$sql = "select * from tbl_boat_slideshow where ". $checkfield ." = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);		
		$found = count($result);

		if ($found == 0){			
			header('Location: '. $cm->sorryredirect(3));
			exit;
		}
		return $result;
	}
	
	public function get_slideshow_design($slideshow_id){
		global $cm;
		$design_id = $cm->get_common_field_name("tbl_boat_slideshow", "design_id", $slideshow_id);
		if ($design_id <= 0){ $design_id = 1; }
		return $design_id;
	}
	
	public function display_boat_image_list_custom($yacht_id, $limit = 0){
		global $db, $cm, $yachtclass;
		$returntxt = '';
		
		$sql = "select * from tbl_yacht_photo where yacht_id = '". $yacht_id ."' and imgpath != '' and status_id = 1 order by rank";
        
		if ($limit > 0){
			$sql .= " limit 0, " . $limit;
		}
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$listing_no = $yachtclass->get_yacht_no($yacht_id);
			$returntxt .= '
			<ul>
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$returntxt .= '<li><img src="'. $cm->folder_for_seo .'yachtimage/'. $listing_no .'/big/'. $imgpath .'" /></li>';
			}
			
			$returntxt .= '
			</ul>
			<div class="clearfix"></div>
			';
		}		
		return $returntxt;
	}
	
	public function display_boat_slideshow($slideshow_id){
		global $db, $cm, $yachtclass;
		$returntext = '';
		
		$group_det = $cm->get_table_fields('tbl_boat_slideshow', '*', $slideshow_id);
		$broker_id = $group_det[0]["broker_id"];
		
		$company_id = $cm->get_common_field_name("tbl_user", "company_id", $broker_id);
		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
        $cname = $company_ar[0]["cname"];
        $logo_imgpath = $company_ar[0]["logo_imgpath"];		
		if ($logo_imgpath != ""){
            $logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="">';
        }else{
			$logo_imgpath = '<img src="'. $cm->site_url .'/image/logo.png" alt="">';
		}
		
		$time_value = $cm->get_systemvar('BSLTM');
   		$time_value = round($time_value * 1000);
		if ($time_value == 0){ $time_value = 6000; }
		
		$design_id = $this->get_slideshow_design($slideshow_id);
		$template_class = "design_" . $design_id;
		
		if ($design_id == 1){
			$slider_move_text_call = 'data-cycle-prev=".boatslideshow-main .boatslidernextprev .boatprevsl" data-cycle-next=".boatslideshow-main .boatslidernextprev .boatnextsl"';
			$slider_move_text = '
			<div class="boatslidernextprev">
				<span class="boatprevsl">prev</span>
				<span class="boatnextsl">next</span>
			</div>';
		}
		
		if ($design_id == 2){
			$slider_move_text_call = 'data-cycle-pager=".boatslideshow-main .pager"';
			$slider_move_text = '<div class="pager"></div>';
		}
		
		$query_sql = "select a.*,";
		$query_form = " from tbl_yacht as a,";
		$query_where = " where";
		
		$query_form .= " tbl_boat_slideshow_assign as b,";
		$query_where .= " a.id = b.boat_id and b.slideshow_id = '". $slideshow_id ."' and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$sql .= " order by b.rank";
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$returntext .= '		
			<div class="boatslideshow-main '. $template_class .'">
				<ul class="cycle-slideshow boatslideshow-slider"
				data-cycle-slides="> li" 
				data-cycle-auto-height="calc"				
				data-cycle-fx="fade" 
				data-cycle-timeout="'. $time_value .'" 
				'. $slider_move_text_call .'
				>
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
												
				//Dimensions & Weight
				$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$ex_row = $ex_result[0];
				foreach($ex_row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Engine
				$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$ex_row = $ex_result[0];
				foreach($ex_row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}			
				
				//Collect Name
				$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
				$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
				$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
				$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
				$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);
				
				$yacht_title = $yachtclass->yacht_name($id);
				$addressfull = $yachtclass->get_yacht_address($id);
				$company_name = $cm->get_common_field_name('tbl_company', 'cname', $company_id);
				$price_display = $yachtclass->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $charter);
				
				$firstimage = $yachtclass->get_yacht_first_image($id);
				$details_url = $cm->get_page_url($id, "yacht") . "slideshow/" . $slideshow_id . "/";
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				
				if ($design_id == 1){
					$returntext .= '
					<li>
						<a href="'. $details_url .'" target="_blank" class="fill-div" title="'. $yacht_title .'"></a>
						<div class="sl_header_1">
							<div class="sl_left">'. $logo_imgpath .'</div>
							<div class="sl_right"><h1 title="'. $yacht_title .'">'. $yacht_title .'</h1></div>
							<div class="clearfix"></div>
						</div>
						
						<div class="sl_content_1">
							<div class="slide-boatimage"><img src="'. $cm->folder_for_seo.'yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></div>
							<div class="slide-boatthumb">'. $this->display_boat_image_list_custom($id, 4) .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="sl_content_1b">
							<div class="sl_content_1b_left">
								<div class="slide-boatinfo-left">
									<div class="customdivrow">
										<div class="labeltitle">Make</div>
										<div class="labelvalue">'. $manufacturer_name .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Model</div>
										<div class="labelvalue">'. $model .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Year</div>
										<div class="labelvalue">'. $year .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Length</div>
										<div class="labelvalue">'. $length .' ft</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Location</div>
										<div class="labelvalue">'. $addressfull .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Engine Make</div>
										<div class="labelvalue">'. $engine_make_name .'</div>
										<div class="clearfix"></div>
									</div>
									
									
								</div>
								<div class="slide-boatinfo-right">
									<div class="customdivrow">
										<div class="labeltitle"># Engine</div>
										<div class="labelvalue">'. $yachtclass->display_yacht_number_field($engine_no) .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Fuel Type</div>
										<div class="labelvalue">'. $fuel_type_name .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Engine Type</div>
										<div class="labelvalue">'. $engine_type_name .'</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="customdivrow">
										<div class="labeltitle">Drive Type</div>
										<div class="labelvalue">'. $drive_type_name .'</div>
										<div class="clearfix"></div>
									</div>
													
									<div class="customdivrow">
										<div class="labeltitle">Price</div>
										<div class="labelvalue customprice">'. $price_display .'</div>
										<div class="clearfix"></div>
									</div>
								
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="sl_content_1b_right">&nbsp;</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="sl_footer_1">
							<div class="sl_footer_1_left">&copy; '. date("Y") .'&nbsp;' . $cm->sitename .'</div>
							<div class="sl_footer_1_right">Powered by <a href="http://www.yachtcloser.com" target="_blank">YachtCloser</a></div>
							<div class="clearfix"></div>
						</div>
						
					</li>
					';
				}
				
				if ($design_id == 2){
				
				$returntext .= '
				<li>
					<a href="'. $details_url .'" target="_blank" class="fill-div" title="'. $yacht_title .'"></a>
					<div class="slide-boatimage">
						<div class="slide-big"><img src="'. $cm->folder_for_seo.'yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></div>
						<div class="slide-thumb">'. $this->display_boat_image_list_custom($id, 4) .'</div>
					</div>
					<div class="slide-boatinfo">
						<h1>'. $yacht_title .'</h1>
						
						<div class="customdivrow">
							<div class="labeltitle">Make</div>
							<div class="labelvalue">'. $manufacturer_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Model</div>
							<div class="labelvalue">'. $model .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Year</div>
							<div class="labelvalue">'. $year .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Length</div>
							<div class="labelvalue">'. $length .' ft</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Location</div>
							<div class="labelvalue">'. $addressfull .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Engine Make</div>
							<div class="labelvalue">'. $engine_make_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle"># Engine</div>
							<div class="labelvalue">'. $yachtclass->display_yacht_number_field($engine_no) .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Fuel Type</div>
							<div class="labelvalue">'. $fuel_type_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Engine Type</div>
							<div class="labelvalue">'. $engine_type_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Drive Type</div>
							<div class="labelvalue">'. $drive_type_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Listing Company</div>
							<div class="labelvalue">'. $company_name .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow">
							<div class="labeltitle">Price</div>
							<div class="labelvalue customprice">'. $price_display .'</div>
							<div class="clearfix"></div>
						</div>
						
						<div class="customdivrow companylogo">'. $logo_imgpath .'</div>										
					</div>
				</li>
				 ';
				}
			}
			
			$returntext .= '
				</ul>
				'. $slider_move_text .'
				<div class="clearfix"></div>	
			</div>
			';
		}
		
		return $returntext;
	}
	/*----------- BOAT SLIDESHOW DISPLAY END --------------*/
}
?>