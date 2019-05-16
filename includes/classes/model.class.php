<?php
class Modelclass {
	public $availablemakeids = "1146";
	public $model_im_width_t = 500;
    public $model_im_height_t = 333;
	
	public $model_im_width_s1 = 1500;
    public $model_im_height_s1 = 585;
	
	public $model_im_width_s2 = 1500;
    public $model_im_height_s2 = 1000;
	
	public $model_im_width_s3 = 1500;
    public $model_im_height_s3 = 1000;
	
	public $model_im_width_s4 = 1000;
    public $model_im_height_s4 = 600;
	
	public $ft_to_meter = 0.3048;

	
	///------ COMMON SECTION ------
	public function get_available_make_combo($manufacturer_id = 0){
        global $db;
        $vsql = "select id, name from tbl_manufacturer where id IN (". $this->availablemakeids .")";
        $vsql .= " order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($manufacturer_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_model_category_combo($category_id = 0){
        global $db;
		$returntext = '';
        $vsql = "select id, name from tbl_model_category where status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
			
			$bck = '';
			if ($category_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_model_combo($model_id = 0){
        global $db;
		$returntext = '';
        $vsql = "select id from tbl_model where status_id = 1 order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $this->get_model_name($c_id);
			
			$bck = '';
			if ($category_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntext .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
		
		return $returntext;
    }
	
	public function get_common_number_combo($numberval, $upto = 100){
		$returntext = '';
		for ($xk = 1; $xk <= $upto; $xk++){
			$vck = '';
			if ($numberval == $xk){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $xk . '"'. $vck .'>'. $xk .'</option>';
		}
		
		return $returntext;
    }
	
	//convert feet to meter
	public function feet_to_meter($feetval){
		$convertval = $this->ft_to_meter;
        $newval = $feetval * $convertval;
        $newval = round($newval, 2);
        return $newval;
	}
	
	//get model full name
	public function get_model_name($model_id){
		global $db, $cm;
        $model_ar = $cm->get_table_fields('tbl_model', 'make_id, category_id, name', $model_id);
		
		$make_id = $model_ar[0]['make_id'];		
		$makear = $cm->get_table_fields('tbl_manufacturer', 'slug, name', $make_id);
		$makeslug = $makear[0]["slug"];
		$make_name = $makear[0]["name"];
		
		$category_id = $model_ar[0]['category_id'];
		$cat_name = $cm->get_common_field_name('tbl_model_category', 'name', $category_id);
		
		$name = $model_ar[0]['name'];
		$model_name = $make_name . ' '. $cm->filtertextdisplay($name) . ' '. $cat_name;
		return $model_name;
	}
	
	//get model slug
	public function create_model_slug($model_id){
        global $db, $cm;
        $model_slug = $this->get_model_name($model_id);
		$model_slug = $cm->create_slug($model_slug);
		return $model_slug;
    }
	
	//get model first image
	public function get_model_first_image($model_id, $picktitle = 0){
        global $db, $cm;
		
		if ($picktitle == 1){			
			$sql = "select imgpath from tbl_model_photo where model_id = '". $model_id ."' and category_id = 1 and imgpath != '' and status_id = 1 order by rank limit 0,1";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];				
				$imgpath = $cm->filtertextdisplay($row["imgpath"]);
			}else{				
				$imgpath = "no.jpg";
			}
			
			if ($im_descriptions == ""){
				$im_descriptions = $this->get_model_name($model_id);
			}
			
			$boatimg_data_ar = array(
				"alttag" => $im_descriptions,
				"imgpath" => $imgpath
			);
			return json_encode($boatimg_data_ar);
		
		}else{
			$imgpath = $db->total_record_count("select imgpath as ttl from tbl_model_photo where model_id = '". $model_id ."' and category_id = 1 and imgpath != '' and status_id = 1 order by rank limit 0,1");
			if ($imgpath == ""){ $imgpath = "no.jpg"; }
			return $imgpath;
		}
    }
	
	//search fields list
	public function search_fields_ar(){
		$returnar[] = array(
			'id' => 1,
			'name' => 'Size In Feet'
		);
		
		$returnar[] = array(
			'id' => 2,
			'name' => 'Draft Max In Feet'
		);
		
		$returnar[] = array(
			'id' => 3,
			'name' => 'Beam Max'
		);
		
		$returnar[] = array(
			'id' => 4,
			'name' => 'Number of Cabins'
		);		
		
		return json_encode($returnar);
	}
	
	//search filed active/inactive update to db
	public function search_field_update(){
		global $db, $cm;
		
		$fieldlists_ar = array();
		foreach ($_POST["search_fields"] as $search_fields_v){
			$search_fields_v = round($search_fields_v);
			if ($search_fields_v > 0){
				$fieldlists_ar["f" . $search_fields_v] = $search_fields_v;
			}
		}
		$fieldlists_ar = json_encode($fieldlists_ar);
		
		$sql = "update tbl_model_search_fields set fieldlists = '". $cm->filtertext($fieldlists_ar) ."' where id = 1";
		$db->mysqlquery($sql);
	}
	
	///------ ADMIN SECTION ---------
	
	//photo category tab
	public function get_model_photo_category_tab($category_id, $make_id, $model_id){
		global $db;
		$returntext = '';
		$sql = "select id, name from tbl_model_photo_category where status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$returntext .= '<ul class="syscategory">';
			
			foreach($result as $row){
				$c_id = $row['id'];
				$cname = $row['name'];
				$activeclass = '';
				if ($c_id == $category_id){
					$activeclass = ' class="active"';
				}
				
				$returntext .= '<li><a'. $activeclass .' href="model-image.php?make_id='. $make_id .'&photocategoryid='. $c_id .'&id='. $model_id .'">'. $cname .'</a></li>';
			}
			$returntext .= '</ul>';
		}		
		return $returntext;
	}
	
	//check model
	public function check_model($make_id, $model_id){
        global $db, $cm;
		$sql = "select * from tbl_model where id = '". $model_id ."' and make_id = '". $make_id ."'";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found == 0){
			$_SESSION["admin_sorry"] = "ERROR! Invalid Manufacturer - Model selection.";
			header('Location: sorry.php');
			exit;
		}
		return $result;        
    }
	
