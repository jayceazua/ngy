<?php
class Leadclass {
	
	public $leadformtype = array(
		"key1" => array("name" => "Contact Us", "messagebr" => 1, "id" => 1),
		"key2" => array("name" => "Contact Broker", "messagebr" => 1, "id" => 2),
		"key3" => array("name" => "Contact Broker - Boat", "messagebr" => 1, "id" => 3),
		"key4" => array("name" => "Sell your Boat", "messagebr" => 0, "id" => 4),
		//"key5" => array("name" => "Boat Worth", "messagebr" => 0, "id" => 5),
		"key6" => array("name" => "Newsletter Sign-up", "messagebr" => 1, "id" => 6),
		"key7" => array("name" => "Contact YC Model", "messagebr" => 1, "id" => 7),
		"key8" => array("name" => "Boat Finder", "messagebr" => 0, "id" => 8),
		//"key9" => array("name" => "Trade-In Welcome", "messagebr" => 0, "id" => 9),
		"key10" => array("name" => "Talk To A Specialist", "messagebr" => 0, "id" => 10),
		"key11" => array("name" => "Ask For Brochure", "messagebr" => 0, "id" => 11),
		//"key12" => array("name" => "Service Request", "messagebr" => 0, "id" => 12),
		//"key13" => array("name" => "Parts Request", "messagebr" => 0, "id" => 13),
		//"key14" => array("name" => "Finance", "messagebr" => 0, "id" => 14),
		//"key15" => array("name" => "We Buy Boats", "messagebr" => 0, "id" => 15),
		//"key16" => array("name" => "Lead Chekout - Email Broker", "messagebr" => 0, "id" => 16),
		//"key17" => array("name" => "Lead Chekout - Get Financed", "messagebr" => 0, "id" => 17),
		//"key18" => array("name" => "Lead Chekout - Price Drop", "messagebr" => 0, "id" => 18),
		//"key19" => array("name" => "Lead Chekout - Send Review", "messagebr" => 0, "id" => 19),
		"key20" => array("name" => "Next Generation Yachting", "messagebr" => 0, "id" => 20),
		"key21" => array("name" => "Boat Evaluation", "messagebr" => 0, "id" => 21),
		"key22" => array("name" => "Increase Yacht Value", "messagebr" => 0, "id" => 22),
		"key23" => array("name" => "Boat Show Registration", "messagebr" => 0, "id" => 23),
		"key24" => array("name" => "Open Yacht Days", "messagebr" => 0, "id" => 24),
		"key25" => array("name" => "Chartering Your Yacht", "messagebr" => 0, "id" => 25),
		"key26" => array("name" => "Contact Local Model", "messagebr" => 1, "id" => 26),
		"key27" => array("name" => "Watch Price", "messagebr" => 0, "id" => 27),
		"key28" => array("name" => "We Can Sell Your Yacht", "messagebr" => 0, "id" => 28),
		"key29" => array("name" => "Charter Boat Enquire", "messagebr" => 0, "id" => 29)
	);
	
	//Lead Form Type Combo
	public function get_lead_form_type_combo($lead_type_id = 0){
		$returntxt = '';
		$lead_type_ar = $this->leadformtype;
		foreach ($lead_type_ar as $key => $lead_type_row){
			$name = $lead_type_row["name"];
			$id = $lead_type_row["id"];
			
			$bck = '';
			if ($lead_type_id == $id){
				$bck = ' selected="selected"';	
			}
			
			$returntxt .= '<option value="'. $id .'"'. $bck .'>'. $name .'</option>';
		}
		return $returntxt;
	}

	//Add lead - after form submit
	public function add_lead_message($param = array()){
		global $db, $cm, $yachtclass, $ymclass;
		$dt = date("Y-m-d H:i:s");
		$lead_id = time() .  $cm->campaignid(5);
		
		$form_type = $param["form_type"];
		$name = $param["name"];
		$email = $param["email"];
		$phone = $param["phone"];
		$message = $param["message"];
		$broker_id = round($param["broker_id"], 0);
		$yacht_id = round($param["yacht_id"], 0);
		
		$sql = "insert into tbl_form_lead (id
                                               , yacht_id
											   , form_type
											   , broker_id
                                               , name
											   , email
											   , phone
											   , message
                                               , reg_date) values ('". $cm->filtertext($lead_id) ."'
                                               , '". $yacht_id ."'
											   , '". $form_type ."'
											   , '". $broker_id ."'
                                               , '". $cm->filtertext($name) ."'
											   , '". $cm->filtertext($email) ."'
											   , '". $cm->filtertext($phone) ."'
											   , '". $cm->filtertext($message) ."'
                                               , '". $dt ."')";
											   
