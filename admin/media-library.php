<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");
$link_name = "Media Library";

$medialibraryclass->get_all_media();

$icclass = "leftsettingsicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
var config = {
	support : "image/jpg,image/png,image/jpeg,image/gif,e/e,application/pdf,application/x-pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/x-zip-compressed,application/zip,video/mpeg4,video/mp4,video/m4v,video/x-mp4,video/x-m4v,video/avi,video/quicktime",		// Valid file formats
	form: "demoFiler",					// Form ID
	dragArea: "dragAndDropFiles",		// Upload Area ID
	uploadUrl: "media-upload.php"				// Server side upload url
}

$(document).ready(function(){
	initMultiUploader(config);
});

function callafter(){
	$(".waitdiv").show();
	$(".waitmessage").html('Please wait....');
	
	var b_sURL = "onlyadminajax.php";
	$.post(b_sURL,
	{ 	
		az:26,
		inoption:3,
		dataType: "json"
	},
	function(data){
		data = $.parseJSON(data);
		returntext = data.returntext;
		$(".mediafileslistholderleft").html(returntext);
		$(".waitdiv").hide();
		$(".waitmessage").html('');
		
		$(".mediafilechoose").removeClass("active");
		$(".mediafilechoose0").addClass("active");
	});
}
</script>

<div id="dragAndDropFiles" class="uploadArea spacer1">
    <h1>Drop Images/Files Here</h1>
</div>
<div class="formholder">
    <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
        <input type="hidden" value="0" id="ms" name="ms" />
        <input type="file" name="multiUpload" id="multiUpload" class="inputbox" size="65" multiple />
        <button type="submit" name="submitHandler" id="submitHandler" class="butta buttonUpload"><span class="uploadIcon butta-space">Upload</span></button>
        <br />[Allowed file types: <?php echo $cm->allow_image_ext; ?>, <?php echo $cm->allow_video_ext; ?>, <?php echo $cm->allow_attachment_ext; ?>]
    </form>
</div>

<?php
echo $medialibraryclass->display_all_media_main();
?>


<?php
include("foot.php");
?>