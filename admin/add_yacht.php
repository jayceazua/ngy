<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$enable_charter = 0;

$link_name = "Add New Boat";
$ms = round($_GET["id"], 0);
$copyid = round($_GET["copyid"], 0);
$status_id = 1;
$sale_usa = 1;
$captains_cabin = 0;
$country_id = 1;
$category_id = $manufacturer_id = $horsepower_combined = 0;

$lengthm = $loam = $beamm = $draftm = $bridge_clearancem = '';
$sold_day_no = 0;
$sold_day_no_class = ' com_none';

$enable_custom_label = 0;
$custom_label_class = ' com_none';
$type_id = 0;
$price_tag_id = 0;
$no_fuel_tanks = $no_fresh_water_tanks = $no_holding_tanks = 1;
$yw_id = 0;

$charter_id = 1;
$charter_class = ' com_none';
$crop_option = 1;
$rotateimage = 0;

$checkid = 0;
if ($ms > 0 OR $copyid > 0){
	
	if ($ms > 0){
		$checkid = $ms;
	}else{
		$checkid = $copyid;
	}
	
	$sql = "select * from tbl_yacht where id = '". $cm->filtertext($checkid) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
        $row = $result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        //Dimensions & Weight
        $ex_sql = "select * from tbl_yacht_dimensions_weight where yacht_id = '". $cm->filtertext($checkid) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        $lengthm = $yachtclass->feet_to_meter($length) . ' M';
        $loam = $yachtclass->feet_to_meter($loa) . ' M';
        $beamm = $yachtclass->feet_to_meter($beam) . ' M';
        $draftm = $yachtclass->feet_to_meter($draft) . ' M';
        $bridge_clearancem = $yachtclass->feet_to_meter($bridge_clearance) . ' M';
		
		$loa_ft_inchs = $yachtclass->explode_feet_inchs($loa);
		$loa_ft = $loa_ft_inchs["ft"];
		$loa_in = $loa_ft_inchs["inchs"];
		
		$beam_ft_inchs = $yachtclass->explode_feet_inchs($beam);
		$beam_ft = $beam_ft_inchs["ft"];
		$beam_in = $beam_ft_inchs["inchs"];
		
		$draft_ft_inchs = $yachtclass->explode_feet_inchs($draft);
		$draft_ft = $draft_ft_inchs["ft"];
		$draft_in = $draft_ft_inchs["inchs"];
		
		$bridge_clearance_ft_inchs = $yachtclass->explode_feet_inchs($bridge_clearance);
		$bridge_clearance_ft = $bridge_clearance_ft_inchs["ft"];
		$bridge_clearance_in = $bridge_clearance_ft_inchs["inchs"];

        //Engine
        $ex_sql = "select * from tbl_yacht_engine where yacht_id = '". $cm->filtertext($checkid) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        //Tank Capacities
        $ex_sql = "select * from tbl_yacht_tank where yacht_id = '". $cm->filtertext($checkid) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        //Accommodations
        $ex_sql = "select * from tbl_yacht_accommodation where yacht_id = '". $cm->filtertext($checkid) ."'";
        $ex_result = $db->fetch_all_array($ex_sql);
        $row = $ex_result[0];
        foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }

        $type_id = $cm->get_common_field_name('tbl_yacht_type_assign', 'type_id', $checkid, 'yacht_id');
        $horsepower_combined = $engine_no * $horsepower_individual;

        $manufacturer_name = $cm->get_common_field_name('tbl_manufacturer', 'name', $manufacturer_id);
        $engine_make_name = $cm->get_common_field_name('tbl_engine_make', 'name', $engine_make_id);        
		$enable_custom_label = $cm->get_common_field_name('tbl_company', 'enable_custom_label', $company_id);
		
		if ($display_upto == $cm->default_future_date){
			$sold_day_no = 0;
		}else{
			$sold_day_no = $cm->difference_between_dates($sold_date, $display_upto);
		}
		
		$statenm = $state;
		if ($country_id == 1){
			$statenm = $cm->get_common_field_name("tbl_state", "code", $state_id);
		}
		
		$meat_ar = array(
			"m1" => $m1,
			"m2" => $m2,
			"m3" => $m3,
			"manufacturer_name" => $manufacturer_name,
			"model" => $model,
			"year" => $year,
			"length" => $length,
			"overview" => $overview,
			"city" => $city,
			"state" => $statenm,
			"company_id" => $company_id
		);
		$final_meta = $yachtclass->collect_meta_info_boat($meat_ar);
		$m1 = $final_meta->m1;
		$m2 = $final_meta->m2;
		$m3 = $final_meta->m3;
	}
}

if ($ms > 0){		
	if ($found > 0){				
		$link_name = "Modify Existing Yacht";
	}else{
		$ms = 0;
	}
}

if ($country_id == 1){
    $state_s1 = "com_none";
    $state_s2 = "";
}else{
    $state_s1 = "";
    $state_s2 = "com_none";
}


if ($status_id == 3){
    $sold_day_no_class = "";
}

if ($enable_custom_label == 1){
	$custom_label_class = '';
}

