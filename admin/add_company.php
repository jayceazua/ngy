<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");
$ms = round($_GET["id"], 0);

if ($ms <= 0){
	$rback = $_SESSION["bck_pg"];
	header('Location:'.$rback);
	exit;
}else{
	$sql="select * from tbl_company where id = '". $cm->filtertext($ms) ."'";
	$result = $db->fetch_all_array($sql); 	
	$row = $result[0];
	$id = $row['id'];	
	$cname = htmlspecialchars($row['cname']);
	$website_url = htmlspecialchars($row['website_url']);
	$logo_imgpath = htmlspecialchars($row['logo_imgpath']);
	$about_company = htmlspecialchars($row['about_company']);
	$enable_custom_label = $row['enable_custom_label'];
	$status_id = $row['status_id'];

	$link_name = "Modify Company Profile";
}
$icclass = "leftusericon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
  $("#company_ff").submit(function(){
      if(!validate_text(document.ff.cname,1,"Please enter Company Name")){
		  return false;
	  }
	 
	  var logo_imgpath = "<?php echo $logo_imgpath; ?>";
	  if (logo_imgpath == ""){
		  if (!image_validation(document.ff.logo_imgpath.value, 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
	  }
	  
      return true; 
 });
 
  //add new record
 $(".addrowasso").click(function(){
		var total_associations = $('#total_associations').val();
		total_associations = eval(total_associations);
		total_associations = total_associations + 1;
		
		b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
		{ 	
			az:41,
			dataType: 'json'
		},
		function(data){			
			data = $.parseJSON(data);
			industryassociation_data = data[0].industryassociation_data;
						
			var added_text = '<tr class="assorowind'+ total_associations +'">';
				added_text += '<td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Industry Association:</td>';
				added_text += '<td width="" align="left" valign="top" class="tdpadding1"><select name="industry_associations_id'+ total_associations +'" id="industry_associations_id'+ total_associations +'" class="combobox_size4 htext"><option value="">Select</option>'+ industryassociation_data +'</select></td>';
				added_text += '<td width="25" align="left" valign="top" class="tdpadding1"><a class="asso_del" title="Delete Record" href="javascript:void(0);" isdb="0" yval="'+ total_associations +'" asso_id=""><img src="images/del.png" title="Delete Record" alt="Delete Record"></a></td>';
			added_text += '</tr>';
			
			$("#assoholder").append(added_text);
		    $('#total_associations').val(total_associations);			
		});		
	});
	
	//delete
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
					az:42
				});
			}
		}		
	});
	
	//sortable
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
				az:43
			});			
		}
	});

});
</script>

<form method="post" action="company_sub.php" id="company_ff" name="ff" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $ms; ?>" name="ms" />   
    <div class="singleblock">
        <div class="singleblock_heading"><span>General Information</span></div>
        <div class="singleblock_box">
        	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                    <td width="35%" align="left"><span class="fontcolor3">* </span>Company Name:</td>
                    <td width="65%" align="left"><input type="text" id="cname" name="cname" value="<?php echo $cname; ?>" class="inputbox inputbox_size1" /></td>
                </tr>
                
                <tr>
                    <td width="" align="left"><span class="mandt_color">&nbsp;&nbsp;</span>Website URL:</td>
                    <td width="" align="left"><input type="text" id="website_url" name="website_url" value="<?php echo $website_url; ?>" class="inputbox inputbox_size1" /></td>
                </tr>
                
                <?php if ($logo_imgpath != ""){ ?>
                    <tr>
                        <td align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Selected Logo Image:</td>
                        <td align="left" valign="top" class="tdpadding1">
                            <img src="../userphoto/<?php echo $logo_imgpath; ?>" border="0" width="100" /><br />
                            <a class="htext" href="javascript:delete_image('<?php echo $ms; ?>','logo_imgpath','tbl_company','id','userphoto')">Delete Image</a>
                        </td>
                    </tr>
                <?php }else{ ?>
                    <tr>
                        <td align="left" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Select Logo Image [w: <?php echo $cm->logo_im_width; ?>px, h: <?php echo $cm->logo_im_height; ?>px]:<br />&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                        <td align="left" class="tdpadding1"><input type="file" id="logo_imgpath" name="logo_imgpath" class="inputbox" size="65" /></td>
                    </tr>
                <?php } ?>
                
                <tr>
                    <td align="left" valign="top"><span class="fontcolor3">&nbsp;&nbsp;</span>About Company:</td>
                    <td align="left" valign="top"><textarea name="about_company" id="about_company" rows="1" cols="1" class="textbox textbox_size5"><?php echo $about_company;?></textarea> </td>
                </tr>
            </table>
        </div>
    </div>   
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Industry Association</span></div>
        <div class="singleblock_box">
        	<?php
				echo $yachtclass->industryassociation_display_list($ms, 1);			
			?>
        </div>
    </div>
    
    <div class="singleblock">
        <div class="singleblock_heading"><span>Admin Only</span></div>
        <div class="singleblock_box">
        	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                    <td width="35%" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">&nbsp;&nbsp;</span>Enable Custom Label?</td>
                    <td width="65%" align="left" valign="top" class="tdpadding1"><input class="checkbox" type="checkbox" id="enable_custom_label" name="enable_custom_label" value="1" <?php if ($enable_custom_label == 1){?> checked="checked"<?php } ?> /> Yes</td>
                </tr>
            
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Display Status:</td>
                    <td width="" align="left" valign="top" class="tdpadding1"><select name="status_id" id="status_id" class="htext">
                            <option value="">Select</option>
                            <?php
                            $adm->get_commonstatus_combo($status_id);
                            ?>
                        </select></td>
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
</form>
<?php
include("foot.php");
?>