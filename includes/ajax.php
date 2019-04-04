<?php
include("common.php");
$az = $_REQUEST["az"];
$az = round($az, 0);
$loggedin_member_id = $yachtclass->loggedin_member_id();

if ($az == 1){
	$iop = round($_POST["iop"], 0);
	$subsection = round($_REQUEST["subsection"], 0);
	
	if ($iop == 1){
        $frontend->go_to_login(1);
    }else{
        $yachtclass->check_backend_admin_login();
    }
	
	if ($subsection == 1){
		//yacht image delete
		$imid = $_POST["imid"];
		$imid = $cm->filtertext($imid);
	
		$yachtdet = $cm->get_table_fields('tbl_yacht_photo', 'yacht_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$fimg1 = $yachtdet->imgpath;
		$yacht_id = $yachtdet->yacht_id;
		$yachtclass->delete_yacht_image($fimg1, $yacht_id);
	
		$sql = "delete from tbl_yacht_photo where id = '".$imid."'";
		$db->mysqlquery($sql);
	
		echo 'y';
	}
	
	if ($subsection == 2){
		//yacht image sort
		$yachtclass->update_yacht_image_rank();
	}
	
	if ($subsection == 3){
		//yacht attachment delete
		$imid = $_POST["imid"];
		$yachtclass->delete_yacht_attachment_file($imid);
	}
	
	if ($subsection == 4){
		//yacht attachment sort
		$yachtclass->update_yacht_attachment_file_rank();
	}
	
	if ($subsection == 5){
		//yacht image rotate
		echo $yachtclass->rotate_boat_image();
	}
	
	if ($subsection == 6){
		//yacht image - remove original
		echo $yachtclass->remove_original_boat_image();
	}
}

if ($az == 2){
	$subsection = round($_REQUEST["subsection"], 0);
	
	if ($subsection == 2){
		//yacht attachment display
		$ms = $_POST["ms"];
		$iop = round($_POST["iop"], 0);
		$ms = round($ms, 0);
		echo $yachtclass->yacht_attachment_display_list($ms, $iop);
	}else{
		//yacht image display
		$ms = $_POST["ms"];
		$iop = round($_POST["iop"], 0);
		$ms = round($ms, 0);
		echo $yachtclass->yacht_image_display_list($ms, $iop);
	}
}

if ($az == 3){
    //Manufacturer suggest search term
    $keyterm = $_REQUEST["keyterm"];
    $opt = round($_REQUEST["opt"], 0);
	$whadd = round($_REQUEST["whadd"], 0);
    if ($opt == 1){
        //common Manufacturer
        $sql = "select id, name from tbl_manufacturer where name like '". $cm->filtertext($keyterm) ."%' order by name";
        $result = $db->fetch_all_array($sql);
        //$found = count($result);
        $returntxt = '';
        foreach($result as $row){
            $id = $row['id'];
            $name = $row['name'];
            $returntxt .= '<a class="set_term" dataholder="'. $opt .'" dataval="'. $id .'" getvl="'. $name .'" whadd="'. $whadd .'" href="javascript:void(0);">'. $name . '</a>';
        }
        $returntxt = '<div class="sclose"><a class="suggestclose" href="javascript:void(0);" title="Close"><img src="'. $cm->folder_for_seo.'images/del.png" /></a></div>' . $returntxt;
        echo $returntxt;
    }

    if ($opt == 2){
        //home page search collection
        $returntxt = '';
        $keyterm = $_POST["keyterm"];
        $i = 0;
        $collectdata_ar = array();

        //Manufacturer
        $sql = "select id, name from tbl_manufacturer where name like '". $cm->filtertext($keyterm) ."%' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $id = $row['id'];
            $name = $row['name'];

            $g_url = $cm->get_page_url($id, 'make');
            $collectdata_ar[$i]["name"] = $name;
            $collectdata_ar[$i]["url"] = $g_url;
            $collectdata_ar[$i]["section"] = 'Manufacturer';
            $collectdata_ar[$i]["id"] = $id;
            $i++;
        }

        //Model
        $sql = "select distinct model as name from tbl_yacht where model like '". $cm->filtertext($keyterm) ."%' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $name = $row['name'];

            $g_url = $cm->get_page_url($name, 'model');
            $collectdata_ar[$i]["name"] = $name;
            $collectdata_ar[$i]["url"] = $g_url;
            $collectdata_ar[$i]["section"] = 'Model';
            $collectdata_ar[$i]["id"] = 0;
            $i++;
        }

        //Year
        $sql = "select distinct year as name from tbl_yacht where year like '". $cm->filtertext($keyterm) ."%' and status_id = 1";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $name = $row['name'];

            $g_url = $cm->get_page_url($name, 'year');
            $collectdata_ar[$i]["name"] = $name;
            $collectdata_ar[$i]["url"] = $g_url;
            $collectdata_ar[$i]["section"] = 'Year';
            $collectdata_ar[$i]["id"] = 0;
            $i++;
        }

        sort($collectdata_ar);
        foreach($collectdata_ar as $row){
            $name = $row["name"];
            $g_url = $row["url"];
            $section = $row["section"];
            $imgpath = $row["imgpath"];

            $returntxt .= '
            <li>
                <div class="left"><a href="'. $g_url .'">'. $name . '</a></div>
            </li>
            ';
        }
        if ($returntxt != ''){
            $returntxt = '
            <div class="sclose"><a class="suggestclose" href="javascript:void(0);" title="Close"><img src="'. $cm->folder_for_seo.'images/del.png" /></a></div>
            <ul class="search-result">
            '. $returntxt .'
            </ul>
            ';
        }

        echo $returntxt;
    }

    if ($opt == 3){
        //common Engine Make
        $sql = "select id, name from tbl_engine_make where name like '". $cm->filtertext($keyterm) ."%' order by name";
        $result = $db->fetch_all_array($sql);
        //$found = count($result);
        $returntxt = '';
        foreach($result as $row){
            $id = $row['id'];
            $name = $row['name'];
            $returntxt .= '<a class="set_term" dataholder="'. $opt .'" dataval="'. $id .'" getvl="'. $name .'" href="javascript:void(0);">'. $name . '</a>';
        }
        $returntxt = '<div class="sclose"><a class="suggestclose" href="javascript:void(0);" title="Close"><img src="'. $cm->folder_for_seo.'images/del.png" /></a></div>' . $returntxt;
        echo $returntxt;
    }
	
	if ($opt == 4){
        //Broker Name
		$logmember = $yachtclass->loggedin_member_id();
		$company_id = $yachtclass->get_broker_company_id($logmember);
        //$sql = "select id, concat(fname, ' ', lname) as name from tbl_user having name like '". $cm->filtertext($keyterm) ."%'";
		$sql = "select id, concat(fname, ' ', lname) as name from tbl_user where";
		if ($company_id > 0){
			$sql .= " company_id = '". $company_id ."' and";
		}
		$sql .= " status_id = 2";
		$sql .= " having name like '". $cm->filtertext($keyterm) ."%'";
		$sql .= "  order by fname, lname";
        $result = $db->fetch_all_array($sql);    
        $returntxt = '';
        foreach($result as $row){
            $id = $row['id'];
            $name = $row['name'];
            $returntxt .= '<a class="set_term" dataholder="'. $opt .'" dataval="'. $id .'" getvl="'. $name .'" whadd="'. $whadd .'" href="javascript:void(0);">'. $name . '</a>';
        }
        $returntxt = '<div class="sclose"><a class="suggestclose" href="javascript:void(0);" title="Close"><img src="'. $cm->folder_for_seo.'images/del.png" /></a></div>' . $returntxt;
        echo $returntxt;
    }
	
	if ($opt == 5){
        //common Manufacturer
        $sql = "select id, name from tbl_manufacturer where name like '". $cm->filtertext($keyterm) ."%' order by name";
        $result = $db->fetch_all_array($sql);
        $suggestar = array();
        foreach($result as $row){
            $id = $row['id'];
            $name = $row['name'];
			$suggestar[] = array(
				"id" => $id,
				"value" => $name
			);
			//$suggestar[] = $name;
        }
		echo json_encode($suggestar);
    }

	if ($opt == 6){
        //Boat list
		$company_id = round($_REQUEST["company_id"], 0);
		$location_id = round($_REQUEST["location_id"], 0);
		$chosanbrokerid = round($_REQUEST["chosanbrokerid"], 0);
		$onlymylistings = round($_REQUEST["onlymylistings"], 0);
		
		$query_sql = "select a.id, a.vessel_name,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";		

		$query_where .= " a.company_id = '". $company_id ."' and";
		
		if ($location_id > 0){
			$query_where .= " a.location_id = '". $location_id ."' and";
		}
		
		if ($loggedin_member_id == 1){
			if ($chosanbrokerid > 0){
				$query_where .= " a.broker_id = '". $chosanbrokerid ."' and";						
			}
		}else{
			if ($onlymylistings == 1){
				$query_where .= " a.broker_id = '". $loggedin_member_id ."' and";
			}
		}
		
		$query_where .= " CONCAT_WS(' ', a.year, b.name, a.model) like '%". $cm->filtertext($keyterm) ."%' and";
		
		$query_form .= " tbl_manufacturer as b,";
		$query_where .= " b.id = a.manufacturer_id and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_where .= " a.status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
		
		$sql .= " order by a.year desc, b.name, a.model";
        $result = $db->fetch_all_array($sql);
        $suggestar = array();
        foreach($result as $row){
            $id = $row['id'];
            $vessel_name = $row['vessel_name'];
			
			$yacht_title = $yachtclass->yacht_name($id);
			/*if ($vessel_name != ""){
				$yacht_title .= " - " . $yacht_title;
			}*/
			$suggestar[] = array(
				"id" => $id,
				"value" => $yacht_title
			);
			//$suggestar[] = $name;
        }
		echo json_encode($suggestar);
    }
}

