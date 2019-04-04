<?php
class Emailclass {
	
	public function __construct() {
        include('class.phpmailer.php');
    }

	public function send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $mail_subject, $mail_txt, $site_name, $fromnamesender = '', $attachfile = array(), $attachfilefull = ""){
        global $cm;
		$e_mainbg = "#ffffff";
	    $e_bordercolor = "#2bbed3";
	    //$e_textcolor = "#000000";
		
		$contact_address = $cm->get_systemvar('STADD');
		$mail_txt = '
		<!DOCTYPE HTML>
	    <html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="viewport" content="width=device-width; initial-scale=1.0;" />
			<style type="text/css">
                body{margin: 0; font-family: Arial, Verdana, Tahoma; font-size: 12px;}
				table{font:normal 12px/18px Arial, Helvetica, sans-serif; color:#000; text-align:left; text-decoration: none;}
				@media only screen and (max-width: 780px) {
					body { margin-left: 10px; margin-right: 10px; }
					body,table,td,p,a,li,blockquote {
					  -webkit-text-size-adjust:none !important;
					}
					table {width: 100% !important;}
			
					img {
					  height: auto !important;
					  max-width: 100% !important;
					  width: 100% !important;
					}
				}
            </style>
		</head>
		<body>
		 <table width="782" border="0" cellspacing="0" cellpadding="0" align="center">
	      <tr>
		    <td width="100%" align="center" style="border: 1px solid '. $e_bordercolor .'; padding-top: 0px; padding-bottom: 5px; background-color: '. $e_mainbg .';">
		        
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				  <tr>
				   <td width="100%" align="center"><a href="'. $cm->site_url .'/"><img src="'. $cm->site_url .'/images/email-head.jpg" border="0" alt="'.$cm->sitename.'" style="display: block; border: 0px;" /></a></td>
				  </tr>
				</table>
				
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				  <tr>
					<td width="100%" align="center" style="padding: 10px; background-color: #FFFFFF;">
					<div style="padding: 10px 0px 20px 0px; text-align:left; color: #000000; font-family: Arial, Verdana, Tahoma; font-size: 12px; text-decoration: none; line-height: 140%;">
					   '. $mail_txt .'
					</div>';
			
					 $mail_txt .= '		 
					 <div style="border-top: 1px solid '. $e_bordercolor .'; padding: 10px 0px 0px 0px; text-align:center; color: #000000; font-family: Arial, Verdana, Tahoma; font-size: 12px; text-decoration: none; line-height: 140%;">
					   '. $contact_address .'
					 </div>
					</td>
				  </tr>
				</table>
			</td>
		  </tr>
		</table>
		</body>
		</html>
	';
	 /*print $mail_to;
	 print $mail_cc;
	 print $mail_txt;
	 print $mail_subject;
	 exit;*/

	//collect email settings
	 $emailsend_option = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 1);
	 if ($emailsend_option == 2){
		 //smtp email
		 $emailsend_host = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 2);
		 $emailsend_port = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 3);
		 $emailsend_login = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 4);
		 $emailsend_password = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 5);
		 $emailsend_fromemail = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 6);
		 $emailsend_tslssl = $cm->get_common_field_name('tbl_email_settings', 'setvalue', 7);
		 if ($mail_fm == ''){ $mail_fm = $emailsend_fromemail; }
		 if ($mail_reply == ''){ $mail_reply = $mail_fm; }
		 
		 $mail = new PHPMailer();
		 $mail->IsSMTP();
		 $mail->SMTPDebug = 1;
		 $mail->Host = $emailsend_host;
		 $mail->Port = $emailsend_port;
		 $mail->SMTPAuth = true; // turn on SMTP authentication
		 $mail->Username = $emailsend_login;
		 $mail->Password = $emailsend_password;
		
		 //$mail->SetFrom($mail_fm, $cm->sitename);
		 //$mail->SetFrom($emailsend_fromemail, $cm->sitename);
		 
		 if ($fromnamesender == ""){ $fromnamesender = $cm->sitename; }
		 $mail->SetFrom($emailsend_fromemail, $fromnamesender);
		 
		 $mail_to_ar = explode(", ", $mail_to);
		 foreach($mail_to_ar as $mail_to_row){
			 if ($mail_to_row != ""){
				 $mail->AddAddress($mail_to_row);
			 }
		 }		 
		 if ($mail_cc != ""){
			 $mail_cc_ar = explode(", ", $mail_cc);
			 foreach($mail_cc_ar as $mail_cc_row){
				 if ($mail_cc_row != ""){
             		$mail->AddCC($mail_cc_row);
				 }
			 }
         }
         if ($mail_bcc != ""){
			 $mail_bcc_ar = explode(", ", $mail_bcc);
			 foreach($mail_bcc_ar as $mail_bcc_row){
				 if ($mail_bcc_row != ""){
             		$mail->AddBCC($mail_bcc_row);
				 }
			 }
         }
		 //$mail->clearReplyTos();
		 $mail->AddReplyTo($mail_reply);
         $mail->Subject = $mail_subject;
         $mail->AltBody = "Please use an HTML compatible email viewer.";
         $mail->MsgHTML($mail_txt);		
	
		 $attachfile_cnt = count($attachfile);		
		 for($atm = 0; $atm < $attachfile_cnt; $atm++){
			$attachfiletype = round($attachfile[$atm]["attype"]);
			$filename = $attachfile[$atm]["name"];
			
			if ($attachfiletype == 1){
				$data = $attachfile[$atm]["pdfdata"];
				$fileatt_type = $attachfile[$atm]["contenttype"];
				$mail->AddStringAttachment($data, $filename);
			}elseif ($attachfiletype == 2){
				$data = $attachfile[$atm]["filedata"];
				$fileatt_type = $attachfile[$atm]["contenttype"];
				$mail->AddStringAttachment($data, $filename);
			}else{
				 $attachfilefull = $attachfile[$atm]["path"];
				 $mail->AddAttachment($attachfilefull, $filename);
			}
		 }
		
         $mail->Send();
	 }else{
		 //default email
		 $headersm = "From: $mail_fm";
		if ($mail_cc != ""){ $headersm .= "\r\nCc: ". $mail_cc; }
		if ($mail_bcc != ""){ $headersm .= "\r\nBcc: ". $mail_bcc; }

		$semi_rand = md5(uniqid(time())); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		
		$headersm .= "\nMIME-Version: 1.0\n" . 
		 "Content-Type: multipart/mixed;\n" . 
		 " boundary=\"{$mime_boundary}\"";
		 
		$mail_body = "This is a multi-part message in MIME format.\n\n" . 
		"--{$mime_boundary}\n" . 
		"Content-Type: text/html; charset=\"iso-8859-1\"\n" . 
		"Content-Transfer-Encoding: 7bit\n\n" . 
		$mail_txt . "\n\n";
		
		$attachfile_cnt = count($attachfile);		
		for($atm = 0; $atm < $attachfile_cnt; $atm++){	
			$attachfiletype = round($attachfile[$atm]["attype"]);
			$filename = $attachfile[$atm]["name"];
			
			// Read the file to be attached ('rb' = read binary) 
			if ($attachfiletype == 1){
				$data = $attachfile[$atm]["pdfdata"];
				$fileatt_type = $attachfile[$atm]["contenttype"];
			}elseif ($attachfiletype == 2){
				$data = $attachfile[$atm]["filedata"];
				$fileatt_type = $attachfile[$atm]["contenttype"];
			}else{
				 $attachfilefull = $attachfile[$atm]["path"];
				 $file = fopen($attachfilefull,'rb'); 
				 $data = fread($file,filesize($attachfilefull)); 
				 fclose($file);
				 $fileatt_type = filetype($attachfilefull);
			}			 
			 $fileatt_name = $filename;
			 
			// Base64 encode the file data 
			 $data = chunk_split(base64_encode($data)); 
			
			 // Add file attachment to the message 
			 $mail_body .= "--{$mime_boundary}\n" . 
						 "Content-Type: {$fileatt_type};\n" . 
						 " name=\"{$fileatt_name}\"\n" . 
						 "Content-Disposition: attachment;\n" . 
						 " filename=\"{$fileatt_name}\"\n" . 
						 "Content-Transfer-Encoding: base64\n\n" . 
						 $data . "\n\n" ;
			
		}
		
		$mail_body .= "--{$mime_boundary}--\n"; 	
		@mail($mail_to, $mail_subject, $mail_body, $headersm);
	 }			
   }
   
   public function test_email_settings($toemail, $ccemail, $bccemail){
	   global $cm;
	   $mail_subject = 'Test from ' . $cm->sitename;
	   $message = 'This is a test email from ' . $cm->sitename;
	   $mail_fm = '';
	   $mail_reply = '';   
	   $this->send_email($mail_fm, $toemail, $ccemail, $bccemail, $mail_reply, $mail_subject, $message, $cm->site_url, '');
   }
}
?>