if ($charter_id == 2 OR $charter_id == 3){
	$charter_class = '';
}

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#yacht_ff").submit(function(){
	  clearInterval(myVar);
	  $("#autosave").val(0);
	  
      //basic information
      var manufacturer_val = $('#manufacturer_id').val();
      if (manufacturer_val == 0){
          alert ("Please select Manufacturer");
          $('.manufacturer_id').focus();
          return false;
      }

      if(!validate_text(document.ff.model,1,"Please enter Model")){
          return false;
      }

      if(!validate_text(document.ff.year,1,"Please select Year")){
          return false;
      }

      if(!validate_text(document.ff.category_id,1,"Please select Category")){
          return false;
      }

      if(!validate_text(document.ff.condition_id,1,"Please select Condition")){
          return false;
      }

      //price
	  if($("#show_price").is(':checked')){
		  if(!validate_numeric(document.ff.price,0,"Please enter Price")){
			  return false;
		  }
		  
		  if(!validate_text(document.ff.price_tag_id,1,"Please select Message for Price")){
			  return false;
		  }
	  }else{
		  if(!validate_pnumeric(document.ff.price,1,"Please enter Price")){
			  return false;
		  }
	  }
	  
	  //company
	  if(!validate_text(document.ff.company_id,1,"Please select Company")){
          return false;
      }
	  
	  if(!validate_text(document.ff.location_id,1,"Please select Location")){
          return false;
      }
	  
	  if(!validate_text(document.ff.broker_id,1,"Please select Broker")){
          return false;
      }
	  
	  //location
      if(!validate_text(document.ff.city,1,"Please enter City")){
          return false;
      }

      if(!validate_text(document.ff.country_id,1,"Please select Country")){
          return false;
      }

      if($('#country_id').val() == 1){
          if(!validate_text(document.ff.state_id,1,"Please select State")){
              return false;
          }
      }else{
          if(!validate_text(document.ff.state,1,"Please enter State")){
              return false;
          }
      }
	  
	  //charter
	  var charterid = parseInt($('#charter_id').val());
	  if (charterid > 1){
		  if(!validate_pnumeric(document.ff.charter_price,1,"Please enter Charter Price")){
			  return false;
		  }
	  }

      //Dimensions & Weight
      if(!validate_numeric(document.ff.length,0,"Please enter Length")){
          return false;
      }

      if(!validate_numeric(document.ff.loa_ft,0,"Please enter LOA - Ft")){
          return false;
      }
	  
	  if(!validate_numeric(document.ff.loa_in,0,"Please enter LOA - Inchs")){
          return false;
      }

      if(!validate_numeric(document.ff.beam_ft,0,"Please enter Beam - Ft")){
          return false;
      }
	  
	  if(!validate_numeric(document.ff.beam_in,0,"Please enter Beam - Inchs")){
          return false;
      }

      if(!validate_numeric(document.ff.draft_ft,0,"Please enter Draft-Max - Ft")){
          return false;
      }
	  
	  if(!validate_numeric(document.ff.draft_in,0,"Please enter Draft-Max - Inchs")){
          return false;
      }

      if(!validate_numeric(document.ff.bridge_clearance_ft,0,"Please enter Bridge Clearance - Ft")){
          return false;
      }
	  
	  if(!validate_numeric(document.ff.bridge_clearance_in,0,"Please enter Bridge Clearance - Inchs")){
          return false;
      }

      if(!validate_numeric(document.ff.dry_weight,0,"Please enter Dry Weight")){
          return false;
      }

      //Engine
      if(!validate_numeric(document.ff.hours,0,"Please enter Hours")){
          return false;
      }

      if(!validate_numeric(document.ff.horsepower_individual,0,"Please enter Horsepower Individual")){
          return false;
      }

      if(!validate_numeric(document.ff.cruise_speed,0,"Please enter Cruise Speed")){
          return false;
      }

      if(!validate_numeric(document.ff.max_speed,0,"Please enter Max Speed")){
          return false;
      }

      if(!validate_numeric(document.ff.en_range,0,"Please enter Range")){
          return false;
      }

      //Tank Capacities
      if(!validate_numeric(document.ff.fuel_tanks,0,"Please enter Fuel Tanks")){
          return false;
      }

      if(!validate_numeric(document.ff.fresh_water_tanks,0,"Please enter Fresh Water Tanks")){
          return false;
      }

      if(!validate_numeric(document.ff.holding_tanks,0,"Please enter Holding Tanks")){
          return false;
      }
          
      //sold
      var statusid = $("#status_id").val();
      if (statusid == 3){
          if(!validate_text(document.ff.sold_day_no,1,"Please select # of days boat is still shown on site")){
              return false;
          }
      }
      return true;
 });
 
 $("#company_id").change(function(){
	   opencompanylocatiob();
	   
	   var targetcombo = "broker_id";
	   $('#' + targetcombo).empty();
	   $("#" + targetcombo).append('<option value="">Select Broker/Agent</option>');
	   
	   var can_enable_custom_label = $('#company_id option:selected').attr('cb');
	   if (can_enable_custom_label == 1){
		   $(".enablecustomlabel").removeClass("com_none");
	   }else{
		   $(".enablecustomlabel").addClass("com_none");
	   }
 });
 
 $("#location_id").change(function(){
	   openbrokerforlocation(0);
 });
 
 $("#same_as_location").click(function(){	 
	 if($(this).is(':checked')){		 
		 var addressval = $("#location_id option:selected").attr('addressval');
		 var addressval_ar = addressval.split(",:");
		 
		 a_address = addressval_ar[0];
		 a_city = addressval_ar[1];
		 a_state = addressval_ar[2];
		 a_state_id = addressval_ar[3];
		 a_country_id = addressval_ar[4];
		 a_zip = addressval_ar[5];
		 
		 $('#address').val(a_address);
		 $('#city').val(a_city);
		 $('#state').val(a_state);
		 $('#state_id').val(a_state_id);
		 $('#country_id').val(a_country_id);
		 $('#zip').val(a_zip);		 
	 }else{
		 a_country_id = 1;
		 $('#address').val('');
		 $('#city').val('');
		 $('#state').val('');
		 $('#state_id').val('');
		 $('#country_id').val(a_country_id);
		 $('#zip').val('');
	 }
	 
	 displaystateopt(a_country_id, '');
 });

    $(".meterconvert").blur(function(){
		var insplit = $(this).attr('insplit');        		
        var convertval = $(this).attr('convertval');		
		if (insplit == 1){
			var converttarget = $(this).attr('converttarget');
			var ft_val = number_round($('#' + converttarget + '_ft').val());
			var in_val = number_round($('#' + converttarget + '_in').val());
			var cval = ft_val+ (in_val/12);
		}else{
			var converttarget = $(this).attr('id');
			var cval = $(this).val();
		}
		
        var meter_value = cval * convertval;
        meter_value = number_round(meter_value);
        $('.' + converttarget + 'm').html(meter_value + ' M');
    });
	
	$(".ktsconvert").click(function(){
		var whchecked = $(this).attr('whchecked');
		if (whchecked == 0){
			$('.ktsconvert').attr('whchecked', 0);
			$(this).attr('whchecked', 1);
			var cval = $(this).val();
			var convertval = $(this).attr('convertval');
			var cruise_speed = $('#cruise_speed').val();
			var max_speed = $('#max_speed').val();
			if (cval == 1){
				//kts to mph
				cruise_speed = cruise_speed / convertval;
				max_speed = max_speed / convertval;
			}
			
			if (cval == 2){
				//mph to kts
				cruise_speed = cruise_speed * convertval;
				max_speed = max_speed * convertval;
			}
			
			$('#cruise_speed').val(number_round(cruise_speed));
			$('#max_speed').val(number_round(max_speed));
		}	    
    });

    $("#engine_no").change(function(){
        horsepower_individual_calculate();
    });

    $("#horsepower_individual").blur(function(){
        horsepower_individual_calculate();
    });

    $(".delyachtimg").click(function(){
        var c = confirm("Are you sure you want to delete this Record?");
        if (c){
            var yval = $(this).attr('yval');
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    imid:yval,
                    az:1
                },
                function(data){
                    if (data == "y"){
                        $('#item-' + yval).addClass('com_none');
                    }
                });
        }
    });

    $("#status_id").change(function(){
        //solddaynoclass
        var statusid = $(this).val();
        if (statusid == 3){
            $(".solddaynoclass").removeClass("com_none");
        }else{
            $(".solddaynoclass").addClass("com_none");
        }
    });
	
	//charter
	$("#charter_id").change(function(){
        var charterid = $(this).val();
        if (charterid == 1){
            $(".charterclass").addClass("com_none");
        }else{
            $(".charterclass").removeClass("com_none");
        }
    });
	//end
	
	//copy mm data
	$(".singleblock").on("click", ".copymmdata", function(){
		var manufacturer_id = $("#manufacturer_id").val();
		var year = $("#year").val();
				
		manufacturer_id = parseInt(manufacturer_id);
		if (year == ""){
			year = 0;
		}else{
			year = parseInt(year);
		}
		
		if (manufacturer_id == 0){
			alert ("Please select Manufacturer");
			$("#manufacturer_name").focus();
			return false;
		}
		
		if (year == 0){
			alert ("Please select Year");
			$("#year").focus();
			return false;
		}
		
		//overlay
		$(".waitdiv").show();
		$(".waitmessage").html('<p>Please wait....</p>');
		
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{			
			manufacturer_id:manufacturer_id,
			year:year,
			az:50,
		},
		function(content){
			$(".inlinediv").html(content);
			$(".inlinediv_main").show();
			$(".waitdiv").hide();
		});
		
		//close
		$(".inlinediv_main").on("click", ".mmcopyclose", function(){
			$(".inlinediv").html('');
			$(".inlinediv_main").hide();
		});
		
		//paste mm data			
		$(".inlinediv_main").on("click", ".pastemmdata", function(){			
			var model_id = $(this).attr("model_id");
			
			$('#yc_mm').val(0);
			if($("#import_photo" + model_id).is(':checked')){
				$('#yc_mm').val(model_id);
			}
			
			var dataref_main = $(this).attr("dataref");
			dataref_main = $.parseJSON(dataref_main);			
			
			//Basic Information
			dataref = dataref_main["basicinfo"];
			$('#model').val(dataref.model);
			$('#category_id').val(dataref.category_id);
			$('#type_id').val(dataref.type_id);
			$('#hull_material_id').val(dataref.hull_material_id);
			$('#hull_type_id').val(dataref.hull_type_id);
			
			//Dimensions & Weight
			dataref = dataref_main["dimention"];
			$('#length').val(dataref.length);
			$('.lengthm').html(dataref.lengthm);
			
			$('#loa_ft').val(dataref.loa_ft);
			$('#loa_in').val(dataref.loa_in);
			$('.loam').html(dataref.loam);
			
			$('#beam_ft').val(dataref.beam_ft);
			$('#beam_in').val(dataref.beam_in);
			$('.beamm').html(dataref.beamm);
			
			$('#draft_ft').val(dataref.draft_ft);
			$('#draft_in').val(dataref.draft_in);
			$('.draftm').html(dataref.draftm);
			
			$('#bridge_clearance_ft').val(dataref.bridge_clearance_ft);
			$('#bridge_clearance_in').val(dataref.bridge_clearance_in);
			$('.bridge_clearancem').html(dataref.bridge_clearancem);			
			$('#dry_weight').val(dataref.dry_weight);
			
			//Engine
			var engine_select = $("#engine_select" + model_id).val();
			dataref = dataref_main["engine"][engine_select];
			$('#engine_make_id').val(dataref.engine_make_id);
			$('#engine_model').val(dataref.engine_model);
			$('#engine_type_id').val(dataref.engine_type_id);
			
			$('#horsepower_individual').val(dataref.horsepower_individual);
			$('#drive_type_id').val(dataref.drive_type_id);
			$('#fuel_type_id').val(dataref.fuel_type_id);
			
			//Tank
			dataref = dataref_main["tank"];
			$('#fuel_tanks').val(dataref.fuel_tanks);
			$('#no_fuel_tanks').val(dataref.no_fuel_tanks);
			$('#fresh_water_tanks').val(dataref.fresh_water_tanks);
			$('#no_fresh_water_tanks').val(dataref.no_fresh_water_tanks);
			$('#holding_tanks').val(dataref.holding_tanks);
			$('#no_holding_tanks').val(dataref.no_holding_tanks);
			
			//Accomodation
			dataref = dataref_main["accomodation"];
			$('#total_cabins').val(dataref.total_cabins);
			$('#total_berths').val(dataref.total_berths);
			$('#total_sleeps').val(dataref.total_sleeps);
			$('#total_heads').val(dataref.total_heads);
			
			$('#crew_cabins').val(dataref.crew_cabins);
			$('#crew_berths').val(dataref.crew_berths);
			$('#crew_sleeps').val(dataref.crew_sleeps);
			$('#crew_heads').val(dataref.crew_heads);
			
			var captains_cabin = dataref.captains_cabin
			captains_cabin = parseInt(captains_cabin);
			if (captains_cabin == 1){
				$('#captains_cabin1').attr('checked','checked');
			}else{
				$('#captains_cabin2').attr('checked','checked');
			}
			
			//Descriptions
			dataref = dataref_main["descriptions"];
			editor_data_set("overview", dataref.descriptions);
			editor_data_set("descriptions", dataref.features);
			
			//External Div
			dataref = dataref_main["extlink"];
			$.each( dataref, function( key, value ) {
				l_title = value.link_title;
				l_url = value.link_url;
				l_descriptions = value.link_description;
				$(this).addextlink(l_title, l_url, l_descriptions);
			});
			
			//close div
			//$(".inlinediv").html('');
			$(".inlinediv_main").hide();
		});
	});
	
	//add new record - engine details
	$(".addrowenginedetails").click(function(){
		var total_engine_details = $('#total_engine_details').val();		
		total_engine_details = parseInt(total_engine_details);
		total_engine_details = total_engine_details + 1;
		
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:53,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			engine_location_data = data.engine_location_data;
			
			var added_text = '<tr class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<td colspan="4" height="15"><img border="0" src="images/sp.gif" alt="" /></td>';
				added_text += '</tr>';
			
				added_text += '<tr class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Location:</td>';
				added_text += '<td width="30%" align="left"><select name="engine_location_id'+ total_engine_details +'" id="engine_location_id'+ total_engine_details +'" class="combobox_size4 htext"><option value="">Select</option>'+ engine_location_data +'</select></td>';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Year:</td>';
				added_text += '<td width="30%" align="left"><input type="text" id="engine_year'+ total_engine_details +'" name="engine_year'+ total_engine_details +'" class="inputbox inputbox_size4" /></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Hours:</td>';
				added_text += '<td width="30%" align="left"><input type="text" id="engine_hours'+ total_engine_details +'" name="engine_hours'+ total_engine_details +'" class="inputbox inputbox_size4" /></td>';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Serial #:</td>';
				added_text += '<td width="30%" align="left"><input type="text" id="engine_serial'+ total_engine_details +'" name="engine_serial'+ total_engine_details +'" class="inputbox inputbox_size4" /></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Date:</td>';
				added_text += '<td width="30%" align="left"><input type="text" id="overhaul_date'+ total_engine_details +'" name="overhaul_date'+ total_engine_details +'" class="inputbox inputbox_size4" /></td>';
				added_text += '<td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Overhaul Hours:</td>';
				added_text += '<td width="30%" align="left"><input type="text" id="overhaul_hours'+ total_engine_details +'" name="overhaul_hours'+ total_engine_details +'" class="inputbox inputbox_size4" /></td>';
				added_text += '</tr>';
				
				added_text += '<tr class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<td align="left" valign="top" class="tdpadding1" colspan="4"><a class="enginedetails_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_engine_details +'" engine_details_id=""><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>';
				added_text += '</tr>';
				
				$("#enginedetailsholder").append(added_text);
				$('#total_engine_details').val(total_engine_details);				
		});			
	});
	
	//delete - engine details
	$(".whitetd").off("click", ".enginedetails_del").on("click", ".enginedetails_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("tr").remove('.enginedetailsind' + del_pointer);
			
			isdb = eval(isdb);			
			if (isdb == 1){
				//record delete from db also using ajax	
				var engine_details_id = $(this).attr('engine_details_id');
				var b_sURL = bkfolder + "includes/ajax.php";		
				$.post(b_sURL,
				{
					engine_details_id:engine_details_id,
					az:54
				});
			}
		}		
	});

    //auto save	
	var myVar = setInterval(function(){ auto_save_form() }, 300000);
	var autosavecounter = 0;
	function auto_save_form(){
		var ms = parseInt($("#ms").val());
		var status_id = parseInt($("#ms").val());
		
		if (ms == 0){
			$("#status_id").val(4);
		}
		
		for ( instance in CKEDITOR.instances ) {
            CKEDITOR.instances[instance].updateElement();
        }
		
		var frm = $('#yacht_ff');
		$("#autosave").val(1);
		autosavecounter++;
		
		$.ajax({
			type: frm.attr('method'),
			url: frm.attr('action'),
			data: frm.serialize(),
			success: function (data) {
				$("#ms").val(data);				
			}
		});	
		return false;		
	}
});

