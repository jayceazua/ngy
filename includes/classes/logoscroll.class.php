<?php
class Logoscrollclass {	
	/*-----------LOGO/NAME SCROLL/DISPALY SECTION--------------*/

	//Logo scroll/display list
	public function logo_dislay_sql($argu = array()){
		$sectionid = round($argu["sectionid"], 0);
		
        $query_sql = "select *";
        $query_form = " from tbl_logo_scroll,";
        $query_where = " where";
	
        $query_where .= " status_id = 1 and";
		
		if ($sectionid > 0){
			$query_where .= " section_id = '". $sectionid ."' and";
		}
		
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
  	}
	
	public function total_logo_dislay_found($sql){
		global $db;
		$sqlm = str_replace("select *","select count(*) as ttl",$sql);
		$foundm = $db->total_record_count($sqlm);
		return $foundm;
	}
	
	public function logo_dislay_list($param = array()){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';

		//param
		$default_param = array("ownboat" => 2);
		$param = array_merge($default_param, $param);	
		$ownboat = round($param["ownboat"], 0);
		//$ownboat = 2;
		//end
	
		$seourlcall = "make";
		if ($ownboat > 0){
			if ($ownboat == 1){				
				$seourlcall = "makeourlistings";
			}
			
			if ($ownboat == 2){
				$seourlcall = "makecobrokerage";
			}
		}

		$sorting_sql = "rank";
        $sql = $this->logo_dislay_sql($param);
        //$foundm = $this->total_logo_dislay_found($sql);

        $sql = $sql." order by ". $sorting_sql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
			$returntext .= '<ul class="brand-exp-list">';
						
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				if ($make_id > 0){
					$detailsurl = $cm->get_page_url($make_id, $seourlcall);
				}elseif ($page_id > 0){
					$detailsurl = $cm->get_page_url($page_id, "page");
				}else{
					$detailsurl = 'javascript:void(0);';
				}
							
				$imagefolder = 'logoscrollimage/';
				if ($imgpath == ""){ $imgpath = 'no.jpg'; }
				$imagedata = '<img src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" alt="">';
				
				$returntext .= '<li><a href="'. $detailsurl .'">'. $imagedata .'</a></li>';						
            }	
					