if ($az == 4){
    //yacht display change
    $p = round($_REQUEST["p"], 0);
    $dval = round($_REQUEST["dval"], 0);
	$compareboat = round($_REQUEST["compareboat"], 0);
	
	$sortop = round($_REQUEST["sortop"], 0);
	$orderbyop = round($_REQUEST["orderbyop"], 0);
	$to_check_val = $_REQUEST["to_check_val"];
	
	if ($dval == 0){
		$dval = 1;
	}
	
    $ajaxpagination = 0;
    if ($p > 1){
        $ajaxpagination = 1;
    }

    $qreset = round($_REQUEST["qreset"], 0);
    if ($qreset == 1){
        $_SESSION["created_sql"] = '';		
    }
	
	$param = array(
		"compareboat" => $compareboat,
		"displayoption" => $dval,
		"ajaxpagination" => $ajaxpagination,
		"dstat" => 0,
		"sortop" => $sortop,
		"orderbyop" => $orderbyop,
		"to_check_val" => $to_check_val,
		"qreset" => $qreset
	);
	echo $yachtclass->display_yacht_listing($p, $param);	
}

if ($az == 5){
    //forgot password
    $eid = $_POST["eid"];
    $yachtclass->forgot_password_check($eid);
}

if ($az == 6){
    //image delete
    $frontend->go_to_login(1);
    $targets = round($_POST["targets"], 0);
    $loggedinmemeber = $yachtclass->loggedin_member_id();
    $yachtclass->delete_images($loggedinmemeber, $targets);
}

