<?php
class Makeclass {
	//-----------------make only profile-------------------------------
	public function default_meta_info_make(){
          global $db;
          $bsql = "select * from tbl_tag_make";
          $bresult = $db->fetch_all_array($bsql);
          $brow = $bresult[0];
          return (object)$brow;
    }
	
	public function collect_meta_info_make($m1, $m2, $m3, $make_id){
		global $cm;
		$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'name, description', $make_id);
		$manufacturer_name = $manufacturerar[0]["name"];
		$manufacturerardescription = $manufacturerar[0]["description"];
		$cname = $cm->sitename;
		
		$default_meta = $this->default_meta_info_make();
		if ($m1 == ""){ 					
			$m1 = $default_meta->m1;
			$m1 = str_replace("#make#", $manufacturer_name, $m1);
			$m1 = str_replace("#companyname#", $cname, $m1);
		}
		if ($m2 == ""){
			$m2 = $default_meta->m2;
			if ($m2 == ""){ 
				$m2 = $cm->get_sort_content_description($manufacturerardescription, 150); 
			}else{
				$m2 = str_replace("#make#", $manufacturer_name, $m2);
				$m2 = str_replace("#companyname#", $cname, $m2);
			}
		}
		if ($m3 == ""){ 
			$m3 = $default_meta->m3; 
		}
		
