<?php
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$yachtform = 1;
$multiuploadim = 1;
$isdashboard = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$lno = round($_REQUEST['lno'], 0);
$result = $yachtclass->check_yacht_with_return($lno, 2);
$found = count($result);
$row = $result[0];
$ms = $row["id"];
$link_name = 'Boat Attachment File(s) - ' . $cm->filtertextdisplay($lno);

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
		support : "e/e,application/pdf,application/x-pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/x-zip-compressed,application/zip",		// Valid file formats
		form: "demoFiler",					// Form ID
		dragArea: "dragAndDropFiles",		// Upload Area ID
		uploadUrl: "<?php echo $cm->folder_for_seo; ?>?fcapi=attachemntfileupload"		// Server side upload url
	}
	$(document).ready(function(){
		initMultiUploader(config);

		$(".main").on("click", ".delfiles", function(){
			var c = confirm("Are you sure you want to delete this Record?");
			if (c){
				var yval = $(this).attr('yval');
				var b_sURL = bkfolder + "includes/ajax.php";
				$.post(b_sURL,
					{
						imid:yval,
						iop:1,
						subsection:3,
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
						subsection:4,
						iop:1,
						az:1
					});	
					
					var newrank = 1;
					$("#recordsortable li").each(function(){	
						if ($(".sortv", this).length > 0){			
							$(".sortv", this).val(newrank);
							newrank++;
						}
					});
				}
			});
		});			
	});

	function callafter(){
		var b_sURL = bkfolder + "includes/ajax.php";
		$.post(b_sURL,
			{
				ms:'<?php echo $ms; ?>',
				iop:1,
				az:2,
				subsection:2
			},
			function(data){
				$("#yfileholder").html('')
				if (data != ''){
					$("#yfileholder").html(data);
				}
			});
	}
</script>

    <div id="dragAndDropFiles" class="uploadArea">
        <h1>Drop Files Here</h1>
    </div>

    <div class="formholder">
        <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
            <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />            
            <input type="file" name="multiUpload" id="multiUpload" class="inputbox" size="65" multiple />
            <button type="submit" name="submitHandler" id="submitHandler" class="butta buttonUpload"><span class="uploadIcon butta-space">Upload</span></button>
            <br />[Allowed file types: <?php echo $cm->allow_attachment_ext; ?>]
        </form>
    </div>

    <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="yacht_attachment_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <input type="hidden" id="fcapi" name="fcapi" value="boatattachmentsave" />
        <div class="singleblock">
            <div class="singleblock_heading"><span>Boat Attachment File Lists</span></div>
            <div id="yfileholder" class="singleblock_box">
                <?php
                echo $yachtclass->yacht_attachment_display_list($ms, 1);
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