if ($az == 7){
    //add-remove fav
    $frontend->go_to_login(1);
    $favopt = round($_POST["favopt"], 0);
    $yid = round($_POST["yid"], 0);
    $yachtclass->user_yacht_favorites($yid, $favopt);
}

if ($az == 8){
    //remove listing
    $frontend->go_to_login(1);
    $yid = round($_POST["yid"], 0);
    $yachtclass->user_yacht_delete($yid);
}

if ($az == 9){
    //remove save search
    $frontend->go_to_login(1);
    $svid = $_POST["svid"];
    echo $yachtclass->user_yacht_remove_search($svid);
}

if ($az == 10){
    //admin featured yacht add
    $yachtclass->check_backend_admin_login();
    $yid = round($_POST["yid"], 0);
    $fea_day_no = round($_POST["fea_day_no"], 0);
    $yachtclass->yacht_add_featured($yid, $fea_day_no);
    echo $yachtclass->yacht_featured_ajax_call(2);
}

if ($az == 11){
    //admin featured yacht remove
    $yachtclass->check_backend_admin_login();
    $yid = round($_POST["yid"], 0);
    $yachtclass->yacht_delete_featured($yid);
    echo $yachtclass->yacht_featured_ajax_call(1);
}

if ($az == 12){
    //broker my boat page list
    $frontend->go_to_login(1);
    $dval = 1;
    $dstat = round($_REQUEST["dstat"], 0);
	$compareboat = round($_REQUEST["compareboat"], 0);
	$sortop = round($_REQUEST["sortop"], 0);
	$orderbyop = round($_REQUEST["orderbyop"], 0);
	$to_check_val = $_REQUEST["to_check_val"];

    $ajaxpagination = 0;
    //$_SESSION["created_sql"] = '';
	
	$qreset = round($_REQUEST["qreset"], 0);
    if ($qreset == 1){
        $_SESSION["created_sql"] = '';		
    }
	
	$param = array(
		"compareboat" => $compareboat,
		"displayoption" => $dval,
		"ajaxpagination" => $ajaxpagination,
		"dstat" => $dstat,
		"sortop" => $sortop,
		"orderbyop" => $orderbyop,
		"qreset" => $qreset,
		"to_check_val" => $to_check_val
	);
	echo $yachtclass->display_yacht_listing(1, $param);
}

if ($az == 13){
    //most viewed - most list filter
    $frontend->go_to_login(1);
    $yachtclass->check_user_permission(array(1, 2, 3, 4, 5), 1);
    $fsection = round($_REQUEST["fsection"], 0);
    $mn = round($_REQUEST["mn"], 0);
    $yr = round($_REQUEST["yr"], 0);
	$nodays = round($_REQUEST["nodays"], 0);
    $p = round($_REQUEST["p"], 0);

    if ($fsection == 1){
        //view
        echo $yachtclass->most_viewed_yacht($p, $loggedin_member_id, $mn, $yr, 0, $nodays);
    }

    if ($fsection == 2){
        //leads
        echo $yachtclass->most_leads_yacht($p, $loggedin_member_id, $mn, $yr, 0, $nodays);
    }
}

if ($az == 14){
    //graph
    $frontend->go_to_login(1);
    $yid = round($_REQUEST["yid"], 0);
    $fsection = round($_REQUEST["fsection"], 0);
    $searchopt = round($_REQUEST["searchopt"], 0);
    $mn = round($_REQUEST["mn"], 0);
    $yr = round($_REQUEST["yr"], 0);
    echo $yachtclass->display_graph($fsection, $searchopt, $yid, $mn, $yr);
}

if ($az == 15){
    //search counter
}

if ($az == 16){
    //check data validation
    $fieldopt = round($_REQUEST["fieldopt"], 0);
    $selvalue = $_REQUEST["selvalue"];
    $oselvalue = $_REQUEST["oselvalue"];
    echo $yachtclass->check_field_validation($fieldopt, $selvalue, $oselvalue);
}

