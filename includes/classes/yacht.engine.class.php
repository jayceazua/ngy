<?php
class Yachtclass_Engine extends Yachtclass{
	//engine location combo
	public function get_engine_location_combo($engine_location_id){
        global $db;
        $returntxt = '';
        $vsql = "select id, name from tbl_yacht_engine_location order by rank";
        $vresult = $db->fetch_all_array($vsql);
        foreach($vresult as $vrow){
            $c_id = $vrow['id'];
            $cname = $vrow['name'];	
			$bck = '';
			if ($engine_location_id == $c_id){
				$bck = ' selected="selected"';	
			}
            $returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
        }
        return $returntxt;
    }
	
	public function get_engine_location_ajax($engine_location_id){		
		$engine_location_data = $this->get_engine_location_combo($engine_location_id);	
		$returnval = array(
			'engine_location_data' => $engine_location_data
		);
		return json_encode($returnval);
	}
	
	//display engine details data - entry form
	public function display_engine_details_form($yacht_id, $frontfrom = 0){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_yacht_engine_details where yacht_id = '". $yacht_id ."' order by engine_hours desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($frontfrom == 0){
			$returntext .= '<table id="enginedetailsholder" border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">';
		}else{
			$returntext .= '<ul id="enginedetailsholder" class="form">';
		}
		if ($found > 0){
			$counter = 0;
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);					
				}
				
				if ($engine_year == 0){ $engine_year = ""; }
				if ($engine_hours == 0){ $engine_hours = ""; }
				if ($overhaul_hours == 0){ $overhaul_hours = ""; }
				
				if ($frontfrom == 0){				
					$returntext .= '
					<tr class="enginedetailsind'. $counter .'" colspan="4" height="15"><img border="0" src="images/sp.gif" alt="" /></td>
					<tr class="enginedetailsind'. $counter .'">
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Location:</td>
						<td width="30%" align="left">
						<select name="engine_location_id'. $counter .'" id="engine_location_id'. $counter .'" class="combobox_size4 htext">
						<option value="">Select</option>
						'. $this->get_engine_location_combo($engine_location_id) .'
						</select>
						</td>
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Year:</td>
						<td width="30%" align="left"><input type="text" id="engine_year'. $counter .'" name="engine_year'. $counter .'" class="inputbox inputbox_size4" value="'. $engine_year .'" /></td>				
					</tr>
					
					<tr class="enginedetailsind'. $counter .'">
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Hours:</td>
						<td width="30%" align="left"><input type="text" id="engine_hours'. $counter .'" name="engine_hours'. $counter .'" class="inputbox inputbox_size4" value="'. $engine_hours .'" /></td>
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Serial #:</td>
						<td width="30%" align="left"><input type="text" id="engine_serial'. $counter .'" name="engine_serial'. $counter .'" class="inputbox inputbox_size4" value="'. $engine_serial .'" /></td>				
					</tr>
					
					<tr class="enginedetailsind'. $counter .'">
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Date:</td>
						<td width="30%" align="left"><input type="text" id="overhaul_date'. $counter .'" name="overhaul_date'. $counter .'" class="inputbox inputbox_size4" value="'. $overhaul_date .'" /></td>
						<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Hours:</td>
						<td width="30%" align="left"><input type="text" id="overhaul_hours'. $counter .'" name="overhaul_hours'. $counter .'" class="inputbox inputbox_size4" value="'. $overhaul_hours .'" /></td>				
					</tr>
					
					<tr class="enginedetailsind'. $counter .'">
						<td width="100%" align="left" colspan="4"><a class="enginedetails_del" title="Delete Record" href="javascript:void(0);" isdb="1" yval="'. $counter .'" engine_details_id="'. $id .'"><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>			
					</tr>
					';
				}else{
					$returntext .= '
					<li class="left enginedetailsind'. $counter .'">
						<p>Location</p>
						<select name="engine_location_id'. $counter .'" id="engine_location_id'. $counter .'" class="my-dropdown2">
						<option value="">Select</option>
						'. $this->get_engine_location_combo($engine_location_id) .'
						</select>
					</li>				
					<li class="right enginedetailsind'. $counter .'">
						<p>Year</p>
						<input type="text" id="engine_year'. $counter .'" name="engine_year'. $counter .'" class="input" value="'. $engine_year .'" />
					</li>
					
					<li class="left enginedetailsind'. $counter .'">
						<p>Hours</p>
						<input type="text" id="engine_hours'. $counter .'" name="engine_hours'. $counter .'" class="input" value="'. $engine_hours .'" />
					</li>
					<li class="right enginedetailsind'. $counter .'">
						<p>Serial #</p>
						<input type="text" id="engine_serial'. $counter .'" name="engine_serial'. $counter .'" class="input" value="'. $engine_serial .'" />
					</li>
					
					<li class="left enginedetailsind'. $counter .'">
						<p>Overhaul Date</p>
						<input type="text" id="overhaul_date'. $counter .'" name="overhaul_date'. $counter .'" class="input" value="'. $overhaul_date .'" />
					</li>
					<li class="right enginedetailsind'. $counter .'">
						<p>Overhaul Hours</p>
						<input type="text" id="overhaul_hours'. $counter .'" name="overhaul_hours'. $counter .'" class="input" value="'. $overhaul_hours .'" />
					</li>
					
					<li class="enginedetailsind'. $counter .'">
						<a class="enginedetails_del" title="Delete Record" href="javascript:void(0);" isdb="1" yval="'. $counter .'" engine_details_id="'. $id .'"><img src="'. $cm->folder_for_seo .'images/del.png" title="Delete Record" alt="Delete Record"></a>		
					</li>
					';
				}
				