		$db->mysqlquery($sql);
	}
	
	//display lead list
	public function form_lead_list_sql($broker_id){
		global $db, $cm;
		
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id', $broker_id);
		$cuser_type_id = $cuser_ar[0]["type_id"];
		
		$fdate = $_REQUEST["fdate"];
		$tdate = $_REQUEST["tdate"];
		$leadformtype = round($_REQUEST["leadformtype"], 0);
		
		$fdate_a = $cm->set_date_format($fdate);
		$tdate_a = $cm->set_date_format($tdate);
		
		$query_sql = "select *,";
        $query_form = " from tbl_form_lead,";
        $query_where = " where";
		
		$query_where .= " form_type > 0 and";
		
		if ($cuser_type_id != 1 AND $cuser_type_id != 2 ){
			$query_where .= " broker_id = '". $broker_id ."' and";
		}
		
		if ($fdate_a != "0000-00-00"){
			$fdate_a = $fdate_a . " 00:00:00";
			$query_where .= " reg_date >= '". $fdate_a ."' and";
		}
		
		if ($tdate_a != "0000-00-00"){
			$tdate_a = $tdate_a . " 23:59:59";
			$query_where .= " reg_date <= '". $tdate_a ."' and";
		}
		
		if ($leadformtype > 0){
			$query_where .= " form_type = '". $leadformtype ."' and";
		}
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
		return $sql;
	}
	
	public function total_form_lead_list_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
    }
	
	public function form_lead_list($p, $pp = 0, $allrec = 0, $broker_id = 0){
		global $db, $cm, $yachtclass, $ymclass, $modelclass, $charterboatclass;
        $returntext = '';
        $moreviewtext = '';
		$limitsql = '';
		
		if ($broker_id == 0){
			$broker_id = $yachtclass->loggedin_member_id();
		}
		
		if ($allrec == 0){
			$dcon = $cm->pagination_record_list;
				
			if ($pp == 1){
				$page = 0;	
				$dcon_updated = $dcon * $p;
			}else{
				$dcon_updated = $dcon;
				$page = ($p - 1) * $dcon_updated;
				if ($page <= 0){ $page = 0; }
			}

			$limitsql = " LIMIT ". $page .", ". $dcon_updated;
		}else{
			require_once 'excel-classes/PHPExcel.php';
			require_once 'excel-classes/PHPExcel/IOFactory.php';
			
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);
		}
		
		$sorting_sql = "reg_date desc";
		
		$sql = $this->form_lead_list_sql($broker_id);
		$foundm = $this->total_form_lead_list_found($sql);
		
		$sql = $sql." order by ". $sorting_sql . $limitsql;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		$remaining = $foundm - ($p * $dcon);
				
		if ($found > 0){
			if ($allrec == 0){
				if ($p == 1){
					$returntext .= '
					<div class="divrow thd clearfixmain">
						<div class="ld_del">Del</div>
						<div class="ld_name">Name</div>
						<div class="ld_email">Email</div>
						<div class="ld_phone">Phone</div>
						<div class="ld_boat">Boat</div>
						<div class="ld_cn">Contacted</div>
						<div class="ld_ft">Form Type</div>
						<div class="ld_dt">Date</div>
					</div>
				';
				}
            }else{
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Email');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Phone');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Boat');
				$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Contacted');
				$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Form Type');				
				$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Message');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Date');
			}
			
			$cellcounter = 2;
			$counter = 1;
			foreach($result as $row){
				
				foreach($row AS $key => $val){
                    ${$key} = $cm->filtertextdisplay(($val));
                }
	
				$listing_no = '';	
				if ($form_type == 7){
					$retval = json_decode($ymclass->get_manufacturer_model_name_by_id($yacht_id));
					$yacht_title = $retval->boat_title;
				}elseif ($form_type == 26){
					$yacht_title = $modelclass->get_model_name($yacht_id);
				}elseif ($form_type == 29){
					$yacht_title = $charterboatclass->get_boat_name($yacht_id);
				}else{
					$yacht_title = $yachtclass->yacht_name($yacht_id);
					$listing_no = $yachtclass->get_yacht_no($yacht_id);
				}
								
				$contacted_to = $yachtclass->member_field('concat(fname, \' \', lname)', $broker_id);					
				$date_summitted = $cm->display_date($reg_date, 'y', 10);
				
				$form_type_text = $this->leadformtype["key" . $form_type]["name"];
				$messagebr = $this->leadformtype["key" . $form_type]["messagebr"];
				if ($messagebr == 1){
					$message_display = nl2br($message);
				}else{
					$message_display = $message;
				}
				
				$message_display_text = '';
				if ($message != ""){
					$message_display_text = '
					<div class="clearfix"></div>
					<div class="ld_fm">'. $message_display .'</div>
					';
				}
				
				if ($allrec == 0){				
					$returntext .= '
					<div class="divrow clearfixmain">
						<div class="ld_del"><a class="lead_del" p="'. $p .'" rowval="'. $counter .'" lead_id="'. $id .'" href="javascript:void(0);" title="Delete Lead"><img alt="Delete Lead" title="Delete Lead" src="'. $cm->folder_for_seo .'images/del.png" /></a></div>
						<div class="ld_name">'. $name .'</div>                    
						<div class="ld_email">'. $email .'</div>
						<div class="ld_phone">'. $phone .'</div>
						<div class="ld_boat">'. $yacht_title .'<br />'. $listing_no .'</div>
						<div class="ld_cn">'. $contacted_to .'</div>
						<div class="ld_ft">'. $form_type_text .'</div>
						<div class="ld_dt">'. $date_summitted .'</div>		
						'. $message_display_text .'
					</div>
					';
				}else{
					$message = str_replace("<tr>", "\r", $message);
					$message = strip_tags($message);
					$objPHPExcel->getActiveSheet()->setCellValue('A' . $cellcounter, $name);
					$objPHPExcel->getActiveSheet()->setCellValue('B' . $cellcounter, $email);
					$objPHPExcel->getActiveSheet()->setCellValue('C' . $cellcounter, $phone);
					$objPHPExcel->getActiveSheet()->setCellValue('D' . $cellcounter, $yacht_title .' - '. $listing_no);
					$objPHPExcel->getActiveSheet()->setCellValue('E' . $cellcounter, $contacted_to);
					$objPHPExcel->getActiveSheet()->setCellValue('F' . $cellcounter, $form_type_text);
					$objPHPExcel->getActiveSheet()->setCellValue('G' . $cellcounter, $message);	
					$objPHPExcel->getActiveSheet()->setCellValue('H' . $cellcounter, $date_summitted);				
					$cellcounter++;					
				}
				$counter++;
			}
			
			if ($allrec == 0){			
				$p++;
				if ($remaining > $dcon){
					$button_no = $dcon;
				}else{
					$button_no = $remaining;
				}
				
				if ($remaining > 0){
					$moreviewtext .= '
					<a href="javascript:void(0);" fsection="1" p="'. $p .'" class="moreviewleads button loding"><span>Loading <recno>'. $button_no .'</recno> more record(s)</span></a>
					';
				}else{
					$moreviewtext = '';
				}
			}
		}
		
		if ($allrec == 0){		
			$returnval[] = array(
				'doc' => $returntext,
				'totalrec' => $foundm,
				'moreviewtext' => $moreviewtext
			);
			return json_encode($returnval);
		}else{
			$objPHPExcel->getActiveSheet()->setTitle('Lead Data');
			$filename = "leadlist.xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename .'"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
		}
	}
	
	//lead delete
	public function lead_delete($postfields = array()){
		global $db, $cm;
		
		$lead_id = $postfields["lead_id"];
		
		$sql = "delete from tbl_form_lead where id = '". $cm->filtertext($lead_id) ."'";
    	$db->mysqlquery($sql);
		
		$returnarray = array(
			'returntext' => "y"
	    );
		return json_encode($returnarray);
	}
	//end
	
	//export leads
	public function export_leads(){
		if(($_POST['fcapi'] == "exportleads")){
			global $db, $cm;
			
			//field data checking
			$email2 = $_POST["email2"];
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			//end
			
			$this->form_lead_list(0, 0, 1);
		}
	}
}
?>