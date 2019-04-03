<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
$def_meta_collect = "n";
include("pageset.php");

$link_name = "Yacht Video List";
$ms = round($_GET["id"], 0);
$yachtclass->check_yacht($ms);
$yacht_name = $yachtclass->yacht_name($ms);

$icclass = "leftlistingicon";
include("head.php");
?>
<script language="javascript" type="text/javascript">
    var config = {
        support : "video/mpeg4,video/mp4,video/m4v,video/x-mp4,video/x-m4v,video/avi,video/quicktime",		// Valid file formats
        form: "demoFiler",					// Form ID
        dragArea: "dragAndDropFiles",		// Upload Area ID
        uploadUrl: "videoupload.php"				// Server side upload url
    }
    $(document).ready(function(){
        initMultiUploader(config);

        $(".whitetd").on("click", ".delyachtimg", function(){
            var c = confirm("Are you sure you want to delete this Record?");
            if (c){
                var yval = $(this).attr('yval');
                var b_sURL = bkfolder + "includes/ajax.php";
                $.post(b_sURL,
                {
                    imid:yval,
                    az:31
                },
                function(data){
                    if (data == "y"){
                        $('.i' + yval).addClass('com_none');
                    }
                });
            }
        });
    });

    function callafter(){
        var b_sURL = bkfolder + "includes/ajax.php";
        $.post(b_sURL,
            {
                ms:'<?php echo $ms; ?>',
                az:32
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
            <h2><?php echo $yacht_name; ?></h2>
        </td>
    </tr>
</table>

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
	<h3>Add YouTube Video Link</h3>
    <form method="post" action="yacht_video_add.php" id="video_ff" name="ff" enctype="multipart/form-data">
    	<input type="hidden" value="<?php echo $ms; ?>" name="ms" />
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="htext">
        	<tr>
              <td width="35%" align="left"><span class="fontcolor3">&nbsp;&nbsp;</span>Video Title:</td>
              <td width="65%" align="left"><input type="text" id="name" name="name" value="" class="inputbox inputbox_size4" /></td>
            </tr>
    
        	<tr>
              <td width="" align="left"><span class="fontcolor3">* </span>Video URL:</td>
              <td width="" align="left"><input type="text" id="link_url" name="link_url" value="" class="inputbox inputbox_size4" /></td>
            </tr>
            
            <tr>
                <td width="" align="left" valign="top" class="tdpadding1"><span class="fontcolor3">* </span>Video Type:</td>
                <td width="" align="left" valign="top" class="tdpadding1"><select name="video_type" id="video_type" class="video_type">
                        <option value="">Select</option>
                        <option value="1">YouTube</option>
                        <option value="3">Vimeo</option>
                    </select></td>
            </tr>
            
            <tr>
                <td width="" align="left">&nbsp;</td>
                <td width="" align="left">
                    <button type="submit" class="butta"><span class="saveIcon butta-space">Save</span></button>                    
                </td>
            </tr>
        </table>    
    </form>
</div>

<div class="clearfix"></div>

<form method="post" action="yacht_video_sub.php" id="yacht_ff" name="ff">
        <input type="hidden" value="<?php echo $ms; ?>" id="ms" name="ms" />
        <div class="singleblock">
            <div class="singleblock_heading"><span>Yacht Video</span></div>
            <div id="yimholder" class="singleblock_box">
                <?php
                echo $yachtclass->yacht_video_display_list($ms);
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