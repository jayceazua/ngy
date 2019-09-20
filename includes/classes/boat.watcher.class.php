<?php
class Boatwatcherclass {
	
	//manage 0 value
	public function manage_zero_val($val){
		if ($val <= 0){
			$val = "";
		}
		return $val;
	}
	
	//dayes frequency
	public function days_frequency(){
		$ar_value = array();
		$ar_value[] = array("name" => "", "days" => 0);
		$ar_value[] = array("name" => "Daily", "days" => 1);
		$ar_value[] = array("name" => "Weekly", "days" => 7);
		$ar_value[] = array("name" => "Monthly", "days" => 30);
		
		$ar_value = json_encode($ar_value);
		return $ar_value;
	}
	//end
	
	//dayes frequency combo
	public function get_days_frequency_combo($schedule_days){
        global $db;
        $returntxt = '';
        $ar_value = json_decode($this->days_frequency());
		foreach($ar_value as $ar_key => $ar_row){
            $cname = $ar_row->name;
			if ($cname != ""){
				$days = $ar_row->days;
				$bck = '';
				if ($schedule_days == $days){
					$bck = ' selected="selected"';	
				}
				$returntxt .= '<option value="'. $days .'"'. $bck .'>'. $cname .'</option>';
			}
        }
        return $returntxt;
    }
	//end
	
	//Get single frequency details
	public function get_days_frequency_single($schedule_days){
		$returntxt = '';
		$ar_value = json_decode($this->days_frequency());
		foreach($ar_value as $ar_key => $ar_row){
            $cname = $ar_row->name;
			if ($cname != ""){
				$days = $ar_row->days;
				if ($schedule_days == $days){
					$returntxt = $cname;
					break;
				}				
			}
        }
		
		return $returntxt;
	}
	
