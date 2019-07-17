$(document).ready(function(){
	onloadmethod();	
	$(".main").off("click", ".clearallfilter").on("click", ".clearallfilter", function(){
		remove_session_storage();		
	});	
	
	//date
	if ($(".date-field-a").length > 0) {
		set_date_picker(".date-field-a", 1);
	}
	
	if ($(".date-field-b").length > 0) {
		set_date_picker(".date-field-b", 2);
	}
	
	if ($(".date-field-c").length > 0) {
		set_date_picker(".date-field-c", 3);
	}
	
	//header func
	$(".header-search-button-link").click(function(){
		$(".header-search-show-hide").toggleClass("active");
		$(this).toggleClass("active");
		$(".menu-cont").toggleClass("com_none");
		$("body").toggleClass("noscroll");
	});
	
	$(".header-login-button-link").click(function(){
		$(this).toggleClass("active");
	});

	//scroll to
	if ($(".fcscrollto").length > 0){

		$(".fcscrollto").on('click', function () {
		  var jQueryel = jQuery(this)
			, id = jQueryel.attr('href');

		  jQuery('html, body').animate({
			scrollTop: jQuery(id).offset().top
		  }, 500);

		  return false;
		});
	}
	 
	 // for number counter
	 $('.numbercounter').counterUp({
		delay: 10,
		time: 1000
	 });

    //tooltip
    $(".main").on("mouseover", ".tooltip", function(){
        $( this ).tooltip();
    });	
	
	//dropdown
	$(function() {
        $( "#pr-range" ).slider({
			range: true,
            step: 5000,
			min: eval($("#pr-range").attr('minval')),
			max: eval($("#pr-range").attr('maxval')),
			values: [ $("#pr-range").attr('minvaldf'), $("#pr-range").attr('maxvaldf') ],			
			slide: function( event, ui ) {
				$( "#pr-min" ).val( "$" + digits(ui.values[ 0 ]) );
				$( "#pr-max" ).val( "$" + digits(ui.values[ 1 ]) );
			}
		}).draggable();

		$( "#pr-min" ).val( "$" + digits($( "#pr-range" ).slider( "values", 0 )) );
		$( "#pr-max" ).val( "$" + digits($( "#pr-range" ).slider( "values", 1 )) );
		
		$( "#ft-range" ).slider({
			range: true,
            min: eval($("#ft-range").attr('minval')),
            max: eval($("#ft-range").attr('maxval')),
            values: [ eval($("#ft-range").attr('minvaldf')), eval($("#ft-range").attr('maxvaldf')) ],
			slide: function( event, ui ) {
				$( "#ft-min" ).val( ui.values[ 0 ] + " ft" );
				$( "#ft-max" ).val( ui.values[ 1 ] + " ft" );
			}
		}).draggable();
		$( "#ft-min" ).val( $( "#ft-range" ).slider( "values", 0 ) + " ft" );
		$( "#ft-max" ).val( $( "#ft-range" ).slider( "values", 1 ) + " ft" );
	});	

	//featured boat slider
	if ($(".featured-boat-listings .featuredboat").length > 0){	
		$(".featured-boat-listings .featuredboat").carouFredSel({
            responsive	: true,
			width: '100%',
    		height: 'variable',
			pagination: "#fb_pager",	
	        scroll		: {
                fx			: "crossfade",
                easing		: "swing",
                timeoutDuration: 7000,
                duration	: 1000

            },
            items		: {
                visible		: 1,							
				height: 'variable',
            }
        });
	}
	
	/*Product Banner*/
    if ($(".product-slider").length > 0) {
		$(".product-slider").carouFredSel({
            responsive	: true,
            prev: '.prev',
            next: '.next',
            pagination: "#pager",
            scroll		: {
                fx			: "crossfade",
                easing		: "swing",
                timeoutDuration: 4000,
                duration	: 1000

            },
            items		: {
                visible		: 1,
                height		: "66.48%"
            }
        });
    }
	
	/*Product Gallery*/
	$('.fancybox').fancybox({
		transitionEffect: "fade",
	});
	
	
	/*Product Tab*/
	/*$("ul.product-description-nav li a").click(function(){
		var tabid = $(this).attr("ptabid");
		$("ul.product-description-nav li a").removeClass("active");
		$(this).addClass("active");
		
		$(".description-tab").hide();
		$("#ptab" + tabid).show();
		$(document.body).trigger("sticky_kit:recalc");
	});
	
	$(".description-tab h3.title").click(function(){
		//$(".description-tab .con").hide()
		//$(this).parent().find(".con").show();
		$( this ).toggleClass( "opened" );
		$(this).parent().find(".con").toggle();
	});*/
	
	$("h3.ad-search").click(function(){
		$(".ad-search-con").slideToggle();
	});

    $("body").off("mouseover", ".input, .input2, .select, .newListSelected2, .comments").on("mouseover", ".input, .input2, .select, .newListSelected2, .comments", function(){
        if ( $( this ).hasClass( "requiredfield" ) ) {
            $(this).removeClass('requiredfield');
        }
    });

    //state display option for country
    $(".countrycls_state").change(function(){
        sval = $(this).val();
        refextra = $(this).attr('refextra');
        displaystateopt(sval, refextra);
    });
    //end

    //register form
    $(".typeid").click(function(){
        var selected_type_id = $(this).val();
        $('.b_admin').addClass('com_none');
		$('.b_consumer').addClass('com_none');
        if (selected_type_id == 2){
            $('.b_admin').removeClass('com_none');
        }
		
		if (selected_type_id == 6){
            $('.b_consumer').removeClass('com_none');
        }
    });
    $("#user_ff").submit(function(){
        var all_ok = "y";
        var tplength = $(':radio[name=type_id]').length;

        if (!username_validation_noalert("d_username", 1)){ all_ok = "n"; }
        if (!password_validation_noalert("d_password", 1)){ all_ok = "n"; }
		if (!field_validation_border("d_email", 2, 1)){ all_ok = "n"; }

        if (tplength > 0){
            var selected_type_id = $('input[name=type_id]:radio:checked').val();
            if (selected_type_id == 2){
                if (!field_validation_border("cname", 1, 1)){ all_ok = "n"; }                
            }
        }
		
		if ($("#company_id").length > 0) {		
			if (!field_validation_border("company_id", 3, 1)){
				all_ok = "n";
				//setfocus = set_field_focus(setfocus, 'company_id_heading');
			}
		}
		
		var sel_type_id = 0;
		if ($("#type_id").length > 0) {	
			sel_type_id = $("#type_id").val();	
			if (!field_validation_border("type_id", 3, 1)){
				all_ok = "n";
				//setfocus = set_field_focus(setfocus, 'company_id_heading');
			}			
		}
		
		if ($("#location_id").length > 0) {	
			if ((sel_type_id == 3) || (sel_type_id == 4) || (sel_type_id == 5)){
				if (!field_validation_border("location_id", 3, 1)){
					all_ok = "n";
					//setfocus = set_field_focus(setfocus, 'location_id_heading');
				}
			}			
		}

        if (!field_validation_border("fname", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("lname", 1, 1)){ all_ok = "n"; }
		//if (!field_validation_border("phone", 1, 1)){ all_ok = "n"; }
		
		if ($("#user_imgpath").length > 0) {
            var validval = $("#user_imgpath").attr("validval");
            if (!image_validation($("#user_imgpath").val(), 'n', validval, 2)){
                $("#user_imgpath").addClass("requiredfield");
                all_ok = "n";
            }
        }

        if (all_ok == "n"){            
            return false;
        }
        return true;
    });
    //end
	
	//display option based on type selection
	$(".typesel").change(function(){
		var sel_type_id = $("#type_id").val();
		$('.b_location').addClass('com_none');
		if (sel_type_id == 3){
			$('.b_location').removeClass('com_none');
		}
		
		if (sel_type_id == 4){
			$('.b_location').removeClass('com_none');
		}
		
		if (sel_type_id == 5){
			$('.b_location').removeClass('com_none');
		}		
	});
	//end

    //check data exists
    $(".checkvaliddata").blur(function(){
        var fieldopt = $(this).attr('fieldopt');
        var selvalue = $(this).val();
        var oselvalue = $(this).attr('currentval');
        var targerholder  = "checkvaliddatares" + fieldopt;
        var b_sURL = bkfolder + "includes/ajax.php";

        if (fieldopt == 1){
            if (!username_validation_noalert("d_username", 1)){ return false; }
        }

        if (fieldopt == 2){
            if (!field_validation_border("d_email", 2, 1)){ return false; }
        }

        $.post(b_sURL,
            {
                fieldopt:fieldopt,
                selvalue:selvalue,
                oselvalue:oselvalue,
                az:16,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                ajclass = data[0].ajclass;
                doc = data[0].doc;

                $("#" + targerholder).removeClass('correctIcon');
                $("#" + targerholder).removeClass('incorrectIcon');
                $("#" + targerholder).addClass(ajclass);
                $("#" + targerholder).attr('title', doc);
            });
    });
    //end

    //login form
    $("#login_ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("t1", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("t2", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });


    //forgot password form
    $(".fpassword").click(function(){
        if (!field_validation_border("t1", 1, 1)){
            all_ok = "n";
            return  false;
        }

        var eid = $('#t1').val();
        b_sURL = bkfolder + "includes/ajax.php";
        $('div.forgotpassword_info').html('<img src="'+ bkfolder +'images/loader.gif">');
        $.post(b_sURL,
        {
            eid:eid,
            az:5
        },
        function(data){
            data = $.parseJSON(data);
            content = data[0].doc;
            if (content != ""){
                $('div.forgotpassword_info').html(content);
            }
        });

        return true;
    });

    //reset password form
    $("#reset_ff").submit(function(){
        if (!password_validation_noalert("d_password", 1, 1)){
            return false;
        }
        return true;
    });

    //compnay image
    $(".deleteimage").click(function(){
        var c = confirm("Are you sure you want to delete this Image?");
        if (c){
            var targets = $(this).attr('targets');
            $('div.idisplay' + targets).html('<img src="'+ bkfolder +'images/loader.gif">');
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
            {
                targets:targets,
                az:6
            },
            function(data){
                data = $.parseJSON(data);
                content = data[0].retval;
                if (content == 'y'){
                    $('div.idisplay' + targets).addClass('com_none');
                    $('div.iupload' + targets).removeClass('com_none');
                }
            });
        }
    });

    //contact broker
    $("#contact_roker_ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("fullname", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("email", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("subject", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });
	
	$("#contact_my_broker_ff").submit(function(){
        var all_ok = "y";        
        if (!field_validation_border("subject", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });
	
	//contact form
	$("#contact_ff").submit(function(event){
        var all_ok = "y";
		var shortversion = $("#shortversion").val();
		shortversion = parseInt(shortversion);
       
        if (!field_validation_border("con_name", 1, 1)){ all_ok = "n"; }
		if (!field_validation_border("con_phone", 1, 1)){ all_ok = "n"; }
		if (!field_validation_border("con_email", 2, 1)){ all_ok = "n"; }
		//if (!field_validation_border("con_contact_subject", 1, 1)){ all_ok = "n"; }
		if (!field_validation_border("con_message", 1, 1)){ all_ok = "n"; }		

        if (all_ok == "n"){            
            return false;
        }
		
		if (shortversion > 0){
			//Ajax submit
			var form = $(this);
			$.ajax({
			  type: form.attr('method'),
			  url: form.attr('action'),
			  data: form.serialize()
			}).done(function() {
			  $(".fomrsubmit-result").addClass("success");
			  $(".fomrsubmit-result").html("Email sent successfully");
			  $(".fomrsubmit-result").removeClass("com_none");
			  
			  $("#con_name").val('');
			  $("#con_phone").val('');
			  $("#con_email").val('');
			  $("#con_contact_subject").val('');
			  $("#con_message").val('');
			  $("#overlay").hide();
			  
			  $("#formsubmitcontent").html("Your Enquiry has been sent.<br>We will get back to you asap.");
			  $("#formsubmitoverlay").show();
			  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
			}).fail(function() {
			  $(".fomrsubmit-result").addClass("error");
			  $(".fomrsubmit-result").html("ERROR! Please try again");
			  $(".fomrsubmit-result").removeClass("com_none");
			  grecaptcha.reset(jQuery(form).find("#data-widget-id").attr("data-widget-id"));
			});
			
			event.preventDefault();
		}else{
        	return true;
		}
        
    });
    //end
	
	//join our mailing list		
	$("#joinourmailing-ff").submit(function(event){
         var all_ok = "y";
		 var shortversion = $("#shortversion").val();
		 shortversion = parseInt(shortversion);
       
        if (!field_validation_border("jname", 1, 1)){ all_ok = "n"; }
		if (!field_validation_border("jemail", 2, 1)){ all_ok = "n"; }	

        if (all_ok == "n"){            
            return false;
        }
        
		if (shortversion == 1){
			//Ajax submit
			var form = $(this);
			$.ajax({
			  type: form.attr('method'),
			  url: form.attr('action'),
			  data: form.serialize()
			}).done(function() {
			  $(".fomrsubmit-result").addClass("success");
			  $(".fomrsubmit-result").html("Email sent successfully");
			  $(".fomrsubmit-result").removeClass("com_none");
			  
			  $("#jemail").val('');
			}).fail(function() {
			  $(".fomrsubmit-result").addClass("error");
			  $(".fomrsubmit-result").html("ERROR! Please try again");
			  $(".fomrsubmit-result").removeClass("com_none");
			});
			
			event.preventDefault();
		}else{
			return true;
		}
    });
	//end
	
	//create your listing
	$("#createyourlisting-ff").submit(function(){
		var all_ok = "y";
		var setfocus = 'n';
		
		if (!field_validation_border("fname", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'fname');
        }
		
		if (!field_validation_border("lname", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'lname');
        }
		
		if (!field_validation_border("city", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'city');
        }
		
		if (!field_validation_border("state", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'state');
        }
		
		if (!field_validation_border("email", 2, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'email');
        }
		
		
		if (!field_validation_border("manufacturer", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'manufacturer');
        }
		
		if (!field_validation_border("boat_size", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'boat_size');
        }
		
		if (!field_validation_border("boat_year", 1, 1)){
            all_ok = "n";
            setfocus = set_field_focus(setfocus, 'boat_year');
        }
		
		if (all_ok == "n"){            
            return false;
        }
        return true;
	});
	//end
	
	//testimonial
	$("#testimonial-ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("name", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }
		
		if ($("#testimonial_imgpath").length > 0) {
			var validval = $("#testimonial_imgpath").attr("validval");
			if (!image_validation($("#testimonial_imgpath").val(), 'n', validval, 2)){
				$("#testimonial_imgpath").addClass("requiredfield");
				all_ok = "n";
			}
		}

        if (all_ok == "n"){
            return false;
        }
        return true;
    });
	//end
	
	//marinaberths
    $("#contactmarinaberth-ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("fullname", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("email", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("subject", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });

    //save search - add - email search    
    $("#savesearch_ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("name", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });

    $("#emailsearch_ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("email", 2, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });

    //remove search
    $(".main").on("click", ".removesearch", function(){
        svid = $(this).attr('svid');
        b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
            {
                svid:svid,
                az:9,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                deleted = data[0].deleted;
                if (deleted == 'y'){
                    totalrecord = data[0].totalrecord;
                    $("#listrow" + svid).hide();
                    $("#svtotal").html(totalrecord);
                }else{
                    alert ("Error!");
                }
            });
    });
    
	//sort field details
	$.fn.getsortfields = function(){
		if ($(".sorttool").length > 0){
			var sortop = $(".sorttool a.active").attr('sortop');
			var orderbyop = 1;
			if ( $( ".sorttool a.active" ).hasClass( "desc" ) ) {
				orderbyop = 2;
			}			
		}else{
			var sortop = 2;
			var orderbyop = 2;
		}
		
		return [sortop, orderbyop];
	}

    //yacht display change
    $(".main").off("click", ".ydchange").on("click", ".ydchange", function(){
        setTimeout( function() {
            $("#listingholdermain ul.product-list li").addClass( 'hidden-listing' );
        }, 30);
        $(".spinnersmall").addClass("spinnersmallimg");
        var dval = $(this).attr('dval');
        $(".ydchange").removeClass('active');
        $(this).addClass('active');	
		
		var compareboat = 0;
		if ($(this).attr('compareboat') !== undefined) {
			compareboat = $(this).attr('compareboat');
			//$('#chosenboat').val(',');
		}
		
		var sortfieldoption = $(this).getsortfields();
		var sortop = sortfieldoption[0];
		var orderbyop = sortfieldoption[1];
		
		var to_check_val = $("#listingholdermain").attr("to_check_val");
		
        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
        {
            dval:dval,
			compareboat:compareboat,
            p:1,
			sortop:sortop,
			orderbyop:orderbyop,
			to_check_val:to_check_val,
            az:4
        },
        function(data){
            data = $.parseJSON(data);
            content = data[0].doc;
            displayoption = data[0].displayoption;

            if (displayoption == 3){
                //map view
                $("#listingholdermain").html(content);
                var mapdataar = data[0].mapdoc;
                listingMap(mapdataar);
            }else{
                if (content != ""){
                    $("#listingholdermain").html(content);
                    $("#listingholdermain ul.product-list li").addClass( 'no-transition' )
                        .addClass( 'hidden-listing' );
                    setTimeout( function() {
                        $("#listingholdermain ul.product-list li").removeClass( 'no-transition' )
                            .removeClass( 'hidden-listing' );
                    }, 30);
                }else{
                    $('#listingholdermain').html('Sorry. Record unavailable.');
                }
            }
            $(".spinnersmall").removeClass("spinnersmallimg");
        });
    });
	
	//yacht sorting
    $(".sortrecord").click(function(){
        setTimeout( function() {
            $("#listingholdermain ul.product-list li").addClass( 'hidden-listing' );
        }, 30);
        $(".spinnersmall").addClass("spinnersmallimg");
		
        var sortop = $(this).attr('sortop');
        $(".sortrecord").removeClass('active');
        $(this).addClass('active');
		
		var sort_desc = 0;
		if ( $( this ).hasClass( "desc" ) ) {
			sort_desc = 1;
		}
		
		$(".sortrecord").removeClass('asc');	
		$(".sortrecord").removeClass('desc');	
		
		if ( sort_desc == 1 ) {
			var orderbyop = 1;
			$(this).removeClass('desc');
			$(this).addClass('asc');
		}else{
			var orderbyop = 2;
			$(this).removeClass('asc');
			$(this).addClass('desc');
		}
		
		var dval = $(".vp a.active").attr('dval');
		var compareboat = 0;
		if ($('.vp a').attr('compareboat') !== undefined) {
			compareboat = $(".vp a").attr('compareboat');
		}
		
		var to_check_val = $("#listingholdermain").attr("to_check_val");
		
        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
        {
            dval:dval,
			compareboat:compareboat,
            p:1,
			sortop:sortop,
			orderbyop:orderbyop,
			to_check_val:to_check_val,
            az:4
        },
        function(data){
            data = $.parseJSON(data);
            content = data[0].doc;
            displayoption = data[0].displayoption;

            if (displayoption == 3){
                //map view
                $("#listingholdermain").html(content);
                var mapdataar = data[0].mapdoc;
                listingMap(mapdataar);
            }else{
                if (content != ""){
                    $("#listingholdermain").html(content);
                    $("#listingholdermain ul.product-list li").addClass( 'no-transition' )
                        .addClass( 'hidden-listing' );
                    setTimeout( function() {
                        $("#listingholdermain ul.product-list li").removeClass( 'no-transition' )
                            .removeClass( 'hidden-listing' );
                    }, 30);
                }else{
                    $('#listingholdermain').html('Sorry. Record unavailable.');
                }
            }
            $(".spinnersmall").removeClass("spinnersmallimg");
			$(document.body).trigger("sticky_kit:recalc");
			
			local_session_storage();
        });
    });
	
	//condition id set from left search box
	$(".conditionidradio").click(function(){
		var conditionid = $('input[name=conditionid_extend]:radio:checked').val();
		$("#conditionid").val(conditionid);
	});

    //yacht search filter
    $.fn.searchfilter = function(){
        $(".spinnersmall").addClass("spinnersmallimg");
		dispay_wait_msg("Please wait!!!!!");
		
        var p = 1;
        var dval = $(".vp a.active").attr('dval');
		
		var compareboat = 0;
		if ($('.vp a').attr('compareboat') !== undefined) {
			compareboat = $(".vp a").attr('compareboat');
		}
		
		var sortfieldoption = $(this).getsortfields();
		var sortop = sortfieldoption[0];
		var orderbyop = sortfieldoption[1];
			
        var mfcname = $("#mfcname").val();
		var modelname = $("#modelname").val();
		
		if ($(".pricefield").length > 0){
			var prmin = $("#prmin").val();
			var prmax = $("#prmax").val();
		}else{
			var pvalues = $( "#pr-range" ).slider( "option", "values" );
			var prmin = pvalues[0];
			var prmax = pvalues[1];
		}
		
		if ($(".lengthfield").length > 0){
			var lnmin = $("#lnmin").val();
			var lnmax = $("#lnmax").val();
		}else{
			var lvalues = $( "#ft-range" ).slider( "option", "values" );
			var lnmin = lvalues[0];
			var lnmax = lvalues[1];
		}

        var yrmin = $("#yrmin").val();
        var yrmax = $("#yrmax").val();
        
		var conditionid = $("#conditionid").val();
        var typeid = $("#typeid").val();
        var categoryid = $("#categoryid").val();
        var enginetypeid = $("#enginetypeid").val();
        var drivetypeid = $("#drivetypeid").val();
        var fueltypeid = $("#fueltypeid").val();
        var stateid = $("#stateid").val();
		var countryid = $("#countryid").val();
		var statename = $("#statename").val();
		
		var tplength = $(':radio[name=allmy]').length;
		if (tplength > 0){
			var allmy = $('input[name=allmy]:radio:checked').val();
		}else{
			var allmy = $("#allmy").val();
		}
		
		var ownedchoose = $(':radio[name=ls_owned]').length;
		if (ownedchoose > 0){
			var owned = $('input[name=ls_owned]:radio:checked').val();
		}else{
			var owned = $("#owned").val();
		}
		
		var brokername = $("#brokername").val();
		var featured = $("#featured").val();
		var feacat = $("#feacat").val();
		var boatstatus = $("#boatstatus").val();
		var tradein = $("#tradein").val();
		var charter = $("#charter").val();
		var uptoday = $("#uptoday").val();
		var filterdisplay = $("#filterdisplay").val();
		
		var brokerslug = '';
		if ($("#brokerslug").length > 0){
			brokerslug = $("#brokerslug").val();
		}
		var to_check_val = $("#listingholdermain").attr("to_check_val");
		
		var dashboard_ex_sold = 0;
		if ($(".dashboardexsold").length > 0){
			if($("#dashboard_ex_sold").is(':checked')){
				dashboard_ex_sold = 1;
			}
		}
		
		/*var sp_typeid = 0;
		if ($("#sp_typeid").length > 0){
			sp_typeid = $("#sp_typeid").val();
		}*/
		
		var sp_typeidchoose = $(':radio[name=ls_sp_typeid]').length;
		if (sp_typeidchoose > 0){
			var sp_typeid = $('input[name=ls_sp_typeid]:radio:checked').val();
		}else{
			var sp_typeid = $("#sp_typeid").val();
		}
				
		var similaryacht_type_filter = 0;
		if ($("#similaryacht_type_filter").length > 0){
			similaryacht_type_filter = $("#similaryacht_type_filter").val();
		}
		
		var mostviewed = 0;
		if ($("#mostviewed").length > 0){
			mostviewed = $("#mostviewed").val();
		}
		
        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
            {
                dval:dval,
				compareboat:compareboat,
                p:1,
                mfcname:mfcname,
				modelname:modelname,
                prmin:prmin,
                prmax:prmax,
                lnmin:lnmin,
                lnmax:lnmax,
                yrmin:yrmin,
                yrmax:yrmax,
                conditionid:conditionid,
                typeid:typeid,
                categoryid:categoryid,
                enginetypeid:enginetypeid,
                drivetypeid:drivetypeid,
                fueltypeid:fueltypeid,
				statename:statename,
                stateid:stateid,
				countryid:countryid,
				allmy:allmy,
				brokername:brokername,
				featured:featured,
				feacat:feacat,				
				tradein:tradein,
				owned:owned,
				boatstatus:boatstatus,
				charter:charter,
				uptoday:uptoday,
				filterdisplay:filterdisplay,
				brokerslug:brokerslug,
				sortop:sortop,
				orderbyop:orderbyop,
				to_check_val:to_check_val,
				dashboard_ex_sold:dashboard_ex_sold,
				sp_typeid:sp_typeid,
				similaryacht_type_filter:similaryacht_type_filter,
				mostviewed:mostviewed,
                qreset:1,
                az:4
            },
            function(data){
	            data = $.parseJSON(data);
                content = data[0].doc;
                displayoption = data[0].displayoption;
                if (displayoption == 3){
                    //map view
                    totalrec = data[0].totalrec;
                    $("#listingholdermain").html(content);
                    var mapdataar = data[0].mapdoc;
                    //alert (mapdataar[0].lat);
                    listingMap(mapdataar);
                }else{
                    if (content != ""){
                        totalrec = data[0].totalrec;
                        $("#listingholdermain").html(content);
                    }else{
                        totalrec = 0;
                        $('#listingholdermain').html('Sorry. Record unavailable.');
                    }
                }
				
				if (filterdisplay == 1){
					var categorylist = data[0].categorylist;
					$(".filterbycategory").html(categorylist);
						
					var conditionlist = data[0].conditionlist;
					$(".filterbycondition").html(conditionlist);
					
					var boattypelist = data[0].boattypelist;
					$(".filterbyboattype").html(boattypelist);
					
					var makelist = data[0].makelist;
					$(".filterbymake").html(makelist);
					
					var modellist = data[0].modellist;
					$(".filterbymodel").html(modellist);
					
					var countrylist = data[0].countrylist;
					$(".filterbycountry").html(countrylist);
					
					var statelist = data[0].statelist;
					$(".filterbystate").html(statelist);
					
					
					//full
					var boattypelistfull = data[0].boattypelistfull;
					$(".boattype_full").html(boattypelistfull);
					
					var makelistfull = data[0].makelistfull;
					$(".make_full").html(makelistfull);
					
					var modellistfull = data[0].modellistfull;
					$(".model_full").html(modellistfull);	
					
					var countrylistfull = data[0].countrylistfull;
					$(".country_full").html(countrylistfull);
					
					var statelistfull = data[0].statelistfull;
					$(".state_full").html(statelistfull);
				}
				
                $(".spinnersmall").removeClass("spinnersmallimg");
                $(".res span.reccounterupdate").html(totalrec);
				hide_wait_msg();
				$(document.body).trigger("sticky_kit:recalc");
				if($(window).width() <= 685){
					//$(".ad-search-con").slideToggle();
				}
				
				local_session_storage();
            });
    }
	$(".boatajaxform").submit(function(event){
		$(this).searchfilter();
		event.preventDefault();
		return false;
	});
    $("#searchb, #searchbk, .allmylisting, .ownedradio, .boatsearchbutton, .dashboardexsold").click(function(){
        $(this).searchfilter();
    });
    $( "#pr-range, #ft-range" ).on( "slidechange", function( event, ui ) {
        var onc = eval($(this).attr('onc'));
        if (onc == 1){
            $(this).searchfilter();
        }
    });
    $("#yrmin, #yrmax, #conditionid, #typeid, #categoryid, #enginetypeid, #drivetypeid, #fueltypeid, #stateid").change(function(){
        $(this).searchfilter();
    });
	$(".serachinput").keyup(function(){
        $(this).searchfilter();
    });
	if ($(".ydchange").length > 0) {
		var dval = $(".vp a.active").attr('dval');
		dval = parseInt(dval);
		if (dval == 3){
			$(this).searchfilter();
		}
	}
	
	$(".main, .fcajaxloadedcontent").off("click", ".filterwork").on("click", ".filterwork", function(){
		$(".all-overlay").hide();
		var tfield = $(this).attr("tfield");
		var tval = $(this).attr("tval");
		$("#" + tfield).val(tval);
        $(this).searchfilter();
    });

    //yacht pagination
    $(".main").on("click", ".moreyacht", function(){
        var p = $(this).attr('p');
        var dval = $(".vp a.active").attr('dval');
		
		var compareboat = 0;
		if ($('.vp a').attr('compareboat') !== undefined) {
			compareboat = $(".vp a").attr('compareboat');
		}

		var sortfieldoption = $(this).getsortfields();
		var sortop = sortfieldoption[0];
		var orderbyop = sortfieldoption[1];
		
		var to_check_val = $("#listingholdermain").attr("to_check_val");

        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
        {
            dval:dval,
			compareboat:compareboat,
            p:p,
			sortop:sortop,
			orderbyop:orderbyop,
			to_check_val:to_check_val,
            az:4,
            dataType: 'json'
        },
        function(data){
            data = $.parseJSON(data);
            page = data[0].pg;
            button_no = data[0].button_no;
            content = data[0].doc;
            $(".t-center a").attr("p",page);
            if (content != ""){
                $("#listingholder").append(content);
                setTimeout( function() {
                    $("#listingholdermain ul.product-list li").removeClass( 'no-transition' ).removeClass( 'hidden-listing' );
                }, 30);
                if (page > 1){
                    if (button_no > 0){
                        $(".t-center a span recno").html(button_no);
                    }else{
                        $(".t-center").addClass('com_none');
                    }
                }
            }
			$(document.body).trigger("sticky_kit:recalc");
			local_session_storage();
        });
    });

    //yacht add fav
    $(".addremovefav").click(function(){
        var yid = $(this).attr('yid');
        if ( $( this ).hasClass( "add-fav" ) ) {
            var favopt = 1;
        }else{
            var favopt = 0;
        }
        $('.addremovefav').addClass('favloading');

        b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
        {
            favopt:favopt,
            yid:yid,
            az:7,
            dataType: 'json'
        },
        function(data){
            data = $.parseJSON(data);
            content = data[0].retval;
            $('.addremovefav').removeClass('favloading');
            if (content == 'a'){
                $('.addremovefav').removeClass('add-fav');
                $('.addremovefav').addClass('remove-fav');
                $('.addremovefav span').html('Your favorite. Remove?');
            }

            if (content == 'd'){
                $('.addremovefav').removeClass('remove-fav');
                $('.addremovefav').addClass('add-fav');
                $('.addremovefav span').html('Add to favorites');
            }
        });
    });

    //yacht add fav from list
    $(".main").off("click", ".yachtfv").on("click", ".yachtfv", function(){
        yid = $(this).attr('yid');
        favopt = $(this).attr('rtsection');
        b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
            {
                favopt:favopt,
                yid:yid,
                az:7,
                dataType: 'json'
            },
            function(data){
                data = $.parseJSON(data);
                content = data[0].retval;
				var favtext = $("#favlist-"+ yid).text();
				var newfavtext = '';
				
                if (content == "a"){
					$("#favlist-"+ yid).attr("rtsection", "0");
					$("#favlist-"+ yid).attr("title", "Your favorite. Remove?");
					$("#favlist-"+ yid).removeClass("addfavboat").addClass("removefavboat");
					newfavtext = "Your Favorite";
                }else{
					$("#favlist-"+ yid).attr("rtsection", "1");
					$("#favlist-"+ yid).attr("title", "Add to favorites");
					$("#favlist-"+ yid).removeClass("removefavboat").addClass("addfavboat");
					newfavtext = "Favorite";
				}
				
				if (favtext != ""){
					$("#favlist-"+ yid).text(newfavtext);
				}
            });
    });

    //yacht del
    $(".main").on("click", ".yachtd", function(){
        var a = confirm("Are you sure you want to remove this listing?");
        if (a){
            yid = $(this).attr('yid');
            favopt = $(this).attr('rtsection');
            fromdet = $(this).attr('fromdet');
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    favopt:favopt,
                    yid:yid,
                    az:8,
                    dataType: 'json'
                },
                function(data){
                    data = $.parseJSON(data);
                    content = data[0].retval;
                    if (content == 'y'){
                        if (fromdet == 2){
                            window.location.href = bkfolder + "";
                        }else{
                            $(this).searchfilter();
                        }
                    }else{
                        optiontext = data[0].optiontext;
                        alert(optiontext);
                    }
                });
        }
    });

    //advance serch
    $("#adv_ff").submit(function(){
        return true;
    });

    //azax search
    $(".azax_suggest").keyup(function(){
        ckpage = $(this).attr('ckpage');

        targetdiv = '';
        if ($(this).attr('targetdiv')){
            targetdiv = $(this).attr('targetdiv');
        }
        targetdiv_main = 'suggestsearch' + targetdiv;

        selvalue = $(this).val();
        if (selvalue != ""){
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    keyterm:selvalue,
                    opt:ckpage,
                    az:3
                },
                function(content){
                    if (content != ""){
                        $("#" + targetdiv_main).html(content);
                        $("#" + targetdiv_main).removeClass("com_none");
                    }else{
                        $("#" + targetdiv_main).addClass("com_none");
                        set_suggest_target_field(0, ckpage);
                    }
                });
        }else{
            $("#" + targetdiv_main).html('');
            $("#" + targetdiv_main).addClass("com_none");
        }
    });
    //end

    //set search term
    $("#suggestsearch, #suggestsearch1, #suggestsearch3, #suggestsearch4").on("click", ".set_term", function(){
        getvl = $(this).attr('getvl');
        dataholder = $(this).attr('dataholder');
        $(".azax_suggest" + dataholder).val(getvl);
        $(".suggestsearch").html('');
        $(".suggestsearch").addClass("com_none");
        var dataval = $(this).attr('dataval');
        set_suggest_target_field(dataval, dataholder);
		
		//boatsearchcol
		if ($(".boatsearchcol").length > 0){
			$(this).searchfilter();
		}
    });

    function set_suggest_target_field(dataval, dataholder){
        if ($('.azax_suggest').attr('connectedfield') !== undefined) {
            var targetfield = $('.azax_suggest' + dataholder).attr('connectedfield');
            $("#" + targetfield).val(dataval);
        }
    }
    //end

    //suggestion box hide on mour leave
    $(".suggestsearch").mouseleave(function(){
        $(".suggestsearch").html('');
        $(".suggestsearch").addClass("com_none");
    });
    //end

    //suggest box close
    $(".suggestsearch").on("click", ".suggestclose", function(){
        $(".suggestsearch").html('');
        $(".suggestsearch").addClass("com_none");
    });
    //end
	
	//auto - azax_auto
	$("body").off("keydown.autocomplete", ".azax_auto").on("keydown.autocomplete", ".azax_auto", function(){
		var counter;
		$(this).autocomplete({
			minLength: 2,
			cache: false,
			source: function(request, response){
				var b_sURL = bkfolder + "includes/ajax.php";
				var ckpage = this.element.attr("ckpage");
				counter = this.element.attr("counter");
				$.post(b_sURL, 
				{
						keyterm:request.term,
						opt:ckpage,
						az:3,
						dataType: "json"
				}, 
				function(data){
					data = $.parseJSON(data);
					response( data );
				});
		    },
			select: function( event, ui ) {
				var idfield = $( "#mid" + counter );
				//var termfield = $( "#keyterm" + counter );
				
				if (idfield.length > 0){
					idfield.val( ui.item.id );
				}
				/*if (termfield.length > 0){
					termfield.val( ui.item.value );
				}*/
				
				$(this).val(ui.item.value);
				
				//boatsearchcol
				if ($(".boatsearchcol").length > 0){
					//$(this).searchfilter();
				}
				
				return false;
			}
		});
	});
	
	//currency change
    $("#currency_id, #currency_id0, #currency_idcharter").change(function(){
        var curtext = $(':selected', this).text();
        var convert_val = $(this).val();
        var setpr = $(this).attr('setpr');
        var tdiv = $(this).attr('tdiv');
        var newprice = eval(setpr) * eval(convert_val);
        newprice = number_round(newprice);
        newprice_display = curtext + newprice;
        $("#pricechange" + tdiv).html(newprice_display);
        //$('#pricechange').currency({ region: 'EUR', thousands: ',' });
    });
	
	//price toggle
	$(".pricetoggle").click(function(){
		var dateref = $(this).attr('dateref');
		$('.pricediv').addClass('com_none');
		$('.' + dateref).removeClass('com_none');
	});

	//common feedback		
	$("#common_feedback_ff").submit(function(){
        var all_ok = "y";
        if (!field_validation_border("fullname", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("email", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("subject", 1, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });
	
	//yacht refer a friend	
	$("#email_friend_ff").submit(function(){
        var all_ok = "y";
		if (!field_validation_border("femail", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("fname", 1, 1)){ all_ok = "n"; }
		if (!field_validation_border("stemail", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });		
	//end	
	
	//yacht email to client		
	$("#email_client_ff").submit(function(){
        var all_ok = "y";		
		if (!field_validation_border("stemail", 2, 1)){ all_ok = "n"; }
        if (!field_validation_border("message", 1, 1)){ all_ok = "n"; }

        if (all_ok == "n"){
            return false;
        }
        return true;
    });		
	//end
	
	//back button
	$(".backbtn").click(function(){
		history.back();
	});

	//yacht typelist based on category
	$(".catupdate").change(function(){
		cat_id = $(this).val();
		targetcombo = $(this).attr('targetcombo');		
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
			{
				cat_id:cat_id,  
				iop:1,         
				az:33,
				dataType: 'json'
			},
			function(data){
				data = $.parseJSON(data);          
				doc = data[0].doc;
				$('#' + targetcombo).empty();			
				$("#" + targetcombo).append('<option value="">Select</option>');
							
				var str_ln = doc.length;
				for (var k = 0; k < str_ln; k++){	
					  $("#" + targetcombo).append('<option addressval="'+ doc[k]["attrval"] +'" value="'+ doc[k]["textval"] +'">'+ doc[k]["text"] +'</option>');
				}            
			});
	});
	//end
	
	//Industry Association - add new record
 	$(".addrowasso").click(function(){
		var total_associations = $('#total_associations').val();
		total_associations = eval(total_associations);
		total_associations = total_associations + 1;
		
		b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:41,
			iop:1,
			dataType: 'json'
		},
		function(data){			
			data = $.parseJSON(data);
			industryassociation_data = data[0].industryassociation_data;
					
			var added_text = '<li class="left assorowind'+ total_associations +'">';
			added_text += '<p>Select Industry Association</p>';
			added_text += '<select name="industry_associations_id'+ total_associations +'" id="industry_associations_id'+ total_associations +'" class="asso-dropdown'+ total_associations +'"><option value="">Select</option>'+ industryassociation_data +'</select>';
			added_text += '</li>';
						
			added_text += '<li class="assorowind'+ total_associations +'">';
			added_text += '<a class="asso_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_associations +'" asso_id=""><img src="'+ bkfolder +'images/del.png" title="Delete Record" alt="Delete Record"></a>';
			added_text += '</li>';
			
			$("#assoholder").append(added_text);
		    $('#total_associations').val(total_associations);
		});		
	});
	
	//Industry Association - delete
	$(".singleblock_box").on("click", ".asso_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("tr, li").remove('.assorowind' + del_pointer);
			
			isdb = eval(isdb);
			if (isdb == 1){
				//record delete from db also using ajax	
				var asso_id = $(this).attr('asso_id');
				var connect_id = $(this).attr('yid');
				var section = $(this).attr('section');		
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					asso_id:asso_id,
					connect_id:connect_id,
					section:section,
					iop:1,
					az:42
				});
			}
		}		
	});
	
	//Industry Association - sortable
	$( "#assosortable" ).sortable({
		update: function (event, ui) {
			var connect_id = $("#assosortable").attr('yid');
			var section = $("#assosortable").attr('section');
			var sortdata = $(this).sortable('serialize');
			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				connect_id:connect_id,
				section:section,
				data:sortdata,
				iop:1,
				az:43
			});			
		}
	});

	//Certification - add new record
 	$(".addrowcert").click(function(){
		var total_certification = $('#total_certification').val();		
		total_certification = eval(total_certification);
		total_certification = total_certification + 1;
		
		var valimg = $(this).attr('valimg');
		var alltype = $(this).attr('alltype');
		
		b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:44,
			iop:1,
			dataType: 'json'
		},
		function(data){			
			data = $.parseJSON(data);
			certification_data = data[0].certification_data;				
			var added_text = '<li class="left certrowind'+ total_certification +'">';
				added_text += '<p>Select Certificate</p>';
				added_text += '<select name="certification_id'+ total_certification +'" id="certification_id'+ total_certification +'" class="cert-dropdown'+ total_certification +'"><option value="">Select</option>'+ certification_data +'</select>';
				added_text += '</li>';
				
				added_text += '<li class=" certrowind'+ total_certification +'">';
				added_text += '<strong>OR</strong>';
				added_text += '</li>';
				
				added_text += '<li class="left certrowind'+ total_certification +'">';
				added_text += '<p>Certificate Name</p>';
				added_text += '<input type="text" id="certification_name'+ total_certification +'" name="certification_name'+ total_certification +'" value="" class="input" />';
				added_text += '</li>';
				
				added_text += '<li class="right certrowind'+ total_certification +'">';
				added_text += '<p>Link URL</p>';
				added_text += '<input type="text" id="certification_link_url'+ total_certification +'" name="certification_link_url'+ total_certification +'" value="" class="input" />';
				added_text += '</li>';
				
				added_text += '<li class="certrowind'+ total_certification +'">';
				added_text += '<p>Logo Image ['+ valimg +']</p>';
				added_text += '<input type="file" id="logo_image'+ total_certification +'" name="logo_image'+ total_certification +'" value="" class="input" />';
				added_text += '<p>['+ alltype +']</p>';
				added_text += '</li>';
						
				added_text += '<li class="certrowind'+ total_certification +'">';
				added_text += '<a class="cert_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_certification +'" cert_id=""><img src="'+ bkfolder +'images/del.png" title="Delete Record" alt="Delete Record"></a>';
				added_text += '</li>';	
			
			$("#certholder").append(added_text);
		    $('#total_certification').val(total_certification);
		});		
	});
	
	//certification - delete
	$(".singleblock_box").on("click", ".cert_del", function(){
		var delconfirm = confirm("Are you sure you want to delete this record?");
		if (delconfirm){
			var del_pointer = $(this).attr('yval');				
			var isdb = $(this).attr('isdb');			
			$("tr, li").remove('.certrowind' + del_pointer);
			
			isdb = eval(isdb);
			if (isdb == 1){
				//record delete from db also using ajax	
				var cert_id = $(this).attr('cert_id');
				var connect_id = $(this).attr('yid');
				var section = $(this).attr('section');		
				b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					cert_id:cert_id,
					connect_id:connect_id,
					section:section,
					iop:1,
					az:45
				});
			}
		}		
	});
	
	//certification - sortable
	$( "#certsortable" ).sortable({
		update: function (event, ui) {
			var connect_id = $("#certsortable").attr('yid');
			var section = $("#certsortable").attr('section');
			var sortdata = $(this).sortable('serialize');
			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				connect_id:connect_id,
				section:section,
				data:sortdata,
				iop:1,
				az:46
			});			
		}
	});
	
	//print
	jQuery('.printbutton').click(function () {
		window.print();
	});
	
	//compare boat
	$(".main").on("click", ".compareboatcheckbox", function(){
		var totalselect = 0;
		var maxselect = $(".compare-boat-holder").attr("maxboat");
		maxselect = parseInt(maxselect);
		$('.compareboatcheckbox').each(function(){
            if ($(this).prop('checked')){
                totalselect++;
            }
        });		
		
		if (maxselect >= totalselect ){
			var chosenboat = $('#chosenboat').val();
			if ($(this).is(':checked')){				
				chosenboat = chosenboat + $(this).val() + ',' ;
			}else{
				var replaceval = ',' + $(this).val() + ',';
				chosenboat = chosenboat.replace(replaceval, ",");
			}
			$('#chosenboat').val(chosenboat);
		}else{
			$(this).prop('checked', false);
			errormessagepop("You can choose maximum "+ maxselect +" boats at a time");
		}
	});
	
	$(".main").on("click", ".compareboatbutton", function(){
		var totalselect = 0;
		$('.compareboatcheckbox').each(function(){
            if ($(this).prop('checked')){
                totalselect++;
            }
        });
		
		if (totalselect < 2){
			errormessagepop("Please choose minimum 2 boats");
			return;
		}
		
		var chosenboat = $('#chosenboat').val();
		chosenboat = chosenboat.substring(1, chosenboat.length-1);
		
		var pg = $(this).attr('pg');	
		pg = pg + chosenboat;	
		window.open(pg);
	});
	
	$(".main").on("click", ".comparcolremove", function(){
		var replaceval = ',' + $(this).attr('lno') + ',';		
		var chosenboat = ',';		
		$('.imgboatrow').each(function(){
            chosenboat = chosenboat + $(this).attr('lno') + ',';
        });
		
		chosenboat = chosenboat.replace(replaceval, ",");
		chosenboat = chosenboat.substring(1, chosenboat.length-1);
		
		var pg = $('.compare-boat-data').attr('pg');	
		pg = pg + chosenboat;
		location.href = pg;
	});
	
	//calculator
	$('.cal1call').click(function (){
		var rate = parseFloat($('#interestrate1').val());
		var monthly = parseFloat($('#monthlypayment').val());
		var down = parseFloat($('#downpayment1').val());
		var termyear1 = parseFloat($('#termyear1').val());		
		
		if ( !isNaN(monthly) && !isNaN(down) && !isNaN(rate) ) {
			termyear = termyear1 * 12;			
			rate = rate/1200;
			total = monthly * (Math.pow(1+rate,termyear)-1)/ (rate*Math.pow(1+rate,termyear));
			if ( !isNaN(total) ) {
				finalamount = number_round(total + down);
				$(".c1result").html('$' + finalamount);
			}else{
				$(".c1result").html('ERROR!');
			}
		}else{
			$(".c1result").html('ERROR!');
		}
	});
	
	$('.cal2call').click(function (){
		var rate = parseFloat($('#interestrate2').val());
		var boatprice = parseFloat($('#boatprice').val());
		var down = parseFloat($('#downpayment2').val());
		var termyear2 = parseFloat($('#termyear2').val());		
		
		if ( !isNaN(boatprice) && !isNaN(down) && !isNaN(rate) ) {
			termyear = termyear2 * 12;
			total = (rate/1200) * (boatprice-down) / ( 1 - Math.pow(1+(rate/1200), -termyear) );
			if ( !isNaN(total) ) {
				finalamount = number_round(total);
				$(".c2result").html('$' + finalamount);
			}else{
				$(".c2result").html('ERROR!');
			}
		}else{
			$(".c2result").html('ERROR!');
		}
	});
	
	//CONTENT READ MORE
	$("body").off("click", ".contentexpand a").on("click", ".contentexpand a", function(){
		$(".contentclosed").animate( { "opacity": "show", top:"1"} , 500 );
		$(".contentexpand").hide();
		$(".contentcollapse").show();
	});
	$("body").off("click", ".contentcollapse a").on("click", ".contentcollapse a", function(){
		$(".contentclosed").animate( { "opacity": "hide", top:"1"} , 500 );
		$(".contentexpand").show();
		$(".contentcollapse").hide();
	});
	
	//OVERLAY
	if ($(".all-overlay").length > 0) {

		//Open Overlay
		$(".all-overlay").mousedown(function(e) {	
			var clicked = $(e.target);
			if (clicked.is('.custom-overlay-container') || clicked.parents().is('.custom-overlay-container')) {
				//nothing
			}else{
				//$(".fadein3").removeClass("in");
				$(this).hide();
			}
		});
		
		//Close Overlay
		$("body").off("click", ".custom-overlay-close").on("click", ".custom-overlay-close", function(){
			$(".all-overlay").hide();
		});
		
		//Search form Open
		$("body").off("click", ".openadvsearch").on("click", ".openadvsearch", function(){
			$(".boatsearch-inline").show();
		});
		
		//Filter more
		$("body").off("click", ".filtermore").on("click", ".filtermore", function(){
			$(".fcajaxloadedcontent").html("Please wait!!!");
			$(".custom-overlay-common").show();
						
			var tdiv = $(this).attr("tdiv");	
			var collecthtml = $("." + tdiv).html();
			$(".fcajaxloadedcontent").html(collecthtml);									
		});
		
		//print boat option
		$("body").off("click", ".openprintpop").on("click", ".openprintpop", function(){
			$(".printlist-message").hide();
			$(".printlist-error").hide();
			$(".print-inline").show();
		});	
	}
	
	// Arrow Down                   
	$('p.fcarrow1 a').click(function(event){
		$('html, body').animate({
			scrollTop: $( $.attr(this, 'href') ).offset().top
		}, 500);
		event.preventDefault();
	});

    //for scroll top
    var offset = 300,
	//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
	offset_opacity = 1200,
	//duration of the top scrolling animation (in ms)
	scroll_top_duration = 700,
	//grab the "back to top" link
	$back_to_top = $('.BackToTop');
	
	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('bck-is-visible') : $back_to_top.removeClass('bck-is-visible bck-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('bck-fade-out');
		}
	});
	
	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
			}, scroll_top_duration
		);
	});
	
	//popup
	$(".contactbroker, .loginpop, .joinmaillist, .commonpop, .joinourmailinglistbtn, .contactmarinaberths, .savesearchlink, .emailsearch, .referfriend, .feedbackbutton, .creategraph, .emailclient, .mappopup").fancybox({
		toolbar  : false,
		smallBtn : true,
		iframe : {
			preload : true,
			/*css : {
				width  : "90%",
				"max-width": "560px"
        	}*/
		}
	});
	
	//custom parallax
	var plx_position = $(window).scrollTop();
	$(window).scroll(function() {
		if ($(".parallax-div").length > 0){
			var scroll = $(window).scrollTop();
			if(scroll > plx_position) {        
				$('.parallax-div').addClass('scale1');
				$('div.fcmain').addClass('class1');
			} else {         
				 $('.parallax-div').removeClass('scale1');
				 $('div.fcmain').removeClass('class1');
			}
			plx_position = scroll;
		}
	});
		
});


