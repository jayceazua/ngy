<?php
class Emailcampaignclass {
	//template combo
	public function get_campaign_template_combo($template_id = 0){
		global $db;
		$returntxt = '';
		$vsql = "select id, name from tbl_email_campaign_template where status_id = 1 order by rank";
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
	
	//template list
	public function get_campaign_template_list($template_id = 0, $rowval = 0){
		global $db, $cm;
		$returntxt = '<ul class="emailtemplatelist emailtemplatelist'. $rowval .'">';
		$vsql = "select id, name, boat_no from tbl_email_campaign_template where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];
			$boat_no = $vrow['boat_no'];
			
			$bck = '';
			if ($template_id == $c_id){
				$bck = ' active';	
			}
			$returntxt .= '<li class="templaterow'. $c_id .''. $bck .'"><a boat_no="'. $boat_no .'" rowval="'. $rowval .'" template_id="'. $c_id .'" class="choosetemplate" href="javascript:void(0);"><img src="'. $cm->folder_for_seo .'emailtemplate/campaign/'. $c_id .'.jpg" /></a>';    
		}
		$returntxt .= '</ul>';
			
		return $returntxt;
	}
	
	//template details
	public function get_campaign_template_details($template_id){
		global $db, $cm;
		$sql = "select * from tbl_email_campaign_template where id = '". $template_id ."'";
		$result = $db->fetch_all_array($sql);
		$result = json_encode($result);
		return $result;
	}
	
	//manage Group
	public function manage_email_campaign_group($postfields = array()){
		global $db, $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$campaign_id = $postfields["campaign_id"];
		$group_name = $postfields["group_name"];
		$template_id = round($postfields["template_id"], 0);
		
		if ($campaign_id == 0){
			//insert	
			$sql = "insert into tbl_email_campaign (broker_id) values ('". $loggedin_member_id ."')";
			$campaign_id = $db->mysqlquery_ret($sql);			
		}
		
		//common update
		$sql = "update tbl_email_campaign set template_id = '". $template_id ."'
		, name = '". $cm->filtertext($group_name) ."' where id = '".$campaign_id."'";
		$db->mysqlquery($sql);
		
		//display group
		$returntext = $this->display_email_campaign_group();
		//end
		
		/*$template_ar = json_decode($this->get_campaign_template_details($template_id));
		$boat_no = $template_ar[0]->boat_no;
		
		if ($boat_no > 1){
			$infotext = "unlimited boats";
		}else{
			$infotext = "1 boat";
		}*/
		
		$returnarray = array(
			"returntext" => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//add notes
	public function email_campaign_add_notes($postfields = array()){
		global $db, $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$campaign_id = $postfields["campaign_id"];
		$message_val = $postfields["message_val"];
		
		$sql = "update tbl_email_campaign set descriptions = '". $cm->filtertext($message_val) ."' where id = '".$campaign_id."'";
		$db->mysqlquery($sql);
	}
	
	//total boat added to campaign
	public function total_boat_campaign($campaign_id){
		global $db, $cm;
		
		$query_sql = "select count(a.id) as ttl,";
		$query_form = " from tbl_yacht as a,";
		$query_where = " where";
		
		$query_form .= " tbl_email_campaign_boat_assign as b,";			
		$query_where .= " a.id = b.boat_id and b.campaign_id = '". $campaign_id ."' and";
					
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		
		$total_boat = $db->total_record_count($sql);
		return $total_boat;
	}
	
	//group boat list
	public function email_campaign_boat_list($opt, $campaign_id){
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
			
			$query_form .= " tbl_email_campaign_boat_assign as b,";			
			$query_where .= " a.id = b.boat_id and b.campaign_id = '". $campaign_id ."' and";
						
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
			
			$query_form .= " LEFT JOIN (select boat_id from tbl_email_campaign_boat_assign where campaign_id = '". $campaign_id ."' ) as b ON a.id = b.boat_id";
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
				$update_data = $boatid . '!#!' . $campaign_id;
				
				 $returntext .= '
				 <div id="item-'. $update_data .'" boat_id="'. $boatid .'" campaign_id="'. $campaign_id .'" class="'. $dragdropclass .' boatrow">
				 	<div class="boatgroupimage">'. $imgpath_d .'</div>
					<div class="boattitle">'. $yacht_title .'</div>
					<div class="clearfix"></div>
				 </div>				
				 ';
			}
			//$returntext .= '</ul>';
		}
		
		//return $returntext;
		
		$return_ar = array(
			"returntext" => $returntext,
			"found" => $found
		);
		return json_encode($return_ar);
	}
	//end
	
