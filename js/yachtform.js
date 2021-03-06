$(document).ready(function(){
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
	//end
	
	//charter
	$("#charter_id").change(function(){
        var charterid = $(this).val();
        if (charterid == 1){
            $(".charterclass").addClass("com_none");
			
			//enable hot deals
			$(".hotdealsclass").removeClass("com_none");
        }else{
            $(".charterclass").removeClass("com_none");
			
			//disable hot deals
			$(".hotdealsclass").addClass("com_none");
        }
    });
	//end
	
	/*
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
	*/

	//external link - boat page
	$(".addrow").click(function(){
		var total_external_link = $('#total_external_link').val();
		total_external_link = eval(total_external_link);
		total_external_link = total_external_link + 1;
		
		var added_text = '<li class="left rowind'+ total_external_link +'">';
			added_text += '<p>Title</p>';
			added_text += '<input type="text" id="ex_link_title'+ total_external_link +'" name="ex_link_title'+ total_external_link +'" value="" class="input" />';
			added_text += '</li>';
			
			added_text += '<li class="right rowind'+ total_external_link +'">';
			added_text += '<p>URL</p>';
			added_text += '<input type="text" id="ex_link_url'+ total_external_link +'" name="ex_link_url'+ total_external_link +'" value="" class="input" />';
			added_text += '</li>';
			
			added_text += '<li class="rowind'+ total_external_link +'">';
			added_text += '<p>Description</p>';
			added_text += '<input type="text" id="ex_link_description'+ total_external_link +'" name="ex_link_description'+ total_external_link +'" value="" class="input" />';
			added_text += '</li>';
			
			added_text += '<li class="rowind'+ total_external_link +'">';
			added_text += '<a class="ex_link_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_external_link +'"><img src="'+ bkfolder +'images/del.png" title="Delete Record" alt="Delete Record"></a>';
			added_text += '</li>';
		
		$("#rowholder").append(added_text);
		$('#total_external_link').val(total_external_link);
	});

	$(".singleblock_box").on("click", ".ex_link_del", function(){
		var del_pointer = $(this).attr('yval');		
		var isdb = $(this).attr('isdb');
		var yid = $(this).attr('yid');
		$("li").remove('.rowind' + del_pointer);
		
		isdb = eval(isdb);
		if (isdb == 1){
			//record delete from db also using ajax			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
            {
                yid:yid,
                del_pointer:del_pointer,
				iop:1,
                az:36
            },
            function(content){
                
            });
		}
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
			iop:1,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);
			engine_location_data = data.engine_location_data;
				
			var added_text = '<li class="left enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Location</p>';
				added_text += '<select name="engine_location_id'+ total_engine_details +'" id="engine_location_id'+ total_engine_details +'" class="my-dropdown2"><option value="">Select</option>'+ engine_location_data +'</select>';
				added_text += '</li>';
				added_text += '<li class="right enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Year</p>';
				added_text += '<input type="text" id="engine_year'+ total_engine_details +'" name="engine_year'+ total_engine_details +'" class="input" />';
				added_text += '</li>';
				
				added_text += '<li class="left enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Hours:</p>';
				added_text += '<input type="text" id="engine_hours'+ total_engine_details +'" name="engine_hours'+ total_engine_details +'" class="input" />';
				added_text += '</li>';
				added_text += '<li class="right enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Serial #:</p>';
				added_text += '<input type="text" id="engine_serial'+ total_engine_details +'" name="engine_serial'+ total_engine_details +'" class="input" />';
				added_text += '</li>';
				
				added_text += '<li class="left enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Overhaul Date:</p>';
				added_text += '<input type="text" id="overhaul_date'+ total_engine_details +'" name="overhaul_date'+ total_engine_details +'" class="input" />';
				added_text += '</li>';
				added_text += '<li class="right enginedetailsind'+ total_engine_details +'">';
				added_text += '<p>Overhaul Hours:</p>';
				added_text += '<input type="text" id="overhaul_hours'+ total_engine_details +'" name="overhaul_hours'+ total_engine_details +'" class="input" />';
				added_text += '</li>';
				
				added_text += '<li class="enginedetailsind'+ total_engine_details +'">';
				added_text += '<a class="enginedetails_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_engine_details +'" engine_details_id=""><img src="'+ bkfolder +'images/del.png" title="Delete Record" alt="Delete Record"></a>';
				added_text += '</li>';
				
				$("#enginedetailsholder").append(added_text);
				$('#total_engine_details').val(total_engine_details);
		});			
	});
	
	//delete - engine details
	$(".singleblock_box").off("click", ".enginedetails_del").on("click", ".enginedetails_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("li").remove('.enginedetailsind' + del_pointer);
			
			isdb = eval(isdb);			
			if (isdb == 1){
				//record delete from db also using ajax	
				var engine_details_id = $(this).attr('engine_details_id');
				var b_sURL = bkfolder + "includes/ajax.php";		
				$.post(b_sURL,
				{
					engine_details_id:engine_details_id,
					az:54,
					iop:1
				});
			}
		}		
	});

    //form valid
    $("#yacht_ff").submit(function(){
        var all_ok = "y";
        var setfocus = 'n';

        //basic information
		if (!field_validation_border("manufacturer_name", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'manufacturer_name');
        }

        var manufacturer_val = $('#manufacturer_id').val();
        if (manufacturer_val == 0){
            $('#manufacturer_name').addClass("requiredfield");
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'manufacturer_name');
        }
        
        if (!field_validation_border("model", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'model');
        }
        if (!field_validation_border("year", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'year_heading');
        }
        if (!field_validation_border("category_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'category_id_heading');
        }
        if (!field_validation_border("condition_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'condition_id_heading');
        }
        
		//price
	  if($("#show_price").is(':checked')){
		  if (!field_validation_border("price", 4, 0)){
				all_ok = "n";
				setfocus = set_field_focus(setfocus, 'price');
		  }
		  
		  if (!field_validation_border("price_tag_id", 3, 1)){
              all_ok = "n";
              setfocus = set_field_focus(setfocus, 'price_tag_id_heading');
          }
	  }else{
		  if (!field_validation_border("price", 4, 1)){
				all_ok = "n";
				setfocus = set_field_focus(setfocus, 'price');
		  }
	  }
		
		//company
		if (!field_validation_border("company_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'company_id_heading');
        }
		
		if (!field_validation_border("location_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'location_id_heading');
        }
		
		if (!field_validation_border("broker_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'broker_id_heading');
        }
		
		//location
        if (!field_validation_border("city", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'city');
        }
        if (!field_validation_border("country_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'country_id_heading');
        }
        if($('#country_id').val() == 1){
            if (!field_validation_border("state_id", 3, 1)){
                all_ok = "n";
                setfocus = set_field_focus(setfocus, 'sps2');
            }
        }else{
            if (!field_validation_border("state", 1, 1)){
                all_ok = "n";
                setfocus = set_field_focus(setfocus, 'state');
            }
        }
		
		//charter
		var charterid = parseInt($('#charter_id').val());
		if (charterid > 1){
			if (!field_validation_border("charter_price", 4, 1)){
				all_ok = "n";
				setfocus = set_field_focus(setfocus, 'charter_price');
			}
		}

        //Dimensions & Weight
        if (!field_validation_border("length", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'length');
        }
		
        if (!field_validation_border("loa_ft", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'loa_ft');
        }
		if (!field_validation_border("loa_in", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'loa_in');
        }
		
        if (!field_validation_border("beam_ft", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'beam_ft');
        }
		if (!field_validation_border("beam_in", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'beam_in');
        }
		
        if (!field_validation_border("draft_ft", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'draft_ft');
        }
		if (!field_validation_border("draft_in", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'draft_in');
        }
		
        if (!field_validation_border("bridge_clearance_ft", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'bridge_clearance_ft');
        }
		if (!field_validation_border("bridge_clearance_in", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'bridge_clearance_in');
        }
		
        if (!field_validation_border("dry_weight", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'dry_weight');
        }

        //Engine
        if (!field_validation_border("hours", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'hours');
        }
        if (!field_validation_border("horsepower_individual", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'horsepower_individual');
        }
        if (!field_validation_border("cruise_speed", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'cruise_speed');
        }
        if (!field_validation_border("max_speed", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'max_speed');
        }
        if (!field_validation_border("en_range", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'en_range');
        }

        //Tank Capacities
        if (!field_validation_border("fuel_tanks", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'fuel_tanks');
        }
        if (!field_validation_border("fresh_water_tanks", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'fresh_water_tanks');
        }
        if (!field_validation_border("holding_tanks", 5, 0)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'holding_tanks');
        }

        //status
        if (!field_validation_border("status_id", 3, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'status_id_heading');
        }

        //sold
        var statusid = $("#status_id").val();
        if (statusid == 3){
            if (!field_validation_border("sold_day_no", 3, 1)){
                all_ok = "n";
                setfocus = set_field_focus(setfocus, 'sold_day_no_heading');
            }
        }

        if (all_ok == "n"){
            return false;
        }
        return true;
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
	
	$("#company_id").change(function(){
		   opencompanylocatiob();
		   if ($(".brokercombo").length > 0) {
			   var targetcombo = "broker_id";
			   $('#' + targetcombo).empty();
			   $("#" + targetcombo).append('<option value="">Select Broker/Agent</option>');
		   }
		   
		   var can_enable_custom_label = $('#company_id option:selected').attr('cb');
		   if (can_enable_custom_label == 1){
			   $(".enablecustomlabel").removeClass("com_none");
		   }else{
			   $(".enablecustomlabel").addClass("com_none");
		   }		   
	 });
	 
	 $("#location_id").change(function(){
		 if ($(".brokercombo").length > 0) {
		   openbrokerforlocation();
		 }
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
			 $('#state_id').getSetSSValue(a_state_id);
			 $('#country_id').getSetSSValue(a_country_id);
			 $('#zip').val(a_zip);				 
		 }else{
			 a_country_id = 1;
			 $('#address').val('');
			 $('#city').val('');
			 $('#state').val('');
			 $('#state_id').getSetSSValue('');
			 $('#country_id').getSetSSValue(a_country_id);
			 $('#zip').val('');
		 }
		 
		 displaystateopt(a_country_id, '');
	});


    function horsepower_individual_calculate(){
        var engine_no = $('#engine_no').val();
        var horsepower_individual = $('#horsepower_individual').val();
        var horsepower_combined = engine_no * horsepower_individual;
        horsepower_combined = number_round(horsepower_combined);
        $('.horsepower_combined_v').html(horsepower_combined);
    }
});