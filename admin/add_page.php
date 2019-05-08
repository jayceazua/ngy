<?php
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "y";
include("pageset.php");

$link_name="Add New Page";
$ms = round($_GET["id"], 0);
$parentid = round($_GET["parentid"], 0);

$disp_on = 3;
$status = "y";
$link_required = "y";
$permit_enable = "n";
$cms_style1 = $cms_seofile = $cms_breadcrumb = "visibility: visible;";
$cms_style2 = "display: none; visibility: hidden;";
$cms_style3 = $cms_style3_a = $cms_style3_b = $pl_a = $pl_b = $cms_linkreq = $cms_topmenu_cms = "display: none; visibility: hidden;";

$st_a = "visibility: visible;";
$st_b = "visibility: visible;";
$st_c = "display: none; visibility: hidden;";
$cms_message = '';
$display_enquire_form = 0;
$editortd_w = "99%";
$rightcol_class = ' class="com_none"';
$defaultpage = 0;
$sidebarclass = ' com_none';
$only_menu = 0;
$display_page_heading = 1;

//$make_shortcode_css = ' com_none';
//$shortcodecontent = '';

$shortcode_tab_0 = ' active';
$shortcode_tab_1 = $shortcode_tab_2 = $shortcode_tab_3 = '';
$shortcode_css_0 = '';
$shortcode_css_1 = $shortcode_css_2 = $shortcode_css_3 = ' com_none';
$connected_manufacturer_id = 0;
$connected_group_id = 0;
$connected_type_id = 0;
$custom_inventory_view = '';
$shortcodecontent = '';
$statemain_class =  ' com_none';

