// JavaScript Document
$(document).ready(function(){
	//left menu open close
	$(".left_menu").click(function(){
		var boxref = $(this).attr("boxref");
		$('#adminoption' + boxref).slideToggle(300);
		
		$(this).toggleClass( "left_menu_open" );
		
		days = 1;
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		
		name = "hdlink";
		value = boxref;
		document.cookie = name+"="+value+expires+"; path=/";
		
		return false;
	});
	
	//check cookie for left menu
	var setck = readCookie("hdlink");
	var closeadmindiv = setck;
	var cca = setck.split(',');
	for(var i=0;i < cca.length;i++) {
		if (cca[i] != ""){
			dvn = "adminoption"+cca[i];
            dvn_lnk = "adminlink"+cca[i];
			$("#" + dvn).show();
			$("#" + dvn_lnk).removeClass('left_menu_open');
		}
	}
	
	//date
	$(".date-field-b").each(function(){		
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');
				
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
			
			butimage = bkfolder + 'images/jump_date.jpg';
				
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: butimage,
				buttonImageOnly: true,
				gotoCurrent: true,
				maxDate: 0,
				dateFormat: "mm/dd/yy",						
			});		
	});
	
	$(".date-field-c").each(function(){		
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
				showOn: 'focus',				
				gotoCurrent: true,
				maxDate: 0,
				dateFormat: "mm/dd/yy",						
			});		
	});
	
	$(".date-field-ca").each(function(){		
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
				showOn: 'focus',				
				gotoCurrent: true,
				dateFormat: "mm/dd/yy",						
			});		
	});
	
	$(".date-field-d").each(function(){		
			var year_range = $(this).attr('rangeyear');
			var default_Date = $(this).attr('defaultdateset');
				
			if(default_Date != ''){
				default_Date = new Date(default_Date);			
			}
			
			butimage = bkfolder + 'images/jump_date.jpg';
				
			// todo: ensure this icon gets moved ...
			$(this).datepicker({			
				defaultDate: default_Date,
				changeMonth: true,
				changeYear: true,
				yearRange: year_range,
				showOn: 'both',
				buttonImage: butimage,
				buttonImageOnly: true,
				gotoCurrent: true,
				dateFormat: "mm/dd/yy",						
			});		
	});
	//end
	
    //state display option for country
    $(".countrycls_state").change(function(){
        sval = $(this).val();
        refextra = $(this).attr('refextra');
        displaystateopt(sval, refextra);
    });
    //end

	//type selection based on category selected
	$(".catupdate").change(function(){
		sval = $(this).val();
		targetcombo = $(this).attr('targetcombo');
		displaytype(sval, targetcombo);
	});
	//end

    //azax search
    $(".azax_suggest").keyup(function(){
        ckpage = $(this).attr('ckpage');
		whadd = $(this).attr('whadd');
        selvalue = $(this).val();
        if (selvalue != ""){
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
            {
                keyterm:selvalue,
                opt:ckpage,
				whadd:whadd,
                az:3
            },
            function(content){
                if (content != ""){
                    $("#suggestsearch" + ckpage).html(content);
                    $("#suggestsearch" + ckpage).removeClass("com_none");
                }else{
                    $("#suggestsearch" + ckpage).addClass("com_none");
                    set_suggest_target_field(0, ckpage);
                }
            });
        }else{
            $("#suggestsearch" + ckpage).html('');
            $("#suggestsearch" + ckpage).addClass("com_none");
        }
    });
    //end

    //set search term
    $("#suggestsearch, #suggestsearch1, #suggestsearch3").on("click", ".set_term", function(){
        getvl = $(this).attr('getvl');
        dataholder = $(this).attr('dataholder');
		whadd = $(this).attr('whadd');
		if (whadd == 1){
			oldvl = $(".azax_suggest").val();
			temp = new Array();
			temp = oldvl.split(",");
			templen = temp.length;
			if (templen > 0){
				repvl = temp[templen - 1];
				oldvl = oldvl.replace(repvl, "");
			}			
			getvl = oldvl + getvl +",";
			
		}
        $(".azax_suggest" + dataholder).val(getvl);
        $(".suggestsearch").html('');
        $(".suggestsearch").addClass("com_none");
        var dataval = $(this).attr('dataval');
        set_suggest_target_field(dataval, dataholder);
    });
    //end

    //suggestion box hide on mour leave
    $("#suggestsearch, #suggestsearch1, #suggestsearch3").mouseleave(function(){
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

    function set_suggest_target_field(dataval, dataholder){
        if ($('.azax_suggest' + dataholder).attr('connectedfield') !== undefined) {
            var targetfield = $('.azax_suggest' + dataholder).attr('connectedfield');
            $("#" + targetfield).val(dataval);
        }
    }	
	
	
	//smart keyord
	$(".portkey_add").click(function(){
		fieldnm = $(this).attr('fieldnm');
		in_value = $("#" + fieldnm).val();
		if (in_value == ""){
			alert ("Please insert value");
			$("#" + fieldnm).focus();
			return;
		}
		$(this).addvalue(in_value, fieldnm);	

	});
	
	//tag
	//Tab
	$("#catholderbox .tab ul").hide();
	$("#catholderbox .tab a:first").addClass("active");	
	$("#catholderbox .tab a.active").parent().find("ul").show();
	
	//$("#catholderbox .tab a").click(function(){
	$("#catholderbox").on("click", ".tab a", function(){	
		$("#catholderbox .tab a").removeClass("active");
		$(this).addClass("active");
		$("#catholderbox .tab ul").hide();
		$(this).parent().find("ul").show();
	});
	
	//Tag add function - Azax work
	$(".addcat").click(function(){
		var h = "#new_tag_name";
		var newcat = $(h).val();
		var newcat1 = newcat.toLowerCase();
		
		if (newcat == ""){
			alert ("Please enter Name");
			$(h).focus();
			return;
		}		
		ckpage = $(this).attr('ckpage');
		
		var ms = $("#ms").val();
		var b_sURL = "onlyadminajax.php";
		
		$.post(b_sURL,
		{
			keyterm:newcat,
			opt:ckpage,
			ms:ms,
			inoption:4,
			az:4,
			dataType: 'json'
		},
		function(data){
			$(h).val('');
			data = $.parseJSON(data);
			content = data.doc;
			$("#catholderbox ul.tab_1 li:first").before(content);
		});
	});
	
	//most and all tag checkbox check-uncheck
	$(".mp_ckbox").click(function(){
		var c_fieldnm = $(this).attr('id');
		var cn_fieldnm = $(this).attr('connectfld');
		var rp_fieldnm = $(this).attr('replacefld');		
		var o_fieldnm = c_fieldnm.replace(cn_fieldnm, rp_fieldnm);		
		if ($(this).prop('checked')){			
			$("#" + o_fieldnm).prop("checked","checked");
		}else{
			$("#" + o_fieldnm).removeAttr("checked");
		}		
	});
	
	
	$.fn.addvalue = function(in_value, fieldnm){
		all_value = $("#key_name_added").val();
		new_value = all_value + in_value + ",";
		$("#key_name_added").val(new_value, fieldnm);
		//alert (new_value);
		//add_html = $(".separate-keywords").html();
		add_html = '';
	    temp = new Array();
	    temp = new_value.split(",");
	    templen = temp.length;
	   
	    for(var k = 0; k < templen; k++){
	    	a = temp[k];
	   	 if (a != ""){
	   		add_html += '<span><a href="javascript:void(0);" class="k_dellink" fieldnm="'+ fieldnm +'" vname="'+ a +'"><img src="images/del.png" alt="" /> '+ a +'</a></span>';
	   	 }
	    }
	    $("#" + fieldnm).val('');
	    $(".separate-keywords").html(add_html);  
	}
	
	$(".separate-keywords").on("click", ".k_dellink", function(){
		fieldnm = $(this).attr('fieldnm');
		del_value = $(this).attr('vname');
		all_value = $("#" + fieldnm +"_added").val();
		new_value = all_value.replace(","+del_value+",", ",");
		$("#" + fieldnm +"_added").val(new_value);
		$(this).parent().addClass('com_none');
	});

	//external link - boat page
	$.fn.addextlink = function(l_title, l_url, l_descriptions){
		var total_external_link = $('#total_external_link').val();
		total_external_link = eval(total_external_link);
		total_external_link = total_external_link + 1;
		
		var added_text = '<tr class="rowind'+ total_external_link +'">';
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Title:</td>';
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_title'+ total_external_link +'" name="ex_link_title'+ total_external_link +'" value="'+ l_title +'" class="inputbox inputbox_size4" /></td>';
			
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>URL:</td>';
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_url'+ total_external_link +'" name="ex_link_url'+ total_external_link +'" value="'+ l_url +'" class="inputbox inputbox_size4" /></td>';
			
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Description:</td>';
			added_text += '<td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="ex_link_description'+ total_external_link +'" name="ex_link_description'+ total_external_link +'" value="'+ l_descriptions +'" class="inputbox inputbox_size4" /></td>';
			
			added_text += '<td width="25" align="left" valign="top" class="tdpadding1"><a class="ex_link_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_external_link +'"><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>';
		added_text += '</tr>';
		
		$("#rowholder").append(added_text);
		$('#total_external_link').val(total_external_link);
	}
	
	$(".addrow").click(function(){	
		$(this).addextlink("", "", "");
	});

	$(".singleblock_box").on("click", ".ex_link_del", function(){
		var del_pointer = $(this).attr('yval');		
		var isdb = $(this).attr('isdb');
		var yid = $(this).attr('yid');
		$("tr").remove('.rowind' + del_pointer);
		
		isdb = eval(isdb);
		if (isdb == 1){
			//record delete from db also using ajax			
			b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
            {
                yid:yid,
                del_pointer:del_pointer,
                az:36
            },
            function(content){
                
            });
		}
	});
	
	$('.openpopup').fancybox({
		toolbar  : false,
		smallBtn : true,
		iframe : {
			preload : false,
			css : {
				width  : "90%",
				"max-width": "560px"
        	}
		}
    });
	
	$(".backbutton").click(function() {
		history.back();
	});
});