if ($az == 17){
    //my broker - search
    $frontend->go_to_login(1);
    $yachtclass->check_user_permission(array(1, 2, 3, 4), 1);
    $p = round($_REQUEST["p"], 0);
	$collectoption = round($_REQUEST["collectoption"], 0);
    echo $yachtclass->my_broker_list($p, $loggedin_member_id, $collectoption);
}

if ($az == 18){
    //my broker - remove
    $yachtclass->check_user_permission(array(1, 2, 3, 4), 1);
    $frontend->go_to_login(1);
    $mbid = round($_POST["mbid"], 0);
    $yachtclass->user_broker_delete($loggedin_member_id, $mbid);
}

if ($az == 19){
    //announcement list or testimonial list or blog list
	$subsection = round($_REQUEST["subsection"], 0);
	if ($subsection == 2){
		$p = round($_REQUEST["p"], 0);
		$searchfields = $_REQUEST["searchfields"];
		$searchfields = json_decode($searchfields, true);
		echo $frontend->testimonial_list($p, $searchfields);
	}elseif ($subsection == 3){
		//blog list
		$p = round($_REQUEST["p"], 0);
		$searchfields = $_REQUEST["searchfields"];
		$searchfields = json_decode($searchfields, true);
		echo $blogclass->blog_list($p, $searchfields, 1);
	}elseif ($subsection == 4){
		//corporate partnet list
		$p = round($_REQUEST["p"], 0);
		echo $frontend->corporate_partner_list($p, 1);
	}elseif ($subsection == 5){
		//event list
		$p = round($_REQUEST["p"], 0);
		$pastevent = round($_REQUEST["pastevent"], 0);
		echo $frontend->event_list_main($p, $pastevent);
	}elseif ($subsection == 6){
		//marinaberths list
		$p = round($_REQUEST["p"], 0);
		echo $frontend->marinaberths_list($p, 1);
	}else{
		$frontend->go_to_login(1);
		$yachtclass->check_user_permission(array(1, 2, 3, 4, 5), 1);
		$p = round($_REQUEST["p"], 0);
		echo $yachtclass->announcement_list($p);
	}    
}

if ($az == 20){
	//broker search - general
    $p = round($_REQUEST["p"], 0);
	$dval = round($_REQUEST["dval"], 0);
	$brokeronly = round($_REQUEST["brokeronly"], 0);
	$isdashboard = round($_REQUEST["idb"], 0);
	
	$argu = array(
		"displayoption" => $dval,
		"brokeronly" => $brokeronly,
		"isdashboard" => $isdashboard
	);
	
    echo $yachtclass->broker_search_list($p, $argu);
}

if ($az == 21){
    //admin yacht search for featured
    $yachtclass->check_backend_admin_login();
    echo $yachtclass->yacht_search_for_featured_ajax();
}

if ($az == 22){
    //location for company
	$iop = round($_POST["iop"], 0);
	$company_id = round($_REQUEST["company_id"], 0);
    if ($iop == 1){
		$frontend->go_to_login(1);
	}else{
		$yachtclass->check_backend_admin_login();
	}	
    echo $yachtclass->get_company_location_combo(0, $company_id, 1);
}

if ($az == 23){
    //broker for company-location    
	$company_id = round($_REQUEST["company_id"], 0);
	$location_id = round($_REQUEST["location_id"], 0);
	$iop = round($_POST["iop"], 0);
	$op = round($_POST["op"], 0);
	if ($iop == 1){
		$frontend->go_to_login(1);
	}else{
		$yachtclass->check_backend_admin_login();
	}
	if ($op == 1){
		echo $yachtclass->get_broker_combo_all($broker_id, $company_id, 1);
	}else{
    	echo $yachtclass->get_broker_combo($broker_id, $company_id, $location_id, 1);
	}
}

if ($az == 24){
    //location office - search
    $frontend->go_to_login(1);
    $yachtclass->check_user_permission(array(2, 3), 1);
    $p = round($_REQUEST["p"], 0);
    echo $yachtclass->my_location_list($p);
}

if ($az == 25){
    //location - remove
    $yachtclass->check_user_permission(array(2, 3), 1);
    $frontend->go_to_login(1);
    $mbid = round($_POST["mbid"], 0);
    $yachtclass->location_office_delete($loggedin_member_id, $mbid);
}

if ($az == 26){
    //resource search - general
    $p = round($_REQUEST["p"], 0);
	$ajaxpagination = 0;
    if ($p > 1){
        $ajaxpagination = 1;
    }
    echo $yachtclass->resource_search_list($p, $ajaxpagination);
}

if ($az == 27){
    //my broker - modify Preferences
	$yachtclass->check_user_permission(array(6), 1);
    $frontend->go_to_login(1);	
    echo $yachtclass->my_broker_preferences();
}

if ($az == 28){
    //my broker - remove
	$yachtclass->check_user_permission(array(6), 1);
    $frontend->go_to_login(1);	
    echo $yachtclass->my_broker_remove();
}

