<?php
class Commonclass {
	public $site_url = "";
	public $site_url_short = "";
	public $sitename = "";
	public $s_check_url = array();
	public $folder_for_seo = "";
	public $editorbasepath = "";
	
	public $lmonth = array("junk", "January", "February","March","April","May","June","July","August","September","October","November","December");
    public $lmonthid = array("00", "Jan", "Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	public $wekk_dy_ar = array("Sunday", "Monday", "Tuesday", "Wednessday", "Thrusday", "Friday", "Saturday");
	
	public $dip_bg = "#24507c";
    public $lt_bg = "#d0e6fc";
	public $hgt = "90%";

    public $logo_im_width = 500;
    public $logo_im_height = 500;

    public $user_im_width = 500;
    public $user_im_height = 500;

    public $user_im_width_t = 80;
    public $user_im_height_t = 80;

    public $home_banner_w = 1500;
    public $home_banner_h = 566;

    public $cat_im_width = 230;
    public $cat_im_height = 153;
	
	public $manufacturer_im_width = 360;
    public $manufacturer_im_height = 176;
	
	public $res_im_width = 300;
    public $res_im_height = 120;

    public $page_banner_w = 920;
    public $page_banner_h = 147;

    public $yacht_im_width_t = 230;
    public $yacht_im_height_t = 153;

    public $yacht_im_width_b = 480;
    public $yacht_im_height_b = 319;

    public $yacht_im_width = 980; //725
    public $yacht_im_height = 652; //482
	
	public $yacht_im_width_sl = 980;
    public $yacht_im_height_sl = 420;	
	
	public $slider_im_width = 1500;
    public $slider_im_height = 690;
	
	public $slider_im_width_inner = 1500;
    public $slider_im_height_inner = 495;
	
	public $indasso_im_width = 400;
    public $indasso_im_height = 120;
	
	public $crt_im_width = 400;
    public $crt_im_height = 120;
	
	public $blog_im_width_t = 500;
    public $blog_im_height_t = 520;
	
	public $blog_im_width = 800; //1366
    public $blog_im_height = 832; //546
	
	public $location_im_width = 1366;
    public $location_im_height = 500;
	
	public $testimonial_im_width = 500;
    public $testimonial_im_height = 375;
	
	public $box1_width = 560;
    public $box1_height = 500;
	
	//public $box2_width = 700;
    //public $box2_height = 466;
	
	public $boattype_box_im_width = 500;
    public $boattype_box_im_height = 333;
	
	public $brand_box_logo_im_width = 185;
    public $brand_box_logo_im_height = 63;
	
	public $menu_im_width = 300;
    public $menu_im_height = 200;
	
	public $make_logo_scroll_im_width = 500;
    public $make_logo_scroll_im_height = 333;
	
    public $allow_image_ext = '.jpg, .jpeg, .gif, .png';
	public $allow_image_ext1 = '.png';
	public $allow_video_ext = '.mp4';
	public $allow_attachment_ext = '.pdf, .doc, .docx, .ppt, .pptx, .zip';
    public $pagination_record = 24;
    public $pagination_record_list = 10;
	public $default_future_date = '9999-12-31';
	public $maxboatcompare = 5;
	public $max_upload_size_form = 10;
	public $googlemapkey = "AIzaSyAlJl6Onr8SDkBtlUuXuW3gCg0BYMRsEPY";
	public $geocodingkey = "AIzaSyAlJl6Onr8SDkBtlUuXuW3gCg0BYMRsEPY"; //AIzaSyCA0FeNzWGdbVBHvBr8dszp672DERvZxfU
	
	public function __construct() {
		$this->sitename = $this->get_systemvar('COMNM');
		$this->site_url = $this->get_systemvar('URLFL');
		$this->site_url_short = $this->get_systemvar('URLST');
		$this->folder_for_seo = $this->get_systemvar('RTFLD');
		$this->s_check_url = array($this->site_url_short);
		$this->editorbasepath = $this->folder_for_seo . 'ckeditor/';
	}
	
	public function form_post_check_valid($formval, $az = 0){
		$ip = $_SERVER['REMOTE_ADDR'];
		$current_time = time();
		$v = 0;
		
		if ($az == 0){
			if ("" == getenv("HTTP_USER_AGENT") || "" == getenv("HTTP_REFERER")){
				$v = 1;
 			}
		}
		
		if (isset($_SESSION["s_current_time_" . $formval]) AND isset($_SESSION["s_ip"])){
			if ($ip == $_SESSION["s_ip"]){
				$timediff = $current_time - $_SESSION["s_current_time_" . $formval];
				if ($timediff <= 30){
					$v = 1;
				}else{
					$_SESSION["s_current_time_" . $formval] = $current_time;
					$_SESSION["s_ip"] = $ip;
				}
			}
			
		}else{
			$_SESSION["s_current_time_" . $formval] = $current_time;
			$_SESSION["s_ip"] = $ip;
		}		
		return $v;
	}
	
	public function form_post_check_valid_main($formval, $pop = 0, $az = 0){
		$v = $this->form_post_check_valid($formval, $az);
		if ($v == 1){
			if ($pop == 1){
				header('Location: ' . $this->get_page_url(0, "popsorry"));
				exit;
			}else{
				$this->sorryredirect(25);
			}
		}
	}
	
	public function get_data_web_url(){
		$data_site_url = $this->get_common_field_name("tbl_mainsite", "site_url", 1);
		return $data_site_url;
	}
		
	public function filtertext($ab, $formtopt = 0){
		global $db;
		$ab = $db->escape($ab);
	    if ($formtopt == 1){
			$ab = htmlentities($ab); 
		}
		$ab = trim($ab);
	    return $ab;
    }
   
    public function filtertextdisplay($ab, $formtopt = 0){
		$ab = trim(stripslashes($ab));
		//if ($formtopt == 1){ $ab = htmlentities($ab); }
		return $ab;
    }
   
   public function get_systemvar($syscode){
		global $db;
		$systemvar = $db->total_record_count("select field_value as ttl from tbl_systemvar where code = '". $this->filtertext($syscode) ."'");
		return $systemvar;
   }
   
   public function admin_email(){
	    $superemail = $this->get_systemvar('FMAIL');
        return $superemail;
   }
   
   public function admin_email_to(){
	    $superemail = $this->get_systemvar('EMAIL');
        return $superemail;
   }
   
   public function yf_email_from(){
	    $superemail = $this->get_systemvar('YFFEM');
        return $superemail;
   }
   
	public function validate_string_withslash($string){
		$pos = strpos($string, '/');
		if (strpos($string, '/') === FALSE OR $pos > 0){
			$string = '/' . $string;
		}
		return $string;
	}
	
	public function split_name_first_last($name){
		$name_ar = explode(" ", $name);
		$lastname = array_pop($name_ar);
		$firstname = implode(" ", $name_ar);
		
		$returnval = array(
            'firstname' => $firstname,
            'lastname' => $lastname
        );
		return $returnval;
	}
   
   public function format_url_txt($ext_url){
       if ($ext_url == "http://" OR $ext_url == "https://"){ $ext_url = ""; }
       if ($ext_url != ""){
           $pos = strpos($ext_url, 'https://');
           if ($pos === false){
               $pos = strpos($ext_url, 'http://');
               if ($pos === false){
                   $ext_url = "http://" . $ext_url;
               }
           }          
       }
       return $ext_url;
  }
   
  public function specific_word_count($mainstring, $searchstring){
        $mainstring = strtolower($mainstring);
        $searchstring = strtolower($searchstring);
        $returnvalue = substr_count($mainstring, $searchstring);
        return $returnvalue;
  } 
  
	public function ar_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		arsort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
  
  public function campaignid($x = 10) {   
	  $salt = "abchefghjkminpqrstuvwxyz0123456789"; 
	  srand((double)microtime()*1000000); 
	  $i = 0;
	  $pass=""; 
	  while ($i <= $x) { 
			$num = rand() % 33; 				
			$tmp = substr($salt, $num, 1); 	
			$pass = $pass . $tmp;
			$i++; 
	  } 
	  return strtoupper($pass); 
  }  
  
  public function get_unq_code($tblnm, $fldnm){  
      global $db;
	  $ucd = $this->campaignid(40);
	  $wh_present = $db->total_record_count("select count(*) as ttl from ".$tblnm." where ".$fldnm." = '". $ucd ."'");	  
	  if ($wh_present > 0){ $ucd = $this->get_unq_code($tblnm, $fldnm); } // next recursion
	  return $ucd;
  }
  
  public function serach_url_filtertext($ab, $opt = 0){
	  $ab = trim($ab);
      if ($opt == 0){
          //$ab = str_replace("-","",$ab);
          $ab = str_replace(" ","+",$ab);
      }
	  $ab = str_replace("&amp;","and",$ab);
	  $ab = str_replace("&","and",$ab);
	  $ab = str_replace("'","",$ab);
	  //$ab = str_replace("/","",$ab); 
	  $ab = str_replace("%","",$ab);
	  $ab = str_replace("#","",$ab);  
      //$ab = str_replace(".",",",$ab);
	  $ab = htmlspecialchars($ab);
	  $ab = strtolower($ab);
	  return $ab;
  }
  
  public function create_slug($text){	
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		
		// trim
		$text = trim($text, '-');
		
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		
		// lowercase
		$text = strtolower($text);
		
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text); 
		
		if (empty($text))
		{
			return 'n-a';
		}		
		return $text;
	}

