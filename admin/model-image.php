<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Model Image List";

$make_id = round($_GET["make_id"], 0);
$result = $yachtclass->check_manufacturer_exist($make_id, 0, 1, 0);
$row = $result[0];
$makename = $cm->filtertextdisplay($row["name"]);

$ms = round($_GET["id"], 0);
$result = $modelclass->check_model($make_id, $ms);
$row = $result[0];
$modelname = $row["name"];
$catid = $row["category_id"];
$cat_name = $cm->get_common_field_name('tbl_category', 'name', $catid);
$finalname = $makename . ' ' . $modelname . ' ' . $cat_name;

$photocategoryid = round($_GET["photocategoryid"], 0);

$crop_option = 1;
$rotateimage = 0;

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
    var config = {
        support : "image/jpg,image/png,image/jpeg,image/gif",		// Valid file formats
        form: "demoFiler",					// Form ID
        dragArea: "dragAndDropFiles",		// Upload Area ID
        uploadUrl: "modelphotoupload.php"				// Server side upload url
    }
    $(document).ready(function(){
        initMultiUploader(config);

        $(".whitetd").on("click", ".delyachtimg", function(){
            var c = confirm("Are you sure you want to delete this Record?");
            if (c){
                var yval = $(this).attr('yval');
                var b_sURL = "onlyadminajax.php";
                $.post(b_sURL,
                {
                    imid:yval,
					inoption:2,
                    az:700
                },
                function(data){
                    if (data == "y"){
                        $('#item-' + yval).addClass('com_none');
                    }
                });
            }
        });
		
		//sortable
		$(".whitetd").on("mouseover", "#recordsortable", function(){
			$( this ).sortable({
				update: function (event, ui) {	
					var sortdata = $(this).sortable('serialize');
					var b_sURL = "onlyadminajax.php";
					$.post(b_sURL,
					{				
						data:sortdata,
						inoption:3,
						az:700
					});	
					
					var newrank = 1;
					$("#recordsortable li").each(function(){
						if ($(".sortv", this).length > 0){			
							$(".sortv", this).val(newrank);
							$(".imgrank", this).html(newrank);
							newrank++;
						}
					});
				}
			});
		});
				
		//rotate image
		$(".whitetd").off("click", ".imgrotate").on("click", ".imgrotate", function(){
			var yval = $(this).attr("yval");			
			var v = $(this).attr("v");
			var c = $(this).attr("c");
			var ko = $(this).attr("ko");
			var ycat = $(this).attr("ycat");
						
			if($("#crop_option" + c).is(':checked')){
				var hardcrop = 1;
			}else{
				var hardcrop = 0;
			}
			
			ko = parseInt(ko);
			
			//overlay
			$(".waitdiv").show();
			
			if (ko == 1){
			
				$(".waitmessage").html('<p>Please wait....</p>');
				var b_sURL = "onlyadminajax.php";
				$.post(b_sURL,
				{
					imid:yval,
					v:v,
					hardcrop:hardcrop,
					ycat:ycat,
					inoption:4,
					az:700
				},
				function(data){					
					if (data != ''){						
						$(".imglist" + c).attr('src', data);
					}
					$(".waitdiv").hide();
				});
				
			}else{
				$(".waitmessage").html('<p>Rotation can not be done. Original image removed.</p>');
				messagedivhide();
			}
		});
		
		//delete original image only		
		$(".whitetd").off("click", ".delete_original").on("click", ".delete_original", function(){
			var yval = $(this).attr("yval");
			var c = $(this).attr("c");
			
			//overlay
			$(".waitdiv").show();
			$(".waitmessage").html('<p>Please wait....</p>');
				
			var b_sURL = "onlyadminajax.php";
			$.post(b_sURL,
			{
				imid:yval,
				subsection:5,
				az:700
			},
			function(data){
				$(".delete_original" + c).hide();
				$(".imgrotate" + c).addClass("img_inactive");
				$("#crop_option" + c).addClass("com_none");
				$(".imgrotate" + c).attr("ko", 0);
           
				$(".waitdiv").hide();
			});
		});
    });

    function callafter(){		
        var b_sURL = "onlyadminajax.php";
        $.post(b_sURL,
            {
                ms:'<?php echo $ms; ?>',
				make_id:'<?php echo $make_id; ?>',
				photocategoryid:'<?php echo $photocategoryid; ?>',
				inoption:1,
                az:700
            },
            function(data){
                if (data != ''){
                    $("#yimholder").html(data);
                }
            });
    }
