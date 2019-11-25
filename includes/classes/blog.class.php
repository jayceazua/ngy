<?php
class Blogclass {	
	/*-----------BLOG SECTION--------------*/
	
	//some required page url
	public function get_news_url(){
		global $cm;
		return $cm->get_page_url(7, 'page');
	}
	
	public function get_blog_url($category_id = 0, $isevent = 0){
		global $cm;
		
		$shortcode = "[fcbloglist";
		
		if ($category_id > 0){
			$shortcode .= " categoryid=" . $category_id;
		}
		
		if ($isevent > 0){
			$shortcode .= " isevent=". $isevent ."]";
		}
		//$shortcode .= "]";
		
		$collected_page_id = $cm->get_page_id_by_shortcode($shortcode);
		
		
		return $cm->get_page_url($collected_page_id, 'page');
	}
	
	//blog category insert/update
	public function blog_category_insert_update($frontfrom = 0){
		global $db, $cm, $adm;
		
		$parentid = round($_POST["parentid"], 0);
		$name = $_POST["name"];
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
		
		if ($parentid == 0){
			$cat_level = 1;
		}else{
			$cat_level = $db->total_record_count("select cat_level as ttl from tbl_blog_category where id = '". $parentid ."'") + 1;
		}
		
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_blog_category where parent_id = '". $parentid ."'") + 1;
			$sql = "insert into tbl_blog_category (parent_id, name) values ('". $parentid ."', '". $cm->filtertext($name) ."')";
			$iiid = $db->mysqlquery_ret($sql);
			$_SESSION["postmessage"] = "nw";
			$rback = "mod_blog_category.php?parentid=" . $parentid;
		}else{
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_blog_category set parent_id = '". $parentid ."', name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
			$_SESSION["postmessage"] = "up";
			$rback = $_SESSION["bck_pg"];
		}
		$slug = $cm->create_slug($name);
		
		// common update
		$sql = "update tbl_blog_category set cat_level = '". $cat_level ."'
		, slug = '". $cm->filtertext($slug) ."'
		, status_id = '". $status_id ."'
		, rank = '".$rank."' where id = '".$iiid."'";
		$db->mysqlquery($sql);
		// end 
		