//function
//error message pop
function errormessagepop(msg){
	$("#fc_msg").html(msg);
	$("#fc_msg").show();
	 setTimeout(function() {
		$("#fc_msg").fadeOut('slow');
	}, 3000);
}

function dispay_wait_msg(msg){
	//$("#fc_msg").html(msg);
	//$("#fc_msg").show();
}
function hide_wait_msg(){
	$("#fc_msg").html("");
	$("#fc_msg").hide();
}

function digits(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function displaystateopt(sval, refextra){
        if (sval == 1){
            $("#sps2" + refextra).removeClass("com_none");
            $("#sps1" + refextra).addClass("com_none");
        }else{
            $("#sps2" + refextra).addClass("com_none");
            $("#sps1" + refextra).removeClass("com_none");
        }
}

function field_validation_border(fieldid, fieldtype, mandatory){
    capt = $('#' + fieldid);
    captss = $('#' + fieldid + '_ss');
    if (fieldtype == 1){
        if(!validate_text_noalert(document.getElementById(fieldid), mandatory, "")){
            capt.addClass("requiredfield");
            return false;
        }else{
            capt.removeClass("requiredfield");
            return true;
        }
    }

    if (fieldtype == 2){
        if(!validate_email_noalert(document.getElementById(fieldid), mandatory, "")){
            capt.addClass("requiredfield");
            return false;
        }else{
            capt.removeClass("requiredfield");
            return true;
        }
    }

    if (fieldtype == 3){
        if(!validate_text_noalert(document.getElementById(fieldid), mandatory, "")){
            captss.addClass("requiredfield");
            return false;
        }else{
            captss.removeClass("requiredfield");
            return true;
        }
    }

    if (fieldtype == 4){
        if(!validate_pnumeric(document.getElementById(fieldid), mandatory, "n")){
            capt.addClass("requiredfield");
            return false;
        }else{
            capt.removeClass("requiredfield");
            return true;
        }
    }

    if (fieldtype == 5){
        if(!validate_numeric(document.getElementById(fieldid), mandatory, "n")){
            capt.addClass("requiredfield");
            return false;
        }else{
            capt.removeClass("requiredfield");
            return true;
        }
    }
}

function datefield_validation_border(fieldname, fieldcaption, whreq){
	 dt = document.getElementById(fieldname);
	 if (whreq == 1){
		 if (!field_validation_border(fieldname, 1, 1)){
			return false;
		 }		 		 
	 }
	 
	 if (dt.value != ""){	 
		 if (isDate(dt.value)==false){			 
			 return false;
		 }
	 }
	 return true;
}

function username_validation_noalert(){
    if (!field_validation_border("d_username", 1, 1)){
        return false;
    }else{
        capt = $('#d_username');
        if (field_validation(capt.val(), 6, '')==false){
            capt.addClass("requiredfield");
            return false;
        }
    }
    return true;
}

function password_validation_noalert(){
    cdcapt = $('#cd_password');
    capt = $('#d_password');
    if (!field_validation_border("d_password", 1, 1)){
        cdcapt.addClass("requiredfield");
        return false;
    }else{
        if (field_validation(capt.val(), 6, '')==false){
            capt.addClass("requiredfield");
            cdcapt.addClass("requiredfield");
            return false;
        }else{
            if(allTrim(capt.val()) != allTrim(cdcapt.val())){
                cdcapt.addClass("requiredfield");
                return false;
            }
        }
    }
    return true;
}

function set_field_focus(setfocus, fieldid){
    if (setfocus == 'n'){
        //$('#' + fieldid).focus();
		var headerheight = $(".header").height() + 30;
		$(window).scrollTop($('#' + fieldid).offset().top - headerheight);
        setfocus = 'y';
    }
    return setfocus;
}

function opencompanylocatiob(){
	   var targetcombo = "location_id";
	   var company_id = $("#company_id").val();	  
	   var b_sURL = bkfolder + "includes/ajax.php";	   
	   $.post(b_sURL,
        {
            company_id:company_id, 
			iop:1,            
            az:22,
            dataType: 'json'
        },
        function(data){
            data = $.parseJSON(data);            
            doc = data[0].doc;
			$('#' + targetcombo).empty();			
			$("#" + targetcombo).append('<option value="">Select Location</option>');
						
			var str_ln = doc.length;
			for (var k = 0; k < str_ln; k++){	
				  $("#" + targetcombo).append('<option addressval="'+ doc[k]["attrval"] +'" value="'+ doc[k]["textval"] +'">'+ doc[k]["text"] +'</option>');
			}            
        });		
}

function openbrokerforlocation(){
	   var targetcombo = "broker_id";
	   var company_id = $("#company_id").val();
	   var location_id = $("#location_id").val();	   
	   var b_sURL = bkfolder + "includes/ajax.php";
	   $.post(b_sURL,
        {
            company_id:company_id,
			location_id:location_id, 
			iop:1,           
            az:23,
            dataType: 'json'
        },
        function(data){
            data = $.parseJSON(data);            
            doc = data[0].doc;
			$('#' + targetcombo).empty();			
			$("#" + targetcombo).append('<option value="">Select Broker/Agent</option>');
						
			var str_ln = doc.length;
			for (var k = 0; k < str_ln; k++){	
				  $("#" + targetcombo).append('<option addressval="'+ doc[k]["attrval"] +'" value="'+ doc[k]["textval"] +'">'+ doc[k]["text"] +'</option>');
			}            
        });		
}

function openuserboat(chosanbrokerid, targetcombo){
	   var company_id = $("#company_id").val();  
	   var b_sURL = bkfolder + "includes/ajax.php";
	   $.post(b_sURL,
        {
            company_id:company_id,
			chosanbrokerid:chosanbrokerid, 
			inoption:4,           
            az:154,
            dataType: 'json'
        },
        function(data){
	        data = $.parseJSON(data);            
            doc = data.doc;
			$('#' + targetcombo).empty();			
			$("#" + targetcombo).append('<option value="">Select Boat</option>');
						
			var str_ln = doc.length;
			for (var k = 0; k < str_ln; k++){	
				  $("#" + targetcombo).append('<option addressval="'+ doc[k]["attrval"] +'" value="'+ doc[k]["textval"] +'">'+ doc[k]["text"] +'</option>');
			}            
        });		
}

function resize_box1(){	
	
	if (jQuery(".eqht").length > 0){
		  equalheight('.eqht', 600);
	}	
}

$(window).load(function(){	
	column_scroll_stick();
});

var cachedWidth = $(window).width();
$(window).resize(function(){
	var newWidth = $(window).width();
	if(newWidth !== cachedWidth){
		onloadmethod();
		column_scroll_stick();
		resize_box1();
		
		/*if($(window).width() > 800){
			if ($(".description-tab").length > 0) {
				var tabid = $("ul.product-description-nav li a.active").attr("ptabid");
				$(".description-tab").hide();
				$(".description-tab .con").show();
				$("#ptab" + tabid).show();
				$(document.body).trigger("sticky_kit:recalc");
			}
		}else{
			if ($(".description-tab").length > 0) {
				$(".description-tab h3.title").removeClass("opened");
				$(".description-tab").show();
				$(".description-tab .con").hide();
			}
		}*/
		
		if ($(".ad-search-con").length > 0) {
			if (newWidth > 725){
				$(".ad-search-con").show();
			}else{
				$(".ad-search-con").hide();
			}
		}
	}
});

//update entered form field into dom
function update_form_field_into_dom(){
	$(".boatlistingmain .left-cell input, .boatlistingmain .left-cell select, .boatlistingmain .left-cell textarea").each(function () {
		var $this = $(this);

		if ($this.is("[type='radio']") || $this.is("[type='checkbox']")) {
			if ($this.prop("checked")) {
				$this.attr("checked", "checked");
			} else {
				$this.removeAttr("checked");
			}
		} else {
			if ($this.is("select")) {
				$this.find(":selected").attr("selected", "selected");
			} else {
				$this.attr("value", $this.val());
			}
		}
	});
}

//local session storage for boat search
function local_session_storage(){
	if ($(".boatlistingmain").length > 0) {
		sessionStorage.boatlistingdatatop = $(".boatlistingmain .boatlistingsearchtop").html();
		if ($(".boatlistingmain .left-cell").length > 0) {
			update_form_field_into_dom();
			sessionStorage.boatlistingdataleft = $(".boatlistingmain .left-cell").html();
		}
		sessionStorage.boatlistingdataright = $(".boatlistingmain #listingholdermain").html();
		sessionStorage.currentpagename = $(location).attr('href');
	}
	
	//remove session storage created from boat details page
	if (sessionStorage["keepsearchsession"]) {
		sessionStorage.removeItem("keepsearchsession");
	}
}

//remove session storage for boat search
function remove_session_storage(){
	if (sessionStorage["boatlistingdatatop"]) {
		sessionStorage.removeItem("boatlistingdatatop");
	}
	if (sessionStorage["boatlistingdataleft"]) {
		sessionStorage.removeItem("boatlistingdataleft");
	}
	if (sessionStorage["boatlistingdataright"]) {
		sessionStorage.removeItem("boatlistingdataright");
	}
}

//col scroll
function column_scroll_stick(){
	//column scroll
	if ($(".scrollcol").length > 0){
		if($(window).width() > 725){
			var extarheight = 0;
			if ($(".fcheader").length > 0) {
				extarheight = $(".fcheader").height() + 40;
			}
			
			var parentdiv = $(".scrollcol").attr("parentdiv");
			$(".scrollcol, .scrollcol2").stick_in_parent({
			  parent: "." + parentdiv,
			  offset_top: extarheight
			});
		}else{
			$(".scrollcol").trigger("sticky_kit:detach");
		}
	}
	
	if ($(".scrollcol2").length > 0){
		if($(window).width() <= 918){
			$(".scrollcol2").trigger("sticky_kit:detach");
		}
	}
}

//equal height box
equalheight = function(container, limitres){
	if(jQuery(window).width() > limitres){
		
		 var currentTallest = 0,
		 currentRowStart = 0,
		 rowDivs = new Array(),
		 $el,
		 topPosition = 0;
		 jQuery(container).each(function() {	
		   $el = jQuery(this);
		   jQuery($el).height('auto')
		   topPostion = $el.position().top;
		
		   if (currentRowStart != topPostion) {
			 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			   rowDivs[currentDiv].height(currentTallest);
			 }
			 rowDivs.length = 0; // empty the array
			 currentRowStart = topPostion;
			 currentTallest = $el.height();
			 rowDivs.push($el);
		   } else {
			 rowDivs.push($el);
			 currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		  }
		   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			 rowDivs[currentDiv].height(currentTallest);
		   }
		 });
	}else{
		jQuery(container).height('auto');
	}
}

