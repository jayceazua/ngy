<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Add New Image/Video - Slider";
$ms = round($_GET["id"], 0);
$status_id = 1;
$new_window = "n";
$cms_style3_a = $cms_style3_b = "display: none; visibility: hidden;";
$link_type = 0;
$buttontext = '';
$slidermessagecss = ' com_none';
$slidermessagepositioncss = ' com_none';
$display_message = 0;
$text_position_id = 5;
$fontcolor = "ffffff";
$fontcolor2 = "ffffff";

$video_type = 1;
$imagevideoselection1 = '';
$imagevideoselection2 = ' com_none';
$imagevideoselection3 = ' com_none';
$imagevideoselection4 = ' com_none';

if ($ms > 0){
	$sql="select * from tbl_image_slider where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql);
    $found = count($result); 	
	if ($found > 0){
		$row = $result[0];
		foreach($row AS $key => $val){
            ${$key} = $cm->filtertextdisplay($val);
        }			
		
		if ($display_message == 1){ 
			$slidermessagecss = '';
			$slidermessagepositioncss = '';
			$int_page_sel = $int_page_id . "/!" . $int_page_tp;
		
			if ($link_type == 1){
			   $cms_style3_a = "visibility: visible;";
			   $cms_style3_b = "display: none; visibility: hidden;";
			}elseif ($link_type == 2){
			   $cms_style3_b = "visibility: visible;";
			   $cms_style3_a = "display: none; visibility: hidden;";
			}else{
			   $cms_style3_a = $cms_style3_b = "display: none; visibility: hidden;";
			}
		}
		
		if ($display_message == 2){ 
			$slidermessagepositioncss = ''; 
		}
		
		if ($video_type == 2){
			//youtube
			$imagevideoselection1 = ' com_none';
			$imagevideoselection2 = '';
			$imagevideoselection3 = ' com_none';
			$imagevideoselection4 = ' com_none';
		}elseif ($video_type == 3){
			//vimeo
			$imagevideoselection1 = ' com_none';
			$imagevideoselection2 = ' com_none';
			$imagevideoselection3 = ' ';
			$imagevideoselection4 = ' com_none';
		}elseif ($video_type == 4){
			//MP4
			$imagevideoselection1 = ' com_none';
			$imagevideoselection2 = ' com_none';
			$imagevideoselection3 = ' com_none';
			$imagevideoselection4 = ' ';
		}else{
			//image
			$imagevideoselection1 = '';
			$imagevideoselection2 = ' com_none';
			$imagevideoselection3 = ' com_none';
			$imagevideoselection4 = ' com_none';
		}
		
		$link_name = "Modify Existing Image/Video - Slider";
	}else{
		$ms = 0;
	}
}

if ($ms == 0){
	$name = $cm->sitename;
}

if ($buttontext == ""){ $buttontext = "Details"; }
$icclass = "leftslidericon";
include("head.php");
?>

<?php echo $sliderclass->display_slider_tab_button($ms, $video_type); ?>