  public function display_default_date($dtfmt = 1){
      $passeddt = '';
      if ($dtfmt == 1){ $passeddt = date("m/d/Y"); }
      return $passeddt;
  }
 
  public function display_date($passeddt, $datestring, $dtfmt = 1){
     if ($datestring == "y"){ $passeddt = strtotime($passeddt); }
     
     if ($dtfmt == 1){ $passeddt = date("jS F, Y h:i A", $passeddt); }
     if ($dtfmt == 2){ $passeddt = date("jS F, Y", $passeddt); }
     
     if ($dtfmt == 3){
        $passeddt1 = date("d/m/Y", $passeddt);
        $passeddt2 = date("h:i:s A", $passeddt);
        $passeddt = $passeddt1 . "<br />" . $passeddt2;
     }
     
     if ($dtfmt == 4){ $passeddt = date("d/m/Y h:i:s A", $passeddt); }
     if ($dtfmt == 5){ $passeddt = date("F, Y", $passeddt); }
     if ($dtfmt == 6){ $passeddt = date("d/m/Y", $passeddt); }
     if ($dtfmt == 7){ $passeddt = date("m-d-Y", $passeddt); }
     if ($dtfmt == 8){ $passeddt = date("F jS, Y", $passeddt); }
     if ($dtfmt == 9){ $passeddt = date("m/d/Y", $passeddt); }
     if ($dtfmt == 10){ $passeddt = date("m/d/Y h:i:s A", $passeddt); }	 
	 if ($dtfmt == 16){ $passeddt = date("Y/n", $passeddt); }
     return $passeddt;
  } 
  
  public function format_price($price, $decplace = 2){
	  return number_format($price, $decplace);
  }
  
  public function display_editor($instancename, $sBasePath, $editorw, $editorh, $file_data, $editorstylepath = "", $editorextrastyle = "", $editortoolbarset = "adminsmall", $modedisplay = 0){
	 
	 if(!class_exists('CKEditor')){
		 include("../ckeditor/ckeditor.php");
		 include_once '../ckeditor/ckfinder/ckfinder.php';	 
	 }
	 
	 $CKEditor = new CKEditor();
	 $CKEditor->returnOutput = true;
	 $CKEditor->basePath = $sBasePath;
	 if ($editorstylepath != ""){ $CKEditor->config['contentsCss'] = $editorstylepath; }
	 if ($editorextrastyle != ""){ $CKEditor->config['bodyClass'] = $editorextrastyle; }
	 $CKEditor->config['width'] = $editorw;
	 $CKEditor->config['height'] = $editorh;
	 $config['toolbar'] = $editortoolbarset;
	 CKFinder::SetupCKEditor($CKEditor, $sBasePath .'ckfinder/');
	 //echo $CKEditor->editor($instancename, $file_data, $config);
	 if ($modedisplay == 1){
		 return $CKEditor->editor($instancename, $file_data, $config);;
	 }else{
		 echo $CKEditor->editor($instancename, $file_data, $config);
	 }
  }

  public function get_total_rec_table($tablenm, $extraq = ""){
      global $db;
      $total_rec = $db->total_record_count("select count(*) as ttl from ". $tablenm ." ". $extraq ."");
      return $total_rec;
  }
  
  public function get_common_field_name($tbl_name, $field_name, $common_id, $wherefield = "id"){
      global $db;
      $common_nm = $db->total_record_count("select ". $field_name ." as ttl from ". $tbl_name ." where ". $wherefield ." = '". $this->filtertext($common_id) ."'");
      return $common_nm;
  }
   
  public function get_table_fields($tbl_name, $field_name, $common_id, $wherefield = "id"){
      global $db;
      $sql_x = "select " . $field_name . " from ". $tbl_name ." where ". $wherefield ." = '". $this->filtertext($common_id) ."'";
      $result_x = $db->fetch_all_array($sql_x);  
      return $result_x;
  }
  
  public function set_date_format($dtst){
      if ($dtst == ""){ $dtst_a = "0000-00-00"; }else{
          $bd_array = explode("/", $dtst);
          $dtst_a = $bd_array[2]."-".$bd_array[0]."-".$bd_array[1];
      } 
      return $dtst_a;
  }

  public function difference_between_dates($sdate, $edate){
      $d_diff = strtotime($edate) - strtotime($sdate);
      $no_of_days = ceil($d_diff / (3600 * 24));
      return $no_of_days;
  }
     
  public function set_yesyno_field($fieldvalue){
      if ($fieldvalue == 1){ $fieldvalue = "Yes"; }else{ $fieldvalue = "No"; }
      return $fieldvalue;
  }
     
  public function set_other_option_value($oth, $tbl_name, $field_name, $common_id){
      if ($oth == ""){
         $returntxt = $oth;
      }else{
         $returntxt = $this->get_common_field_name($tbl_name, $field_name, $common_id);
      }     
      return $returntxt;
  }


    public function display_multiplevl($whosid, $whostable, $collectfld, $queryfld, $mastertbl, $linktopage = 0){
        global $db;
        $multipleop_vl = "";
        $fn_sql = "select distinct ". $collectfld ." from ". $whostable ." where ". $queryfld ." = '". $whosid ."'";
        $fn_result = $db->fetch_all_array($fn_sql);
        foreach( $fn_result as $fn_row ){
            $cm_vl = $fn_row[$collectfld];
            $masternm = $db->total_record_count("select name as ttl from ". $mastertbl ." where id = '".$cm_vl."'");
            if ($linktopage == 1){ 
				//blog tag
				$tagslug = $db->total_record_count("select slug as ttl from ". $mastertbl ." where id = '".$cm_vl."'");
				$taglinkurl = $this->get_page_url($tagslug, 'blogtag');
				$masternm = '<a title="Tag" href="'. $taglinkurl .'">'. $masternm .'</a>'; 
			}
            $multipleop_vl .= $masternm . ", ";
        }
        $multipleop_vl = rtrim($multipleop_vl, ", ");
        return $multipleop_vl;
    }
 
 public function get_sort_content_description($pdes, $deslength = 100){  
     $pdes = strip_tags($pdes, "<h3>");
	 $pdes_len = strlen($pdes);	 
	 if ($pdes_len > $deslength){
		$pdes = substr($pdes, 0, $deslength) . '...';
	 }     
     return $pdes;
 }
 
 public function get_content_length($pdes){  
     $pdes = strip_tags($pdes);
     $returntxt = strlen($pdes);
     return $returntxt;
 }

 public function format_text_content($text_content, $deslength = 100){
     $text_content_c = strlen($text_content);
     if ($text_content_c > $deslength){
         $text_content = substr($text_content, 0, $deslength) . '...';
     }
     return $text_content;
 }
 
	public function fc_word_count($string, $limit) { 
		$words = explode(' ', $string); 
		return implode(' ', array_slice($words, 0, $limit)); 
	}
 
  //seo URL
  public function get_seo_linked_url($int_page_id, $int_page_tp){	  
	  if ($int_page_tp == "c"){
			//tbl_yacht_search_link
			$m_ar = $this->get_table_fields('tbl_yacht_search_link', 'section, section_id', $int_page_id);
			$section = $m_ar[0]['section'];
			$section_id = $m_ar[0]['section_id']; 
			$tname = 'tbl_' . $section;
			$section_name = $this->get_common_field_name($tname, 'name', $section_id);
			$ret_url = $this->get_page_url($section_name, $section);
	  }elseif ($int_page_tp == "d"){	
			//tbl_resource_type			
			$ret_url = $this->get_page_url($int_page_id, 'resourcetype');
	  }elseif ($int_page_tp == "e"){	
			//YC Model link
		  	global $ymclass;		  	
		  	$int_page_int_ar = explode("_", $int_page_id);
		  	$model_id = $int_page_int_ar[0];
			$make_id = $int_page_int_ar[1];
		  
		  	$model_ar = $ymclass->get_manufacturer_model_details_by_id($make_id, $model_id);
			$model_ar = json_decode($model_ar);
		  	$model_row = $model_ar->docarray;
		  	$ret_url = $model_row[0]->details_url;
	  }elseif ($int_page_tp == "f"){	
			//Boat Url			
			$ret_url = $this->get_page_url($int_page_id, 'yacht');
	  }else{
			$ret_url = $this->get_page_url($int_page_id, "page");
	  }
	  return $ret_url;
  }
  