	//boat watcher form
	public function boat_watcher_form($param = array()){
		global $db, $cm, $yachtclass, $yachtchildclass, $captchaclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();		
		$login_register_link_text = '';	

		//param
		$default_param = array("formtemplate" => 1, "counter" => 0, "searchfield" => "{}");
		$param = array_merge($default_param, $param);
		
		$formtemplate =  $param["formtemplate"];
		$counter =  $param["counter"];
		//end
		
		if ($formtemplate == 1){
			//home page form
			
			//--
			$datastring = $cm->session_field_boat_watcher();
			$return_ar = $cm->collect_session_for_form($datastring);
			
			foreach($return_ar AS $key => $val){
				${$key} = $val;
			}
			//--			
			
			if ($loggedin_member_id > 0){
				
				$broker_det = $cm->get_table_fields('tbl_user', 'fname, lname, email', $loggedin_member_id);
				$broker_det = $broker_det[0];
				$broker_name = $broker_det["fname"] . " " . $broker_det["lname"];
				$broker_email = $broker_det["email"];
				
				$name_email_text = '
				<li class="breakeall">
					Name: <strong>'. $broker_name .'</strong><br>
					Email: <strong>'. $broker_email .'</strong>
					<input type="hidden" id="reg_name'. $counter .'" name="reg_name'. $counter .'" value="'. $broker_name .'" />
					<input type="hidden" id="email'. $counter .'" name="email'. $counter .'" value="'. $broker_email .'" />
				</li>
				';
			}else{
				$name_email_text = '
				<li class="left">
					<p><label for="reg_name'. $counter .'">Name</label></p>
					<input type="text" class="input" id="reg_name'. $counter .'" name="reg_name'. $counter .'" value="'. $reg_name .'" placeholder="" />
				</li>
				<li class="right">
					<p><label for="email'. $counter .'">Email</label></p>
					<input type="text" class="input" id="email'. $counter .'" name="email'. $counter .'" value="'. $email .'" placeholder="" />
				</li>
				';
				
				$login_register_link_text = '
				<div class="boatwatchelogin clearfixmain">
					<a href="'. $cm->get_page_url(0, "login") .'">Log-in</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'. $cm->get_page_url(0, "register") .'">Register</a>
				</div>
				';
			}
			
			$makename = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
			$start_content = '<div class="boatwatcher-startcontent clearfixmain">'. nl2br($cm->get_systemvar('BWFTC')).'</div>';
			
			$formstart = '
			<form method="post" action="'. $cm->folder_for_seo .'" id="boatwatcher-ff" name="boatwatcher-ff">
			<label class="com_none" for="email2b">email2</label>
			<input type="hidden" value="'. $schedule_days .'" id="schedule_days_old'. $counter .'" name="schedule_days_old'. $counter .'" />
			<input class="finfo" id="email2b" name="email2" type="text" />
			<input type="hidden" id="fcapi" name="fcapi" value="submitboatwatcherform" />
			';
			
			$returntext = '
			<div class="boatwatcherpage clearfixmain">
				<h2 class="singlelinebottom30">Yacht <span>Finder</span></h2>
				'. $start_content .'
				'. $formstart . '
				
				<ul class="form">
					'. $name_email_text .'
					
					<li class="left">
						<p><label for="schedule_days'. $counter .'">Send Alert</label></p>
						<select name="schedule_days'. $counter .'" id="schedule_days'. $counter .'" class="select">
						'. $this->get_days_frequency_combo($schedule_days) .'
						</select>
					</li>
					
					<li>
						<p><label for="keyterm'. $counter .'">Manufacturer</label><label class="com_none" for="mid'. $counter .'">Mid</label></p>
						<input type="hidden" value="'. $makeid .'" id="mid'. $counter .'" name="mid'. $counter .'" />
						<input type="text" id="keyterm'. $counter .'" class="azax_auto input" name="keyterm'. $counter .'" ckpage="5" counter="'. $counter .'"  value="'. $makename .'" placeholder="Manufacturer Name" autocomplete="off">
					</li>
					
					<li>
						<p>Length Range(ft)</p>
						<label class="com_none" for="lnmin'. $counter .'">Min</label>
						<label class="com_none" for="lnmax'. $counter .'">Max</label>
						<div class="left-side"><input id="lnmin'. $counter .'" name="lnmin'. $counter .'" type="text" value="" class="input" placeholder="Min" value="'. $lnmin .'" /></div>
						<div class="right-side"><input id="lnmax'. $counter .'" name="lnmax'. $counter .'" type="text" value="" class="input" placeholder="Max" value="'. $lnmax .'" /></div>
					</li>
					
					<li>
						<p>Price Range($)</p>
						<label class="com_none" for="prmin'. $counter .'">Min</label>
						<label class="com_none" for="prmax'. $counter .'">Max</label>
						<div class="left-side"><input id="prmin'. $counter .'" name="prmin'. $counter .'" type="text" value="'. $prmin .'" class="input" placeholder="Min" /></div>
						<div class="right-side"><input id="prmax'. $counter .'" name="prmax'. $counter .'" type="text" value="'. $prmax .'" class="input" placeholder="Max" /></div>
					</li>
					
					<li>'. $captchaclass->call_captcha() .'</li>
					<li><button type="submit" class="button" value="Submit">Submit</button>	</li>
				</ul>				
				<div class="fomrsubmit-result com_none"></div>
				
				</form>
			</div>
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				$("#boatwatcher-ff").submit(function(event){
					var all_ok = "y";
					var setfocus = "n";
					
					if (!field_validation_border("reg_name0", 1, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "reg_name0");
					}
						   
					if (!field_validation_border("email0", 2, 1)){ 
						all_ok = "n"; 
						setfocus = set_field_focus(setfocus, "email0"); 		   
					}
					
					var mid = getNumber_validate_nan($("#mid0").val(), 0);
					var lnmin = getNumber_validate_nan($("#lnmin0").val(), 0);
					var lnmax = getNumber_validate_nan($("#lnmax0").val(), 0);
					var prmin = getNumber_validate_nan($("#prmin0").val(), 0);
					var prmax = getNumber_validate_nan($("#prmax0").val(), 0);
										
					if ((mid == 0) && (lnmin == 0) && (lnmax == 0) && (prmin == 0) && (prmax == 0)){
						all_ok = "n";
						open_error_area("ERROR! Please add at least one criteria.");
					}else{
						close_error_area();
					}
					
					if (all_ok == "n"){						
						return false;
					}
															
					//Ajax submit
					var form = $(this);
					$.ajax({
						type: form.attr("method"),
						url: form.attr("action"),
						data: form.serialize()
					}).done(function(data){
						data = $.parseJSON(data);
						retmsg = parseInt(data.retmsg);
						if (retmsg == 1){						
							$(".fomrsubmit-result").addClass("success");
							$(".fomrsubmit-result").html("THANK YOU! Record saved successfully.");
							$(".fomrsubmit-result").removeClass("com_none");
							
							$("#mid0").val("");
							$("#reg_name0").val("");
							$("#email0").val("");
							$("#keyterm0").val("");
							$("#prmin0").val("");
							$("#prmax0").val("");
							$("#lnmin0").val("");
							$("#lnmax0").val("");
						}else{
							open_error_area("Captcha ERROR! Please try again.");
						}
						grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					}).fail(function(){
						open_error_area("ERROR! Please try again.");
						grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
					});
					
					if ($(".boatsearchcol").length > 0){
						$(document.body).trigger("sticky_kit:recalc");
					}
					
					event.preventDefault();

				});					
			});
			
