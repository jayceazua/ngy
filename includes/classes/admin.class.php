<?php
class Adminclass {
  private $admin_login_time = 4500; // in sec

  public function validate_admin_login(){
      global $yachtclass;
	  //$this->sub_admin_login();
      $yachtclass->user_login(0, 1);
  }
  
  public function sub_admin_login(){
	  global $db, $cm, $edclass;
	  
	  $t1 = $_POST["t1"];
	  $t2 = $_POST["t2"];
	  
	  $sql = "select id, uid, name from tbl_sub_admin where uid = '" . $cm->filtertext($t1) . "' and pwd = '". $cm->filtertext($edclass->txt_encode($t2)) ."' and status_id = 1";
	  $result = $db->fetch_all_array($sql);
	  $found = count($result);
	  
	  if ($found > 0){
		  $row = $result[0];
		  $_SESSION["usernid"] = $row['id'];
		  //$_SESSION["usernid"] = 1;
		  $_SESSION["cr_uid"] = $row['uid'];
		  
		  $_SESSION["cr_user_name"] = $row['name'];
          $_SESSION["cr_type_id"] = 2;		  
		  $_SESSION["asuc"] = "true";
          $_SESSION["sesid"] = session_id();
		  $_SESSION["subadmin"] = 1;
		  
		  $remember_me = $_POST["remember_me"];
		  if ($remember_me == "y"){
				setcookie("cookie_member_id",$_SESSION['adminid'],time()+2592000);
		  }else{
				setcookie ("cookie_member_id","", time()-3600);
		  }

		  $currenttimecollect = time();
		  $diff = $currenttimecollect - (24 * 3600);

		  $sql = "delete from tbl_session where ses_en <= '". $diff ."'";
		  $db->mysqlquery($sql);
		  header('Location: adminhome.php');
		  exit;
	  }
  }
	
  public function admin_login(){
	  if ($_SESSION["asuc"] != "true"){
		  $_SESSION["pass"] = "www";
		  header('Location: index.php');
		  exit;
	  }
	  $this->session_check($_SESSION["sesid"], $this->admin_login_time, 'a');
  }
	
  public function go_to_admin_account(){
	  if ($_SESSION["asuc"] == "true"){
		header('Location: adminhome.php'); 	
		exit;
	  }
  }
	
  public function session_check($loggedsessionid, $exptime, $wheretogo){
	  global $db;
	  if ($exptime == 0){ $exptime = $this->admin_login_time; }
	  $sql = "select * from tbl_session where ses_id = '".$loggedsessionid."'";
	  $result = $db->fetch_all_array($sql);
	  $found = count($result); 
	  if ($found > 0){
            $row = $result[0];
            $ses_en = $row['ses_en'];
            $currenttimecollect = time();
            $diff = $currenttimecollect - $ses_en;

            if ($diff > $exptime){
                $sql = "delete from tbl_session where ses_id='".$loggedsessionid."'";
                $db->mysqlquery($sql);

                if ($wheretogo == "az"){
					$_SESSION["asuc"] = "";
                    $_SESSION["adminid"] = "";

                    $_SESSION["rank"] = "";
                    $_SESSION["uid"] = "";
					$_SESSION["pass"] = "logtimeout";
					return 1;
					exit;
				}elseif ($wheretogo == "u"){
                    $_SESSION["suc"] = "";
                    $_SESSION["pass"] = "wwr";
                    header('Location: index.php');
                    exit;
                }else{
                     $_SESSION["asuc"] = "";
                     $_SESSION["adminid"] = "";

                     $_SESSION["rank"] = "";
                     $_SESSION["uid"] = "";
					 $_SESSION["pass"] = "logtimeout";
                     header('Location: index.php');
                     exit;
                }
            }else{
				if ($wheretogo == "az"){
					return 0;
					exit;
				}else{
					$sql = "update tbl_session set ses_en = '".time()."' where ses_id = '".$loggedsessionid."'";
					$db->mysqlquery($sql);
				}
            }
	  
	  }else{
		  	if ($wheretogo == "az"){
				return 0;
				exit;
			}
			
            $sql="insert into tbl_session (ses_id,ses_en) values ('".$loggedsessionid."','".time()."')";
            $db->mysqlquery($sql);
	  }
  }
	
  public function page_brdcmp_array($brdcmp_array, $fontcls = "lastlink", $linkcls = "alllink"){
	  $array_ln = count($brdcmp_array);
	  $trail_string = "";
	  if ($fontcls == ""){ $fontcls = "lastlink"; }
	  
	  for ($i=0;$i<$array_ln;$i++){
		$title = $brdcmp_array[$i]["a_title"];
		$link = $brdcmp_array[$i]["a_link"];
		
		if ($link == ""){
		 $trail_string .= '<font class="text_nocol '.$fontcls.'">'.$title.'</font>';
		}else{
		 $trail_string .= '<a class="text_nocol '.$linkcls.'" href="'.$link.'">'.$title.'<a/>';
		}
		
		if ($i < $array_ln - 1){
		  $trail_string .= ' &gt;&gt; ';
		}
		
	  }
	  $trail_string = '' . $trail_string;
	  return $trail_string; 
  }
	
  public function pagination($small_range, $p, $start_no ,$end_no, $total_page ,$q, $stlcss, $gotopagenm, $extraqry){
        $returntxt = '';

        //previous link
        if ($p > 1){
            $pii = $p - 1;
            $pxno = $pii % $small_range;
            $pxnoint = $pii / $small_range;
            if ($pxnoint >=1){
                $st_p = $pii - $pxno;
                $qq = $st_p - 1;
            }else{
                $qq = 1;
            }
            $returntxt .= '<a href="'. $gotopagenm .'?p='. $pii .'&q='. $qq . $extraqry .'" class="'. $stlcss .'"><img border="0" src="images/prevarrow.gif" alt="" align="absmiddle" /></a>&nbsp;|&nbsp;';
        }else{
            $returntxt .= '<img border="0" src="images/prevarrow1.gif" alt="" align="absmiddle" />&nbsp;|&nbsp;';
        }

        //page number
        if ($total_page > 1){
            for ($ii = $start_no; $ii <= $end_no; $ii++){
                $xno = $ii % $small_range;
                $xnoint = $ii / $small_range;
                if ($xnoint >=1){
                    $st_p = $ii - $xno;
                    $qq = $st_p - 1;
                }else{
                    $qq = 1;
                }
                if ($p == $ii){ $stlcssd = "fcolor2"; }else{ $stlcssd = $stlcss; }
                $returntxt .= '<a href="'. $gotopagenm .'?p='. $ii .'&q='. $qq . $extraqry .'" class="'. $stlcssd .'">'. $ii .'</a>';
                if ($ii != $end_no){
                    $returntxt .= '&nbsp;|&nbsp;';
                }
            }
        }

        //next link
        if ($p < $total_page){
            $pii = $p + 1;
            $pxno = $pii % $small_range;
            $pxnoint = $pii / $small_range;
            if ($pxnoint >=1){
                $st_p = $pii - $pxno;
                $qq = $st_p - 1;
            }else{
                $qq = 1;
            }
            $returntxt .= '&nbsp;|&nbsp;<a href="'. $gotopagenm .'?p='. $pii .'&q='. $qq . $extraqry .'" class="'. $stlcss .'"><img border="0" src="images/nextarrow.gif" alt="" align="absmiddle" /></a>';
        }else{
            $returntxt .= '&nbsp;|&nbsp;<img border="0" src="images/nextarrow1.gif" alt="" align="absmiddle" />';
        }
        return $returntxt;
  }
  
  public function pagination_main($foundm, $dcon, $small_range, $range_span, $p, $q, $gotopagenm, $extraqry){
      $returntxt = '';
      $total_page = ceil($foundm / $dcon);
      if ($p > 5){
		  $end_no = $p + 4;
		  $start_no = $p - 4;
	  }else{
		  $start_no = 1;
		  $end_no = $start_no + $range_span;
	  }
		
	  if ($end_no > $total_page){
          $end_no = $total_page;
      }
	  $start_pointer = (($p - 1) * $dcon) + 1;
	  $end_pointer = $start_pointer + $dcon - 1;
	  if ($end_pointer > $foundm){
          $end_pointer = $foundm;
      }

      $returntxt .= '
            <table border="0" cellspacing="0" cellpadding="0" width="95%">
                <tr>
                    <td align="left">
                        <div class="main_con">
      ';

      if ($foundm > $dcon){
          $stlcss = "fcolor1";
          $returntxt .= $this->pagination($small_range, $p, $start_no, $end_no, $total_page, $q, $stlcss, $gotopagenm, $extraqry);
      }

      $returntxt .= '   </div>

                    </td>
                    <td align="right">
                        <div class="main_con">
                            <div align="right">
      ';
      if ($foundm > 0){
                    $returntxt .= 'Total: <strong>'. $foundm .'</strong>';
      }
      $returntxt .= '       </div>
                        </div>
                    </td>
                </tr>
            </table>
      ';
      echo $returntxt;
  }
  