if ($az == 29){
    //broker - my client list
	$yachtclass->check_user_permission(array(2, 3, 4, 5));
	$frontend->go_to_login(1);
    $p = round($_REQUEST["p"], 0);
	$ajaxpagination = 0;
    if ($p > 1){
        $ajaxpagination = 1;
    }
    echo $yachtclass->my_client_list($p, $ajaxpagination);
}

if ($az == 30){
	//collect 2nd level boat type combo
	$typeid = round($_REQUEST["typeid"], 0);
	$r = $yachtclass->get_type_combo(0, $typeid, 1, 1, 1);
	$returnval[] = array(
		'doc' => $r
	);	
	echo json_encode($returnval);
}

if ($az == 31){
    //yacht video delete
    $imid = $_POST["imid"];
    $imid = $cm->filtertext($imid);
    $iop = round($_POST["iop"], 0);
    if ($iop == 1){
        $frontend->go_to_login(1);
    }else{
        $yachtclass->check_backend_admin_login();
    }

    $fimg1 = $db->total_record_count("select videopath as ttl from tbl_yacht_video where id = '". $imid ."'");
    $yachtclass->delete_yacht_video($fimg1);

    $sql = "delete from tbl_yacht_video where id = '".$imid."'";
    $db->mysqlquery($sql);

    echo 'y';
}

if ($az == 32){
    //yacht video display
    $ms = $_POST["ms"];
    $iop = round($_POST["iop"], 0);
    $ms = round($ms, 0);
    echo $yachtclass->yacht_video_display_list($ms, $iop);
}

