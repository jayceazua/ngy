<?php
$display_brd_array = "n";
$main_heading = "n";
$googlemap = 0;
$cnm = $_REQUEST["cnm"];
$result = $yachtclass->check_manufacturer_exist($cnm, 1, 0, 0);

$row = $result[0];
foreach($row AS $key => $val){
    ${$key} = $cm->filtertextdisplay($val);
}

$atm1 = "Profile Of " . $name;
$total_y = $yachtclass->get_total_yacht_by_manufacturer($id);
$manufacturer_inv_url = $cm->get_page_url($id, 'make');
if ($logo_image == ""){ $logo_image = 'no.png'; }

$p = 1;
$retval = json_decode($ymclass->get_manufacturer_model_list($id, $p));
$foundm = $retval[0]->foundm;
$manufacturerarname = $retval[0]->manufacturerarname;
$manufacturerardescription = $retval[0]->manufacturerardescription;
$manufacturerarlogo_image = $retval[0]->manufacturerarlogo_image;
include($bdr."includes/head.php");
?>
<div class="profile-main">
    <div class="mainleft">
        <img src="<?php echo $manufacturerarlogo_image; ?>" alt="" />
    </div>
    <div class="mainright">        
        <div class="meta">
        <h1><?php echo $manufacturerarname; ?></h1>            
        </div>         
        <?php echo $manufacturerardescription; ?> 
    </div>
    <div class="clear"></div>
</div>

<?php
if ($foundm > 0){
?>
<div class="profile-main">
    <div class="header-bottom-bg">
        <div class="header-bottom-inner">
            <div class="sch">
                <span>Model Lists</span>
            </div>
            <div class="vp">
                &nbsp;
            </div>
            <div class="res"><div class="spinnersmall"><span class="reccounterupdate"><?php echo $foundm; ?></span> result(s)</div></div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div id="filtersection" class="profile-main">
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
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        $.fn.filtermakemodel = function(p){
            b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
                {
                    p:p,                    
					manufacturer_id:'<?php echo $ycid; ?>',
					az:48,
                    dataType: 'json'
                },
                function(data){
                    data = $.parseJSON(data);
                    content = data[0].doc;
                    moreviewtext = data[0].moreviewtext;
                    if (content != ""){
                        if (p == 1){
                            $("#listingholder").html(content);
                        }else{
                            $("#listingholder").append(content);
                        }
                    }else{
                        $('#listingholder').html('Sorry. Record unavailable.');
                    }
                    $(".t-center").html(moreviewtext);
                });
        }

        $(".main").on("click", ".moremodel", function(){
            var p = $(this).attr("p");
            $(this).filtermakemodel(p);
        });
    });
</script>
<?php
include($bdr."includes/foot.php");
?>