  public function atoz_search($caption){
	  $ret_string = '';
	  for ($chtr = 65; $chtr <= 90; $chtr++){
		 $current_letter = chr($chtr);
		 $ret_string .= '<a href="'. $_SERVER["PHP_SELF"] .'?key='. strtolower($current_letter) .'" class="htext fontbold">'. $current_letter .'</a> | '; 
	  }
	  
	  $ret_string = rtrim($ret_string, " | ");
	  $ret_string = '<span class="fontcolor3">Search By '. $caption .':</span> <a href="'. $_SERVER["PHP_SELF"] .'" class="htext fontbold">ALL</a> | ' . $ret_string;
	  return $ret_string;
  }
  
  public function change_rank($rank, $oldrank, $tablenm, $wherecls){  
  global $db;
  if ($oldrank != $rank){      
	  if ($oldrank > 0){
		if ($oldrank > $rank){
			$sql = "select id,rank from ". $tablenm ." where rank >='".$rank."' and  rank <'".$oldrank."' and";
		}else{
			$sql = "select id,rank from ". $tablenm ." where rank ='".$rank."' and";
		}	
	  }else{
			$sql="select id,rank from ". $tablenm ." where rank >='".$rank."' and";
	  }
	  
	  $sql.=$wherecls;  
	  $result = $db->fetch_all_array($sql);
      //$found = $db->num_rows($sql);
	  $found = count($result);	
	  for($k = 0; $k < $found; $k++){
		$updt_id = $result[$k]['id'];
		$updt_rank = $result[$k]['rank'];
		if ($oldrank > 0){		
			if ($oldrank > $rank){
			  $updt_rank = $updt_rank + 1 ;
			}else{
			  $updt_rank = $oldrank ;
			}  
		}else{
		   $updt_rank = $updt_rank + 1 ;
		}	
		
		$sql0 = "update ". $tablenm ." set rank='".$updt_rank."' where id='".$updt_id."'";
		$db->mysqlquery($sql0);
	  } 
	} 
  }
  
  public function admin_rank_module($id, $imagerank, $tblnm, $found, $extra_sql = ""){
	    global $db;
		$maxrnk = $db->total_record_count("select max(rank) as ttl from ". $tblnm . $extra_sql ."");
		$imgprint = "";
		$rk = $imagerank;
		
		if ($found > 1){
		
			if ($rk == $maxrnk){
				$upid = $rk-1;
				$crid = $rk;
				$imgprint = "<a href='posi_chn.php?tbl=". $tblnm ."&id=" . $id . "&crid=" . $crid . "&newid=" . $upid . "&p=a'><img border='0' src='../images/u.gif' align='absmiddle'></a>";
			}else{
			
			   if ($rk == 1){
				   $dnid = $rk + 1;
				   $crid = $rk;
				   $imgprint = "<a href='posi_chn.php?tbl=". $tblnm ."&id=" . $id . "&crid=" . $crid . "&newid=" . $dnid . "&p=a'><img border='0' src='../images/d.gif' align='absmiddle'></a>";
			   }else{
				   $upid = $rk - 1;
				   $dnid = $rk + 1;
				   $crid = $rk;
				   $imgprint = "<a href='posi_chn.php?tbl=". $tblnm ."&id=" . $id . "&crid=" . $crid . "&newid=" . $upid . "&p=a'><img border='0' src='../images/u.gif' align='absmiddle'></a>&nbsp;<a href='posi_chn.php?tbl=". $tblnm ."&id=" . $id . "&crid=" . $crid . "&newid=" . $dnid ."&p=a'><img border='0' src='../images/d.gif' align='absmiddle'></a>";
			   }
			
			}
		}
	   return $imgprint;
  }
	
  public function delete_record($id, $delpointer){
		$delete_ret_string = '<a onclick="javascript:return confirm(\'Are you sure you want to delete this Record?\');" href="cpdel.php?del_id='. $id .'&t='. $delpointer .'" title="Delete Record"><img alt="Delete Record" src="images/del.png"  class="imgcommon" /></a>';
		return $delete_ret_string;
  }

  public function prevent_delete_record($delpointer){
        $delete_ret_string = '<a href="javascript:void(0);" onclick="javascript:alert(\'You can not delete this '. $delpointer .'\')" title="Required '. $delpointer .'"><img alt="Required '. $delpointer .'" src="images/del_g.png" class="imgcommon" /></a>';
        return $delete_ret_string;
  }

  //------------------
		
  public function get_pagetype_combo($page_type = 0){
		global $db;
		$vsql = "select * from tbl_page_type where combo_status = 'y' order by id";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
		  $c_id = $vrow['id'];
		  $cname = $vrow['name'];
	?>
    <option value="<?php echo $c_id;?>"<?php if ($page_type == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
    <?php	  
		}
  }
  