			$returntext .= '</ul>';                       
        }		
		return $returntext;
  	}
	
	//only brand name without scroll
	public function display_brand_list($param = array()){
        global $db, $cm;
				
		//param
		$default_param = array("ownboat" => 1, "sectionid" => 1);
		$param = array_merge($default_param, $param);
		
		$ownboat = round($param["ownboat"], 0);
		$sectionid = round($param["sectionid"], 0);
		//end
		
		$seourlcall = "make";
		if ($ownboat > 0){
			if ($ownboat == 1){				
				$seourlcall = "makeourlistings";
			}
			
			if ($ownboat == 2){
				$seourlcall = "makecobrokerage";
			}
		}
		
        $returntext = '';
        $moreviewtext = '';

		$sorting_sql = "rank";
        $sql = $this->logo_dislay_sql($param);
        //$foundm = $this->total_logo_dislay_found($sql);

        $sql = $sql." order by ". $sorting_sql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
			$returntext .= '<ul class="brand-name-list">';
						
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				if ($make_id > 0){
					$detailsurl = $cm->get_page_url($make_id, $seourlcall);
				}elseif ($page_id > 0){
					$detailsurl = $cm->get_page_url($page_id, "page");
				}else{
					$detailsurl = 'javascript:void(0);';
				}
				
				$returntext .= '<li><a href="'. $detailsurl .'">'. $name .'</a></li>';						
            }	
					
			$returntext .= '</ul>';                       
        }		
		return $returntext;
  	}
	
	//brand name with box image
	public function display_brand_list_with_img($param = array()){
        global $db, $cm;
		
		//param
		$default_param = array("ownboat" => 1, "sectionid" => 1);
		$param = array_merge($default_param, $param);
		
		$ownboat = round($param["ownboat"], 0);
		$sectionid = round($param["sectionid"], 0);
		//end
		
		$seourlcall = "make";
		if ($ownboat > 0){
			if ($ownboat == 1){				
				$seourlcall = "makeourlistings";
			}
			
			if ($ownboat == 2){
				$seourlcall = "makecobrokerage";
			}
		}
		
        $returntext = '';
        $moreviewtext = '';

		$sorting_sql = "rank";
        $sql = $this->logo_dislay_sql($param);
        //$foundm = $this->total_logo_dislay_found($sql);

        $sql = $sql." order by ". $sorting_sql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
			$returntext .= '<ul class="brandlistboximg"><!--';
						
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				if ($make_id > 0){
					$detailsurl = $cm->get_page_url($make_id, $seourlcall);
				}elseif ($page_id > 0){
					$detailsurl = $cm->get_page_url($page_id, "page");
				}else{
					$detailsurl = 'javascript:void(0);';
				}
				
				if ($imgpath == ""){
					$imgpath = 'no.jpg';
				}
				
				$returntext .= '--><li><a href="'. $detailsurl .'">
					<div class="topimg areaoverlay3"><img src="'. $cm->folder_for_seo .'logoscrollimage/'. $imgpath .'" /></div>
					<div class="boxname">'. $name .'</div>
				</a></li><!--';
				
				$returntext .= '<li><a href="'. $detailsurl .'">'. $name .'</a></li>';						
            }	
					
			$returntext .= '--></ul>';
        }		
		return $returntext;
  	}
	
	/*-----------/LOGO/NAME SCROLL/DISPALY SECTION--------------*/	
	
	/*-----------FUNCTION ADMIN------------*/
	
	//logo insert/update
	public function logo_insert_update(){
		global $db, $cm, $adm, $fle;
		
		$name = $_POST["name"];
		$make_id = round($_POST["make_id"], 0);
		$page_id = round($_POST["page_id"], 0);
		$crop_option = round($_POST["crop_option"], 0);
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$section_id = round($_POST["section_id"], 0);
		$ms = round($_POST["ms"], 0);
		
		if ($page_id > 0){
			$make_id = 0;
		}
		
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_logo_scroll where section_id = '". $section_id ."'") + 1;
			$sql = "insert into tbl_logo_scroll (name) values ('". $cm->filtertext($name) ."')";
			$iiid = $db->mysqlquery_ret($sql);
			$whedit = 0;
		}else{
			$sql = "update tbl_logo_scroll set name = '". $cm->filtertext($name) ."' where id = '". $ms. "'";
			$db->mysqlquery($sql);
			$rank = round($_POST["rank"], 0);			
			$iiid = $ms;
			$whedit = 1;
		}
		
		// common update
		$sql = "update tbl_logo_scroll set page_id = '". $page_id ."'
		, make_id = '". $make_id ."'
		, status_id = '". $status_id ."'
		, rank = '". $rank ."'
		, section_id = '". $section_id ."' where id = '". $iiid ."'";
		$db->mysqlquery($sql);
		// end 
		
		//image upload
		$filename = $_FILES['imgpath']['name'] ;
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
			if ($wh_ok == "y"){
				$filename_tmp = $_FILES['imgpath']['tmp_name'];
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."logoscroll".$filename;
		
				$target_path_main = "logoscrollimage/";
				$target_path_main = "../" . $target_path_main;

				//image
				$r_width = $cm->make_logo_scroll_im_width;
				$r_height = $cm->make_logo_scroll_im_height;
				$target_path = $target_path_main;
				
				/*if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}*/
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

				$fle->filedelete($filename_tmp);
				$sql = "update tbl_logo_scroll set imgpath = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
				$db->mysqlquery($sql);
			}
		}
		//end
		
		// update the rank
		$tablenm = "tbl_logo_scroll";
		$wherecls = " id != '".$iiid."' and section_id = '". $section_id ."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		$returnar = array(
			"ms" => $iiid,
			"section_id" => $section_id,
			"whedit" => $whedit
		);
		
		return json_encode($returnar);
	}	
	
	//set ourteam rank
	public function set_logo_display_rank(){
		global $db, $cm;
		
		$sql = "select * from tbl_logo_scroll order by rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$returntext = '
			<ul id="recordsortable" class="recordorder2">
			';			
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				if ($imgpath == ""){
					$imgpath = "no.jpg";	
				}
				
				$target_path_main = 'logoscrollimage/';
        		$imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $imgpath .'" border="0" />';
				
				$returntext .= '
				<li id="item-'. $id .'">
				<div class="top">'. $imgpath_d .'</div>
				<div class="bottom">Rank: <span>'. $rank .'</span></div>
				</li>
				';
			}
			
			$returntext .= '
			</ul>
			';
		}else{
			$returntext = '<p>No data.</p>';
		}
		return $returntext;
	}
	
	//update logo rank
	public function update_logo_display_rank(){
		global $db, $cm;
		parse_str($_POST['data'], $recOrder);
		$i = 1;
		foreach ($recOrder['item'] as $value) {
		   $sql = "update tbl_logo_scroll set rank = '". $i ."' where id = '". $value ."'";
		   $db->mysqlquery($sql);
		   $i++;			
		}
	}	
	
	//update logo rank quick
	public function update_logo_display_rank_quick($choseroption){
		global $db, $cm;
		
		if ($choseroption == 2){
			//Name: Z-A
			$sql = "select id from tbl_logo_scroll order by name desc";
		}else{
			//Name: A-Z
			$sql = "select id from tbl_logo_scroll order by name";
		}
		$result = $db->fetch_all_array($sql);
		
		$counter = 1;
		foreach($result as $row){
			$id = $row['id'];
			$sql_u = "update tbl_logo_scroll set rank = '". $counter ."' where id = '". $id ."'";
			$db->mysqlquery($sql_u);
			$counter++;
		}
		
		$returntext = $this->set_logo_display_rank();
		$returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);
	}
	
	//remove logo scroll
	public function delete_logo($logo_id){
		global $db, $fle;
		
		$fimg1 = $db->total_record_count("select imgpath as ttl from tbl_logo_scroll where id = '". $logo_id ."'");
		if ($fimg1 != ""){
			$fle->filedelete("../logoscrollimage/".$fimg1);
		}
		
		$sql = "delete from tbl_logo_scroll where id = '". $logo_id ."'";
        $db->mysqlquery($sql);
	}	
	
	/*-----------/REMOVE FUNCTION ADMIN------------*/
}
?>