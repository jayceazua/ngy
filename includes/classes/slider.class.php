<?php
class Sliderclass {
	
	//slider message option
	public function slider_message_option_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "None");
		$val_ar[] = array("name" => "Text Message");
		//$val_ar[] = array("name" => "Only Link. No message");
		//$val_ar[] = array("name" => "Talk To A Specialist Button");
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_slider_message_option_combo($display_message = 0){
        global $db;
		$val_ar = json_decode($this->slider_message_option_val());		
		$returntext = '';
  
        foreach($val_ar as $key => $val_row){
            $cname = $val_row->name;
			$bck = '';
			if ($display_message == $key){
				$bck = ' selected="selected"';	
			}			
			$returntext .= '<option value="'. $key .'"'. $bck .'>'. $cname .'</option>';
        }		
		return $returntext;
    }
	
	//slider category combo
	public function get_slider_category_combo($category_id = 0){
        global $db;
		$returntext = '';
        $sql = "select id, name from tbl_slider_category where status_id = 1 order by name";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$mp4_video_sql_check = "select count(*) as ttl from tbl_image_slider where category_id = '". $c_id ."' and video_type = 4";
			$wh_mp4_video = $db->total_record_count($mp4_video_sql_check);
			
			$img_sql_check = "select count(*) as ttl from tbl_image_slider where category_id = '". $c_id ."' and video_type IN (1,2,3)";
			$wh_img = $db->total_record_count($img_sql_check);
			
			$vck = '';
			if ($category_id == $c_id){ $vck = ' selected="selected"'; }
			$returntext .= '<option wh_img="'. $wh_img .'" wh_video="'. $wh_mp4_video .'" value="'. $c_id .'"'. $vck .'>'. $cname .'</option>';
        }
		return $returntext;
	}
	
	//slider text position combo
	public function get_text_position_combo($text_position_id = 0){
        global $db;
		$returntext = '';
        $sql = "select id, name from tbl_slider_text_position where status_id = 1 order by id";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$vck = '';
			if ($text_position_id == $c_id){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>';
        }
		return $returntext;
	}
	
	//display back-end tab
	public function display_slider_tab_button($ms, $video_type){
		$returntext = '';
		
		if ($ms > 0 and $video_type == 1){
			
			if (isset($_SESSION["from_list_slider"]) AND $_SESSION["from_list_slider"] == 1){
				$backlink = $_SESSION["bck_pg"];
			}else{
				$backlink = "mod_slider_image.php";
			}
			
			$returntext = '
			<table border="0" width="93%" cellspacing="0" cellpadding="5" class="htext">
				<tr>
					<td align="left" valign="top">
						<ul class="syscategory">
							<li><a tabid="1" class="toptablink toptablink1 active" href="add_slider_image.php?id='. $ms .'">Form</a></li>
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
	
	//slider insert/update
	public function slider_insert_update(){
		global $db, $cm, $adm, $fle;
		
		$category_id = round($_POST["category_id"], 0);
		$display_message = round($_POST["display_message"], 0);
		$name = $_POST["name"];
		$link_type = round($_POST["link_type"], 0);
		$page_url = $_POST["page_url"];
		$int_page_sel = $_POST["int_page_sel"];
		$pdes = $_POST["pdes"];
		$buttontext = $_POST["buttontext"];
		$fontcolor = $_POST["fontcolor"];
		$fontcolor2 = $_POST["fontcolor2"];
		$text_position_id = round($_POST["text_position_id"], 0);
		$status_id = round($_POST["status_id"], 0);
		
		$video_type = round($_POST["video_type"], 0);
		$video_url = $_POST["video_url"];
		
		$oldrank = round($_POST["oldrank"], 0);
		$ms = round($_POST["ms"], 0);
		$new_window = "n";
		
		if ($link_type == 2){ 
			 $int_page_sel_ar = explode("/!", $int_page_sel);
			 $int_page_id = $int_page_sel_ar[0];
			 $int_page_tp = $int_page_sel_ar[1];
			 $page_url = "";
		}else{
			 $int_page_id = 0;
			 $int_page_tp = "";
		}
		
		if ($ms == 0){
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_image_slider where category_id = '". $cm->filtertext($category_id) ."'") + 1;
			$sql = "insert into tbl_image_slider (category_id) values ('". $cm->filtertext($category_id) ."')";
			$iiid = $db->mysqlquery_ret($sql);
			$_SESSION["postmessage"] = "nw"; 
			$rback = "mod_slider_image.php";
		}else{
			$rank = round($_POST["rank"], 0);
			$sql = "update tbl_image_slider set category_id = '". $cm->filtertext($category_id) ."' where id = '".$ms."'";
			$db->mysqlquery($sql);
			$iiid = $ms;
			$_SESSION["postmessage"] = "up";
			$rback = $_SESSION["bck_pg"];
		}
		
		// common update
		$sql = "update tbl_image_slider set display_message = '". $cm->filtertext($display_message) ."'
		, name = '". $cm->filtertext($name) ."'
		, pdes = '". $cm->filtertext($pdes) ."'
		, fontcolor = '". $cm->filtertext($fontcolor) ."'
		, fontcolor2 = '". $cm->filtertext($fontcolor2) ."'
		, buttontext = '". $cm->filtertext($buttontext) ."'
		, text_position_id = '". $text_position_id ."'
		, video_type = '". $video_type ."'
		, video_url = '". $cm->filtertext($video_url) ."'
		, link_type = '". $link_type ."'
		, page_url = '". $cm->filtertext($page_url) ."'
		, int_page_id = '". $int_page_id ."' 
		, int_page_tp = '". $int_page_tp ."'
		, new_window = '". $new_window ."'
		
		, status_id = '". $status_id ."'
		, rank = '".$rank."' where id = '".$iiid."'";
		$db->mysqlquery($sql);
		// end 
		
		// update the rank
		$tablenm = "tbl_image_slider";
		$wherecls = " id != '".$iiid."' and category_id = '". $cm->filtertext($category_id) ."'";
		$adm->change_rank($rank, $oldrank, $tablenm, $wherecls);  
		//end
		
		if ($video_type == 4){
			//video upload
			$filename = $_FILES['videopath']['name'] ;
			if ($filename != ""){
				
				$wh_ok = $fle->check_file_ext($cm->allow_video_ext, $filename);
				if ($wh_ok == "y"){
					$filename_tmp = $_FILES['videopath']['tmp_name'];
					$filename = $fle->uploadfilename($filename);	
					$filename1 = $iiid."slider".$filename;
					$target_path = "../sliderimage/";		
					$target_path = $target_path . $cm->filtertextdisplay($filename1);
					$fle->fileupload($filename_tmp, $target_path);
					
					//update database	
					$sql = "update tbl_image_slider set videopath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
					$db->mysqlquery($sql);
				}	
			}			
			//end
		}else{
		
			//image upload
			$filename = $_FILES['imgpath']['name'] ;
			if ($filename != ""){
				$filename_tmp = $_FILES['imgpath']['tmp_name'];
				$filename = $fle->uploadfilename($filename);	
				$filename1 = $iiid."slider".$filename;

				$target_path_main = "../sliderimage/";

				//slider image
				$target_path = $target_path_main;
				$r_width = $cm->slider_im_width;
				$r_height = $cm->slider_im_height;
				$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));					

				//original image store
				$target_path = $target_path_main . 'original/';
				$target_path = $target_path . $cm->filtertextdisplay($filename1);
				$fle->fileupload($_FILES['imgpath']['tmp_name'], $target_path);

				//update database
				$sql = "update tbl_image_slider set imgpath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
				$db->mysqlquery($sql);
			}
			//end
		}
		$returnar = array(
			"sliderid" => $iiid,
			"infotext" => ''
		);
		return json_encode($returnar);	
		exit;
	}
	
	//slider crop option
	public function display_slider_image_crop_option($slider_id){
		global $db, $cm, $adm, $fle;
		
		$rw = $cm->slider_im_width;
		$rh = $cm->slider_im_height;
		$iprop = round($rw / $rh, 2);
			
		$imgpath = $cm->get_common_field_name("tbl_image_slider", "imgpath", $slider_id);
		if ($imgpath != ""){
			$returntext = '
			
			<div class="cropimageholder nospace">
				<div class="cropimagesection box_border">
					<div class="box_heading">Original Image</div>
					<div class="bottomspacer1 clearfixmain">
						<input type="radio" class="radiobutton fullwidthcrop" name="whfullwidth" value="0" checked="checked"> Both width and height crop &nbsp;&nbsp;&nbsp;
						<input type="radio" class="radiobutton fullwidthcrop" name="whfullwidth" value="1"> Only height crop
					</div>
					<img iwidth="'. $rw .'" iprop="'. $iprop .'" id="myImage" class="myImage" src="'. $cm->folder_for_seo . 'sliderimage/original/'. $imgpath .'" />
				</div>
				
				<div class="outputsection box_border">
					<div class="box_heading">Currently Saved</div>
					<div class="imageoutput"><img src="'. $cm->folder_for_seo . 'sliderimage/'. $imgpath .'" /></div>
				</div>
				<div class="clearfix"></div>
			</div>
			
			<div class="saveholder nospace">
			<button slider_id="'. $slider_id .'" type="button" class="butta saveimage"><span class="saveIcon butta-space">Save Image</span></button>
			</div>
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				
				var x1 = 0,
					y1 = 5,
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
					//alert (selected_opt);
					
					//x2 = wd;
					//x2 = iwidth / wrt ;
					
					if (selected_opt == 1){
						x2 = wd;
					}else{
						x2 = iwidth / wrt ;
					}
					
					var iheight = x2 / iprop;
					
					y2 = 5 + iheight;
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
						imas.setSelection(0, 5, croplastpointar[0], croplastpointar[1]);
						imas.update();
					});					
					
				});
				
				$(".whitetd").off("click", ".saveimage").on("click", ".saveimage", function(){
					var slider_id = $(this).attr("slider_id");
					
					var b_sURL = "onlyadminajax.php";
					$.post(b_sURL,
					{
						x1:x1,
						y1:y1,
						tw:tw,
						th:th,
						slider_id:slider_id,
						az:10,
						inoption:3
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
			$returntext = '<p>You have not uploaded image for this slider.</p>';	
		}
		
		echo $returntext;
	}
	
	//process crop
	function process_crop($slider_id){
		global $db, $cm, $adm, $fle;
		
		$x1 = round($_POST["x1"], 0);
		$y1 = round($_POST["y1"], 0);
		$w = round($_POST["tw"], 0);
		$h = round($_POST["th"], 0);
		
		$imgpath = $cm->get_common_field_name("tbl_image_slider", "imgpath", $slider_id);
		$imgpath_new = $fle->create_different_file_name($imgpath);
		
		$source_path = "../sliderimage/original/" . $imgpath;
		$source_path_rename = "../sliderimage/original/" . $imgpath_new;
		
		$x = @getimagesize($source_path);
		$source_width = $x[0];
		$source_height = $x[1];
		$source_type = $x[2];
		
		$rw = $cm->slider_im_width;
		$rh = $cm->slider_im_height;
		
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

		$path = "../sliderimage/";		
		imagecopyresampled($newimg,$source,0,0,$x1,$y1,$newW,$newH,$w,$h);
		imagejpeg($newimg,$path.$imgpath_new, 80);
		
		//rename original file
		$fle->rename_existing_file($source_path, $source_path_rename);
		
		//remove existing file
		$fle->filedelete($path.$imgpath);
		
		//update filename
		$sql = "update tbl_image_slider set imgpath = '".$cm->filtertext($imgpath_new)."' where id = '". $slider_id ."'";
		$db->mysqlquery($sql);
		
		//output file		
		echo '<img src="'. $cm->folder_for_seo .'sliderimage/'. $imgpath_new .'?x='.time().'" />';
		exit;
	}
	
	//get video type cast
	public function get_video_type_cast($filename){
		global $fle;
		$f_ext = $fle->get_file_extension($filename);
		if ($f_ext == ".webm"){
			$typecast = "video/webm";
		}elseif ($f_ext == ".mov"){
			$typecast = "video/mov";
		}else{
			$typecast = "video/mp4";
		}		
		return $typecast;
	}
	
	//display slider
	public function count_slider_image($category_id){
		global $db;
		$sql = "select count(*) as ttl from tbl_image_slider where category_id = '". $category_id ."' and status_id = 1";
		$total_slider_image = $db->total_record_count($sql);
		return $total_slider_image;
	}
	
	public function page_top_banner($param = array()){
		global $db, $cm;
		$returntxt = '';
		
		//param
		$pageid = $param["pageid"];
		$templatefile = $param["templatefile"];
		$slider_make_id = $param["slider_make_id"];
		$display_make_name = $param["display_make_name"];
		//end
		
		$slider_category_id = $cm->get_common_field_name('tbl_page', 'slider_category_id', $pageid);
		
		if ($pageid == 1 AND $cm->isMobileDevice()){
			$imagefolder = 'sliderimage/';		
			$imgpath = $db->total_record_count("select imgpath as ttl from tbl_image_slider where category_id = '". $slider_category_id ."' and imgpath != '' and status_id = 1 order by rank limit 0,1");
			if ($imgpath == ""){
				$imgpath = "default.jpg"; 
			}
			
			$returntxt = '
			<div class="mobilestatic fill clearfixmain" style="background-image:url('. $cm->folder_for_seo . $imagefolder . $imgpath.');">
				<div class="container clearfixmain">
					<div class="mobile-search-container clearfixmain">
						<h6>Yacht &amp; Catamaran Search</h6>
						<div class="mobile-search-container-in clearfixmain">
							<form method="get" action="'. $cm->get_page_url(2, "page") .'" id="mboat_ff" name="ff">
								<ul>
									<li class="left">
										<label for="mfcname">Make</label>
										<input id="mfcname" name="mfcname" class="azax_auto input-field" placeholder="Manufacturer" type="text" value="" ckpage="5" autocomplete="off">
									</li>
									<li class="right">
										<label for="lnmin">Length</label><label class="com_none" for="lnmax">Length</label>
										<div class="clearfixmain">
											<div class="input-left"><input id="lnmin" name="lnmin" class="input-field" placeholder="Min" type="text" value=""></div>
											<div class="input-right"><input id="lnmax" name="lnmax" class="input-field" placeholder="Max" type="text" value=""></div>
										</div>
									</li>
									
									<li class="left">
										<label for="yrmin">Year</label><label class="com_none" for="yrmax">Year</label>
										<div class="clearfixmain">
											<div class="input-left"><input id="yrmin" name="yrmin" class="input-field" placeholder="Min" type="text" value=""></div>
											<div class="input-right"><input id="yrmax" name="yrmax" class="input-field" placeholder="Max" type="text" value=""></div>
										</div>
									</li>
									<li class="right">
										<label for="prmin">Price</label><label class="com_none" for="prmax">Price</label>
										<div class="clearfixmain">
											<div class="input-left"><input id="prmin" name="prmin" class="input-field" placeholder="Min" type="text" value=""></div>
											<div class="input-right"><input id="prmax" name="prmax" class="input-field" placeholder="Max" type="text" value=""></div>
										</div>
									</li>
									
									<li>
										<label>Search Type</label>
										<div class="clearfixmain">
											<label class="com_none" for="cm_sp_typeid1">Yachts</label>
											<label class="com_none" for="cm_sp_typeid2">Catamaran</label>
											<div class="input-left"><input class="setformaction radiobutton" type="radio" id="cm_sp_typeid1" name="sp_typeid" value="1" checked="checked" /> Yachts</div> 
											<div class="input-right"><input class="setformaction radiobutton radiobutton_next" type="radio" id="cm_sp_typeid2" name="sp_typeid" value="2" /> Catamaran</div>
										</div>
									</li>
									
									<li><button type="submit" class="button1">Search</button></li>
								</ul>
								
								<input type="hidden" name="freshstart" value="1">
								<input type="hidden" name="rawtemplate" value="0">
								<input type="hidden" name="owned" value="0">
							</form>
						</div>
					</div>
				</div>
			</div>
			';
			
		}else{
			if ($slider_category_id > 0){
				$argu = array(
					"slider_category_id" => $slider_category_id,
					"templatefile" => $templatefile,
					"slider_make_id" => $slider_make_id,
					"display_make_name" => $display_make_name
				);
				$returntxt .= $this->display_top_image_slider($argu);	
			}
		}
		return $returntxt;
	}
	
	public function display_top_image_slider_camera($param = array()){
		global $db, $cm;
		
		//param
		$slider_category_id = $param["slider_category_id"];
		$templatefile = $param["templatefile"];
		$slider_make_id = $param["slider_make_id"];
		$display_make_name = $param["display_make_name"];
		//end
		
		$tag_line = $cm->get_systemvar('HMTAG');
		$time_value = $cm->get_systemvar('SLTMH');
   		$time_value = round($time_value * 1000);
		if ($time_value == 0){ $time_value = 3000; }
				
		/*$imagefolder = 'sliderimage/inner/';
		if ($slider_category_id == 1){
			$imagefolder = 'sliderimage/';
		}*/
		$imagefolder = 'sliderimage/';
		
		$hang_button_text = '';
		/*if ($slider_category_id == 1 AND $templatefile = "home.php"){
			$hang_button_text = '
			<div class="fcfirst-section clearfixmain">
				<div class="fcscroll1"><a class="fcscrollto" href="#modellist">Custom Luxury Tenders</a></div>
			</div>
			';
		}*/
		
		$video_sql_check = "select count(*) as ttl from tbl_image_slider where category_id = '". $slider_category_id ."' and video_type = 4";
		$wh_video = $db->total_record_count($video_sql_check);
				
        $query_sql = "select *,";
        $query_form = " from tbl_image_slider,";
        $query_where = " where";
		
		if ($slider_category_id > 0){
			$query_where .= " category_id = '". $slider_category_id ."' and";
		}

        $query_where .= " status_id = 1 and";
      
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        
		if ($wh_video > 0){
			$sql = $sql." order by RAND() limit 0, 1";
		}else{		
			$sql = $sql." order by rank";
		}
		
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
        if ($found > 0){
			
			if ($wh_video > 0){
				$text_animation_class = '';
				$returntxt = '
				<div class="imagevideoslider mormalvideo clearfixmain">				
				';
			}else{			
				$returntxt = '
				<div class="normalslider clearfixmain">
				'. $hang_button_text .'
				<div class="camera_wrap camera_emboss" id="camera_wrap_4">
				';
			}
			
			$counter = 0;
			 foreach($result as $row){
				 
				 foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				 }
				
				 $text_css_name = $cm->get_common_field_name("tbl_slider_text_position", "css_name", $text_position_id);
				 $messagetxt = '';
				 
				 if ($display_message == 1){
					 			 
					 if ($link_type == 1){
						  $go_url = $page_url;
					 }elseif ($link_type == 2){
						  $go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
					 }else{
						  $go_url = '';
					 }
					 
					 $s_an = '';
					 $e_an = '';
					 $s_an_text = '';
					 $messagelink = '';
					 if ($go_url != ""){
						 $link_target = "";
						 if ($new_window == "y"){ $link_target = ' target = "_blank"'; }
						 $s_an = '<a href="'. $go_url .'"'. $link_target .'>';
						 $s_an_text = '<a style="color: #'. $fontcolor .';" href="'. $go_url .'"'. $link_target .'>';
						 $e_an = '</a>';					 				 
						 $messagelink = '<div datadelay="900" class="sliderdetailsbutton sliderdetailsbutton'. $counter .' animated animatedopacity clearfixmain"><a href="'. $go_url .'"'. $link_target .'>' . $buttontext .'' . $e_an . '</div>';
					 }
					 			 
					 $messagetxt = '<h2 datadelay="500" class="captionanimation captionanimation'. $counter .' animated animatedopacity" style="color: #'. $fontcolor .'">'. $s_an_text . $name . $e_an .'</h2>';
					 if ($pdes != ''){ $messagetxt .= '<p datadelay="800" class="scaptionanimation scaptionanimation'. $counter .' animated animatedopacity" style="color: #'. $fontcolor2 .'">'. $pdes .'</p>'; }
					 $messagetxt = '
					 <div class="textmessage '. $text_css_name .' clearfixmain">
						<div class="textmessage_con clearfixmain">'. $messagetxt . '</div>' . $messagelink . '
					 </div>';
			
				 }
				 
				 if ($display_message == 2){	
				 	 $phone = $cm->get_systemvar('PCLNW');				 
					 if ($slider_make_id > 0){
						 $make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $slider_make_id);
						 $custom_button_text = "Talk To A Certified ". $make_name ." Specialist";
					 }else{
						 $custom_button_text = "Talk To A Specialist";
					 }
					 
					 $messagetxt = '
					 	<div class="buttonmessage '. $text_css_name .' clearfixmain">
							<div datadelay="500" class="buttonmessage_con buttonmessage_con'. $counter .' animated animatedopacity clearfixmain">
								<a class="commonpop" data-type="iframe" href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-talk-to-specialist") .'?make_id='. $slider_make_id .'">'. $custom_button_text .'</a>
								<div class="sliderphone">'. $phone .'</div>
							</div>
						</div>
					 ';
					 
					 if ($display_make_name == 1 AND $slider_make_id > 0){
						 global $yachtclass;
						 $make_name_format = $yachtclass->format_brand_name($make_name);
						 $messagetxt .= '
						 <div class="textmessage transparentbgcolor middleleft clearfixmain">
						 	<h2>'. $make_name_format .'</h2>
						 </div>
						 ';
					 }
				 }
				 
				 if ($wh_video > 0){
					 $video_filepath = $cm->folder_for_seo . $imagefolder . $videopath;
					 $typecast = $this->get_video_type_cast($video_filepath);
					 $returntxt .= '
					 <video loop autoplay poster="'. $cm->folder_for_seo . $imagefolder . 'videoimg.jpg" id="bgvid">
						<source src="'. $video_filepath .'" type="'. $typecast .'">  
					 </video>
					 '. $messagetxt .'
					 ';
				 }else{
					 
					 if ($video_type == 2){
						//youtube
						$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						//https://img.youtube.com/vi/'. $video_id .'/0.jpg
						$returntxt .= '
						<div data-src="'. $cm->folder_for_seo .'images/spacer.gif">
							'. $cm->play_youtube_video($video_id, "100%", "100%") .'
							'. $messagetxt .'
						</div>
						';					
					}elseif($video_type == 3){
						//vimeo
						$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						$returntxt .= '
						<div data-src="'. $cm->folder_for_seo .'images/spacer.gif">
							'. $cm->play_vimeo_video($video_id, "100%", "100%") .'
							'. $messagetxt .'
						</div>
						';
					}else{
						//image
						$returntxt .= '
						<div data-src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'">                
							'. $messagetxt .'
						</div>
						';
					 }
					 
				 }
				 
						
				$counter++;
			 }
			
			if ($wh_video > 0){
				$returntxt .= '
				<div class="videooptions">
				<a class="videosound" href="javascript:void(0);" title="Mute">Mute/Un Mute</a>
				<a class="videoplaypause" href="javascript:void(0);" title="Pause Video">Play/Pause</a>
				</div>
				</div>
				';
				
				$returntxt .= '
				<script language="javascript" type="text/javascript">
				$(document).ready(function(){
					$("#bgvid").on("loadstart", function (event) {
						$(this).addClass("videoloading");
					});
					
					$("#bgvid").on("canplay", function (event) {
						$(this).removeClass("videoloading");
						$("#bgvid").get(0).play();
					});
					
					$(".videoplaypause").click(function(){
						if ($(this).hasClass("videoplaypause videopause")){
							$("#bgvid").get(0).play();
							$(this).removeClass("videopause");
							$(this).attr("title", "Pause Video");
						}else{
							$("#bgvid").get(0).pause();
							$(this).addClass("videopause");
							$(this).attr("title", "Play Video");
						}
					});
					
					$(".videosound").click(function(){
						if ($(this).hasClass("videosoundmute")){
							$("#bgvid").prop("muted", false);
							$(this).removeClass("videosoundmute");
							$(this).attr("title", "Mute");
						}else{
							$("#bgvid").prop("muted", true);
							$(this).addClass("videosoundmute");
							$(this).attr("title", "Play Sound");
						}
					});
					
					//checking mobile
					var isMobile = {
						Android: function() {
							return navigator.userAgent.match(/Android/i);
						},
						BlackBerry: function() {
							return navigator.userAgent.match(/BlackBerry/i);
						},
						iOS: function() {
							return navigator.userAgent.match(/iPhone|iPad|iPod/i);
						},
						Opera: function() {
							return navigator.userAgent.match(/Opera Mini/i);
						},
						Windows: function() {
							return navigator.userAgent.match(/IEMobile/i);
						},
						any: function() {
							return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
						}
					};
					
					if(isMobile.any()) {
						// It is mobile
						$("#bgvid").get(0).pause();
						$(".videoplaypause").addClass("videopause");
						$(".videoplaypause").attr("title", "Play Video");
					}
					
				});
				</script>
				';
			}else{
				$returntxt .= '		
				</div>
				</div>
				';
				
				if ($templatefile == "home.php"){
					global $yachtchildclass;			
					$boatsearchformar = $yachtchildclass->display_boat_advanced_search_form_small();

					$returntxt = '
					<div class="slidersearch clearfixmain">
						<div class="container clearfixmain">
						'. $boatsearchformar["smallform"] .'				
						</div>
						'. $boatsearchformar["responsiveform"] .'				
						'. $returntxt .'
					</div>
					';
				}
				
				$returntxt .= '<script>
				var $highlight = function(){
					var pos = $(".camera_target .cameraSlide.cameracurrent").index();
					if ($(".captionanimation").length > 0) {					
						var datadelay = parseInt($(".captionanimation" + pos).attr("datadelay"));
						$(".captionanimation" + pos).delay(datadelay).queue(function(next) {
							$(this).addClass("fadeIn2");											  
						});
					}

					if ($(".scaptionanimation").length > 0) {					
						var datadelay = parseInt($(".scaptionanimation" + pos).attr("datadelay"));
						$(".scaptionanimation" + pos).delay(datadelay).queue(function(next) {
						  $(this).addClass("fadeIn2");
						});
					}

					if ($(".sliderdetailsbutton").length > 0) {					
						var datadelay = parseInt($(".sliderdetailsbutton" + pos).attr("datadelay"));
						$(".sliderdetailsbutton" + pos).delay(datadelay).queue(function(next) {
						  $(this).addClass("fadeIn2");
						});
					}

					if ($(".buttonmessage_con").length > 0) {					
						var datadelay = parseInt($(".buttonmessage_con" + pos).attr("datadelay"));
						$(".buttonmessage_con" + pos).delay(datadelay).queue(function(next) {
						  $(this).addClass("fadeIn2");
						});
					}
				};

				$(document).ready(function(){
					$("#camera_wrap_4").camera({
						height				: "48%",
						minHeight			: "100px",
						loader				: "none",
						autoAdvance			: true,
						mobileAutoAdvance	: true,
						slideOn				: "prev",
						fx					: "simpleFade",
						mobileFx			: "simpleFade",
						pagination			: true,
						paginationClass		: "pager",
						thumbnails			: false,
						prevButton			: "homeslider-prev",
						nextButton			: "homeslider-next",
						navigation			: true,
						navigationHover		: false,
						mobileNavHover		: false,
						playPause			: false,				
						hover				: true,
						opacityOnGrid		: true,
						imagePath			: "'. $cm->folder_for_seo .'images/",
						transPeriod			: 1500,
						time				: "'. $time_value .'",
						onEndTransition		: $highlight
					});
				});
				</script>						
				';
			}
			
		}else{
			$returntxt = '';
		}						
		return $returntxt;
	}
	
	public function display_top_image_slider_owl($param = array()){
		global $db, $cm;
		
		//param
		$slider_category_id = $param["slider_category_id"];
		$templatefile = $param["templatefile"];
		$slider_make_id = $param["slider_make_id"];
		$display_make_name = $param["display_make_name"];
		//end
		
		$tag_line = $cm->get_systemvar('HMTAG');
		$time_value = $cm->get_systemvar('SLTMH');
   		$time_value = round($time_value * 1000);
		if ($time_value == 0){ $time_value = 5000; }
				
		/*$imagefolder = 'sliderimage/inner/';
		if ($slider_category_id == 1){
			$imagefolder = 'sliderimage/';
		}*/
		$imagefolder = 'sliderimage/';
		
		$hang_button_text = '';
		/*if ($slider_category_id == 1 AND $templatefile = "home.php"){
			$hang_button_text = '
			<div class="fcfirst-section clearfixmain">
				<div class="fcscroll1"><a class="fcscrollto" href="#modellist">Custom Luxury Tenders</a></div>
			</div>
			';
		}*/
		
		$video_sql_check = "select count(*) as ttl from tbl_image_slider where category_id = '". $slider_category_id ."' and video_type = 4";
		$wh_video = $db->total_record_count($video_sql_check);
				
        $query_sql = "select *,";
        $query_form = " from tbl_image_slider,";
        $query_where = " where";
		
		if ($slider_category_id > 0){
			$query_where .= " category_id = '". $slider_category_id ."' and";
		}

        $query_where .= " status_id = 1 and";
      
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        
		if ($wh_video > 0){
			$sql = $sql." order by RAND() limit 0, 1";
		}else{		
			$sql = $sql." order by rank";
		}
		
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
        if ($found > 0){
			
			if ($wh_video > 0){
				$text_animation_class = '';
				$returntxt = '
				<div class="imagevideoslider mormalvideo clearfixmain">				
				';
			}else{			
				$returntxt = '
				<div class="normalslider clearfixmain">
				'. $hang_button_text .'
				<div class="owl-carousel" id="mainslider">
				';
			}
			
			$counter = 0;
			 foreach($result as $row){
				 
				 foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				 }
				
				 $text_css_name = $cm->get_common_field_name("tbl_slider_text_position", "css_name", $text_position_id);
				 $messagetxt = '';
				 
				 if ($display_message == 1){
					 			 
					 if ($link_type == 1){
						  $go_url = $page_url;
					 }elseif ($link_type == 2){
						  $go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
					 }else{
						  $go_url = '';
					 }
					 
					 $s_an = '';
					 $e_an = '';
					 $s_an_text = '';
					 $messagelink = '';
					 if ($go_url != ""){
						 $link_target = "";
						 if ($new_window == "y"){ $link_target = ' target = "_blank"'; }
						 $s_an = '<a href="'. $go_url .'"'. $link_target .'>';
						 $s_an_text = '<a style="color: #'. $fontcolor .';" href="'. $go_url .'"'. $link_target .'>';
						 $e_an = '</a>';					 				 
						 $messagelink = '<div class="sliderdetailsbutton sliderdetailsbutton'. $counter .' animatedopacity clearfixmain"><a href="'. $go_url .'"'. $link_target .'>' . $buttontext .'' . $e_an . '</div>';
					 }
					 			 
					 $messagetxt = '<h2 class="captionanimation captionanimation'. $counter .' animatedopacity" style="color: #'. $fontcolor .'">'. $s_an_text . $name . $e_an .'</h2>';
					 if ($pdes != ''){ $messagetxt .= '<p class="scaptionanimation scaptionanimation'. $counter .' animatedopacity" style="color: #'. $fontcolor2 .'">'. $pdes .'</p>'; }
					 $messagetxt = '
					 <div class="textmessage '. $text_css_name .' clearfixmain">
						<div class="textmessage_con clearfixmain">'. $messagetxt . '</div>' . $messagelink . '
					 </div>';
			
				 }
				 
				 if ($display_message == 2){	
				 	 $phone = $cm->get_systemvar('PCLNW');				 
					 if ($slider_make_id > 0){
						 $make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $slider_make_id);
						 $custom_button_text = "Talk To A Certified ". $make_name ." Specialist";
					 }else{
						 $custom_button_text = "Talk To A Specialist";
					 }
					 
					$messagetxt = '
					<div class="buttonmessage '. $text_css_name .' clearfixmain">
						<div datadelay="500" class="buttonmessage_con buttonmessage_con'. $counter .' animated animatedopacity clearfixmain">
							<a class="commonpop" data-type="iframe" href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-talk-to-specialist") .'?make_id='. $slider_make_id .'">'. $custom_button_text .'</a>
							<div class="sliderphone">'. $phone .'</div>
						</div>
					</div>
					 ';
					 
					 if ($display_make_name == 1 AND $slider_make_id > 0){
						 global $yachtclass;
						 $make_name_format = $yachtclass->format_brand_name($make_name);
						 $messagetxt .= '
						 <div class="textmessage transparentbgcolor middleleft clearfixmain">
						 	<h2>'. $make_name_format .'</h2>
						 </div>
						 ';
					 }
				 }
				 
				 if ($wh_video > 0){
					 $video_filepath = $cm->folder_for_seo . $imagefolder . $videopath;
					 $typecast = $this->get_video_type_cast($video_filepath);
					 $returntxt .= '
					 <video loop muted autoplay poster="'. $cm->folder_for_seo . $imagefolder . 'videoimg.jpg" id="bgvid" allow="autoplay">
						<source src="'. $video_filepath .'" type="'. $typecast .'">  
					 </video>
					 '. $messagetxt .'
					 ';
				 }else{
					 
					 if ($video_type == 2){
						//youtube
						$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						//https://img.youtube.com/vi/'. $video_id .'/0.jpg
						$u = "https://www.youtube.com/watch?v=" . $video_id;
						$returntxt .= '
						<div>
						<div class="item-video" data-merge="1">
						<a class="owl-video" href="'. $u .'"></a>
						'. $messagetxt .'
						</div>
						</div>
						';					
					}elseif($video_type == 3){
						//vimeo
						//$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						 
						$returntxt .= '
						<div class="item-video" data-merge="1">
						<a class="owl-video" href="'. $cm->filtertextdisplay($video_url) .'"></a>
						'. $messagetxt .'
						</div>
						';						
					}else{
						//image
						$returntxt .= '
						<div>
						'. $messagetxt .'
						<img class="" src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'">
						</div>
						';
					 }					 
				 }				 
						
				$counter++;
			 }
			
			if ($wh_video > 0){
				$returntxt .= '
				<div class="videooptions">
				<a class="videosound videosoundmute" href="javascript:void(0);" title="Un Mute">Mute/Un Mute</a>
				<a class="videoplaypause" href="javascript:void(0);" title="Pause Video">Play/Pause</a>
				</div>
				</div>
				';
				
				$returntxt .= '
				<script language="javascript" type="text/javascript">
				$(document).ready(function(){
					$("#bgvid").on("loadstart", function (event) {
						$(this).addClass("videoloading");
					});
					
					$("#bgvid").on("canplay", function (event) {
						$(this).removeClass("videoloading");
						$("#bgvid").get(0).play();
					});
					
					$(".videoplaypause").click(function(){
						if ($(this).hasClass("videoplaypause videopause")){
							$("#bgvid").get(0).play();
							$(this).removeClass("videopause");
							$(this).attr("title", "Pause Video");
						}else{
							$("#bgvid").get(0).pause();
							$(this).addClass("videopause");
							$(this).attr("title", "Play Video");
						}
					});
					
					$(".videosound").click(function(){
						if ($(this).hasClass("videosoundmute")){
							$("#bgvid").prop("muted", false);
							$(this).removeClass("videosoundmute");
							$(this).attr("title", "Mute");
						}else{
							$("#bgvid").prop("muted", true);
							$(this).addClass("videosoundmute");
							$(this).attr("title", "Play Sound");
						}
					});
					
					//checking mobile
					var isMobile = {
						Android: function() {
							return navigator.userAgent.match(/Android/i);
						},
						BlackBerry: function() {
							return navigator.userAgent.match(/BlackBerry/i);
						},
						iOS: function() {
							return navigator.userAgent.match(/iPhone|iPad|iPod/i);
						},
						Opera: function() {
							return navigator.userAgent.match(/Opera Mini/i);
						},
						Windows: function() {
							return navigator.userAgent.match(/IEMobile/i);
						},
						any: function() {
							return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
						}
					};
					
					if(isMobile.any()) {
						// It is mobile
						$("#bgvid").get(0).pause();
						$(".videoplaypause").addClass("videopause");
						$(".videoplaypause").attr("title", "Play Video");
					}
					
				});
				</script>
				';
			}else{
				$returntxt .= '		
				</div>
				</div>
				';
				
				if ($templatefile == "home.php"){
					global $yachtchildclass;			
					$boatsearchformar = $yachtchildclass->display_boat_advanced_search_form_small();

					$returntxt = '
					<div class="slidersearch clearfixmain">
						<div class="container clearfixmain">
						'. $boatsearchformar["smallform"] .'				
						</div>
						'. $boatsearchformar["responsiveform"] .'				
						'. $returntxt .'
					</div>
					';
				}
				
				$returntxt .= '<script>
				function highlight(pos){
					if ($(".captionanimation").length > 0) {
						$(".captionanimation").removeClass("animated delay1 fadeIn");
						$(".owl-item").not(".cloned").eq(pos).find(".captionanimation").addClass("animated delay1 fadeIn");
					}
					
					if ($(".scaptionanimation").length > 0) {
						$(".scaptionanimation").removeClass("animated delay2 fadeIn");
						$(".owl-item").not(".cloned").eq(pos).find(".scaptionanimation").addClass("animated delay2 fadeIn");
					}
					
					if ($(".sliderdetailsbutton").length > 0) {
						$(".sliderdetailsbutton").removeClass("animated delay3 fadeIn");
						$(".owl-item").not(".cloned").eq(pos).find(".sliderdetailsbutton").addClass("animated delay3 fadeIn");
					}
				};

				$(document).ready(function(){
					var owl_mainslider = $("#mainslider");
					
					owl_mainslider.on(\'initialized.owl.carousel\', function(event) {
						highlight(0);						
					});
					
					owl_mainslider.owlCarousel({
						items: 1,
						merge: true,
						video: true,
						loop: true,
						autoplay: true,
						autoplayHoverPause: true,
						center              :true,
						stagePadding		:0,
						autoplayTimeout: '. $time_value .',
						animateOut: \'fadeOut\',
						nav: false,
						navText: ["<span class=\"homeslider-prev\"></span>","<span class=\"homeslider-next\"></span>"],
						margin: 0
					});					
					
					owl_mainslider.on(\'changed.owl.carousel\', function(event) {
						var item = event.page.index;						
						highlight(item);						
					});
				});
				</script>						
				';
			}
			
		}else{
			$returntxt = '';
		}						
		return $returntxt;
	}
	
	public function display_top_image_slider($param = array()){
		global $db, $cm;
		
		//param
		$slider_category_id = $param["slider_category_id"];
		$templatefile = $param["templatefile"];
		$slider_make_id = $param["slider_make_id"];
		$display_make_name = $param["display_make_name"];
		//end
		
		$tag_line = $cm->get_systemvar('HMTAG');
		$time_value = $cm->get_systemvar('SLTMH');
   		$time_value = round($time_value * 1000);
		if ($time_value == 0){ $time_value = 5000; }
				
		/*$imagefolder = 'sliderimage/inner/';
		if ($slider_category_id == 1){
			$imagefolder = 'sliderimage/';
		}*/
		$imagefolder = 'sliderimage/';
		
		$hang_button_text = '';
		/*if ($slider_category_id == 1 AND $templatefile = "home.php"){
			$hang_button_text = '
			<div class="fcfirst-section clearfixmain">
				<div class="fcscroll1"><a class="fcscrollto" href="#modellist">Custom Luxury Tenders</a></div>
			</div>
			';
		}*/
		
		$video_sql_check = "select count(*) as ttl from tbl_image_slider where category_id = '". $slider_category_id ."' and video_type = 4";
		$wh_video = $db->total_record_count($video_sql_check);
				
        $query_sql = "select *,";
        $query_form = " from tbl_image_slider,";
        $query_where = " where";
		
		if ($slider_category_id > 0){
			$query_where .= " category_id = '". $slider_category_id ."' and";
		}

        $query_where .= " status_id = 1 and";
      
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        
		if ($wh_video > 0){
			$sql = $sql." order by RAND() limit 0, 1";
		}else{		
			$sql = $sql." order by rank";
		}
		
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
        if ($found > 0){
			
			if ($wh_video > 0){
				$text_animation_class = '';
				$returntxt = '
				<div class="imagevideoslider mormalvideo clearfixmain">				
				';
			}else{			
				$returntxt = '
				<div class="normalslider clearfixmain">
				'. $hang_button_text .'
				<div class="main-slider" id="mainslider">
				';
			}
			
			$counter = 0;
			 foreach($result as $row){
				 
				 foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				 }
				
				 $text_css_name = $cm->get_common_field_name("tbl_slider_text_position", "css_name", $text_position_id);
				 $messagetxt = '';
				 
				 if ($display_message == 1){
					 			 
					 if ($link_type == 1){
						  $go_url = $page_url;
					 }elseif ($link_type == 2){
						  $go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
					 }else{
						  $go_url = '';
					 }
					 
					 $s_an = '';
					 $e_an = '';
					 $s_an_text = '';
					 $messagelink = '';
					 if ($go_url != ""){
						 $link_target = "";
						 if ($new_window == "y"){ $link_target = ' target = "_blank"'; }
						 $s_an = '<a href="'. $go_url .'"'. $link_target .'>';
						 $s_an_text = '<a style="color: #'. $fontcolor .';" href="'. $go_url .'"'. $link_target .'>';
						 $e_an = '</a>';					 				 
						 $messagelink = '<div class="sliderdetailsbutton sliderdetailsbutton'. $counter .' clearfixmain"><a href="'. $go_url .'"'. $link_target .'>' . $buttontext .'' . $e_an . '</div>';
					 }
					 			 
					 $messagetxt = '<h2 class="captionanimation captionanimation'. $counter .'" style="color: #'. $fontcolor .'">'. $s_an_text . $name . $e_an .'</h2>';
					 if ($pdes != ''){ $messagetxt .= '<p class="scaptionanimation scaptionanimation'. $counter .'" style="color: #'. $fontcolor2 .'">'. $pdes .'</p>'; }
					 $messagetxt = '
					 <div class="textmessage '. $text_css_name .' clearfixmain">
						<div class="textmessage_con clearfixmain">'. $messagetxt . '</div>' . $messagelink . '
					 </div>';
			
				 }
				 
				 if ($display_message == 2){	
				 	 $phone = $cm->get_systemvar('PCLNW');				 
					 if ($slider_make_id > 0){
						 $make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $slider_make_id);
						 $custom_button_text = "Talk To A Certified ". $make_name ." Specialist";
					 }else{
						 $custom_button_text = "Talk To A Specialist";
					 }
					 
					$messagetxt = '
					<div class="buttonmessage '. $text_css_name .' clearfixmain">
						<div datadelay="500" class="buttonmessage_con buttonmessage_con'. $counter .'  clearfixmain">
							<a class="commonpop" data-type="iframe" href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-talk-to-specialist") .'?make_id='. $slider_make_id .'">'. $custom_button_text .'</a>
							<div class="sliderphone">'. $phone .'</div>
						</div>
					</div>
					 ';
					 
					 if ($display_make_name == 1 AND $slider_make_id > 0){
						 global $yachtclass;
						 $make_name_format = $yachtclass->format_brand_name($make_name);
						 $messagetxt .= '
						 <div class="textmessage transparentbgcolor middleleft clearfixmain">
						 	<h2>'. $make_name_format .'</h2>
						 </div>
						 ';
					 }
				 }
				 
				 if ($wh_video > 0){
					 $video_filepath = $cm->folder_for_seo . $imagefolder . $videopath;
					 $typecast = $this->get_video_type_cast($video_filepath);
					 $returntxt .= '
					 <video loop muted autoplay poster="'. $cm->folder_for_seo . $imagefolder . 'videoimg.jpg" id="bgvid" allow="autoplay">
						<source src="'. $video_filepath .'" type="'. $typecast .'">  
					 </video>
					 '. $messagetxt .'
					 ';
				 }else{
					 $loadingtext = '';
					 if ($counter == 0){
						//$loadingtext = '<span class="loading">Loading...</span>';
					 }
					
					 if ($video_type == 2){
						//youtube
						$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						//https://img.youtube.com/vi/'. $video_id .'/0.jpg
						$u = "https://www.youtube.com/embed/" . $video_id . "?enablejsapi=1";
						
						$returntxt .= '
						<div class="item youtube">
							'. $loadingtext .'
							<iframe class="embed-player slide-media" src="'. $u .'" frameborder="0" allowfullscreen></iframe>
							'. $messagetxt .'
						</div>
						';				
					}elseif($video_type == 3){
						//vimeo
						$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($video_url));
						$u = "https://player.vimeo.com/video/". $video_id ."?api=1&byline=0&portrait=0&title=0&background=1&mute=1&loop=1&autoplay=0&id=". $video_id .""; 
						$returntxt .= '
						<div class="item vimeo" data-video-start="4">
							'. $loadingtext .'
							<iframe class="embed-player slide-media" src="'. $u .'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							'. $messagetxt .'
						</div>
						';						
					}else{
						//image
						$returntxt .= '
						<div class="item image">
							'. $loadingtext .'
							<figure>
								<div class="slide-image slide-media" style="background-image:url(\''. $cm->folder_for_seo . $imagefolder . $imgpath .'\');">
								<img alt="'. $name .'" data-lazy="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" class="image-entity" />
								</div>
								'. $messagetxt .'
							</figure>
						</div>
						';
					 }					 
				 }				 
						
				$counter++;
			 }
			
			if ($wh_video > 0){
				$returntxt .= '
				<div class="videooptions">
				<a class="videosound videosoundmute" href="javascript:void(0);" title="Un Mute">Mute/Un Mute</a>
				<a class="videoplaypause" href="javascript:void(0);" title="Pause Video">Play/Pause</a>
				</div>
				</div>
				';
				
				$returntxt .= '
				<script language="javascript" type="text/javascript">
				$(document).ready(function(){
					$("#bgvid").on("loadstart", function (event) {
						$(this).addClass("videoloading");
					});
					
					$("#bgvid").on("canplay", function (event) {
						$(this).removeClass("videoloading");
						$("#bgvid").get(0).play();
					});
					
					$(".videoplaypause").click(function(){
						if ($(this).hasClass("videoplaypause videopause")){
							$("#bgvid").get(0).play();
							$(this).removeClass("videopause");
							$(this).attr("title", "Pause Video");
						}else{
							$("#bgvid").get(0).pause();
							$(this).addClass("videopause");
							$(this).attr("title", "Play Video");
						}
					});
					
					$(".videosound").click(function(){
						if ($(this).hasClass("videosoundmute")){
							$("#bgvid").prop("muted", false);
							$(this).removeClass("videosoundmute");
							$(this).attr("title", "Mute");
						}else{
							$("#bgvid").prop("muted", true);
							$(this).addClass("videosoundmute");
							$(this).attr("title", "Play Sound");
						}
					});
					
					//checking mobile
					var isMobile = {
						Android: function() {
							return navigator.userAgent.match(/Android/i);
						},
						BlackBerry: function() {
							return navigator.userAgent.match(/BlackBerry/i);
						},
						iOS: function() {
							return navigator.userAgent.match(/iPhone|iPad|iPod/i);
						},
						Opera: function() {
							return navigator.userAgent.match(/Opera Mini/i);
						},
						Windows: function() {
							return navigator.userAgent.match(/IEMobile/i);
						},
						any: function() {
							return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
						}
					};
					
					if(isMobile.any()) {
						// It is mobile
						$("#bgvid").get(0).pause();
						$(".videoplaypause").addClass("videopause");
						$(".videoplaypause").attr("title", "Play Video");
					}
					
				});
				</script>
				';
			}else{
				$returntxt .= '		
				</div>
				</div>
				';
				
				$returntxt .= '<script>				
				var slideWrapper = $(".main-slider"),
				iframes = slideWrapper.find(".embed-player"),
				lazyImages = slideWrapper.find(".slide-image"),
				lazyCounter = 0;
				
				// POST commands to YouTube or Vimeo API
				function postMessageToPlayer(player, command){
					if (player == null || command == null) return;
					player.contentWindow.postMessage(JSON.stringify(command), "*");
				}
				
				// When the slide is changing
				function playPauseVideo(slick, control){
				  var currentSlide, slideType, startTime, player, video;
				
				  currentSlide = slick.find(".slick-current");
				  slideType = currentSlide.attr("class").split(" ")[1];
				  player = currentSlide.find("iframe").get(0);
				  startTime = currentSlide.data("video-start");
				
				  if (slideType === "vimeo") {
					switch (control) {
					  case "play":
						if ((startTime != null && startTime > 0 ) && !currentSlide.hasClass("started")) {
						  currentSlide.addClass("started");
						  postMessageToPlayer(player, {
							"method": "setCurrentTime",
							"value" : startTime
						  });
						}
						postMessageToPlayer(player, {
						  "method": "play",
						  "value" : 1
						});
						break;
					  case "pause":
						postMessageToPlayer(player, {
						  "method": "pause",
						  "value": 1
						});
						break;
					}
				  } else if (slideType === "youtube") {
					switch (control) {
					  case "play":
						postMessageToPlayer(player, {
						  "event": "command",
						  "func": "mute"
						});
						postMessageToPlayer(player, {
						  "event": "command",
						  "func": "playVideo"
						});
						break;
					  case "pause":
						postMessageToPlayer(player, {
						  "event": "command",
						  "func": "pauseVideo"
						});
						break;
					}
				  } else if (slideType === "video") {
					video = currentSlide.children("video").get(0);
					if (video != null) {
					  if (control === "play"){
						video.play();
					  } else {
						video.pause();
					  }
					}
				  }
				}
				
				// Resize player
				function resizePlayer(iframes, ratio) {
				  if (!iframes[0]) return;
				  var win = $(".main-slider"),
					  width = win.width(),
					  playerWidth,
					  height = win.height(),
					  playerHeight,
					  ratio = ratio || 16/9;
				
				  iframes.each(function(){
					var current = $(this);
					if (width / ratio < height) {
					  playerWidth = Math.ceil(height * ratio);
					  current.width(playerWidth).height(height).css({
						left: (width - playerWidth) / 2,
						 top: 0
						});
					} else {
					  playerHeight = Math.ceil(width / ratio);
					  current.width(width).height(playerHeight).css({
						left: 0,
						top: (height - playerHeight) / 2
					  });
					}
				  });
				}
				
				// DOM Ready
				$(function() {
				  // Initialize
				  slideWrapper.on("init", function(slick){
					slick = $(slick.currentTarget);
					setTimeout(function(){
					  playPauseVideo(slick,"play");
					}, 1000);
					resizePlayer(iframes, 16/9);
				  });
				  slideWrapper.on("beforeChange", function(event, slick) {
					slick = $(slick.$slider);
					playPauseVideo(slick,"pause");
				  });
				  slideWrapper.on("afterChange", function(event, slick) {
					slick = $(slick.$slider);
					playPauseVideo(slick,"play");
				  });
				  slideWrapper.on("lazyLoaded", function(event, slick, image, imageSource) {
					lazyCounter++;
					if (lazyCounter === lazyImages.length){
					  lazyImages.addClass("show");
					  // slideWrapper.slick("slickPlay");
					}
				  });
				  
				  //start the slider
				  slideWrapper.slick({
					fade:true,
					autoplay: true,
					autoplaySpeed:'. $time_value .',
					lazyLoad:"progressive",
					speed:600,
					arrows:false,
					dots:true,
					pauseOnHover: true,
					cssEase:"cubic-bezier(0.87, 0.03, 0.41, 0.9)"
				  });
				 });
				 
				 // Resize event
				$(window).on("resize.slickVideoPlayer", function(){  
				  resizePlayer(iframes, 16/9);
				});
				</script>						
				';
			}
			
			if ($templatefile == "home.php"){
				global $yachtchildclass;			
				$boatsearchformar = $yachtchildclass->display_boat_advanced_search_form_small();

				$returntxt = '
				<div class="slidersearch clearfixmain">
					<div class="container clearfixmain">
					'. $boatsearchformar["smallform"] .'				
					</div>
					'. $boatsearchformar["responsiveform"] .'				
					'. $returntxt .'
				</div>
				';
			}
			
		}else{
			$returntxt = '';
		}						
		return $returntxt;
	}
}
?>