<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$googlemap = 0;

$atm1 = $link_name = "Announcements";
include($bdr."includes/head.php");
?>
<h1>Announcements</h1>
<div id="filtersection" class="mostviewed">
    <?php
    $p = 1;
    $retval = json_decode($yachtclass->announcement_list($p));
    echo $retval[0]->doc;
    ?>
    <div class="clear"></div>
</div>
<div class="mostviewed">
    <?php
    echo '<p class="t-center">'. $retval[0]->moreviewtext .'</p>';
    ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.fn.filterbroker = function(p){
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    p:p,
                    az:19,
                    dataType: 'json'
                },
                function(data){
                    data = $.parseJSON(data);
                    content = data[0].doc;
                    moreviewtext = data[0].moreviewtext;
                    if (content != ""){
                        if (p == 1){
                            $("#filtersection").html(content);
                        }else{
                            $("#filtersection").append(content);
                        }
                    }else{
                        $('#filtersection').html('Sorry. Record unavailable.');
                    }
                    $(".t-center").html(moreviewtext);
                });
        }

        $(".main").on("click", ".moreannouncement", function(){
            var p = $(this).attr("p");
            $(this).filterbroker(p);
        });
    });
</script>
<?php
include($bdr."includes/foot.php");
?>