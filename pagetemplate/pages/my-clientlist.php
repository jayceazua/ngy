<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "y";
$googlemap = 0;

$p = 1;
$retval = json_decode($yachtclass->my_client_list($p));
$foundm = $retval[0]->totalrec;

$atm1 = $link_name = "Client List";
include($bdr."includes/head.php");
?>

<div class="profile-main">
    <div class="header-bottom-bg">
        <div class="header-bottom-inner">
            <div class="vp">
                &nbsp;
            </div>
            <div class="res"><div class="spinnersmall"><span class="reccounterupdate"><?php echo $foundm; ?></span> result(s)</div></div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>

<div id="filtersection" class="mostviewed myclientlist">
    <?php
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
        $.fn.filteresource = function(p){
            $(".spinnersmall").addClass("spinnersmallimg");           
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    p:p,					               
                    az:29,
                    dataType: 'json'
                },
                function(data){				
                    data = $.parseJSON(data);
                    content = data[0].doc;
                    moreviewtext = data[0].moreviewtext;
                    if (content != ""){
                        totalrec = data[0].totalrec;
                        if (p == 1){
                            $("#filtersection").html(content);
                        }else{
                            $("#filtersection").append(content);
                        }
                    }else{
                        totalrec = 0;
                        $('#filtersection').html('Sorry. Record unavailable.');
                    }
                    $(".t-center").html(moreviewtext);
                    $(".spinnersmall").removeClass("spinnersmallimg");
                    $(".res span.reccounterupdate").html(totalrec);
                });
        }

        $(".main").on("click", ".morebrokerlist", function(){
            var p = $(this).attr("p");
            $(this).filteresource(p);
        });
		
		
    });
</script>
<?php
include($bdr."includes/foot.php");
?>