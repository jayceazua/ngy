<?php
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$isdashboard = 1;

$yachtform = 1;
$multiuploadim = 1;
$crop_option = 1;
$rotateimage = 0;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$lno = round($_REQUEST['lno'], 0);
$result = $yachtclass->check_yacht_with_return($lno, 2);
$found = count($result);
$row = $result[0];
$ms = $row["id"];

$link_name = 'Boat Image Listing - ' . $cm->filtertextdisplay($lno);

$yachtclass->remove_yach_search_var();
$atm1 = $link_name;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 1, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
echo $html_start;
?>
    <script language="javascript" type="text/javascript">
        var config = {
            support : "image/jpg,image/png,image/jpeg,image/gif",		// Valid file formats
            form: "demoFiler",					// Form ID
            dragArea: "dragAndDropFiles",		// Upload Area ID
            uploadUrl: "<?php echo $cm->folder_for_seo; ?>?fcapi=mupload"	// Server side upload url
        }
        $(document).ready(function(){
            initMultiUploader(config);

            $(".main").on("click", ".delyachtimg", function(){
                var c = confirm("Are you sure you want to delete this Record?");
                if (c){
                    var yval = $(this).attr('yval');
                    var b_sURL = bkfolder + "includes/ajax.php";
                    $.post(b_sURL,
                        {
                            imid:yval,
							subsection:1,
                            iop:1,
                            az:1
                        },
                        function(data){
                            if (data == "y"){
                                $('#item-' + yval).addClass('com_none');
                            }
                        });
                }
            });
			
			//sortable
			$(".main").on("mouseover", "#recordsortable", function(){
				$( this ).sortable({
					update: function (event, ui) {	
						var sortdata = $(this).sortable('serialize');
						var b_sURL = bkfolder + "includes/ajax.php";						
						$.post(b_sURL,
						{				
							data:sortdata,
							subsection:2,
							iop:1,
							az:1
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

			//display change
			$(".yimchange").click(function(){
				var dval = $(this).attr("dval");
				$(".yimchange").removeClass("active");
				$(this).addClass("active");
				
				if (dval == 2){
					$(".imagedisplay").removeClass("gridview");
				}else{
					$(".imagedisplay").addClass("gridview");
				}	
				$(document.body).trigger("sticky_kit:recalc");			
			});
        });
		
		//rotate image
		$(".main").off("click", ".imgrotate").on("click", ".imgrotate", function(){
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
			
			if (ko == 1){
				//overlay
				dispay_wait_msg("<p>Please wait!!!!!</p>");
				var b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
				{
					imid:yval,
					v:v,
					hardcrop:hardcrop,
					subsection:5,
					az:1
				},
				function(data){
					if (data != ''){
						$(".imglist" + c).attr('src', data);
					}
					hide_wait_msg();
				});
				
			}else{
				errormessagepop("Rotation can not be done. Original image removed.");				
			}
		});
		
		//delete original image only		
		$(".main").off("click", ".delete_original").on("click", ".delete_original", function(){
			var yval = $(this).attr("yval");
			var c = $(this).attr("c");
			
			//overlay
			dispay_wait_msg("<p>Please wait!!!!!</p>");
				
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{
				imid:yval,
				subsection:6,
				az:1
			},
			function(data){
				$(".delete_original" + c).hide();
				$(".imgrotate" + c).addClass("img_inactive");
				$("#crop_option" + c).addClass("com_none");
				$(".imgrotate" + c).attr("ko", 0);
           
				hide_wait_msg();
			});
		});

        function callafter(){
            var b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    ms:'<?php echo $ms; ?>',
                    iop:1,
                    az:2
                },
                function(data){
                    $("#yimholder").html('')
                    if (data != ''){
                        $("#yimholder").html(data);
                    }
                });
        }
    </script>

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
            &nbsp;&nbsp;[w: <?php echo $cm->yacht_im_width; ?>px, h: <?php echo $cm->yacht_im_height; ?>px]&nbsp;&nbsp;[Allowed file types: <?php echo $cm->allow_image_ext; ?>]
        </form>
    </div>

    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="yacht_im_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <input type="hidden" id="fcapi" name="fcapi" value="boatimagesave" />
        <div class="singleblock">
            <div class="singleblock_heading">
            	<span>Boat Image</span>
                <ul class="galleryviewoption">	
					<li><a href="javascript:void(0);" dval="1" title="Grid view" class="yimchange icon grid active">Grid view</a></li>
					<li><a href="javascript:void(0);" dval="2" title="List view" class="yimchange icon list">List view</a></li>
				</ul>
            </div>
            <div id="yimholder" class="singleblock_box singleblock_box_h">
                <?php
                echo $yachtclass->yacht_image_display_list($ms, 1);
                ?>
            </div>
        </div>
        <div class="singleblock">
            <input type="submit" value="Save" class="button" />
        </div>
    </form>

<?php
echo $html_end;
include($bdr."includes/foot.php");
?>