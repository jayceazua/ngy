<?php
class CharterBoatclass {
	//vars
	public $destination_im_width = 1200;
	public $destination_im_height = 700;
	
	public $boat_im_width_t = 600;
    public $boat_im_height_t = 284;
	
	public $boat_im_width = 1650;
    public $boat_im_height = 780;
	//end
	
	//common functions
	public function get_cruisingarea_combo($cruisingarea_id = 0){
        global $db;
		$returntext = '';
        $sql = "select id, name from tbl_cruisingarea where status_id = 1 order by rank";
		$pdo_param = array();
        $result = $db->pdo_select($sql, $pdo_param);
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$bck = '';
			if ($cruisingarea_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_guest_combo($guest = 0){
        global $db;
		$d_ar = array(
			array(
				"id" => 1,
				"name" => "6+",
			),
			array(
				"id" => 2,
				"name" => "8+",
			),
			array(
				"id" => 3,
				"name" => "10+",
			),
			array(
				"id" => 4,
				"name" => "12",
			),
			array(
				"id" => 5,
				"name" => "12+",
			)
		);
		
		$returntext = '';
        foreach($d_ar as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$bck = '';
			if ($guest == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_cabin_combo($cabin = 0){
        global $db;
		$d_ar = array(
			array(
				"id" => 2,
				"name" => "2+",
			),
			array(
				"id" => 4,
				"name" => "4+",
			),
			array(
				"id" => 6,
				"name" => "6+",
			),
			array(
				"id" => 8,
				"name" => "8+",
			),
			array(
				"id" => 10,
				"name" => "10+",
			),
			array(
				"id" => 12,
				"name" => "12+",
			)
		);
		
		$returntext = '';
        foreach($d_ar as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$bck = '';
			if ($cabin == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_crew_combo($crew = 0){
        global $db;
		$d_ar = array(
			array(
				"id" => 5,
				"name" => "5+",
			),
			array(
				"id" => 10,
				"name" => "10+",
			),
			array(
				"id" => 15,
				"name" => "15+",
			),
			array(
				"id" => 20,
				"name" => "20+",
			),
			array(
				"id" => 25,
				"name" => "25+",
			),
			array(
				"id" => 30,
				"name" => "30+",
			)
		);
		
		$returntext = '';
        foreach($d_ar as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$bck = '';
			if ($crew == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_number_combo($numberval, $start = 0, $upto = 100, $increment = 1){
		$returntext = '';
		for ($xk = $start; $xk <= $upto; $xk+=$increment){
			$vck = '';
			if ($numberval == $xk){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $xk . '"'. $vck .'>'. $xk .'</option>';
		}
		return $returntext;
	}
	
	public function get_boatname_list($searchTxt){
		global $db, $cm;
		$sql = "select boat_name, boat_slug from tbl_boat_charter where boat_name like :searchTxt and status_id = 1 order by boat_name";
		$pdo_param = array(
			array(
				"id" => "searchTxt",
				"value" => "%". $searchTxt ."%",
				"c" => "PARAM_STR"
			)
		);
        $result = $db->pdo_select($sql, $pdo_param);
		$ar_data = array();
		foreach($result as $row){
            $boat_name = $row['boat_name'];
            $boat_slug = $row['boat_slug'];
			$boat_url = $cm->get_page_url($boat_slug, "charterboat");
			
			$ar_data[] = array(
				"boatName" => $boat_name,
				"boatUrl" => $boat_url,
			);			
        }
		echo json_encode($ar_data);
	}
	
	public function get_category_list(){
        global $db;
        $sql = "select id, name from tbl_category where status_id = 1 order by rank";
		$pdo_param = array();
        $result = $db->pdo_select($sql, $pdo_param);
		$ar_data = array();
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$ar_data[] = array(
				"id" => $c_id,
				"name" => $cname,
			);			
        }
		echo json_encode($ar_data);
    }
	
	/*-----------TENDER AND TOY------------*/
	
	//tendertoy insert/update
	public function tendertoy_insert_update(){
		global $db, $cm, $adm, $fle;
		
		//form post value
		$name = $_POST["name"];
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
		//end
		
		if ($ms == 0){			
			$sql = "select max(rank) as ttl from tbl_tendertoy";
			$pdo_param = array();
			$rank = $db->pdo_get_single_value($sql, $pdo_param) + 1;
			
			$sql = "insert into tbl_tendertoy (name) values (:name)";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				)				
			);
			$iiid = $db->pdo_query($sql, $pdo_param, 1);
			$whedit = 0;
		}else{			
			$sql = "update tbl_tendertoy set name = :name where id = :ms";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "ms",
					"value" => $ms,
					"c" => "PARAM_INT"
				)				
			);
			$db->pdo_query($sql, $pdo_param);			
			$rank = round($_POST["rank"], 0);			
			$iiid = $ms;
			$whedit = 1;
		}
		
		// common update
		$sql = "update tbl_tendertoy set status_id = :status_id
		, rank = :rank where id = :iiid";		

		$pdo_param = array(			
			array(
				"id" => "status_id",
				"value" => $status_id,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "rank",
				"value" => $rank,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "iiid",
				"value" => $iiid,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		// end	
		
		//icon file upload
		$filename = $_FILES['iconpath']['name'] ;
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
			if ($wh_ok == "y"){
				$filename_tmp = $_FILES['iconpath']['tmp_name'];
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."icon".$filename;
		
				$target_path_main = YCROOTPATH."charterboat/tendertoy/";
				$target_path = $target_path_main . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['iconpath']['tmp_name'], $target_path);
				
				$sql = "update tbl_tendertoy set iconpath = :filename1 where id = :iiid";
				$pdo_param = array(
					array(
						"id" => "filename1",
						"value" => $filename1,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "iiid",
						"value" => $iiid,
						"c" => "PARAM_INT"
					)				
				);
				$db->pdo_query($sql, $pdo_param);				
			}
		}
		//end
		
		// update the rank
		$tablenm = "tbl_tendertoy";
		$wherecls = " id != '".$iiid."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		$returnar = array(
			"ms" => $iiid,			
			"whedit" => $whedit
		);
		return json_encode($returnar);
	}
	
	//tendertoy delete
	public function delete_tendertoy($tendertoy_id){
		global $db, $fle;
		
		$sql = "select iconpath as ttl from tbl_tendertoy where id = :tendertoy_id";
		$pdo_param = array(			
			array(
				"id" => "tendertoy_id",
				"value" => $tendertoy_id,
				"c" => "PARAM_INT"
			)				
		);
		
		$fimg1 = $db->pdo_get_single_value($sql, $pdo_param);
		if ($fimg1 != ""){
			$fle->filedelete(YCROOTPATH . "charterboat/tendertoy/".$fimg1);
		}
		
		$sql = "delete from tbl_tendertoy where id = :tendertoy_id";
		$pdo_param = array(			
			array(
				"id" => "tendertoy_id",
				"value" => $tendertoy_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		
		$sql = "delete from tbl_tendertoy_boat_assign where tendertoy_id = :tendertoy_id";
		$pdo_param = array(			
			array(
				"id" => "tendertoy_id",
				"value" => $tendertoy_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	//display tendertoy list as checkbox
	public function get_all_tendertoy_checkbox($boat_id = 0){
		global $db, $cm;
		$returntxt = '';
		
		$pdo_param = array();
		$sql = "select * from tbl_tendertoy where status_id =:status_id order by rank";
		$pdo_param[] = array(
			"id" => "status_id",
			"value" => 1,
			"c" => "PARAM_INT"
		);
		$result = $db->pdo_select($sql, $pdo_param);
		$found = count($result);
		
		if ($found > 0){
			$returntxt .= '
			<div class="multiselect3col clearfixmain">
			<ul>
			';
			
			foreach($result as $row){
				$c_id = $row['id'];
				$cname = $row['name'];
				
				$bck = '';
				$wh_selected_sql = "select count(*) as ttl from tbl_tendertoy_boat_assign where boat_id = :boat_id and tendertoy_id = :tendertoy_id";
				$pdo_param2 = array(
					array(
						"id" => "boat_id",
						"value" => $boat_id,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "tendertoy_id",
						"value" => $c_id,
						"c" => "PARAM_INT"
					)				
				);
				
				$wh_selected = $db->pdo_get_single_value($wh_selected_sql, $pdo_param2);
				if ($wh_selected > 0){ 
					$bck = ' checked="checked"';	
				}
				
				$returntxt .= '
				<li><input class="checkbox" type="checkbox" value="'. $c_id .'" name="tendertoy_ids[]" '. $bck .' /> '. $cname .'</li>
				';
			}
			
			$returntxt .= '
			</ul>
			</div>
			';
		}		
		return $returntxt;
	}
	
	//assign tendertoy to boat
	public function assign_tendertoy_boat($ms){
		global $db, $cm;
		
		$pdo_param = array();
		$sql = "delete from tbl_tendertoy_boat_assign where boat_id = :ms";
		$pdo_param[] = array(
			"id" => "ms",
			"value" => $ms,
			"c" => "PARAM_INT"
		);
		$db->pdo_query($sql, $pdo_param);
		
		foreach($_POST["tendertoy_ids"] as $tendertoy_id){
			if ($tendertoy_id > 0){
				$pdo_param = array();
				$sql = "insert into tbl_tendertoy_boat_assign (boat_id, tendertoy_id) values (:ms, :tendertoy_id)";
				$pdo_param = array(
					array(
						"id" => "ms",
						"value" => $ms,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "tendertoy_id",
						"value" => $tendertoy_id,
						"c" => "PARAM_INT"
					)				
				);
				$db->pdo_query($sql, $pdo_param);
			}
		}
	}
	
	/*-----------/TENDER AND TOY------------*/
	
	/*-----------CRUISING AREA------------*/
	
	//cruisingarea insert/update
	public function cruisingarea_insert_update(){
		global $db, $cm, $adm, $fle;
		
		//form post value
		$name = $_POST["name"];
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
		//end
		
		if ($ms == 0){			
			$sql = "select max(rank) as ttl from tbl_cruisingarea";
			$pdo_param = array();
			$rank = $db->pdo_get_single_value($sql, $pdo_param) + 1;
			
			$sql = "insert into tbl_cruisingarea (name) values (:name)";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				)				
			);
			$iiid = $db->pdo_query($sql, $pdo_param, 1);
			$whedit = 0;
		}else{			
			$sql = "update tbl_cruisingarea set name = :name where id = :ms";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "ms",
					"value" => $ms,
					"c" => "PARAM_INT"
				)				
			);
			$db->pdo_query($sql, $pdo_param);			
			$rank = round($_POST["rank"], 0);			
			$iiid = $ms;
			$whedit = 1;
		}
		
		// common update
		$sql = "update tbl_cruisingarea set status_id = :status_id
		, rank = :rank where id = :iiid";		

		$pdo_param = array(			
			array(
				"id" => "status_id",
				"value" => $status_id,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "rank",
				"value" => $rank,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "iiid",
				"value" => $iiid,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		// end
		
		// update the rank
		$tablenm = "tbl_cruisingarea";
		$wherecls = " id != '".$iiid."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		$returnar = array(
			"ms" => $iiid,			
			"whedit" => $whedit
		);
		return json_encode($returnar);
	}
	
	//cruisingarea delete
	public function delete_cruisingarea($cruisingarea_id){
		global $db, $fle;
		
		$sql = "delete from tbl_cruisingarea where id = :cruisingarea_id";
		$pdo_param = array(			
			array(
				"id" => "cruisingarea_id",
				"value" => $cruisingarea_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		
		$sql = "delete from tbl_cruisingarea_boat_assign where cruisingarea_id = :cruisingarea_id";
		$pdo_param = array(			
			array(
				"id" => "cruisingarea_id",
				"value" => $cruisingarea_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	//display cruisingarea list as checkbox
	public function get_all_cruisingarea_checkbox($boat_id = 0){
		global $db, $cm;
		$returntxt = '';
		
		$pdo_param = array();
		$sql = "select * from tbl_cruisingarea where status_id =:status_id order by rank";
		$pdo_param[] = array(
			"id" => "status_id",
			"value" => 1,
			"c" => "PARAM_INT"
		);
		$result = $db->pdo_select($sql, $pdo_param);
		$found = count($result);
		
		if ($found > 0){
			$returntxt .= '
			<div class="multiselect3col clearfixmain">
			<ul>
			';
			
			foreach($result as $row){
				$c_id = $row['id'];
				$cname = $row['name'];
				
				$bck = '';
				$wh_selected_sql = "select count(*) as ttl from tbl_cruisingarea_boat_assign where boat_id = :boat_id and cruisingarea_id = :cruisingarea_id";
				$pdo_param2 = array(
					array(
						"id" => "boat_id",
						"value" => $boat_id,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "cruisingarea_id",
						"value" => $c_id,
						"c" => "PARAM_INT"
					)				
				);
				
				$wh_selected = $db->pdo_get_single_value($wh_selected_sql, $pdo_param2);
				if ($wh_selected > 0){ 
					$bck = ' checked="checked"';	
				}
				
				$returntxt .= '
				<li><input class="checkbox" type="checkbox" value="'. $c_id .'" name="cruisingarea_ids[]" '. $bck .' /> '. $cname .'</li>
				';
			}
			
			$returntxt .= '
			</ul>
			</div>
			';
		}		
		return $returntxt;
	}
	
	//assign cruisingarea to boat
	public function assign_cruisingarea_boat($ms){
		global $db, $cm;
		
		$pdo_param = array();
		$sql = "delete from tbl_cruisingarea_boat_assign where boat_id = :ms";
		$pdo_param[] = array(
			"id" => "ms",
			"value" => $ms,
			"c" => "PARAM_INT"
		);
		$db->pdo_query($sql, $pdo_param);
		
		foreach($_POST["cruisingarea_ids"] as $cruisingarea_id){
			if ($cruisingarea_id > 0){
				$pdo_param = array();
				$sql = "insert into tbl_cruisingarea_boat_assign (boat_id, cruisingarea_id) values (:ms, :cruisingarea_id)";
				$pdo_param = array(
					array(
						"id" => "ms",
						"value" => $ms,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "cruisingarea_id",
						"value" => $cruisingarea_id,
						"c" => "PARAM_INT"
					)				
				);
				$db->pdo_query($sql, $pdo_param);
			}
		}
	}
	
	/*-----------/CRUISING AREA------------*/
	
	/*-----------DESTINATION------------*/
	
	//destination insert/update
	public function destination_insert_update(){
		global $db, $cm, $adm, $fle;
		
		//form post value
		$name = $_POST["name"];
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
		//end
		
		if ($ms == 0){			
			$sql = "select max(rank) as ttl from tbl_destination";
			$pdo_param = array();
			$rank = $db->pdo_get_single_value($sql, $pdo_param) + 1;
			
			$sql = "insert into tbl_destination (name) values (:name)";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				)				
			);
			$iiid = $db->pdo_query($sql, $pdo_param, 1);
			$whedit = 0;
		}else{			
			$sql = "update tbl_destination set name = :name where id = :ms";
			$pdo_param = array(
				array(
					"id" => "name",
					"value" => $name,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "ms",
					"value" => $ms,
					"c" => "PARAM_INT"
				)				
			);
			$db->pdo_query($sql, $pdo_param);			
			$rank = round($_POST["rank"], 0);			
			$iiid = $ms;
			$whedit = 1;
		}
		
		// common update
		$sql = "update tbl_destination set status_id = :status_id
		, rank = :rank where id = :iiid";		

		$pdo_param = array(			
			array(
				"id" => "status_id",
				"value" => $status_id,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "rank",
				"value" => $rank,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "iiid",
				"value" => $iiid,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		// end
		
		//icon file upload
		$filename = $_FILES['imagepath']['name'] ;
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
			if ($wh_ok == "y"){
				$filename_tmp = $_FILES['imagepath']['tmp_name'];
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."destination".$filename;
		
				$target_path_main = YCROOTPATH."charterboat/destination/";
				$r_width = $this->destination_im_width;
				$r_height = $this->destination_im_height;
				$target_path = $target_path_main;

				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				$fle->filedelete($filename_tmp);
				
				$sql = "update tbl_destination set imagepath = :filename1 where id = :iiid";
				$pdo_param = array(
					array(
						"id" => "filename1",
						"value" => $filename1,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "iiid",
						"value" => $iiid,
						"c" => "PARAM_INT"
					)				
				);
				$db->pdo_query($sql, $pdo_param);				
			}
		}
		//end
		
		// update the rank
		$tablenm = "tbl_destination";
		$wherecls = " id != '".$iiid."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		$returnar = array(
			"ms" => $iiid,			
			"whedit" => $whedit
		);
		return json_encode($returnar);
	}
	
	//destination delete
	public function delete_destination($destination_id){
		global $db, $fle;
		
		$sql = "select imagepath as ttl from tbl_destination where id = :destination_id";
		$pdo_param = array(			
			array(
				"id" => "destination_id",
				"value" => $destination_id,
				"c" => "PARAM_INT"
			)				
		);
		
		$fimg1 = $db->pdo_get_single_value($sql, $pdo_param);
		if ($fimg1 != ""){
			$fle->filedelete(YCROOTPATH . "charterboat/destination/".$fimg1);
		}
		
		$sql = "delete from tbl_destination where id = :destination_id";
		$pdo_param = array(			
			array(
				"id" => "destination_id",
				"value" => $destination_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		
		$sql = "delete from tbl_destination_boat_assign where destination_id = :destination_id";
		$pdo_param = array(			
			array(
				"id" => "destination_id",
				"value" => $destination_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	//display destination list as checkbox
	public function get_all_destination_checkbox($boat_id = 0){
		global $db, $cm;
		$returntxt = '';
		
		$pdo_param = array();
		$sql = "select * from tbl_destination where status_id =:status_id order by rank";
		$pdo_param[] = array(
			"id" => "status_id",
			"value" => 1,
			"c" => "PARAM_INT"
		);
		$result = $db->pdo_select($sql, $pdo_param);
		$found = count($result);
		
		if ($found > 0){
			$returntxt .= '
			<div class="multiselect3col clearfixmain">
			<ul>
			';
			
			foreach($result as $row){
				$c_id = $row['id'];
				$cname = $row['name'];
				
				$bck = '';
				$wh_selected_sql = "select count(*) as ttl from tbl_destination_boat_assign where boat_id = :boat_id and destination_id = :destination_id";
				$pdo_param2 = array(
					array(
						"id" => "boat_id",
						"value" => $boat_id,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "destination_id",
						"value" => $c_id,
						"c" => "PARAM_INT"
					)				
				);
				
				$wh_selected = $db->pdo_get_single_value($wh_selected_sql, $pdo_param2);
				if ($wh_selected > 0){ 
					$bck = ' checked="checked"';	
				}
				
				$returntxt .= '
				<li><input class="checkbox" type="checkbox" value="'. $c_id .'" name="destination_ids[]" '. $bck .' /> '. $cname .'</li>
				';
			}
			
			$returntxt .= '
			</ul>
			</div>
			';
		}		
		return $returntxt;
	}
	
	//assign destination to boat
	public function assign_destination_boat($ms){
		global $db, $cm;
		
		$pdo_param = array();
		$sql = "delete from tbl_destination_boat_assign where boat_id = :ms";
		$pdo_param[] = array(
			"id" => "ms",
			"value" => $ms,
			"c" => "PARAM_INT"
		);
		$db->pdo_query($sql, $pdo_param);
		
		foreach($_POST["destination_ids"] as $destination_id){
			if ($destination_id > 0){
				$pdo_param = array();
				$sql = "insert into tbl_destination_boat_assign (boat_id, destination_id) values (:ms, :destination_id)";
				$pdo_param = array(
					array(
						"id" => "ms",
						"value" => $ms,
						"c" => "PARAM_STR"
					),
					array(
						"id" => "destination_id",
						"value" => $destination_id,
						"c" => "PARAM_INT"
					)				
				);
				$db->pdo_query($sql, $pdo_param);
			}
		}
	}
	
	/*-----------/DESTINATION------------*/
	
	/*-----------BOAT------------*/
	public function charterboat_insert_update(){
		global $db, $cm, $adm, $fle;
		
		//form post value
		$ms = round($_POST["ms"], 0);
		$p_ar = $_POST;
		foreach($p_ar AS $key => $val){
			${$key} = $val;
			if ($key != "ms"){
				if ($key == "price_perday" OR $key == "price_perweek" OR $key == "length" OR $key == "max_speed"){
					${$key} = round(${$key}, 2);
				}elseif ($key == "make_id" 
					OR $key == "category_id"
					OR $key == "guest"
					OR $key == "cabin"
					OR $key == "crew"
					OR $key == "status_id"					
				){
					${$key} = round(${$key}, 0);
				}else{
					//no format
				}
			}
		}
		//end
		
		$reg_date = date("Y-m-d H:i:s");
		if ($ms == 0){		
			$sql = "insert into tbl_boat_charter (boat_name, reg_date) values (:boat_name, :reg_date)";
			$pdo_param = array(
				array(
					"id" => "boat_name",
					"value" => $boat_name,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "reg_date",
					"value" => $reg_date,
					"c" => "PARAM_STR"
				)				
			);
			$iiid = $db->pdo_query($sql, $pdo_param, 1);
			$whedit = 0;
			
			//create folder
			$source = YCROOTPATH."charterboat/listings/defaultimage";
			$destination = YCROOTPATH."charterboat/listings/".$iiid;
			$fle->copy_folder($source, $destination);
			//end
		}else{			
			$sql = "update tbl_boat_charter set boat_name = :boat_name where id = :ms";
			$pdo_param = array(
				array(
					"id" => "boat_name",
					"value" => $boat_name,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "ms",
					"value" => $ms,
					"c" => "PARAM_INT"
				)				
			);
			$db->pdo_query($sql, $pdo_param);			
			$iiid = $ms;
			$whedit = 1;
		}
		
		$boat_slug = $cm->create_slug($boat_name);
		
		// common update
		if ($m1 == ""){
			$m1 = $boat_name;
		}
		
		$sql = "update tbl_boat_charter set boat_slug = :boat_slug
		, make_id = :make_id
		, category_id = :category_id
		, year = :year
		, guest = :guest
		, cabin = :cabin
		, crew = :crew
		, length = :length
		, max_speed = :max_speed
		, price_perday = :price_perday
		, price_perweek = :price_perweek
		, subtitle = :subtitle
		, description = :description
		, status_id = :status_id
		, m1 = :m1
		, m2 = :m2
		, m3 = :m3 where id = :iiid";		

		$pdo_param = array(			
			array(
				"id" => "boat_slug",
				"value" => $boat_slug,
				"c" => "PARAM_STR"
			),			
			array(
				"id" => "make_id",
				"value" => $make_id,
				"c" => "PARAM_INT"
			),			
			array(
				"id" => "category_id",
				"value" => $category_id,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "year",
				"value" => $year,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "guest",
				"value" => $guest,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "cabin",
				"value" => $cabin,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "crew",
				"value" => $crew,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "length",
				"value" => $length,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "max_speed",
				"value" => $max_speed,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "price_perday",
				"value" => $price_perday,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "price_perweek",
				"value" => $price_perweek,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "subtitle",
				"value" => $subtitle,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "description",
				"value" => $description,
				"c" => "PARAM_STR"
			),			
			array(
				"id" => "status_id",
				"value" => $status_id,
				"c" => "PARAM_INT"
			),
			array(
				"id" => "m1",
				"value" => $m1,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "m2",
				"value" => $m2,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "m3",
				"value" => $m3,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "iiid",
				"value" => $iiid,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		// end
		
		//assign tendertoy
		$this->assign_tendertoy_boat($iiid);
		//end
		
		//assign cruisingarea
		$this->assign_cruisingarea_boat($iiid);
		//end
		
		//assign destination
		$this->assign_destination_boat($iiid);
		//end
		
		//section bg upload file upload
		for ($k = 1; $k <= 5; $k++){
			$filename = $_FILES['bg_section' . $k]['name'];
			if ($filename != ""){
				$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
				if ($wh_ok == "y"){
					$filename_tmp = $_FILES['bg_section' . $k]['tmp_name'];
					$filename = $fle->uploadfilename($filename);
					$filename = $iiid."section". $k .$filename;
					
					$target_path_main = YCROOTPATH."charterboat/listings/". $iiid ."/background/";
					$target_path = $target_path_main . $cm->filtertextdisplay($filename);
					$fle->fileupload($_FILES['bg_section' . $k]['tmp_name'], $target_path);
					
					$sql = "update tbl_boat_charter set bg_section". $k ." = :filename where id = :iiid";
					$pdo_param = array(
						array(
							"id" => "filename",
							"value" => $filename,
							"c" => "PARAM_STR"
						),
						array(
							"id" => "iiid",
							"value" => $iiid,
							"c" => "PARAM_INT"
						)				
					);
					$db->pdo_query($sql, $pdo_param);
				}
			}
		}		
		//end
		
		$returnar = array(
			"ms" => $iiid,			
			"whedit" => $whedit
		);
		return json_encode($returnar);
	}
	
	//boat delete
	//delete boat image - single
	public function delete_boat_image($fimg1, $boat_id){
		global $cm, $fle;
		if ($fimg1 != ""){						
			$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/thumbnail/" . $fimg1);
			$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/big/" . $fimg1);			
			$original_img = YCROOTPATH ."charterboat/listings/". $boat_id ."/original/" . $fimg1;
			if (file_exists($original_img)){
				$fle->filedelete($original_img);
			}
		}
	}
	
	//delete boat image - single from Ajax call
	public function delete_charterboat_image_ajax_call($imid){
		global $db, $cm;
		
		$sql = "select boat_id, imgpath from tbl_boat_charter_photo where id = :imid";
		$pdo_param = array(			
			array(
				"id" => "imid",
				"value" => $imid,
				"c" => "PARAM_STR"
			)				
		);
		$result = $db->pdo_select($sql, $pdo_param);
		foreach($result as $row){
			$boat_id = $row['boat_id'];
			$fimg1 = $row['imgpath'];
			$this->delete_boat_image($fimg1, $boat_id);
		}
		
		$sql = "delete from tbl_boat_charter_photo where id = :imid";
		$pdo_param = array(			
			array(
				"id" => "imid",
				"value" => $imid,
				"c" => "PARAM_STR"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	public function delete_boat_image_all($boat_id){
		global $db, $cm, $fle;		
		$sql = "select imgpath from tbl_boat_charter_photo where boat_id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$result = $db->pdo_select($sql, $pdo_param);
        foreach($result as $row){
            $fimg1 = $row['imgpath'];
            $this->delete_boat_image($fimg1, $boat_id);
        }
		
		$sql = "delete from tbl_boat_charter_photo where boat_id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	public function delete_charterboat($boat_id){
		global $db, $fle;		
		$sql = "select bg_section1, bg_section2, bg_section3, bg_section4, bg_section5 as ttl from tbl_boat_charter where id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$result = $db->pdo_select($sql, $pdo_param);
		$found = count($result);
		if ($found > 0){
			$row = $result[0];
			$bg_section1 = $row["bg_section1"];
			$bg_section2 = $row["bg_section2"];
			$bg_section3 = $row["bg_section3"];
			$bg_section4 = $row["bg_section4"];
			$bg_section5 = $row["bg_section5"];
			
			if ($bg_section1 != ""){
				$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/background/".$bg_section1);
			}
			if ($bg_section2 != ""){
				$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/background/".$bg_section2);
			}
			if ($bg_section3 != ""){
				$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/background/".$bg_section3);
			}
			if ($bg_section4 != ""){
				$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/background/".$bg_section4);
			}
			if ($bg_section5 != ""){
				$fle->filedelete(YCROOTPATH . "charterboat/listings/". $boat_id ."/background/".$bg_section5);
			}
		}
		
		$sql = "delete from tbl_boat_charter where id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);		
		
		$this->delete_boat_image_all($boat_id);
		$folderpath = YCROOTPATH . "charterboat/listings/" . $boat_id;
		$fle->remove_folder($folderpath);
		
		$sql = "delete from tbl_tendertoy_boat_assign where boat_id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);		
		
		$sql = "delete from tbl_cruisingarea_boat_assign where boat_id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		
		$sql = "delete from tbl_destination_boat_assign where boat_id = :boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	//check boat
	public function check_charterboat($boat_id, $whfrontend = 0){
        global $db, $cm;
		$sql = "select * from tbl_boat_charter where id =:boat_id";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$result = $db->pdo_select($sql, $pdo_param);
		$found = count($result);
		if ($found == 0){
			if ($whfrontend == 1){
				header('Location: '. $cm->sorryredirect(3));
				exit;
			}else{
				$_SESSION["admin_sorry"] = "ERROR! Invalid Manufacturer - Model selection.";
				header('Location: sorry.php');
				exit;
			}
		}
		return $result;        
    }
	
	//insert boat image
	public function insert_charterboat_image(){
		global $db, $cm, $fle;
		
		//form post
		$boat_id = round($_POST["ms"], 0);
		$crop_option = round($_POST["crop_option"], 0);
		$rotateimage = round($_POST["rotateimage"], 0);		
		//end
		
		$filename = $_FILES['file']['name'];
		$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
		if ($wh_ok == "y"){
			$sql = "select max(rank) as ttl from tbl_boat_charter_photo where boat_id = :boat_id";
			$pdo_param = array(			
				array(
					"id" => "boat_id",
					"value" => $boat_id,
					"c" => "PARAM_INT"
				)				
			);
			$rank = $db->pdo_get_single_value($sql, $pdo_param) + 1;			
			$i_iiid = $cm->get_unq_code("tbl_boat_charter_photo", "id", 6) . time();
			$status_id = 1;
			
			$sql = "insert into tbl_boat_charter_photo (id, boat_id, status_id, rank) values (:i_iiid, :boat_id, :status_id, :rank)";
			$pdo_param = array(			
				array(
					"id" => "i_iiid",
					"value" => $i_iiid,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "boat_id",
					"value" => $boat_id,
					"c" => "PARAM_INT"
				),
				array(
					"id" => "status_id",
					"value" => $status_id,
					"c" => "PARAM_INT"
				),
				array(
					"id" => "rank",
					"value" => $rank,
					"c" => "PARAM_INT"
				)				
			);			
			$db->pdo_query($sql, $pdo_param);	
			
			$filename_tmp = $_FILES['file']['tmp_name'];
			$filename = $fle->uploadfilename($filename);
			$filename = $i_iiid."boat".$filename;
			$target_path_main = YCROOTPATH . "charterboat/listings/" . $boat_id . "/";
			
			//thumbnail image
			$r_width = $this->boat_im_width_t;
			$r_height = $this->boat_im_height_t;
			$target_path = $target_path_main . "thumbnail/";        
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename), $rotateimage);
			}
			
			//bigger image
			$r_width = $this->boat_im_width;
			$r_height = $this->boat_im_height;
			$target_path = $target_path_main . "big/";
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename), $rotateimage);
			}
			
			//original image store
			$target_path = $target_path_main . 'original/';
			$target_path = $target_path . $cm->filtertextdisplay($filename);
			$fle->fileupload($filename_tmp, $target_path);
			
			//rotate original image
			if ($rotateimage > 0){
				$im = @ImageCreateFromJPEG ($target_path);
				$im = imagerotate($im, $rotateimage, 0);
				@ImageJPEG ($im, $target_path, 100);
			}
			
			$sql = "update tbl_boat_charter_photo set imgpath = :filename where id = :i_iiid";
			$pdo_param = array(
				array(
					"id" => "filename",
					"value" => $filename,
					"c" => "PARAM_STR"
				),
				array(
					"id" => "i_iiid",
					"value" => $i_iiid,
					"c" => "PARAM_STR"
				)				
			);
			$db->pdo_query($sql, $pdo_param);
			echo($_POST['index']);
		}
	}
	
	//rotate boat image
	public function rotate_charterboat_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$crop_option = round($_POST["hardcrop"], 0);
		$rotateimage = round($_POST["v"], 0);
		
		$boatdet = $cm->get_table_fields('tbl_boat_charter_photo', 'boat_id, imgpath', $imid);
		$boatdet = (object)$boatdet[0];
		
		$filename1 = $oldfilename = $boatdet->imgpath;
		$boat_id = $boatdet->boat_id;
		
		$target_path_raw = "charterboat/listings/" . $boat_id . "/";
		$target_path_main = YCROOTPATH . $target_path_raw;
		
		$filename_tmp = $target_path_main . "original/" . $filename1;
		$filename1 = $fle->create_different_file_name($filename1);
		$original_filename_rename = $target_path_main . "original/" . $filename1;
		
		//thumbnail image
		$r_width = $this->boat_im_width_t;
		$r_height = $this->boat_im_height_t;
		$target_path = $target_path_main . "thumbnail/";
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		//bigger image
		$r_width = $this->boat_im_width;
		$r_height = $this->boat_im_height;
		$target_path = $target_path_main . "big/";
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
		$this->delete_boat_image($oldfilename, $boat_id);
		
		//update filename		
		$sql = "update tbl_boat_charter_photo set imgpath = :filename1 where id = :imid";
		$pdo_param = array(
			array(
				"id" => "filename1",
				"value" => $filename1,
				"c" => "PARAM_STR"
			),
			array(
				"id" => "imid",
				"value" => $imid,
				"c" => "PARAM_STR"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
		
		//output image
		$imgpath_d = "../". $target_path_raw . "thumbnail/" . $filename1 . "?t=" . time();
		return $imgpath_d;
	}
	
	//remove original boat image
	public function remove_original_charterboat_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$imid = $cm->filtertext($imid);
	
		$yachtdet = $cm->get_table_fields('tbl_boat_charter_photo', 'boat_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$fimg1 = $yachtdet->imgpath;
		$boat_id = $yachtdet->boat_id;
		
		$original_img = "../charterboat/listings/". $boat_id ."/original/" . $fimg1;
		$fle->filedelete($original_img);
		
		$sql = "update tbl_boat_charter_photo set keep_original = 0 where id = :imid";
		$pdo_param = array(			
			array(
				"id" => "imid",
				"value" => $imid,
				"c" => "PARAM_STR"
			)				
		);
		$db->pdo_query($sql, $pdo_param);
	}
	
	//update boat photo rank
	public function update_charterboat_image_rank(){
		   global $db, $cm;
		   parse_str($_POST['data'], $recOrder);
		   $i = 1;
		   foreach ($recOrder['item'] as $value) {
				$sql = "update tbl_boat_charter_photo set rank = :i where id = :value";
				$pdo_param = array(			
					array(
						"id" => "i",
						"value" => $i,
						"c" => "PARAM_INT"
					),
					array(
						"id" => "value",
						"value" => $value,
						"c" => "PARAM_STR"
					)				
				);
				$db->pdo_query($sql, $pdo_param);
			    $i++;			
		   }
	}
	
	//back-end boat image display
	public function charterboat_image_display_list($boat_id){
		global $db, $cm;
        $returntext = '';
        $im_found = 0;
        $im_sql = "select * from tbl_boat_charter_photo where boat_id  =:boat_id order by rank";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
        $im_result = $db->pdo_select($im_sql, $pdo_param);
        $im_found = count($im_result);
		if ($im_found > 0){
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="htext">
				<tr>
					<td class="tdouter">
						<ul id="recordsortable" class="imagedisplay gridview">';
						
							$rc_count = 0;
                            foreach($im_result as $im_row){
                                $im_id = $im_row['id'];
                                $imgpath  = $im_row['imgpath'];
                                $im_status_id = $im_row['status_id'];
                                $im_rank = $im_row['rank'];
								$keep_original = $im_row['keep_original'];

                                $imgpath_d = '-';
                                if ($imgpath != ""){
                                    $target_path_main = 'charterboat/listings/' . $boat_id . '/thumbnail/';
                                    $target_path_main = "../" . $target_path_main;
                                    $delpath = '';
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
								
								$returntext .= '
								<li id="item-'. $im_id .'">
									<div class="imgholder">
									<div class="imgrotatemain">
										<ul>
											<li>'. $original_img_del_text .'</li>
											<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class . '" ko="'. $keep_original .'" v="90" c="'. $rc_count .'" yval="'. $im_id .'" ycat="'. $photocategoryid .'" href="javascript:void(0);" title="Rotate ACW"><img src="'. $delpath .'images/rotate_acw.png" alt="Rotate ACW" /></a></li>
											<li><a class="imgrotate imgrotate'. $rc_count . $img_inactive_class .'" ko="'. $keep_original .'" v="270" c="'. $rc_count .'" yval="'. $im_id .'" ycat="'. $photocategoryid .'" href="javascript:void(0);" title="Rotate CW"><img src="'. $delpath .'images/rotate_cw.png" alt="Rotate CW" /></a></li>
											<li><input class="checkbox '. $hidden_class .'" type="checkbox" id="crop_option'. $rc_count .'" name="crop_option'. $rc_count .'" value="1" title="Hard Crop" /></li>
										</ul>
									</div>
									'. $imgpath_d .'
									</div>
									<div class="imgrank">'. $im_rank .'</div>																		
									<div class="options">
										<a class="delyachtimg" yval="'. $im_id .'" href="javascript:void(0);" title="Delete Record"><img src="'. $delpath .'images/del.png" alt="Remove Image" /></a>
										<input type="hidden" class="input sortv" name="sortorder'. $rc_count .'" id="sortorder'. $rc_count .'" value="'. $im_rank .'" />
										<input type="hidden" value="'. $im_id .'" name="id'. $rc_count .'" id="id'. $rc_count .'" />
									</div>
									<div class="clear"></div>
								</li>
                                ';
          
                                $rc_count++;
                            }
						
			$returntext .= '
						</ul>
					</td>
				</tr>
			</table>
            ';
            $returntext .= '<input type="hidden" id="im_thefilecount" name="im_thefilecount" value="'. $im_found .'"/>';
		}
		
		return $returntext;
	}
	
	//get boat first image
	public function get_charterboat_first_image($boat_id, $picktitle = 0){
		global $db, $cm;
		
		$sql = "select imgpath as ttl from tbl_boat_charter_photo where boat_id = :boat_id and imgpath != '' and status_id = 1 order by rank limit 0,1";
		$pdo_param = array(			
			array(
				"id" => "boat_id",
				"value" => $boat_id,
				"c" => "PARAM_INT"
			)				
		);
		$imgpath = $db->pdo_get_single_value($sql, $pdo_param);
		if ($imgpath == ""){ $imgpath = "no.jpg"; }
		return $imgpath;
	}
	
	//get boat name by is
	public function get_boat_name($boat_id){
		global $cm;
		$boat_name = $cm->get_common_field_name_pdo('tbl_boat_charter', 'boat_name', $boat_id);
		return $boat_name;
	}
	
	//Boat listings
	public function charterboat_sql($argu = array()){
		$pdo_param = array();
		
		$category_id = round($argu["category_id"], 0);
		$guest = round($argu["guest"], 0);
		$cruisingarea_id = round($argu["cruisingarea_id"], 0);
		$lnmin = round($argu["lnmin"], 0);
		$lnmax = round($argu["lnmax"], 0);
		$cabin = round($argu["cabin"], 0);
		$crew = round($argu["crew"], 0);
		$max_speed_min = round($argu["max_speed_min"], 0);
		$max_speed_max = round($argu["max_speed_max"], 0);
		
        $query_sql = "select distinct a.*";
        $query_form = " from tbl_boat_charter as a,";
        $query_where = " where";
		
		if ($category_id > 0){
			$query_where .= " a.category_id = :category_id and";
			$pdo_param[] = array(
				"id" => "category_id",
				"value" => $category_id,
				"c" => "PARAM_INT"
			);
		}
		
		if ($guest > 0){
			switch($guest){
				case 1:
					$query_where .= " a.guest > 6 and";
					break;
				case 2:
					$query_where .= " a.guest > 8 and";
					break;
				case 3:
					$query_where .= " a.guest > 10 and";
					break;
				case 4:
					$query_where .= " a.guest = 12 and";
					break;
				case 5:
					$query_where .= " a.guest > 12 and";
					break;
				 default:
				 	break;
			}
		}
		
		if ($cabin > 0){
			$query_where .= " a.cabin > :cabin and";
			$pdo_param[] = array(
				"id" => "cabin",
				"value" => $cabin,
				"c" => "PARAM_INT"
			);
		}
		
		if ($crew > 0){
			$query_where .= " a.crew > :crew and";
			$pdo_param[] = array(
				"id" => "crew",
				"value" => $crew,
				"c" => "PARAM_INT"
			);
		}
		
		if ($lnmin > 0){
			$query_where .= " a.length >= :lnmin and";
			$pdo_param[] = array(
				"id" => "lnmin",
				"value" => $lnmin,
				"c" => "PARAM_INT"
			);
		}
		
		if ($lnmax > 0){
			$query_where .= " a.length <= :lnmax and";
			$pdo_param[] = array(
				"id" => "lnmax",
				"value" => $lnmax,
				"c" => "PARAM_INT"
			);
		}
		
		if ($max_speed_min > 0){
			$query_where .= " a.max_speed >= :max_speed_min and";
			$pdo_param[] = array(
				"id" => "max_speed_min",
				"value" => $max_speed_min,
				"c" => "PARAM_INT"
			);
		}
		
		if ($max_speed_max > 0){
			$query_where .= " a.max_speed <= :max_speed_max and";
			$pdo_param[] = array(
				"id" => "max_speed_max",
				"value" => $max_speed_max,
				"c" => "PARAM_INT"
			);
		}
		
		if ($cruisingarea_id > 0){
			$query_form .= " tbl_cruisingarea_boat_assign as ca,";
			$query_where .= " a.id = ca.boat_id and ca.cruisingarea_id = :cruisingarea_id and";
			$pdo_param[] = array(
				"id" => "cruisingarea_id",
				"value" => $cruisingarea_id,
				"c" => "PARAM_INT"
			);
		}
	
        $query_where .= " a.status_id = 1 and";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $returnar = array(
			"sql" => $sql,			
			"pdo_param" => $pdo_param
		);
		return $returnar;
  	}
	
	public function total_charterboat_found($sql, $pdo_param = array()){
		global $db;
		$sqlm = str_replace("select distinct a.*","select count(distinct a.id) as ttl", $sql);
		$foundm = $db->pdo_get_single_value($sqlm, $pdo_param);
		return $foundm;
	}
	
	public function charterboat_list($argu = array()){
		global $db, $cm;
		$sorting_sql = "a.length";
		
		$sql_ar = $this->charterboat_sql($argu);
		$sql = $sql_ar["sql"];
		$pdo_param = $sql_ar["pdo_param"];
		
		$sql = $sql." order by ". $sorting_sql;
		$result = $db->pdo_select($sql, $pdo_param);
        $found = count($result);
		$boatdata = array();
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay(($val));
			}
			
			$make_name = $cm->get_common_field_name_pdo('tbl_manufacturer', 'name', $make_id);
			$imgpath = $this->get_charterboat_first_image($id);
			$boat_url = $cm->get_page_url($boat_slug, "charterboat");
			
			$boatdata[] = array(
				"id" => $id,
				"boatname" => $boat_name,
				"boatlength" => $length,
				"boatguest" => $guest,
				"makename" => $make_name,
				"imgpath" => $imgpath,
				"boaturl" => $boat_url
			);
		}
		
		$all_details = array(
			"totalrecord" => $found,
			"allboats" => $boatdata,
		);
		echo json_encode($all_details);
	}
	
	//Boat listings main
	public function display_charterboat_listings_main($argu = array()){
		global $db, $cm, $yachtclass;
        $returntext = '
		<div ng-app="live_search_app" ng-controller="live_search_controller" ng-init="fetchData()">
			<div class="listing-yeacht-search wow fadeInUp" data-wow-duration="1.2s">
				<div class="container">
					<h1>Yacht Search</h1>
					<form id="cbsecrhfilter" name="cbsecrhfilter">
						<ul class="listing-yeacht-search-list clearfixmain">
							<li>
								<label for="boat_name">Name</label>
								<input type="text" name="boat_name" placeholder="" id="boat_name" value="" ng-model="cb.boat_name" ng-keyup="fetchBoats()" ng-click="searchboxClicked($event);">
								<ul ng-show="showdataval" id="autoCompleteResult" class="autoCompleteResult">
									<li ng-repeat="data in boatNameData" ><a href="{{ data.boatUrl }}">{{ data.boatName }}</a></li>
								</ul>
							</li>
							<li>                    
								<label for="guest">Guests</label>                        
								<select name="guest" id="guest" ng-model="cb.guest" ng-change="fetchData()">
									<option value="">ANY</option>
									'. $this->get_guest_combo(0) .'
								</select>
							</li>
							<li>
								<label for="boattype">Boat Type</label>
								<select name="category_id" ng-model="cb.category_id" ng-change="fetchData()">
									<option value="" selected="selected">ANY</option>
									'. $yachtclass->get_category_combo(2, 0, 1) .'
								</select>
							</li>
							<li class="half">    
								<div><label for="lnmin">Size (M)</label>
								<select name="lnmin" id="lnmin" ng-model="cb.lnmin" ng-change="fetchData()">
									<option value="">MIN</option>
									'. $this->get_number_combo(0, 30, 100, 10) .'
								</select></div>
								<div><label for="lnmax" class="com_none">Max Size</label>
								<select name="lnmax" id="lnmax" ng-model="cb.lnmax" ng-change="fetchData()">
									<option value="">MAX</option>
									'. $this->get_number_combo(0, 30, 100, 10) .'
								</select></div>
							</li>
							<li class="half">    
								<div><label for="max_speed_min">Max Speed (KT)</label>
								<select name="max_speed_min" id="max_speed_min" ng-model="cb.max_speed_min" ng-change="fetchData()">
									<option value="">MIN</option>
									'. $this->get_number_combo(0, 10, 70, 10) .'
								</select></div>
								<div><label for="max_speed_max" class="com_none">Max Speed (KT)</label>
								<select name="max_speed_max" id="max_speed_max" ng-model="cb.max_speed_max" ng-change="fetchData()">
									<option value="">MAX</option>
									'. $this->get_number_combo(0, 10, 70, 10) .'
								</select></div>
							</li>
							<li>
								<label for="cruisingarea_id">Cruising Area</label>
								<select name="cruisingarea_id" id="cruisingarea_id" ng-model="cb.cruisingarea_id" ng-change="fetchData()">
									<option value="">ANY</option>
									'. $this->get_cruisingarea_combo(0) .'
								</select>
							</li>
							<li>                    
								<label for="cabin">Cabins</label>                        
								<select name="cabin" id="cabin" ng-model="cb.cabin" ng-change="fetchData()">
									<option value="">ANY</option>
									'. $this->get_cabin_combo(0) .'
								</select>
							</li>
							<li>                    
								<label for="crew">Crew</label>                        
								<select name="crew" id="crew" ng-model="cb.crew" ng-change="fetchData()">
									<option value="">ANY</option>
									'. $this->get_crew_combo(0) .'
								</select>
							</li>
							<li class="">								
								<div><input type="button" value="Reset" ng-click="clearSearch(); fetchData()"></div>
							</li>
							<li class="listing-yeacht-search-result-count"><span class="totalitem">{{ totalrecord }}</span></li>
						</ul>
					</form>
				</div>
			</div>
			
			<div class="listing-yeacht-items wow fadeInUp" data-wow-duration="1.2s">
				<ul class="listing-yeacht-items-list clearfixmain">
					<li ng-repeat="data in searchData">
						<a href="{{ data.boaturl }}">
							<img src="'. $cm->folder_for_seo.'charterboat/listings/{{ data.id }}/thumbnail/{{ data.imgpath }}" alt="{{ data.boatname }}">                    
							<div class="overlayy"></div>
							<div class="listing-yeacht-items-details">
								<h3>{{ data.boatname }}</h3>
								<p>{{ data.makename }} |  {{ data.boatlength }}M | {{ data.boatguest }} Guests</p>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>	
		';
		
		$returntext .= '
		<script>
			var app = angular.module("live_search_app", []);
			app.controller("live_search_controller", function($scope, $http, $document){				
			if (sessionStorage.keepsearchsessioncb){
				sessionStorage.removeItem("keepsearchsessioncb");		
				if (sessionStorage.cbsearch) {
						var temp = sessionStorage.getItem("cbsearch");
						var viewName = $.parseJSON(temp);		
						$scope.cb = angular.copy({
								category_id: viewName.category_id,
								guest: viewName.guest,
								cruisingarea_id: viewName.cruisingarea_id,
								lnmin: viewName.lnmin,
								lnmax: viewName.lnmax,
								cabin: viewName.cabin,
								crew: viewName.crew,
								max_speed_min: viewName.max_speed_min,
								max_speed_max: viewName.max_speed_max,
						});
				}else{
					$scope.cb = angular.copy({});
				}
			}else{
				$scope.cb = angular.copy({});
			}
						
			$scope.fetchData = function(){				
				$scope.filterdata = {
					az:1,
					category_id:$scope.cb.category_id,
					guest:$scope.cb.guest,
					cruisingarea_id:$scope.cb.cruisingarea_id,
					lnmin:$scope.cb.lnmin,
					lnmax:$scope.cb.lnmax,
					cabin:$scope.cb.cabin,
					crew:$scope.cb.crew,
					max_speed_min:$scope.cb.max_speed_min,
					max_speed_max:$scope.cb.max_speed_max,
				};
				sessionStorage.setItem("cbsearch", JSON.stringify($scope.filterdata));						
				$http({
					method:"POST",
					url: bkfolder + "includes/nggetdata.php",
					data:$scope.filterdata
				}).then(function successCallback(response){
              		$scope.searchData = response.data.allboats;
					var totalboatfound = response.data.totalrecord
					if (totalboatfound > 1){
						$scope.totalrecord = totalboatfound + " Yachts";
					}else{
						$scope.totalrecord = totalboatfound + " Yacht";
					}
									
                });
			};
			
			$scope.fetchBoats = function(){
				var searchText_len = $scope.cb.boat_name.trim().length;
				if(searchText_len > 0){
					$http({
					method:"POST",
					url: bkfolder + "includes/nggetdata.php",
					data:{
						az:2,
						subsection:1,
						boat_name:$scope.cb.boat_name
					}
				}).then(function successCallback(response){
              		$scope.boatNameData = response.data;
					$scope.showdataval = true;
					$(".autoCompleteResult").show();									
                });
				}else{
					$scope.boatNameData = {};
					$scope.showdataval = false;
				}				
			};
			
			$scope.searchboxClicked = function($event){
				$event.stopPropagation();
			}
			
			$document.on("click",function(){
				$scope.boatNameData = {};
				$scope.showdataval = false;
				$(".autoCompleteResult").hide();
				$("#boat_name").val("");
			});
			
			$scope.clearSearch = function(){
				$scope.cb = angular.copy({});
			};
		});
		</script>
		';
		
		return $returntext;
	}
	
	public function charterboat_details($param = array()){
		global $db, $cm, $frontend;
		
		//param
		$default_param = array("checkopt" => 0, "htmlreturn" => 1);
		$param = array_merge($default_param, $param);
		
		$checkval = $param["checkval"];
		$checkopt = $param["checkopt"];
		$htmlreturn = $param["htmlreturn"];
		//ends
		
		if ($checkopt == 1){
			$checkfield = 'boat_slug';
		}else{
			$checkfield = 'id';
		}
		
		$sql = "select * from tbl_boat_charter where ". $checkfield ." = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);		
		$found = count($result);
		
		$boatdata = array();		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay(($val));
			}
			
			$make_name = $cm->get_common_field_name_pdo('tbl_manufacturer', 'name', $make_id);
			$category_name = $cm->get_common_field_name_pdo('tbl_category', 'name', $category_id);
			$imgpath = $this->get_charterboat_first_image($id);
			$boat_url = $cm->get_page_url($boat_slug, "charterboat");
			$imagelink = $cm->site_url . '/' ."charterboat/listings/" . $id . "/big/" .$imgpath;
			
			//cruisingarea
			$cruisingarea = array();
			$pdo_param2 = array();
			$sql2 = "select a.name from tbl_cruisingarea as a, tbl_cruisingarea_boat_assign as b where a.id = b.cruisingarea_id and b.boat_id = :id order by rank";
			$pdo_param2[] = array(
				"id" => "id",
				"value" => $id,
				"c" => "PARAM_INT"
			);
			$result2 = $db->pdo_select($sql2, $pdo_param2);
			foreach($result2 as $row2){
				$cruisingarea[] = array(
					"name" => $cm->filtertextdisplay($row2["name"])
				);
			}
			
			//boatgallery
			$boatgallery = array();
			$pdo_param2 = array();
			$sql2 = "select * from tbl_boat_charter_photo where boat_id = :id and imgpath != '' and status_id = 1 order by rank";
			$pdo_param2[] = array(
				"id" => "id",
				"value" => $id,
				"c" => "PARAM_INT"
			);
			$result2 = $db->pdo_select($sql2, $pdo_param2);
			foreach($result2 as $row2){
				$boatgallery[] = array(
					"imgpath" => $cm->filtertextdisplay($row2["imgpath"])
				);
			}
			
			//tendortoy
			$tendertoy = array();
			$pdo_param2 = array();
			$sql2 = "select a.* from tbl_tendertoy as a, tbl_tendertoy_boat_assign as b where a.id = b.tendertoy_id and b.boat_id = :id order by rank";
			$pdo_param2[] = array(
				"id" => "id",
				"value" => $id,
				"c" => "PARAM_INT"
			);
			$result2 = $db->pdo_select($sql2, $pdo_param2);
			foreach($result2 as $row2){
				$tendertoy[] = array(
					"name" => $cm->filtertextdisplay($row2["name"]),
					"iconpath" => $cm->filtertextdisplay($row2["iconpath"])
				);
			}
			
			//destination
			$destination = array();
			$pdo_param2 = array();
			$sql2 = "select a.* from tbl_destination as a, tbl_destination_boat_assign as b where a.id = b.destination_id and b.boat_id = :id order by rank";
			$pdo_param2[] = array(
				"id" => "id",
				"value" => $id,
				"c" => "PARAM_INT"
			);
			$result2 = $db->pdo_select($sql2, $pdo_param2);
			foreach($result2 as $row2){
				$destination[] = array(
					"name" => $cm->filtertextdisplay($row2["name"]),
					"imagepath" => $cm->filtertextdisplay($row2["imagepath"])
				);
			}
				
			$boatdata[] = array(
				"id" => $id,
				"boatname" => $boat_name,
				"subtitle" => $subtitle,
				"boatlength" => $length,
				"boatyear" => $year,
				"boatguest" => $guest,
				"boatcabin" => $cabin,
				"boatcrew" => $crew,
				"maxspeed" => $max_speed,
				"priceperday" => $price_perday,
				"priceperdayformat" => $cm->format_price($price_perday, 0),
				"priceperweek" => $price_perweek,
				"priceperweekformat" => $cm->format_price($price_perweek, 0),
				"makename" => $make_name,
				"categoryname" => $category_name,
				"imgpath" => $imgpath,
				"boaturl" => $boat_url,
				"imagelink" => $imagelink,
				"description" => $description,
				"bg_section1" => $bg_section1,
				"bg_section2" => $bg_section2,
				"bg_section3" => $bg_section3,
				"bg_section4" => $bg_section4,
				"bg_section5" => $bg_section5,
				"m1" => $m1,
				"m2" => $m2,
				"m3" => $m3,
				"cruisingarea" => $cruisingarea,
				"boatgallery" => $boatgallery,
				"tendertoy" => $tendertoy,
				"destination" => $destination,
			);
		}
		
		$all_details = array(
			"totalrecord" => $found,
			"allboats" => $boatdata,
		);
		echo json_encode($all_details);
	}
	
	public function submit_charterboat_enquire_form(){
		if(($_POST['fcapi'] == "charterboatsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$subject = $_POST["subject"];
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$comment = $_POST["comment"];
			
			$email2 = $_POST["email2"];
			$pgid = round($_POST["pgid"], 0);
			$boat_id = round($_POST["boat_id"], 0);
			//end
			
			//create the session
			$datastring = $cm->session_field_charterboat_enquery();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$boat_ar = $this->check_charterboat($boat_id, 1);
			$boat_row = $boat_ar[0];
			$boat_name = $boat_row["boat_name"];
			$boat_slug = $boat_row["boat_slug"];
			$fullurl = $cm->site_url . $cm->get_page_url($boat_slug, "charterboat");
			
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($comment, '', 'Comment', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$comment = nl2br($comment);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message - admin
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Charter Boat Enquery Form</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Boat Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width=""><a href="'. $fullurl .'">'. $cm->filtertextdisplay($boat_name, 1) .'</a></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comment, 1) .'</td>
			  	</tr>												
			</table>
			';
			//end
			
			//add to lead
			$form_type = 29;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => $boat_id
			);
			$leadclass->add_lead_message($param);
			//end
			
			//send email to admin
			$send_ml_id = 53;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#formdatasubmission#", $emailmessage, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 54;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			$_SESSION["s_pgid"] = $pgid;
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}
	/*-----------/BOAT------------*/	
}
?>