function messagedivhide(){
	setTimeout(function() {
		$('.waitdiv').fadeOut('slow');
	}, 3000);
}

function checklogin(){
	var b_sURL = "onlyadminajax.php";
	$.post(b_sURL,
	{
		az:50,
		dataType: 'json'
	},
	function(data){	
		data = parseInt(data);	
		if (data == 1){
			$(".loggedoutdiv").show();
		}
	});
	t = setTimeout("checklogin()", 1000);
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

function opencompanylocatiob(){
	   var targetcombo = "location_id";
	   var company_id = $("#company_id").val();	   
	   var b_sURL = bkfolder + "includes/ajax.php";
	   $.post(b_sURL,
        {
            company_id:company_id,            
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

function openbrokerforlocation(op){
	   var targetcombo = "broker_id";
	   var company_id = $("#company_id").val();
	   var location_id = $("#location_id").val();	   
	   var b_sURL = bkfolder + "includes/ajax.php";
	   $.post(b_sURL,
        {
            company_id:company_id,
			location_id:location_id,
			op:op,            
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

function displaytype(cat_id, targetcombo){
	var b_sURL = bkfolder + "includes/ajax.php";
	$.post(b_sURL,
        {
            cat_id:cat_id,           
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
}

function re_sort_order(){
  var t_found = document.getElementById("t_found").value;
  t_found = eval(t_found);
  
  for (var k = 0; k < t_found; k++){
  
      if(!validate_pnumeric(document.getElementById("sortorder"+k),1,"Please enter valid Sort Order")){
	      return;
	  }  
  }
  
  document.ff.action = "re-sort-order.php";
  document.ff.submit();
}

//------------------------
//----------------//
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return "";
}

function getOffsets (evt) {
  var target = evt.target;
  if (typeof target.offsetLeft == 'undefined') {
    target = target.parentNode;
  }
  var pageCoords = getPageCoords(target);
  var eventCoords = { 
    x: window.pageXOffset + evt.clientX,
    y: window.pageYOffset + evt.clientY
  };
  var offsets = {
    offsetX: eventCoords.x - pageCoords.x,
    offsetY: eventCoords.y - pageCoords.y
  }
  return offsets;
}

function getPageCoords (element) {
  var coords = {x : 0, y : 0};
  while (element) {
    coords.x += element.offsetLeft;
    coords.y += element.offsetTop;
    element = element.offsetParent;
  }
  return coords;
}

function set_tab_class(div_nm,clasnm){
	document.getElementById(div_nm).className = clasnm;
}

function all_subtab_hide(){
	Hide_MQ("cn1");
	Hide_MQ("cn2");
	Hide_MQ("cn3");
	
	set_tab_class("lp1", "buttonlink");
	set_tab_class("lp2", "buttonlink");
	set_tab_class("lp3", "buttonlink");	 
}

function display_subtab(dvid){
	all_subtab_hide();
	set_tab_class("lp"+dvid, "buttonlinkac");
	Show_MQ("cn"+dvid);
}

function enable_disable(id, tbl_field, tbl_name, ch_opt, wh_field, where_to_go){
  var a=confirm("Are you sure you want to change the option?")
  if (a){
	if(!where_to_go){ where_to_go = 1; }
	location.href="enable_disable.php?id="+id+"&tbl_field="+tbl_field+"&tbl_name="+tbl_name+"&ch_opt="+ch_opt+"&wh_field="+wh_field+"&where_to_go="+where_to_go;
  }
}

function delete_image(id, tbl_field, tbl_name, wh_field, foldernm){
 var a=confirm("Are you sure you want to DELETE this record?")
  if (a){
	location.href="delete_image.php?id="+id+"&tbl_field="+tbl_field+"&tbl_name="+tbl_name+"&wh_field="+wh_field+"&foldernm="+foldernm;
  }	
}

function dimg(aa,bb,cc){
  var a=window.open("dimg.php?ap="+aa+"&bb="+bb+"&cc="+cc,"Img","width=550, height=400, scrollbars=yes");     
}

function fdel(aa,bb,cc,dd){
 var a = confirm ("Are you sure you want to delete this file?"); 
 if (a){
   location.href = "fdel.php?id="+aa+"&tbl="+bb+"&fld="+cc+"&foldr="+dd;
 }
}

function getDateAsYmd(strInpDate, strFormat){
	var rtValue = "";
	if(strInpDate == "") return rtValue;

	var objToday = new Date();
	var currFullYear = objToday.getFullYear();

	var strFullYear, strMonth, strDay;
	var tempSortYear, tempFullYear = String(currFullYear);
	tempSortYear = tempFullYear.substr(tempFullYear.length - 2);
	currSortYear = parseInt(tempSortYear, 10);
	var century = currFullYear - tempSortYear;

	switch(strFormat){
		case "YYYYMMDD":
			strFullYear = strInpDate.substr(0, 4);
			strMonth = strInpDate.substr(4, 2);
			strDay = strInpDate.substr(6, 2);
			break;
		case "MMDDYY":
			strMonth = strInpDate.substr(0, 2);
			strDay = strInpDate.substr(2, 2);
			var strYear = strInpDate.substr(4, 2);

			var intYear = parseInt(strYear, 10);
			intYear += century;

			strFullYear = String(intYear);
			break;
		case "MM/DD/YY":
			strMonth = strInpDate.substr(0, 2);
			strDay = strInpDate.substr(3, 2);
			var strYear = strInpDate.substr(6, 2);

			var intYear = parseInt(strYear, 10);
			intYear += century;

			strFullYear = String(intYear);
			break;
		case "DD/MM/YY":
			strMonth = strInpDate.substr(3, 2);
			strDay = strInpDate.substr(0, 2);
			var strYear = strInpDate.substr(6, 2);

			var intYear = parseInt(strYear, 10);
			intYear += century;

			strFullYear = String(intYear);
			break;
		case "DD/MM/YYYY":
			strMonth = strInpDate.substr(3, 2);
			strDay = strInpDate.substr(0, 2);
			strFullYear = strInpDate.substr(6, 4);
			break;	
		case "DDMMYY":
		default:
			strMonth = strInpDate.substr(2, 2);
			strDay = strInpDate.substr(0, 2);
			var strYear = strInpDate.substr(4, 2);

			var intYear = parseInt(strYear, 10);
			intYear += century;

			strFullYear = String(intYear);
			break;
	}

	rtValue = strFullYear + strMonth + strDay;
	return rtValue;
}

function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}