				$counter++;
			}
		}else{
			if ($frontfrom == 0){
				$returntext .= '
				<tr class="enginedetailsind0">
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Location:</td>
					<td width="30%" align="left">
					<select name="engine_location_id0" id="engine_location_id0" class="combobox_size4 htext">
					<option value="">Select</option>
					'. $this->get_engine_location_combo(0) .'
					</select>
					</td>
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Year:</td>
					<td width="30%" align="left"><input type="text" id="engine_year0" name="engine_year0" class="inputbox inputbox_size4" /></td>				
				</tr>
				
				<tr class="enginedetailsind0">
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Hours:</td>
					<td width="30%" align="left"><input type="text" id="engine_hours0" name="engine_hours0" class="inputbox inputbox_size4" /></td>
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Serial #:</td>
					<td width="30%" align="left"><input type="text" id="engine_serial0" name="engine_serial0" class="inputbox inputbox_size4" /></td>				
				</tr>
				
				<tr class="enginedetailsind0">
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Date:</td>
					<td width="30%" align="left"><input type="text" id="overhaul_date0" name="overhaul_date0" class="inputbox inputbox_size4" /></td>
					<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Hours:</td>
					<td width="30%" align="left"><input type="text" id="overhaul_hours0" name="overhaul_hours0" class="inputbox inputbox_size4" /></td>				
				</tr>
				';
			}else{
				$returntext .= '
				<li class="left enginedetailsind0">
                    <p>Location</p>
                    <select name="engine_location_id0" id="engine_location_id0" class="my-dropdown2">
					<option value="">Select</option>
					'. $this->get_engine_location_combo(0) .'
					</select>
                </li>				
				<li class="right enginedetailsind0">
                    <p>Year</p>
                    <input type="text" id="engine_year0" name="engine_year0" class="input" />
                </li>
				
				<li class="left enginedetailsind0">
                    <p>Hours</p>
                    <input type="text" id="engine_hours0" name="engine_hours0" class="input" />
                </li>
				<li class="right enginedetailsind0">
                    <p>Serial #</p>
                    <input type="text" id="engine_serial0" name="engine_serial0" class="input" />
                </li>
				
				<li class="left enginedetailsind0">
                    <p>Overhaul Date</p>
                    <input type="text" id="overhaul_date0" name="overhaul_date0" class="input" />
                </li>
				<li class="right enginedetailsind0">
                    <p>Overhaul Hours</p>
                    <input type="text" id="overhaul_hours" name="overhaul_hours" class="input" />
                </li>
				';
			}
		}
		
		if ($frontfrom == 0){
			$returntext .= '</table>';
			
			$returntext .= '
			<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
				<tr>
					<td width="" align="left" valign="top" class="tdpadding1"><button type="button" class="addrowenginedetails butta"><span class="addIcon butta-space">Add New</span></button></td>
				</tr>
			</table>
			<input type="hidden" value="'. $found .'" id="total_engine_details" name="total_engine_details" />
			';
		}else{
			$returntext .= '</ul>
			<a href="javascript:void(0);" class="addrowenginedetails icon-add">Add New</a>
			<input type="hidden" value="'. $found .'" id="total_engine_details" name="total_engine_details" />
			';			
		}
		
		return $returntext;
	}
	
	//assign engine details to yacht
	public function engine_details_assign($yacht_id){
		global $db, $cm;
		
		$sql = "delete from tbl_yacht_engine_details where yacht_id = '". $yacht_id ."'";
		$db->mysqlquery($sql);
		$total_engine_details = round($_POST["total_engine_details"], 0);
		for ($i = 0; $i <= $total_engine_details; $i++){
			$engine_location_id = round($_POST["engine_location_id" . $i], 0);
			$engine_year = round($_POST["engine_year" . $i], 0);
			$engine_hours = round($_POST["engine_hours" . $i], 0);
			$engine_serial = $_POST["engine_serial" . $i];
			$overhaul_date = $_POST["overhaul_date" . $i];
			$overhaul_hours = round($_POST["overhaul_hours" . $i], 0);
			
			if ($engine_location_id > 0 AND $engine_hours > 0){
				$engine_details_id = $cm->campaignid(35) . $yacht_id;
				
				$sql = "insert into tbl_yacht_engine_details (id, yacht_id, engine_location_id, engine_year, engine_hours, engine_serial, overhaul_date, overhaul_hours) values ('". $cm->filtertext($engine_details_id) ."', '". $yacht_id ."', '". $engine_location_id ."', '". $engine_year ."', '". $engine_hours ."', '". $cm->filtertext($engine_serial) ."', '". $cm->filtertext($overhaul_date) ."', '". $overhaul_hours ."')";
				$db->mysqlquery($sql);
			}
		}
	}
	
	//delete engine details
	public function delete_engine_details($engine_details_id){
		global $db, $cm;
		$sql = "delete from tbl_yacht_engine_details where id = '". $cm->filtertext($engine_details_id) ."'";
		$db->mysqlquery($sql);
	}
	
	//display engine details on yacht details page
	public function display_engine_details($yacht_id, $nostartul = 0, $template = 1){
		global $db, $cm;
		$returntext = '';
		
		if ($template == 2){
			$labeltitle_class = "labeltitle2";
			$labelvalue_class = "labelvalue2";
		}else{
			$labeltitle_class = "labeltitle";
			$labelvalue_class = "labelvalue";
		}		
		
		
		$sql = "select * from tbl_yacht_engine_details where yacht_id = '". $yacht_id ."' order by engine_hours desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			if ($nostartul == 0){
				$returntext .= '<ul>';
			}
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);					
				}
					
				if ($engine_location_id > 0 OR $engine_hours > 0){
					$engine_location_name = $cm->get_common_field_name("tbl_yacht_engine_location", "name", $engine_location_id);
					$returntext .= '
					<li class="clearfixmain">
						<div class="'. $labeltitle_class .'">Location:</div>
						<div class="'. $labelvalue_class .'">'. $engine_location_name .'</div>
					</li>

					<li class="clearfixmain">
						<div class="'. $labeltitle_class .'">Hours:</div>
						<div class="'. $labelvalue_class .'">'. $engine_hours .'</div>
					</li>
					';
				}
			}
			if ($nostartul == 0){
				$returntext .= '</ul>';
			}
		}
		
		return $returntext;		
	}
	
}
?>