		// update the rank
		$tablenm = "tbl_blog_category";
		$wherecls = " id != '".$iiid."' and parent_id = '". $parentid ."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		return $iiid;
	}
	
	//blog category combo
	public function get_blog_category_combo($category_id = 0, $parent_id = 0, $azop = 0){
		global $db;
		$returntxt = '';
		$returnarray = array();
		$vsql = "select id, name from tbl_blog_category where parent_id = '". $parent_id ."' and status_id = 1 order by id";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];
			$bck = '';
			if ($category_id == $c_id){
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
	
	//blog category insert/update
	public function blog_tag_insert_update($frontfrom = 0){
		global $db, $cm, $adm;
		
		$name = $_POST["name"];
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
			
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_blog_tag") + 1;
			$sql = "insert into tbl_blog_tag (name) values ('". $cm->filtertext($name) ."')";
			$iiid = $db->mysqlquery_ret($sql);
			$_SESSION["postmessage"] = "nw";
			$rback = "mod_blog_tag.php";
		}else{
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_blog_tag set name = '". $cm->filtertext($name) ."' where id = '".$ms."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
			$_SESSION["postmessage"] = "up";
			$rback = $_SESSION["bck_pg"];
		}
		$slug = $cm->create_slug($name);
		
		// common update
		$sql = "update tbl_blog_tag set slug = '". $cm->filtertext($slug) ."'
		, status_id = '". $status_id ."'
		, rank = '".$rank."' where id = '".$iiid."'";
		$db->mysqlquery($sql);
		// end 
		
		// update the rank
		$tablenm = "tbl_blog_tag";
		$wherecls = " id != '".$iiid."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		return $iiid;
	}
	
	public function insert_tag_ajax($name){
		global $db, $cm;
		
		$rank = $db->total_record_count("select max(rank) as ttl from tbl_blog_tag") + 1;
		$slug = $cm->create_slug($name);
		$status_id = 1;
		
		$sql = "insert into tbl_blog_tag (name) values ('". $cm->filtertext($name) ."')";
		$iiid = $db->mysqlquery_ret($sql);
		
		// common update
		$sql = "update tbl_blog_tag set slug = '". $cm->filtertext($slug) ."'
		, status_id = '". $status_id ."'
		, rank = '".$rank."' where id = '".$iiid."'";
		$db->mysqlquery($sql);
		// end
		
		$checkbox_name = "tag_id[]";
		$checkbox_id = "tag_id";
		$vck = ' checked="checked"';
		$addedtag = '
		<li>
			<input class="mp_ckbox" type="checkbox" value="'. $iiid .'" id="all_'. $checkbox_id . $iiid .'" name="all_'. $checkbox_name .'" connectfld="all" replacefld="most"'. $vck .' /> '. $cm->filtertextdisplay($name) .'
		</li>
		';
		
		$returnval = array(
            'doc' => $addedtag
        );
        return json_encode($returnval);
	}
	
	//display smart tag selection
	public function display_smart_tag_selection($ms, $addoption = 0, $tablewidth = '100%'){
		global $db, $cm;
		$returntext = '';
		
		$maintbl = "tbl_blog_tag";
		$checktbl = "tbl_blog_tag_assign";
		$checkfield = "tag_id";
		$mainfield = "blog_id";
		$checkbox_name = "tag_id[]";
		$checkbox_id = "tag_id";
		$ckpage = 1;
		$heading_cap = "Tag";
		$heading_txt = "Tag";
		
		$ck_sql = "select distinct a.id, a.name, count(b.". $checkfield .") as total, if (a.id IN( select ". $checkfield ." from ". $checktbl ." where ". $mainfield ." = '". $ms ."'), 'y', 'n') as wcheck from ". $maintbl ." as a LEFT JOIN ". $checktbl ." as b ON a.id = b.". $checkfield ." and a.status_id = 1 group by a.id order by wcheck desc, a.name";
		//echo $ck_sql;
		$ck_result = $db->fetch_all_array($ck_sql);
		$ck_found = count($ck_result);
		if ($ck_found > 0){
			$returntext .= '
			<ul class="tab">
			';
			
			
			//All List
			$returntext .= '
			<li>
				<a href="javascript:void(0);">All '. $heading_cap .'</a>
				<ul class="tab_1">
			';
			
			foreach($ck_result as $ck_row){
				$ck_id = $ck_row["id"];
                $ck_name = $cm->filtertextdisplay($ck_row["name"]);
                $ck_total = $ck_row["total"];
                $ck_wcheck = $ck_row["wcheck"];
				if ($ck_wcheck == "y"){ $vck = ' checked="checked"'; }else{ $vck = ""; }
				$returntext .= '
				<li>
					<input class="mp_ckbox" type="checkbox" value="'. $ck_id .'" id="all_'. $checkbox_id . $ck_id .'" name="all_'. $checkbox_name .'" connectfld="all" replacefld="most"'. $vck .' /> '. $ck_name .'
				</li>
				';
			}
			
			$returntext .= '
				</ul>
			</li>	
			';
			
			//Most Used
			$ck_result = $cm->ar_sort($ck_result, 'total');
			$returntext .= '
			<li>
				<a href="javascript:void(0);">Most Used</a>
				<ul>
			';
			
			foreach($ck_result as $ck_row){
				$ck_id = $ck_row["id"];
                $ck_name = $ck_row["name"];
                $ck_total = $ck_row["total"];
                $ck_wcheck = $ck_row["wcheck"];
				if ($ck_total > 0){
					if ($ck_wcheck == "y"){ $vck = ' checked="checked"'; }else{ $vck = ""; }
					$returntext .= '
					<li>
						<input class="mp_ckbox" type="checkbox" value="'. $ck_id .'" id="most_'. $checkbox_id . $ck_id .'" name="most_'. $checkbox_name .'" connectfld="most" replacefld="all"'. $vck .' /> ' . $ck_name .'
					</li>
					';
				}
			}
			
			$returntext .= '
				</ul>
			</li>	
			';
			
			$returntext .= '
			</ul>
			';
		}else{
			$returntext = 'No '. $heading_cap .' found.';
		}
		
		if ($addoption == 1){
			$returntext .= '
			<div class="add-new-record">
				<input type="text" id="new_tag_name" name="new_tag_name"  value="" placeholder="Add new '. $heading_txt .'" class="input_text" /> 
				<button type="button" class="butta addcat"  ckpage="'. $ckpage .'"><span class="addIcon butta-space">Add</span></button>
				<div class="clear"></div>
			</div>
			';
		}
		
		return $returntext;		
	}
   
	//update blog date
	public function update_blog_date($updateid, $reg_date){
		global $db, $cm;
		$reg_date_a = $cm->set_date_format($reg_date);
		$sql = "update tbl_blog set reg_date = '". $cm->filtertext($reg_date_a) ."' where id = '". $updateid ."'";
		$db->mysqlquery($sql);	  
	}
	
	//tottal blog tag
	public function get_total_blog_tag(){
		global $db;
		$total_rec = $db->total_record_count("select count(*) as ttl from tbl_blog_tag where status_id = 1");
		return $total_rec;
	} 
	
	//assing tag to blog
	public function blog_tag_assign($iiid){
		global $db, $cm;
		$total_rec = $this->get_total_blog_tag();
		
		$sql = "delete from tbl_blog_tag_assign where blog_id = '". $iiid ."'";
		$db->mysqlquery($sql);	
		
		$selected_record = array();
		//print_r($_POST["most_tag_id"]);
		//exit;
		foreach($_POST["all_tag_id"] as $all_tag_id_vl){
			$all_tag_id_vl = round($all_tag_id_vl, 0);
			if ($all_tag_id_vl > 0){
				$selected_record[] = $all_tag_id_vl;
			}           
		}
		
		foreach($_POST["most_tag_id"] as $most_tag_id_vl){
			$most_tag_id_vl = round($most_tag_id_vl, 0);
			if ($most_tag_id_vl > 0){
				$selected_record[] = $most_tag_id_vl;
			}           
		}
		
		$selected_record = array_unique($selected_record); 
		foreach($selected_record as $selected_record_vl){
			$sql = "insert into tbl_blog_tag_assign (blog_id, tag_id) values ('". $iiid ."', '". $selected_record_vl ."')";
			$db->mysqlquery($sql);  
		}	  
	}  
	
	//blog insert/update
	public function blog_insert_update($frontfrom = 0){
		global $db, $cm, $fle;
		
		$category_id = round($_POST["category_id"], 0);		
		$name = $_POST["name"];
		$description = $_POST["description"];
		$small_description = $_POST["small_description"];	
		$crop_option = round($_POST["crop_option"], 0);
		$display_date = round($_POST["display_date"], 0);
		$image_display_post = round($_POST["image_display_post"], 0);
		$featured_post = round($_POST["featured_post"], 0);
		
		$m1 = $_POST["m1"];
		$m2 = $_POST["m2"];
		$m3 = $_POST["m3"];
		
		$ms = round($_POST["ms"], 0);		
		
		if ($frontfrom == 0){
			//backend
			$status_id = round($_POST["status_id"], 0);
			$poster_id = 1;
			
			$reg_date = $_POST["reg_date"];
			$reg_date_a = $cm->set_date_format($reg_date);
		}else{
			//frontend
			$status_id = 1;
			$poster_id =  1;
			$reg_date_a = date("Y-m-d");
		}
		
		if ($ms == 0){
			$sql = "insert into tbl_blog (category_id, poster_id) values ('". $cm->filtertext($category_id) ."', '". $poster_id ."')";
			$iiid = $db->mysqlquery_ret($sql);			
		}else{
			$sql = "update tbl_blog set category_id = '". $cm->filtertext($category_id) ."' where id = '".$ms."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
		}
		
		$slug = $cm->create_slug($name);
		if ($small_description == ""){ $small_description = $cm->get_sort_content_description($description, 350); }
		
		// common update
		$sql = "update tbl_blog set slug = '". $cm->filtertext($slug) ."'
		, name = '". $cm->filtertext($name) ."'
		, description = '". $cm->filtertext($description) ."'
		, small_description = '". $cm->filtertext($small_description) ."'
		, image_display_post = '". $image_display_post ."'
		, crop_option = '". $crop_option ."'
		, status_id = '". $status_id ."'
		, reg_date = '". $cm->filtertext($reg_date_a) ."'
		, display_date = '". $display_date ."'
		, featured_post = '". $featured_post ."'
		, m1 = '". $cm->filtertext($m1) ."'
		, m2 = '". $cm->filtertext($m2) ."'
		, m3 = '". $cm->filtertext($m3) ."' where id = '". $iiid ."'";
		$db->mysqlquery($sql);
		// end 
		
		//blog tag assing
		$this->blog_tag_assign($iiid);
		//end
		
		//blog image upload
		$filename = $_FILES['imgpath']['name'] ;
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
			if ($wh_ok == "y"){
				$filename_tmp = $_FILES['imgpath']['tmp_name'];
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."blog".$filename;
		
				$target_path_main = "blogimage/";
				if ($frontfrom == 0){
					$target_path_main = "../" . $target_path_main;
				}
				
				//image
				$r_width = $cm->blog_im_width;
				$r_height = $cm->blog_im_height;
				$target_path = $target_path_main;
				
				if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}
				
				//thumb
				$r_width = $cm->blog_im_width_t;
				$r_height = $cm->blog_im_height_t;
				$target_path = $target_path_main . "thumb/";
				
				if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				}
				
				$fle->filedelete($filename_tmp);
				$sql = "update tbl_blog set blog_image = '".$cm->filtertext($filename1)."' where id = '". $iiid ."'";
				$db->mysqlquery($sql);
			}
		}
		//end
		
		return $iiid;
	}		

	//blog total - by category id
	public function get_total_blog_by_category($category_id){
		global $db, $cm;
		$sql = "select count(*) as ttl from tbl_blog where category_id = '". $category_id ."' and status_id = 1";
		$total_blog = $db->total_record_count($sql);
		return $total_blog;
	}
	
	//latest blog
	public function blog_list_latest($argu = array()){
		global $db, $cm;
		$returntext = '';
		$limit = round($argu["limit"], 0);
		if ($limit <= 0){ $limit = 3; }
		
		$sql = "select id, slug, name, small_description, reg_date, display_date from tbl_blog where status_id = 1 order by reg_date desc limit 0, " . $limit;
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '<ul class="fcblog">';
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$linkurl = $cm->get_page_url($slug, 'blog');
				$reg_date_timestamp = strtotime($reg_date);
				$reg_date_day = date("d", $reg_date_timestamp);
				$reg_date_month = date("M", $reg_date_timestamp);
				$small_description = $cm->get_sort_content_description($small_description, 90);
				
				$date_text = '';
				if ($display_date == 1){
					$date_text = '<div class="fcdate"><span>'. $reg_date_day .'</span>'. $reg_date_month .'</div>';
				}
				
				$returntext .= '
				<li>
				'. $date_text .'
				<h4><a href="'. $linkurl .'">'. $name .'</a></h4>
				<p>'. $small_description .'</p>
				</li>
				';
				//$returntext .= '<li><a href="'. $linkurl .'">'. $name .'</a></li>';
			}
			$returntext .= '</ul>			
			';
		}		
		return $returntext;
	}
	
	public function blog_list_box($argu = array()){
		global $db, $cm;
		$returntext = '';
		$limit = round($argu["limit"], 0);
		if ($limit <= 0){ $limit = 3; }
		
		$sql = "select id, slug, name from tbl_blog where status_id = 1 order by reg_date desc limit 0, " . $limit;
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '<ul class="tick">';
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				$linkurl = $cm->get_page_url($slug, 'blog');
				$returntext .= '<li><a href="'. $linkurl .'">'. $name .'</a></li>';
			}
			$returntext .= '</ul>			
			';
		}		
		return $returntext;
	}
	
	public function blog_category_list_col($argu = array()){
		global $db, $cm;
		$returntext = '';
		$limit = round($argu["limit"], 0);
		if ($limit <= 0){ $limit = 5; }
		
		$fieldname = "a.id, a.slug, a.name, count(b.id) as total";
        $sql = "select ". $fieldname ." from tbl_blog_category as a, tbl_blog as b where a.id = b.category_id and a.status_id = 1 and b.status_id = 1 group by a.id order by total desc limit 0, " . $limit;
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '
			<h2 class="sidebartitle">Categories</h2>
			<div class="leftrightcolsection notopborder clearfix">
			<ul class="tick">
			';
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				$linkurl = $cm->get_page_url($slug, 'blogcategory');
				$returntext .= '
				<li><a href="'. $linkurl .'">'. $name .'</a></li>
				';
			}
			$returntext .= '</ul>
			</div>		
			';
		}		
		return $returntext;
	}
	
	public function blog_archive_list_col($argu = array()){
		global $db, $cm;
		$returntext = '';
		$limit = round($argu["limit"], 0);
		
        $sql = "select reg_date from tbl_blog where status_id = 1 group by YEAR(reg_date), MONTH(reg_date) order by reg_date desc";
		if ($limit > 0){
			$sql .= " limit 0, " . $limit;
		}
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$returntext .= '
			<h2 class="sidebartitle">Archives</h2>
			<div class="leftrightcolsection notopborder clearfix">			
			<ul class="tick">
			';
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$reg_date_d = $cm->display_date($reg_date, 'y', 5);
				$slug = $cm->display_date($reg_date, 'y', 16);
				$linkurl = $cm->get_page_url($slug, 'blogarchive');
				$returntext .= '
				<li><a href="'. $linkurl .'">'. $reg_date_d .'</a></li>
				';
			}
			$returntext .= '</ul>	
			</div>		
			';
		}		
		return $returntext;
	}
	
	public function blog_category_fields($category_id){
		global $db, $cm;
		$categoryarray = $cm->get_table_fields('tbl_blog_category', 'slug, name', $category_id);
		$categoryslug = $categoryarray[0]["slug"];		
		$catlinkurl = $cm->get_page_url($categoryslug, 'blogcategory');
		$categoryarray[0]["catlinkurl"] = $catlinkurl;
		
		$categoryarrayret = json_encode($categoryarray[0]);
		$categoryarrayret = json_decode($categoryarrayret);
		return $categoryarrayret;
	}
	
	public function blog_sql($argu = array()){
		
		$categoryid = round($argu["categoryid"], 0);
		$tagid = round($argu["tagid"], 0);
		$posterid = round($argu["posterid"], 0);
		$byear = round($argu["byear"], 0);
		$bmonth = round($argu["bmonth"], 0);
		
        $query_sql = "select distinct a.*";
        $query_form = " from tbl_blog as a,";
        $query_where = " where";
		
		if ($categoryid > 0){
			$query_where .= " a.category_id = '". $categoryid ."' and";
		}
		
		if ($posterid > 0){
			$query_where .= " a.poster_id = '". $posterid ."' and";
		}
		
		if ($byear > 0){
			$query_where .= " YEAR(a.reg_date) = '". $byear ."' and";
		}
		
		if ($bmonth > 0){
			$query_where .= " MONTH(a.reg_date) = '". $bmonth ."' and";
		}
		
		if ($tagid > 0){
			$query_form .= " tbl_blog_tag_assign as b,";
			$query_where .= " a.id = b.blog_id and";
			$query_where .= " b.tag_id = '". $tagid ."' and";
		}
	
        $query_where .= " a.status_id = 1 and";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
  	}
	
	public function total_blog_found($sql){
		global $db;
		$sqlm = str_replace("select distinct a.*","select count(distinct a.id) as ttl",$sql);
		$foundm = $db->total_record_count($sqlm);
		return $foundm;
	}
	
	public function blog_list($p, $argu = array(), $az = 0){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';
		$categoryid = round($argu["categoryid"], 0);
		$isevent = round($argu["isevent"], 0);
		$sortbyoption = round($argu["sortbyoption"], 0);

        $dcon = $cm->pagination_record_list;
		//$dcon = 4;
		$page = ($p - 1) * $dcon;
		if ($page <= 0){ $page = 0; }
		$limitsql = " LIMIT ". $page .", ". $dcon;
		
		$date_check_sql = '';
		if ($isevent == 1){
			$sorting_sql = "reg_date";
			$date_check_sql = ' and reg_date > CURDATE()';
			$title_before = "Upcoming ";
		}elseif ($isevent == 2){
			$sorting_sql = "reg_date desc";
			$date_check_sql = ' and reg_date <= CURDATE()';
			$title_before = "Past ";
		}else{
			$sorting_sql = "reg_date desc, id desc";
			if ($sortbyoption == 1){
				$sorting_sql = "reg_date";
			}
		}
		
        $sql = $this->blog_sql($argu);
        $foundm = $this->total_blog_found($sql);
		
		$sql .= $date_check_sql;
        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
        if ($found > 0){			
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
			
				$categoryarray = $this->blog_category_fields($category_id);
				$categoryname = $categoryarray->name;
				$categoryslug = $categoryarray->slug;
				$catlinkurl = $categoryarray->catlinkurl;
				
				$linkurl = $cm->get_page_url($slug, 'blog');
				$fullurl = $cm->site_url . $linkurl;				
				$reg_date_d = $cm->display_date($reg_date, 'y', 9);		
				
				$tagname = $cm->display_multiplevl($id, 'tbl_blog_tag_assign', 'tag_id', 'blog_id', 'tbl_blog_tag', 1);
				if ($tagname != ""){
					$tagname = '<span class="posttag">'. $tagname .'</span>';
				}
				
				$imagefolder = 'blogimage/thumb/';
				if ($blog_image == ""){ $blog_image = 'no.jpg'; }
				
				//display_date
				$date_text = '';
				if ($display_date == 1){
					$date_text = '<span class="postdate">'. $reg_date_d .'</span>';
				}
				
                $returntext .= '
				<div class="editordivrow blogrow">
					<div class="edimgleft"><img src="'. $cm->folder_for_seo . $imagefolder . $blog_image .'" alt=""></div>
					<div class="edcontentright">
						<h3 class="bloghead"><a href="'. $linkurl .'">'. $name .'</a></h3>
					
						<div class="blogmeta">
						'. $date_text .'
						<span class="postcategory"><a title="Category" href="'. $catlinkurl .'">'. $categoryname .'</a></span>
						'. $tagname .'
						</div>
						
						<div class="blog">'. $small_description .'</div>
						<a href="'. $linkurl .'" class="button">Read More</a>
						'. $this->blog_share_button($name, $small_description, $fullurl) .'	
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
                <a href="javascript:void(0);" p="'. $p .'" c=\''. json_encode($argu) .'\' class="moreblog button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
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
	
	public function blog_list_main($argu = array()){
		global $db, $cm;		
		$p = 1;
		$retval = json_decode($this->blog_list($p, $argu));
		$returntext = '
		  <div id="filtersection" class="mostviewed">
		  '. $retval[0]->doc .'
		  <div class="clear"></div>
		  </div>
		  
		  <div class="mostviewed">
			<p class="t-center">'. $retval[0]->moreviewtext .'</p>
		  </div>
		';
		
		$returntext .= '
	  	<script type="text/javascript">
		$(document).ready(function(){
			$.fn.filterblog = function(p, c){
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						p:p,
						searchfields:c,
						subsection:3,
						az:19,
						dataType: \'json\'
					},
					function(data){
						data = $.parseJSON(data);
						content = data[0].doc;
						moreviewtext = data[0].moreviewtext;
						if (content != ""){
							if (p == 1){
								$("#filtersection").html(content);
							}else{
								$("#filtersection").append(content);
							}
						}else{
							$(\'#filtersection\').html(\'Sorry. Record unavailable.\');
						}
						$(".t-center").html(moreviewtext);
						$(document.body).trigger("sticky_kit:recalc");
					});
			}
	
			$(".main").on("click", ".moreblog", function(){
				var p = $(this).attr("p");
				var c = $(this).attr("c");
				$(this).filterblog(p, c);
			});
		});
		</script>
	  ';	  
	  return $returntext;
	}
	
	public function check_blog_with_return($checkval, $checkopt = 0){
		global $db, $cm, $frontend;
		
		if ($checkopt == 1){
			$checkfield = 'slug';
		}else{
			$checkfield = 'id';
		}
		
		$sql = "select * from tbl_blog where ". $checkfield ." = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);		
		$found = count($result);

		if ($found == 0){
			header('Location: '. $cm->sorryredirect(3));
			exit;
		}
		return $result;
	}
	
	public function check_blog_category_with_return($checkval, $checkopt = 0){
		global $db, $cm, $frontend;
		$sql = "select * from tbl_blog_category where slug = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found == 0){
			header('Location: '. $cm->sorryredirect(3));
			exit;
		}
		return $result;
	}
	
	public function check_blog_tag_with_return($checkval, $checkopt = 0){
		global $db, $cm, $frontend;
		$sql = "select * from tbl_blog_tag where slug = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found == 0){
			header('Location: '. $cm->sorryredirect(3));
			exit;
		}
		return $result;
	}
	
	//blog share button
	public function blog_share_button($name, $small_description, $fullurl, $template = 1){
	  global $cm;
	  
	  if ($template == 2){
		  //$cm->googleplus_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 3));
		  $returntext = '
		  <span class="ng-social">Share:
		  <ul>
		  	'. $cm->facebook_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 3)) .'
			'. $cm->twitter_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 3)) .'
			'. $cm->linkedin_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 3)) .'
		  </ul>
		  </span>
		  ';
	  }else{
		  //$cm->googleplus_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 1));
		  $returntext = '
		  <div class="social">
				<ul>  
					<li class="title">Share: </li>          
					'. $cm->facebook_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 1)) .'
					'. $cm->twitter_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 1)) .'
					'. $cm->linkedin_share_url(array("title" => $name, "content" => $small_description, "fullurl" => $fullurl, "template" => 1)) .'                                   
				</ul>
		  </div>
		  ';
	  }	  	  
	  return $returntext;
	}
	
	//blog category table view
	public function blog_category_sql(){
		$query_sql = "select *";
		$query_form = " from tbl_blog_category,";
		$query_where = " where";
		
		$query_where .= "  status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
	}
	
	public function total_blog_category_found($sql){
		global $db;
		$sqlm = str_replace("select *","select count(*) as ttl",$sql);
		$foundm = $db->total_record_count($sqlm);
		return $foundm;
	}
	
	public function blog_category_list($p, $az = 0){
		global $db, $cm;
		$returntext = '';
		$moreviewtext = '';
		
		$dcon = $cm->pagination_record_list;		
        $page = ($p - 1) * $dcon;
        if ($page <= 0){ $page = 0; }
		
		$sorting_sql = "rank";
        $limitsql = " LIMIT ". $page .", ". $dcon;
        $sql = $this->blog_category_sql();
		$foundm = $this->total_blog_category_found($sql);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		$remaining = $foundm - ($p * $dcon);
		
		if ($found > 0){
			if ($p == 1){
                $returntext .= '
                <div class="divrow thd">
                    <div class="bcategory">Category</div>
					<div class="btotalpost"># Post</div>
                    <div class="btotalcomment"># Comment</div>
					<div class="boption"></div>
                    <div class="clearfix"></div>
                </div>
            ';
            }
			
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }				

				$linkurl = $cm->get_page_url($slug, 'blogcategory');
				$addblogpost = $cm->get_page_url($slug, 'addblogpost');
				$totalpost_category = $this->get_total_blog_by_category($id);
				$totalcomment_category = $this->get_total_blog_comment_by_category($id);
				
				$returntext .= '
                <div class="divrow">
                    <div class="bcategory"><a title="Open" href="'. $linkurl .'">'. $name .'</a></div>
                    <div class="btotalpost">'. $totalpost_category .'</div> 
					<div class="btotalcomment">'. $totalcomment_category .'</div>
					<div class="boption">
					<a title="Open" href="'. $linkurl .'"><img src="'. $cm->folder_for_seo .'images/open-icon.png" /></a>
					<a title="Add Post" href="'. $addblogpost .'"><img src="'. $cm->folder_for_seo .'images/addpost-icon.png" /></a>
					</div>					
                    <div class="clearfix"></div>
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
                <a href="javascript:void(0);" p="'. $p .'" class="moreblogcategorylist btn2 loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
		}else{
			if ($p == 1){
				$returntext = 'Nothing found';
			}
		}
		
		$returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
	}
	
	public function blog_category_list_main(){
		global $db, $cm;
		$p = 1;
		$retval = json_decode($this->blog_category_list($p));
		$returntext = '
		  <div id="filtersection" class="mostviewed">
		  '. $retval[0]->doc .'
		  <div class="clear"></div>
		  </div>
		  
		  <div class="mostviewed">
			<p class="t-center">'. $retval[0]->moreviewtext .'</p>
		  </div>
		';
		
		$returntext .= '
	    <script type="text/javascript">
		$(document).ready(function(){
			$.fn.filterblogcategorylist = function(p){
				var b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						p:p,
						subsection:7,
						az:4,
						dataType: \'json\'
					},
					function(data){
						data = $.parseJSON(data);
						content = data[0].doc;
						moreviewtext = data[0].moreviewtext;
						if (content != ""){
							if (p == 1){
								$("#filtersection").html(content);
							}else{
								$("#filtersection").append(content);
							}
						}else{
							$(\'#filtersection\').html(\'Sorry. Record unavailable.\');
						}
						$(".t-center").html(moreviewtext);
					});
			}
	
			$(".main").on("click", ".moreblogcategorylist", function(){
				var p = $(this).attr("p");
				$(this).filterblogcategorylist(p);
			});
		});
		</script>
	  ';	  
	  return $returntext;
	}
	
	//blog box row
	public function display_box_blog_row($row, $template = 0){
		global $db, $cm;
		$returntext = '';
		
		foreach($row AS $key => $val){
			${$key} = $cm->filtertextdisplay($val);
		}
		if ($blog_image == ""){ $blog_image = "no.jpg"; }
		$reg_date_d = $cm->display_date($reg_date, 'y', 8);
		$details_url = $cm->get_page_url($slug, "blog");
		
		$categoryarray = $this->blog_category_fields($category_id);
		$categoryname = $categoryarray->name;
		
		if ($template == 1){
			//display_date
			$date_text = '';
			if ($display_date == 1){
				$date_text = '<h6>'. $reg_date_d .'</h6>';
			}
			
			$returntext .= '
			<li>
				<h3><a href="'. $details_url .'">'. $name .'</a></h3>
				'. $date_text .'
			</li>		
			';
		}elseif ($template == 2){
			$description = $cm->fc_word_count($description, 50);
			$returntext .= '
				<li class="full">
					<div class="blog_template2 clearfixmain">
						<div class="blogimage_template2"><a style="background-image:url('. $cm->folder_for_seo .'blogimage/thumb/'. $blog_image .')" href="'. $details_url .'"></a></div>
						<div class="blogcontet_template2">
							<h3>'. $name .'</h3>
							<div class="spacerbottom clearfixmain">'. $description .'</div>
							<a class="readmore" href="'. $details_url .'">Read More</a>
						</div>
					</div>	
				</li>				
			';
		}elseif ($template == 3){
			//display_date
			$date_text = '';
			if ($display_date == 1){
				//$date_text = '<p class="date">'. $reg_date_d .'</p>';
			}
								
			$returntext .= '			
			<li style="background-image:url('. $cm->folder_for_seo .'blogimage/thumb/'. $blog_image .');">
				<div class="newstext clearfixmain">
					<div class="newstextin">
						<h3><a href="'. $details_url .'">'. $name .'</a></h3>
						'. $date_text .'
						<p class="news">'. $small_description .'</p>
						<a class="button" href="'. $details_url .'">Read More</a>
					</div>			
				</div>
				<div class="clearfix"></div>			
			</li>		
			';
		}elseif ($template == 4){
			$small_description = $cm->fc_word_count($small_description, 200);
			$returntext .= '
			<li>
				<div class="ng-news-flexdiv">
					<div class="ng-news-left"><img src="'. $cm->folder_for_seo .'blogimage/thumb/'. $blog_image .'" title="'. $name .'" alt="'. $name .'"></div>
					<div class="ng-news-right">
						<div class="ng-news-top">
							<h3>'. $name .'</h3>
							<h4>'. $categoryname .'</h4>
							<p>'. $small_description .'...</p>
						</div>
						<div class="ng-news-bottom">
							<a href="'. $details_url .'" class="ng-button">Read More</a>
							'. $this->blog_share_button($name, $small_description, $fullurl, 2) .'
						</div>
					</div>
				</div>
			</li>
			';
		}else{
			//display_date
			$date_text = '';
			if ($display_date == 1){
				$date_text = '<p class="date">'. $reg_date_d .'</p>';
			}
			$small_description = $cm->get_sort_content_description($small_description, 80);
							
			$returntext .= '			
			<li>
				<div class="newsimg">
					<a href="'. $details_url .'"><img src="'. $cm->folder_for_seo .'blogimage/thumb/'. $blog_image .'" title="'. $name .'" alt="'. $name .'"></a>						
				</div>
				<div class="newstext">
					<h3><a href="'. $details_url .'">'. $name .'</a></h3>
					'. $date_text .'
					<p class="news">'. $small_description .'</p>
					<a class="arrow" href="'. $details_url .'">Read More</a>				
				</div>
				<div class="clearfix"></div>			
			</li>		
			';
		}
		
		return $returntext;
	}
	
	//featured blog
	public function display_featured_blog($argu = array()){
		global $db, $cm;
		$returntext = '';
		$default_title = "Blog";
		$title_before = '';
		
		$innerpage = round($argu["innerpage"], 0);
		$categoryid = round($argu["categoryid"], 0);
		$featured = round($argu["featured"], 0);
		$isevent = round($argu["isevent"], 0);
		$sortbyoption = round($argu["sortbyoption"], 0);
		$limit = round($argu["limit"], 0);
		
		$date_check_sql = '';
		if ($isevent == 1){
			$limit = 1;
			$sorting_sql = "reg_date";
			$date_check_sql = ' reg_date > CURDATE() and';
			$title_before = "Upcoming ";
		}elseif ($isevent == 2){
			$limit = 1;
			$sorting_sql = "reg_date desc";
			$date_check_sql = ' reg_date <= CURDATE() and';
			$title_before = "Past ";
		}else{
			$title_before = "Latest ";
			if ($featured == 1){
				$title_before = "Featured ";
			}
			
			if ($limit <= 0){ $limit = 3; }
			$sorting_sql = "reg_date desc, id desc";
			if ($sortbyoption == 1){
				$sorting_sql = "reg_date";
			}
		}
		
		if ($title_before != ""){
			//$title_before = '<span>' . $title_before . '</span>';
		}
		
		$query_sql = "select *,";
		$query_form = " from tbl_blog,";
		$query_where = " where";
		
		if ($categoryid > 0){
			$query_where .= " category_id = '". $categoryid ."' and";
			$default_title = $cm->get_common_field_name("tbl_blog_category", "name", $categoryid);
		}
		
		$query_where .= " status_id = 1 and";
		$query_where .= $date_check_sql;
		
		if ($featured == 1){
			$query_where .= " featured_post = 1 and";
		}
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$sql = $sql." order by ". $sorting_sql ." limit 0, " . $limit;

		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		$contauner_start = "";
		$contauner_end = "";
		
		if ($innerpage == 1){
			$contauner_start = '<h2 class="singlelinebottom30">'. $title_before . '<span>' . $default_title . '</span></h2>';
		}else{
			$contauner_start = '
			<div class="homesection4 clearfixmain"><div class="container clearfixmain">
			<h2 class="singlelinebottom30">'. $title_before . '<span>' . $default_title . '</span></h2>
			';
			
			$contauner_end = '</div></div>';
		}
		
		
		if ($found > 0){
			$news_url = $this->get_blog_url($categoryid, $isevent);
			$returntext .= $contauner_start . '			
			<ul class="latestnews">
			';
			foreach($result as $row){
				$returntext .= $this->display_box_blog_row($row, 3);
			}
			$returntext .= '
			</ul>
			<div class="clearfix"></div>					
			' . $contauner_end;
		}	  
		return $returntext;
	}
	
	//latest news
	public function display_latest_news($argu = array()){
		global $db, $cm;
		$returntext = '';
		$default_title = "News &amp; Events";
		
		$categoryid = round($argu["categoryid"], 0);
		$limit = round($argu["limit"], 0);
		
		if ($limit <= 0){ $limit = 3; }
		$sorting_sql = "reg_date desc, id desc";
		
		$query_sql = "select *,";
		$query_form = " from tbl_blog,";
		$query_where = " where";
		
		//$query_where .= " category_id IN (1,2) and";
		
		$query_where .= " status_id = 1 and";
		//$query_where .= $date_check_sql;
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$sql = $sql." order by ". $sorting_sql ." limit 0, " . $limit;

		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$news_url = $this->get_blog_url($categoryid, $isevent);
			$returntext .= '<h1 class="ng-h1 uppercase ml-5"><span>NEWS & EVENTS</span></h1>			
			<ul class="ng-news-list">
			';
			foreach($result as $row){
				$returntext .= $this->display_box_blog_row($row, 4);
			}
			$returntext .= '
			</ul>
			';
		}	  
		return $returntext;
	}
	
	//recent blog
	public function display_recent_blog($argu = array()){
		global $db, $cm;
		$returntext = '';
		$categoryid = round($argu["categoryid"], 0);
		$limit = round($argu["limit"], 0);
		if ($limit <= 0){ $limit = 1; }
		$query_sql = "select *,";
		$query_form = " from tbl_blog,";
		$query_where = " where";
		
		if ($categoryid > 0){
			$query_where .= " category_id = '". $categoryid ."' and";
		}
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$sql = $sql." order by reg_date desc limit 0, " . $limit;
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$news_url = $this->get_blog_url($categoryid);
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				//if ($blog_image == ""){ $blog_image = "no.jpg"; }		
				//$small_description = $cm->get_sort_content_description($small_description, 100);	  
				//$reg_date_d = $cm->display_date($reg_date, 'y', 8);
				$details_url = $cm->get_page_url($slug, "blog");
				
				$returntext .= '
				<p>'. $small_description .'</p>
				<p><a class="readmore" href="'. $details_url .'">View</a></p>
				';
			}
		}	  
		return $returntext;
	}
	
	//most read blog - column
	public function display_mostread_blog($argu = array()){
		global $db, $cm;
		$returntext = '';
		$limit = round($argu["limit"], 0);
		$sidebar = round($argu["sidebar"], 0);
		$category = round($argu["category"], 0);
		if ($limit <= 0){ $limit = 3; }
		
		$sql = "select distinct a.*, sum(b.total_view) as total_view from tbl_blog as a LEFT JOIN tbl_blog_view as b ON a.id = b.blog_id where";
		
		if ($category > 0){		
			$sql .= " a.category_id = '". $category ."' and";
		}
		$sql .= " a.status_id = 1 GROUP BY a.id";
		$sql = $sql." order by total_view desc limit 0, " . $limit;
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
		  $news_url = $this->get_blog_url($category);		  
		  
		  if ($sidebar == 1){
			  $returntext .= '
			  	<h2 class="sidebartitle">Most Read</h2>
				<div class="leftrightcolsection notopborder clearfix">				
			  ';
		  }else{
			  $returntext .= '
			  <h2 class="icon1">Most Read</h2>
			  <div class="widget">
			  ';
		  }
		  
		  $returntext .= '		 
		  <ul class="latestnewssidebar">
		  ';
		  foreach($result as $row){
			  $returntext .= $this->display_box_blog_row($row, 1);
		  }
		  $returntext .= '
		  </ul>
		  <div class="clear"></div>
		  </div>		  
		  ';
		}	  
		return $returntext;
	}
	
	//update blog view
	public function update_blog_view($blog_id){
		global $db, $cm;
		$dt = date("Y-m-d");
		
		$sql = "select count(*) as ttl from tbl_blog_view where blog_id = '". $blog_id ."' and reg_date = '". $dt ."'";
		$ifound = $db->total_record_count($sql);
		
		if ($ifound > 0){
			$sql = "update tbl_blog_view set total_view = (total_view + 1) where blog_id = '". $blog_id ."' and reg_date = '". $dt ."'";
			$db->mysqlquery($sql);
		}else{			
			$sql = "insert into tbl_blog_view (blog_id
										   , total_view
										   , reg_date) values ('". $blog_id ."'
										   , '1'
										   , '". $dt ."')";
			$db->mysqlquery($sql);
		}
	}
	/*-----------/BLOG SECTION--------------*/	
	
	/*-----------REMOVE FUNCTION ADMIN------------*/
	
	//remove blog category
	public function delete_blog_category($category_id){
		global $db;
		
		$sql = "select id from tbl_blog where category_id = '". $category_id ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$blog_id = $row["id"];
			$this->delete_blog($blog_id);
		}
		
		$sql = "delete from tbl_blog_category where id = '". $category_id ."'";
		$db->mysqlquery($sql);
	}
	
	//remove blog tag
	public function delete_blog_tag($tag_id){
		global $db;		
		$sql = "delete from tbl_blog_tag where id = '". $tag_id ."'";
		$db->mysqlquery($sql);
		
		$sql = "delete from tbl_blog_tag_assign where tag_id = '". $tag_id ."'";
		$db->mysqlquery($sql);
	}
	
	//remove blog
	public function delete_blog($blog_id){
		global $db, $fle;
		
		$fimg1 = $db->total_record_count("select blog_image as ttl from tbl_blog where id = '". $blog_id ."'");
		if ($fimg1 != ""){
			$fle->filedelete("../blogimage/".$fimg1);
			$fle->filedelete("../blogimage/thumb/".$fimg1);
		}
		
		$sql = "delete from tbl_blog where id = '". $blog_id ."'";
        $db->mysqlquery($sql);
	}	
	
	/*-----------/REMOVE FUNCTION ADMIN------------*/
}
?>