	//model insert update
	public function model_insert_update(){
		global $db, $cm, $fle, $adm;
		
		//get posted value
		$category_id = round($_POST["category_id"], 0);
		$name = $_POST["name"];		
		$construction = $_POST["construction"];
		
		$length = round($_POST["length"], 2);
		$max_beam = round($_POST["max_beam"], 2);
		$draft = round($_POST["draft"], 2);
		
		$engines = $_POST["engines"];
		$cruising_speed = $_POST["cruising_speed"];
		$top_speed = $_POST["top_speed"];
		$fuel_capacity = $_POST["fuel_capacity"];
		$water_capacity = $_POST["water_capacity"];
		$mainsail = $_POST["mainsail"];
		$genoa = $_POST["genoa"];
		$living_space = $_POST["living_space"];
		$guests = $_POST["guests"];
		$category = $_POST["category"];
		$naval_architecture = $_POST["naval_architecture"];
		$design = $_POST["design"];
		$description = $_POST["description"];
		$total_cabin = round($_POST["total_cabin"], 0);
		
		$m1 = $_POST["m1"];
		$m2 = $_POST["m2"];
		$m3 = $_POST["m3"];
		
		$status_id = round($_POST["status_id"], 0);
		$oldrank = round($_POST["oldrank"], 0);
		$make_id = round($_POST["make_id"], 0);
		$ms = round($_POST["ms"], 0);
		//end
		
		//process
		$dt = date("Y-m-d H:i:s");
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_model") + 1;
			$sql = "insert into tbl_model (make_id, reg_date) values ('". $make_id ."', '". $dt ."')";
			$iiid = $db->mysqlquery_ret($sql);	
			
			//create folder
			$source = "../models/rawfolder";
			$destination = "../models/".$iiid;
			$fle->copy_folder($source, $destination);		
		}else{
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_blog_tag set make_id = '". $make_id ."' where id = '". $ms ."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
		}
		$slug = $this->create_model_slug($iiid);
		
		$sql = "update tbl_model set category_id = '". $category_id ."'
		, name = '". $cm->filtertext($name) ."'
		, construction = '". $cm->filtertext($construction) ."'
		, length = '". $length ."'
		, max_beam = '". $max_beam ."'
		, draft = '". $draft ."'
		, engines = '". $cm->filtertext($engines) ."'
		, cruising_speed = '". $cm->filtertext($cruising_speed) ."'
		, top_speed = '". $cm->filtertext($top_speed) ."'
		, fuel_capacity = '". $cm->filtertext($fuel_capacity) ."'
		, water_capacity = '". $cm->filtertext($water_capacity) ."'
		, mainsail = '". $cm->filtertext($mainsail) ."'
		, genoa = '". $cm->filtertext($genoa) ."'
		, living_space = '". $cm->filtertext($living_space) ."'
		, guests = '". $cm->filtertext($guests) ."'
		, category = '". $cm->filtertext($category) ."'
		, naval_architecture = '". $cm->filtertext($naval_architecture) ."'
		, design = '". $cm->filtertext($design) ."'
		, total_cabin = '". $total_cabin ."'
		, description = '". $cm->filtertext($description) ."'
		, status_id = '". $status_id ."'
		, rank = '". $rank ."'		
		, slug = '". $cm->filtertext($slug) ."'
		, m1 = '". $cm->filtertext($m1) ."'
		, m2 = '". $cm->filtertext($m2) ."'
		, m3 = '". $cm->filtertext($m3) ."' where id = '". $iiid ."'";
		$db->mysqlquery($sql);
		