function onloadmethod(){	
	
	/*Prodcut details Banner*/
    if ($("header .header-bottom-bg").length > 0) {
        var positiontop = $("header .header-bottom-bg").offset().top;
    }
    var setMenuOffset = function () {
        var nav = $("header .header-bottom-bg");
        if (nav.length === 0) { return; }
        if($(window).width() > 767){
            var currentOffset = document.documentElement.scrollTop || document.body.scrollTop;
            if (currentOffset > positiontop && nav.css('position') != 'fixed') {
                nav.css('top', '0px');
                nav.css('position', 'fixed');
            }else if (nav.css('position') == 'fixed' && currentOffset <= positiontop) {
                nav.css('position', 'static');
            }
        }else{
            nav.css('position', 'static');
        }

    };

    $(window).scroll(function () {
        setMenuOffset();
    });
}

function set_date_picker(fieldclass, pickertype){
	$(fieldclass).each(function(){
		if (pickertype == 1){	
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');	
	
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
	
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: bkfolder + 'images/jump_date.jpg',
				buttonImageOnly: true,
				gotoCurrent: true,
				maxDate: 0,
				dateFormat: "mm/dd/yy",						
			});	
		}
		
		if (pickertype == 2){
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');	
	
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
	
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: bkfolder + 'images/jump_date.jpg',
				buttonImageOnly: true,
				gotoCurrent: true,
				dateFormat: "mm/dd/yy",						
			});
		}
		
		if (pickertype == 3){	
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');	
	
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
	
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: bkfolder + 'images/jump_date.jpg',
				buttonImageOnly: true,
				gotoCurrent: true,
				minDate: 0,
				dateFormat: "mm/dd/yy",						
			});	
		}
	});
}

function getNumber_validate_nan(number, defaultNumber) {
    return isNaN(parseInt(number, 10)) ? defaultNumber : parseInt(number, 10);
	//return parseInt(number) || defaultNumber;
}