<div class="tabcontent tabcontent1">
<form method="post" id="slider_image_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
    <input type="hidden" value="<?php echo $rank; ?>" id="oldrank" name="oldrank" />
    <table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Slider Category:</td>
        <td width="65%" align="left" valign="top" class="tdpadding1"><select name="category_id" id="category_id" class="htext combobox_size6">
                <option value="">Select</option>
                <?php
                echo $sliderclass->get_slider_category_combo($category_id);
                ?>
            </select></td>
    </tr>
    
    <tr>
        <td width="" align="left"><span class="fontcolor3">* </span>Title:</td>
        <td width="" align="left"><input type="text" id="name" name="name" value="<?php echo $name; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr>
    	<td colspan="2">
        	<p><strong>Image/Video</strong></p>
            <div class="singleblock_box clearfixmain">
            	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                	<tr>
                    	<td width="100%" align="left" valign="top" class="tdpadding1" colspan="2">
                        <input class="imagevideo radiobutton radiobutton1st" type="radio" name="video_type" id="video_type1" value="1" <?php if ($video_type == "1"){ echo 'checked="checked"'; } ?> /> Image&nbsp;&nbsp;
                        <input class="imagevideo radiobutton" type="radio" name="video_type" id="video_type2" value="2" <?php if ($video_type == "2"){ echo 'checked="checked"'; } ?> /> YouTube&nbsp;&nbsp;
                        <input class="imagevideo radiobutton" type="radio" name="video_type" id="video_type3" value="3" <?php if ($video_type == "3"){ echo 'checked="checked"'; } ?> /> Vimeo&nbsp;&nbsp;
                        <input class="imagevideo radiobutton" type="radio" name="video_type" id="video_type4" value="4" <?php if ($video_type == "4"){ echo 'checked="checked"'; } ?> /> Video (<?php echo $cm->allow_video_ext; ?>)
                        <div class="imagevideo_error"></div>
                        </td>
                    </tr>
                    
                    <tr class="imagevideoselection imagevideoselection1<?php echo $imagevideoselection1; ?>">
                    	<?php if ($imgpath != ""){ ?>       
                        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Slider Image:</td>
                        <td width="65%" align="left" valign="top" class="tdpadding1">
                        <img src="../sliderimage/<?php echo $imgpath; ?>" border="0" width="100" /><br />
                        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','imgpath','tbl_image_slider','id','sliderimage')">Delete Image</a>
                        </td>         
                        <?php }else{ ?>
                        <td width="35%" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Slider Image [w: <?php echo $cm->slider_im_width; ?>px, h: <?php echo $cm->slider_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                        <td width="65%" align="left" class="tdpadding1"><input type="file" id="imgpath" name="imgpath" class="inputbox inputbox_size4" /></td> 
                        <?php } ?>
                    </tr>
                    
                    <tr class="imagevideoselection imagevideoselection2<?php echo $imagevideoselection2; ?>">
                    	<td width="35%" align="left"><span class="fontcolor3">* </span>YouTube Share URL:</td>
      					<td width="65%" align="left"><input type="text" id="video_url_2" name="video_url_2" value="<?php echo $video_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr class="imagevideoselection imagevideoselection3<?php echo $imagevideoselection3; ?>">
                    	<td width="35%" align="left"><span class="fontcolor3">* </span>Vimeo Share URL:</td>
      					<td width="65%" align="left"><input type="text" id="video_url_3" name="video_url_3" value="<?php echo $video_url; ?>" class="inputbox inputbox_size4" /></td>
                    </tr>
                    
                    <tr class="imagevideoselection imagevideoselection4<?php echo $imagevideoselection4; ?>">
                    	<?php if ($videopath != ""){ ?>       
                        <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Slider Image:</td>
                        <td width="65%" align="left" valign="top" class="tdpadding1">
                        <a class="htext" href="../sliderimage/<?php echo $videopath; ?>" target="_blank">Open Video</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;
                        <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','videopath','tbl_image_slider','id','sliderimage')">Delete Video</a>
                        </td>         
                        <?php }else{ ?>
                        <td width="35%" align="left" class="tdpadding1"><span class="fontcolor3">* </span>Select Video:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_video_ext; ?>]</td>
                        <td width="65%" align="left" class="tdpadding1"><input type="file" id="videopath" name="videopath" class="inputbox inputbox_size4" /></td> 
                        <?php } ?>
                    </tr>                   
                </table>
            </div>
        </td>
    </tr>
    
    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Display message over slider?</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="display_message" id="display_message" class="htext combobox_size6">
                <?php
                echo $sliderclass->get_slider_message_option_combo($display_message);
                ?>
            </select>
        </td>
    </tr> 
    
    <tr class="slidermessagefield<?php echo $slidermessagecss; ?>">
      <td width="" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Message 2:</td>
      <td width="" align="left"><input type="text" id="pdes" name="pdes" value="<?php echo $pdes; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr class="slidermessagefield<?php echo $slidermessagecss; ?>">
      <td width="" align="left"><span class="fontcolor3">* </span>Heading Text Color:</td>
      <td width="" align="left"><input type="text" id="fontcolor" name="fontcolor" value="<?php echo $fontcolor; ?>" class="inputbox inputbox_size4 jscolor" /></td>
    </tr>
    
    <tr class="slidermessagefield<?php echo $slidermessagecss; ?>">
      <td width="" align="left"><span class="fontcolor3">* </span>Message Text Color:</td>
      <td width="" align="left"><input type="text" id="fontcolor2" name="fontcolor2" value="<?php echo $fontcolor2; ?>" class="inputbox inputbox_size4 jscolor" /></td>
    </tr>
    
    <tr class="slidermessagefield<?php echo $slidermessagecss; ?>">
    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Link:</td>
    <td width="" align="left" valign="top" class="tdpadding1">
    <select name="link_type" id="link_type" class="htext combobox_size6">
     <option value="0" <?php if ($link_type == 0){ echo "selected";} ?>>None</option>
     <option value="1" <?php if ($link_type == 1){ echo "selected";} ?>>External Link</option>
     <option value="2" <?php if ($link_type == 2){ echo "selected";} ?>>Internal Link</option>
    </select>&nbsp;&nbsp;&nbsp;
    <span id="cmsoption3_a" style=" <?php echo $cms_style3_a; ?>"><span class="fontcolor3">* </span>Specify URL:&nbsp;&nbsp;<input type="text" id="page_url" name="page_url" class="inputbox inputbox_size4_b" value="<?php echo $page_url; ?>" /></span>
    <span id="cmsoption3_b" style=" <?php echo $cms_style3_b; ?>"><span class="fontcolor3">* </span>Select Page:&nbsp;&nbsp;    
    <select name="int_page_sel" id="int_page_sel" class="htext combobox_size6">
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
   
    <tr class="slidermessagefield<?php echo $slidermessagecss; ?>">
      <td width="" align="left"><span class="fontcolor3">* </span>Button Text:</td>
      <td width="" align="left"><input type="text" id="buttontext" name="buttontext" value="<?php echo $buttontext; ?>" class="inputbox inputbox_size4" /></td>
    </tr>
    
    <tr class="slidermessageposition<?php echo $slidermessagepositioncss; ?>">
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Select Message Display Position:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="text_position_id" id="text_position_id" class="text_position_id htext combobox_size6">
			<?php
            echo $sliderclass->get_text_position_combo($text_position_id);
            ?>
        </select></td>
    </tr>

    <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext combobox_size6">
                <option value="">Select</option>
                <?php
                $adm->get_commonstatus_combo($status_id);
                ?>
            </select></td>
    </tr>

    <?php if ($ms > 0){ ?>
       <tr>
        <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Sort Order:</td>
        <td width="" align="left" valign="top" class="tdpadding1"><input type="text" id="rank" name="rank" class="inputbox inputbox_size1" value="<?php echo $rank; ?>" /></td>
       </tr>
    <?php } ?>

    <tr>
        <td width="" align="left">&nbsp;</td>
        <td width="" align="left">
            <button type="button" class="butta saveslider"><span class="saveIcon butta-space">Save</span></button>
            <?php if ($ms == 0){ ?><button type="reset" class="butta"><span class="resetIcon butta-space">Reset</span></button><?php } ?>
        </td>
    </tr>
    </table>