		//brochure upload
		$filename = $_FILES['brochurefilepath']['name'];
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_attachment_ext, $filename);
			if ($wh_ok == "y"){
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."-".$filename;
			
				$target_path = "../models/" . $iiid . "/modelbrochure/";
				$target_path = $target_path . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['brochurefilepath']['tmp_name'], $target_path);
		
				$sql = "update tbl_model set brochurefilepath = '". $cm->filtertext($filename1)."' where id = '". $iiid ."'";
				$db->mysqlquery($sql);
			}
		}
		
		//mp4 video upload
		$filename = $_FILES['videofilepath']['name'];
		if ($filename != ""){
			$wh_ok = $fle->check_file_ext($cm->allow_video_ext, $filename);
			if ($wh_ok == "y"){
				$filename = $fle->uploadfilename($filename);
				$filename1 = $iiid."-".$filename;
			
				$target_path = "../models/" . $iiid . "/modelvideo/";
				$target_path = $target_path . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['videofilepath']['tmp_name'], $target_path);
		
				$sql = "update tbl_model set videofilepath = '". $cm->filtertext($filename1)."' where id = '". $iiid ."'";
				$db->mysqlquery($sql);
			}
		}		
		//end
		
		$returnar = array(
			"ms" => $ms,
			"make_id" => $make_id
		);
		
		return json_encode($returnar);
	}
	
	//insert model image
	public function insert_model_image(){
		global $db, $cm, $fle;
		
		//form post
		$iiid = round($_POST["ms"], 0);
		$crop_option = round($_POST["crop_option"], 0);
		$rotateimage = round($_POST["rotateimage"], 0);
		$make_id = round($_POST["make_id"], 0);
		$photocategoryid = round($_POST["photocategoryid"], 0);
		//end
		
		$filename = $_FILES['file']['name'];
		$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
		if ($wh_ok == "y"){
			$i_rank = $db->total_record_count("select max(rank) as ttl from tbl_model_photo where model_id = '". $iiid ."' and category_id = '". $photocategoryid ."'") + 1;
			$i_iiid = $cm->get_unq_code("tbl_model_photo", "id", 20);
			$status_id = 1;
			
			$sql = "insert into tbl_model_photo (id, make_id, model_id, category_id, status_id, rank) values ('". $i_iiid ."', '". $make_id ."', '". $iiid ."', '". $photocategoryid ."', '". $status_id ."', '". $i_rank ."')";
			$db->mysqlquery($sql);
			
			$filename_tmp = $_FILES['file']['tmp_name'];
			$filename = $fle->uploadfilename($filename);
			$filename1 = $i_iiid."model".$filename;
			$target_path_main = "../models/" . $iiid . "/modelimage/";
			
			//thumbnail image
			$r_width = $this->model_im_width_t;
			$r_height = $this->model_im_height_t;
			$target_path = $target_path_main;        
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}
			
			if ($photocategoryid == 4){
				//bigger image
				$r_width = $this->model_im_width_s4;
				$r_height = $this->model_im_height_s4;
				$target_path = $target_path_main . "bigger/";
				if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}
			}elseif ($photocategoryid == 3){
				//bigger image
				$r_width = $this->model_im_width_s3;
				$r_height = $this->model_im_height_s3;
				$target_path = $target_path_main . "bigger/";
				if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}
			}elseif ($photocategoryid == 2){
				//bigger image
				$r_width = $this->model_im_width_s2;
				$r_height = $this->model_im_height_s2;
				$target_path = $target_path_main . "bigger/";
				if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}
			}else{
				//bigger image
				$r_width = $this->model_im_width_s1;
				$r_height = $this->model_im_height_s1;
				$target_path = $target_path_main . "bigger/";
				/*if ($crop_option == 1){
					$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}else{
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
				}*/
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
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
			
			$sql = "update tbl_model_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
			$db->mysqlquery($sql);
			echo($_POST['index']);
		}
	}
	
	//rotate model image
	public function rotate_model_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$crop_option = round($_POST["hardcrop"], 0);
		$rotateimage = round($_POST["v"], 0);
		//$ycat = round($_POST["ycat"], 0);
		
		$yachtdet = $cm->get_table_fields('tbl_model_photo', 'model_id, category_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$category_id = $oldfilename = $yachtdet->category_id;
		$filename1 = $oldfilename = $yachtdet->imgpath;
		$model_id = $yachtdet->model_id;
		
		$target_path_main = "../models/" . $model_id . "/modelimage/";
		
		$filename_tmp = $target_path_main . "original/" . $filename1;
		$filename1 = $fle->create_different_file_name($filename1);
		$original_filename_rename = $target_path_main . "original/" . $filename1;
		
		//thumbnail image
		$r_width = $this->model_im_width_t;
		$r_height = $this->model_im_height_t;
		$target_path = $target_path_main;
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		if ($category_id == 4){
			//bigger image
			$r_width = $this->model_im_width_s4;
			$r_height = $this->model_im_height_s4;
			$target_path = $target_path_main . "bigger/";
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}
		}elseif ($photocategoryid == 3){
			//bigger image
			$r_width = $this->model_im_width_s3;
			$r_height = $this->model_im_height_s3;
			$target_path = $target_path_main . "bigger/";
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}
		}elseif ($photocategoryid == 2){
			//bigger image
			$r_width = $this->model_im_width_s2;
			$r_height = $this->model_im_height_s2;
			$target_path = $target_path_main . "bigger/";
			if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}
		}else{
			//bigger image
			$r_width = $this->model_im_width_s1;
			$r_height = $this->model_im_height_s1;
			$target_path = $target_path_main . "bigger/";
			/*if ($crop_option == 1){
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}else{
				$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
			}*/
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
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
		$this->delete_model_image($oldfilename, $model_id);
		
		//update filename
		$sql = "update tbl_model_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $cm->filtertext($imid) ."'";
        $db->mysqlquery($sql);
		
		//output image
		$imgpath_d = $target_path_main . "" . $filename1 . "?t=" . time();
		return $imgpath_d;
	}
	
	//model image display
	public function model_image_display_list($model_id, $make_id, $photocategoryid){
        global $db, $cm;
        $returntext = '';
        $im_found = 0;
        $im_sql = "select * from tbl_model_photo where make_id  = '". $make_id ."' and model_id  = '". $model_id ."' and category_id  = '". $photocategoryid ."' order by rank";
        $im_result = $db->fetch_all_array($im_sql);
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
                                //$im_status_d = $cm->get_common_field_name('tbl_common_status', 'name', $im_status_id);
                                //if ($im_status_id == 1){ $ch_opt = 2; }else{ $ch_opt = 1; }

                                $imgpath_d = '-';
                                if ($imgpath != ""){
                                    $target_path_main = 'models/' . $model_id . '/modelimage/';
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
	
	//remove original model image
	public function remove_original_model_image(){
		global $db, $cm, $fle;
		$imid = $_POST["imid"];
		$imid = $cm->filtertext($imid);
	
		$yachtdet = $cm->get_table_fields('tbl_model_photo', 'model_id, imgpath', $imid);
		$yachtdet = (object)$yachtdet[0];
		
		$fimg1 = $yachtdet->imgpath;
		$model_id = $yachtdet->model_id;
		
		$original_img = "../models/". $model_id ."/modelimage/original/" . $fimg1;
		$fle->filedelete($original_img);
		
		$sql = "update tbl_model_photo set keep_original = 0 where id = '".$imid."'";
		$db->mysqlquery($sql);
	}
		
	//delete model image - single
	public function delete_model_image($fimg1, $model_id){
		global $cm, $fle;
		if ($fimg1 != ""){						
			$fle->filedelete("../models/". $model_id ."/modelimage/" . $fimg1);
			$fle->filedelete("../models/". $model_id ."/modelimage/bigger/" . $fimg1);
			
			$original_img = "../models/". $model_id ."/modelimage/original/" . $fimg1;
			if (file_exists($original_img)){
				$fle->filedelete($original_img);
			}
		}
	}
		
	//delete model image - single from Ajax call
	public function delete_model_image_ajax_call($imid){
		global $db, $cm;
		
		$sql = "select model_id, imgpath from tbl_model_photo where id = '". $cm->filtertext($imid) ."'";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$model_id = $row['model_id'];
			$fimg1 = $row['imgpath'];
			$this->delete_model_image($fimg1, $model_id);
		}
		
		$sql = "delete from tbl_model_photo where id = '". $cm->filtertext($imid) ."'";
		$db->mysqlquery($sql);
	}
	
	//delete model image all
	public function delete_model_image_all($model_id){
		global $db;
		$sql = "select imgpath from tbl_model_photo where model_id = '". $model_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['imgpath'];
            $this->delete_model_image($fimg1, $model_id);
        }
		
		$sql = "delete from tbl_model_photo where model_id = '". $model_id ."'";
        $db->mysqlquery($sql);
	}
	
	//delete model brochure
	public function delete_model_brochure($model_id){
		global $db, $fle;		
		$sql = "select brochurefilepath from tbl_model where id = '". $model_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['brochurefilepath'];
            if ($fimg1 != ""){
				$fle->filedelete("../models/". $model_id ."/modelbrochure/" . $fimg1);
			}
        }
		
		$sql = "update tbl_model set brochurefilepath = '' where id = '". $model_id ."'";
        $db->mysqlquery($sql);
	}
	
	//delete model video
	public function delete_model_video($model_id){
		global $db, $fle;		
		$sql = "select videofilepath from tbl_model where id = '". $model_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['videofilepath'];
            if ($fimg1 != ""){
				$fle->filedelete("../models/". $model_id ."/modelvideo/" . $fimg1);
			}
        }
		
		$sql = "update tbl_model set videofilepath = '' where id = '". $model_id ."'";
        $db->mysqlquery($sql);
	}
	
	//delete model
	public function delete_model($model_id){
        global $db, $fle;
	
		$this->delete_model_image_all($model_id); 
		$this->delete_model_brochure($model_id);
		$this->delete_model_video($model_id);		
		
		if ($listing_no != ""){
			$folderpath = "../models/" . $model_id;
			$fle->remove_folder($folderpath);
		}

		$sql = "delete from tbl_model where id = '". $model_id ."'";
        $db->mysqlquery($sql);		
    }
    
	//update model photo rank
	public function update_model_image_rank(){
		   global $db, $cm;
		   parse_str($_POST['data'], $recOrder);
		   $i = 1;
		   foreach ($recOrder['item'] as $value) {
			   $sql = "update tbl_model_photo set rank = '". $i ."' where id = '". $value ."'";
			   $db->mysqlquery($sql);
			   $i++;			
		   }
	}
	
	///------ ADMIN SECTION END ---------
	
	///------ FRONT-END SECTION START ---------
	
	//listing page botton
	public function display_listing_page_bottom($argu = array()){
		global $cm;
		$makeid = round($argu["makeid"], 0);
		
		$returntext = '
		<div class="container clearfixmain">
			<div class="three-buttons-container">	
				<ul class="three-buttons-list clearfixmain">
					<li>
						<div class="clearfixmain">
							<div class="left">
								<img src="'. $cm->folder_for_seo .'images/icon-boat-new.png" alt="" class="full">
							</div>
							<div class="right">
								<h4>Find the Right Model for you</h4>
								<a class="button" href="'. $cm->get_page_url(146, "page") .'">Start Search</a>
							</div>
						</div>
					</li>
					<li>
						<div class="clearfixmain">
							<div class="left">
								<img src="'. $cm->folder_for_seo .'images/icon-brochure-new.png" alt="" class="full">
							</div>
							<div class="right">
								<h4>Request Brochure</h4>
								<a class="button commonpop" href="'. $cm->get_page_url(0, "pop-ask-for-brochure") .'?make_id='. $makeid .'" data-type="iframe">Send Request</a>
							</div>
						</div>
					</li>
					<li>
						<div class="clearfixmain">
							<div class="left">
								<img src="'. $cm->folder_for_seo .'images/icon-calendar-new.png" alt="" class="full">
							</div>
							<div class="right">
								<h4>Contact Specialist</h4>
								<a class="button commonpop" href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-talk-to-specialist") .'?make_id='. $makeid .'" data-type="iframe">appointment</a>
							</div>
						</div>
					</li>
				</ul>   
			</div>
		</div>
		';
		
		return $returntext;
	}
	
	//boat list
	public function boat_model_sql($argu = array()){
		$fqstring = round($_REQUEST["fqstring"], 0);
		
		if ($fqstring == 1){
			$makeid = round($_REQUEST["makeid"], 0);
			$lengthmin = round($_REQUEST["lengthmin"], 2);
			$lengthmax = round($_REQUEST["lengthmax"], 2);
			$draftmin = round($_REQUEST["draftmin"], 2);
			$draftmax = round($_REQUEST["draftmax"], 2);
			$beammin = round($_REQUEST["beammin"], 2);
			$beammax = round($_REQUEST["beammax"], 2);
			$cabinmin = round($_REQUEST["cabinmin"], 0);
			$cabinmax = round($_REQUEST["cabinmax"], 0);
		}else{
			$makeid = round($argu["makeid"], 0);
		}
		
		$searchfileds_ar = array();
				
        $query_sql = "select *";
        $query_form = " from tbl_model,";
        $query_where = " where";
		
		if ($makeid > 0){
			$query_where .= " make_id = '". $makeid ."' and";
			$searchfileds_ar["makeid"] = $makeid;
		}
		
		if ($lengthmin > 0){
			$query_where .= " length >= '". $lengthmin ."' and";
			$searchfileds_ar["lengthmin"] = $lengthmin;
		}
		if ($lengthmax > 0){
			$query_where .= " length <= '". $lengthmax ."' and";
			$searchfileds_ar["lengthmax"] = $lengthmax;
		}
		
		if ($beammin > 0){
			$query_where .= " max_beam >= '". $beammin ."' and";
			$searchfileds_ar["beammin"] = $beammin;
		}
		if ($beammax > 0){
			$query_where .= " max_beam <= '". $beammax ."' and";
			$searchfileds_ar["beammax"] = $beammax;
		}
		
		if ($draftmin > 0){
			$query_where .= " draft >= '". $draftmin ."' and";
			$searchfileds_ar["draftmin"] = $draftmin;
		}
		if ($draftmax > 0){
			$query_where .= " draft <= '". $draftmax ."' and";
			$searchfileds_ar["draftmax"] = $draftmax;
		}
		
		if ($cabinmin > 0){
			$query_where .= " total_cabin >= '". $cabinmin ."' and";
			$searchfileds_ar["cabinmin"] = $cabinmin;
		}
		if ($cabinmax > 0){
			$query_where .= " total_cabin <= '". $cabinmax ."' and";
			$searchfileds_ar["cabinmax"] = $cabinmax;
		}
	
        $query_where .= " status_id = 1 and";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        //return $sql;
		
		$returnar = array(
			"sql" => $sql,
			"searchfileds_ar" => $searchfileds_ar
		);
		
		return $returnar;
  	}
	
	public function total_boat_model_found($sql){
		global $db;
		$sqlm = str_replace("select *","select count(*) as ttl",$sql);
		$foundm = $db->total_record_count($sqlm);
		return $foundm;
	}
	
	public function display_boat_model_list($argu = array()){
		global $db, $cm;
        $returntext = '';
		
		$makeid = round($argu["makeid"], 0);
		$makename = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
		
		$sorting_sql = "rank";
        $sql_ar = $this->boat_model_sql($argu);
		$sql = $sql_ar["sql"];
        //$foundm = $this->total_boat_model_found($sql);
		
		$sql = $sql." order by ". $sorting_sql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
		 if ($found > 0){
			$returntext .= '
			<div class="container pt-5 mb-5 clearfixmain">
				<h1 class="h2 border-below t-center font-normal mb-4">'. $makename .' Collection</h1>
				<ul class="collection-yacht-list">
			';
			
			foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$model_name = $this->get_model_name($id);
				$detailsurl = $cm->get_page_url($slug, "boatmodel");
				
				$imgpath_ar = json_decode($this->get_model_first_image($id, 1));
				$alttag = $imgpath_ar->alttag;
				$imgpath = $imgpath_ar->imgpath;						
				
				$imagefolder = 'models/' . $id . '/modelimage/';				
				$imagedata = '<img class="full" src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" alt="'. $alttag .'">';
				
				$returntext .= '
				<li><a href="'. $detailsurl .'">'. $imagedata .'</a>
                '. $model_name .'</li>
				';
            }
			
			$returntext .= '
				</ul>
			</div>
			';
		 }		 
		 
		 $returntext .= $this->display_listing_page_bottom($argu);		 
		 return $returntext;
	}
	
	public function display_boat_model_list_by_category($argu = array()){
		global $db, $cm;
        $returntext = '';
		
		$makeid = round($argu["makeid"], 0);
		$makename = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
		
		$returntext .= '
		<div class="container pt-5 clearfixmain">
			<h1 class="h2 border-below t-center font-normal mb-4">'. $makename .' Collection</h1>
		';
		
		$query_sql = "select id, name";
		$query_form = " from tbl_model_category";
		$query_where = " where";
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql_cat = $query_sql . $query_form . $query_where;
		$sql_cat .= " order by rank";
		$result_cat = $db->fetch_all_array($sql_cat);
		$found_cat = count($result_cat);
		
		if ($found_cat > 0){
			foreach($result_cat as $row_cat){
				$category_id = $row_cat['id'];
				$category_name = $cm->filtertextdisplay($row_cat['name']);
				
				//collect model for this category
				$query_sql = "select *";
				$query_form = " from tbl_model,";
				$query_where = " where";
				
				if ($makeid > 0){
					$query_where .= " make_id = '". $makeid ."' and";
				}

				$query_where .= " category_id = '". $category_id ."' and";
				$query_where .= " status_id = 1 and";
				
				$query_sql = rtrim($query_sql, ",");
				$query_form = rtrim($query_form, ",");
				$query_where = rtrim($query_where, "and");
				
				$sql = $query_sql . $query_form . $query_where;
				$sql .= " order by rank";
				
				$result = $db->fetch_all_array($sql);
        		$found = count($result);				
				
				if ($found > 0){
					$returntext .= '					
					<div class="mm-modelbox clearfixmain">
						<h2>'. $category_name .'</h2>
						<ul class="collection-yacht-list">
					';
					
					foreach($result as $row){
						foreach($row AS $key => $val){
							${$key} = $cm->filtertextdisplay(($val));
						}
						
						$model_name = $this->get_model_name($id);
						$detailsurl = $cm->get_page_url($slug, "boatmodel");
						
						$imgpath_ar = json_decode($this->get_model_first_image($id, 1));
						$alttag = $imgpath_ar->alttag;
						$imgpath = $imgpath_ar->imgpath;						
						
						$imagefolder = 'models/' . $id . '/modelimage/';				
						$imagedata = '<img class="full" src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" alt="'. $alttag .'">';
						
						$returntext .= '
						<li><a href="'. $detailsurl .'">'. $imagedata .'</a>
						'. $model_name .'</li>
						';
					}
					
					$returntext .= '
							</ul>
						</div>
					';
					
				}				
			}			
		}
		
		$returntext .= '</div>';
		$returntext .= $this->display_listing_page_bottom($argu);
		
		return $returntext;
	}
	
	//boat model details
	public function display_main_slider($model_id){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_model_photo where model_id = '". $model_id ."' and category_id = 1 and imgpath != '' and status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$model_name = $this->get_model_name($model_id);
			$photo_category_name = $cm->get_common_field_name('tbl_model_photo_category', 'name', $category_id);
			$returntext .= '
			<div class="fclayoutstop clearfixmain">				
				<div class="fclayoutstop-carousel">
			';
			
			foreach($result as $row){
                $imgpath = $row['imgpath'];
                $returntext .= '<div><img alt="'. $model_name .'" src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/bigger/'. $imgpath .'" /></div>'
                ;
            }
			
			$returntext .= '					
				</div>
			</div>
			';
			
			$returntext .= '
			<script>    	
				$( document ).ready(function() {
					$(".fclayoutstop-carousel").slick({
					  fade:true,
					  dots: true,
					  autoplay: true,
					  arrows :false,
					  infinite: true,
					  speed: 300,
					  slidesToShow: 1,
					  adaptiveHeight: true,
					  pauseOnHover: true
					});	
				});
			</script>
			';
		}
		
		return $returntext;
	}
	
	public function display_exterior_interior_image_slider_slick($model_id, $category_id){
        global $db, $cm;
        $returntxt = '';
		$thumbnailtext = '';

        $sql = "select * from tbl_model_photo where model_id = '". $model_id ."' and category_id = '". $category_id ."' and imgpath != '' and status_id = 1 order by rank";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
        if ($found > 0){
			$sliderclassmain = 'slider-for-' . $category_id;
			$sliderclassnav = 'slider-nav-' . $category_id;
			
			$model_name = $this->get_model_name($model_id);
			$photo_category_name = $cm->get_common_field_name('tbl_model_photo_category', 'name', $category_id);

            $returntxt .= '
            <div class="fc_slick_slider clearfixmain">
                <div class="slider-for '. $sliderclassmain .'">
                ';
	
				foreach($result as $row){
					$imgpath = $row['imgpath'];
					$returntxt .= '<div><img alt="'. $model_name .'" src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/bigger/'. $imgpath .'" alt="'. $model_name .'" /></div>';
					$thumbnailtext .= '<div><img alt="'. $model_name .'" src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/'. $imgpath .'" alt="'. $model_name .'" /></div>';					
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
        return $returntxt;
    }
	
	public function display_exterior_interior_image($model_id, $category_id){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_model_photo where model_id = '". $model_id ."' and category_id = '". $category_id ."' and imgpath != '' and status_id = 1 order by rank limit 0, 8";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		if ($found > 0){
			$model_name = $this->get_model_name($model_id);
			$photo_category_name = $cm->get_common_field_name('tbl_model_photo_category', 'name', $category_id);
			$returntext .= '
			<div class="container pt-5  mb-5 clearfixmain">
				<h1 class="h2 border-below t-center font-normal mb-4">'. $photo_category_name .'</h1>
				<ul class="collection-yacht-list hover-effect">
			';
			
			foreach($result as $row){
                $imgpath = $row['imgpath'];
                //$returntext .= '<li><a class="fancybox" rel="gallery"  href="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/bigger/'. $imgpath .'" alt="'. $model_name .'"><img src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/'. $imgpath .'" /></a></li>';
				$returntext .= '<li><a class="fc-slick-pop-open" c="'. $category_id .'" href="javascript:void(0);"><img alt="'. $model_name .'" src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/'. $imgpath .'" /></a></li>';
            }
			
			$returntext .= '
				</ul>
			';

			//gallery pop
			$returntext .= '
			<div id="overlay2" class="imgoverlayslick'. $category_id .' animated hide">
				<a class="fc-close-contact" c="imgoverlayslick'. $category_id .'" href="javascript:void(0);"><i class="fas fa-times"></i><span class="com_none">Close</span></a>
				<div class="fc_slick_slider_top clearfixmain">
				'. $this->display_exterior_interior_image_slider_slick($model_id, $category_id) .'
				</div>
			</div>				
			';

			
			$returntext .= '
			</div>
			';
			
			$returntext .= '
			<script>
				$(document).ready(function(){
					$(".fc-slick-pop-open").click(function(){
						var c = $(this).attr("c");
						
						$(".imgoverlayslick" + c).fadeIn(300);
						$(".slider-for-" + c)[0].slick.refresh();
						$(".slider-nav-" + c)[0].slick.refresh();
					});
					
					$(".fc-close-contact").click(function(){
						var c = $(this).attr("c");
						$("." + c).fadeOut(300);
					});
				});
			</script>
			';
		}
		
		return $returntext;
	}
	
	public function display_layout_image($model_id){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_model_photo where model_id = '". $model_id ."' and category_id = 4 and imgpath != '' and status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$model_name = $this->get_model_name($model_id);
			$photo_category_name = $cm->get_common_field_name('tbl_model_photo_category', 'name', $category_id);
			$returntext .= '
			<div class="fclayouts clearfixmain">
				<div class="container">
				<h1 class="h2 border-below t-center font-normal mb-4">Layouts</h1>
				<div class="fclayouts-carousel">
			';
			
			foreach($result as $row){
                $imgpath = $row['imgpath'];
                $returntext .= '<div><img alt="'. $model_name .'" src="'. $cm->folder_for_seo .'models/'. $model_id .'/modelimage/bigger/'. $imgpath .'" /></div>'
                ;
            }
			
			$returntext .= '
					</div>
				</div>
			</div>
			';
			
			$returntext .= '
			<script>    	
				$( document ).ready(function() {
					$(".fclayouts-carousel").slick({
					  dots: true,
					  autoplay: true,
					  arrows :false,
					  infinite: true,
					  speed: 300,
					  slidesToShow: 1,
					  adaptiveHeight: true
					});	
				});
			</script>
			';
		}
		
		return $returntext;
	}
	
	public function boat_model_details($param = array()){
		global $db, $cm, $frontend;
				
		//param
		$default_param = array("checkopt" => 0, "htmlreturn" => 1);
		$param = array_merge($default_param, $param);
		
		$checkval = $param["checkval"];
		$checkopt = $param["checkopt"];
		$htmlreturn = $param["htmlreturn"];
		//end
		
		if ($checkopt == 1){
			$checkfield = 'slug';
		}else{
			$checkfield = 'id';
		}
		
		$sql = "select * from tbl_model where ". $checkfield ." = '". $cm->filtertext($checkval) ."' and status_id = 1";	
		$result = $db->fetch_all_array($sql);		
		$found = count($result);
		
		if ($found == 0){
			header('Location: '. $cm->sorryredirect(3));
			exit;
		}else{
			if ($htmlreturn == 1){
				$modelcontent = '';
								
				$row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);	
				}
				
				$model_name = $this->get_model_name($id);
				$detailsurl = $cm->get_page_url($slug, "boatmodel");
				$model_image = json_decode($this->get_model_first_image($id));
				$cat_name = $cm->get_common_field_name('tbl_model_category', 'name', $category_id);
				
				$modelcontent .= $this->display_main_slider($id);
				$modelcontent .= $this->display_exterior_interior_image($id, 2);
				$modelcontent .= $this->display_exterior_interior_image($id, 3);
				
				$brochurelink = '';
				if ($brochurefilepath != ""){
					$brochurelink = '<a target="_blank" href="'. $cm->folder_for_seo .'models/'. $id .'/modelbrochure/'. $brochurefilepath .'" class="button large mb-2"><i class="fas fa-download"></i> &nbsp; Download Brochure</a>';
				}
				
				if ($videofilepath != ""){
					$modelcontent .= '
					<div class="fcvideocont">
						<div class="container">
							<h1 class="h2 border-below t-center font-normal mb-4">Video</h1>         
							<video controls poster="">
								<source src="'. $cm->folder_for_seo .'models/'. $id .'/modelvideo/'. $videofilepath .'" type="video/mp4">
								Your browser does not support HTML5 video.
							</video>
						</div>
					</div>
					';
				}
				
				$star_content = '';
				$modelcontent .= '
				<div class="container pt-5 mb-5 clearfixmain">
					<h1 class="h2 border-below t-center font-normal mb-4">Specification</h1>
					<ul class="spec-list-bordered">
						<li>
							<div class="labeltitle">Type</div>
							<div class="labelvalue">'. $cat_name .'</div>
						</li>
				';
				
				
				
				if ($construction != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Construction</div>
						<div class="labelvalue">'. $construction .'</div>
					</li>
					';
				}
				
				if ($length > 0){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Length</div>
						<div class="labelvalue">'. $this->feet_to_meter($length) .' m / '. round($length, 2) .' ft</div>
					</li>
					';
				}
				
				if ($max_beam > 0){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Maximum beam</div>
						<div class="labelvalue">'. $this->feet_to_meter($max_beam) .' m / '. round($max_beam, 2) .' ft</div>
					</li>
					';
				}				
				
				if ($draft > 0){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Draft</div>
						<div class="labelvalue">'. $this->feet_to_meter($draft) .' m / '. round($draft, 2) .' ft</div>
					</li>
					';
				}
				
				if ($engines != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Engines</div>
						<div class="labelvalue">'. $engines .'</div>
					</li>
					';
				}
				
				if ($cruising_speed != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Cruising Speed</div>
						<div class="labelvalue">'. $cruising_speed .'</div>
					</li>
					';
				}
				
				if ($top_speed != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Top Speed</div>
						<div class="labelvalue">'. $top_speed .'</div>
					</li>
					';					
				}
				
				if ($fuel_capacity != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Fuel Capacity</div>
						<div class="labelvalue">'. $fuel_capacity .'</div>
					</li>
					';					
				}
				
				if ($water_capacity != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Water Capacity</div>
						<div class="labelvalue">'. $water_capacity .'</div>
					</li>
					';					
				}
				
				if ($mainsail != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Mainsail</div>
						<div class="labelvalue">'. $mainsail .'</div>
					</li>
					';					
				}
				
				if ($genoa != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Genoa</div>
						<div class="labelvalue">'. $genoa .'</div>
					</li>
					';					
				}
				
				if ($living_space != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Living Space</div>
						<div class="labelvalue">'. $living_space .'</div>
					</li>
					';					
				}
				
				if ($guests != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Guests</div>
						<div class="labelvalue">'. $guests .'</div>
					</li>
					';					
				}
				
				if ($total_cabin > 0){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Total Cabins</div>
						<div class="labelvalue">'. $total_cabin .'</div>
					</li>
					';					
				}
				
				if ($category != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Category</div>
						<div class="labelvalue">'. $category .'</div>
					</li>
					';					
				}
				
				if ($naval_architecture != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Naval Architecture</div>
						<div class="labelvalue">'. $naval_architecture .'</div>
					</li>
					';					
				}
				
				if ($design != ""){
					$modelcontent .= '
					<li>
						<div class="labeltitle">Design</div>
						<div class="labelvalue">'. $design .'</div>
					</li>
					';					
				}
				
				if ($cruising_speed != "" OR $top_speed != ""){
					$star_content = '<p class="t-center mb-5"><em><small>* depending on the engine option and fuel tanks selected</small></em></p>';
				}else{
					$star_content = '<p class="t-center mb-5">&nbsp;</p>';
				}
				
				
				$modelcontent .= '
					</ul>
					'. $star_content .'
					
					<div align="center">
						'. $brochurelink .' 
						<a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-model-local/?m='. $id . '" class="contactbroker button large inverse" data-type="iframe"><i class="far fa-envelope"></i> &nbsp; Request Quote</a>
					</div>
				</div>
				';
				
				$modelcontent .= $this->display_layout_image($id);	
				
				if ($description != ""){
					$modelcontent .= '
					<div class="container pt-5 pb-3-new clearfixmain">
						<h2 class="font-normal t-center mb-3">'. $model_name .'</h2>
					'. $description .'
					</div>
					';
				}
				
				$model_content_ar = array(
					"modelcontent" => $modelcontent,					
					"model_image" => $model_image,
					"model_name" => $model_name,
					"detailsurl" => $detailsurl,
					"make_id" => $make_id,
					"model_id" => $id,
					"m1" => $m1,
					"m2" => $m2,
					"m3" => $m3
				);
				
				return json_encode($model_content_ar);
			}else{
				return $result;
			}
		}
	}
	
	//find the right model for you
	public function display_find_model_search_form($argu = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$makeid = round($argu["makeid"], 0);
		$lengthmin = round($argu["lengthmin"], 2);
		$lengthmax = round($argu["lengthmax"], 2);
		$draftmin = round($argu["draftmin"], 2);
		$draftmax = round($argu["draftmax"], 2);
		$beammin = round($argu["beammin"], 2);
		$beammax = round($argu["beammax"], 2);
		$cabinmin = round($argu["cabinmin"], 0);
		$cabinmax = round($argu["cabinmax"], 0);
		//end
		
		if ($lengthmin == 0){$lengthmin = ''; }
		if ($lengthmax == 0){$lengthmax = ''; }
		if ($draftmin == 0){$draftmin = ''; }
		if ($draftmax == 0){$draftmax = ''; }
		if ($beammin == 0){$beammin = ''; }
		if ($beammax == 0){$beammax = ''; }
		if ($cabinmin == 0){$cabinmin = ''; }
		if ($cabinmax == 0){$cabinmax = ''; }
		
		$sql = "select * from tbl_model_search_fields where id = 1";
		$result = $db->fetch_all_array($sql);
		$row = $result[0];
		foreach($row AS $key => $val){
			${$key} = $cm->filtertextdisplay($val);
		}
		$fieldlists_ar = json_decode($fieldlists);
		
		foreach($fieldlists_ar as $obj){
			if ($obj == 1){
				$returntext .= '
				<label class="com_none" for="lengthmin">min</label>
				<label class="com_none" for="lengthmax">max</label>
				<p>SIZE IN FEET:</p>
				<div class="clearfixmain mb-3">
					<div class="left"><input type="text" placeholder="Min" id="lengthmin" name="lengthmin" value="'. $lengthmin .'"></div>
					<div class="right"><input type="text" placeholder="Max" id="lengthmax" name="lengthmax" value="'. $lengthmax .'"></div>
				</div>
				';
			}
			
			if ($obj == 2){
				$returntext .= '
				<label class="com_none" for="draftmin">max</label>
				<label class="com_none" for="draftmax">min</label>
				<p>DRAFT IN FEET:</p>
				<div class="clearfixmain mb-3">
					<div class="left"><input type="text" placeholder="Min" id="draftmin" name="draftmin" value="'. $draftmin .'"></div>
					<div class="right"><input type="text" placeholder="Max" id="draftmax" name="draftmax" value="'. $draftmax .'"></div>
				</div>
				';
			}
			
			if ($obj == 3){
				$returntext .= '
				<label class="com_none" for="beammin">min</label>
				<label class="com_none" for="beammax">max</label>
				<p>BEAM IN FEET:</p>
				<div class="clearfixmain mb-3">
					<div class="left"><input type="text" placeholder="Min" id="beammin" name="beammin" value="'. $beammin .'"></div>
					<div class="right"><input type="text" placeholder="Max" id="beammax" name="beammax" value="'. $beammax .'"></div>
				</div>
				';
			}
			
			if ($obj == 4){
				$returntext .= '
				<label class="com_none" for="cabinmin">min</label>
				<label class="com_none" for="cabinmax">max</label>
				<p>NUMBER OF CABINS:</p>
				<div class="clearfixmain mb-3">
					<div class="left"><input type="text" placeholder="Min" id="cabinmin" name="cabinmin" value="'. $cabinmin .'"></div>
					<div class="right"><input type="text" placeholder="Max" id="cabinmax" name="cabinmax" value="'. $cabinmax .'"></div>
				</div>
				';
			}
		}
		
		$returntext = '
		<form method="get" action="'. $_SERVER["REQUEST_URI"] .'" id="modelsearchfields" name="modelsearchfields">
		<input type="hidden" name="makeid" id="makeid" value="'. $makeid .'">
		<input type="hidden" name="fqstring" id="fqstring" value="1">
		'. $returntext .'
		<button type="submit" class="button">Search</button>
		</form>
		';
		
		return $returntext;
	}
	
	public function display_boat_model_list_for_search($argu = array()){
		global $db, $cm;
        $returntext = '';
		$sorting_sql = "rank";
        $sql_ar = $this->boat_model_sql($argu);
		$sql = $sql_ar["sql"];
		$searchfileds_ar = $sql_ar["searchfileds_ar"];

		$sql = $sql." order by ". $sorting_sql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$returntext .= '
			<ul class="right-yacht-list">
			';
			
			foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				
				$model_name = $this->get_model_name($id);
				$detailsurl = $cm->get_page_url($slug, "boatmodel");
				
				$imgpath_ar = json_decode($this->get_model_first_image($id, 1));
				$alttag = $imgpath_ar->alttag;
				$imgpath = $imgpath_ar->imgpath;						
				
				$imagefolder = 'models/' . $id . '/modelimage/';				
				$imagedata = '<img class="full" src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" alt="'. $alttag .'">';
				
				$returntext .= '
				<li><a href="'. $detailsurl .'">'. $imagedata .'</a>
                '. $model_name .'</li>
				';
            }
			
			$returntext .= '
			</ul>
			';
		 }else{
			 $returntext .= '<p>'. $cm->get_systemvar('BTNFD') .'</p><p><a class="button fc-open-contact" href="javascript:void(0);">Contact Us</a></p>';
		 }
		 
		 //return $returntext;
		 $returnar = array(
		 	"returntext" => $returntext,
			"searchfileds_ar" => $searchfileds_ar
		 );
		 
		 return $returnar;
	}
	
	public function display_find_right_model_main($argu = array()){
		global $db, $cm;		
		
		$modellistcontent_ar = $this->display_boat_model_list_for_search($argu);
		$modellistcontent = $modellistcontent_ar["returntext"];
		$searchfileds_ar = $modellistcontent_ar["searchfileds_ar"];
		
		$formcontent = $this->display_find_model_search_form($searchfileds_ar);
		
        $returntext = '
		<div class="modelmain container pt-5 pb-new-5 mb-5 clearfixmain">
			<div class="clearfixmain">
				<p class="t-left"><a href="'. $cm->get_page_url(145, "page").'" class="button">Back to Sunreef Collection</a></p>
				<div class="right-col-30 sidebar-search-2 mb-3 scrollcol" parentdiv="modelmain">
					'. $formcontent.'
				</div>
				
				<div class="left-col-70">
					'. $modellistcontent.'
				</div>		
		';
	
		$returntext .= '
			</div>
		</div>
		';
		
		$returntext .= '
		<script>
		$(document).ready(function(){
			$(".findrightmodel").click(function(){
				$("html, body").animate({
					scrollTop: $("#findrightmodelcontenter").offset().top - 120
				}, 500);
			});
		});
		</script>
		';
		
		return $returntext;
	}	
	///------ FRONT-END SECTION ENF ---------
	
}
?>