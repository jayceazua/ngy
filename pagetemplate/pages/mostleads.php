<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$isdashboard = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));

$atm1 = $link_name = "Most Leads";
$breadcrumb = 0;
/*$breadcrumb_extra[] = $frontend->dashboard_breadcrumb_start();
$breadcrumb_extra[] = array(
            'a_title' => 'Site Statistics',
            'a_link' => $cm->folder_for_seo . 'site-statistics/'
);
$breadcrumb_extra[] = array(
            'a_title' => $link_name,
            'a_link' => ''
);
$brdcmp_array = $frontend->create_bradcrumb_holder($pageid, $category_id_holder, '', $breadcrumb_extra);*/

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 4, "m2" => 1, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

include($bdr."includes/head.php");
?>
<?php echo $html_start; ?>
<div class="common_header clearfixmain">
    <div class="header-bottom-inner clearfixmain">        
        <ul class="form">
            <li>
                <div class="s_field1">
                    <select id="mn" name="mn" class="select">
                        <option value="0">Month</option>
                        <?php $cm->get_genmonth_combo(); ?>
                    </select>
                </div>
                <div class="s_field1">
                    <input type="text" id="yr" name="yr" class="input" placeholder="YEAR" />
                </div>
                <div class="s_button1"><a class="filterrecord active" fsection="2" href="javascript:void(0);" title="Filter"><img src="<?php echo $cm->folder_for_seo;?>images/search.png" /></a></div>
            </li>
        </ul>       
    </div>    
</div>

<div id="filtersection" class="mostviewed">
    <?php
    $p = 1;
    $retval = json_decode($yachtclass->most_leads_yacht($p, $loggedin_member_id, 0, 0));
    echo $retval[0]->doc;
    ?>
    <div class="clear"></div>
</div>
<div class="mostviewed">
    <?php
    echo '<p class="t-center">'. $retval[0]->moreviewtext .'</p>';
    ?>
</div>
<?php echo $html_end; ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $.fn.filterresult = function(p, fsection){
                $(".spinnersmall").addClass("spinnersmallimg");
                var mn = $("#mn").val();
                var yr = $("#yr").val();
                b_sURL = bkfolder + "includes/ajax.php";
                $.post(b_sURL,
                    {
                        p:p,
                        fsection:fsection,
                        mn:mn,
                        yr:yr,
                        az:13,
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
                var fsection = $(this).attr('fsection');
                $(this).filterresult(1, fsection);
            });

            $(".main").on("click", ".moreviewlead", function(){
                var p = $(this).attr("p");
                var fsection = $(this).attr('fsection');
                $(this).filterresult(p, fsection);
            });
        });
    </script>
<?php
include($bdr."includes/foot.php");
?>