</form>
<div class="clearfix"></div>
</div>

<div class="tabcontent tabcontent2 com_none">	
    <div class="clearfix"></div>
</div>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
	//form submit
	$(".whitetd").off("click", ".saveslider").on("click", ".saveslider", function(){
		var ms = parseInt($("#ms").val());
		if(!validate_text(document.ff.category_id,1,"Please select Category")){
			return false;
		}
		
		if(!validate_text(document.ff.name,1,"Please enter Title")){
			return false;
		}
		
		var video_url = "";
		var selected_opt = parseInt($("input[name=video_type]:radio:checked").val());
		if (selected_opt == 1){		
			//image
			if ($("#imgpath").length > 0){
				if (!image_validation(document.ff.imgpath.value, 'y', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
			}
		}else if (selected_opt == 4){		
			//MP4 Video
			if ($("#videopath").length > 0){
				if (!file_validation(document.ff.videopath.value, 'y', '<?php echo $cm->allow_video_ext; ?>')){ return false; }
			}
		}else{
			if(!validate_text(document.getElementById("video_url_" + selected_opt),1,"Please enter Video share URL")){
				return false;
			}
			video_url = $("#video_url_" + selected_opt).val();
		}		
		
		//display_message
		var display_message = parseInt($("#display_message").val());
		if (display_message == 1){
			if(!validate_text(document.ff.fontcolor,1,"Please enter Color Code in Hexadecimal format")){
				return false;
			}
			
			if(!validate_text(document.ff.fontcolor2,1,"Please enter Color Code in Hexadecimal format")){
				return false;
			}
			
			if (document.getElementById("link_type").value == 1){
				if(!validate_text(document.ff.page_url,1,"Please enter Link URL")){
					return false;
				}
			}else if (document.getElementById("link_type").value == 2){
					if(!validate_text(document.ff.int_page_sel,1,"Please select Page")){
						return false;
					}
			}
			
			if(!validate_text(document.ff.buttontext,1,"Please enter Button Text")){
				return false;
			}
		}

		if (ms > 0){
			if(!validate_numeric(document.ff.rank,1,"Please enter Sort Order")){
				return false;
			}
		}	  
		
		//start form submit
		var ms = $("#ms").val();
		var category_id = $("#category_id").val();
		var display_message = parseInt($("#display_message").val());
		var name = $("#name").val();
		var link_type = $("#link_type").val();
		var page_url = $("#page_url").val();
		var int_page_sel = $("#int_page_sel").val();
		var pdes = $("#pdes").val();
		var buttontext = $("#buttontext").val();
		var fontcolor = $("#fontcolor").val();
		var fontcolor2 = $("#fontcolor2").val();
		var text_position_id = $("#text_position_id").val();
		var status_id = $("#status_id").val();
		var rank = $("#rank").val();
		var oldrank = $("#oldrank").val();
		
		var fd = new FormData();
		fd.append("ms", ms);
		fd.append("category_id", category_id);
		fd.append("display_message", display_message);
		fd.append("name", name);
		fd.append("link_type", link_type);
		fd.append("page_url", page_url);
		fd.append("int_page_sel", int_page_sel);
		fd.append("pdes", pdes);
		fd.append("buttontext", buttontext);
		fd.append("fontcolor", fontcolor);
		fd.append("fontcolor2", fontcolor2);
		fd.append("text_position_id", text_position_id);
		fd.append("status_id", status_id);
		fd.append("rank", rank);
		fd.append("oldrank", oldrank);
		fd.append("video_type", selected_opt);
		fd.append("video_url", video_url);
		
		//
		if ($("#imgpath").length > 0) {
			fd.append("imgpath", $("#imgpath")[0].files[0]);
		}
		
		if ($("#videopath").length > 0) {
			fd.append("videopath", $("#videopath")[0].files[0]);
		}
		
		fd.append("inoption", 1);
		fd.append("az", 10);
		
		$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
		
		//call ajax
		var b_sURL = "onlyadminajax.php";
		$.ajax({
			url : b_sURL,
			type: "POST",
			data : fd,
			processData: false,
			contentType: false,
			success:function(data, textStatus, jqXHR){
				data = $.parseJSON(data);
				sliderid = data.sliderid;
				infotext = data.infotext;
				
				sliderid = parseInt(sliderid);
				if (sliderid > 0){
					location.href = "add_slider_image.php?id=" + sliderid;
					$("#progressBar").dialog("close");
				}else{
					$("#progressBar").dialog("close");
					$(".waitdiv").show();
					$(".waitmessage").html("<p>ERROR! Please try again</p>");
					messagedivhide();
					return false;
				}				
			}
		});
		
	});
	//end
	
	//link type selection
	$("#link_type").change(function(){
		set_link_type = $(this).val();
		if (set_link_type == 1){
			Show_MQ("cmsoption3_a");
			Hide_MQ("cmsoption3_b");
		}else if (set_link_type == 2){
			Show_MQ("cmsoption3_b");
			Hide_MQ("cmsoption3_a");
		}else{
			Hide_MQ("cmsoption3_a");
			Hide_MQ("cmsoption3_b");  
		}
	}); 
	//end
 
	//slider message
	$("#display_message").change(function(){
		var selectedval = parseInt($(this).val());
		   
		if(selectedval == 1){
			$(".slidermessagefield").show();
		}else{
			$(".slidermessagefield").hide();
		}
		
		if(selectedval > 0){
			$(".slidermessageposition").show();
		}else{
			$(".slidermessageposition").hide();
		}
	}); 
	//end
	
	//tab sectopn
	$(".toptablink").click(function(){
		if ( $( this ).hasClass( "active" ) ) {
			return;
		}
		
		var tabid = $(this).attr("tabid");
		$(".toptablink").removeClass("active");
		$(this).addClass("active");
		
		$(".tabcontent").addClass("com_none");
		$(".tabcontent" + tabid).removeClass("com_none");
		
		tabid = parseInt(tabid);
		if (tabid == 2){
			//image crop option display
			$("#progressBar").dialog({ modal: true, height: 250, closeOnEscape: false, open: function(event, ui) { $(this).closest('.ui-dialog').find('.ui-dialog-titlebar').hide(); }}); //Open the dialog
			
			//ajax call
			var slider_id = $(this).attr("sliderid");
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				slider_id:slider_id,
				inoption:2,
				az:10
			},
			function(data){
				$(".tabcontent2").html(data);
				$("#progressBar").dialog("close");
			});
		}
	});
	//end
	
	//image - video selection
	$("#category_id").change(function(){
		$(".imagevideo_error").hide();
		var cval = parseInt($(this).val());
		var wh_img = parseInt($("option:selected", this).attr('wh_img'));
		var wh_video = parseInt($("option:selected", this).attr('wh_video'));
		
		if (cval > 0){
			$(".imagevideoselection").addClass("com_none");
			if (wh_video > 0){
				$('input[name=video_type][value=1]').prop('checked', false);
				$('input[name=video_type][value=2]').prop('checked', false);
				$('input[name=video_type][value=3]').prop('checked', false);
				$('input[name=video_type][value=4]').prop('checked', true);
				$(".imagevideoselection4").removeClass("com_none");			
				return;
			}else{
				$('input[name=video_type][value=1]').prop('checked', true);
				$('input[name=video_type][value=4]').prop('checked', false);
				$(".imagevideoselection1").removeClass("com_none");			
				return;
			}
		}
	});
	
	$(".imagevideo").click(function(){
		$(".imagevideo_error").hide();
		
		var selected_opt = parseInt($("input[name=video_type]:radio:checked").val());
		var wh_img = parseInt($("#category_id option:selected").attr('wh_img'));
		var wh_video = parseInt($("#category_id option:selected").attr('wh_video'));
		
		if ((wh_img > 0) && (selected_opt == 4)){
			$(".imagevideo_error").show();
			$(".imagevideo_error").html("You can not add MP4 Video to this category. It has Image/YouTube URL/Vimeo URL already.");
			$('input[name=video_type][value=1]').prop('checked', true);
			$('input[name=video_type][value=4]').prop('checked', false);
			selected_opt = 1;
		}
		
		if (wh_video > 0){
			if ((selected_opt == 1) || (selected_opt == 2) || (selected_opt == 3)){
				$(".imagevideo_error").show();
				$(".imagevideo_error").html("You can not add Image/YouTube URL/Vimeo URL to this category. It has Video already.");
				$('input[name=video_type][value=1]').prop('checked', false);
				$('input[name=video_type][value=2]').prop('checked', false);
				$('input[name=video_type][value=3]').prop('checked', false);
				$('input[name=video_type][value=4]').prop('checked', true);
				selected_opt = 4;
			}
		}

		$(".imagevideoselection").addClass("com_none");
		if(selected_opt == 4){
			//MP4 video
			$(".imagevideoselection" + selected_opt).removeClass("com_none");
		}else{
			//Image/YouTube URL/Vimeo URL
			$(".imagevideoselection" + selected_opt).removeClass("com_none");
		}		
	});
	//end
});
</script>

<?php
include("foot.php");
?>