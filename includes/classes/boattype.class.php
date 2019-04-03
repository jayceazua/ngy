<?php
class BoatTypeclass {
	//-----------------Boat Type only profile-------------------------------
	public function default_meta_info_boat_type(){
          global $db;
          $bsql = "select * from tbl_tag_boat_type";
          $bresult = $db->fetch_all_array($bsql);
          $brow = $bresult[0];
          return (object)$brow;
    }
	
	public function collect_meta_info_boat_type($m1, $m2, $m3, $boat_type_id){
		global $cm;
		$cname = $cm->sitename;
		
		$boattypear = $cm->get_table_fields('tbl_type', 'name, pdes', $boat_type_id);
		$boat_type_name = $boattypear[0]["name"];
		$boat_type_description = $boattypear[0]["pdes"];
		
		$default_meta = $this->default_meta_info_boat_type();
		if ($m1 == ""){ 					
			$m1 = $default_meta->m1;
			$m1 = str_replace("#boattype#", $boat_type_name, $m1);
			$m1 = str_replace("#companyname#", $cname, $m1);
		}
		if ($m2 == ""){
			$m2 = $default_meta->m2;
			if ($m2 == ""){ 
				$m2 = $cm->get_sort_content_description($boat_type_description, 150); 
			}else{
				$m2 = str_replace("#boattype#", $boat_type_name, $m2);
				$m2 = str_replace("#companyname#", $cname, $m2);
			}
		}
		if ($m3 == ""){ 
			$m3 = $default_meta->m3; 
		}
		
		$final_meta = array("m1" => $m1, "m2" => $m2, "m3" => $m3);
		return (object)$final_meta;
	}
	
	public function display_boat_type_profile($argu = array()){
		global $db, $cm, $yachtclass;
		$boat_type_id = round($argu["boattypeid"], 0);
		$main_website_url = $cm->get_data_web_url();
				
		$boattypear = $cm->get_table_fields('tbl_type', 'slug, name, pdes, imgpath', $boat_type_id);
		$boattypeslug = $boattypear[0]["slug"];
		$boattypename = $boattypear[0]["name"];
		$boattypedescription = $boattypear[0]["pdes"];
		$boattypelogo_image = $boattypear[0]["imgpath"];
		if ($boattypelogo_image == ""){ $boattypelogo_image = 'no.jpg'; }
		$boattypelogo_image = $main_website_url . '/typeimage/' . $boattypelogo_image;
				
		$returntext = '
		<div class="profile-main">
			<div class="mainleft">
				<img src="'. $boattypelogo_image .'" alt="" />
			</div>
			<div class="mainright">        
				<div class="meta">
				<h1>'. $boattypename .'</h1>            
				</div>         
				'. $boattypedescription .'				
			</div>
			<div class="clear"></div>
		</div>
		';
		return $returntext;
	}
	//-----------------/Boat Type only profile-------------------------------
	
	
	//-----------------LOCAL DATA BOAT BASED ON BOAT TYPE-------------------------------
	public function boat_sql($argu = array()){
		$boattypeid = round($argu["boattypeid"], 0);
		
		$query_sql = "select a.*";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_form .= " tbl_yacht_type_assign as d,";
		$query_where .= " a.id = d.yacht_id and d.type_id = '". $boattypeid ."' and";		
	
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
        $sqlm = str_replace("select a.*","select count(a.id) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function display_boat_type_boat_profile_local($p, $argu = array(), $ajaxpagination = 0){
		global $db, $cm, $yachtclass, $ymclass;
		$returntext = '';
        $moreviewtext = '';
		
		$dcon = $cm->pagination_record;
		//$dcon = 2;
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
	
	public function display_boat_type_boat_profile_local_main($argu = array()){
		global $db, $cm, $yachtclass, $ymclass;
		$makeid = round($argu["makeid"], 0);		
		
		$returntext = '';
		$p = 1;
		$retval = json_decode($this->display_boat_type_boat_profile_local($p, $argu));
		$foundm = $retval[0]->foundm;
		
		$returntext = '
		
		<div class="profile-main">
			<div class="header-bottom-bg">
				<div class="header-bottom-inner">
					<div class="sch">
						<span>Boat Lists</span>
					</div>
					<div class="vp"><div class="spinnersmall"><span class="reccounterupdate">'. $foundm .'</span> result(s)</div></div>
					
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
			
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
			$.fn.filterboat = function(p, c){
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						p:p,
						searchfields:c,
						az:52,
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
	
	//-----------------/LOCAL DATA BOAT BASED ON BOAT TYPE-------------------------------
}
?>