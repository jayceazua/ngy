<?php
class Frontendclass {
    public function go_to_login($az = 0){
        global $bdir;
        if (!isset($_SESSION["suc"]) OR $_SESSION["suc"] != "true"){
            if ($az == 0){
                header('Location: '. $bdir .'login/');
                exit;
            }else{
                $returnval[] = array(
                    'retval' => 'n'
                );
                echo json_encode($returnval);
                exit;
            }
        }
    }

    public function go_to_account($az = 0){
        global $cm;
        if (isset($_SESSION["suc"]) AND $_SESSION["suc"] == "true"){
            if ($az == 0){
                header('Location: '. $cm->folder_for_seo);
                exit;
            }
        }
    }
	
	public function member_get_logout(){
		if(($_REQUEST['fcapi'] == "logout")){
			global $db, $cm;
			session_destroy();
			header('Location: '. $cm->folder_for_seo);
			exit;
		}
	}
	
	public function get_template_page(){
		global $db, $cm;
		//$pageid = round($_REQUEST["pageid"], 0);

		//$pageslug = $_REQUEST["pageslug"];
		$templateseo = $_REQUEST["templateseo"];
		$popp = round($_REQUEST["popp"], 0);
		$nohead = round($_REQUEST["nohead"], 0);
		
		$pageslug = $cm->format_page_slug();
		$pageid = $cm->get_page_id_by_slug($pageslug);
		
		$lastpageid = 0;
		$bodyclass = '';
		$startend = 1;
		
		if (isset($_SESSION["s_backpageid"])){
			$lastpageid = $_SESSION["s_backpageid"];
		}
		
		if ($templateseo != ""){
			$rawtemplate = round($_REQUEST["rawtemplate"], 0);
			if ($rawtemplate == 1){
				$templatefile = $templateseo . ".php";
			}else{
				if ($popp == 1){
					$templatefile = "popup/" . $templateseo . ".php";
				}else{
					$templatefile = "pages/" . $templateseo . ".php";
				}	
			}
			$pageid = 0;
		}else{
			//check 404 and 301
			if ($pageslug != '' AND $pageid <= 0){
				$templatefile = $cm->check_404_301_redirect($pageslug);
			}else{
				if ($pageslug == ''){ $pageid = 1; }
				$templatefile = $cm->get_common_field_name('tbl_page', 'templatefile', $pageid);
			}
			
			if ($templatefile == "" OR is_null($templatefile)){
				$templatefile = 'fullpage.php';
			}	
			
			if ($templatefile == "home.php"){
				$bodyclass = ' class="home"';
				$startend = 0;
			}
		}
		
		$returnval = array(
            'pageid' => $pageid,
            'templatefile' => $templatefile,
			'bodyclass' => $bodyclass,
			'lastpageid' => $lastpageid,
			'startend' => $startend,
			'nohead' => $nohead
        );
        return json_encode($returnval);
	}

    public function default_meta_info(){
          global $db;
          $bsql = "select * from tbl_tag";
          $bresult = $db->fetch_all_array($bsql);
          //$bfound = count($bresult);
          $brow = $bresult[0];
          return $brow;
    }

    public function default_page_info($pageid){
          global $db;
          $bsql = "select * from tbl_page where id = '".$pageid."' and status = 'y'";
          $bresult = $db->fetch_all_array($bsql);
          //$bfound = count($bresult);
          $brow = $bresult[0];
          return $brow;
    }

	public function head_title($titletext, $wh_split = 0){	
		if ($wh_split == 1){
			$headtitle_array = explode(" ", $titletext);
			$first_part = "";
			$second_part = "";
			$headtitle_array_cnt = count($headtitle_array);
			if ($headtitle_array_cnt > 1){
				$first_part = $headtitle_array[0];
				$second_part = str_replace($first_part, "", $titletext);
				//$titletext = $first_part . '<span>' . $second_part . '</span>';
				$titletext = '<span>' . $first_part . '</span>' . $second_part;
			}	
		}
		return $titletext;
	}
	
	public function format_text_heading($titletext, $collectno = 1){
		$headtitle_array = explode(" ", $titletext);
		$first_part = "";
		$second_part = "";		
		$headtitle_array_cnt = count($headtitle_array);
		if ($headtitle_array_cnt > 0){
			 for ($hk = 0; $hk < $collectno; $hk++){
				 $first_part = $headtitle_array[$hk] . " ";
			 }
			 
			 for ($hk = $collectno; $hk < $headtitle_array_cnt; $hk++){
				 $second_part .= $headtitle_array[$hk] . " ";
			 }			 
			 $titletext = '<span>'.$first_part.'</span>'.$second_part; 
		}
		return $titletext;
	}
	
	//breadcrumb
	public function dashboard_breadcrumb_start(){
		global $cm;
		$breadcrumb_st = array(
			'a_title' => 'Dashboard',
			'a_link' => $cm->folder_for_seo . "dashboard/"
		);
		return $breadcrumb_st;
	}
	
	public function page_brdcmp_array($brdcmp_array, $fontcls = "lastlink", $linkcls = "alllink"){
	  $array_ln = count($brdcmp_array);
	  if ($fontcls == ""){ $fontcls = "lastlink"; }
	  $trail_string = '<ul class="breadcrumbs">';
	  for ($i = 0; $i < $array_ln; $i++){
		    $title = $brdcmp_array[$i]["a_title"];
		    $link = $brdcmp_array[$i]["a_link"];
		
		    if ($link == ""){
		        $trail_string .= '<li><a href="javascript:void(0);">'.$title.'</a></li>';
		    }else{
		        $trail_string .= '<li><a href="'.$link.'">'.$title.'</a></li>';
		    }
	  }
	  $trail_string .= '</ul>
	  <div class="clear"></div>
	  ';
	  return $trail_string; 
	}
	
	public function create_bradcrumb_holder($pageid, $category_id_holder, $link_name, $breadcrumb_extra = array()){
		global $cm;
		//start breadcrumb
		$brdcmp_array = array();
		$arry_cnt = 0;
		$brdcmp_array[$arry_cnt]["a_title"] = 'Home';
		$brdcmp_array[$arry_cnt]["a_link"] = $cm->folder_for_seo;
		$arry_cnt++;
		
		if ($pageid > 0){
			$category_id_holder = $cm->collect_parent_page_category($pageid, $category_id_holder, 0, "y");		    
			$category_id_holder_cnt = count($category_id_holder);
			for ($chk = 0; $chk < $category_id_holder_cnt; $chk++){		
			  $brdcmp_array[$arry_cnt]["a_title"] = $category_id_holder[$chk]["name"];	
			  $brdcmp_array[$arry_cnt]["a_link"] = $category_id_holder[$chk]["linkurl"];		  
			  $arry_cnt++;		  
			}
		}
		
		if (count($breadcrumb_extra) > 0){
			if ($pageid > 0 AND $link_name != ""){
				$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
				$brdcmp_array[$arry_cnt]["a_link"] = $cm->get_page_url($pageid, "page");
				$arry_cnt++;
			}
			
			foreach($breadcrumb_extra as $breadcrumb_extra_row){
				$brdcmp_array[$arry_cnt]["a_title"] = $breadcrumb_extra_row["a_title"];
				$brdcmp_array[$arry_cnt]["a_link"] = $breadcrumb_extra_row["a_link"];
				$arry_cnt++;
			}
		}else{
			$brdcmp_array[$arry_cnt]["a_title"] = $link_name;
			$brdcmp_array[$arry_cnt]["a_link"] = "";
			$arry_cnt++;
		}
		return $brdcmp_array;
	}
	
	//language
	public function language_option_selection(){
		global $cm;
		$returntext = '
		<div id="google_translate_element" style="display:none;"></div>
		<ul class="language">
			<li class="active"><span class="notranslate"><a title="Transalate Into English" fromlan="auto" tolan="en" data-lang="English" class="lang-change en English" href="javascript:void(0);">English</a></span></li>				
			<li><span class="notranslate"><a title="Transalate Into German" fromlan="auto" tolan="de" data-lang="German" class="lang-change de German" href="javascript:void(0);">German</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into French" fromlan="auto" tolan="fr" data-lang="French" class="lang-change fr French" href="javascript:void(0);">French</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Spanish" fromlan="auto" tolan="es" data-lang="Spanish" class="lang-change es Spanish" href="javascript:void(0);">Spanish</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Russian" fromlan="auto" tolan="ru" data-lang="Russian" class="lang-change ru Russian" href="javascript:void(0);">Russian</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Italian" fromlan="auto" tolan="it" data-lang="Italian" class="lang-change it Italian" href="javascript:void(0);">Italian</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Portuguese" fromlan="auto" tolan="pt" data-lang="Portuguese" class="lang-change pt Portuguese" href="javascript:void(0);">Portuguese</a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Japanese" fromlan="auto" tolan="ja" data-lang="Japanese" class="lang-change ja Japanese" href="javascript:void(0);">Japanese</a></span></li>	
			<li><span class="notranslate"><a title="Transalate Into Chinese" fromlan="auto" tolan="zh-CN" data-lang="Chinese" class="lang-change zh-CN Chinese" href="javascript:void(0);">Chinese</a></span></li>			
		</ul>
		';

		/*$returntext = '
		<div id="google_translate_element" style="display:none;"></div>
		<ul class="language">
			<li><span class="notranslate"><a title="Transalate Into English" fromlan="auto" tolan="en" data-lang="English" class="lang-change English active" href="javascript:void(0);"><img src="'. $cm->folder_for_seo .'images/flags/lang_en.png" alt="Transalate Into English"></a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Portuguese" fromlan="auto" tolan="pt" data-lang="Portuguese" class="lang-change Portuguese" href="javascript:void(0);"><img src="'. $cm->folder_for_seo .'images/flags/lang_brazil.png" alt="Transalate Into Portuguese"></a></span></li>
			<li><span class="notranslate"><a title="Transalate Into Spanish" fromlan="auto" tolan="es" data-lang="Spanish" class="lang-change Spanish" href="javascript:void(0);"><img src="'. $cm->folder_for_seo .'images/flags/lang_spain.png" alt="Transalate Into Spanish"></a></span></li>			
		</ul>
		';*/
		return $returntext;
	}
	
	//common social link
	public function common_social_link($argu = array()){
		global $db, $cm;
		$returntxt = '';
		
		$displaytemplate = round($argu["displaytemplate"], 0);
		$displaytitle = round($argu["displaytitle"], 0);
		
		$sql = "select name, field_value from tbl_systemvar where category_id = 2 order by rank";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		foreach($result as $row){
			$socialmedia_title = $row['name'];
			$socialmedia_url = $row['field_value'];
			
			if ($socialmedia_title == "Facebook URL"){
				$socialmedia_name = "Facebook";
				$socialmedia_class = "facebook-f";
			}
			
			if ($socialmedia_title == "Google Plus URL"){
				$socialmedia_name = "Google Plus";
				$socialmedia_class = "google-plus-g";
			}
			
			if ($socialmedia_title == "LinkedIn URL"){
				$socialmedia_name = "Linkedin";
				$socialmedia_class = "linkedin-in";
			}
			
			if ($socialmedia_title == "Twitter URL"){
				$socialmedia_name = "Twitter";
				$socialmedia_class = "twitter";
			}
			
			if ($socialmedia_title == "Pinterest URL"){
				$socialmedia_name = "Pinterest";
				$socialmedia_class = "pinterest-p";
			}
			
			if ($socialmedia_title == "YouTube URL"){
				$socialmedia_name = "YouTube";
				$socialmedia_class = "youtube";
			}
			
			if ($socialmedia_title == "Instagram URL"){
				$socialmedia_name = "Instagram";
				$socialmedia_class = "instagram";
			}

			if ($socialmedia_title == "Snapchat URL"){
				$socialmedia_name = "Snapchat";
				$socialmedia_class = "snapchat-ghost";
			}
			
			//$social_tag = '';
			if ($displaytitle == 1){
				$social_tag = $socialmedia_name;
			}else{
				$social_tag = '<span class="com_none">'. $socialmedia_name . '</span>';
			}
			
			if ($socialmedia_url != "#"){
				$socialmedia_title_ar = explode(" - ", $socialmedia_title);					
				$socialmedia_lastpart = array_pop($socialmedia_title_ar);
				if ($displaytemplate == 2){					
					$returntxt .= '<li class="social-template2"><a class="'. $socialmedia_class .'" title="'. $socialmedia_name .'" href="'. $socialmedia_url .'" target="_blank"><span class="socicon socicon-'. $socialmedia_class .'"></span>'. $socialmedia_lastpart .'</a></li>';
				}elseif ($displaytemplate == 3){					
					$returntxt .= '<li class="social-template3"><a class="'. $socialmedia_class .'" title="'. $socialmedia_name .'" href="'. $socialmedia_url .'" target="_blank"><span class="socicon socicon-'. $socialmedia_class .'"></span>'. $socialmedia_lastpart .'</a></li>';
				}else{
					$returntxt .= '<li><a class="'. $socialmedia_class .'" title="'. $socialmedia_name .'" href="'. $socialmedia_url .'" target="_blank"><i class="fab fa-'. $socialmedia_class .' fa-fw"></i>'. $social_tag .'</a></li>';
				}
			}
		}

		return $returntxt;
	}
	
	//Header
	public function display_noscript_message(){
		$returntext = '
		<noscript>
			<div class="noscript">
				<div class="noscript-inner">
					<p><strong>JavaScript seem to be disabled in your browser.</strong></p>
					<p>You must have JavaScript enabled in your browser to utilize the functionality of this website.</p>
				</div>
			</div>
		</noscript>
		';
					
		return $returntext;
	}
	
	public function header_logos($argu = array()){		
		global $db, $cm;		
		$returntext = '';		

		$sql = "select * from tbl_homepage_box order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$returntext .= '
			<div class="headerlogos clearfixmain">
			<ul><!--';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay($val);
                }
				
				if ($link_type == 1){
					$go_url = $page_url;
				}elseif ($link_type == 2){					
					$go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
				}else{
					$go_url = '';
				}
				
