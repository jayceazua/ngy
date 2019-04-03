<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Yacht Attachment List";
$ms = round($_GET["id"], 0);
$yachtclass->check_yacht($ms);
$yacht_name = $yachtclass->yacht_name($ms);
$crop_option = 1;

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
    var config = {
        support : "e/e,application/pdf,application/x-pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/x-zip-compressed,application/zip",		// Valid file formats
        form: "demoFiler",					// Form ID
        dragArea: "dragAndDropFiles",		// Upload Area ID
        uploadUrl: "mfileupload.php"				// Server side upload url
    }
    $(document).ready(function(){
        initMultiUploader(config);

        $(".whitetd").on("click", ".delfiles", function(){
            var c = confirm("Are you sure you want to delete this Record?");
            if (c){
                var yval = $(this).attr('yval');
                var b_sURL = bkfolder + "includes/ajax.php";
                $.post(b_sURL,
                {
                    imid:yval,
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
		$(".whitetd").on("mouseover", "#recordsortable tbody", function(){
			$( this ).sortable({
				update: function (event, ui) {	
					var sortdata = $(this).sortable('serialize');
					var b_sURL = bkfolder + "includes/ajax.php";						
					$.post(b_sURL,
					{				
						data:sortdata,
						subsection:4,
						az:1
					});	
					
					var newrank = 1;
					$("#recordsortable tbody tr").each(function(){	
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
                az:2,
				subsection:2
            },
            function(data){
                if (data != ''){
                    $("#yfileholder").html(data);
                }
            });
    }
</script>
<table border="0" width="95%" cellspacing="0" cellpadding="5" class="htext">
    <tr>
        <td align="left">
            <h2><?php echo $yacht_name; ?></h2>
        </td>
    </tr>
</table>

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

    <form method="post" action="yacht_attachment_sub.php" id="yacht_attachment_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <div class="singleblock">
            <div class="singleblock_heading"><span>File Lists</span></div>
            <div id="yfileholder" class="singleblock_box">
                <?php
                echo $yachtclass->yacht_attachment_display_list($ms);
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