  public function get_page_url($checkid, $pagetype = "mainmenu"){
	  global $yachtclass, $bdir, $db;
      $ret_url = '';
	  if ($pagetype == "file"){
		  //$ret_url = $bdir."filedownload.php?id=" . $checkid;
		  $ret_url = 'javascript:openpopupwindow(\''. $checkid .'\')';
	  }elseif ($pagetype == "pop-join-our-list"){
			$ret_url = $this->folder_for_seo . "pop-join-our-list/";
	  }elseif ($pagetype == "pop-trade-in-welcome"){
			$ret_url = $this->folder_for_seo . "pop-trade-in-welcome/";
	  }elseif ($pagetype == "pop-talk-to-specialist"){
			$ret_url = $this->folder_for_seo . "pop-talk-to-specialist/";
	  }elseif ($pagetype == "pop-ask-for-brochure"){
			$ret_url = $this->folder_for_seo . "pop-ask-for-brochure/";
	  }elseif ($pagetype == "pop-lead-checkout"){
			$ret_url = $this->folder_for_seo . "pop-lead-checkout/";
	  }elseif ($pagetype == "pop-increase-yacht-value"){
			$ret_url = $this->folder_for_seo . "pop-increase-yacht-value/";
	  }elseif ($pagetype == "pop-boat-show-registration"){
			$ret_url = $this->folder_for_seo . "pop-boat-show-registration/";
	  }elseif ($pagetype == "pop-open-yacht-days"){
			$ret_url = $this->folder_for_seo . "pop-open-yacht-days/";
	  }elseif ($pagetype == "pop-chartering-your-yacht"){
			$ret_url = $this->folder_for_seo . "pop-chartering-your-yacht/";
	  }elseif ($pagetype == "pop-watch-price"){
			$ret_url = $this->folder_for_seo . "pop-watch-price/";
	  }elseif ($pagetype == "popthankyou"){
			$ret_url = $this->folder_for_seo . "popthankyou/";
	  }elseif ($pagetype == "popsorry"){
			$ret_url = $this->folder_for_seo . "popsorry/";
	  }elseif ($pagetype == "thankyou"){
			$ret_url = $this->folder_for_seo . "thankyou/";
	  }elseif ($pagetype == "sorry"){
			$ret_url = $this->folder_for_seo . "sorry/";
	  }elseif ($pagetype == "login"){
			$ret_url = $this->folder_for_seo . "login/";
	  }elseif ($pagetype == "register"){
			$ret_url = $this->folder_for_seo . "register/";
	  }elseif ($pagetype == "editprofile"){
			$ret_url = $this->folder_for_seo . "editprofile/";
	  }elseif ($pagetype == "dashboard"){
			$ret_url = $this->folder_for_seo . "dashboard/";
	  }elseif ($pagetype == "advancedsearch"){
			$ret_url = $this->folder_for_seo . "advanced-search/";
	  }elseif ($pagetype == "compareboat"){
			$ret_url = $this->folder_for_seo . "compareboat/";
	  }elseif ($pagetype == "typybyid"){
          $pagename = $this->get_common_field_name('tbl_type', 'slug', $checkid);
          $ret_url = $this->folder_for_seo . "type/" . $pagename . "/";

      }elseif ($pagetype == "type"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "type/" . $pagename . "/";

      }elseif ($pagetype == "typecobrokerage"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "type/cobrokerage/" . $pagename . "/";

      }elseif ($pagetype == "typeourlistings"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "type/ourlistings/" . $pagename . "/";

      }elseif ($pagetype == "make"){
          $pagename = $this->get_common_field_name('tbl_manufacturer', 'slug', $checkid);
          $ret_url = $this->folder_for_seo . "make/" . $pagename . "/";

      }elseif ($pagetype == "makecobrokerage"){
          $pagename = $this->get_common_field_name('tbl_manufacturer', 'slug', $checkid);
          $ret_url = $this->folder_for_seo . "make/cobrokerage/" . $pagename . "/";

      }elseif ($pagetype == "makeourlistings"){
          $pagename = $this->get_common_field_name('tbl_manufacturer', 'slug', $checkid);
          $ret_url = $this->folder_for_seo . "make/ourlistings/" . $pagename . "/";

      }elseif ($pagetype == "model"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "model/" . $pagename . "/";

      }elseif ($pagetype == "year"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "year/" . $pagename . "/";

      }elseif ($pagetype == "category"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "category/" . $pagename . "/";

      }elseif ($pagetype == "condition"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "condition/" . $pagename . "/";

      }elseif ($pagetype == "country"){
          $pagename = $this->get_common_field_name('tbl_country', 'code', $checkid);
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "country/" . $pagename . "/";

      }elseif ($pagetype == "state"){
          $pagename = $this->get_common_field_name('tbl_state', 'code', $checkid);
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "state/" . $pagename . "/";

      }elseif ($pagetype == "search"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "search/" . $pagename . "/";

      }elseif ($pagetype == "savesearch"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo . "savesearch/" . $pagename . "/";

      }elseif ($pagetype == "savesearchclient"){
		  $pagename = $this->get_common_field_name("tbl_user", "uid", $checkid, "id");
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "clientsearch/" . $pagename . "/";

      }elseif ($pagetype == "clientfavorites"){
		  $pagename = $this->get_common_field_name("tbl_user", "uid", $checkid, "id");
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "clientfavorites/" . $pagename . "/";

      }elseif ($pagetype == "yacht"){
		  $pagename = $yachtclass->yacht_name($checkid, 1);          
          $pagename = $this->serach_url_filtertext($pagename);
		  
		  $listingno = $this->get_common_field_name('tbl_yacht', 'listing_no', $checkid);
          $ret_url = $this->folder_for_seo . "boat/" . $pagename . "/".$listingno.'/';

      }elseif ($pagetype == "yachtsale"){		  
		  $listingno = $this->get_common_field_name('tbl_yacht', 'listing_no', $checkid);
		  $boatslug = $this->get_common_field_name('tbl_yacht', 'boat_slug', $checkid);
          $ret_url = $this->folder_for_seo . "yacht-sales/" . $boatslug . "/".$listingno.'/';

      }elseif ($pagetype == "catamaransales"){		  
		  $listingno = $this->get_common_field_name('tbl_yacht', 'listing_no', $checkid);
		  $boatslug = $this->get_common_field_name('tbl_yacht', 'boat_slug', $checkid);
          $ret_url = $this->folder_for_seo . "catamaran-sales/" . $boatslug . "/".$listingno.'/';

      }elseif ($pagetype == "yachtsmall"){
          $pagename = $this->get_common_field_name('tbl_yacht', 'listing_no', $checkid);
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "boat/" . $pagename . "/";

      }elseif ($pagetype == "previewboat"){
          $pagename = $this->get_common_field_name('tbl_yacht', 'listing_no', $checkid);
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo . "previewboat/" . $pagename . "/";

      }elseif ($pagetype == "comprofile"){
          $pagename = $this->get_common_field_name("tbl_company", "slug", $checkid, "id");
          //$pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo."companyprofile/" . $pagename . "/";
      }elseif ($pagetype == "companyinv"){
          $pagename = $this->get_common_field_name("tbl_company", "slug", $checkid, "id");          
          $ret_url = $this->folder_for_seo."companyinventory/" . $pagename . "/";
      }elseif ($pagetype == "user"){
          $pagename = $this->get_common_field_name("tbl_user", "uid", $checkid, "id");
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo."profile/" . $pagename . "/";
      }elseif ($pagetype == "userinsidedb"){
          $pagename = $this->get_common_field_name("tbl_user", "uid", $checkid, "id");
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo."brokerprofile/" . $pagename . "/";
      }elseif ($pagetype == "soldboat"){
          $pagename = $this->get_common_field_name("tbl_user", "uid", $checkid, "id");
          $pagename = $this->serach_url_filtertext($pagename);
          $ret_url = $this->folder_for_seo."soldboat/" . $pagename . "/";
      }elseif ($pagetype == "brokersub"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."edit-brokerlist/" . $pagename . "/";
      }elseif ($pagetype == "managersub"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."edit-manager/" . $pagename . "/";
      }elseif ($pagetype == "locationsub"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."edit-locationadmin/" . $pagename . "/";
      }elseif ($pagetype == "locationofficesub"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."edit-locationoffice/" . $pagename . "/";
      }elseif ($pagetype == "announcement"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."announcement/" . $pagename . "/";
      }elseif ($pagetype == "resourceprofile"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."resourceprofile/" . $pagename . "/";
      }elseif ($pagetype == "^resourceslistings"){
          $pagename = $this->serach_url_filtertext($checkid);
          $ret_url = $this->folder_for_seo."resourceslistings/" . $pagename . "/";
      }elseif ($pagetype == "resourcetype"){
          $pagename = $this->get_common_field_name("tbl_resource_type", "slug", $checkid, "id");          
          $ret_url = $this->folder_for_seo."resources/" . $pagename . "/";
      }elseif ($pagetype == "manufacturerprofile"){
		  $pagename = $this->serach_url_filtertext($checkid);	
          $ret_url = $this->folder_for_seo."manufacturerprofile/" . $pagename . "/";
      }elseif ($pagetype == "locationprofile"){
		  $pagename = $this->serach_url_filtertext($checkid);	
          $ret_url = $this->folder_for_seo."locationprofile/" . $pagename . "/";
      }elseif ($pagetype == "blogcategory"){
			$pagename = $checkid;
			$ret_url = $this->folder_for_seo."blogcategory/" . $pagename . "/";
		}elseif ($pagetype == "blogtag"){
			$pagename = $checkid;
			$ret_url = $this->folder_for_seo."blogtag/" . $pagename . "/";
		}elseif ($pagetype == "blogarchive"){
			$pagename = $checkid;
			$ret_url = $this->folder_for_seo."blogarchive/" . $pagename . "/";
		}elseif ($pagetype == "blog"){
		  $pagename = $this->serach_url_filtertext($checkid);
		  $ret_url = $this->folder_for_seo."blog/" . $pagename . "/";
      }elseif ($pagetype == "boatslideshow"){
		  $pagename = $this->serach_url_filtertext($checkid);
		  $ret_url = $this->folder_for_seo."boatslideshow/" . $pagename . "/";
      }elseif ($pagetype == "customboatslideshow"){
          $ret_url = $this->folder_for_seo . "customboatslideshow/" . $checkid . "/";

      }elseif ($pagetype == "boatmodel"){
			$pagename = $this->serach_url_filtertext($checkid);
			$ret_url = $this->folder_for_seo."boatmodel/" . $pagename . "/";		  
	  	}elseif ($pagetype == "page"){
	  	  $pagedet_ar = $this->get_table_fields('tbl_page', 'page_type, int_page_id, int_page_tp, pgnm, page_url, doc_name, only_menu', $checkid);
		  $pagedet_ar = (object)$pagedet_ar[0];
		  
		  $page_type = $pagedet_ar->page_type;
		  if ($page_type == 1 OR $page_type == 4 OR $page_type == 5 ){	
		  	  $only_menu = $pagedet_ar->only_menu;
			  if ($only_menu == 1){
				  $ret_url = 'javascript:void(0);';
			  }else{
			  	  if ($checkid == 1){
					  $ret_url = $this->folder_for_seo;
				  }else{
					$ret_url = $this->folder_for_seo . $pagedet_ar->pgnm;
				  }
			  }
		  }elseif ($page_type == 3){
			  $int_page_id = $pagedet_ar->int_page_id;		  
			  if ($int_page_id > 0){
				$int_page_tp = $pagedet_ar->int_page_tp;
				if ($int_page_tp == "a"){
					//$ret_url = $this->get_page_url($int_page_id, $pagetype = "spage");
				}
				$ret_url = $this->get_seo_linked_url($int_page_id, $int_page_tp);		    
			  }else{
				$page_url = $pagedet_ar->page_url; 
				$ret_url = $page_url;
			  }	
		  }else{
			  $doc_name = $pagedet_ar->doc_name;
			  $ret_url = $this->folder_for_seo . "docfile/" . $doc_name;
		  }
	  }
	  return $ret_url;
  }
  //end
	
