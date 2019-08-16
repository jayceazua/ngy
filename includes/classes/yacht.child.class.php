<?php
class Yachtclass_Child extends Yachtclass{
	//Boat search left col
	public function get_make_name_by_field($val, $fieldcheck = 1){
		global $cm;
		
		if ($fieldcheck == 2){
			$field_name = "slug";
		}else{
			$field_name = "id";
		}
		
		$name = $cm->get_common_field_name('tbl_manufacturer', 'name', $val, $field_name);
		return $name;
	}
	
	public function yacht_search_column($param = array()){
		$searchtemplate = $param["searchtemplate"];
		$searchoption = $param["searchoption"];
		$dashboardinventory = round($param["dashboardinventory"], 0);
		$rawtemplate = $param["rawtemplate"];	
		//$apinoselection = round($_REQUEST["owned"], 0);
		$apinoselection = round($param["apinoselection"], 0);
		$searchtypeselection = round($param["searchtypeselection"], 0);
		$disable_make_search = round($param["disable_make_search"], 0);
		$gen_sql = $param["gen_sql"];		
		
		if (isset($_SESSION["created_search"]) AND is_array($_SESSION["created_search"]) AND count($_SESSION["created_search"]) > 0){
			$mfcname = $_SESSION["created_search"]["s_mfcname"];
			$mfslug = $_SESSION["created_search"]["s_mfslug"];
			$makeid = $_SESSION["created_search"]["s_makeid"];
			$modelname = $_SESSION["created_search"]["s_modelname"];
			$stateid = $_SESSION["created_search"]["s_stateid"];
			$countryid = $_SESSION["created_search"]["s_countryid"];
			$yrmin = $_SESSION["created_search"]["s_yrmin"];
			$yrmax = $_SESSION["created_search"]["s_yrmax"];
			$typeid = $_SESSION["created_search"]["s_typeid"];
			$conditionid = $_SESSION["created_search"]["s_conditionid"];
			$prmin = $_SESSION["created_search"]["s_prmin"];
			$prmax = $_SESSION["created_search"]["s_prmax"];
			$statename = $_SESSION["created_search"]["s_statename"];
			$countryname = $_SESSION["created_search"]["s_countryname"];
			$lnmin = $_SESSION["created_search"]["s_lnmin"];
			$lnmax = $_SESSION["created_search"]["s_lnmax"];
			$categoryid = $_SESSION["created_search"]["s_categoryid"];
			$categorynm = $_SESSION["created_search"]["s_categorynm"];
			$enginetypeid = $_SESSION["created_search"]["s_enginetypeid"];
			$drivetypeid = $_SESSION["created_search"]["s_drivetypeid"];
			$fueltypeid = $_SESSION["created_search"]["s_fueltypeid"];
			$owned = $_SESSION["created_search"]["s_owned"];
			$regshowid = $_SESSION["created_search"]["s_regshowid"];
			$featured = $_SESSION["created_search"]["s_featured"];
			$feacat = $_SESSION["created_search"]["s_feacat"];
			$charter = $_SESSION["created_search"]["s_charter"];
			$boatstatus = $_SESSION["created_search"]["s_boatstatus"];
			$tradein = $_SESSION["created_search"]["s_tradein"];
			$uptoday = $_SESSION["created_search"]["s_uptoday"];
			$brokerslug = $_SESSION["created_search"]["s_brokerslug"];
			$sp_typeid = $_SESSION["created_search"]["s_sp_typeid"];
			$similaryacht_type_filter = $_SESSION["created_search"]["s_similaryacht_type_filter"];
			$mostviewed = $_SESSION["created_search"]["s_mostviewed"];
		}else{
			$mfcname = '';
			$mfslug = '';
			$modelname = '';
			$makeid = 0;
			$stateid = 0;
			$countryid = 0;
			$yrmin = 0;
			$yrmax = 0;
			$typeid = 0;
			$conditionid = 0;
			$prmin = 0;
			$prmax = 0;
			$statename = '';
			$countryname = '';
			$lnmin = 0;
			$lnmax = 0;
			$categoryid = 0;
			$categorynm = '';
			$enginetypeid = 0;
			$drivetypeid = 0;
			$fueltypeid = 0;
			$owned = 0;
			$regshowid = 0;
			$featured = 0;
			$feacat = 0;
			$charter = 0;
			$boatstatus = 0;
			$tradein = 0;
			$uptoday = 0;
			$brokerslug = '';
			$sp_typeid = 0;
			$similaryacht_type_filter = 0;
			$mostviewed = 0;
		}
		
		if ($prmin == 0){ $prmin = ''; }
		if ($prmax == 0){ $prmax = ''; }
		if ($lnmin == 0){ $lnmin = ''; }
		if ($lnmax == 0){ $lnmax = ''; }
		
		if ($makeid > 0){
			$mfcname = $this->get_make_name_by_field($makeid);
		}else{
			if ($mfslug != ""){
				$mfcname = $this->get_make_name_by_field($mfslug, 2);
			}
		}
		
		if ($searchtemplate == 1){
			$form_argu = array(
				"formtype" => 3,
				"disable_make_search" => $disable_make_search,
				"apinoselection" => $apinoselection,
				"searchtypeselection" => $searchtypeselection,
				"owned" => $owned,
				"mfcname" => $mfcname,
				"conditionid" => $conditionid,
				"typeid" => $typeid,
				"sp_typeid" => $sp_typeid,
				"lnmin" => $lnmin,
				"lnmax" => $lnmax,
				"yrmin" => $yrmin,
				"yrmax" => $yrmax,
				"prmin" => $prmin,
				"prmax" => $prmax
			);
			$formdata = json_decode($this->boat_advanced_search_form_small($form_argu));
			$smallform = $formdata->smallform;
			
			if ($countryid == 1){
				$stateactiveval = $stateid;
			}else{
				$stateactiveval = $statename;
			}
			
			$returntext = '
			<form id="secrhfilter" name="secrhfilter" class="boatajaxform">
			<h3 class="ad-search"><span>Advanced search</span></h3>
			<div class="ad-search-con">
			<div class="search-container clearfixmain">
				<h4 class="borderstyle1">Search By</h4>
				<div class="search-container-in clearfixmain">
				'. $smallform .'
				</div>
				
				<div class="boatfilterbyholder clearfixmain">
					<a class="filterby" href="javascript:void(0);">Filter By</a>
					<div class="boatfilterbyholder-in com_none clearfixmain">
						<div class="filterbycategory clearfixmain">'. $this->get_category_list_filter(array("sql" => $gen_sql, "activeval" => $categoryid, "small_list" => 1)) .'</div>					
						<div class="filterbycondition clearfixmain">'. $this->get_condition_list_filter(array("sql" => $gen_sql, "activeval" => $conditionid, "small_list" => 1)) .'</div>
						<div class="filterbyboattype clearfixmain">'. $this->get_boattype_list_filter(array("sql" => $gen_sql, "activeval" => $typeid, "small_list" => 1)) .'</div>
						<div class="filterbymake clearfixmain">'. $this->get_make_list_filter(array("sql" => $gen_sql, "activeval" => $mfcname, "small_list" => 1)) .'</div>
						<div class="filterbymodel clearfixmain">'. $this->get_model_list_filter(array("sql" => $gen_sql, "mfcname" => $mfcname, "activeval" => $modelname, "small_list" => 1)) .'</div>
						<div class="filterbycountry clearfixmain">'. $this->get_country_list_filter(array("sql" => $gen_sql, "activeval" => $countryid, "small_list" => 1)) .'</div>
						<div class="filterbystate clearfixmain">'. $this->get_state_list_filter(array("sql" => $gen_sql, "countryid" => $countryid, "activeval" => $stateactiveval, "small_list" => 1)) .'</div>					
					</div>
				</div>
				
				<div class="clearfixmain">
					<div class="boattype_full filterfull clearfixmain">
						'. $this->get_boattype_list_filter(array("sql" => $gen_sql, "activeval" => $typeid, "small_list" => 0)) .'
					</div>
					
					<div class="make_full filterfull clearfixmain">
						'. $this->get_make_list_filter(array("sql" => $gen_sql, "activeval" => $mfcname, "small_list" => 0)) .'
					</div>
					<div class="model_full filterfull clearfixmain">
						'. $this->get_model_list_filter(array("sql" => $gen_sql, "mfcname" => $mfcname, "activeval" => $modelname, "small_list" => 0)) .'
					</div>
					<div class="country_full filterfull clearfixmain">
						'. $this->get_country_list_filter(array("sql" => $gen_sql, "activeval" => $countryid, "small_list" => 0)) .'
					</div>
					<div class="state_full filterfull clearfixmain">
						'. $this->get_state_list_filter(array("sql" => $gen_sql, "countryid" => $countryid, "activeval" => $stateactiveval, "small_list" => 1)) .'
					</div>
				</div>				
				
			</div>
			';
			
			$returntext .= '
			</div>
			
			<input type="hidden" value="0" name="allmy" id="allmy" />
            <input type="hidden" value="" name="brokername" id="brokername" />
			
			<input type="hidden" name="conditionid" id="conditionid" value="'. $conditionid .'" />
			<input type="hidden" name="categoryid" id="categoryid" value="'. $categoryid .'" />
			<input type="hidden" name="typeid" id="typeid" value="'. $typeid .'" />
			<input type="hidden" name="enginetypeid" id="enginetypeid" value="'. $enginetypeid .'" />
			<input type="hidden" name="drivetypeid" id="drivetypeid" value="'. $drivetypeid .'" />
			<input type="hidden" name="fueltypeid" id="fueltypeid" value="'. $fueltypeid .'" />
			
			<input type="hidden" name="regshowid" id="regshowid" value="'. $regshowid .'" />
			<input type="hidden" name="featured" id="featured" value="'. $featured .'" />
			<input type="hidden" name="feacat" id="feacat" value="'. $feacat .'" />
			<input type="hidden" name="charter" id="charter" value="'. $charter .'" />
			<input type="hidden" name="boatstatus" id="boatstatus" value="'. $boatstatus .'" />
			<input type="hidden" name="tradein" id="tradein" value="'. $tradein .'" />
			<input type="hidden" name="uptoday" id="uptoday" value="'. $uptoday .'" />
			<input type="hidden" name="modelname" id="modelname" value="'. $modelname .'" />
			
			<input type="hidden" name="statename" id="statename" value="'. $statename .'" />
			<input type="hidden" name="stateid" id="stateid" value="'. $stateid .'" />
			<input type="hidden" name="countryid" id="countryid" value="'. $countryid .'" />
			<input type="hidden" name="brokerslug" id="brokerslug" value="'. $brokerslug .'" />
			<input type="hidden" name="similaryacht_type_filter" id="similaryacht_type_filter" value="'. $similaryacht_type_filter .'" />
			<input type="hidden" name="mostviewed" id="mostviewed" value="'. $mostviewed .'" />
			
			<input type="hidden" name="filterdisplay" id="filterdisplay" value="1" />
			</form>';
			
			global $boatwatcherclass;
			$returntext .= $boatwatcherclass->boat_watcher_form(array("formtemplate" => 1));
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				$(".main").off("click", ".filterby").on("click", ".filterby", function(){
					$(this).toggleClass("active");
					$(".boatfilterbyholder-in").toggle();
					$(document.body).trigger("sticky_kit:recalc");
				});
			});
			</script>
			';
			
		}elseif ($searchtemplate == 2){
			$form_argu = array(
				"formtype" => 3,
				"apinoselection" => $apinoselection,
				"owned" => $owned,
				"mfcname" => $mfcname,
				"conditionid" => $conditionid,
				"lnmin" => $lnmin,
				"lnmax" => $lnmax,
				"yrmin" => $yrmin,
				"yrmax" => $yrmax,
				"prmin" => $prmin,
				"prmax" => $prmax
			);
			$formdata = json_decode($this->boat_advanced_search_form_small($form_argu));
			$smallform = $formdata->smallform;
			
			if ($countryid == 1){
				$stateactiveval = $stateid;
			}else{
				$stateactiveval = $statename;
			}
			
			$returntext = '
			<form id="secrhfilter" name="secrhfilter" class="boatajaxform">
			<h3 class="ad-search"><span>Advanced search</span></h3>
			<div class="ad-search-con">
				<div class="search-container clearfixmain">
					<h4>Search By</h4>
					<div class="search-container-in clearfixmain">
					'. $smallform .'
					</div>
				</div>
			</div>
			
			<div class="spacertop clearfixmain">
			'. $this->yacht_featured_small(1) .'
			</div>
			';
			
			$returntext .= '			
			<input type="hidden" value="0" name="allmy" id="allmy" />
            <input type="hidden" value="" name="brokername" id="brokername" />
			
			<input type="hidden" name="conditionid" id="conditionid" value="'. $conditionid .'" />
			<input type="hidden" name="categoryid" id="categoryid" value="'. $categoryid .'" />
			<input type="hidden" name="typeid" id="typeid" value="'. $typeid .'" />
			<input type="hidden" name="enginetypeid" id="enginetypeid" value="'. $enginetypeid .'" />
			<input type="hidden" name="drivetypeid" id="drivetypeid" value="'. $drivetypeid .'" />
			<input type="hidden" name="fueltypeid" id="fueltypeid" value="'. $fueltypeid .'" />
			
			<input type="hidden" name="regshowid" id="regshowid" value="'. $regshowid .'" />
			<input type="hidden" name="featured" id="featured" value="'. $featured .'" />
			<input type="hidden" name="feacat" id="feacat" value="'. $feacat .'" />
			<input type="hidden" name="charter" id="charter" value="'. $charter .'" />
			<input type="hidden" name="boatstatus" id="boatstatus" value="'. $boatstatus .'" />
			<input type="hidden" name="tradein" id="tradein" value="'. $tradein .'" />
			<input type="hidden" name="uptoday" id="uptoday" value="'. $uptoday .'" />
			<input type="hidden" name="modelname" id="modelname" value="'. $modelname .'" />
			
			<input type="hidden" name="statename" id="statename" value="'. $statename .'" />
			<input type="hidden" name="stateid" id="stateid" value="'. $stateid .'" />
			<input type="hidden" name="countryid" id="countryid" value="'. $countryid .'" />
			<input type="hidden" name="brokerslug" id="brokerslug" value="'. $brokerslug .'" />
			
			<input type="hidden" name="sp_typeid" id="sp_typeid" value="'. $sp_typeid .'" />
			<input type="hidden" name="similaryacht_type_filter" id="similaryacht_type_filter" value="'. $similaryacht_type_filter .'" />
			<input type="hidden" name="mostviewed" id="mostviewed" value="'. $mostviewed .'" />
			
			<input type="hidden" name="filterdisplay" id="filterdisplay" value="2" />
			</form>';
		}else{
		
			if ($searchoption == 2){
				$extra_search_text = '
				<section class="section clearfixmain">
					<div><label class="com_none" for="allmy2">All Inventory</label><input class="radiobutton allmylisting" type="radio" value="2" id="allmy2" name="allmy" /><span class="formlabel">All Inventory</span></div>
					<div><label class="com_none" for="allmy1">My Listing</label><input class="radiobutton allmylisting" type="radio" value="1" id="allmy1" name="allmy" checked="checked" /><span class="formlabel">My Listing</span></div>
				</section>
				
				<section class="section com_none brokersearchdiv clearfixmain">
					<h3><label for="brokername">Broker Name :</label></h3>
					<div class="serach">
						<input type="text" id="brokername" name="brokername" value="" targetdiv="4" ckpage="4" class="azax_suggest azax_suggest4 input" autocomplete="off">
						<div id="suggestsearch4" class="suggestsearch suggestsearchspace com_none"></div>
						<input type="button" name="searchbk" id="searchbk" value="" class="submit">
					</div>
				</section>
				';
			}else{
				$extra_search_text = '			
				<input type="hidden" value="0" name="allmy" id="allmy" />
				<input type="hidden" value="" name="brokername" id="brokername" />
				';
			}
			
			$owned_field_text = '';
			//if ($apinoselection > 0 AND $rawtemplate == 1){
			if ($apinoselection == 0){				
				if ($owned == 1){
					$owned1 = ' checked="checked"';
					$owned2 = '';
					$owned3 = '';
				}elseif ($owned == 2){
					$owned1 = '';
					$owned2 = ' checked="checked"';
					$owned3 = '';
				}else{
					$owned1 = '';
					$owned2 = '';
					$owned3 = ' checked="checked"';
				}
				
				$owned_field_text = '
				<section class="section clearfixmain">
				<label class="com_none" for="ls_owned1">Our Listings</label>
				<label class="com_none" for="ls_owned2">Co-Brokerage</label>
				<label class="com_none" for="ls_owned3">All Listings</label>
				<input class="radiobutton ownedradio" type="radio" id="ls_owned1" name="ls_owned" value="1"'. $owned1 .' /> Our Listings<br />
				<input class="radiobutton ownedradio" type="radio" id="ls_owned2" name="ls_owned" value="2"'. $owned2 .' /> Co-Brokerage<br />
				<input class="radiobutton ownedradio" type="radio" id="ls_owned3" name="ls_owned" value="0"'. $owned3 .' /> All Listings
				</section>
				';
			}else{
				$owned_field_text = '<input type="hidden" name="owned" id="owned" value="'. $owned .'" />';
			}
			
			$excludesold_text = '';
			if ($dashboardinventory == 1){
				$excludesold_text = '
				<section class="section clearfixmain">
				<label class="com_none" for="dashboard_ex_sold">Exclude Sold</label>
				<input class="checkbox dashboardexsold" type="checkbox" id="dashboard_ex_sold" name="dashboard_ex_sold" value="1" /> Exclude Sold
				</section>
				';
			}else{
				$excludesold_text = '<input type="hidden" name="dashboard_ex_sold" id="dashboard_ex_sold" value="0" />';
			}
			
			$returntext = '
			<form id="secrhfilter" name="secrhfilter">
			<h3 class="ad-search"><span>Advanced search</span></h3>
			<div class="ad-search-con">
				'. $extra_search_text .'
				'. $owned_field_text .'
				'. $excludesold_text .'
				<section class="section clearfixmain">
					<h3><label for="mfcname">Manufacturer :</label></h3>
					<div class="serach clearfixmain">
						<input type="text" id="mfcname" name="mfcname" value="'. $mfcname .'" ckpage="5" class="azax_auto input" autocomplete="off">
						<button type="button" name="searchb" id="searchb" value="" class="submit" style="text-indent:-9999px;">Search</button>
					</div>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="lnmin">Length(ft) :</label><label class="com_none" for="lnmax">Length(ft) :</label></h3>
					<div class="left-side clearfixmain">
						<input id="lnmin" name="lnmin" type="text" value="'. $lnmin .'" placeholder="Min" class="serachinput lengthfield" autocomplete="off" />
					</div>
					<div class="right-side clearfixmain">
						<input id="lnmax" name="lnmax" type="text" value="'. $lnmax .'" placeholder="Max" class="serachinput lengthfield" autocomplete="off" />
					</div>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="prmin">Price($) :</label><label class="com_none" for="prmax">Price($) :</label></h3>
					<div class="left-side clearfixmain">
						<input id="prmin" name="prmin" type="text" value="'. $prmin .'" placeholder="Min" class="serachinput pricefield" autocomplete="off" />
					</div>
					<div class="right-side clearfixmain">
						<input id="prmax" name="prmax" type="text" value="'. $prmax .'" placeholder="Max" class="serachinput pricefield" autocomplete="off" />
					</div>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="yrmin">Year :</label><label class="com_none" for="yrmax">Year :</label></h3>
					<div class="left-side clearfixmain">
						<select class="my-dropdown2" id="yrmin" name="yrmin">
							<option value="0" selected="selected">Min</option>
							'. $this->get_year_combo($yrmin, 1) .'
						</select>
					</div>
					<div class="right-side clearfixmain">
						<select class="my-dropdown2" id="yrmax" name="yrmax">
							<option  value="0" selected="selected">Max</option>
							'. $this->get_year_combo($yrmax, 1) .'
						</select>
					</div>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="conditionid">Condition :</label></h3>
					<select class="my-dropdown2" name="conditionid" id="conditionid">
						<option value="0" selected="selected">All</option>
						'. $this->get_condition_combo($conditionid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="categoryid">Category :</label></h3>
					<select class="my-dropdown2 catupdate" targetcombo="typeid" name="categoryid" id="categoryid">
						<option selected="selected">All</option>
						'. $this->get_category_combo($categoryid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="typeid">Boat Type :</label></h3>
					<select class="my-dropdown2" name="typeid" id="typeid">
						<option selected="selected">All</option>
						'. $this->get_type_combo_parent($typeid, $categoryid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="enginetypeid">Engine Type :</label></h3>
					<select class="my-dropdown2" name="enginetypeid" id="enginetypeid">
						<option selected="selected">All</option>
						'. $this->get_engine_type_combo($enginetypeid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="drivetypeid">Drive Type :</label></h3>
					<select class="my-dropdown2" name="drivetypeid" id="drivetypeid">
						<option selected="selected">All</option>
						'. $this->get_drive_type_combo($drivetypeid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="fueltypeid">Fuel Type :</label></h3>
					<select class="my-dropdown2" name="fueltypeid" id="fueltypeid">
						<option selected="selected">All</option>
						'. $this->get_fuel_type_combo($fueltypeid, 0, 1) .'
					</select>
				</section>
				
				<section class="section clearfixmain">
					<h3><label for="stateid">US State :</label></h3>
					<select class="my-dropdown2" name="stateid" id="stateid">
						<option selected="selected">All</option>
						'. $this->get_state_combo($stateid, 1) .'
					</select>
				</section>				
			</div>		
			<input type="hidden" name="regshowid" id="regshowid" value="'. $regshowid .'" />
			<input type="hidden" name="featured" id="featured" value="'. $featured .'" />
			<input type="hidden" name="charter" id="charter" value="'. $charter .'" />
			<input type="hidden" name="boatstatus" id="boatstatus" value="'. $boatstatus .'" />
			<input type="hidden" name="tradein" id="tradein" value="'. $tradein .'" />
			<input type="hidden" name="uptoday" id="uptoday" value="'. $uptoday .'" />
			<input type="hidden" name="filterdisplay" id="filterdisplay" value="0" />
			<input type="hidden" name="modelname" id="modelname" value="'. $modelname .'" />
			<input type="hidden" name="statename" id="statename" value="'. $statename .'" />
			<input type="hidden" name="countryid" id="countryid" value="'. $countryid .'" />
			<input type="hidden" name="sp_typeid" id="sp_typeid" value="'. $sp_typeid .'" />
			<input type="hidden" name="similaryacht_type_filter" id="similaryacht_type_filter" value="'. $similaryacht_type_filter .'" />
			<input type="hidden" name="mostviewed" id="mostviewed" value="'. $mostviewed .'" />
			</form>
			';
		}
		
		return $returntext;		
	}
	//end
	
	//exclude part from SQL
	public function remove_string_from_sql($sql){
		if (strpos($sql, ', sum(mv.total_view) as total_view_boat') !== false){
			$sql = str_replace(", sum(mv.total_view) as total_view_boat", "", $sql);
		}
		
		if (strpos($sql, 'GROUP BY a.id') !== false){
			$sql = str_replace("GROUP BY a.id", "", $sql);
		}
		
		return $sql;
	}
	
	//category list - filter
	public function get_category_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end
			
		if ($sql != ""){
			$sql = $this->remove_string_from_sql($sql);			
			$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name, count(distinct a.id) as total from tbl_yacht as a, tbl_category as sa", $sql);
			$sql .= " and sa.id = a.category_id group by sa.id order by total desc";
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$returntext .= '				
				<h6>Type</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($id == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="categoryid" tval="0" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="categoryid" tval="'. $id .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $name .'</a>'. $righttext .'</li>';
				}
				
				$returntext .= '
				</ul>
				';
			}			
		}
		return $returntext;
	}
	
	//condition list - filter
	public function get_condition_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end
		
		if ($sql != ""){
			$sql = $this->remove_string_from_sql($sql);
			$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name, count(distinct a.id) as total from tbl_yacht as a, tbl_condition as sa", $sql);
			$sql .= " and sa.id = a.condition_id group by sa.id order by total desc";
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$returntext .= '				
				<h6>Condition</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($id == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="conditionid" tval="0" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="conditionid" tval="'. $id .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $name .'</a>'. $righttext .'</li>';
				}
				
				$returntext .= '
				</ul>
				';
			}			
		}	
		return $returntext;
	}
	
	//boat type list - filter
	public function get_boattype_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end
		
		if ($sql != ""){
			$sql = $this->remove_string_from_sql($sql);
			if (strpos($sql, 'tbl_yacht_type_assign as d') === false){
				$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name, count(distinct a.id) as total from tbl_yacht as a, tbl_type as sa, tbl_yacht_type_assign as d", $sql);
				$sql .= " and a.id = d.yacht_id and sa.id = d.type_id group by sa.id order by total desc";
			}else{
				$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name, count(distinct a.id) as total from tbl_yacht as a, tbl_type as sa", $sql);
				$sql .= " and sa.id = d.type_id group by sa.id order by total desc";
			}
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			$counter = 0;
			if ($found > 0){
				$returntext .= '				
				<h6>Class</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($id == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="typeid" tval="0" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="typeid" tval="'. $id .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $name .'</a>'. $righttext .'</li>';
				
					if ($small_list == 1){
						$counter++;					
						if ($counter == $limit){
							break;
						}
					}
				}
				
				if ($small_list == 1 AND $found > $limit){
					$returntext .= '<li class="clearfixmain"><a tdiv="boattype_full" class="filtersearchlist filtermore" href="javascript:void(0);">More...</a></li>';
				}
				
				$returntext .= '
				</ul>
				';
			}			
		}	
		return $returntext;
	}
	
	//make list - filter
	public function get_make_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end
		
		if ($sql != ""){
			$sql = $this->remove_string_from_sql($sql);
			$sql = str_replace("select distinct a.*", "select distinct b.id, b.name, count(distinct a.id) as total", $sql);
			$sql .= " group by b.id order by total desc";
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			$counter = 0;
			if ($found > 0){
				$returntext .= '				
				<h6>Make</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($name == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="mfcname" tval="" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="mfcname" tval="'. $name .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $name .'</a>'. $righttext .'</li>';
					
					if ($small_list == 1){
						$counter++;					
						if ($counter == $limit){
							break;
						}
					}
				}
				
				if ($small_list == 1 AND $found > $limit){
					$returntext .= '<li class="clearfixmain"><a tdiv="make_full" class="filtersearchlist filtermore" href="javascript:void(0);">More...</a></li>';
				}
				
				$returntext .= '
				</ul>
				';
			}			
		}	
		return $returntext;
	}
	
	//model list - filter
	public function get_model_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$mfcname = $param["mfcname"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end		
		
		if ($sql != "" AND $mfcname != ""){
			$sql = $this->remove_string_from_sql($sql);
			$sql = str_replace("select distinct a.*", "select distinct a.model, a.model_slug, count(distinct a.id) as total", $sql);
			$sql .= " group by a.model_slug order by total desc";
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			$counter = 0;
			if ($found > 0){
				$returntext .= '
				<h6>Model</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($model_slug == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="modelname" tval="" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="modelname" tval="'. $model_slug .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $model .'</a>'. $righttext .'</li>';
				
					if ($small_list == 1){
						$counter++;					
						if ($counter == $limit){
							break;
						}
					}
				}
				
				if ($small_list == 1 AND $found > $limit){
					$returntext .= '<li class="clearfixmain"><a tdiv="model_full" class="filtersearchlist filtermore" href="javascript:void(0);">More...</a></li>';
				}
				
				$returntext .= '
				</ul>
				';				
			}			
		}
		return $returntext;
	}
	
	//country list - filter
	public function get_country_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end
		
		if ($sql != ""){
			$sql = $this->remove_string_from_sql($sql);
			$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name, count(distinct a.id) as total from tbl_yacht as a, tbl_country as sa", $sql);
			$sql .= " and sa.id = a.country_id group by sa.id order by total desc";
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			$counter = 0;
			if ($found > 0){
				$returntext .= '				
				<h6>Location</h6>
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($id == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="countryid" tval="0" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="countryid" tval="'. $id .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $name .'</a>'. $righttext .'</li>';
				
					if ($small_list == 1){
						$counter++;					
						if ($counter == $limit){
							break;
						}
					}
				}
				
				if ($small_list == 1 AND $found > $limit){
					$returntext .= '<li class="clearfixmain"><a tdiv="country_full" class="filtersearchlist filtermore" href="javascript:void(0);">More...</a></li>';
				}
				
				$returntext .= '
				</ul>
				';
			}			
		}	
		return $returntext;
	}
	
	//state list - filter
	public function get_state_list_filter($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param
		$sql = $param["sql"];
		$countryid = $param["countryid"];
		$activeval = $param["activeval"];
		$small_list = $param["small_list"];
		//end		
		
		if ($sql != "" AND $countryid > 0){
			$sql = $this->remove_string_from_sql($sql);
			if ($countryid == 1){
				$tfield = "stateid";
				$sql = str_replace("select distinct a.* from tbl_yacht as a", "select distinct sa.id, sa.name as statename, count(distinct a.id) as total from tbl_yacht as a, tbl_state as sa", $sql);
				$sql .= " and sa.id = a.state_id group by sa.id order by total desc";
			}else{
				$tfield = "statename";
				$sql = str_replace("select distinct a.*", "select distinct a.state as statename, count(distinct a.id) as total", $sql);
				$sql .= " and a.state != '' group by a.state order by total desc";
			}
			
			if ($small_list == 1){
				$limit = $this->list_filter_small;
				$sql .= " limit 0, " . ($limit + 1);
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			$counter = 0;
			if ($found > 0){
				$returntext .= '
				<ul class="filtersection-list">
				';
				
				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
						
						if ($countryid == 1){
							$tval = $id;
						}else{
							$tval = $statename;
						}
						
						if ($tval == $activeval){
							$righttext = '<div class="filter-remove"><a tfield="stateid" tval="" class="filterwork" href="javascript:void(0);"><i class="fa fa-times" aria-hidden="true"></i><span class="com_none">Remove</span></a></div>';
						}else{
							$righttext = '<div class="filter-no"> ('. $total .')</div>';
						}
					}
			
					$returntext .= '<li class="clearfixmain"><a tfield="'. $tfield .'" tval="'. $tval .'" class="filtersearchlist filterwork" href="javascript:void(0);">'. $statename .'</a>'. $righttext .'</li>';
				
					if ($small_list == 1){
						$counter++;					
						if ($counter == $limit){
							break;
						}
					}
				}
				
				if ($small_list == 1 AND $found > $limit){
					$returntext .= '<li class="clearfixmain"><a tdiv="model_full" class="filtersearchlist filtermore" href="javascript:void(0);">More...</a></li>';
				}
				
				$returntext .= '
				</ul>
				';				
			}			
		}
		return $returntext;
	}
	
	//Advanced search form
	public function get_advanced_search_post_url(){
		global $cm;
		/*$our_page_id = $cm->get_page_id_by_shortcode("[fcboatlist owned=1");
		$post_url = $cm->get_page_url($our_page_id, "page");
		
		$co_broker_page_id = $cm->get_page_id_by_shortcode("[fcboatlist owned=2");
		$post_url2 = $cm->get_page_url($co_broker_page_id, "page");*/
		
		$our_page_id_yacht = $cm->get_page_id_by_shortcode("[fcboatlist owned=1 sp_typeid=1");
		$post_url_yacht = $cm->get_page_url($our_page_id_yacht, "page");
		
		$co_broker_page_id_yacht = $cm->get_page_id_by_shortcode("[fcboatlist owned=2 sp_typeid=1");
		$post_url2_yacht = $cm->get_page_url($co_broker_page_id_yacht, "page");
		
		$our_page_id_catamaran = $cm->get_page_id_by_shortcode("[fcboatlist owned=1 sp_typeid=2");
		$post_url_catamaran = $cm->get_page_url($our_page_id_catamaran, "page");
		
		$co_broker_page_id_catamaran = $cm->get_page_id_by_shortcode("[fcboatlist owned=2 sp_typeid=2");
		$post_url2_catamaran = $cm->get_page_url($co_broker_page_id_catamaran, "page");
		
		$returnar = array(
			"our_page_id_yacht" => $our_page_id_yacht,
			"co_broker_page_id_yacht" => $co_broker_page_id_yacht,
			"post_url_yacht" => $post_url_yacht,
			"post_url2_yacht" => $post_url2_yacht,
			
			"our_page_id_catamaran" => $our_page_id_catamaran,
			"co_broker_page_id_catamaran" => $co_broker_page_id_catamaran,
			"post_url_catamaran" => $post_url_catamaran,
			"post_url2_catamaran" => $post_url2_catamaran
		);
		
		return json_encode($returnar);
	}
	
	public function get_custom_list_page_url(){
		global $cm;
		$custom_page_id = $cm->get_page_id_by_shortcode("[fcboatlist categoryid=1");
		$custom_page_url1 = $cm->get_page_url($custom_page_id, "page");

		$custom_page_id = $cm->get_page_id_by_shortcode("[fcboatlist categoryid=2");
		$custom_page_url2 = $cm->get_page_url($custom_page_id, "page");

		$custom_page_id = $cm->get_page_id_by_shortcode("[fcboatlist typeid=9");
		$custom_page_url3 = $cm->get_page_url($custom_page_id, "page");

		$returnar = array(
			"custom_page_url1" => $custom_page_url1,
			"custom_page_url2" => $custom_page_url2,
			"custom_page_url3" => $custom_page_url3
		);
		
		return json_encode($returnar);
	}
	
	public function display_boat_advanced_search_form($param = array()){
		global $db, $cm;
		//$this->remove_yach_search_var();
		
		$formpostar = json_decode($this->get_advanced_search_post_url());		
		$our_page_id_yacht = $formpostar->our_page_id_yacht;
		$post_url_yacht = $formpostar->post_url_yacht;
		$co_broker_page_id_yacht = $formpostar->co_broker_page_id_yacht;
		$post_url2_yacht = $formpostar->post_url2_yacht;
		
		$our_page_id_catamaran = $formpostar->our_page_id_catamaran;
		$post_url_catamaran = $formpostar->post_url_catamaran;
		$co_broker_page_id_catamaran = $formpostar->co_broker_page_id_catamaran;
		$post_url2_catamaran = $formpostar->post_url2_catamaran;
		
		$returntxt = '
		<form method="get" action="'. $post_url2_yacht .'" id="adv_ff" name="ff">
		
		<div class="singleblock clearfixmain">
			<div class="singleblock_box clearfixmain">
			<ul class="form">
				<li class="left">
					<p><label for="ad_mfcname">Manufacturer</label></p>
					<input type="text" id="ad_mfcname" name="mfcname" class="input" />
				</li>
				<li class="right">
					<p><label for="ad_prmin">Price</label><label class="com_none" for="ad_prmax">Price</label></p>
					<div class="left-side"><input type="text" id="ad_prmin" name="prmin" class="input" placeholder="Min" /></div>
					<div class="right-side"><input type="text" id="ad_prmax" name="prmax" class="input" placeholder="Max" /></div>
				</li>
			
				<li class="left">
					<p><label for="ad_yrmin">Year</label><label class="com_none" for="ad_yrmax">Year</label></p>
					<div class="left-side clearfixmain">
						<select class="my-dropdown2" id="ad_yrmin" name="yrmin">
							<option selected>Min</option>
							'. $this->get_year_combo(0, 1) .'
						</select>
					</div>
					<div class="right-side clearfixmain">
						<select class="my-dropdown2" id="ad_yrmax" name="yrmax">
							<option  selected="selected">Max</option>
							'. $this->get_year_combo(0, 1) .'
						</select>
					</div>
				</li>
				<li class="right">
					<p><label for="ad_lnmin">Length</label><label class="com_none" for="ad_lnmax">Length</label></p>
					<div class="left-side"><input type="text" id="ad_lnmin" name="lnmin" class="input" placeholder="Min" /></div>
					<div class="right-side"><input type="text" id="ad_lnmax" name="lnmax" class="input" placeholder="Max" /></div>
				</li>
			
				<li class="left">
					<p><label for="ad_conditionid">Condition</label></p>
					<select class="my-dropdown2" name="conditionid" id="ad_conditionid">
						<option value="0" selected="selected">All</option>
						'. $this->get_condition_combo(0, 0, 1) .'
					</select>
				</li>
				<li class="right">
					<p><label for="ad_categoryid">Category</label></p>
					<select class="my-dropdown2 catupdate" targetcombo="typeid" name="categoryid" id="ad_categoryid">
						<option selected="selected">All</option>
						'. $this->get_category_combo(0, 0, 1) .'
					</select>
				</li>
			
				<li class="left">
					<p><label for="ad_enginetypeid">Engine Type</label></p>
					<select class="my-dropdown2" name="enginetypeid" id="ad_enginetypeid">
						<option selected="selected">All</option>
						'. $this->get_engine_type_combo(0, 0, 1) .'
					</select>
				</li>
				<li class="right">
					<p><label for="ad_drivetypeid">Drive Type</label></p>
					<select class="my-dropdown2" name="drivetypeid" id="ad_drivetypeid">
						<option selected="selected">All</option>
						'. $this->get_drive_type_combo(0, 0, 1) .'
					</select>
				</li>
			
				<li class="left">
					<p><label for="ad_fueltypeid">Fuel Type</label></p>
					<select class="my-dropdown2" name="fueltypeid" id="ad_fueltypeid">
						<option selected="selected">All</option>
						'. $this->get_fuel_type_combo(0, 0, 1) .'
					</select>
				</li>
				<li class="right">
					<p><label for="ad_stateid">US State</label></p>
					<select class="my-dropdown2" name="stateid" id="ad_stateid">
						<option selected="selected">All</option>
						'. $this->get_state_combo(0, 1, 1) .'
					</select>
				</li>
				
				<li class="left">
					<p><label for="ad_typeid">Boat Type</label></p>
					<select class="my-dropdown2 toplevelcat-x" name="typeid" id="ad_typeid">
						<option selected="selected" value="0">All</option>
						'. $this->get_type_combo_parent($typeid, $categoryid, 0, 1) .'
					</select>
				</li>
				<li class="right">
					<p>Search Type</p>
					<label class="com_none" for="ad_sp_typeid1">Yachts</label>
					<label class="com_none" for="ad_sp_typeid2">Catamaran</label>
					<input class="setformaction2 radiobutton" type="radio" id="ad_sp_typeid1" name="sp_typeid" value="1" checked="checked" /> Yachts
					<input class="setformaction2 radiobutton radiobutton_next" type="radio" id="ad_sp_typeid2" name="sp_typeid" value="2" /> Catamaran
				</li>
				
				<li>
					<p>Search In</p>
					<label class="com_none" for="ad_owned1">Our Listings</label>
					<label class="com_none" for="ad_owned2">Co-Brokerage</label>
					<input p="'. $post_url_yacht .'" pid="'. $our_page_id_yacht .'" p2="'. $post_url_catamaran .'" pid2="'. $our_page_id_catamaran .'" class="setformaction2 radiobutton" type="radio" id="ad_owned1" name="owned" value="1" /> Our Listings
					<input p="'. $post_url2_yacht .'" pid="'. $co_broker_page_id_yacht . '" p2="'. $post_url2_catamaran .'" pid2="'. $co_broker_page_id_catamaran .'" class="setformaction2 radiobutton radiobutton_next" type="radio" id="ad_owned2" name="owned" value="2" checked="checked" /> Co-Brokerage
				</li>
			</ul>			
			</div>
		</div>
		
		<div class="singleblock"><input type="submit" value="Search" class="button" /></div>
		<input type="hidden" name="freshstart" value="1">
		<input type="hidden" name="rawtemplate" value="0">
		<input type="hidden" id="setpg2" name="setpg" value="'. $co_broker_page_id_yacht .'">
		</form>
		';
		
		$returntxt .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$(".setformaction2").click(function(){				
				var selected_opt = parseInt($("#adv_ff input[name=owned]:radio:checked").val());
				var sp_typeid_opt = parseInt($("#adv_ff input[name=sp_typeid]:radio:checked").val());
				
				if (sp_typeid_opt == 2){
					var formp = $("#adv_ff input[name=owned]:radio:checked").attr("p2");	
					var pid = $("#adv_ff input[name=owned]:radio:checked").attr("pid2");
				}else{
					var formp = $("#adv_ff input[name=owned]:radio:checked").attr("p");	
					var pid = $("#adv_ff input[name=owned]:radio:checked").attr("pid");
				}
				
				$("#adv_ff #setpg").val(pid);
				$("#adv_ff").attr("action", formp);

				//remove session storage if any
				remove_session_storage();
			});
		});
		</script>
		';
		
		return $returntxt;
	}
	//end
	
	
	//Print Inventory
	public function get_print_inventory_header($include_broker, $list_broker_id = 0, $printoption = 0){
		global $db, $cm;
		$returntxt = '';
		
		if ($include_broker == 2){
			$broker_id = $list_broker_id;
		}else{
			$broker_id = $this->loggedin_member_id();
		}
		
		//compnay information
		$company_id = $this->get_broker_company_id($broker_id);		
		$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
        $cname = $company_ar[0]["cname"];
        $logo_imgpath = $company_ar[0]["logo_imgpath"];		
		
		$compnay_location_id = $this->get_company_default_location($company_id);
		$compnay_location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone', $compnay_location_id);		       
		$compnay_address = $compnay_location_ar[0]['address'];
		$compnay_city = $compnay_location_ar[0]['city'];
		$compnay_state = $compnay_location_ar[0]['state'];
		$compnay_state_id = $compnay_location_ar[0]["state_id"];
		$compnay_country_id = $compnay_location_ar[0]["country_id"];
		$compnay_zip = $compnay_location_ar[0]["zip"];
		$compnay_phone = $compnay_location_ar[0]["phone"];				
		$compnay_addressfull = $this->com_address_format('', $compnay_city, $compnay_state, $compnay_state_id, $compnay_country_id);
		
		//broker information
		$broker_ar = $cm->get_table_fields('tbl_user', 'email, fname, lname, phone', $broker_id);
        $fname = $broker_ar[0]["fname"];
        $lname = $broker_ar[0]["lname"];
		$phone = $broker_ar[0]["phone"];
		$brokeremail = $broker_ar[0]["email"];
		
		$broker_ad_ar = $this->get_broker_address_array($broker_id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"];
        
        $member_image = $this->get_user_image($broker_id);        
        $b_addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
		
		if ($include_broker == 3){
			if ($logo_imgpath != ""){
				$logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="" style="max-height: 65px;">';
			}
			
			$returntxt = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>						
					<td style="padding: 4px;" width="22%" align="center" valign="middle">
					'. $logo_imgpath .'				
					</td>
					
					<td style="padding: 4px;" width="30%" align="center" valign="middle">&nbsp;</td>
					
					<td style="padding: 4px; font-weight: normal; color: #4c4c4c; font-family: Arial; font-size: 15px;" width="46%" align="right" valign="middle">
					<strong>'. $cname .'</strong><br />
					'. $compnay_address .'<br />'. $compnay_addressfull .'<br />';
					if ($compnay_phone != ""){ $returntxt .= 'Phone: '. $compnay_phone .'<br>'; }				
					$returntxt .= '</td>
					<td width="2%" align="center" valign="middle">&nbsp;</td>
				</tr>
			</table>
			';
		}elseif ($include_broker == 1 OR $include_broker == 2){
			if ($printoption == 4){
				if ($logo_imgpath != ""){
					$logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="" style="max-height: 40px;">';
				}
				
				$returntxt = '				
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td class="cellpadding cellborder1" width="20%" align="center" valign="middle">
						'. $logo_imgpath .'				
						</td>
						
						<td style="padding: 4px; font-weight: normal; color: #4c4c4c; font-family: Arial; font-size: 11px;" width="60%" align="right" valign="middle">
						<strong>'. $fname .' '. $lname .'</strong><br />';
						if ($phone != ""){ $returntxt .= 'Mobile: '. $phone .'<br>'; }			
						if ($officephone != ""){ $returntxt .= 'Work: '. $officephone .'<br />'; }
						$returntxt .= 'Email: <a href="mailto:'. $brokeremail .'">'. $brokeremail .'</a><br />';				
						$returntxt .= '</td>
								
						<td style="padding: 4px;" width="13%" align="center" valign="middle">
						<img src="'. $cm->site_url .'/userphoto/'. $member_image .'" alt="">			
						</td>
					</tr>
				</table>
				';
			}else{
				if ($logo_imgpath != ""){
					$logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="" style="max-height: 65px;">';
				}
				
				$returntxt = '
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>						
						<td style="padding: 4px;" width="14%" align="center" valign="middle">
						'. $logo_imgpath .'				
						</td>
						<td style="padding: 4px; font-weight: normal; color: #4c4c4c; font-family: Arial; font-size: 13px;" width="36%" align="left" valign="middle">
						<strong>'. $cname .'</strong><br />
						'. $address .'<br />
						'. $b_addressfull .'				
						</td>
						<td style="padding: 4px; font-weight: normal; color: #4c4c4c; font-family: Arial; font-size: 13px;" width="40%" align="right" valign="middle">
						<strong>'. $fname .' '. $lname .'</strong><br />';
						if ($phone != ""){ $returntxt .= 'Mobile: '. $phone .'<br>'; }			
						if ($officephone != ""){ $returntxt .= 'Work: '. $officephone .'<br />'; }
						$returntxt .= 'Email: <a href="mailto:'. $brokeremail .'">'. $brokeremail .'</a><br />';				
						$returntxt .= '</td>						
						<td style="padding: 4px;" width="10%" align="center" valign="middle">
						<img src="'. $cm->site_url .'/userphoto/'. $member_image .'" alt="">			
						</td>
					</tr>
				</table>
				';
			}
			
		}else{
			if ($logo_imgpath != ""){
				$logo_imgpath = '<img src="'. $cm->site_url .'/userphoto/'. $logo_imgpath .'" alt="" style="max-height: 65px;">';
			}
			
			$returntxt = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="padding: 4px;" width="14%" align="left" valign="middle">
					'. $logo_imgpath .'				
					</td>
					
					<td style="padding: 4px; font-weight: bold; color: #4c4c4c; font-family: Arial; font-size: 18px;" width="84%" align="right" valign="middle">
					'. $cname .'		
					</td>
					<td style="padding: 4px;" width="2%" align="center" valign="middle">&nbsp;</td>
				</tr>
			</table>
			';
		}			
		return $returntxt;
	}
	
	public function display_print_inventory($row, $printoption, $include_broker){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
        $returntxt = '';
		
		$defaultheading = ' font-family: Arial; font-size: 15px; text-align:left; text-decoration: none; text-transform:uppercase;';
		$defaultfontcss = ' font-family: Arial; font-size: 13px; color:#4c4c4c; text-align:left; text-decoration: none;';
		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		$yacht_title = $this->yacht_name($id);
		$addressfull = $this->get_yacht_address($id);
		$company_url = $cm->get_common_field_name('tbl_company', 'website_url', $company_id);
		
		//Dimensions & Weight
        $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
        }
		
		//Engine
        $ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
        }

        //Tank Capacities
        $ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($id) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
        }

        //Accommodations
        $ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($id) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = htmlspecialchars($val);
        }
		
		$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
		$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
		$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
        $drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
        $fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);
		
		$ppath = $this->get_yacht_first_image($id);
        $imagefolder = 'yachtimage/'. $listing_no .'/slider/';
		$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);		
		
		if ($printoption == 1){			
			$invheadingtdrow = 'background-color: #fff; padding: 4px; border-bottom: 1px solid #000; font-family: Arial; font-size: 13px; color:#4c4c4c;';
			$returntxt .= '				
			<tr>
				<td style="'. $invheadingtdrow .'" width="" align="center"><img style="max-width: 110px;" src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt="" /></td>
				<td style="'. $invheadingtdrow .'" width="" align="left">'. $this->display_yacht_number_field($length, 1, 2) .'</td>
				<td style="'. $invheadingtdrow .'" width="" align="left">'. $manufacturer_name .'<br />'. $model .'</td>
				<td style="'. $invheadingtdrow .'" width="" align="center">'. $year .'</td>
				<td style="'. $invheadingtdrow .'" width="" align="left">'. $engine_make_name .'<br />'. $engine_model .' - '. $this->display_yacht_number_field($engine_no) .'</td>
				<td style="'. $invheadingtdrow .'" width="" align="center">'. $price_display .'</td>
				<td style="'. $invheadingtdrow .'" width="" align="center">'. $addressfull .'</td>
			</tr>
			';
		}
		
		if ($printoption == 2 OR $printoption == 3 OR $printoption == 4){
			$returntxt .= $this->get_print_inventory_header($include_broker, $broker_id, $printoption);
		}
		
		if ($printoption == 2 OR $printoption == 3){			
			$invphotoholder = " text-align: right;";
			$invphotoholderdiv = "float: right; padding: 0px 5px 5px 5px; margin: 0px; width: 15%;";
			if ($printoption == 3){ 
				$invphotoholder = " text-align: left;"; 
				$invphotoholderdiv = "float: left; width: 30%; padding: 0px 5px 2px 5px;";
			}
			
			$sqlp = "select * from tbl_yacht_photo where yacht_id = '". $id ."' and imgpath != '' and status_id = 1 order by rank limit 0, 6";
        	$result = $db->fetch_all_array($sqlp);
			$photo_txt = '<div style="width: 100%;'. $invphotoholder .'">';
			foreach($result as $row){
				$imgpath  = $row['imgpath'];
				$photo_txt .= '<div style="'. $invphotoholderdiv .'"><img src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/'. $imgpath .'" alt="" /></div>';
			}
			$photo_txt .= '</div>';
		}
		
		if ($printoption == 2){
			$invheading = "margin-top: 3px;";
			$invheadingtd = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 15px; font-family: Arial;";
			$invheadingtd2 = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 20px; font-family: Arial;";	
			$invheadingtdrow2 = "background-color: #fff; padding: 1px; color: #4c4c4c; font-size: 13px; font-family: Arial;";
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd2 .'" align="center">'. $yacht_title .'</td>
				</tr>
				
				<tr>
                    <td style="text-align:center;" valign="top"><img src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></td>
                </tr>
			</table>
			'. $photo_txt .'		
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">VESSEL INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $manufacturer_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Listing Price:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%"><strong>'. $price_display .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $model .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Length:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($length, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Year:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $year .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Beam:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($beam, 1, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Location:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $addressfull .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Draft:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($draft, 1, 1) .'</td>
				</tr>
			</table>
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">ENGINE INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $engine_make_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Number of Engines:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%"><strong>'. $this->display_yacht_number_field($engine_no) .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_model .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Horsepower:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Engine Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Hours:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($hours) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Drive Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $drive_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Cruise Speed:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Fuel Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $fuel_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
			</table>
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">DESCRIPTION</td>
				</tr>
				<tr>
					<td style="'. $invheadingtdrow2 .'" align="left">'. $overview .'</td>
				</tr>
			</table>			
			';
			
			if ($company_url != ""){
				$returntxt .= '
				<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td style="'. $invheadingtd .'" align="center">'. $company_url .'</td>
					</tr>
				</table>
				';
			}			
		}
		
		if ($printoption == 3){
			$invheading = "margin-top: 2px;";
			$invheadingtd = "background-color: #4f5660; padding: 1px 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 13px; font-family: Arial;";
			$invheadingtd2 = "background-color: #4f5660; padding: 1px 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 16px; font-family: Arial;";	
			$invheadingtdrow2 = "background-color: #fff; padding: 1px; color: #4c4c4c; font-size: 12px; font-family: Arial; line-height: 12px;";
					
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd2 .'" align="center" colspan="2">'. $yacht_title .'</td>
				</tr>
			</table>
			
			<div style="width: 100%">
				<div style="width: 55%; float: left; padding: 0 0 0 2px;"><img style="height: 145px; width: 100% !important;" src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></div>
				<div style="width: 43%; float: right; padding: 0 2px 0 0;">'. $photo_txt .'</div>
			</div>		
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">VESSEL INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="5%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%">'. $manufacturer_name .'</td>	
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="4%">&nbsp;</td>				

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Listing Price:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%"><strong>'. $price_display .'</strong></td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="5%">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $model .'</td>	
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>				

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Length:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($length, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Year:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $year .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Beam:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($beam, 1, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Location:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $addressfull .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Draft:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($draft, 1, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
			</table>
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">ENGINE INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="5%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%">'. $engine_make_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="4%">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Number of Engines:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%"><strong>'. $this->display_yacht_number_field($engine_no) .'</strong></td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="5%">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_model .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Horsepower:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Engine Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Hours:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($hours) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Drive Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $drive_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Cruise Speed:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Fuel Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $fuel_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>
				</tr>
			</table>
			';
			
			if ($company_url != ""){
				$returntxt .= '
				<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td style="'. $invheadingtd .'" align="center">'. $company_url .'</td>
					</tr>
				</table>
				';
			}
		}
		
		if ($printoption == 4){
			$invheading = "margin-top: 3px;";
			$invheadingtd = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 13px; font-family: Arial;";
			$invheadingtd2 = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 16px; font-family: Arial;";	
			$invheadingtdrow2 = "background-color: #fff; padding: 1px; color: #4c4c4c; font-size: 12px; font-family: Arial;";
					
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd2 .'" align="center">'. $yacht_title .'</td>
				</tr>				
				<tr>
                    <td style="text-align: center;" valign="top"><img src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></td>
                </tr>
			</table>		
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="width:100%; '. $invheadingtd .'" align="left">VESSEL INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="20%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%">'. $manufacturer_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="8%">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="20%">Listing Price:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%"><strong>'. $price_display .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $model .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Length:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($length, 1, 2) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Year:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $year .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Beam:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($beam, 1, 2) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Location:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $addressfull .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Draft:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($draft, 1, 2) .'</td>
				</tr>
			</table>
			';
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">ENGINE INFORMATION</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="20%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%">'. $engine_make_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="8%">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="20%"># Engines:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="26%"><strong>'. $this->display_yacht_number_field($engine_no) .'</strong></td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_model .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Hours:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($hours) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Engine Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $engine_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Cruise Speed:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Drive Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $drive_type_name .'</td>
					
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">&nbsp;</td>

					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Fuel Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">'. $fuel_type_name .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="">Horsepower:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="" colspan="3">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>					
				</tr>
			</table>
			';	
			
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" align="left">DESCRIPTION</td>
				</tr>
				<tr>
					<td style="'. $invheadingtdrow2 .'" align="left">'. $overview .'</td>
				</tr>
			</table>			
			';		
			
			if ($company_url != ""){
				$company_url = str_replace("http://", "", $company_url);
				$company_url = str_replace("https://", "", $company_url);
				$returntxt .= '
				<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-top: 1px solid #999; color: #4c4c4c; font-family: Arial; font-size: 12px;" align="center">'. $company_url .'</td>
					</tr>
				</table>
				';
			}			
		}
		
		if ($printoption == 5){
			
			$invheading = "margin-top: 3px;";
			$invheadingsubhead = "color:#4c4c4c; font-size: 20px; font-weight: bold; font-family: Arial;";
			$invheadingfull = "border-top: 1px solid #dedcdc; padding: 10px 5px 5px 0px; text-align:center; color:#4c4c4c; font-size: 25px; font-weight: bold; font-family: Arial;";
			$invheadingsubheadmedium = "color:#4c4c4c; font-size: 18px; font-family: Arial; font-weight: bold;";
			
			$invheadingtd = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 15px; font-family: Arial;";
			$invheadingtd2 = "background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-size: 20px; font-family: Arial;";	
			$invheadingtdrow2 = "background-color: #fff; padding: 1px; color: #4c4c4c; font-size: 14px; font-family: Arial;";
			
			$sqlp = "select * from tbl_yacht_photo where yacht_id = '". $id ."' and imgpath != '' and status_id = 1 order by rank";
        	$result = $db->fetch_all_array($sqlp);
			$photo_thumb_txt = '<p style="page-break-before:always;"></p>
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingsubhead .'" align="left">Gallery</td>
				</tr>
			</table>
			<div style="width: 100%; text-align: left; padding-top: 8px;">';
			foreach($result as $row){
				$imgpath  = $row['imgpath'];
				$photo_thumb_txt .= '<div style="float: left; padding: 0px 5px 5px 5px; margin: 0px; width: 15%;"><img src="'. $cm->site_url .'/yachtimage/'. $listing_no .'/'. $imgpath .'" alt="" /></div>';
			}
			$photo_thumb_txt .= '</div>';
			
			$imagefolder = 'yachtimage/'. $listing_no .'/slider/';
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="3" style="'. $invheadingfull .'" align="center">'. $yacht_title .'</td>
				</tr>
				
				<tr>
                    <td align="left" valign="top" style="color: #4c4c4c; font-weight: normal; font-size: 14px; font-family: Arial;">Boat Type: '. $type_name .'</td>
                    <td align="center" valign="top" style="color: #4c4c4c; font-weight: normal; font-size: 14px; font-family: Arial;">Address: '. $addressfull .'</td>
                    <td align="right" valign="top" style="color: #00afef; font-weight: bold; font-size: 14px; font-family: Arial;">Price: $'. $cm->price_format($price) .'</td>
                </tr>
			</table>
			
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
                 <tr>
                    <td align="center" valign="top"><img src="'. $cm->site_url . '/' . $imagefolder . $ppath .'" alt=""></td>
                 </tr>
            </table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingsubhead .'" align="left">Overview</td>
				</tr>
				<tr>
					<td style="'. $invheadingtdrow2 .'" align="left">'. $overview .'</td>
				</tr>
			</table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingsubhead .'" align="left">Specifications</td>
				</tr>
				<tr>
					<td style="'. $invheadingsubheadmedium .'" align="left">Basic Information</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Manufacturer:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $manufacturer_name .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Vessel Name:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $vessel_name .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $model .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Boat Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $type_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Year:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $model .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Hull Material:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $hull_material_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Category:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $category_name .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Hull Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $hull_type_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Condition:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $condition_name .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Hull Color:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $hull_color .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Location:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $addressfull .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Designer:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $designer .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Available for sale in U.S. waters:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $cm->set_yesyno_field($sale_usa) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Flag of Registry:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $flag_country_name .'</td>
				</tr>
			</table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td style="'. $invheadingsubheadmedium .'" align="left">Dimensions &amp; Weight</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Length:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_number_field($length, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">LOA:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_number_field($loa, 1, 1) .'</td>
				</tr>
				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Draft - max:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($draft, 1, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Dry Weight:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($dry_weight, 1) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Beam:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($beam, 1, 1) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Bridge Clearance:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($bridge_clearance, 1, 1) .'</td>
				</tr>
			</table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td style="'. $invheadingsubheadmedium .'" align="left">Engine</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Make:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $engine_make_name .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Engine Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $engine_type_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Model:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $engine_model .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Drive Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $drive_type_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Engine(s):</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($engine_no) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Fuel Type:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $fuel_type_name .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Hours:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($hours) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Horsepower:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_hp($engine_no, $horsepower_individual) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Cruise Speed:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($cruise_speed, 5) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Max Speed:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($max_speed, 5) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Range:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($en_range, 7) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Joystick Control:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $cm->set_yesyno_field($joystick_control) .'</td>
				</tr>
			</table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td style="'. $invheadingsubheadmedium .'" align="left">Tank Capacities</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Fuel Tank:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Holding Tank:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_tank_cap($holding_tanks, $no_holding_tanks) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Fresh Water Tank:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
				</tr>
			</table>
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">				
				<tr>
					<td style="'. $invheadingsubheadmedium .'" align="left">Accommodations</td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Total Cabins:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_number_field($total_cabins) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="10%">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="17%">Crew Cabins:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'" width="28%">'. $this->display_yacht_number_field($crew_cabins) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Total Berths:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($total_berths) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Crew Berths:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($crew_berths) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Total Sleeps:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($total_sleeps) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Crew Sleeps:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($crew_sleeps) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Total Heads:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($total_heads) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Crew Heads:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($crew_heads) .'</td>
				</tr>

				<tr>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">Captains Cabin:</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">'. $this->display_yacht_number_field($captains_cabin) .'</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
					<td align="left" valign="top" style="'. $invheadingtdrow2 .'">&nbsp;</td>
				</tr>
			</table>
			
			<div style="'. $invheadingtdrow2 .'">
			<p style="'. $invheadingsubhead .'">Descriptions</p>
			'. $descriptions .'
			</div>
			
			<div style="'. $invheadingtdrow2 .'">
			'. $this->display_yacht_external_link($id) .'
			</div>
			
			'. $photo_thumb_txt .'	
			
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
					<td style="'. $invheadingsubhead .'" align="left">Location Map</td>
				</tr>
				<tr>
                    <td align="left" valign="top">
                    <p style="padding: 5px 0px 5px 0px;"><img style="width: 100%;" src="https://maps.googleapis.com/maps/api/staticmap?center='. urlencode($addressfull) .'&zoom=9&size=750x300&maptype=roadmap&sensor=false&&markers=size:mid%7Ccolor:green%7C'. urlencode($addressfull) .'&key='. $cm->googlemapkey .'"></p>
                    </td>
                </tr>
            </table>	
			';
		}
		
		return $returntxt;
	}
	
	public function get_print_inventory($printoption, $include_broker, $sortop, $orderbyop, $boatselected = ''){
		global $db, $cm;
        $returntxt = '<div class="print-inv-holder">';
		
		if ($boatselected != ""){
			$chosenboat_filter = '';
			$chosenboat_ar = explode(",", $boatselected);
			foreach($chosenboat_ar as $chosenboat_r){
				$chosenboat_filter .= '\''. $cm->filtertext($chosenboat_r) .'\',';
			}
			$chosenboat_filter = rtrim($chosenboat_filter, ',');
			
			$sql = "select * from tbl_yacht as a, tbl_yacht_dimensions_weight as c where a.id = c.yacht_id and a.listing_no IN ( ". $chosenboat_filter ." ) and a.status_id IN (1,3) and a.display_upto >= CURDATE()";
		}else{		
			$sql = $_SESSION["created_sql"];
		}
			
		if ($sortop == 1){
			$sortfield = 'a.price_tag_id, a.price';
		}elseif ($sortop == 3){
			$sortfield = 'a.year';
		}elseif ($sortop == 4){
			$sortfield = 'b.name';
		}else{
			$sortfield = 'c.length';
		}

		if ($orderbyop == 2){
			$orderby = ' desc';
		}else{
			$orderby = '';
		}

		$sorting_sql = $sortfield . $orderby;
		$sql = $sql." order by ". $sorting_sql;	
		
		$result = $db->fetch_all_array($sql);	
		$found = count($result);	
		
		if ($printoption == 1){
			$returntxt .= $this->get_print_inventory_header($include_broker);
			$invheading = 'margin-top: 5px;';
			$invheadingtd = 'background-color: #4f5660; padding: 4px; border-top: 1px solid #000; border-bottom: 1px solid #000; color: #fff; font-weight: bold; font-family: Arial; font-size: 14px;';
			$returntxt .= '
			<table style="'. $invheading .'" border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td style="'. $invheadingtd .'" width="110" align="center">Photo</td>
					<td style="'. $invheadingtd .'" width="80" align="left">Length</td>
					<td style="'. $invheadingtd .'" width="150" align="left">Make / Model</td>
					<td style="'. $invheadingtd .'" width="80" align="center">Year</td>
					<td style="'. $invheadingtd .'" width="140" align="left">Engine(s)</td>
					<td style="'. $invheadingtd .'" width="100" align="center">Price</td>
					<td style="'. $invheadingtd .'" width="140" align="center">Location</td>
				</tr>
			';
		}
		
		$maincount = 0;
		$pcount = 0;
		foreach($result as $row){
			if ($printoption == 4){
				if ($pcount == 0){
					$returntxt .= '<div style="width: 49%; float: left; text-align: left;">';	
				}else{
					$returntxt .= '<div style="width: 49%; float: right; text-align: left;">';
				}
			}
			$returntxt .= $this->display_print_inventory($row, $printoption, $include_broker);
			if ($printoption == 4){ 
				$returntxt .= '</div>';
			}
			$maincount++;
			if ($maincount < $found){
				
				if ($printoption == 2 OR $printoption == 5){
					$returntxt .= '<pagebreak />';
				}
				
				if ($printoption == 3){
					$pcount++;
					if ($pcount == 2){
						$returntxt .= '<pagebreak />';
						$pcount = 0;
					}else{
						$returntxt .= '
						<table style="margin-top: 3px;" border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td style="background-color: #fff; padding: 0; border-bottom: 2px solid #000;" align="center">&nbsp;</td>
							</tr>
						</table>
						';
					}
				}
				
				if ($printoption == 4){
					$pcount++;
					if ($pcount == 2){
						$returntxt .= '											
						<pagebreak />
						';
						$pcount = 0;
					}
				}
			}
		}
		
		if ($printoption == 1){
			$returntxt .= '
				</table>
			';
		}
		
		$returntxt .= '
			<div class="clear"></div>
		</div>';
		
		return $returntxt;
	}
	
	public function get_print_inventory_broker(){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		$cuser_ar = $cm->get_table_fields('tbl_user', 'type_id, company_id, location_id', $loggedin_member_id);		
		$cuser_type_id = $cuser_ar[0]["type_id"];
		$com_id = $cuser_ar[0]["company_id"];
		$location_id_user = $cuser_ar[0]["location_id"];
		
		$sql_location = "select * from tbl_location_office where company_id = '". $com_id ."' and";
		if ($cuser_type_id == 4){
			$sql_location .= " location_id = '". $location_id_user ."'";
		}
		$sql_location .= " status_id = 1 order by state_id, state";
		$result_location = $db->fetch_all_array($sql_location);		
		
		$returntxt = '<div class="print-inv-holder">';
			foreach($result_location as $row_location){
				$location_id = $row_location["id"];                
				
				$query_sql = "select *";
				$query_form = " from tbl_user,";
				$query_where = " where";
				$query_where .= " company_id = '". $com_id ."' and";				
				//$query_where .= " (location_id = '". $location_id ."' OR type_id = 2 OR type_id = 3) and";
				$query_where .= " location_id = '". $location_id ."' and";
				$query_where .= " status_id = 2 and";
				
				$query_sql = rtrim($query_sql, ",");
				$query_form = rtrim($query_form, ",");
				$query_where = rtrim($query_where, "and");
		
				$sql = $query_sql . $query_form . $query_where;
				$sql = $sql." order by uid";
				$result = $db->fetch_all_array($sql);
				$found = count($result);
				
				if ($found > 0){
					$name = $row_location["name"];
					$address = $row_location["address"];
					$city = $row_location["city"];
					$state = $row_location["state"];
					$state_id = $row_location["state_id"];
					$country_id = $row_location["country_id"];
					$zip = $row_location["zip"];
					$phone = $row_location["phone"];
					$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
					
					$returntxt .= '
					<table class="invheading" border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="invheading-td3" align="left">'. $addressfull .'</td>
						</tr>
					</table>
					';
					
					$returntxt .= '
					<table class="invheading" border="0" width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td class="invheading-td" width="110" align="center">Photo</td>
							<td class="invheading-td" width="150" align="left">Name</td>
							<td class="invheading-td" width="80" align="left">Phone</td>
							<td class="invheading-td" width="160" align="left">Email</td>
							<td class="invheading-td" width="200" align="left">Location</td>
							<td class="invheading-td" width="100" align="center"># Listings</td>
						</tr>
					';
					
					foreach($result as $row){
						$id = $row["id"];
						$b_uid = $row["uid"];
						$b_fname = $row["fname"];
						$b_lname = $row["lname"];
						$b_email = $row["email"];
						$b_phone = $row["phone"];
						$member_image = $this->get_user_image($id);
						$target_path_main = 'userphoto/big/';
						$imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';
						$total_y = $this->get_total_yacht_by_broker(array("broker_id" => $id, "status_id" => 1));
						
						$returntxt .= '				
						<tr>
							<td class="invheading-tdrow" width="" align="center">'. $imgpath_d .'</td>
							<td class="invheading-tdrow" width="" align="left">'. $b_fname . ' ' . $b_lname .'</td>
							<td class="invheading-tdrow" width="" align="left">'. $b_phone .'</td>
							<td class="invheading-tdrow" width="" align="left">'. $b_email .'</td>
							<td class="invheading-tdrow" width="" align="left">'. $addressfull .'</td>
							<td class="invheading-tdrow" width="" align="center">'. $total_y .'</td>
						</tr>
					';
					}
					
					$returntxt .= '
						</table>
						
						<table class="invheading" border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td style="'. $invheadingtdrow2 .'" align="center">&nbsp;</td>
							</tr>
						</table>
					';
					
				}//if found				
			}
		$returntxt .= '
			<div class="clear"></div>
		</div>';
		return $returntxt;
	}
	
	public function dashboard_print_boats(){
		if(($_REQUEST['fcapi'] == "dashboardprintboats")){
			global $cm, $frontend, $sdeml;
			$printoption = round($_REQUEST['printoption'], 0);
			$include_broker = round($_REQUEST['include_broker'], 0);
			$sortop = round($_REQUEST['sortop'], 0);
			$orderbyop = round($_REQUEST['orderbyop'], 0);
			$boatselected = $_REQUEST['boatselected'];
			
			$frontend->go_to_login();
			$html = $this->get_print_inventory($printoption, $include_broker, $sortop, $orderbyop, $boatselected);
			$filename = "boatinventory.pdf";
			$cm->generate_pdf('', $html, '', $filename, 'I');
			exit;
		}		
	}
	//end
	
	//offering boat
	public function display_offering_boat_collect($mid, $imgurl = '', $linkurl = ''){
		global $db, $cm;
		//set 1
		$set1 = '';
		$m_ar = $cm->get_table_fields('tbl_manufacturer', 'slug, name, logo_image', $mid);
		$slug = $m_ar[0]["slug"];
		if ($slug != ""){
			$name = $m_ar[0]["name"];
			
			if ($imgurl != ""){
				$logo_image = $cm->folder_for_seo . 'images/' . $imgurl;
			}else{
			
				$logo_image = $m_ar[0]["logo_image"];
				if ($logo_image == ""){ $logo_image = 'no.png'; }
				$logo_image = $cm->folder_for_seo .'manufacturerimage/'. $logo_image;
			}
			if ($linkurl == ""){ $linkurl = $cm->get_page_url($slug, 'manufacturerprofile'); }
			$set1 = '<a href="'. $linkurl .'"><img src="'. $logo_image .'" title="'. $name .'" alt="" /></a>';
		}
		
		return $set1;
	}
	
	public function display_offering_boat($argu = array()){
		global $ymclass;		
	  	$returntext = '';				
		$innerpage = round($argu["innerpage"], 0);
		
		$retval = json_decode($ymclass->get_assign_manufacturer_list());
		$found = $retval->found;
		if ($found > 0){
			
			if ($innerpage == 1){
				$returntext = '				
				'. $retval->doc .'
				<div class="clear"></div>
				';
			}else{			
				$returntext = '
				<div class="offeringnewboats">
					<div class="container">
						<h2>Offering New Boats From</h2>
						'. $retval->doc .'
						<div class="clear"></div>
					</div>
				</div>
				';
			}
		}			
		return $returntext;
	}
	//end
	
	//location module
	public function check_location_exist($checkvalue, $optn = 1, $adminfrom = 0, $frompopup = 0){
		global $db, $cm;
		if ($optn == 1){
            $sql = "select * from tbl_location_office where slug = '". $cm->filtertext($checkvalue) ."'";
        }else{
            $sql = "select * from tbl_location_office where id = '". $cm->filtertext($checkvalue) ."'";
        }
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found == 0){
			if ($adminfrom == 1){
				$_SESSION["admin_sorry"] = "ERROR! Invalid Location Office.";
				header('Location: sorry.php');
				exit;				
			}else{
				global $frontend;
				$_SESSION["ob"] = $frontend->display_message(25);
				if ($frompopup == 1){
					//frontend popup
					$redpage = $cm->get_page_url(0, "popsorry");
				}else{
					//frontend normal
					$redpage = $cm->get_page_url(0, "sorry");
				}
				header('Location: '. $redpage);
				exit;				
			}	
        }
        return $result;		
	}
	
	public function location_sql(){
        $query_sql = "select *,";
        $query_form = " from tbl_location_office,";
        $query_where = " where";
		
	  	$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
        return $sql;
  }
  
  public function total_featured_found($sql){
        global $db;
        $sqlm = str_replace("select *","select count(*) as ttl",$sql);
        $foundm = $db->total_record_count($sqlm);
        return $foundm;
  }
	
	public function display_location_map_view(){
		global $db, $cm;
	  	$returntext = '';
		$iounter = 0;
		$mapdataar = array();
		$sql = $this->location_sql();
		$result = $db->fetch_all_array($sql);
        $found = count($result);

		if ($found > 0){
			$returntext .= $cm->google_map_js_include();
			$returntext .= '<div id="map"></div>';
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = htmlspecialchars($val);
				}
				
				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, '');
				$details_url = $cm->get_page_url($slug, 'locationprofile');
				if ($logo_image == ""){ $logo_image = 'noflag.png'; }
				
				//user list
				$usertext = '';
				$sqlu = "select concat(fname, ' ', lname) as uname, phone from tbl_user where status_id = 2 and front_display = 1 and (location_id = '". $id ."' OR location_id = 0)";
				$resultu = $db->fetch_all_array($sqlu);
				foreach($resultu as $rowu){
					$uname = $rowu["uname"];
					$userphone = $rowu["phone"];
					$usertext .= '<br />' . $uname;
					if ($userphone != ""){
						$usertext .= '<br />' . $userphone;
					}
				}
				
				$contentval = '
				<div class="listing-map-label listing-status-for-sale">
					<img alt="'. $name .'" class="listing-thumbnail wp-post-image" src="'. $cm->folder_for_seo . 'locationimage/' . $logo_image .'">					
					<div class="map-label-content">
						<span class="listing-address"><a href="'. $details_url .'"><strong>'. $addressfull .'</strong>'. $usertext .'</a></span>
					</div>
				</div>';
				
				$mapdataar[] = array(
					'contentval' => $contentval,
					'lat' => $lat_val,
					'lon' => $lon_val
				);
				$iounter++;
			}			
			
			$returntext .= '
			<script>
			listingMap('. json_encode($mapdataar) .', 15);
			</script>
			';			
		}
		return $returntext;
	}
	
  	public function display_featured_location(){
		global $db, $cm;
	  	$returntext = '';
		$sql = $this->location_sql();
        $sql = $sql." order by reg_date desc limit 0, 20";
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
		$returntext .= '
		<div class="widgeticon"><img src="'. $cm->folder_for_seo .'images/location_icon.png" title="Our Location" alt=""></div>
		<div class="widget widgetmap eqht1">
			<h2 class="ourlocation-icon">Our Location</h2>
			<div class="mapimg"><img src="'. $cm->folder_for_seo .'images/location-map.png" title="Our Location" alt=""></div>
		';
		
		if ($found > 0){
			$locationpage_url = $cm->get_page_url(29, "page");
			$returntext .= '<ul class="locationname">';
			foreach($result as $row){
				$locationid = $row['id'];
				$name = $cm->filtertextdisplay($row['name']);
				$city = $cm->filtertextdisplay($row['city']);
				$slug = $cm->filtertextdisplay($row['slug']);
				$details_url = $cm->get_page_url($slug, 'locationprofile');
				$returntext .= '
				<li><a href="'. $details_url .'">'. $city .'</a></li>
				';
			}
			$returntext .= '
			</ul>
	
			<a href="'. $locationpage_url .'" class="button2"><span>Locations in Larger Map</span></a>
			
			';
		}
			
		$returntext .= '</div>
		';
		
		return $returntext;
	}
	
	public function get_location_by_state($state_id, $state, $status_id = 0){
		global $db, $cm;
		$returntext = '';
		if ($state_id > 0){
			$sql = "select id from tbl_location_office where state_id = '". $state_id ."'";
		}else{
			$sql = "select id from tbl_location_office where state = '". $cm->filtertext($state) ."'";
		}
		
		if ($status_id > 0){
			$sql .= " and status_id = '". $status_id ."'";
		}
		$result = $db->fetch_all_array($sql);
		foreach($result as $row){
			$id = $row['id'];
			$returntext .= $id . ',';
		}
		
		if ($returntext != ""){
			$returntext = rtrim($returntext, ',');
		}else{
			$returntext = 0;
		}
		return $returntext;
	}
	//end
	
	//Our Team	
	public function display_out_team_broker_old($argu = array()){
		global $db, $cm;
        $returntext = '';		
		$filteruser = round($argu["filteruser"], 0);
		$filtertitle = round($argu["filtertitle"], 0);
		$default_view = round($argu["default_view"], 0);
		
		$defalut_view_class = ' listview';
		$gridactive = ' active';
		$listactive = '';
		if ($default_view == 0){
			$default_view = $cm->get_systemvar("OTMDV");
		}
		
		if ($default_view == 2){
			$defalut_view_class = '';
			$gridactive = '';
			$listactive = ' active';
		}
		
		$display_change = '
		<ul class="galleryviewoption">
			<li>View Option</li>
			<li><a href="javascript:void(0);" filteruser="'. $filteruser .'" dval="1" title="Grid view" class="ourteamchange icon grid'. $gridactive .'">Grid view</a></li>
			<li><a href="javascript:void(0);" filteruser="'. $filteruser .'" dval="2" title="List view" class="ourteamchange icon list'. $listactive .'">List view</a></li>
			<li><span class="displaywaiting"><img src="'. $cm->folder_for_seo .'images/ajax-loader.gif" /></span></li>
		</ul>
		';
		
		//Collect broker for non group
		//collect broker for this location
			$query_sql = "select *";
			$query_form = " from tbl_user,";
			$query_where = " where";
			$query_where .= " status_id = 2 and front_display = 1 and";
					
			$query_where .= " above_group = 1 and";
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by rank";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$returntext .= '
				<h2>&nbsp;</h2>
				<ul class="ourteam-list'. $defalut_view_class .'">
				';
				foreach($result as $row){
					$returntext .= $this->display_broker_ind($row, 0, $default_view);
				}
				$returntext .= '
                </ul>
				<div class="clearfix"></div>
				';
				$k++;
			}	
		//end
		
		//$query_sql = "select distinct state_id, state, country_id";
		$query_sql = "select *";
		$query_form = " from tbl_location_office";
		$query_where = " where";
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql_location = $query_sql . $query_form . $query_where;
		//$sql_location .= " order by state_rank";
		$sql_location .= " order by rank";
		$result_location = $db->fetch_all_array($sql_location);
		
		$k = 0;
		foreach($result_location as $row_location){	
			$location_id = $row_location['id'];
			$address = $row_location['address'];		
			$city = $row_location['city'];
			$state = $row_location['state'];
			$state_id = $row_location["state_id"];
			$country_id = $row_location["country_id"];
			
			/*if ($country_id == 1){
				$state_name = $cm->get_common_field_name('tbl_state', 'name', $state_id);
			}else{
				$state_name = $state;
			}
			
			$locationsql = $this->get_location_by_state($state_id, $state, 1);*/
			
			
			//collect broker for this location
			$query_sql = "select distinct a.*";
			$query_form = " from tbl_user as a,";
			$query_where = " where";
			
			//$query_where .= " a.location_id IN ( ". $locationsql ." ) and";
			$query_where .= " a.location_id = '". $location_id ."' and";
			$query_where .= " a.status_id = 2 and a.front_display = 1 and a.type_id IN ( 2,3,4,5 ) and";
			
			//$user_heading_text = "Brokers";
			$user_heading_text = '';
			if ($filtertitle == 2){
				//$user_heading_text = "Location";
			}
			if ($filteruser > 0){
				if ($filteruser == 2){
					//only crew
					$query_where .= " a.support_crew = 1 and";
					$user_heading_text = "Crew";
				}else{
					//other that crew
					$query_where .= " a.support_crew = 0 and";
				}
			}
			
			$query_where .= " a.above_group = 0 and";
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql .= " order by a.rank";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$returntext .= '
				<h2 class="color1 extraspace1">' . $this->com_address_format('', $city, $state, $state_id, $country_id, '') .'</h2>
				<ul class="ourteam-list'. $defalut_view_class .'">
				';
				foreach($result as $row){
					$returntext .= $this->display_broker_ind($row, 0, $default_view);
				}
				$returntext .= '
                </ul>
				<div class="clearfix"></div>
				';
				$k++;
			}			
		}
		
		if ($k > 0){
			$returntext = $display_change . $returntext;
		}
		
		$returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);
	}
	
	public function display_out_team_broker_by_group($argu = array()){
		global $db, $cm;
        $returntext = '';		
		$filteruser = round($argu["filteruser"], 0);
		$filtertitle = round($argu["filtertitle"], 0);
		$default_view = round($argu["default_view"], 0);
		$user_location_id = round($argu["user_location_id"], 0);
		$user_sub_group_id = round($argu["subgroup"], 0);
		$isdashboard = round($argu["isdashboard"], 0);
		
		if ($default_view == 0){
			$default_view = $cm->get_systemvar("OTMDV");
		}
		
		$g_query_sql = "select *,";
		$g_query_form = " from tbl_user_sub_group,";
		$g_query_where = " where";
		
		if ($user_sub_group_id > 0){
			$g_query_where .= " id = '". $user_sub_group_id ."' and";
		}
		
		$g_query_where .= " status_id = 1 and";
		
		$g_query_sql = rtrim($g_query_sql, ",");
		$g_query_form = rtrim($g_query_form, ",");
		$g_query_where = rtrim($g_query_where, "and");
		
		$g_sql = $g_query_sql . $g_query_form . $g_query_where;
		$g_sql .= " order by rank";
		$g_result = $db->fetch_all_array($g_sql);
		$g_found = count($g_result);
		
		if ($g_found > 0){
			foreach($g_result as $g_row){
				$sub_group_id = $g_row["id"];
				$group_name = $cm->filtertextdisplay($g_row["name"]);
				
				//create sql
				$query_sql = "select *,";
				$query_form = " from tbl_user,";
				$query_where = " where";

				if ($user_location_id > 0){
					$query_where .= " location_id = '". $user_location_id ."' and";
				}

				$query_where .= " status_id = 2 and";
				$query_where .= " front_display = 1 and";
				$query_where .= " sub_group_id = '". $sub_group_id ."' and";
				

				$query_sql = rtrim($query_sql, ",");
				$query_form = rtrim($query_form, ",");
				$query_where = rtrim($query_where, "and");

				$sql = $query_sql . $query_form . $query_where;
				$sql .= " order by rank";
				//end

				$result = $db->fetch_all_array($sql);
				$found = count($result);

				if ($found > 0){
					$returntext .= '
					<h2 class="doublelinebothside"><span>'. $group_name .'</span></h2>
					<ul class="ourteam-list-new">
					';

					foreach($result as $row){
						$returntext .= $this->display_broker_ind($row, 0, $default_view, $isdashboard);
					}

					$returntext .= '
					</ul>
					<div class="clearfix"></div>
					';
				}	
			}
		}
		
		$returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);
	}
	
	public function display_out_team_broker($argu = array()){
		global $db, $cm;
        $returntext = '';		
		$filteruser = round($argu["filteruser"], 0);
		$filtertitle = round($argu["filtertitle"], 0);
		$default_view = round($argu["default_view"], 0);
		$user_location_id = round($argu["user_location_id"], 0);
		$user_sub_group_id = round($argu["subgroup"], 0);
		$isdashboard = round($argu["isdashboard"], 0);
		$specificuserlist = $argu["specificuserlist"];
		
		if ($default_view == 0){
			$default_view = $cm->get_systemvar("OTMDV");
		}
				
		//create sql
		$query_sql = "select *,";
		$query_form = " from tbl_user,";
		$query_where = " where";

		if ($user_location_id > 0){
			$query_where .= " location_id = '". $user_location_id ."' and";
		}
		
		if ($specificuserlist != ""){
			$query_where .= " id IN (". $specificuserlist .") and";
		}

		$query_where .= " status_id = 2 and";
		$query_where .= " front_display = 1 and";

		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");

		$sql = $query_sql . $query_form . $query_where;
		$sql .= " order by rank";
		//end

		$result = $db->fetch_all_array($sql);
		$found = count($result);

		if ($found > 0){
			$returntext .= '		
			<ul class="ourteam-list-new">
			';

			foreach($result as $row){
				$returntext .= $this->display_broker_ind($row, 0, $default_view, $isdashboard);
			}

			$returntext .= '
			</ul>
			<div class="clearfix"></div>
			';
		}
		
		$returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);
	}
	
	public function display_our_team($argu = array()){
		global $db, $cm;
		$_SESSION["s_locationpage"] = 0;
		$_SESSION["s_brokerprofilepath"] = $cm->get_page_id_by_slug($cm->format_page_slug());
			
		$retval = json_decode($this->display_out_team_broker($argu));
		$returntext = '		
		<div id="ourteamlist" class="mostviewed clearfixmain">
			'. $retval->doc .'
		</div>
		';
		
		$returntext .= '
		<script type="text/javascript">
			$(document).ready(function(){
				$(".main").on("mouseenter", ".mappopup", function(){
					$(this).fancybox({
						maxWidth	: 560,
						maxHeight	: 500,
						fitToView	: true,
						autoHeight	: true,
						autoWidth	: true,
						autoSize	: true
					});
				});
				
				$(".main").off("change", ".user_location_id").on("change", ".user_location_id", function(){
					var user_location_id = $(this).val();
					dispay_wait_msg("Please wait!!!!!");
					
					var b_sURL = bkfolder + "includes/ajax.php";
					$.post(b_sURL,
					{
						user_location_id:user_location_id,
						subsection:2,
						az:49,
						dataType: "json"
					},
					function(data){
						data = $.parseJSON(data);
						content = data.doc;		
						$("#ourteamlist").html(content);
						
						if (content != ""){
							$("#ourteamlist img").on("load", function(){
								hide_wait_msg();
							});
						}else{
							hide_wait_msg();
						}
					});
				});
				
				$(".main").off("click", ".ourteamchange").on("click", ".ourteamchange", function(){
					var dval = $(this).attr("dval");
					var filteruser = $(this).attr("filteruser");
					$(".ourteamchange").removeClass("active");
					$(this).addClass("active");
					$(".displaywaiting").show();			
					var b_sURL = bkfolder + "includes/ajax.php";
					
					$.post(b_sURL,
					{
						dval:dval,
						filteruser:filteruser,			
						az:49
					},
					function(data){
						data = $.parseJSON(data);
						content = data.doc;		
						$("#ourteamlist").html(content);
						$(".displaywaiting").hide();	
					});			
					
				});
			});
		</script>
		';
		return $returntext;
	}
	
	public function display_our_team_homelist($argu = array()){
		global $db, $cm;
		$returntext = '';
		$_SESSION["s_locationpage"] = 0;
		$_SESSION["s_brokerprofilepath"] = $cm->get_page_id_by_slug($cm->format_page_slug());
		
		//create sql
		$query_sql = "select *,";
		$query_form = " from tbl_user,";
		$query_where = " where";

		$query_where .= " status_id = 2 and";
		$query_where .= " front_display = 1 and";

		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");

		$sql = $query_sql . $query_form . $query_where;
		$sql .= " order by RAND() limit 0, 3";
		//end
		
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$collected_page_id = $cm->get_page_id_by_shortcode("[fcourteam");
		  	$ourteam_url = $cm->get_page_url($collected_page_id, "page");
			
			$returntext .= '<h2><span>Meet</span> Our Sales Team</h2>';			
			$returntext .= '
			<div class="threecolumnlist clearfixmain">
			<ul>
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$profile_url = $cm->get_page_url($id, 'user');
				$target_path_main = 'userphoto/big/';
				$member_image = $this->get_user_image($id);
				$imgpath_d = '<img class="full" src="'. $cm->folder_for_seo . $target_path_main . $member_image .'" border="0" />';
				$brokername = $fname .' '. $lname;
				if ($display_title == 1 AND $title != ""){
					$brokername .= ', ' . $title;
				}
				
				$returntext .= '
				<li>
					<div class="thumb"><a href="'. $profile_url .'">'. $imgpath_d .'</a></div>
					<h6>'. $brokername . '</h5>
				</li>
				';
			}
			
			$returntext .= '
			</ul>
			</div>
			<p><a class="arrow" href="'. $ourteam_url .'">Read more</a></p>
			';
		}		
		return $returntext;
	}
	//end
			
	
	//FEED
	public function get_numeric_value_from($value, $replaceval = ""){
		$value = str_replace($replaceval, "", $value);
		return $value;
	}
	
	public function module_check($checkvalue, $collectfield, $checkfield, $checktable, $searchtype = 1, $addeddata = 0, $ycidadd = 0){
		global $db, $cm;
		$existing_id = 0;
		$pass_to_bs = '';		
		if ($checkvalue != ""){
			if ($searchtype == 2){
				$sql = "select ". $collectfield ." as ttl from ". $checktable ." where ". $checkfield ." like '%". $cm->filtertext($checkvalue) ."%'";
				$existing_id = $db->total_record_count($sql);
			}elseif ($searchtype == 3){
				$sql = "select ". $collectfield ." as ttl from ". $checktable ." where ". $checkfield ." = '". $cm->filtertext($checkvalue) ."'";
				$existing_id = $db->total_record_count($sql);
			}else{
				$existing_id = $cm->get_common_field_name($checktable, $collectfield, $checkvalue, $checkfield);
			}
			
			if ($existing_id == 0 AND $addeddata == 1){
				$rank = $db->total_record_count("select max(rank) as ttl from ". $checktable ."") + 1;
				$sql = "insert into ". $checktable ." (name, status_id, rank, isimported) values ('". $cm->filtertext($checkvalue) ."', 1, '". $rank ."', 1)";
				$existing_id = $db->mysqlquery_ret($sql);
				//$addeddata .= $checkvalue . '<br />';
				
				if ($checktable == "tbl_manufacturer"){
					$slug = $cm->serach_url_filtertext($checkvalue, 1);
					$sql = "update ". $checktable ." set slug = '". $cm->filtertext($slug) ."' where id = '". $existing_id ."'";
					$db->mysqlquery($sql);
				}
				
				if ($ycidadd == 1){
					$sql = "update ". $checktable ." set ycid = '". $cm->filtertext($checkvalue) ."' where id = '". $existing_id ."'";
					$db->mysqlquery($sql);
				}
			}
		}
		
		if ($existing_id == 0){
			$pass_to_bs = $checkvalue;
		}
		
		$returnval = (object) array(
            'existing_id' => $existing_id,
            'pass_to_bs' => $pass_to_bs
        );
		return $returnval;
	}
	
	public function check_feed_boat_exist($checkval, $checkfield){
		global $db, $cm;
		$sql = "select id as ttl from tbl_yacht where ". $checkfield ." = '". $cm->filtertext($checkval) ."'";
		$boat_id = $db->total_record_count($sql);
		return $boat_id;
	}

	public function skip_feed_boat($checkval, $checkfield, $owned){
		global $db, $cm;
		$sql = "select id as ttl from tbl_yacht where ". $checkfield ." = '". $cm->filtertext($checkval) ."' and ownboat = '". $owned ."'";
		$boat_id = $db->total_record_count($sql);
		return $boat_id;
	}

	public function delete_yacht_existing_image($boat_id){
		global $db, $cm;
		$sql = "select imgpath from tbl_yacht_photo where yacht_id = '". $boat_id ."'";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $fimg1 = $row['imgpath'];
            $this->delete_yacht_image($fimg1, $boat_id);
        }
		
		$sql = "delete from tbl_yacht_photo where yacht_id = '". $boat_id ."'";
        $db->mysqlquery($sql);
	}
	
	//yw feed manage
	public function update_yw_feed_modified_date($ywboatid){
		global $db, $cm;
		$currentdate = date("Y-m-d");
		
		$sql = "update tbl_yacht set last_updated = '". $currentdate ."' where yw_id = '". $cm->filtertext($ywboatid) ."'";
		$db->mysqlquery($sql);
	}
	
	public function check_feed_image_exist($filename){
		$returnval = 1;
		if (false === file_get_contents($filename)) {
			$returnval = 0;
		}
		return $returnval;
	}
	
	public function manage_yw_feed($VehicleRemarketingrow, $ownboat, $feedtype = 1, $apikey = ""){
		global $db, $cm, $geo, $fle;
		$currentdate = date("Y-m-d");
		if ($feedtype == 1){
			//listings feed - XML
			$ywboatid = $VehicleRemarketingrow->VehicleRemarketingHeader[0]->DocumentIdentificationGroup[0]->DocumentIdentification[0]->DocumentID;
			$VehicleRemarketingBoatLineItem = $VehicleRemarketingrow->VehicleRemarketingBoatLineItem[0];
			
			$partyid_1 = $VehicleRemarketingBoatLineItem->DealerParty[0]->PartyID;
			$ywownerid = $ywowneridcheck = round($VehicleRemarketingBoatLineItem->DealerParty[0]->SpecifiedOrganization[0]->PrimaryContact[0]->ID, 0);
			if ($ywownerid == 0){
				$ywownerid = $partyid_1;
			}
			
			//location, broker assign
			$broker_id = 1;
			$company_id = 1;
			$location_id = 1;
			$broker_to_bs = '';
			
			$sql = "select id, company_id, location_id from tbl_user where yw_broker_id = '". $cm->filtertext($ywownerid) ."'";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			if ($found > 0){
				$row = $result[0];
				$broker_id = $row["id"];
				$company_id = $row["company_id"];
				$location_id = $row["location_id"];
			}else{
				if ($ywowneridcheck > 0){
					$broker_to_bs = 'ID: ' . $ywownerid . ', Name: ' . $VehicleRemarketingBoatLineItem->DealerParty[0]->SpecifiedOrganization[0]->PrimaryContact[0]->PersonName;
				}
				
				$sql = "select id from tbl_location_office where yw_broker_id = '". $cm->filtertext($ywownerid) ."'";
				$result = $db->fetch_all_array($sql);
				$found = count($result);
				if ($found > 0){
					$row = $result[0];
					$location_id = $row["id"];
				}
			}
			//end
			
			$PriceHideIndicator = $VehicleRemarketingBoatLineItem->PricingABIE[0]->PriceHideIndicator;
			$price = $VehicleRemarketingBoatLineItem->PricingABIE[0]->Price[0]->ChargeAmount;
			$price = (double)$price;
			$status_name = $VehicleRemarketingBoatLineItem->SalesStatus;
			
			$soldDate = '';
			$sold_day_no = 0;
			$enhanced = 0;
			echo "YachtWorld DocumentID [" . $ywboatid . "]";
			
			//price check
			$price_tag_id = 0;
			if ($PriceHideIndicator == "true"){
				$price_tag_id = 1;
			}else{
				if (round($price) <= 0){
					$price_tag_id = 1;
				}
			}
			//end
			
			$VehicleRemarketingBoat = $VehicleRemarketingBoatLineItem->VehicleRemarketingBoat[0];
			
			//manufacturer	
			$manufacturer_name = $VehicleRemarketingBoat->MakeString;
			$manufacturer_ar = $this->module_check($manufacturer_name, 'manufacturer_id', 'name', 'tbl_manufacturer_cross', 3);
			$manufacturer_id = $manufacturer_ar->existing_id;
			$manufacturer_to_bs = $manufacturer_ar->pass_to_bs;
			//end
			
			$model = $VehicleRemarketingBoat->Model;
			$year = $VehicleRemarketingBoat->ModelYear;
			
			//category
			$vesselCategory = $VehicleRemarketingBoat->BoatCategoryCode;
			$category_id = $cm->get_common_field_name('tbl_category', 'id', $vesselCategory, 'name');
			
			//condition	
			$condition = $VehicleRemarketingBoat->SaleClassCode;
			$condition_id = $cm->get_common_field_name('tbl_condition', 'id', $condition, 'name');
			
			$vessel_name = $VehicleRemarketingBoat->BoatName;
			
			//boat location
			$locationmain = $VehicleRemarketingBoatLineItem->Location[0]->LocationAddress[0];
			$address = '';
			$city = $locationmain->CityName;
			$state = $locationmain->{'StateOrProvinceCountrySub-DivisionID'};
			$country = $locationmain->CountryID;
			$zip = $locationmain->Postcode;
			
			$country_id = $cm->get_common_field_name('tbl_country', 'id', $country, 'code');
			if ($country_id == 1){
				$state_id = $cm->get_common_field_name('tbl_state', 'id', $state, 'code');
				$state = '';
			}else{
				$state_id = 0;
			}
			//end
			
			$hull_color = '';
			
			//hullmaterial
			$hullMaterial = $VehicleRemarketingBoat->Hull[0]->BoatHullMaterialCode;
			$hullMaterial_ar = $this->module_check($hullMaterial, 'hull_material_id', 'name', 'tbl_hull_material_cross', 3);
			$hull_material_id = $hullMaterial_ar->existing_id;
			$hullMaterial_to_bs = $hullMaterial_ar->pass_to_bs;
			//end
			
			//hull type
			$hullType = $VehicleRemarketingBoat->Hull[0]->BoatHullDesignCode;
			$hullType_ar = $this->module_check($hullType, 'hull_type_id', 'name', 'tbl_hull_type_cross', 3);
			$hull_type_id = $hullType_ar->existing_id;
			$hullType_to_bs = $hullType_ar->pass_to_bs;
			//end
			
			//hin
			$hull_no = $VehicleRemarketingBoat->Hull[0]->HullID;
			//end
			
			$designer = $VehicleRemarketingBoat->DesignerName;
			$sale_usa = 1;
			$flagcountry = $country_id;
			
			//boat type
			$boattype = $VehicleRemarketingBoat->BoatClassGroup[0]->BoatClassCode;
			$boattype_ar = $this->module_check($boattype[0], 'type_id', 'name', 'tbl_type_cross', 3);
			$type_id = $boattype_ar->existing_id;
			$type_to_bs = $boattype_ar->pass_to_bs;
			//end
			
			$flag_country_id = $country_id;
			
			//Dimensions & Weight
			$length = $VehicleRemarketingBoat->BoatLengthGroup[0]->BoatLengthMeasure;
			$length = round($length, 2);
			
			$loa = 0;
			
			$beam = $VehicleRemarketingBoat->BeamMeasure;
			$beam = (double)$beam;
			/*$beam = round($beam, 2);
			$b = (int)$beam;
			$beam_in = $beam - $b;
			if ($beam_in > 0){
				$beam = $this->implode_feet_inchs($b, $beam_in);
			}*/
			
			$draft = $VehicleRemarketingBoat->DraftMeasureGroup[0]->DraftMeasure;
			$draft = (double)$draft;
			/*$draft = round($draft, 2);
			$b = (int)$draft;
			$draft_in = $draft - $b;
			if ($draft_in > 0){
				$draft = $this->implode_feet_inchs($b, $draft_in);
			}*/
			
			$bridge_clearance = $VehicleRemarketingBoat->BridgeClearanceMeasure;
			$bridge_clearance = (double)$bridge_clearance;		
			/*$bridge_clearance = round($bridge_clearance, 2);
			$b = (int)$bridge_clearance;
			$bridge_clearance_in = $bridge_clearance - $b;
			if ($bridge_clearance_in > 0){
				$bridge_clearance = $this->implode_feet_inchs($b, $bridge_clearance_in);
			}*/
			
			$dry_weight = $VehicleRemarketingBoat->DryWeightMeasure;
			$dry_weight = round($dry_weight, 0);		
			//end
			
			//Engine
			$VehicleRemarketingEngineLineItem = $VehicleRemarketingBoatLineItem->VehicleRemarketingEngineLineItem;
			$engine_no = count($VehicleRemarketingEngineLineItem);
			
			$engine_make_id = 0;
			$enginemake_to_bs = "";
			$engine_model = "";
			$engine_type_id = 0;
			$enginetype_to_bs = "";
			$drive_type_id = 0;
			$drivetype_to_bs = "";
			$fuel_type_id = 0;
			$fueltype_to_bs = "";
			$cruise_speed = 0;
			$max_speed = 0;
			$en_range = 0;
			$horsepower_individual = 0;
			$hours = 0;
			if ($engine_no > 0){
				//Collect Only First engine
				$VehicleRemarketingEngine = $VehicleRemarketingEngineLineItem[0]->VehicleRemarketingEngine;
				
				$enginemake = $VehicleRemarketingEngine->MakeString;
				$enginemake_ar = $this->module_check($enginemake, 'engine_make_id', 'name', 'tbl_engine_make_cross', 3);
				$engine_make_id = $enginemake_ar->existing_id;
				$enginemake_to_bs = $enginemake_ar->pass_to_bs;
				
				$engine_model = $VehicleRemarketingEngine->Model;
				
				$enginetype = $VehicleRemarketingEngine->BoatEngineTypeCode;
				$enginetype_ar = $this->module_check($enginetype, 'engine_type_id', 'name', 'tbl_engine_type_cross', 3);
				$engine_type_id = $enginetype_ar->existing_id;
				$enginetype_to_bs = $enginetype_ar->pass_to_bs;
				
				$drivetype = $VehicleRemarketingBoat->DriveTypeCode;
				$drivetype_ar = $this->module_check($drivetype, 'drive_type_id', 'name', 'tbl_drive_type_cross', 3);
				$drive_type_id = $drivetype_ar->existing_id;
				$drivetype_to_bs = $drivetype_ar->pass_to_bs;
				
				$fueltype = $VehicleRemarketingEngine->FuelTypeCode;
				$fueltype_ar = $this->module_check($fueltype, 'fuel_type_id', 'name', 'tbl_fuel_type_cross', 3);
				$fuel_type_id = $fueltype_ar->existing_id;
				$fueltype_to_bs = $fueltype_ar->pass_to_bs;
				
				$cruise_speed = $VehicleRemarketingBoat->CruisingSpeedMeasure;
				$cruise_speed = round($cruise_speed, 2) / $this->mph_to_kts;
				$cruise_speed = round($cruise_speed, 2);
				
				$max_speed = $VehicleRemarketingBoat->MaximumSpeedMeasure;
				$max_speed = round($max_speed, 2) / $this->mph_to_kts;
				$max_speed = round($max_speed, 2);
				
				$en_range = $VehicleRemarketingBoat->RangeMeasure;
				$en_range = round($en_range, 2);
				
				$horsepower_individual = $VehicleRemarketingEngine->PowerMeasure[0]->MechanicalEnergyMeasure;
				$horsepower_individual = round($horsepower_individual, 0);
				
				$hours = $VehicleRemarketingEngine->TotalEngineHoursNumeric;
			}
		
			$joystick_control = 0;
			//end
			
			//Tank Capacities
			$fuel_tanks = $VehicleRemarketingBoat->FuelTankCapacityMeasure;
			$fuel_tanks = round($fuel_tanks, 0);
			$no_fuel_tanks = 1;
			
			$fresh_water_tanks = $VehicleRemarketingBoat->WaterTankCapacityMeasure;
			$fresh_water_tanks = round($fresh_water_tanks, 0);
			$no_fresh_water_tanks = 1;
			
			$holding_tanks = $VehicleRemarketingBoat->HoldingTankCapacityMeasure;
			$holding_tanks = round($holding_tanks, 0);
			$no_holding_tanks = 1;		
			
			$tanknumberar = $VehicleRemarketingBoat->Tank;
			foreach ($tanknumberar as $key => $tanknumberrow){
				$TankUsageCode = $tanknumberrow->TankUsageCode;
				if ($TankUsageCode == "Fuel"){
					$no_fuel_tanks = $tanknumberrow->TankCountNumeric;
				}
				
				if ($TankUsageCode == "Water"){
					$no_fresh_water_tanks = $tanknumberrow->TankCountNumeric;
				}
				
				if ($TankUsageCode == "Black Water"){
					$no_holding_tanks = $tanknumberrow->TankCountNumeric;
				}
			}
			
			if ($no_fuel_tanks == 0){ $no_fuel_tanks = 1; }
			if ($no_fresh_water_tanks == 0){ $no_fresh_water_tanks = 1; }
			if ($no_holding_tanks == 0){ $no_holding_tanks = 1; }
			
			//end
			
			//Accommodations
			$total_cabins = 0;
			$total_berths = 0;
			$total_sleeps = 0;
			$total_heads = 0;
			$captains_cabin = 0;
			$crew_cabins = 0;
			$crew_berths = 0;
			$crew_sleeps = 0;
			$crew_heads = 0;
			
			$accommodationar = $VehicleRemarketingBoat->Accommodation;
			foreach ($accommodationar as $key => $accommodationrow){
				$AccommodationTypeCode = $accommodationrow->AccommodationTypeCode;
				
				if ($AccommodationTypeCode == "Cabin"){
					$total_cabins = $accommodationrow->AccommodationCountNumeric;
				}
				
				if ($AccommodationTypeCode == "Head"){
					$total_heads = $accommodationrow->AccommodationCountNumeric;
				}
				
				if ($AccommodationTypeCode == "SingleBerth" OR $AccommodationTypeCode == "DoubleBerth" OR $AccommodationTypeCode == "TwinBerth"){
					$total_berths = $total_berths + $accommodationrow->AccommodationCountNumeric;
				}
			}
			//end
			
			//Overview
			$overview = $VehicleRemarketingBoat->GeneralBoatDescription;
			
			//Description
			$descriptions = '';
			$descriptionar = $VehicleRemarketingBoatLineItem->AdditionalDetailDescription;
			foreach ($descriptionar as $key => $descriptionrow){
				$title = $descriptionrow->Title;
				$content = $descriptionrow->Description;
				
				$descriptions .= '
				<h3>'. $title .'</h3>
				'. $content .'
				';
			}
			//end	
			
			//set status_id / or custom label
			$custom_label_id = 0;
				
			if ($status_name == "Sale Pending"){
				$status_id = 1;
				$custom_label_id = 5;
			}elseif ($status_name == "Ordered"){
				$status_id = 1;
				$custom_label_id = 15;
			}elseif ($status_name == "Trade Pending"){
				$status_id = 1;
				$custom_label_id = 16;
			}else{
			
				if ($status_name == "Sold"){
					$status_id = 3;
					$sold_day_no = 0;
					$soldDate = $VehicleRemarketingBoatLineItem->SoldDate;
				}elseif ($status_name == "Inactive" OR $status_name == "Delete" OR $status_name == "Expired" OR $status_name == "Rejected"){
					$status_id = 2;
				}else{
					$status_id = 1;
				}
			}
			//ends	
			
			//Add/Edit Boat
			$dt = date("Y-m-d H:i:s");
			$add_date = $VehicleRemarketingBoatLineItem->ItemReceivedDate;
			$boat_id = $this->check_feed_boat_exist($ywboatid, 'yw_id');
			if ($boat_id == 0){
				$sql = "insert into tbl_yacht (company_id, reg_date) values ('". $cm->filtertext($company_id) ."', '". $add_date ."')";
				$boat_id = $db->mysqlquery_ret($sql);
			
				$listing_no = $this->listing_start + $boat_id;
				$sql = "update tbl_yacht set listing_no = '". $listing_no ."' where id = '". $boat_id ."'";
				$db->mysqlquery($sql);			
				$this->add_delete_yacht_extra_info($boat_id, 1);			
				
			}else{
				$listing_no = $this->get_yacht_no($boat_id);
				$ownboat = $cm->get_common_field_name('tbl_yacht', 'ownboat', $boat_id);			
			}
			
			//create folder
			$source = "../yachtimage/rawimage";
			$destination = "../yachtimage/".$listing_no;
			$fle->copy_folder($source, $destination);
			
			// common update	
			$link_url = "";
			$video_id = "";
			
			$charter_id = 1;
			$charter_price = 0;
			$charter_descriptions = 0;
			$charter_descriptions = '';
			$price_per_option_id = 0;
			
			$locationbrokersql = " location_id = '". $location_id ."', broker_id = '". $broker_id ."', ";
			$model_slug = $cm->create_slug($model);	
			$sql = "update tbl_yacht set". $locationbrokersql ." manufacturer_id = '". $manufacturer_id ."'
			, model = '". $cm->filtertext($model) ."'
			, model_slug = '". $cm->filtertext($model_slug) ."'
			, year = '". $year ."'
			, category_id = '". $category_id ."'
			, condition_id = '". $condition_id ."'
			, price = '". $price ."'
			, price_tag_id = '". $price_tag_id ."'
			
			, address = '". $cm->filtertext($address) ."'
			, city = '". $cm->filtertext($city) ."'
			, state = '". $cm->filtertext($state) ."'
			, state_id = '". $state_id ."'
			, country_id = '". $country_id ."'
			, zip = '". $cm->filtertext($zip) ."'
			, sale_usa = '". $sale_usa ."'
			, flag_country_id = '". $flag_country_id ."'
			
			, vessel_name = '". $cm->filtertext($vessel_name) ."'
			, hull_material_id = '". $hull_material_id ."'
			, hull_type_id = '". $hull_type_id ."'
			, hull_color = '". $cm->filtertext($hull_color) ."'
			, hull_no = '". $cm->filtertext($hull_no) ."'
			, designer = '". $cm->filtertext($designer) ."'
			
			, overview = '". $cm->filtertext($overview) ."'
			, descriptions = '". $cm->filtertext($descriptions) ."'
			
			, link_url = '". $cm->filtertext($link_url)."'
			, video_id = '". $cm->filtertext($video_id)."'
			, custom_label_id = '". $cm->filtertext($custom_label_id)."'
			
			, status_id = '". $status_id ."'
			, charter_id = '". $charter_id ."'
			, charter_price = '". $charter_price ."'
			, price_per_option_id = '". $price_per_option_id ."'
			, yw_id = '". $ywboatid ."'
			, ownboat = '". $ownboat ."'
			, last_updated = '". $currentdate ."'
			, yw_broker_id = '". $ywownerid ."'
			, apikey = '". $cm->filtertext($apikey)."' where id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$latlonar = $geo->getLatLon($boat_id, 1);
			$lat = $latlonar["lat"];
			$lon = $latlonar["lon"];
			
			$sql = "update tbl_yacht set lat_val = '". $cm->filtertext($lat)."', lon_val = '". $cm->filtertext($lon)."' where id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_dimensions_weight set length = '". $length ."'
			, loa = '". $loa ."'
			, beam = '". $beam ."'
			, draft = '". $draft ."'
			, bridge_clearance = '". $bridge_clearance ."'
			, dry_weight = '". $dry_weight ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_engine set engine_make_id = '". $engine_make_id ."'
			, engine_model = '". $cm->filtertext($engine_model) ."'
			, engine_no = '". $engine_no ."'
			, hours = '". $hours ."'
			, engine_type_id = '". $engine_type_id ."'
			, drive_type_id = '". $drive_type_id ."'
			, fuel_type_id = '". $fuel_type_id ."'
			, cruise_speed = '". $cruise_speed ."'
			, max_speed = '". $max_speed ."'
			, en_range = '". $en_range ."'
			, horsepower_individual = '". $horsepower_individual ."'
			, joystick_control = '". $joystick_control ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_tank set fuel_tanks = '". $fuel_tanks ."'
			, no_fuel_tanks = '". $no_fuel_tanks ."'
			, fresh_water_tanks = '". $fresh_water_tanks ."'
			, no_fresh_water_tanks = '". $no_fresh_water_tanks ."'
			, holding_tanks = '". $holding_tanks ."'
			, no_holding_tanks = '". $no_holding_tanks ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_accommodation set total_cabins = '". $total_cabins ."'
			, total_berths = '". $total_berths ."'
			, total_sleeps = '". $total_sleeps ."'
			, total_heads = '". $total_heads ."'
			, captains_cabin = '". $captains_cabin ."'
			, crew_cabins = '". $crew_cabins ."'
			, crew_berths = '". $crew_berths ."'
			, crew_sleeps = '". $crew_sleeps ."'
			, crew_heads = '". $crew_heads ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$this->update_sold_yacht_display_date($boat_id, $sold_day_no, $soldDate);
			$this->add_yacht_keywords($boat_id);
			$this->remove_sold_yacht_from_featured($boat_id, $status_id);
			
			//Boat Type
			$sql = "delete from tbl_yacht_type_assign where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			if ($type_id > 0){
				$sql = "insert into tbl_yacht_type_assign (yacht_id, type_id) values ('". $boat_id ."', '". $type_id ."')";
				$db->mysqlquery($sql);
			}
			
			//image
			$this->delete_yacht_existing_image($boat_id);
			$images_ar = $VehicleRemarketingBoatLineItem->ImageAttachmentExtended;
			$imrank = 1;
			foreach($images_ar as $images_row){
				echo "\n - Processing Image...";
				$filename_main = $images_row->URI;
				$im_title = "";
				$i_rank = $imrank;
				
				if ($filename_main != ""){
					$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename_main);
					if ($wh_ok == "y"){
						
						//new code - for process image
						$processimage = 0;
						$processimage = $this->check_feed_image_exist($filename_main);
						
						if ($processimage == 0){
							echo "\n - 1st check failed for URL:". $filename_main ." ...";
							
							$processimage = $this->check_feed_image_exist($filename_main);
							if ($processimage == 0){
								echo "\n - 2nd check failed for URL:". $filename_main ." ...";
								
								$processimage = $this->check_feed_image_exist($filename_main);
								if ($processimage == 0){
									$processimage = $this->check_feed_image_exist($filename_main);
								}
							}
						}					
						//end
						
						if ($processimage == 1){					
							$i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
							$sql = "insert into tbl_yacht_photo (id, yacht_id, im_title, keep_original, status_id, rank) values ('". $i_iiid ."', '". $boat_id ."', '". $cm->filtertext($im_title) ."', 0, 1, '". $i_rank ."')";
							$db->mysqlquery($sql);
							
							$filename_ar = explode("/", $filename_main);
							$arcount = count($filename_ar);
							$filename1 = $filename_ar[$arcount - 1];
							
							$target_path_main = "yachtimage/";
							$target_path_main = "../" . $target_path_main;
							
							//copy main image
							$copy_path = $target_path_main . "feed/";
							$copyfile = $copy_path . $filename1;
							copy($filename_main, $copyfile);
							echo " - Copying Image [" . $filename_main . "] to [" . $copyfile . "]";
							
							$target_path_main = $target_path_main . $listing_no . "/";
							$filename_tmp = $copyfile;
							
							//thumbnail image
							$r_width = $cm->yacht_im_width_t;
							$r_height = $cm->yacht_im_height_t;
							$target_path = $target_path_main;
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//big image
							$r_width = $cm->yacht_im_width_b;
							$r_height = $cm->yacht_im_height_b;
							$target_path = $target_path_main . "big/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//bigger image
							$r_width = $cm->yacht_im_width;
							$r_height = $cm->yacht_im_height;
							$target_path = $target_path_main . "bigger/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//slider image
							$r_width = $cm->yacht_im_width_sl;
							$r_height = $cm->yacht_im_height_sl;
							$target_path = $target_path_main . "slider/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
							$db->mysqlquery($sql);	
							
							unlink($filename_tmp);
							$imrank++;	
						}else{
							echo "\n - 3rd check failed for URL:". $filename_main .". Image removed from list.";
						}
					}
				}
				
				$images_row = null;
				$filename_main = null;
				$target_path_main = null;
				$target_path = null;
				$r_width = null;
				$r_height = null;
			}//image
			
			//AdditionalMedia - as external link and video
			$sql = "delete from tbl_yacht_external_link where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "delete from tbl_yacht_video where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$external_link_rank = 1;
			$i_rank = 1;
			$AdditionalMedia = $VehicleRemarketingBoatLineItem->AdditionalMedia;
			foreach ($AdditionalMedia as $AdditionalMediarow){
				$MediaSourceURI_ori = $AdditionalMediarow->MediaSourceURI;
				$MediaAlternateText = $AdditionalMediarow->MediaAlternateText;
				$MediaAttachmentTitle = $AdditionalMediarow->MediaAttachmentTitle;
				$MediaTypeString = $AdditionalMediarow->MediaTypeString;
				$MediaSubTypeString = $AdditionalMediarow->MediaSubTypeString;
				$f_ext = $fle->get_file_extension($MediaSourceURI_ori);
				if ($f_ext != ".flv"){
					if ($MediaTypeString == "Embedded Video"){
						$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);
						
						$MediaSourceURI = $cm->create_youtube_share_url($MediaSourceURI_ori);				
						if ($MediaSourceURI != ""){
							$video_type = 1;
							$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($MediaSourceURI));
						}else{
							$video_id = "";
							$video_type = 4;
							$MediaSourceURI = $MediaSourceURI_ori;
						}
						
						$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, name, link_url, video_id, rank, status_id) values ('". $i_iiid ."', '". $boat_id ."', '". $video_type ."', '". $cm->filtertext($MediaAttachmentTitle) ."', '". $cm->filtertext($MediaSourceURI) ."', '". $cm->filtertext($video_id)."', '". $i_rank ."', 1)";
						$db->mysqlquery($sql);
						$i_rank++;
					}elseif($MediaSubTypeString == "Additional Media"){
						$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);
						$video_id = "";
						$video_type = 5;
						$MediaSourceURI = $MediaSourceURI_ori;
						$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, name, link_url, video_id, rank, status_id) values ('". $i_iiid ."', '". $boat_id ."', '". $video_type ."', '". $cm->filtertext($MediaAttachmentTitle) ."', '". $cm->filtertext($MediaSourceURI) ."', '". $cm->filtertext($video_id)."', '". $i_rank ."', 1)";
						$db->mysqlquery($sql);
						$i_rank++;
					}else{
						$sql = "insert into tbl_yacht_external_link (yacht_id, name, link_url, link_description, rank) values ('". $boat_id ."', '". $cm->filtertext($MediaAttachmentTitle) ."', '". $cm->filtertext($MediaSourceURI_ori) ."', '". $cm->filtertext($MediaAlternateText) ."', '". $external_link_rank ."')";
						$db->mysqlquery($sql);
						$external_link_rank++;
					}
				}
			}
		}else{
			//listings feed - JSON
			$postdata = $VehicleRemarketingrow;
				
			$ywboatid = $postdata->DocumentID;
			$price = (double)$postdata->NormPrice;
			$status_name = $postdata->SalesStatus;
			$soldDate = '';
			$sold_day_no = 0;
			$enhanced = 0;
			
			echo "DocumentID [" . $ywboatid . "]";
			
			//location, broker assign
			$broker_id = 1;
			$company_id = 1;
			$location_id = 1;			
			$ywownerid = 0;
			$broker_to_bs = '';
			
			if ($ownboat == 1){				
				$partyid_1 = $postdata->Owner->PartyId;
				$ywownerid = $postdata->SalesRep->PartyId;
				$ywownerid = round($ywownerid);
				if ($ywownerid == 0){
					$ywownerid = $partyid_1;
				}
				
				$sql = "select id, company_id, location_id from tbl_user where yw_broker_id = '". $cm->filtertext($ywownerid) ."'";
				$result = $db->fetch_all_array($sql);
				$found = count($result);
				if ($found > 0){
					$row = $result[0];
					$broker_id = $row["id"];
					$company_id = $row["company_id"];
					$location_id = $row["location_id"];
				}else{
					$broker_to_bs = 'ID: ' . $ywownerid . ', Name: ' . $postdata->SalesRep->Name;
					$sql = "select id from tbl_location_office where yw_broker_id = '". $cm->filtertext($ywownerid) ."'";
					$result = $db->fetch_all_array($sql);
					$found = count($result);
					if ($found > 0){
						$row = $result[0];
						$location_id = $row["id"];
					}
				}
			}			
			//end
			
			//price check
			$price_tag_id = 0;
			$PriceHideIndicator = $postdata->PriceHideInd;
			if ($PriceHideIndicator){
				$price_tag_id = 1;
			}else{
				if (round($price) <= 0){
					$price_tag_id = 1;
				}
			}			
			//end
			
			//manufacturer	
			$manufacturer_name = $postdata->MakeString;
			$manufacturer_ar = $this->module_check($manufacturer_name, 'manufacturer_id', 'name', 'tbl_manufacturer_cross', 3);
			$manufacturer_id = $manufacturer_ar->existing_id;
			$manufacturer_to_bs = $manufacturer_ar->pass_to_bs;
			//end
			
			$model = $postdata->ModelExact;
			$year = $postdata->ModelYear;
			
			//category
			$vesselCategory = $postdata->BoatCategoryCode;
			$category_id = $cm->get_common_field_name('tbl_category', 'id', $vesselCategory, 'name');
			
			//condition	
			$condition = $postdata->SaleClassCode;
			$condition_id = $cm->get_common_field_name('tbl_condition', 'id', $condition, 'name');
			
			$vessel_name = $postdata->BoatName;
			
			//boat location
			$address = '';
			$city = $postdata->BoatLocation->BoatCityName;
			$state = $postdata->BoatLocation->BoatStateCode;
			$country = $postdata->BoatLocation->BoatCountryID;
			
			$country_id = $cm->get_common_field_name('tbl_country', 'id', $country, 'code');
			if ($country_id == 1){
				$state_id = $cm->get_common_field_name('tbl_state', 'id', $state, 'code');
				$state = '';
			}else{
				$state_id = 0;
			}
			//end
			
			$hull_color = '';
			$hull_no = $postdata->BoatHullID;
			
			//hullmaterial
			$hullMaterial = $postdata->BoatHullMaterialCode;
			$hullMaterial_ar = $this->module_check($hullMaterial, 'hull_material_id', 'name', 'tbl_hull_material_cross', 3);
			$hull_material_id = $hullMaterial_ar->existing_id;
			$hullMaterial_to_bs = $hullMaterial_ar->pass_to_bs;
			//end
			
			$hull_type_id = 0;
			$designer = $postdata->DesignerName;
			$sale_usa = 0;
			$flag_country_id = $country_id;
			
			//boat type
			$boattype = $postdata->BoatClassCode;
			$boattype_ar = $this->module_check($boattype[0], 'type_id', 'name', 'tbl_type_cross', 3);
			$type_id = $boattype_ar->existing_id;
			$type_to_bs = $boattype_ar->pass_to_bs;
			//end
			
			//Dimensions & Weight
			$length = $postdata->NormNominalLength / $this->ft_to_meter;
			$length = round($length, 2);
			$loa = 0;
			$beam = $postdata->BeamMeasure;
			$beam = round($beam, 2);
			$draft = $postdata->MaxDraft;
			$draft = round($draft, 2);
			$bridge_clearance = $postdata->BridgeClearanceMeasure;
			$bridge_clearance = round($bridge_clearance, 2);
			$dry_weight = $postdata->DryWeightMeasure;
			$dry_weight = $this->get_numeric_value_from($dry_weight, ' lb');
			$dry_weight = $this->get_numeric_value_from($dry_weight, ',');
			//end
			
			//Engine
			$enginemake = $postdata->Engines[0]->Make;
			$enginemake_ar = $this->module_check($enginemake, 'engine_make_id', 'name', 'tbl_engine_make_cross', 3);
			$engine_make_id = $enginemake_ar->existing_id;
			$enginemake_to_bs = $enginemake_ar->pass_to_bs;
			
			$engine_model = $postdata->Engines[0]->Model;
			$engine_no = $postdata->NumberOfEngines;
					
			$enginetype = $postdata->Engines[0]->Type;
			$enginetype_ar = $this->module_check($enginetype, 'engine_type_id', 'name', 'tbl_engine_type_cross', 3);
			$engine_type_id = $enginetype_ar->existing_id;
			$enginetype_to_bs = $enginetype_ar->pass_to_bs;
			
			$drivetype = $postdata->DriveTypeCode;
			$drivetype_ar = $this->module_check($drivetype, 'drive_type_id', 'name', 'tbl_drive_type_cross', 3);
			$drive_type_id = $drivetype_ar->existing_id;
			$drivetype_to_bs = $drivetype_ar->pass_to_bs;
			
			$fueltype = $postdata->Engines[0]->Fuel;
			$fueltype_ar = $this->module_check($fueltype, 'fuel_type_id', 'name', 'tbl_fuel_type_cross', 3);
			$fuel_type_id = $fueltype_ar->existing_id;
			$fueltype_to_bs = $fueltype_ar->pass_to_bs;
			
			$cruise_speed = $postdata->CruisingSpeedMeasure;
			$cruise_speed = round($cruise_speed, 2) / $this->mph_to_kts;
			$cruise_speed = round($cruise_speed, 2);
			
			$max_speed = $postdata->MaximumSpeedMeasure;
			$max_speed = round($max_speed, 2) / $this->mph_to_kts;
			$max_speed = round($max_speed, 2);
			
			$en_range = $postdata->RangeMeasure;
			$en_range = round($en_range, 2);
			
			$horsepower_individual = $postdata->Engines[0]->EnginePower;
			$horsepower_individual = round($horsepower_individual, 0);
			
			$hours = 0;
			if ($engine_no > 0){
				$en_ar = $postdata->Engines;
				/*foreach($en_ar as $en_row){
					$hours += $en_row->Hours;
				}*/
				
				$hours = $postdata->Engines[0]->Hours;
			}
			
			$joystick_control = 0;
			//end
			
			//Tank Capacities
			$fuel_tanks = $postdata->FuelTankCapacityMeasure;
			$fuel_tanks = round($fuel_tanks, 0);
			$no_fuel_tanks = $postdata->FuelTankCountNumeric;
			
			$fresh_water_tanks = $postdata->WaterTankCapacityMeasure;
			$fresh_water_tanks = round($fresh_water_tanks, 0);
			$no_fresh_water_tanks = $postdata->WaterTankCountNumeric;
			
			$holding_tanks = $postdata->HoldingTankCapacityMeasure;
			$holding_tanks = round($holding_tanks, 0);
			$no_holding_tanks = $postdata->HoldingTankCountNumeric;
			//end
			
			//Accommodations
			$total_cabins = $postdata->CabinsCountNumeric;
			$total_berths = $postdata->DoubleBerthsCountNumeric + $postdata->SingleBerthsCountNumeric + $postdata->TwinBerthsCountNumeric;
			$total_sleeps = 0;
			$total_heads = $postdata->HeadsCountNumeric;
			
			$captains_cabin = 0;
			$crew_cabins = 0;
			$crew_berths = 0;
			$crew_sleeps = 0;
			$crew_heads = 0;
			//end
			
			//Description
			$overview_ar = $postdata->GeneralBoatDescription;
			$overview = '';
			foreach($overview_ar as $overview_row){
				$overview .= $overview_row;
			}
			
			$descriptions_ar = $postdata->AdditionalDetailDescription;
			$descriptions = '';
			foreach($descriptions_ar as $descriptions_row){
				$descriptions .= $descriptions_row;
			}
			//end
			
			//set status_id / or custom label
			$custom_label_id = 0;
				
			if ($status_name == "Sale Pending"){
				$status_id = 1;
				$custom_label_id = 5;
			}elseif ($status_name == "Ordered"){
				$status_id = 1;
				$custom_label_id = 15;
			}elseif ($status_name == "Trade Pending"){
				$status_id = 1;
				$custom_label_id = 16;
			}else{
			
				if ($status_name == "Sold"){
					$status_id = 3;
					$sold_day_no = 0;
					$soldDate = $postdata->SoldDate;
				}elseif ($status_name == "Inactive" OR $status_name == "Delete" OR $status_name == "Expired" OR $status_name == "Rejected"){
					$status_id = 2;
				}else{
					$status_id = 1;
				}
			}
			//end
		
			//Add/Edit Boat
			$dt = date("Y-m-d H:i:s");
			$add_date = $postdata->ItemReceivedDate;
			$boat_id = $this->check_feed_boat_exist($ywboatid, 'yw_id');
			if ($boat_id == 0){
				$sql = "insert into tbl_yacht (company_id, reg_date) values ('". $cm->filtertext($company_id) ."', '". $add_date ."')";
				$boat_id = $db->mysqlquery_ret($sql);
			
				$listing_no = $this->listing_start + $boat_id;
				$sql = "update tbl_yacht set listing_no = '". $listing_no ."' where id = '". $boat_id ."'";
				$db->mysqlquery($sql);
			
				$this->add_delete_yacht_extra_info($boat_id, 1);
			}else{
				$listing_no = $this->get_yacht_no($boat_id);
				$ownboat = $cm->get_common_field_name('tbl_yacht', 'ownboat', $boat_id);
			}
			
			//create folder
			$source = "../yachtimage/rawimage";
			$destination = "../yachtimage/".$listing_no;
			$fle->copy_folder($source, $destination);
			
			// common update	
			$link_url = "";
			$video_id = "";
			
			$charter_id = 1;
			$charter_price = 0;
			$charter_descriptions = 0;
			$charter_descriptions = '';
			$price_per_option_id = 0;
			
			$locationbrokersql = " location_id = '". $location_id ."', broker_id = '". $broker_id ."', ";
			$model_slug = $cm->create_slug($model);
			
			$sql = "update tbl_yacht set". $locationbrokersql ." manufacturer_id = '". $manufacturer_id ."'
			, model = '". $cm->filtertext($model) ."'
			, model_slug = '". $cm->filtertext($model_slug) ."'
			, year = '". $year ."'
			, category_id = '". $category_id ."'
			, condition_id = '". $condition_id ."'
			, price = '". $price ."'
			, price_tag_id = '". $price_tag_id ."'
			
			, address = '". $cm->filtertext($address) ."'
			, city = '". $cm->filtertext($city) ."'
			, state = '". $cm->filtertext($state) ."'
			, state_id = '". $state_id ."'
			, country_id = '". $country_id ."'
			, zip = '". $cm->filtertext($zip) ."'
			, sale_usa = '". $sale_usa ."'
			, flag_country_id = '". $flag_country_id ."'
			
			, vessel_name = '". $cm->filtertext($vessel_name) ."'
			, hull_material_id = '". $hull_material_id ."'
			, hull_type_id = '". $hull_type_id ."'
			, hull_color = '". $cm->filtertext($hull_color) ."'
			, hull_no = '". $cm->filtertext($hull_no) ."'
			, designer = '". $cm->filtertext($designer) ."'
			
			, overview = '". $cm->filtertext($overview) ."'
			, descriptions = '". $cm->filtertext($descriptions) ."'
			
			, link_url = '". $cm->filtertext($link_url)."'
			, video_id = '". $cm->filtertext($video_id)."'
			, custom_label_id = '". $cm->filtertext($custom_label_id)."'
			
			, status_id = '". $status_id ."'
			, charter_id = '". $charter_id ."'
			, charter_price = '". $charter_price ."'
			, price_per_option_id = '". $price_per_option_id ."'
			, yw_id = '". $ywboatid ."'
			, ownboat = '". $ownboat ."'
			, last_updated = '". $currentdate ."'
			, yw_broker_id = '". $ywownerid ."'
			, apikey = '". $cm->filtertext($apikey)."' where id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$latlonar = $geo->getLatLon($boat_id, 1);
			$lat = $latlonar["lat"];
			$lon = $latlonar["lon"];
			
			$sql = "update tbl_yacht set lat_val = '". $cm->filtertext($lat)."', lon_val = '". $cm->filtertext($lon)."' where id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_dimensions_weight set length = '". $length ."'
			, loa = '". $loa ."'
			, beam = '". $beam ."'
			, draft = '". $draft ."'
			, bridge_clearance = '". $bridge_clearance ."'
			, dry_weight = '". $dry_weight ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_engine set engine_make_id = '". $engine_make_id ."'
			, engine_model = '". $cm->filtertext($engine_model) ."'
			, engine_no = '". $engine_no ."'
			, hours = '". $hours ."'
			, engine_type_id = '". $engine_type_id ."'
			, drive_type_id = '". $drive_type_id ."'
			, fuel_type_id = '". $fuel_type_id ."'
			, cruise_speed = '". $cruise_speed ."'
			, max_speed = '". $max_speed ."'
			, en_range = '". $en_range ."'
			, horsepower_individual = '". $horsepower_individual ."'
			, joystick_control = '". $joystick_control ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_tank set fuel_tanks = '". $fuel_tanks ."'
			, no_fuel_tanks = '". $no_fuel_tanks ."'
			, fresh_water_tanks = '". $fresh_water_tanks ."'
			, no_fresh_water_tanks = '". $no_fresh_water_tanks ."'
			, holding_tanks = '". $holding_tanks ."'
			, no_holding_tanks = '". $no_holding_tanks ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$sql = "update tbl_yacht_accommodation set total_cabins = '". $total_cabins ."'
			, total_berths = '". $total_berths ."'
			, total_sleeps = '". $total_sleeps ."'
			, total_heads = '". $total_heads ."'
			, captains_cabin = '". $captains_cabin ."'
			, crew_cabins = '". $crew_cabins ."'
			, crew_berths = '". $crew_berths ."'
			, crew_sleeps = '". $crew_sleeps ."'
			, crew_heads = '". $crew_heads ."' where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			$this->update_sold_yacht_display_date($boat_id, $sold_day_no, $soldDate);
			$this->add_yacht_keywords($boat_id);
			$this->remove_sold_yacht_from_featured($boat_id, $status_id);
			
			//Boat Type
			$sql = "delete from tbl_yacht_type_assign where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			if ($type_id > 0){
				$sql = "insert into tbl_yacht_type_assign (yacht_id, type_id) values ('". $boat_id ."', '". $type_id ."')";
				$db->mysqlquery($sql);
			}
			
			//image
			$this->delete_yacht_existing_image($boat_id);
			$images_ar = $postdata->Images;
			foreach($images_ar as $images_row){
				echo "\n - Processing Image...";
				$filename_main = $images_row->Uri;
				$im_title = $images_row->Caption;
				$i_rank = $images_row->Priority + 1;
				
				if ($filename_main != ""){
					$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename_main);
					if ($wh_ok == "y"){
						
						//new code - for process image
						$processimage = 0;
						$processimage = $this->check_feed_image_exist($filename_main);
						
						if ($processimage == 0){
							echo "\n - 1st check failed for URL:". $filename_main ." ...";
							
							$processimage = $this->check_feed_image_exist($filename_main);
							if ($processimage == 0){
								echo "\n - 2nd check failed for URL:". $filename_main ." ...";
								
								$processimage = $this->check_feed_image_exist($filename_main);
								if ($processimage == 0){
									$processimage = $this->check_feed_image_exist($filename_main);
								}
							}
						}					
						//end
						
						if ($processimage == 1){					
							$i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
							$sql = "insert into tbl_yacht_photo (id, yacht_id, im_title, rank, status_id) values ('". $i_iiid ."', '". $boat_id ."', '". $cm->filtertext($im_title) ."', '". $i_rank ."', 1)";
							$db->mysqlquery($sql);
							
							$filename_ar = explode("/", $filename_main);
							$arcount = count($filename_ar);
							$filename1 = $filename_ar[$arcount - 1];
							
							$target_path_main = "yachtimage/";
							$target_path_main = "../" . $target_path_main;
							
							//copy main image
							$copy_path = $target_path_main . "feed/";
							$copyfile = $copy_path . $filename1;
							copy($filename_main, $copyfile);
							echo " - Copying Image [" . $filename_main . "] to [" . $copyfile . "]";
							
							$target_path_main = $target_path_main . $listing_no . "/";
							$filename_tmp = $copyfile;
							
							//thumbnail image
							$r_width = $cm->yacht_im_width_t;
							$r_height = $cm->yacht_im_height_t;
							$target_path = $target_path_main;
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//big image
							$r_width = $cm->yacht_im_width_b;
							$r_height = $cm->yacht_im_height_b;
							$target_path = $target_path_main . "big/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//bigger image
							$r_width = $cm->yacht_im_width;
							$r_height = $cm->yacht_im_height;
							$target_path = $target_path_main . "bigger/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							//slider image
							$r_width = $cm->yacht_im_width_sl;
							$r_height = $cm->yacht_im_height_sl;
							$target_path = $target_path_main . "slider/";
							$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));
							
							$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
							$db->mysqlquery($sql);	
							
							unlink($filename_tmp);	
						}else{
							echo "\n - 3rd check failed for URL:". $filename_main .". Image removed from list.";
						}
					}
				}
			}//image

			//video
			$sql = "delete from tbl_yacht_video where yacht_id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			$EmbeddedVideoar = $postdata->EmbeddedVideo;
			foreach($EmbeddedVideoar as $EmbeddedVideo_row){
				if ($EmbeddedVideo_row != ""){					
					$i_rank = 1;
					$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);
					
					$EmbeddedVideo_ar = explode("|", $EmbeddedVideo_row);
					$MediaSourceURI_ori = $EmbeddedVideo_ar[0];
					$f_ext = $fle->get_file_extension($MediaSourceURI_ori);
					if ($f_ext != ".flv"){
						$MediaAttachmentTitle = $EmbeddedVideo_ar[1];
						$MediaSourceURI = $cm->create_youtube_share_url($MediaSourceURI_ori);				
						if ($MediaSourceURI != ""){
							$video_type = 1;
							$video_id = $cm->get_youtube_video_code($cm->filtertextdisplay($MediaSourceURI));
						}else{
							$video_id = "";
							$video_type = 4;
							$MediaSourceURI = $MediaSourceURI_ori;
						}

						$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, name, link_url, video_id, rank, status_id) values ('". $i_iiid ."', '". $boat_id ."', '". $video_type ."', '". $cm->filtertext($MediaAttachmentTitle) ."', '". $cm->filtertext($MediaSourceURI) ."', '". $cm->filtertext($video_id)."', '". $i_rank ."', 1)";
						$db->mysqlquery($sql);
					}
				}
			}
			
			$hullType_to_bs = '';
		}
		
		$returnval = (object) array(
            'manufacturer_to_bs' => $manufacturer_to_bs,
            'hullMaterial_to_bs' => $hullMaterial_to_bs,
			'hullType_to_bs' => $hullType_to_bs,
			'type_to_bs' => $type_to_bs,
			'enginemake_to_bs' => $enginemake_to_bs,
			'enginetype_to_bs' => $enginetype_to_bs,
			'drivetype_to_bs' => $drivetype_to_bs,
			'fueltype_to_bs' => $fueltype_to_bs,
			'broker_to_bs' => $broker_to_bs
        );
		return $returnval;
	}
	
	public function manage_yw_feed_assign_user($VehicleRemarketingrow){
		global $db, $cm, $fle;
		$currentdate = date("Y-m-d");
		$ywboatid = $VehicleRemarketingrow->VehicleRemarketingHeader[0]->DocumentIdentificationGroup[0]->DocumentIdentification[0]->DocumentID;
		$VehicleRemarketingBoatLineItem = $VehicleRemarketingrow->VehicleRemarketingBoatLineItem[0];
		
		$partyid_1 = $VehicleRemarketingBoatLineItem->DealerParty[0]->PartyID;
		$ywownerid = round($VehicleRemarketingBoatLineItem->DealerParty[0]->SpecifiedOrganization[0]->PrimaryContact[0]->ID, 0);
		if ($ywownerid == 0){
			$ywownerid = $partyid_1;
		}
		
		$ownboat = 1;
		
		//default broker
		$broker_id = 1;
		$company_id = 1;
		$location_id = 1;
		
		$sql = "select id, company_id, location_id from tbl_user where yw_broker_id = '". $cm->filtertext($ywownerid) ."'";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$row = $result[0];
			$broker_id = $row["id"];
			$company_id = $row["company_id"];
			$location_id = $row["location_id"];
		}
		//end

        echo "YachtWorldID [" . $ywboatid . "]";
		
		//Add/Edit Boat
		$dt = date("Y-m-d H:i:s");
		$boat_id = $this->check_feed_boat_exist($ywboatid, 'yw_id');
		if ($boat_id > 0){
			$sql = "update tbl_yacht set location_id = '". $location_id ."', broker_id = '". $broker_id ."' where id = '". $boat_id ."'";
			$db->mysqlquery($sql);
			
			echo '<p>'. $sql .'</p>';
		}
	}
	
	public function make_yw_feed_inactive(){
		global $db, $cm;
		
		$sql = "select * from tbl_yacht where yw_id > 0 and last_updated < DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)";
		$result = $db->fetch_all_array($sql);
		$counter = 0;
		foreach($result as $row){
			$yacht_id = $row['id'];
			
			//make inactive
			$sql_update = "update tbl_yacht set status_id = 2 where id = '". $yacht_id ."'";
			$db->mysqlquery($sql_update);
			
			//delete images
			$sql_update = "select imgpath from tbl_yacht_photo where yacht_id = '". $yacht_id ."'";
			$result_update = $db->fetch_all_array($sql_update);
			foreach($result_update as $row_update){
				$fimg1 = $row_update['imgpath'];
				$this->delete_yacht_image($fimg1, $yacht_id);
			}
			$sql_update = "delete from tbl_yacht_photo where yacht_id = '". $yacht_id ."'";
        	$db->mysqlquery($sql_update);
			
			//delete video
			$sql_update = "select videopath from tbl_yacht_video where yacht_id = '". $yacht_id ."'";
			$result_update = $db->fetch_all_array($sql_update);
			foreach($result_update as $row_update){
				$fimg1 = $row_update['videopath'];
				$this->delete_yacht_video($fimg1);
			}
			
			$sql_update = "delete from tbl_yacht_video where yacht_id = '". $yacht_id ."'";
			$db->mysqlquery($sql_update);
			
			$counter++;
		}
		
		return $counter;
	}
	
	public function manage_yw_feed_sold($VehicleRemarketingrow){
		global $db, $cm, $fle;
		$currentdate = date("Y-m-d");
		$ywboatid = $VehicleRemarketingrow->VehicleRemarketingHeader[0]->DocumentIdentificationGroup[0]->DocumentIdentification[0]->DocumentID;
		$VehicleRemarketingBoatLineItem = $VehicleRemarketingrow->VehicleRemarketingBoatLineItem[0];
		
		$partyid_1 = $VehicleRemarketingBoatLineItem->DealerParty[0]->PartyID;
		$ywownerid = round($VehicleRemarketingBoatLineItem->DealerParty[0]->SpecifiedOrganization[0]->PrimaryContact[0]->ID, 0);
		if ($ywownerid == 0){
			$ywownerid = $partyid_1;
		}
		
		$price = $VehicleRemarketingBoatLineItem->PricingABIE[0]->Price[0]->ChargeAmount;
		$status_name = $VehicleRemarketingBoatLineItem->SalesStatus;
		
		$soldDate = '';
		$sold_day_no = 0;
		$enhanced = 0;
        echo "YachtWorldID [" . $ywboatid . "]";
		$VehicleRemarketingBoat = $VehicleRemarketingBoatLineItem->VehicleRemarketingBoat[0];
			
		if ($status_name == "Sold"){
			$status_id = 3;
			$sold_day_no = 365;
			$soldDate = $VehicleRemarketingBoatLineItem->SoldDate;
			
			$boat_id = $this->check_feed_boat_exist($ywboatid, 'yw_id');
			if ($boat_id > 0){
				$this->update_sold_yacht_display_date($boat_id, $sold_day_no, $soldDate);
				echo '<p>ID: '. $ywboatid .' - SD: '. $soldDate .'</p>';
			}
		}
	}

	public function update_feed_image($VehicleRemarketingrow, $feedtype = 1){
		global $db, $cm, $fle;
		if ($feedtype == 1){
			//XML
		}else{
			//JSON
			$postdata = $VehicleRemarketingrow;
			$ywboatid = $postdata->DocumentID;
			$boat_id = $this->check_feed_boat_exist($ywboatid, 'yw_id');
			if ($boat_id > 0){
				$listing_no = $this->get_yacht_no($boat_id);
				
				//create folder
				$source = "../yachtimage/rawimage";
				$destination = "../yachtimage/".$listing_no;
				$fle->copy_folder($source, $destination);
				//end
				
				$this->delete_yacht_existing_image($boat_id);
				$images_ar = $postdata->Images;
				foreach($images_ar as $images_row){
					echo "\n - Processing Image...";
					$filename_main = $images_row->Uri;
					$im_title = $images_row->Caption;
					$i_rank = $images_row->Priority + 1;

					if ($filename_main != ""){
						$wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename_main);
						if ($wh_ok == "y"){

							//new code - for process image
							$processimage = 0;
							$processimage = $this->check_feed_image_exist($filename_main);

							if ($processimage == 0){
								echo "\n - 1st check failed for URL:". $filename_main ." ...";

								$processimage = $this->check_feed_image_exist($filename_main);
								if ($processimage == 0){
									echo "\n - 2nd check failed for URL:". $filename_main ." ...";

									$processimage = $this->check_feed_image_exist($filename_main);
									if ($processimage == 0){
										$processimage = $this->check_feed_image_exist($filename_main);
									}
								}
							}					
							//end

							if ($processimage == 1){					
								$i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
								$sql = "insert into tbl_yacht_photo (id, yacht_id, im_title, rank, status_id) values ('". $i_iiid ."', '". $boat_id ."', '". $cm->filtertext($im_title) ."', '". $i_rank ."', 1)";
								$db->mysqlquery($sql);

								$filename_ar = explode("/", $filename_main);
								$arcount = count($filename_ar);
								$filename1 = $filename_ar[$arcount - 1];

								$target_path_main = "yachtimage/";
								$target_path_main = "../" . $target_path_main;

								//copy main image
								$copy_path = $target_path_main . "feed/";
								$copyfile = $copy_path . $filename1;
								copy($filename_main, $copyfile);
								echo " - Copying Image [" . $filename_main . "] to [" . $copyfile . "]";

								$target_path_main = $target_path_main . $listing_no . "/";
								$filename_tmp = $copyfile;

								//thumbnail image
								$r_width = $cm->yacht_im_width_t;
								$r_height = $cm->yacht_im_height_t;
								$target_path = $target_path_main;
								$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

								//big image
								$r_width = $cm->yacht_im_width_b;
								$r_height = $cm->yacht_im_height_b;
								$target_path = $target_path_main . "big/";
								$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

								//bigger image
								$r_width = $cm->yacht_im_width;
								$r_height = $cm->yacht_im_height;
								$target_path = $target_path_main . "bigger/";
								$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

								//slider image
								$r_width = $cm->yacht_im_width_sl;
								$r_height = $cm->yacht_im_height_sl;
								$target_path = $target_path_main . "slider/";
								$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1));

								$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
								$db->mysqlquery($sql);	

								unlink($filename_tmp);	
							}else{
								echo "\n - 3rd check failed for URL:". $filename_main .". Image removed from list.";
							}
						}
					}
				}
			}
		}
	}
	//end
	
	//display # of new boats
	public function display_new_boats($displayoption = 1, $ajaxpagination = 0){
		global $db, $cm;
        $returntext = '';		
		$limit = $cm->get_systemvar('NBNUM');
		$p = 1;
		
		$query_sql = "select *,";
        $query_form = " from tbl_yacht,";
        $query_where = " where";

        $query_where .= " status_id IN (1,3) and";
        $query_where .= " display_upto >= CURDATE() and";
		
        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by reg_date desc limit 0, " . $limit;
        $result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			if ($displayoption == 3){
				//map view
                $mapdataar = $this->display_yacht_map_view($result);
			}else{
				//list view or grid view
                $class_ex = '';
                $imoption = 0;
                if ($displayoption == 2){
                    $class_ex = ' list-view';
                }

                $extraclass = 'class="no-transition hidden-listing"';
                if ($ajaxpagination == 0){
                    $extraclass = '';
                	$returntxt .= '
                    <ul id="listingholder" class="product-list'. $class_ex .'">
                    ';
                }

                foreach($result as $row){
					$returntxt .= $this->display_yacht($row, $displayoption, $extraclass, $compareboat, $charter);
                }
				
				if ($ajaxpagination == 0){

                    $returntxt .= '
                    </ul>
                    <div class="clear"></div>
                    ';
                }
			}
			
			$returnval[] = array(
                'pg' => $p,
                'button_no' => $button_no,
                'totalrec' => $foundm,
                'displayoption' => $displayoption,
                'doc' => $returntxt,
                'mapdoc' => $mapdataar
            );
		}else{//found
			global $frontend;
			$returntxt = '<p>'. $cm->get_systemvar('BTNFD') .'</p>'. $frontend->display_boat_finder_form(1);
            $returnval[] = array(
                'pg' => 1,
                'button_no' => 0,
                'totalrec' => 0,
                'displayoption' => $displayoption,
                'doc' => $returntxt,
                'mapdoc' => array()
            );
		}
		
		return json_encode($returnval);
	}
	
	public function display_new_boats_main(){
		global $db, $cm;
		$retval = json_decode($this->display_new_boats(1));
		$returntext = '
		<div id="filtersection" class="mostviewed">
		'. $retval[0]->doc .'
		<div class="clear"></div>
		</div>
		';
		
		return $returntext;
	}
	//end
	
	//Homepage Location display
	public function display_location_homepage($argu = array()){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_location_office where status_id = 1 order by default_location desc, reg_date desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$returntext .= '
			<div class="homesection4 clearfixmain">
				<div class="container clearfixmain">					
					<div class="homeleft3"><a href="'. $cm->get_page_url(20, "page") .'"><img src="'. $cm->folder_for_seo .'images/map.png" /></a></div>
					<div class="homeright3">
						<ul class="mapandlocation">
			';
						foreach($result as $row){
							foreach($row AS $key => $val){
								${$key} = $cm->filtertextdisplay($val);
							}
							
							$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id, $zip);
							$location_page_url = $cm->get_page_url($slug, 'locationprofile');
											
							$returntext .= '
							<li>
								<h3><a href="'. $location_page_url .'">'. $name .'</a></h3>
								<div class="locaddress">'. $address .'</div>
								<div class="locaddress">'. $addressfull .'</div>
								<div class="locphone"><a class="tel" href="tel:'. $phone .'">'. $phone .'</a></div>
							</li>
							';
						}
			
			$returntext .= '
						</ul>
					</div>					
				</div>
			</div>
			';
		}
		
		return $returntext;
	}
	//end
	
	//Footer Location display
	public function display_location_footer($argu = array()){
		global $db, $cm;
		$returntext = '';
		
		$option = round($argu["option"], 0);
		
		$sql = "select * from tbl_location_office where status_id = 1 order by default_location desc, reg_date desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			
			if ($option == 0){
				$returntxt .= '<ul>';
			}
			
			$returntext .= '
			<li>
			<h3>Locations</h3>
			<ul class="bespokefooter">
			';
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}

				$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
				$location_page_url = $cm->get_page_url($slug, 'locationprofile');

				$returntext .= '
				<li><a href="'. $location_page_url .'">'. $addressfull .'</a></li>
				';
			}
			
			$returntext .= '
			</ul>
			</li>
			';
			
			if ($option == 0){
				$returntxt .= '</ul>';
			}
		}
		
		return $returntext;
	}
	//end
	
	//Homepage - Featured model
	public function display_featured_model($argu = array()){
		global $ymclass;		
	  	$returntext = '';				
		$manufacturer_id = round($argu["manufacturer_id"], 0);
		if ($manufacturer_id > 0){		
			$retval = json_decode($ymclass->get_manufacturer_model_list_featured($manufacturer_id));
			$found = $retval->found;
			if ($found > 0){				
				if ($innerpage == 1){
					$returntext = '				
					'. $retval->doc .'
					<div class="clear"></div>
					';
				}else{			
					$returntext = '
					<div class="offeringnewboats">
						<div class="container">
							<h2>Representing J-Boats in New Jersey and Eastern Pennsylvania</h2>
							<div class="offeringnewboatsholder">
								'. $retval->doc .'
								<div class="clear"></div>
							</div>
							<a class="modelprev" href="#"></a><a class="modelnext" href="#"><span>next</span></a>
							<div class="clear"></div>
						</div>
					</div>
					';
					
					$returntext .= '
					<script type="text/javascript">
					$(document).ready(function(){
						if (jQuery(".offeringnewboatsholder .offerboatlist").length > 0){
							jQuery(".offeringnewboatsholder .offerboatlist").carouFredSel({
								align: false,
								auto: false,	
								responsive	: true,
								prev: ".modelprev",
								next: ".modelnext",
								pagination: false,		
								scroll: {
									items: 1,
									timeoutDuration: 4000,	
									duration: 800,									
									easing: "linear",
									pauseOnHover: "immediate"
								},
								items		: {
										visible: {
											min: 1,
											max: 4
										},
										height: "auto"
										
								}
							});
						}
					});
					</script>
					';
				}
			}
		}
		return $returntext;
	}
	//end
	
	//import YC MM data to inventory - media
	public function import_yc_mm_media($boat_id, $model_id){
		global $db, $cm, $ymclass;
		$mainsite_ar = $cm->get_table_fields('tbl_mainsite', 'site_url', 1);
		$main_website_url = $mainsite_ar[0]['site_url'];
		$listing_no = $this->get_yacht_no($boat_id);
		
		$media_ar = json_decode($ymclass->get_manufacturer_model_media_list_import($model_id));
				
		//images
		$image_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_photo where yacht_id = '". $boat_id ."'") + 1;
		$images_ar = $media_ar->images;
		foreach($images_ar as $images_row){
			$imgpath = $images_row->imgpath;
			if ($imgpath != ""){
				$filename1 = $boat_id . "_" . $imgpath;
				
				$im_title = $images_row->im_title;
				$im_descriptions = $images_row->im_descriptions;
				$im_status_id = $images_row->im_status_id;
				
				$i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
				$sql = "insert into tbl_yacht_photo (id, yacht_id, im_title, im_descriptions, status_id, rank) values ('". $i_iiid ."', '". $boat_id ."', '". $cm->filtertext($im_title) ."', '". $cm->filtertext($im_descriptions) ."', '". $im_status_id ."', '". $image_rank ."')";
				$db->mysqlquery($sql);
				
				$filepath = $main_website_url . '/manufacturerimage/modelimage/';
				
				//thumb
				$filename_main = $filepath . $imgpath;
				$copy_path = "../yachtimage/" . $listing_no . '/';
				$copyfile = $copy_path . $filename1;
				copy($filename_main, $copyfile);
				
				//big
				$filename_main = $filepath . 'big/' . $imgpath;
				$copy_path = "../yachtimage/" . $listing_no . '/big/';
				$copyfile = $copy_path . $filename1;
				copy($filename_main, $copyfile);


				
				//bigger
				$filename_main = $filepath . 'bigger/' . $imgpath;
				$copy_path = "../yachtimage/" . $listing_no . '/bigger/';
				$copyfile = $copy_path . $filename1;
				copy($filename_main, $copyfile);
				
				//slider
				$filename_main = $filepath . 'bigger/' . $imgpath;
				$copy_path = "../yachtimage/" . $listing_no . '/slider/';
				$copyfile = $copy_path . $filename1;
				copy($filename_main, $copyfile);
				
				$sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
				$db->mysqlquery($sql);			
				$image_rank++;
				
			}
		}
		
		//Video
		$video_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_video where yacht_id = '". $boat_id ."'") + 1;
		$video_ar = $media_ar->videos;
		foreach($video_ar as $video_row){
			$video_type = $video_row->video_type;
			$video_name = $video_row->name;
			$videopath = $video_row->videopath;
			$link_url = $video_row->link_url;
			$video_id = $video_row->video_id;
			$video_status_id = $video_row->status_id;
			
			$i_iiid = $cm->get_unq_code("tbl_yacht_video", "id", 10);
			$sql = "insert into tbl_yacht_video (id, yacht_id, video_type, name, link_url, video_id, status_id, rank) values ('". $i_iiid ."', '". $boat_id ."', '". $video_type ."', '".$cm->filtertext($video_name)."', '".$cm->filtertext($link_url)."', '".$cm->filtertext($video_id)."', '". $video_status_id ."', '". $video_rank ."')";
			$db->mysqlquery($sql);
			
			if ($videopath != ""){
				if ($video_type == 2){
					$filename1 = $boat_id . "_" . $videopath;
					$filename_main = $main_website_url . '/manufacturerimage/modelvideo/yachtvideo/' . $videopath;
					
					$copy_path = "../yachtvideo/";
					$copyfile = $copy_path . $filename1;
					copy($filename_main, $copyfile);
					
					$sql = "update tbl_yacht_video set videopath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
					$db->mysqlquery($sql);
				}
			}
			$video_rank++;			
		}
		
		//Attachment Files
		$attachment_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_file where yacht_id = '". $boat_id ."'") + 1;
		$attachment_ar = $media_ar->attachment;
		foreach($attachment_ar as $attachment_row){
			$title = $attachment_row->title;
			$filepath = $attachment_row->filepath;
			$originalname = $attachment_row->originalname;
			
			$at_status_id = 1;
			$i_iiid = $cm->campaignid(30) . $boat_id;
			$sql = "insert into tbl_yacht_file (id, yacht_id, title, status_id, rank) values ('". $i_iiid ."', '". $boat_id ."', '".$cm->filtertext($title)."', '". $at_status_id ."', '". $attachment_rank ."')";
			$db->mysqlquery($sql);
			
			if ($filepath != ""){
				$filename1 = $boat_id . "_" . $filepath;
				$filename_main = $main_website_url . '/manufacturerfiles/' . $filepath;
				
				$copy_path = "../yachtfiles/";
				$copyfile = $copy_path . $filename1;
				copy($filename_main, $copyfile);
				
				$sql = "update tbl_yacht_file set filepath = '".$cm->filtertext($filename1)."', originalname = '".$cm->filtertext($originalname)."' where id = '". $i_iiid ."'";
				$db->mysqlquery($sql);
			}
			$attachment_rank++;
		}
	}
	//end
	
	//Boats with condition = new - based on manufracture
	public function eligible_make(){
		$manufacturer_ar = array(644,475);
		return $manufacturer_ar;
	}
	
	public function get_page_id_connected_to_make($manufacturer_id){
		global $db, $cm;
		$pageid = $cm->get_common_field_name('tbl_page', 'id', $manufacturer_id, 'connected_manufacturer_id');
		return $pageid;
	}
	
	public function assign_new_boats(){
		global $db, $cm;
		$returntext = '';
		$rc_count = 0;
		$manufacturer_ar = $this->eligible_make();

		foreach($manufacturer_ar as $manufacturerid){
			
			$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'name', $manufacturerid);
			$manufacturerarname = $manufacturerar[0]["name"];
			
			$query_sql = "select distinct a.id, a.listing_no, a.home_page_new_boats,";
			$query_form = " from tbl_yacht as a,";
			$query_where = " where";
			
			$query_form .= " tbl_yacht_dimensions_weight as c,";
			$query_where .= " a.id = c.yacht_id and";

			$query_where .= " a.manufacturer_id = '". $manufacturerid ."' and";
			$query_where .= " a.condition_id = 1 and";
			$query_where .= " a.status_id = 1 and";
			
			$oby = "c.length desc";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, " and");
			
			$query_where .= " order by ".$oby;
			$sql = $query_sql . $query_form . $query_where;
			$result = $db->fetch_all_array($sql);
			
			$returntext .= '
			<div class="locationorder">
				<h3>'. $manufacturerarname .'</h3>
				<ul class="recordorder nosort">
			';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$ppath = $this->get_yacht_first_image($id);
				$yacht_title = $this->yacht_name($id);
				
				$target_path_main = 'yachtimage/'. $listing_no .'/big/';
				$imgpath_d = '<img src="'. $cm->folder_for_seo . $target_path_main . $ppath .'" border="0" />';
				
				$bck = '';
				if ($home_page_new_boats == 1){
					$bck = ' checked="checked"';	
				}
				
				$returntext .= '
				<li id="item-'. $id .'">
				<div class="top">'. $imgpath_d .'</div>
				<div class="middle" title="'. $name .'">'. $yacht_title .'</div>
				<div class="bottom">Assign? <input class="checkbox assignboat" type="checkbox" manufacturer_id="'. $manufacturerid .'" name="home_page_new_boats'. $id .'" id="home_page_new_boats'. $id .'" value="'. $id .'"'. $bck .' /></div>
				</li>
				';
				
				$rc_count++;		
			}
			
			$returntext .= '
				</ul>
				<div class="clearfix"></div>
			</div>
			';
		}		
		return $returntext;
	}
	
	public function assign_new_boats_sub($argu = array()){
		global $db, $cm;
		$boat_id = round($argu["boat_id"], 0);
		$home_page_new_boats = round($argu["home_page_new_boats"], 0);
		
		$sql = "update tbl_yacht set home_page_new_boats = '".$cm->filtertext($home_page_new_boats)."' where id = '". $boat_id ."'";
		$db->mysqlquery($sql);
	}
	
	public function display_boats_new_by_manufracture(){
		global $db, $cm;
		$datatext = '';
		$manufacturer_ar = $this->eligible_make();
		
		$mainsite_ar = $cm->get_table_fields('tbl_mainsite', 'site_url', 1);
		$main_website_url = $mainsite_ar[0]['site_url'];				
		
		foreach($manufacturer_ar as $manufacturerid){
			
			//collect manufacturer details
			$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'name, logo_image', $manufacturerid);
			$manufacturerarname = $manufacturerar[0]["name"];
			$manufacturerarlogo_image = $manufacturerar[0]["logo_image"];
			if ($manufacturerarlogo_image == ""){ $manufacturerarlogo_image = 'no.jpg'; }
			//$manufacturerarlogo_image = $main_website_url . '/manufacturerimage/' . $manufacturerarlogo_image;
			$manufacturerarlogo_image = $cm->folder_for_seo . 'images/make/' . $manufacturerid. '.png';
			
			$query_sql = "select *,";
			$query_form = " from tbl_yacht,";
			$query_where = " where";
			
			$query_where .= " manufacturer_id = '". $manufacturerid ."' and";
			$query_where .= " condition_id = 1 and";
	
			//$query_where .= " (a.status_id = 1 or a.status_id = 3) and";
			//$query_where .= " ( a.display_upto >= CURDATE() OR a.display_upto = '0000-00-00' ) and";
			$query_where .= " status_id = 1 and";
			$query_where .= " home_page_new_boats = 1 and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by RAND() limit 0, 1";
			$result = $db->fetch_all_array($sql);
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$imagefolder = 'yachtimage/'. $listing_no .'/big/';
				$ppath = $this->get_yacht_first_image($id);
				$details_url = $cm->get_page_url($id, "yacht");
				
				$datatext .= '
				<li>
					<div class="topimg"><a href="'. $details_url .'"><img src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt=""></a></div>
					<div class="botimg"><a href="'. $details_url .'"><img src="'. $manufacturerarlogo_image .'" alt=""></a></div>				
				</li>		
			';
			}			
		}
		
		if ($datatext != ""){
			$returntext = '
			<h2>New <span>Boats</span></h2>
			<ul class="newboats">
			'. $datatext .'
			</ul>
			<div class="clearfix"></div>
			';
		}else{
			$returntext = '';
		}
	
		return $returntext;
	}
	
	public function display_boats_new_by_manufracture_yc(){
		global $db, $cm, $ymclass;
		$datatext = '';
		$manufacturer_ar = $this->eligible_make();
		
		$mainsite_ar = $cm->get_table_fields('tbl_mainsite', 'site_url', 1);
		$main_website_url = $mainsite_ar[0]['site_url'];				
		
		foreach($manufacturer_ar as $manufacturerid){
			
			//collect manufacturer details
			$manufacturerar = $cm->get_table_fields('tbl_manufacturer', 'name, logo_image', $manufacturerid);
			$manufacturerarname = $manufacturerar[0]["name"];
			$manufacturerarlogo_image = $manufacturerar[0]["logo_image"];
			if ($manufacturerarlogo_image == ""){ $manufacturerarlogo_image = 'no.jpg'; }
			//$manufacturerarlogo_image = $main_website_url . '/manufacturerimage/' . $manufacturerarlogo_image;
			$manufacturerarlogo_image = $cm->folder_for_seo . 'images/make/' . $manufacturerid. '.png';
			
			$query_sql = "select distinct a.*,";
			$query_form = " from tbl_manufacturer_model_home_assign as a,";
			$query_where = " where";
			
			$query_where .= " a.make_id = '". $manufacturerid ."' and";
			//$query_where .= " a.status_id = 1 and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where;
			$sql = $sql." order by RAND() limit 0, 1";
			$result = $db->fetch_all_array($sql);
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
	
				$model_ar = $ymclass->get_manufacturer_model_details_by_id($manufacturerid, $model_id);
				$model_ar = json_decode($model_ar);
				$model_found = $model_ar->found;
				if ($model_found > 0){
					$model_row = $model_ar->docarray;
					//$details_url = $model_row[0]->details_url;
					$imgurl = $model_row[0]->imgurl;
					$connected_page_id = $this->get_page_id_connected_to_make($manufacturerid);
					$details_url = $cm->get_page_url($connected_page_id, 'page');
					
					$datatext .= '
					<li>
						<div class="topimg"><a href="'. $details_url .'">'. $imgurl .'</a></div>
						<div class="botimg"><a href="'. $details_url .'"><img src="'. $manufacturerarlogo_image .'" alt=""></a></div>				
					</li>		
					';
				}
			}			
		}
		
		if ($datatext != ""){
			$returntext = '
			<div class="homesection1">
			<div class="container">
				<h2>New <span>Yachts</span></h2>
				<ul class="newboats">
				'. $datatext .'
				</ul>
				<div class="clearfix"></div>
			</div>
			</div>	
			';
		}else{
			$returntext = '';
		}
	
		return $returntext;
	}
	//end
	
	//Make list combo - YC Data
	public function get_make_list_combo($make_id = 0){
		global $db, $cm, $ymclass;
		
		$makear = $ymclass->get_assign_manufacturer_list_raw();
		$makear = json_decode($makear);		
		
		$returntxt = '';
		foreach($makear as $vrow){
			$c_id = $vrow->id;
			$cname = $vrow->name;	
			$bck = '';
			if ($make_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
		}
		return $returntxt;
	}
	//end
	
	//Make list combo - YC Data
	public function get_model_group_list_combo($make_id, $group_id = 0){
		global $db, $cm, $ymclass;
		
		$makear = $ymclass->get_manufacturer_model_group_list_raw($make_id);
		$makear = json_decode($makear);		
		
		$returntxt = '';
		foreach($makear as $vrow){
			$c_id = $vrow->id;
			$cname = $vrow->name;	
			$bck = '';
			if ($group_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';
		}
		return $returntxt;
	}
	//end
	
	//Page id collction for yc model details page
	public function get_page_id_for_yc_model($param = array()){
		global $db, $cm;
		
		//param
		$makeid = round($param["makeid"], 0);
		$modelgroupid = round($param["modelgroupid"], 0);
		//end
		
		$sql = "select id as ttl from tbl_page where connected_manufacturer_id = '". $makeid ."'";
		if ($modelgroupid > 0){
			$sql .= " and connected_group_id = '". $modelgroupid ."'";
		}

		$pageid = $db->total_record_count($sql);
		if ($pageid == 0){
			$pageid = $cm->get_common_field_name('tbl_page', 'id', $makeid, 'connected_manufacturer_id');
		}
		
		return $pageid;
	}
	
	//Boat list - sql checking
	public function get_boat_check_sql($sqloption = 0, $dstat = 0, $argu = array()){
		global $db, $cm;
		
		$currenturl = $_SERVER["REQUEST_URI"];
		$_SESSION["listing_file_name"] = $currenturl;
		$freshstart = $argu["freshstart"];
		$to_check_val = $cm->get_actual_url_from_header() . $_SERVER["REQUEST_URI"];
		
		if ($freshstart == 1){
			$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
			$_SESSION["s_currenturl"] = $currenturl;
		}else{
			/*
			$sql = $cm->get_data_set(array("to_check_val" => $to_check_val, "section_for" => 2));
			if ($sql == ""){
				$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
				$_SESSION["s_currenturl"] = $currenturl;
			}*/
			
			
			/*if (isset($_SESSION["s_currenturl"]) AND $_SESSION["s_currenturl"] == $currenturl ){
				$sql = $cm->get_data_set(array("to_check_val" => $to_check_val, "section_for" => 2));
				if ($sql == ""){
					$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
					$_SESSION["s_currenturl"] = $currenturl;
				}
			}else{				
				$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
				$_SESSION["s_currenturl"] = $currenturl;
			}*/
			
			if (isset($_SESSION["visited_boat_page"]) AND $_SESSION["visited_boat_page"] == 1 ){
				$sql = $cm->get_data_set(array("to_check_val" => $to_check_val, "section_for" => 2));
				if ($sql == ""){
					$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
					$_SESSION["created_sortop"] = 2;
					$_SESSION["created_orderbyop"] = 2;				
				}
				$_SESSION["visited_boat_page"] = 0;
			}else{				
				$sql = $this->create_yacht_sql($sqloption, $dstat, $argu);
				$_SESSION["created_sortop"] = 2;
				$_SESSION["created_orderbyop"] = 2;
			}
		}
		
		//update data session
		$param_page = array(
			"to_get_val" => $sql,
			"section_for" => 2
		);
		$cm->insert_data_set($param_page);
		//end
		
		$returnar = array("sql" => $sql, "to_check_val" => $to_check_val);
		return json_encode($returnar);
	}
	
	//Boat List - Shortcode
	public function display_boat_top_text($param = array()){
		global $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		$foundm = $param["foundm"];
		$listsort = $param["listsort"];
		$listorder = $param["listorder"];
		$compareboat = $param["compareboat"];
		
		$option_retval = json_decode($this->display_view_option($foundm, $compareboat));
		$dval = $option_retval->dval;
		
		$sort_retval = json_decode($this->display_sort_option($listsort, $listorder));							
		$sortop = $sort_retval->sortop;
		$orderbyop = $sort_retval->orderbyop;
		
		$searchtool_loggedin = '';
		if ($loggedin_member_id > 0){
			$searchtool_loggedin = '
			<li><a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'popsavesearch/" class="savesearchlink icon-savesearch" data-type="iframe">Save this search</a></li>
			<li><a href="'. $cm->folder_for_seo .'searches/" class="icon-searchsearches">Saved searches</a></li>
			';
		}
		
		$boattoptext = '
		<div class="header-bottom-bg boatlistingsearchtop clearfixmain">
			<div class="header-bottom-inner clearfixmain">
				<div class="sch">
					<a href="javascript:void(0);" class="stools"><span>Search tools</span></a>
					<ul>
						'. $searchtool_loggedin .'
						<li><a href="'. $cm->folder_for_seo .'?fcapi=clearfilters" class="icon-clear clearallfilter">Clear all filters</a></li>
					</ul>
				</div>
				
				<div class="vp">'. $option_retval->doc .'</div>
				
				<div class="res">
					<div class="spinnersmall"><span class="reccounterupdate">'. $foundm .'</span> result(s)</div>
					<div class="sorttool">'. $sort_retval->doc .'</div>
				</div>
				
			</div>
		</div>
		';
		
		return $boattoptext;
	}
	
	public function display_boat_list($argu = array()){
		global $db, $cm;
		$returntext = '';
		
		if (!is_array($argu)){
			$argu = array();
		}
		
		$p_ar = $_REQUEST;
		foreach($p_ar AS $key => $val){
			$argu["$key"] = $val;
		}
		
		$makeid = round($argu["makeid"], 0);
		$listsort = round($argu["listsort"], 0);
		$listorder = round($argu["listorder"], 0);
		$nosearchcol = round($argu["nosearchcol"], 0);
		$mostviewed = round($argu["mostviewed"], 0);
		$sp_typeid = round($argu["sp_typeid"], 0);
		$compareboat = 0;
		
		$disable_make_search = 0;
		if ($makeid > 0){
			$disable_make_search = 1;
		}
	
		//$sql = $this->create_yacht_sql(0, 0, $argu);
		$boat_check_sql = json_decode($this->get_boat_check_sql(0, 0, $argu));
		$to_check_val = $boat_check_sql->to_check_val;
		$sql = $boat_check_sql->sql;
		
		$foundm = $this->total_yach_found($sql);
		if ($mostviewed > 0){
			if ($foundm > $this->mostviewdno){
				//$foundm = $this->mostviewdno;
			}
		}
		
		$_SESSION["shortcode_used"] = 1;		
		
		$boat_top_text = $this->display_boat_top_text(array("foundm" => $foundm, "listsort" => $listsort, "listorder" => $listorder, "compareboat" => $compareboat));	
		$dval = $this->get_selected_display_option($foundm);
		
		$sort_retval = json_decode($this->get_selected_sort_option($listsort, $listorder));						
		$sortop = $sort_retval->sortop;
		$orderbyop = $sort_retval->orderbyop;
		
		$param = array(
			"compareboat" => $compareboat,
			"displayoption" => $dval,
			"ajaxpagination" => 0,
			"dstat" => 0,
			"sortop" => $sortop,
			"orderbyop" => $orderbyop,
			"to_check_val" => $to_check_val,
			"mostviewed" => $mostviewed,
			"sp_typeid" => $sp_typeid
		);
		$retval = json_decode($this->display_yacht_listing(1, $param));
		
		if ($nosearchcol == 1){		
			$boatdata = $soldboatsearch . '<div class="boatlistingmain clearfixmain">' . $boat_top_text . '
			<div class="boatlisting-detail clearfixmain">
				<div id="listingholdermain" class="clearfixmain" to_check_val="'. $to_check_val .'">
					'. $retval[0]->doc .'
				</div>
			</div>
			</div>
			';
		}else{
			$param = array(
				"searchtemplate" => 1,
				"searchoption" => 1,
				"rawtemplate" => 0,
				"apinoselection" => $argu["apinoselection"],
				"searchtypeselection" => $argu["searchtypeselection"],
				"disable_make_search" => $disable_make_search,
				"gen_sql" => $sql
			);
	
			$leftsearchcol = '<div class="left-cell boatsearchcol scrollcol"  parentdiv="boatlisting-detail">'. $this->yacht_search_column($param) .'</div>';
			$boatdata = '
			<div class="boatlistingmain clearfixmain">
				'. $boat_top_text . '
				<div class="boatlisting-detail clearfixmain">
					'. $leftsearchcol .'
					<div id="listingholdermain" class="right-cell" to_check_val="'. $to_check_val .'">
						'. $retval[0]->doc .'
					</div>
				</div>
			</div>	
			';
		}
		
		return $boatdata;
	}
	
	//COMPARE BOAT
	public function display_boat_compare($chosenboat){
		global $db, $cm;
		$returntext = '';
		$errormessage = 'Invalid Boat selection';
		
		if ($chosenboat != ""){
			$chosenboat_filter = '';
			$chosenboat_ar = explode(",", $chosenboat);
			foreach($chosenboat_ar as $chosenboat_r){
				$chosenboat_filter .= '\''. $cm->filtertext($chosenboat_r) .'\',';
			}
			$chosenboat_filter = rtrim($chosenboat_filter, ',');
			
			$sql = "select * from tbl_yacht where listing_no IN ( ". $chosenboat_filter ." ) and status_id IN (1,3) and display_upto >= CURDATE() limit 0, 5";
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			//echo $sql;
			if ($found < 2){
				$returntext = $errormessage;
			}else{
				$boatimage = array(
					'imagepath' => array()
				);
				
				$boatgeneral = array(
					'Category' => array(),
					'Condition' => array(),
					'Price' => array(),
					'Make' => array(),
					'Model' => array(),
					'Year' => array(),
					'Length' => array(),
					'LOA' => array(),
					'Beam' => array(),
					'Draft - Max' => array(),
					'Location' => array()
				);
				
				$boatengine = array(
					'Engine Make' => array(),
					'Engines' => array(),
					'Engine Type' => array(),
					'Drive Type' => array(),
					'Fuel Type' => array(),
					'Horsepower' => array(),
					'Hours' => array()
				);
				
				$boattank = array(
					'Fuel' => array(),
					'Fresh Water' => array(),
					'Holding' => array()
				);
				
				$boataccommodation = array(
					'Total Cabins' => array(),
					'Total Berths' => array(),
					'Total Sleeps' => array(),
					'Total Heads' => array(),
					'Captains Cabin' => array()
				);
				
				$boatlistinginfo = array(
					'Company' => array(),
					'Listing Agent' => array(),
					'Phone' => array(),
					'Contact' => array(),
					'Remove' => array()
				);

				foreach($result as $row){
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
					}
					
					$imagefolder = 'yachtimage/'. $listing_no .'/big/';
					$firstimage = $this->get_yacht_first_image($id);
					$yacht_title = $this->yacht_name($id);
					
					//Dimensions & Weight
					$ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($id) ."'";
					$ex_result = $db->fetch_all_array($ex_sql);
					$row = $ex_result[0];
					foreach($row AS $key => $val){
						${$key} = $cm->filtertextdisplay($val);
					}
					$details_url = $cm->get_page_url($id, "yacht");
					
					//image
					$boatimage["imagepath"][] = '<a class="imgboatrow" lno="'. $listing_no.'" target="_blank" href="'. $details_url .'" title="'. $yacht_title .'"><img class="boatimg" src="'. $cm->folder_for_seo . $imagefolder . $firstimage .'" alt=""></a>';
					
					//general info
					$category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
					$condition_name = $cm->get_common_field_name('tbl_condition', 'name', $condition_id);
					$manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
					$addressfull = $this->get_yacht_address($id);
					
					$boatgeneral["Category"][] = $category_name;
					$boatgeneral["Condition"][] = $condition_name;
					$boatgeneral["Price"][] = '$' . $cm->price_format($price);
					$boatgeneral["Make"][] = $manufacturer_name;
					$boatgeneral["Model"][] = $model;
					$boatgeneral["Year"][] = $year;
					$boatgeneral["Length"][] = $this->display_yacht_number_field($length, 1);
					$boatgeneral["LOA"][] = $this->display_yacht_number_field($loa, 1, 1);
					$boatgeneral["Beam"][] = $this->display_yacht_number_field($beam, 1, 1);
					$boatgeneral["Draft - Max"][] = $this->display_yacht_number_field($draft, 1, 1);					
					$boatgeneral["Location"][] = $addressfull;
					
					//Engine
					$ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($id) ."'";
					$ex_result = $db->fetch_all_array($ex_sql);
					$row = $ex_result[0];
					foreach($row AS $key => $val){
						${$key} = htmlspecialchars($val);
					}
					$engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);
					$engine_type_name = $cm->get_common_field_name('tbl_engine_type', 'name', $engine_type_id);
					$drive_type_name = $cm->get_common_field_name('tbl_drive_type', 'name', $drive_type_id);
					$fuel_type_name = $cm->get_common_field_name('tbl_fuel_type', 'name', $fuel_type_id);
					
					$boatengine["Engine Make"][] = $engine_make_name;
					$boatengine["Engines"][] =  $this->display_yacht_number_field($engine_no);
					$boatengine["Engine Type"][] = $engine_type_name;
					$boatengine["Drive Type"][] = $drive_type_name;
					$boatengine["Fuel Type"][] = $fuel_type_name;
					$boatengine["Horsepower"][] = $this->display_yacht_hp($engine_no, $horsepower_individual);
					$boatengine["Hours"][] = $hours;
					
					//Tank Capacities
					$ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($id) ."'";
					$ex_result = $db->fetch_all_array($ex_sql);
					$row = $ex_result[0];
					foreach($row AS $key => $val){
						${$key} = htmlspecialchars($val);
					}
					
					$boattank["Fuel"][] = $this->display_yacht_tank_cap($fuel_tanks, $no_fuel_tanks);
					$boattank["Fresh Water"][] =  $this->display_yacht_tank_cap($fresh_water_tanks, $no_fresh_water_tanks);
					$boattank["Holding"][] = $this->display_yacht_tank_cap($holding_tanks, $no_holding_tanks);
					
					//Accommodations
					$ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($id) ."'";
					$ex_result = $db->fetch_all_array($ex_sql);
					$row = $ex_result[0];
					foreach($row AS $key => $val){
						${$key} = htmlspecialchars($val);
					}
					
					$boataccommodation["Total Cabins"][] = $this->display_yacht_number_field($total_cabins);
					$boataccommodation["Total Berths"][] = $this->display_yacht_number_field($total_berths);
					$boataccommodation["Total Sleeps"][] = $this->display_yacht_number_field($total_sleeps);
					$boataccommodation["Total Heads"][] = $this->display_yacht_number_field($total_heads);
					$boataccommodation["Captains Cabin"][] = $cm->set_yesyno_field($captains_cabin);
					
					//listing info					
					$company_ar = $cm->get_table_fields('tbl_company', 'cname, logo_imgpath', $company_id);
       				$companyname = $company_ar[0]["cname"];
					
					$broker_ar = $cm->get_table_fields('tbl_user', 'fname, lname, phone', $broker_id);
					$fname = $broker_ar[0]["fname"];
					$lname = $broker_ar[0]["lname"];
					$phone = $broker_ar[0]["phone"];
					
					if ($found > 2){
						$removelink = '<a href="javascript:void(0);" class="comparcolremove" title="Remove Boat" lno="'. $listing_no.'"><img src="'. $cm->folder_for_seo .'images/deletebigcolor.png" alt="Remove Boat" /></a>';
					}else{
						$removelink = '<a href="javascript:void(0);" class="" title="Remove Boat" lno="'. $listing_no.'"><img src="'. $cm->folder_for_seo .'images/deletebig.png" alt="Remove Boat" /></a>';
					}
					
					$boatlistinginfo["Company"][] = $companyname;
					$boatlistinginfo["Listing Agent"][] = $fname .'&nbsp;'. $lname;
					$boatlistinginfo["Phone"][] = $phone;
					$boatlistinginfo["Contact"][] = '<a href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $broker_id . '&yid='. $id .'" class="contactbroker button contact" data-type="iframe"><span>Contact Broker</span></a>';
					$boatlistinginfo["Remove"][] = $removelink;
					
				}
				
				$colspan = $found + 1;
				
				$returntext = '
				<div class="compare-boat-data" pg="'. $cm->folder_for_seo  .'compareboat/">
				<table class="compare-boat-data-table" border="0" cellspacing="1" cellpadding="0">
				';
				
				//image				
				foreach($boatimage as $boatdataar){
					$returntext .= '<tr class="scrollcol" parentdiv="compare-boat-data">';
						$returntext .= '<td class="boatkey">&nbsp;</td>';
						foreach($boatdataar as $boatdata){
							$returntext .= '<td class="boatcols'. $found .' imgtd">'. $boatdata .'</td>';
						}
					$returntext .= '</tr>';
				}
				
				//general info
				$returntext .= '<tr>
					<td class="boatkeytop" colspan="'. $colspan .'">General Boat Information</td>
				</tr>
				';
				foreach($boatgeneral as $key => $boatdataar){
					$returntext .= '<tr>';
					$returntext .= '<td class="boatkey">'. $key .'</td>';
					$exclass = '';
					if ($key == "Price"){ $exclass = ' boatprice'; }
					foreach($boatdataar as $boatdata){
						$returntext .= '<td class="boatcols'. $found . $exclass . '">'. $boatdata .'</td>';
					}
					$returntext .= '</tr>';					
				}				
				
				//engine
				$returntext .= '<tr>
					<td class="boatkeytop" colspan="'. $colspan .'">Engine Information</td>
				</tr>
				';
				foreach($boatengine as $key => $boatdataar){
					$returntext .= '<tr>';
					$returntext .= '<td class="boatkey">'. $key .'</td>';
					foreach($boatdataar as $boatdata){
						$returntext .= '<td>'. $boatdata .'</td>';
					}
					$returntext .= '</tr>';					
				}
								
				//tank
				$returntext .= '<tr>
					<td class="boatkeytop" colspan="'. $colspan .'">Tank Capacities</td>
				</tr>
				';
				foreach($boattank as $key => $boatdataar){
					$returntext .= '<tr>';
					$returntext .= '<td class="boatkey">'. $key .'</td>';
					foreach($boatdataar as $boatdata){
						$returntext .= '<td>'. $boatdata .'</td>';
					}
					$returntext .= '</tr>';
				}
				
				//accomodation
				$returntext .= '<tr>
					<td class="boatkeytop" colspan="'. $colspan .'">Accommodations</td>
				</tr>
				';
				foreach($boataccommodation as $key => $boatdataar){
					$returntext .= '<tr>';
					$returntext .= '<td class="boatkey">'. $key .'</td>';
					foreach($boatdataar as $boatdata){
						$returntext .= '<td>'. $boatdata .'</td>';
					}
					$returntext .= '</tr>';						
				}
				
				//listing information
				$returntext .= '<tr>
					<td class="boatkeytop" colspan="'. $colspan .'">Listing Information</td>
				</tr>
				';
				foreach($boatlistinginfo as $key => $boatdataar){
					$returntext .= '<tr>';
					$returntext .= '<td class="boatkey">'. $key .'</td>';
					$centerclass = '';
					if ($key == "Contact" OR $key == "Remove"){ $centerclass = ' class="centerclass"'; }
					foreach($boatdataar as $boatdata){
						$returntext .= '<td'. $centerclass .'>'. $boatdata .'</td>';
					}
					$returntext .= '</tr>';					
				}
				
				$returntext .= '
					</table>
					<div class="clear"></div>
				</div>';
			}
		}else{
			$returntext = $errormessage;
		}
		
		return $returntext;
	}
	
	//Boat list based on model group
	public function boat_list_group_wise($argu = array()){
		global $db, $cm;
		$returntext = '';
		$groupid = round($argu["groupid"], 0);
		
		$_SESSION["s_normal_pagination"] = 1;
		$pageid = $cm->get_page_id_by_slug($cm->format_page_slug());
		$_SESSION["conditional_page_id"] = $pageid;
		
		$query_sql = "select id, name,";
		$query_form = " from tbl_boat_model_group";
		$query_where = " where";

		if ($groupid > 0){
			$query_where .= " id = '". $groupid ."' and";
		}
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql_group = $query_sql . $query_form . $query_where;
		$sql_group .= " order by rank";
		$result_group = $db->fetch_all_array($sql_group);
		$found_group = count($result_group);
		
		if ($found_group > 0){
			foreach($result_group as $row_group){
				$group_id = $row_group['id'];
				$group_name = $cm->filtertextdisplay($row_group['name']);
				
				//collect boat for this group
				$query_sql = "select a.*";
				$query_form = " from tbl_yacht as a,";
				$query_where = " where";				
		
				$query_form .= " tbl_boat_model_group_assign as b,";
				$query_where .= " a.id = b.boat_id and";
				$query_where .= " b.group_id = '". $group_id ."' and";
				
				$query_where .= " a.status_id IN (1,3) and";
				$query_where .= " a.display_upto >= CURDATE() and";
				
				$query_sql = rtrim($query_sql, ",");
				$query_form = rtrim($query_form, ",");
				$query_where = rtrim($query_where, "and");
				
				$sql = $query_sql . $query_form . $query_where;
				$sql .= " order by b.rank";
				$result = $db->fetch_all_array($sql);
        		$found = count($result);
				$foundm = $foundm + $found;
				
				if ($found > 0){
					$returntext .= '
					<div id="filtersection" class="profile-main">
					<div class="mm-modelbox">
						<h2>'. $group_name .'</h2>
						<ul id="listingholder" class="product-list">
					';
					
					$extraclass = '';
					$compareboat = 0;
					$displayoption = 1;
					$charter = 0;
					foreach($result as $row){
						$returntext .= $this->display_yacht($row, $displayoption, $extraclass, $compareboat, $charter);
					}
					
					$returntext .= '
						</ul>
						<div class="clearfix"></div>
					</div>
					</div>
					';
				}
				//boat found end				
			}
		}		
		return $returntext;		
	}
	
	//Dislay Boat Group
	public function display_boat_group_list(){
		global $db, $cm;
		$returntext = '';		
	
		$query_sql = "select id, name,";
		$query_form = " from tbl_boat_model_group";
		$query_where = " where";
		
		$query_where .= " status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql_group = $query_sql . $query_form . $query_where;
		$sql_group .= " order by rank";
		$result_group = $db->fetch_all_array($sql_group);
		$found_group = count($result_group);
		
		if ($found_group > 0){
			
			$returntext .= '<ul class="boatgrouplist">';
			
			foreach($result_group as $row_group){
				$group_id = $row_group['id'];
				$group_name = $cm->filtertextdisplay($row_group['name']);
				
				//collect boat for this group
				$query_sql = "select a.id as ttl";
				$query_form = " from tbl_yacht as a,";
				$query_where = " where";				
		
				$query_form .= " tbl_boat_model_group_assign as b,";
				$query_where .= " a.id = b.boat_id and";
				$query_where .= " b.group_id = '". $group_id ."' and";
				
				$query_where .= " a.status_id IN (1,3) and";
				$query_where .= " a.display_upto >= CURDATE() and";
				
				$query_sql = rtrim($query_sql, ",");
				$query_form = rtrim($query_form, ",");
				$query_where = rtrim($query_where, "and");
				
				$sql = $query_sql . $query_form . $query_where;
				$sql .= " order by b.rank LIMIT 0, 1";
				
				$boatid = $db->total_record_count($sql);
				if ($boatid > 0){
					$checking_shortcode = "[fcboatlistgroup groupid=". $group_id ."]";
					$sqltext = "select id as ttl from tbl_page where file_data like '%". $checking_shortcode ."%'";
					$pageid = $db->total_record_count($sqltext);
					
					if ($pageid > 0){
						$details_url = $cm->get_page_url($pageid, "page");
					}else{
						$details_url = 'javascript:void(0);';
					}
					
					$ppath = $this->get_yacht_first_image($boatid);
					$listing_no = $this->get_yacht_no($boatid);
					$imagefolder = 'yachtimage/'. $listing_no .'/bigger/';					
					
					$returntext .= '<li><!--
					--><a href="'. $details_url .'">
					<img src="'. $cm->folder_for_seo . $imagefolder . $ppath . '" />
					<div class="boatgroupname">'. $group_name .'</div>
					</a><!--
					--></li>';
				}
			}
			
			$returntext .= '</ul>';
		}		
		return $returntext;		
	}
	
	//search form small
	public function boat_advanced_search_form_small($param = array()){
		global $db, $cm;
		$formpostar = json_decode($this->get_advanced_search_post_url());
		
		$our_page_id_yacht = $formpostar->our_page_id_yacht;
		$post_url_yacht = $formpostar->post_url_yacht;
		$co_broker_page_id_yacht = $formpostar->co_broker_page_id_yacht;
		$post_url2_yacht = $formpostar->post_url2_yacht;
		
		$our_page_id_catamaran = $formpostar->our_page_id_catamaran;
		$post_url_catamaran = $formpostar->post_url_catamaran;
		$co_broker_page_id_catamaran = $formpostar->co_broker_page_id_catamaran;
		$post_url2_catamaran = $formpostar->post_url2_catamaran;
		
		$formtype = $param["formtype"];
		$disable_make_search = $param["disable_make_search"];
		$apinoselection = $param["apinoselection"];
		$searchtypeselection = $param["searchtypeselection"];
		$conditionid = $param["conditionid"];
		$typeid = $param["typeid"];
		$owned = $param["owned"];
		$sp_typeid = $param["sp_typeid"];
		
		$mfcname = $param["mfcname"];
		$lnmin = $param["lnmin"];
		$lnmax = $param["lnmax"];
		$yrmin = $param["yrmin"];
		$yrmax = $param["yrmax"];
		$prmin = $param["prmin"];
		$prmax = $param["prmax"];
		
		$field_id_extra = '';
		$price_field_class = '';
		$length_field_class = '';
		
		$form_button_text = '<div class="button-group clearfixmain">
		<div class="input-left"><button type="submit" class="button1">Search</button></div> 
		<div class="input-right"><a class="openadvsearch button2" href="javascript:void(0);">Advanced Search</a></div> 
		</div>';
		
		$boat_owned_section_text = '
		<label class="com_none" for="cm_owned1">Our Listings</label>
		<label class="com_none" for="cm_owned2">Co-Brokerage</label>
		<div class="input-left"><input p="'. $post_url_yacht .'" pid="'. $our_page_id_yacht .'" p2="'. $post_url_catamaran .'" pid2="'. $our_page_id_catamaran .'" class="setformaction radiobutton" type="radio" id="cm_owned1" name="owned" value="1" /> Our Listings</div> 
		<div class="input-right"><input p="'. $post_url2_yacht .'" pid="'. $co_broker_page_id_yacht .'" p2="'. $post_url2_catamaran .'" pid2="'. $co_broker_page_id_catamaran .'" class="setformaction radiobutton radiobutton_next" type="radio" id="cm_owned2" name="owned" value="2" checked="checked" /> Co-Brokerage</div>
		';
		
		if ($searchtypeselection == 1){
			if ($sp_typeid == 2){
				$sp_typeid1 = '';
				$sp_typeid2 = ' checked="checked"';
				$sp_typeid3 = '';
			}elseif ($sp_typeid == 1){
				$sp_typeid1 = ' checked="checked"';
				$sp_typeid2 = '';
				$sp_typeid3 = '';
			}else{
				$sp_typeid1 = '';
				$sp_typeid2 = '';
				$sp_typeid3 = ' checked="checked"';
			}
			
			$searchtypeselection_text = '
			<div class="radio-group clearfixmain">
			<p>Search Type</p>
			<div class="clearfixmain">
				<label class="com_none" for="ls_sp_typeid1">Yacht</label>
				<label class="com_none" for="ls_sp_typeid2">Catamaran</label>
				<label class="com_none" for="ls_sp_typeid3">All</label>
				<div><input class="radiobutton ownedradio" type="radio" id="ls_sp_typeid1" name="ls_sp_typeid" value="1"'. $sp_typeid1 .' /> Yacht</div> 
				<div><input class="radiobutton ownedradio" type="radio" id="ls_sp_typeid2" name="ls_sp_typeid" value="2"'. $sp_typeid2 .' /> Catamaran</div>
				<div><input class="radiobutton ownedradio" type="radio" name="ls_sp_typeid" id="ls_sp_typeid3" value="0"'. $sp_typeid3 .' /> All</div>
			</div>
			</div>
			';
		}else{
			$searchtypeselection_text = '<input type="hidden" name="sp_typeid" id="sp_typeid" value="'. $sp_typeid .'" />';
		}
		
		//$condition_field_include_text = '';
		if ($conditionid == 1){
			$conditionid1 = ' checked="checked"';
			$conditionid2 = '';
			$conditionid3 = '';
		}elseif ($conditionid == 2){
			$conditionid1 = '';
			$conditionid2 = ' checked="checked"';
			$conditionid3 = '';
		}else{
			$conditionid1 = '';
			$conditionid2 = '';
			$conditionid3 = ' checked="checked"';
		}
			
		$condition_field_include_text = '
		<div class="button-group clearfixmain">
			<p>Condition</p>
			<div class="clearfixmain">
			<input class="radiobutton conditionidradio radiobutton_rightside" type="radio" name="conditionid" value="1"'. $conditionid1 .'>New
			<input class="radiobutton conditionidradio radiobutton_bothside" type="radio" name="conditionid" value="2"'. $conditionid2 .'>Used
			<input class="radiobutton conditionidradio radiobutton_leftside" type="radio" name="conditionid" value="0"'. $conditionid3 .'>All
			</div>
		</div>
		';
		
		$boat_type_section_text = '<div class="radio-group clearfixmain">
			<h5 class="singlelinerightside">Search Type</h5>
			<div class="clearfixmain">
				<label class="com_none" for="cm_sp_typeid1">Yachts</label>
				<label class="com_none" for="cm_sp_typeid2">Catamaran</label>
				<div class="input-left"><input class="setformaction radiobutton" type="radio" id="cm_sp_typeid1" name="sp_typeid" value="1" checked="checked" /> Yachts</div> 
				<div class="input-right"><input class="setformaction radiobutton radiobutton_next" type="radio" id="cm_sp_typeid2" name="sp_typeid" value="2" /> Catamaran</div>
			</div>
		</div>
		';
		
		if ($formtype == 1){
			//for top menu - old
			$field_id_extra = '_menusection';
			$boat_owned_section_text = '
			<div class="input-left"><input p="'. $post_url .'" pid="'. $our_page_id .'" class="setformaction'. $field_id_extra .' radiobutton" type="radio" name="owned" value="1" /> Our Listings</div> 
			<div class="input-right"><input p="'. $post_url2 .'" pid="'. $co_broker_page_id .'" class="setformaction'. $field_id_extra .' radiobutton radiobutton_next" type="radio" name="owned" value="2" checked="checked" /> Co-Brokerage</div>
			';
		
		}elseif ($formtype == 2){
			$condition_field_include_text = '';
			//$boat_owned_section_text = '<input type="hidden" name="owned" id="owned" value="2" />';
			//$boat_owned_section_text = '<div class="button-group clearfixmain">'. $boat_owned_section_text .'</div>';
						
			/*$boat_owned_section_text = '
			<div class="radio-group clearfixmain">
				<h5 class="singlelinerightside">Search In</h5>
				<div class="clearfixmain">
				'. $boat_owned_section_text .'
				</div>
			</div>
			';*/
			$boat_owned_section_text = '';
			$boat_type_section_text = '';
			
			$form_button_text = '<div class="button-group mt-2 clearfixmain">
			<div class="input-left"><button type="submit" class="button1">Search</button></div> 
			</div>';
			
		}elseif ($formtype == 3){
			$price_field_class = ' pricefield';
			$length_field_class = ' lengthfield';
			$boat_type_section_text = '';
			$boat_type_section_text = '';
			
			$form_button_text = '
			<div class="button-group clearfixmain">
			<div class="input-left"><button type="submit" class="button1 boatsearchbutton">Search</button></div>
			<div class="input-right"><a href="'. $cm->folder_for_seo .'?fcapi=clearfilters" class="clearallfilter"><i class="fa fa-times" aria-hidden="true"></i>Clear all filters</a></div>
			</div>
			';
			if ($apinoselection == 0){
				if ($owned == 2){
					$owned1 = '';
					$owned2 = ' checked="checked"';
					$owned3 = '';
				}elseif ($owned == 1){
					$owned1 = ' checked="checked"';
					$owned2 = '';
					$owned3 = '';
				}else{
					$owned1 = '';
					$owned2 = '';
					$owned3 = ' checked="checked"';
				}
				
				$boat_owned_section_text = '
				<div class="radio-group clearfixmain">
				<p>Search In</p>
				<div class="clearfixmain">
					<label class="com_none" for="ls_owned1">Our Listings</label>
					<label class="com_none" for="ls_owned2">Co-Brokerage</label>
					<label class="com_none" for="ls_owned3">All</label>
					<div><input class="radiobutton ownedradio" type="radio" id="ls_owned1" name="ls_owned" value="1"'. $owned1 .' /> Our Listings</div> 
					<div><input class="radiobutton ownedradio" type="radio" id="ls_owned2" name="ls_owned" value="2"'. $owned2 .' /> Co-Brokerage</div>
					<div><input class="radiobutton ownedradio" type="radio" name="ls_owned" id="ls_owned3" value="0"'. $owned3 .' /> All</div>
				</div>
				</div>
				';
			}else{
				$boat_owned_section_text = '<input type="hidden" name="owned" id="owned" value="'. $owned .'" />';
			}
			
			if ($conditionid == 1){
				$conditionid1 = ' checked="checked"';
				$conditionid2 = '';
				$conditionid3 = '';
			}elseif ($conditionid == 2){
				$conditionid1 = '';
				$conditionid2 = ' checked="checked"';
				$conditionid3 = '';
			}else{
				$conditionid1 = '';
				$conditionid2 = '';
				$conditionid3 = ' checked="checked"';
			}
			
			$condition_field_include_text = '
			<div class="button-group clearfixmain">
				<p>Condition</p>
				<div class="clearfixmain">
				<label class="com_none" for="conditionid_extend1">New</label>
				<label class="com_none" for="conditionid_extend2">Used</label>
				<label class="com_none" for="conditionid_extend3">All</label>
				<input class="radiobutton conditionidradio radiobutton_rightside" type="radio" id="conditionid_extend1" name="conditionid_extend" value="1"'. $conditionid1 .'>New
				<input class="radiobutton conditionidradio radiobutton_bothside" type="radio" id="conditionid_extend2" name="conditionid_extend" value="2"'. $conditionid2 .'>Used
				<input class="radiobutton conditionidradio radiobutton_leftside" type="radio" id="conditionid_extend3" name="conditionid_extend" value="0"'. $conditionid3 .'>All
				</div>
			</div>
			';
		}
		
		if ($formtype == 4){
			// inside content
			$field_id_extra = '_wws';			
			$smallform = '
			<ul>
				<li>
					<div class="wws_heading">Search</div>
					 <div class="wws_group"><div class="input-group wws_group_select clearfixmain">
						<select class="select" name="owned" id="wws_owned">
							<option p="'. $post_url2 .'" pid="'. $our_page_id .'" value="2" selected="selected">Worldwide</option>
							<option p="'. $post_url .'" pid="'. $co_broker_page_id .'" value="1">Our Listings</option>
						</select></div>
					</div>
				</li>
				
				<li>
					<div class="wws_heading">Manufacturer</div>
					<div class="wws_group"><div class="input-group clearfixmain"><input id="mfcname'. $field_id_extra .'" name="mfcname" ckpage="5" class="azax_auto input-field" autocomplete="off" placeholder="Manufacturer" type="text" value="'. $mfcname .'"></div></div>
				</li>
				
				<li>
					<div class="wws_heading">Length</div>
					<div class="wws_group clearfixmain">
						<div class="input-left">
							<div class="input-group clearfixmain">
								<input id="lnmin'. $field_id_extra .'" name="lnmin" class="input-field'. $length_field_class .'" placeholder="Min" type="text" value="'. $lnmin .'">
							</div>
						</div>
						<div class="input-right">    
							<div class="input-group clearfixmain">
								<input id="lnmax'. $field_id_extra .'" name="lnmax" class="input-field'. $length_field_class .'" placeholder="Max" type="text" value="'. $lnmax .'">
							</div>
						</div>
					</div>
				</li>
				
				<li>
					<div class="wws_heading">Price</div>
					<div class="wws_group clearfixmain">
						<div class="input-left">
							<div class="input-group clearfixmain">
								<input id="prmin'. $field_id_extra .'" name="prmin" class="input-field'. $price_field_class .'" placeholder="Min" type="text" value="'. $prmin .'">
							</div>
						</div>
						<div class="input-right">    
							<div class="input-group clearfixmain">
								<input id="prmax'. $field_id_extra .'" name="prmax" class="input-field'. $price_field_class .'" placeholder="Max" type="text" value="'. $prmax .'">
							</div>
						</div>
					</div>
				</li>
				
				<li class="wws_submit"><button type="submit" class="button1">Search</button></li>
			</ul>
			';			
		}elseif ($formtype == 5){
			//inside menu
			$field_id_extra = '_inm';
			
			$condition_field_include_text = '';
			
			$boat_type_section_text = '<div class="button-group clearfixmain">
			<div class="input-left"><input class="setformaction radiobutton" type="radio" name="typeid" value="0"  checked="checked" /> Yachts</div> 
			<div class="input-right"><input class="setformaction radiobutton radiobutton_next" type="radio" name="typeid" value="9" /> Catamaran</div>
			</div>
			';
			
			$boat_owned_section_text = '<div class="button-group clearfixmain">
			<div class="input-left"><input p="'. $post_url .'" pid="'. $our_page_id .'" class="setformaction'. $field_id_extra .' radiobutton" type="radio" name="owned" value="1" /> Our Listings</div> 
			<div class="input-right"><input p="'. $post_url2 .'" pid="'. $co_broker_page_id .'" class="setformaction'. $field_id_extra .' radiobutton radiobutton_next" type="radio" name="owned" value="2" checked="checked" /> Co-Brokerage</div>
			</div>
			';			
			//$boat_owned_section_text = '<input type="hidden" name="owned" value="2" />';
			
			$smallform = '
			<div class="input-group clearfixmain">
				<span class="input-icon manufacturer"></span>
				<input id="mfcname'. $field_id_extra .'" name="mfcname" ckpage="5" class="azax_auto input-field" autocomplete="off" placeholder="Make" type="text" value="'. $mfcname .'">
			</div>
	
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<span class="input-icon length-range"></span>
						<input id="lnmin'. $field_id_extra .'" name="lnmin" class="input-field'. $length_field_class .'" placeholder="Min Length" type="text" value="'. $lnmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<span class="input-icon length-range"></span>
						<input id="lnmax'. $field_id_extra .'" name="lnmax" class="input-field'. $length_field_class .'" placeholder="Max Length" type="text" value="'. $lnmax .'">
					</div>
				</div>
			</div>			
	
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<span class="input-icon year-range"></span>
						<input id="yrmin'. $field_id_extra .'" name="yrmin" class="input-field" placeholder="Min Year" type="text" value="'. $yrmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<span class="input-icon year-range"></span>
						<input id="yrmax'. $field_id_extra .'" name="yrmax" class="input-field" placeholder="Max Year" type="text" value="'. $yrmax .'">
					</div>
				</div>
			</div>			
		
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<span class="input-icon price-range"></span>
						<input id="prmin'. $field_id_extra .'" name="prmin" class="input-field'. $price_field_class .'" placeholder="Min Price" type="text" value="'. $prmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<span class="input-icon price-range"></span>
						<input id="prmax'. $field_id_extra .'" name="prmax" class="input-field'. $price_field_class .'" placeholder="Max Price" type="text" value="'. $prmax .'">
					</div>
				</div>
			</div>
			
			
			'. $boat_owned_section_text .'
			'. $searchtypeselection_text .'
			'. $boat_type_section_text .'
			'. $form_button_text .'
			';
		}else{			
			if ($disable_make_search == 1){
				$make_search_string = '<input id="mfcname'. $field_id_extra .'" name="mfcname"type="hidden" value="'. $mfcname .'">';
			}else{
				$make_search_string = '
				<p><label for="mfcname'. $field_id_extra .'">Make</label></p>
				<div class="input-group clearfixmain">			
					<input id="mfcname'. $field_id_extra .'" name="mfcname" class="azax_auto input-field" placeholder="Manufacturer" type="text" value="'. $mfcname .'" ckpage="5" autocomplete="off">
				</div>
				';
			}
				
			$smallform = $make_search_string . '

			<p><label for="lnmin'. $field_id_extra .'">Length Range</label><label class="com_none" for="lnmax'. $field_id_extra .'">Length Range</label></p>
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<input id="lnmin'. $field_id_extra .'" name="lnmin" class="input-field'. $length_field_class .'" placeholder="Min" type="text" value="'. $lnmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<input id="lnmax'. $field_id_extra .'" name="lnmax" class="input-field'. $length_field_class .'" placeholder="Max" type="text" value="'. $lnmax .'">
					</div>
				</div>
			</div>

			<p><label for="yrmin'. $field_id_extra .'">Year Range</label><label class="com_none" for="yrmax'. $field_id_extra .'">Year Range</label></p>
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<input id="yrmin'. $field_id_extra .'" name="yrmin" class="input-field" placeholder="Min" type="text" value="'. $yrmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<input id="yrmax'. $field_id_extra .'" name="yrmax" class="input-field" placeholder="Max" type="text" value="'. $yrmax .'">
					</div>
				</div>
			</div>

			<p><label for="prmin'. $field_id_extra .'">Price Range</label><label class="com_none" for="prmax'. $field_id_extra .'">Price Range</label></p>
			<div class="clearfixmain">
				<div class="input-left">
					<div class="input-group clearfixmain">
						<input id="prmin'. $field_id_extra .'" name="prmin" class="input-field'. $price_field_class .'" placeholder="Min" type="text" value="'. $prmin .'">
					</div>
				</div>
				<div class="input-right">    
					<div class="input-group clearfixmain">
						<input id="prmax'. $field_id_extra .'" name="prmax" class="input-field'. $price_field_class .'" placeholder="Max" type="text" value="'. $prmax .'">
					</div>
				</div>
			</div>

			'. $condition_field_include_text .'	
			'. $searchtypeselection_text .'
			'. $boat_type_section_text .'			
			'. $boat_owned_section_text .'				 
			'. $form_button_text .'   
			';
		}
		
		$returnar = array(
			"smallform" => $smallform,
			
			"our_page_id_yacht" => $our_page_id_yacht,
			"post_url_yacht" => $post_url_yacht,
			"co_broker_page_id_yacht" => $co_broker_page_id_yacht,
			"post_url2_yacht" => $post_url2_yacht,
			
			"our_page_id_catamaran" => $our_page_id_catamaran,
			"post_url_catamaran" => $post_url_catamaran,
			"co_broker_page_id_catamaran" => $co_broker_page_id_catamaran,
			"post_url2_catamaran" => $post_url2_catamaran
		);
		
		return json_encode($returnar);
	}
	
	//search inside menu
	public function search_inside_menu(){
		$formdata = json_decode($this->boat_advanced_search_form_small(array("formtype" => 5)));
		$smallform = $formdata->smallform;
		$our_page_id = $formdata->our_page_id;
		$post_url = $formdata->post_url;
		$co_broker_page_id = $formdata->co_broker_page_id;
		$post_url2 = $formdata->post_url2;
		
		$returntext = '
		<form method="get" action="'. $post_url2 .'" id="searchboat-ff" name="searchboat-ff">
		
		<div class="search-container-in clearfixmain">
		'. $smallform .'
		</div>
		
		<input type="hidden" name="freshstart" value="1">
		<input type="hidden" name="rawtemplate" value="0">
		<input type="hidden" id="setpg_menusection" name="setpg" value="'. $co_broker_page_id .'">
		</form>
		';
		
		$returntext .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$(".setformaction_inm").click(function(){
				var selected_opt = parseInt($("#searchboat-ff input[name=owned]:radio:checked").val());
				var formp = $("#searchboat-ff input[name=owned]:radio:checked").attr("p");	
				var pid = $("#searchboat-ff input[name=owned]:radio:checked").attr("pid");
				$("#searchboat-ff #setpg_menusection").val(pid);
				$("#searchboat-ff").attr("action", formp);

				//remove session storage if any
				remove_session_storage();
			});
		});
		</script>
		';
		
		return $returntext;
	}
	
	//search over slider	
	public function display_boat_advanced_search_form_small_old(){
		global $db, $cm;
		
		$formdata = json_decode($this->boat_advanced_search_form_small(array("formtype" => 2)));
		$smallform = $formdata->smallform;
		
		$our_page_id_yacht = $formdata->our_page_id_yacht;
		$post_url_yacht = $formdata->post_url_yacht;
		$co_broker_page_id_yacht = $formdata->co_broker_page_id_yacht;
		$post_url2_yacht = $formdata->post_url2_yacht;
		
		$our_page_id_catamaran = $formdata->our_page_id_catamaran;
		$post_url_catamaran = $formdata->post_url_catamaran;
		$co_broker_page_id_catamaran = $formdata->co_broker_page_id_catamaran;
		$post_url2_catamaran = $formdata->post_url2_catamaran;
		
		$smallform = '		
		<div class="search-container clearfixmain">
			<h6>Yacht &amp; Catamaran Search</h6>
			<div class="search-container-in clearfixmain">
				<form method="get" action="'. $post_url2_yacht .'" id="mboat_ff" name="ff">
				'. $smallform .'
				
				<input type="hidden" name="freshstart" value="1">
				<input type="hidden" name="rawtemplate" value="0">
				<input type="hidden" id="setpg" name="setpg" value="'. $co_broker_page_id_yacht .'">
				</form>
			</div>
		</div>
		';		
		
		$smallform .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$(".setformaction").click(function(){
				var selected_opt = parseInt($("#mboat_ff input[name=owned]:radio:checked").val());
				var sp_typeid_opt = parseInt($("#mboat_ff input[name=sp_typeid]:radio:checked").val());
				
				if (sp_typeid_opt == 2){
					var formp = $("#mboat_ff input[name=owned]:radio:checked").attr("p2");	
					var pid = $("#mboat_ff input[name=owned]:radio:checked").attr("pid2");
				}else{
					var formp = $("#mboat_ff input[name=owned]:radio:checked").attr("p");	
					var pid = $("#mboat_ff input[name=owned]:radio:checked").attr("pid");
				}
				
				$("#mboat_ff #setpg").val(pid);
				$("#mboat_ff").attr("action", formp);

				//remove session storage if any
				remove_session_storage();
			});
		});
		</script>
		';
		
		$responsiveform = '<div class="slidersearch_responsive"><a class="openadvsearch button" href="javascript:void(0);">Search Inventory</a></div>';
		
		$returnar = array(
			"smallform" => $smallform,
			"responsiveform" => $responsiveform
		);
		
		return $returnar;
	}
	public function display_boat_advanced_search_form_small(){
		global $db, $cm;
		
		$formdata = json_decode($this->boat_advanced_search_form_small(array("formtype" => 2)));
		$smallform = $formdata->smallform;
		
		$smallform = '		
		<div class="search-container clearfixmain">
			<h6>Yacht &amp; Catamaran Search</h6>
			<div class="search-container-in clearfixmain">
				<form method="get" action="'. $cm->get_page_url(2, "page") .'" id="mboat_ff" name="ff">
				'. $smallform .'
				
				<input type="hidden" name="freshstart" value="1">
				<input type="hidden" name="rawtemplate" value="0">
				<input type="hidden" name="owned" value="0">
				<input type="hidden" name="sp_typeid" value="0">
				</form>
			</div>
		</div>
		';		
		
		$smallform .= '
		<script type="text/javascript">
		$(document).ready(function(){				
			$("#mboat_ff").submit(function(){				
				//remove session storage if any
				remove_session_storage();
				return true;
			});
		});
		</script>
		';
		
		$responsiveform = '<div class="slidersearch_responsive"><a class="openadvsearch button" href="javascript:void(0);">Search Inventory</a></div>';
		
		$returnar = array(
			"smallform" => $smallform,
			"responsiveform" => $responsiveform
		);
		
		return $returnar;
	}
	
	public function display_quick_search_static2(){
		$pagear = json_decode($this->get_custom_list_page_url());

		$returntext = '
		<ul class="searchextrabutton">
			<li><a class="button" href="'. $pagear->custom_page_url1 .'">Power</a></li>
			<li><a class="button" href="'. $pagear->custom_page_url2 .'">Sail</a></li>
			<li><a class="button" href="'. $pagear->custom_page_url3 .'">Catamaran</a></li>
		</ul>
		';
		
		return $returntext;
	}
	
	//sliding Advanced Search form
	public function sliding_advanced_search_form($isdashboard = 0, $pageid = 0){
		if ($isdashboard == 0 AND $pageid == 1){
			global $cm;

			$advform .= '
			<div class="boatsearch-inline all-overlay custom-overlay">
				<div class="custom-overlay-container clearfixmain">
					<div class="modal-dialog clearfixmain">
						<div class="custom-overlay-close"><a href="javascript:void(0);" title="Close"><img alt="Advanced Search Form Close" src="'. $cm->folder_for_seo .'images/inactive-icon.png" /></a></div>
						<h5>Advanced Search</h5>
						'. $this->display_boat_advanced_search_form() .'
					</div>
				</div>
			</div>
			';
			return $advform;
		}
	}
	
	//search inside content
	public function display_content_boat_advanced_search_form_small($param = array()){
		global $db, $cm;
		
		//param
		$default_param = array("innerpage" => 0);
		$param = array_merge($default_param, $param);
		$innerpage = $param["innerpage"];
		//end
		
		$formdata = json_decode($this->boat_advanced_search_form_small(array("formtype" => 4)));
		$smallform = $formdata->smallform;
		$our_page_id = $formdata->our_page_id;
		$post_url = $formdata->post_url;
		$co_broker_page_id = $formdata->co_broker_page_id;
		$post_url2 = $formdata->post_url2;
		
		if ($innerpage == 1){
			$startpart = '<div class="worldwidesearch clearfixmain">';
			$endpart = '</div>';
		}else{
			$startpart = '<div class="worldwidesearch clearfixmain"><div class="container clearfixmain">';
			$endpart = '</div></div>';
		}
		
		$smallform = $startpart . '
		<form method="get" action="'. $post_url2 .'" id="content_boat_ff" name="content_boat_ff">
		'. $smallform .'

		<input type="hidden" name="freshstart" value="1">
		<input type="hidden" name="rawtemplate" value="0">
		<input type="hidden" id="setpg" name="setpg" value="'. $co_broker_page_id .'">
		</form>
		' . $endpart. '
		';		
		
		$smallform .= '
		<script type="text/javascript">
		$(document).ready(function(){
			$("#wws_owned").change(function(){
				var selected_opt = parseInt($(this).val());
				var formp = $("option:selected", this).attr("p");
				var pid = $("option:selected", this).attr("pid")
				
				$("#content_boat_ff #setpg").val(pid);
				$("#content_boat_ff").attr("action", formp);				
			});
			
			$("#content_boat_ff").submit(function(){				
				//remove session storage if any
				remove_session_storage();
				return true;
			});
		});
		</script>
		';
				
		return $smallform;
	}
	
	//search boat by category and brand - home page
	public function boat_search_by_cat_make_data_pick($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//param val
		$category_id = $param["category_id"];
		$ownboat = round($param["ownboat"], 0);
		//end
		
		$category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
		$sql = "select a.id, a.slug, a.name, count(b.id) as total from tbl_manufacturer as a, tbl_yacht as b where a.id = b.manufacturer_id and b.category_id = '". $category_id ."' and a.status_id = 1 and b.status_id = 1 and b.display_upto >= CURDATE() and b.ownboat = '". $ownboat ."' group by a.id order by total desc";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		
		if ($found > 0){
			$returntext .= '<ul class="catmake-list">';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				$category_slug = strtolower($category_name);
				$inv_url_format = 'category/' . $category_slug . '/make/' . $slug;
				$pagename = $cm->serach_url_filtertext($inv_url_format);
          		$ret_url = $cm->folder_for_seo . $pagename . "/?owned=" . $ownboat;
				
				$returntext .= '<li><a href="'. $ret_url .'">'. $name .'</a></li>';
			}
			
			$returntext .= '</ul>';
		}		
		return $returntext;
	}
	
	public function boat_search_by_cat_make_data($category_id){
		global $db, $cm;
		$returntext = '';
		$category_name = $cm->get_common_field_name('tbl_category', 'name', $category_id);
		$returntext .= '
		<h3 class="singlelinebothside">'. $category_name .'</h3>
		<ul class="catmake-list-button">
			<li><a href="javascript:void(0);" class="catmakebutton catmakebutton-'.$category_id.'" owned="1" category_id="'. $category_id .'">Exclusive</a></li>
			<li><a href="javascript:void(0);" class="catmakebutton catmakebutton-'.$category_id.' active" owned="0" category_id="'. $category_id .'">Co-brokerage</a></li>
		</ul>
		<div class="clearfix"></div>
		
		<div id="ownboat1_'. $category_id .'" class="catmake-list-holder catmake-list-holder-'. $category_id .' com_none clearfixmain">
			'. $this->boat_search_by_cat_make_data_pick(array("category_id" => $category_id, "ownboat" => 1)) .'
		</div>
		
		<div id="ownboat0_'. $category_id .'" class="catmake-list-holder catmake-list-holder-'. $category_id .' clearfixmain">
			'. $this->boat_search_by_cat_make_data_pick(array("category_id" => $category_id, "ownboat" => 0)) .'
		</div>
		';		
		return $returntext;
	}
	
	public function boat_search_by_cat_make(){
		global $db, $cm;
		$returntext = '';
		
		$cat1_text = $this->boat_search_by_cat_make_data(1);
		$cat2_text = $this->boat_search_by_cat_make_data(2);
		
		if ($cat1_text != "" OR $cat2_text != ""){
			$returntext .= '
			<ul class="catmake-list-top">
				<li>'. $cat1_text .'</li>
				<li>'. $cat2_text .'</li>
			</ul>
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				$(".catmakebutton").click(function(){
					var owned = $(this).attr("owned");
					var category_id = $(this).attr("category_id");
					var targetdiv = "ownboat" + owned + "_" + category_id;
					
					$(".catmakebutton-" + category_id).removeClass("active");
					$(this).addClass("active");
					
					$(".catmake-list-holder-" + category_id).animate( { "opacity": "hide", top:"100"} , 500 );
					$("#" + targetdiv).animate( { "opacity": "show", top:"100"} , 500 );
				});
			});
			</script>
			';
		}
		
		return $returntext;
	}
	
	//search boat by boat type
	public function boat_search_by_boat_type(){
		global $db, $cm;
		$returntext = '';
		
		$sql = "select * from tbl_boat_type_specific order by id";
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			
			$returntext .= '<ul class="boxcol4">';
			
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				
				if ($ycdataid > 0){
					$ret_url = $cm->get_page_url($ycdataid, 'typybyid');
				}else{
					$ret_url = $cm->get_page_url($int_page_id, "page");
				}
				
				$returntext .= '<li><div class="shadowbox clearfixmain"><!--
				--><div class="boxcol4image"><a href="'. $ret_url .'"><img src="'. $cm->folder_for_seo .'boattypeboximage/'. $imagepath .'" /></a></div><!--
				--><h3><a href="'. $ret_url .'">'. $name .'</a></h3><!--
				--></div></li>';
			}
			
			$returntext .= '</ul>';
			
		}
		
		return $returntext;
	}
	
	//update boat slug
	public function update_boat_slug_feed(){
		if(($_REQUEST['fcapi'] == "updateboatslug")){
			global $db, $cm;
			$sql = "select * from tbl_yacht order by id";
			$result = $db->fetch_all_array($sql);
			foreach($result as $row){
				$boatid = $row['id'];
				$boat_slug = $this->create_boat_slug($boatid);
				
				$sql2 = "update tbl_yacht set boat_slug = '". $cm->filtertext($boat_slug) ."' where id = '". $boatid ."'";
				$db->mysqlquery($sql2);
			}			
		}
	}
	
	//max price boat
	public function display_max_price_boat($argu = array()){
		global $db, $cm;
		$returntext = '';
		
		//collect boat
        $query_sql = "select *,";
        $query_form = " from tbl_yacht,";
        $query_where = " where";
		
		$query_where .= " manufacturer_id > 0 and";
		$query_where .= " price_tag_id = 0 and";
        $query_where .= " status_id IN (1,3) and";

        $query_where .= " display_upto >= CURDATE() and";

        $query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
        $sql = $sql." order by price desc";
		
		$sql = $sql." limit 0, 1";
		$result = $db->fetch_all_array($sql);
		
        $found = count($result);
		if ($found > 0){
			$row = $result[0];
			
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$addressfull = $this->get_yacht_address($id, 2);
			$name = $this->yacht_name($id);
			$ppath = $this->get_yacht_first_image($id);
			$details_url = $cm->get_page_url($id, "yacht");
			$imagefolder = 'yachtimage/'. $listing_no .'/bigger/';
			$addressfull = $this->get_yacht_address($id);
			
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
					$clabel = $this->get_custom_label_name($custom_label_id);
					$custom_label_txt = '<div class="custom_label_div"'. $custom_label_extra_class .'><div>'. $clabel .'</div></div>';
				}
			}

			$price_display = $this->yacht_price_display($price, $price_tag_id, $charter_id, $charter_price, $price_per_option_id);
		
			$returntext .= '<ul>
				<li>'. $custom_label_txt .'<a href="'. $details_url .'"><img class="full" src="'. $cm->folder_for_seo . $imagefolder . $ppath .'" alt=""></a></li>
				<li>
					<div class="maxpriceboatinfo">
						<span class="info">'. $name .'</span>
						<span class="info2">'. $addressfull .'</span>
						<span class="price">'. $price_display .'</span>
						<span class="detailsbutton"><a class="button arrow" href="'. $details_url .'">Details</a></span>
					</div>
				</li>
			</ul>	
			';		
		}
		
		return $returntext;
	}
	
	//broker profile shortcode
	public function display_broker_profile_content($argu = array()){
		global $db, $cm, $frontend;
		
		//collect parameter
		$row = $argu["row"];
		//end
		
		foreach($row AS $key => $val){
			if ($key != "about_me"){
				${$key} = htmlspecialchars($val);
			}else{
				${$key} = $cm->filtertextdisplay($val);
			}	
		}
		
		$total_yacth_active = $this->get_total_yacht_by_broker(array("broker_id" => $id, "status_id" => 1));
		$total_yacht_sold = $this->get_total_yacht_by_broker(array("broker_id" => $id, "status_id" => 3, "sold_expired" => 1));
		$member_image = $this->get_user_image($id);
		
		$broker_ad_ar = $this->get_broker_address_array($id);		
		$address = $broker_ad_ar["address"];
		$city = $broker_ad_ar["city"];
		$state = $broker_ad_ar["state"];
		$state_id = $broker_ad_ar["state_id"];
		$country_id = $broker_ad_ar["country_id"];
		$zip = $broker_ad_ar["zip"];
		$officephone = $broker_ad_ar["phone"];
		
		$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id);
		$company_id = $this->get_broker_company_id($id);
		$com_url = $cm->get_page_url($company_id, 'comprofile');
		
		$brokername = $brokernameoriginal = $fname .' '. $lname;
		$gaeventtracking = $this->google_event_tracking_code('broker', $brokername);
		
		if ($display_title == 1 AND $title != ""){
			$brokername .= ' - ' . $title;
		}
		
		$office_phone_ext_text = '';
		if ($office_phone_ext != ""){
			$office_phone_ext_text = ' (Ex: ' . $office_phone_ext . ')';
		}
		
		//testimonial
		$retval = json_decode($frontend->testimonial_list_main(array("broker_id" => $id, "innerbox" => 1, "innerslider" => 1, "displaytype" => 1)));
		$testimonialfoundm = $retval->foundm;
		
		$returntext = '<div class="profile-main clearfixmain">
		<div class="mainleft">
			<img src="'. $cm->folder_for_seo .'userphoto/big/'. $member_image .'" alt="'. $brokernameoriginal .'" />
			'. $this->display_industry_associations($id, 2, 1) .'
			'. $this->display_certification($id, 2, 1) .'
		</div>
		
		<div class="mainright">
		';
		
		if ($type_id == 1 OR $type_id == 6){
			$returntext .= '
			<h1>'. $frontend->head_title($brokername) .'</h1>
			<div class="mobile"><a class="tel" href="tel:'. $phone .'"><span>'. $phone .'</span></a></div>
			<div class="clear extrapara"></div>
			'. $about_me .'
			';
		}else{
			$contactbutton_text = 'Contact';
			if ($type_id == 5){
				$contactbutton_text = 'Contact Broker';
			}
			
			$mobiletext = '';
			if ($phone != ""){
				$mobiletext = '<div class="mobile"><a class="tel" href="tel:'. $phone .'"><span>'. $phone .'</span></a></div>';
			}
			
			$returntext .= '
			<div class="left">
				<div class="meta">
					<h1>'. $frontend->head_title($brokername) .'</h1>
					'. $addressfull .'				            
					'. $mobiletext .'
					<div class="phone"><a class="tel" href="tel:'. $officephone .'"><span>'. $officephone . $office_phone_ext_text .'</span></a></div>
				</div>
			</div>
			
			<div class="right brokerprofile">
				<ul class="brokerprofilebutton">
			';
			
			if ($support_crew == 0){
				$returntext .= '<li><a '. $gaeventtracking .' href="javascript:void(0);" data-src="'. $cm->folder_for_seo .'contact-broker/?id='. $id .'" class="contactbroker button contact" data-type="iframe"><span>'. $contactbutton_text .'</span></a></li>';
			}
			
			if ($testimonialfoundm > 0){
				$returntext .= '<li><a href="javascript:void(0);" gotosection="1" class="button buttongoto"><i class="fa fa-comments" aria-hidden="true"></i>Read Testimonials</a></li>';
			}
			
			if ($total_yacth_active > 0 AND $display_listings == 1){
				$returntext .= '<li><a href="javascript:void(0);" gotosection="2" class="button buttongoto"><i class="fa fa-list-ol" aria-hidden="true"></i>View My Listings</a></li>';
			}
			
			if ($total_yacht_sold > 0){
				$soldboaturl = $cm->get_page_url($id, 'soldboat');
				$returntext .= '<li><a href="'. $soldboaturl .'" class="button"><i class="fa fa-dollar-sign" aria-hidden="true"></i>View My Sold Boats</a></li>';
			}
			
			$returntext .= '</ul>
				<div class="clear"></div>
				'. $this->get_user_social_media($id) .'
			</div>
			<div class="clear extrapara"></div>
			'. $about_me .'
			';
		}
		
		if ($loggedin_member_id == $id){
			$returntext .= '
			<ul class="listmenu">
				<li class="left"><a href="'. $cm->folder_for_seo .'editprofile/" class="icon-tools">Edit Profile</a></li>
			</ul>			
			';
		}
		
		$returntext .= '</div>
		</div>';
		
		if ($testimonialfoundm > 0){
			$returntext .= '<div class="gotosection1 clearfixmain">'. $retval->doc .'</div>';
		}
		
		if ($display_listings == 1){
			if ($type_id != 6){				
				$compareboat = 0;
				if ($type_id == 1){
					$sectiontitle = "All Listings";
				}else{
					$sectiontitle = "Listings by " . $fname . " " .  $lname;
				}
				$_SESSION["s_currenturl"] = '';
				$sql = $this->create_yacht_sql();
				$foundm = $this->total_yach_found($sql);

				$param_page = array(
					"to_get_val" => $sql,
					"section_for" => 2
				);
				$to_check_val = $cm->insert_data_set($param_page);
				if ($foundm > 0){
					$option_retval = json_decode($this->display_view_option());
					$dval = $option_retval->dval;
					
					$param = array(
						"compareboat" => $compareboat,
						"displayoption" => $dval,
						"to_check_val" => $to_check_val
					);
					$retval = json_decode($this->display_yacht_listing(1, $param));
					
					$returntext .= '
					<div class="profile-main gotosection2 clearfixmain">
						<h2 class="borderstyle1">Current Inventory</h2>
						<div class="header-bottom-bg clearfixmain">
							<div class="header-bottom-inner clearfixmain">
								<div class="sch">
									<span>'. $sectiontitle .'</span>
								</div>
								<div class="vp">'. $option_retval->doc .'</div>
								<div class="res"><div class="spinnersmall"><span class="reccounterupdate">'. $foundm .'</span> result(s)</div></div>
								<div class="clear"></div>
							</div>
						</div>
					</div>					
					<div id="listingholdermain" class="profile-main clearfixmain" to_check_val="'. $to_check_val .'">'. $retval[0]->doc .'</div>
					';
				}				
			}
		}
		
		$returntext .= '
		<script>
		//buttongoto
		$(document).ready(function(){
			$(".buttongoto").click(function(){
				var gotosection = parseInt($(this).attr("gotosection"));
				if(gotosection > 0){

					var gototop = $(".gotosection" + gotosection).offset().top;
					if($(window).width() > 685){
						var extranumber = parseInt($(".fcheader").height());
						gototop = gototop - extranumber;
					}

					$("html,body").animate({
						scrollTop: gototop},
					400);

				}
			});
		});
		</script>
		';
		
		$returnar = array(
			"brokername" => $brokernameoriginal,
			"doc" => $returntext
		);
		
		return json_encode($returnar);		
	}
	
	public function display_broker_profile($argu = array()){
		global $db, $cm, $frontend;
		$returntext = '';
		
		$brokerid = round($argu["brokerid"], 0);
		
		$sql = "select * from tbl_user where id = '". $brokerid ."' and status_id = 2";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		
		if ($found > 0){
			$addressfull = '';
			$row = $result[0];
			$broker_profile_ar = json_decode($this->display_broker_profile_content(array("row" => $row)));
			$returntext = $broker_profile_ar->doc;
		}
		
		return $returntext;
	}
	
	//Company Profile - Broker List
	public function company_profile_broker_list($argu = array()){
		global $db, $cm;
		$returntext = '';		
		$returntext = $this->display_our_team($argu);
		return $returntext;
	}
	
	//Company Profile - Invemtory List
	public function company_profile_boat_list($postfields = array()){
		global $db, $cm;
		$returntext = '';		
		$isdashboard = $postfields["isdashboard"];
					
		$_SESSION["s_currenturl"] = '';
		$sql = $this->create_yacht_sql();
		$foundm = $this->total_yach_found($sql);
		
		$param_page = array(
			"to_get_val" => $sql,
			"section_for" => 2
		);
		$to_check_val = $cm->insert_data_set($param_page);
		
		if ($foundm > 0){
			$param = array(
				"compareboat" => 0,
				"to_check_val" => $to_check_val
			);
			$retval = json_decode($this->display_yacht_listing(1, $param));
			$returntext .= '
			<div class="profile-main clearfixmain">
				<div class="header-bottom-bg clearfixmain">
					<div class="header-bottom-inner clearfixmain">
						<div class="sch">
							<span>Inventory List</span>
						</div>
						<div class="vp">
							<span>View options</span>
							<a href="javascript:void(0);" dval="1" title="Grid view" class="ydchange icon grid active">Grid view</a>
							<a href="javascript:void(0);" dval="2" title="List view" class="ydchange icon list">List view</a>
							<a href="javascript:void(0);" dval="3" title="Map view" class="ydchange icon map">Map view</a>
						</div>
						<div class="res"><div class="spinnersmall"><span class="reccounterupdate">'. $foundm .'</span> result(s)</div></div>						
					</div>
				</div>				
			</div>
			
			<div id="listingholdermain" class="profile-main clearfixmain" to_check_val="'. $to_check_val .'">
				'. $retval[0]->doc .'
			</div>
			';
		}		
		return $returntext;
	}
	
	//Special button for right col
	public function resource_widget_button($param = array()){
		global $cm;
		$returntext = '';		
		$buttontext = '';
		
		//param
		$listing_no = $param["listing_no"];
		$res_row = $param["res_row"];
		$buttonsection = $param["buttonsection"];
		//$outsideys = $param["outsideys"];
		//end
		
		//Service Request
		//$collected_page_id = $cm->get_page_id_by_shortcode("[fcservicerequestform");
		//$buttontext .= '<div class="buttonholder"><a href="'. $cm->get_page_url($collected_page_id, 'page') .'" class="button insurance_quote"><span>Service Request</span></a></div>';
		//end
		
		//Parts Request
		//$collected_page_id = $cm->get_page_id_by_shortcode("[fcpartsrequestform");
		//$buttontext .= '<div class="buttonholder"><a href="'. $cm->get_page_url($collected_page_id, 'page') .'" class="button parts_quote"><span>Parts Request</span></a></div>';
		//end
		
		//Financing
		//$collected_page_id = $cm->get_page_id_by_shortcode("[fcfinanceform");
		//$buttontext .= '<div class="buttonholder"><a href="'. $cm->get_page_url($collected_page_id, 'page') .'" class="button finance_quote"><span>Financing</span></a></div>';
		//end
		
				
		//boathistory report button
		//$boat_history_button = $this->display_boathistoryreport_button(0, $res_row, 2);
		//end
		
		if ($buttonsection == 2){
			//Seller Service
			$single_quote_button = '
			<div class="buttonholder"><a href="'. $cm->get_page_url(43, 'page') .'" class="button whylistwithus">Why List With PYS</a></div>
			<div class="buttonholder"><a href="'. $cm->get_page_url(52, 'page') .'" class="button marketingefforts">Our Marketing Efforts</a></div>
			<div class="buttonholder"><a href="'. $cm->get_page_url(53, 'page') .'" class="button listwithus">List Your Boat</a></div>
			';

			$returntext .= '
			<h3>PYS Seller Services</h3>
			<div class="sidebarspecialbutton clearfixmain">		
				'. $single_quote_button .'
				<p>Do You Own a Boat Like this? <a href="'. $cm->get_page_url(53, 'page') .'">Sell it Now</a></p>
			</div>
			';
		}else{
			//Buyer Service
			$single_quote_button = '
			<div class="buttonholder"><a href="'. $cm->get_page_url(65, 'page') .'" class="button buyusingbroker">Why Buy Using a Broker?</a></div>
			<div class="buttonholder"><a href="'. $cm->get_page_url(66, 'page') .'" class="button buyingsteps">Simply Buying Steps</a></div>
			<div class="buttonholder"><a href="'. $cm->get_page_url(54, 'page') .'" class="button tradeinboat">Trade In Your Boat</a></div>
			';

			$returntext .= '
			<h3>PYS Buyer Services</h3>
			<div class="sidebarspecialbutton clearfixmain">		
				'. $single_quote_button .'
			</div>
			';
		}
		
		
		
		return $returntext;
	}
	
	//Top make list by added boat
	public function get_top_make($param = array()){
		global $db, $cm;
		$returntext = '';
		$ownboat = round($param["ownboat"], 0);
		$limit = round($param["limit"], 0);
		$callaction = round($param["callaction"], 0);		
		$seourlcall = "make";
		
		$sql = "select a.id, a.slug, a.name, count(b.id) as total from tbl_manufacturer as a, tbl_yacht as b where a.id = b.manufacturer_id and a.status_id = 1 and b.status_id = 1 and b.display_upto >= CURDATE()";
		
		if ($ownboat > 0){
			if ($ownboat == 1){				
				$sql .= " and b.ownboat = 1";
				$seourlcall = "makeourlistings";
			}
			
			if ($ownboat == 2){
				$sql .= "  and b.yw_id > 0 and b.ownboat = 0";
				$seourlcall = "makecobrokerage";
			}
		}
		
		$sql .= " group by a.id order by total desc, a.name";
		
		if ($limit > 0){
			$sql .= " limit 0, " . $limit;
		}

		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$returntext .= '<ul class="topdata-list">';
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				$ret_url = $cm->get_page_url($id, $seourlcall);				
				$returntext .= '<li><a href="'. $ret_url .'">'. $name .'</a></li>';
			}
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	
	//top boat type by added boat
	public function get_top_boat_type($param = array()){
		global $db, $cm;
		$returntext = '';
		$ownboat = round($param["ownboat"], 0);
		$limit = round($param["limit"], 0);
		$callaction = round($param["callaction"], 0);
		$seourlcall = "type";
		
		$sql = "select a.id, a.slug, a.name, count(b.id) as total from tbl_type as a, tbl_yacht as b, tbl_yacht_type_assign as ct where a.id = ct.type_id and b.id = ct.yacht_id and a.status_id = 1 and b.status_id = 1 and b.display_upto >= CURDATE()";
		
		if ($ownboat > 0){
			if ($ownboat == 1){				
				$sql .= " and b.ownboat = 1";
				$seourlcall = "typeourlistings";
			}
			
			if ($ownboat == 2){
				$sql .= "  and b.yw_id > 0 and b.ownboat = 0";
				$seourlcall = "typecobrokerage";
			}
		}
		
		$sql .= " group by a.id order by total desc, a.name";
		
		if ($limit > 0){
			$sql .= " limit 0, " . $limit;
		}
		
		$result = $db->fetch_all_array($sql);
		$found = count($result);
		if ($found > 0){
			$returntext .= '<ul class="topdata-list">';
			foreach($result as $row){
				foreach($row AS $key => $val){
					${$key} = $cm->filtertextdisplay($val);
				}
				$ret_url = $cm->get_page_url($slug, $seourlcall);				
				$returntext .= '<li><a href="'. $ret_url .'">'. $name .'</a></li>';
			}
			$returntext .= '</ul>';
		}
		
		return $returntext;
	}
	
	//custom page heading
	public function display_custom_page_heading($param = array()){
		global $db, $cm;
		$returntext = '';
		
		$custom_make_id = $param["custom_make_id"];
		$custom_condition_id = $param["custom_condition_id"];
		$custom_stateid = $param["custom_stateid"];			
		
		if ($custom_condition_id > 0){
			$conditionname = $cm->get_common_field_name("tbl_condition", "name", $custom_condition_id);
			$returntext .= $conditionname . " ";
		}
		
		if ($custom_make_id > 0){
			$manufacturerarname = $cm->get_common_field_name("tbl_manufacturer", "name", $custom_make_id);
			$manufacturerarname = $this->format_brand_name($manufacturerarname);
			$returntext .= $manufacturerarname . " ";
		}
		
		if ($custom_stateid > 0){
			$statename = $cm->get_common_field_name("tbl_state", "name", $custom_stateid);
			$returntext .= "In " . $statename . " ";
		}
		
		$returntext = rtrim($returntext, " ");
		return $returntext;
	}
	
	//Similar Yacht
	public function display_similar_yacht($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//get param
		$default_param = array(
			"lnmin" => 0,
			"lnmax" => 0,
			"prmin" => 0,
			"prmax" => 0,
			"categoryid" => 0,
			"owned" => 1,
			"sp_typeid" => 1,
			"similaryacht_type_filter" => 0,
			"currentboat" => 0,
			"template" => 0
		);
		$param = array_merge($default_param, $param);
				
		$lnmin = round($param["lnmin"], 0);
		$lnmax = round($param["lnmax"], 0);
		$prmin = round($param["prmin"], 0);
		$prmax = round($param["prmax"], 0);
		$categoryid = round($param["categoryid"], 0);
		//$owned = round($param["owned"], 0);
		$owned = 2;
		$sp_typeid = round($param["sp_typeid"], 0);
		$similaryacht_type_filter = round($param["similaryacht_type_filter"], 0);
		$currentboat = round($param["currentboat"], 0);
		$template = round($param["template"], 0);
		//end
		
		//title set
		if ($sp_typeid == 2){
			$sm_title = "Catamarans";
		}else{
			$sm_title = "Yachts";
		}
		//end
		
		//exclude some boat ids  - Home assigned boat and current boat
		$homd_assigned_featured_boats = $this->get_home_featured_boat_ids() . ', ' . $currentboat;
		
		//sql
		$query_sql = "select distinct a.*";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_form .= " tbl_manufacturer as b,";
		$query_where .= " b.id = a.manufacturer_id and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		if ($categoryid > 0){
			$query_where .= " a.category_id = '". $categoryid ."' and";
		}
		
		if ($prmin > 0){
			$query_where .= " a.price >= '". $prmin ."' and";
		}
		
		if ($prmax > 0){
			$query_where .= " a.price <= '". $prmax ."' and";
		}
		
		if ($lnmin > 0){
			$query_where .= " c.length >= '". $lnmin ."' and";
		}
		
		if ($lnmax > 0){
			$query_where .= " c.length <= '". $lnmax ."' and";
		}
		
		if ($owned == 1){
			$query_where .= "  a.ownboat = 1 and";
			if ($sp_typeid == 1){
				$query_form .= " tbl_yacht_type_assign as d,";
				$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->catamaran_id .") and";
			}elseif ($sp_typeid == 2){
				$query_form .= " tbl_yacht_type_assign as d,";
				$query_where .= " a.id = d.yacht_id and (d.type_id IN (". $this->catamaran_id .") OR a.feed_id = '". $this->catamaran_feed_id2 ."') and";
			}				
		}else{
			$query_where .= "  a.yw_id > 0 and a.ownboat = 0 and";
			if ($sp_typeid == 1){
				$query_where .= "  a.feed_id = '". $this->yacht_feed_id ."' and";
			}elseif ($sp_typeid == 2){
				$query_where .= "  a.feed_id = '". $this->catamaran_feed_id ."' and";
			}
			
			if ($similaryacht_type_filter > 0){
				
				if ($similaryacht_type_filter == $this->centerconsole_id ){
					//only center console
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->centerconsole_id .") and";
					
				}elseif ($similaryacht_type_filter == $this->sportfishing_id ){
					//only sport fishing
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->sportfishing_id .") and";
					
				}elseif ($similaryacht_type_filter == $this->convertible_id ){
					//only convertible
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id IN (". $this->convertible_id .") and";
					
				}elseif ($similaryacht_type_filter == $this->motoryacht_id ){
					//Motor Yacht - exclude Sportfishing and CONVERTIBLE BOAT
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->sportfishing_id .", ". $this->convertible_id .") and";
					
				}else{
					//all except center console
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->centerconsole_id .") and";
				}
				
			}
		}
		
		$query_where .= " a.status_id = 1 and";
		//$query_where .= " a.id != '". $currentboat ."' and";
		//$query_where .= " a.id NOT IN (". $homd_assigned_featured_boats .") and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$sql = $sql . " order by RAND() limit 0, 3";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		//echo $sql;
		//end
		
		//process
		if ($found > 0){		
			$qstring = "?lnmin=" . $lnmin . "&lnmax=" . $lnmax . "&prmin=" . $prmin . "&prmax=" . $prmax . "&categoryid=" . $categoryid. "&owned=" . $owned. "&sp_typeid=" . $sp_typeid. "&similaryacht_type_filter=" . $similaryacht_type_filter . "&freshstart=1&rawtemplate=0";
			
			$formpostar = json_decode($this->get_advanced_search_post_url());
			/*
			if ($owned == 2){
				if ($sp_typeid == 2){
					$all_url = $formpostar->post_url2_catamaran;
				}else{
					$all_url = $formpostar->post_url2_yacht;
				}				
			}else{
				if ($sp_typeid == 2){
					$all_url = $formpostar->post_url_catamaran;
				}else{
					$all_url = $formpostar->post_url_yacht;
				}
			}
			*/
			$all_url = $cm->get_page_url(2, "page");
			
			$returntext = '
			<div class="similaryacht clearfixmain">
			<h2 class="singlelinebottom30"><span>Similar</span> '. $sm_title .' For Sale</h2>
			
			<ul>
				<li>
				<p>'. nl2br($cm->get_systemvar('SYFSC')) .'</p>
				<p><a class="button" href="'. $all_url . $qstring .'">View All</a></p>
				</li>
				<li>
					<ul class="product-list gridview-new">
			';
			
				foreach($result as $row){
					$returntext .= $this->display_yacht($row, 1, '', 0, 0, 0);
					$this->update_yacht_view($row["id"], 2);
                }
			
			$returntext .= '
					</ul>
				</li>
			';
			
			$returntext .= '
			</ul>
			</div>';
			
			if ($template == 1){
				$returntext = '
				<div class="container clearfixmain">
					'. $returntext .'
				</div>
				';
			}
		}
		//end
		
		return $returntext;
	}
	
	//Most viewed/popular boats
	public function most_popular_boat_list($param = array()){
		global $db, $cm;
		$returntext = '';
		
		//get param
		$default_param = array(
			"sp_typeid" => 0,
			"owned" => 0
		);
		$param = array_merge($default_param, $param);
		$sp_typeid = round($param["sp_typeid"], 0);
		$owned = round($param["owned"], 0);
		//end
		
		//sql
		
		$query_sql = "select distinct a.*,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		$query_group_by = "";
		
		$query_form .= " tbl_manufacturer as b,";
		$query_where .= " b.id = a.manufacturer_id and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		if ($owned > 0){
			if ($owned == 1){
				if ($sp_typeid == 1){
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and d.type_id NOT IN (". $this->catamaran_id .") and";
				}elseif ($sp_typeid == 2){
					$query_form .= " tbl_yacht_type_assign as d,";
					$query_where .= " a.id = d.yacht_id and (d.type_id IN (". $this->catamaran_id .")  OR a.feed_id = '". $this->catamaran_feed_id2 ."') and";
				}
				
				$query_where .= "  a.ownboat = 1 and";
			}elseif ($owned == 2){
				$query_where .= "  a.yw_id > 0 and a.ownboat = 0 and";
					
				if ($sp_typeid == 1){
					$query_where .= "  a.feed_id = '". $this->yacht_feed_id."' and";
				}elseif ($sp_typeid == 2){
					$query_where .= "  a.feed_id IN = '". $this->catamaran_feed_id."' and";
				}
			}
		}else{
			if ($sp_typeid == 1){
				$query_form .= " tbl_yacht_type_assign as d,";
				$query_where .= " a.id = d.yacht_id and ((d.type_id NOT IN (". $this->catamaran_id .")  and a.ownboat = 1) OR a.feed_id = '". $this->yacht_feed_id."') and";
			}elseif ($sp_typeid == 2){
				$query_form .= " tbl_yacht_type_assign as d,";
				$query_where .= " a.id = d.yacht_id and ((d.type_id IN (". $this->catamaran_id .")  and a.ownboat = 1) OR a.feed_id IN ('". $this->catamaran_feed_id."','". $this->catamaran_feed_id2 ."')) and";
			}
		}
		
		$mostviewed = 30;
		$query_sql .= " sum(mv.total_view) as total_view_boat,";
		$query_form .= " tbl_yacht_view as mv,";
		$query_where .= " a.id = mv.yacht_id and mv.reg_date >= DATE_SUB(CURDATE(), INTERVAL ". $mostviewed ." DAY) and mv.view_type = 1 and";
		$query_group_by = " GROUP BY a.id";
		
		$query_where .= " a.status_id = 1 and";
		
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where . $query_group_by;
		
		$sql = $sql . " order by total_view_boat desc limit 0, 30";
		$result = $db->fetch_all_array($sql);
        $found = count($result);
		//end
		
		$compareboat = 0;
		$charter = 0;
		$extraclass = '';
		$displayoption = 1;
		
		if ($found > 0){
			$returntext .= '
			<div class="mostviewed clearfixmain">
				<ul class="product-list gridview-new">
			';
			
			foreach($result as $row){
				$returntext .= $this->display_yacht($row, $displayoption, $extraclass, $compareboat, $charter);				
			}
			
			$returntext .= '
				</ul>
			</div>
			';
		}else{
			global $frontend;
			$returntext = '<script src="https://www.google.com/recaptcha/api.js" async defer></script><p>'. $cm->get_systemvar('BTNFD') .'</p>'. $frontend->display_boat_finder_form(1);
		}
		
		return $returntext;
	}
	
	//----------sold boat stat---------------
	//total sold unit
	public function get_total_sold_unit($argu = array()){
		global $db, $cm;
		$yearfor = round($argu["yearfor"], 0);
		
		$query_sql = "select count(a.id) as ttl,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
		$query_where .= " a.status_id = 3 and";
		$query_where .= " YEAR(a.sold_date) = '". $yearfor ."' and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$total_sold_unit = $db->total_record_count($sql);
		return $total_sold_unit;
	}
	
	//Total Length
	public function get_total_length_sold_unit($argu = array()){
		global $db, $cm;
		$yearfor = round($argu["yearfor"], 0);
		
		$query_sql = "select sum(c.length) as ttl,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
		$query_where .= " a.status_id = 3 and";
		$query_where .= " YEAR(a.sold_date) = '". $yearfor ."' and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$total_length_sold_unit = $db->total_record_count($sql);
		return $total_length_sold_unit;
	}
	
	//Total Price
	public function get_total_price_sold_unit($argu = array()){
		global $db, $cm;
		$yearfor = round($argu["yearfor"], 0);
		
		$query_sql = "select sum(a.price) as ttl,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.manufacturer_id > 0 and";
		$query_where .= " a.status_id = 3 and";
		$query_where .= " YEAR(a.sold_date) = '". $yearfor ."' and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");
		$sql = $query_sql . $query_form . $query_where;
		$total_price_sold_unit = $db->total_record_count($sql);
		return $total_price_sold_unit;
	}
	
	public function display_sold_boat_stat($argu = array()){
		global $cm;
		$current_year = date("Y");
		$last_year = $current_year - 1;		
		$home = round($argu["home"], 0);
		
		$container_start = '';
		$container_end = '';
		$innerpageclass = ' inner';
		if ($home == 1){
			$container_start = '<div class="container clearfixmain">';
			$container_end = '</div>';
			$innerpageclass = '';
		}
		
		$returntext = '
		<div class="generalstat'. $innerpageclass .' clearfixmain">
			'. $container_start .'
				<h2>'. $cm->sitename .' Activity</h2>
				
				<ul class="generalstat_parent">
				';
				
				for ($k = $current_year; $k >= $last_year; $k--){
					
					$returntext .= '<li>
						<h3>'. $k .'</h3>						
						<ul class="generalstat_child">
							<li>
								<h3><span class="numbercounter">'. round($this->get_total_sold_unit( array("yearfor" => $k) ), 0) .'</span></h3>
								<h4>Units</h4>
							</li>
							
							<li>
								<h3><span class="numbercounter">'. round($this->get_total_length_sold_unit( array("yearfor" => $k) ), 0) .'</span></h3>
								<h4>Length In Feet</h4>
							</li>
							
							<li>
								<h3>$<span class="numbercounter">'. $cm->format_price($this->get_total_price_sold_unit( array("yearfor" => $k) ), 0) .'</span></h3>
								<h4>Value of Boats</h4>
							</li>
						</ul>						
					</li>
					';
				}				
				
				$returntext .= '
				</ul>
				
			'. $container_end .'
		</div>
		';
		
		return $returntext;
	}

	//All acttive boat combo
	public function get_active_boat_combo($argu = array()){
		global $db, $cm;
		$returntxt = '';
		$returnarray = array();
		
		$loggedin_member_id = $this->loggedin_member_id();
		$company_id = round($argu["company_id"]);
		$location_id = round($argu["location_id"]);
		$chosanbrokerid = round($argu["chosanbrokerid"]);
		$boat_id = round($argu["boat_id"]);
		$azop = round($argu["azop"]);
		
		$query_sql = "select a.id, a.vessel_name,";
        $query_form = " from tbl_yacht as a,";
        $query_where = " where";
		
		$query_where .= " a.company_id = '". $company_id ."' and";

		if ($chosanbrokerid > 0){
			$query_where .= " a.broker_id = '". $chosanbrokerid ."' and";
		}else{
			if ($loggedin_member_id > 1){
				$query_where .= " a.broker_id = '". $loggedin_member_id ."' and";
			}
		}
		
		if ($location_id > 0){
			$query_where .= " a.location_id = '". $location_id ."' and";
		}
		
		$query_form .= " tbl_manufacturer as b,";
		$query_where .= " b.id = a.manufacturer_id and";
		
		$query_form .= " tbl_yacht_dimensions_weight as c,";
		$query_where .= " a.id = c.yacht_id and";
		
		$query_where .= " a.status_id = 1 and";
		
		$query_sql = rtrim($query_sql, ",");
        $query_form = rtrim($query_form, ",");
        $query_where = rtrim($query_where, "and");

        $sql = $query_sql . $query_form . $query_where;
		
		$sql .= " order by a.year desc, b.name, a.model";
        $result = $db->fetch_all_array($sql);
        foreach($result as $row){
            $id = $row['id'];
            $vessel_name = $row['vessel_name'];
			
			$yacht_title = $this->yacht_name($id);
			/*if ($vessel_name != ""){
				$yacht_title .= " - " . $yacht_title;
			}*/
			
			if ($azop == 1){
				$returnarray[] = array(
					'text' => $yacht_title,
					'textval' => $id
				);
			}else{
				$bck = '';
				if ($boat_id == $id){
					$bck = ' selected="selected"';	
				}
				$returntxt .= '<option value="'. $id .'"'. $bck .'>'. $yacht_title .'</option>'; 
			}
        }
		
		if ($azop == 1){
			$returnval = array(
				'doc' => $returnarray
			);
        	return json_encode($returnval);
		}else{
			return $returntxt;
		}		
	}
	
	public function get_location_office_address($location_id){
		global $db, $cm;
		$location_ar = $cm->get_table_fields('tbl_location_office', 'address, city, state, state_id, country_id, zip, phone', $location_id);		       
		$address = $location_ar[0]["address"];
		$city = $location_ar[0]["city"];
		$state = $location_ar[0]["state"];
		$state_id = $location_ar[0]["state_id"];
		$country_id = $location_ar[0]["country_id"];
		$zip = $location_ar[0]["zip"];					
		$addressfull = $this->com_address_format('', $city, $state, $state_id, $country_id,'');
		return $addressfull;
	}
	
	//DASHBOARD - GRAPH CALL----------------------------
	public function get_max_boat_year(){
		global $db;
		$sql = "select max(year) as ttl from tbl_yacht where year > 0 and status_id = 1";
		$max_year = $db->total_record_count($sql);
		return $max_year;
	}
	
	public function get_min_boat_year(){
		global $db;
		$sql = "select min(year) as ttl from tbl_yacht where year > 0 and status_id = 1";
		$min_year = $db->total_record_count($sql);
		return $min_year;
	}
	
	public function get_boat_year_combo($selyear = 0){
		 global $cm;
		 $min_year = $this->get_min_boat_year();
		 $max_year = $this->get_max_boat_year();		 
		 return $cm->get_genyear_combo1($selyear, $max_year, $min_year, 1);
	}
	
	public function get_boat_length_segments_combo($length_segments_id = 0){
		global $db;
		$returntxt = '';
		$vsql = "select id, name from tbl_length_segments where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];
			
			$bck = '';
			if ($length_segments_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';    
		}		
		return $returntxt;
	}
	
	public function get_boat_price_segments_combo($price_segments_id = 0){
		global $db;
		$returntxt = '';
		$vsql = "select id, name from tbl_price_segments where status_id = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
			$c_id = $vrow['id'];
			$cname = $vrow['name'];
			
			$bck = '';
			if ($price_segments_id == $c_id){
				$bck = ' selected="selected"';	
			}
			$returntxt .= '<option value="'. $c_id .'"'. $bck .'>'. $cname .'</option>';    
		}		
		return $returntxt;
	}
	
	//impression - view - lead
	public function impression_view_lead_val(){	
		$val_ar = array();
		$val_ar[] = array("name" => "", "oth" => 0);
		$val_ar[] = array("name" => "Impressions", "oth" => 0);
		$val_ar[] = array("name" => "Views", "oth" => 0);
		$val_ar[] = array("name" => "Leads", "oth" => 0);		
		$val_ar = json_encode($val_ar);
		return $val_ar;		
	}
	public function impression_view_lead_combo($im_view_lead = 0){
        global $db;
		$val_ar = json_decode($this->impression_view_lead_val());		
		$returntext = '';
  
        foreach($val_ar as $key => $val_row){
            $cname = $val_row->name;
			
			if ($key > 0){
				$bck = '';
				if ($im_view_lead == $key){
					$bck = ' selected="selected"';	
				}			
				$returntext .= '<option value="'. $key .'"'. $bck .'>'. $cname .'</option>';
			}
        }		
		return $returntext;
    }
	
	public function display_most_viewed_data($view_id = 1, $param = array(), $limit = 0){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();		
		$returntext = '';
		
		//Different parameter		
		$company_id = round($param["company_id"], 0);
		$location_id = round($param["location_id"], 0);
		$chosanbrokerid = round($param["chosanbrokerid"], 0);
		$onlymylistings = round($param["onlymylistings"], 0);
		
		$boat_id = round($param["boat_id"], 0);
		$boat_make = round($param["boat_make"], 0);
		$boat_model = $param["boat_model"];
		$boat_year = round($param["boat_year"], 0);
		$boat_type = round($param["boat_type"], 0);
		
		$fr_date = $param["fr_date"];
		$to_date = $param["to_date"];
		$wh_print_view = round($param["wh_print_view"]);
		//end		
		
		$headingh4 = 'width: 100%; background-color: #e1e1e1; text-align: left; padding: 6px 10px; font-weight: bold; font-family: Arial; font-size: 18px;';
		$headingh5 = 'width: 100%; background-color: #e1e1e1; text-align: left; padding: 6px 10px; font-weight: bold; font-family: Arial; font-size: 15px;';
		
		//Boat View
		if ($view_id == 1){
			$query_sql = "select sum(a.total_view) as total_view_lead, b.id, b.listing_no,";
			$query_form = " from tbl_yacht_view as a,";
			$query_where = " where";
			
			$query_form .= " tbl_yacht as b,";
			$query_where .= " a.yacht_id = b.id and";
			
			if ($loggedin_member_id == 1){
				if ($company_id > 0){
					$query_where .= " b.company_id = '". $company_id ."' and";						
				}
				
				if ($chosanbrokerid > 0){
					$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";						
				}
			}else{						
				$query_where .= " b.company_id = '". $company_id ."' and";
				if ($onlymylistings == 1){
					$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
				}
			}
			
			if ($location_id > 0){
				$query_where .= " b.location_id = '". $location_id ."' and";						
			}
			
			if ($boat_id > 0){
				$query_where .= " a.yacht_id = '". $boat_id ."' and";
			}
			
			if ($boat_make > 0){
				$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
			}

			if ($boat_model != ""){
				$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
			}

			if ($boat_year > 0){
				$query_where .= " b.year = '". $boat_year ."' and";
			}

			if ($boat_type > 0){
				$query_form .= " tbl_yacht_type_assign as tp,";
				$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
			}
			
			if ($fr_date != ""){
				$fr_date_a = $cm->set_date_format($fr_date);
				$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
			}
			if ($to_date != ""){
				$to_date_a = $cm->set_date_format($to_date);
				$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
			}
			
			$query_where .= " b.status_id = 1 and";
			$query_where .= " a.view_type = 1 and";
			
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where . " GROUP BY b.id order by total_view_lead desc";
			
			if ($limit > 0){
				$sql .= " limit 0," . $limit;
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			
			if ($wh_print_view == 1){
				//Print View
				$returntext .= '<div style="'. $headingh5 .'">Most Viewed</div>';
				$returntext .= '<div style="width: 100%; border: 1px solid #e1e1e1;">';
				
				if ($found > 0){				
					foreach($result as $row){
						$returntext .= $this->most_view_lead_boat_display($row, $wh_print_view);
					}
				}else{
					$returntext .= 'No Data';
				}
				
				$returntext .= '</div>';
				
			}else{
				//Page View
				$view_link = '';
				if ($chosanbrokerid == 0){
					$view_link = '<div class="chartbutton clearfixmain"><a class="headbutton" href="'. $cm->folder_for_seo .'mostviewed/" title="View All">View All</a></div>';					
				}
				
				$returntext .= '
				<div class="charthead clearfixmain">
					<h5>Most Viewed</h5>
					'. $view_link .'
				</div>
				<div class="singlechart clearfixmain">
				';
				
				if ($found > 0){				
					foreach($result as $row){
						$returntext .= $this->most_view_lead_boat_display($row);
					}
				}else{
					$returntext .= 'No Data';
				}
			
				$returntext .= '</div>';
			}
		}
		//end
		
		//Boat Lead
		if ($view_id == 2){
			$query_sql = "select count(a.yacht_id) as total_view_lead, b.id, b.listing_no,";
			$query_form = " from tbl_form_lead as a,";
			$query_where = " where";
			
			$query_form .= " tbl_yacht as b,";
			$query_where .= " a.yacht_id = b.id and";
			
			if ($loggedin_member_id == 1){
				if ($company_id > 0){
					$query_where .= " b.company_id = '". $company_id ."' and";						
				}
				
				if ($chosanbrokerid > 0){
					$query_where .= " b.broker_id = '". $chosanbrokerid ."' and";						
				}
			}else{						
				$query_where .= " b.company_id = '". $company_id ."' and";
				if ($onlymylistings == 1){
					$query_where .= " b.broker_id = '". $loggedin_member_id ."' and";
				}
			}
			
			if ($location_id > 0){
				$query_where .= " b.location_id = '". $location_id ."' and";						
			}
			
			if ($boat_id > 0){
				$query_where .= " a.yacht_id = '". $boat_id ."' and";
			}
			
			if ($boat_make > 0){
				$query_where .= " b.manufacturer_id = '". $boat_make ."' and";
			}

			if ($boat_model != ""){
				$query_where .= " b.model like '%".$cm->filtertext($modelname)."%' and";
			}

			if ($boat_year > 0){
				$query_where .= " b.year = '". $boat_year ."' and";
			}

			if ($boat_type > 0){
				$query_form .= " tbl_yacht_type_assign as tp,";
				$query_where .= " a.yacht_id = tp.yacht_id and tp.type_id = '". $boat_type ."' and";
			}
			
			if ($fr_date != ""){
				$fr_date_a = $cm->set_date_format($fr_date);
				$query_where .= " a.reg_date >= '". $fr_date_a ."'  and";
			}
			if ($to_date != ""){
				$to_date_a = $cm->set_date_format($to_date);
				$query_where .= " a.reg_date <= '". $to_date_a ."'  and";
			}
			
			$query_where .= " b.status_id = 1 and";
				
			$query_sql = rtrim($query_sql, ",");
			$query_form = rtrim($query_form, ",");
			$query_where = rtrim($query_where, "and");
			
			$sql = $query_sql . $query_form . $query_where . " GROUP BY b.id order by total_view_lead desc";
			
			if ($limit > 0){
				$sql .= " limit 0," . $limit;
			}
			
			$result = $db->fetch_all_array($sql);
			$found = count($result);
			
			if ($wh_print_view == 1){
				//Print View
				$returntext .= '<div style="'. $headingh5 .'">Most Leads</div>';
				$returntext .= '<div style="width: 100%; border: 1px solid #e1e1e1;">';
				
				if ($found > 0){				
					foreach($result as $row){
						$returntext .= $this->most_view_lead_boat_display($row, $wh_print_view);
					}
				}else{
					$returntext .= 'No Data';
				}
				
				$returntext .= '</div>';
				
			}else{
				//Page View
				$view_link = '';
				if ($chosanbrokerid == 0){
					$view_link = '<div class="chartbutton clearfixmain"><a class="headbutton" href="'. $cm->folder_for_seo .'mostleads/" title="View All">View All</a></div>';					
				}
				$returntext .= '
				<div class="charthead clearfixmain">
					<h5>Most Leads</h5>
					'. $view_link .'
				</div>
				<div class="singlechart clearfixmain">
				';
				
				if ($found > 0){				
					foreach($result as $row){
						$returntext .= $this->most_view_lead_boat_display($row);
					}
				}else{
					$returntext .= 'No Data';
				}
				
				$returntext .= '</div>';
			}
		}
		//end
		
		return $returntext;
	}
	
	public function display_chart($chart_id = 1, $param = array()){
		global $cm, $chartclass;		
		$returntext = '';
		$boat_id = round($param["boat_id"], 0);
		
		$section = 1;
		$yr = date("Y");
		$mn = date("m");
		
		$wh_print_view = round($param["wh_print_view"]);
		$headingh4 = 'width: 100%; background-color: #e1e1e1; text-align: left; padding: 6px 10px; font-weight: bold; font-family: Arial; font-size: 18px;';
		$headingh5 = 'width: 100%; background-color: #e1e1e1; text-align: left; padding: 6px 10px; font-weight: bold; font-family: Arial; font-size: 15px;';
		
		//Chart 1 - Impression / View /Leads
		if ($chart_id == 1){
			$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));
			$im_view_lead = round($param["im_view_lead"], 0);
			
			if ($wh_print_view == 1){
				//Print View				
				$impression_view_lead_text_ar = json_decode($this->impression_view_lead_val());
				$impression_view_lead_text = $impression_view_lead_text_ar[$im_view_lead]->name;
				
				$returntext .= '
				<div style="'. $headingh4 .' margin-bottom: 10px;">'. $retval->extra_return->search_text .'</div>
				
				<div style="'. $headingh5 .'">'. $impression_view_lead_text .'</div>
				<div style="width: 100%;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
				
				<div style="width: 49%; float: left; text-align: left; padding-top: 6px;">
					<div style="'. $headingh5 .'">'. $retval->extra_return->total_count_title .'</div>
					<div style="width: 100%;  padding: 6px 5px; border: 1px solid #e1e1e1; text-align: center; font-size: 34px;">'. $retval->extra_return->total_count .'</div>
				</div>
				<div style="width: 49%; float: right; text-align: left; padding-top: 6px;">
					<div style="'. $headingh5 .'">'. $retval->extra_return->avg_count_title .'</div>
					<div style="width: 100%; padding: 6px 5px; border: 1px solid #e1e1e1; text-align: center; font-size: 34px;">'. $retval->extra_return->avg_count .'</div>
				</div>
				';
			}else{
				//Page View		
				$returntext .= '
				<div class="charthead charthead1 clearfixmain"><h4>'. $retval->extra_return->search_text .'</h4></div>
				
				<div class="singlechart clearfixmain">
					<div class="right-cell-half clearfixmain">
						<ul class="form">                            
							<li class="right">
								<select fsection="1" id="im_view_lead" name="im_view_lead" class="select">
									'. $this->impression_view_lead_combo($im_view_lead) .'
								</select>
							</li>
						</ul>
					</div>
					<div class="clear"></div>
					<div id="large_chart_stat" class="clearfixmain">
						'. $retval->doc .'
					</div>
				</div>
				
				<div class="singlechart clearfixmain">
					<div class="left-cell-half clearfixmain">
						<div class="charthead charthead1_1 clearfixmain"><h5>'. $retval->extra_return->total_count_title .'</h5></div>
						<div class="boxvalue boxvalue1_1">'. $retval->extra_return->total_count .'</div>
					</div>
					
					<div class="right-cell-half clearfixmain">
						<div class="charthead charthead1_2 clearfixmain"><h5>'. $retval->extra_return->avg_count_title .'</h5></div>
						<div class="boxvalue boxvalue1_2">'. $retval->extra_return->avg_count .'</div>
					</div>
				</div>
				';
			}
		}
		//end

		if ($boat_id == 0){
		
			//Chart 2 - View by boat length
			if ($chart_id == 2){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));	
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">View Boat % by Length</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>View Boat % by Length</h5>
					</div>
					<div class="singlechart">				
						<div id="view_boat_length_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}
			
			//Chart 3 - Leads by boat length
			if ($chart_id == 3){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));	
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">Leads Boat % by Length</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>Leads Boat % by Length</h5>
					</div>
					<div class="singlechart">				
						<div id="leads_boat_length_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}		
			
			//Chart 4 - View by boat value
			if ($chart_id == 4){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));
				
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">View Boat % by Value</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>View Boat % by Value</h5>
					</div>
					<div class="singlechart">				
						<div id="view_boat_value_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}
			
			//Chart 5 - Leads by boat value
			if ($chart_id == 5){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));
				
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">Leads Boat % by Value</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>Leads Boat % by Value</h5>
					</div>
					<div class="singlechart">				
						<div id="leads_boat_value_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}
			
			
			//Chart 6 - View by boat age
			if ($chart_id == 6){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));
				
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">View Boat % by Age</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>View Boat % by Age</h5>
					</div>
					<div class="singlechart">				
						<div id="view_boat_age_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}
			
			//Chart 7 - Leads by boat age
			if ($chart_id == 7){				
				$retval = json_decode($chartclass->display_graph($section, $chart_id, $param));
				
				if ($wh_print_view == 1){
					//Print View
					$returntext .= '
					<div style="'. $headingh5 .'">Leads Boat % by Age</div>
					<div style="width: 100%; border: 1px solid #e1e1e1;"><img src="'. $cm->folder_for_seo . 'chartimg/' . $retval->doc .'" /></div>
					';
				}else{
					//Page View
					$returntext .= '
					<div class="charthead clearfixmain">
						<h5>Leads Boat % by Age</h5>
					</div>
					<div class="singlechart">				
						<div id="leads_boat_age_percent" class="piechart1 clearfixmain">
							'. $retval->doc .'
						</div>
					</div>
					';
				}
			}		
		}

		return $returntext;
	}
	
	public function display_site_stat_top_section($param = array()){
		global $db, $cm, $chartclass;
		$returntext = '';
		$wh_print_view = round($param["wh_print_view"]);
		
		$section = 1;
		$yr = date("Y");
		$mn = date("m");
		
		if ($wh_print_view == 1){
			//Prine View
			$returntext .= '
			<div style="width: 100%; float: left; text-align: left; padding-top: 6px;">'. $this->display_chart(1, $param) . '</div>
			
			<div style="width: 49%; float: left; text-align: left; padding-top: 6px;">'. $this->display_chart(2, $param) . '</div>
			<div style="width: 49%; float: right; text-align: left; padding-top: 6px;">'. $this->display_chart(3, $param) . '</div>
			
			<div style="width: 49%; float: left; text-align: left; padding-top: 6px;">'. $this->display_chart(4, $param) . '</div>
			<div style="width: 49%; float: right; text-align: left; padding-top: 6px;">'. $this->display_chart(5, $param) . '</div>
			
			<div style="width: 49%; float: left; text-align: left; padding-top: 6px;">'. $this->display_chart(6, $param) . '</div>
			<div style="width: 49%; float: right; text-align: left; padding-top: 6px;">'. $this->display_chart(7, $param) . '</div>
			
			<div style="width: 49%; float: left; text-align: left; padding-top: 6px;">'. $this->display_most_viewed_data(1, $param, 3) . '</div>
			<div style="width: 49%; float: right; text-align: left; padding-top: 6px;">'. $this->display_most_viewed_data(2, $param, 3) . '</div>
			';
		}else{
			//Page View
			$returntext .= '<ul class="chartcolmix">';
			$returntext .= '<li class="singlecol">'. $this->display_chart(1, $param) . '</li>';
			
			$returntext .= '<li class="leftcol">'. $this->display_chart(2, $param) . '</li>';
			$returntext .= '<li class="rightcol">'. $this->display_chart(3, $param) . '</li>';
			
			$returntext .= '<li class="leftcol">'. $this->display_chart(4, $param) . '</li>';
			$returntext .= '<li class="rightcol">'. $this->display_chart(5, $param) . '</li>';
			
			$returntext .= '<li class="leftcol">'. $this->display_chart(6, $param) . '</li>';
			$returntext .= '<li class="rightcol">'. $this->display_chart(7, $param) . '</li>';
			
			$returntext .= '<li class="leftcol">'. $this->display_most_viewed_data(1, $param, 3) . '</li>';
			$returntext .= '<li class="rightcol">'. $this->display_most_viewed_data(2, $param, 3) . '</li>';
					
			$returntext .= '</ul>';
		}
		
		return $returntext;		
	}
	
	public function dashboard_site_stat_print(){
		if(($_REQUEST['fcapi'] == "sitestatprint")){
			global $db, $cm, $frontend, $fle;
			$frontend->go_to_login();
			
			$loggedin_member_id = $this->loggedin_member_id();
			if ($loggedin_member_id == 1){
				$company_id = round($_REQUEST["company_id"], 0);
			}else{
				$company_id = $this->get_broker_company_id($loggedin_member_id);
			}
			
			$location_id = round($_REQUEST["location_id"], 0);
			$chosanbrokerid = round($_REQUEST["chosanbrokerid"], 0);
			$onlymylistings = round($_REQUEST["onlymylistings"], 0);
			
			$boat_id = round($_REQUEST["boat_id"], 0);
			$boat_make = round($_REQUEST["boat_make"], 0);
			$boat_model = $_REQUEST["boat_model"];
			$boat_year = round($_REQUEST["boat_year"], 0);
			$boat_type = round($_REQUEST["boat_type"], 0);
			
			$fr_date = $_REQUEST["fr_date"];
			$to_date = $_REQUEST["to_date"];
			$im_view_lead = round($_REQUEST["im_view_lead"], 0);

			$currenttime = time();
			
			$param = array(
				"company_id" => $company_id,
				"location_id" => $location_id,
				"chosanbrokerid" => $chosanbrokerid,
				"onlymylistings" => $onlymylistings,
				"boat_id" => $boat_id,
				"boat_make" => $boat_make,
				"boat_model" => $boat_model,
				"boat_year" => $boat_year,
				"boat_type" => $boat_type,
				"fr_date" => $fr_date,
				"to_date" => $to_date,
				"im_view_lead" => $im_view_lead,
				"currenttime" => $currenttime,
				"wh_print_view" => 1
			);
			$filename = "sitestat.pdf";
			$html =  $this->display_site_stat_top_section($param);
			$cm->generate_pdf('', $html, '', $filename, 'I', 1);
			
			sleep(1);
			//remove temp graph images
			for ($k = 1; $k <= 7; $k++){
				$filename = "f" . session_id() . $currenttime . "_" . $k . ".png";
				
				$savepath = $_SERVER["DOCUMENT_ROOT"] . $cm->folder_for_seo . "chartimg/" . $filename;
				if (file_exists($savepath)){
					$fle->filedelete($savepath);
				}
			}
			
		}		
	}
	
	//databox
	public function display_total_active_listings($param = array()){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		
		//Different parameter		
		$company_id = round($param["company_id"], 0);
		$boatoption = round($param["boatoption"], 0);
		//end
		
		$query_sql = "select count(*) as ttl,";
		$query_form = " from tbl_yacht,";
		$query_where = " where";
		
		if ($loggedin_member_id == 1){
			if ($company_id > 0){
				$query_where .= " company_id = '". $company_id ."' and";						
			}
		}else{						
			$query_where .= " company_id = '". $company_id ."' and";
			if ($boatoption == 1){
				$query_where .= " broker_id = '". $loggedin_member_id ."' and";
			}
		}
				
		$query_where .= " manufacturer_id > 0 and";		
		$query_where .= " status_id = 1 and";
		
		if ($boatoption == 3){
			$query_where .= " ownboat = 0 and";
		}elseif ($boatoption == 2){
			$query_where .= " ownboat = 1 and";
		}
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$total_unit = $db->total_record_count($sql);
		$returntext = '
		<div class="singlestatholder clearfixmain">
			<h4>Active Listings</h4>
			<div class="singlestat" title="'. $total_unit .'">'. $total_unit .'</div>
		</div>
		';
		return $returntext;
	}
	
	public function display_avarage_active_listings_price($param = array()){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		
		//Different parameter		
		$company_id = round($param["company_id"], 0);
		$boatoption = round($param["boatoption"], 0);
		//end
		
		$query_sql = "select avg(price) as ttl,";
		$query_form = " from tbl_yacht,";
		$query_where = " where";
		
		if ($loggedin_member_id == 1){
			if ($company_id > 0){
				$query_where .= " company_id = '". $company_id ."' and";						
			}
		}else{						
			$query_where .= " company_id = '". $company_id ."' and";
			if ($boatoption == 1){
				$query_where .= " broker_id = '". $loggedin_member_id ."' and";
			}
		}
		
		$query_where .= " manufacturer_id > 0 and";
		$query_where .= " status_id = 1 and";
		
		if ($boatoption == 3){
			$query_where .= " ownboat = 0 and";
		}elseif ($boatoption == 2){
			$query_where .= " ownboat = 1 and";
		}
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$avg_price = $db->total_record_count($sql);
		$avg_price = $cm->format_price($avg_price, 0);
		$returntext = '
		<div class="singlestatholder clearfixmain">
			<h4>Avg Listing Value</h4>
			<div class="singlestat" title="$'. $avg_price .'">$'. $avg_price .'</div>
		</div>
		';
		return $returntext;
	}
	
	public function display_total_active_listings_price($param = array()){
		global $db, $cm;
		$loggedin_member_id = $this->loggedin_member_id();
		
		//Different parameter		
		$company_id = round($param["company_id"], 0);
		$boatoption = round($param["boatoption"], 0);
		//end
		
		$query_sql = "select sum(price) as ttl,";
		$query_form = " from tbl_yacht,";
		$query_where = " where";
		
		if ($loggedin_member_id == 1){
			if ($company_id > 0){
				$query_where .= " company_id = '". $company_id ."' and";						
			}
		}else{						
			$query_where .= " company_id = '". $company_id ."' and";
			if ($boatoption == 1){
				$query_where .= " broker_id = '". $loggedin_member_id ."' and";
			}
		}
		
		$query_where .= " manufacturer_id > 0 and";
		$query_where .= " status_id = 1 and";
		
		if ($boatoption == 3){
			$query_where .= " ownboat = 0 and";
		}elseif ($boatoption == 2){
			$query_where .= " ownboat = 1 and";
		}
		
		$query_sql = rtrim($query_sql, ",");
		$query_form = rtrim($query_form, ",");
		$query_where = rtrim($query_where, "and");
		
		$sql = $query_sql . $query_form . $query_where;
		$total_price = $db->total_record_count($sql);
		$total_price = $cm->format_price($total_price, 0);
		$returntext = '
		<div class="singlestatholder clearfixmain">
			<h4>Total Listing Value</h4>
			<div class="singlestat" title="$'. $total_price .'">$'. $total_price .'</div>
		</div>
		';
		return $returntext;
	}
	
	public function display_dashboard_general_databox($param = array()){
		$returntext = '
		<ul>
			<li>'. $this->display_total_active_listings($param) .'</li>
			<li>'. $this->display_avarage_active_listings_price($param) .'</li>
			<li>'. $this->display_total_active_listings_price($param) .'</li>
		</ul>
		';		
		return $returntext;
	}
	
	//END
}
?>