function horsepower_individual_calculate(){
	var engine_no = $('#engine_no').val();
	var horsepower_individual = $('#horsepower_individual').val();
	var horsepower_combined = engine_no * horsepower_individual;
	horsepower_combined = number_round(horsepower_combined);
	$('.horsepower_combined_v').html(horsepower_combined);
}
</script>

<form method="post" action="yacht_sub.php" id="yacht_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $yachtclass->max_engine; ?>" name="max_engine" id="max_engine" />
    <input type="hidden" value="0" id="autosave" name="autosave" />
    
    <?php
	if ($yw_id > 0){
	?>
    <h3>YachtWorld Boat</h3>
    <?php
	}
	?>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Basic Information</span></div>
        <?php if ($ms == 0){ ?><div class="singleblock_heading_right"><button type="button" class="butta copymmdata"><span class="boatmergeIcon butta-space">Boat Merge</span></button></div><?php } ?>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <?php
                if ($ms > 0){
                ?>
                    <tr>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Listing No:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><strong><?php echo $listing_no; ?></strong></td>
                        <?php if ($yw_id > 0){?>
                        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>YachtWorld ID:</td>
                        <td width="" align="left" valign="top" class="tdpadding1"><strong><?php echo $yw_id; ?></strong></td>
                        <?php }else{?>
                        <td width="" align="left">&nbsp;</td>
                        <td width="" align="left">&nbsp;</td>
                        <?php } ?>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td width="20%" align="left"><span class="fontcolor3">* </span>Manufacturer:</td>
                    <td width="30%" align="left"><input type="text" value="<?php echo $manufacturer_name; ?>" id="manufacturer_name" name="manufacturer_name" connectedfield="manufacturer_id" ckpage="1" class="azax_suggest azax_suggest1 inputbox inputbox_size4" autocomplete="off" /><div id="suggestsearch1" class="suggestsearch com_none"></div>
                        <input type="hidden" value="<?php echo $manufacturer_id; ?>" name="manufacturer_id" id="manufacturer_id" />
                    </td>
                    <td width="20%" align="left"><span class="fontcolor3">* </span>Model:</td>
                    <td width="30%" align="left"><input type="text" id="model" name="model" value="<?php echo $model; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">* </span>Year:</td>
                    <td width="" align="left"><select name="year" id="year" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_year_combo($year);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Category:</td>
                    <td width="" align="left"><select name="category_id" id="category_id" targetcombo="type_id" class="combobox_size4 htext catupdate">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_category_combo($category_id);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">* </span>Condition:</td>
                    <td width="" align="left"><select name="condition_id" id="condition_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_condition_combo($condition_id);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Price [$]:</td>
                    <td width="" align="left"><input type="text" id="price" name="price" value="<?php echo $price; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Vessel Name:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="vessel_name" name="vessel_name" value="<?php echo $vessel_name; ?>" class="inputbox inputbox_size4" /></td>

                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Hull Material:</td>
                    <td width="" align="left"><select name="hull_material_id" id="hull_material_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_hull_material_combo($hull_material_id);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Hull Type:</td>
                    <td width="" align="left"><select name="hull_type_id" id="hull_type_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_hull_type_combo($hull_type_id);
                            ?>
                        </select></td>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Hull Color:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="hull_color" name="hull_color" value="<?php echo $hull_color; ?>" class="inputbox inputbox_size4" /></td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>HIN:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="hull_no" name="hull_no" value="<?php echo $hull_no; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left">&nbsp;&nbsp;Boat Type:</td>
                    <td width="" align="left"><select name="type_id" id="type_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                                echo $yachtclass->get_type_combo_parent($type_id, $category_id);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Designer:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="designer" name="designer" value="<?php echo $designer; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left">&nbsp;</td>
                    <td width="" align="left">&nbsp;</td>
                </tr>
                
                <tr>
                    <td colspan="4" align="left"><span class="subhead">Boat Price Display</span></td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Do Not Show Price?</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="checkbox" id="show_price" name="show_price" value="1" class="checkbox" <?php if ($price_tag_id > 0){?>checked="checked"<?php } ?> /></td>
                    
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Message to Display:</td>
                    <td width="" align="left">
                    <select id="price_tag_id" name="price_tag_id" class="combobox_size4 htext">
                        <option value="">Select</option>
                        <?php echo $yachtclass->get_price_tag_combo($price_tag_id); ?>
                    </select>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="4" align="left"><span class="subhead">Company Information</span></td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Company:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                    <select id="company_id" name="company_id" class="combobox_size4 htext">
                        <option value="">Select Company</option>
                        <?php echo $yachtclass->get_company_combo($company_id); ?>
                    </select>
                    </td>
                    <td width="" align="left"><span class="fontcolor3">* </span>Office Location:</td>
                    <td width="" align="left">
                    <select id="location_id" name="location_id" class="combobox_size4 htext">
                        <option value="" addressval="">Select Location</option>
                        <?php echo $yachtclass->get_company_location_combo($location_id, $company_id); ?>
                    </select>
                    </td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Broker/Agent:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                    <select name="broker_id" id="broker_id" class="combobox_size4 htext">
                        <option value="">Select Broker/Agent</option>
                        <?php
                        echo $yachtclass->get_broker_combo($broker_id, $company_id, $location_id);
                        ?>
                    </select>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    
                </tr>

                <tr>
                    <td colspan="4" align="left"><span class="subhead">Boat Location</span>&nbsp;&nbsp;&nbsp;&nbsp;[&nbsp; Same as Office Location &nbsp;&nbsp; <input type="checkbox" id="same_as_location" name="same_as_location" value="1" class="checkbox" /> &nbsp;]</td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Address:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="address" name="address" value="<?php echo $address; ?>" class="inputbox inputbox_size4" /></td>

                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>City:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="city" name="city" value="<?php echo $city; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Country:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><select id="country_id" name="country_id" refextra="" class="countrycls_state combobox_size4 htext">
                            <option value="">Select</option>
                            <?php $yachtclass->get_country_combo($country_id); ?>
                        </select>
                    </td>

                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>State:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                        <div id="sps2" class="<?php echo $state_s2; ?>">
                            <select id="state_id" name="state_id" class="combobox_size4 htext">
                                <option value="">Select State</option>
                                <?php $yachtclass->get_state_combo($state_id); ?>
                            </select>
                        </div>
                        <div id="sps1" class="<?php echo $state_s1; ?> ">
                            <input type="text" id="state" name="state" class="inputbox inputbox_size4" value="<?php echo $state; ?>" />
                        </div>
                    </td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Zipcode:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="zip" name="zip" value="<?php echo $zip; ?>" class="inputbox inputbox_size4" /></td>

                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Available for sale in<br />&nbsp;&nbsp;U.S. waters:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                        <input type="radio" id="sale_usa1" name="sale_usa" value="1" <?php if ($sale_usa == 1){?> checked="checked"<?php } ?> /> Yes
                        <input type="radio" id="sale_usa2" name="sale_usa" value="0" <?php if ($sale_usa == 0){?> checked="checked"<?php } ?> /> No
                    </td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Flag of Registry:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><select id="flag_country_id" name="flag_country_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php $yachtclass->get_country_combo($flag_country_id); ?>
                        </select>
                    </td>
                    
                    <td width="" align="left">&nbsp;&nbsp;</td>
                    <td width="" align="left">&nbsp;&nbsp;</td>
                </tr>
              
              <?php
			  if ($enable_charter == 1){
			  ?>  
                <tr>
                    <td colspan="4" align="left"><span class="subhead">Charter Designation</span></td>
                </tr>
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Option:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><select id="charter_id" name="charter_id" class="combobox_size4 htext">
                             <?php echo $yachtclass->get_charter_combo($charter_id); ?>
                        </select>
                    </td>
                    
                    <td class="charterclass<?php echo $charter_class; ?>" width="" align="left"><span class="fontcolor3">* </span>Charter Price [$]:</td>
                    <td class="charterclass<?php echo $charter_class; ?>" width="" align="left">
                    <div class="formfield_left"><input type="text" id="charter_price" name="charter_price" value="<?php echo $charter_price; ?>" class="inputbox inputbox_size4" /></div>
                    <div class="formfield_right">
                    <select id="price_per_option_id" name="price_per_option_id" class="combobox_size4 htext">
						 <?php echo $yachtclass->get_price_per_option_combo($price_per_option_id); ?>
                    </select>
                    </div>
                    </td>
                </tr> 
               <?php
			  	}else{
			   ?>
               <input type="hidden" value="1" id="charter_id" name="charter_id" />
               <?php
				}
			   ?>                            
            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Dimensions & Weight</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Length [in Ft.]:</td>
                    <td width="30%" align="left"><input type="text" id="length" name="length" value="<?php echo $length; ?>" class="meterconvert inputbox inputbox_size4_a" insplit="0" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> <span class="lengthm fontbold"><?php echo $lengthm; ?></span></td>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>LOA:</td>
                    <td width="30%" align="left">
                    Ft - <input type="text" id="loa_ft" name="loa_ft" value="<?php echo $loa_ft; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="loa" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    In - <input type="text" id="loa_in" name="loa_in" value="<?php echo $loa_in; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="loa" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    <span class="loam fontbold"><?php echo $loam; ?></span></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Beam:</td>
                    <td width="" align="left">
                    Ft - <input type="text" id="beam_ft" name="beam_ft" value="<?php echo $beam_ft; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="beam" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    In - <input type="text" id="beam_in" name="beam_in" value="<?php echo $beam_in; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="beam" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    <span class="beamm fontbold"><?php echo $beamm; ?></span></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Draft - max:</td>
                    <td width="" align="left">
                    Ft - <input type="text" id="draft_ft" name="draft_ft" value="<?php echo $draft_ft; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="draft" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    In - <input type="text" id="draft_in" name="draft_in" value="<?php echo $draft_in; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="draft" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    <span class="draftm fontbold"><?php echo $draftm; ?></span></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Bridge Clearance:</td>
                    <td width="" align="left">
                    Ft - <input type="text" id="bridge_clearance_ft" name="bridge_clearance_ft" value="<?php echo $bridge_clearance_ft; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="bridge_clearance" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    In - <input type="text" id="bridge_clearance_in" name="bridge_clearance_in" value="<?php echo $bridge_clearance_in; ?>" class="meterconvert inputbox inputbox_size4_d" insplit="1" converttarget="bridge_clearance" convertval="<?php echo $yachtclass->ft_to_meter; ?>" /> 
                    <span class="bridge_clearancem fontbold"><?php echo $bridge_clearancem; ?></span></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Dry Weight [in lbs.]:</td>
                    <td width="" align="left"><input type="text" id="dry_weight" name="dry_weight" value="<?php echo $dry_weight; ?>" class="inputbox inputbox_size4_a" /></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Engine</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Engine Make:</td>
                    <td width="30%" align="left"><select name="engine_make_id" id="engine_make_id" class="combobox_size4 htext">
                        <option value="">Select</option>
                        <?php echo $yachtclass->get_engine_make_combo($engine_make_id, 0, 1); ?>
					</select>
                </td>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Engin Model:</td>
                    <td width="30%" align="left"><input type="text" id="engine_model" name="engine_model" value="<?php echo $engine_model; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Engine(s):</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><select name="engine_no" id="engine_no" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($engine_no, 4);
                            ?>
                        </select></td>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Hours:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="hours" name="hours" value="<?php echo $hours; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Engine Type:</td>
                    <td width="" align="left"><select name="engine_type_id" id="engine_type_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_engine_type_combo($engine_type_id);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Drive Type:</td>
                    <td width="" align="left"><select name="drive_type_id" id="drive_type_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_drive_type_combo($drive_type_id);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Fuel Type:</td>
                    <td width="" align="left"><select name="fuel_type_id" id="fuel_type_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_fuel_type_combo($fuel_type_id);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Horsepower Individual:</td>
                    <td width="" align="left"><input type="text" id="horsepower_individual" name="horsepower_individual" value="<?php echo $horsepower_individual; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Joystick Control:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="checkbox" id="joystick_control" name="joystick_control" value="1" <?php if ($joystick_control == 1){?> checked="checked"<?php } ?> class="checkbox" /></td>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Horsepower Combined:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="horsepower_combined_v fontbold"><?php echo $horsepower_combined; ?></span></td>                    
                </tr>
           </table>  
           
           <?php echo $yachtengineclass->display_engine_details_form($ms); ?>
           
           <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td colspan="4" align="left">
                    <strong>Speed and Distance</strong> Unit:&nbsp;&nbsp;
                    <input convertval="<?php echo $yachtclass->mph_to_kts; ?>" whchecked="1" class="ktsconvert" type="radio" id="speed_unit1" name="speed_unit" value="1" checked="checked" /> MPH
                    <input convertval="<?php echo $yachtclass->mph_to_kts; ?>" whchecked="0" class="ktsconvert" type="radio" id="speed_unit2" name="speed_unit" value="2" /> KTS
                    </td>
                </tr>
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Cruise Speed:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="cruise_speed" name="cruise_speed" value="<?php echo $cruise_speed; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Max Speed:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="max_speed" name="max_speed" value="<?php echo $max_speed; ?>" class="inputbox inputbox_size4" /></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Range [MI]:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="en_range" name="en_range" value="<?php echo $en_range; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left">&nbsp;</td>
                    <td width="" align="left">&nbsp;</td>
                </tr>

            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Tank Capacities</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Fuel Tank Total Gallons</td>
                    <td width="30%" align="left"><input type="text" id="fuel_tanks" name="fuel_tanks" value="<?php echo $fuel_tanks; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>No of Fuel Tanks</td>
                    <td width="30%" align="left"><select name="no_fuel_tanks" id="no_fuel_tanks" class="combobox_size4 htext">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_fuel_tanks);
                        ?>
                    </select></td>
                 </tr>
                 
                 <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Fresh Water Tank Total<br />&nbsp;&nbsp;Gallons:</td>
                    <td width="" align="left"><input type="text" id="fresh_water_tanks" name="fresh_water_tanks" value="<?php echo $fresh_water_tanks; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>No of Fresh Water Tanks</td>
                    <td width="" align="left"><select name="no_fresh_water_tanks" id="no_fresh_water_tanks" class="combobox_size4 htext">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_fresh_water_tanks);
                        ?>
                    </select></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Holding Tank Total Gallons:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="holding_tanks" name="holding_tanks" value="<?php echo $holding_tanks; ?>" class="inputbox inputbox_size4" /></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>No of Holding Tanks</td>
                    <td width="" align="left"><select name="no_holding_tanks" id="no_holding_tanks" class="combobox_size4 htext">
                        <option value="">Select</option>
                        <?php
                        $yachtclass->get_common_number_combo($no_holding_tanks);
                        ?>
                    </select></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Accommodations</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Total Cabins:</td>
                    <td width="30%" align="left"><select name="total_cabins" id="total_cabins" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($total_cabins);
                            ?>
                        </select></td>
                    <td width="20%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Total Berths:</td>
                    <td width="30%" align="left"><select name="total_berths" id="total_berths" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($total_berths);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Total Sleeps:</td>
                    <td width="" align="left"><select name="total_sleeps" id="total_sleeps" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($total_sleeps);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Total Heads:</td>
                    <td width="" align="left"><select name="total_heads" id="total_heads" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($total_heads);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Captains Cabin:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                        <input type="radio" id="captains_cabin1" name="captains_cabin" value="1" <?php if ($captains_cabin == 1){?> checked="checked"<?php } ?> /> Yes
                        <input type="radio" id="captains_cabin2" name="captains_cabin" value="0" <?php if ($captains_cabin == 0){?> checked="checked"<?php } ?> /> No
                    </td>
                    <td width="" align="left">&nbsp;</td>
                    <td width="" align="left">&nbsp;</td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Crew Cabins:</td>
                    <td width="" align="left"><select name="crew_cabins" id="crew_cabins" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($crew_cabins);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Crew Berths:</td>
                    <td width="" align="left"><select name="crew_berths" id="crew_berths" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($crew_berths);
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Crew Sleeps:</td>
                    <td width="" align="left"><select name="crew_sleeps" id="crew_sleeps" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($crew_sleeps);
                            ?>
                        </select></td>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Crew Heads:</td>
                    <td width="" align="left"><select name="crew_heads" id="crew_heads" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_common_number_combo($crew_heads);
                            ?>
                        </select></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Main Description</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="center" class="tdpadding1">
                        <?php
                        $editorstylepath = "";
                        $editorextrastyle = "adminbodyclass text_area";
                        $cm->display_editor("overview", $sBasePath, "100%", 300, $overview, $editorstylepath, $editorextrastyle);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Descriptions / Features</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="center" class="tdpadding1">
                        <?php
                        $editorstylepath = "";
                        $editorextrastyle = "adminbodyclass text_area";
                        $cm->display_editor("descriptions", $sBasePath, "100%", 350, $descriptions, $editorstylepath, $editorextrastyle);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <?php
    if ($enable_charter == 1){
    ?>
    <div class="singleblock charterclass<?php echo $charter_class; ?>">
        <div class="singleblock_heading"><span>Charter Description</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="100%" align="center" class="tdpadding1">
                        <?php
                        $editorstylepath = "";
                        $editorextrastyle = "adminbodyclass text_area";
                        $cm->display_editor("charter_descriptions", $sBasePath, "100%", 300, $charter_descriptions, $editorstylepath, $editorextrastyle);
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
	}
	?>
    
	<div class="singleblock">
        <div class="singleblock_heading"><span>External Links</span></div>
        <div class="singleblock_box">
        	<?php
				echo $yachtclass->yacht_external_link_display_list($ms);			
			?>            
        </div>
    </div>
     
    <div class="singleblock">
        <div class="singleblock_heading"><span>Yacht Image</span></div>
        <div class="singleblock_box">
            <?php
                $im_found = 0;
                echo $yachtclass->yacht_image_display_list_short($ms);
            ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="40%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Image [w: <?php echo $cm->yacht_im_width; ?>px, h: <?php echo $cm->yacht_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                    <td width="" align="left"><input type="file" id="imgpath" name="imgpath[]" class="inputbox" size="65" multiple /> [press and hold CTRL key for mutiple selection]</td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Hard crop image?</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="crop_option" name="crop_option" value="1" <?php if ($crop_option == 1){?> checked="checked"<?php } ?> /> Yes</td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Rotate Image?</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                    <input class="" type="radio" id="rotateimage0" name="rotateimage" value="0" <?php if ($rotateimage == 0){?> checked="checked"<?php } ?> /> None
                    <input class="" type="radio" id="rotateimage1" name="rotateimage" value="90" <?php if ($rotateimage == 90){?> checked="checked"<?php } ?> /> 90 Degree ACW
                    <input class="" type="radio" id="rotateimage2" name="rotateimage" value="270" <?php if ($rotateimage == 270){?> checked="checked"<?php } ?> /> 90 Degree CW
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <div class="singleblock">
        <div class="singleblock_heading"><span>Display Information</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="20%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
                    <td width="30%" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            $yachtclass->get_yachtstatus_combo($status_id);
                            ?>
                        </select></td>
                    <td width="20%" align="left" valign="top" class="tdpadding1"><div class="solddaynoclass<?php echo $sold_day_no_class; ?>"><span class="fontcolor3">* </span># of days boat is still shown<br />&nbsp;on site:</div></td>
                    <td width="30%" align="left" valign="top" class="tdpadding1"><div class="solddaynoclass<?php echo $sold_day_no_class; ?>"><select name="sold_day_no" id="sold_day_no" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            echo $yachtclass->get_sold_boat_days_combo($sold_day_no);
                            ?>
                        </select></div></td>
                </tr>
                
                <tr>
                	<td width="" align="left" valign="top" class="tdpadding1"><div class="enablecustomlabel<?php echo $custom_label_class; ?>"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Custom Label:</div></td>
                    <td width="" align="left" valign="top" class="tdpadding1"><div class="enablecustomlabel<?php echo $custom_label_class; ?>"><select name="custom_label_id" id="custom_label_id" class="combobox_size4 htext">
                            <option value="">Select</option>
                            <?php
                            echo $yachtclass->get_custom_label_combo($custom_label_id);
                            ?>
                        </select></div></td>
                    <td>&nbsp;</td>    
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Meta Information</span></div>
        <div class="singleblock_box">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Page Title:</td>
                    <td width="" align="left"><input type="text" name="m1" id="m1" value="<?php echo $m1;?>" class="inputbox inputbox_size4" /></td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Description:</td>
                    <td width="" align="left" valign="top"><textarea name="m2" id="m2" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m2;?></textarea></td>
                </tr>
                
                <tr>
                    <td width="" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Keywords:</td>
                    <td width="" align="left" valign="top"><textarea name="m3" id="m3" rows="1" cols="1" class="textbox textbox_size4"><?php echo $m3;?></textarea></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="singleblock">
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            <tr>
                <td width="" align="right">
                    <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" value="0" name="yc_mm" id="yc_mm" />
</form>

<!--Copy Content-->
<div class="inlinediv_main">
	<div class="sclose"><a class="mmcopyclose" href="javascript:void(0);" title="Close"><img src="<?php echo $cm->folder_for_seo; ?>images/del.png" /></a></div>
    <div class="inlinediv">
    	
    </div>
</div>
<?php
include("foot.php");
?>