  public function get_page_combo($int_page_sel = '', $returnoption = 0){
		global $db, $cm;
		$returntext = '';
		$vsql = "select id, parent_id, name from tbl_page where status = 'y' and page_type = 1 order by name";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
		  $c_id = $vrow['id']; 
		  $parent_id = $vrow['parent_id']; 
		  $cname = $vrow['name'];
		  $c_id = $c_id . "/!b";
		  
		  $top_menu_name = '';
		  if ($parent_id > 0){
			  $top_menu_name = ' - ' . $cm->get_common_field_name("tbl_page", "name", $parent_id);
		  }
		  
		  $vck = '';
		  if ($int_page_sel == $c_id){ $vck = ' selected="selected"'; }		  
		  $returntext .= '
		  <option value="'. $c_id .'"'. $vck .'>'. $cname . $top_menu_name .'</option>
		  '; 
		}
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
  }
  
  public function get_page_combo_direct($int_page_id = 0){
		global $db, $cm;
		$returntext = '';
		$vsql = "select id, parent_id, name from tbl_page where status = 'y' and page_type = 1 order by parent_id, name";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
		  $c_id = $vrow['id']; 
		  $parent_id = $vrow['parent_id']; 
		  $cname = $vrow['name'];
		  
		  $top_menu_name = '';
		  if ($parent_id > 0){
			  //$top_parentpage_category = $cm->collect_top_parentpage_category($c_id);
			  $top_menu_name = ' - ' . $cm->get_common_field_name("tbl_page", "name", $parent_id);
		  }
		  
		  $vck = '';
		  if ($int_page_id == $c_id){ $vck = ' selected="selected"'; }		  
		  $returntext .= '
		  <option value="'. $c_id .'"'. $vck .'>'. $cname . $top_menu_name .'</option>
		  '; 
		}

		return $returntext;
  }
	
	public function get_yc_model_combo($int_page_sel = ''){
		global $db, $cm, $ymclass;
		$returntext = '';
		
		$makear = $ymclass->get_assign_manufacturer_list_raw();
		$makear = json_decode($makear);
		
		foreach($makear as $vrow){
			$make_id = $vrow->id;
			$make_name = $vrow->name;
			$modelar = json_decode($ymclass->get_manufacturer_model_list_link($make_id, 0));
			
			foreach($modelar as $model_row){
				$model_id = $model_row->id;
				$model_name = $model_row->name;
				$model_id = $model_id . "_" . $make_id . "/!e";
				
				$vck = '';
				if ($int_page_sel == $model_id){ $vck = ' selected="selected"'; }	
				
				$returntext .= '
				<option value="'. $model_id .'"'. $vck .'>'. $model_name . ' - ' . $make_name .'</option>
				';
			}
		}
		
		return $returntext;		
  	}
	
	public function get_local_boat_combo($int_page_sel = ''){
		global $db, $cm, $yachtclass;
		$returntxt = '';		
		$query_sql = "select a.id, a.vessel_name,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
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
        foreach($result as $row){
            $id = $row['id'];
            $vessel_name = $row['vessel_name'];			
			$yacht_title = $yachtclass->yacht_name($id);
			/*if ($vessel_name != ""){
				$yacht_title .= " - " . $yacht_title;
			}*/
			
			$boat_id = $id . "/!f";
			
			$bck = '';
			if ($int_page_sel == $boat_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $boat_id .'"'. $bck .'>'. $yacht_title .'</option>';
        }
		
		return $returntxt;		
	}
  
  public function get_section_combo($int_page_sel = '', $returnoption = 0){
		global $db;
		$returntext = '';
		$vsql = "select id, name from tbl_yacht_search_link where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
		  $c_id = $vrow['id'];  
		  $cname = $vrow['name'];
		  $c_id = $c_id . "/!c";
		  
		  $vck = '';
		  if ($int_page_sel == $c_id){ $vck = ' selected="selected"'; }		  
		  $returntext .= '
		  <option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>
		  ';  
		}
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
  }
  
  public function get_resource_type_combo($int_page_sel = '', $returnoption = 0){
		global $db;
		$returntext = '';
		$vsql = "select id, name from tbl_resource_type where status_id = 1 order by name";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
		  $c_id = $vrow['id'];  
		  $cname = $vrow['name'];
		  $c_id = $c_id . "/!d";
		  
		  $vck = '';
		  if ($int_page_sel == $c_id){ $vck = ' selected="selected"'; }		  
		  $returntext .= '
		  <option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>
		  '; 
		}
		
		if ($returnoption == 1){
			return $returntext;
		}else{
			echo $returntext;
		}
  }
	
  public function get_displayon_option($disp_on, $page_type){
		global $db;
		$display_en_dis = '';
		$vsql = "select id, name from tbl_link_display where status = 'y' order by rank";
		$vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
			$v_id = $vrow['id'];
			$v_name = $vrow['name'];
			if ($page_type == 5 AND $v_id > 1){ $display_en_dis = ' disabled="disabled"'; }
		?>
        <div class="opt_holder_in"><input class="disp_on_cb" type="radio" name="disp_on" value="<?php echo $v_id; ?>" <?php if ($disp_on == $v_id){ echo "checked"; } ?><?php echo $display_en_dis; ?> />&nbsp;<?php echo $v_name; ?></div>
        <?php	
		}
  }

  public function get_commonstatus_combo($status_id = 0){
        global $db;
        $vsql = "select id, name from tbl_common_status where status = 'y' order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
        ?>
            <option value="<?php echo $c_id;?>"<?php if ($status_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
  }

  public function get_leftbox_combo($column_id = 0){
        global $db;
        $vsql = "select id, name from tbl_left_box_content order by name";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
        ?>
            <option value="<?php echo $c_id;?>"<?php if ($column_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
  }
  
  public function get_slider_category_combo($category_id = 0){
        global $db;
		$returntext = '';
        $sql = "select id, name from tbl_slider_category where status_id = 1 order by name";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$vck = '';
			if ($category_id == $c_id){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>';
        }
		return $returntext;
	}
	
	public function get_modulestatus_combo($status_id = 0){
        global $db;
		$returntext = '';
        $sql = "select id, name from tbl_module_status where status = 'y' order by id";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $c_id = $row['id'];
            $cname = $row['name'];
			
			$vck = '';
			if ($status_id == $c_id){ $vck = ' selected="selected"'; }
			$returntext .= '<option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>';
        }
		return $returntext;
	}
	
  public function get_sort_product_description($pdes){
	 $pdes = strip_tags($pdes);
	 $pdes = substr($pdes, 0, 100) . "...";
	 return $pdes;
  }
  
  //settings category
	public function get_settings_category_tab($category_id){
		global $db;
		$returntext = '';
		$sql = "select id, name from tbl_syscategory where status = 'y' order by rank";
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
				
				$returntext .= '<li><a'. $activeclass .' href="mod-sysvar.php?categoryid='. $c_id .'">'. $cname .'</a></li>';
			}
			$returntext .= '</ul>';
		}		
		return $returntext;
	}
	
	//featured yacht category
	public function get_featured_yacht_category_tab($category_id){
		global $db;
		
		$activeclass1 = $activeclass2 = $activeclass3 = '';
		if ($category_id == 1){
			$activeclass1 = ' class="active"';
		}elseif ($category_id == 2){
			$activeclass2 = ' class="active"';
		}elseif ($category_id == 3){
			$activeclass3 = ' class="active"';
		}
		
		$returntext = '
		<ul class="syscategory">
			<li><a'. $activeclass3 .' href="featured_yacht.php?categoryid=3">Home</a></li>
			<li><a'. $activeclass1 .' href="featured_yacht.php?categoryid=1">Yacht</a></li>
			<li><a'. $activeclass2 .' href="featured_yacht.php?categoryid=2">Catamaran</a></li>
		</ul>
		';
		
		return $returntext;
	}
  
  //sub admin
  public function get_subadmin_account_combo($status_id = 0){
        global $db;
        $vsql = "select id, name from tbl_subadmin_account_status where status = 'y' and main_ac_status = 'y' order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $vid = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $vid; ?>"<?php if ($status_id == $vid){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
  }
  
  public function sub_admin_insert_update(){
	  global $db, $cm, $edclass, $yachtclass;
	  
	  $d_username = $_POST["d_username"];
      $d_email = $_POST["d_email"];
      $d_password = $_POST["d_password"];
	  
	  $name = $_POST["name"];
	  $phone = $_POST["phone"];
	  $status_id = round($_POST["status_id"], 0);
      $old_status_id = round($_POST["old_status_id"], 0);
	  
	  $ms = round($_POST["ms"], 0);
	  $old_d_username = $_POST["old_d_username"];
	  $old_d_email = $_POST["old_d_email"];
	  
	  if ($ms > 0){
		  $red_pg = "add_sub_admin.php?id=" . $ms;
	  }else{
		  $red_pg = "add_sub_admin.php";
	  }
	  
	  if ($ms == 0){
		  $sql = "insert into tbl_sub_admin (uid) values ('". $cm->filtertext($d_username) ."')";
          $iiid = $db->mysqlquery_ret($sql);
	  }else{
		  $sql = "update tbl_sub_admin set uid = '". $cm->filtertext($d_username) ."' where id = '". $ms ."'";
          $db->mysqlquery($sql);
          $iiid = $ms;
	  }
	  
	  // common update
        $sql = "update tbl_sub_admin set email = '". $cm->filtertext($d_email) ."'
        , pwd = '". $cm->filtertext($edclass->txt_encode($d_password)) ."'
		, name = '". $cm->filtertext($name) ."'
        , phone = '". $cm->filtertext($phone) ."'        
        , status_id = '". $status_id ."' where id = '". $iiid ."'";
        $db->mysqlquery($sql);
      // end
	  
	  if ($old_status_id != $status_id){			
		  $yachtclass->send_user_email($iiid, $status_id, 1);			
	  }
  }
  
  //blog
  public function get_blog_category_combo($category_id = 0){
        global $db;
        $vsql = "select id, name from tbl_blog_category where status_id = 1 order by id";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
        ?>
            <option value="<?php echo $c_id;?>"<?php if ($category_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
  }
  
  //update testimonial date
  public function update_testimonial_date($updateid, $reg_date){
	  global $db, $cm;
	  $reg_date_a = $cm->set_date_format($reg_date);
	  $sql = "update tbl_testimonial set reg_date = '". $cm->filtertext($reg_date_a) ."' where id = '". $updateid ."'";
	  $db->mysqlquery($sql);	  
  }
  
  //update blog date
  public function update_blog_date($updateid, $reg_date){
	  global $db, $cm;
	  $reg_date_a = $cm->set_date_format($reg_date);
	  $sql = "update tbl_blog set reg_date = '". $cm->filtertext($reg_date_a) ."' where id = '". $updateid ."'";
	  $db->mysqlquery($sql);	  
  }
  
  //update event date
  public function update_event_date($updateid, $reg_date){
	  global $db, $cm;
	  $reg_date_a = $cm->set_date_format($reg_date);
	  $sql = "update tbl_event set reg_date = '". $cm->filtertext($reg_date_a) ."' where id = '". $updateid ."'";
	  $db->mysqlquery($sql);	  
  }
  
  //location rank - for our team page
  public function update_location_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $sql = "update tbl_location_office set rank = '". $i ."' where id = '". $value ."'";
           $db->mysqlquery($sql);
		   $i++;			
	   }
   }
   
  //location state rank - for our team page
  public function update_location_state_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $value_ar = explode("!#!", $value);
		   $state_id = round($value_ar[0]);
		   $state = $value_ar[1];
		   
		   if ($state_id > 0){
			   $sql = "update tbl_location_office set state_rank = '". $i ."' where state_id = '". $state_id ."'";
			   $db->mysqlquery($sql);
		   }
		   
		   if ($state != ""){
		   		$sql = "update tbl_location_office set state_rank = '". $i ."' where state = '". $cm->filtertext($state) ."'";
				$db->mysqlquery($sql);
		   }           
		   $i++;			
	   }
  } 
    
  //user rank - for our team page
  public function update_user_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $sql = "update tbl_user set rank = '". $i ."' where id = '". $value ."'";
           $db->mysqlquery($sql);
		   $i++;			
	   }
   }
   
   //bespoke footer
  public function bespoke_footer_display_list_main($id){
	  global $db, $cm;
	  $returntext = '';
	  $sql = "select * from tbl_bespoke_footer_link where bespoke_id = '". $id ."' order by rank";
	  $result = $db->fetch_all_array($sql);
	  $found = count($result);
	  $rc_count = 1;
	  if ($found > 0){
		  foreach($result as $row){
			  $mn_id = $row['id'];
			  $bespoke_id = $row['bespoke_id'];
			  $name = $row['name'];
			  $link_type = $row['link_type'];
			  $page_url = htmlspecialchars($row['page_url']);
			  $int_page_id = $row['int_page_id'];
			  $int_page_tp = $row['int_page_tp'];
			  $link_rank = $row['rank'];
			  
			  $int_page_sel = $int_page_id . "/!" . $int_page_tp;
			  if ($link_type == 1){
				   $cms_style3_a = "visibility: visible;";
				   $cms_style3_b = "display: none; visibility: hidden;";
			  }elseif ($link_type == 2){
				   $cms_style3_b = "visibility: visible;";
				   $cms_style3_a = "display: none; visibility: hidden;";
			  }else{
				   $cms_style3_a = $cms_style3_b = "display: none; visibility: hidden;";
			  }
			  
			  $extlink = '';
			  $intlink = '';
			  if ($link_type == 1){ $extlink = ' selected="selected"'; }
			  if ($link_type == 2){ $intlink = ' selected="selected"'; }
			  
			  $returntext .= '
			  	<tr id="item-'. $mn_id .'" class="besrowind'. $rc_count .' addedbg">
					<td align="left" valign="top" width="15%" class="tdpadding1"><span class="fontcolor3">* </span>Link Caption:</td>
					<td align="left" valign="top" width="33%" class="tdpadding1"><input name="name'. $rc_count .'" id="name'. $rc_count .'" class="inputbox inputbox_size4" value="'. $name .'" type="text" /></td>
					
					<td width="10%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link:</td>
					<td width="34%" align="left" valign="top" class="tdpadding1">
					<select name="link_type'. $rc_count .'" id="link_type'. $rc_count .'" class="link_type htext combobox_size6" cnum="'. $rc_count .'">					
						<option value="1"'. $extlink .'>External Link</option>
						<option value="2"'. $intlink .'>Internal Link</option>
					</select>
					<p>
					<span id="cmsoption3_a'. $rc_count .'" style=" display: none; visibility: hidden;"><span class="fontcolor3">* </span>Specify URL:<br/><input type="text" id="page_url'. $rc_count .'" name="page_url'. $rc_count .'" class="inputbox inputbox_size4" value="'. $page_url .'" /></span>
					<span id="cmsoption3_b'. $rc_count .'" style="">
						<span class="fontcolor3">* </span>Select Page:<br/>
						<select name="int_page_sel'. $rc_count .'" id="int_page_sel'. $rc_count .'" class="htext combobox_size6">
							<optgroup label="Pages">
							'. $this->get_page_combo($int_page_sel, 1) .'						
							</optgroup>
							
							<optgroup label="Listing Search">    
							'. $this->get_section_combo($int_page_sel, 1) .'
							</optgroup>    
						</select>
					</span>	
					</p>
					</td>					
					<td width="8%" align="left" valign="top" class="tdpadding1"><a class="bes_del" isdb="1" yval="'. $rc_count .'" besid="'. $mn_id .'" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="images/del.png" /></a></td>
				</tr>
			  ';
			  $rc_count++;			  
		  }
	  }//found
	  
	  $returnval = array(
		'displaytext' => $returntext,
		'totalrecord' => $rc_count
	  );
	  return $returnval;
  }
  
  public function bespoke_footer_display_list_add($counter){
	  $delbutton = '&nbsp;&nbsp;';
	  if ($counter > 1){
		  $delbutton = '<a class="bes_del" isdb="0" yval="'. $counter .'" besid="" href="javascript:void(0);" title="Delete Record"><img alt="Delete Record" title="Delete Record" src="images/del.png" /></a>';
	  }
	  
	  $returntext = '
	    <tr class="besrowind'. $counter .'">
			<td width="15%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Link Caption:</td>
			<td width="33%" align="left" valign="top" width="" class="tdpadding1"><input name="name'. $counter .'" id="name'. $counter .'" class="inputbox inputbox_size4" value="" type="text" /></td>
			
			<td width="10%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link:</td>
			<td width="34%" align="left" valign="top" class="tdpadding1">
			<select name="link_type'. $counter .'" id="link_type'. $counter .'" class="link_type htext combobox_size6" cnum="'. $counter .'">					
				<option value="1">External Link</option>
				<option value="2" selected="selected">Internal Link</option>
			</select>
			<p>
			<span id="cmsoption3_a'. $counter .'" style=" display: none; visibility: hidden;"><span class="fontcolor3">* </span>Specify URL:<br/><input type="text" id="page_url'. $counter .'" name="page_url'. $counter .'" class="inputbox inputbox_size4_b" value="" /></span>
			<span id="cmsoption3_b'. $counter .'" style="">
				<span class="fontcolor3">* </span>Select Page:<br/>
				<select name="int_page_sel'. $counter .'" id="int_page_sel'. $counter .'" class="htext combobox_size6">
					<optgroup label="Pages">
					'. $this->get_page_combo(0, 1) .'						
					</optgroup>
					
					<optgroup label="Listing Search">    
					'. $this->get_section_combo(0, 1) .'
					</optgroup>   
				</select>
			</span>
			</p>	
			</td>
			
			<td width="8%" align="left" valign="top" class="tdpadding1">'. $delbutton .'</td>
		</tr>
	  ';	  
	  return $returntext;
  }
  
  public function bespoke_footer_display_list($id){
	  $returntext = '';
	  $bespoke_footer_details = $this->bespoke_footer_display_list_main($id);
	  $total_bespoke_footer = $bespoke_footer_details['totalrecord'];
	  	  
	  $returntext .= '
		<table id="besrowholder" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
		<tbody>
		'. $bespoke_footer_details['displaytext'] .'';
		
		if ($total_bespoke_footer == 1){
			$returntext .= $this->bespoke_footer_display_list_add(1);
		}
		
	$returntext .= '
		</tbody>
		</table>
	';
	
	$returntext .= '
	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
		<tr>
			<td width="" align="left" valign="top" class="tdpadding1"><button type="button" class="besaddrow butta"><span class="addIcon butta-space">Add New</span></button></td>
		</tr>
	</table>
	<input type="hidden" value="'. $total_bespoke_footer .'" id="total_bespoke_footer" name="total_bespoke_footer" />
	';
	
	return $returntext;	  
  }
  
  public function bespoke_footer_assign($bespoke_id){
		global $db, $cm;
		$total_bespoke_footer = round($_POST["total_bespoke_footer"], 0);		
		$sql = "delete from tbl_bespoke_footer_link where bespoke_id = '". $bespoke_id ."'";
        $db->mysqlquery($sql);
		
		$bes_link_rank = 1;
		for ($i = 1; $i <= $total_bespoke_footer; $i++){
			$name = $_POST["name" . $i];
			$link_type = $_POST["link_type" . $i];
			$int_page_sel = $_POST["int_page_sel" . $i];
			$page_url = $_POST["page_url" . $i];
			$new_window = "n";
			$bespoke_check = 0;
			if ($link_type == 2){ 
				 $int_page_sel_ar = explode("/!", $int_page_sel);
				 $int_page_id = $int_page_sel_ar[0];
				 $int_page_tp = $int_page_sel_ar[1];
				 $page_url = "";
				 
				 if ($name != "" AND $int_page_id > 0){
					 $bespoke_check = 1;
				 }
				 
			}else{
				 $int_page_id = 0;
				 $int_page_tp = "";
				 
				 if ($name != "" AND $page_url != ""){
					 $bespoke_check = 1;
				 }
			}
			
			if ($bespoke_check == 1){
				$page_url = $cm->format_url_txt($page_url);
				$id = $cm->campaignid(15) . time();
				$sql = "insert into tbl_bespoke_footer_link (id
															, bespoke_id
															, name
															, link_type
															, page_url
															, int_page_id
															, int_page_tp
															, new_window
															, rank) values 
															('". $cm->filtertext($id) ."'
															, '". $bespoke_id ."'
															, '". $cm->filtertext($name) ."'
															, '". $link_type ."'
															, '". $cm->filtertext($page_url) ."'
															, '". $int_page_id ."'
															, '". $int_page_tp ."'
															, '". $new_window ."'
															, '". $bes_link_rank ."')";
                $db->mysqlquery($sql);
				$bes_link_rank++;
			}
		}
	}
	
	public function bespoke_footer_delete($id){
		global $db, $cm;
		$sql = "delete from tbl_bespoke_footer_link where id = '". $cm->filtertext($id) ."'";
        $db->mysqlquery($sql);
	}
	
	public function bespoke_footer_sort($section){
		global $db;
		parse_str($_POST['data'], $recOrder);
		$i = 1;
		foreach ($recOrder['item'] as $value) {
			$sql = "update tbl_bespoke_footer_link set rank = '". $i ."' where id = '". $value ."' and bespoke_id = '". $section ."'";
        	$db->mysqlquery($sql);
			$i++;			
		}
	}
	
	/*---------- Boat Model Group View Sort for Boat ----------*/
	
	//group model list
	public function group_boat_list($opt, $manufacturer_id, $group_id){
		global $db, $cm, $yachtclass;
		$returntext = '';
		$dragdropclass = '';
		
		if ($opt == 2){
			//assigned list
			$dragdropclass = 'drp';
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
			
			$query_form .= " tbl_boat_model_group_assign as b,";
			
			//$query_where .= " a.manufacturer_id = '". $manufacturer_id ."' and";
			$query_where .= " a.id = b.boat_id and b.group_id = '". $group_id ."' and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by b.rank";
		}else{
			//available list
			$dragdropclass = 'drg';
			$query_sql = "select *,";
			$query_form = " from tbl_yacht as a";
			$query_where = " where";
			
			//$query_where .= " a.manufacturer_id = '". $manufacturer_id ."' and";
			
			$query_form .= " LEFT JOIN tbl_boat_model_group_assign as b ON a.id = b.boat_id";
            $query_where .= ' b.boat_id IS NULL and';
            //$query_where .= ' a.status_id = 1 and';
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by a.year";
		}
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		foreach($result as $row){
			$boatid = $row["id"];
			$listing_no = $row["listing_no"];
            $yacht_title = $yachtclass->yacht_name($boatid);
            $imgpath = $yachtclass->get_yacht_first_image($boatid);
            $imagefolder = 'yachtimage/' . $listing_no . '/';
			
			$imgpath_d = '<img src="../'. $imagefolder . $imgpath .'" border="0" />';
			$update_data = $manufacturer_id . '!#!' . $boatid . '!#!' . $group_id;
			
			 $returntext .= '
			 	<div id="item-'. $update_data .'" boat_id="'. $boatid .'" manufacturer_id="'. $manufacturer_id .'" group_id="'. $group_id .'" class="'. $dragdropclass .' divrow">
					<div class="imgholder">'. $imgpath_d .'</div>
					<div class="ytitle">'. $yacht_title .'</div>
					<div class="clearfix"></div>
				</div>
			 ';
		}
		
		return $returntext;
	}
	//end
	
	//boat group list
	public function display_boat_model_group($manufacturer_id = 0){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_boat_model_group order by rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$rc_count = 1;
			$returntext .= '
			<ul id="group_sortable" class="manufacturegroup" manufacturerid="'. $manufacturer_id .'">
			';
			
			foreach($result as $row){
				$group_id = $row["id"];
				$group_name = $row["name"];
								
				$returntext .= '
				<li id="item-'. $group_id .'" class="manufacturegroup_ind'. $rc_count .'">
					<div class="left-col">
						<div class="normal_mode'. $rc_count .'">							
							<a class="group_edit viewmode" rowval="'. $rc_count .'" group_id="'. $group_id .'" manufacturer_id="'. $manufacturer_id .'" href="javascript:void(0);" title="Edit Group"><img alt="Edit Group" title="Edit Group" src="images/edit.png" /></a>
							<a class="group_del viewmode" rowval="'. $rc_count .'" group_id="'. $group_id .'" manufacturer_id="'. $manufacturer_id .'" href="javascript:void(0);" title="Delete Group"><img alt="Delete Group" title="Delete Group" src="images/del.png" /></a>
							<span class="viewmodeoption groupname groupname'. $rc_count .'">'. $group_name .'</span>
							<span class="viewmodeoption groupshortcode groupshortcode'. $rc_count .'">[fcboatlistgroup groupid='. $group_id .']</span>
						</div>
						
						<div class="edit_mode'. $rc_count .' com_none">
						<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
							<tr>								
								<td width="70%" align="left"><input type="text" id="group_name'. $rc_count .'" name="group_name'. $rc_count .'" value="'. $group_name .'" class="inputbox inputbox_size4_e" /></td>
								<td width="30%" align="left">
								<a class="updategroup"  rowval="'. $rc_count .'" group_id="'. $group_id .'" manufacturer_id="'. $manufacturer_id .'" href="javascript:void(0);" title="Update"><img alt="Update" title="Update" src="images/correct.png" /></a>
								<a class="update_cancel" rowval="'. $rc_count .'" group_id="'. $group_id .'" manufacturer_id="'. $manufacturer_id .'" href="javascript:void(0);" title="Cancel"><img alt="Cancel" title="Cancel" src="images/del.png" /></a>
								</td>
							</tr>
						</table>
						</div>
					</div>
					<div class="right-col">
						<a class="groupopenclose open_group" rowval="'. $rc_count .'" manufacturerid="'. $manufacturer_id .'" groupid="'. $group_id .'" viewid="'. $view_id .'" href="javascript:void(0);" title="Expand Group">open</a>
					</div>
					<div class="clearfix"></div>
					
					<div class="assign_model_holder assign_model_holder'. $rc_count .'">
						<div class="boxleft box_border">
							<div class="box_heading">Available Model List</div>
							<div class="box_div app_box1 app_box1_'. $rc_count .'" rowval="'. $rc_count .'"></div>
						</div>
						
						<div class="boxright box_border">
							<div class="box_heading">Assign Model List</div>
							<div class="box_div app_box2 app_box2_'. $rc_count .'" rowval="'. $rc_count .'"></div>
						</div>
						<div class="clearfix"></div>
					</div>
					
				</li>
				';
				$rc_count++;
			}
			
			$returntext .= '
			</ul>			
			';
		}else{
			$returntext = 'Group(s) yet to create.';
		}
		
		return $returntext;
	}
	//end
	
	//manage Group
	public function manage_boat_model_group($manufacturer_id, $group_name, $group_id = 0){
		global $db, $cm;
		
		if ($group_id > 0){
			//update
			$sql = "update tbl_boat_model_group set name = '". $cm->filtertext($group_name) ."' where id = '". $group_id ."'";
			$db->mysqlquery($sql);
		}else{
			//insert
			$rank = $db->total_record_count("select max(rank) as ttl from tbl_boat_model_group") + 1;
			$sql = "insert into tbl_boat_model_group (name, status_id, rank) values ('". $cm->filtertext($group_name) ."', 1, '". $cm->filtertext($rank) ."')";
			$group_id = $db->mysqlquery_ret($sql);
		}
		
		//display group
		$returntext = $this->display_boat_model_group($manufacturer_id);
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//group sort
	public function boat_model_group_sort($manufacturer_id = 0){
		global $db, $cm;
		parse_str($_POST['data'], $recOrder);
		$i = 1;
		foreach ($recOrder['item'] as $value) {
			$sql = "update tbl_boat_model_group set rank = '". $i ."' where id = '". $value ."'";
        	$db->mysqlquery($sql);
			$i++;			
		}
	}
	//end
	
	//group delete
	public function boat_model_group_delete($manufacturer_id, $group_id){
		global $db, $cm;
		
		$sql = "delete from tbl_boat_model_group where id = '". $group_id ."'";
    	$db->mysqlquery($sql);
		
		$sql = "delete from tbl_boat_model_group_assign where group_id = '". $group_id ."'";
    	$db->mysqlquery($sql);
		
		//display group
		$returntext = $this->display_boat_model_group($manufacturer_id);
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	//end
	
	//boat assign to group
	public function boat_add_group($manufacturer_id, $boat_id, $group_id){
        global $db, $cm;		
		$sql = "insert into tbl_boat_model_group_assign (boat_id, group_id, rank) values ('". $boat_id ."', '". $group_id ."', 1)";
		$db->mysqlquery($sql);
    }
	
	public function group_boat_list_ajax_call($displayopt, $manufacturer_id, $group_id){
        $mlistnormal = $this->group_boat_list(1, $manufacturer_id, $group_id);
        $mlistassign = $this->group_boat_list(2, $manufacturer_id, $group_id);

        $returnval[] = array(
            'displayopt' => $displayopt,
            'mlistnormal' => $mlistnormal,
            'mlistassign' => $mlistassign
        );
        return json_encode($returnval);
    }
	//end
	
	//model assign sort	
	public function update_group_boat_list_rank(){
	   global $db, $cm;
	   parse_str($_POST['data'], $recOrder);
	   $i = 1;
	   foreach ($recOrder['item'] as $value) {
		   $value_ar = explode("!#!", $value);
		   $manufacturer_id = round($value_ar[0]);
		   $boat_id = round($value_ar[1]);
		   $group_id = round($value_ar[2]);
		   
		   $sql = "update tbl_boat_model_group_assign set rank = '". $i ."' where boat_id = '". $boat_id ."' and group_id = '". $group_id ."'";
		   $db->mysqlquery($sql);         
		   $i++;	
	   }
	}
	//end
	
	//model remove from group
	public function boat_remove_group($manufacturer_id, $boat_id, $group_id){
        global $db;
        $sql = "delete from tbl_boat_model_group_assign where boat_id = '". $boat_id ."' and group_id = '". $group_id ."'";
        $db->mysqlquery($sql);
    }
	
	//manufacturer sidebar
	public function update_manufacturer_sidebar(){
		global $db, $cm, $fle;
		
		$manufacturer_id = round($_POST["manufacturer_id"], 0);
		$catalog_link = $_POST["catalog_link"];
		$catalog_link = $cm->format_url_txt($catalog_link);
		
		$sql = "select count(*) as ttl from tbl_manufacturer_catalog where manufacturer_id = '". $manufacturer_id ."'";
		$iffound = round($db->total_record_count($sql));
		
		if ($iffound > 0){
			$sql = "update tbl_manufacturer_catalog set catalog_link = '". $cm->filtertext($catalog_link) ."' where manufacturer_id = '". $manufacturer_id ."'";
			$db->mysqlquery($sql);
		}else{
			$sql = "insert into tbl_manufacturer_catalog (manufacturer_id, catalog_link) values ('". $manufacturer_id ."', '". $cm->filtertext($catalog_link) ."')";
			$db->mysqlquery($sql);
		}
		
		//image upload
		$filename = $_FILES['imgpath']['name'] ;
		if ($filename != ""){
			$filename_tmp = $_FILES['imgpath']['tmp_name'];
			$filename = $fle->uploadfilename($filename);	
			$filename1 = $manufacturer_id."make".$filename;
			
			$target_path_main = "../manufacturerimage/sidebar/";
		
			//slider image
			$target_path = $target_path_main;
			$r_width = $cm->menu_im_width_s;
			$r_height = $cm->menu_im_height_s;
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));				
			
						
			$fle->filedelete($filename_tmp);
			$sql = "update tbl_manufacturer_catalog set imgpath = '".$cm->filtertext($filename1)."' where manufacturer_id = '". $manufacturer_id ."'";
			$db->mysqlquery($sql);
		}
		//end
		
		$returnar = array(
			"sliderid" => $manufacturer_id,
			"infotext" => "y"
		);
		return json_encode($returnar);	
		exit;
	}
	
	//get shortcode list
	public function get_shortcode_list(){
		global $db; 
		$returnar = array();	
		$sql = "select * from tbl_shortcode where status_id = 1 order by id";
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			 $id = $row['id'];
			 $name = $row['name'];
			 $shortcode = $row['shortcode'];
			 $returnar[] = array("name" => $name, "scode" => $shortcode);			 			 	
		}
		return json_encode($returnar);
	}
	
	//URL Re-write
	public function seo_url_settings(){
		global $db, $cm, $yachtchildclass;
		
		//$str = "Options +FollowSymlinks\n";

		$str = '
		<IfModule mod_expires.c>
		  ExpiresActive On
		  ExpiresDefault "access plus 1 seconds"
		  ExpiresByType text/html "access plus 1 seconds"
		  ExpiresByType image/gif "access plus 120 minutes"
		  ExpiresByType image/jpeg "access plus 120 minutes"
		  ExpiresByType image/png "access plus 120 minutes"
		  ExpiresByType text/css "access plus 60 minutes"
		  ExpiresByType text/javascript "access plus 60 minutes"
		  ExpiresByType application/x-javascript "access plus 60 minutes"
		  ExpiresByType text/xml "access plus 60 minutes"
		</IfModule>

		#Begin gzip and deflate
		<IfModule mod_deflate.c>
			SetOutputFilter DEFLATE
			SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|ico|png)$ \ no-gzip dont-vary
			SetEnvIfNoCase Request_URI \.(?:exe|t?gz|zip|bz2|sit|rar)$ \no-gzip dont-vary
			SetEnvIfNoCase Request_URI \.pdf$ no-gzip dont-vary

			BrowserMatch ^Mozilla/4 gzip-only-text/html
			BrowserMatch ^Mozilla/4\.0[678] no-gzip
			BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
		</IfModule>

		# 1 Month for most static assets
		<filesMatch ".(css|jpg|jpeg|png|gif|js|ico)$">
		Header set Cache-Control "max-age=2592000, public"
		</filesMatch>
		';

		$str.= "RewriteEngine On\n";
		$str.= "SetEnvIfNoCase User-Agent '^libwww-perl*' block_bad_bots\n";
		$str.= "Deny from env=block_bad_bots\n";
		
		//$str .= "RewriteCond %{HTTP_HOST} !^www..*\n";
		//$str .= "RewriteCond %{HTTP_HOST} !^$\n";
		//$str .= "RewriteCond %{HTTP_HOST} ^([^.]*).(com|com/)\n";
		//$str .= "RewriteRule ^.*$ http://www.%1.%2%{REQUEST_URI} [R=301,L]\n";
		$str.= "RewriteBase ". $cm->folder_for_seo ."\n\n";
		
		$str.= "RewriteCond %{HTTP_USER_AGENT} libwww-perl [OR]\n";
		$str.= "RewriteCond %{QUERY_STRING} tool25 [OR]\n";
		$str.= "RewriteCond %{QUERY_STRING} cmd.txt [OR]\n";
		$str.= "RewriteCond %{QUERY_STRING} cmd.gif [OR]\n";
		$str.= "RewriteCond %{QUERY_STRING} r57shell [OR]\n";
		$str.= "RewriteCond %{QUERY_STRING} c99 [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} almaden [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Anarchie [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^ASPSeek [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^attach [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^autoemailspider [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^BackWeb [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Bandit [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^BatchFTP [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^BlackWidow [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Bot\ mailto:craftbot@yahoo.com [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Buddy [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^bumblebee [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^CherryPicker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^ChinaClaw [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^CICC [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Collector [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Copier [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Crescent [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Custo [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^DA [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^DIIbot [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^DISCo [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^DISCo\ Pump [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Download\ Demon [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Download\ Wonder [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Downloader [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Drip [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^DSurf15a [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^eCatch [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EasyDL/2.99 [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EirGrabber [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} email [NC,OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EmailCollector [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EmailSiphon [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EmailWolf [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Express\ WebPictures [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^ExtractorPro [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^EyeNetIE [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^FileHound [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^FlashGet [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} FrontPage [NC,OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^GetRight [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^GetSmart [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^GetWeb! [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^gigabaz [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Go\!Zilla [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Go!Zilla [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Go-Ahead-Got-It [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^gotit [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Grabber [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^GrabNet [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Grafula [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^grub-client [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^HMView [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^HTTrack [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^httpdown [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} .*httrack.* [NC,OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^ia_archiver [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Image\ Stripper [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Image\ Sucker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Indy*Library [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} Indy\ Library [NC,OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^InterGET [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^InternetLinkagent [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Internet\ Ninja [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^InternetSeer.com [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Iria [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^JBH*agent [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^JetCar [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^JOC\ Web\ Spider [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^JustView [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^larbin [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^LeechFTP [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^LexiBot [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^lftp [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Link*Sleuth [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^likse [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Link [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^LinkWalker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mag-Net [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Magnet [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mass\ Downloader [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Memo [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Microsoft.URL [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^MIDown\ tool [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mirror [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mister\ PiX [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mozilla.*Indy [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mozilla.*NEWT [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Mozilla*MSIECrawler [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^MS\ FrontPage* [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^MSFrontPage [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^MSIECrawler [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^MSProxy [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Navroad [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NearSite [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NetAnts [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NetMechanic [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NetSpider [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Net\ Vampire [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NetZIP [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^NICErsPRO [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Ninja [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Octopus [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Offline\ Explorer [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Offline\ Navigator [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Openfind [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^PageGrabber [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Papa\ Foto [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^pavuk [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^pcBrowser [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Ping [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^PingALink [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Pockey [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^psbot [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Pump [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^QRVA [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^RealDownload [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Reaper [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Recorder [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^ReGet [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Scooter [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Seeker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Siphon [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^sitecheck.internetseer.com [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SiteSnagger [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SlySearch [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SmartDownload [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Snake [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SpaceBison [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^sproose [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Stripper [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Sucker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SuperBot [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^SuperHTTP [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Surfbot [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Szukacz [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^tAkeOut [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Teleport\ Pro [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^URLSpiderPro [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Vacuum [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^VoidEYE [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Web\ Image\ Collector [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Web\ Sucker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebAuto [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^[Ww]eb[Bb]andit [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^webcollage [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebCopier [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Web\ Downloader [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebEMailExtrac.* [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebFetch [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebGo\ IS [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebHook [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebLeacher [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebMiner [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebMirror [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebReaper [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebSauger [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Website [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Website\ eXtractor [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Website\ Quester [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Webster [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebStripper [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} WebWhacker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WebZIP [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Wget [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Whacker [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Widow [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^WWWOFFLE [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^x-Tractor [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Xaldon\ WebSpider [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Xenu [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Zeus.*Webster [OR]\n";
		$str.= "RewriteCond %{HTTP_USER_AGENT} ^Zeus\n\n";
		
		$str.= "RewriteRule ^.* - [F,L]\n";
		$str.= "RewriteCond %{HTTP_REFERER} ^". $cm->site_url ."$\n";
		$str.= "RewriteRule !^http://[^/.]\.". $cm->site_url_short .".* - [F,L]\n\n";
		
		$str.= "<Files ~ '^\.ht'>\n";
		$str.= "Order allow,deny\n";
		$str.= "Deny from all\n";
		$str.= "Satisfy All\n";
		$str.= "</Files>\n\n";
		
		$str.= "<FilesMatch '\.(inc|tpl|h|ihtml|sql|ini|conf|class|bin|spd|theme|module|exe)$'>\n";
		$str.= "deny from all\n";
		$str.= "</FilesMatch>\n\n";
		
		$str .= "RewriteRule ^make/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&mfslug=$1 [L]\n";
		$str .= "RewriteRule ^make/cobrokerage/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&mfslug=$1&owned=2 [L]\n";
		$str .= "RewriteRule ^make/ourlistings/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&mfslug=$1&owned=1 [L]\n";
		$str .= "RewriteRule ^model/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&modelname=$1 [L]\n";
		$str .= "RewriteRule ^year/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&yearvl=$1 [L]\n";
		$str .= "RewriteRule ^country/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&countryname=$1 [L]\n";
		$str .= "RewriteRule ^type/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&typename=$1 [L]\n";
		$str .= "RewriteRule ^type/cobrokerage/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&typename=$1&owned=2 [L]\n";
		$str .= "RewriteRule ^type/ourlistings/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&typename=$1&owned=1 [L]\n";
		$str .= "RewriteRule ^condition/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&conditionname=$1 [L]\n";
		$str .= "RewriteRule ^state/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&statename=$1 [L]\n";
		$str .= "RewriteRule ^search/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&keyterm=$1 [L]\n";
		$str .= "RewriteRule ^category/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&categorynm=$1 [L]\n";
		$str .= "RewriteRule ^make/([^/\.]+)/condition/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&mfslug=$1&conditionname=$2&owned=2&frm=1 [L]\n";
		$str .= "RewriteRule ^category/([^/\.]+)/make/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&categorynm=$1&mfslug=$2&owned=2&frm=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^brokerinventory/([^/\.]+)/embed/?$ index.php?templateseo=listboat&rawtemplate=1&brokercode=$1&nohead=1 [L]\n";
		
		$str .= "RewriteRule ^savesearch/([^/\.]+)/?$ index.php?templateseo=listboat&rawtemplate=1&searchno=$1 [L]\n";
		$str .= "RewriteRule ^boat/([^/\.]+)/?$ index.php?templateseo=yacht-details&lno=$1 [L]\n";
		$str .= "RewriteRule ^boat/([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/?$ index.php?templateseo=yacht-details&y=$1&mf=$2&md=$3&lnum=$4 [L]\n";
		$str .= "RewriteRule ^boat/([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/embed/?$ index.php?templateseo=yacht-details&y=$1&mf=$2&md=$3&lnum=$4&nohead=1 [L]\n";
		$str .= "RewriteRule ^boat/([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/slideshow/([^/\.]+)/?$ index.php?templateseo=yacht-details&y=$1&mf=$2&md=$3&lnum=$4&slideshowcode=$5&nohead=5 [L]\n";
		$str .= "RewriteRule ^boat/([^/\.]+)/([^/\.]+)/([^/\.]+)/([^/\.]+)/campaign/([^/\.]+)/?$ index.php?templateseo=yacht-details&y=$1&mf=$2&md=$3&lnum=$4&campaigncode=$5&nohead=5 [L]\n";
		
		$str .= "RewriteRule ^previewboat/([^/\.]+)/?$ index.php?templateseo=yacht-preview&lno=$1 [L]\n";
		$str .= "RewriteRule ^make-boat-live/([^/\.]+)/?$ index.php?templateseo=make-boat-live&lno=$1 [L]\n";
		$str .= "RewriteRule ^profile/([^/\.]+)/?$ index.php?templateseo=brokerprofile&unm=$1 [L]\n";
		$str .= "RewriteRule ^brokerprofile/([^/\.]+)/?$ index.php?templateseo=brokerprofile&unm=$1&insidedashboard=1 [L]\n";
		$str .= "RewriteRule ^soldboat/([^/\.]+)/?$ index.php?templateseo=soldboatbroker&unm=$1&boatstatus=3 [L]\n";
		$str .= "RewriteRule ^brokerboat/([^/\.]+)/?$ index.php?templateseo=brokerboatlist&brokerslug=$1 [L]\n";
		$str .= "RewriteRule ^companyprofile/([^/\.]+)/?$ index.php?templateseo=companyprofile&cnm=$1 [L]\n";
		$str .= "RewriteRule ^companyinventory/([^/\.]+)/?$ index.php?templateseo=companyinventory&cnm=$1 [L]\n";
		$str .= "RewriteRule ^companyinventory/([^/\.]+)/embed/?$ index.php?templateseo=listboat&rawtemplate=1&companycode=$1&nohead=1 [L]\n";
		$str .= "RewriteRule ^manufacturerprofile/([^/\.]+)/?$ index.php?templateseo=manufacturerprofile&cnm=$1 [L]\n";
		$str .= "RewriteRule ^model/([^/\.]+)/([^/\.]+)/([^/\.]+)/?$ index.php?templateseo=model-details&y=$1&mf=$2&md=$3 [L]\n";
		$str .= "RewriteRule ^locationprofile/([^/\.]+)/?$ index.php?templateseo=locationprofile&locnm=$1 [L]\n";
		
		$str .= "RewriteRule ^edit-boat/([^/\.]+)/?$ index.php?templateseo=add-yacht&lno=$1 [L]\n";
		$str .= "RewriteRule ^edit-brokerlist/([^/\.]+)/?$ index.php?templateseo=add-broker&id=$1 [L]\n";
		$str .= "RewriteRule ^edit-manager/([^/\.]+)/?$ index.php?templateseo=add-manager&id=$1 [L]\n";
		$str .= "RewriteRule ^edit-locationadmin/([^/\.]+)/?$ index.php?templateseo=add-locationadmin&id=$1 [L]\n";
		$str .= "RewriteRule ^edit-locationoffice/([^/\.]+)/?$ index.php?templateseo=add-office-location&id=$1 [L]\n";
		$str .= "RewriteRule ^boat-image/([^/\.]+)/?$ index.php?templateseo=boat-image&lno=$1 [L]\n";
		$str .= "RewriteRule ^boat-video/([^/\.]+)/?$ index.php?templateseo=boat-video&lno=$1 [L]\n";
		$str .= "RewriteRule ^boat-attachment/([^/\.]+)/?$ index.php?templateseo=boat-attachment&lno=$1 [L]\n";
		$str .= "RewriteRule ^announcement/([^/\.]+)/?$ index.php?templateseo=announcementdetails&slug=$1 [L]\n";
				
		$str .= "RewriteRule ^blog/([^/\.]+)/?$ index.php?templateseo=blogdetails&slug=$1 [L]\n";
		$str .= "RewriteRule ^blogcategory/([^/\.]+)/?$ index.php?templateseo=bloglist&slug=$1 [L]\n";
		$str .= "RewriteRule ^blogtag/([^/\.]+)/?$ index.php?templateseo=bloglist&tagslug=$1 [L]\n";
		$str .= "RewriteRule ^blogarchive/([^/\.]+)/([^/\.]+)/?$ index.php?templateseo=bloglist&byear=$1&bmonth=$2 [L]\n";
		
		$str .= "RewriteRule ^resourceprofile/([^/\.]+)/?$ resourceprofile.php?id=$1 [L]\n";
		$str .= "RewriteRule ^resourceslistings/([^/\.]+)/?$ resourcesbrokerage-boats.php?id=$1 [L]\n";
		$str .= "RewriteRule ^resources/([^/\.]+)/?$ resources.php?typename=$1 [L]\n";
		$str .= "RewriteRule ^resourceform/([^/\.]+)/([^/\.]+)/?$ resourceform.php?resourceid=$1&lno=$2 [L]\n";
		$str .= "RewriteRule ^clientsearch/([^/\.]+)/?$ searches-public.php?unm=$1 [L]\n";
		$str .= "RewriteRule ^clientfavorites/([^/\.]+)/?$ favorites-public.php?unm=$1 [L]\n";
				
		$str .= "RewriteRule ^login(/)?$ index.php?templateseo=login [L]\n";		
		$str .= "RewriteRule ^register(/)?$ index.php?templateseo=add-user [L]\n";
		$str .= "RewriteRule ^reset-password/([^/\.]+)/?$ index.php?templateseo=reset-password&cd=$1 [L]\n";
		$str .= "RewriteRule ^editprofile(/)?$ index.php?templateseo=edit-user [L]\n";
		$str .= "RewriteRule ^editcompanyprofile(/)?$ index.php?templateseo=edit-company [L]\n";
		
		$str .= "RewriteRule ^dashboard(/)?$ index.php?templateseo=dashboard [L]\n";
		$str .= "RewriteRule ^searches(/)?$ index.php?templateseo=searches [L]\n";
		$str .= "RewriteRule ^favorites(/)?$ index.php?templateseo=favorites [L]\n";
		$str .= "RewriteRule ^add-boat(/)?$ index.php?templateseo=add-yacht [L]\n";
		$str .= "RewriteRule ^my-boatlist(/)?$ index.php?templateseo=my-yachtlist [L]\n";
		$str .= "RewriteRule ^mostviewed(/)?$ index.php?templateseo=mostviewed [L]\n";
		$str .= "RewriteRule ^mostleads(/)?$ index.php?templateseo=mostleads [L]\n";
		//$str .= "RewriteRule ^advanced-search(/)?$ index.php?templateseo=advanced-search [L]\n";
		$str .= "RewriteRule ^add-manager(/)?$ index.php?templateseo=add-manager [L]\n";
		$str .= "RewriteRule ^managerlist(/)?$ index.php?templateseo=managerlist [L]\n";
		$str .= "RewriteRule ^add-office-location(/)?$ index.php?templateseo=add-office-location [L]\n";
		$str .= "RewriteRule ^office-locationlist(/)?$ index.php?templateseo=office-locationlist [L]\n";
		$str .= "RewriteRule ^add-locationadmin(/)?$ index.php?templateseo=add-locationadmin [L]\n";
		$str .= "RewriteRule ^locationadminlist(/)?$ index.php?templateseo=locationadminlist [L]\n";
		$str .= "RewriteRule ^add-broker(/)?$ index.php?templateseo=add-broker [L]\n";
		$str .= "RewriteRule ^my-brokerlist(/)?$ index.php?templateseo=my-brokerlist [L]\n";
		$str .= "RewriteRule ^my-clientlist(/)?$ index.php?templateseo=my-clientlist [L]\n";
		$str .= "RewriteRule ^announcement(/)?$ index.php?templateseo=announcement [L]\n";
		$str .= "RewriteRule ^site-statistics(/)?$ index.php?templateseo=site-statistics [L]\n";
		$str .= "RewriteRule ^select-broker(/)?$ index.php?templateseo=select-broker [L]\n";
		$str .= "RewriteRule ^leads(/)?$ index.php?templateseo=leads [L]\n";
		
		$str .= "RewriteRule ^my-inventory-feed(/)?$ index.php?templateseo=my-inventory-feed [L]\n";
		$str .= "RewriteRule ^my-lead-reports(/)?$ index.php?templateseo=my-lead-reports [L]\n";
		
		$str .= "RewriteRule ^sorry(/)?$ index.php?templateseo=sorry [L]\n";
		$str .= "RewriteRule ^thankyou(/)?$ index.php?templateseo=thanks [L]\n";
		$str .= "RewriteRule ^logout(/)?$ index.php?fcapi=logout [L]\n";
		$str .= "RewriteRule ^compareboat/([^/\.]+)/?$ index.php?templateseo=compareboat&chosenboat=$1 [L]\n";
		
		$str .= "RewriteRule ^print-inventory(/)?$ index.php?templateseo=print-inventory&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^print-inventory-broker(/)?$ index.php?templateseo=print-inventory-broker&popp=1 [L]\n";
		$str .= "RewriteRule ^popsavesearch(/)?$ index.php?templateseo=savesearch&popp=1 [L]\n";
		$str .= "RewriteRule ^pop-send-email-client(/)?$ index.php?templateseo=send-email-client&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-send-email-friend(/)?$ index.php?templateseo=send-email-friend&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-send-email-my-broker(/)?$ index.php?templateseo=send-email-my-broker&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^common-feedback(/)?$ index.php?templateseo=common-feedback&popp=1 [L]\n";
		$str .= "RewriteRule ^company-broker-map(/)?$ index.php?templateseo=company-broker-map&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^contact-broker(/)?$ index.php?templateseo=contact-broker&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^contact-model(/)?$ index.php?templateseo=contact-model&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^contact-resource(/)?$ index.php?templateseo=contact-resource&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^emailsearch(/)?$ index.php?templateseo=emailsearch&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-login(/)?$ index.php?templateseo=login&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-yacht-stat(/)?$ index.php?templateseo=yacht-stat&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-trade-in-welcome(/)?$ index.php?templateseo=trade-in-welcome&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-talk-to-specialist(/)?$ index.php?templateseo=talk-to-specialist&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-ask-for-brochure(/)?$ index.php?templateseo=ask-for-brochure&popp=1&%{QUERY_STRING} [L]\n";
		$str .= "RewriteRule ^pop-join-our-list(/)?$ index.php?templateseo=join-our-list&popp=1 [L]\n";
		$str .= "RewriteRule ^popsorry(/)?$ index.php?templateseo=sorry&popp=1 [L]\n";
		$str .= "RewriteRule ^popthankyou(/)?$ index.php?templateseo=thanks&popp=1 [L]\n";
		
		$str .= "RewriteRule ^mkt-campaign-creator(/)?$ index.php?templateseo=mkt-campaign-creator [L]\n";
		$str .= "RewriteRule ^mkt-custom-slideshow(/)?$ index.php?templateseo=mkt-custom-slideshow [L]\n";
		$str .= "RewriteRule ^customboatslideshow/([^/\.]+)/?$ index.php?templateseo=customboatslideshow&slideshowcode=$1&nohead=3 [L]\n";
		
		//CMS Page update
		/*$formpostar = json_decode($yachtchildclass->get_advanced_search_post_url());		
		$our_page_id = $formpostar->our_page_id;
		$co_broker_page_id = $formpostar->co_broker_page_id;
		
		$query = "select id, pgnm, defaultpage from tbl_page WHERE pgnm != '' and id > 1";
		$res = $db->fetch_all_array($query);
				
		foreach($res as $row){
			$c_pgid = $row["id"];
			$c_pgnm = $row["pgnm"];
			$defaultpage = $row["defaultpage"];
			$red_page = "index.php";
			
			if ($c_pgid == $our_page_id OR $c_pgid == $co_broker_page_id){
				$str.= 'RewriteRule ^'.$c_pgnm.'(/)?$ '. $red_page .'?pageid='.$c_pgid.'&%{QUERY_STRING} [L]'."\n";
			}else{
				$str.= 'RewriteRule ^'.$c_pgnm.'(/)?$ '. $red_page .'?pageid='.$c_pgid.' [L]'."\n";
			}
		}*/
				
		$str.= "RewriteRule ^([^/\.]+)$ index.php?pageslug=$1&%{QUERY_STRING} [L]\n";
		
		//301 redirect
		$query = "select * from tbl_page_301";
		$res = $db->fetch_all_array($query);
		foreach($res as $row){
			$oldurl = $row["oldurl"];
			$newurl = $row["newurl"];			
				
			if (strpos($oldurl, '?') !== false){
				$parts = explode("?", $oldurl);	
				$qstring = array_pop($parts);
				$parturl = implode(" ", $parts);
				$parturl = substr($parturl, 1);
				$str .= "RewriteCond %{QUERY_STRING}  ^". $qstring ."(&.*)?$ [NC]\n";
				$str .= "RewriteRule ^". $parturl ."$ ". $newurl ."?%1 [R=301,NE,NC,L]\n";				
			}else{				
				$str.= "Redirect 301 ". $oldurl ." ". $newurl ."\n";
			}
		}
		
		$str.= "\n";
		$str.= "ErrorDocument 404 ". $cm->folder_for_seo ."\n";
		$str.= "ErrorDocument 403 ". $cm->folder_for_seo ."index.php?templateseo=403\n";
		$str.= "ErrorDocument 302 ". $cm->folder_for_seo ."index.php?templateseo=302\n\n";
		
		$str.= "RewriteCond %{THE_REQUEST} ^[A-Z]{3,9}\ /([^/]+/)*index\.php\ HTTP/\n";
		$str.= "RewriteRule ^(([^/]+/)*)index\.php$ ". $cm->site_url ."/$1 [R=301,L]\n";		
		
		//chmod('../.htaccess', 0664);
		$fp = fopen('../.htaccess', 'w+');		
		if (!fwrite($fp, $str))
		{
			echo "Unable to write in file";
			exit(0);
		}
		fclose($fp);
		//chmod('../.htaccess', 0644);
	}
}
?>