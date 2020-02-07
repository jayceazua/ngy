<?php
class Ymclass {
	private $connect_url = "";
	private $tokencode = "";
	private $website_url = '';
	
	public function __construct(){
		global $cm;
		$mainsite_ar = $cm->get_table_fields('tbl_mainsite', 'site_url, tokencode', 1);
		$this->connect_url = $mainsite_ar[0]['site_url'] . '/includes/subsite/';
		$this->tokencode = $mainsite_ar[0]['tokencode'];
		$this->website_url = $cm->site_url;
		//$this->website_url = preg_replace("(^https?://)", "", $this->website_url );
	}
	
	public function get_curl_response($postfields, $connectoption = 0){	
		$connecturl = $this->connect_url;	
		if ($connectoption == 1){
			$connecturl .= 'makemodellist.php';
		}elseif ($connectoption == 2){
			$connecturl .= 'makemodeldetails.php';
		}elseif ($connectoption == 3){
			$connecturl .= 'makelist.php';
		}elseif ($connectoption == 4){
			$connecturl .= 'makemodellist-featured.php';
		}elseif ($connectoption == 5){
			$connecturl .= 'fetch_model_by_make_year.php';
		}elseif ($connectoption == 6){
			$connecturl .= 'fetch_model_media_by_make_year.php';
		}elseif ($connectoption == 7){
			$connecturl .= 'makelist-raw.php';
		}elseif ($connectoption == 8){
			$connecturl .= 'makemodeldetailsbyid.php';
		}elseif ($connectoption == 9){
			$connecturl .= 'makemodellistlink.php';
		}elseif ($connectoption == 10){
			$connecturl .= 'modelnamebyid.php';
		}elseif ($connectoption == 11){
			$connecturl .= 'makemodelgrouplist-raw.php';
		}else{
			$connecturl .= 'subsiteaccess.php';
		}
		
		/*$ch = curl_init( $connecturl );
		if ($ch == FALSE) {
			echo "ERROR !";
			exit();
		}
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		$res = curl_exec($ch);
		return $res;*/
		
		//echo $connecturl . '/' . $postfields;	
		//exit;
		
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => $postfields,
			),
		);
		
		$context  = stream_context_create($options);
		$result = file_get_contents($connecturl, false, $context);
		return $result;
	}
	
	public function get_ym_email(){
		$postfields = 'website_url='.urlencode($this->website_url);	
		$postfields .= '&tokencode='.urlencode($this->tokencode);	
		$res = $this->get_curl_response($postfields);
		$retval = json_decode($res);	
		$supportemail = $retval[0]->supportemail;
		return $supportemail;
	}
	
	public function check_ym_login_step1($tokencode){
		global $db, $cm;
		$tempkey = '';
		$sqltext = "select count(*) as ttl from tbl_mainsite where tokencode = '". $cm->filtertext($tokencode) ."'";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound > 0){
			$tempkey = md5(rand());
			$sql = "update tbl_mainsite set tempkey = '". $cm->filtertext($tempkey)."' where id = 1";
			$db->mysqlquery($sql);
			$retval = 1;
		}else{
			$retval = 0;
		}	
		
		$returnval[] = array(
			'retval' => $retval,
			'tempkey' => $tempkey
		);			
		return json_encode($returnval);
	}
	
	public function check_ym_login_step2($tempkey){
		global $db, $cm;
		if ($tempkey != ""){
			$sqltext = "select count(*) as ttl from tbl_mainsite where tempkey = '". $cm->filtertext($tempkey) ."'";
			$iffound = $db->total_record_count($sqltext);
			if ($iffound > 0){
				$sql = "select id, uid, fname, type_id from tbl_user where id = 1 and status_id = 2";
				$result = $db->fetch_all_array($sql);
				$found = count($result);
				if ($found > 0){
					$row = $result[0];
					$_SESSION["usernid"] = $row['id'];
					$_SESSION["cr_uid"] = $row['uid'];
					$_SESSION["cr_user_name"] = $row['fname'];
					$_SESSION["cr_type_id"] = $row['type_id'];
					$_SESSION["suc"] = "true";
					//if ($_SESSION["cr_type_id"] == 1){
						$_SESSION["asuc"] = "true";
						$_SESSION["sesid"] = session_id();
					//}
					$redirect_url = $cm->folder_for_seo .'dashboard/';
				}else{
					$redirect_url = $cm->folder_for_seo .'login/';
				}
			}else{
				$redirect_url = $cm->folder_for_seo .'login/';
			}
		}else{
			$redirect_url = $cm->folder_for_seo .'login/';	
		}
		$sql = "update tbl_mainsite set tempkey = '' where id = 1";
		$db->mysqlquery($sql);
		header('Location: '. $redirect_url);
	}
	
	//email settings
	public function get_em_options($tokencode){
		global $db, $cm;
		$tempkey = '';
		$sqltext = "select count(*) as ttl from tbl_mainsite where tokencode = '". $cm->filtertext($tokencode) ."'";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound > 0){
			$sql = "select * from tbl_email_settings order by rank";
			$result = $db->fetch_all_array($sql);
			$retval = 1;
		}else{
			$retval = 0;
		}	
		
		$returnval[] = array(
			'retval' => $retval,
			'gerrs' => $result
		);			
		return json_encode($returnval);
	}
	
	public function update_em_options($tokencode, $setvalue, $setid){
		global $db, $cm;
		$tempkey = '';
		$sqltext = "select count(*) as ttl from tbl_mainsite where tokencode = '". $cm->filtertext($tokencode) ."'";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound > 0){
			$sql = "update tbl_email_settings set setvalue = '". $cm->filtertext($setvalue) ."' where id = '". $setid ."'";
			$db->mysqlquery($sql);
			$retval = 1;
		}else{
			$retval = 0;
		}
	}
	
	public function test_em_options($tokencode, $toemail, $ccemail, $bccemail){
		global $db, $cm;
		$tempkey = '';
		$sqltext = "select count(*) as ttl from tbl_mainsite where tokencode = '". $cm->filtertext($tokencode) ."'";
		$iffound = $db->total_record_count($sqltext);
		if ($iffound > 0){
			global $sdeml;
			$sdeml->test_email_settings($toemail, $ccemail, $bccemail);
			$retval = 1;
		}else{
			$retval = 0;
		}
	}
	
	//manufacturer - model list
	public function get_manufacturer_model_list($manufacturer_id, $model_group_id = 0, $p = 1){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&model_group_id='.urlencode($model_group_id);
		$postfields .= '&p='.urlencode($p);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 1);		
		return $res;
	}
	
	//manufacturer - model details
	public function get_manufacturer_model_details($y, $mf, $md){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&y='.urlencode($y);
		$postfields .= '&mf='.urlencode($mf);
		$postfields .= '&md='.urlencode($md);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 2);		
		return $res;
	}
	
	//assign manufacturer list
	public function get_assign_manufacturer_list(){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&p='.urlencode($p);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 3);		
		return $res;
	}
	
	//manufacturer - model list - featured
	public function get_manufacturer_model_list_featured($manufacturer_id){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 4);		
		return $res;
	}
	
	//manufacturer - model list - all
	public function get_manufacturer_model_list_all($manufacturer_id){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$postfields .= '&collectoption=1';
		$res = $this->get_curl_response($postfields, 4);		
		return $res;
	}
	
	//manufacturer - model list - based on manufacturer and year
	public function get_manufacturer_model_list_import($manufacturer_id, $year){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&y='.urlencode($year);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 5);		
		return $res;
	}
	
	//manufacturer - model media - based on manufacturer and year
	public function get_manufacturer_model_media_list_import($modelid){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&modelid='.urlencode($modelid);
		$res = $this->get_curl_response($postfields, 6);		
		return $res;
	}
	
	//assign manufacturer list as combo
	public function get_assign_manufacturer_list_raw(){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&p='.urlencode($p);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 7);		
		return $res;
	}
	
	//manufacturer - model details - by model id
	public function get_manufacturer_model_details_by_id($manufacturer_id, $model_id){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&model_id='.urlencode($model_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 8);		
		return $res;
	}
	
	//Model link only - based on manufacturer and/or group
	public function get_manufacturer_model_list_link($manufacturer_id, $model_group_id = 0){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&model_group_id='.urlencode($model_group_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 9);		
		return $res;
	}
	
	//manufacturer - model details - by model id
	public function get_manufacturer_model_name_by_id($model_id){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&model_id='.urlencode($model_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 10);		
		return $res;
	}
	
	//Model Group list by manufacturer id
	public function get_manufacturer_model_group_list_raw($manufacturer_id){
		global $cm;				
		$postfields = 'website_url='.urlencode($this->website_url);
		$postfields .= '&tokencode='.urlencode($this->tokencode);
		$postfields .= '&manufacturer_id='.urlencode($manufacturer_id);
		$postfields .= '&sf='.urlencode($cm->folder_for_seo);
		$res = $this->get_curl_response($postfields, 11);		
		return $res;
	}
}
?>