if ($az == 33){	
	//type list based on category selected    
	$cat_id = round($_REQUEST["cat_id"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		$frontfrom = 1;
	}else{
		$yachtclass->check_backend_admin_login();
		$frontfrom = 0;
	}
    echo $yachtclass->get_type_combo_parent(0, $cat_id, 1, $frontfrom);
}

if ($az == 34){	
	//broker - leads
	$frontend->go_to_login(1);
	$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
	$inoption = round($_POST["inoption"], 0);
	
	//pagination
	if ($inoption == 1){
		$p = round($_REQUEST["p"], 0);
		$pp = round($_REQUEST["pp"], 0);
    	echo $leadclass->form_lead_list($p, $pp);
	}
	
	//delete
	if ($inoption == 2){
		$lead_id = $_REQUEST["lead_id"];
		
		$postfields = array(
			"lead_id" => $lead_id
		);
		
		echo $leadclass->lead_delete($postfields);
	}
}

if ($az == 35){
    //broker - Lead Reporting Tool
	$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
    $frontend->go_to_login(1);	
    echo $yachtclass->broker_lead_reporting_tool();
}

if ($az == 36){
    //boat - remove boat external link
	$yid = round($_POST["yid"], 0);
	$del_pointer = round($_POST["del_pointer"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
        $frontend->go_to_login(1);
		$yachtclass->can_access_yacht($yid);
    }else{
        $yachtclass->check_backend_admin_login();
    }    
    $yachtclass->delete_boat_external_link($yid, $del_pointer);
}

if ($az == 41){
    //Industry Association create		
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
        $frontend->go_to_login(1);		
    }else{
        $yachtclass->check_backend_admin_login();
    }    
	echo $yachtclass->get_industryassociation_box_details(0);    
}

if ($az == 42){
    //Industry Association - delete	
	$asso_id = $_POST["asso_id"];		
	$section = round($_POST["section"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		if ($section == 2){
			$connect_id = $loggedin_member_id;
		}else{
			$connect_id = $yachtclass->get_broker_company_id($loggedin_member_id);
		}		
        $frontend->go_to_login(1);		
    }else{
		$connect_id = round($_POST["connect_id"], 0);
        $yachtclass->check_backend_admin_login();
    }    
    $yachtclass->delete_industryassociation($asso_id, $connect_id, $section);
}

if ($az == 43){
    //Industry Association - sort			
	$section = round($_POST["section"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		if ($section == 2){
			$connect_id = $loggedin_member_id;
		}else{
			$connect_id = $yachtclass->get_broker_company_id($loggedin_member_id);
		}
        $frontend->go_to_login(1);		
    }else{
		$connect_id = round($_POST["connect_id"], 0);
        $yachtclass->check_backend_admin_login();
    }	 
    $yachtclass->industryassociation_box_sort($connect_id, $section);
}

if ($az == 44){
    //certification create		
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
        $frontend->go_to_login(1);		
    }else{
        $yachtclass->check_backend_admin_login();
    }    
	echo $yachtclass->get_certification_box_details(0);    
}

if ($az == 45){
    //certification - delete	
	$cert_id = $_POST["cert_id"];		
	$section = round($_POST["section"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		if ($section == 2){
			$connect_id = $loggedin_member_id;
		}else{
			$connect_id = $yachtclass->get_broker_company_id($loggedin_member_id);
		}		
        $frontend->go_to_login(1);		
    }else{
		$connect_id = round($_POST["connect_id"], 0);
        $yachtclass->check_backend_admin_login();
    }    
    $yachtclass->delete_certification($cert_id, $connect_id, $section, $iop);
}

if ($az == 46){
    //certification - sort			
	$section = round($_POST["section"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		if ($section == 2){
			$connect_id = $loggedin_member_id;
		}else{
			$connect_id = $yachtclass->get_broker_company_id($loggedin_member_id);
		}
        $frontend->go_to_login(1);		
    }else{
		$connect_id = round($_POST["connect_id"], 0);
        $yachtclass->check_backend_admin_login();
    }	 
    $yachtclass->certification_box_sort($connect_id, $section);
}

if ($az == 48){
	//Manufacture Model List
	$manufacturer_id = round($_POST["manufacturer_id"], 0);
	$p = round($_POST["p"], 0);
	echo $ymclass->get_manufacturer_model_list($manufacturer_id, $p);
}

if ($az == 49){
	$subsection = round($_REQUEST["subsection"], 0);
	
	if ($subsection == 2){
		//Our Team Display based on location selection
		$user_location_id = round($_REQUEST["user_location_id"], 0);
		$argu = array(
			"user_location_id" => $user_location_id,
		);
		echo $yachtchildclass->display_out_team_broker($argu);
	}else{	
		//Our Team Display Change
		$dval = round($_REQUEST["dval"], 0);
		$filteruser = round($_REQUEST["filteruser"], 0);
		if ($dval == 0) { $dval = 1; }
		$argu = array(
			"filteruser" => $filteruser,
			"default_view" => $dval,
		);
		echo $yachtchildclass->display_out_team_broker($argu);
	}
}

if ($az == 50){
	//Import MM data
	$manufacturer_id = round($_POST["manufacturer_id"], 0);
	$year = round($_POST["year"], 0);
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
		//frontend
		$frontend->go_to_login(1);
	}else{
		//backend
		$yachtclass->check_backend_admin_login();
	}
	echo $ymclass->get_manufacturer_model_list_import($manufacturer_id, $year);
}

if ($az == 51){
	//Local boat list - On manufacture page
	$p = round($_REQUEST["p"], 0);
	$searchfields = $_REQUEST["searchfields"];
	$searchfields = json_decode($searchfields, true);
	echo $makeclass->display_make_boat_profile_local($p, $searchfields, 1);
}

if ($az == 52){
	//signature script image
	$sigtext = $cm->filtertextdisplay($_POST["fullname"]);
	$pointer = round($_REQUEST["pointer"], 0);
	if ($pointer <= 0){ $pointer = 1; }
	
	$check_pointer = $pointer - 1;
	
	$font_list = $frontend->get_scripted_signature_font();
	$font_list = json_decode($font_list);
	
	$font = $font_list[$check_pointer]->fontname;
	$font_size = $font_list[$check_pointer]->fontsize;
	
	$foldername = "signatureimage";
	echo $fle->create_image_from_text($sigtext, $font, $font_size, $foldername);
}

if ($az == 53){
    //engine location combo		
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){
        $frontend->go_to_login(1);		
    }else{
        $yachtclass->check_backend_admin_login();		
    } 
	echo $yachtengineclass->get_engine_location_ajax(0);  
}

if ($az == 54){
    //engine details - delete	
	$engine_details_id = $_POST["engine_details_id"];
	$iop = round($_POST["iop"], 0);
	if ($iop == 1){				
        $frontend->go_to_login(1);		
    }else{
        $yachtclass->check_backend_admin_login();
    }    
    $yachtengineclass->delete_engine_details($engine_details_id);	
}

if ($az == 148){
	$inoption = round($_POST["inoption"], 0);
	$isdashboard = round($_POST["isdashboard"], 0);
	$companyid = round($_POST["companyid"], 0);
	$mapjs = round($_POST["mapjs"], 0);
	$postfields = array(            
		"isdashboard" => $isdashboard
	);
	
	//broker 
	if ($inoption == 1){
		echo $yachtchildclass->company_profile_broker_list($postfields);
	}
	
	//inventory 
	if ($inoption == 2){
		echo $yachtchildclass->company_profile_boat_list($postfields);
	}
	
	//locations 
	if ($inoption == 3){
		echo $yachtchildclass->display_company_location_details_with_map(array("company_id" => $companyid, "mapjs" => 1));
	}
}

if ($az == 151){
    //Custom Label Slideshow Group
	$frontend->go_to_login(1);
	$inoption = round($_POST["inoption"], 0);
	
	//add/edit group
	if ($inoption == 1){
		$group_name = $_POST["group_name"];
		$design_id = round($_POST["design_id"], 0);
		$ms = round($_REQUEST["ms"], 0);
		
		$postfields = array(
			"group_name" => $group_name,
			"design_id" => $design_id,
			"slideshow_id" => $ms
		);
		
		echo $slideshowclass->manage_boat_custom_slideshow_group($postfields);
	}
	
	//group delete
	if ($inoption == 2){
		$slideshow_id = round($_REQUEST["slideshow_id"], 0);			
		echo $slideshowclass->boat_custom_slideshow_group_delete($slideshow_id);
	}
	
	//display model assign to group
	if ($inoption == 3){
		$slideshow_id = round($_REQUEST["slideshow_id"], 0);
		echo $slideshowclass->custom_slideshow_group_boat_list_ajax_call(2, $slideshow_id);
	}
	
	//boat assign to group
	if ($inoption == 4){		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$slideshow_id = round($_REQUEST["slideshow_id"], 0);
		$slideshowclass->boat_add_custom_slideshow_group($boat_id, $slideshow_id);			
		echo $slideshowclass->custom_slideshow_group_boat_list_ajax_call(2, $slideshow_id);
	}
	
	//model assign sort
	if ($inoption == 5){		
		$slideshowclass->update_custom_slideshow_group_boat_list_rank();
	}
	
	//remove boat from group
	if ($inoption == 6){		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$slideshow_id = round($_REQUEST["slideshow_id"], 0);
		$slideshowclass->boat_remove_custom_slideshow_group($boat_id, $slideshow_id);			
		echo $slideshowclass->custom_slideshow_group_boat_list_ajax_call(1, $slideshow_id);
	}
	
	//search boat
	if ($inoption == 7){
		$slideshow_id = round($_REQUEST["slideshow_id"], 0);
		echo $slideshowclass->boat_custom_search_slideshow_ajax($slideshow_id);
	}
}

if ($az == 153){
    //Email Campaign
	$frontend->go_to_login(1);
	$inoption = round($_POST["inoption"], 0);
	
	//add/edit group
	if ($inoption == 1){
		$group_name = $_POST["group_name"];
		$template_id = round($_POST["template_id"], 0);
		$ms = round($_REQUEST["ms"], 0);
		
		$postfields = array(
			"group_name" => $group_name,
			"template_id" => $template_id,
			"campaign_id" => $ms
		);
		
		echo $emailcampaignclass->manage_email_campaign_group($postfields);
	}
	
	//group delete
	if ($inoption == 2){
		$campaign_id = round($_REQUEST["campaign_id"], 0);			
		echo $emailcampaignclass->email_campaign_group_delete($campaign_id);
	}
	
	//display model assign to group
	if ($inoption == 3){
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		echo $emailcampaignclass->email_campaign_group_boat_list_ajax_call(2, $campaign_id);
	}
	
	//boat assign to group
	if ($inoption == 4){		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		$emailcampaignclass->boat_add_email_campaign_group($boat_id, $campaign_id);			
		echo $emailcampaignclass->email_campaign_group_boat_list_ajax_call(2, $campaign_id);
	}
	
	//model assign sort
	if ($inoption == 5){		
		$emailcampaignclass->update_email_campaign_boat_list_rank();
	}
	
	//remove boat from group
	if ($inoption == 6){		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		$emailcampaignclass->boat_remove_email_campaign_group($boat_id, $campaign_id);			
		echo $emailcampaignclass->email_campaign_group_boat_list_ajax_call(1, $campaign_id);
	}
	
	//search boat
	if ($inoption == 7){
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		echo $emailcampaignclass->boat_custom_search_email_campaign_ajax($campaign_id);
	}
	
	//add notes
	if ($inoption == 8){
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		$message_val = $_POST["message_val"];
		
		$postfields = array(
			"campaign_id" => $campaign_id,
			"message_val" => $message_val
		);
		
		echo $emailcampaignclass->email_campaign_add_notes($postfields);
	}
	
	//Preview / HTML code  - email camaign
	if ($inoption == 9){
		$campaign_id = round($_REQUEST["campaign_id"], 0);
		$modedisplay = round($_REQUEST["modedisplay"], 0);
		
		$postfields = array(
			"campaign_id" => $campaign_id,
			"modedisplay" => $modedisplay
		);
		
		echo $emailcampaignclass->display_email_campaign($postfields);
	}
}

if ($az == 154){
	//Site Stat
	$frontend->go_to_login(1);
	$inoption = round($_POST["inoption"], 0);
	if ($loggedin_member_id == 1){
		$company_id = round($_REQUEST["company_id"], 0);
	}else{
		$company_id = $yachtclass->get_broker_company_id($loggedin_member_id);
	}
	
	//Update All data - based on top search
	if ($inoption == 1){
		$location_id = round($_POST["location_id"], 0);
		$chosanbrokerid = round($_POST["chosanbrokerid"], 0);
		$onlymylistings = round($_REQUEST["onlymylistings"], 0);
		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$boat_make = round($_REQUEST["boat_make"], 0);
		$boat_model = $_REQUEST["boat_model"];
		$boat_year = round($_REQUEST["boat_year"], 0);
		$boat_type = round($_REQUEST["boat_type"], 0);
		
		$fr_date = $_REQUEST["fr_date"];
		$to_date = $_REQUEST["to_date"];
		$im_view_lead = round($_REQUEST["im_view_lead"], 0);
		
		$param = array(
			"company_id" => $company_id,
			"location_id" => $location_id,
			"chosanbrokerid" => $chosanbrokerid,
			"onlymylistings" => $onlymylistings,
			"boat_id" => $boat_id,
			"boat_make" => $boat_make,
			"boat_model" => $boat_model,
			"boat_year" => $boat_year,
			"boat_type" => $boat_type,
			"fr_date" => $fr_date,
			"to_date" => $to_date,
			"im_view_lead" => $im_view_lead
		);
		$v =  $yachtchildclass->display_site_stat_top_section($param);
		$returnval = array(
            'doc' => $v
        );
		echo json_encode($returnval);
	}
	
	//Impression - view - leads graph
	if ($inoption == 2){
		$location_id = round($_POST["location_id"], 0);
		$chosanbrokerid = round($_POST["chosanbrokerid"], 0);
		$onlymylistings = round($_REQUEST["onlymylistings"], 0);
		
		$boat_id = round($_REQUEST["boat_id"], 0);
		$boat_make = round($_REQUEST["boat_make"], 0);
		$boat_model = $_REQUEST["boat_model"];
		$boat_year = round($_REQUEST["boat_year"], 0);
		$boat_type = round($_REQUEST["boat_type"], 0);
		
		$fr_date = $_REQUEST["fr_date"];
		$to_date = $_REQUEST["to_date"];
		$im_view_lead = round($_REQUEST["im_view_lead"], 0);
		
		$param = array(
			"company_id" => $company_id,
			"location_id" => $location_id,
			"chosanbrokerid" => $chosanbrokerid,
			"onlymylistings" => $onlymylistings,
			"boat_id" => $boat_id,
			"boat_make" => $boat_make,
			"boat_model" => $boat_model,
			"boat_year" => $boat_year,
			"boat_type" => $boat_type,
			"fr_date" => $fr_date,
			"to_date" => $to_date,
			"im_view_lead" => $im_view_lead
		);
		//echo $yachtchildclass->display_site_stat_top_section($param);
		echo $chartclass->display_graph(1, 1, $param);
	}	
	
	//Dashboard data box
	if ($inoption == 3){
		$boatoption = round($_POST["boatoption"], 0);
		$param = array(
			"company_id" => $company_id,
			"boatoption" => $boatoption
		);
		$v =  $yachtchildclass->display_dashboard_general_databox($param);
		$returnval = array(
            'doc' => $v
        );
		echo json_encode($returnval);
	}

	//boat list for user
	if ($inoption == 4){
		$chosanbrokerid = round($_POST["chosanbrokerid"], 0);
		$param = array(
			"company_id" => $company_id,
			"chosanbrokerid" => $chosanbrokerid,
			"azop" => 1
		);
		
		echo $yachtchildclass->get_active_boat_combo($param);
	}
}

if ($az == 156){
    //Boat Watcher
	$frontend->go_to_login(1);
	$inoption = round($_POST["inoption"], 0);
		
	//add/edit Record
	if ($inoption == 1){
		$name = $_POST["name"];
		$email_to = $_POST["email_to"];
		$schedule_days = round($_POST["schedule_days"], 0);
		$schedule_days_old = round($_POST["schedule_days_old"], 0);
		
		$makeid = round($_POST["makeid"], 0);
		$yrmin = round($_REQUEST["yrmin"], 0);
		$yrmax = round($_REQUEST["yrmax"], 0);
		$prmin = round($_REQUEST["prmin"], 0);
		$prmax = round($_REQUEST["prmax"], 0);
		$lnmin = round($_REQUEST["lnmin"], 0);
		$lnmax = round($_REQUEST["lnmax"], 0);
		
		$categoryid = round($_REQUEST["categoryid"], 0);
		$conditionid = round($_REQUEST["conditionid"], 0);
		$typeid = round($_REQUEST["typeid"], 0);
		$enginetypeid = round($_REQUEST["enginetypeid"], 0);
		$drivetypeid = round($_REQUEST["drivetypeid"], 0);
		$fueltypeid = round($_REQUEST["fueltypeid"], 0);
		$stateid = round($_REQUEST["stateid"], 0);
		$boatwatchercode = $_POST["ms"];
		
		$postfields = array(
			"boatwatchercode" => $boatwatchercode,
			"name" => $name,
			"email_to" => $email_to,
			"schedule_days" => $schedule_days,
			"schedule_days_old" => $schedule_days_old,
			
			"makeid" => $makeid,
			"yrmin" => $yrmin,
			"yrmax" => $yrmax,
			"prmin" => $prmin,
			"prmax" => $prmax,
			"lnmin" => $lnmin,
			"lnmax" => $lnmax,
			"categoryid" => $categoryid,
			"conditionid" => $conditionid,
			"typeid" => $typeid,
			"enginetypeid" => $enginetypeid,
			"drivetypeid" => $drivetypeid,
			"fueltypeid" => $fueltypeid,
			"stateid" => $stateid	
		);
		
		echo $boatwatcherclass->manage_boat_watcher($postfields);
	}
	
	//Watcher delete
	if ($inoption == 2){
		$boatwatchercode = $_REQUEST["boatwatchercode"];	
		echo $boatwatcherclass->boat_watcher_delete($boatwatchercode);
	}	
}

if ($az == 199){
	$subsection = round($_POST["subsection"], 0);

	//Instagram feed - indirect way
	if ($subsection == 2){
		$hashtag = $_POST["hashtag"];			
		$param = array(
			"hashtag" => $hashtag
		);
		echo $instagramclass->display_instagram_feed_ajax_call($param);
	}
}
?>