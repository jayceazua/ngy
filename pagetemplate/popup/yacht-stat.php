<?php
$loggedin_member_id = $yachtclass->loggedin_member_id();
$id = round($_REQUEST['id'], 0);
$yachtclass->check_user_exist($loggedin_member_id, 0, 1);
$m = $yachtclass->yacht_name($id);
$fsection = round($_REQUEST["fsection"], 0);

$yr = date("Y");
$mn = date("m");

if ($fsection == 1){
    $link_name = 'Views Of ' . $m;
}

if ($fsection == 2){
    $link_name = 'Leads Of ' . $m;
}

include("head.php");
?>
<h2><?php echo $link_name; ?></h2>

<div class="common_header">
    <div class="leftsection">
        <ul class="form">
            <li class="left">
                <select id="mn" name="mn" class="select">
                    <?php $cm->get_genmonth_combo($mn,1); ?>
                </select>
            </li>
            <li class="left">
                <select id="yr" name="yr" class="select">
                    <?php $cm->get_genyear_combo1($yr, date("Y"), 2013); ?>
                </select>
            </li>
            <li class="leftbutton">
                <a class="graphchange active" searchopt="1" fsection="<?php echo $fsection; ?>" href="javascript:void(0);" title="Filter"><img src="<?php echo $cm->folder_for_seo;?>images/search.png" /></a>
            </li>
        </ul>
    </div>
    <div class="rightsection">
        <ul class="form">
            <li class="left">
                <select id="yr1" name="yr1" class="select">
                    <?php $cm->get_genyear_combo1($year, date("Y"), 2013); ?>
                </select>
            </li>
            <li class="leftbutton">
                <a class="graphchange active" searchopt="2" fsection="<?php echo $fsection; ?>" href="javascript:void(0);" title="Filter"><img src="<?php echo $cm->folder_for_seo;?>images/search.png" /></a>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div id="graphholder" class="mostviewed">
    <?php
    $retval = json_decode($yachtclass->display_graph($fsection, 1, $id, $mn, $yr));
    echo $retval[0]->doc;
    ?>
</div>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".main").on("click", ".graphchange", function(){
                var fsection = $(this).attr('fsection');
                var searchopt = $(this).attr('searchopt');

                if (searchopt == 1){
                    var mn = $("#mn").val();
                    var yr = $("#yr").val();
                }

                if (searchopt == 2){
                    var mn = 0;
                    var yr = $("#yr1").val();
                }

                b_sURL = bkfolder + "includes/ajax.php";
                $.post(b_sURL,
                    {
                        yid:<?php echo $id; ?>,
                        fsection:fsection,
                        searchopt:searchopt,
                        mn:mn,
                        yr:yr,
                        az:14,
                        dataType: 'json'
                    },
                    function(data){
                        data = $.parseJSON(data);
                        content = data[0].doc;
                        $("#graphholder").html(content);
                    });
            });
        });
    </script>
<?php
include("foot.php");
?>