			function open_error_area(msg){
				$(".fomrsubmit-result").addClass("error");
				$(".fomrsubmit-result").html(msg);
				$(".fomrsubmit-result").removeClass("com_none");
			}
			function close_error_area(){
				$(".fomrsubmit-result").removeClass("error");
				$(".fomrsubmit-result").html("");
				$(".fomrsubmit-result").addClass("com_none");
			}
			</script>
			';
		}else{
			//Collecting Data
			$boatwatchercode =  $param["boatwatchercode"];
			$name =  $param["name"];
			$email_to =  $param["email_to"];
			$searchfield =  $param["searchfield"];
			$schedule_days =  $param["schedule_days"];
			$schedule_date = $param["schedule_date"];
		
			$searchfield = json_decode($searchfield);
			$makeid = $searchfield->makeid;
			$makename = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
			//end
			
			if ($counter > 0){
				$end_form = '<li><div class="inlineblock"><button rowval="'. $counter .'" boatwatchercode="'. $boatwatchercode .'" type="button" class="button addeditboatwatcher">Update</button></div><div class="inlineblock"><a class="update_cancel" rowval="'. $counter .'" boatwatchercode="'. $boatwatchercode .'" href="javascript:void(0);" title="Cancel"><img alt="Cancel" title="Cancel" src="'. $cm->folder_for_seo .'images/close-icon.png" /></a></div></li>';
			}else{
				$end_form = '<li><button rowval="0" boatwatchercode="" type="button" class="button addeditboatwatcher">Create</button></li>';
			}
			
			$start_form = '
			<li class="left">
				<p>Name:</p>
				<input type="text" id="name'. $counter .'" class="input" name="name'. $counter .'" value="'. $name .'">
			</li>        
			<li class="right">
				<p">Email:</p>
				<input type="text" id="email_to'. $counter .'" class="input" name="email_to'. $counter .'" value="'. $email_to .'">
			</li>
			<li class="left">
				<p>Send Report:</p>
				<select name="schedule_days'. $counter .'" id="schedule_days'. $counter .'" class="select">
					'. $this->get_days_frequency_combo($schedule_days) .'
				</select>
				<input type="hidden" value="'. $schedule_days .'" id="schedule_days_old'. $counter .'" name="schedule_days_old'. $counter .'" />
			</li>
			
			<li><strong>Choosen Boats</strong></li>
			';
			
			$returntext = '
			<input type="hidden" value="'. $usertype .'" id="btype'. $counter .'" name="btype'. $counter .'" />
			<ul class="form">
				'. $start_form .'
			</ul>
			<ul class="form">
				<li class="left">
					<p>Manufacturer</p>
					<input type="hidden" value="'. $makeid .'" id="mid'. $counter .'" name="mid'. $counter .'" />
					<input type="text" id="keyterm'. $counter .'" class="azax_auto input" name="keyterm'. $counter .'" ckpage="5" counter="'. $counter .'"  value="'. $makename .'" autocomplete="off">
				</li>
				<li class="right">
					<p>Year</p>
					<div class="left-side clearfixmain">									
						<select class="select" id="yrmin'. $counter .'" name="yrmin'. $counter .'">
							<option selected>Min</option>
							'. $yachtclass->get_year_combo($searchfield->yrmin, 1) .'
						</select>
					</div>
					<div class="right-side clearfixmain">
						<select class="select" id="yrmax'. $counter .'" name="yrmax'. $counter .'">
							<option  selected="selected">Max</option>
							'. $yachtclass->get_year_combo($searchfield->yrmax, 1) .'
						</select>
					</div>
				</li>
				
				<li class="left">
					<p>Price($)</p>
					<div class="left-side"><input id="prmin'. $counter .'" name="prmin'. $counter .'" type="text" value="'. $this->manage_zero_val($searchfield->prmin) .'" class="input" /></div>
					<div class="right-side"><input id="prmax'. $counter .'" name="prmax'. $counter .'" type="text" value="'. $this->manage_zero_val($searchfield->prmax) .'" class="input" /></div>
				</li>
				<li class="right">
					<p>Length(ft)</p>
					<div class="left-side"><input id="lnmin'. $counter .'" name="lnmin'. $counter .'" type="text" class="input" placeholder="Min" value="'. $this->manage_zero_val($searchfield->lnmin) .'" /></div>
					<div class="right-side"><input id="lnmax'. $counter .'" name="lnmax'. $counter .'" type="text" class="input" placeholder="Max" value="'. $this->manage_zero_val($searchfield->lnmax) .'" /></div>
				</li>
				
				<li class="left">
					<p>Category</p>
					<select class="select" name="categoryid'. $counter .'" id="categoryid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_category_combo($searchfield->categoryid, 0, 1) .'
					</select>
				</li>
				<li class="right">
					<p>Condition</p>
					<select class="select" name="conditionid'. $counter .'" id="conditionid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_condition_combo($searchfield->conditionid, 0, 1).'
					</select>
				</li>
				
				<li class="left">
					<p>Boat Type</p>
					<select class="select" name="typeid'. $counter .'" id="typeid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_type_combo_parent($searchfield->typeid, $searchfield->categoryid, 0, 1) .'
					</select>
				</li>        
				<li class="right">
					<p>Engine Type</p>
					<select class="select" name="enginetypeid'. $counter .'" id="enginetypeid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_engine_type_combo($searchfield->enginetypeid, 0, 1) .'
					</select>
				</li>
				
				<li class="left">
					<p>Drive Type</p>
					<select class="select" name="drivetypeid'. $counter .'" id="drivetypeid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_drive_type_combo($searchfield->drivetypeid, 0, 1) .'
					</select>
				</li>        
				<li class="right">
					<p>Fuel Type</p>
					<select class="select" name="fueltypeid'. $counter .'" id="fueltypeid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_fuel_type_combo($searchfield->fueltypeid, 0, 1) .'
					</select>
				</li>
				
				<li class="left">
					<p>US State</p>
					<select class="select" name="stateid'. $counter .'" id="stateid'. $counter .'">
						<option value="0" selected="selected">All</option>
						'. $yachtclass->get_state_combo($searchfield->stateid, 1, 1) .'
					</select>
				</li>														
				'. $end_form .'										
			</ul>
			';
		}
		
		return $returntext;
	}
	//end
	
	//Submit boat watcher form
	public function submit_boat_watcher_form(){
		if(($_POST['fcapi'] == "submitboatwatcherform")){
			global $db, $cm, $sdeml;
			
			//field		
			$reg_name = $_POST["reg_name0"];
			$email = $_POST["email0"];
			$schedule_days = round($_POST["schedule_days0"], 0);
			$schedule_days_old = round($_POST["schedule_days_old0"], 0);
			
			$makeid = round($_POST["mid0"], 0);
			$lnmin = round($_POST["lnmin0"], 0);
			$lnmax = round($_POST["lnmax0"], 0);
			$prmin = round($_POST["prmin0"], 0);
			$prmax = round($_POST["prmax0"], 0);
			$email2 = $_POST["email2"];
			//end
			
			if ($email2 != ""){
				header('Location: '. $cm->site_url .'');
				exit;
			}
			
			//captcha
			global $captchaclass;
			$captcha_ret = $captchaclass->validate_captcha($red_pg, 1);
			if ($captcha_ret == 0){
				$returnar = array(
					"retmsg" => 0
				);
				echo json_encode($returnar);
				exit;
			}
			//end
			
			$boatwatchercode = '';
			$postfields = array(
				"boatwatchercode" => $boatwatchercode,
				"reg_name" => $reg_name,
				"name" => "Yacht Finder",
				"email_to" => $email,
				"schedule_days" => $schedule_days,
				"schedule_days_old" => $schedule_days_old,				
				"makeid" => $makeid,
				"prmin" => $prmin,
				"prmax" => $prmax,
				"lnmin" => $lnmin,
				"lnmax" => $lnmax,
				"fromdashboard" => 0
			);
			
			$this->manage_boat_watcher($postfields);
		}
	}
	
	//manage Boat Watcher (add/edit)
	public function manage_boat_watcher($postfields = array()){
		global $db, $cm, $yachtclass;
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		$boatwatchercode = $postfields["boatwatchercode"];
		$name = $postfields["name"];
		$reg_name = $postfields["reg_name"];
		$email_to = $postfields["email_to"];
		$schedule_days = round($postfields["schedule_days"], 0);
		$schedule_days_old = round($postfields["schedule_days_old"], 0);		
		
		$makeid = round($postfields["makeid"], 0);
		$categoryid = round($postfields["categoryid"], 0);
		$lnmin = round($postfields["lnmin"], 0);
		$lnmax = round($postfields["lnmax"], 0);
		$yrmin = round($postfields["yrmin"], 0);
		$yrmax = round($postfields["yrmax"], 0);
		$prmin = round($postfields["prmin"], 0);
		$prmax = round($postfields["prmax"], 0);
		$stateid = round($postfields["stateid"], 0);
		$fromdashboard = round($postfields["fromdashboard"], 0);
		
		$json_data = json_encode($postfields);			
		$initial_add = 0;
		
		//Broker
		if ($boatwatchercode == ""){
			//insert
			$dt = date("Y-m-d H:i:s");
			$boatwatchercode = $cm->get_unq_code("tbl_boat_watcher_broker", "id", 10) . time();		
			$sql = "insert into tbl_boat_watcher_broker (id, broker_id, reg_date) values ('". $cm->filtertext($boatwatchercode) ."', '". $loggedin_member_id ."', '". $dt ."')";
			$db->mysqlquery($sql);
			$initial_add = 1;
		}
		
		//common update
		if ($schedule_days != $schedule_days_old){
			$schedule_date_raw = time() + (3600 * 24 * $schedule_days);
			$schedule_date = date("Y-m-d", $schedule_date_raw);
		}else{
			$schedule_date = $cm->get_common_field_name("tbl_boat_watcher_broker", "schedule_date", $boatwatchercode);
		}
		
		$sql = "update tbl_boat_watcher_broker set name = '". $cm->filtertext($name) ."'
		, reg_name = '". $cm->filtertext($reg_name) ."'
		, email_to = '". $cm->filtertext($email_to) ."'
		, searchfield = '". $cm->filtertext($json_data) ."'		
		, schedule_days = '". $schedule_days ."'
		, schedule_date = '". $schedule_date ."' where id = '". $cm->filtertext($boatwatchercode) ."'";
		$db->mysqlquery($sql);
		
		//send email
		if ($initial_add == 1){
			//$broker_email = $cm->get_common_field_name("tbl_user", "email", $broker_id);
			//$broker_name = $cm->get_common_field_name("tbl_user", "concat(fname, ' ', lname)", $broker_id);
			
			if ($loggedin_member_id > 0){
				$broker_name = $cm->get_common_field_name("tbl_user", "concat(fname, ' ', lname)", $loggedin_member_id);
			}else{
				$broker_name = $cm->filtertextdisplay($reg_name);
			}
			
			$param = array(
				"searchfield" => $json_data,
				"boatwatchercode" => $boatwatchercode,
				"schedule_days" => $schedule_days
			);
			
			//$boatwatcher_content = $this->get_boat_watcher_list($param);
			$boatwatcher_content_ar = json_decode($this->get_boat_watcher_list($param));
			$boatwatcher_content = $boatwatcher_content_ar->doc;
			$total_found = $boatwatcher_content_ar->foundrec;
					
			$postfields = array(
				"consumername" => $broker_name,
				"name" => $name,
				"email_to" => $email_to,
				"broker_email" => $email_to,
				"boatwatcher_content" => $boatwatcher_content,
				"total_found" => $total_found,
				"schedule_days" => $schedule_days,
				"searchfield" => $json_data,
				"boatwatchercode" => $boatwatchercode
			);
			
			$this->run_boat_watcher_inside($postfields);
		}
		//end
		
		if ($fromdashboard == 1){
			$returntext = $this->display_boat_watcher();
			$returnarray = array(
				'returntext' => $returntext
			);
			
			return json_encode($returnarray);
		}else{
			$returnar = array(
				"retmsg" => 1
			);
			echo json_encode($returnar);
			exit;
		}
	}
	//end
	
	//Display Boat Watcher
	public function display_boat_watcher(){
		global $db, $cm, $yachtclass, $yachtchildclass;
		$returntext = '';
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		$usertype = $yachtclass->get_user_type($loggedin_member_id);		
	
		$sql = "select * from tbl_boat_watcher_broker where broker_id = '". $loggedin_member_id ."' order by reg_date desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$counter = 1;
			$returntext .= '
			<ul class="boatgroup">
			';
			
			foreach($result as $row){				
				$boatwatchercode = $cm->filtertextdisplay($row["id"]);				
				$name = $cm->filtertextdisplay($row["name"]);				
				$email_to = $cm->filtertextdisplay($row["email_to"]);
				$searchfield = $cm->filtertextdisplay($row["searchfield"]);				
				$schedule_days = $row["schedule_days"];
				$schedule_date = $row["schedule_date"];
				
				$boatwatcher_url = $cm->get_page_url($boatwatchercode, "boatwatcherlist");
				
				$paraminside = array(
					"boatwatchercode" => $boatwatchercode,
					"name" => $name,
					"email_to" => $email_to,
					"searchfield" => $searchfield,
					"schedule_days" => $schedule_days,
					"schedule_date" => $schedule_date,
					"counter" => $counter,
					"usertype" => $usertype,
					"formtemplate" => 2
				);
				
				$searchfield = json_decode($searchfield);
				$makeid = $searchfield->makeid;
				$makename = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);				
				
				$returntext .= '
				<li id="item-'. $boatwatchercode .'" class="customgroup_ind'. $counter .'">
					<div class="group-left-col">
						<div class="normal_mode normal_mode'. $counter .'">
							<a class="group_edit viewmode" rowval="'. $counter .'" boatwatchercode="'. $boatwatchercode .'" href="javascript:void(0);" title="Edit Report"><img alt="Edit Report" title="Edit Report" src="'. $cm->folder_for_seo .'images/edit.png" /></a>
							<a class="group_del viewmode" rowval="'. $counter .'" boatwatchercode="'. $boatwatchercode .'" href="javascript:void(0);" title="Delete Report"><img alt="Delete Report" title="Delete Report" src="'. $cm->folder_for_seo .'images/del.png" /></a>
							<span class="viewmodeoption groupname groupname'. $counter .'">'. $name .'</span>
						</div>						
					</div>
					<div class="clearfix"></div>
					
					<div class="edit_mode edit_mode'. $counter .' clearfixmain com_none">
					'. $this->boat_watcher_form($paraminside) .'
					</div>
				</li>
				';				
				$counter++;
			}
			
			$returntext .= '
			</ul>			
			';
		}else{
			$returntext = 'Record(s) yet to create.';
		}
						
		return $returntext;
	}
	//end
	
	//record delete
	public function boat_watcher_delete($boatwatchercode){
		global $db, $cm, $yachtclass;
		
		$loggedin_member_id = $yachtclass->loggedin_member_id();
		
		$sql = "delete from tbl_boat_watcher_broker where id = '". $cm->filtertext($boatwatchercode) ."'";
		$db->mysqlquery($sql);
		
		//display report list
		$returntext = $this->display_boat_watcher();
		//end
		
		$returnarray = array(
			'returntext' => $returntext
	   );
	   
	   return json_encode($returnarray);
	}
	
	public function boat_watcher_delete_backend($boatwatchercode){
		global $db, $cm;		
		$sql = "delete from tbl_boat_watcher_broker where id = '". $cm->filtertext($boatwatchercode) ."'";
		$db->mysqlquery($sql);
	}
	//end
	
	//display assign boats - frontend
	public function group_user_details($boatwatchercode, $broker_id = 0){
		global $db, $cm;
		
		if ($broker_id == 0){
			$broker_id = $cm->get_common_field_name("tbl_boat_watcher_broker", "broker_id", $boatwatchercode);
		}
		$broker_det = $cm->get_table_fields('tbl_user', '*', $broker_id);
		$broker_det = $broker_det[0];
		return json_encode($broker_det);
	}
	
	
	//run script
	public function get_boat_watcher_list($param = array()){
		global $db, $cm, $yachtclass;
		$returntext = '';
		$current_date = date("Y-m-d");
		
		$boatwatchercode = $param["boatwatchercode"];
		$schedule_days = round($param["schedule_days"], 0);
		$searchfield = $param["searchfield"];	
		$searchfield = json_decode($searchfield);
		
		$makeid = $searchfield->makeid;
		$yrmin = $searchfield->yrmin;
		$yrmax = $searchfield->yrmax;
		$prmin = $searchfield->prmin;
		$prmax = $searchfield->prmax;
		$lnmin = $searchfield->lnmin;
		$lnmax = $searchfield->lnmax;
		$categoryid = $searchfield->categoryid;	
		$conditionid = $searchfield->conditionid;				
		$typeid = $searchfield->typeid;
		$enginetypeid = $searchfield->enginetypeid;
		$drivetypeid = $searchfield->drivetypeid;
		$fueltypeid = $searchfield->fueltypeid;
		$stateid = $searchfield->stateid;
		$regionselect = $searchfield->regionselect;
		
		$query_sql = "select distinct a.*";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_form .= " tbl_manufacturer as b,";
		$query_where .= " b.id = a.manufacturer_id and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		if ($makeid > 0){
			$query_where .= " a.manufacturer_id = '". $makeid ."' and";
		}
		
		if ($yrmin > 0){
			$query_where .= " a.year >= '". $yrmin ."' and";
		}
		
		if ($yrmax > 0){
			$query_where .= " a.year <= '". $yrmax ."' and";
		}
		
		if ($categoryid > 0){
			$query_where .= " a.category_id = '". $categoryid ."' and";
		}
		
		if ($conditionid > 0){
			$query_where .= " a.condition_id = '". $conditionid ."' and";
		}
		
		if ($prmin > 0){
			$query_where .= " a.price >= '". $prmin ."' and";
		}
		
		if ($prmax > 0){
			$query_where .= " a.price <= '". $prmax ."' and";
		}
		
		if ($stateid > 0){
			$query_where .= " a.state_id = '". $stateid ."' and";
		}
		
		if ($regionselect != ""){ 
			$regionselect = rtrim($regionselect, ',');
			$state_filter = '';
			$regionselect_ar = explode(",", $regionselect);
			foreach($regionselect_ar as $regionselect_row){
				$states_det = json_decode($this->get_states_for_region($regionselect_row));
				$state_filter .= $states_det->state_ids . ",";
			}
			$state_filter = rtrim($state_filter, ',');
			
			$query_where .= " a.state_id IN ( ". $state_filter. " ) and";
		}
		
		if ($lnmin > 0){
			$query_where .= " c.length >= '". $lnmin ."' and";
		}
		
		if ($lnmax > 0){
			$query_where .= " c.length <= '". $lnmax ."' and";
		}
		
		if ($typeid > 0 ){
			$typesql = "";
			$type_sql = $cm->all_child_type($typeid, $typesql);
			$type_sql = $typeid . ", " . $type_sql;
			$type_sql = rtrim($type_sql, ", ");

			$query_form .= " tbl_yacht_type_assign as d,";
			$query_where .= " a.id = d.yacht_id and d.type_id IN (". $type_sql .") and";
		}
		
		if ($enginetypeid > 0 OR $drivetypeid > 0 OR $fueltypeid > 0){
			$query_form .= " tbl_yacht_engine as e,";
			$query_where .= " a.id = e.yacht_id and";
		}
		if ($enginetypeid > 0){
			$query_where .= "  e.engine_type_id = '". $enginetypeid . "' and";
		}
		if ($drivetypeid > 0){
			$query_where .= "  e.drive_type_id = '". $drivetypeid . "' and";
		}
		if ($fueltypeid > 0){
			$query_where .= "  e.fuel_type_id = '". $fueltypeid . "' and";
		}
		
		$query_where .= " a.status_id = 1 and";

		if ($schedule_days > 1){	
			$query_where .= " a.update_for_yf >= DATE_SUB(CURDATE(), INTERVAL ". $schedule_days ." DAY) and";
		}else{
			$query_where .= " year(a.update_for_yf) = '". date("Y") ."' and";
			$query_where .= " month(a.update_for_yf) = '". date("m") ."' and";
			$query_where .= " dayofmonth(a.update_for_yf) = '". date("d") ."' and";
		}
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		
		$sortfield = 'a.last_updated desc';
        $sql = $query_sql . $query_form . $query_where . " order by " . $sortfield;
		//echo $sql;
		
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$tabletopspace = 'margin-top: 5px; border-collapse: collapse; border: 1px solid #4f5660;';
			
			$tdheading = 'border: 1px solid #4f5660; background-color: #4f5660; color: #fff; font-family: Arial, Tahoma; font-size: 13px; font-weight: bold; text-decoration: none;';
			$tdrow = 'border: 1px solid #4f5660; background-color: #fff; color: #000000; font-family: Arial, Tahoma; font-size: 13px; font-weight: normal; text-decoration: none;';
			
			$returntext = '
			<table style="'. $tabletopspace .'" border="0" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<td style="'. $tdheading .'" width="30%" align="center">Photo</td>
					<td style="'. $tdheading .'" width="50%" align="left">Info</td>
					<td style="'. $tdheading .'" width="20%" align="center">&nbsp;</td>
				</tr>
			';
			
			foreach($result as $row){
				
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				$yacht_title = $yachtclass->yacht_name($id);
				
				$ppath = $yachtclass->get_yacht_first_image($id);
				$imagefolder = 'yachtimage/'. $listing_no .'/slider/';
				$price_display = $yachtclass->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
				
				$b_ar = array(
					"boatid" => $id, 
					"makeid" => $manufacturer_id, 
					"ownboat" => $ownboat, 
					"feed_id" => $feed_id, 
					"getdet" => 0
				);
				$fullurl = $cm->site_url . $yachtclass->get_boat_details_url($b_ar);
				
				$returntext .= '
				<tr>
					<td style="'. $tdrow .'" width="" align="center"><a href="'. $fullurl .'"><img style="width: 100%" src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt="" /></a></td>
					<td style="'. $tdrow .'" width=" align="center">'. $yacht_title .'<br /><strong>'. $price_display .'</strong></td>
					<td style="'. $tdrow .'" width="" align="center"><a style="text-decoration: none; color: #003e7e" href="'. $fullurl .'">Details</a></td>
				</tr>
				';
			}
			
			$returntext .= '</table>';
		}		
		//return $returntext;
		
		$returnar = array(
			"doc" => $returntext,
			"foundrec" => $found
		);
		
		return json_encode($returnar);	
	}
	
	public function run_boat_watcher_inside($postfields = array()){
		global $db, $cm, $sdeml, $fle;
		
		$consumername = $postfields["consumername"];
		$name = $postfields["name"];
		$email_to = $postfields["email_to"];
		$boatwatcher_content = $postfields["boatwatcher_content"];
		$total_found = $postfields["total_found"];
		$schedule_days = round($postfields["schedule_days"], 0);
		$searchfield = $postfields["searchfield"];
		$boatwatchercode = $postfields["boatwatchercode"];
		$searchfield = json_decode($searchfield);
		
		$makeid = $searchfield->makeid;
		$yrmin = $searchfield->yrmin;
		$yrmax = $searchfield->yrmax;
		$prmin = $searchfield->prmin;
		$prmax = $searchfield->prmax;
		$lnmin = $searchfield->lnmin;
		$lnmax = $searchfield->lnmax;
		$categoryid = $searchfield->categoryid;	
		$conditionid = $searchfield->conditionid;				
		$typeid = $searchfield->typeid;
		$enginetypeid = $searchfield->enginetypeid;
		$drivetypeid = $searchfield->drivetypeid;
		$fueltypeid = $searchfield->fueltypeid;
		$stateid = $searchfield->stateid;
		$regionselect = $searchfield->regionselect;
		
		$searchfield_text = '';
		if ($makeid > 0){
			$make_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $makeid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Manufacturer:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $make_name .'</td>
			</tr>
			';
		}
		
		if ($yrmin > 0){
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Year Min:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $yrmin .'</td>
			</tr>
			';
		}
		
		if ($yrmax > 0){
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Year Max:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $yrmax .'</td>
			</tr>
			';
		}
		
		if ($categoryid > 0){
			$category_name = $cm->get_common_field_name('tbl_category', 'name', $categoryid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Category:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $category_name .'</td>
			</tr>
			';
		}
		
		if ($conditionid > 0){
			$condition_name = $cm->get_common_field_name('tbl_condition', 'name', $conditionid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Condition:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $condition_name .'</td>
			</tr>
			';
		}
		
		if ($prmin > 0){
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Price Min:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $prmin .'</td>
			</tr>
			';
		}
		
		if ($prmax > 0){
			$query_where .= " a.price <= '". $prmax ."' and";
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Price Max:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $prmax .'</td>
			</tr>
			';
		}
		
		if ($lnmin > 0){
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Length Min:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $lnmin .'</td>
			</tr>
			';
		}
		
		if ($lnmax > 0){
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Length Max:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $lnmax .'</td>
			</tr>
			';
		}
		
		if ($typeid > 0 ){
			$type_name = $cm->get_common_field_name('tbl_typr', 'name', $typeid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Class:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $type_name .'</td>
			</tr>
			';
		}
		
		if ($enginetypeid > 0){
			$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $enginetypeid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Engine Class:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $engine_type_name .'</td>
			</tr>
			';
		}
		if ($drivetypeid > 0){
			$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drivetypeid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Drive Type:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $drive_type_name .'</td>
			</tr>
			';
		}
		if ($fueltypeid > 0){
			$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fueltypeid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">Fuel Type:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $fuel_type_name .'</td>
			</tr>
			';
		}
				
		if ($stateid > 0){
			$state_name = $cm->get_common_field_name('tbl_state', 'code', $stateid);
			$searchfield_text .= '
			<tr>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="45%">US State:</td>
				<td align="left" valign="top" style="padding: 5px 10px 5px 0px;'. $defaultfontcss .'" width="">'. $state_name .'</td>
			</tr>
			';
		}
		
		if ($searchfield_text != ""){
			$searchfield_text = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="padding: 15px 5px 5px 0px;'. $defaultheading .'" colspan="2"><strong>Search Criteria</strong></td>
				</tr>
				'. $searchfield_text .'
			</table>
			';
		}
		

		if ($schedule_days > 1){
			$daycount = $schedule_days . ' days';
		}else{
			$daycount = '1 day';
		}
		
		if ($boatwatcher_content != ""){
			$companyname = $cm->sitename;
			$unsubscribewatcherlink = $cm->site_url."/?fcapi=unsubscribewatchersubmit&id=".$boatwatchercode;
			$unsubscribewatcher = '<a style="font-size: 12px; color:#b3adb0;" href="'. $unsubscribewatcherlink .'">Turn off this alert</a>';
	
			//send email to user
			$send_ml_id = 36;
			$fr_email_ar = $cm->get_table_fields('tbl_system_email', 'email_subject, pdes', $send_ml_id);
			$fr_email_ar = (object)$fr_email_ar[0];
			$fr_msg = $fr_email_ar->pdes;
			$fr_mail_subject = $fr_email_ar->email_subject;			
			
			$fr_msg = str_replace("#name#", $cm->filtertextdisplay($consumername), $fr_msg);
			$fr_msg = str_replace("#watchername#", $cm->filtertextdisplay($name), $fr_msg);
			$fr_msg = str_replace("#companyname#", $cm->filtertextdisplay($companyname), $fr_msg);
			
			$fr_msg = str_replace("#daycount#", $daycount, $fr_msg);
			$fr_msg = str_replace("#totalfound#", $total_found, $fr_msg);
			$fr_msg = str_replace("#searchfield#", $searchfield_text, $fr_msg);
			$fr_msg = str_replace("#boatwatchercontent#", $boatwatcher_content, $fr_msg);
			$fr_msg = str_replace("#unsubscribewatcher#", $unsubscribewatcher, $fr_msg);
					
			$fr_mail_subject = str_replace("#name#", $cm->filtertextdisplay($consumername), $fr_mail_subject);
			$fr_mail_subject = str_replace("#watchername#", $cm->filtertextdisplay($name), $fr_mail_subject);
			$fr_mail_subject = str_replace("#companyname#", $companyname, $fr_mail_subject);	
			
			$mail_fm = $cm->yf_email_from();
			$mail_to = $cm->filtertextdisplay($email_to);
			$mail_cc = "";
			$mail_reply = "";
			$fromnamesender = "The Yacht Finder";
			$sdeml->send_email($mail_fm, $mail_to, $mail_cc, $mail_bcc, $mail_reply, $fr_mail_subject, $fr_msg, $cm->site_url, '');
			//end
			
			//insert email to DB
			$send_date = date("Y-m-d H:i:s");
			$boatwatcheremailcode = $cm->get_unq_code("tbl_boat_watcher_email", "id", 10) . time();
			$sql = "insert into tbl_boat_watcher_email (id, email, email_content, send_date) values ('". $cm->filtertext($boatwatcheremailcode) ."', '". $cm->filtertext($email_to) ."', '". $cm->filtertext($fr_msg) ."', '". $send_date ."')";
			$db->mysqlquery($sql);
			//end
		}
	}
	
	public function run_boat_watcher(){
		global $db, $cm, $sdeml, $fle;
		$rowset = 100;
		$current_date = date("Y-m-d");
		
		$sql = "select count(*) as ttl from tbl_boat_watcher_broker where schedule_date = '". $current_date ."'";
		$totalboat = $db->total_record_count($sql);
			
		for($k = 0; $k < $totalboat; $k+=$rowset){
			$sql = "select * from tbl_boat_watcher_broker where schedule_date = '". $current_date ."' limit ". $k . ", " . $rowset;
			$result = $db->fetch_all_array($sql);
			foreach($result as $row){
				$boatwatchercode = $row["id"];
				$broker_id = $row["broker_id"];	
				$name = $cm->filtertextdisplay($row["name"]);
				$reg_name = $cm->filtertextdisplay($row["reg_name"]);
				$schedule_days = $row["schedule_days"];
				$schedule_date = $row["schedule_date"];
				$email_to = $row["email_to"];
				
				$searchfield = $cm->filtertextdisplay($row["searchfield"]);
				
				if ($broker_id > 0){
					$broker_name = $cm->get_common_field_name("tbl_user", "concat(fname, ' ', lname)", $broker_id);
					$broker_status_id = $cm->get_common_field_name("tbl_user", "status_id", $broker_id);
				}else{
					$broker_name = $reg_name;
					$broker_status_id = 2;
				}
				
				if ($broker_status_id == 2){
										
					$broker_email = $cm->get_common_field_name("tbl_user", "email", $broker_id);				
					
					$param = array(
						"searchfield" => $searchfield,
						"boatwatchercode" => $boatwatchercode,
						"schedule_days" => $schedule_days
					);
							
					$boatwatcher_content_ar = json_decode($this->get_boat_watcher_list($param));
					$boatwatcher_content = $boatwatcher_content_ar->doc;
					$total_found = $boatwatcher_content_ar->foundrec;
					
					$postfields = array(
						"consumername" => $broker_name,
						"name" => $name,
						"email_to" => $email_to,
						"broker_email" => $email_to,
						"boatwatcher_content" => $boatwatcher_content,
						"total_found" => $total_found,
						"schedule_days" => $schedule_days,
						"searchfield" => $searchfield,
						"boatwatchercode" => $boatwatchercode
					);					
					
					$this->run_boat_watcher_inside($postfields);
				}
				
				//update schedule_date
				$next_schedule_date = strtotime($schedule_date) + (3600 * 24 * $schedule_days);
				$sql_u = "update tbl_boat_watcher_broker set schedule_date = '". date("Y-m-d", $next_schedule_date) ."'  where id = '". $cm->filtertext($boatwatchercode) ."'";
				$db->mysqlquery($sql_u);						
			}
		}
	}
	
	//unsubscribe - delete from email
	public function boat_watcher_unsubscribe(){
		if(($_REQUEST['fcapi'] == "unsubscribewatchersubmit")){
			global $db, $cm;
			
			$boatwatchercode = $_REQUEST["id"];
			$sql = "delete from tbl_boat_watcher_broker where id = '". $cm->filtertext($boatwatchercode) ."'";
			$db->mysqlquery($sql);
			
			$cm->thankyouredirect(35);
		}
	}
	//end
	
	//display boat watcher email content
	public function display_boat_watcher_email_content($id){
		global $db, $cm;
		$returntext = '';
		
		$query_sql = "select *";
		$query_form = " from tbl_boat_watcher_email";
		$query_where = " where";
		
		$query_where .= " id = '". $cm->filtertext($id) ."' and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$row = $result[0];
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$returntext = $email_content;
		}else{
			$returntext = '<h2>Invalid selection</h2>';
		}
		
		return $returntext;
	}
	
	//delete boat watcher email content
	public function boat_watcher_delete_email_content($boatwatcheremailcode){
		global $db, $cm;		
		$sql = "delete from tbl_boat_watcher_email where id = '". $cm->filtertext($boatwatcheremailcode) ."'";
		$db->mysqlquery($sql);
	}
	
}
?>
