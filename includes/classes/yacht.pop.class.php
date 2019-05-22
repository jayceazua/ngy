<?php
class Yachtclass_Pop extends Yachtclass{
	
	//submit common feedback
	public function submit_common_feedback_form(){
		if(($_POST['fcapi'] == "commonfeedbacksubmit")){
			global $db, $cm, $frontend, $ymclass, $sdeml;
			$loggedin_member_id = $this->loggedin_member_id();
			$this->check_user_exist($loggedin_member_id, 0, 1);
			
			$fullname = $_POST["fullname"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$company_name = $_POST["company_name"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			
			//create the session
			$datastring = $cm->session_field_common_feedback();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "common-feedback/";
			$cm->field_validation($fullname, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			$cm->delete_session_for_form($datastring);
			$message = nl2br($message);			
			$companyname = $cm->sitename;
			
			//send email to admin
			$messagedetails = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fullname, 1) .'</td>
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
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Company Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($company_name, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
			  </tr>
			</table>';
			
			$mail_subject = $companyname . ' - Feedback';
			$mail_fm = $cm->admin_email();
			$mail_to = $ymclass->get_ym_email();
			$mail_cc = "";
			$mail_reply = $cm->filtertextdisplay($email);
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $messagedetails, $cm->site_url, $news_footer_u);
			
			//send email to poster
			$send_ml_id = 8;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($fullname), $msg);
			$msg = str_replace("#messagedetails#", $messagedetails, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $ymclass->get_ym_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = "";
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$_SESSION["thnk"] = $msg;
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
	
	//submit contact broker submit
	public function submit_contact_broker_form(){
		if(($_POST['fcapi'] == "contactbrokersubmit")){
			global $db, $cm, $frontend, $leadclass, $sdeml;
			
			$id = round($_POST["id"], 0);
			$yid = round($_POST["yid"], 0);
			$fullname = $_POST["fullname"];
			$email = $_POST["email"];
			$phone = $_POST["phone"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			
			$result = $this->check_user_exist($id, 0, 1);
			$row = $result[0];
			$b_fname = $row["fname"];
			$b_lname = $row["lname"];
			$b_email = $row["email"];
			$b_fullname = $b_fname . ' ' . $b_lname;
			
			//create the session
			$datastring = $cm->session_field_contact_broker();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "contact-broker/?id=" . $id . "&yid=" . $yid;
			$cm->field_validation($fullname, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
					
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('contactbroker' . $id . '-' . $yid, 1);
			$cm->delete_session_for_form($datastring);
			
			//add to lead
			$form_type = 2;
			$yw_text = '';
			if ($yid > 0){
				$form_type = 3;
				//$boat_url = $cm->site_url . $cm->get_page_url($yid, "yacht");
				$b_ar = array(
					"boatid" => $yid, 
					"makeid" => 0, 
					"ownboat" => 0, 
					"feed_id" => "", 
					"getdet" => 1
				);
				$boat_url = $cm->site_url . $this->get_boat_details_url($b_ar);
				$subject = '<a href="'. $boat_url .'" target="_blank">'. $subject .'</a>';
				$yw_id = $cm->get_common_field_name("tbl_yacht", "yw_id", $yid);
				if ($yw_id > 0){
					$yw_text = '
					<tr>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">YW Boat ID:</td>
					   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $yw_id .'</td>
					</tr>
					';
				}
			}
			
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"phone" => $phone,
				"message" => $message,
				"broker_id" => $id,
				"yacht_id" => $yid
			);
			$leadclass->add_lead_message($param);
			
			//create email
			$message = nl2br($message);
			
			//$defaultheading = ' font:normal 15px/20px Arial, Helvetica, sans-serif; color:#000; text-align:left; text-decoration: none; text-transform:uppercase;';
			//$defaultfontcss = ' font:normal 12px/18px Arial, Helvetica, sans-serif; color:#000; text-align:left; text-decoration: none;';
			
			$messagedetails = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fullname, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>

			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Phone:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($phone, 1) .'</td>
			  </tr>

			  '. $yw_text .'
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
			  </tr>
			</table>';
			
			if ($yid > 0){
				$this->add_yacht_contact_message($yid, $messagedetails);
			}
			
			$send_ml_id = 2;
			$ad_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes, cc_email', $send_ml_id);
			$ad_email_ar = (object)$ad_email_ar[0];
			$msg = $ad_email_ar->pdes;
			$mail_subject = $ad_email_ar->email_subject;
			$ad_cc_email = $ad_email_ar->cc_email;			
			
			$companyname = $cm->sitename;
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($b_fullname), $msg);
			$msg = str_replace("#messagedetails#", $messagedetails, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#postername#", $fullname, $mail_subject);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($b_email);
			$mail_cc = $ad_cc_email;
			$mail_reply = $cm->filtertextdisplay($email);
			$fromnamesender = $cm->filtertextdisplay($fullname);
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $fromnamesender);
		
			$pgid = 13;
			/*$redpageurl = $cm->get_page_url(0, "popthankyou") . "?c=" . $pgid;
			$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
			$_SESSION["thnk"] = $pagecontent;
			header('Location: ' . $redpageurl);
			exit;*/
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}
	
	//submit contact yc model submit
	public function submit_contact_model_form(){
		if(($_POST['fcapi'] == "contactmodelsubmit")){
			global $db, $cm, $frontend, $leadclass, $sdeml;
			$fullname = $_POST["fullname"];
			$email = $_POST["email"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			$yid = round($_POST["yid"], 0);
			
			/*$result = $this->check_user_exist($id, 0, 1);
			$row = $result[0];
			$b_fname = $row["fname"];
			$b_lname = $row["lname"];
			$b_email = $row["email"];*/
			$b_fullname = 'Member';
			
			//create the session
			$datastring = $cm->session_field_contact_broker();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "contact-model/";
			$cm->field_validation($fullname, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
						
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('contactmodelsubmit', 1);
			$cm->delete_session_for_form($datastring);
			
			//add to lead
			$form_type = 7;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"message" => $message,
				"broker_id" => 1,
				"yacht_id" => $yid
			);
			$leadclass->add_lead_message($param);
			//end
			
			
			$message = nl2br($message);
			
			$messagedetails = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fullname, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
			  </tr>
			</table>';
			
			$send_ml_id = 2;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$companyname = $cm->sitename;
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($b_fullname), $msg);
			$msg = str_replace("#messagedetails#", $messagedetails, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_cc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$pgid = 13;
			/*$redpageurl = $cm->get_page_url(0, "popthankyou") . "?c=" . $pgid;
			$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
			$_SESSION["thnk"] = $pagecontent;
			header('Location: ' . $redpageurl);*/
			header('Location: ' . $cm->get_page_url($pgid, 'page'));
			exit;
		}
	}
	
	//submit contact local model submit
	public function submit_contact_local_model_form(){
		if(($_POST['fcapi'] == "contactmodellocalsubmit")){
			global $db, $cm, $frontend, $leadclass, $modelclass, $sdeml;
			$fullname = $_POST["fullname"];
			$email = $_POST["email"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			$yid = round($_POST["yid"], 0);
			
			/*$result = $this->check_user_exist($id, 0, 1);
			$row = $result[0];
			$b_fname = $row["fname"];
			$b_lname = $row["lname"];
			$b_email = $row["email"];*/
			$b_fullname = 'Member';
			
			//create the session
			$datastring = $cm->session_field_contact_broker();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "contact-model-local/?m=" . $yid;
			$cm->field_validation($fullname, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
						
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			//$cm->form_post_check_valid_main('contactmodelsubmit', 1);
			$cm->delete_session_for_form($datastring);
			
			//add to lead
			$form_type = 26;
			$param = array(
				"form_type" => $form_type,
				"name" => $fullname,
				"email" => $email,
				"message" => $message,
				"broker_id" => 1,
				"yacht_id" => $yid
			);
			$leadclass->add_lead_message($param);
			//end
			
			$model_name = $modelclass->get_model_name($yid);
			$message = nl2br($message);
			
			$messagedetails = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Chosen Model:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width=""><strong>'. $model_name .'</strong></td>
			  </tr>
			  
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fullname, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
			  </tr>
			</table>';
			
			$send_ml_id = 2;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$companyname = $cm->sitename;
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($b_fullname), $msg);
			$msg = str_replace("#messagedetails#", $messagedetails, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->admin_email_to();
			$mail_cc = '';
			$mail_reply = $cm->filtertextdisplay($email);
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$pgid = 13;
			$redpageurl = $cm->get_page_url(0, "popthankyou") . "?c=" . $pgid;
			$pagecontent = $cm->get_common_field_name('tbl_page', 'file_data', $pgid);
			$_SESSION["thnk"] = $pagecontent;
			header('Location: ' . $redpageurl);
			exit;
		}
	}
	
	//submit contact resource submit
	public function submit_contact_resource_form(){
		if(($_POST['fcapi'] == "contactresourcesubmit")){
			global $db, $cm, $frontend, $sdeml;
			$id = round($_POST["id"], 0);
			$fullname = $_POST["fullname"];
			$email = $_POST["email"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$email2 = $_POST["email2"];
			
			$result = $this->check_resource_with_return($id, 1);
			$row = $result[0];
			$b_email = $row["email"];
			$b_company_name = $row["company_name"];
			
			//create the session
			$datastring = $cm->session_field_contact_broker();
			$cm->create_session_for_form($datastring, $_POST);
			//end
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "contact-resource/?id=" . $id;
			$cm->field_validation($fullname, '', 'Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($email, '', 'Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->delete_session_for_form($datastring);
			$message = nl2br($message);
			
			$messagedetails = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="200">Name:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($fullname, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Email Address:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($email, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Subject:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($subject, 1) .'</td>
			  </tr>
			
			  <tr>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">Message:</td>
				   <td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $cm->filtertextdisplay($message, 1) .'</td>
			  </tr>
			</table>';
			
			$send_ml_id = 4;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$companyname = $cm->sitename;
			
			$msg = str_replace("#name#", $cm->filtertextdisplay($b_company_name), $msg);
			$msg = str_replace("#messagedetails#", $messagedetails, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($b_email);
			$mail_cc = $cm->admin_email_to();
			$mail_reply = $cm->filtertextdisplay($email);
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$_SESSION["thnk"] = $frontend->display_message(16);			
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
	
	//submit email search
	public function submit_email_search_form(){
		if(($_POST['fcapi'] == "emailsearchsubmit")){
			global $db, $cm, $frontend, $sdeml;
			$loggedin_member_id = $this->loggedin_member_id();
			$id = $_POST["id"];
			$email = $_POST["email"];
			$email2 = $_POST["email2"];
			
			$result = $this->check_user_exist($loggedin_member_id, 0, 1);
			$this->check_save_search($id);
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "emailsearch/?id=" . $id;
			$cm->field_validation($email, '', 'Email', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//create email
			$row = $result[0];
			$b_fname = $row["fname"];
			$b_lname = $row["lname"];
			$b_fullname = $b_fname . ' ' . $b_lname;
			
			$openurl = $cm->site_url . '/' . $cm->get_page_url($id, 'savesearch');
			$send_ml_id = 3;
			$msg = $db->total_record_count("select pdes as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$mail_subject = $db->total_record_count("select email_subject as ttl from tbl_system_email where id = '". $send_ml_id ."'");
			$companyname = $cm->sitename;
			
			$msg = str_replace("#sendername#", $cm->filtertextdisplay($b_fullname), $msg);
			$msg = str_replace("#searchurl#", $openurl, $msg);
			$msg = str_replace("#companyname#", $companyname, $msg);
			$mail_subject = str_replace("#companyname#", $companyname, $mail_subject);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($email);
			$mail_cc = $cm->admin_email_to();
			$mail_reply = "";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $msg, $cm->site_url, $news_footer_u);
			
			$_SESSION["thnk"] = $frontend->display_message(22, $mail_to);
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
	
	//submit save search
	public function submit_save_search_form(){
		if(($_POST['fcapi'] == "savesearchsubmit")){
			global $db, $cm, $frontend;
			$loggedin_member_id = $this->loggedin_member_id();
			$name = $_POST["name"];
			$email2 = $_POST["email2"];
			
			$this->check_user_exist($loggedin_member_id, 0, 1);
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "popsavesearch/";
			$cm->field_validation($name, '', 'Name', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//insert into database
			$addsave = $this->yacht_save_search_insert($name);
			if ($addsave == "y"){
				$_SESSION["thnk"] = $frontend->display_message(18);
				header('Location: ' . $cm->get_page_url(0, "popthankyou"));
				exit;
			}else{
				$_SESSION["ob"] = $frontend->display_message(19);
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
		}
	}
	
	//submit email client
	public function submit_email_client_form(){
		if(($_POST['fcapi'] == "emailclientsubmit")){
			global $db, $cm, $frontend, $sdeml;
			$loggedin_member_id = $this->loggedin_member_id();
			$id = $_POST["id"];
			$this->loggedin_broker_icon_permission(1);
			
			$lno = round($_POST["lno"], 0);
			$stemail = $_POST["stemail"];
			$message = $_POST["message"];
			$sendmecopy = round($_POST["sendmecopy"], 0);
			$email2 = $_POST["email2"];
			
			if ($sendmecopy != 1){ $sendmecopy = 2; }
			
			$result = $this->check_yacht_with_return($lno, 1);
			$row = $result[0];
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			//create the session
			$datastring = $cm->session_field_refer_friend();
			$cm->create_session_for_form($datastring, $_POST);
			//end			
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "pop-send-email-client/?lno=" . $lno;
			$cm->field_validation($stemail, '', 'Client Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			$cm->delete_session_for_form($datastring);
			$message = nl2br($message);
			$subject = $this->yacht_name($id);
			
			$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
			$femail = $user_det[0]['email'];
			$fullname = $user_det[0]['fullname'];
				
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($stemail);
			$mail_cc = "";
			$mail_reply = $cm->filtertextdisplay($femail);
			if ($sendmecopy == 1){ $mail_cc = $mail_reply; }
			
			//pdf attachment
			//$html = $this->create_yacht_pdf_html($result, 1);
			$pdf_content_ar = $this->create_yacht_pdf_html($result, 1);
			$pdf_content_ar = json_decode($pdf_content_ar);			
			$headertext = $pdf_content_ar->headertext;
			$html = $pdf_content_ar->returntxt;
			
			$pdfcontent = $cm->generate_pdf('', $html, $headertext, '', 'S');
			$filename = "Yacht-" . $lno . ".pdf";
			$attachfile = array();
			$attachfile[0]["attype"] = 1;
			$attachfile[0]["contenttype"] = "application/pdf";
			$attachfile[0]["name"] = $filename;
			$attachfile[0]["pdfdata"] = $pdfcontent;
			//end
			
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $subject, $message, $cm->site_url, $news_footer_u, $attachfile);			
			
			$_SESSION["thnk"] = $frontend->display_message(16);
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
	
	//submit email friend
	public function submit_email_friend_form(){
		if(($_POST['fcapi'] == "sendemailfriendsubmit")){
			global $db, $cm, $frontend, $sdeml;
			
			$loggedin_member_id = $this->loggedin_member_id();
			$lno = round($_POST["lno"], 0);
			$femail = $_POST["femail"];
			$fname = $_POST["fname"];
			$stemail = $_POST["stemail"];
			$message = $_POST["message"];
			$sendmecopy = round($_POST["sendmecopy"], 0);
			$email2 = $_POST["email2"];
			if ($sendmecopy != 1){ $sendmecopy = 2; }
			
			$result = $this->check_yacht_with_return($lno, 1);
			$row = $result[0];
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			//create the session
			$datastring = $cm->session_field_refer_friend();
			$cm->create_session_for_form($datastring, $_POST);
			//end			
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "pop-send-email-friend/?lno=" . $lno;
			$cm->field_validation($femail, '', 'From Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($fname, '', 'From Name', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($stemail, '', 'Send To Email Address', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			//captcha
			global $captchaclass;
			$captchaclass->validate_captcha($red_pg);
			//end
			
			$cm->form_post_check_valid_main('emailfriend', 1);
			$cm->delete_session_for_form($datastring);
			$message = nl2br($message);
			$subject = $this->yacht_name($id);
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($stemail);
			$mail_cc = "";
			$mail_reply = $cm->filtertextdisplay($femail);
			if ($sendmecopy == 1){ $mail_cc = $mail_reply; }
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $subject, $message, $cm->site_url, $news_footer_u);			
			
			$_SESSION["thnk"] = $frontend->display_message(16);
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
	
	//submit send email my broker form
	public function submit_send_email_my_broker_form(){
		if(($_POST['fcapi'] == "sendemailmybrokersubmit")){
			global $db, $cm, $frontend, $sdeml;
			$this->loggedin_consumer_icon_permission(1);
			$lno = round($_POST["lno"], 0);
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			$sendmecopy = round($_POST["sendmecopy"], 0);
			$email2 = $_POST["email2"];
			
			if ($sendmecopy != 1){ $sendmecopy = 2; }

			$result = $this->check_yacht_with_return($lno, 1);
			$row = $result[0];
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			//create the session
			$datastring = $cm->session_field_contact_my_broker();
			$cm->create_session_for_form($datastring, $_POST);
			//end	
			
			//field data checking
			$red_pg = $cm->folder_for_seo . "pop-send-email-my-broker/?lno=" . $lno;
			$cm->field_validation($subject, '', 'Subject', $red_pg, '', '', 1, 'fr_');
			$cm->field_validation($message, '', 'Message', $red_pg, '', '', 1, 'fr_');
			if ($email2 != ""){
				header('Location: ' . $cm->get_page_url(0, "popsorry"));
				exit;
			}
			//end
			
			$cm->delete_session_for_form($datastring);
			$message = nl2br($message);
			
			$loggedin_member_id = $this->loggedin_member_id();
			$user_det = $cm->get_table_fields('tbl_user', 'concat(fname, " ", lname) as fullname, email', $loggedin_member_id);
			$femail = $user_det[0]['email'];
			$fullname = $user_det[0]['fullname'];
			
			$my_broker_id = $cm->get_common_field_name('tbl_user_to_broker', 'broker_id', $loggedin_member_id, 'user_id');
			$user_det = $cm->get_table_fields('tbl_user', 'email', $my_broker_id);
			$stemail = $user_det[0]['email'];
			
			$mail_fm = $cm->admin_email();
			$mail_to = $cm->filtertextdisplay($stemail);
			$mail_cc = "";
			$mail_reply = $cm->filtertextdisplay($femail);
			if ($sendmecopy == 1){ $mail_cc = $mail_reply; }

			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $subject, $message, $cm->site_url, $news_footer_u, $attachfile);		
			
			$_SESSION["thnk"] = $frontend->display_message(16);
			header('Location: ' . $cm->get_page_url(0, "popthankyou"));
			exit;
		}
	}
}
?>