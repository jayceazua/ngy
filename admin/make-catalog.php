<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Sidebar Option for Manufacturer";

$makear = $ymclass->get_assign_manufacturer_list_raw();
$makear = json_decode($makear);

$icclass = "leftlistingicon";
include("head.php");
?>


<table border="0" width="95%" cellspacing="0" cellpadding="0" class="htext">
    <tr>
        <td height="20"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<?php if ($_SESSION["postmessage"] != ""){ ?>
	<table border="0" width="95%" cellspacing="0" cellpadding="4" class="htext" align="center">
     <tr>
       <td width="100%" align="center"><span class="fontcolor3">
	  		 
		 <?php if ($_SESSION["postmessage"] == "up"){ ?>
		 Record updated successfully.
		 <?php } ?> 
		
	   </span></td>
	 </tr>
	</table>
<?php $_SESSION["postmessage"] = ""; } ?>

<form method="post" id="make-catalog-ff" name="make-catalog-ff" enctype="multipart/form-data">
<?php
$counter = 0;
foreach($makear as $vrow){
	$manufacturer_id = $vrow->id;
	$manufacturer_name = $vrow->name;
	
	$makecontent_ar = $cm->get_table_fields("tbl_manufacturer_catalog", "catalog_link, imgpath", $manufacturer_id, "manufacturer_id");
	$catalog_link = $makecontent_ar[0]["catalog_link"];
	$imgpath = $makecontent_ar[0]["imgpath"];
?>
	<div class="singleblock clearfixmain">
    	<div class="singleblock_heading"><span><?php echo $manufacturer_name; ?></span></div>
    	<div class="singleblock_box clearfixmain">
        	<table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
            	<tr>
                    <td width="35%" align="left">Catalog Link:</td>
                    <td width="65%" align="left">
                        <input type="hidden" value="<?php echo $manufacturer_id; ?>" id="manufacturer_id<?php echo $counter; ?>" name="manufacturer_id<?php echo $counter; ?>" />
                        <input type="text" id="catalog_link<?php echo $counter; ?>" name="catalog_link<?php echo $counter; ?>" value="<?php echo $catalog_link; ?>" class="inputbox inputbox_size4" />
                    </td>
                </tr>
                
				<?php if ($imgpath != ""){ ?>
                <tr>
                    <td width="" align="left" valign="top" class="tdpadding1">Selected Sidebar Image:</td>
                    <td width="" align="left" valign="top" class="tdpadding1">
                    <img src="../manufacturerimage/sidebar/<?php echo $imgpath; ?>" border="0" width="100" /><br />
                    <a class="htext" href="javascript:delete_image('<?php echo $manufacturer_id; ?>','imgpath','tbl_manufacturer_catalog','manufacturer_id','manufacturerimage/sidebar')">Delete Image</a>
                </td>
                </tr>
                <?php }else{ ?>
                <tr>
                    <td width="" align="left" class="tdpadding1">Select Sidebar Image [w: <?php echo $cm->menu_im_width; ?>px, h: <?php echo $cm->menu_im_height; ?>px]:<br />[Allowed file types: <?php echo $cm->allow_image_ext; ?>]</td>
                    <td width="" align="left" class="tdpadding1"><input type="file" id="imgpath<?php echo $counter; ?>" name="imgpath<?php echo $counter; ?>" class="inputbox inputbox_size4" /></td>
                </tr>
                <?php } ?>
                
                <tr>
                    <td width="" align="right" colspan="2"><button counter="<?php echo $counter; ?>" type="button" class="butta savemake"><span class="saveIcon butta-space">Save</span></button></td>
                </tr>
            </table>
        </div>
    </div>
<?php
	$counter++;
}
?>
</form>

<div id="progressBar"><strong>PROCESSING</strong><br /><br /><em>Note: Processing may take a few minutes</em><br /><img src="images/ajax-loader.gif" alt="uploading image" style="margin-top: 15px;" /></div>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$(".whitetd").off("click", ".savemake").on("click", ".savemake", function(){
		var counter = $(this).attr("counter");
		
		var img_id = "imgpath" + counter;
		if ($("#"+ img_id).length > 0) {
			if (!image_validation($("#"+ img_id).val(), 'n', '<?php echo $cm->allow_image_ext; ?>')){ return false; }
		}
		
		//submit
		var manufacturer_id = $("#manufacturer_id" + counter).val();
		var catalog_link = $("#catalog_link" + counter).val();
		
		var fd = new FormData();
		fd.append("manufacturer_id", manufacturer_id);
		fd.append("catalog_link", catalog_link);
		
		if ($("#imgpath"+ counter).length > 0) {
			fd.append("imgpath", $("#imgpath" + counter)[0].files[0]);
		}
		
		fd.append("inoption", 1);
		fd.append("az", 11);
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
				location.reload();							
			}
		});
	});
});
</script>
<?php
include("foot.php");
?>