  //common month day year combo
  public function get_genmonth_combo($selmonth = 0, $smallop = 0){
       for ($k = 1; $k <= 12; $k++){
           if ($k < 10){ $kk = "0".$k; }else{ $kk = $k; }
           if ($smallop == 1){
               $ds = $this->lmonthid[$k];
           }else{
               $ds = $this->lmonth[$k];
           }

    ?>
    <option value="<?php echo $kk; ?>" <?php if ($selmonth == $k){ echo ' selected="selected"'; } ?>><?php echo $ds; ?></option>
    <?php       
       }
  }
    
  public function get_genday_combo($selday = 0){
        for ($k = 1; $k <= 31; $k++){
          if ($k < 10){ $kk = "0".$k; }else{ $kk = $k; }
    ?>
    <option value="<?php echo $kk; ?>" <?php if ($selday == $k){ echo ' selected="selected"'; } ?>><?php echo $k; ?></option>
    <?php       
        }
  }
    
  public function get_genyear_combo($selyear = 0, $start_cnt, $end_yr){
        $start_yr = date("Y") + $start_cnt;       
        for ($k = $start_yr; $k >= $end_yr;$k--){
    ?>
    <option value="<?php echo $k; ?>" <?php if ($selyear == $k){ echo ' selected="selected"'; } ?>><?php echo $k; ?></option>
    <?php       
        }
  }

  public function get_genyear_combo1($selyear = 0, $start_yr, $end_yr){
        for ($k = $start_yr; $k >= $end_yr;$k--){
            ?>
            <option value="<?php echo $k; ?>" <?php if ($selyear == $k){ echo ' selected="selected"'; } ?>><?php echo $k; ?></option>
        <?php
        }
  }
  //end
	
  //page
  public function collect_parent_page_category($pcatid, $catholder_ar, $arnt, $seol = "n"){
	 global $db; 
	 $c_prid = $db->total_record_count("select parent_id as ttl from tbl_page where id = '". $pcatid."'"); 
	 if ($c_prid > 0){   
	   $c_prnm = $db->total_record_count("select name as ttl from tbl_page where id = '". $c_prid."'");
	   $catholder_ar[$arnt]["name"] = $c_prnm;
	   if ($seol == "n"){
	        $catholder_ar[$arnt]["linkurl"] = "mod_page.php?parentid=" . $c_prid;
	   }else{
	        $catholder_ar[$arnt]["linkurl"] = $this->get_page_url($c_prid, "page");
            $catholder_ar[$arnt]["id"] = $c_prid;
	   }
	   $arnt++;
	   $catholder_ar = $this->collect_parent_page_category($c_prid, $catholder_ar, $arnt, $seol);
	 }
	 $catholder_ar = array_reverse($catholder_ar);
	 return $catholder_ar;
  }
	
  public function collect_top_parentpage_category($pcatid){
	    global $db;
	    $c_prid = $db->total_record_count("select parent_id as ttl from tbl_page where id = '". $pcatid."'");
	    if ($c_prid > 0){
	        return $this->collect_top_parentpage_category($c_prid);
	    }else{
	        return $pcatid;
	    }
  }
    
  public function count_sub_pages($page_id){
       global $db;
       $p_sql = "select count(*) as ttl from tbl_page where parent_id = '". $page_id ."'";
       $p_fnd = $db->total_record_count($p_sql);
       $p_fnd = round($p_fnd, 0);
       return $p_fnd;
  }
  //end

  //video
	public function youtube_thumb($video_id){
		echo '<img src="https://i4.ytimg.com/vi/'. $video_id .'/default.jpg" width="120" alt="" border="0" />'; 
	}
	