		$final_meta = array("m1" => $m1, "m2" => $m2, "m3" => $m3);
		return (object)$final_meta;
	}
	
	public function display_make_profile($argu = array()){
		global $db, $cm, $yachtclass;
		$manufacturer_id = round($argu["makeid"], 0);
		$condition_raw = $argu["condition"];
		
		$main_website_url = $cm->get_data_web_url();
		
		$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'slug, name, description, logo_image', $manufacturer_id);
		$manufacturerarslug = $manufacturerar[0]["slug"];
		$manufacturerarname = $manufacturerar[0]["name"];
		$manufacturerardescription = $manufacturerar[0]["description"];
		$manufacturerarlogo_image = $manufacturerar[0]["logo_image"];
		if ($manufacturerarlogo_image == ""){ $manufacturerarlogo_image = 'no.jpg'; }
		$manufacturerarlogo_image = $main_website_url . '/manufacturerimage/' . $manufacturerarlogo_image;
		
		$boat_button_text = '';
		if ($condition_raw != ""){
			$condition_ar = explode(",", $condition_raw);
			foreach ($condition_ar as $condition_row){
				
				if ($condition_row == 1){
					$button_text = 'New Inventory in Stock';
					$conditionslug = 'new';					
				}
				
				if ($condition_row == 2){
					
					/*if (strpos($manufacturerarname, 'Yachts') !== false){
						$button_text = 'Used '. $manufacturerarname;
					}else{
						$button_text = 'Used '. $manufacturerarname .' Yachts';
					}*/
					
					$button_text = 'Used '. $manufacturerarname .' Boats';	
					$conditionslug = 'used';				
				}
				
				$inv_url_format = 'make/' . $manufacturerarslug . '/condition/' . $conditionslug;
				$pagename = $cm->serach_url_filtertext($inv_url_format);
          		$ret_url = $cm->folder_for_seo . $pagename . "/";
				
				$boat_button_text .= '<li><a href="'. $ret_url .'" class="button">'. $button_text .'</a></li>';
			}

			if ($boat_button_text != ""){
				$boat_button_text = '<ul class="makebutton">'. $boat_button_text .'</ul>';
			}
		}
				
		$returntext = '
		<div class="profile-main">
			<div class="mainleft">
				<img src="'. $manufacturerarlogo_image .'" alt="" />
			</div>
			<div class="mainright">        
				<div class="meta">
				<h1>'. $manufacturerarname .'</h1>            
				</div>         
				'. $manufacturerardescription .'
				'. $boat_button_text .'
			</div>
			<div class="clear"></div>
		</div>
		';
		return $returntext;
	}
	//-----------------/make only profile-------------------------------
	
	//-----------------YC DATA MAKE-GROUP-------------------------------
	
	public function display_make_group_profile_yc($argu = array()){
		global $db, $cm, $yachtclass, $ymclass;
		$makeid = round($argu["makeid"], 0);		
		$returntext = '';
		
		$retval = $ymclass->get_manufacturer_model_group_list_raw($makeid);
		$retval = json_decode($retval);
		$total_record = count($retval);
		
		if ($total_record > 0){
			$returntext .= '
			<div class="fourcolumnlist clearfixmain">
			<ul>
			';
			
			foreach($retval as $vrow){
				$groupid = $vrow->id;
				$groupname = $vrow->name;
				$imagefolder = $vrow->imagefolder;
				$groupimage = $vrow->group_image;
				
				$groupimage = $cm->get_data_web_url() . "/" . $imagefolder . $groupimage;
				$checkingshortcode = "[fcpredesignmake makeid=". $makeid ." modelgroupid=". $groupid ."]";
				$goto_page_id = $cm->get_page_id_by_shortcode($checkingshortcode);
				$gotopageurl = $cm->get_page_url($goto_page_id, "page");
				
				$returntext .= '
				<li class="clearfixmain"><a href="'. $gotopageurl .'">
				<div class="topimg"><img src="'. $groupimage .'"></div>
				<div class="botimg">'. $groupname .'</div>
				</a></li>
				';
			}
			
			$returntext .= '
			</ul>
			</div>';
		}
				
		return $returntext;
	}
	
	//-----------------/YC DATA MAKE-GROUP-------------------------------
	
	//-----------------YC DATA MAKE-MODEL-------------------------------
	
	public function display_make_boat_profile_yc($argu = array()){
		global $db, $cm, $yachtclass, $ymclass;
		$makeid = round($argu["makeid"], 0);
		$modelgroupid = round($argu["modelgroupid"], 0);
		
		$returntext = '';
		$p = 1;
		$retval = json_decode($ymclass->get_manufacturer_model_list($makeid, $modelgroupid, $p));
		$foundm = $retval[0]->foundm;
		$template_id = $retval[0]->template_id;
		
		if ($foundm > 0){
			$manufacturerarname = $retval[0]->manufacturerarname;
			
			//sidebar data
			$makecontent_ar = $cm->get_table_fields("tbl_manufacturer_catalog", "catalog_link, imgpath", $makeid, "manufacturer_id");
			$catalog_link = $makecontent_ar[0]["catalog_link"];
			$sidebar_imgpath = $makecontent_ar[0]["imgpath"];
			
			$catalog_link_text = '';			
			if ($catalog_link != ""){
				$catalog_link_text = '<div class="commonsection"><a href="'. $catalog_link .'" class="button afterarrow" target="_blank">View Interactive '. $manufacturerarname .' Catalog</a></div>';
			}
			//end
			
			//used page link and image
			$checkcode = "[fcboatlist makeid=". $makeid ." conditionid=2";
			$usedpageid = $cm->get_page_id_by_shortcode($checkcode);
			$usedpageurl = $cm->get_page_url($usedpageid, "page");
			
			if ($template_id == 2){
			
				$usedbutton_border_class = '';
				$sidebar_imgpath_text = '';
				if ($sidebar_imgpath != ""){
					$sidebar_imgpath_text = '
					<div class="boximagemake clearfixmain"><a href="'. $usedpageurl .'"><img src="'. $cm->folder_for_seo .'manufacturerimage/sidebar/'. $sidebar_imgpath .'" /></a></div>
					';
					$usedbutton_border_class = ' toprightround';
				}

				/*if (strpos($manufacturerarname, 'Yachts') !== false){
					$button_text = "Inventory";
				}else{
					$button_text = "Yachts Inventory";
				}*/

				$manufacturerarname_format = $yachtclass->format_brand_name($manufacturerarname);
				$button_text = "Inventory";
				//end

				$returntext .= '
				<div class="model-detail clearfixmain">
					<div class="left-cell">
						<div id="filtersection" class="profile-main">
							'. $retval[0]->doc .'
							<div class="clear"></div>
						</div>
						<div class="mostviewed">
							<p class="t-center">'. $retval[0]->moreviewtext .'</p>
						</div>
					</div>

					<div class="right-cell scrollcol" parentdiv="model-detail">
						'. $catalog_link_text .'
						<div class="commonsection"><a href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-ask-for-brochure") .'?make_id='. $makeid .'" class="button afterarrow buttonstyle2 commonpop" data-type="iframe">Ask For '. $manufacturerarname .' Brochure</a></div>

						<div class="commonsection section2"><a href="javascript:void(0);" data-src="'. $cm->get_page_url(0, "pop-trade-in-welcome") .'" class="commonpop" data-type="iframe">
							<span>Request your</span>
							Free Trade-in Valuation</a>
						</div>

						<div class="commonsection clearfixmain">
							<a href="'. $usedpageurl .'" class="button buttonstyle3'. $usedbutton_border_class .'">Used <span>'. $manufacturerarname_format .'</span> '. $button_text .'</a>
							'. $sidebar_imgpath_text .'
						</div>
					</div>	
				</div>		
				';
			}else{
				$returntext .= '
				<div class="model-detail clearfixmain">
					<div id="filtersection" class="profile-main clearfixmain">
						'. $retval[0]->doc .'
					</div>
					<div class="mostviewed">
						<p class="t-center">'. $retval[0]->moreviewtext .'</p>
					</div>
				</div>
				';
			}
		}
				
		return $returntext;
	}
	
	//-----------------/YC DATA MAKE-MODEL-------------------------------
	
	//-----------------LOCAL DATA BOAT BASED ON MAKE-------------------------------
	public function boat_sql($argu = array()){
		$makeid = round($argu["makeid"], 0);
		
		$query_sql = "select a.*";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_where .= " a.manufacturer_id = '". $makeid ."' and";
		
		$query_where .= " a.status_id IN (1,3) and";
        $query_where .= " a.display_upto >= CURDATE() and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
	}
	
	function total_boat_found($sql){
        global $db;
        $sqlm = str_replace("select a.*","select count(distinct a.id) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function display_make_boat_profile_local($p, $argu = array(), $ajaxpagination = 0){
		global $db, $cm, $yachtclass, $ymclass;
		$returntext = '';
        $moreviewtext = '';
		
		$dcon = $cm->pagination_record;
		$page = ($p - 1) * $dcon;
		if ($page <= 0){ $page = 0; }
		$limitsql = " LIMIT ". $page .", ". $dcon;
		
		$sorting_sql = "c.length desc";
        $sql = $this->boat_sql($argu);
        $foundm = $this->total_boat_found($sql);
		
		$sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
		$remaining = $foundm - ($p * $dcon);
		if ($found > 0){
			//list view or grid view
			$class_ex = '';
			$imoption = 0;
			if ($displayoption == 2){
				$class_ex = ' list-view';
			}
			
			$extraclass = 'class="no-transition hidden-listing"';
			if ($ajaxpagination == 0){
				$extraclass = '';
				$returntext .= '
				<ul id="listingholder" class="product-list'. $class_ex .'">
				';
			}
			
			foreach($result as $row){
				$returntext .= $yachtclass->display_yacht($row, $displayoption, $extraclass, $compareboat, $charter);				
			}
			
			$p++;
			if ($remaining > $dcon){
				$button_no = $dcon;
			}else{
				$button_no = $remaining;
			}
			
			if ($ajaxpagination == 0){
				$returntext .= '
				</ul>
				<div class="clear"></div>
				';
			}
			
			if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" c=\''. json_encode($argu) .'\' class="moreboat button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
				
		}else{
			global $frontend;
			$returntext = '<script src="https://www.google.com/recaptcha/api.js" async defer></script><p>'. $cm->get_systemvar('BTNFD') .'</p>'. $frontend->display_boat_finder_form(1);
        }
		
		$returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext,
			'foundm' => $foundm
        );
        return json_encode($returnval);
	}
	
	public function display_make_boat_profile_local_main($argu = array()){
		global $db, $cm, $yachtclass, $ymclass;
		$makeid = round($argu["makeid"], 0);		
		
		$returntext = '';
		$p = 1;
		$retval = json_decode($this->display_make_boat_profile_local($p, $argu));
		$foundm = $retval[0]->foundm;
		
		$returntext = '
		<div class="profile-main clearfixmain">
			<div class="header-bottom-bg clearfixmain">
				<div class="header-bottom-inner clearfixmain">
					<div class="sch">
						<span>Boat Lists</span>
					</div>
					<div class="vp">'. $foundm .' result(s)</div>
				</div>
			</div>			
		</div>
			
		<div id="filtersection" class="mostviewed clearfixmain">
		'. $retval[0]->doc .'
		</div>
		';
		
		$returntext .= '
	  	<script type="text/javascript">
		$(document).ready(function(){
			$.fn.filterboat = function(p, c){
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						p:p,
						searchfields:c,
						az:51,
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
								$("#listingholder").append(content);
								setTimeout( function() {
									$("#filtersection ul.product-list li").removeClass( "no-transition" )
										.removeClass( "hidden-listing" );
								}, 30);
							}
						}else{
							$(\'#filtersection\').html(\'Sorry. Record unavailable.\');
						}
						$(".t-center").html(moreviewtext);
						$(document.body).trigger("sticky_kit:recalc");
					});
			}
	
			$(".main").on("click", ".moreboat", function(){
				var p = $(this).attr("p");
				var c = $(this).attr("c");
				$(this).filterboat(p, c);
			});
		});
		</script>
	  ';
				
		return $returntext;
	}
	
	//-----------------/LOCAL DATA BOAT BASED ON MAKE-------------------------------
	
	//-----------------HOME PAGE NEW BOATS - BY YC BOAT-------------------------------
	public function check_yc_boats_assign_for_home_by_make($manufacturer_id, $model_id){
		global $db, $cm;
		$sql = "select count(*) as ttl from tbl_manufacturer_model_home_assign where make_id = '". $manufacturer_id ."' and model_id = '". $model_id ."'";
		$found = $db->total_record_count($sql);
		return $found;
	}
	
	public function assign_new_boats_yc(){
		global $db, $cm, $yachtchildclass, $ymclass;
		$returntext = '';
		$rc_count = 0;
		$manufacturer_ar = $yachtchildclass->eligible_make();		
		
		foreach($manufacturer_ar as $manufacturerid){
			
			$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'name', $manufacturerid);
			$manufacturerarname = $manufacturerar[0]["name"];
			
			
			$make_model_ar = $ymclass->get_manufacturer_model_list_all($manufacturerid);
			$make_model_ar = json_decode($make_model_ar);
			$result = $make_model_ar->docarray;
						
			$returntext .= '
			<div class="locationorder">
				<h3>'. $manufacturerarname .'</h3>
				<ul class="recordorder nosort">
			';
			
			foreach($result as $row){
				$model_id = $row->id;
				$model = $cm->filtertextdisplay($row->model);
				$year = $cm->filtertextdisplay($row->year);
				$imgurl = $cm->filtertextdisplay($row->imgurl);				
				$boat_title = $year . '&nbsp;' . $manufacturerarname . '&nbsp;' . $model;
	
				$bck = '';
				if ($this->check_yc_boats_assign_for_home_by_make($manufacturerid, $model_id) > 0){
					$bck = ' checked="checked"';	
				}
				
				$returntext .= '
				<li id="item-'. $model_id .'">
				<div class="top">'. $imgurl .'</div>
				<div class="middle" title="'. $boat_title .'">'. $boat_title .'</div>
				<div class="bottom">Assign? <input class="checkbox assignboat" type="checkbox" manufacturer_id="'. $manufacturerid .'" name="home_page_new_boats'. $model_id .'" id="home_page_new_boats'. $model_id .'" value="'. $model_id .'"'. $bck .' /></div>
				</li>
				';				
				$rc_count++;		
			}
			
			$returntext .= '
				</ul>
				<div class="clearfix"></div>
			</div>
			';
		}		
		return $returntext;
	}
	
	public function assign_new_boats_sub_yc($argu = array()){
		global $db, $cm;
		$manufacturer_id = round($argu["manufacturer_id"], 0);
		$boat_id = round($argu["boat_id"], 0);
		$home_page_new_boats = round($argu["home_page_new_boats"], 0);
	
		$sql = "delete from tbl_manufacturer_model_home_assign where make_id = '". $manufacturer_id ."' and model_id = '". $boat_id ."'";
		$db->mysqlquery($sql);
		
		if ($home_page_new_boats > 0){
			$sql = "insert into tbl_manufacturer_model_home_assign (make_id, model_id) values ('". $manufacturer_id ."', '". $boat_id ."')";
			$db->mysqlquery($sql);
		}		
	}
	//-----------------HOME PAGE NEW BOATS - BY YC BOAT-------------------------------
}
?>