	//group boat list ajax call
	public function email_campaign_group_boat_list_ajax_call($displayopt, $campaign_id){
        $mlistnormal = json_decode($this->email_campaign_boat_list(1, $campaign_id));
        $mlistassign = json_decode($this->email_campaign_boat_list(2, $campaign_id));
		$total_boat = $this->total_boat_campaign($campaign_id);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal->returntext,
            'mlistassign' => $mlistassign->returntext,
			'found' => $mlistnormal->found,
			'total_boat' => $total_boat
        );
        return json_encode($returnval);
    }
	
	//Display Group
	public function display_email_campaign_group(){
		global $db, $cm, $yachtclass;
		$sBasePath = $cm->editorbasepath;
		$returntext = '';
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$sql = "select * from tbl_email_campaign where broker_id = '". $loggedin_member_id ."' order by id desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$counter = 1;
			$returntext .= '
			<ul class="boatgroup">
			';
			
			foreach($result as $row){				
				$campaign_id = $cm->filtertextdisplay($row["id"]);
				$group_name = $cm->filtertextdisplay($row["name"]);
				$descriptions = $cm->filtertextdisplay($row["descriptions"]);
				$template_id = $cm->filtertextdisplay($row["template_id"]);
				
				$template_ar = json_decode($this->get_campaign_template_details($template_id));				
				$total_boat = $this->total_boat_campaign($campaign_id);
				$max_boat = $template_ar[0]->boat_no;
				
				if ($max_boat > 1){
					$infotext = "unlimited boats";
				}else{
					$infotext = "1 boat";
				}
				
				$editorstylepath = "";
                $editorextrastyle = "adminbodyclass text_area";
				$editor_html = $cm->display_editor("descriptions" . $counter, $sBasePath, "100%", 250, $descriptions, $editorstylepath, $editorextrastyle, "frontendbasic", 1);
								
				$returntext .= '
				<li id="item-'. $campaign_id .'" class="customgroup_ind'. $counter .'">
					<div class="group-left-col">
						<div class="normal_mode normal_mode'. $counter .'">
							<a class="group_edit viewmode" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Edit Campaign"><img alt="Edit Campaign" title="Edit Campaign" src="'. $cm->folder_for_seo .'images/edit.png" /></a>
							<a class="add_notes viewmode" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Add Notes"><img alt="Add Notes" title="Add Notes" src="'. $cm->folder_for_seo .'images/add-notes.png" /></a>
							<a class="campaign_preview viewmode" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Preview"><img alt="Preview" title="Preview" src="'. $cm->folder_for_seo .'images/item-preview.png" /></a>
							<a class="campaign_htmlcode viewmode" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="HTML Code"><img alt="HTML Code" title="HTML Code" src="'. $cm->folder_for_seo .'images/htmlcode.png" /></a>
							<a class="group_del viewmode" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Delete Campaign"><img alt="Delete Campaign" title="Delete Campaign" src="'. $cm->folder_for_seo .'images/del.png" /></a>
							<a class="groupopenclose open_group" rowval="'. $counter .'" groupid="'. $campaign_id .'" href="javascript:void(0);" title="Expand Campaign">open</a>
						</div>						
					</div>
					<div class="group-right-col">
						<span class="viewmodeoption groupname groupname'. $counter .'">'. $group_name .'</span>						
					</div>
					<div class="clearfix"></div>
					
					<div class="edit_mode edit_mode'. $counter .' clearfixmain com_none">						
						<ul class="form">
							<li>
								<div class="fieldlabel">Campaign Name:</div>
								<div class="fieldval"><input type="text" id="group_name'. $counter .'" name="group_name'. $counter .'" value="'. $group_name .'" class="input" /></div>
							</li>
							<li>
								<div class="fieldlabel">Choose Template:</div>
								<div class="fieldval">'. $this->get_campaign_template_list($template_id, $counter) .'
								<input type="hidden" value="'. $template_id .'" id="template_id'. $counter .'" name="template_id'. $counter .'" />
								<input type="hidden" value="'. $total_boat .'" id="total_boat'. $counter .'" name="total_boat'. $counter .'" />
								<input type="hidden" value="'. $max_boat .'" id="max_boat'. $counter .'" name="max_boat'. $counter .'" />
								</div>
							</li>
							<li>
								<div class="fieldlabel fieldlabelempty">&nbsp;</div>
								<div class="fieldval"><div class="inlineblock"><button rowval="'. $counter .'" campaign_id="'. $campaign_id .'" type="button" class="button addeditemailcampaign">Update</button></div><div class="inlineblock"><a class="update_cancel" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Cancel"><img alt="Cancel" title="Cancel" src="'. $cm->folder_for_seo .'images/close-icon.png" /></a></div></div>
							</li>
						</ul>
					</div>
					
					<div class="edit_mode_notes edit_mode_notes'. $counter .' clearfixmain com_none">
						<ul class="form">
							<li>'. $editor_html .'</li>
							<li>
								<div class="inlineblock"><button rowval="'. $counter .'" campaign_id="'. $campaign_id .'" type="button" class="button add_notes_campaign">Update</button></div><div class="inlineblock"><a class="update_cancel_notes" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Cancel"><img alt="Cancel" title="Cancel" src="'. $cm->folder_for_seo .'images/close-icon.png" /></a></div>
							</li>
						</ul>
					</div>
					
					<div class="campaign_preview_display campaign_preview_display'. $counter .' clearfixmain com_none"></div>
					
					<div class="campaign_htmlcode_display campaign_htmlcode_display'. $counter .' clearfixmain com_none">
						<ul class="form">
							<li><textarea name="htmlcode'. $counter .'" id="htmlcode'. $counter .'" class="comments comments2 htmlcode"></textarea></li>
							<li>
								<div class="fieldval clearfixmain">
									<div class="inlineblock"><button rowval="'. $counter .'" campaign_id="'. $campaign_id .'" type="button" class="button selecthtmlcode">Select Code</button></div>
									<div class="inlineblock"><button rowval="'. $counter .'" campaign_id="'. $campaign_id .'" type="button" class="button copyhtmlcode">Copy Code</button></div>
								</div>
							</li>
						</ul>
					</div>
					
					<div class="assign_model_holder assign_model_holder'. $counter .' clearfixmain">
						<div class="drag-boxleft box_border">
							<div class="box_heading">Available Boats( <span class="validboat'. $counter .'"></span>) <span class="custom-group-boat-search"><a class="viewmode openboatsearchform" rowval="'. $counter .'" campaign_id="'. $campaign_id .'" href="javascript:void(0);" title="Search Available Boat"><img alt="Search Available Boat" title="Search Available Boat" src="'. $cm->folder_for_seo .'images/search.png" /></a></span></div>
							<div class="box_div app_box1 app_box1_'. $counter .'" rowval="'. $counter .'"></div>
						</div>
						
						<div class="drop-boxright box_border">
							<div class="box_heading">Assign Boats (can assign <span class="canchoose">'. $infotext .'</span>)</div>
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
			$returntext = 'Campaign yet to create.';
		}		
		return $returntext;
	}
	
	//group delete
	public function email_campaign_group_delete($campaign_id){
		global $db, $cm;
		
		$sql = "delete from tbl_email_campaign where id = '". $campaign_id ."'";
    	$db->mysqlquery($sql);
		
		$sql = "delete from tbl_email_campaign_boat_assign where campaign_id = '". $campaign_id ."'";
    	$db->mysqlquery($sql);
		
		//display group
		$returntext = $this->display_email_campaign_group();
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//boat assign to group
	public function boat_add_email_campaign_group($boat_id, $campaign_id){
        global $db, $cm;		
		$sql = "insert into tbl_email_campaign_boat_assign (boat_id, campaign_id, rank) values ('". $boat_id ."', '". $campaign_id ."', 1)";
		$db->mysqlquery($sql);
    }
	//end
	
	//boat assign sort	
	public function update_email_campaign_boat_list_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $value_ar = explode("!#!", $value);
		   $boat_id = round($value_ar[0]);
		   $campaign_id = round($value_ar[1]);
		   
		   $sql = "update tbl_email_campaign_boat_assign set rank = '". $i ."' where boat_id = '". $boat_id ."' and campaign_id = '". $campaign_id ."'";
		   $db->mysqlquery($sql);         
		   $i++;	
	   }
	}
	//end
	
	//boat remove from group
	public function boat_remove_email_campaign_group($boat_id, $campaign_id){
        global $db;
        $sql = "delete from tbl_email_campaign_boat_assign where boat_id = '". $boat_id ."' and campaign_id = '". $campaign_id ."'";
        $db->mysqlquery($sql);
    }
	//end
	
	//boat search
	public function boat_custom_search_email_campaign_ajax($campaign_id){
		
		$mlistnormal = json_decode($this->email_campaign_boat_list(1, $campaign_id));
        $returnval[] = array(
            'mlistnormal' => $mlistnormal->returntext,
			'found' => $mlistnormal->found
        );
        return json_encode($returnval);
    }
	//end
	
	/*----------- CAMPAIGN DISPLAY --------------*/
	//get campaign template
	public function get_campaign_template($campaign_id){
		global $cm;
		$template_id = $cm->get_common_field_name("tbl_email_campaign", "template_id", $campaign_id);
		if ($template_id <= 0){ $template_id = 1; }
		return $template_id;
	}
	
	//responsive css default
	public function css_responsive_default(){
		$responsivedefault = '
		body,table,td,p,a,li,blockquote {
		  -webkit-text-size-adjust:none !important;
		}
		';		
		return $responsivedefault;
	}
	
	//get header footer
	public function get_campaign_common_var($template_id, $default_company_id = 0){
		global $db, $cm;
		
		$customstylecss = '';		
		$header_text = '';
		$footer_text = '';
		$footer_text_up = '';
		$copyrighttext = "&copy; ". date("Y") ." ". $cm->sitename .".";
		
		$fburl = $cm->get_systemvar("SCFBU");
		$twurl = $cm->get_systemvar("SCTWU");
		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $default_company_id);
        $cname = $company_ar[0]["cname"];
        $company_logo_imgpath = $company_ar[0]["logo_imgpath"];
		
		if ($company_logo_imgpath != ""){
            $company_logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $company_logo_imgpath .'" style="display: inline-block; border: 0px; max-width: 200px;">';
        }else{
			$company_logo_imgpath = '<img src="'. $cm->site_url .'/image/logo.png" alt="" style="display: inline-block; border: 0px; max-width: 200px;">';
		}
		
		if ($template_id == 1){
			$designpath = $cm->site_url . '/emailtemplate/design1/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#ffffff';
			$bg2 = '#ffffff';
			$bg3 = '#2bbed3';
			$bg_extra1 = '';
			$description_head_color = '';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" style="padding: 15px 0px 0px 0px; text-align: center; line-height: 0;"><a style="text-align: center;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
				</tr>
			</table>
			';
			
			$footer_text = '
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">
				<tr>
					<td width="60%" align="left" valign="middle" style="padding: 0px 5px 0px 5px; color:#000000;">'. $copyrighttext .'</td>
					
					<td width="20%" align="center" valign="middle" style="padding: 0px 5px;">&nbsp;</td>

					<td width="20%" align="right" valign="middle" style="padding: 0px 5px 0px 5px; color:#000000;"><a style="text-decoration: none; color: #000000;" href="'. $cm->site_url .'/">View Online</a></td>					
				</tr>
			</table>
			';						
		}
		
		if ($template_id == 2){
			$designpath = $cm->site_url . '/emailtemplate/design2/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#ffffff';
			$bg2 = '#ffffff';
			$bg3 = '#1a96d0';
			$bg_extra1 = '#f1f1f1';
			$description_head_color = '';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="70%" style="padding: 15px 0px; text-align: left;"><a style="text-align: left;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
					<td width="30%" valign="bottom" style="padding: 15px 0px; text-align: right;"><div style="text-align: right; padding-left: 10px;">'. date("F j, Y") .'</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" height="20" align="left" valign="top" style="border-bottom: 1px solid #94a0a9;"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
				</tr>
			</table>
			';
			
			$footer_text_up = '';
			
			$footer_text = '
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">
				<tr>
					<td width="80%" align="left" valign="middle" style="padding: 0px 5px 0px 5px; color:#fff;">'. $copyrighttext .'</td>	
					<td width="20%" align="right" valign="middle" style="padding: 0px 5px 0px 5px; color:#fff;"><a style="text-decoration: none; color: #fff;" href="'. $cm->site_url .'/">View Online</a></td>					
				</tr>
			</table>
			';						
		}		
		
		if ($template_id == 3){
			$designpath = $cm->site_url . '/emailtemplate/design3/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#bbb8b8';
			$bg2 = '#1a96d0';
			$bg3 = '#bbb8b8';
			$bg_extra1 = '';
			$description_head_color = '';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" style="padding: 15px 0px; text-align: center;"><a style="text-align: center;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
				</tr>
			</table>
			';
			
			$footer_text = '		
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">
				<tr>
					<td width="60%" align="left" valign="middle" style="padding: 0px 5px 0px 5px; color:#003767;">'. $copyrighttext .'</td>
					
					<td width="20%" align="center" valign="middle" style="padding: 0px 5px;">&nbsp;</td>

					<td width="20%" align="right" valign="middle" style="padding: 0px 5px 0px 5px; color:#003767;"><a style="text-decoration: none; color: #003767;" href="'. $cm->site_url .'/">View Online</a></td>					
				</tr>
			</table>
			';						
		}
		
		
		if ($template_id == 4){
			$designpath = $cm->site_url . '/emailtemplate/design4/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#ffffff';
			$bg2 = '#ffffff';
			$bg3 = '#2bbed3';
			$bg_extra1 = '';
			$description_head_color = '#4d4d4d';
			$description_cell_css = ' padding: 0; color: #000;';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" style="padding: 15px 0px 0px 0px; text-align: center; line-height: 0;"><a style="text-align: center;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" height="2" align="left" valign="top" style="border-bottom: 1px solid #c9c9c9;"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
				</tr>
			</table>
			';
			
			$footer_text = '		
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">
				<tr>
					<td width="60%" align="left" valign="middle" style="padding: 0px 5px 0px 5px; color:#ffffff;">'. $copyrighttext .'</td>
					
					<td width="20%" align="center" valign="middle" style="padding: 0px 5px;">&nbsp;</td>

					<td width="20%" align="right" valign="middle" style="padding: 0px 5px 0px 5px; color:#ffffff;"><a style="text-decoration: none; color: #ffffff;" href="'. $cm->site_url .'/">View Online</a></td>					
				</tr>
			</table>
			';						
		}
		
		if ($template_id == 5){
			$designpath = $cm->site_url . '/emailtemplate/design5/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#bbb8b8';
			$bg2 = '#ffffff';
			$bg3 = '#bbb8b8';
			$bg_extra1 = '';
			$description_head_color = '#4d4d4d';
			$description_cell_css = ' padding: 0; color: #000;';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" style="padding: 15px 0px; text-align: center;"><a style="text-align: center;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
				</tr>
			</table>		
			';
			
			$footer_text = '		
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">					
				<tr>
					<td width="100%" align="center" valign="middle" style="padding: 10px 5px 0px 5px; color:#434343;">'. $copyrighttext .'</td>
				</tr>
			</table>
			';						
		}
		
		if ($template_id == 6){
			$designpath = $cm->site_url . '/emailtemplate/design6/';
			
			$responsivedefault = $this->css_responsive_default();			
			$customstylecss = '';
			$bg1 = '#ffffff';
			$bg2 = '#eaecee';
			$bg3 = '#1a96d0';
			$bg_extra1 = '';
			$description_head_color = '#1a96d0';
			$description_cell_css = ' padding: 20px 0 0 0; color: #1a1a1a; border-top: 1px solid #1a96d0;';
			
			$header_text .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				<tr>
					<td width="100%" style="padding: 15px 0px; text-align: center;"><a style="text-align: center;" href="'. $cm->site_url .'/">'. $company_logo_imgpath .'</a></td>
				</tr>
			</table>		
			';
			
			$footer_text = '	
			<table class="footertable1" width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%!important;">
				<tr>
					<td width="100%" align="center" valign="middle" style="padding: 10px 5px 0px 5px; color:#fff;">'. $copyrighttext .'</td>
				</tr>
			</table>
			';						
		}
		
		$retuanar = array(
			"header_text" => $header_text,
			"footer_text" => $footer_text,
			"footer_text_up" => $footer_text_up,
			"customstylecss" => $customstylecss,
			"designpath" => $designpath,
			"bg1" => $bg1,
			"bg2" => $bg2,
			"bg3" => $bg3,
			"bg_extra1" => $bg_extra1,
			"description_head_color" => $description_head_color,
			"description_cell_css" => $description_cell_css
		);
		
		return json_encode($retuanar);		
	}
	
	//display template
	public function display_email_campaign($postfields = array()){
		global $db, $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$default_company_id = $cm->get_common_field_name("tbl_user", "company_id", $loggedin_member_id);
		
		$campaign_id = round($postfields["campaign_id"], 0);
		$modedisplay = round($postfields["modedisplay"], 0);
		if ($modedisplay != 2){ $modedisplay = 1; }
		
		//campaign main
		$sql = "select * from tbl_email_campaign where id = '". $campaign_id ."' and broker_id = '". $loggedin_member_id ."'";
		$result = $db->fetch_all_array($sql);
		$row = $result[0];
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		$campaign_name = $name;
		$campaign_descriptions = $descriptions;
		//end
		
		//template details
		$template_ar = json_decode($this->get_campaign_template_details($template_id));
		$boat_no = $template_ar[0]->boat_no;
		//end
		
		//header and footer
		$campaign_common_var = json_decode($this->get_campaign_common_var($template_id, $default_company_id));
		$header_text = $campaign_common_var->header_text;
		$footer_text = $campaign_common_var->footer_text;
		$footer_text_up = $campaign_common_var->footer_text_up;
		$customstylecss = $campaign_common_var->customstylecss;
		$designpath = $campaign_common_var->designpath;
		$bg1 = $campaign_common_var->bg1;
		$bg2 = $campaign_common_var->bg2;
		$bg3 = $campaign_common_var->bg3;
		$bg_extra1 = $campaign_common_var->bg_extra1;
		$description_head_color = $campaign_common_var->description_head_color;
		$description_cell_css = $campaign_common_var->description_cell_css;
		
		$responsivedefault = $this->css_responsive_default();
		//end
		
		//process and collect boat infor for body
		$query_sql = "select a.*,";
		$query_form = " from tbl_yacht as a,";
		$query_where = " where";
		
		$query_form .= " tbl_email_campaign_boat_assign as b,";
		$query_where .= " a.id = b.boat_id and b.campaign_id = '". $campaign_id ."' and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$sql .= " order by b.rank";
		
		if ($boat_no == 1){
			$sql .= " limit 0, 1";
		}
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		$body_text = '';
		
		if ($found > 0){
			
			$counter = 0;
			$allcounter = 0;
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
				
				//broker info				        
				$profile_url = $cm->site_url . $cm->get_page_url($broker_id, 'user');
				
				if ($broker_id == 1){
					$broker_name = $cm->sitename;
					$location_ad_ar = $yachtclass->get_location_address_array($location_id);		
					$officephone = $location_ad_ar["phone"];
					$phone = "";
					$mobile_phone = "";
					$member_image = $designpath . "logo-broker.png";
					$broker_details_button = "";
				}else{
					$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
					$broker_fname = $broker_ar[0]["fname"];
					$broker_lname = $broker_ar[0]["lname"];
					$phone = $broker_ar[0]["phone"];
					$broker_name = $broker_fname . "" . $broker_lname;					
					
					$broker_ad_ar = $yachtclass->get_broker_address_array($broker_id);		
					$officephone = $broker_ad_ar["phone"];
					$member_image = $yachtclass->get_user_image($broker_id);
					$member_image = $cm->site_url . "/userphoto/big/" . $member_image;					
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
				
				$b_ar = array(
					"boatid" => $id, 
					"makeid" => $manufacturer_id, 
					"ownboat" => $ownboat, 
					"feed_id" => $feed_id, 
					"getdet" => 0
				);
				$details_url = $yachtclass->get_boat_details_url($b_ar);
				//$details_url = $cm->get_page_url($id, "yacht");
				$fullurl = $cm->site_url . $details_url . "campaign/" . $campaign_id . "/";
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				
				if ($boat_no == 1){
					if ($campaign_descriptions == ""){ $campaign_descriptions = $overview; }
				}
				
				if ($template_id == 1){
					
					if ($broker_id > 1){
						$broker_details_button = '<div style="text-align: left; padding: 20px 0 0 0;"><a href="'. $profile_url .'" style=" background-color: #2bbed3; color: #ffffff; font-weight: bold; font-size: 15px; text-decoration: none; display: inline-block; padding: 10px;">Contact '. $broker_fname .'</a></div>';
					}
					
					if ($phone != ""){
						$mobile_phone = '<div style="text-align: left; padding: 0 0 6px 0;"><a href="tel:'. $phone .'" style="color: #fff; font-family: Arial; font-weight:normal; font-size: 18px; text-transform: capitalize; text-decoration: none;"><img src="'. $designpath .'mobile.png" style="display: inline-block; border: none; outline: none; text-decoration: none; vertical-align:middle; padding-right: 8px;">'. $phone .'</a></div>';
					}
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" height="1" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td align="center"><a href="'. $fullurl .'"><img class="boatbigimage" style="max-width: 100%; width: 100%;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></td>
						</tr>
					</table>
					';
				
					$label_css = "text-align: left; padding: 10px; color: #798fa0; font-family: Arial; font-size: 14px; line-height: 14px;";
					$field_css = "text-align: left; padding: 10px; color: #030029; font-family: Arial; font-size: 14px; line-height: 14px;";
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center"><div style="text-align: center; padding: 20px 0 10px 0; color: #2bbed3; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase; border-bottom: 1px solid #94a0a9;">Basic Information</div></td>
						</tr>
						<tr>
							<td width="100%" height="10" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="20%" align="left" valign="top"><div style="'. $label_css .'">Make</div></td>
							<td width="26%" align="left" valign="top"><div style="'. $field_css .'">'. $manufacturer_name .'</div></td>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="20%" align="left" valign="top"><div style="'. $label_css .'">Engine Make</div></td>
							<td width="26%" align="left" valign="top"><div style="'. $field_css .'">'. $engine_make_name .'</div></td>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Model</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $model .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'"># Engine</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $yachtclass->display_yacht_number_field($engine_no) .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Year</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $year .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Fuel Type</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $fuel_type_name .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Length</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $length .' ft</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Drive Type</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $drive_type_name .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Location</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $addressfull .' ft</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Price</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $price_display .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" height="10" align="left" valign="top" style="border-bottom: 1px solid #94a0a9;"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					';
					
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center"><div style="text-align: center; padding: 20px 0 0 0; color: #2bbed3; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">'. $yacht_title .'</div></td>
						</tr>
				
						<tr>
							<td width="100%" align="left"><div style="text-align: left; padding: 0 0 10px 0; color: #000; font-family: Arial; font-weight:normal; font-size: 14px; line-height: 22px;">'. $campaign_descriptions .'</div></td>
						</tr>
						
						<tr>
							<td width="100%" align="center"><a href="'. $fullurl .'" style=" background-color: #0a1b40; color: #ffffff; font-weight: bold; font-size: 15px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 6px 10px;">Details</a></td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>				
					';
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center" style=" background-color: #0a1b40; color: #fff; padding: 20px;">
							
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
									<tr>
										<td align="left" valign="middle" width="40%">
											
											<div style="text-align: left; padding: 0 0 20px 0; color: #fff; font-family: Arial; font-weight:normal; font-size: 18px; line-height: 18px; text-transform: uppercase;">Presented by :</div>
											<div style="text-align: left; padding: 0 0 20px 0; color: #fff; font-family: Arial; font-weight:normal; font-size: 30px; line-height: 30px;">'. $broker_name .'</div>
											<div style="text-align: left; padding: 0 0 6px 0;"><a href="tel:'. $officephone .'" style="color: #fff; font-family: Arial; font-weight:normal; font-size: 18px; text-transform: capitalize; text-decoration: none;"><img src="'. $designpath .'phone.png" style="display: inline-block; border: none; outline: none; text-decoration: none; vertical-align:middle; padding-right: 8px;">'. $officephone .'</a></div>
											'. $mobile_phone .'
											'. $broker_details_button .'
										</td>
										<td align="right" valign="middle" width="60%"><img class="brokerimage" style="max-width: 200px; width: 90%;" src="'. $member_image .'" /></td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					';
				}
				
				if ($template_id == 2){
					
					if ($phone != ""){
						$mobile_phone = '
						<tr>
							<td width="100%" align="center"><div style="text-align: left; padding: 5px 0;"><a href="tel:'. $phone .'" style="color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 14px; text-transform: capitalize; text-decoration: none;">Phone: '. $phone .'</a></div></td>
						</tr>
						';
					}
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td align="center"><a href="'. $fullurl .'"><img class="boatbigimage" style="max-width: 100%; width: 100%;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></td>
						</tr>
					</table>
					';
					
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center"><div style="text-align: left; padding: 20px 0 0 0; color: #013769; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">'. $yacht_title .'</div></td>
						</tr>
				
						<tr>
							<td width="100%" align="left"><div style="text-align: left; padding: 0 0 10px 0; color: #000; font-family: Arial; font-weight:normal; font-size: 14px; line-height: 22px;">'. $campaign_descriptions .'</div></td>
						</tr>
						
						<tr>
							<td width="100%" align="left"><a href="'. $fullurl .'" style=" background-color: #0879dc; color: #ffffff; font-weight: bold; font-size: 15px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 10px;">Details</a></td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>				
					';
				
					$label_css = "text-align: left; padding: 5px 0; color: #1a96d0; font-family: Arial; font-size: 14px; line-height: 14px;";
					$field_css = "text-align: left; padding: 5px 0 5px 10px; color: #000000; font-family: Arial; font-size: 14px; line-height: 14px;";
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" height="10" align="left" valign="top" style="border-top: 1px solid #d1d1d1;"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td align="center" valign="top" width="48%">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
									<tr>
										<td width="100%" align="center"><div style="text-align: left; padding: 20px 0 10px 0; color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">Basic Information</div></td>
									</tr>									
								</table>
								
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
									<tr>
										<td width="45%" align="left" valign="top"><div style="'. $label_css .'">Make</div></td>
										<td width="55%" align="left" valign="top"><div style="'. $field_css .'">'. $manufacturer_name .'</div></td>
									</tr>	
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Model</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $model .'</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Year</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $year .'</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Length</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $length .' ft</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Location</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $addressfull .' ft</div></td>
									</tr>
									
									<tr>
										<td width="%" align="left" valign="top"><div style="'. $label_css .'">Engine Make</div></td>
										<td width="%" align="left" valign="top"><div style="'. $field_css .'">'. $engine_make_name .'</div></td>										
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'"># Engine</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $yachtclass->display_yacht_number_field($engine_no) .'</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Fuel Type</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $fuel_type_name .'</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Drive Type</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $drive_type_name .'</div></td>
									</tr>
									
									<tr>
										<td width="" align="left" valign="top"><div style="'. $label_css .'">Price</div></td>
										<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $price_display .'</div></td>
									</tr>
								</table>
							</td>
							<td width="4%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td align="center" valign="top" width="48%">
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
									<tr>
										<td width="100%" align="center"><div style="text-align: left; padding: 20px 0 10px 0; color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">Presented by</div></td>
									</tr>
									
									<tr>
										<td width="100%" align="center"><div style="padding: 20px 0;"><img class="brokerimage" style="max-width: 206px; width: 100%;" src="'. $member_image .'" /></div></td>
									</tr>
									
									<tr>
										<td width="100%" align="center"><div style="text-align: left; padding: 20px 0 10px 0; color: #000; font-family: Arial; font-weight:bold; font-size: 14px;">'. $broker_name .'</div></td>
									</tr>
									
									<tr>
										<td width="100%" align="center"><div style="text-align: left; padding: 5px 0;"><a href="tel:'. $officephone .'" style="color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 14px; text-transform: capitalize; text-decoration: none;">Phone: '. $officephone .'</a></div></td>
									</tr>								
									
									'. $mobile_phone .'	
									
									'. $broker_details_button .'							
								</table>
							</td>
						</tr>
					</table>
					';
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">				
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					';
				}
				
				if ($template_id == 3){
					
					if ($broker_id > 1){
						$broker_details_button = '<div style="text-align: left; padding: 20px 0 0 0;"><a href="'. $profile_url .'" style=" background-color: #1a96d0; color: #ffffff; font-weight: bold; font-size: 15px; text-decoration: none; display: inline-block; padding: 10px;">Contact '. $broker_fname .'</a></div>';
					}
					
					if ($phone != ""){
						$mobile_phone = '<div style="text-align: left; padding: 0 0 6px 0;"><a href="tel:'. $phone .'" style="color: #454545; font-family: Arial; font-weight:normal; font-size: 18px; text-transform: capitalize; text-decoration: none;"><img src="'. $designpath .'mobile.png" style="display: inline-block; border: none; outline: none; text-decoration: none; vertical-align:middle; padding-right: 8px;">'. $phone .'</a></div>';
					}
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						
						<tr>
							<td width="100%" align="center"><div style="text-align: center; padding: 20px 0 0 0; color: #ffffff; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">'. $yacht_title .'</div></td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td align="center"><a href="'. $fullurl .'"><img class="boatbigimage" style="max-width: 100%; width: 100%;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></td>
						</tr>
					</table>
					';
				
					$label_css = "text-align: left; padding: 10px; color: #ffffff; font-family: Arial; font-size: 13px; line-height: 13px;";
					$field_css = "text-align: left; padding: 10px; color: #fff; font-family: Arial; font-size: 13px; line-height: 13px;";
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center"><div style="text-align: center; padding: 20px 0 10px 0; color: #ffffff; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase; border-bottom: 1px solid #91caff;">Basic Information</div></td>
						</tr>
						<tr>
							<td width="100%" height="10" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="20%" align="left" valign="top"><div style="'. $label_css .'">Make</div></td>
							<td width="26%" align="left" valign="top"><div style="'. $field_css .'">'. $manufacturer_name .'</div></td>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="20%" align="left" valign="top"><div style="'. $label_css .'">Engine Make</div></td>
							<td width="26%" align="left" valign="top"><div style="'. $field_css .'">'. $engine_make_name .'</div></td>
							<td width="2%" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Model</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $model .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'"># Engine</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $yachtclass->display_yacht_number_field($engine_no) .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Year</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $year .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Fuel Type</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $fuel_type_name .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Length</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $length .' ft</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Drive Type</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $drive_type_name .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
						
						<tr>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Location</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $addressfull .' ft</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
							<td width="" align="left" valign="top"><div style="'. $label_css .'">Price</div></td>
							<td width="" align="left" valign="top"><div style="'. $field_css .'">'. $price_display .'</div></td>
							<td width="" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" height="10" align="left" valign="top" style="border-bottom: 1px solid #91caff;"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					';
					
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
										
						<tr>
							<td width="100%" align="left"><div style="text-align: left; padding: 0 0 10px 0; color: #fff; font-family: Arial; font-weight:normal; font-size: 13px; line-height: 22px;">'. $campaign_descriptions .'</div></td>
						</tr>
						
						<tr>
							<td width="100%" align="center"><a href="'. $fullurl .'" style=" background-color: #013668; color: #ffffff; font-weight: bold; font-size: 15px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 10px;">Details</a></td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>				
					';
				
					$body_text .= '
					<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
						<tr>
							<td width="100%" align="center" style=" background-color: #ffffff; color: #454545; padding: 20px;">
							
								<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
									<tr>
										<td align="left" valign="middle" width="40%">
											
											<div style="text-align: left; padding: 0 0 20px 0; color: #454545; font-family: Arial; font-weight:normal; font-size: 18px; line-height: 18px; text-transform: uppercase;">Presented by :</div>
											<div style="text-align: left; padding: 0 0 20px 0; color: #454545; font-family: Arial; font-weight:normal; font-size: 30px; line-height: 30px;">'. $broker_name .'</div>
											<div style="text-align: left; padding: 0 0 6px 0;"><a href="tel:'. $officephone .'" style="color: #454545; font-family: Arial; font-weight:normal; font-size: 18px; text-transform: capitalize; text-decoration: none;"><img src="'. $designpath .'phone.png" style="display: inline-block; border: none; outline: none; text-decoration: none; vertical-align:middle; padding-right: 8px;">'. $officephone .'</a></div>
											'. $mobile_phone .'
											'. $broker_details_button .'
										</td>
										<td align="right" valign="middle" width="60%"><img class="brokerimage" style="max-width: 182px; width: 90%;" src="'. $member_image .'" /></td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
						</tr>
					</table>
					';
				}
				
				
				if ($template_id == 4){					
					
					$tdborder = " border: 1px solid #c9c9c9; border-left: 0;";
					if ($counter == 0){
						$tdborder = " border: 1px solid #c9c9c9;";
						$body_text_boat .= '<tr>';
					}
					
					if ($allcounter > 1){
						$tdborder .= " border-top: 0;";
					}
									
					$body_text_boat .= '		
						<td width="50%" style="padding: 10px; '. $tdborder .'">
							<div style="padding: 0; text-align: center;">
								<div style="width: 100%; line-height: 0; font-size: 0;"><a href="'. $fullurl .'"><img width="258" height="172" style="max-width: 258px; width: 100%; height: auto; display:block;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></div>
								<div style="padding: 10px 0 0 0; color: #000000; font-family: Arial; font-weight:bold; font-size: 16px;">'. $yacht_title .'</div>
								<div style="padding: 6px 0 0 0; color: #2bbed3; font-family: Arial; font-weight:bold; font-size: 18px;">'. $price_display .'</div>
								<div style="padding: 6px 0 0 0; color: #9c9c9c; font-family: Arial; font-weight:bold; font-size: 13px;">'. $addressfull .'</div>
								<div style="padding: 6px 0 0 0;"><a href="'. $fullurl .'" style=" background-color: #0a1b40; font-family: Arial; color: #fff; font-weight: bold; font-size: 11px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 5px 10px;">View Details</a></div>
							</div>
						</td>
					';							
				}
				
				if ($template_id == 5){					
					
					$tdborder = " border: 1px solid #c9c9c9; border-left: 0;";
					if ($counter == 0){
						$tdborder = " border: 1px solid #c9c9c9;";
						$body_text_boat .= '<tr>';
					}
					
					if ($allcounter > 1){
						$tdborder .= " border-top: 0;";
					}
									
					$body_text_boat .= '		
						<td width="50%" style="padding: 10px;'. $tdborder .'">
							<div style="padding: 0; text-align: center;">
								<div style="width: 100%; line-height: 0; font-size: 0;"><a href="'. $fullurl .'"><img width="258" height="172" style="max-width: 258px; width: 100%; height: auto; display:block;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></div>
								<div style="padding: 10px 0 0 0; color: #000000; font-family: Arial; font-weight:bold; font-size: 16px;">'. $yacht_title .'</div>
								<div style="padding: 6px 0 0 0; color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 18px;">'. $price_display .'</div>
								<div style="padding: 6px 0 0 0; color: #9c9c9c; font-family: Arial; font-weight:bold; font-size: 13px;">'. $addressfull .'</div>
								<div style="padding: 6px 0 0 0;"><a href="'. $fullurl .'" style=" background-color: #0879dc; font-family: Arial; color: #fff; font-weight: bold; font-size: 11px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 5px 10px;">View Details</a></div>
							</div>
						</td>
					';							
				}
				
				if ($template_id == 6){					
					
					//$tdborder = " border: 1px solid #c9c9c9; border-left: 0;";
					if ($counter == 0){
						//$tdborder = " border: 1px solid #c9c9c9;";
						$body_text_boat .= '<tr>';
					}
					
					if ($allcounter > 1){
						//$tdborder .= " border-top: 0;";
					}
					
					$tdborder = '';				
					$body_text_boat .= '		
						<td width="50%" style="padding: 10px;'. $tdborder .'">
							<div style="padding: 0; text-align: center;">
								<div style="width: 100%; line-height: 0; font-size: 0;"><a href="'. $fullurl .'"><img width="258" height="172" style="max-width: 258px; width: 100%; height: auto; display:block;" src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/bigger/'. $firstimage .'" /></a></div>
								<div style="padding: 10px 0 0 0; color: #000000; font-family: Arial; font-weight:bold; font-size: 16px;">'. $yacht_title .'</div>
								<div style="padding: 6px 0 0 0; color: #1a96d0; font-family: Arial; font-weight:bold; font-size: 18px;">'. $price_display .'</div>
								<div style="padding: 6px 0 0 0; color: #000000; font-family: Arial; font-weight:bold; font-size: 13px;">'. $addressfull .'</div>
								<div style="padding: 6px 0 0 0;"><a href="'. $fullurl .'" style=" background-color: #94a0a9; font-family: Arial; color: #fff; font-weight: bold; font-size: 11px; text-transform: uppercase; text-decoration: none; display: inline-block; padding: 5px 10px;">View Details</a></div>
							</div>
						</td>
					';							
				}
				
				$counter++;
				$allcounter++;
				if ($counter == 2){
					$counter = 0;
					$body_text_boat .= '	
					</tr>
					';
				}
			}			
			
			if ($boat_no > 1){
				
				if ($counter == 1){
					$body_text_boat .= '<td>&nbsp;</td></tr>';
				}
				
				$body_text .= '
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
					<tr>
						<td width="100%" align="center" style="padding: 20px 0 0 0"><div style="text-align: center; padding: 0; color: '. $description_head_color .'; font-family: Arial; font-weight:bold; font-size: 18px; line-height: 18px; text-transform: uppercase;">New Listings on the Market</div></td>
					</tr>
					
					<tr>
						<td width="100%" height="20" align="left" valign="top"><img src="'. $cm->site_url . '/images/sp.gif" /></td>
					</tr>
				</table>		
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
				'. $body_text_boat .'
				</table>
				';
				
				$body_text .= '
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="width:100%!important;">
								
					<tr>
						<td width="100%" align="left" style="padding: 10px 0 10px 0;"><div style="text-align: left; font-family: Arial; font-weight:normal; font-size: 14px; line-height: 22px;'. $description_cell_css .'">'. $campaign_descriptions .'</div></td>
					</tr>		
					
				</table>				
				';		
			}
			
		}else{
			$body_text = '<p>You have not select any boat yet.</p>';
		}
		//end
		
		//create html
		
		if ($footer_text_up != ""){		
			$footer_text_up = '
			<tr>
				<td align="center" style="padding: 0px 20px; background-color: '. $bg_extra1 .';">'. $footer_text_up .'</td>
			</tr>
			';
		}
		
		if ($modedisplay == 2){
			$returntext = '
			<style type="text/css">				
				/* Some resets and issue fixes */
				#outlook a{padding:0;}
				.ReadMsgBody{width:100%;} 
				.ExternalClass{width:100%;}
				ol li {margin-bottom:15px;}
				/* End reset */
				
				table { font-family: Arial, Verdana, Tahoma; font-size: 13px; font-weight: 400; }
				a { text-decoration: none; }
				img {height:auto; line-height:100%; outline:none; text-decoration:none;}
				.responsiveimg img { max-width: 100% !important; width: 100% !important;}
				img.boatbigimage { width: 100% !important; }
				img.brokerimage { width: 90% !important; max-width: 320px;  }
				
				@media only screen and (max-width: 599px) {
					'. $responsivedefault .'
					table.maintable {width: 100% !important;}
				}
				
				'. $customstylecss .'
				
			</style>

			<table width="600" class="maintable" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="center" style="padding: 0px 20px; background-color: '. $bg1 .';">'. $header_text .'</td>
				</tr>
				
				<tr>
					<td align="center" style="padding: 0px 20px; background-color: '. $bg2 .';">'. $body_text .'</td>
				</tr>
				
				'. $footer_text_up .'
				
				<tr>
					<td align="center" style="padding:10px 0; clear:both; font-size: 12px; background-color: '. $bg3 .';">'. $footer_text .'</td>
				</tr>
			</table>
	
			';

		}else{
			$returntext = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<meta name="format-detection" content="telephone=no"> 
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
			<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
			
			<title>'. $campaign_name .'</title>
			<style type="text/css">
				
				/* Some resets and issue fixes */
				#outlook a{padding:0;}
				body{ font-family: Arial, Verdana, Tahoma; font-size: 13px; font-weight: 400; width:100% !important; background-color:#fff;-webkit-text-size-adjust:none; -ms-text-size-adjust:none;margin:0 !important; padding:0 !important;}  
				table { font-family: Arial, Verdana, Tahoma; font-size: 13px; font-weight: 400; }
				.ReadMsgBody{width:100%;} 
				.ExternalClass{width:100%;}
				ol li {margin-bottom:15px;}
				/* End reset */
				
				a { text-decoration: none; }
				img {height:auto; line-height:100%; outline:none; text-decoration:none;}
				.responsiveimg img { max-width: 100% !important; width: 100% !important;}
				img.boatbigimage { width: 100% !important; }
				img.brokerimage { width: 90% !important; max-width: 320px;  }
				
				@media only screen and (max-width: 599px) {
					table.maintable {width: 100% !important;}
				}
				
				'. $customstylecss .'
				
			</style>
			</head>
			<body style="padding:0; margin:0">
				<table width="600" class="maintable" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td align="center" style="padding: 0px 20px; background-color: '. $bg1 .';">'. $header_text .'</td>
					</tr>
					
					<tr>
						<td align="center" style="padding: 0px 20px; background-color: '. $bg2 .';">'. $body_text .'</td>
					</tr>
					
					'. $footer_text_up .'
					
					<tr>
						<td align="center" style="padding:10px 0; clear:both; font-size: 12px; background-color: '. $bg3 .';">'. $footer_text .'</td>
					</tr>
				</table>
			</body>
			</html>
			';
		}
		//end
		
		return $returntext;
	}
	
	/*----------- CAMPAIGN DISPLAY END --------------*/

}
?>