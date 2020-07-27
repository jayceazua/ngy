<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Charter Boat Image List";
$ms = round($_GET["id"], 0);
$result = $charterboatclass->check_charterboat($ms);
$row = $result[0];
$boat_name = $row["boat_name"];
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
        uploadUrl: "charterboatphotoupload.php"				// Server side upload url
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
                    az:800
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
						az:800
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
					inoption:4,
					az:800
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
				az:800
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
				inoption:1,
                az:800
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
            <h2><?php echo $boat_name; ?></h2>
        </td>
    </tr>
</table>

    <div id="dragAndDropFiles" class="uploadArea">
        <h1>Drop Images Here</h1>
    </div>

    <div class="formholder">
        <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
            
            <p>Hard crop image?&nbsp;&nbsp;<input class="checkbox" type="checkbox" id="crop_option" name="crop_option" value="1" <?php if ($crop_option == 1){?> checked="checked"<?php } ?> /> Yes</p>
            <p>Rotate Image?&nbsp;&nbsp;
            <input class="" type="radio" id="rotateimage0" name="rotateimage" value="0" <?php if ($rotateimage == 0){?> checked="checked"<?php } ?> /> None
            <input class="" type="radio" id="rotateimage1" name="rotateimage" value="90" <?php if ($rotateimage == 90){?> checked="checked"<?php } ?> /> 90 Degree ACW
            <input class="" type="radio" id="rotateimage2" name="rotateimage" value="270" <?php if ($rotateimage == 270){?> checked="checked"<?php } ?> /> 90 Degree CW
            </p>
            
            <input type="file" name="multiUpload" id="multiUpload" class="inputbox" size="65" multiple />
            <button type="submit" name="submitHandler" id="submitHandler" class="butta buttonUpload"><span class="uploadIcon butta-space">Upload</span></button>
            &nbsp;&nbsp;[w: <?php echo $charterboatclass->boat_im_width; ?>px, h: <?php echo $charterboatclass->boat_im_height; ?>px]&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]
        </form>
    </div>

    <form method="post" action="yacht_image_sub.php" id="yacht_im_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <div class="singleblock">
            <div class="singleblock_heading">
            	<span>Boat Image</span>               
            </div>
            <div id="yimholder" class="singleblock_box">
                <?php
                echo $charterboatclass->charterboat_image_display_list($ms);
                ?>
            </div>
        </div>       
    </form>    
<?php
include("foot.php");
?>