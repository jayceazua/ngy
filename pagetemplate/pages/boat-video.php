<?php
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$isdashboard = 1;

$yachtform = 1;
$multiuploadim = 1;
$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$lno = round($_REQUEST['lno'], 0);
$result = $yachtclass->check_yacht_with_return($lno, 2);
$found = count($result);
$row = $result[0];
$ms = $row["id"];

$link_name = 'Boat Video Listing - ' . $cm->filtertextdisplay($lno);

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
            support : "video/mpeg4,video/mp4,video/m4v,video/x-mp4,video/x-m4v,video/avi,video/quicktime",		// Valid file formats
            form: "demoFiler",					// Form ID
            dragArea: "dragAndDropFiles",		// Upload Area ID
            uploadUrl: "<?php echo $cm->folder_for_seo; ?>?fcapi=videoupload"	// Server side upload url
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
                            iop:1,
                            az:31
                        },
                        function(data){
                            if (data == "y"){
                                $('.i' + yval).addClass('com_none');
                            }
                        });
                }
            });
			
			//add youtube video - video_ff
			$("#video_ff").submit(function(){
				var all_ok = "y";
				if (!field_validation_border("link_url", 1, 1)){ all_ok = "n"; }
				if (all_ok == "n"){
					return false;
				}
				return true;
			});
        });

        function callafter(){
            var b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    ms:'<?php echo $ms; ?>',
                    iop:1,
                    az:32
                },
                function(data){
                    $("#yimholder").html('')
                    if (data != ''){
                        $("#yimholder").html(data);
                    }
                });
        }
    </script>
    
    <div class="uploadleft">
        <div id="dragAndDropFiles" class="uploadArea">
            <h1>Drop Videos Here</h1>
        </div>
    
        <div class="formholder">
            <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
                <input type="file" name="multiUpload" id="multiUpload" class="inputbox" size="65" multiple />
                <button type="submit" name="submitHandler" id="submitHandler" class="butta buttonUpload"><span class="uploadIcon butta-space">Upload</span></button>
                <br />[Allowed file types: <?php echo $cm->allow_video_ext; ?>]
            </form>
        </div>
   </div>
   
   <div class="uploadright">
   		<h3>Add Video Link</h3>
        <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="video_ff" name="ff" enctype="multipart/form-data">
        	<input type="hidden" value="<?php echo $ms; ?>" name="ms" />
            <input type="hidden" id="fcapi" name="fcapi" value="boatvideoadd" />
            <ul class="form">
            	<li>
                    <p>Video Title</p>
                    <input type="text" class="input" id="name" name="name" value="" />
                </li>
                
                <li>
                    <p>Video URL</p>
                    <input type="text" class="input" id="link_url" name="link_url" value="" />
                </li>
                
                <li>
                    <p>Video Type</p>
                    <select name="video_type" id="video_type" class="my-dropdown2">
                        <option value="">Select</option>
                        <option value="1">YouTube</option>
                        <option value="3">Vimeo</option>
                    </select>
                </li>
                
                <li class="submit">
                    <input type="submit" value="Submit" class="button" />
                </li>
            </ul>
        </form>
   
   </div>
   <div class="clear"></div>     

    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="yacht_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <input type="hidden" id="fcapi" name="fcapi" value="boatvideosave" />
        <div class="singleblock">
            <div class="singleblock_heading"><span>Yacht Video</span></div>
            <div id="yimholder" class="singleblock_box">
                <?php
                echo $yachtclass->yacht_video_display_list($ms, 1);
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