</script>
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td align="left">
            <h2><?php echo $finalname; ?></h2>
        </td>
    </tr>
</table>

<table border="0" width="95%" cellspacing="0" cellpadding="0">
    <tr>
    	<td width="" height="1"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
    <tr>
    	<td align="left" valign="top">
        <?php echo $modelclass->get_model_photo_category_tab($photocategoryid, $make_id, $ms); ?>
        </td>
    </tr>
    <tr>
    	<td width="" height="10"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>

    <div id="dragAndDropFiles" class="uploadArea">
        <h1>Drop Images Here</h1>
    </div>

    <div class="formholder">
        <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
            <input type="hidden" value="<?php echo $make_id; ?>" id="make_id" name="make_id" />
            <input type="hidden" value="<?php echo $photocategoryid; ?>" id="photocategoryid" name="photocategoryid" />
            
            <p>Hard crop image?&nbsp;&nbsp;<input class="checkbox" type="checkbox" id="crop_option" name="crop_option" value="1" <?php if ($crop_option == 1){?> checked="checked"<?php } ?> /> Yes</p>
            <p>Rotate Image?&nbsp;&nbsp;
            <input class="" type="radio" id="rotateimage0" name="rotateimage" value="0" <?php if ($rotateimage == 0){?> checked="checked"<?php } ?> /> None
            <input class="" type="radio" id="rotateimage1" name="rotateimage" value="90" <?php if ($rotateimage == 90){?> checked="checked"<?php } ?> /> 90 Degree ACW
            <input class="" type="radio" id="rotateimage2" name="rotateimage" value="270" <?php if ($rotateimage == 270){?> checked="checked"<?php } ?> /> 90 Degree CW
            </p>
            
            <input type="file" name="multiUpload" id="multiUpload" class="inputbox" size="65" multiple />
            <button type="submit" name="submitHandler" id="submitHandler" class="butta buttonUpload"><span class="uploadIcon butta-space">Upload</span></button>
            
            <?php
			if ($photocategoryid == 4){
			?>
            &nbsp;&nbsp;[w: <?php echo $modelclass->model_im_width_s4; ?>px, h: <?php echo $modelclass->model_im_height_s4; ?>px]
            <?php
			}elseif ($photocategoryid == 3){
			?>
            &nbsp;&nbsp;[w: <?php echo $modelclass->model_im_width_s3; ?>px, h: <?php echo $modelclass->model_im_height_s3; ?>px]
            <?php
			}elseif ($photocategoryid == 2){
			?>
            &nbsp;&nbsp;[w: <?php echo $modelclass->model_im_width_s2; ?>px, h: <?php echo $modelclass->model_im_height_s2; ?>px]
            <?php
			}else{
			?>
            &nbsp;&nbsp;[w: <?php echo $modelclass->model_im_width_s1; ?>px, h: <?php echo $modelclass->model_im_height_s1; ?>px]
            <?php
			}
			?>            
            &nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]
        </form>
    </div>

    <form method="post" action="yacht_image_sub.php" id="yacht_im_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <input type="hidden" value="<?php echo $make_id; ?>" id="postdata1" name="postdata1" />
        <div class="singleblock">
            <div class="singleblock_heading"><span>Model Image</span></div>
            <div id="yimholder" class="singleblock_box">
                <?php
                echo $modelclass->model_image_display_list($ms, $make_id, $photocategoryid);
                ?>
            </div>
        </div>
        <div class="singleblock">
            <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
                <tr>
                    <td width="" align="right">
                        <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>
                    </td>
                </tr>
            </table>
        </div>
    </form>    
<?php
include("foot.php");
?>