<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;

$atm1 = $link_name = "Office Location List";

$breadcrumb = 1;
$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);

include($bdr."includes/head.php");
?>
<div class="profile-main">
	<ul class="listmenu">
        <li class="left"><a href="<?php echo $bdir; ?>add-office-location/" class="icon-locadd">Add Office Location</a></li>
    </ul>	
    <div class="clear"></div>
</div>
<div class="common_header">
        <div class="header-bottom-inner">
            <div class="sch">
                <span><?php echo $link_name; ?></span>
            </div>
            <div class="vp">
                <ul class="form">
                    <li class="left">
                        <input type="text" id="onm" name="onm" class="input" placeholder="Office Name" />
                    </li>
                    <li class="left">
                        <input type="text" id="postcode" name="postcode" class="input" placeholder="Post Code" />
                    </li>
                    <li class="leftbutton">
                        <a class="filterrecord active" fsection="1" href="javascript:void(0);" title="Filter"><img src="<?php echo $cm->folder_for_seo;?>images/search.png" /></a>
                    </li>
                </ul>
            </div>
            <div class="res"><div class="spinnersmall">&nbsp;</div></div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
</div>

<div id="filtersection" class="mostviewed">
    <?php	
    $p = 1;
    $retval = json_decode($yachtclass->my_location_list($p));
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
                $(".spinnersmall").addClass("spinnersmallimg");
                var onm = $("#onm").val();
                var postcode = $("#postcode").val();
                b_sURL = bkfolder + "includes/ajax.php";
                $.post(b_sURL,
                    {
                        p:p,						
                        onm:onm,
                        postcode:postcode,
                        az:24,
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
                        $(".spinnersmall").removeClass("spinnersmallimg");
                    });
            }

            $(".main").on("click", ".filterrecord", function(){
                $(this).filterbroker(1);
            });

            $(".main").on("click", ".morebroker", function(){
                var p = $(this).attr("p");
                $(this).filterbroker(p);
            });

            $(".main").on("click", ".removeofficelocation", function(){
                var a = confirm("Are you sure you want to remove this Broker?");
                if (a){
                    mbid = $(this).attr('mbid');
                    b_sURL = bkfolder + "includes/ajax.php";
                    $.post(b_sURL,
                        {
                            mbid:mbid,
                            az:25,
                            dataType: 'json'
                        },
                        function(data){
                            data = $.parseJSON(data);
                            content = data[0].retval;
                            if (content == 'y'){
                                window.location.reload();
                            }else{
                                optiontext = data[0].optiontext;
                                alert(optiontext);
                            }
                        });
                }
            });
        });
    </script>
<?php
include($bdr."includes/foot.php");
?>