				$returntext .= '--><li><a href="'. $go_url .'"><img src="'. $cm->folder_for_seo .'images/headerlogos/'. $id .'.png" alt=""></a></li><!--';
			}			
			$returntext .= '--></ul></div>';			
		}			
		return $returntext;		
	}
	
	public function get_header($param = array()){
		global $db, $cm;
		$companyname = $cm->get_systemvar('COMNM');
		$phone = $cm->get_systemvar('PCLNW');
		$tollfree = $cm->get_systemvar('STPH3');
		$address = $cm->get_systemvar('STADD');
		
		$loggedin_member_id = $param["loggedin_member_id"];
		$pageid = round($param["pageid"], 0);		
		
		$blanktop_text = '';
		$headerbgclass = '';
		
		$slider_category_id = $cm->get_common_field_name('tbl_page', 'slider_category_id', $pageid);
		/*if ($slider_category_id == 0){
			$headerbgclass = ' headerbg';
			$blanktop_text = '<div class="blank_top"></div>';
		}*/
		$blanktop_text = '<div class="blank_top"></div>';
		
		$returntext = '
		<h1 class="com_none">'. $companyname .'</h1>
		'. $blanktop_text .'
		
		<div class="header_left"><a href="'. $cm->site_url .'" title="'. $companyname .'"><img src="'. $cm->folder_for_seo .'images/logo.png" alt="'. $companyname .'" /></a></div>
		'. $this->get_top_right_menu($loggedin_member_id) .'
		<div class="header-search-button"><a class="header-search-button-link" href="javascript:void(0);"><span class="com_none">Search</span></a></div> 
				
		
		<div class="fcheader header'. $headerbgclass .' clearfixmain">
			<div class="container clearfixmain">				
				'. $this->display_top_navigation($loggedin_member_id) .'				
			</div>
		</div>
		';		
		return $returntext;
	}
	
	public function display_top_navigation($loggedin_member_id){
		$loggedinclass = '';
		if ($loggedin_member_id > 0){
			$loggedinclass = ' loggedinuser';
		}
		
		$returntext = '
		<div class="menu-cont'. $loggedinclass .'">
		<input id="topmenu-state" type="checkbox" />
		<label class="topmenu-btn" for="topmenu-state"><span class="topmenu-btn-icon"></span><menutitle>Menu</menutitle></label>
		'. $this->get_top_menu(array("displayon" => "1,4", "menuclass" => "topmenu" . $loggedinclass)) .'
		</div>
		';
		
		$returntext .= '
		<script>
		$(document).ready(function(){			
			if( $( window ).width() > 768 ){	
				$("ol.fcmenuproductlist li a").hover(function(e){
					var liNumber = $(this).parent("li").index();
					$("#fcproductphotos div").css("display","none");
					$("#fcproductphotos div:nth-child("+ (liNumber+1) +")").show();
					$("ol.fcproductlist li a").parent("li").removeClass("active");
					$(this).parent("li").addClass("active");
				});
			}else{
				$("ol.fcmenuproductlist li a").click(function(e){
					var liNumber = $(this).parent("li").index();
					$("#fcproductphotos div").css("display","none");
					$("#fcproductphotos div:nth-child(" + (liNumber+1) + ")").show();
					$("ol.fcproductlist li a").parent("li").removeClass("active");
					$(this).parent("li").addClass("active");
				});
			}
		});
		</script>
		';
		
		return $returntext;
	}
  

	public function get_top_right_menu($loggedin_member_id){
		global $cm, $yachtclass;
		$returntext = '';
		if (isset($_SESSION["suc"]) AND $_SESSION["suc"] == "true"){
			$loggedin_member_name = $yachtclass->member_field('uid', $loggedin_member_id);
			$admin_access = $yachtclass->member_field('admin_access', $loggedin_member_id);
			$logged_type_id = $yachtclass->member_field('type_id', $loggedin_member_id);
			$member_image = $yachtclass->get_user_image($loggedin_member_id);
						
			$backend_access_text = '';
			if ($_SESSION["usernid"] == 1 OR $admin_access == 1){
				$backend_access_text = '<li><a href="'. $cm->folder_for_seo .'admin/" target="_blank" class="icon-agent">Open Back-end</a></li>';
			}
			
			if ($logged_type_id == 6){
				$profile_url = $cm->get_page_url(0, 'dashboard');
			}else{
				$profile_url = $cm->get_page_url($loggedin_member_id, 'user');
			}
			
			$returntext = '
			<div class="header-login-button after-login clearfixmain">
				<a href="javascript:void(0);" class="header-login-button-link-afterlogin user"><span class="thumb"><img src="'. $cm->folder_for_seo .'userphoto/'. $member_image .'" alt=""></span></a>
				<ul>
					'. $backend_access_text .'
					<li><a href="'. $cm->folder_for_seo .'dashboard/" class="icon-agent">Dashboard</a></li>
					<li><a href="'. $cm->folder_for_seo .'searches/" class="icon-search">Your searches</a></li>
					<li><a href="'. $cm->folder_for_seo .'favorites/" class="icon-heart">Your favorites</a></li>
					<li><a href="'. $cm->folder_for_seo .'editprofile/" class="icon-tools">Edit Profile</a></li>
					<li><a href="'. $cm->folder_for_seo .'logout/" class="icon-login">Sign out</a></li>
				</ul>			
			</div>	
			';
		}else{
			$returntext = '
			<div class="header-login-button after-login clearfixmain">
				<a class="header-login-button-link" href="'. $cm->get_page_url(0, "login") .'"><span class="com_none">Login</span></a>				
				<ul>
					<li><a href="'. $cm->get_page_url(0, "login") .'" class="icon-login">Login</a></li>
					<li><a href="'. $cm->get_page_url(0, "register") .'" class="icon-agent">Register</a></li>
				</ul>							
			</div>	
			';
		}
		
		return $returntext;
	}
  
  	public function get_whether_child_menu($parent_id){
	  global $db, $cm;
	  $sql = "select count(*) as ttl from tbl_page where parent_id = '". $parent_id ."' and status = 'y'";
	  $countmenu = $db->total_record_count($sql);
	  return $countmenu;
  }
  
	public function get_child_menu_first($menuid){
		global $db;
		$sql = "select id as ttl from tbl_page where parent_id = '". $menuid ."' and status = 'y' order by rank limit 0, 1";
		$page_id_first = $db->total_record_count($sql);
		return $page_id_first;
	}	
  
	public function total_user_count(){
		global $db, $cm;
		$sql = "select count(*) as ttl from tbl_user where status_id = 2 and front_display = 1 order by rank";
		$total_user = $db->total_record_count($sql);
		return round($total_user);
	}
	
	public function get_top_menu($param = array()){
		global $db, $cm, $top_parentpage_category, $get_connected_to_otherpage;
		$returntext = '';
		$displayon = $param["displayon"];
		$menuclass = $param["menuclass"];
		
		//Page
		$mm_sql = "select id, name, int_page_id, int_page_tp, new_window, connected_manufacturer_id, connected_group_id, submenusection from tbl_page where disp_on IN (". $displayon .") and status = 'y' order by rank";
        $mm_result = $db->fetch_all_array($mm_sql);
		$mm_found = count($mm_result);
		$mm_counter = 0;
		if ($mm_found > 0){
			$returntext .= '<ul id="topmenu" class="'. $menuclass .'">';
			
			foreach($mm_result as $mm_row){
				$mm_id = $mm_row['id'];
				$mm_name = $mm_row['name'];
	
				$mm_int_page_id = $mm_row['int_page_id'];
				$mm_int_page_tp = $mm_row['int_page_tp'];
				$mm_open_new_window = $mm_row['new_window'];
				
				$connected_manufacturer_id = $mm_row['connected_manufacturer_id'];
				$connected_group_id = $mm_row['connected_group_id'];
				$submenusection = json_decode($mm_row['submenusection']);
				$submenusection_count = count($submenusection);
				
				//$mm_lnk_url = $cm->get_page_url($mm_id, "page");				
				
				if ($mm_id == 6){
					$mm_lnk_url = 'javascript:void(0);';
					$popclass = ' fc-open-contact';
				}else{
					$mm_lnk_url = $cm->get_page_url($mm_id, "page");
					$popclass = '';
				}
				
				if ($mm_int_page_tp != 'a'){ $mm_int_page_id = 0; }
				$link_target = "";
				if ($mm_open_new_window == "y"){ $link_target = ' target = "_blank"'; }			
			
				$liclass = '';
				$anchorclass = '';
				if ($top_parentpage_category == $mm_id OR $get_connected_to_otherpage == $mm_id){ 
					$liclass .= 'current-menu-item ';
					$anchorclass = 'current';
				}
				
				$countmenusub = $this->get_whether_child_menu($mm_id);
				if ($countmenusub == 0 AND $connected_manufacturer_id > 0 AND $connected_group_id > 0){
					$countmenusub = 1;
				}
				
				if ($countmenusub > 0){
					$liclass .= 'has-sub-menu ';
				}
				$liclass = rtrim($liclass, " ");
				
				$mm_counter++;
				$last_col = '';	
				if ($mm_counter == $mm_found){
					$last_col = ' nav_last';
				}
				
				if ($submenusection_count > 0){
					$returntext .= '<li class="megamenu"><a class="'. $anchorclass . $popclass .'" href="'. $mm_lnk_url .'"'. $popextra . $link_target .'>'. $mm_name .'</a>'. $this->get_special_menu(array("mnid" => $mm_id, "submenuclass" => "", "submenusection" => $submenusection, "menutemplate" => 1)) .'</li>';
				}elseif ($mm_id == 22){
					$returntext .= '<li class="megamenu"><a class="'. $anchorclass . $popclass .'" href="'. $mm_lnk_url .'"'. $popextra . $link_target .'>'. $mm_name .'</a>'. $this->get_special_menu_2(array("mnid" => $mm_id, "menutemplate" => 1)) .'</li>';
				}else{
					$returntext .= '<li class="normalmenu"><a class="'. $anchorclass . $popclass .'" href="'. $mm_lnk_url .'"'. $popextra . $link_target .'>'. $mm_name .'</a>'. $this->get_all_menu(array("mnid" => $mm_id, "submenuclass" => "sub-menu", "last_col" => $last_col, "connected_manufacturer_id" => $connected_manufacturer_id, "connected_group_id" => $connected_group_id)) .'</li>';
				}	
			}
		
			$returntext .= '</ul>';
		}
		return $returntext;        
  	}  
	
  	public function get_all_menu($param = array()){
		global $db, $cm, $ymclass;
		$returntext = '';
		$mnid = $param["mnid"];
		$submenuclass = $param["submenuclass"];
		$last_col = $param["last_col"];
		
		$connected_manufacturer_id = $param["connected_manufacturer_id"];
		$connected_group_id = $param["connected_group_id"];
		
		$yc_model_link_data = '';
		if ($connected_manufacturer_id > 0 AND $connected_group_id > 0){
			$retval = json_decode($ymclass->get_manufacturer_model_list_link($connected_manufacturer_id, $connected_group_id));
			foreach($retval as $model_row){			
				$yc_model_link_data .= '<li><a href="'. $model_row->linkurl .'">'. $model_row->name .'</a></li>';			
			}
		}
		
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, connected_manufacturer_id, connected_group_id from tbl_page where parent_id = '". $mnid ."' and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		if ($ss_found > 0 OR $connected_manufacturer_id > 0){
			$returntext .= '<ul class="'. $submenuclass .''. $last_col .'">';
			foreach($ss_result as $ss_row){ 
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$ss_connected_manufacturer_id = $ss_row['connected_manufacturer_id'];
				$ss_connected_group_id = $ss_row['connected_group_id'];				
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
											
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
				
				$returntext .= '
				<li><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a>'. $this->get_all_menu(array("mnid" => $ss_id, "submenuclass" => $submenuclass, "last_col" => $last_col, "connected_manufacturer_id" => $ss_connected_manufacturer_id, "connected_group_id" => $ss_connected_group_id)) .'</li>
				';
				
				/*if ($ss_id == 15){
					
					if ($this->total_user_count() > 0){
						$liclass .= 'has-sub-menu';
					}
					
					$returntext .= '
					<li class="'. $liclass .' with-thumb2"><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a>'. $this->our_team_top_menu(array("mnid" => $ss_id)) .'</li>
					';
				}else{				
					$returntext .= '
					<li class="'. $liclass .'"><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a>'. $this->get_all_menu(array("mnid" => $ss_id, "submenuclass" => $submenuclass, "last_col" => $last_col, "connected_manufacturer_id" => $ss_connected_manufacturer_id, "connected_group_id" => $ss_connected_group_id)) .'</li>
					';
				}*/
            }
			
			$returntext .= $yc_model_link_data;
      		$returntext .= '</ul>';
		}
		return $returntext;
	}
	
	public function get_child_page($sct_id, $sct_level, $category_id_holder, $pageid = 0){
	 global $db, $cm;
	 $ssql = "select id, parent_id, name, int_page_id, int_page_tp, new_window from tbl_page where parent_id = '". $sct_id ."' and status='y' and rank > 0 order by rank";
	 $sresult = $db->fetch_all_array($ssql);
	 $sfound = count($sresult);
	 if ($sfound > 0){
	?>
	<ul>
	<?php
	if ($sct_level > 1){ $liclass = ' class="level_1"'; $lnkclass = "lvl2"; }else{ $liclass = ""; $lnkclass = ""; }
	
	$cur_cid = $pageid;
	$display_subcat = "n";
	$ar_ck = $sct_level - 1;
	$sct_level++;
	foreach( $sresult as $srow ){	
	$l_catid = $srow['id'];
	$l_catname = $srow['name'];
	$l_catname = str_replace("", "&nbsp;", $l_catname);
	$cat_url = $cm->get_page_url($l_catid,"page");
	
	$mm_int_page_id = $srow['int_page_id'];
	$mm_int_page_tp = $srow['int_page_tp'];
	$mm_open_new_window = $srow['new_window'];
	
	if ($mm_int_page_tp != 'a'){ $mm_int_page_id = 0; }
	$link_target = "";
	if ($mm_open_new_window == "y"){ $link_target = ' target = "_blank"'; }
	
	if ($l_catid == $cur_cid){ 
	 $display_subcat = "y"; 
	 $lnkclass_d = $lnkclass . "current_page_item";
	}else{ 
	 $lnkclass_d = $lnkclass; 
	 if ($l_catid == $category_id_holder[$ar_ck]["id"]){
	  $display_subcat = "y";
	  $lnkclass_d = $lnkclass . "current_page_item";
	 }else{
	  $display_subcat = "n"; 
	 }
	}
	?>
	<li class="<?php echo $lnkclass_d; ?>">
	<a href="<?php echo $cat_url; ?>" title="<?php echo $l_catname; ?>"><?php echo $l_catname; ?></a>
	<?php if ($display_subcat == "y"){ $this->get_child_page($l_catid, $sct_level, $category_id_holder); } ?>
	</li>
	<?php
	}
	?>
	</ul>
    <?php
	}
   }
	
	public function get_special_menu_next_level($param = array()){
		global $db, $cm;
		$returntext = '';
		$mnid = $param["mnid"];
		$menulimit = round($param["menulimit"], 0);
		$orderby = round($param["orderby"], 0);
		$parentlink = $param["parentlink"];
		$parentlinktarget = $param["parentlinktarget"];
		
		
		if ($orderby == 1){
			$orderbyfield = "name";
		}else{
			$orderbyfield = "rank";
		}
		
		//collect child menu
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, extraclass from tbl_page where parent_id = '". $mnid ."' and status = 'y' order by " . $orderbyfield;
		if ($menulimit > 0){
			$ss_sql .= " limit 0, " . $menulimit;
		}
				
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		//end
		
		if ($ss_found > 0){
			$returntext .= '<ol>';
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$ss_extraclass = $ss_row['extraclass'];
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
				
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
				
				$ss_extraclass_text = '';
				if ($ss_extraclass != ""){
					$ss_extraclass_text = '<i class="fa fa-'. $ss_extraclass .'" aria-hidden="true"></i> ';
				}
				
				$returntext .= '<li><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_extraclass_text . $ss_name .'</a></li>';
				
			}
			$returntext .= '</ol>';
			
			if ($menulimit > 0){
				$t_sql = "select count(*) as ttl from tbl_page where parent_id = '". $mnid ."' and status = 'y'";
				$total = $db->total_record_count($t_sql);
				//if ($total > $menulimit){
					$returntext .= '
					<div class="clearfixmain"><a href="'. $parentlink .'" class="button">See All</a></div>
					';
				//}
			}
		}
		
		return $returntext;
	}
	
	public function get_special_menu($param = array()){
		global $db, $cm, $yachtchildclass;
		$returntext = '';
		$mnid = $param["mnid"];
		$submenuclass = $param["submenuclass"];
		$submenusection = $param["submenusection"];
		$menutemplate = $param["menutemplate"];
		
		if ($mnid == 40){
			$menusectiontag = 'Yachts';
			$menusectiontag2 = 'Yachts';
			$feacat = 1;
			$featuredboat_url = $cm->get_page_url(16, "page");
		}elseif ($mnid == 36){
			$menusectiontag = 'Catamarans';
			$menusectiontag2 = 'Constructions';
			$featuredboat_url = $cm->get_page_url(65, "page");
			$feacat = 2;
		}else{
			$menusectiontag = '';
			$feacat = 0;
			$featuredboat_url = '';
		}
		
		//collect child menu
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, extraclass from tbl_page where parent_id = '". $mnid ."' and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		//end
		
		if ($menutemplate == 2){
			if ($ss_found > 0){
				$returntext .= '
				<ul>
					<li>
				';
				foreach($ss_result as $ss_row){
					$ss_id = $ss_row['id'];
					$ss_name = $ss_row['name'];				
					$ss_open_new_window = $ss_row['new_window'];
					$ss_extraclass = $ss_row['extraclass'];
					$ss_lnk_url = $cm->get_page_url($ss_id, "page");
					
					$ss_link_target = "";
					if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
								
					$returntext .= '
					<div class="cols2">
						<h3><a class="titlelink" href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a></h3>
						<div class="cols2-padded clearfixmain">
						'. $this->get_special_menu_next_level(array("mnid" => $ss_id)) .'
						</div>
					</div>
					';
					
					//$returntext .= '<li><h6><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_extraclass_text . $ss_name .'</a></h6>'. $this->get_special_menu_next_level(array("mnid" => $ss_id)) .'</li>';
				}
				
				$returntext .= '
					</li>
				</ul>	
				';
			}
		}elseif ($menutemplate == 3){
			$returntext .= '
			<ul>
				<li>
					<div class="fcproductimages">
					<ol class="fcproductlist">
			';
			
			foreach($ss_result as $ss_row){
					$ss_id = $ss_row['id'];
					$ss_name = $ss_row['name'];				
					$ss_open_new_window = $ss_row['new_window'];
					$ss_extraclass = $ss_row['extraclass'];
					$ss_lnk_url = $cm->get_page_url($ss_id, "page");
					
					$ss_link_target = "";
					if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
						
					$returntext .= '
					';
				}
			
			$returntext .= '</ol>';
			
			$returntext .= '
					</div>
				</li>
			</ul>
			';
		}else{
			$menulimit = 0;
			$returntext .= '
			<ul>
				<li><div style="width:100%; max-width: 1366px; margin:0 auto;">
					<div class="cols_menu">';
			
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$ss_extraclass = $ss_row['extraclass'];
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
				
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
				
				if ($ss_id == 48 OR $ss_id == 67){
					$menulimit = 11;
				}
				
				$returntext .= '
				<div class="cols2">
					<h3><a class="titlelink" href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a></h3>
					<div class="cols3-padded clearfixmain">
					'. $this->get_special_menu_next_level(array("mnid" => $ss_id, "parentlink" => $ss_lnk_url, "parentlinktarget" => $ss_link_target, "menulimit" => $menulimit)) .'
					</div>
				</div>
				';
			}		
					
			$returntext .= '
					</div>
					
					<div class="cols_menu_after">
						'. $this->top_menu_section_display(array("submenusection" => $submenusection, "menusectiontag" => $menusectiontag, "menusectiontag2" => $menusectiontag2, "feacat" => $feacat, "featuredboat_url" => $featuredboat_url)) .'
					</div>
				</div>
				</li>
			</ul>
			';
		}
		
		return $returntext;
	}
	
	public function top_menu_section_display($param = array()){
		global $db, $cm, $yachtclass;
		$returntext = '';
		
		//param
		$submenusection = $param["submenusection"];
		$menusectiontag = $param["menusectiontag"];
		$menusectiontag2 = $param["menusectiontag2"];
		$feacat = $param["feacat"];
		$featuredboat_url = $param["featuredboat_url"];		
		//end
		
		//Featured Boat - single
		if (in_array(2, $submenusection)){
			$query_sql = "select a.*,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
			
			$query_where .= " a.manufacturer_id > 0 and";
			$query_where .= " a.status_id IN (1,3) and";
	
			$query_form .= " tbl_yacht_featured as b,";
			$query_where .= " a.id = b.yacht_id and b.featured_upto >= CURDATE() and a.display_upto >= CURDATE() and";
			$query_where .= " b.categoryid_front = '". $feacat ."' and";			
	
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
	
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by rand()";
			
			$sql = $sql." limit 0, 1";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$addressfull = $yachtclass->get_yacht_address($id, 2);
                $name = $yachtclass->yacht_name($id);
				
				$boatimg_data_ar = json_decode($yachtclass->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;
				
                //$details_url = $cm->get_page_url($id, "yacht");
				$b_ar = array(
					"boatid" => $id, 
					"makeid" => $manufacturer_id, 
					"ownboat" => $ownboat, 
					"feed_id" => $feed_id, 
					"getdet" => 0
				);
				$details_url = $yachtclass->get_boat_details_url($b_ar);
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$addressfull = $yachtclass->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $yachtclass->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$returntext .= '
				<div class="cols2">                        	
					<h3>Featured '. $menusectiontag .'</h3>
					<div class="menuboatimg clearfixmain">'. $custom_label_txt .'<a class="imgbox" href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a></div>
					<a href="'. $details_url .'">'. $name .'</a>
					<a href="'. $featuredboat_url .'" class="button">See All</a>
				</div>
				';
			}
		}
		//end
		
		/*
		//Latest Boat - single
		if (in_array(1, $submenusection)){
			$query_sql = "select *,";
			$query_form = " from tbl_yacht,";
			$query_where = " where";
			
			$query_where .= " manufacturer_id > 0 and";
			$query_where .= " status_id IN (1,3) and";
			
			//$query_where .= " yw_id > 0 and";
	
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
	
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by reg_date desc";
			
			$sql = $sql." limit 0, 1";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$addressfull = $yachtclass->get_yacht_address($id, 2);
                $name = $yachtclass->yacht_name($id);
				
				$boatimg_data_ar = json_decode($yachtclass->get_yacht_first_image($id, 1));				
                $ppath = $boatimg_data_ar->imgpath;
				$imgalt = $boatimg_data_ar->alttag;
				
                $details_url = $cm->get_page_url($id, "yacht");
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$addressfull = $yachtclass->get_yacht_address($id);
				
				$custom_label_txt = '';
				$custom_label_extra_class = '';
				if ($status_id == 3){
					$custom_label_txt = '<div class="sold"><div>Sold</div></div>';					
				}else{					
					if ($custom_label_id > 0){
						$custom_label_color = $cm->get_table_fields("tbl_custom_label_options", "custom_label_bgcolor, custom_label_textcolor", $custom_label_id, "custom_label_id");
						$custom_label_bgcolor = $custom_label_color[0]["custom_label_bgcolor"];
						$custom_label_textcolor = $custom_label_color[0]["custom_label_textcolor"];
						$custom_label_extra_class = ' style="background-color: #'. $custom_label_bgcolor .'; color: #'. $custom_label_textcolor .';"';
						$clabel = $yachtclass->get_custom_label_name($custom_label_id);
						$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
					}
				}
				
				$returntext .= '
				<div class="cols2">                        	
					<h3>New Yachts</h3>
					<div class="menuboatimg clearfixmain">'. $custom_label_txt .'<a class="imgbox" href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt="'. $imgalt .'"></a></div>
					<a href="'. $details_url .'">'. $name .'</a>
				</div>
				';
			}
		}
		//end
		*/
		
		//New Yachts - from Menu box
		if (in_array(1, $submenusection)){
			$sql = "select * from tbl_menu_box where id = '". $feacat ."'";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				if ($make_id > 0){
					$go_url = $cm->get_page_url($make_id, $seourlcall);
				}elseif ($page_id > 0){
					$go_url = $cm->get_page_url($page_id, "page");
				}elseif ($link_url != ""){
					$go_url = $link_url;
				}else{
					$go_url = 'javascript:void(0);';
				}
				
				if ($make_id2 > 0){
					$go_url2 = $cm->get_page_url($make_id2, $seourlcall);
				}elseif ($page_id2 > 0){
					$go_url2 = $cm->get_page_url($page_id2, "page");
				}elseif ($link_url2 != ""){
					$go_url2 = $link_url2;
				}else{
					$go_url2 = 'javascript:void(0);';
				}
					
				$returntext .= '
				<div class="cols2">                        	
					<h3>New '. $menusectiontag2 .'</h3>
					<div class="menuboatimg clearfixmain"><a class="imgbox" href="'. $go_url .'"><img src="'. $cm->folder_for_seo .'menuboximage/'. $imagepath .'" alt="'. $name .'"></a></div>
					<a href="'. $go_url .'">'. $name .'</a>
					<a href="'. $go_url2 .'" class="button">See All</a>
				</div>
				';
			}
		}
		//end
		
		return $returntext;
	}
	
	public function get_special_menu_2($param = array()){
		global $db, $cm, $yachtchildclass;
		$returntext = '';
		$mnid = $param["mnid"];
		$menutemplate = $param["menutemplate"];
		
		//collect child menu
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, extraclass from tbl_page where parent_id = '". $mnid ."' and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		//end
		
		$menulimit = 0;
		$returntext .= '
		<ul>
			<li><div style="width:100%; max-width: 1366px; margin:0 auto;">
				<div class="cols_menu2">
				<div class="cols1">
				<h3>About NGY</h3>
				<div class="cols3-padded clearfixmain">
				<ol>
				';
		
		foreach($ss_result as $ss_row){
			$ss_id = $ss_row['id'];
			$ss_name = $ss_row['name'];				
			$ss_open_new_window = $ss_row['new_window'];
			$ss_extraclass = $ss_row['extraclass'];
			$ss_lnk_url = $cm->get_page_url($ss_id, "page");
			
			$ss_link_target = "";
			if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
			
			$returntext .= '<li><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_extraclass_text . $ss_name .'</a></li>';
		}		
				
		$returntext .= '
						</ol>
						</div>
					</div>
				</div>
				
				<div class="cols_menu2_after">
					'. $this->top_menu_section_news() .'
				</div>
			</div>
			</li>
		</ul>
		';
		
		return $returntext;
	}
	
	public function top_menu_section_news(){
		global $db, $cm;
		$returntext = '';
		
		$sorting_sql = "reg_date desc, id desc";
		
		$query_sql = "select *,";
		$query_form = " from tbl_blog,";
		$query_where = " where";
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$sql = $sql." order by ". $sorting_sql ." limit 0,2";
		
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			global $blogclass;
			$news_url = $blogclass->get_blog_url(1, 0);
			$returntext .= '
			<div class="cols1"><h3>News &amp; Events</h3></div>
			<div class="clearfixmain">
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				if ($blog_image == ""){ $blog_image = "no.jpg"; }
				$details_url = $cm->get_page_url($slug, "blog");
				
				$returntext .= '
				<div class="cols3">
					<div class="menuboatimg clearfixmain"><a class="imgbox" href="'. $details_url .'"><img src="'. $cm->folder_for_seo .'blogimage/thumb/'. $blog_image .'" alt="'. $name .'"></a></div>
					<a href="'. $details_url .'">'. $name .'</a>
					<a href="'. $details_url .'" class="button">Read More</a>
				</div>
				';
			}
			
			$returntext .= '
			<div class="cols3">
				<div class="menuboatimg clearfixmain"><a class="imgbox" href="'. $news_url .'"><img src="'. $cm->folder_for_seo .'blogimage/thumb/see-more.png" alt="See More"></a></div>
			</div>
			</div>
			';
		}
		
		return $returntext;
	}
	
	public function our_team_top_menu($param = array()){
		global $db, $cm;
		
		$menuleveltop = $param["menuleveltop"];
		$mnid = $param["mnid"];
		$returntext = '';
		
		$ss_sql = "select id, fname, lname, user_imgpath from tbl_user where status_id = 2 and front_display = 1 order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		if ($ss_found > 0){
			$returntext .= '<ul>';
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_fname = $ss_row['fname'];
				$ss_lname = $ss_row['lname'];				
				$user_imgpath = $ss_row['user_imgpath'];
				
				$ss_name = $ss_fname . " " . $ss_lname; 
				if ($user_imgpath == ""){
					$user_imgpath = "no.png";	
				}
				
				$profile_url = $cm->get_page_url($ss_id, 'user');
				$returntext .= '<li><a href="'. $profile_url .'"><img src="'. $cm->folder_for_seo .'userphoto/big/'. $user_imgpath .'">'. $ss_name .'</a></li>';
			}
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	
	public function brand_top_menu($param = array()){
		global $db, $cm;
		
		$menuleveltop = $param["menuleveltop"];
		$mnid = $param["mnid"];
		$returntext = '';
		
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, menu_imgpath from tbl_page where parent_id = '". $mnid ."' and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		if ($ss_found > 0){
			$returntext .= '<ul>';
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$menu_imgpath = $ss_row['menu_imgpath'];
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
				
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }				
				
				if ($menu_imgpath == ""){
					$menu_imgpath = "no.png";
				}
				
				$returntext .= '<li><a href="'. $ss_lnk_url .'"'. $ss_link_target .'><img src="'. $cm->folder_for_seo .'menuimage/'. $menu_imgpath .'">'. $ss_name .'</a></li>';
			}
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	
  public function get_connected_to_otherpage($pageid){
		global $db;
		$c_prid = $db->total_record_count("select id as ttl from tbl_page where int_page_id = '". $pageid."'");
		return $c_prid;
  }

  public function display_child_page($top_parentpage_category, $pageid){
        global $db, $cm, $category_id_holder;
        //print_r($category_id_holder);
        $returntxt = '';
        $display_subcat = "n";
        $lnkclass_d = '';
        $page_level  = $cm->get_common_field_name('tbl_page', 'page_level', $pageid);
        $ar_ck = $page_level - 1;
        $ssql = "select id, parent_id, name, int_page_id, int_page_tp, new_window from tbl_page where parent_id = '". $top_parentpage_category ."' and status='y' and rank > 0 order by rank";
        $sresult = $db->fetch_all_array($ssql);
        $sfound = count($sresult);
        if ($sfound > 0){
            $returntxt .= '
           <ul class="list">
           ';
            foreach($sresult as $srow){
                $l_catid = $srow['id'];
                $l_catname = $srow['name'];
                $l_catname = str_replace("", "&nbsp;", $l_catname);
                $cat_url = $cm->get_page_url($l_catid,"page");

                $mm_int_page_id = $srow['int_page_id'];
                $mm_int_page_tp = $srow['int_page_tp'];
                $mm_open_new_window = $srow['new_window'];

                if ($mm_int_page_tp != 'a'){ $mm_int_page_id = 0; }
                $link_target = "";
                if ($mm_open_new_window == "y"){ $link_target = ' target = "_blank"'; }

                if ($l_catid == $pageid){
                    $display_subcat = "y";
                    $lnkclass_d = "current_page_item";
                }else{
                    $lnkclass_d = '';
                    if (in_array(array('name' => $srow['name'], 'linkurl' => $cat_url, 'id' => $l_catid), $category_id_holder)) {
                    //if ($l_catid == $category_id_holder[$ar_ck]["id"]){
                        $display_subcat = "y";
                        $lnkclass_d = "current_page_item";
                    }else{
                        $display_subcat = "n";
                    }
                }

                $nxtlvl = '';
                if ($display_subcat == "y"){
                    $nxtlvl = $this->display_child_page($l_catid, $pageid);
                }

                $returntxt .= '
                <li><a class="' . $lnkclass_d .'" href="'. $cat_url .'"'. $link_target .'>'. $l_catname .'</a>'. $nxtlvl .'</li>
                ';
            }
            $returntxt .= '</ul>';
        }
        return $returntxt;
  }

	//footer menu
	public function get_footer_menu($displayar){
        global $db, $cm, $yachtclass, $top_parentpage_category, $get_connected_to_otherpage;;
        $display_ar = explode(",", $displayar);
        $disp_on = '';
        foreach($display_ar as $display_row){
            $disp_on .= 'disp_on = ' . $display_row . ' OR ';
        }
        $disp_on = rtrim($disp_on, ' OR ');
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
        $returntxt = '';
        $mm_sql = "select id, name, int_page_id, int_page_tp, new_window, only_menu from tbl_page where (". $disp_on .") and status = 'y' order by rank";
        $mm_result = $db->fetch_all_array($mm_sql);
        $mm_found = count($mm_result);
        //if ($mm_found > 0){
            $returntxt .= '
             <ul class="footermenu">
            ';
            foreach($mm_result as $mm_row){
                 $mm_id = $mm_row['id'];
                 $mm_name = $mm_row['name'];
                 $mm_int_page_id = $mm_row['int_page_id'];
                 $mm_int_page_tp = $mm_row['int_page_tp'];
                 $mm_open_new_window = $mm_row['new_window'];
				 $only_menu = $mm_row['only_menu'];
                 
				 if ($only_menu == 1){
					$sub_page_first = $this->get_child_menu_first($mm_id);
					$mm_lnk_url = $cm->get_page_url($sub_page_first, "page");
				 }else{
					$mm_lnk_url = $cm->get_page_url($mm_id, "page");
				 }

                 if ($mm_int_page_tp != 'a'){ $mm_int_page_id = 0; }
                 $link_target = "";
                 if ($mm_open_new_window == "y"){ $link_target = ' target = "_blank"'; }

                 $footer_li_class = '';
                 if ($top_parentpage_category == $mm_id OR $get_connected_to_otherpage == $mm_id){
                     $footer_li_class = ' class="current_page_item"';
                 }

                 $returntxt .= '
                 <li'. $footer_li_class .'><a href="'. $mm_lnk_url .'"'. $link_target .'>'. $mm_name .'</a></li>
                 ';
            }	
			
			if ($loggedin_member_id == 0){
				 $returntxt .= '
                 <li><a href="'. $cm->folder_for_seo .'login/">Login</a></li>
                 ';
			}else{
				$returntxt .= '
                 <li><a href="'. $cm->folder_for_seo .'dashboard/">Dashboard</a></li>
                 ';
			}					
			
            $returntxt .= '
            </ul>
			<div class="clearfix"></div>
            ';
        //}
        return $returntxt;
 	}
  
	public function get_footer_menu_by_id($page_id){
		global $db, $cm;
		$returntext = '';
		
		$parentpagear = $cm->get_table_fields('tbl_page', 'id, name', $page_id);
		$parentpagear = (object)$parentpagear[0];
		$link_name = $parentpagear->name;
		
		$returntext .= '<h3>'. $link_name .'</h3>';
		
		$mm_sql = "select id, name, int_page_id, int_page_tp, new_window from tbl_page where parent_id = '". $page_id ."' and status = 'y' order by rank";
		$mm_result = $db->fetch_all_array($mm_sql);
		$mm_found = count($mm_result);		
		if ($mm_found > 0){
			$returntext .= '<ul class="footermenu2">';
			
			foreach($mm_result as $mm_row){
				$mm_id = $mm_row['id'];
				$mm_name = $mm_row['name'];
				$mm_int_page_id = $mm_row['int_page_id'];
				$mm_int_page_tp = $mm_row['int_page_tp'];
				$mm_open_new_window = $mm_row['new_window'];
				$mm_lnk_url = $cm->get_page_url($mm_id, "page");
				
				if ($mm_int_page_tp != 'a'){ $mm_int_page_id = 0; }
				$link_target = "";
				if ($mm_open_new_window == "y"){ $link_target = ' target = "_blank"'; }
				
				$returntext .= '
				<li><a href="'. $mm_lnk_url .'"'. $link_target .'>'. $mm_name .'</a></li>
				';
			}
			
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
  
	//footer main
	public function get_footer($param = array()){
		global $db, $cm, $yachtclass, $yachtchildclass;
		$companyname = $cm->get_systemvar('COMNM');
		$address = $cm->get_systemvar('STADD');
		$phone = $cm->get_systemvar('PCLNW');
		$phone_toll_free = $cm->get_systemvar('STPH3');
		$fax = $cm->get_systemvar('COFAX');
		$about_footer = nl2br($cm->get_systemvar('ABTXT'));	
		$loggedin_member_id = $param["loggedin_member_id"];
		
		$contact_text = '';		
		if ($phone_toll_free != "#"){
			$contact_text .= '<div class="footercontact"><a class="tel" href="tel:'. $phone_toll_free .'"><i class="fas fa-phone-volume"></i><strong>'. $phone_toll_free .'</strong></a></div>';
		}
		if ($phone != "#"){
			$contact_text .= '<div class="footercontact"><a class="tel" href="tel:'. $phone .'"><i class="fas fa-phone-volume"></i><strong>'. $phone .'</strong></a></div>';
		}
		
		$contact_text2 = '';
		if ($fax != "#"){
			$contact_text2 .= '<div class="footercontact">Fax: ' . $fax . '</div>';
		}		
		$contact_text2 .= '<div class="footercontact"><a href="mailto:'. $cm->admin_email_to() .'"><i class="far fa-envelope"></i>'. $cm->admin_email_to() .'</a></div>';
		
		$returntext = '
		<div class="footer clearfixmain">
			<div class="container clearfixmain">
				<ul class="footercol">
					<li>
						<div class="footer_logo"><img src="'. $cm->folder_for_seo .'images/logo.png" alt="'. $companyname .'" title="'. $companyname .'" /></div>
						<div class="footer_about">'. $about_footer .'</div>					
					</li>
					
					<li>
						<h3>Location</h3>
						<div class="footeraddress clearfixmain">
						'. $companyname .'<br>
						'. nl2br($address) .'
						</div>
					</li>
					
					<li>
						<h3>Contact</h3>
						'. $contact_text .'
						'. $contact_text2 .'
						
						<div class="footer_social clearfixmain">
						<h3>Follow Us</h3>
						<ul class="social_icon">               	
							'. $this->common_social_link() .'
						</ul>
						</div>
					</li>
					
					<li>
						<h3>Member of the International Yacht Brokers Association</h3>
						<div class="iyba_logo"><img src="'. $cm->folder_for_seo .'images/iyba_logo.png" alt="IYBA" title="IYBA" /></div>
					</li>					
				</ul>
			</div>
		</div>
		
		<div class="footerlast clearfixmain">
			<div class="container clearfixmain">
				<ul class="footerbox">
					<li>Copyright &copy; '. date("Y") .'</li>
					<li><a href="'. $cm->get_page_url(42, "page").'">Terms and Conditions</a>  |  <a class="fc-open-contact" href="javascript:void(0);">Contact Us</a></li>					
				</ul>
			</div>
		</div>		
		';
		
		return $returntext;
	}
  
  //bespoke footer
  public function bespoke_footer($option = 0){
	  global $db, $cm, $yachtclass;
	  $returntxt = '';
	  $sql = "select id, name, include_reglink from tbl_bespoke_footer where status_id = 1 order by rank";
	  $result = $db->fetch_all_array($sql);
	  $found = count($result);	  
	  if ($found > 0){
		  if ($option == 0){
			  $returntxt .= '<ul>';
		  }
		  foreach($result as $row){
			  $id = $row['id'];
			  $name = $row['name'];
			  $include_reglink = $row['include_reglink'];
			  $returntxt .= '
			  <li>
			  	<h3>'. $name .'</h3>
			  ';
			  
			  $bc_sql = "select a.*, b.status from tbl_bespoke_footer_link as a LEFT JOIN tbl_page as b ON a.int_page_id = b.id where a.bespoke_id = '".$id."' and if(a.link_type = 2 AND a.int_page_tp = 'b', b.status, 'y') = 'y' order by a.rank ";
			  $bc_result = $db->fetch_all_array($bc_sql);
			  $bc_found = count($bc_result);			  		 
			  if ($bc_found > 0 OR $include_reglink == 1){
				  $returntxt .= '<ul class="bespokefooter">';
				  foreach($bc_result as $bc_row){
					  $bc_name = $bc_row['name'];
					  $link_type = $bc_row['link_type'];
					  $page_url = $bc_row['page_url'];
					  $int_page_id = $bc_row['int_page_id'];
					  $int_page_tp = $bc_row['int_page_tp'];
					  $new_window = $bc_row['new_window'];
					  
					  if ($link_type == 1){
						  $go_url = $page_url;
					  }elseif ($link_type == 2){                      
						  $go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
					  }else{
						  $go_url = '';
					  }						  
					  $returntxt .= '<li><a href="'. $go_url .'">'. $bc_name .'</a></li>';
				  }
				  
				  if ($include_reglink == 1){
					  $loggedin_member_id = $yachtclass->loggedin_member_id();
					  if ($loggedin_member_id > 0){
						  $returntxt .= '<li><a href="'. $cm->folder_for_seo .'dashboard/">Dashboard</a></li>';
						  $returntxt .= '<li><a href="'. $cm->folder_for_seo .'logout/">Logout</a></li>';
					  }else{
						  $returntxt .= '<li><a href="'. $cm->folder_for_seo .'login/">Login</a></li>';
					  }
				  }
				  
				  $returntxt .= '</ul>';
			  }
			  $returntxt .= '</li>';
		  }
		  
		  if ($option == 0){
			  $returntxt .= '</ul>';
		  }
	  }
	  return $returntxt;
  }
  
  public function bespoke_social(){
	  global $db, $cm;
	  $returntxt = '';
	  $phone_copy = $cm->get_systemvar('PCLNW');
	  $fburl = $cm->get_systemvar('SCFBU');
	  $twurl = $cm->get_systemvar('SCTWU');
	  $gpurl = $cm->get_systemvar('SCGPU');
	  $lnurl = $cm->get_systemvar('SCLNU');
	  $yturl = $cm->get_systemvar('SCYTU');
	  
	  if ($fburl != "" OR $twurl != ""){
		  $returntxt .= '
		  <h3>Social Media</h3>
		  <ul>
		  	<li class="bottomphone"><a class="tel" href="tel:'. $phone_copy .'">'. $phone_copy .'</a></li>
			<li>
				<div class="bottomsocial">
                    <a href="'. $fburl .'" target="_blank"><i class="fa fa-facebook-square"></i></a>
					<a href="'. $twurl .'" target="_blank"><i class="fa fa-twitter-square"></i></a>
                    <a href="'. $gpurl .'" target="_blank"><i class="fa fa-google-plus-square"></i></a>                    
                    <a href="'. $lnurl .'" target="_blank"><i class="fa fa-linkedin-square"></i></a>
                </div>
			</li>
			<li>
			<img src="'. $cm->folder_for_seo .'images/footerlogo.png" alt="" />
			</li>
		  ';
		  $returntxt .= '</ul>';
	  }	  
	  return $returntxt;
  }
  
  //Testimonial
  public function display_featured_testimonial($argu = array()){
	  global $db, $cm;
	  $s = round($argu["s"], 0);
	  $featured = round($argu["featured"], 0);
	  
	  $returntext = '';
	  $query_sql = "select *,";
      $query_form = " from tbl_testimonial,";
      $query_where = " where";

      if ($featured == 1){
		  $query_where .= " featured = 1 and";
	  }
	  $query_where .= " status_id = 1 and";
      
      $query_sql = rtrim($query_sql, ",");
      $query_form = rtrim($query_form, ",");
      $query_where = rtrim($query_where, "and");

      $sql = $query_sql . $query_form . $query_where;
      
      /*if ($s == 1 OR $s == 2){
		  $sql = $sql." order by reg_date desc";
	  }else{
		  $sql = $sql." order by rand()";
		  $sql = $sql." limit 0, 2";
	  }
	  */
	  $sql = $sql." order by reg_date desc";
	  
	  $result = $db->fetch_all_array($sql);
      $found = count($result);
	  if ($found > 0){
		  $collected_page_id = $cm->get_page_id_by_shortcode("[fctestimonial]");
		  $testimonial_url = $cm->get_page_url($collected_page_id, "page");
		  
		  if ($s == 1){
			  //left-right col testimonials slider
			  $quoteclass = 'addquotes';
			  $returntext .= '
			  <div class="widgetsidebar">
			   <h3>Client Testimonials</h3>
			  ';
			  
			  $paginationtext = '
			  <div class="widget-bottom noborder clearfixmain">
				<div class="floatleft"><div id="tm_pager" class="tm_pager"></div></div>
				<div class="floatright"><a href="'. $testimonial_url .'" class="button">View All</a></div>
			  </div>
			  ';			  
		  }elseif ($s == 2){
			  //home page testimonials slider
			  $quoteclass = ' addquotesimg';
			  $returntext .= '
			  <div class="pagesection">
			  <h2>Client <span>Testimonials</span></h2>	  
			  ';
			  
			  $paginationtext = '
			  <div class="testimonialbottom">			  	
				<div class="testimonialpagination"><div id="tm_pager" class="tm_pager"></div></div>	
				<div class="testimonialreadall clearfixmain"><a href="'. $testimonial_url .'" class="arrow">View All</a></div>			
			  </div>
			  ';
		  }else{
			  //home page testimonials - last 2 display
			  $quoteclass = '';
			  $returntext .= '
			  <div class="pagesection">
			  ';
			  
			  $paginationtext = '
			  <div class="widget-bottom noborder"><a href="'. $testimonial_url .'" class="button">View All</a></div>
			  ';
		  }
		  
		  $returntext .= '		 
		  <ul class="testimonialslider">
		  ';
		  foreach($result as $row){
			    foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay(($val));
			    }
				$reg_date_d = $cm->display_date($reg_date, 'y', 9);
				if ($imgpath == ""){ $imgpath = "no.jpg"; }
				
				if ($s == 1){
					
					$poster_data = '<span class="postcolor1">'. $name .'</span>, ';
					if ($designation != ""){
						$poster_data .= $designation . ', ';
					}
					$poster_data = rtrim($poster_data, ', ');
					
					if ($company_name != ""){
						$poster_data .= ' - ' . $company_name;
					}				
					
					$startlink = $endlink = '';
					if ($website_url != ""){
						$startlink = '<a href="'. $website_url .'" target="_blank">';
						$endlink = '</a>';
					}
					
					$description = $cm->get_sort_content_description($description, 300);
					$returntext .= '
					<li>
						<div class="'. $quoteclass .'">'. $description .'</div>	
						<div class="posterdata">'. $startlink . $poster_data . $endlink .'</div>
						<div class="postermeta"><span class="postdate">'. $reg_date_d .'</span></div>
					</li>';
				}elseif ($s == 2){
					
					$poster_data = '<span class="postarname">' . $name . '</span>';
					if ($designation != ""){
						$poster_data .= $designation . ', ';
					}
					$poster_data = rtrim($poster_data, ', ');
					
					if ($company_name != ""){
						$poster_data .= '<span class="postarcompany">' . $company_name . '</span>';
					}				
					
					$startlink = $endlink = '';
					if ($website_url != ""){
						$startlink = '<a href="'. $website_url .'" target="_blank">';
						$endlink = '</a>';
					}
					
					$description = $cm->get_sort_content_description($description, 300);
					$returntext .= '
					<li class="clearfixmain">						
						<div class="posterimagehome"><img src="'. $cm->folder_for_seo .'testimonialimage/'. $imgpath .'" title="'. $name .'" alt="'. $name .'"></div>
						<div class="postercontenthome">
							<div class="postercontenthome_top">'. $description .'</div>
							<div class="postercontenthome_bottom">'. $poster_data .'</div>
							<div class="postermeta"><span class="postdate">'. $reg_date_d .'</span></div>
						</div>
					</li>
					';
				}else{
					$poster_data = '<span class="postarname">' . $name . '</span>';
					$startlink = $endlink = '';
					if ($website_url != ""){
						$startlink = '<a href="'. $website_url .'" target="_blank">';
						$endlink = '</a>';
					}
					
					if ($company_name != ""){
						$poster_data .= '<span class="postarcompany">'. $startlink . $company_name . $endlink .'</span>';
					}
					
					$description = $cm->get_sort_content_description($description, 300);
					$returntext .= '
					<li class="clearfixmain">						
						<div class="posterimagehome">
							<div class="posterimagehome_top"><img src="'. $cm->folder_for_seo .'testimonialimage/'. $imgpath .'" title="'. $name .'" alt="'. $name .'"></div>
							<div class="posterimagehome_bottom">'. $poster_data .'</div>
							<div class="postermeta"><span class="postdate">'. $reg_date_d .'</span></div>
						</div>
						<div class="postercontenthome">
							<div>'. $description .'</div>
						</div>
					</li>
					';
				}
		  }
		  $returntext .= '
		  </ul>		  
		  ';
		  
		  if ($s == 1 OR $s == 2){
			  $returntext .= '
			  </div>
			  '. $paginationtext.'
			  ';
			  
			  $returntext .= '
			  <script type="text/javascript">
			  $(document).ready(function(){
				  if ($(".testimonialslider").length > 0){	
					$(".testimonialslider").carouFredSel({
						responsive	: true,
						width: "100%",
						height: "variable",
						pagination: "#tm_pager",
						scroll		: {
							fx			: "crossfade",
							easing		: "swing",
							timeoutDuration: 7000,
							duration	: 1000
			
						},
						items		: {
							visible		: 1,
							height: "auto",
						}
					});
				  }
			  });
			  </script>
			  ';
		  
		  }else{
			  $returntext .= '
			  </div>
			  '. $paginationtext.'
			  ';
		  }		  
	  }	  
	  return $returntext;
  }
  
	public function display_testimonial_slider($argu = array()){
		global $db, $cm;
		$template = round($argu["template"], 0);	
		$returntext = '';
		if ($template == 2){
			$query_sql = "select *,";
			$query_form = " from tbl_testimonial,";
			$query_where = " where";
			
			$query_where .= " status_id = 1 and";
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by reg_date desc";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			
			if ($found > 0){
				$returntext .= '
				<div class="fctestimonial clearfixmain">
					<div class="container clearfixmain">
						<h1 class="t-center"><span>SEE WHAT OUR CLIENTS HAVE TO SAY</span>Read some of our reviews below</h1>
						<div class="fctestimonial-wrap">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$rating_text = '';
					$full_rating = $rating;
					$empty_rating = 5 - $rating;
					
					for ($k = 1; $k <= $full_rating; $k++){
						$rating_text .= '<span class="fa fa-star checked"><span class="com_none">star</span></span>';
					}
					for ($k = 1; $k <= $empty_rating; $k++){
						$rating_text .= '<span class="fa fa-star"><span class="com_none">star</span></span>';
					}
					
					
					$returntext .= '
					<div class="clearfixmain">
						<div class="right">'. $description .'</div>
						<div class="left">
							<h4>'. $name .'</h4>
							'. $rating_text .'
						</div>                
					</div>
					';
				}
				
				$returntext .= '
						</div>
					</div>
				</div>
				';
				
				$returntext .= '
				<script>
				$(document).ready(function(){
					$(".fctestimonial-wrap").slick({
					  dots: true,
					  infinite: true,
					  speed: 300,
					  slidesToShow: 1,
					  adaptiveHeight: true,
					  pauseOnHover: true,
					  arrows:false,
					});	
				});
				</script>
				';
			}
		}elseif ($template == 3){
			$query_sql = "select *,";
			$query_form = " from tbl_testimonial,";
			$query_where = " where";
			
			$query_where .= " status_id = 1 and";
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by reg_date desc";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			
			if ($found > 0){
				$returntext .= '
				<div class="clearfixmain ng-padding ng-testimonialbg">
					<div class="container wow fadeInUp" data-wow-duration="1.2s">
						<h1 class="ng-h1 uppercase mb-2"><span>WHAT OUR CLIENTS SAY</span></h1>
						<h2 class="ng-h3 uppercase white">Read some of our reviews Below</h2>
						<div class="ng-testimonial owl-carousel">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$rating_text = '';
					$full_rating = $rating;
					$empty_rating = 5 - $rating;
					
					for ($k = 1; $k <= $full_rating; $k++){
						$rating_text .= '<span class="fa fa-star checked"><span class="com_none">star</span></span>';
					}
					for ($k = 1; $k <= $empty_rating; $k++){
						$rating_text .= '<span class="fa fa-star"><span class="com_none">star</span></span>';
					}
					
					
					$returntext .= '
					<div class="item">
						<p class="ng-testimonial-author">'. $name .'</p>
						<p>'. $rating_text .'</p>
						<div class="white ng-text t-justify">'. $description .'</div>
					</div>
					';
				}
				
				$returntext .= '
						</div>
					</div>
				</div>
				';
				
				$returntext .= '
				<script>
				$(document).ready(function(){
					var owl = $(".ng-testimonial");
					  owl.owlCarousel({
						margin: 10,
						loop: true,
						items: 1
					  });
				});
				</script>
				';
			}
		}else{
		
			if ($template == 1){
				$starttext = '
				<h2 class="border-below t-center">WOWs from <span>Clients</span></h2>
				';
			}else{
				$starttext = '
				<h2>Not sure yet?</h2>
				<p class="t-center uppercase"><strong>See some of our customer reviews below:</strong></p>
				';
			}
			
			$query_sql = "select *,";
			$query_form = " from tbl_testimonial,";
			$query_where = " where";
			
			$query_where .= " status_id = 1 and";
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by reg_date desc";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			
			if ($found > 0){
				$collected_page_id = $cm->get_page_id_by_shortcode("[fctestimonial]");
				$testimonial_url = $cm->get_page_url($collected_page_id, "page");
				
				$returntext .= '
				<div class="fcscrollslider clearfixmain">
				<div class="container container2 clearfixmain">
					'. $starttext .'
					<div id="fcwow" class="clearfixmain">
						<div class="wow-slider owl-carousel" style="margin-top:0">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay(($val));
					}
					
					$reg_date_d = $cm->display_date($reg_date, 'y', 9);
					if ($imgpath == ""){ $imgpath = "no.jpg"; }
					
					$poster_data = '';
					if ($designation != ""){
						$poster_data .= $designation . ', ';
					}
					
					if ($company_name != ""){
						$poster_data .= $company_name . ', ';
					}
					$poster_data = rtrim($poster_data, ', ');
					if ($website_url != ""){
						$poster_data = '<a href="'. $website_url .'" target="_blank">'. $poster_data .'</a>';
					}
					
					if ($poster_data != ""){
						$poster_data = '<br>' . $poster_data;
					}				
					
					$returntext .= '
					<div>
						<div class="wow-left">
							<img src="'. $cm->folder_for_seo .'testimonialimage/'. $imgpath .'" title="'. $name .'" alt="'. $name .'">
							<p><span>'. $name .'</span>'. $poster_data .'</p>
						</div> 
						<div class="wow-right">      
						  '. $description .'
						</div>       
					</div>
					';
				}
				
				$returntext .= '
						</div>
					</div>
				</div>
				</div>
				';
				
				$returntext .= '
				<script> 
				$(document).ready(function(){ 						
					// Owl Carousel
					var owl = $(".wow-slider");
					owl.owlCarousel({
						items: 1,
						loop: true,
						nav:false,
						dots: true,
						autoplay:true,
						autoplayTimeout:5000,
						autoplayHoverPause:true
					});
				});
				</script> 
				';
			}
		}
		
		return $returntext;
	}
  
  public function testimonial_sql($broker_id = 0){
        $query_sql = "select *";
        $query_form = " from tbl_testimonial,";
        $query_where = " where";
		
		if ($broker_id > 0){
			$query_where .= " broker_id = '". $broker_id ."' and";
		}

        $query_where .= " status_id = 1 and";
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        return $sql;
 }
  
  public function total_testimonial_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
  }
  
  public function testimonial_list($p, $argu = array()){
        global $db, $cm;
        $returntext = '';
        $moreviewtext = '';
		$broker_id = round($argu["broker_id"], 0);
		$innerslider = round($argu["innerslider"], 0);
		$sorting_sql = "reg_date desc";	
	
		if ($p > 0){
			if ($innerslider == 0){
				if ($broker_id > 0){
					$dcon = 5;
				}else{
					$dcon = $cm->pagination_record_list;
					//$dcon = 1;
				}
				$page = ($p - 1) * $dcon;
				if ($page <= 0){ $page = 0; }
				$limitsql = " LIMIT ". $page .", ". $dcon;
			}else{
				$limitsql = '';
			}
		}else{
			$limitsql = '';
		}

        $sql = $this->testimonial_sql($broker_id);
        $foundm = $this->total_testimonial_found($sql);

        $sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        $remaining = $foundm - ($p * $dcon);
        if ($found > 0){
			$counter = 1;
            foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
				$reg_date_d = $cm->display_date($reg_date, 'y', 9);
				$poster_data = '<span class="testi-author">'. $name .'</span>, ';
				if ($designation != ""){
					$poster_data .= $designation . ', ';
				}
				$poster_data = rtrim($poster_data, ', ');
				
				if ($company_name != ""){
					$poster_data .= ' - ' . $company_name;
				}				
				
				$startlink = $endlink = '';
				if ($website_url != ""){
					$startlink = '<a href="'. $website_url .'" target="_blank">';
					$endlink = '</a>';
				}
				
				if ($imgpath != ""){ 
					$imagefolder = 'testimonialimage/';
					$imagedata = $startlink . '<img src="'. $cm->folder_for_seo . $imagefolder . $imgpath .'" alt="'. $name .'">' . $endlink;
					$testimonialcontent = '
					<div class="edimgleft testimonialimage">'. $imagedata .'</div>
					<div class="edcontentright">						
						<div class="addquotes">'. $description .'</div>
						<div class="posterdata">'. $startlink . $poster_data . $endlink .'</div>
						<!--<div class="blogmeta"><span class="postdate">'. $reg_date_d .'</span></div>-->
					</div>
					';
				}else{
					$testimonialcontent = '
					<div class="addquotes">'. $description .'</div>
					<div class="posterdata">'. $startlink . $poster_data . $endlink .'</div>
					<!--<div class="blogmeta"><span class="postdate">'. $reg_date_d .'</span></div>-->
					';
				}
				
				$noborder = '';
				$wh_display_row = '';
				$row_item_id = '';
				if ($innerslider == 1){
					if ($counter > 1){
						//$wh_display_row = ' com_none';
					}

					$row_item_id = ' id="testimonialitem'. $counter .'"';
					$noborder = ' noborder';
				}

				
                $returntext .= '
				<div'. $row_item_id .' class="editordivrow editordivrowtestimonial'. $wh_display_row . $noborder .' clearfixmain">
					'. $testimonialcontent .'
				</div>
                ';
				
				$counter++;
            }

            $p++;
            if ($remaining > $dcon){
                $button_no = $dcon;
            }else{
                $button_no = $remaining;
            }

            if ($remaining > 0){
                $moreviewtext .= '
                <a href="javascript:void(0);" p="'. $p .'" c=\''. json_encode($argu) .'\' class="moretestimonial button loding"><span>Load <recno>'. $button_no .'</recno> more record(s)</span></a>
                ';
            }else{
                $moreviewtext = '';
            }
        }

        $returnval[] = array(
            'doc' => $returntext,
            'moreviewtext' => $moreviewtext,
			'foundm' => $foundm
        );
        return json_encode($returnval);
  }
  
  public function testimonial_list_main($argu = array()){
		global $db, $cm;
	  	
	  	//param - innerbox
	  	$default_param = array("broker_id" => 0, "innerbox" => 0, "innerslider" => 0, "displaytype" => 0);
		$argu = array_merge($default_param, $argu);
		
		$innerbox = $argu["innerbox"];
		$innerslider = $argu["innerslider"];
	  	$displaytype = $argu["displaytype"];
	    //end
	  
	  	$p = 1;
		$retval = json_decode($this->testimonial_list($p, $argu));
	    $foundm = $retval[0]->foundm;
	  
	  	if ($foundm > 0){
			$holderclass = "mostviewed clearfixmain";
			$testimonialheading = '';
			if ($innerbox == 1){
				$holderclass = "broker_testimonial_slider_in owl-carousel mostviewed brokercontent spacerbottom clearfixmain";
				$testimonialheading = '<h2 class="borderstyle1">Testimonials</h2>';
			}

			$returntext = $testimonialheading . '
			<div class="broker_testimonial_slider clearfixmain">
			  <div id="testimonial_filtersection" class="'. $holderclass .'">
			  '. $retval[0]->doc .'
			  </div>
			';

			if ($innerslider == 1){
				/*if ($foundm > 1){
					$returntext .= '					
					<i maxitem="'. $foundm .'" c="'. $foundm .'" class="fa fa-chevron-left testimonialtabbutton testimonialtabbuttonprev" aria-hidden="true"></i>
					<i maxitem="'. $foundm .'" c="2" class="fa fa-chevron-right testimonialtabbutton testimonialtabbuttonnext" aria-hidden="true"></i>
					';
				}*/

				$returntext .= '
				</div>
				<script type="text/javascript">
				$(document).ready(function(){
					var broker_testimonial_slider_owl = $(".broker_testimonial_slider_in");
					broker_testimonial_slider_owl.owlCarousel({
						items: 1,
						merge: true,
						loop: true,
						autoplay: true,
						autoplayHoverPause: true,
						center              :true,
						stagePadding		:0,
						autoplayTimeout: 5000,
						animateOut: \'fadeOut\',
						dots: false,
						nav: true,
						navText: ["<span class=\"testimonialtabbuttonprev\"></span>","<span class=\"testimonialtabbuttonnext\"></span>"],
						margin: 0
					});
				
				
					/*$(".main").on("click", ".testimonialtabbutton", function(){
						var c = parseInt($(this).attr("c"));
						var maxitem = parseInt($(this).attr("maxitem"));

						//next
						var nc = c + 1;
						if (nc > maxitem){
							nc = 1;
						}

						//prev
						var pc = c - 1;
						if (pc < 1){
							pc = maxitem;
						}

						$(".editordivrowtestimonial").hide();
						$("#testimonialitem" + c).show();

						//$(".editordivrowtestimonial").animate( { "opacity": "hide", top:"100"} , 500 );
						//$("#testimonialitem" + c).animate( { "opacity": "show", top:"100"} , 500 );

						$(".testimonialtabbuttonprev").attr("c", pc);
						$(".testimonialtabbuttonnext").attr("c", nc);

					});*/
				});
				</script>
				';
			}else{
				$returntext .= '
				<div class="mostviewed clearfixmain">
					<p class="textcenter testimonialpagination">'. $retval[0]->moreviewtext .'</p>
				</div>
				';

				$returntext .= '
				</div>
				<script type="text/javascript">
				$(document).ready(function(){
					$.fn.filtertestimonial = function(p, c){
						b_sURL = bkfolder + "includes/ajax.php";
						$.post(b_sURL,
							{
								p:p,
								searchfields:c,
								subsection:2,
								az:19,
								dataType: \'json\'
							},
							function(data){
								data = $.parseJSON(data);
								content = data[0].doc;
								moreviewtext = data[0].moreviewtext;
								if (content != ""){
									if (p == 1){
										$("#testimonial_filtersection").html(content);
									}else{
										$("#testimonial_filtersection").append(content);
									}
								}else{
									$(\'#testimonial_filtersection\').html(\'Sorry. Record unavailable.\');
								}
								$(".testimonialpagination").html(moreviewtext);
								$(document.body).trigger("sticky_kit:recalc");
							});
					}

					$(".main").on("click", ".moretestimonial", function(){
						var p = $(this).attr("p");
						var c = $(this).attr("c");
						$(this).filtertestimonial(p, c);
					});
				});
				</script>
			   ';
			}			
	   }
	  
	  if ($displaytype == 1){
		  $returnar = array(
            'doc' => $returntext,
			'foundm' => $foundm
        );
        return json_encode($returnar);
	  }else{
		  return $returntext;
	  }	  
  }
  //end
  
	//testimonial form
	public function share_testimonial_form(){	
		global $db, $cm, $yachtclass, $captchaclass;
		
		$datastring = $cm->session_field_testimonial();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		if ($rating < 1){
			$rating = 5;
		}
		
		$returntext = '
		<div class="singleblock_box">
			<form method="post" action="'. $cm->folder_for_seo .'" id="testimonial-ff" name="testimonial-ff" enctype="multipart/form-data">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="testimonialform" />
			
			<ul class="form">				           
				<li class="left">
					<p><label for="name">Name</label> <span class="requiredtext">*</span></p>
					<input type="text" class="input" id="name" name="name" value="'. $name .'" />
				</li>
				<li class="right">
					<p><label for="designation">Designation</label></p>
					<input type="text" class="input" id="designation" name="designation" value="'. $designation .'" />
				</li>
				
				<li class="left">
					<p><label for="company_name">Company Name</label></p>
					<input type="text" class="input" id="company_name" name="company_name" value="'. $company_name .'" />
				</li>
				<li class="right">
					<p><label for="website_url">Website URL</label></p>
					<input type="text" class="input" id="website_url" name="website_url" value="'. $website_url .'" />
				</li>
				
				<li class="left">
					<p><label for="testimonial_imgpath">Select Image [w: '. $cm->testimonial_im_width .'px, h: '. $cm->testimonial_im_height .'px]</label></p>
					<input validval="'. $cm->allow_image_ext .'" type="file" id="testimonial_imgpath" name="testimonial_imgpath" class="input" />
					<p>[Allowed file types: '. $cm->allow_image_ext .']</p>
				</li>
				<li class="right">
					<p><label for="rating">Rating (5 = Excellent, 1 = Very Poor)</label></p>
					<select name="rating" id="rating" class="select">
						'. $yachtclass->get_common_number_combo($rating, 5, 1) .'
					</select>
				</li>
		   </ul>
		   
		   <ul class="form">		
				<li>
					<p><label for="message">Comments</label> <span class="requiredtext">*</span></p>
					<textarea rows="1" cols="1" id="message" name="message" class="comments">'. $message .'</textarea>
				</li>
				
				<li>'. $captchaclass->call_captcha(). '</li>     
		
				<li class="submit">
					<button type="submit" class="button" value="Submit">Submit</button>
				</li>
				
				<li>
					<p><span class="requiredtext">*</span> = Mandatory fields</p>            
				</li>
			</ul>
			</form>
			<div class="clear"></div>
		</div>
		';		
		return $returntext;	
	}
	//end
  
	//submit testimonial form
	public function submit_testimonial_form(){
		if(($_POST['fcapi'] == "testimonialform")){
			global $db, $cm, $fle, $sdeml;
						
			$name = $_POST["name"];
			$company_name = $_POST["company_name"];
			$designation = $_POST["designation"];
			$website_url = $_POST["website_url"];
			//$boat_reference = $_POST["boat_reference"];
			$message = $_POST["message"];
			$rating = round($_POST["rating"], 0);
			$email2 = $_POST["email2"];
						
			//create the session
			$datastring = $cm->session_field_testimonial();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			$red_pg = $_SESSION["s_backpage"];
			
			//field data checking	
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Comments', $red_pg, '', '', 1, 'fr_');

			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('sharetestimonial');
			$cm->delete_session_for_form($datastring);
			$website_url = $cm->format_url_txt($website_url);
			
			//insert to db
			$small_description = $cm->get_sort_content_description($message, 450);
			$reg_date = date("Y-m-d");
			$status_id = 3;
			$sql = "insert into tbl_testimonial (name
												, description
												, small_description
												, company_name
												, designation
												, website_url						
												, status_id
												, reg_date) values ('". $cm->filtertext($name) ."'
												, '". $cm->filtertext($message) ."'
												, '". $cm->filtertext($small_description) ."'
												, '". $cm->filtertext($company_name) ."'
												, '". $cm->filtertext($designation) ."'
												, '". $cm->filtertext($website_url) ."'
												, '". $status_id ."'
												, '". $reg_date ."'
												, rating = '". $rating ."')";							
			$iiid = $db->mysqlquery_ret($sql);
			//end
			
			//image upload
			$attachment_ar = array();
			$filename = $_FILES['testimonial_imgpath']['name'] ;
			if ($filename != ""){
				$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
				if ($wh_ok == "y"){
					$filename_tmp = $_FILES['testimonial_imgpath']['tmp_name'];
					$filename = $fle->uploadfilename($filename);	
					$filename1 = $iiid."testimonial".$filename;
					
					$target_path_main = "testimonialimage/";
				
					//client image
					$target_path = $target_path_main;
					$r_width = $cm->testimonial_im_width;
					$r_height = $cm->testimonial_im_height;
					$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
				
					$fle->filedelete($filename_tmp);
					$sql = "update tbl_testimonial set imgpath = '".$cm->filtertext($filename1)."' where id = '".$iiid."'";
					$db->mysqlquery($sql);
					
					$attachment_ar[0]["name"] =  $cm->filtertext($filename);
        			$attachment_ar[0]["path"] = $cm->folder_for_seo . $target_path_main . $filename1;
				}
			}
			//end		
			
			$message = nl2br($message);			
			$testimonialsubmission = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
								
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Company Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($company_name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Designation:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($designation, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Rating:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $rating .'</td>
				</tr>
					
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comments:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
				</tr>
			</table>          
			';		

			$companyname = $cm->sitename;
			
			//send email to admin
			$send_ml_id = 16;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#testimonialsubmission#", $testimonialsubmission, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = '';
			$fromnamesender = $cm->filtertextdisplay($name);	 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender, $$attachment_ar);			
			//end
			
			$_SESSION["thnk"] = 'Thank you! Your testimonial has been submitted.';
			header('Location: ' . $cm->get_page_url('', 'thankyou'));
			exit;		  
		}
	}
	//end  
 
    //Home box	
	public function homebox_list($argu = array()){		
		global $db, $cm;		
		$returntext = '';		
		$innerpage = round($argu["innerpage"], 0);
		
		$contauner_start = "";
		$contauner_end = "";
		$box_heading = '';
		
		if ($innerpage == 1){
			$contauner_start = $box_heading;
		}else{
			$contauner_start = '
			<div class="homesection2 clearfixmain">			
			<div class="container clearfixmain">
			'. $box_heading .'
			';
			
			$contauner_end = '</div></div>';
		}
		
		$sql = "select * from tbl_homepage_box where status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$returntext .= $contauner_start . '
			<ul class="tophomebox"><!--';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay($val);
                }
				
				if ($imagepath == ""){
					$imagepath = "no.jpg";
				}
				
				if ($link_type == 1){
					$go_url = $page_url;
				}elseif ($link_type == 2){
					if ($make_id > 0){
						$int_page_id .= "_" . $make_id;
					}
					$go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
				}else{
					$go_url = '';
				}				
				
				$returntext .= '--><li><a href="'. $go_url .'">
				<article class="caption">
					<img alt="'. $name .'" class="caption__media" src="'. $cm->folder_for_seo .'homeboximage/'. $imagepath .'" />
					<div class="caption__overlay">
						<div class="caption__overlay__title"><img alt="'. $name .' Logo" src="'. $cm->folder_for_seo .'homeboximage/logo'. $id .'.png" /></div>
						<p class="caption__overlay__content">
							'. $description .'
						</p>
					</div>
				</article>	
				</a></li><!--';
			}
			
			$returntext .= '--></ul>
			'. $contauner_end .'
			';
		}
			
		return $returntext;		
	}
	
	public function get_home_box($boxid){
		global $db, $cm, $yachtclass;
		$returntext = '';
		
		$boxar = $cm->get_table_fields('tbl_homepage_box', '*', $boxid);
		$boxar = (object)$boxar[0];
		
		$name = $boxar->name;
		$description = $boxar->description;
		$imagepath = $boxar->imagepath;
		$link_type = $boxar->link_type;
		$page_url = $boxar->page_url;
		$int_page_id = $boxar->int_page_id;
		$int_page_tp = $boxar->int_page_tp;
		
		if ($imagepath == ""){
			$imagepath = "no.jpg";
		}
		
		if ($link_type == 1){
			$go_url = $page_url;
		}elseif ($link_type == 2){
			if ($make_id > 0){
				$int_page_id .= "_" . $make_id;
			}
			$go_url = $cm->get_seo_linked_url($int_page_id, $int_page_tp);
		}else{
			$go_url = '';
		}
		
		$buttontext = '';
		if ($go_url != ""){
			$buttontext .= '<div class="buttonlink clearfixmain"><a class="button arrow" href="'. $go_url .'">View Details</a></div>';
		}

		if ($int_page_tp == "f"){
			$broker_id = $cm->get_common_field_name("tbl_yacht", "broker_id", $int_page_id);
			$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
			$fname = $broker_ar[0]["fname"];
			$lname = $broker_ar[0]["lname"];
			$brokername = $fname .' '. $lname;

			$gaeventtracking = $yachtclass->google_event_tracking_code('broker', $brokername);
			$buttontext .= '<div class="buttonlink clearfixmain"><a '.$gaeventtracking.' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $int_page_id .'" class="contactbroker button requestprice arrow" data-type="iframe">Begin to Create</a></div>';
		}
		
		$returntext .= '
		<div class="topimg"><a href="'. $go_url .'"><img class="caption__media" src="'. $cm->folder_for_seo .'homeboximage/'. $imagepath .'" /></a></div>
		<div class="boxtext">'. nl2br($description) .'</div>	
		<div class="buttontext">'. $buttontext .'</div>	
		';		
		return $returntext;
	}
	
	public function homebox_list_2col($argu = array()){		
		global $db, $cm, $yachtclass;		
		$returntext = '';		
		$innerpage = round($argu["innerpage"], 0);
		
		$contauner_start = "";
		$contauner_end = "";
		$box_heading = '<h2 class="icontop">Models</h2>';
		
		if ($innerpage == 1){
			$contauner_start = $box_heading;
		}else{
			$contauner_start = '
			<div class="homesection2 clearfixmain">			
			<div class="container clearfixmain">
			'. $box_heading .'
			';
			
			$contauner_end = '</div></div>';
		}
		
		$returntext .= $contauner_start;
		
		$returntext .= '<div class="boxleft clearfixmain">
		<h3>Yachting Series</h3>
		<ul class="tophomebox"><li>'. $this->get_home_box(1) .'</li><li>'. $this->get_home_box(2) .'</li></ul>
		</div>';
		
		$returntext .= '<div class="boxright clearfixmain">
		<h3>Jet Series</h3>
		<ul class="tophomebox"><li>'. $this->get_home_box(3) .'</li></ul>
		</div>';
		
		$returntext .= $contauner_end;			
		return $returntext;		
	}
	
	
	//Brand Box
	public function display_brand_box($param = array()){
		global $db, $cm;		
		$returntext = '';
		
		//param
		$default_param = array("ownboat" => 1, "innerpage" => 0, "sectionid" => 1, "hideheading" => 0);
		$param = array_merge($default_param, $param);	
		$ownboat = round($param["ownboat"], 0);
		$innerpage = round($param["innerpage"], 0);
		$hideheading = round($param["hideheading"], 0);
		$sectionid = round($param["sectionid"], 0);
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
		
		$contauner_start = "";
		$contauner_end = "";
		$box_heading = '<h2 class="singlelinebottom30">Yacht Search By <span>Brand</span></h2>';
				
		if ($innerpage == 1){
			if ($hideheading == 0){
				$contauner_start = $box_heading;
			}
		}else{
			$contauner_start = '
			<div class="homesectionbrandbox clearfixmain">			
			<div class="container clearfixmain">
			'. $box_heading .'
			<div class="homesectionbrandbox_container2 clearfixmain">
				<a class="brandviewall" href="'. $cm->get_page_url(48, "page") .'">View All<br>Brands</a>
			';
			
			$contauner_end = '</div></div></div>';
		}
		
		$sql = "select * from tbl_brand_specific where section_id = '". $sectionid ."' order by rank";
		$result = $db->fetch_all_array($sql);
        $found = count($result);

		if ($found > 0){
			$returntext .= $contauner_start . '
			<ul class="tophomeboxbrand"><!--';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay($val);
                }
				
				if ($make_id > 0){
					$go_url = $cm->get_page_url($make_id, $seourlcall);
				}elseif ($page_id > 0){
					$go_url = $cm->get_page_url($page_id, "page");
				}elseif ($link_url != ""){
					$go_url = $link_url;
				}else{
					$go_url = 'javascript:void(0);';
				}
				
				if ($logoimage != ""){
					$overlay_content = '<img alt="'. $name .' Logo" src="'. $cm->folder_for_seo .'brandboximage/'. $logoimage .'" />';
				}else{
					$overlay_content = $name;
				}
				
				if ($bgimagealt == ""){
					$bgimagealt = $name;
				}
					
				$returntext .= '--><li><a href="'. $go_url .'">
				<article class="caption">
					<img alt="'. $bgimagealt .'" class="caption__media" src="'. $cm->folder_for_seo .'brandboximage/'. $imagepath .'" />
					<div class="caption__overlay">
						<div class="caption__overlay__title">'. $overlay_content .'</div>
						<p class="caption__overlay__content">
							'. $description .'
						</p>
					</div>
				</article>	
				</a></li><!--';
			}
			
			$returntext .= '--></ul>
			'. $contauner_end .'
			';
		}
		
		return $returntext;
	}
  
  	//FAQ
    public function faq_list($argu = array()){
        global $db, $cm;
        $returntext = '';
		$s = round($argu["s"], 0);
		$shortclass = "";

        $sql = "select f_question, f_answer from tbl_faq where status_id = 1 order by rank";
		
		if ($s == 1){
			$sql .= " limit 0, 6";
			$shortclass = " faqshort";
		}
		  
        $result = $db->fetch_all_array($sql);
        $found = count($result);

        if ($found > 0){
			$returntext .= '<ul class="faqlist'. $shortclass .' clearfixmain">';
			$f_counter = 0;
            foreach($result as $row){
                foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay($val);
                }
				$display_counter = $f_counter + 1;
				if ($s == 1){
					$previewtext = '<h4>'. $f_question .'</h4>' . $f_answer;
					$returntext .= '<li><a class="faqhelp_preview" href="javascript:void(0);" data-con="'. $previewtext .'">'. $f_question .'</a></li>';
				}else{
					$returntext .= '
					<li><!--
						--><h4><a class="f_question" href="javascript:void(0);" pointer="'. $f_counter .'">'. $display_counter . '.&nbsp;' . $f_question .'</a></h4><!--
						--><div class="f_answer f_answer'. $f_counter .' com_none">'. $f_answer .'</div><!--
						--><div class="clear"></div><!--
					 --></li>
					';
				}
				$f_counter++;
            }
			$returntext .= '</ul>';
			
			if ($s == 1){
				$returntext .= '
				<p class="t-right"><a class="button" href="'. $cm->get_page_url(15, "page") .'">View All</a></p>
				<script type="text/javascript">
				$(document).ready(function(){
					$(".faqhelp_preview").mouseover(function(){
						var datacon = $(this).attr("data-con");
						$(this).previewer({
							trigger: "click",
							type: "text",
							text: datacon,
							containerCSS: {						
								"background-color": "#fff",
								"border-radius": "5px"
							},
							containerCLASS: "previewclass"
						});
					});
				});
				</script>
				';
			}else{
				$returntext .= '
				<script type="text/javascript">
					$(document).ready(function(){
						$(".main").on("click", ".f_question", function(){
							var p = $(this).attr("pointer");
							var answer = $(".f_answer" + p);
							if(answer.is( ":visible" )){
								$(answer).slideUp(300);
							}else {
								$(answer).slideDown(150);
							};
							$(document.body).trigger("sticky_kit:recalc");
						});
					});
				</script>
				';
			}
        }

        $returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);
  }
  //end
  
  //join our mailing list
  public function submit_join_our_mailing_list_form(){
	  if(($_POST['fcapi'] == "joinourmailing")){
		  global $db, $cm, $sdeml;
		  
		  $shortversion = round($_POST["shortversion"], 0);
		  $name = $_POST["jname"];
		  $email = $_POST["jemail"];
		  $email2 = $_POST["email2"];
		  $s = round($_POST["s"], 0);
		  
		  //create the session
		  $datastring = $cm->session_field_join();
		  $cm->create_session_for_form($datastring, $_POST);
		  //end
		  
		  if ($s == 3){
			  //from popup
			  $cm->form_post_check_valid_main('mailinglist', 1);
			  $red_pg = "pop-join-our-list";
			  $sorrypage = $cm->get_page_url(0, "popsorry");
			  $thankyoupage = $cm->get_page_url(0, "popthankyou");
		  }else{
			  $cm->form_post_check_valid_main('mailinglist');
			  $red_pg = $_SESSION["s_backpage"];
			  $sorrypage = $cm->site_url;
			  $thankyoupage = $cm->get_page_url(0, "thankyou");
		  }
		  
		  //checking		  
		  $cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
		  if ($email2 != ""){
			header('Location: '. $sorrypage .'');
			exit;
		  }
		  //end
		  
		  $cm->delete_session_for_form($datastring);
		  
		  //add to lead
			$form_type = 6;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => "",
				"message" => "",
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
		  
		  $msg = '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Join Our Mailing List</strong></td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
			  </tr>		
			
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
		  </table>          
		  ';
		  
		  //send email to admin
		  $mail_bcc = "";
		  $mail_to =  $cm->admin_email_to();
		  $mail_fm =  $cm->admin_email();
		  $mail_cc = "";
		  $mail_reply =  $cm->filtertextdisplay($email);
		  $mail_subject = "Join Our Mailing List";
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		  //end
		
		  $_SESSION["thnk"] = $msg;
		  header('Location: '. $thankyoupage);
		  exit;
	  }
  }
  
  public function display_join_our_mailing_list($argu = array()){
		global $cm;	   
		$s = round($argu["s"], 0); //s = 0 (sidebar and common cms), s = 2 (homepage content), s = 3 (popup)
		
		$datastring = $cm->session_field_join();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
	   
		if ($s == 2){
			//home page
			$returntext = '
			<h3 class="doublelinebothside"><span>Join our</span> Mailing List</h3>
			<form method="post" action="'. $cm->folder_for_seo .'" id="joinourmailing-ff" name="joinourmailing-ff">
			<label class="com_none" for="email2j">email2</label>
			<input class="finfo" id="email2j" name="email2" type="text" />
			<input id="jname" name="jname" type="hidden" value="User" />
			<input id="s" name="s" type="hidden" value="'. $s .'" />
			<input type="hidden" value="1" id="shortversion" name="shortversion" />
			<input type="hidden" id="fcapi" name="fcapi" value="joinourmailing" />
			<ul class="newsletterformhome clearfixmain">
				<li><label class="com_none" for="jemail">Email</label><input type="text" class="input" id="jemail" name="jemail" value="'. $email .'" placeholder="your email address here" /></li>
				<li class="submit"><button type="submit" class="button" value="Submit">Sign Up</button></li>
			</ul>
			<div class="fomrsubmit-result com_none"></div>
			
			<p>Be the first to know about incoming trades and price reductions!</p>
			</form>
			';
		}else{	   	   
		   $returntext = '<section class="section clearfixmain">';	   
		   $returntext .= '
		   <h3>Join our Mailing List</h3>
		   <form method="post" action="'. $cm->folder_for_seo .'" id="joinourmailing-ff" name="joinourmailing-ff">
		   <label class="com_none" for="email2j">email2</label>
		   <input class="finfo" id="email2j" name="email2" type="text" />
		   <input id="s" name="s" type="hidden" value="'. $s .'" />
		   <input type="hidden" value="0" id="shortversion" name="shortversion" />
		   <input type="hidden" id="fcapi" name="fcapi" value="joinourmailing" />
		   <ul class="form clearfixmain">
				<li><label class="com_none" for="jname">Name</label><input type="text" class="input" id="jname" name="jname" value="'. $name .'" placeholder="Name" /></li>
				<li><label class="com_none" for="jemail">Email</label><input type="text" class="input" id="jemail" name="jemail" value="'. $email .'" placeholder="Email Address" /></li>
				<li class="submit"><button type="submit" class="button" value="Submit">Submit</button></li>
		   </ul>
		   </form>
		   </section>
		   ';
		}
		return $returntext;
  }
	
	//mail-chimp 
  public function display_mailchimp_form($argu = array()){
	   global $cm;
	   $s = round($argu["s"], 0); //s = 0 (sidebar and common cms), s = 1 (page), s = 2 (footer)	   
	   
	   if ($s == 1){
		   $returntext = '
		   <!-- Begin MailChimp Signup Form -->
			<style type="text/css">
			#mc_embed_signup {background:none; clear:left; }
			</style>
			<div id="mc_embed_signup" class="clearfixmain">
				<form action="https://ngyachting.us12.list-manage.com/subscribe/post?u=de6006c711080e85329f32c9b&amp;id=62f50a8e95" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll"> 		
						
					<ul class="newsletterforpage clearfixmain">					
						<li><label class="com_none" for="mce-EMAIL">Email</label><input type="email" value="" name="EMAIL" class="input required email" id="mce-EMAIL" placeholder="Enter your Email"></li>
						<li><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></li>
				   </ul>
				   <div class="clear"></div>
				
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div> 
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;" aria-hidden="true">
					<label class="com_none" for="b_de6006c711080e85329f32c9b_62f50a8e95">Id</label>
					<input type="text" id="b_de6006c711080e85329f32c9b_62f50a8e95" name="b_de6006c711080e85329f32c9b_62f50a8e95" tabindex="-1" value="">
					</div>
					<div class="clear"></div>
				</div>
				</form>
			</div>
			
			<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
			<script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";}(jQuery));var $mcj = jQuery.noConflict(true);</script>
            <!--End mc_embed_signup-->
		   ';
	   }else{	   
		   $formtext = '
		   <style type="text/css">
			#mc_embed_signup {background:none; clear:left; }
			</style>
			<div id="mc_embed_signup" class="clearfixmain">
				<form action="https://ngyachting.us12.list-manage.com/subscribe/post?u=de6006c711080e85329f32c9b&amp;id=62f50a8e95" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll"> 		
						
					<ul class="form clearfixmain">
						<li>
							<p>Email Address <span class="requiredtext">*</span></p>
							<input type="email" value="" name="EMAIL" class="input required email" id="mce-EMAIL">
						</li>
						
						<li class="submit"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></li>
					</ul>
				   <div class="clear"></div>
				
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div> 
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;" aria-hidden="true">
					<input type="text" name="b_de6006c711080e85329f32c9b_62f50a8e95" tabindex="-1" value="">
					</div>
					<div class="clear"></div>
				</div>
				</form>
			</div>
			
			<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
			<script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]="EMAIL";ftypes[0]="email";fnames[1]="FNAME";ftypes[1]="text";fnames[2]="LNAME";ftypes[2]="text";}(jQuery));var $mcj = jQuery.noConflict(true);</script>
            <!--End mc_embed_signup-->
		   ';
		   
		   //sidebar
		  $returntext .= '
		  <section class="section">
		  <h3>Join our Mailing List</h3>
		  '. $formtext .'
		  </section>
		  ';
	   }
		   
	   return $returntext;
  }
  
  //Create Your Listing form
  public function submit_create_your_listing_form(){
	  if(($_POST['fcapi'] == "createyourlisting")){
		  global $db, $cm, $sdeml;
		  		  
		  $fname = $_POST["fname"];
		  $lname = $_POST["lname"];
		  $city = $_POST["city"];
		  $state = $_POST["state"];
		  $phone = $_POST["phone"];
		  $email = $_POST["email"];
		  
		  $boat_location = $_POST["boat_location"];
		  $boat_type = round($_POST["boat_type"], 0);
		  $manufacturer = $_POST["manufacturer"];
		  $model = $_POST["model"];
		  $boat_size = $_POST["boat_size"];
		  $boat_year = $_POST["boat_year"];
		  $condition = round($_POST["condition"], 0);
		  $comments = $_POST["comments"];		  
		  $email2 = $_POST["email2"];
		  
		  //create the session
		  $datastring = $cm->session_field_create_listing();
		  $cm->create_session_for_form($datastring, $_POST);
		  //end
		  
		  //checking
		  $red_pg = $_SESSION["s_backpage"];
		  $cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($city, '', 'City Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($state, '', 'State Name', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
		  
		  $cm->field_validation($manufacturer , '', 'Manufacturer ', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($boat_size, '', 'Size', $red_pg, '', '', 1, 'fr_');
		  $cm->field_validation($boat_year, '', 'Year', $red_pg, '', '', 1, 'fr_');
		  
		  if ($email2 != ""){
			header('Location: '. $cm->site_url .'');
			exit;
		  }
		  //end
		  
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
		  
		  $cm->form_post_check_valid_main('createlisting');
		  $cm->delete_session_for_form($datastring);
		  $boat_type_name = $cm->get_common_field_name('tbl_type', 'name', $boat_type);
		  $comments = nl2br($comments);
		  
		  $msg = '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Create My Listing</strong></td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Owner Detail:</strong></td>
			  </tr>	
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
			  </tr>	
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
			  </tr>	
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
			  </tr>	
			
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Description:</strong></td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Location:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">>Boat Type:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_type_name, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($manufacturer, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Size:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_size, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
			  </tr>
			  			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Condition (10 being Pristine):</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($condition, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comments:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
			  </tr>
		  </table>          
		  ';
		  
		  //send email to admin
		  $mail_bcc = "";
		  $mail_to =  $cm->admin_email_to();
		  $mail_fm =  $cm->admin_email();
		  $mail_cc = "";
		  $mail_reply =  $cm->filtertextdisplay($email);
		  $mail_subject = "Create My Listing";
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		  //end
		  
		  //send to user
		  $send_ml_id = 10;
		  $msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		  $mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		  $companyname = $cm->sitename;
		  
		  $fullname = $fname . ' ' . $lname;
		  $msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $msg);
		  $msg = str_replace("#companyname#", $companyname, $msg);
		  $mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
		
		  $mail_fm = $cm->admin_email();
		  $mail_to = $cm->filtertextdisplay($email);
		  $mail_cc = "";
		  $mail_reply = "";
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		  //end
		  
		  $_SESSION["thnk"] = $msg;
		  header('Location: '. $cm->folder_for_seo .'thankyou');
		  exit;
	  }
  }
  
  public function display_create_your_listing_form($s = 0){
	   global $cm, $yachtclass, $captchaclass;
	   
	   $datastring = $cm->session_field_create_listing();
	   $return_ar = $cm->collect_session_for_form($datastring);
		
	   foreach($return_ar AS $key => $val){
		   ${$key} = $val;
	   }
	   
	   $returntext = '
	   <form method="post" action="'. $cm->folder_for_seo .'" id="createyourlisting-ff" name="createyourlisting-ff">
	   <input class="finfo" id="email2" name="email2" type="text" />
	   <input type="hidden" id="fcapi" name="fcapi" value="createyourlisting" />
	   	   
	   ';
	   
	   $returntext .= '	
	   <div class="singleblock"> 
	   <div class="singleblock_heading"><span>Owner Detail</span></div> 
	   <div class="singleblock_box singleblock_box_h">	   
	   <ul class="form">	   		
			<li class="left">
				<p>First Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p>Last Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>
			
			<li class="left">
				<p>City <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="city" name="city" value="'. $city .'" class="input" />
			</li>
			<li class="right">
				<p>State <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="state" name="state" value="'. $state .'" class="input" />
			</li>
			
			<li class="left">
				<p>Phone</p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
			<li class="right">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
	   </ul>
	   <div class="clear"></div>
	   </div>
	   </div>
	   ';
	   
	   $returntext .= '	
	   <div class="singleblock"> 
	   <div class="singleblock_heading"><span>Boat Description</span></div> 
	   <div class="singleblock_box singleblock_box_h">	   
	   <ul class="form">	   		
			<li class="left">
				<p>Boat Location</p>
				<input type="text" id="boat_location" name="boat_location" value="'. $boat_location .'" class="input" />
			</li>
			<li class="right">
				<p>Boat Type</p>
				<select name="boat_type" id="boat_type" class="select">
					<option value="">Select</option>
					'.
					$yachtclass->get_type_combo($type_id,1, 1)
					.'
				</select>
			</li>
			
			<li class="left">
				<p>Manufacturer <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="manufacturer" name="manufacturer" value="'. $manufacturer .'" class="input" />
			</li>
			<li class="right">
				<p>Model</p>
				<input type="text" id="model" name="model" value="'. $model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Size <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_size" name="boat_size" value="'. $boat_size .'" class="input" />
			</li>
			<li class="right">
				<p>Year <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>
			
			<li class="left">
				<p>Condition (10 being Pristine)</p>
				<select name="condition" id="condition" class="select">
					<option value="">Select</option>
					'.
					$yachtclass->get_common_number_combo($condition, 10, 1)
					.'
				</select>
			</li>
			
			<li>
				<p>Comments</p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
	   </ul>
	   <div class="clear"></div>
	   </div>
	   </div>
	   
	   <div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
       <input type="submit" value="Submit My Boat Listing" class="button" />
       </div>
	   </form>';
	   return $returntext;
  }
  
  //box
  public function display_box_content($boxid){
	  global $cm;
	  $returntext = '';
	  $boxcontent = $cm->get_common_field_name('tbl_box_content', 'pdes', $boxid);
	  $boxcontent = $cm->passed_content_for_shortcode($boxcontent);			
	  return $boxcontent;
  }
  
  //button
  public function display_button($argu = array()){
		$buttontext = $argu["text"];
		$buttonlink = $argu["linkurl"];
		$buttonnewwindow = round($argu["newwindow"], 0);
				
		$openw = '';
		if ($buttonnewwindow == 1){
			$openw = ' target="_blank"';
		}
		$returntext = '<a href="'. $buttonlink .'" class="button"'. $openw .'>'. $buttontext .'</a>';
		return $returntext;
  }
  
  //sell your boat
  	public function sell_your_boat_form_old($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$formoption = round($argu["formoption"], 0);
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_sell_boat();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$country_id = round($country_id);
		if ($country_id == 0){ $country_id = 1; }	  
	  
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="sell-boat-ff" name="sell-boat-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="sellyourboat" />
		<input type="hidden" id="formoption" name="formoption" value="'. $formoption .'" />
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
		';
	  
	   $returntext .= '
	   <div class="singleblock_box">
	   		<ul class="form">
                                        
                    <li class="left">
                        <p>Name <span class="requiredfieldindicate">*</span></p>
                        <input type="text" class="input" id="name" name="name" value="'. $name .'" />
                    </li>
                    
                    <li class="right">
                        <p>Email Address <span class="requiredfieldindicate">*</span></p>
                        <input type="text" class="input" id="email" name="email" value="'. $email .'" />
                    </li>
                    
                    <li class="left">
                        <p>Phone <span class="requiredfieldindicate">*</span></p>
                        <input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
                    </li>                   
            </ul>
			
			<ul class="form">
                    <li class="left">
                        <p>Length</p>
                        <input type="text" class="input" id="lengthinfo" name="lengthinfo" value="'. $lengthinfo .'" />
                    </li>
                    
                    <li class="right">
                        <p>Manufacturer</p>
                        <input type="text" class="input" id="manufacturer" name="manufacturer" value="'. $manufacturer .'" />
                    </li>
                    
                    <li class="left">
                        <p>Model</p>
                        <input type="text" class="input" id="model" name="model" value="'. $model .'" />
                    </li>
                    
                    <li class="right">
                        <p>Engines</p>
                        <input type="text" class="input" id="engines" name="engines" value="'. $engines .'" />
                    </li>
					
					<li class="left">
                        <p>Overall Condition</p>
                        <input type="text" class="input" id="overallcondition" name="overallcondition" value="'. $overallcondition .'" />
                    </li>
					
					<li class="right">
                        <p>What do you feel your boat is worth?</p>
                        <input type="text" class="input" id="boatworth" name="boatworth" value="'. $boatworth .'" />
                    </li>
                    
					<li class="left">
                        <p>Location of Boat</p>
                        <input type="text" class="input" id="boatlocation" name="boatlocation" value="'. $boatlocation .'" />
                    </li>
				</ul>
				
				<ul class="form">	
                    <li>
                        <p>Message</p>
                        <textarea rows="1" cols="1" id="message" name="message" class="comments">'. $message .'</textarea>
                    </li>
					
					<li>'. $captchaclass->call_captcha(). '</li>
                    
                    <li class="submit">
                        <button type="submit" class="button" value="Submit">Submit</button>
                    </li>
                </ul>
				
	   		<div class="clear"></div>
	   </div>
	   ';	
	   
	   $returntext .= '	   
	   </form>';
	   
	   $returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#sell-boat-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}							
				
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';
	   	      
	   return $returntext;
  }
	  
  	public function submit_sell_your_boat_form_old(){		
		if(($_POST['fcapi'] == "sellyourboat")){
			global $db, $cm, $sdeml;
						
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$lookingto = $_POST["lookingto"];
			$lengthinfo = $_POST["lengthinfo"];
			$manufacturer = $_POST["manufacturer"];
			$model = $_POST["model"];
			$engines = $_POST["engines"];
			$overallcondition = $_POST["overallcondition"];
			$boatworth = $_POST["boatworth"];
			$boatlocation = $_POST["boatlocation"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			$formoption = round($_POST["formoption"], 0);
			$pgid = round($_POST["pgid"], 0);
			
			//create the session
			$datastring = $cm->session_field_sell_boat();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
		    $red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'phone', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('sellyourboat');
			//$cm->delete_session_for_form($datastring);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//send email to admin
			$message = nl2br($message);		
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
								
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lengthinfo, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($manufacturer, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engines:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engines, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Overall Condition:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($overallcondition, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">What do you feel your boat is worth?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boatworth, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location of Boat:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boatlocation, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
				</tr>				
			</table>          
			';
			
			//add to lead
			$form_type = 4;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			$send_ml_id = 17;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#sellboatsubmission#", $emailmessage, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 18;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	public function sell_your_boat_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_webuyboat();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($bottom_paint == 1){
			$bottom_paint1 = ' checked="checked"';
			$bottom_paint2 = '';
		}else{
			$bottom_paint2 = ' checked="checked"';
			$bottom_paint1 = '';
		}
		
		$originalengineyear = '';
		if ($original_engine_year == 1){
			$originalengineyear = ' checked="checked"';
		}
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="sell-boat-ff" name="sell-boat-ff" enctype="multipart/form-data">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="sellyourboatsubmit" />
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />	   
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Personal Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">	   		
			<li class="left">
				<p>First Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p>Last Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>
			
			<li class="left">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
			<li class="right">
				<p>Phone</p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Boat Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">	   		
			<li class="left">
				<p>Make <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>			
		</ul>
		
		<ul class="form">	   		
			<li class="left">
				<p>Engine Make</p>
				<input type="text" id="boat_engine_make" name="boat_engine_make" value="'. $boat_engine_make .'" class="input" />
			</li>
			<li class="right">
				<p>Engine Year</p>
				<div class="leftfield2"><input type="text" id="boat_engine_year" name="boat_engine_year" value="'. $boat_engine_year .'" class="input" /></div>
				<div class="rightfield2">Original: <input class="checkbox" type="checkbox" id="original_engine_year" name="original_engine_year" value="1"'. $originalengineyear .' /></div>
			</li>
			
			<li class="left">
				<p>Hours</p>
				<input type="text" id="boat_hours" name="boat_hours" value="'. $boat_hours .'" class="input" />
			</li>
			<li class="right">
				<p>Equipment</p>
				<input type="text" id="boat_equipment" name="boat_equipment" value="'. $boat_equipment .'" class="input" />
			</li>
			
			<li>Bottom Paint: <input type="radio" class="radiobutton2" id="bottom_paint1" name="bottom_paint" value="1"'. $bottom_paint1 .' /> Yes <input type="radio" class="radiobutton2" id="bottom_paint2" name="bottom_paint" value="0"'. $bottom_paint2 .' /> No</li>			
			
			<li>
				<p>Any Issues</p>
				<textarea rows="1" cols="1" id="any_issues" name="any_issues" class="comments">'. $any_issues .'</textarea>
			</li>
			
			<li><strong>Attach pictures of your boat</strong></li>
			<li>Please Note: The total size of your attachment(s) should not exceed <span class="bold requiredfieldindicate">'. $cm->max_upload_size_form .' MB</span>.</li>
			<li><input type="file" name="myfile1" id="myfile1" class="input" /></li>
			<li><input type="file" name="myfile2" id="myfile2" class="input" /></li>
			<li><input type="file" name="myfile3" id="myfile3" class="input" /></li>
			<li><input type="file" name="myfile4" id="myfile4" class="input" /></li>
			<li><input type="file" name="myfile5" id="myfile5" class="input" /></li>
					
			<li>
				<p>If you have your boat listed online, simply fill out the required info and copy/paste the link below</p>
				<textarea rows="1" cols="1" id="boat_online" name="boat_online" class="comments">'. $boat_online .'</textarea>
			</li>
			
			<li>
				<p>Shopping for a new boat? Tell us what you’re looking for!</p>
				<textarea rows="1" cols="1" id="boat_shopping" name="boat_shopping" class="comments">'. $boat_shopping .'</textarea>
			</li>

			<li>
				<input type="checkbox" id="newsletter_subscribe" name="newsletter_subscribe" value="1" class="checkbox" checked="checked" /> <strong>Be the first to know about incoming trades!</strong>
			</li>
		
		</ul>
		
		<div class="clear"></div>
		</div>
		</div>
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#sell-boat-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "fname");
				}
				
				if (!field_validation_border("lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "lname");
				}
				
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_model");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}
												
				if (all_ok == "n"){
					return false;
				}
				return true;
			});			
			
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit sell your boat form
	public function submit_sell_your_boat_form(){
		if(($_POST['fcapi'] == "sellyourboatsubmit")){
			global $db, $cm, $sdeml;
			
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_engine_make = $_POST["boat_engine_make"];
			$boat_engine_year = $_POST["boat_engine_year"];
			
			$original_engine_year = round($_POST["original_engine_year"], 0);
			
			$boat_hours = $_POST["boat_hours"];
			$boat_equipment = $_POST["boat_equipment"];
			$bottom_paint = round($_POST["bottom_paint"], 0);
				
			$any_issues = $_POST["any_issues"];	
			$boat_online = $_POST["boat_online"];
			$boat_shopping = $_POST["boat_shopping"];
			$newsletter_subscribe = round($_POST["newsletter_subscribe"], 0);
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			
			//create the session
			$datastring = $cm->session_field_webuyboat();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			
			$cm->field_validation($boat_make, '', 'Make', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_model, '', 'Model', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Year', $red_pg, '', '', 1, 'fr_');				
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//check fileupload size
			$uploaded_max_file_size = $cm->max_upload_size_form * 1024 * 1024;
			$uploaded_size = 0;
			
			for($kk = 1; $kk <=5; $kk++){
				$filename = $_FILES['myfile' . $kk ]['name'];
				if ($filename != ""){
					$cr_size = $_FILES['myfile' . $kk ]['size'];
					$uploaded_size = $uploaded_size + $cr_size;
				}
			}
			
			if ($uploaded_size > $uploaded_max_file_size){
				$_SESSION["fr_postmessage"] = 'Uploaded file size greater than '. $cm->max_upload_size_form .' MB. Please reduce your file size and upload again.';
				header('Location: ' . $red_pg);
				exit;
			}
			//end	
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end		
			
			////$cm->form_post_check_valid_main('sellyourboat');
			$cm->delete_session_for_form($datastring);
						
			$any_issues = nl2br($any_issues);
			$boat_online = nl2br($boat_online);
			$boat_shopping = nl2br($boat_shopping);
			
			$original_engine_year_text = $cm->set_yesyno_field($original_engine_year);
			$bottom_paint_text = $cm->set_yesyno_field($bottom_paint);

			$newsletter_subscribe_text = $cm->set_yesyno_field($newsletter_subscribe);			
			
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>We Buy Boats Form</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Personal Information:</strong></td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>			
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engine_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engine_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Year Original:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $original_engine_year_text .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Equipment:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_equipment, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Bottom Paint:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $bottom_paint_text .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any Issues:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($any_issues, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Online Boat Details:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_online, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Shopping for a new boat:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_shopping, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_subscribe_text .'</td>
				</tr>

			</table>          
			';
			
			$attachment_ar = array();
			for($kk = 1; $kk <= 5; $kk++){
				$filename = $_FILES['myfile' . $kk ]['name'];
				if ($filename!=""){
					$attachment_ar[$kk-1]["name"] =  $cm->filtertext($filename);
					$attachment_ar[$kk-1]["path"] = $_FILES['myfile' . $kk]['tmp_name'];
				}
			}
			
			$fullname = $fname . ' ' . $lname;
			
			//add to lead
			$form_type = 4;
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			//end
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();			
			
			//send email to admin
			$send_ml_id = 17;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#sellboatsubmission#", $emailmessage, $ad_msg);
			//$ad_msg = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $ad_msg);
			
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			//$ad_mail_subject = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender, $attachment_ar);			
			//end
			
			//send to user
			$send_ml_id = 18;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			//$fr_msg = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			//$fr_mail_subject = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			//$cm->thankyouredirect(2);
			$_SESSION["s_pgid"] = $pgid;
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}
	
	//we buy boat form
	public function we_buy_boat_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_webuyboat();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($bottom_paint == 1){
			$bottom_paint1 = ' checked="checked"';
			$bottom_paint2 = '';
		}else{
			$bottom_paint2 = ' checked="checked"';
			$bottom_paint1 = '';
		}
		
		$originalengineyear = '';
		if ($original_engine_year == 1){
			$originalengineyear = ' checked="checked"';
		}
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="webuyboat-ff" name="webuyboat-ff" enctype="multipart/form-data">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="webuyboatsubmit" />
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />		   
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Personal Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">	   		
			<li class="left">
				<p>First Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p>Last Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>
			
			<li class="left">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
			<li class="right">
				<p>Phone</p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Boat Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">	   		
			<li class="left">
				<p>Make <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>			
		</ul>
		
		<ul class="form">	   		
			<li class="left">
				<p>Engine Make</p>
				<input type="text" id="boat_engine_make" name="boat_engine_make" value="'. $boat_engine_make .'" class="input" />
			</li>
			<li class="right">
				<p>Engine Year</p>
				<div class="leftfield2"><input type="text" id="boat_engine_year" name="boat_engine_year" value="'. $boat_engine_year .'" class="input" /></div>
				<div class="rightfield2">Original: <input class="checkbox" type="checkbox" id="original_engine_year" name="original_engine_year" value="1"'. $originalengineyear .' /></div>
			</li>
			
			<li class="left">
				<p>Hours</p>
				<input type="text" id="boat_hours" name="boat_hours" value="'. $boat_hours .'" class="input" />
			</li>
			<li class="right">
				<p>Equipment</p>
				<input type="text" id="boat_equipment" name="boat_equipment" value="'. $boat_equipment .'" class="input" />
			</li>
			
			<li>Bottom Paint: <input type="radio" class="radiobutton2" id="bottom_paint1" name="bottom_paint" value="1"'. $bottom_paint1 .' /> Yes <input type="radio" class="radiobutton2" id="bottom_paint2" name="bottom_paint" value="0"'. $bottom_paint2 .' /> No</li>			
			
			<li>
				<p>Any Issues</p>
				<textarea rows="1" cols="1" id="any_issues" name="any_issues" class="comments">'. $any_issues .'</textarea>
			</li>
			
			<li><strong>Attach pictures of your boat</strong></li>
			<li>Please Note: The total size of your attachment(s) should not exceed <span class="bold requiredfieldindicate">'. $cm->max_upload_size_form .' MB</span>.</li>
			<li><input type="file" name="myfile1" id="myfile1" class="input" /></li>
			<li><input type="file" name="myfile2" id="myfile2" class="input" /></li>
			<li><input type="file" name="myfile3" id="myfile3" class="input" /></li>
			<li><input type="file" name="myfile4" id="myfile4" class="input" /></li>
			<li><input type="file" name="myfile5" id="myfile5" class="input" /></li>
					
			<li>
				<p>If you have your boat listed online, simply fill out the required info and copy/paste the link below</p>
				<textarea rows="1" cols="1" id="boat_online" name="boat_online" class="comments">'. $boat_online .'</textarea>
			</li>
			
			<li>
				<p>Shopping for a new boat? Tell us what you’re looking for!</p>
				<textarea rows="1" cols="1" id="boat_shopping" name="boat_shopping" class="comments">'. $boat_shopping .'</textarea>
			</li>

			<li>
				<input type="checkbox" id="newsletter_subscribe" name="newsletter_subscribe" value="1" class="checkbox" checked="checked" /> <strong>Be the first to know about incoming trades!</strong>
			</li>
		
		</ul>
		
		<div class="clear"></div>
		</div>
		</div>
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#webuyboat-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "fname");
				}
				
				if (!field_validation_border("lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "lname");
				}
				
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_model");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}
												
				if (all_ok == "n"){
					return false;
				}
				return true;
			});			
			
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit we buy boat form
	public function submit_we_buy_boat_form(){
		if(($_POST['fcapi'] == "webuyboatsubmit")){
			global $db, $cm, $sdeml;
			
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_engine_make = $_POST["boat_engine_make"];
			$boat_engine_year = $_POST["boat_engine_year"];
			
			$original_engine_year = round($_POST["original_engine_year"], 0);
			
			$boat_hours = $_POST["boat_hours"];
			$boat_equipment = $_POST["boat_equipment"];
			$bottom_paint = round($_POST["bottom_paint"], 0);
				
			$any_issues = $_POST["any_issues"];	
			$boat_online = $_POST["boat_online"];
			$boat_shopping = $_POST["boat_shopping"];
			$newsletter_subscribe = round($_POST["newsletter_subscribe"], 0);
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			
			//create the session
			$datastring = $cm->session_field_webuyboat();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			
			$cm->field_validation($boat_make, '', 'Make', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_model, '', 'Model', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Year', $red_pg, '', '', 1, 'fr_');				
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//check fileupload size
			$uploaded_max_file_size = $cm->max_upload_size_form * 1024 * 1024;
			$uploaded_size = 0;
			
			for($kk = 1; $kk <=5; $kk++){
				$filename = $_FILES['myfile' . $kk ]['name'];
				if ($filename != ""){
					$cr_size = $_FILES['myfile' . $kk ]['size'];
					$uploaded_size = $uploaded_size + $cr_size;
				}
			}
			
			if ($uploaded_size > $uploaded_max_file_size){
				$_SESSION["fr_postmessage"] = 'Uploaded file size greater than '. $cm->max_upload_size_form .' MB. Please reduce your file size and upload again.';
				header('Location: ' . $red_pg);
				exit;
			}
			//end	
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end		
			
			////$cm->form_post_check_valid_main('sellyourboat');
			$cm->delete_session_for_form($datastring);
						
			$any_issues = nl2br($any_issues);
			$boat_online = nl2br($boat_online);
			$boat_shopping = nl2br($boat_shopping);
			
			$original_engine_year_text = $cm->set_yesyno_field($original_engine_year);
			$bottom_paint_text = $cm->set_yesyno_field($bottom_paint);

			$newsletter_subscribe_text = $cm->set_yesyno_field($newsletter_subscribe);			
			
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>We Buy Boats Form</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Personal Information:</strong></td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>			
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engine_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engine_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Year Original:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $original_engine_year_text .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Equipment:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_equipment, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Bottom Paint:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $bottom_paint_text .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any Issues:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($any_issues, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Online Boat Details:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_online, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Shopping for a new boat:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_shopping, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_subscribe_text .'</td>
				</tr>

			</table>          
			';
			
			$attachment_ar = array();
			for($kk = 1; $kk <= 5; $kk++){
				$filename = $_FILES['myfile' . $kk ]['name'];
				if ($filename!=""){
					$attachment_ar[$kk-1]["name"] =  $cm->filtertext($filename);
					$attachment_ar[$kk-1]["path"] = $_FILES['myfile' . $kk]['tmp_name'];
				}
			}
			
			$fullname = $fname . ' ' . $lname;
			
			//add to lead
			$form_type = 15;
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			//end
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			
			//send email to admin
			$send_ml_id = 34;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#webuyboatsubmission#", $emailmessage, $ad_msg);
			$ad_msg = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $ad_msg);
			
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			$ad_mail_subject = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender, $attachment_ar);			
			//end
			
			//send to user
			$send_ml_id = 35;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			$fr_msg = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			$fr_mail_subject = str_replace("#locationname#", $cm->filtertextdisplay($locationname, 1), $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			//$cm->thankyouredirect(2);
			$_SESSION["s_pgid"] = $pgid;
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}
	
	//contact us page form
	public function contact_page_pop(){
		global $db, $cm;		
		
		$contact_page_id = 6;
		$brow = $this->default_page_info($contact_page_id);
		$f_pdata = $brow["file_data"];
		$f_pdata = $cm->passed_content_for_shortcode($f_pdata);
		
		$returntext = '
		<div id="overlay" class="animated popcontact hide">
			<a class="fc-close-contact" c="overlay" href="javascript:void(0);"><i class="fas fa-times"></i><span class="com_none">Close</span></a>
			'. $f_pdata .'
		</div>
		';
		
		$returntext .= '
		<script>
		$(document).ready(function(){
			$(".fc-open-contact").click(function(){
				$("#overlay").fadeIn(300);
			});
			
			$(".fc-close-contact").click(function(){
				var c = $(this).attr("c");
				$("#" + c).fadeOut(300);
			});
		});
		</script>
		';
		
		return $returntext;
	}
  
	//contact form
	public function inquiry_type_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "", "oth" => 0);
		$val_ar[] = array("name" => "Yacht Sales", "oth" => 0);
		$val_ar[] = array("name" => "Brokerage", "oth" => 0);
		$val_ar[] = array("name" => "Service", "oth" => 0);
		$val_ar[] = array("name" => "Storage", "oth" => 0);
		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_inquiry_type_checkbox(){
        global $db;
		$val_ar = json_decode($this->inquiry_type_val());		
		$returntext = '';
  
        foreach($val_ar as $ar_key => $val_row){
            $cname = $val_row->name;
			$oth = $val_row->oth;
			if ($ar_key > 0){				
				$returntext .= '<li><input class="checkbox" type="checkbox" name="inquiry_type_id[]" value="'. $ar_key .'"  id="inquiry_type_id'. $ar_key .'" /> '. $cname .'</li>';
			}
        }
		
		if ($returntext != ""){
			$returntext = '<ul class="formcol">' . $returntext . '</ul>';
		}
				
		return $returntext;
    }
	
	public function get_inquiry_type_ind_value($inquiry_type_id){
        global $db;
		$val_ar = json_decode($this->inquiry_type_val());		
		$returntext = $val_ar[$inquiry_type_id]->name;
		return $returntext;
    }
	
  	public function display_contact_form($argu = array()){
		global $db, $cm, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$shortversion = round($argu["shortversion"], 0);
		$withoutbg = round($argu["withoutbg"], 0);
		$template = round($argu["template"], 0);
		
		$withoutbg_class = "";
		if ($withoutbg == 1){
			$withoutbg_class = " withoutbg";
		}
		
		$datastring = $cm->session_field_contact();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="contact_ff" name="con_ff">
		<label class="com_none" for="email2con">email2</label>
		<input type="hidden" value="'. $shortversion .'" id="shortversion" name="shortversion" />
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input class="finfo" id="email2con" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="contactform" />
		';
		
		if ($shortversion == 1){
			$returntext = '
			<h3>Contact Us</h3>
			<div class="leftrightcolsection notopborder clearfix">
			'. $formstart .'
			
			<ul class="form">
				<li>
					<label class="com_none" for="con_name">Name</label>
					<input type="text" id="con_name" name="name" value="'. $name .'" placeholder="Your Name" title="Enter Your Name" class="input nameicon" />
				</li>
				
				<li>
					<label class="com_none" for="con_phone">Phone</label>
					<input type="text" id="con_phone" name="phone" value="'. $phone .'" placeholder="Phone" class="input phoneicon" />
				</li>
				
				<li>
					<label class="com_none" for="con_email">Email</label>
					<input type="text" id="con_email" name="email" value="'. $email .'" placeholder="Email" class="input emailicon" />
				</li>
				
				<li>
					<label class="com_none" for="con_contact_subject">Contact Subject</label>
					<input type="text" id="con_contact_subject" name="contact_subject" value="'. $contact_subject .'" placeholder="Subject" class="input subjecticon" />
				</li>
				
				<li>					
					<label class="com_none" for="con_message">Comments</label>
					<textarea name="message" id="con_message" rows="1" cols="1" placeholder="Comments" class="comments commenticon">'. $message .'</textarea>
				</li>
				
				<li>'. $captchaclass->call_captcha(). '</li>
				
				<li>
					<button type="submit" class="button" value="Submit">Submit</button>
				</li>
				
				<li class="fomrsubmit-result com_none"></li>
			</ul>
			
			</form>
			<div class="clear"></div>
			</div>
			';
		}else{
			
			if ($template == 1){
				$returntext = '
				'. $formstart .'
				<h3>Online Inquiry</h3>
				<div class="rowflex">
					<div class="col-50 pr-3">
						<label class="com_none" for="con_name">Name</label><input type="text" class="input" id="con_name" name="name" value="'. $name .'" placeholder="Name *" />
						<label class="com_none" for="con_phone">Phone</label><input type="text" class="input" id="con_phone" name="phone" value="'. $phone .'" placeholder="Phone *" />
						<label class="com_none" for="con_email">Email</label><input type="text" class="input" id="con_email" name="email" value="'. $email .'" placeholder="Email *" />
						<label class="com_none" for="con_contact_subject">Contact Subject</label><input type="text" class="input" id="con_contact_subject" name="contact_subject" value="'. $contact_subject .'" placeholder="Subject" />
					</div>
					<div class="col-50">
						<label class="com_none" for="con_message">Comments</label>
						<textarea rows="1" cols="1" id="con_message" name="message" class="comments" placeholder="Comments *">'. $message .'</textarea>
						
						<div>'. $captchaclass->call_captcha(). '</div>
						
						<input type="submit" value="Submit">
					</div>
				</div>
				</form>
				';
			}else{
		
				$returntext = '
				<div class="singleblock_box'. $withoutbg_class .'">
					'. $formstart .'
					
					<ul class="form">				          
						<li>
							<p><label for="con_name">Name</label> <span class="requiredtext">*</span></p>
							<input type="text" class="input" id="con_name" name="name" value="'. $name .'" />
						</li>
						
						<li>
							<p><label for="con_phone">Phone</label> <span class="requiredtext">*</span></p>
							<input type="text" class="input" id="con_phone" name="phone" value="'. $phone .'" />
						</li>
						
						<li>
							<p><label for="con_email">Email</label> <span class="requiredtext">*</span></p>
							<input type="text" class="input" id="con_email" name="email" value="'. $email .'" />
						</li>
				
						<li>
							<p><label for="con_contact_subject">Subject</label></p>
							<input type="text" class="input" id="con_contact_subject" name="contact_subject" value="'. $contact_subject .'" />
						</li>
						
				
						<li>
							<p><label for="con_message">Comments</label> <span class="requiredtext">*</span></p>
							<textarea rows="1" cols="1" id="con_message" name="message" class="comments">'. $message .'</textarea>
						</li> 
						
						<li>'. $captchaclass->call_captcha(). '</li>     
				
						<li class="submit">
							<button type="submit" class="button" value="Submit">Submit</button>
						</li>
						
						<li>
							<p><span class="requiredtext">*</span> = Mandatory fields</p>            
						</li>
					</ul>
					</form>
					<div class="clear"></div>
				</div>
				';
			}
		}
		
		return $returntext;
	}
	
	//submit contact form
	public function submit_contact_form(){
		if(($_POST['fcapi'] == "contactform")){					
			global $db, $cm, $leadclass, $sdeml;			
						
			$shortversion = round($_POST["shortversion"], 0);
			$pgid = round($_POST["pgid"], 0);
			$name = $_POST["name"];
			$phone = $_POST["phone"];
			$contact_subject = $_POST["contact_subject"];
			$email = $_POST["email"];
			$message = $_POST["message"];
			$promo_code = $_POST["promo_code"];
			$email2 = $_POST["email2"];
			
			//create the session
			$datastring = $cm->session_field_contact();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			$red_pg = $_SESSION["s_backpage"];
			
			//field data checking	
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');			
			//$cm->field_validation($contact_subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Comments', $red_pg, '', '', 1, 'fr_');

			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('contactform');
			$cm->delete_session_for_form($datastring);
			
			//add to lead
			$form_type = 1;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $message,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//create email
			$message = nl2br($message);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//send to admin
			$contactsubmission = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Contact Email</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($contact_subject, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comments:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
				</tr>
			</table>          
			';
			
			$send_ml_id = 21;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#contactsubmission#", $contactsubmission, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);			
			
			$mail_fm =  $cm->admin_email();
			$mail_to =  $cm->admin_email_to();
			$mail_bcc = "";
			$mail_reply =  $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
						
			//send to user
			$send_ml_id = 1;
			if ($promo_code != ""){
				$msg = $db->total_record_count("select pdes2 as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			}else{
				$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			}
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			
			
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
			$msg = str_replace("#promocode#", $cm->filtertextdisplay($promo_code), $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$msg = str_replace("#companyphone#", $companyphone, $msg);
			$msg = str_replace("#companyemail#", $companyemail, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
			
			$_SESSION["s_pgid"] = $pgid;
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}

	//display boat finder form
	public function display_boat_finder_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$shortversion = round($argu["shortversion"], 0);
		
		$datastring = $cm->session_field_boat_finder();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="boatfinder-ff" name="boatfinder-ff">
		<label class="com_none" for="email2">email2</label>
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="boatfinder" />		   
		';
		
		$formend = '</form>';
		
		if ($shortversion == 1){
			//Full form - for sidebar
			$returntext = $formstart . '
			<h2 class="sidebartitle">Find My Boat</h2>
			<div class="leftrightcolsection notopborder clearfixmain">			
			<ul class="form">
				<li><strong>My Information</strong></li>   		
				<li>
					<p><label for="name">Name</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="name" name="name" value="'. $name .'" class="input" />
				</li>			
				<li>
					<p><label for="city">City</label></p>
					<input type="text" id="city" name="city" value="'. $city .'" class="input" />
				</li>
				
				<li>
					<p><label for="state">State</label></p>
					<input type="text" id="state" name="state" value="'. $state .'" class="input" />
				</li>			
				<li>
					<p><label for="phone">Phone</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
				</li>
				
				<li>
					<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="email" name="email" value="'. $email .'" class="input" />
				</li>
			</ul>
			';
			
			$returntext .= '
			<ul class="form">
				<li><strong>Boat Requirements</strong></li>	   		
				<li>
					<p><label for="boat_budget">Budget</label></p>
					<input type="text" id="boat_budget" name="boat_budget" value="'. $boat_budget .'" class="input" />
				</li>
				<li>
					<p><label for="boat_category">Boat Category</label></p>
					<select name="boat_category" id="boat_category" class="select">
						<option value="">Select</option>
						'.
						$yachtclass->get_category_combo($boat_category, 1, 1)
						.'
					</select>
				</li>
				
				<li>
					<p><label for="manufacturer">Manufacturer</label></p>
					<input type="text" id="manufacturer" name="manufacturer" value="'. $manufacturer .'" class="input" />
				</li>
				<li>
					<p><label for="model">Model</label></p>
					<input type="text" id="model" name="model" value="'. $model .'" class="input" />
				</li>
				
				<li>
					<p><label for="boat_size">Size</label></p>
					<input type="text" id="boat_size" name="boat_size" value="'. $boat_size .'" class="input" />
				</li>
				<li>
					<p><label for="boat_year">Year</label></p>
					<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
				</li>			
				<li>
					<p><label for="comments">Special Request</label></p>
					<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
				</li>
			</ul>
			';
			
			$returntext .= '
			<ul class="form">
				<li>'. $captchaclass->call_captcha(). '</li>
				<li>
					<button type="submit" class="button" value="Submit">Submit</button>
				</li>
			</ul>
			';
			
			$returntext .= '</div></form>';
			
		}elseif ($shortversion == 2){
			//Short form - for sidebar
			
			$returntext = $formstart . '
			<h2 class="sidebartitle">Boat to Buy</h2>
			<div class="leftrightcolsection notopborder clearfixmain">			
			<ul class="form">
				<li>
					<p><label for="name">Name</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="name" name="name" value="'. $name .'" class="input" />
				</li>
				<li>
					<p><label for="phone">Phone</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
				</li>
				<li>
					<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="email" name="email" value="'. $email .'" class="input" />
				</li>
			</ul>
			';
			
			$returntext .= '
			<ul class="form">
				<li>
					<p><label for="manufacturer">Manufacturer</label></p>
					<input type="text" id="manufacturer" name="manufacturer" value="'. $manufacturer .'" class="input" />
				</li>
				<li>
					<p><label for="model">Model</label></p>
					<input type="text" id="model" name="model" value="'. $model .'" class="input" />
				</li>
				<li>
					<p><label for="boat_year">Year</label></p>
					<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
				</li>	
				<li>
					<p><label for="boat_budget">Budget</label></p>
					<input type="text" id="boat_budget" name="boat_budget" value="'. $boat_budget .'" class="input" />
				</li>							
				<li>
					<p><label for="comments">Special Request</label></p>
					<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
				</li>
			</ul>
			';
			
			$returntext .= '
			<ul class="form">
				<li>'. $captchaclass->call_captcha(). '</li>
				<li>
					<button type="submit" class="button" value="Submit">Submit</button>
				</li>
			</ul>
			';
			
			$returntext .= '</div></form>';
		}else{
			//Full form - for CMS Page
			$returntext = $formstart . '	
			<div class="singleblock clearfixmain"> 
			<div class="singleblock_heading"><span>My Information</span></div> 
			<div class="singleblock_box singleblock_box_h clearfixmain">	   
			<ul class="form">					   		
				<li class="left">
					<p><label for="name">Name</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="name" name="name" value="'. $name .'" class="input" />
				</li>			
				<li class="right">
					<p><label for="city">City</label></p>
					<input type="text" id="city" name="city" value="'. $city .'" class="input" />
				</li>
				
				<li class="left">
					<p><label for="state">State</label></p>
					<input type="text" id="state" name="state" value="'. $state .'" class="input" />
				</li>			
				<li class="right">
					<p><label for="phone">Phone</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
				</li>
				
				<li class="left">
					<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="email" name="email" value="'. $email .'" class="input" />
				</li>
			</ul>
			</div>
			</div>
			';
			
			$returntext .= '	
			<div class="singleblock clearfixmain"> 
			<div class="singleblock_heading"><span>Boat Requirements</span></div> 
			<div class="singleblock_box singleblock_box_h clearfixmain">	   
			<ul class="form">	   		
				<li class="left">
					<p><label for="boat_budget">Budget</label></p>
					<input type="text" id="boat_budget" name="boat_budget" value="'. $boat_budget .'" class="input" />
				</li>
				<li class="right">
					<p><label for="boat_category">Boat Category</label></p>
					<select name="boat_category" id="boat_category" class="select">
						<option value="">Select</option>
						'.
						$yachtclass->get_category_combo($boat_category, 1, 1)
						.'
					</select>
				</li>
				
				<li class="left">
					<p><label for="manufacturer">Manufacturer</label></p>
					<input type="text" id="manufacturer" name="manufacturer" value="'. $manufacturer .'" class="input" />
				</li>
				<li class="right">
					<p><label for="model">Model</label></p>
					<input type="text" id="model" name="model" value="'. $model .'" class="input" />
				</li>
				
				<li class="left">
					<p><label for="boat_size">Size</label></p>
					<input type="text" id="boat_size" name="boat_size" value="'. $boat_size .'" class="input" />
				</li>
				<li class="right">
					<p><label for="boat_year">Year</label></p>
					<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
				</li>
			
				<li>
					<p><label for="comments">Special Request</label></p>
					<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
				</li>
			</ul>
			</div>
			</div>
			
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
			';
			
			$returntext .= '
			<div class="singleblock">
			<input type="submit" value="Submit Form" class="button" />
			</div>
			'. $formend .'
			';
		}
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				$(".main").on("submit", "#boatfinder-ff", function(){
					var all_ok = "y";
					var setfocus = "n";
					
					if (!field_validation_border("name", 1, 1)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "name");
					}
					
					if (!field_validation_border("email", 2, 1)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "email");
					}
					
					if (!field_validation_border("phone", 1, 1)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "phone");
					}
			
					if (all_ok == "n"){            
						return false;
					}
					return true;
				});			   
			});
		</script>
		';		
		return $returntext;
	}
	
	//submit boat finder
	public function submit_boat_finder_form(){		
		if(($_POST['fcapi'] == "boatfinder")){
			global $db, $cm, $sdeml;
					
			$name = $_POST["name"];
			$city = $_POST["city"];
			$state = $_POST["state"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			
			$boat_budget = $_POST["boat_budget"];
			$boat_category = round($_POST["boat_category"], 0);
			$manufacturer = $_POST["manufacturer"];
			$model = $_POST["model"];
			$boat_size = $_POST["boat_size"];
			$boat_year = $_POST["boat_year"];
			$comments = $_POST["comments"];		  
			$email2 = $_POST["email2"];
		  
			//create the session
			$datastring = $cm->session_field_boat_finder();
			$cm->create_session_for_form($datastring, $_POST);
			//end
		  
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');		
			//$cm->field_validation($manufacturer , '', 'Manufacturer ', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
		  
		  //$cm->form_post_check_valid_main('boatfinder');
		  //$cm->delete_session_for_form($datastring);
		  $boat_category_name = $cm->get_common_field_name('tbl_category', 'name', $boat_category);
		  $comments = nl2br($comments);
		  
		  $msg = '
		  <table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Boat Finder</strong></td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>My Information:</strong></td>
			  </tr>	
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
			  </tr>	
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
			  </tr>	
			
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Requirements:</strong></td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Budget:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_budget, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">>Boat Category:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_category_name, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($manufacturer, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Size:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_size, 1) .'</td>
			  </tr>
			  
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
			  </tr>
		
			  <tr>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Special Request:</td>
			   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
			  </tr>
		  </table>          
		  ';
		  
		  //add to lead
			$form_type = 8;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $msg,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			//end
		  
		  //send email to admin
		  $mail_bcc = "";
		  $mail_to =  $cm->admin_email_to();
		  $mail_fm =  $cm->admin_email();
		  $mail_cc = "";
		  $mail_reply =  $cm->filtertextdisplay($email);
		  $fromnamesender =  $cm->filtertextdisplay($name);
		  $mail_subject = "Boat Finder";
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $fromnamesender);
		  //end
		  
		  //send to user
		  $send_ml_id = 15;
		  $msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		  $mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
		  $companyname = $cm->sitename;
		  
		  $msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
		  $msg = str_replace("#companyname#", $companyname, $msg);
		  $mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
		
		  $mail_fm = $cm->admin_email();
		  $mail_to = $cm->filtertextdisplay($email);
		  $mail_cc = "";
		  $mail_reply = "";
		  $sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
		  //end
		  
		  $_SESSION["thnk"] = $msg;
		  header('Location: '. $cm->folder_for_seo .'thankyou');
		  exit;
		}
	}
	
	//Service Request form
	public function display_service_request_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_service_request();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$boat_warranty1_chk = $boat_warranty2_chk = '';
		if ($boat_warranty == 1){ $boat_warranty1_chk = ' checked="checked"'; }
		if ($boat_warranty == 2){ $boat_warranty2_chk = ' checked="checked"'; }
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="service-request-ff" name="service-request-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="servicerequestsubmit" />	
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />   
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Contact Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="name" name="name" value="'. $name .'" class="input" />
			</li>			
			<li class="right">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
			
			<li class="left">
				<p>Phone <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
			
			<li>
				<p>Comments <span class="requiredfieldindicate">*</span></p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Boat Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">
			<li class="left">
				<p>Make <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>
			<li class="right">
				<p>Hull ID Number</p>
				<input type="text" id="boat_hull_no" name="boat_hull_no" value="'. $boat_hull_no .'" class="input" />
			</li>
			
			<li class="left">
				<p>Hours</p>
				<input type="text" id="boat_hours" name="boat_hours" value="'. $boat_hours .'" class="input" />
			</li>
			<li class="right">
				<p>Warranty</p>
				<input type="radio" class="radiobtn" id="boat_warranty1" name="boat_warranty" value="1"'. $boat_warranty1_chk .' /> Yes &nbsp;&nbsp;
				<input type="radio" class="radiobtn" id="boat_warranty2" name="boat_warranty" value="0"'. $boat_warranty2_chk .' /> No				
			</li>
		</ul>
		</div>
		</div>
		';		
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Engine Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">
			<li class="left">
				<p>Make</p>
				<input type="text" id="engine_make" name="engine_make" value="'. $engine_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model</p>
				<input type="text" id="engine_model" name="engine_model" value="'. $engine_model .'" class="input" />
			</li>			
			
			<li class="left">
				<p>Engine Type</p>
				<select name="engine_type" id="engine_type" class="select">
				<option value="">Select</option>
                '. $yachtclass->get_engine_type_combo($engine_type, 1, 1) .'                      
                </select>
			</li>
			<li class="right">
				<p>Engine #</p>
				<input type="text" id="engine_motor_no" name="engine_motor_no" value="'. $engine_motor_no .'" class="input" />
			</li>
			
			<li class="left">
				<p>Horsepower</p>
				<input type="text" id="engine_hp" name="engine_hp" value="'. $engine_hp .'" class="input" />
			</li>
			<li class="right">
				<p>Fuel Type</p>
				<select name="fuel_type" id="fuel_type" class="select">
				<option value="">Select</option>
                '. $yachtclass->get_fuel_type_combo($fuel_type, 1, 1) .'                      
                </select>
			</li>
		</ul>	
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>What kind of service do you need done?</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">
			<li>
				<textarea rows="1" cols="1" id="kind_of_service" name="kind_of_service" class="comments" placeholder="">'. $kind_of_service .'</textarea>
			</li>		
		</ul>
		</div>
		</div>
				
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#service-request-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}
				
				if (!field_validation_border("comments", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "comments");
				}
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_model");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Service Request form
	public function submit_service_request_form(){
		if(($_POST['fcapi'] == "servicerequestsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			$comments = $_POST["comments"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_hours = $_POST["boat_hours"];
			$boat_hull_no = $_POST["boat_hull_no"];
			$boat_warranty = round($_POST["boat_warranty"], 0);
			
			$engine_make = $_POST["engine_make"];
			$engine_model = $_POST["engine_model"];
			$engine_year = $_POST["engine_year"];
			$engine_type = round($_POST["engine_type"], 0);
			$engine_hp = $_POST["engine_hp"];			
			$engine_motor_no = $_POST["engine_motor_no"];
			$fuel_type = round($_POST["fuel_type"], 0);
			$kind_of_service = $_POST["kind_of_service"];
				
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_service_request();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($comments, '', 'Comments', $red_pg, '', '', 1, 'fr_');
			
			$cm->field_validation($boat_make, '', 'Boat Make', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_model, '', 'Boat Model', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Boat Year', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('servicerequestsubmit');
			$cm->delete_session_for_form($datastring);
			
			//get name by field id
			$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type);
			$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type);
			//ends
			
			$comments = nl2br($comments);
			$kind_of_service = nl2br($kind_of_service);
			$boat_warranty_text = $cm->set_yesyno_field($boat_warranty);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Service Request</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Contact Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comments:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Hull ID Number:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hull_no, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Hours:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hours, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Warranty:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_warranty_text .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Engine Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Type:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $engine_type_name .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine #:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_motor_no).'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_hp, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Fuel Type:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $fuel_type_name .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">What kind of service do you need done?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($kind_of_service) .'</td>
				</tr>								
			</table>
			';
			//end
			
			//add to lead
			$form_type = 12;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 28;
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
			$send_ml_id = 29;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	//Parts Request form
	public function display_parts_request_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_parts_request();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="parts-request-ff" name="parts-request-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="partsrequestsubmit" />	
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />   
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="name" name="name" value="'. $name .'" class="input" />
			</li>			
			<li class="right">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
			
			<li class="left">
				<p>Phone <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
		</ul>
		
		<ul class="form">
			<li class="left">
				<p>Make <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>
			<li class="right">
				<p>HIN</p>
				<input type="text" id="boat_hin" name="boat_hin" value="'. $boat_hin .'" class="input" />
			</li>
			
			<li class="left">
				<p>VIN</p>
				<input type="text" id="boat_vin" name="boat_vin" value="'. $boat_vin .'" class="input" />
			</li>
			<li class="right">
				<p>Part #</p>
				<input type="text" id="boat_part_no" name="boat_part_no" value="'. $boat_part_no .'" class="input" />
			</li>
			
			<li class="left">
				<p>QTY</p>
				<input type="text" id="boat_qty" name="boat_qty" value="'. $boat_qty .'" class="input" />
			</li>
			
			<li>
				<p>Comments <span class="requiredfieldindicate">*</span></p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
		</ul>
		</div>
		</div>
		';		
		
		$returntext .= '
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#parts-request-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_model");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Parts Request form
	public function submit_parts_request_form(){
		if(($_POST['fcapi'] == "partsrequestsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			$comments = $_POST["comments"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_hin = $_POST["boat_hin"];
			$boat_vin = $_POST["boat_vin"];
			$boat_part_no = $_POST["boat_part_no"];
			$boat_qty = $_POST["boat_qty"];
				
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_parts_request();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			
			$cm->field_validation($boat_make, '', 'Boat Make', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_model, '', 'Boat Model', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Boat Year', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);			
		
			$comments = nl2br($comments);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Parts Request</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">HIN:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hin, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">VIN:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_vin, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Part #:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_part_no, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">QTY:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_qty, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Comments:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>											
			</table>
			';
			//end
			
			//add to lead
			$form_type = 13;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 30;
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
			$send_ml_id = 31;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	//Buyer Services form
	public function display_buyer_services_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		$templateid = round($argu["templateid"], 0);
		$ajaxsubmit = round($argu["ajaxsubmit"], 0);
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_buyer_services_request();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($fname == "" AND $email == ""){
			//form not submitted
			$user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
			//$name = $fname . ' ' . $lname;
		}
		
		if ($templateid == 1){
			$returntext = '
			<h4 class="ng-h4 uppercase">MAKE A DIFFERENCE WHEN BUYING</h4>
        	<h1 class="ng-h1 uppercase white">NEXT GENERATION YACHTING</h1>
			
			<form class="ngsearchform" method="post" action="'. $cm->folder_for_seo .'" id="buyer-services-ff" name="buyer-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="buyerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			
			<ul class="ng-search-form">
            	<li>
                	<p>Contact Info</p>
                    <label for="bs_fname" class="com_none">Name</label>
                    <input type="text" id="bs_fname" name="fname" value="'. $fname .'" class="input" placeholder="First Name">
                    <label for="bs_lname" class="com_none">Last Name</label>
                    <input type="text" id="bs_lname" name="lname" value="'. $lname .'" class="input" placeholder="Last Name">
                    <label for="bs_email" class="com_none">Email</label>
                    <input type="text" id="bs_email" name="email" value="'. $email .'" class="input" placeholder="Email">
                    <label for="bs_phone" class="com_none">Phone Number</label>
                    <input type="text" id="bs_phone" name="phone" value="'. $phone .'" class="input" placeholder="Phone Number">
                </li>   
                <li>
                	<p>Yacht On Trade</p>
                    <label for="bs_boat_make" class="com_none">Make</label>
                    <input type="text" id="bs_boat_make" name="boat_make" value="'. $boat_make .'" class="input" placeholder="Make">
                    <label for="bs_boat_model" class="com_none">Model</label>
                    <input type="text" id="bs_boat_model" name="boat_model" value="'. $boat_model .'" class="input" placeholder="Model">
                    <label for="bs_boat_year" class="com_none">Year</label>
                    <input type="text" id="bs_boat_year" name="boat_year" value="'. $boat_year .'" class="input" placeholder="Year">
                    <label for="bs_boat_location" class="com_none">Location</label>
                    <input type="text" id="bs_boat_location" name="boat_location" value="'. $boat_location .'" class="input" placeholder="Location">
                </li>   
                <li>
                	<p>Yacht You Are Looking For</p>
                    <div class="ng-half ng-first"><label for="bs_boat_size" class="com_none">Size</label>
                    <input type="text" id="bs_boat_size" name="boat_size" value="'. $boat_size .'" class="input" placeholder="Size"></div>
                    
                    <div class="ng-half"><label for="bs_boat_ideal_brand" class="com_none">Ideal Brand</label>
                    <input type="text" id="bs_boat_ideal_brand" name="boat_ideal_brand" value="'. $boat_ideal_brand .'" class="input" placeholder="Ideal Brand"></div>
                    
                    <div class="ng-half ng-first"><label for="bs_boat_budget_min" class="com_none">Budget Minimum</label>
                    <input type="text" id="bs_boat_budget_min" name="boat_budget_min" value="'. $boat_budget_min .'" class="input" placeholder="Budget Minimum"></div>
                    
                    <div class="ng-half"><label for="bs_boat_budget_max" class="com_none">Budget Maximum</label>
                    <input type="text" id="bs_boat_budget_max" name="boat_budget_max" value="'. $boat_budget_max .'" class="input" placeholder="Budget Maximum"></div>
                    
                    <label for="bs_comments" class="com_none">Message</label>
                    <textarea name="comments" id="bs_comments" class="comments" rows="1" cols="1" placeholder="Message">'. $comments .'</textarea>
                </li>   
            </ul>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
            <div class="ng-hr clearfixmain">
            	<div><input type="submit" value="SEARCH FOR YOUR YACHT" class="ng-submit"></div>
            </div>
						
			</form>
			';
		}else{
		
			$returntext = '
			<div class="container clearfixmain">
			<div class="ssform clearfixmain">
			<h2>Your Yacht Search</h2>
			
			<form method="post" action="'. $cm->folder_for_seo .'" id="buyer-services-ff" name="buyer-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="buyerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />   
			';
			
			$returntext .= '
			<div class="rowflex mt-4">
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>CONTACT INFO:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="bs_fname">First Name:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="bs_fname" name="fname" value="'. $fname .'" class="input" />
					</div>
					
					<div class="rowflex mb-1">
						<label for="bs_lname">Last Name:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="bs_lname" name="lname" value="'. $lname .'" class="input" />
					</div>
					
					<div class="rowflex mb-1">
						<label for="bs_email">Email:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="bs_email" name="email" value="'. $email .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_phone">Phone Number:</label>
						<input type="text" id="bs_phone" name="phone" value="'. $phone .'" class="input" />
					</div>                
				</div>
				
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>YACHT ON TRADE:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="bs_boat_make">Make:</label>
						<input type="text" id="bs_boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_boat_model">Model:</label>
						<input type="text" id="bs_boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_boat_year">Year:</label>
						<input type="text" id="bs_boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
					</div>    
					<div class="rowflex mb-1">
						<label for="bs_boat_location">Location:</label>
						<input type="text" id="bs_boat_location" name="boat_location" value="'. $boat_location .'" class="input" />
					</div>            
				</div>
				
				<div class="col-30">
					<h5 class="mb-3"><strong>YACHT YOU ARE LOOKING FOR:</strong></h5>
					<div class="rowflex mb-1">
						<label for="bs_boat_size">Size:</label>
						<input type="text" id="bs_boat_size" name="boat_size" value="'. $boat_size .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_boat_ideal_brand">Ideal Brand:</label>
						<input type="text" id="bs_boat_ideal_brand" name="boat_ideal_brand" value="'. $boat_ideal_brand .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_boat_budget_min">Budget Minimun:</label>
						<input type="text" id="bs_boat_budget_min" name="boat_budget_min" value="'. $boat_budget_min .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="bs_boat_budget_max">Budget Maximum:</label>
						<input type="text" id="bs_boat_budget_max" name="boat_budget_max" value="'. $boat_budget_max .'" class="input" />
					</div>
				</div>
			</div>
			<p class="mb-1">Message:</p>
			<label class="com_none" for="comments">Message</label>
			<textarea name="comments" id="comments" class="comments" rows="1" cols="1">'. $comments .'</textarea>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
			<div align="center"><input name="submit" type="submit" value="Search Your Yacht"></div>
	
			</form>
			</div>
			</div>
			';
		}
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#buyer-services-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("bs_fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "bs_fname");
				}
				
				if (!field_validation_border("bs_lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "bs_lname");
				}				
					
				if (!field_validation_border("bs_email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "bs_email");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Buyer Services form
	public function submit_buyer_services_form(){
		if(($_POST['fcapi'] == "buyerservicessubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_location = $_POST["boat_location"];
			
			$boat_size = $_POST["boat_size"];
			$boat_ideal_brand = $_POST["boat_ideal_brand"];
			$boat_budget_min = $_POST["boat_budget_min"];
			$boat_budget_max = $_POST["boat_budget_max"];
			
			$comments = $_POST["comments"];
				
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_buyer_services_request();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);			
		
			$comments = nl2br($comments);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Contact Info</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Yacht On Trade</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Yacht You Are Looking For</strong></td>
				</tr>				
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Size:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_size, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Ideal Brand:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_ideal_brand, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Budget Minimum:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_budget_min, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Budget Maximum:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_budget_max, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>											
			</table>
			';
			//end
			
			$fullname = $fname . ' ' . $lname;
			
			//add to lead
			$form_type = 20;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 37;
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
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 38;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	//Seller Services form
	public function display_seller_services_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$templateid = round($argu["templateid"], 0);
		$ajaxsubmit = round($argu["ajaxsubmit"], 0);
		
		$datastring = $cm->session_field_seller_services_request();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($templateid == 1){
			$returntext = '
			<form method="post" action="'. $cm->folder_for_seo .'" id="seller-services-ff" name="seller-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="sellerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			<input type="hidden" value="'. $ajaxsubmit .'" id="ajaxsubmit" name="ajaxsubmit" />
			
			<h3 class="t-center"><span>BOAT EVALUATION</span></h3>
			<hr class="blue m-0">
			<div class="fcform1">
				<p>Contact Info</p>
				<p><label for="name" class="d-none">Name</label>
				<input type="text" name="name" placeholder="Name" id="name" value="'. $name .'" class="input" /></p>
				<p><label for="email" class="d-none">Name</label>
				<input type="text" name="email" placeholder="Email" id="email" value="'. $email .'" class="input" /></p>
				<p><label for="phone" class="d-none">Name</label>
				<input type="text" name="phone" placeholder="Phone Number" id="phone" value="'. $phone .'" class="input" /></p>
			</div>
			<hr class="blue m-0">
			
			<div class="fcform1">
				<p>Yacht Info</p>
				<p><label for="boat_make" class="d-none">Make</label>
				<input type="text" placeholder="Make" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" /></p>
				<p><label for="boat_model" class="d-none">Model</label>
				<input type="text" placeholder="Model" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" /></p>
				<p><label for="boat_year" class="d-none">Year</label>
				<input type="text" placeholder="Year" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" /></p>
				<p><label for="boat_engines" class="d-none">Engines</label>
				<input type="text" placeholder="Engines" id="boat_engines" name="boat_engines" value="'. $boat_engines .'" class="input" /></p>
				<p><label for="boat_hours_on_engines" class="d-none">Hours On Engines</label>
				<input type="text" placeholder="Hours On Engines" id="boat_hours_on_engines" name="boat_hours_on_engines" value="'. $boat_hours_on_engines .'" class="input" /></p>
				<p><label for="boat_location" class="d-none">Location</label>
				<input type="text" placeholder="Location" id="boat_location" name="boat_location" value="'. $boat_location .'" class="input" /></p>
				<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
			</div>
			<hr class="blue m-0">
			
			<div class="fcform1">
				<p><button type="submit" class="button">SUBMIT</button></p>
			</div> 
			<div class="fomrsubmit-result com_none"></div>
			</form>
			';
		}elseif ($templateid == 2){
			$returntext = '
			<h1 class="ng-h1 uppercase"><span class="navy navyborder">YOUR BOAT EVALUATION</span></h1>
			
			<form method="post" action="'. $cm->folder_for_seo .'" id="seller-services-ff" name="seller-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="sellerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			<input type="hidden" value="'. $ajaxsubmit .'" id="ajaxsubmit" name="ajaxsubmit" />
			
			<ul class="ng-boat-evaluation-form">
            	<li>
                	<p>Contact Info</p>
                    <label for="name" class="com_none">Name</label>
                    <input type="text" name="name" placeholder="Name" id="name" value="'. $name .'" class="input" />
                    <label for="email" class="com_none">Email</label>
                    <input type="text" name="email" placeholder="Email" id="email" value="'. $email .'" class="input" />
                    <label for="phone" class="com_none">Phone Number</label>
                    <input type="text" name="phone" placeholder="Phone Number" id="phone" value="'. $phone .'" class="input" />
                </li>   
                <li>
                	<p>Yacht</p>
                    <label for="boat_make" class="com_none">Make</label>
                    <input type="text" placeholder="Make" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
                    <label for="boat_model" class="com_none">Model</label>
                    <input type="text" placeholder="Model" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
                    <label for="boat_year" class="com_none">Year</label>
                    <input type="text" placeholder="Year" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />                    
                </li>   
                <li>
                	<p class="mob-none">&nbsp;</p>
                    <label for="boat_engines" class="com_none">Engines</label>
                    <input type="text" placeholder="Engines" id="boat_engines" name="boat_engines" value="'. $boat_engines .'" class="input" />
                    
                    <label for="boat_hours_on_engines" class="com_none">Hours</label>
                    <input type="text" placeholder="Hours On Engines" id="boat_hours_on_engines" name="boat_hours_on_engines" value="'. $boat_hours_on_engines .'" class="input" />
                    
                    <label for="boat_location" class="com_none">Location</label>
                    <input type="text" placeholder="Location" id="boat_location" name="boat_location" value="'. $boat_location .'" class="input" />
                </li>   
            </ul>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
            <div class="ng-hr clearfixmain">
            	<div><input type="submit" value="EVALUATE YOUR BOAT" class="ng-submit"></div>
            </div>
			<div class="fomrsubmit-result com_none"></div>
			</form>
			';
		}else{
		
			$returntext = '
			<!--<div class="container clearfixmain">
			<div class="ssform clearfixmain">
			<h2>Your Boat Evaluation</h2>-->
			
			<form method="post" action="'. $cm->folder_for_seo .'" id="seller-services-ff" name="seller-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="sellerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			<input type="hidden" value="'. $ajaxsubmit .'" id="ajaxsubmit" name="ajaxsubmit" /> 
			';
			
			$returntext .= '
			<div class="rowflex mt-4">
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>CONTACT INFO:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="name">Name:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="name" name="name" value="'. $name .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="email">Email:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="email" name="email" value="'. $email .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="phone">Phone Number:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
					</div>                
				</div>
				
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>YACHT:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="boat_make">Make:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="boat_model">Model:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="boat_year">Year:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
					</div>      
				</div>
				
				<div class="col-30">
					 <h5 class="mb-3 md-none">&nbsp;</h5>
					<div class="rowflex mb-1">
						<label for="boat_engines">Engines:</label>
						<input type="text" id="boat_engines" name="boat_engines" value="'. $boat_engines .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="boat_hours_on_engines">Hours on Engines:</label>
						<input type="text" id="boat_hours_on_engines" name="boat_hours_on_engines" value="'. $boat_hours_on_engines .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="boat_location">Location:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="boat_location" name="boat_location" value="'. $boat_location .'" class="input" />
					</div>
				</div>
			</div>
			<p class="mb-1">Message:</p>
			<label class="com_none" for="comments">Message</label>
			<textarea name="comments" id="comments" class="comments" rows="1" cols="1">'. $comments .'</textarea>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
			<div align="center"><input name="submit" type="submit" value="Evaluate Your Boat"></div>			
			</form>
			<!--</div>
			</div>-->
			';
		}
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#seller-services-ff").submit(function(event){
				var all_ok = "y";
				var setfocus = "n";
				
				var ajaxsubmit = $("#ajaxsubmit").val();
				ajaxsubmit = parseInt(ajaxsubmit);
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}	
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_model");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}
				
				if (!field_validation_border("boat_location", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_location");
				}		
				
				if (all_ok == "n"){
					return false;
				}
				
				if (ajaxsubmit > 0){
					
					//Ajax submit
					var form = $(this);
					$.ajax({
					  type: form.attr("method"),
					  url: form.attr("action"),
					  data: form.serialize()
					}).done(function() {					  
					  $("#name").val("");
					  $("#email").val("");
					  $("#phone").val("");
					  $("#boat_make").val("");
					  $("#boat_model").val("");
					  $("#boat_year").val("");
					  $("#boat_engines").val("");
					  $("#boat_hours_on_engines").val("");
					  $("#boat_location").val("");
					  
					  //$(".fomrsubmit-result").addClass("success");
					  //$(".fomrsubmit-result").html("Email sent successfully");
					  //$(".fomrsubmit-result").removeClass("com_none");
					  
					  $("#formsubmitcontent").html("Thank you for sending your yacht info.<br>We will get back to you asap.");
					  $("#formsubmitoverlay").show();
					  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					}).fail(function() {
					  $(".fomrsubmit-result").addClass("error");
					  $(".fomrsubmit-result").html("ERROR! Please try again");
					  $(".fomrsubmit-result").removeClass("com_none");
					  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					});
					
					event.preventDefault();
					
				}else{
					return true;
				}
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Seller Services form
	public function submit_seller_services_form(){
		if(($_POST['fcapi'] == "sellerservicessubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];			
			
			$boat_engines = $_POST["boat_engines"];
			$boat_hours_on_engines = $_POST["boat_hours_on_engines"];
			$boat_location = $_POST["boat_location"];
			
			$comments = $_POST["comments"];
				
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			$ajaxsubmit = round($_POST["ajaxsubmit"], 0);
			//end
			
			//create the session
			$datastring = $cm->session_field_seller_services_request();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);			
		
			$comments = nl2br($comments);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Contact Info</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Yacht</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>				
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engines:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engines, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Hours on Engines:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hours_on_engines, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>											
			</table>
			';
			//end
			
			//add to lead
			$form_type = 21;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 39;
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
			$send_ml_id = 40;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($ajaxsubmit > 0){
				return 1;
			}else{
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}
		}
	}
	
	//Increase Yacht Value form
	public function display_increase_yacht_value_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$datastring = $cm->session_field_increase_yacht_value();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$newsletter = round($newsletter, 0);
		if ($newsletter != 1){
			$newsletter_text = '';
		}else{
			$newsletter_text = ' checked="checked"';
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="increaseyachtvalue-ff" name="increaseyachtvalue-ff">
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="increaseyachtvaluesubmit" />
		';		
		
		$returntext = '
		<h3>How to increase your yacht value with minimum investment</h3>
		'. $formstart .'
		<ul class="form">				          
			<li>
				<p>Name <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="name" name="name" value="'. $name .'" />
			</li>
			
			<li>
				<p>Email <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="email" name="email" value="'. $email .'" />
			</li>
		
			<li>
				<p>Boat Owned</p>
				<input type="text" class="input" id="boat_owned" name="boat_owned" value="'. $boat_owned .'" />
			</li>
			
			<li>
				<p>Receive Newsletter?&nbsp;&nbsp;&nbsp;<input type="checkbox" id="newsletter" name="newsletter" value="1" class="checkbox"'. $newsletter_text .' /></p>
			</li>
			
			
			
			<li>'. $captchaclass->call_captcha(). '</li>     
	
			<li class="submit">
				<button type="submit" class="button" value="Submit">Submit</button>
			</li>
			
			<li>
				<p><span class="requiredtext">*</span> = Mandatory fields</p>            
			</li>
		</ul>
		</form>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#increaseyachtvalue-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Increase Yacht Value form
	public function submit_increase_yacht_value_form(){
		if(($_POST['fcapi'] == "increaseyachtvaluesubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];	
			
			$boat_owned = $_POST["boat_owned"];	
			$newsletter = round($_POST["newsletter"], 0);
				
			$pgid = round($_POST["pgid"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_increase_yacht_value();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-increase-yacht-value");				
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);
			
			$newsletter_text = $cm->set_yesyno_field($newsletter);		
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
					
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Owned:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_owned, 1) .'</td>
				</tr>
				
						
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_text .'</td>
				</tr>										
			</table>
			';
			//end
			
			//add to lead
			$form_type = 22;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => '',
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 41;
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
			$send_ml_id = 42;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($frompopup == 1){
				$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
				$thankyoupage = $cm->get_page_url(0, "popthankyou") . "?increaseyouyachtvalue=" . $pgid;
				$_SESSION["thnk"] = $pagecontent;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}			
		}
	}
	
	//Boat Show Registration form
	public function display_boat_show_registration_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$datastring = $cm->session_field_boat_show_registration();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$newsletter = round($newsletter, 0);
		if ($newsletter != 1){
			$newsletter_text = '';
		}else{
			$newsletter_text = ' checked="checked"';
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="boatshowregistration-ff" name="boatshowregistration-ff">
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="boatshowregistrationsubmit" />
		';		
		
		$returntext = '
		<h3>Boat Show Registration</h3>
		'. $formstart .'
		<ul class="form">				          
			<li>
				<p>Name <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="name" name="name" value="'. $name .'" />
			</li>
			
			<li>
				<p>Email <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="email" name="email" value="'. $email .'" />
			</li>
			
			<li>
				<p>Phone <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
			</li>
		
			<li>
				<p>Boat Details</p>
				<input type="text" class="input" id="boat_details" name="boat_details" value="'. $boat_details .'" />
			</li>
			
			<li>
				<p>Location</p>
				<input type="text" class="input" id="boat_location" name="boat_location" value="'. $boat_location .'" />
			</li>
			
			<li>
				<p>Message</p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
			
			<li>
				<p>Receive Newsletter?&nbsp;&nbsp;&nbsp;<input type="checkbox" id="newsletter" name="newsletter" value="1" class="checkbox"'. $newsletter_text .' /></p>
			</li>			
			
			<li>'. $captchaclass->call_captcha(). '</li>     
	
			<li class="submit">
				<button type="submit" class="button" value="Submit">Submit</button>
			</li>
			
			<li>
				<p><span class="requiredtext">*</span> = Mandatory fields</p>            
			</li>
		</ul>
		</form>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#boatshowregistration-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Boat Show Registration form
	public function submit_boat_show_registration_form(){
		if(($_POST['fcapi'] == "boatshowregistrationsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_details = $_POST["boat_details"];
			$boat_location = $_POST["boat_location"];	
			$newsletter = round($_POST["newsletter"], 0);
			$comments = $_POST["comments"];
				
			$pgid = round($_POST["pgid"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_boat_show_registration();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-boat-show-registration");				
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$comments = nl2br($comments);
			$newsletter_text = $cm->set_yesyno_field($newsletter);		
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
					
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Details:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_details, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>
				
						
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_text .'</td>
				</tr>										
			</table>
			';
			//end
			
			//add to lead
			$form_type = 23;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 43;
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
			$send_ml_id = 44;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($frompopup == 1){
				$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
				$thankyoupage = $cm->get_page_url(0, "popthankyou") . "?boatshowregistration=" . $pgid;
				$_SESSION["thnk"] = $pagecontent;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}			
		}
	}
	
	//Open Yacht Days form
	public function display_open_yacht_days_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$datastring = $cm->session_field_open_yacht_days();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$newsletter = round($newsletter, 0);
		if ($newsletter != 1){
			$newsletter_text = '';
		}else{
			$newsletter_text = ' checked="checked"';
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="openyachtdays-ff" name="openyachtdays-ff">
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="openyachtdayssubmit" />
		';		
		
		$returntext = '
		<h3>Learn More About Open Yacht Days</h3>
		'. $formstart .'
		<ul class="form">				          
			<li>
				<p>Name <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="name" name="name" value="'. $name .'" />
			</li>
			
			<li>
				<p>Email <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="email" name="email" value="'. $email .'" />
			</li>
			
			<li>
				<p>Phone <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
			</li>
		
			<li>
				<p>Boat Details</p>
				<input type="text" class="input" id="boat_details" name="boat_details" value="'. $boat_details .'" />
			</li>
			
			<li>
				<p>Location</p>
				<input type="text" class="input" id="boat_location" name="boat_location" value="'. $boat_location .'" />
			</li>
			
			<li>
				<p>Message</p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
			
			<li>
				<p>Receive Newsletter?&nbsp;&nbsp;&nbsp;<input type="checkbox" id="newsletter" name="newsletter" value="1" class="checkbox"'. $newsletter_text .' /></p>
			</li>			
			
			<li>'. $captchaclass->call_captcha(). '</li>     
	
			<li class="submit">
				<button type="submit" class="button" value="Submit">Submit</button>
			</li>
			
			<li>
				<p><span class="requiredtext">*</span> = Mandatory fields</p>            
			</li>
		</ul>
		</form>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#openyachtdays-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Open Yacht Days form
	public function submit_open_yacht_days_form(){
		if(($_POST['fcapi'] == "openyachtdayssubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_details = $_POST["boat_details"];
			$boat_location = $_POST["boat_location"];	
			$newsletter = round($_POST["newsletter"], 0);
			$comments = $_POST["comments"];
				
			$pgid = round($_POST["pgid"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_open_yacht_days();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-open-yacht-days");				
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$comments = nl2br($comments);
			$newsletter_text = $cm->set_yesyno_field($newsletter);		
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
					
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Details:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_details, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>
				
						
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_text .'</td>
				</tr>										
			</table>
			';
			//end
			
			//add to lead
			$form_type = 24;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 45;
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
			$send_ml_id = 46;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($frompopup == 1){
				$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
				$thankyoupage = $cm->get_page_url(0, "popthankyou") . "?openyachtdays=" . $pgid;
				$_SESSION["thnk"] = $pagecontent;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}			
		}
	}
	
	//Chartering Your Yacht form
	public function display_chartering_your_yacht_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$datastring = $cm->session_field_chartering_your_yacht();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$newsletter = round($newsletter, 0);
		if ($newsletter != 1){
			$newsletter_text = '';
		}else{
			$newsletter_text = ' checked="checked"';
		}
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="charteringyouryacht-ff" name="charteringyouryacht-ff">
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="charteringyouryachtsubmit" />
		';		
		
		$returntext = '
		<h3>Learn more about chartering your yacht</h3>
		'. $formstart .'
		<ul class="form">				          
			<li>
				<p>Name <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="name" name="name" value="'. $name .'" />
			</li>
			
			<li>
				<p>Email <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="email" name="email" value="'. $email .'" />
			</li>
			
			<li>
				<p>Phone <span class="requiredtext">*</span></p>
				<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
			</li>
		
			<li>
				<p>Boat Details</p>
				<input type="text" class="input" id="boat_details" name="boat_details" value="'. $boat_details .'" />
			</li>
			
			<li>
				<p>Where is your boat located?</p>
				<input type="text" class="input" id="boat_location" name="boat_location" value="'. $boat_location .'" />
			</li>
			
			<li>
				<p>Message</p>
				<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
			</li>
			
			<li>
				<p>Receive Newsletter?&nbsp;&nbsp;&nbsp;<input type="checkbox" id="newsletter" name="newsletter" value="1" class="checkbox"'. $newsletter_text .' /></p>
			</li>			
			
			<li>'. $captchaclass->call_captcha(). '</li>     
	
			<li class="submit">
				<button type="submit" class="button" value="Submit">Submit</button>
			</li>
			
			<li>
				<p><span class="requiredtext">*</span> = Mandatory fields</p>            
			</li>
		</ul>
		</form>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#charteringyouryacht-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}				
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Chartering Your Yacht form
	public function submit_chartering_your_yacht_form(){
		if(($_POST['fcapi'] == "charteringyouryachtsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_details = $_POST["boat_details"];
			$boat_location = $_POST["boat_location"];	
			$newsletter = round($_POST["newsletter"], 0);
			$comments = $_POST["comments"];
				
			$pgid = round($_POST["pgid"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_chartering_your_yacht();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-chartering-your-yacht");				
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$comments = nl2br($comments);
			$newsletter_text = $cm->set_yesyno_field($newsletter);		
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
					
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Details:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_details, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width=""> Boat Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>
				
						
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_text .'</td>
				</tr>										
			</table>
			';
			//end
			
			//add to lead
			$form_type = 25;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 47;
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
			$send_ml_id = 48;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($frompopup == 1){
				$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
				$thankyoupage = $cm->get_page_url(0, "popthankyou") . "?charteringyouryacht=" . $pgid;
				$_SESSION["thnk"] = $pagecontent;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}			
		}
	}
	
	//Finance form
	public function display_finance_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_finance();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$own_rent1 = ' checked="checked"';
	  	$own_rent2 = '';
	  	if ($own_rent == "Rent") {$own_rent1 = ''; $own_rent2 = ' checked="checked"';}
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="finance-ff" name="finance-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="financesubmit" />	
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />   
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Contact Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>First Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p>Last Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>			
			
			<li class="left">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>			
			<li class="right">
				<p>Phone <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>
			
			<li class="left">
				<p>Social Security # <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="social_security" name="social_security" value="'. $social_security .'" class="input" />
			</li>
			<li class="right">
				<p>Date of Birth (mm/dd/yyyy)</p>
				<input defaultdateset="01/01/1980" rangeyear="1900:'. (date("Y") - 18) .'" type="text" id="dob" name="dob" value="'. $dob .'" class="date-field-b input2" />
			</li>
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Current Address</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">
			<li class="left">
				<p>Address</p>
				<input type="text" id="address" name="address" value="'. $address .'" class="input" />
			</li>
			<li class="right">
				<p>City</p>
				<input type="text" id="city" name="city" value="'. $city .'" class="input" />
			</li>
			
			<li class="left">
				<p>State</p>
				<input type="text" id="state" name="state" value="'. $state .'" class="input" />
			</li>
			<li class="right">
				<p>Postal Code</p>
				<input type="text" id="zip" name="zip" value="'. $zip .'" class="input" />
			</li>
			
			<li class="left" id="country_heading">
				<p>Country</p>
				<select id="country" name="country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($country, 1) .'
				</select>
			</li>
			<li class="right">
				<p>Years at Address</p>
				<div class="leftfield"><input type="text" id="address_year" name="address_year" value="'. $address_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="address_month" name="address_month" value="'. $address_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Own or Rent</p>
				<input type="radio" id="own_rent1" name="own_rent" value="Own" class="radiobutton"'. $own_rent1 .' />&nbsp;Own&nbsp;&nbsp;&nbsp;
				<input type="radio" id="own_rent2" name="own_rent" value="Rent" class="radiobutton"'. $own_rent2 .' />&nbsp;Rent
			</li>
			<li class="right">
				<p>Mortgage or Rent Amount</p>
				<input type="text" id="mortgage_rent_amount" name="mortgage_rent_amount" value="'. $mortgage_rent_amount .'" class="input" />
			</li>
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '
		<div id="pevious_address" class="singleblock clearfixmain com_none"> 
		<div class="singleblock_heading"><span>Previous Address</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Address</p>
				<input type="text" id="prev_address" name="prev_address" value="'. $prev_address .'" class="input" />
			</li>
			<li class="right">
				<p>City</p>
				<input type="text" id="prev_city" name="prev_city" value="'. $prev_city .'" class="input" />
			</li>

			<li class="left">
				<p>State</p>
				<input type="text" id="prev_state" name="prev_state" value="'. $prev_state .'" class="input" />
			</li>
			<li class="right">
				<p>Postal Code</p>
				<input type="text" id="prev_zip" name="prev_zip" value="'. $prev_zip .'" class="input" />
			</li>

			<li class="left" id="prev_country_heading">
				<p>Country</p>
				<select id="prev_country" name="prev_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($prev_country, 1) .'
				</select>				
			</li>					
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '
		<div class="singleblock clearfixmain"> 
		<div class="singleblock_heading"><span>Employment Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Employer</p>
				<input type="text" id="employer" name="employer" value="'. $employer .'" class="input" />
			</li>
			<li class="right">
				<p>Address</p>
				<input type="text" id="emp_address" name="emp_address" value="'. $emp_address .'" class="input" />
			</li>

			<li class="left">
				<p>City</p>
				<input type="text" id="emp_city" name="emp_city" value="'. $emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p>State</p>
				<input type="text" id="emp_state" name="emp_state" value="'. $emp_state .'" class="input" />
			</li>

			<li class="left">
				<p>Postal Code</p>
				<input type="text" id="emp_zip" name="emp_zip" value="'. $emp_zip .'" class="input" />
			</li>
			<li class="right" id="emp_country_heading">
				<p>Country</p>
				<select id="emp_country" name="emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($emp_country, 1) .'
				</select>
			</li>

			<li class="left">
				<p>Phone</p>
				<input type="text" id="emp_phone" name="emp_phone" value="'. $emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>How long employed there?</p>
				<div class="leftfield"><input type="text" id="emp_year" name="emp_year" value="'. $emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="emp_month" name="emp_month" value="'. $emp_month .'" class="input" placeholder="Months" /></div>
			</li>
			
			<li class="left">
				<p>Annual Income</p>
				<input type="text" id="annual_income" name="annual_income" value="'. $annual_income .'" class="input" />
			</li>
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '
		<div id="pevious_employer" class="singleblock clearfixmain com_none">
		<div class="singleblock_heading"><span>Previous Employment Information</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Employer</p>
				<input type="text" id="prev_employer" name="prev_employer" value="'. $prev_employer .'" class="input" />
			</li>
			<li class="right">
				<p>Address</p>
				<input type="text" id="prev_emp_address" name="prev_emp_address" value="'. $prev_emp_address .'" class="input" />
			</li>

			<li class="left">
				<p>City</p>
				<input type="text" id="prev_emp_city" name="prev_emp_city" value="'. $prev_emp_city .'" class="input" />
			</li>			
			<li class="right">
				<p>State</p>
				<input type="text" id="prev_emp_state" name="prev_emp_state" value="'. $prev_emp_state .'" class="input" />
			</li>

			<li class="left">
				<p>Postal Code</p>
				<input type="text" id="prev_emp_zip" name="prev_emp_zip" value="'. $prev_emp_zip .'" class="input" />
			</li>
			<li class="right" id="prev_emp_country_heading">
				<p>Country</p>
				<select id="prev_emp_country" name="prev_emp_country" class="select">
				<option value="">Select</option>
				'. $yachtclass->get_country_combo($prev_emp_country, 1) .'
				</select>
			</li>

			<li class="left">
				<p>Phone</p>
				<input type="text" id="prev_emp_phone" name="prev_emp_phone" value="'. $prev_emp_phone .'" class="input" />
			</li>
			<li class="right">
				<p>Length of Employment</p>
				<div class="leftfield"><input type="text" id="prev_emp_year" name="prev_emp_year" value="'. $prev_emp_year .'" class="input" placeholder="Years" /></div>
				<div class="rightfield"><input type="text" id="prev_emp_month" name="prev_emp_month" value="'. $prev_emp_month .'" class="input" placeholder="Months" /></div>
			</li>		
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '
		<div id="pevious_employer" class="singleblock clearfixmain">
		<div class="singleblock_heading"><span>References</span></div> 
		<div class="singleblock_box singleblock_box_h clearfixmain">	   
		<ul class="form">	   		
			<li class="left">
				<p>Name</p>
				<input type="text" id="ref_name1" name="ref_name1" value="'. $ref_name1 .'" class="input" />
			</li>
			<li class="right">
				<p>Phone</p>
				<input type="text" id="ref_phone1" name="ref_phone1" value="'. $ref_phone1 .'" class="input" />
			</li>

			<li class="left">
				<p>Relationship</p>
				<input type="text" id="ref_relationship1" name="ref_relationship1" value="'. $ref_relationship1 .'" class="input" />
			</li>
			
			<li class="left">
				<p>Name</p>
				<input type="text" id="ref_name2" name="ref_name2" value="'. $ref_name2 .'" class="input" />
			</li>
			<li class="right">
				<p>Phone</p>
				<input type="text" id="ref_phone2" name="ref_phone2" value="'. $ref_phone2 .'" class="input" />
			</li>

			<li class="left">
				<p>Relationship</p>
				<input type="text" id="ref_relationship2" name="ref_relationship2" value="'. $ref_relationship2 .'" class="input" />
			</li>		
		</ul>
		</div>
		</div>
		';
		
		$returntext .= '
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#finance-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				//contact
				if (!field_validation_border("fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "fname");
				}
				
				if (!field_validation_border("lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "lname");
				}
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}
				
				if (!field_validation_border("social_security", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "social_security");
				}				
				//end
				
				/*Current Address*/
				if (!field_validation_border("address_year", 5, 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "address_year");
				}

				if (!field_validation_border("address_month", 5, 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "address_month");
				}
				
				var yr = $("#address_year").val();
				var mn = $("#address_month").val();		
				yr = parseInt(yr);
				mn = parseInt(mn);
				if ((yr == 0) && (mn == 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "address_year");
				}
				/*end*/
				
				/*Current Employer*/
				if (!field_validation_border("emp_year", 5, 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "emp_year");
				}

				if (!field_validation_border("emp_month", 5, 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "emp_month");
				}
				
				var em_yr = $("#emp_year").val();
				var em_mn = $("#emp_month").val();		
				em_yr = parseInt(em_yr);
				em_mn = parseInt(em_mn);
				if ((em_yr == 0) && (em_mn == 0)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "emp_year");
				}
				/*end*/
				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Finance form
	public function submit_finance_form(){
		if(($_POST['fcapi'] == "financesubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$p_ar = $_POST;
		  foreach($p_ar AS $key => $val){
			  ${$key} = $val;
			  if ($key == "address_year" 
					OR $key == "address_month" 					
					OR $key == "emp_year" 
					OR $key == "emp_month" 
					OR $key == "prev_emp_year" 
					OR $key == "prev_emp_month"
					OR $key == "country" 
					OR $key == "prev_country" 
					OR $key == "emp_country" 
					OR $key == "prev_emp_country"){
				  ${$key} = round(${$key}, 0);
			  }
		  }
			//end
			
			//create the session
			$datastring = $cm->session_field_finance();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($social_security , '', 'Social Security #', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('financesubmit');
			$cm->delete_session_for_form($datastring);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			$fullname = $fname . ' ' . $lname;
			
			$country_name = $cm->get_common_field_name('tbl_country', 'name', $country);
			$prev_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_country);

			$emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $emp_country);
			$prev_emp_country_name = $cm->get_common_field_name('tbl_country', 'name', $prev_emp_country);
			
			$years_address = '';
			if ($address_year >= 0){
				$years_address .= $address_year . ' Year(s) - ';
			}
			if ($address_month > 0){
				$years_address .= $address_month . ' Month(s)';
			}
			
			$emp_length = '';
			if ($emp_year >= 0){
				$emp_length .= $emp_year . ' Year(s) - ';
			}
			if ($emp_month > 0){
				$emp_length .= $emp_month . ' Month(s)';
			}
			
			//create email message			
			$previous_address = '';
			if ($address_year < 3){
				$prev_years_address = '';
				if ($prev_address_year >= 0){
					$prev_years_address .= $prev_address_year . ' Year(s) - ';
				}
				if ($prev_address_month > 0){
					$prev_years_address .= $prev_address_month . ' Month(s)';
				}
				
				$previous_address = '
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Address:</strong></td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_address, 1) .'</td>
				</tr>	

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_city, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_state, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_zip, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_country_name, 1) .'</td>
				</tr>
				';
			}
			
			$previous_employee_text = '';
			if ($emp_year < 3){
				$prev_emp_length = '';
				if ($prev_emp_year >= 0){
					$prev_emp_length .= $prev_emp_year . ' Year(s) - ';
				}
				if ($prev_emp_month > 0){
					$prev_emp_length .= $prev_emp_month . ' Month(s)';
				}
				
				$previous_employee_text = '
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Previous Employment Information:</strong></td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Employer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_employer, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_address, 1) .'</td>
				</tr>	

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_city, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_state, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_zip, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_country_name, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_phone, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length of Employment:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($prev_emp_length, 1) .'</td>
				</tr>
				';
			}
			
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Finance Form</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Contact Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Social Security:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($social_security, 1) .'</td>
			  	</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($dob, 1) .'</td>
			  	</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Current Address:</strong></td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($address, 1) .'</td>
				</tr>	

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($zip, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($country_name, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years at Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($years_address, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Own or Rent:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($own_rent, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mortgage or Rent Amount:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($mortgage_rent_amount, sha1_file()) .'</td>
				</tr>
				
				'. $previous_address .'								
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Employment Information:</strong></td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Employer:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($employer, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_address, 1) .'</td>
				</tr>	

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_city, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_state, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Postal Code:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_zip, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Country:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_country_name, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_phone, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">How long employed there?:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($emp_length, 1) .'</td>
				</tr>

				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Annual Income:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($annual_income, 1) .'</td>
				</tr>
				
				'. $previous_employee_text .'
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>References:</strong></td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_name1, 1) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_phone1, 1) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_relationship1, 1) .'</td>
				</tr>
				
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_name2, 1) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_phone2, 1) .'</td>
				</tr>
				
				<tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Relationship:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($ref_relationship2, 1) .'</td>
				</tr>
			</table>
			';
			//end
			
			//add to lead
			$form_type = 14;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 32;
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
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 33;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_mail_subject);
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
	
	//Trade-In Evaluation form
	public function display_tradein_evaluation_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_tradein_evaluation();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="tradein_evaluation-ff" name="tradein_evaluation-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="tradeinevaluationsubmit" />	
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" /	   
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Contact Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">	   		
			<li class="left">
				<p>First Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
			</li>
			<li class="right">
				<p>Last Name <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
			</li>
			
			<li class="left">
				<p>Email Address <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
			<li class="right">
				<p>Phone</p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>			
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Boat Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li class="left">
				<p>Make</p>
				<input type="text" id="boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model</p>
				<input type="text" id="boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year</p>
				<input type="text" id="boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
			</li>
			<li class="right">
				<p>Color</p>
				<input type="text" id="boat_color" name="boat_color" value="'. $boat_color .'" class="input" />
			</li>
			
			<li class="left">
				<p>Hours</p>
				<input type="text" id="boat_hours" name="boat_hours" value="'. $boat_hours .'" class="input" />
			</li>			
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Engine Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li class="left">
				<p>Make</p>
				<input type="text" id="engine_make" name="engine_make" value="'. $engine_make .'" class="input" />
			</li>
			<li class="right">
				<p>Model</p>
				<input type="text" id="engine_model" name="engine_model" value="'. $engine_model .'" class="input" />
			</li>
			
			<li class="left">
				<p>Year</p>
				<input type="text" id="engine_year" name="engine_year" value="'. $engine_year .'" class="input" />
			</li>
			<li class="right">
				<p>Drive Type</p>
				<select name="drive_type" id="drive_type" class="select">
				<option value="">Select</option>
                '. $yachtclass->get_drive_type_combo($drive_type, 1, 1) .'                      
                </select>
			</li>
			
			<li class="left">
				<p>HP</p>
				<input type="text" id="engine_hp" name="engine_hp" value="'. $engine_hp .'" class="input" />
			</li>			
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Notable Equipment</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li>
				<textarea rows="1" cols="1" id="notable_equipment" name="notable_equipment" class="comments">'. $notable_equipment .'</textarea>
			</li>		
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Mechanical Information</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li class="left">
				<p>Has this boat ever had an insurance claim or sustained any flood damage?</p>
				<input type="text" id="boat_insurance_claim" name="boat_insurance_claim" value="'. $boat_insurance_claim .'" class="input" />
			</li>
			<li class="right">
				<p>Engine in good working condition?</p>
				<input type="text" id="engine_condition" name="engine_condition" value="'. $engine_condition .'" class="input" />
			</li>
			
			<li class="left">
				<p>When where the manifolds & risers replaced?</p>
				<input type="text" id="boat_manifolds" name="boat_manifolds" value="'. $boat_manifolds .'" class="input" />
			</li>
			<li class="right">
				<p>Did the engine(s) have water ingestion?</p>
				<input type="text" id="engine_water_ingestion" name="engine_water_ingestion" value="'. $engine_water_ingestion .'" class="input" />
			</li>
			
			<li class="left">
				<p>Any major mechanical repairs?</p>
				<input type="text" id="major_mechanical_repairs" name="major_mechanical_repairs" value="'. $major_mechanical_repairs .'" class="input" />
			</li>
			<li class="right">
				<p>Are service records available?</p>
				<input type="text" id="service_records_available" name="service_records_available" value="'. $service_records_available .'" class="input" />
			</li>
			
			<li class="left">
				<p>Are the engines under warranty, if so until what date?</p>
				<input type="text" id="engines_warranty_date" name="engines_warranty_date" value="'. $engines_warranty_date .'" class="input" />
			</li>
			<li class="right">
				<p>Are all systems operable? If no, what systems are not working?</p>
				<input type="text" id="systems_operable" name="systems_operable" value="'. $systems_operable .'" class="input" />
			</li>		
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Hull, Deck and Transom</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li class="left">
				<p>Fiberglass scratched, faded, blistered or damaged?</p>
				<input type="text" id="fiberglass_damaged" name="fiberglass_damaged" value="'. $fiberglass_damaged .'" class="input" />
			</li>
			<li class="right">
				<p>Has the hull, deck, or transom ever flooded or had major repair?</p>
				<input type="text" id="major_repair" name="major_repair" value="'. $major_repair .'" class="input" />
			</li>
			
			<li class="left">
				<p>Tape or graphics on hull or deck damaged?</p>
				<input type="text" id="tape_graphics_hull_damaged" name="tape_graphics_hull_damaged" value="'. $tape_graphics_hull_damaged .'" class="input" />
			</li>	
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		';
		
		$returntext .= '	
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Interior</span></div> 
		<div class="singleblock_box singleblock_box_h">	   
		<ul class="form">
			<li class="left">
				<p>Any tears, cracking, or sun fading of vinyl?</p>
				<input type="text" id="tears_cracking" name="tears_cracking" value="'. $tears_cracking .'" class="input" />
			</li>
			<li class="right">
				<p>Any wood rot under seats, in floor, or any other upholstered parts?</p>
				<input type="text" id="upholstered_parts" name="upholstered_parts" value="'. $upholstered_parts .'" class="input" />
			</li>
			
			<li class="left">
				<p>Has the interior ever flooded?</p>
				<input type="text" id="interior_flooded" name="interior_flooded" value="'. $interior_flooded .'" class="input" />
			</li>
			<li class="right">
				<p>Any damage to the Bimini Top, cockpit, bow covers?</p>
				<input type="text" id="cockpit_damage" name="cockpit_damage" value="'. $cockpit_damage .'" class="input" />
			</li>
			
			<li class="left">
				<p>Condition of Eisenglass enclosure?</p>
				<input type="text" id="condition_eisenglass_enclosure" name="condition_eisenglass_enclosure" value="'. $condition_eisenglass_enclosure .'" class="input" />
			</li>
		</ul>
		<div class="clear"></div>
		</div>
		</div>
		
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
		
		$returntext .= '
		<div class="singleblock">
		<input type="submit" value="Submit Form" class="button" />
		</div>
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#tradein_evaluation-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "fname");
				}
				
				if (!field_validation_border("lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "lname");
				}
				
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Trade-In Evaluation form
	public function submit_tradein_evaluation_form(){
		if(($_POST['fcapi'] == "tradeinevaluationsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_color = $_POST["boat_color"];
			$boat_hours = $_POST["boat_hours"];
			
			$engine_make = $_POST["engine_make"];
			$engine_model = $_POST["engine_model"];
			$engine_year = $_POST["engine_year"];
			$drive_type = $_POST["drive_type"];
			$engine_hp = $_POST["engine_hp"];
			
			$notable_equipment = $_POST["notable_equipment"];
			
			$boat_insurance_claim = $_POST["boat_insurance_claim"];
			$engine_condition = $_POST["engine_condition"];
			$boat_manifolds = $_POST["boat_manifolds"];
			$engine_water_ingestion = $_POST["engine_water_ingestion"];
			$major_mechanical_repairs = $_POST["major_mechanical_repairs"];
			$service_records_available = $_POST["service_records_available"];
			$engines_warranty_date = $_POST["engines_warranty_date"];
			$systems_operable = $_POST["systems_operable"];
			
			$fiberglass_damaged = $_POST["fiberglass_damaged"];
			$major_repair = $_POST["major_repair"];
			$tape_graphics_hull_damaged = $_POST["tape_graphics_hull_damaged"];
			
			$tears_cracking = $_POST["tears_cracking"];
			$upholstered_parts = $_POST["upholstered_parts"];
			$interior_flooded = $_POST["interior_flooded"];
			$cockpit_damage = $_POST["cockpit_damage"];
			$condition_eisenglass_enclosure = $_POST["condition_eisenglass_enclosure"];
			
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_tradein_evaluation();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('tradeinevaluationsubmit');
			$cm->delete_session_for_form($datastring);
			
			//get name by field id
			$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type);
			//ends
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			$fullname = $fname . ' ' . $lname;
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Trade Evaluation</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Contact Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Color:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_color, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Hours:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hours, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Engine Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drive Type:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $drive_type_name .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">HP:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_hp, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Notable Equipment:</strong></td>
				</tr>
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'"  colspan="2">'. $cm->filtertextdisplay(nl2br($notable_equipment), 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Mechanical Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Has this boat ever had an insurance claim or sustained any flood damage:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_insurance_claim, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine in good working condition?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_condition, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">When where the manifolds & risers replaced?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_manifolds, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Did the engine(s) have water ingestion?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_water_ingestion, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any major mechanical repairs?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($major_mechanical_repairs, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Are service records available?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($service_records_available, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Are the engines under warranty, if so until what date?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engines_warranty_date, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Are all systems operable? If no, what systems are not working?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($systems_operable, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Hull, Deck and Transom Information:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Fiberglass scratched, faded, blistered or damaged?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fiberglass_damaged, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Has the hull, deck, or transom ever flooded or had major repair?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($major_repair, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Tape or graphics on hull or deck damaged?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tape_graphics_hull_damaged, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Interior:</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any tears, cracking, or sun fading of vinyl?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($tears_cracking, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any wood rot under seats, in floor, or any other upholstered parts?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($upholstered_parts, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Has the interior ever flooded?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($interior_flooded, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Any damage to the Bimini Top, cockpit, bow covers?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($cockpit_damage, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Condition of Eisenglass enclosure?:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($condition_eisenglass_enclosure, 1) .'</td>
				</tr>				
			</table>
			';
			//end
			
			//send email to admin
			$send_ml_id = 19;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#tradeevaluationssubmission#", $emailmessage, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 20;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	//What Is Your Boat Worth?
	public function boat_worth_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_boat_worth();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		$country_id = round($country_id);
		if ($country_id == 0){ $country_id = 1; }	  
	  
		$returntext = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="boat-worth-ff" name="boat-worth-ff">
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="boatworthsubmit" />
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
		';
	  
	   $returntext .= '
		<div class="singleblock"> 
		<div class="singleblock_heading"><span>Boat Information</span><span class="requiredinfo">* Required</span></div> 
		<div class="singleblock_box singleblock_box_h">
			
			<ul class="form">
					<li class="left">
						<p>Boat Manufacturer <span class="requiredfieldindicate">*</span></p>
						<input type="text" class="input" id="boat_make" name="boat_make" value="'. $boat_make .'" />
					</li>                    
					<li class="right">
						<p>Boat Model</p>
						<input type="text" class="input" id="boat_model" name="boat_model" value="'. $boat_model .'" />
					</li>
					
					<li class="left">
						<p>Model Year <span class="requiredfieldindicate">*</span></p>
						<input type="text" class="input" id="boat_year" name="boat_year" value="'. $boat_year .'" />
					</li>                    
					<li class="right">
						<p>Boat Length</p>
						<input type="text" class="input" id="boat_length" name="boat_length" value="'. $boat_length .'" />
					</li>
					
					<li class="left">
						<p>Condition</p>
						<input type="text" class="input" id="boat_condition" name="boat_condition" value="'. $boat_condition .'" />
					</li>					
				</ul>				
				<div class="clear"></div>
	   </div>
	   </div>
	   
	   <div class="singleblock"> 
	   <div class="singleblock_heading"><span>Personal Information</span><span class="requiredinfo">* Required</span></div> 
	   <div class="singleblock_box singleblock_box_h">
			<ul class="form">                                        
					<li class="left">
						<p>Name <span class="requiredfieldindicate">*</span></p>
						<input type="text" class="input" id="name" name="name" value="'. $name .'" />
					</li>
					
					<li class="right">
						<p>Email Address <span class="requiredfieldindicate">*</span></p>
						<input type="text" class="input" id="email" name="email" value="'. $email .'" />
					</li>
					
					<li class="left">
						<p>Phone <span class="requiredfieldindicate">*</span></p>
						<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
					</li>                   
			</ul>
		<div class="clear"></div>
		</div>
		</div>
		
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
	   
	   <ul class="form">
			<li class="submit">
				<button type="submit" class="button" value="Submit">Submit</button>
			</li>
		</ul>
	   ';	
	   
	   $returntext .= '	   
	   </form>';
	   
	   $returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#boat-worth-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}							
				
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}
				
				if (!field_validation_border("boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_make");
				}
				
				if (!field_validation_border("boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "boat_year");
				}				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';
			  
	   return $returntext;
	}
	
	//Submit Boat Worth form
	public function submit_boat_worth_form(){		
		if(($_POST['fcapi'] == "boatworthsubmit")){
			global $db, $cm, $sdeml;
						
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];
			$boat_length = $_POST["boat_length"];
			$boat_condition = $_POST["boat_condition"];
		
			$email2 = $_POST["email2"];
			$pgid = round($_POST["pgid"], 0);
			
			//create the session
			$datastring = $cm->session_field_boat_worth();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($boat_make, '', 'Boat Manufacturer', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Boat Year', $red_pg, '', '', 1, 'fr_');			
			
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('boatworthsubmit');
			$cm->delete_session_for_form($datastring);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//send email to admin
			$message = nl2br($message);		
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Boat Information</strong></td>
				</tr>
								
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Boat Manufacturer:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boat Length:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_length, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Condition:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_condition, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Personal Information</strong></td>
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
			</table>          
			';
			
			//add to lead
			$form_type = 5;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			$send_ml_id = 19;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#boatworthsubmission#", $emailmessage, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 20;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
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
	
	//boat insurance
	public function boat_insurance_form(){
		global $db, $cm, $yachtclass, $captchaclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$resource_company_name = $cm->sitename;
		$dctxt_resource = $cm->get_systemvar('RDTXT');
		
		$datastring = $cm->session_field_contact_resource();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		if ($fname == "" AND $email == ""){
			//form not submitted
			$user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
			
			if (isset($_SESSION["visited_boat"]) AND $_SESSION["visited_boat"] > 0){
				$result = $yachtclass->check_yacht_with_return($_SESSION["visited_boat"]);
				$row = $res_row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Dimensions & Weight
				$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Engine
				$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
			}
		}
		
		if ($country_id == 1){
			$state = $cm->get_common_field_name('tbl_state', 'code', $state_id);
		}
		$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
		$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
		
		$insurance_losses_details_css = ' com_none';
		if ($insurance_losses_details == 1){ $insurance_losses_details_css = ''; }
		
		$prior_insurance_losses1_chk = $prior_insurance_losses2_chk = '';
		if ($prior_insurance_losses == 1){ $prior_insurance_losses1_chk = ' checked="checked"'; }
		if ($prior_insurance_losses == 2){ $prior_insurance_losses2_chk = ' checked="checked"'; }
		
		$returntext = '
	    <form method="post" action="'. $cm->folder_for_seo .'" id="boat_insurance-ff" name="boat_insurance-ff">
	    <input class="finfo" id="email2" name="email2" type="text" />
	    <input type="hidden" id="fcapi" name="fcapi" value="boatinsurance" />
	    ';
		
		 $returntext .= '
		<div class="singleblock">
		<div class="singleblock_heading"><span>Owner Information</span></div>
		<div class="singleblock_box singleblock_box_h">
		<div class="column1">
			<ul class="form">
				<li>
					<p>First Name</p>
					<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
				</li>
				
				<li>
					<p>Last Name</p>
					<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
				</li>
				
				<li>
					<p>Address</p>
					<input type="text" id="address" name="address" value="'. $address .'" class="input" />
				</li>
				
				<li>
					<p>City</p>
					<input type="text" id="city" name="city" value="'. $city .'" class="input" />
				</li>
				
				<li>
					<p>State</p>
					<input type="text" id="state" name="state" value="'. $state .'" class="input" />
				</li>
				
				<li>
					<p>Zip</p>
					<input type="text" id="zip" name="zip" value="'. $zip .'" class="input" />
				</li>  
			</ul>
		</div>
		
		<div class="column2">
			<ul class="form">            	
				<li>
					<p>Phone</p>
					<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
				</li>                    
				  
				<li>
					<p>Email</p>
					<input type="text" id="email" name="email" value="'. $email .'" class="input" />
				</li> 
				
				<li>
					<p>Date of Birth (mm/dd/yyyy)</p>
					<input defaultdateset="01/01/1980" rangeyear="1900:'. (date("Y") - 18) .'" type="text" id="dob" name="dob" value="'. $dob .'" class="date-field-b input2" />
				</li>                    
				   
				<li>
					<p>Drivers License #</p>
					<input type="text" id="drivers_license" name="drivers_license" value="'. $drivers_license .'" class="input" />
				</li>
			</ul>
		</div>                     
		<div class="clear"></div>
		</div>
		</div>
		
		<div class="singleblock">
        <div class="singleblock_heading"><span>Boat Information</span></div>
        <div class="singleblock_box singleblock_box_h">
        	<div class="column1">
            	<ul class="form">
                	<li><p><strong>Vessel</strong></p></li>
                    
                    <li>
                        <p>Make</p>
                        <input type="text" id="manufacturer_name" name="manufacturer_name" value="'. $manufacturer_name .'" class="input" />
                    </li>
                    
                    <li>
                        <p>Model</p>
                        <input type="text" id="model" name="model" value="'. $model .'" class="input" />
                    </li>
                    
                    <li id="year_heading">
                        <p>Year</p>
                        <select class="my-dropdown2" id="year" name="year">
                            <option value="">Select</option>
                            '. $yachtclass->get_year_combo($year, 1) .'
                        </select>
                    </li>
                    
                    <li>
                        <p>Length [in Ft.]</p>
                        <input type="text" id="length" name="length" value="'. $length .'" class="input" />
                    </li>
                    
                    <li>
                        <p>Intended Mooring Location</p>
                        <input type="text" id="intended_mooring_location" name="intended_mooring_location" value="'. $intended_mooring_location .'" class="input" />
                    </li>
                    
                    <li>
                        <p>Intended Navigation Area</p>
                        <input type="text" id="intended_navigation_area" name="intended_navigation_area" value="'. $intended_navigation_area .'" class="input" />
                    </li>
                </ul>
            </div>
            
			<div class="column2">
				<ul class="form">            	
					<li><p><strong>Engine</strong></p></li>                    
					
					<li>
						<p>Make</p>
						<input type="text" id="engine_make_name" name="engine_make_name" value="'. $engine_make_name .'" class="input" />
					</li>
					
					<li>
						<p>Model</p>
						<input type="text" id="engine_model" name="engine_model" value="'. $engine_model .'" class="input" />
					</li>
					
					<li>
						<p>Engine(s)</p>
						<select name="engine_no" id="engine_no" class="my-dropdown2">
							<option value="">Select</option>
							'. $yachtclass->get_common_number_combo($engine_no, 4, 1) . '
						</select>
					</li>
					
					<li>
						<p>Horsepower Individual</p>
						<input type="text" id="horsepower_individual" name="horsepower_individual" value="'. $horsepower_individual .'" class="input" />
					</li>
					
					<li>
						<p>Engine Type</p>
						<select name="engine_type_id" id="engine_type_id" class="my-dropdown2">
							<option value="">Select</option>
							'. $yachtclass->get_engine_type_combo($engine_type_id, 1, 1) .'
						</select>
					</li>
					
					<li>
						<p>Drive Type</p>
						<select name="drive_type_id" id="drive_type_id" class="my-dropdown2">
							<option value="">Select</option>
							'. $yachtclass->get_drive_type_combo($drive_type_id,1, 1) .'
						</select>
					</li>
					
					<li>
						<p>Fuel Type</p>
						<select name="fuel_type_id" id="fuel_type_id" class="my-dropdown2">
							<option value="">Select</option>
							'. $yachtclass->get_fuel_type_combo($fuel_type_id, 1, 1) .'
						</select>
					</li>
				</ul>
			</div>                
			<div class="clear"></div>
			</div>
			</div>
			
			<div class="singleblock">
				<div class="singleblock_heading"><span>Boating Experience</span></div>
				<div class="singleblock_box singleblock_box_h">
					<ul class="form">
						<li>
							<p>Previous Boats Owned</p>
							<textarea name="previous_boats_owned" id="previous_boats_owned" rows="1" cols="1" class="comments">'. $previous_boats_owned .'</textarea>
						</li>
						
						<li class="left">
							<p>Years Boating Experience</p>
							<input type="text" id="boating_experience_year" name="boating_experience_year" value="'. $boating_experience_year .'" class="input" />
						</li>
						<li class="right">
							<p>Boating Courses Completed</p>
							<select name="boating_courses_completed" id="boating_courses_completed" class="my-dropdown2">
								<option value="">Select</option>
								'. $yachtclass->get_boating_courses_combo($boating_courses_completed, 1, 1) .'
							</select>
						</li>
						<li>
							<p>Prior Insurance Losses</p>
							<input type="radio" class="radiobtn" id="prior_insurance_losses1" name="prior_insurance_losses" value="1"'. $prior_insurance_losses1_chk .' /> Yes &nbsp;&nbsp;
							<input type="radio" class="radiobtn" id="prior_insurance_losses2" name="prior_insurance_losses" value="0"'. $prior_insurance_losses1_chk .' /> No
							<div class="insurance_losses_details'. $insurance_losses_details_css .'"><input type="text" id="prior_insurance_losses_details" name="prior_insurance_losses_details" value="'. $prior_insurance_losses_details .'" class="input" placeholder="Describe" /></div>
						</li>
						
						<li>
							<p>By clicking "<strong>Confirm and Send</strong>", I consent to share information on this form with <strong>'. $resource_company_name .'</strong> and acknowledge that such information is true and accurate. I further understand that I am requesting services from said company, at my own discretion, and that I am in no way obligated nor otherwise influenced to purchase intended services.</p>                    
						</li>
						
					</ul>
					<div class="clear"></div>
				</div>
			</div>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
		';
	   	$returntext .= '
		<div class="singleblock">
			<input id="redpageid" name="redpageid" type="hidden" value="'. $pageid .'" />
			<input type="submit" value="Confirm and Send" class="button" />
			<input type="button" value="Cancel" class="button cancel" />
		</div>
		';
		
		$returntext .= '	   
		</form>		
		<div class="disclaimer_div">'. $dctxt_resource .'</div>		
		';
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				$(".radiobtn").click(function(){  
					sel_value_op = $(\'input:radio[name=prior_insurance_losses]:checked\').val();
					if (sel_value_op == 1){
						$(".insurance_losses_details").removeClass("com_none");
					}else{
						$(".insurance_losses_details").addClass("com_none");
					}
			   });
			   
			   $("#boat_insurance-ff").submit(function(){		
				   var all_ok = "y";
				   var setfocus = "n";
					
				   //Owner Information	    
				   if (!field_validation_border("fname", 1, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "fname");
				   }
				   
				   if (!field_validation_border("lname", 1, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "lname");
				   }
				   
				   if (!field_validation_border("phone", 1, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "phone");
				   }
				   
				   if (!field_validation_border("email", 2, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "email"); 		   
				   }
				   
				   if (!datefield_validation_border("dob", "", 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "dob"); 		   
				   }
				   
				   //Boat Information
				   if (!field_validation_border("year", 3, 0)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "year_heading");
				   }
				   
				   if (!field_validation_border("length", 5, 0)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "length");
				   }
				   
				   if (!field_validation_border("horsepower_individual", 5, 0)){
						all_ok = "n";
						setfocus = set_field_focus(setfocus, "horsepower_individual");
				   }
				   
				   //Boating Experience
				   sel_value_op = $("input:radio[name=prior_insurance_losses]:checked").val();
				   if (sel_value_op == 1){
						if (!field_validation_border("prior_insurance_losses_details", 1, 1)){ 
							all_ok = "n"; 
							setfocus = set_field_focus(setfocus, "prior_insurance_losses_details");
						}
				   }
				   
				   if (all_ok == "n"){            
						return false;
				   }
				   return true;		   
			   });
			   
			   $(".cancel").click(function(){  
					history.back(-1);
			   });
			   
			});
		</script>
		';
		
		return $returntext;
	}
	
	public function submit_boat_insurance_form(){
		if(($_POST['fcapi'] == "boatinsurance")){
			global $db, $cm, $sdeml;
			//form field collect
			$p_ar = $_POST;
			foreach($p_ar AS $key => $val){
				${$key} = $val;
				if ($key != "resourceid" AND $key != "lno"){
			
					if ($key == "year"
						OR $key == "engine_no"
						OR $key == "length"
						OR $key == "horsepower_individual"
						OR $key == "engine_type_id"
						OR $key == "drive_type_id"
						OR $key == "fuel_type_id"
						OR $key == "boating_courses_completed"
						OR $key == "prior_insurance_losses"   
						OR $key == "redpageid"         
					){
						${$key} = round(${$key}, 0);
					}else{
						//no format
					}
				}
			}
			//end
			
			//create the session
			$datastring = $cm->session_field_contact_resource();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			$dob_a = $cm->set_date_format($dob);
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($dob, '', 'Date of birth', $red_pg, '', '', 1, 'fr_');
			$cm->check_dob_validation($dob_a, $red_pg, 'fr_');
			if ($prior_insurance_losses == 1){
				$cm->field_validation($prior_insurance_losses_details, '', 'Prior Insurance Losses Details', $red_pg, '', '', 1, 'fr_');
			}
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			//end
			
			//start process
			$previous_boats_owned = nl2br($previous_boats_owned);
			$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
			$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
			$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);
			$boating_courses_completed_name = $cm->get_common_field_name('tbl_boating_courses', 'name', $boating_courses_completed);
			
			$prior_insurance_losses_display = $cm->set_yesyno_field($prior_insurance_losses);
			if ($prior_insurance_losses == 1){
				$prior_insurance_losses_display = $cm->filtertextdisplay($prior_insurance_losses_details, 1);
			}
			$companyname = $cm->sitename;
			
			//send email to admin
			$messagedetails = '
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Owner Information</strong></td>
				  </tr>
					
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Zip:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
				  </tr>  
				
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Dob:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($dob, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drivers License #:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drivers_license, 1) .'</td>
				  </tr>
				  
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information - Vessel</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($manufacturer_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($year, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length [in Ft.]:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($length, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Intended Mooring Location:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($intended_mooring_location, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Intended Navigation Area:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($intended_navigation_area, 1) .'</td>
				  </tr>
				  
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information - Engine</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_make_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_model, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine(s):</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_no, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Horsepower Individual:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($horsepower_individual, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engine Type:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($engine_type_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drive Type:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drive_type_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Fuel Type:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fuel_type_name, 1) .'</td>
				  </tr>
				  
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boating Experience</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Previous Boats Owned:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($previous_boats_owned, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Years Boating Experience:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boating_experience_year, 1) .'</td>
				  </tr>
				  
				  <tr>
				
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Boating Courses Completed:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boating_courses_completed_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Prior Insurance Losses:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $prior_insurance_losses_display .'</td>
				  </tr>
				</table>';
				
				$send_ml_id = 1;				
				$email_ar = $cm->get_table_fields('tbl_brokerage_services_email', 'agent_name, agent_email, cc_email, email_subject, pdes', $send_ml_id);
				$agent_name = $email_ar[0]["agent_name"];
				$agent_email = $email_ar[0]["agent_email"];
				$cc_email = $email_ar[0]["cc_email"];
				$mail_subject = $email_ar[0]["email_subject"];
				$msg = $email_ar[0]["pdes"];
				
				$msg = str_replace("#name#", $cm->filtertextdisplay($agent_name), $msg);
				$msg = str_replace("#messagedetails#", $messagedetails, $msg);
				$msg = str_replace("#companyname#", $companyname, $msg);
				$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
				
				$mail_fm = $cm->admin_email();
				$mail_to = $cm->filtertextdisplay($agent_email);
				$mail_cc = $cm->admin_email_to();
				if ($cc_email != ""){ $mail_cc = $mail_cc . ', ' . $cc_email; }
				$mail_reply = $cm->filtertextdisplay($email);
				$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
				//end
				
				$_SESSION["thnk"] = $this->display_message(2);
				header('Location: '. $cm->folder_for_seo .'thankyou');
				exit;
		}
	}
	
	//boat transport form
	public function get_pick_up_location($pickup_location = ''){
		$returntext = '';
		$pickuplocationar = array("Marina", "Dealer", "Home", "Other");	
		foreach($pickuplocationar as $pickuplocationrow){
			$bck = '';
			if ($pickup_location == $pickuplocationrow){
				$bck = ' selected="selected"';	
			}			
			$returntext .= '<option value="'. $pickuplocationrow .'"'. $bck .'>'. $pickuplocationrow .'</option>';
		}	
		return $returntext;
	}
	
	public function display_boat_transpost_form(){
		global $db, $cm, $yachtclass, $captchaclass;
		$datastring = $cm->session_field_boat_transport();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		if ($fname == "" AND $email == ""){
			//form not submitted
			$user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
	
			if (isset($_SESSION["visited_boat"]) AND $_SESSION["visited_boat"] > 0){
				$result = $yachtclass->check_yacht_with_return($_SESSION["visited_boat"]);
				$row = $res_row = $result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Dimensions & Weight
				$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				//Engine
				$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($_SESSION["visited_boat"]) ."'";
				$ex_result = $db->fetch_all_array($ex_sql);
				$row = $ex_result[0];
				foreach($row AS $key => $val){
					${$key} = htmlspecialchars($val);
				}
			}
		}
		
		if ($country_id == 1){
			$state = $cm->get_common_field_name('tbl_state', 'code', $state_id);
		}
		$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
		$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
		
		$insurance_losses_details_css = ' com_none';
		if ($insurance_losses_details == 1){ $insurance_losses_details_css = ''; }
		
		$returntext = '
	    <form method="post" action="'. $cm->folder_for_seo .'" id="boattransportform-ff" name="boattransportform-ff">
	    <input class="finfo" id="email2" name="email2" type="text" />
	    <input type="hidden" id="fcapi" name="fcapi" value="boattransport" />
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Contact Information</span></div>
        <div class="singleblock_box singleblock_box_h">        
            <ul class="form">
                <li class="left">
                    <p>First Name <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
                </li>                
                <li class="right">
                    <p>Last Name <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Address</p>
                    <input type="text" id="address" name="address" value="'. $address .'" class="input" />
                </li>                
                <li class="right">
                    <p>City / Town</p>
                    <input type="text" id="city" name="city" value="'. $city .'" class="input" />
                </li>
                
                <li class="left">
                    <p>State</p>
                    <input type="text" id="state" name="state" value="'. $state .'" class="input" />
                </li>                
                <li class="right">
                    <p>Zipcode</p>
                    <input type="text" id="zip" name="zip" value="'. $zip .'" class="input" />
                </li>  
                
                <li class="left">
                    <p>Home Phone</p>
                    <input type="text" id="h_phone" name="h_phone" value="'. $h_phone .'" class="input" />
                </li>
                <li class="right">
                    <p>Work Phone</p>
                    <input type="text" id="w_phone" name="w_phone" value="'. $w_phone .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Mobile Phone</p>
                    <input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
                </li>
                <li class="right">
                    <p>Fax</p>
                    <input type="text" id="fax" name="fax" value="'. $fax .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Email <span class="requiredfieldindicate">*</span></p>
                    <input type="text" id="email" name="email" value="'. $email .'" class="input" />
                </li>
            </ul>                                 
            <div class="clear"></div>
        	</div>
    	</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Boat Information</span></div>
        <div class="singleblock_box singleblock_box_h">
        	<ul class="form">
            	<li class="left" id="type_id_heading">
                    <p>Category</p>
                    <select class="my-dropdown2" name="category_id" id="category_id">
                        <option value="">Select</option>
                        '.
                        $yachtclass->get_category_combo($category_id, 1, 1)
                        .'
                    </select>
                </li>
                <li class="right" id="year_heading">
                    <p>Year</p>
                    <select class="my-dropdown2" id="year" name="year">
                        <option value="">Select</option>
                        '.
                        $yachtclass->get_year_combo($year, 1) .'
                    </select>
                </li>
                
                <li class="left">
                    <p>Manufacturer</p>
                    <input type="text" id="manufacturer_name" name="manufacturer_name" value="'. $manufacturer_name .'" class="input" />
                </li>
                <li class="right">
                    <p>Model</p>
                    <input type="text" id="model" name="model" value="'. $model .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Length</p>
                    <input type="text" id="length" name="length" value="'. $length .'" class="input" />
                </li>                
                <li class="right">
                    <p>Beam</p>
                    <input type="text" id="beam" name="beam" value="'. $beam .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Height</p>
                    <input type="text" id="height" name="height" value="'. $height .'" class="input" />
                </li>
                <li class="right">
                    <p>Weight</p>
                    <input type="text" id="weight" name="weight" value="'. $weight .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Draft</p>
                    <input type="text" id="draft" name="draft" value="'. $draft .'" class="input" />
                </li>
                <li class="right">
                    <p>Mast Length</p>
                    <input type="text" id="mast_length" name="mast_length" value="'.  $mast_length .'" class="input" />
                </li>                    
            </ul>                              
            <div class="clear"></div>
        </div>
    	</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Schedule Preference</span></div>
        <div class="singleblock_box singleblock_box_h">        
            <ul class="form">
                <li class="left">
                    <p>Requested Pick Up Date (mm/dd/yyyy)</p>
                    <input defaultdateset="'. date("m-d-Y") .'" rangeyear="'. date("Y") .':'. (date("Y") + 1) .'" type="text" id="pickupdate" name="pickupdate" value="'. $pickupdate .'" class="date-field-c input2" />
                </li>                
            </ul>                                 
            <div class="clear"></div>
        </div>
    	</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Pickup Location</span></div>
        <div class="singleblock_box singleblock_box_h">        
            <ul class="form">
                <li class="left" id="pickup_location_heading">
                    <p>Pickup Location</p>
                    <select class="my-dropdown2" id="pickup_location" name="pickup_location">
                        '. $this->get_pick_up_location($pickup_location) .'
                    </select>
                </li>
                <li class="right">
                    <p>Marina / Dealer Name</p>
                    <input type="text" id="pick_marina_dealer_name" name="pick_marina_dealer_name" value="'. $pick_marina_dealer_name .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Contact Name</p>
                    <input type="text" id="pick_contact_name" name="pick_contact_name" value="'. $pick_contact_name .'" class="input" />
                </li>
                <li class="right">
                    <p>Phone Number</p>
                    <input type="text" id="pick_phone" name="pick_phone" value="'. $pick_phone .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Pickup Address</p>
                    <input type="text" id="pick_address" name="pick_address" value="'. $pick_address .'" class="input" />
                </li>
                <li class="right">
                    <p>City / Town</p>
                    <input type="text" id="pick_city" name="pick_city" value="'. $pick_city .'" class="input" />
                </li>
                
                <li class="left">
                    <p>State</p>
                    <input type="text" id="pick_state" name="pick_state" value="'. $pick_state .'" class="input" />
                </li>
                <li class="right">
                    <p>Zipcode</p>
                    <input type="text" id="pick_zip" name="pick_zip" value="'. $pick_zip .'" class="input" />
                </li>                
            </ul>                                 
            <div class="clear"></div>
        </div>
    	</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Drop Off Location</span></div>
        <div class="singleblock_box singleblock_box_h">        
            <ul class="form">
                <li class="left">
                    <p>Drop Off Location</p>
                    <input type="text" id="dropoff_location" name="dropoff_location" value="'. $dropoff_location .'" class="input" />
                </li>                       
                <li class="right">
                    <p>Contact Name</p>
                    <input type="text" id="drop_contact_name" name="drop_contact_name" value="'. $drop_contact_name .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Phone Number</p>
                    <input type="text" id="drop_phone" name="drop_phone" value="'. $drop_phone .'" class="input" />
                </li>                
                <li class="right">
                    <p>Address</p>
                    <input type="text" id="drop_address" name="drop_address" value="'. $drop_address .'" class="input" />
                </li>
                
                <li class="left">
                    <p>City / Town</p>
                    <input type="text" id="drop_city" name="drop_city" value="'. $drop_city .'" class="input" />
                </li>                
                <li class="right">
                    <p>State</p>
                    <input type="text" id="drop_state" name="drop_state" value="'. $drop_state .'" class="input" />
                </li>
                
                <li class="left">
                    <p>Zipcode</p>
                    <input type="text" id="drop_zip" name="drop_zip" value="'. $drop_zip .'" class="input" />
                </li>                
            </ul>                                 
            <div class="clear"></div>
        </div>
    	</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">
        <div class="singleblock_heading"><span>Special Instructions / Comments</span></div>
        <div class="singleblock_box singleblock_box_h">        
            <ul class="form">
                <li>                    
                    <textarea name="special_comments" id="special_comments" rows="1" cols="1" class="comments">'. $special_comments .'</textarea>
                </li>               
            </ul>                                 
            <div class="clear"></div>
        </div>
    	</div>
		<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
	   ';
	   
	   $returntext .= '
	   <div class="singleblock">			
			<input type="submit" value="Confirm and Send" class="button" />
			<input type="button" value="Cancel" class="button cancel" />
	   </div>
	   </form>
	   ';
	   
	   $returntext .= '
	   <script type="text/javascript">
	   $(document).ready(function(){
		   $("#boattransportform-ff").submit(function(){
			   var all_ok = "y";
			   var setfocus = "n";
			   
			   //Contact Information	    
			   if (!field_validation_border("fname", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "fname");
			   }
			   
			   if (!field_validation_border("lname", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "lname");
			   }
					   
			   if (!field_validation_border("email", 2, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "email"); 		   
			   }
			   
			   //Boat Information
			   
			   //Schedule Preference		   
			   if (!datefield_validation_border("pickupdate", "", 0)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "pickupdate"); 		   
			   }
			   
			   //Pickup Location		   
			   
			   //Drop Off Location		   	   
			   
			   if (all_ok == "n"){            
					return false;
			   }
			   return true;
		   });
		   
		   $(".cancel").click(function(){  
				history.back(-1);
		   });
		   
	   });
	   </script>
	   ';	   
	   return $returntext;
	}
	
	public function submit_boat_transpost_form(){
		if(($_POST['fcapi'] == "boattransport")){
			global $db, $cm, $yachtclass, $sdeml;
			
			//form field collect
			$p_ar = $_POST;
			foreach($p_ar AS $key => $val){
				${$key} = $val;
				if ($key != "resourceid" AND $key != "lno"){
			
					if ($key == "year"
						OR $key == "category_id"      
					){
						${$key} = round(${$key}, 0);
					}else{
						//no format
					}
				}
			}
			//end
			
			//create the session
			$datastring = $cm->session_field_boat_transport();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			$pickupdate_a = $cm->set_date_format($pickupdate);
			
			//checking		    
			if ($resourceid > 0){
				$red_pg = $yachtclass->resource_form_page_url($resourceid, $lno);
			}else{
				$$red_pg = $_SESSION["s_backpage"];
			}
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			
			if ($pickupdate != ""){
				$cm->check_date_validation_common($pickupdate_a, $red_pg, 'fr_');
			}
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
		    }
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$special_comments = nl2br($special_comments);
			$boat_category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
			
			$messagedetails = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Contact Information</strong></td>
				  </tr>
					
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">First Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City / Town:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Zipcode:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Home Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($h_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Work Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($w_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mobile Phone:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
				  </tr> 
				
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Boat Information</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Category:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_type_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($year, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacturer:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($manufacturer_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model, 1) .'</td>
				  </tr>
					
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($length, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Beam:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($beam, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Height:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($height, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Weight:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($weight, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Draft:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($draft, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Mast Length:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($mast_length, 1) .'</td>
				  </tr>
				  
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Schedule Preference</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Requested Pick Up Date (mm/dd/yyyy):</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pickupdate, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Pickup Location</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Pickup Location:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pickup_location, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Marina / Dealer Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_marina_dealer_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Contact Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_contact_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone Number:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Pickup Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City / Town:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Zipcode:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($pick_zip, 1) .'</td>
				  </tr>
				  
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" colspan="2"><strong>Drop Off Location</strong></td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Drop Off Location:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($dropoff_location, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Contact Name:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_contact_name, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone Number:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_phone, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Pickup Address:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_address, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">City / Town:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_city, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">State:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_state, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Zipcode:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($drop_zip, 1) .'</td>
				  </tr>
				  
				  <tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Special Instructions / Comments:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $special_comments .'</td>
				  </tr>
				</table>
				';
				
				$send_ml_id = 2;
				$email_ar = $cm->get_table_fields('tbl_brokerage_services_email', 'agent_name, agent_email, cc_email, email_subject, pdes', $send_ml_id);
				$agent_name = $email_ar[0]["agent_name"];
				$agent_email = $email_ar[0]["agent_email"];
				$cc_email = $email_ar[0]["cc_email"];
				$mail_subject = $email_ar[0]["email_subject"];
				$msg = $email_ar[0]["pdes"];
				$companyname = $cm->sitename;
				
				$msg = str_replace("#name#", $cm->filtertextdisplay($agent_name), $msg);
				$msg = str_replace("#messagedetails#", $messagedetails, $msg);
				$msg = str_replace("#companyname#", $companyname, $msg);
				$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
				
				$mail_fm = $cm->admin_email();
				$mail_to = $cm->filtertextdisplay($agent_email);
				$mail_cc = $cm->admin_email_to();
				if ($cc_email != ""){ $mail_cc = $mail_cc . ', ' . $cc_email; }
				$mail_reply = $cm->filtertextdisplay($email);
				$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
				
				$_SESSION["thnk"] = $this->display_message(2);
				header('Location: '. $cm->site_url .'/thankyou/');
				exit;
		}
	}
	
	//trade in welcome form
	public function display_tradein_welcome_form($argu = array()){
		global $db, $cm, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$shortversion = round($argu["shortversion"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$datastring = $cm->session_field_tradein_welcome();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		$start_content = '<div class="trade-start clearfixmain"><span class="uppercase textcolor1">Free</span> Trade valuation, <span class="textcolor2">YachtBrasil</span> offers the best deal for your used yacht in exchange of one of our new yachts.</div>';
		$end_content = '<div class="trade-end clearfixmain">Disclosure: Non obligation trade-in valuation. Your personal information may not be disclosure to third parties.</div>';
		
		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="tradeinwelcome-ff" name="tradeinwelcome-ff">
		<input type="hidden" value="'. $shortversion .'" id="shortversion" name="shortversion" />
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="submittradeinwelcomeform" />
		';
		
		if ($shortversion == 1){
			//siderbar - inner
			$returntext = '
			<h2 class="sidebartitle">Trade Evaluation</h2>
			<div class="leftrightcolsection notopborder clearfixmain">
			'. $start_content .'
			'. $formstart .'
			
			<ul class="form">
				<li class="left"><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
				<li class="right"><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
				<li><input type="text" class="input" id="boat_make_model" name="boat_make_model" value="'. $boat_make_model .'" placeholder="Manufacture / Model" /></li>
				<li class="left"><input type="text" class="input" id="boat_year" name="boat_year" value="'. $boat_year .'" placeholder="Year" /></li>
				<li class="right"><input type="text" class="input" id="boat_length" name="boat_length" value="'. $boat_length .'" placeholder="Length" /></li>
				<li>'. $end_content .'</li>
				<li>'. $captchaclass->call_captcha() .'</li>
				<li><button type="submit" class="button" value="Submit">Request your Free Valuation Today!</button>	</li>
			</ul>
			<div class="fomrsubmit-result com_none"></div>			
			</form>			
			</div>
			';
		}elseif ($shortversion == 2){
			//homepage
			$returntext = '
			<div class="tradeinwelcome clearfixmain">
				<h2 class="borderstyle1">Trade Evaluation</h2>
				
				'. $start_content .'
			
				'. $formstart . '
				
				<ul class="form">
					<li class="left"><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li class="right"><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><input type="text" class="input" id="boat_make_model" name="boat_make_model" value="'. $boat_make_model .'" placeholder="Manufacture / Model" /></li>
					<li class="left"><input type="text" class="input" id="boat_year" name="boat_year" value="'. $boat_year .'" placeholder="Year" /></li>
					<li class="right"><input type="text" class="input" id="boat_length" name="boat_length" value="'. $boat_length .'" placeholder="Length" /></li>
					<li>'. $end_content .'</li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Request your Free Valuation Today!</button>	</li>
				</ul>							
				<div class="fomrsubmit-result com_none"></div>
				</form>
				
			</div>	
			';
		}elseif ($shortversion == 3){
			//pop-up
			$returntext = '
			<div class="section clearfixmain">
				<h3><span>Trade-in</span> Welcome</h3>				
				'. $start_content .'			
				'. $formstart . '
				
				<ul class="form">
					<li class="left"><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li class="right"><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><input type="text" class="input" id="boat_make_model" name="boat_make_model" value="'. $boat_make_model .'" placeholder="Manufacture / Model" /></li>
					<li class="left"><input type="text" class="input" id="boat_year" name="boat_year" value="'. $boat_year .'" placeholder="Year" /></li>
					<li class="right"><input type="text" class="input" id="boat_length" name="boat_length" value="'. $boat_length .'" placeholder="Length" /></li>
					<li>'. $end_content .'</li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Request your Free Valuation Today!</button>	</li>
				</ul>							
				</form>
			</div>	
			';
		}else{
			//inner page
			$returntext =  $start_content .'
			<div class="singleblock_box clearfixmain">
				'. $formstart .'
				
				<ul class="form">				          
					<li>
						<p>Name <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="name" name="name" value="'. $name .'" />
					</li>
					
					<li>
						<p>Email <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="email" name="email" value="'. $email .'" />
					</li>
				
					<li>
						<p>Manufacture / Model <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="boat_make_model" name="boat_make_model" value="'. $boat_make_model .'" />
					</li>
					
					<li>
						<p>Year</p>
						<input type="text" class="input" id="boat_year" name="boat_year" value="'. $boat_year .'" />
					</li>
					
					<li>
						<p>Length</p>
						<input type="text" class="input" id="boat_length" name="boat_length" value="'. $boat_length .'" />
					</li>
					
					<li>'. $end_content .'</li>
					
					<li>'. $captchaclass->call_captcha(). '</li>     
			
					<li class="submit">
						<button type="submit" class="button" value="Submit">Submit</button>
					</li>
					
					<li>
						<p><span class="requiredtext">*</span> = Mandatory fields</p>            
					</li>
				</ul>
				</form>				
			</div>';
		}
		
		$returntext .= '
	   <script type="text/javascript">
	   $(document).ready(function(event){
		   $("#tradeinwelcome-ff").submit(function(){
			   var all_ok = "y";
			   var setfocus = "n";
			   
			   var shortversion = $("#shortversion").val();
			   shortversion = parseInt(shortversion);
	    
			   if (!field_validation_border("name", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "name");
			   }
					   
			   if (!field_validation_border("email", 2, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "email"); 		   
			   }
			   
			   if (!field_validation_border("boat_make_model", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "boat_make_model");
			   }			   
			   
			   if (all_ok == "n"){            
					return false;
			   }
			   
			   if (shortversion > 0){
					//Ajax submit
					var form = $(this);
					$.ajax({
					  type: form.attr("method"),
					  url: form.attr("action"),
					  data: form.serialize()
					}).done(function(){
					  $(".fomrsubmit-result").addClass("success");
					  $(".fomrsubmit-result").html("Email sent successfully");
					  $(".fomrsubmit-result").removeClass("com_none");
					  
					  $("#name").val("");
					  $("#email").val("");
					  $("#boat_make_model").val("");
					  $("#boat_year").val("");
					  $("#boat_length").val("");
					}).fail(function(){
					  $(".fomrsubmit-result").addClass("error");
					  $(".fomrsubmit-result").html("ERROR! Please try again");
					  $(".fomrsubmit-result").removeClass("com_none");
					});
					
					event.preventDefault();
				}else{
					return true;
				}			   
			   
		   });		   
	   });
	   </script>
	   ';		
		return $returntext;
	}
	
	//submit trade in welcome form
	public function submit_tradein_welcome_form(){
		if(($_POST['fcapi'] == "submittradeinwelcomeform")){					
			global $db, $cm, $leadclass, $sdeml;			
			
			//field		
			$shortversion = round($_POST["shortversion"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$pgid = round($_POST["pgid"], 0);
			$name = $_POST["name"];
			$email = $_POST["email"];
			$boat_make_model = $_POST["boat_make_model"];
			$boat_year = $_POST["boat_year"];
			$boat_length = $_POST["boat_length"];
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_tradein_welcome();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-talk-to-specialist") . "?make_id=" . $make_id;
				$sorrypage = $cm->get_page_url(0, "popsorry");
			  	$thankyoupage = $cm->get_page_url(0, "popthankyou");
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			//field data checking	
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_make_model, '', 'Manufacture / Model', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
				
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//email message
			$contactsubmission = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Trade-In Welcome Email</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($name, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Manufacture / Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make_model, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Length:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_length, 1) .'</td>
				</tr>
			</table>          
			';
			
			//add to lead
			$form_type = 9;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => '',
				"message" => $contactsubmission,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send to admin
			$send_ml_id = 22;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#formdatasubmission#", $contactsubmission, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);			
			
			$mail_fm =  $cm->admin_email();
			$mail_to =  $cm->admin_email_to();
			$mail_bcc = "";
			$mail_reply =  $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
						
			//send to user
			$send_ml_id = 23;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
						
			$msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
			$msg = str_replace("#promocode#", $cm->filtertextdisplay($promo_code), $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$msg = str_replace("#companyphone#", $companyphone, $msg);
			$msg = str_replace("#companyemail#", $companyemail, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
			
			if ($frompopup == 1){
				$_SESSION["thnk"] = $msg;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				if ($shortversion > 0){
					return 1;
					exit;
				}else{			
					$_SESSION["s_pgid"] = $pgid;
					header('Location: ' . $cm->get_page_url($pgid, 'page'));
					exit;
				}
			}
		}
	}
	
	//Talk To A Specialist form
	public function display_talk_to_specialist_form($argu = array()){
		global $db, $cm, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$shortversion = round($argu["shortversion"], 0);
		$frompopup = round($argu["frompopup"], 0);
		$make_id = round($argu["make_id"], 0);
		
		$datastring = $cm->session_field_talk_to_specialist();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		if ($make_id > 0){
			$make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $make_id);
			$custom_button_text = "Talk To A Certified ". $make_name ." Specialist";
		}else{
			$custom_button_text = "Talk To A Specialist";
		}

		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="talktospecialist-ff" name="talktospecialist-ff">
		<label class="com_none" for="email2">email2</label>
		<input type="hidden" value="'. $shortversion .'" id="shortversion" name="shortversion" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $make_id .'" id="make_id" name="make_id" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="submittalktospecialistform" />
		';
		
		if ($shortversion == 1){
			//siderbar - inner
			$returntext = '
			<h2 class="sidebartitle">'. $custom_button_text .'</h2>
			<div class="leftrightcolsection notopborder clearfixmain">
				'. $formstart .'
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="preferred_date">Date</label><input defaultdateset="'. date("m-d-Y") .'" rangeyear="'. date("Y") .':'. (date("Y") + 1) .'" type="text" id="preferred_date" name="preferred_date" value="'. $preferred_date .'" class="date-field-c input2" placeholder="Appointment Date" /></li>
					<li><label class="com_none" for="preferred_time">Time</label><input type="text" class="input timepick" id="preferred_time" name="preferred_time" value="'. $preferred_time .'" placeholder="Appointment Time" /></li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>
				<div class="fomrsubmit-result com_none"></div>			
				</form>			
			</div>
			';
		}elseif ($shortversion == 2){
			//homepage
			$returntext = '
			<div class="tradeinwelcome clearfixmain">
				<h3>'. $custom_button_text .'</h3>			
				'. $formstart . '
				
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="preferred_date">Date</label><input defaultdateset="'. date("m-d-Y") .'" rangeyear="'. date("Y") .':'. (date("Y") + 1) .'" type="text" id="preferred_date" name="preferred_date" value="'. $preferred_date .'" class="date-field-c input2" placeholder="Appointment Date" /></li>
					<li><label class="com_none" for="preferred_time">Time</label><input type="text" class="input timepick" id="preferred_time" name="preferred_time" value="'. $preferred_time .'" placeholder="Appointment Time" /></li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>							
				<div class="fomrsubmit-result com_none"></div>
				</form>
			</div>	
			';
		}elseif ($shortversion == 3){
			//pop-up
			$returntext = '
			<div class="section clearfixmain">
				<h3>'. $custom_button_text .'</h3>			
				'. $formstart . '				
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="preferred_date">Date</label><input defaultdateset="'. date("m-d-Y") .'" rangeyear="'. date("Y") .':'. (date("Y") + 1) .'" type="text" id="preferred_date" name="preferred_date" value="'. $preferred_date .'" class="date-field-c input2" placeholder="Appointment Date" /></li>
					<li><label class="com_none" for="preferred_time">Time</label><input type="text" class="input timepick" id="preferred_time" name="preferred_time" value="'. $preferred_time .'" placeholder="Appointment Time" /></li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>
				</form>
			</div>	
			';
		}else{
			//inner page
			$returntext = '
			<div class="singleblock_box clearfixmain">
				'. $formstart .'
				
				<ul class="form">				          
					<li>
						<p><label for="name">Name</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="name" name="name" value="'. $name .'" />
					</li>
					
					<li>
						<p><label for="email">Email</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="email" name="email" value="'. $email .'" />
					</li>
				
					<li>
						<p><label for="phone">Phone</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
					</li>
					
					<li>
						<p><label for="preferred_date">Appointment Date</label> <span class="requiredtext">*</span></p>
						<input defaultdateset="'. date("m-d-Y") .'" rangeyear="'. date("Y") .':'. (date("Y") + 1) .'" type="text" id="preferred_date" name="preferred_date" value="'. $preferred_date .'" class="date-field-c input2" />
					</li>
					<li>
						<p><label for="preferred_time">Appointment Time</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input timepick" id="preferred_time" name="preferred_time" value="'. $preferred_time .'" placeholder="Appointment Time" />
					</li>
					
					<li>'. $captchaclass->call_captcha(). '</li>     
			
					<li class="submit">
						<button type="submit" class="button" value="Submit">Send</button>
					</li>
					
					<li>
						<p><span class="requiredtext">*</span> = Mandatory fields</p>            
					</li>
				</ul>
				</form>				
			</div>';
		}
		
		$returntext .= '
	   <script type="text/javascript">
	   $(document).ready(function(event){
		   $(".timepick").timepicker({ "timeFormat": "h:i A", "minTime": "8:00 AM",  "maxTime": "6:00 PM", useSelect: true, className: "select" });
		   $("#talktospecialist-ff").submit(function(){
			   var all_ok = "y";
			   var setfocus = "n";
			   
			   var shortversion = $("#shortversion").val();
			   shortversion = parseInt(shortversion);
	    
			   if (!field_validation_border("name", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "name");
			   }
					   
			   if (!field_validation_border("email", 2, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "email"); 		   
			   }
			   
			   if (!field_validation_border("phone", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "phone");
			   }
			   
			   if (!field_validation_border("preferred_date", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "preferred_date");
			   }			   
			   
			   if (all_ok == "n"){            
					return false;
			   }
			   
			   if (shortversion > 0){
					//Ajax submit
					var form = $(this);
					$.ajax({
					  type: form.attr("method"),
					  url: form.attr("action"),
					  data: form.serialize()
					}).done(function(){
					  $(".fomrsubmit-result").addClass("success");
					  $(".fomrsubmit-result").html("Email sent successfully");
					  $(".fomrsubmit-result").removeClass("com_none");
					  
					  $("#name").val("");
					  $("#email").val("");
					  $("#phone").val("");
					  $("#preferred_date").val("");					 
					}).fail(function(){
					  $(".fomrsubmit-result").addClass("error");
					  $(".fomrsubmit-result").html("ERROR! Please try again");
					  $(".fomrsubmit-result").removeClass("com_none");
					});
					
					event.preventDefault();
				}else{
					return true;
				}
		   });		   
	   });
	   </script>
	   ';		
		return $returntext;
	}
	
	//submit Talk To A Specialist form
	public function submit_talk_to_specialist_form(){
		if(($_POST['fcapi'] == "submittalktospecialistform")){					
			global $db, $cm, $leadclass, $sdeml;			
			
			//field		
			$shortversion = round($_POST["shortversion"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$pgid = round($_POST["pgid"], 0);
			$make_id = round($_POST["make_id"], 0);
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			
			$preferred_date = $_POST["preferred_date"];
			$preferred_time = $_POST["preferred_time"];
			
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_talk_to_specialist();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-talk-to-specialist") . "?make_id=" . $make_id;
				$sorrypage = $cm->get_page_url(0, "popsorry");
			  	$thankyoupage = $cm->get_page_url(0, "popthankyou");
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			//field data checking	
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($preferred_date, '', 'Preferred Date', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($preferred_time, '', 'Preferred Time', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			if ($make_id > 0){
				$make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $make_id);
				$custom_button_text = "Talk To A Certified ". $make_name ." Specialist";
			}else{
				$custom_button_text = "Talk To A Specialist";
			}
				
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//email message
			$contactsubmission = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>'. $custom_button_text .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Preferred Date:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($preferred_date, 1) .'</td>
				</tr>	
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Preferred Time:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($preferred_time, 1) .'</td>
				</tr>
			</table>          
			';
			
			//add to lead
			$form_type = 10;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $contactsubmission,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send to admin
			$send_ml_id = 24;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#formdatasubmission#", $contactsubmission, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);			
			
			$mail_fm =  $cm->admin_email();
			$mail_to =  $cm->admin_email_to();
			$mail_bcc = "";
			$mail_reply =  $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
						
			//send to user
			$send_ml_id = 25;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
						
			$msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
			$msg = str_replace("#promocode#", $cm->filtertextdisplay($promo_code), $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$msg = str_replace("#companyphone#", $companyphone, $msg);
			$msg = str_replace("#companyemail#", $companyemail, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
			
			if ($frompopup == 1){
				$_SESSION["thnk"] = $msg;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				if ($shortversion > 0){
					return 1;
					exit;
				}else{			
					$_SESSION["s_pgid"] = $pgid;
					header('Location: ' . $cm->get_page_url($pgid, 'page'));
					exit;
				}
			}
		}
	}
	
	//Ask For Brochure form
	public function display_ask_for_brochure_form($argu = array()){
		global $db, $cm, $modelclass, $captchaclass;
		$pgid = round($argu["pgid"], 0);
		$shortversion = round($argu["shortversion"], 0);
		$frompopup = round($argu["frompopup"], 0);
		$make_id = round($argu["make_id"], 0);
		
		$datastring = $cm->session_field_ask_for_brochure();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		if ($make_id > 0){
			$make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $make_id);
			$custom_button_text = "Ask For ". $make_name ." Brochure";
		}else{
			$custom_button_text = "Ask For Brochure";
		}

		$formstart = '
		<form method="post" action="'. $cm->folder_for_seo .'" id="askforbrochure-ff" name="askforbrochure-ff">
		<label class="com_none" for="email2">email2</label>
		<input type="hidden" value="'. $shortversion .'" id="shortversion" name="shortversion" />
		<input type="hidden" value="'. $frompopup .'" id="frompopup" name="frompopup" />
		<input type="hidden" value="'. $pgid .'" id="pgid" name="pgid" />
		<input type="hidden" value="'. $make_id .'" id="make_id" name="make_id" />
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="submitaskforbrochureform" />
		';
		
		if ($shortversion == 1){
			//siderbar - inner
			$returntext = '
			<h2 class="sidebartitle">'. $custom_button_text .'</h2>
			<div class="leftrightcolsection notopborder clearfixmain">
				'. $formstart .'
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="model_name">Model</label>
					<select id="model_id" name="model_id" class="select">
					<option value="">Select Model</option>
					'. $modelclass->get_model_combo($model_id, 1) .'
					</select>
					</li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>
				<div class="fomrsubmit-result com_none"></div>			
				</form>			
			</div>
			';
		}elseif ($shortversion == 2){
			//homepage
			$returntext = '
			<div class="tradeinwelcome clearfixmain">
				<h3>'. $custom_button_text .'</h3>			
				'. $formstart . '
				
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="model_name">Model</label>
					<select id="model_id" name="model_id" class="select">
					<option value="">Select Model</option>
					'. $modelclass->get_model_combo($model_id, 1) .'
					</select>
					</li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>							
				<div class="fomrsubmit-result com_none"></div>
				</form>
			</div>	
			';
		}elseif ($shortversion == 3){
			//pop-up
			$returntext = '
			<div class="section clearfixmain">
				<h3>'. $custom_button_text .'</h3>			
				'. $formstart . '				
				<ul class="form">
					<li><label class="com_none" for="name">Name</label><input type="text" class="input" id="name" name="name" value="'. $name .'" placeholder="Name" /></li>
					<li><label class="com_none" for="email">Email</label><input type="text" class="input" id="email" name="email" value="'. $email .'" placeholder="Email Address" /></li>
					<li><label class="com_none" for="phone">Phone</label><input type="text" class="input" id="phone" name="phone" value="'. $phone .'" placeholder="Phone" /></li>
					<li><label class="com_none" for="model_name">Model</label>
					<select id="model_id" name="model_id" class="select">
					<option value="">Select Model</option>
					'. $modelclass->get_model_combo($model_id, 1) .'
					</select>
					</li>
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Send</button>	</li>
				</ul>
				</form>
			</div>	
			';
		}else{
			//inner page
			$returntext = '
			<div class="singleblock_box clearfixmain">
				'. $formstart .'
				
				<ul class="form">				          
					<li>
						<p><label for="name">Name</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="name" name="name" value="'. $name .'" />
					</li>
					
					<li>
						<p><label for="email">Email</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="email" name="email" value="'. $email .'" />
					</li>
				
					<li>
						<p><label for="phone">Phone</label> <span class="requiredtext">*</span></p>
						<input type="text" class="input" id="phone" name="phone" value="'. $phone .'" />
					</li>
					
					<li>
						<p><label for="name">model_name</label> <span class="requiredtext">*</span></p>
						<select id="model_id" name="model_id" class="select">
						<option value="">Select Model</option>
						'. $modelclass->get_model_combo($model_id, 1) .'
						</select>
					</li>
					
					<li>'. $captchaclass->call_captcha(). '</li>     
			
					<li class="submit">
						<button type="submit" class="button" value="Submit">Send</button>
					</li>
					
					<li>
						<p><span class="requiredtext">*</span> = Mandatory fields</p>            
					</li>
				</ul>
				</form>				
			</div>';
		}
		
		$returntext .= '
	   <script type="text/javascript">
	   $(document).ready(function(event){
		   $("#askforbrochure-ff").submit(function(){
			   var all_ok = "y";
			   var setfocus = "n";
			   
			   var shortversion = $("#shortversion").val();
			   shortversion = parseInt(shortversion);
	    
			   if (!field_validation_border("name", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "name");
			   }
					   
			   if (!field_validation_border("email", 2, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "email"); 		   
			   }
			   
			   if (!field_validation_border("phone", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "phone");
			   }
			   
			   if (!field_validation_border("model_id", 1, 1)){ 
					all_ok = "n"; 
					setfocus = set_field_focus(setfocus, "model_id");
			   }			   
			   
			   if (all_ok == "n"){            
					return false;
			   }
			   
			   if (shortversion > 0){
					//Ajax submit
					var form = $(this);
					$.ajax({
					  type: form.attr("method"),
					  url: form.attr("action"),
					  data: form.serialize()
					}).done(function(){
					  $(".fomrsubmit-result").addClass("success");
					  $(".fomrsubmit-result").html("Email sent successfully");
					  $(".fomrsubmit-result").removeClass("com_none");
					  
					  $("#name").val("");
					  $("#email").val("");
					  $("#phone").val("");	
					  $("#model_name").val("");				 
					}).fail(function(){
					  $(".fomrsubmit-result").addClass("error");
					  $(".fomrsubmit-result").html("ERROR! Please try again");
					  $(".fomrsubmit-result").removeClass("com_none");
					});
					
					event.preventDefault();
				}else{
					return true;
				}
		   });		   
	   });
	   </script>
	   ';		
		return $returntext;
	}
	
	//submit Ask For Brochure form
	public function submit_ask_for_brochure_form(){
		if(($_POST['fcapi'] == "submitaskforbrochureform")){					
			global $db, $cm, $modelclass, $leadclass, $sdeml;			
			
			//field		
			$shortversion = round($_POST["shortversion"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$pgid = round($_POST["pgid"], 0);
			$make_id = round($_POST["make_id"], 0);
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$model_id = round($_POST["model_id"], 0);
			//$model_name = $_POST["model_name"];
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_ask_for_brochure();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			if ($frompopup == 1){
				//pop
				$red_pg = $cm->get_page_url(0, "pop-ask-for-brochure") . "?make_id=" . $make_id;
				$sorrypage = $cm->get_page_url(0, "popsorry");
			  	$thankyoupage = $cm->get_page_url(0, "popthankyou");
			}else{
				//normal
				$red_pg = $_SESSION["s_backpage"];
			}
			
			//field data checking	
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($model_id, '', 'Model', $red_pg, '', '', 1, 'fr_');
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			if ($make_id > 0){
				$make_name = $cm->get_common_field_name("tbl_manufacturer", "name", $make_id);
				$custom_button_text = "Ask For ". $make_name ." Brochure";
			}else{
				$custom_button_text = "Ask For Brochure";
			}
			
			$model_name = $modelclass->get_model_combo($model_id);
				
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//email message
			$contactsubmission = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>'. $custom_button_text .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($model_name, 1) .'</td>
				</tr>
			</table>          
			';
			
			//add to lead
			$form_type = 11;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $contactsubmission,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send to admin
			$send_ml_id = 26;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#formdatasubmission#", $contactsubmission, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);			
			
			$mail_fm =  $cm->admin_email();
			$mail_to =  $cm->admin_email_to();
			$mail_bcc = "";
			$mail_reply =  $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
						
			//send to user
			$send_ml_id = 27;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
						
			$msg = str_replace("#name#", $cm->filtertextdisplay($name), $msg);
			$msg = str_replace("#promocode#", $cm->filtertextdisplay($promo_code), $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$msg = str_replace("#companyphone#", $companyphone, $msg);
			$msg = str_replace("#companyemail#", $companyemail, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, '');
			
			if ($frompopup == 1){
				$_SESSION["thnk"] = $msg;
				header('Location: '. $thankyoupage);
				exit;
			}else{			
				if ($shortversion > 0){
					return 1;
					exit;
				}else{			
					$_SESSION["s_pgid"] = $pgid;
					header('Location: ' . $cm->get_page_url($pgid, 'page'));
					exit;
				}
			}
		}
	}
	
	//lead checkout form
	public function lead_checkout_inquiry_type_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "", "oth" => 0);
		$val_ar[] = array("name" => "Yes, contact me to purchase a yacht like this with sales price", "oth" => 0);
		$val_ar[] = array("name" => "Boat Show RSVP", "oth" => 0);
		$val_ar[] = array("name" => "Email me when Price Drops", "oth" => 0);
		$val_ar[] = array("name" => "Yes, I need financing info to purchase a yacht like this.", "oth" => 0);
		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_lead_checkout_inquiry_type_checkbox($param = array()){
        global $db;
		$val_ar = json_decode($this->lead_checkout_inquiry_type_val());		
		$returntext = '';
  
        foreach($val_ar as $ar_key => $val_row){
            $cname = $val_row->name;
			$oth = $val_row->oth;
			if ($ar_key > 0){
				$vck = '';
				if (in_array($cname, $param)){
					$vck = ' checked="checked"';
				}
				$returntext .= '<li><input class="checkbox" type="checkbox" name="enq_type[]" value="'. $ar_key .'"  id="enq_type'. $ar_key .'"'. $vck .' /> '. $cname .'</li>';
			}
        }
		
		if ($returntext != ""){
			$returntext = '<ul class="formcol_single">' . $returntext . '</ul>';
		}
				
		return $returntext;
    }
	
	public function get_lead_checkout_inquiry_type_ind_value($inquiry_type_id){
        global $db;
		$val_ar = json_decode($this->lead_checkout_inquiry_type_val());		
		$returntext = $val_ar[$inquiry_type_id]->name;
		return $returntext;
    }
	
	public function display_lead_checkout_form($argu = array()){
		global $db, $cm, $yachtclass, $captchaclass;
		//$servicerequest = $cm->filtertextdisplay($_REQUEST["servicerequest"]);		
		
		$brokerid = round($argu["brokerid"], 0);
		$boatid = round($argu["boatid"], 0);
		$servicerequest = round($_REQUEST["servicerequest"], 0);
		$frompopup = round($argu["frompopup"], 0);
		
		$boatnametext = '';
		if ($boatid > 0){
			$boatname = $yachtclass->yacht_name($boatid);
			$boatnametext = '<p>'. $boatname .'</p>';
		}
		
		$selected_enq = array();
		if ($servicerequest == 2){
			$enqval = $this->get_lead_checkout_inquiry_type_ind_value(4);
		}elseif ($servicerequest == 3){
			$enqval = $this->get_lead_checkout_inquiry_type_ind_value(3);
		}
		$selected_enq[] = $enqval;
		
		$datastring = $cm->session_field_lead_checkout_request();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
			${$key} = $val;
		}
		
		$returntext = '
		<h2>Customer Inquiry</h2>
		'. $boatnametext .'
		
	    <form method="post" action="'. $cm->folder_for_seo .'" id="leadcheckout-ff" name="leadcheckout-ff">
	    <input id="frompopup" name="frompopup" value="'. $frompopup .'" type="hidden" />
		<input id="brokerid" name="brokerid" value="'. $brokerid .'" type="hidden" />
		<input id="boatid" name="boatid" value="'. $boatid .'" type="hidden" />
		<input id="servicerequest" name="servicerequest" value="'. $servicerequest .'" type="hidden" />
		<input class="finfo" id="email2" name="email2" type="text" />
	    <input type="hidden" id="fcapi" name="fcapi" value="leadcheckoutsubmit" />
	    ';
		
		$returntext .= '
		<div class="singleblock clearfixmain">
			<ul class="form">
				<li>
					<p>First Name <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="fname" name="fname" value="'. $fname .'" class="input" />
				</li>
				
				<li>
					<p>Last Name <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="lname" name="lname" value="'. $lname .'" class="input" />
				</li>
				
				<li>
					<p>Email <span class="requiredfieldindicate">*</span></p>
					<input type="text" id="email" name="email" value="'. $email .'" class="input" />
				</li>
				
				<li>
					<p>Phone</p>
					<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
				</li>
				
				<li>
					<p>Is there anything we can help you with? <span class="requiredfieldindicate">*</span></p>
					<textarea name="comments" id="comments" rows="1" cols="1" class="comments">'. $comments .'</textarea>
				</li>
				
				<li>'. $this->get_lead_checkout_inquiry_type_checkbox($selected_enq) .'</li>
				
				<li>'. $captchaclass->call_captcha(). '</li>
				
				<li class="submit"><button type="submit" class="button" value="Submit">Submit</button></li>
			</ul>
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#leadcheckout-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				if (!field_validation_border("fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "fname");
				}
				
				if (!field_validation_border("lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "lname");
				}
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("comments", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "comments");
				}
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';
		
		return $returntext;
	}
	
	//Submit Lead Checkout form
	public function submit_lead_checkout_form(){
		if(($_POST['fcapi'] == "leadcheckoutsubmit")){
			global $db, $cm, $yachtclass, $sdeml;

			//get form fields
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$phone = $_POST["phone"];
			$email = $_POST["email"];
			$comments = $_POST["comments"];


			$brokerid = round($_POST["brokerid"], 0);
			$boatid = round($_POST["boatid"], 0);
			$servicerequest = round($_POST["servicerequest"], 0);

			$email2 = $_POST["email2"];
			//end

			//create the session
			$datastring = $cm->session_field_lead_checkout_request();
			$cm->create_session_for_form($datastring, $_POST);
			//end

			//checking
			//$red_pg = $_SESSION["s_backpage"];
			$red_pg = $cm->get_page_url(0, "pop-lead-checkout") . "?id=" . $brokerid . "&yid=" . $boatid . "&servicerequest=" . $servicerequest;
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($comments, '', 'Comments', $red_pg, '', '', 1, 'fr_');			
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end

			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end

			$cm->delete_session_for_form($datastring);
			
			//Enq
			$inquiry_type_text = '';
			$inquiry_type_ar = json_decode($this->lead_checkout_inquiry_type_val());
			foreach($_POST["enq_type"] as $inquiry_type_id_val){
				if ($inquiry_type_id_val > 0){
					$inquiry_type_name = $inquiry_type_ar[$inquiry_type_id_val] ->name;
					$inquiry_type_text .= $inquiry_type_name . ', ';
				}
			}
			if ($inquiry_type_text != ""){
				$inquiry_type_text = rtrim($inquiry_type_text, ', ');
			}
			//end

			$boat_info_text = '';
			if ($boatid > 0){
				//$boat_url = $cm->site_url . $cm->get_page_url($boatid, "yacht");
				$b_ar = array(
					"boatid" => $boatid, 
					"makeid" => 0, 
					"ownboat" => 0, 
					"feed_id" => "", 
					"getdet" => 1
				);
				$boat_url = $cm->site_url . $yachtclass->get_boat_details_url($b_ar);
				
				$boat_ar = $cm->get_table_fields("tbl_yacht", "manufacturer_id, model, year, yw_id", $boatid);
				$boat_ar = $boat_ar[0];

				$manufacturer_id = $boat_ar["manufacturer_id"];
				$boat_model = $boat_ar["model"];
				$boat_year = $boat_ar["year"];
				$yw_id = $boat_ar["yw_id"];

				$boat_make = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
				
				$yw_text = '';
				if ($yw_id > 0){
					$yw_text = '
					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">YW Boat ID:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $yw_id .'</td>
					</tr>
					';
				}

				$boat_info_text = '
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Selected Boat</strong></td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Make:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_make .'</td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_model .'</td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_year .'</td>
					</tr>
					'. $yw_text .'
				</table>
				';				
			}

			$comments = nl2br($comments);
			$fullname = $fname . ' ' . $lname;
	
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();

			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Is there anything we can help you with?</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($comments, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Inquiry For:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($inquiry_type_text, 1) .'</td>
				</tr>
			</table>
			'. $boat_info_text .'
			';
			//end

			//Lead Part
			if ($servicerequest == 4){
				$form_type = 19;
			}elseif ($servicerequest == 3){
				$form_type = 18;
			}elseif ($servicerequest == 2){
				$form_type = 17;
			}else{
				$form_type = 16;
			}

			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			//end

			//Send Email
			if ($servicerequest == 1 AND $brokerid > 0){
				//To Broker
				$result = $this->check_user_exist($brokerid, 0, 1);
				$row = $result[0];
				$b_fname = $row["fname"];
				$b_lname = $row["lname"];
				$b_email = $row["email"];
				$b_fullname = $b_fname . ' ' . $b_lname;

				$send_ml_id = 2;
				$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
				$ad_email_ar = (object)$ad_email_ar[0];
				$msg = $ad_email_ar->pdes;
				$mail_subject = $ad_email_ar->email_subject;
				$ad_cc_email = $ad_email_ar->cc_email;

				$msg = str_replace("#name#", $cm->filtertextdisplay($b_fullname), $msg);
				$msg = str_replace("#messagedetails#", $emailmessage, $msg);
				$msg = str_replace("#companyname#", $companyname, $msg);
				$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
				
				$mail_fm = $cm->admin_email();
				$mail_to = $cm->filtertextdisplay($b_email);
				$mail_cc = $ad_cc_email;
				$mail_reply = $cm->filtertextdisplay($email);
				$fromnamesender = $cm->filtertextdisplay($fullname);
				$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $fromnamesender);

			}else{
				//To Admin
				$ad_msg = '
				<p>The following information has been submitted through Lead Checkout form:<br />
				<br />
				#formdatasubmission#<br />
				<br />
				Sincerely,<br />
				#companyname#</p>
				';

				$ad_mail_subject = 'Lead Checkout Submission from #companyname#';
				$ad_cc_email = '';

				$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
				$ad_msg = str_replace("#formdatasubmission#", $emailmessage, $ad_msg);
				$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);

				$mail_fm = $cm->admin_email();
				$mail_to = $cm->admin_email_to();
				$mail_bcc = '';
				$mail_reply = $cm->filtertextdisplay($email);
				$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
				$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			}
			//end

			$pgid = 67;
			$redpageurl = $cm->get_page_url(0, "popthankyou") . "?servicerequest=" . $servicerequest;
			$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
			$_SESSION["thnk"] = $pagecontent;
			header('Location: ' . $redpageurl);
			exit;
		}
	}
	
	//Watch Price form
	public function display_watch_price_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		$boat_id = round($argu["boat_id"], 0);
		$frompopup = round($argu["frompopup"], 0);
		$pgid = round($argu["pgid"], 0);
		
		$datastring = $cm->session_field_watch_price();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($fname == "" AND $email == ""){
			//form not submitted
			$user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
			$name = $fname . ' ' . $lname;
		}		
		
		$returntext = '
		<h2>Watch Price</h2>
		
		<form method="post" action="'. $cm->folder_for_seo .'" id="watch-price-ff" name="watch-price-ff">
		<label class="com_none" for="email2">email2</label>
		<input class="finfo" id="email2" name="email2" type="text" />
		<input type="hidden" id="fcapi" name="fcapi" value="watchpricesubmit" />
		<input id="boat_id" name="boat_id" value="'. $boat_id .'" type="hidden" />	
		<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
		<input id="frompopup" name="frompopup" value="'. $frompopup .'" type="hidden" />
		';
		
		$returntext .= '	
		<div class="singleblock clearfixmain"> 
		<ul class="form">	   		
			<li>
				<p><label for="name">Name</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="name" name="name" value="'. $name .'" class="input" />
			</li>
						
			<li>
				<p><label for="email">Email Address</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="email" name="email" value="'. $email .'" class="input" />
			</li>
						
			<li>
				<p><label for="phone">Phone</label> <span class="requiredfieldindicate">*</span></p>
				<input type="text" id="phone" name="phone" value="'. $phone .'" class="input" />
			</li>			
			
			<li>'. $captchaclass->call_captcha(). '</li>
			<li class="submit"><button type="submit" class="button" value="Submit">Submit</button></li>		
		</ul>
		</div>
		';
		
		$returntext .= '
		</form>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#watch-price-ff").submit(function(){
				var all_ok = "y";
				var setfocus = "n";
				
				//contact
				if (!field_validation_border("name", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "name");
				}			
					
				if (!field_validation_border("email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "email");
				}
				
				if (!field_validation_border("phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "phone");
				}
				//end				
				
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
		});
		</script>
		';		
		return $returntext;
	}
	
	//Submit Watch Price form
	public function submit_watch_price_form(){
		if(($_POST['fcapi'] == "watchpricesubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$name = $_POST["name"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			
			$newsletter_subscribe = round($_POST["newsletter_subscribe"], 0);
			$boat_id = round($_POST["boat_id"], 0);				
			$pgid = round($_POST["pgid"], 0);
			$frompopup = round($_POST["frompopup"], 0);
			$email2 = $_POST["email2"];
			//end
			
			//create the session
			$datastring = $cm->session_field_watch_price();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);			
		
			$comments = nl2br($comments);
	
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			$boat_info_text = '';
			$broker_id = 1;
			if ($boat_id > 0){
				//$boat_url = $cm->site_url . $cm->get_page_url($boat_id, "yacht");
				$boat_ar = $cm->get_table_fields("tbl_yacht", "manufacturer_id, broker_id, model, year, yw_id", $boat_id);
				$boat_ar = $boat_ar[0];

				$manufacturer_id = $boat_ar["manufacturer_id"];
				$boat_model = $boat_ar["model"];
				$boat_year = $boat_ar["year"];
				$yw_id = $boat_ar["yw_id"];
				$broker_id = $boat_ar["broker_id"];
				$boat_make = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);	
				
				global $yachtclass;
				$b_ar = array(
					"boatid" => $boat_id, 
					"makeid" => 0, 
					"ownboat" => 0, 
					"feed_id" => "", 
					"getdet" => 1
				);
				$boat_url = $cm->site_url . $yachtclass->get_boat_details_url($b_ar);						
				
				$boat_info_text = '
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Selected Boat</strong></td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Make:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_make .'</td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_model .'</td>
					</tr>

					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $boat_year .'</td>
					</tr>
					
					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Listing URL:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width=""><a href="'. $boat_url .'">'. $boat_url .'</a></td>
					</tr>
				</table>
				';				
			}
			
			$newsletter_subscribe_text = $cm->set_yesyno_field($newsletter_subscribe);
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Watch Price</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">Name:</td>
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
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Newsletter:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $newsletter_subscribe_text .'</td>
			  	</tr>												
			</table>
			'. $boat_info_text .'
			';
			//end
			
			//add to lead
			$form_type = 27;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => $broker_id,
				"yacht_id" => $boat_id
			);
			$leadclass->add_lead_message($param);
				
			//send email to admin
			$send_ml_id = 49;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$ad_msg = $ad_email_ar->pdes;
			$ad_mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;
			
			$ad_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $ad_msg);
			$ad_msg = str_replace("#formdatasubmission#", $emailmessage, $ad_msg);
			$ad_mail_subject = str_replace("#companyname#", $companyname, $ad_mail_subject);
			
			if ($broker_id > 0){
				$extra_cc_email = $cm->get_common_field_name("tbl_user", "email", $broker_id);
			}else{
				$extra_cc_email = '';
			}
			
			if ($ad_cc_email != ""){
				if ($extra_cc_email != ""){
					$ad_cc_email = ', ' . $extra_cc_email;
				}
			}else{
				$ad_cc_email = $extra_cc_email;
			}
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_bcc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($name);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 50;
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
			
			$redpageurl = $cm->get_page_url(0, "popthankyou") . "?pgid=" . $pgid;
			$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
			$_SESSION["thnk"] = $pagecontent;
			header('Location: ' . $redpageurl);
			exit;
		}
	}
	
	//We Can Sell Your Yacht form
	public function display_we_can_sell_your_yacht_form($argu = array()){
		global $cm, $yachtclass, $captchaclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		$pgid = round($argu["pgid"], 0);
		$templateid = round($argu["templateid"], 0);
		$ajaxsubmit = round($argu["ajaxsubmit"], 0);
		
		$datastring = $cm->session_field_we_can_sell_your_yacht();
		$return_ar = $cm->collect_session_for_form($datastring);
		
		foreach($return_ar AS $key => $val){
		   ${$key} = $val;
		}
		
		if ($fname == "" AND $email == ""){
			//form not submitted
			$user_det = $cm->get_table_fields('tbl_user', 'fname, lname, email, phone', $loggedin_member_id);
			$email = $user_det[0]['email'];
			$fname = $user_det[0]['fname'];
			$lname = $user_det[0]['lname'];
			$phone = $user_det[0]['phone'];
			//$name = $fname . ' ' . $lname;
		}	
		
		if ($templateid == 1){
			$returntext = '
			<form method="post" action="'. $cm->folder_for_seo .'" class="sellyachtcatamaranform" id="sell-your-yacht-ff" name="sell-your-yacht-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="wecansellyouryachtsubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			<input type="hidden" value="'. $ajaxsubmit .'" id="ajaxsubmit" name="ajaxsubmit" />
			
			<ul class="ng-sell-yacht-catamaran-form">
                <li>
                	<h4 class="ng-h4 uppercase"><span class="white">WANT TO LEARN MORE ABOUT HOW</span></h4>
                    <h1 class="ng-h1 uppercase m-0">WE CAN SELL YOUR YACHT OR CATAMARAN?</h1>
                    
                </li>   
                <li>
                    <p>Contact Info</p>
                    <label for="wcs_fname" class="com_none">First Name</label>
                    <input class="input" type="text" id="wcs_fname" name="fname" value="'. $fname .'" placeholder="First Name">
                    
                    <label for="wcs_lname" class="com_none">Last Name</label>
                    <input class="input" type="text" id="wcs_lname" name="lname" value="'. $lname .'" placeholder="Last Name">
                    
                    <label for="wcs_email" class="com_none">Email</label>
                    <input class="input" type="text" id="wcs_email" name="email" value="'. $email .'" placeholder="Email">
                    
                    <label for="wcs_phone" class="com_none">Phone Number</label>
                    <input class="input" type="text" id="wcs_phone" name="phone" value="'. $phone .'" placeholder="Phone Number">                   
                </li>   
                <li>
                    <p>Yacht Info</p>
                    <div class="ng-half ng-first"><label for="wcs_boat_make" class="com_none">Make</label>
                    <input class="input" type="text" id="wcs_boat_make" name="boat_make" value="'. $boat_make .'" placeholder="Make"></div>   
                                     
                    <div class="ng-half"><label for="wcs_boat_model" class="com_none">Model</label>
                    <input class="input" type="text" id="wcs_boat_model" name="boat_model" value="'. $boat_model .'" placeholder="Model"></div>                 
                    
                    <div class="ng-half ng-first"><label for="wcs_boat_year" class="com_none">Year</label>
                    <input class="input" type="text" id="wcs_boat_year" name="boat_year" value="'. $boat_year .'" placeholder="Year"></div>
                    
                    <div class="ng-half"><label for="wcs_boat_engines" class="com_none">Engines</label>
                    <input class="input" type="text" id="wcs_boat_engines" name="boat_engines" value="'. $boat_engines .'" placeholder="Engines"></div>

					<label for="wcs_boat_hours_on_engines" class="com_none">Hours On Engines</label>
                    <input class="input" type="text" id="wcs_boat_hours_on_engines" name="boat_hours_on_engines" value="'. $boat_hours_on_engines .'" placeholder="Hours On Engines">
                    
                    <label for="wcs_boat_location" class="com_none">Location</label>
                    <input class="input" type="text" id="wcs_boat_location" name="boat_location" value="'. $boat_location .'" placeholder="Location">
                </li>   
            </ul>
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
            <div class="ng-hr clearfixmain">
                <div><input type="submit" value="Submit" class="ng-submit"></div>
            </div>						
			<div class="fomrsubmit-result com_none"></div>
			</form>
			';
		}else{
			$returntext = '
			<!--<div class="container clearfixmain">
			<div class="ssform clearfixmain">
			<h2>Your Boat Evaluation</h2>-->
			
			<form method="post" action="'. $cm->folder_for_seo .'" id="seller-services-ff" name="seller-services-ff">
			<label class="com_none" for="email2">email2</label>
			<input class="finfo" id="email2" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="sellerservicessubmit" />	
			<input type="hidden" id="pgid" name="pgid" value="'. $pgid .'" />
			<input type="hidden" value="'. $ajaxsubmit .'" id="ajaxsubmit" name="ajaxsubmit" /> 
			';
			
			$returntext .= '
			<div class="rowflex mt-4">
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>CONTACT INFO:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="wcs_fname">First Name:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_fname" name="fname" value="'. $fname .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_lname">Last Name:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_lname" name="lname" value="'. $lname .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_email">Email:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_email" name="email" value="'. $email .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_phone">Phone Number:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_phone" name="phone" value="'. $phone .'" class="input" />
					</div>                
				</div>
				
				<div class="col-30 pr-2">
					<h5 class="mb-3"><strong>YACHT:</strong></h5>                
					<div class="rowflex mb-1">
						<label for="wcs_boat_make">Make:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_boat_make" name="boat_make" value="'. $boat_make .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_boat_model">Model:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_boat_model" name="boat_model" value="'. $boat_model .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_boat_year">Year:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_boat_year" name="boat_year" value="'. $boat_year .'" class="input" />
					</div>      
				</div>
				
				<div class="col-30">
					 <h5 class="mb-3 md-none">&nbsp;</h5>
					<div class="rowflex mb-1">
						<label for="wcs_boat_engines">Engines:</label>
						<input type="text" id="wcs_boat_engines" name="boat_engines" value="'. $boat_engines .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_boat_hours_on_engines">Hours on Engines:</label>
						<input type="text" id="wcs_boat_hours_on_engines" name="boat_hours_on_engines" value="'. $boat_hours_on_engines .'" class="input" />
					</div>
					<div class="rowflex mb-1">
						<label for="wcs_boat_location">Location:<span class="requiredfieldindicate">*</span></label>
						<input type="text" id="wcs_boat_location" name="boat_location" value="'. $boat_location .'" class="input" />
					</div>
				</div>
			</div>			
			<div class="recaptchablock">'. $captchaclass->call_captcha(). '</div>
			<div align="center"><input name="submit" type="submit" value="Evaluate Your Boat"></div>			
			</form>
			<!--</div>
			</div>-->
			';
		}
		
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#sell-your-yacht-ff").submit(function(event){
				var all_ok = "y";
				var setfocus = "n";
				
				var ajaxsubmit = $("#ajaxsubmit").val();
				ajaxsubmit = parseInt(ajaxsubmit);
				
				if (!field_validation_border("wcs_fname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_fname");
				}
				
				if (!field_validation_border("wcs_lname", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_lname");
				}				
					
				if (!field_validation_border("wcs_email", 2, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_email");
				}
				
				if (!field_validation_border("wcs_phone", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_phone");
				}	
				
				if (!field_validation_border("wcs_boat_make", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_boat_make");
				}
				
				if (!field_validation_border("wcs_boat_model", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_boat_model");
				}
				
				if (!field_validation_border("wcs_boat_year", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_boat_year");
				}
				
				if (!field_validation_border("wcs_boat_location", 1, 1)){
					all_ok = "n";
					setfocus = set_field_focus(setfocus, "wcs_boat_location");
				}		
				
				if (all_ok == "n"){
					return false;
				}
				
				if (ajaxsubmit > 0){
					
					//Ajax submit
					var form = $(this);
					$.ajax({
					  type: form.attr("method"),
					  url: form.attr("action"),
					  data: form.serialize()
					}).done(function() {					  
					  $("#wcs_fname").val("");
					  $("#wcs_lname").val("");
					  $("#wcs_email").val("");
					  $("#wcs_phone").val("");
					  $("#wcs_boat_make").val("");
					  $("#wcs_boat_model").val("");
					  $("#wcs_boat_year").val("");
					  $("#wcs_boat_engines").val("");
					  $("#wcs_boat_hours_on_engines").val("");
					  $("#wcs_boat_location").val("");
					  
					  //$(".fomrsubmit-result").addClass("success");
					  //$(".fomrsubmit-result").html("Email sent successfully");
					  //$(".fomrsubmit-result").removeClass("com_none");
					  
					  $("#formsubmitcontent").html("Thank you for sending your yacht info.<br>We will get back to you asap.");
					  $("#formsubmitoverlay").show();
					  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					}).fail(function() {
					  $(".fomrsubmit-result").addClass("error");
					  $(".fomrsubmit-result").html("ERROR! Please try again");
					  $(".fomrsubmit-result").removeClass("com_none");
					  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					});
					
					event.preventDefault();
					
				}else{
					return true;
				}
			});
		});
		</script>
		';
		
			
		
		
		return $returntext;
	}
	
	//Submit We Can Sell Your Yacht form
	public function submit_we_can_sell_your_yacht_form(){
		if(($_POST['fcapi'] == "wecansellyouryachtsubmit")){
			global $db, $cm, $sdeml;
			
			//get form fields
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];			
			
			$boat_make = $_POST["boat_make"];
			$boat_model = $_POST["boat_model"];
			$boat_year = $_POST["boat_year"];			
			
			$boat_engines = $_POST["boat_engines"];
			$boat_hours_on_engines = $_POST["boat_hours_on_engines"];
			$boat_location = $_POST["boat_location"];			
			
			$pgid = round($_POST["pgid"], 0);
			$email2 = $_POST["email2"];
			$ajaxsubmit = round($_POST["ajaxsubmit"], 0);
			//end
			
			//create the session
			$datastring = $cm->session_field_we_can_sell_your_yacht();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//checking
			$red_pg = $_SESSION["s_backpage"];
			$cm->field_validation($fname, '', 'First Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($lname, '', 'Last Name', $red_pg, '', '', 1, 'fr_');			
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($phone, '', 'Phone', $red_pg, '', '', 1, 'fr_');
			
			$cm->field_validation($boat_make, '', 'Make', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_model, '', 'Model', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_year, '', 'Year', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($boat_location, '', 'Location', $red_pg, '', '', 1, 'fr_');
						
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('partsrequestsubmit');
			$cm->delete_session_for_form($datastring);			
		
			$comments = nl2br($comments);
			
			$companyname = $cm->sitename;
			$companyphone = $cm->get_systemvar('COMPH');
			$companyemail = $cm->admin_email_to();
			
			//create email message
			$emailmessage = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Contact Info</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="55%">First Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fname, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Last Name:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($lname, 1) .'</td>
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
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Yacht</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Make:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_make, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Model:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_model, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Year:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_year, 1) .'</td>
				</tr>				
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Engines:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_engines, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Hours on Engines:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_hours_on_engines, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Location:</td>
					<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($boat_location, 1) .'</td>
				</tr>										
			</table>
			';
			//end
			
			$fullname = $fname . ' ' . $lname;
			
			//add to lead
			$form_type = 28;
			$extra_message = '';
			global $leadclass;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $emailmessage,
				"broker_id" => 1,
				"yacht_id" => 0
			);
			$leadclass->add_lead_message($param);
			
			//send email to admin
			$send_ml_id = 51;
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
			$fromnamesender = $cm->filtertextdisplay($fullname);		 		  
			$sdeml->send_email($mail_fm, $mail_to, $ad_cc_email, $mail_bcc, $mail_reply, $ad_mail_subject, $ad_msg, $cm->site_url, $fromnamesender);
			//end
			
			//send email to user
			$send_ml_id = 52;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			$fr_msg = str_replace("#companyphone#", $companyphone, $fr_msg);
			$fr_msg = str_replace("#companyemail#", $companyemail, $fr_msg);
			$fr_msg = str_replace("#contactsubmission#", $contactsubmission, $fr_msg);
			
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($fullname), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyphone#", $companyphone, $fr_mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url);
			//end
			
			if ($ajaxsubmit > 0){
				return 1;
			}else{
				$_SESSION["s_pgid"] = $pgid;
				header('Location: ' . $cm->get_page_url($pgid, 'page'));
				exit;
			}
		}
	}
	
	//loan calculator
	public function trem_year($idname){
		$returntxt = '
		<select id="'. $idname .'" name="'. $idname .'" class="select">
			<option value="0" selected="selected">-</option>
			<option value="10">10</option>
			<option value="12">12</option>
			<option value="15">15</option>
			<option value="20">20</option>
		</select>
		';
		return $returntxt;
	}
	
	public function display_loan_amount_form(){
		$returntext = '
		<h4>What Can I Afford?</h4>
		<div class="singleblock_box clearfixmain">
			<form id="cal1">
				<ul class="form">
					<li class="left">
						<p><label for="monthlypayment">Monthly Payment</label></p>
						<input type="text" id="monthlypayment" name="monthlypayment" value="" class="input" />
					</li>
					
					<li class="right">
						<p><label for="downpayment1">Down Payment</label></p>
						<input type="text" id="downpayment1" name="downpayment1" value="" class="input" />
					</li>
					
					<li class="left">
						<p><label for="interestrate1">Interest Rate</label></p>
						<input type="text" id="interestrate1" name="interestrate1" value="" class="input" />
					</li>
					
					<li class="right">
						<p><label for="termyear1">Term (Years)</label></p>
						'. $this->trem_year('termyear1').'
					</li>
					
					<li class="left"><button type="button" class="button cal1call">Calculate</button></li>
					<li class="right">I can afford <span class="result c1result"></span></li>
				</ul>
			</form>
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$(".cal1call").click(function (){
				var rate = parseFloat($("#interestrate1").val());
				var monthly = parseFloat($("#monthlypayment").val());
				var down = parseFloat($("#downpayment1").val());
				var termyear1 = parseFloat($("#termyear1").val());		
				
				if ( !isNaN(monthly) && !isNaN(down) && !isNaN(rate) ) {
					termyear = termyear1 * 12;			
					rate = rate/1200;
					total = monthly * (Math.pow(1+rate,termyear)-1)/ (rate*Math.pow(1+rate,termyear));
					if ( !isNaN(total) ) {
						finalamount = number_round(total + down);
						$(".c1result").html("$" + finalamount);
					}else{
						$(".c1result").html("ERROR!");
					}
				}else{
					$(".c1result").html("ERROR!");
				}
			});
		});
		</script>
		';
			   
		return $returntext;
	}
	//end
	
	//Monthly payment calculator
	public function display_monthly_payment_form(){
		$returntext = '
		<h4>Monthly Payment</h4>
		<div class="singleblock_box clearfixmain">
			<form id="cal2">
				<ul class="form">
					<li class="left">
						<p><label for="boatprice">Boat Price</label></p>
						<input type="text" id="boatprice" name="boatprice" value="" class="input" />
					</li>
					
					<li class="right">
						<p><label for="downpayment2">Down Payment</label></p>
						<input type="text" id="downpayment2" name="downpayment2" value="" class="input" />
					</li>
					
					<li class="left">
						<p><label for="interestrate2">Interest Rate</label></p>
						<input type="text" id="interestrate2" name="interestrate2" value="" class="input" />
					</li>
					
					<li class="right">
						<p><label for="termyear2">Term (Years)</label></p>
						'. $this->trem_year('termyear2').'
					</li>
					
					<li class="left"><button type="button" class="button cal2call">Calculate</button></li>
					<li class="right">Monthly Payment <span class="result c2result"></span></li>
				</ul>
			</form>
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$(".cal2call").click(function (){
				var rate = parseFloat($("#interestrate2").val());
				var boatprice = parseFloat($("#boatprice").val());
				var down = parseFloat($("#downpayment2").val());
				var termyear2 = parseFloat($("#termyear2").val());		
				
				if ( !isNaN(boatprice) && !isNaN(down) && !isNaN(rate) ) {
					termyear = termyear2 * 12;
					total = (rate/1200) * (boatprice-down) / ( 1 - Math.pow(1+(rate/1200), -termyear) );
					if ( !isNaN(total) ) {
						finalamount = number_round(total);
						$(".c2result").html("$" + finalamount);
					}else{
						$(".c2result").html("ERROR!");
					}
				}else{
					$(".c2result").html("ERROR!");
				}
			});
		});
		</script>
		';
		
		return $returntext;
	}
	//end
	
	//instagram feed
	public function get_instagram_feed($hashtag){
		$returntext = '';
		$instaResult= file_get_contents('https://www.instagram.com/'.$hashtag.'/media/');
		$insta = json_decode($instaResult);
		//print_r($insta);		
		$instatem = $insta->items;
		$total = count((array)$instatem);
		
		if ($total > 0){
			$returntext .= '<ul>';
			$counter = 0;
			foreach($instatem as $instarow){
				$returntext .= '<li><a href="'. $instarow->link .'" target="_blank"><img src="'. $instarow->images->standard_resolution->url .'" /></a></li>';
				$counter++;
				
				if ($counter == 8){
					break;
				}
			}
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	
	public function display_instagram_feed($argu = array()){		
		$hashtag = $argu["hashtag"];
		$feeddata = $this->get_instagram_feed($hashtag);
		
		$returntext = '
		<h2 class="iconbefore">Instagram @'. $hashtag .'</h2>
		<div class="instagram_feed clearfixmain">'. $feeddata .'</div>
		';
		
		return $returntext;
	}
	
	/*-------------------DASHBOARD-------------------*/
	
	//dashboard initial html start end
	public function get_dashboard_initial_html_start_end($argu = array()){
		$link_name_text = '';
		$link_name = $argu["link_name"];
		if ($link_name != ""){
			$link_name_text = '<h2>'. $link_name .'</h2>';
		}
		
		$html_start = $html_start2 = '
		<div class="dashboard-holder dashboard-main-flex clearfixmain">
		'. $this->get_dashboard_menu_col($argu) .'
		<div class="dashboard-contentcol flexcol clearfixmain">
			'. $link_name_text .'
		';
		
		$html_start .= '<div class="dashboard-contentcol-inner clearfixmain">';
		
		$html_end = $html_end2 = '				      
			</div>
		</div>
		';
		
		$html_end = '</div>' . $html_end;
		
		$ycappnotice = '<div class="ycappnotice">To add/edit information here, please visit the YachtCloser application.</div>';
		
		$returnar = array(
			"htmlstart" => $html_start,
			"htmlend" => $html_end,
			"htmlstart2" => $html_start2,
			"htmlend2" => $html_end2,
			"ycappnotice" => $ycappnotice
		);
		
		return json_encode($returnar);
	}
	
	//header search content
	public function header_search_content(){
		global $db, $cm;
		$search_content = '';
		
		/*$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, extraclass from tbl_page where id IN (48,67) and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		if ($ss_found > 0){
			$c = 0;
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$ss_extraclass = $ss_row['extraclass'];
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
				
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
				
				if ($c == 0){
					$containerclass = 'header-search-show-hide-left';	
				}else{
					$containerclass = 'header-search-show-hide-right';
				}
				
				$search_content .= '
				<div class="'. $containerclass .'">
					<h3><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a></h3>
					<hr>	
					'. $this->get_special_menu_next_level(array("mnid" => $ss_id)) .'	
				</div>
				';
				
				$c++;
				if ($c == 1){
					$c = 0;	
				}
			}
		}
		*/
		
		/*$returntext = '
		<div class="header-search-show-hide clearfixmain">
			<div class="header-search-show-hide-full clearfixmain">
				'. $this->get_special_menu_next_level(array("mnid" => 90)) .'
			</div>
		</div>
		';*/
		
		$ss_sql = "select id, name, int_page_id, int_page_tp, new_window, extraclass from tbl_page where id IN (48,67) and status = 'y' order by rank";
		$ss_result = $db->fetch_all_array($ss_sql);
		$ss_found = count($ss_result);
		if ($ss_found > 0){
			foreach($ss_result as $ss_row){
				$ss_id = $ss_row['id'];
				$ss_name = $ss_row['name'];				
				$ss_open_new_window = $ss_row['new_window'];
				$ss_extraclass = $ss_row['extraclass'];
				$ss_lnk_url = $cm->get_page_url($ss_id, "page");
				
				$ss_link_target = "";
				if ($ss_open_new_window == "y"){ $ss_link_target = ' target = "_blank"'; }
				
				$search_content .= '
				<div class="header-search-show-hide-full clearfixmain">
					<h3><a href="'. $ss_lnk_url .'"'. $ss_link_target .'>'. $ss_name .'</a></h3>
					<hr>	
					'. $this->get_special_menu_next_level(array("mnid" => $ss_id, "orderby" => 1)) .'	
				</div>
				';
			}
			
		}
		
		$returntext = '
		<div class="header-search-show-hide clearfixmain">
			<div class="header-search-show-hide-in clearfixmain">
				'. $search_content .'
			</div>
		</div>
		';
		
		return $returntext;
	}
	
	//sudden popup
	public function display_sudden_popup(){
		global $cm;
		$returntext = '
		<div id="suddenpopupoverlay" class="animated hide">
    		<div class="fc-pop-container">
				<div class="fc-pop-content">
                	<a class="fc-close-suddenpopup fc-close-pop" href="javascript:void(0);"><i class="fas fa-times"></i><span class="com_none">Close</span></a>
                    	<h1>Selling <br> Your <br> Yacht?</h1>
                    	<p>Learn more about our seller services.</p>
                        <p class="pop-more-btn"><a href="'. $cm->get_page_url('46', 'page') .'">Learn More</a></p>
				</div><!--/fc-pop-content-->
			</div><!--/.fc-pop-container-->
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				setTimeout(function() {
					displayPopup();
				  }, 60000);
				  
				$(".fc-close-pop").click(function(){
					$("#suddenpopupoverlay").fadeOut(300);
				});
			})
			
			function displayPopup(){
				if (sessionStorage.displaypopuponce) {
					// no popup display for this session
				}else{
					sessionStorage.displaypopuponce = 1;
					$("#suddenpopupoverlay").fadeIn(300);
				}
			}
		</script> 
		';
		
		return $returntext;
	}
	
	//form submit thank you popup
	public function display_formsubmit_popup(){
		global $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		if ($loggedin_member_id == 0){
			$default_content = '
			<hr/>
			<p>If you would like to improve your browsing experience and save your preferences, <a href="'. $cm->get_page_url(0, "register") .'">sign up</a> or <a href="'. $cm->get_page_url(0, "login") .'">log in</a> to your account.</p>
			';
		}else{
			$default_content = '';
		}
		
		$returntext = '
		<div id="formsubmitoverlay" class="">
			<div class="fc-formsubmit-container">
				<a class="fc-close-formsubmit" href="javascript:void(0);"><i class="fas fa-times"></i><span class="com_none">Close</span></a>
				<i class="far fa-check-circle"><span class="com_none">Success</span></i>
				
				<h2>Thank You</h2>
				<p id="formsubmitcontent"></p>
				'. $default_content .'
			</div>
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				
				$(".fc-close-formsubmit").click(function(){
					$("#formsubmitoverlay").fadeOut(300);
				});
			})
		</script> 
		';
		
		return $returntext;
	}
	
	//useful stats
	public function get_useful_stats($id){
		global $db, $cm;
		$sql = "select * from tbl_stats where id = '". $id ."' and status_id = 1 order by rank";
		$result = $db->fetch_all_array($sql);
		$row = $result[0];
		return json_encode($row);
	}
	
	public function display_useful_stats($argu = array()){
		global $cm;
		
		//stat 1
		$stat1 = json_decode($this->get_useful_stats(1));
		$stat1_min_value = $stat1->min_value;
		$stat1_max_value = $stat1->max_value;
		$stat1_cell_percent = ($stat1_min_value * 100) / $stat1_max_value;
		$border_right_class1 = ' progress-bar-status-border';
		if ($stat1_cell_percent >= 100){$border_right_class1 = '';}
		//end
		
		//stat 2
		$stat2 = json_decode($this->get_useful_stats(2));
		$stat2_min_value = $stat2->min_value;
		$stat2_max_value = $stat2->max_value;
		$stat2_cell_percent = ($stat2_min_value * 100) / $stat2_max_value;
		$border_right_class2 = ' progress-bar-status-border';
		if ($stat2_cell_percent >= 100){$border_right_class2 = '';}
		//end
		
		//stat 3
		$stat3 = json_decode($this->get_useful_stats(3));
		$stat3_min_value = $stat3->min_value;
		$stat3_max_value = $stat3->max_value;
		$stat3_cell_percent = ($stat3_min_value * 100) / $stat3_max_value;
		$border_right_class3 = ' progress-bar-status-border';
		if ($stat3_cell_percent >= 100){$border_right_class3 = '';}
		//end

	
		$returntext .= '
		<ul class="ng-stats">
			<li>
				<h3><span class="large-number numbercounter">'. $cm->format_price($stat1_min_value, 0) .'</span>
				Feet Of Yachts Sold |<span class="white"> As Of Today</span></h3>
				<p>Stern to Bow, all the yachts we sold would stretchto almost 10 Football fields and growing!</p>
				<div class="progress-bar-container">
					<div class="progress-bar-base">
						<div class="progress-bar-status'. $border_right_class1 .' colcounter" v="'. $stat1_cell_percent .'" style="width:0px;"></div>
					</div>
					<span class="floatleft">0</span> <span class="floatright">'. $cm->format_price($stat1_max_value, 0) .'</span>
				</div>
			</li>
			
			<li>
            	<h3><span class="large-number numbercounter">'. $cm->format_price($stat2_min_value, 0) .'</span>
                Sales Call |<span class="white"> Per Day</span></h3>
                <p>On average per day between our customers and industry network. We stay connected to succeed.</p>
                <div class="progress-bar-container">
                    <div class="progress-bar-base">
                        <div class="progress-bar-status'. $border_right_class2 .' colcounter" v="'. $stat2_cell_percent .'" style="width: 0px"></div>
                    </div>
                    <span class="floatleft">0</span> <span class="floatright">'. $cm->format_price($stat2_max_value, 0) .'</span>
                </div>
            </li>
			
			<li>
            	<h3><span class="large-number numbercounter">'. $cm->format_price($stat3_min_value, 0) .'</span>
                Yachts Sold |<span class="white"> Worldwide</span></h3>
                <p>Our Marketing and network focus on worldwid exposure for faster sales wherever the market is best.</p>
                <div class="progress-bar-container">
                    <div class="progress-bar-base">
                        <div class="progress-bar-status'. $border_right_class3 .' colcounter" v="'. $stat3_cell_percent .'" style="width: 0px"></div>
                    </div>
                    <span class="floatleft">0</span> <span class="floatright">'. $cm->format_price($stat3_max_value, 0) .'</span>
                </div>
            </li>
		</ul>
		';
		
		return $returntext;
	}
	
	//dashboard menu
	public function get_dashboard_url(){
		global $cm;
		return $cm->folder_for_seo . 'dashboard/';
	}
	
	public function get_dashboard_menu_list(){
		global $db, $cm, $yachtclass;
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$company_id = $yachtclass->get_broker_company_id($loggedin_member_id);
		//$enable_mls_feed = $cm->get_common_field_name('tbl_company', 'enable_mls_feed', $company_id);
		$com_url = $cm->get_page_url($company_id, 'comprofile');
		$profile_url = $cm->get_page_url($loggedin_member_id, 'userinsidedb');
		
		$menu_list = array();
		
		//Dashboard
		$sub_menu_list = array();		
		$menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'Dashboard',
			'faclass' => 'fa-tachometer-alt',
			'linkurl' => $this->get_dashboard_url(),
			'directlink' => 1,
			'submenu' => $sub_menu_list
		);
		//end
		
		//Company
		$sub_menu_list = array();		
		$sub_menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Company Profile',
			'linkurl' => $com_url
		);		
		/*$sub_menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3),
			'name' => 'Office Location(s)',
			'linkurl' => $cm->folder_for_seo . 'office-locationlist/',
		);		
		$sub_menu_list[] = array(
			'id' => 3,
			'type' => array(1, 2, 3, 4),
			'name' => 'Broker List',
			'linkurl' => $cm->folder_for_seo . 'my-brokerlist/',
		);*/		
		$sub_menu_list[] = array(
			'id' => 4,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'My Profile',
			'linkurl' => $profile_url,
		);
		
		$menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Company',
			'faclass' => 'fa-building',
			'linkurl' => '',
			'directlink' => 0,
			'submenu' => $sub_menu_list
		);		
		//end		
		
		
		//Inventory
		$sub_menu_list = array();		
		$sub_menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'View Inventory',
			'linkurl' => $cm->folder_for_seo . 'my-boatlist/',
		);
		/*$sub_menu_list[] = array(
			'id' => 5,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'My Inventory Feed',
			'linkurl' => $cm->folder_for_seo . 'my-inventory-feed/',
		);*/
		/*if ($enable_mls_feed == 1){
			$sub_menu_list[] = array(
				'id' => 7,
				'type' => array(1, 2, 3, 4, 5),
				'name' => 'MLS Inventory Feeds',
				'linkurl' => $cm->folder_for_seo . 'mls-inventory-feeds/',
			);
		}*/
		$sub_menu_list[] = array(
			'id' => 3,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'My Favorites',
			'linkurl' => $cm->folder_for_seo . 'favorites/',
		);
		$sub_menu_list[] = array(
			'id' => 4,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'My Searches',
			'linkurl' => $cm->folder_for_seo . 'searches/',
		);
		$sub_menu_list[] = array(
			'id' => 8,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'Boat Watcher',
			'linkurl' => $cm->folder_for_seo . 'boat-watcher-broker/',
		);
		
		$menu_list[] = array(
			'id' => 3,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'Inventory',
			'faclass' => 'fa-ship',
			'linkurl' => '',
			'directlink' => 0,
			'submenu' => $sub_menu_list
		);
		//end
		
		
		//Marketing Tools
		$sub_menu_list = array();		
		$sub_menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Custom Slideshow',
			'linkurl' => $cm->folder_for_seo . 'mkt-custom-slideshow/',
		);
		$sub_menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Campaign Creator',
			'linkurl' => $cm->folder_for_seo . 'mkt-campaign-creator/',
		);
		/*$sub_menu_list[] = array(
			'id' => 3,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'e-Brochures',
			'linkurl' => $cm->folder_for_seo . 'custom-label-search-group/',
		);*/
		
		$menu_list[] = array(
			'id' => 6,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Marketing',
			'faclass' => 'fa-paper-plane',
			'linkurl' => '',
			'submenu' => $sub_menu_list
		);
		//end
		
		
		//Analytics
		$sub_menu_list = array();		
		$sub_menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Site Statistics',
			'linkurl' => $cm->folder_for_seo . 'site-statistics/',
		);
		/*$sub_menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Activity Reports',
			'linkurl' => $cm->folder_for_seo . 'boat-activity-reports/',
		);*/
		
		$menu_list[] = array(
			'id' => 4,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Analytics',
			'faclass' => 'fa-chart-line',
			'linkurl' => '',
			'submenu' => $sub_menu_list
		);
		//end
		
		
		//Leads / Clients
		$sub_menu_list = array();		
		$sub_menu_list[] = array(
			'id' => 1,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'My Leads',
			'linkurl' => $cm->folder_for_seo . 'leads/',
		);
		/*$sub_menu_list[] = array(
			'id' => 2,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'My Clients',
			'linkurl' => $cm->folder_for_seo . 'my-clientlist/',
		);*/
		$sub_menu_list[] = array(
			'id' => 3,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Lead Reports',
			'linkurl' => $cm->folder_for_seo . 'my-lead-reports/',
		);
		
		$menu_list[] = array(
			'id' => 5,
			'type' => array(1, 2, 3, 4, 5),
			'name' => 'Leads',
			'linkurl' => '',
			'faclass' => 'fa-users',
			'directlink' => 0,
			'submenu' => $sub_menu_list
		);
		//end
		
		//Logout
		$sub_menu_list = array();		
		$menu_list[] = array(
			'id' => 100,
			'type' => array(1, 2, 3, 4, 5, 6),
			'name' => 'Logout',
			'faclass' => 'fa-lock',
			'linkurl' => $cm->folder_for_seo . 'logout/',
			'directlink' => 1,
			'submenu' => $sub_menu_list
		);
		//end
		
		return json_encode($menu_list);
	}
	
	public function get_dashboard_menu_col($chosenmenu = array()){
		global $db, $cm, $yachtclass;	
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$usertype = $yachtclass->get_user_type($loggedin_member_id);
		
		$menu1 = round($chosenmenu["m1"], 0);
		$menu2 = round($chosenmenu["m2"], 0);
		
		$menu_list = $this->get_dashboard_menu_list();
		$menu_list = json_decode($menu_list);
		
		$returntext = '<div class="dashboard-menucol flexcol clearfixmain">
			<label for="dashboard-show-menu" class="dashboard-show-menu">Dashboard Menu <i class="fa fa-list-ul" aria-hidden="true"></i></label>
			<input type="checkbox" id="dashboard-show-menu" role="button">
			<ul class="menucol-parent">
		';
		
		$counter = 0;
		foreach($menu_list as $menu_row){
			$menu_id = $menu_row->id;
			$access_type_ar = $menu_row->type;
			$name = $menu_row->name;
			$faclass = $menu_row->faclass;
			$linkurl = $menu_row->linkurl;
			$directlink = $menu_row->directlink;
			$submenu = $menu_row->submenu;
			$p_menu_class = '';
			$hidden_class = ' com_none';
			
			if (in_array($usertype, $access_type_ar)) {				
				if ($directlink == 0){
					$linkurl = 'javascript:void(0);';
					$p_menu_class .= 'menuclick menuclick'. $counter .' ';
				}
				
				if ($menu1 == $menu_id){
					$p_menu_class .= 'active ';
					$hidden_class = '';
				}
				$p_menu_class = rtrim($p_menu_class, " ");
				
				//submenu
				$submenutext = '';
				$submenu_count = count($submenu);
				if ($submenu_count > 0){
					$submenutext .= '<ul class="menucol-child menucol-child'. $counter . $hidden_class .'">';
					
					foreach($submenu as $submenu_row){
						$submenu_id = $submenu_row->id;
						$access_type_ar_sub = $submenu_row->type;
						$name_sub = $submenu_row->name;
						$linkurl_sub = $submenu_row->linkurl;
						$p_menu_class_sub = '';
						
						if (in_array($usertype, $access_type_ar_sub)) {
							
							if ($menu1 == $menu_id AND $menu2 == $submenu_id){
								$p_menu_class_sub .= 'active ';
							}
							$p_menu_class_sub = rtrim($p_menu_class_sub, " ");
												
							$submenutext .= '<li><a class="'. $p_menu_class_sub .'" href="'. $linkurl_sub .'">'. $name_sub .'</a></li>';
						}
					}
					
					$submenutext .= '</ul>';
				}
				//end
				
				$returntext .= '
				<li>
					<a counter="'. $counter .'" class="'. $p_menu_class .'" href="'. $linkurl .'"><i class="fa '. $faclass .'" aria-hidden="true"></i>'. $name .'</a>'. $submenutext .'
				</li>
				';
				
				$counter++;
			}
		}
		
		$returntext .= '
			</ul>
		</div>';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			
			$(".menuclick").click(function(){
				var counter = $(this).attr("counter");
				$(".menucol-child" + counter).slideToggle(300);
				$(this).toggleClass( "semiactive" );
			});
			
		});
		</script>
		';
		
		return $returntext;
		
	}
	
	/*-------------------DASHBOARD END-------------------*/

	//message
  	public function display_message($mid, $extravalue = ""){
        global $cm;
        switch($mid){

            case 1:
                //session expire
                $ret_message = 'Your session has expired.';
                break;

            case 2:
                //contact thank you message
                $ret_message = 'Your request has been submitted.';
                break;

            case 3:
                //record not found
				$ret_message = 'SORRY! The record you want to view is not available.';
				break;

            case 4:
                //Member account create thank you message
                $ret_message = 'Your Details have been registered successfully. <br /><br />Please check your email and activate your account.';
                break;

            case 5:
                //account activate thank you message.
                $ret_message = 'You have activated your account.<br /><br><a href="'. $extravalue .'"><strong>Continue</strong></a>';
                break;

            case 6:
                //account activate error message.
                $ret_message = 'You have faced an error. Please click the activation link from your email and try again.';
                break;

            case 7:
                //Member account modify thank you message
                $ret_message = 'Your details have been updated in our secure database.';
                break;

            case 8:
                //forgot password thank you message
                $ret_message = 'Reset Password link has been sent to your Email Address.';
                break;

            case 9:
                //reset password - sq page - session expired
                $ret_message = 'ERROR! Your session expired. Please <a href="index.php">try again</a>.';
                break;

            case 10:
                //reset password thankyou message
                $ret_message = 'You have successfully reset your account password.<br /><br><a href="login/"><strong>Log-in</strong></a> now.';
                break;

            case 11:
                //reset password sorry message
                $ret_message = 'ERROR! Please click forgot password link and try again.';
                break;

            case 12:
                //forgot password sorry message
                $ret_message = 'ERROR! Invalid Username.';
                break;

            case 13:
                //Invalid category selection error message
                $ret_message = 'You have selected an invalid Category.';
                break;

            case 14:
                //Listing not found message
                $ret_message = 'Sorry. Listing not available.';
                break;

            case 15:
                //User not found
                $ret_message = 'Sorry. The broker you are looking for is not found.';
                break;

            case 16:
                //Broker contact thank you
                $ret_message = 'Email sent successfully.';
                break;

            case 17:
                //Fav List Added
                $ret_message = 'Listing has been added to your list.';
                break;

            case 18:
                //Save search add - thank you
                $ret_message = 'Search saved.';
                break;

            case 19:
                //Save search add - sorry
                $ret_message = 'Error! Please try again.';
                break;

            case 20:
                //Save search not found
                $ret_message = 'Error! Please try again.';
                break;

            case 22:
                //Save search - email send
                $ret_message = 'Your search link has been sent to '. $extravalue .'.';
                break;

            case 23:
                //Yacht add
                $ret_message = 'You have successfully added Yacht. <br /><a href="'. $cm->folder_for_seo .'boat-image/'. $extravalue .'/">Add Image Now</a>';
                break;

            case 24:
                //Yacht edit
                $ret_message = 'You have successfully modified Yacht. <br />
                <a href="'. $cm->folder_for_seo .'boat-image/'. $extravalue .'/">Manage Image Now</a><br />
				<a href="'. $cm->folder_for_seo .'boat-video/'. $extravalue .'/">Manage Video Now</a><br />
                <a href="'. $_SESSION["listing_file_name"] .'">Back To Listing</a>
                ';
                break;

            case 25:
                //no permission
                $ret_message = 'ERROR! No permission.';
                break;

            case 26:
                //Member created broker account - thank you message
                $ret_message = 'An email has been sent to the user for activate the account.';
                break;

            case 27:
                //Member modify broker account
                $ret_message = 'Details have been updated in our secure database.';
                break;

            case 28:
                //announcement not found
                $ret_message = 'Sorry. The page you are trying to view is not available.';
                break;
				
			case 29:
                //company profile edit
                $ret_message = 'Your company details have been updated in our secure database.';
                break;	
				
			case 30:
                //location add
                $ret_message = 'You have added location successfully.';
                break;	
				
			case 31:
                //location edit
                $ret_message = 'Your location details have been updated in our secure database.';
                break;	
				
			case 32:
                //Member account - Master broker admin
                $ret_message = 'Your Details have been registered successfully.<br /><br />You will be notified after your account activation.';
                break;
				
			case 33:
                //User to broker add - thankyou
                $ret_message = 'You have successfully added '. $extravalue .' to your account.<br /><a href="'. $cm->folder_for_seo .'dashboard/">Back To Dashboard</a>';
                break;	
				
			case 34:
                //User to broker add - sorry
                $ret_message = 'You have already added a Broker on your account.<br />Go <a href="'. $cm->folder_for_seo .'dashboard/">Back To Dashboard</a> and remove that Broker first.';
                break;	
				
			case 35:
                //location edit
                $ret_message = 'Your have successfully unsubscribed from the list.';
                break;
				

            default:
                $ret_message = 'Thank you.';
                break;
        }
        return $ret_message;
  }
}
?>