	public function create_youtube_share_url($rawurl){
		$shareurl = "";	
		if (strpos($rawurl,'www.youtube.com/watch') !== false) {
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $rawurl, $matches);
			$shareurl = "https://youtu.be/" . $matches[1];
		}else{
			if (strpos($rawurl,'youtu.be') !== false) {
				$shareurl = $rawurl;
			}
		}
		return $shareurl;		
	}
  
    public function get_youtube_video_code($link_url){
        $video_id_ar = explode("/", $link_url);
        $video_id_ar_cnt = count($video_id_ar);
        $video_id = $video_id_ar[$video_id_ar_cnt - 1];
        return $video_id;
    }

    public function play_youtube_video($video_id, $v_w, $v_h){
        $returntxt = '<iframe src="//www.youtube.com/embed/'. $video_id .'" frameborder="0" width="'. $v_w .'" height="'. $v_h .'"></iframe>';
        return $returntxt;
    }

	public function play_vimeo_video($video_id, $v_w, $v_h){
        $returntxt = '<iframe src="//player.vimeo.com/video/'. $video_id .'?title=0&amp;byline=0&amp;portrait=0&amp;color=abcb3c" frameborder="0" width="'. $v_w .'" height="'. $v_h .'" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        return $returntxt;
    }
	
	public function play_video($videoid, $v_w, $v_h, $folderpath = ""){
		global $db;
		$returntxt = '';
		$video_type = $this->get_common_field_name('tbl_yacht_video', 'video_type', $videoid);
		if ($video_type == 1){
			//youtube video			
			$video_id = $this->get_common_field_name('tbl_yacht_video', 'video_id', $videoid);
		    $returntxt = $this->play_youtube_video($video_id, $v_w, $v_h);
		}elseif ($video_type == 3){
			//vimeo video			
			$video_id = $this->get_common_field_name('tbl_yacht_video', 'video_id', $videoid);
		    $returntxt = $this->play_vimeo_video($video_id, $v_w, $v_h);
		}elseif ($video_type == 4){
			//external link video	
			$videopath = $this->get_common_field_name('tbl_yacht_video', 'link_url', $videoid);
			/*$returntxt = '
			<embed src="'. $videopath .'" height="'. $v_h .'" width="'. $v_w .'" autoplay="false" pluginspage="http://www.apple.com/quicktime/download/">			
			';*/
			
			$returntxt = '
			<video height="'. $v_h .'" width="'. $v_w .'" controls>
				<source src="'. $videopath .'" type="video/mp4">  
			</video>
			';
		}elseif ($video_type == 5){
			//external link video - with iframe
			$link_url = $this->get_common_field_name('tbl_yacht_video', 'link_url', $videoid);
			$returntxt = '<iframe allowfullscreen="" frameborder="0" src="'. $link_url .'"></iframe>';
		}else{			
			$videopath = $this->get_common_field_name('tbl_yacht_video', 'videopath', $videoid);
			/*$returntxt = '
			<embed src="'. $this->site_url.'/yachtvideo/'. $videopath .'" height="'. $v_h .'" width="'. $v_w .'" autoplay="false" pluginspage="http://www.apple.com/quicktime/download/">			
			';*/

			$returntxt = '
			<video height="'. $v_h .'" width="'. $v_w .'" controls>
				<source src="'. $this->site_url.'/yachtvideo/'. $videopath .'" type="video/mp4">  
			</video>
			';
		}		
		return $returntxt;
	}
  //end
  
 //age and date and time calculation
  public function agecalculator ($birthday){
    $currenttime = time();
    $dobtime = strtotime($birthday);
    $dob_diff = $currenttime - $dobtime;
    $no_of_days = ceil($dob_diff / (3600 * 24));
    //$year_d = (int)($no_of_days / 365);
    $year_age = ceil($no_of_days / 365);
    return $year_age;
  }

  public function displayinterval ($displaytime){
        $returntxt = '';
        $currenttime = time();
        $caltime = strtotime($displaytime);
        $time_diff = $currenttime - $caltime;
        if ($time_diff >= 0 AND $time_diff < 60){
            $returntxt = $time_diff . ' sec';
        }elseif ($time_diff >= 60 AND $time_diff < 3600){
            $min = (int)($time_diff / 60);
            $returntxt = $min . ' min';
        }elseif ($time_diff >= 3600 AND $time_diff < 86400){
            $hrs = (int)($time_diff / 3600);
            $returntxt = $hrs . ' hrs';
        }elseif ($time_diff >= 86400 AND $time_diff < 31536000){
            $days = (int)($time_diff / 86400);
            $returntxt = $days . ' days';
        }else{
            $years = (int)($time_diff / 31536000);
            $returntxt = $years . ' yrs';
        }
        return $returntxt;
  }
  //end 
   
 //field checking
 public function check_email_address($email) {
     $email = strtolower($email);
     $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})$/';
     if (preg_match($regex, $email)) {
         return "y";
     } else {
         return "n";
     }
 }
 
 public function string_length_check($text, $length_checck){
        $term_length = strlen($text);
        if ($term_length > $length_checck){
            return "Max characters allowed: " . $length_checck;
        }else{
            return 'y';
        }
 }

 public function check_dob_validation($birthday, $red_pg, $sesinitial = ''){
        $age = $this->agecalculator($birthday);
        if ($age < $this->minimum_age){
            $_SESSION[$sesinitial . "postmessage"] = '"Sorry, you must be at least 16 years old.';
            header('Location: '.$red_pg);
            exit;
        }
 }

 public function field_validation($c_field, $old_field, $fieldname, $red_pg, $tblnm, $checkfield, $select_enter, $sesinitial = ''){
        global $db;
        $displaymess_phase = '';
        if ($select_enter == 1){ $displaymess_phase = "Please enter "; }
        if ($select_enter == 2){ $displaymess_phase = "Please select " ; }
            
        if (trim($c_field) == "" OR trim($c_field) == "&nbsp;"){
            $_SESSION[$sesinitial . "postmessage"] = $displaymess_phase . $fieldname;
            if ($red_pg != ""){
				header('Location: '.$red_pg);
				exit;
			}else{
				return $_SESSION[$sesinitial . "postmessage"];
				exit;
			}
        }
        
        if ($fieldname == "Email Address"){
            $ck_emal = $this->check_email_address($c_field);
            if ($ck_emal == "n"){
                $_SESSION[$sesinitial . "postmessage"] = $displaymess_phase . $fieldname;
                if ($red_pg != ""){
					header('Location: '.$red_pg);
					exit;
				}else{
					return $_SESSION[$sesinitial . "postmessage"];
					exit;
				}
            }
        }
        
        if ($tblnm != ""){              
            if ($old_field != $c_field){    
                $found = $db->total_record_count("select count(*) as ttl from ". $tblnm ." where ". $checkfield ." = '". $this->filtertext($c_field) ."'");
                if ($found > 0){
                  $_SESSION[$sesinitial . "postmessage"] = $fieldname . " exists."; 
                  if ($red_pg != ""){
						header('Location: '.$red_pg);
						exit;
				  }else{
						return $_SESSION[$sesinitial . "postmessage"];
						exit;
				  }
                }
            }           
        }               
 }
 //end
 
	//session created for form 
	public function session_field_contact(){
		$datastring = "name,phone,contact_subject,email,message,promo_code";
		return $datastring;
	}
	
	public function session_field_sell_boat(){
		$datastring = "name,email,phone,lookingto,lengthinfo,manufacturer,model,boat_year,engines,overallcondition,boatworth,boatlocation,message";
		return $datastring;
	}
	
	public function session_field_webuyboat(){
		$datastring = "fname,lname,email,phone,newsletter_subscribe";
		$datastring .= ",boat_make,boat_model,boat_year,boat_engine_make,boat_engine_year,original_engine_year,boat_hours,boat_equipment,bottom_paint,any_issues,boat_online,boat_shopping";
		return $datastring;
	}
	
	public function session_field_boat_worth(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_make,boat_model,boat_year,boat_length,boat_condition";
		return $datastring;
	}
	
	public function session_field_charter_form(){
		$datastring = "name,email,charter_frequency,region_area,guests_no,charter_date_form,charter_time_form,charter_date_to,charter_time_to,message";
		return $datastring;
	}
 
	public function session_field_user(){
		$datastring = "d_username,d_password,d_email,type_id,company_id,location_id,";
		$datastring .= "fname,lname,cname,address,city,state,state_id,country_id,zip,phone,about_me";
		return $datastring;
	}
	
	public function session_field_location(){     
		$datastring .= "name,address,city,state,state_id,country_id,zip,phone,fax,time_zone_id,";
		$datastring .= "language_id,currency_id,unit_measure_id,status_id";
		return $datastring;
	}
	
	public function session_field_contact_broker(){
		$datastring = "fullname,email,phone,subject,message";
		return $datastring;
	}
	
	public function session_field_common_feedback(){
		$datastring = "fullname,email,phone,company_name,subject,message";
		return $datastring;
	}
	
	public function session_field_refer_friend(){
		$datastring = "femail,fname,stemail,message,sendmecopy";
		return $datastring;
	}
	
	public function session_field_contact_my_broker(){
		$datastring = "subject,message,sendmecopy";
		return $datastring;
	}
	
	public function session_field_boat_finder(){     
		$datastring .= "name,city,state,phone,email";
		$datastring .= ",boat_budget,boat_category,manufacturer,model,boat_size,boat_year,comments";
		return $datastring;
	}
	
	public function session_field_contact_resource(){
		$datastring = "fname,phone,lname,email,address,dob,city,drivers_license,state,zip";
		$datastring .= ",manufacturer_name,engine_make_name,model,engine_model,year,engine_no,length,horsepower_individual,intended_mooring_location,engine_type_id";
		$datastring .= ",intended_navigation_area,drive_type_id,fuel_type_id,previous_boats_owned,boating_experience_year,boating_courses_completed,boating_experience_year,prior_insurance_losses,prior_insurance_losses_details";
		return $datastring;
	}
	
	public function session_field_boat_transport(){
      $datastring = "fname,lname,email,address,city,state,zip,h_phone,w_phone,phone,fax,";
	  $datastring .= "type_id,year,manufacturer_name,model,model,length,beam,height,weight,draft,mast_length,";
	  $datastring .= "pickupdate,pickup_location,pick_marina_dealer_name,pick_contact_name,pick_phone,pick_address,pick_city,pick_state,pick_zip,";
	  $datastring .= "dropoff_location,drop_contact_name,drop_phone,drop_address,drop_city,drop_state,drop_zip,special_comments";
      return $datastring;
  }
	
	public function session_field_join(){
	  $datastring = "jname,jemail";
	  return $datastring;
	}
	
	public function session_field_create_listing(){     
	 $datastring .= "fname,lname,city,state,phone,email";
	 $datastring .= ",boat_location,boat_type,manufacturer,model,boat_size,boat_year,condition,comments";
	 return $datastring;
	}
	
	public function session_field_testimonial(){     
	  $datastring = "name,company_name,designation,website_url,boat_reference,message,rating";
	  return $datastring;
	}
	
	public function session_field_service_request(){
		$datastring = "name,phone,email,comments";
		$datastring .= ",boat_make,boat_model,boat_year,boat_hull_no,boat_hours,boat_warranty";
		$datastring .= ",engine_make,engine_model,engine_year,engine_type,engine_motor_no,engine_hp,fuel_type,kind_of_service";
		return $datastring;
	}
	
	public function session_field_parts_request(){
		$datastring = "name,phone,email,comments";
		$datastring .= ",boat_make,boat_model,boat_year,boat_hin,boat_vin,boat_part_no,boat_qty";
		return $datastring;
	}

	public function session_field_finance(){
		$datastring = "fname,lname,phone,email,social_security,dob";
		$datastring .= ",address,city,state,zip,country,address_year,address_month,own_rent,mortgage_rent_amount";
		$datastring .= ",prev_address,prev_city,prev_state,prev_zip,prev_country";
		$datastring .= ",employer,emp_address,emp_city,emp_state,emp_zip,emp_country,emp_phone,emp_year,emp_month,annual_income";
		$datastring .= ",prev_employer,prev_emp_address,prev_emp_city,prev_emp_state,prev_emp_zip,prev_emp_country,prev_emp_phone,prev_emp_year,prev_emp_month";
		$datastring .= ",ref_name1,ref_phone1,ref_relationship1";
		$datastring .= ",ref_name2,ref_phone2,ref_relationship2";
		return $datastring;
	}
	
	public function session_field_tradein_evaluation(){
		$datastring = "fname,lname,phone,email";
		$datastring .= ",boat_make,boat_model,boat_year,boat_color,boat_hours";
		$datastring .= ",engine_make,engine_model,engine_year,drive_type,engine_hp,notable_equipment";
		$datastring .= ",boat_insurance_claim,engine_condition,boat_manifolds,engine_water_ingestion,major_mechanical_repairs,service_records_available,engines_warranty_date,systems_operable";
		$datastring .= ",fiberglass_damaged,major_repair,tape_graphics_hull_damaged";
		$datastring .= ",tears_cracking,upholstered_parts,interior_flooded,cockpit_damage,condition_eisenglass_enclosure";
		return $datastring;
	}
	
	public function session_field_tradein_welcome(){
		$datastring = "name,email,boat_make_model,boat_year,boat_length";
		return $datastring;
	}
	
	public function session_field_talk_to_specialist(){
		$datastring = "name,email,phone,preferred_date,preferred_time";
		return $datastring;
	}
	
	public function session_field_ask_for_brochure(){
		$datastring = "name,email,phone,model_id";
		return $datastring;
	}
	
	public function session_field_lead_checkout_request(){
		$datastring = "fname,lname,phone,email,comments,enq_type";
		return $datastring;
	}
	
	public function session_field_boat_watcher(){
		$datastring = "reg_name0,email0,makeid0,schedule_days0,schedule_days_old0";
		return $datastring;
	}
	
	public function session_field_buyer_services_request(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_make,boat_model,boat_year,boat_location";
		$datastring .= ",boat_size,boat_ideal_brand,boat_budget,comments";
		return $datastring;
	}
	
	public function session_field_seller_services_request(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_make,boat_model,boat_year";
		$datastring .= ",boat_engines,boat_hours_on_engines,boat_location,comments";
		return $datastring;
	}
	
	public function session_field_increase_yacht_value(){
		$datastring = "name,email";
		$datastring .= ",boat_owned,newsletter";
		return $datastring;
	}
	
	public function session_field_boat_show_registration(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_details,boat_location,comments,newsletter";
		return $datastring;
	}
	
	public function session_field_open_yacht_days(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_details,boat_location,comments,newsletter";
		return $datastring;
	}
	
	public function session_field_chartering_your_yacht(){
		$datastring = "name,email,phone";
		$datastring .= ",boat_details,boat_location,comments,newsletter";
		return $datastring;
	}
	
	public function session_field_watch_price(){     
	  $datastring = "name,email,phone";
	  return $datastring;
	}
 
	public function create_session_for_form($datastring, $vl_ar = array()){
		$datastring_ar = explode(",", $datastring);
		$datastring_cnt = count($datastring_ar);
		for ($x = 0; $x < $datastring_cnt; $x++){
			$_SESSION["s_" . $datastring_ar[$x]] = $this->filtertextdisplay($vl_ar[$datastring_ar[$x]]);
		}
	}
  
	public function delete_session_for_form($datastring){
		$datastring_ar = explode(",", $datastring);
		$datastring_cnt = count($datastring_ar);
		for ($x = 0; $x < $datastring_cnt; $x++){
			unset($_SESSION["s_" . $datastring_ar[$x]]);        
		}
	}
  
	public function collect_session_for_form($datastring){
		$return_ar = array();
		$datastring_ar = explode(",", $datastring);
		$datastring_cnt = count($datastring_ar);
		for ($x = 0; $x < $datastring_cnt; $x++){
			$return_ar[$datastring_ar[$x]] = $_SESSION["s_" . $datastring_ar[$x]];      
		}   
		return $return_ar;
	}
  
	public function check_date_valid($strdate){
		if((strlen($strdate)<10)OR(strlen($strdate)>10)){
			return "n";
		}else{
			return "y";
		}
	}
	//end

   public function get_howdid_combo($how_did_id = 0){
        global $db;
        $vsql = "select id, name from  tbl_howdid where status = 'y' order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($how_did_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
   }

   public function get_how_contact_combo($how_contact_id = 0){
        global $db;
        $vsql = "select id, name from  tbl_howcontact where status = 'y' order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($how_contact_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
   }

   public function get_contact_subject($subject_id = 0){
        global $db;
        $vsql = "select id, name from tbl_contact_subject where status = 'y' order by rank";
        $vresult = $db->fetch_all_array($vsql);
        $vfound = count($vresult);
        for ($xk = 0; $xk < $vfound; $xk++){
            $vrow = $vresult[$xk];
            $c_id = $vrow['id'];
            $cname = $vrow['name'];
            ?>
            <option value="<?php echo $c_id; ?>"<?php if ($subject_id == $c_id){ echo ' selected="selected"';} ?>><?php echo $cname; ?></option>
        <?php
        }
   }

    public function display_multi_checkbox($ms, $chkoption, $sex_id = 1){
        global $db, $bdir;
        $extrasql = '';
        $orderby = ' order by name';
        $extraclass = '';
        if ($chkoption == "category"){
            $maintbl = "tbl_category";
            $checktbl = "tbl_yacht_category_assign";
            $checkfield = "category_id";
            $mainfield = "yacht_id";
            $checkbox_name = "category_id";
        }

        if ($chkoption == "engine"){
            $maintbl = "tbl_engine";
            $checktbl = "tbl_yacht_engine_assign";
            $checkfield = "engine_id";
            $mainfield = "yacht_id";
            $checkbox_name = "engine_id";
            $extraclass = 'enginecheckbox ';
        }
		
		if ($chkoption == "state"){
            $maintbl = "tbl_state";
            $checktbl = "tbl_resource_state";
            $checkfield = "state_id";
            $mainfield = "resource_id";
            $checkbox_name = "res_state_id";
            $extraclass = '';
        }

        $ck_sql = "select id, name from ". $maintbl ." where status_id = 1". $extrasql . $orderby;
        $ck_result = $db->fetch_all_array($ck_sql);
        $ck_found = count($ck_result);
        ?>
        <ul>
            <?php
            $ac_b = 0;
            foreach ( $ck_result as $ck_row){
                $r_id = $ck_row['id'];
                $r_name = $ck_row['name'];
                $wfound = $db->total_record_count("select count(*) as ttl from ". $checktbl ." where ". $mainfield ." = '". $ms ."' and ". $checkfield ." = '". $r_id ."'");
                if ($wfound > 0){ $vck = " checked"; }else{
                    if ($chkoption == "modelneeded" AND $ac_b == 0){ $vck = " checked"; }else{$vck = "";}
                }
                ?>
                <li><input class="<?php echo $extraclass?>checkbox" type="checkbox" name="<?php echo $checkbox_name?><?php echo $ac_b?>" id="<?php echo $checkbox_name?><?php echo $ac_b?>" value="<?php echo $r_id?>"<?php echo $vck?> /> <?php echo $r_name?></li>
                <?php $ac_b++; } ?>
        </ul>
    <?php
    }

    public function collect_top_parent_type($pcatid){
        global $db;
        $c_prid = $db->total_record_count("select parent_id as ttl from tbl_type where id = '". $pcatid."'");
        if ($c_prid > 0){
            return $this->collect_top_parent_type($c_prid);
        }else{
            return $pcatid;
        }
    }

    public function collect_parent_type($pcatid, $catholder_ar, $arnt, $seol = "n"){
        global $db;
        $c_prid = $db->total_record_count("select parent_id as ttl from tbl_type where id = '". $pcatid."'");
        if ($c_prid > 0){
            $c_prnm = $db->total_record_count("select name as ttl from tbl_type where id = '". $c_prid."'");
            $catholder_ar[$arnt]["name"] = $c_prnm;
            if ($seol == "n"){
                $catholder_ar[$arnt]["linkurl"] = "mod_type.php?parentid=" . $c_prid;
            }else{
                $catholder_ar[$arnt]["linkurl"] = $this->get_page_url($c_prid, "category");
            }
            $arnt++;
            $catholder_ar = $this->collect_parent_type($c_prid, $catholder_ar, $arnt, $seol);
        }

        $catholder_ar = array_reverse($catholder_ar);
        return $catholder_ar;
    }

    public function all_child_type($checkcid, $catsql){
        global $db;
        $chc_sql = "select id from tbl_type where parent_id = '". $checkcid ."' and status_id = 1 order by rank";
        $chc_result = $db->fetch_all_array($chc_sql);
        $chc_found = count($chc_result);
        if ($chc_found > 0){
            foreach( $chc_result as $chc_row ){
                $chc_id = $chc_row['id'];
                $catsql .= $chc_id . ", ";
                $catsql = $this->all_child_type($chc_id, $catsql);
            }
        }
        return $catsql;
    }

    public function total_child_type($checkcid){
        global $db;
        $chc_sql = "select count(*) as ttl from tbl_type where parent_id = '". $checkcid ."'";
        $chc_found = $db->total_record_count($chc_sql);
        return $chc_found;
    }

    public function price_format($price){
        return number_format($price, 0);
    }

    public function default_share_content(){
        return 'Boat from ' . $this->sitename;
    }

    public function facebook_share_url($param = array()){
        //param
		$default_param = array("template" => 1, "extraclass" => "");
		$param = array_merge($default_param, $param);	
		$template = round($param["template"], 0);
		$title = $param["title"];
		$content = $param["content"];
		$fullurl = $param["fullurl"];
		$extraclass = $param["extraclass"];
		//end		
		
		if ($content == ""){
            $content = $this->default_share_content();
        }		
		if ($template == 1){
			//$returntext = '<li><a title="Facebook Share" href="http://www.facebook.com/sharer.php?s=100&p[title]='. urlencode($title) .'&p[summary]='. urlencode($content) .'&p[url]='. urlencode($fullurl) .'" target="_blank"><img src="'. $this->folder_for_seo .'images/facebook.png" alt=""></a></li>';
			$returntext = '<li><a title="Facebook Share" href="https://www.facebook.com/sharer/sharer.php?u='. urlencode($fullurl) .'" target="_blank"><img src="'. $this->folder_for_seo .'images/facebook.png" alt="Facebook"></a></li>';
		}else{
			//$returntext = '<a title="Facebook Share" href="http://www.facebook.com/sharer.php?s=100&p[title]='. urlencode($title) .'&p[summary]='. urlencode($content) .'&p[url]='. urlencode($fullurl) .'" target="_blank"><i class="fab fa-facebook-square"></i>';
			$returntext = '<a title="Facebook Share" href="https://www.facebook.com/sharer/sharer.php?u='. urlencode($fullurl) .'" target="_blank"><i class="fab fa-facebook-square"></i><span class="com_none">Facebook</span></a>';
		}		
        
        return $returntext;
    }

    public function googleplus_share_url($param = array()){
		
		//param
		$default_param = array("template" => 1);
		$param = array_merge($default_param, $param);	
		$template = round($param["template"], 0);
		$fullurl = $param["fullurl"];
		//end
		
		if ($template == 1){
			 $returntext = '<li><a title="Google + Share" href="https://plusone.google.com/_/+1/confirm?hl=en&url='. urlencode($fullurl) .'" target="_blank"><img src="'. $this->folder_for_seo .'images/googleplus.png" alt="Google Plus"></a></li>';
		}else{
			 $returntext = '<a title="Google + Share" href="https://plusone.google.com/_/+1/confirm?hl=en&url='. urlencode($fullurl) .'" target="_blank"><i class="fab fa-google-plus-g"></i><span class="com_none">Google Plus</span></a>';
		}
       
        return $returntext;
    }

    public function twitter_share_url($param = array()){
        
		//param
		$default_param = array("template" => 1, "extraclass" => "");
		$param = array_merge($default_param, $param);	
		$template = round($param["template"], 0);
		$title = $param["title"];
		$content = $param["content"];
		$fullurl = $param["fullurl"];
		$extraclass = $param["extraclass"];
		//end
		
		if ($content == ""){
            $content = $this->default_share_content() . ' ' . $fullurl;
        }
		
		if ($template == 1){
			$returntext = '<li><a title="Twitter Post" href="https://twitter.com/intent/tweet?text='. urlencode($content) .'" target="_blank"><img src="'. $this->folder_for_seo .'images/twitter.png" alt="Twitter"></a></li>';
		}else{
			$returntext = '<a title="Twitter Post" href="https://twitter.com/intent/tweet?text='. urlencode($content) .'" target="_blank"><i class="fab fa-twitter"></i><span class="com_none">Twitter</span></a>';
		}
        
        return $returntext;
    }

    public function linkedin_share_url($param = array()){
        
		//param
		$default_param = array("template" => 1, "extraclass" => "");
		$param = array_merge($default_param, $param);	
		$template = round($param["template"], 0);
		$title = $param["title"];
		$content = $param["content"];
		$fullurl = $param["fullurl"];
		$extraclass = $param["extraclass"];
		//end
		
		if ($content == ""){
            $content = $this->default_share_content();
        }
		
		if ($template == 1){
			$returntext = '<li><a title="Linkedin Post" href="https://www.linkedin.com/shareArticle?mini=true&url='. urlencode($fullurl) .'&title='. urlencode($title) .'&summary='. urlencode($content) .'&source='. $this->sitename .'" target="_blank"><img src="'. $this->folder_for_seo .'images/linkden.png" alt="Linkedin"></a></li>';
		}else{
			$returntext = '<a title="Linkedin Post" href="https://www.linkedin.com/shareArticle?mini=true&url='. urlencode($fullurl) .'&title='. urlencode($title) .'&summary='. urlencode($content) .'&source='. $this->sitename .'" target="_blank"><i class="fab fa-linkedin-in"></i><span class="com_none">Linkedin</span></a>';
		}
		
        return $returntext;
    }

    public function pinterest_share_url($param = array()){
        
		//param
		$default_param = array("template" => 1, "extraclass" => "");
		$param = array_merge($default_param, $param);	
		$template = round($param["template"], 0);
		$title = $param["title"];
		$listing_no = round($param["listing_no"], 0);
		$content = $param["content"];
		$image = $param["image"];
		$fullurl = $param["fullurl"];
		$extraclass = $param["extraclass"];
		//end
		
		$image = $this->site_url . '/yachtimage/'. $listing_no .'/bigger/'.$image;
        
		if ($template == 1){
			$returntext = '<li><a title="Pinterest Post" href="https://pinterest.com/pin/create/button/?url='. urlencode($fullurl) .'&media='. urlencode($image) .'&description='. urlencode($title) .'" target="_blank"><img src="'. $this->folder_for_seo .'images/pinterest.png" alt="Pinterest"></a></li>';
		}else{
			$returntext = '<a title="Pinterest Post" href="https://pinterest.com/pin/create/button/?url='. urlencode($fullurl) .'&media='. urlencode($image) .'&description='. urlencode($title) .'" target="_blank"><i class="fab fa-pinterest-p"></i><span class="com_none">Pinterest</span></a>';
		}
		
        return $returntext;
    }
	
	public function meta_open_graph($title, $content, $imageurl, $fullurl){		
		if ($imageurl == ""){
			$imageurl = $this->site_url . "/images/shareimg.png";
		}
		$returntext = '
		<meta property="og:title" content="'. $title .'" />
		<meta property="og:description" content="'. $content .'" />
		<meta property="og:url" content="'. $fullurl .'" />
		<meta property="og:image" content="'. $imageurl .'" />
		
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="'. $content .'" />
		<meta name="twitter:title" content="'. $title .'" />
		<meta name="twitter:image" content="'. $imageurl .'" />	  
		';				
		return $returntext;
	}
	
	public function google_map_js_include(){
		$returntext = '
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='. $this->googlemapkey .'&#038;ver=3.0"></script>
		<script type="text/javascript" src="'. $this->folder_for_seo .'js/markerclusterer_packed.js?ver=2"></script>
		<script type="text/javascript" src="'. $this->folder_for_seo .'js/infobox_packed.js?ver=3.0"></script>
		<script type="text/javascript" src="'. $this->folder_for_seo .'js/maplisting.js"></script>
		';
		return $returntext;
	}
	
	//download files
	public function downloadfiles(){
		if(($_REQUEST['fcapi'] == "downloadfiles")){
			$this->filedownload(0);
		}
	}
	
	public function filedownload($ad_front = 1){
       global $db;
       $fileid = $_REQUEST["fileid"];
       $opt = round($_REQUEST["opt"], 0); 
	   $outsiderequest = 0;
	   
	   if ($opt == 1){
            $tbl_name = "tbl_yacht_file";
            $where_field = "id";
            $tbl_field = "filepath";
            $tbl_field_original = "originalname";
            $all_filed = $tbl_field . ", " . $tbl_field_original;
            $all_filed = rtrim($all_filed, ", ");
            $foldernm = "yachtfiles";			
       }
       
       $sql = "select ". $all_filed ." from ". $tbl_name ." where ". $where_field ." = '". $this->filtertext($fileid) ."'";    
       $result = $db->fetch_all_array($sql);
       $found = count($result);    
       if ($found > 0){		   
           $row = $result[0];
           $upfilename = $row[$tbl_field];
           if ($tbl_field_original != ""){
                $original_name = $row[$tbl_field_original];
           }else{
               $original_name = $upfilename;
           }           
            
            if ($ad_front == 1){
				$foldernm = "../" . $foldernm;
			}
                
            $readfilename = $foldernm . "/" . $upfilename; 
            $size = filesize($readfilename);
                    
            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false); // required for certain browsers
            
            header("Content-Type: application/force-download"); 
            header("Content-Length: ".$size); 
             
            header("Content-Disposition: attachment; filename=".$original_name); 
            header("Content-Transfer-Encoding: binary"); 
            $fd = fopen("$readfilename", "rb"); 
            
            fpassthru($fd);
            fclose($fd);
           
       }else{
           if ($ad_front == 2){
                global $frontend;  
                $_SESSION["ob"] = $frontend->display_message(5);
                header('Location: '. $this->folder_for_seo .'sorry/');
           }else{
                $_SESSION["admin_sorry"] = "You have selected an invalid record.";
                header('Location: sorry.php');
                exit;
           }
       }       
   }

    public function generate_pdf($folderpath, $html, $headertext, $filename, $saveop = "D", $scripcont = 0){
        include($folderpath . "mpdf/mpdf.php");
		
		$html = '
		<!DOCTYPE html>
		<html>
		<head>
		<title>Print Details</title>
		<style>
		body
		{
			width:100%;
			margin:0;
			padding:0;
			font-family: Arial
			font-size: 13px;
		}		
		
		h3.subtitle { text-transform:uppercase; margin-bottom:10px; }
		
		.specifications h3{text-transform:uppercase; margin-bottom:10px;}
		.specifications-row{padding:20px 0; margin-bottom:25px; border-bottom:solid 1px #dcdcdc; border-top:solid 1px #dcdcdc;}
		.specifications-row .devider{background:url('. $this->site_url .'/images/devider.jpg) center 0 repeat-y;}
		.specifications-row .left-side{width:45%; float:left;}
		.specifications-row .right-side{width:45%; float:right;}
		.specifications-row dl{padding:0; margin:0; float:left; width:100%;}
		.specifications-row dt{padding:0; margin:0; float:left; width:37%; color:#000000;}
		.specifications-row dd{padding:0; margin:0; margin-left:40%;}
		
		.print-inv-holder { width: 100%; margin: 0 auto; padding: 0;}
		.print-inv-holder img { width: 100%; }
		</style>
		<body>
		'. $html .'
		</body>
		</html>
		';
		
		
		if ($headertext != ""){
			$mpdf = new mPDF('c','A4','','',15,15,30,10,1,1);
			$mpdf->SetHTMLHeader($headertext);
		}else{
			$mpdf = new mPDF('c','A4','','',15,15,10,10,1,1);
		}

        $mpdf->WriteHTML($html);
		if ($saveop == 'S'){
			return $mpdf->Output('', $saveop);
		}else{
			$mpdf->Output($filename, $saveop);
			if ($scripcont == 0){
        		exit;
			}
		}      
    }
	
	//parse content for shortcode
	public function passed_content_for_shortcode($content){
		global $shortcodeclass;
		return $shortcodeclass->do_shortcode($content);
	}
	
	//parse content for readmore
	public function passed_content_for_readmore_block($content){
		if ( preg_match( '/<!--more-->/', $content, $matches ) ) {
			$content = str_replace("<p><!--more--></p>", "<!--more-->", $content);
			$content = str_replace("<p>
	<!--more--></p>", "<!--more-->", $content);	
			$content_ar = explode( $matches[0], $content, 2 );
			$open_content = $content_ar[0];
			$rest_content = $content_ar[1];
			
			if ( preg_match( '/<!--open-->/', $rest_content, $matches ) ) {
				$rest_content = str_replace("<p><!--open--></p>", "<!--open-->", $rest_content);
				$rest_content = str_replace("<p>
	<!--open--></p>", "<!--open-->", $rest_content);
				$rest_content_ar = explode( $matches[0], $rest_content, 2 );
				
				$close_content = $rest_content_ar[0];
				$free_content = $rest_content_ar[1];
			}else{
				$close_content = $rest_content;
				$free_content = '';
			}
			
			$close_content = '
			<div class="contentexpand"><a href="javascript:void(0);" class="button">Read More</a></div>
			<div class="contentclosed clearfixmain">'. $close_content .'</div>
			<div class="contentcollapse"><a href="javascript:void(0);" class="button">Collapse</a></div>
			';
			
			$content = $open_content . $close_content . $free_content;
		}
		
		return $content;
	}
	
	//thanks page redirect
	public function thankyouredirect($messageno, $inum = ""){
		global $db, $frontend;
		$extramessage = '';				
		$tmessage = $frontend->display_message($messageno, $inum);
		$_SESSION["thnk"] = $tmessage . $extramessage;
		$gotopage = $this->get_page_url('', 'thankyou');
		header('Location: '. $gotopage);
		exit;
	}
	
	//sorry page redirect
	public function sorryredirect($messageno, $extramessage = "", $inum = ""){
		global $db, $frontend;
		$extramessage = '';		
		$tmessage = $frontend->display_message($messageno, $inum) . $extramessage;
		$_SESSION["ob"] = $tmessage;
		$gotopage = $this->get_page_url('', 'sorry');
		header('Location: '. $gotopage);
		exit;
	}
	
	//page id by shortcode
	public function get_page_id_by_shortcode($shortcode){
		global $db;
		$sql = "select id as ttl from tbl_page where file_data like '%". $shortcode ."%'";	   
		$getid = $db->total_record_count($sql);
		return $getid;
	}

	//page id by slug
	public function get_page_id_by_slug($pageslug){
		global $db;
		$sql = "select id as ttl from tbl_page where pgnm = '". $this->filtertext($pageslug) ."' and status = 'y' order by id asc limit 0, 1";	   
		$getid = $db->total_record_count($sql);
		return $getid;
	}

	//404 / 301
	public function check_404_301_redirect($pageslug){
		global $db;

		$sql = "select newurl from tbl_page_301 where oldurl like '/". $this->filtertext($pageslug) ."%' limit 0,1";
		$result = $db->fetch_all_array($sql);
		$found = count($result);

		if ($found > 0){
			$row = $result[0];
			$newurl = $this->filtertextdisplay($row["newurl"]);
			$newurl = ltrim($newurl, "/");
			header('Location: '. $this->folder_for_seo . $newurl);
			exit;
		}else{
			$templatefile = "pages/404.php";
			return $templatefile;
		}
	}
	
	//format page slug
	public function format_page_slug(){
		$pageslug = $_REQUEST["pageslug"];
		$pageslug = rtrim($pageslug, "/");
		return $pageslug;
	}
	
	//----------------data session--------------------------
	public function get_actual_url_from_header(){
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER[HTTP_HOST];
		return $actual_link;
	}

	//insert page specific data into table - by session
	public function insert_data_set($param = array()){
		global $db;
		
		//param
		$to_check_val = $param["to_check_val"];
		$to_get_val = $param["to_get_val"];
		$section_for = $param["section_for"]; //1 = Page ID, 2 = Generated SQL for boat List
		//end

		if ($to_check_val == ""){
			$to_check_val = $this->get_actual_url_from_header() . $_SERVER["REQUEST_URI"];
		}
		$sessionid = session_id();
		
		$sql = "select to_get_val as ttl from tbl_datastore_by_session where sessionid = '". $sessionid ."' and to_check_val = '". $this->filtertext($to_check_val) ."' and section_for = '". $this->filtertext($section_for) ."'";
		$checkexist = $db->num_rows($sql);

		if ($checkexist){
			$sql = "update tbl_datastore_by_session set to_get_val = '". $this->filtertext($to_get_val) ."', added_on = '". time() ."' where sessionid = '". $sessionid ."' and to_check_val = '". $this->filtertext($to_check_val) ."' and section_for = '". $this->filtertext($section_for) ."'";
			$db->mysqlquery($sql);
		}else{
			$sql = "insert into tbl_datastore_by_session(sessionid, to_check_val, to_get_val, added_on, section_for) values ('". $sessionid ."', '". $this->filtertext($to_check_val) ."', '". $this->filtertext($to_get_val) ."', '". time() ."', '". $this->filtertext($section_for) ."')";
			$db->mysqlquery($sql);
		}
		
		return $to_check_val;
	}
	
	//get page specific data into table - by session
	public function get_data_set($param = array()){
		global $db;
		
		//param
		$to_check_val = $param["to_check_val"];
		$section_for = $param["section_for"]; //1 = Page ID, 2 = Generated SQL for boat List
		//end
		
		$sessionid = session_id();
		if ($section_for == 1){
			$to_check_val = $_SERVER['HTTP_REFERER'];
		}
		
		$sql = "select to_get_val as ttl from tbl_datastore_by_session where sessionid = '". $sessionid ."' and to_check_val = '". $this->filtertext($to_check_val) ."' and section_for = '". $this->filtertext($section_for) ."' order by added_on desc limit 0, 1";
		$to_get_val = $db->total_record_count($sql);
		return $to_get_val;
	}

	//remove page specific data from table - by session
	public function remove_data_set(){
		global $db;
		$currenttime = time();
		$timetoremove = $currenttime - (24 * 60 * 60);

		$sql = "delete from tbl_datastore_by_session where added_on <= '". $timetoremove ."'";
		$db->mysqlquery($sql);
	}
	
	//Top level Menu section option
	public function top_menu_section_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "None");
		$val_ar[] = array("name" => "New To The Market");
		$val_ar[] = array("name" => "Featured Yachts");
		//$val_ar[] = array("name" => "Featured Destinations");
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	
	public function get_top_menu_section_checkbox($param = array()){
        global $db;
		
		//param
		$ulclass = $param["ulclass"];
		$submenusection = json_decode($param["submenusection"]);
		//end
		
		$val_ar = json_decode($this->top_menu_section_val());		
		$returntext = '';
  
        foreach($val_ar as $ar_key => $val_row){
            $cname = $val_row->name;
			if ($ar_key > 0){
				
				$vck = '';
				if (in_array($ar_key, $submenusection)){
					$vck = ' checked="checked"';
				}
								
				$returntext .= '<li><input class="checkbox" type="checkbox" name="section_type_id[]" value="'. $ar_key .'"  id="section_type_id'. $ar_key .'"'. $vck .' /> '. $cname .'</li>';
			}
        }
		
		if ($returntext != ""){
			$returntext = '<ul class="'. $ulclass .'">' . $returntext . '</ul>';
		}
				
		return $returntext;
    }

	//Is mobile
	public function isMobileDevice(){
		$aMobileUA = array(
			'/iphone/i' => 'iPhone', 
			'/ipod/i' => 'iPod', 
			'/ipad/i' => 'iPad', 
			'/android/i' => 'Android', 
			'/blackberry/i' => 'BlackBerry', 
			'/webos/i' => 'Mobile'
		);
	
		//Return true if Mobile User Agent is detected
		foreach($aMobileUA as $sMobileKey => $sMobileOS){
			if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
				return true;
			}
		}
		//Otherwise return false..  
		return false;
	}
}
?>