if ($ms > 0){
	$sql = "select * from tbl_page where id = '". $cm->filtertext($ms)."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result);
	
	if ($found > 0){
		$row = $result[0];		
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }
		$parentid = $parent_id;
		
		$int_page_sel = $int_page_id . "/!" . $int_page_tp;
		if ($disp_on == 0){ $disp_on = 3; }
		if ($display_enquire_form == 1){ $editortd_w = "73%"; $rightcol_class = ''; }
     		
		if ($page_type == 1 OR $page_type == 4 OR $page_type == 5){
		  $cms_style1 = "visibility: visible;"; 
		  $cms_style2 = "display: none; visibility: hidden;";
		  $cms_style3 = $cms_style3_a = $cms_style3_b = "display: none; visibility: hidden;";
		  $cms_seofile = $cms_breadcrumb = "visibility: visible;"; 
		  if ($disp_on == 1){ $cms_topmenu_cms = "visibility: visible;"; }
		}elseif ($page_type == 2){
		  $cms_style2 = "visibility: visible;"; 
		  $cms_style1 = "display: none; visibility: hidden;";
		  $cms_style3 = $cms_style3_a = $cms_style3_b = $cms_seofile = $cms_breadcrumb = "display: none; visibility: hidden;";
		}else{
		  $cms_style3 = "visibility: visible;"; 
		  $cms_seofile = $cms_breadcrumb = "display: none; visibility: hidden;";
		  
		  if ($link_type == 1){
		   $cms_style3_a = "visibility: visible;";
		   $cms_style3_b = "display: none; visibility: hidden;";
		  }else{
		   $cms_style3_b = "visibility: visible;";
		   $cms_style3_a = "display: none; visibility: hidden;";
		  }
		  
		  $cms_style1 = "display: none; visibility: hidden;";
		  $cms_style2 = "display: none; visibility: hidden;";
		}
		
		if ($page_type == 5){ 
		    $cms_linkreq = "visibility: visible;"; 
		}else{
		    $cms_linkreq = "display: none; visibility: hidden;";
		}
		
		if ($link_required == "n"){ $cms_style1 = $cms_seofile = $cms_breadcrumb = "display: none; visibility: hidden;"; }		
		
		
		//side bar
		if ($templatefile == "twocolumnleft.php" OR $templatefile == "twocolumnright.php"){			
			$sidebarclass = '';
		}
		
		//make short code
		if ($connected_manufacturer_id > 0){			
			$shortcode_tab_0 = '';
			$shortcode_tab_1 = ' active';
			
			$shortcode_css_1 = '';
			$shortcode_css_0 = ' com_none';
			
			$predesign_scode_param = " makeid=" . $connected_manufacturer_id;
			
			if ($connected_group_id > 0){
				$predesign_scode_param .= " modelgroupid=" . $connected_group_id;
			}
						
			$shortcodecontent = '
			<p>Manufacturer Imge &amp; Description: <span class="getshortcode1_1">[fcmakeprofile makeid='. $connected_manufacturer_id .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode1_1" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			
			<p>"Pre-Designed" manufacturer Group display: <span class="getshortcode1_2">[fcpredesignmakegroup makeid='. $connected_manufacturer_id .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode1_2" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			
			<p>"Pre-Designed" manufacturer Model display: <span class="getshortcode1_3">[fcpredesignmake'. $predesign_scode_param .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode1_3" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			
			<p>Locally added boats list: <span class="getshortcode1_4">[fclocalboat makeid='. $connected_manufacturer_id .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode1_4" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			';
			
			$final_meta = $makeclass->collect_meta_info_make($m1, $m2, $m3, $connected_manufacturer_id);
			$m1 = $final_meta->m1;
			$m2 = $final_meta->m2;
			$m3 = $final_meta->m3;
		}
		
		//Boat Type Shortcode
		if ($connected_type_id > 0){
			$shortcode_tab_0 = '';
			$shortcode_tab_2 = ' active';
			
			$shortcode_css_2 = '';
			$shortcode_css_0 = ' com_none';
			
			$shortcodecontent = '
			<p>Boat Type Imge &amp; Description: <span class="getshortcode2_1">[fcboattypeprofile boattypeid='. $connected_type_id .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode2_1" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			
			<p>Locally added boats list: <span class="getshortcode2_2">[fclocalboattype boattypeid='. $connected_type_id .']</span></p>
			<div class="clearfixmain"><button type="button" datadiv="getshortcode2_2" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
			';
			
			$final_meta = $boattypeclass->collect_meta_info_boat_type($m1, $m2, $m3, $connected_type_id);
			$m1 = $final_meta->m1;
			$m2 = $final_meta->m2;
			$m3 = $final_meta->m3;
		}
		
		//Custom View Shortcode
		if ($custom_inventory_view != ""){
			$shortcode_tab_0 = '';
			$shortcode_tab_3 = ' active';
			
			$shortcode_css_3 = '';
			$shortcode_css_0 = ' com_none';
			
			$custom_inventory_view_ar = json_decode($custom_inventory_view);
			$custom_make_id = $custom_inventory_view_ar->custom_make_id;
			$custom_condition_id = $custom_inventory_view_ar->custom_condition_id;
			$custom_category_id = $custom_inventory_view_ar->custom_category_id;
			$custom_type_id = $custom_inventory_view_ar->custom_type_id;
			$custom_status_id = $custom_inventory_view_ar->custom_status_id;
			$custom_owned = $custom_inventory_view_ar->custom_owned;
			$sp_typeid = $custom_inventory_view_ar->sp_typeid;
			$custom_statemain = $custom_inventory_view_ar->custom_statemain;
			$custom_stateid = $custom_inventory_view_ar->custom_stateid;
			$custom_lnmin = $custom_inventory_view_ar->custom_lnmin;
			$custom_lnmax = $custom_inventory_view_ar->custom_lnmax;
			$nosearchcol = $custom_inventory_view_ar->nosearchcol;
			$dyanamicheading = $custom_inventory_view_ar->dyanamicheading;
						
			$scode_param = '';
			if ($custom_make_id > 0){
				$scode_param .= " makeid=" . $custom_make_id;
			}
			
			if ($custom_condition_id > 0){
				$scode_param .= " conditionid=" . $custom_condition_id;
			}
			
			if ($custom_category_id > 0){
				$scode_param .= " categoryid=" . $custom_category_id;
			}
			
			if ($custom_type_id > 0){
				$scode_param .= " typeid=" . $custom_type_id;
			}
			
			if ($custom_stateid > 0){
				$scode_param .= " stateid=" . $custom_stateid;
			}

			if ($custom_lnmin > 0){
				$scode_param .= " lnmin=" . $custom_lnmin;
			}
			
			if ($custom_lnmax > 0){
				$scode_param .= " lnmax=" . $custom_lnmax;
			}
			
			if ($custom_status_id > 0){
				$scode_param .= " boatstatus=" . $custom_status_id;
			}
			
			if ($custom_owned > 0){
				$scode_param .= " owned=" . $custom_owned;
			}
			
			if ($sp_typeid > 0){
				$scode_param .= " sp_typeid=" . $sp_typeid;
			}
			
			if ($nosearchcol == 1){
				$scode_param .= " nosearchcol=1";
			}
			
			if ($custom_owned == 2 AND $sp_typeid == 2){
				$custom_owned = 3;
			}
			
			$shortcodecontent = '
			<p>Generated Shortcode: <span class="getshortcode3">[fcboatlist'. $scode_param .']</span></p>
			';
		}
			
		$link_name = "Modify Existing Page";  	                    
					
	}else{
		$ms = 0;
	}
}
if ($new_window != "y"){ $new_window = "n"; }
//collect all pagename except current page
$arrProdCode = array();
$code_sql = "select pgnm from tbl_page where id != '". $ms ."'";
$code_result = $db->fetch_all_array($code_sql);
$code_found = count($code_result);
for ($k = 0; $k < $code_found; $k++){	
	$code_row = $code_result[$k];
	$arrProdCode[] = strtoupper($code_row['pgnm']);
}
//end
$icclass = "leftpageicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	var currenttemplate = $("#templatefile").val();
    $("#page_ff").submit(function(){
        var ms = parseInt($('#ms').val());
        if(!validate_text(document.getElementById("name"),1,"Please enter Page Heading")){
            return false;
        }

        if (document.ff.page_type.value == 2){ // for document link
            var doc_name = "<?php echo $doc_name; ?>";
            if (doc_name == ""){
                if (!file_validation(document.ff.myfile.value, 'n', '<?php echo $cm->allow_attachment_ext; ?>')){ return false; }
            }
        }

        if (document.ff.page_type.value == 3){ // for link URL
            if (document.getElementById("link_type").value == 1){
                if(!validate_text(document.ff.page_url,1,"Please enter Link URL")){
                    return false;
                }
            }else{
                if(!validate_text(document.ff.int_page_sel,1,"Please select Page")){
                    return false;
                }
            }
        }

        if (ms == 0){
            if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
                return false;
            }
        }

        if ((document.ff.page_type.value == 1) || (document.ff.page_type.value == 4) || (document.ff.page_type.value == 5)){
            var pagenmval = "y";
            if (pagenmval == "y"){
                if(!validate_text(document.ff.pgnm,1,"Please enter Seo File Name")){
                    return false;
                }
                ckfilenm = document.ff.pgnm.value;
                if (chkpagename("y", ckfilenm)){
                    // no code
                }else{
                    document.ff.pgnm.focus();
                    return false;
                }
            }
			
			if ($("#imgpath").length > 0) {
				if (!image_validation(document.ff.imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
			}

            //if (!editor_validation('file_data', 'Page Content')){ return false; }


            /*if(!validate_text(document.getElementById("m1"),1,"Please enter Page Title")){
                return false;
            }*/
        }
        return true;
    });
	
	//set sidebar
	$("#templatefile").change(function(){
		var selecttemplate = $(this).val();
		if ((selecttemplate == "twocolumnleft.php") || (selecttemplate == "twocolumnright.php")){
			$(".sidebar").removeClass("com_none");
		}else{	
			$(".sidebar").addClass("com_none");
		}
	});
	
	/*SHORT CODE*/
	$(".generateshortcode").click(function(){
		var connectdiv = parseInt($(this).attr("connectdiv"));
		$(".generateshortcode").removeClass("active");
		$(this).addClass("active");
		
		$(".shortcodediv").addClass("com_none");
		$(".shortcodediv" + connectdiv).removeClass("com_none");
		
		if (connectdiv == 3){
			//choose listboat as template
			$("#templatefile").val("listboat.php");
		}else{
			//back to current template
			$("#templatefile").val(currenttemplate);
		}
		
	});
	
	//Custom Inv code
	$.fn.custom_inv_code_generate = function(){
		//Other Reset
		$("#connected_manufacturer_id").val('');
		$("#connected_group_id").val('');
		$("#connected_type_id").val('');
		$(".displayshortcode").html('');
		//end
		
		var catamaran_id = parseInt($("#catamaran_id").val());
		
		var custom_make_id = $("#custom_make_id").val();
		var custom_condition_id = $("#custom_condition_id").val();
		var custom_category_id = $("#custom_category_id").val();
		var custom_type_id = $("#custom_type_id").val();
		var custom_stateid = $("#custom_stateid").val();
		var custom_lnmin = $("#custom_lnmin").val();
		var custom_lnmax = $("#custom_lnmax").val();
		var custom_status_id = $("#custom_status_id").val();
		var custom_owned = $("#custom_owned").val();
		var nosearchcol = 0;
		if($("#nosearchcol").is(':checked')){
			nosearchcol = 1;
		}
		
		var sp_typeid = 0;
		if (custom_owned == 2){
			sp_typeid = 1;
		}else if (custom_owned == 3){
			custom_owned = 2;
			sp_typeid = 2;
		}else if (custom_owned == 1){
			//catamaran_id
			if (custom_type_id == catamaran_id){
				sp_typeid = 2;
			}else{
				sp_typeid = 1;
			}
		}
		
		custom_make_id = parseInt(custom_make_id);
		custom_condition_id = parseInt(custom_condition_id);
		custom_category_id = parseInt(custom_category_id);
		custom_type_id = parseInt(custom_type_id);
		custom_stateid = parseInt(custom_stateid);
		custom_status_id = parseInt(custom_status_id);
		custom_owned = parseInt(custom_owned);
		sp_typeid = parseInt(sp_typeid);
		
		var scode_param = '';
		if (custom_make_id > 0){
			scode_param += " makeid=" + custom_make_id;
		}
		
		if (custom_condition_id > 0){
			scode_param += " conditionid=" + custom_condition_id;
		}
		
		if (custom_category_id > 0){
			scode_param += " categoryid=" + custom_category_id;
		}
		
		if (custom_type_id > 0){
			scode_param += " typeid=" + custom_type_id;
		}
		
		if (custom_stateid > 0){
			scode_param += " stateid=" + custom_stateid;
		}

		if (custom_lnmin > 0){
			scode_param += " lnmin=" + custom_lnmin;
		}
		
		if (custom_lnmax > 0){
			scode_param += " lnmax=" + custom_lnmax;
		}
		
		if (custom_status_id > 0){
			scode_param += " boatstatus=" + custom_status_id;
		}
		
		if (custom_owned > 0){
			scode_param += " owned=" + custom_owned;
		}
		
		if (sp_typeid > 0){
			scode_param += " sp_typeid=" + sp_typeid;
		}
		
		if (nosearchcol == 1){
			scode_param += " nosearchcol=1";
		}

		var shortcodecontent = '';
		if (scode_param != ""){
			var shortcodecontent = '<p>Generated Shortcode: <span class="getshortcode3">[fcboatlist' + scode_param + ']</span></p>';
		}
		$(".displayshortcode3").html(shortcodecontent);
	}
	
	$(".custom_inv_code").change(function(){
		$(this).custom_inv_code_generate();	
	});
	
	$(".nosearchcol").click(function(){
		$(this).custom_inv_code_generate();			
	});

	$(".custom_inv_code_text").keyup(function(){
       $(this).custom_inv_code_generate();
    });
	
	$("#statemain").change(function(){
		var statemain = parseInt($(this).val());
		if (statemain == 1){
			$(".statesub").removeClass("com_none");
		}else{
			$(".statesub").addClass("com_none");
		}
	});
	
	//insert code to editor
	$(".whitetd").off("click", ".insertcodetoeditor").on("click", ".insertcodetoeditor", function(){
		var datadiv = $(this).attr("datadiv");
		var shortcode_content = $("." + datadiv).html();
		if (shortcode_content != ""){
			shortcode_content = '<div class="clearfixmain">' + shortcode_content + '</div>';
			CKEDITOR.instances.file_data.insertHtml(shortcode_content);
		}
	});
	
	//display yc model group based on make selection
	$("#connected_manufacturer_id").change(function(){
		var targetcombo = $(this).attr("targetcombo");
		var makeid = $(this).val();
		
		var b_sURL = "onlyadminajax.php";
		$.post(b_sURL,
		{
			makeid:makeid,
			inoption:15,
			az:13,
			dataType: 'json'
		},
		function(data){
			data = $.parseJSON(data);            
			$('#' + targetcombo).empty();			
			$("#" + targetcombo).append('<option value="">Select Model Group</option>');
						
			var str_ln = data.length;
			for (var k = 0; k < str_ln; k++){	
				  $("#" + targetcombo).append('<option value="'+ data[k]["id"] +'">'+ data[k]["name"] +'</option>');
			}
		});
	});
	
	//Display Manufracture shortcode
	$(".make_group").change(function(){
		//Other Reset
		$("#connected_type_id").val('');
		$("#custom_make_id").val('');
		$("#custom_condition_id").val('');
		$("#custom_category_id").val('');
		$("#custom_type_id").val('');
		$("#custom_stateid").val('');
		$("#custom_lnmin").val('');
		$("#custom_lnmax").val('');
		$("#custom_status_id").val('');		
		$(".displayshortcode").html('');
		//end
		
		var ms = $("#ms").val();
		ms = parseInt(ms);
		
		var makeid = $("#connected_manufacturer_id").val();
		makeid = parseInt(makeid);
		if (makeid > 0){
			
			var modelgroupid = $("#connected_group_id").val();
			modelgroupid = parseInt(modelgroupid);
			
			var predesign_scode_param = " makeid=" + makeid;
			if (modelgroupid > 0){
				predesign_scode_param += " modelgroupid=" + modelgroupid;
			}
			
			var shortcodecontent = '<p>Manufacturer Image &amp; Description: <span class="getshortcode1_1">[fcmakeprofile makeid='+ makeid +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode1_1" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
				
				shortcodecontent += '<p>"Pre-Designed" manufacturer Group display: <span class="getshortcode1_2">[fcpredesignmakegroup makeid='+ makeid +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode1_2" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
			
				shortcodecontent += '<p>"Pre-Designed" manufacturer Model display: <span class="getshortcode1_3">[fcpredesignmake'+ predesign_scode_param +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode1_3" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
				
				shortcodecontent += '<p>Locally added boats list: <span class="getshortcode1_4">[fclocalboat makeid='+ makeid +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode1_4" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
				
			$(".displayshortcode1").html(shortcodecontent);
			//$(".displayshortcode1").removeClass("com_none");
			
			if (ms == 0){
				$("#m1").val("");
			}
			
		}else{
			$(".displayshortcode1").html('');
			//$(".displayshortcode1").addClass("com_none");
		}		
	});
	
	//Display Boat Type shortcode
	$("#connected_type_id").change(function(){
		//Other Reset
		$("#connected_manufacturer_id").val('');
		$("#custom_make_id").val('');
		$("#custom_condition_id").val('');
		$("#custom_category_id").val('');
		$("#custom_type_id").val('');
		$("#custom_stateid").val('');
		$("#custom_lnmin").val('');
		$("#custom_lnmax").val('');
		$("#custom_status_id").val('');
		$(".displayshortcode").html('');
		//end
		
		var ms = $("#ms").val();
		ms = parseInt(ms);
		
		var boattypeid = $(this).val();
		boattypeid = parseInt(boattypeid);
		if (boattypeid > 0){
			
			var shortcodecontent = '<p>Boat Type Image &amp; Description: <span class="getshortcode2_1">[fcboattypeprofile boattypeid='+ boattypeid +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode2_1" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
				
				shortcodecontent += '<p>Locally added boats list: <span class="getshortcode2_2">[fclocalboattype boattypeid='+ boattypeid +']</span></p>';
				shortcodecontent += '<div class="clearfixmain"><button type="button" datadiv="getshortcode2_2" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>';
			
			$(".displayshortcode2").html(shortcodecontent);
			//$(".displayshortcode1").removeClass("com_none");
			
			if (ms == 0){
				$("#m1").val("");
			}
			
		}else{
			$(".displayshortcode2").html('');
			//$(".displayshortcode1").addClass("com_none");
		}		
	});
});

function sel_page_type(){
  var set_page_type = document.getElementById("page_type");
  if ((set_page_type.value == 1) || (set_page_type.value == 4) || (set_page_type.value == 5)){
   Show_MQ("cmsoption1");
   Show_MQ("cmsoption1_1");
   //Show_MQ("cmsoption1_3");
   Hide_MQ("cmsoption2");
   Hide_MQ("cmsoption3");
   Hide_MQ("cmsoption3_a");
   Hide_MQ("cmsoption3_b");
   Hide_MQ("cmsoption4");
   
   //link req display or not and set link display position
   var ttldo = document.ff.disp_on.length;
   if (set_page_type.value == 5){
   Show_MQ("cmsoption1_2");
   document.ff.disp_on[0].checked = true;
   for(var kj = 1; kj < ttldo; kj++){
    document.ff.disp_on[kj].disabled = true;
   }
   }else{
   Hide_MQ("cmsoption1_2"); 
   for(var kj = 0; kj < ttldo; kj++){
    document.ff.disp_on[kj].disabled = false;
   }  
   }
   //end   
   
  }else if (set_page_type.value == 2){
   Show_MQ("cmsoption2");
   Hide_MQ("cmsoption1");
   Hide_MQ("cmsoption1_1");
   Hide_MQ("cmsoption1_2");
   //Hide_MQ("cmsoption1_3");
   Hide_MQ("cmsoption3");
   Hide_MQ("cmsoption3_a");
   Hide_MQ("cmsoption3_b");
   Hide_MQ("cmsoption4");
  }else{
   document.getElementById("link_type").value = 1;
   Show_MQ("cmsoption3");
   Show_MQ("cmsoption3_a");
   Hide_MQ("cmsoption3_b");
   Show_MQ("cmsoption4");
   Hide_MQ("cmsoption1");
   Hide_MQ("cmsoption1_1");
   Hide_MQ("cmsoption1_2");
   //Hide_MQ("cmsoption1_3");
   Hide_MQ("cmsoption2");
  }
}

function set_link_type(){
  var set_link_type = document.getElementById("link_type");
  if (set_link_type.value == 1){
   Show_MQ("cmsoption3_a");
   Hide_MQ("cmsoption3_b");
  }else{
   Show_MQ("cmsoption3_b");
   Hide_MQ("cmsoption3_a");
  }
}

function chkpagename(msg_display, code){
arrProdCode = new Array();<?php

foreach($arrProdCode AS $key => $val) {
	if($key == "") {
		$key = 0;
	}
	?>arrProdCode[<?php echo $key; ?>] = "<?php echo $val; ?>";<?php
}
?>
var flagCode = false;
codeUpper = code.toUpperCase();
// Check code availability
if(arrProdCode.length > 0) {
	for(var intCnt = 0; intCnt < arrProdCode.length; intCnt++) {
		if(codeUpper == arrProdCode[intCnt]) {
			flagCode = true;
			break;
		}
		else {
			flagCode = false;
		}
	}
}

if(flagCode) {
    if (msg_display == "y"){
		alert("SEO File Name '" + code + "' already exists. Please enter another file name.");
	}
	return false;
}
else {   
	//check format
	var ms = $('#ms').val();
	ms = parseInt(ms);
	if (ms != 1){
		if (!/^[a-z0-9_-]+$/i.test(code)) {
			alert ("Invalid format");
			return false;
		} 	
	}	
	return true;
}

}

function setTitleFileName(objForm) {
    var set_page_type = document.getElementById("page_type");
    if ((set_page_type.value == 1) || (set_page_type.value == 4) || (set_page_type.value == 5)){
	   // set title
	   objForm.m1.value = objForm.name.value;	   
	   	   
	   // set file name
		var inputString = objForm.name.value;
		var fileName = "";
		var flagSet = false;

		for(var i = 0; i < inputString.length; i++) {
			var singleChar = inputString.charAt(i);
			var charUnicodeVal = singleChar.charCodeAt(0);

			if((charUnicodeVal > 47 && charUnicodeVal < 58) || (charUnicodeVal > 64 &&  charUnicodeVal < 91) || (charUnicodeVal > 96 && charUnicodeVal < 123) || (charUnicodeVal == 45 || charUnicodeVal == 95)) {
				fileName += singleChar;
				flagSet = true;
			}
			else if(charUnicodeVal == 32) {
				fileName += "-";
			}
		}

		if(fileName != "" && flagSet) {
			if(objForm.pgnm.value == "") {
			    fileName = fileName.toLowerCase();
				newfilename = fileName ;
				if (chkpagename("n", newfilename)){
				 objForm.pgnm.value = newfilename;
				}else{
				 objForm.pgnm.value = fileName + "-1";
				}
				
			}
		}
	}
}

function set_for_cattype(){
  if (document.getElementById("link_required1").checked == true){
    Show_MQ("cmsoption1");
    Show_MQ("cmsoption1_1");
	//Show_MQ("cmsoption1_3");
  }else{
    Hide_MQ("cmsoption1");
    Hide_MQ("cmsoption1_1");
	//Hide_MQ("cmsoption1_3");
  }
}
</script>
<form method="post" action="page_sub.php" id="page_ff" name="ff" enctype="multipart/form-data">
<input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
<input type="hidden" value="<?php echo $rank; ?>" name="oldrank" />
<input type="hidden" value="<?php echo $parentid; ?>" name="parentid" />
<input type="hidden" value="<?php echo $yachtclass->catamaran_id; ?>" id="catamaran_id" name="catamaran_id" />
 <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
 <?php
 if ($defaultpage > 0){
 $page_type_nm = $db->total_record_count("select name as ttl from tbl_page_type where id = '". $page_type ."'");
 ?>
 <tr>
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Selected Page Type:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><span class="fontbold"><?php echo $page_type_nm; ?></span>
    <input type="hidden" value="<?php echo $page_type; ?>" id="page_type" name="page_type" />
    </td>
   </tr>
 <?php }else{ ?>
   <tr>
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Page Type:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><select name="page_type" id="page_type" class="combobox_size4 htext" onchange="javascript:sel_page_type()">
        <?php
        $adm->get_pagetype_combo($page_type);
        ?>
       </select></td>
   </tr>
 <?php } ?>

    <tr>
        <td width="35%" align="left"><span class="fontcolor3">* </span>Page Heading:</td>
        <td width="65%" align="left"><input type="text" name="name" id="name" value="<?php echo $name; ?>" class="inputbox inputbox_size1"<?php if ($ms != 1){?> onBlur="javascript: setTitleFileName(this.form);"<?php } ?> /></td>
    </tr>

    <tr id="cmsoption1_2" style=" <?php echo $cms_linkreq; ?>">
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Link Required:</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><input type="radio" name="link_required" id="link_required1" value="y" <?php if ($link_required == "y"){ echo "checked"; } ?> onclick="javascript:set_for_cattype()" />&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="link_required" id="link_required2" value="n" <?php if ($link_required == "n"){ echo "checked"; } ?> onclick="javascript:set_for_cattype()" />&nbsp;&nbsp;No</td>
    </tr>

 <?php 
 //if ($defaultpage > 0 AND $link_required == "y"){ 
 if ($ms == 1){
 ?>
   <tr id="cmsoption1_1" style=" <?php echo $cms_seofile; ?>">
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Seo File Name:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><span class="fontbold"><?php echo $pgnm; ?></span>
    <input type="hidden" name="pgnm" value="<?php echo $pgnm; ?>" />
    </td>
   </tr>
 <?php }else{ ?>
   <tr id="cmsoption1_1" style=" <?php echo $cms_style1; ?>">
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Seo File Name:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" name="pgnm" class="inputbox inputbox_size1" value="<?php echo $pgnm; ?>" />&nbsp;Ex: aboutus/</td>
   </tr>
 <?php } ?>

 <tr id="cmsoption2" style=" <?php echo $cms_style2; ?>">
   <?php if ($doc_name != ""){ ?>
   <td width="35%" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Document File:</td>
   <td width="65%" align="left" class="tdpadding1"><strong><?php echo $doc_name; ?></strong>&nbsp;&nbsp;<a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','doc_name','tbl_page','id','docfile')">Delete Document</a></td>
   <?php }else{ ?>
     <td width="35%" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Document File:<br />&nbsp;&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_attachment_ext; ?>]</td>
     <td width="65%" align="left" class="tdpadding1"><input type="file" name="myfile" class="inputbox" size="50" /></td>
   <?php } ?>
   </tr>

   <tr id="cmsoption3" style=" <?php echo $cms_style3; ?>">
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Link:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1">
    <select name="link_type" id="link_type" class="htext" onchange="javascript:set_link_type()">
     <option value="1" <?php if ($link_type == 1){ echo "selected";} ?>>External Link</option>
     <option value="2" <?php if ($link_type == 2){ echo "selected";} ?>>Internal Link</option>
    </select>&nbsp;&nbsp;&nbsp;
    <span id="cmsoption3_a" style=" <?php echo $cms_style3_a; ?>"><span class="fontcolor3">* </span>Specify URL:&nbsp;&nbsp;<input type="text" name="page_url" class="inputbox inputbox_size4_c" value="<?php echo $page_url; ?>" /></span>
    <span id="cmsoption3_b" style=" <?php echo $cms_style3_b; ?>"><span class="fontcolor3">* </span>Select Page:&nbsp;&nbsp;
    <select name="int_page_sel" id="int_page_sel" class="htext">
    	<optgroup label="Pages">
       	<?php
        //dynamic page = b
        $adm->get_page_combo($int_page_sel);
		?>
        </optgroup>
        
        <optgroup label="Listing Search">        
        <?php
		//yacht section search = c
        $adm->get_section_combo($int_page_sel);
        ?>
        </optgroup>
    </select>
    </span>
    </td>
   </tr>

   <tr id="cmsoption4" style="<?php echo $cms_style3; ?>">
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>On Click, Open In:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><input type="radio" name="new_window" id="new_window1" value="y" <?php if ($new_window == "y"){ echo "checked"; } ?> />&nbsp;&nbsp;New Window&nbsp;&nbsp;&nbsp;<input type="radio" name="new_window" id="new_window2" value="n" <?php if ($new_window == "n"){ echo "checked"; } ?> />&nbsp;&nbsp;Same Window</td>
   </tr>

   <tr>
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Display On:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><div class="opt_holder">
    <?php $adm->get_displayon_option($disp_on, $page_type); ?>
    </div></td>
   </tr>
   
   <?php if ($ms != 1){ ?>
 	<tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display Page heading at the top of page content?</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><input type="checkbox" name="display_page_heading" id="display_page_heading" value="1" <?php if ($display_page_heading == "1"){ echo 'checked="checked"'; } ?> /></td>
    </tr>
    <?php } ?>

   <tr>
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Display Status:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><input type="radio" name="status" id="status1" value="y" <?php if ($status == "y"){ echo "checked"; } ?> />&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;<input type="radio" name="status" id="status2" value="n" <?php if ($status == "n"){ echo "checked"; } ?> />&nbsp;&nbsp;No</td>
   </tr>

   <?php if ($ms > 0){ ?>
   <tr>
    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
    <td width="65%" align="left" valign="top" class="tdpadding1"><input type="text" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
   </tr>
   <?php } ?>
 </table>

 <table id="cmsoption1" border="0" width="95%" cellspacing="0" cellpadding="5" class="htext" style=" <?php echo $cms_style1; ?>">
   <tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Slider:</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><select name="slider_category_id" id="slider_category_id" class="combobox_size4 htext">
                <option value="">Select</option>
                <?php
                echo $adm->get_slider_category_combo($slider_category_id);
                ?>
            </select></td>
    </tr>
    
    <tr>
        <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;<strong class="fontuppercase">Page Content</strong>:</td>
    </tr>
   
   <?php if ($defaultpage == 0){ ?>
 	<tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display not as a Page?</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><input type="checkbox" name="only_menu" id="only_menu" value="1" <?php if ($only_menu == "1"){ echo 'checked="checked"'; } ?> /></td>
    </tr>
    <?php } ?>
    
    <?php
	 if ($parentid == 4000000){
		 $imw = $cm->menu_im_width;
		 $imh = $cm->menu_im_height;
	 ?>
	 <?php if ($menu_imgpath != ""){ ?>
        <tr>
         <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Menu Image:</td>
         <td width="" align="left" valign="top" class="tdpadding1">
         <img src="../menuimage/<?php echo $menu_imgpath; ?>" border="0" width="100" /><br />
         <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','menu_imgpath','tbl_page','id','menuimage')">Delete Image</a>
         </td>
        </tr>
    <?php }else{ ?>
        <tr>
         <td width="" align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Menu Image [w: <?php echo $imw; ?>px, h: <?php echo $imh ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
         <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox" size="65" /></td>
        </tr>
    <?php } ?>
	 <?php
	 }
	 ?>
     
     <?php
	 if ($parentid == 0){
	 ?>
     <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Choose Section(s) for Sub-menu area:</td>
        <td width="" align="left" valign="top" class="tdpadding1">
        <?php
        echo $cm->get_top_menu_section_checkbox(array("ulclass" => "customview3col", "submenusection" => $submenusection));
        ?>
        
        <div class="custominvcodenotification">Note: The above section(s) will display if this menu has sub-menu link.</div>
        </td>
    </tr>
     <?php
	 }
	 ?>
    
    <tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Page Template:</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><select name="templatefile" id="templatefile" class="combobox_size4 htext">
        <?php
        echo $templateclass->page_template_dropdown($templatefile);
        ?>
        </select></td>
    </tr>
    
    <tr class="sidebar<?php echo $sidebarclass; ?>">
       <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Select Sidebar:</td>
       <td width="65%" align="left" valign="top" class="tdpadding1"><select name="column_id" id="column_id" class="combobox_size4 htext">
       <?php
	   echo $templateclass->get_sidebar_combo($column_id);
	   ?>
       </select></td>
	</tr>
    
    <tr>
    	<td colspan="2">
        <p><strong>Generate Shortcode</strong></p>
        <div class="singleblock_box clearfixmain">
        	<div class="generateshortcodetab clearfixmain">
                <ul>
                    <li><a class="generateshortcode<?php echo $shortcode_tab_1; ?>" connectdiv="1" href="javascript:void(0);">Connect with Manufacturer</a></li>
                    <li><a class="generateshortcode<?php echo $shortcode_tab_2; ?>" connectdiv="2" href="javascript:void(0);">Connect with Boat Type</a></li>
                    <li><a class="generateshortcode<?php echo $shortcode_tab_3; ?>" connectdiv="3" href="javascript:void(0);">Custom Inventory Views</a></li>
                    <li><a class="generateshortcode<?php echo $shortcode_tab_0; ?>" connectdiv="0" href="javascript:void(0);">None</a></li>
                </ul>
            </div>
            
            <div class="generateshortcodecontent clearfixmain">
            	<div class="shortcodediv shortcodediv1<?php echo $shortcode_css_1; ?> clearfixmain">
                	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    	<tr>
                        	<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Connect to Manufacturer:</td>
                            <td width="65%" align="left" valign="top" class="tdpadding1">                                
                                <ul class="customview2col clearfixmain">
                                	<li>
                                		<select sconnectdiv="1" name="connected_manufacturer_id" id="connected_manufacturer_id" targetcombo="connected_group_id" class="make_group combobox_size4 htext">
											<option value="">Select</option>
											<?php
											echo $yachtchildclass->get_make_list_combo($connected_manufacturer_id);
											?>
										</select>
                                	</li>
                                	
                                	<li>
                                		<select sconnectdiv="1" name="connected_group_id" id="connected_group_id" class="make_group combobox_size4 htext">
											<option value="">Select Model Group</option>
											<?php
											echo $yachtchildclass->get_model_group_list_combo($connected_manufacturer_id, $connected_group_id);
											?>
										</select>
                                	</li>
								</ul>
                                
                                <div class="displayshortcode displayshortcode1"><?php echo $shortcodecontent; ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="generateshortcodecontent clearfixmain">
            	<div class="shortcodediv shortcodediv2<?php echo $shortcode_css_2; ?> clearfixmain">
                	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                    	<tr>
                        	<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Connect to Boat Type:</td>
                            <td width="65%" align="left" valign="top" class="tdpadding1">
                            	<select sconnectdiv="2" name="connected_type_id" id="connected_type_id" class="combobox_size4 htext">
                                    <option value="">Select</option>
                                    <?php
                                    echo $yachtclass->get_type_combo_parent($connected_type_id, 0, 0, 1);
                                    ?>
                                </select>                                
                                <div class="displayshortcode displayshortcode2"><?php echo $shortcodecontent; ?></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="generateshortcodecontent clearfixmain">
            	<div class="shortcodediv shortcodediv3<?php echo $shortcode_css_3; ?> clearfixmain">
                	
                    <ul class="customviewcombo clearfixmain">
                    	<li>
                        	<p>Make</p>
                            <select name="custom_make_id" id="custom_make_id" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_manufacturer_combo($custom_make_id, 1, 1);
                                ?>
                            </select>
                        </li>
                        
                        <li>
                        	<p>Condition</p>
                            <select name="custom_condition_id" id="custom_condition_id" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_condition_combo($custom_condition_id, 1, 1);
                                ?>
                            </select>
                        </li>
                        
                        <li>
                        	<p>Category</p>
                            <select name="custom_category_id" id="custom_category_id" targetcombo="custom_type_id" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_category_combo($custom_category_id, 1, 1);
                                ?>
                            </select>
                        </li>
                        
                        <li>
                        	<p>Boat Type</p>
                            <select name="custom_type_id" id="custom_type_id" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_type_combo_parent($custom_type_id, $custom_category_id, 0, 1);
                                ?>
                            </select>
                        </li>
                                                
                        <li>
                        	<p>Boat Status</p>
                            <select name="custom_status_id" id="custom_status_id" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_yachtstatus_combo_wthout_preview($custom_status_id);
                                ?>
                            </select>
                        </li>
                        
                        <li>
                        	<p>Listing Type</p>
                            <select name="custom_owned" id="custom_owned" class="combobox_size4 htext custom_inv_code">
                                <?php
                                echo $yachtclass->get_boat_listing_type_combo($custom_owned);
                                ?>
                            </select>
                        </li>
                        
                        <li class="statesub">
                        	<p>US State</p>
                            <select name="custom_stateid" id="custom_stateid" class="combobox_size4 htext custom_inv_code">
                                <option value="">Select</option>
                                <?php
                                echo $yachtclass->get_state_combo($custom_stateid, 1);
                                ?>
                            </select>
                        </li>
						
						<li>
                        	<p>Min Length - ft</p>
                           <input type="text" name="custom_lnmin" id="custom_lnmin" value="<?php echo $custom_lnmin;?>" class="custom_inv_code_text inputbox inputbox_size4" />
                        </li>
                        <li>
                        	<p>Max Length - ft</p>
                           <input type="text" name="custom_lnmax" id="custom_lnmax" value="<?php echo $custom_lnmax;?>" class="custom_inv_code_text inputbox inputbox_size4" />
                        </li>
                    </ul>
                    <div class="spacer2 clearfixmain">Exclude Left Search Column?&nbsp;&nbsp; <input class="checkbox nosearchcol" type="checkbox" id="nosearchcol" name="nosearchcol" value="1"<?php if ($nosearchcol == 1){ echo ' checked="checked"'; } ?> /> Yes</div>
                    <div class="spacer2 clearfixmain">Replace Page Heading with Dynamic Heading (<strong>[Condition] [Make] for Sale in [US State]</strong>)?&nbsp;&nbsp; <input class="checkbox dyanamicheading" type="checkbox" id="dyanamicheading" name="dyanamicheading" value="1"<?php if ($dyanamicheading == 1){ echo ' checked="checked"'; } ?> /> Yes</div>
                    
                    <div class="displayshortcode displayshortcode3"><?php echo $shortcodecontent; ?></div>
                    <div class="clearfixmain"><button type="button" datadiv="getshortcode3" class="insertcodetoeditor butta"><span class="addIcon butta-space">Insert Code to Editor</span></button></div>
                    <div class="custominvcodenotification">Note: Please choose Page Template as "Boat Listing"</div>
                </div>
            </div>
            
        </div>
        </td>
    </tr>    
   
   <tr>
        <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;&nbsp;<strong>Content</strong>:</td>
   </tr>

   <tr>
        <td width="100%" align="center" colspan="2" class="tdpadding1">
          <?php
            $editorstylepath = "";
			if ($ms == 1){
				$editorextrastyle = "adminbodyclass home text_area home-content";
			}elseif ($ms == 6){
				$editorextrastyle = "adminbodyclass text_area contact-content";
			}else{
				$editorextrastyle = "adminbodyclass text_area";
			}            
            $cm->display_editor("file_data", $sBasePath, "100%", 400, $file_data, $editorstylepath, $editorextrastyle);
          ?>
        </td>
   </tr>

   <tr>
        <td width="100%" align="left" valign="middle" colspan="2">&nbsp;&nbsp;&nbsp;<strong>Meta Information:</strong></td>
   </tr>

   <tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;&nbsp;</span>Use Default Meta Information  </td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><input type="checkbox" name="def_meta" id="def_meta" value="y" <?php if ($def_meta == "y"){ echo "checked"; } ?> onclick="javascript:def_mata()" /></td>
   </tr>

   <tr>
          <td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Page Title:</td>
          <td width="65%" align="left"><input type="text" name="m1" id="m1" value="<?php echo $m1;?>" class="inputbox inputbox_size1" /></td>
   </tr>

   <tr>
          <td width="35%" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Description:</td>
          <td width="65%" align="left" valign="top"><textarea name="m2" id="m2" rows="1" cols="1" class="textbox textbox_size1"><?php echo $m2;?></textarea> </td>
   </tr>

   <tr>
          <td width="35%" align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>Meta Keywords:</td>
          <td width="65%" align="left" valign="top"><textarea name="m3" id="m3" rows="1" cols="1" class="textbox textbox_size1"><?php echo $m3;?></textarea> </td>
    </tr>
 </table>

 <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
   <tr>
      <td width="35%" align="left">&nbsp;</td>
      <td width="65%" align="left">
          <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
          <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
      </td>
    </tr>
</table>
</form>
<?php
include("metajs.php");
include("foot.php");
?>