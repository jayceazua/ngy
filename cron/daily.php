<?php
set_time_limit(0);
include("../includes/common.php");
$sql = "select user_id, emails from tbl_user_lead_settings where timeperiods = 2";
$result = $db->fetch_all_array($sql);
foreach($result as $row){
	$user_id = $row["user_id"];
	$emails = $row["emails"];
	
	ob_start();
	$leadclass->form_lead_list(0, 1, $user_id);
	$data = ob_get_contents();
	ob_clean();
	
	$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email, company_id', $user_id);
	$b_email = $user_det[0]['email'];
	$fullname = $user_det[0]['fullname'];
	$company_id = $user_det[0]['company_id'];
	
	//$lead_bcc = $cm->get_common_field_name("tbl_company", "lead_bcc", $company_id);
	
	$send_ml_id = 7;
	
	$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
	$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
	$companyname = $cm->sitename;
	
	$msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $msg);
	$msg = str_replace("#companyname#", $companyname, $msg);
	$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
	
	$mail_fm = $cm->admin_email();
	$mail_to = $cm->filtertextdisplay($emails);
	$mail_cc = "";
	$mail_bcc = "";
	$mail_reply = '';
	
	//excel attachment	
	$filename = "leadlist.xls";
	$attachfile = array();
	$attachfile[0]["attype"] = 2;
	$attachfile[0]["contenttype"] = "application/vnd.ms-excel";
	$attachfile[0]["name"] = $filename;
	$attachfile[0]["filedata"] = $data;
	//end
	
	$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u, $attachfile);
}

?>