<?php
class Captchaclass {
	
	//call captcha
	public function call_captcha(){
		global $cm;
		//$publickey = "6LcwGwgUAAAAAJN4_TdrEza_l3sM3iSk0QXfC0L_";
		//$captext = '<div class="g-recaptcha" data-sitekey="'. $publickey .'"></div>';
		$captext = '<div class="g-recaptcha defaultcaptcha"></div>';
		return $captext;
	}
	
	//call captcha invisible
	public function call_captcha_invisible($param = array()){
		global $cm;
		
		//param
		$buttonid = $param["buttonid"];
		//end
		
		//$captext = '<button id="'. $buttonid .'" type="submit" class="button g-recaptcha invisible-recaptcha" value="Submit">Submit</button>';
		$captext = '
		<div id="'. $buttonid .'" class="g-recaptcha invisible-recaptcha" data-sitekey="6LceTHEUAAAAAM8okTHbTXQOjvcdv0MuqbenDmNZ" data-size="invisible"></div>
		';
		return $captext;
	}
	
	//validate captcha	
	public function validate_captcha($red_pg, $wh_ajax = 0){
		global $cm;
		$privatekey = "6LcwGwgUAAAAAKefQtzyDNCs7TPV8f-xI-SmG0Nl";
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
		
		if($responseData->success){
			//valid
			return 1;
		}else{
			//error
			//$errormsg = $resp->error;
			if ($wh_ajax == 1){
				return 0;
			}else{
				$_SESSION["fr_postmessage"] = "Captcha Error!";
				header('location:' .$red_pg);
				exit;
			}
		}
	}
	
	//validate captcha invisible
	public function validate_captcha_invisible($red_pg, $wh_ajax = 0){
		global $cm;
		$privatekey = "6LceTHEUAAAAAOMlC0XUO53_1dQYhNPcrzrJKT1s";
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$privatekey.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
		
		if($responseData->success){
			//valid
			return 1;
		}else{
			//error
			//$errormsg = $resp->error;
			if ($wh_ajax == 1){
				return 0;
			}else{
				$_SESSION["fr_postmessage"] = "Captcha Error!";
				header('location:' .$red_pg);
				exit;
			}
		}
	}
}
?>