<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$breadcrumb = 0;
$isdashboard = 1;
$link_name = $atm1 = "Dashboard";

$profile_url = $cm->get_page_url($loggedin_member_id, 'user');
$usertype = $yachtclass->get_user_type($loggedin_member_id);
$company_id = $yachtclass->get_broker_company_id($loggedin_member_id);
$com_url = $cm->get_page_url($company_id, 'comprofile');
include($bdr."includes/head.php");
if ($usertype == 6){
?>
	<div utype="<?php echo $usertype; ?>" class="dashboard-holder dashboard-main-flex clearfixmain">
	<?php echo $frontend->get_dashboard_menu_col(array("m1" => 1)); ?>
    <div class="dashboard-contentcol flexcol clearfixmain">
		<div class="dashboard-contentcol-home clearfixmain">
        	<?php echo $yachtclass->display_user_info_dashboard($loggedin_member_id); ?>
        </div>
    </div>
</div>
<?php
}else{
	$cobrokerage_active = 1;
	$databoxtab = '';

	if ($loggedin_member_id == 1){
		if ($cobrokerage_active == 1){
			$databoxtab = '<a boatoption="1" class="button active databoxtabbutton">All Listings</a>';
			$databoxtab .= '<a boatoption="2" class="button databoxtabbutton">House Listings</a>';
			$databoxtab .= '<a boatoption="3" class="button databoxtabbutton">Co-Brokerage Listings</a>';
		}
	}else{
		$databoxtab = '<a boatoption="1" class="button active databoxtabbutton">My Listings</a>';
		$databoxtab .= '<a boatoption="2" class="button databoxtabbutton">House Listings</a>';
		if ($cobrokerage_active == 1){
			$databoxtab .= '<a boatoption="3" class="button databoxtabbutton">Co-Brokerage Listings</a>';
		}
	}
	
	if ($databoxtab != ""){
		$databoxtab = '<div class="databoxtab clearfixmain">'. $databoxtab .'</div>';
	}
?>

	<div utype="<?php echo $usertype; ?>" class="dashboard-holder dashboard-main-flex clearfixmain">
	<?php echo $frontend->get_dashboard_menu_col(array("m1" => 1)); ?>
    <div class="dashboard-contentcol flexcol clearfixmain">
		<div class="dashboard-contentcol-home clearfixmain">
        	<?php echo $yachtclass->display_user_info_dashboard($loggedin_member_id); ?>
        </div>
        
        <div class="dashboard-contentcol-home databox clearfixmain">
			<?php echo $databoxtab; ?>
        	<div class="contentwaitmsg contentwaitmsg1"><img src="<?php echo $cm->folder_for_seo; ?>images/ajax-loader.gif" /></div>
            <div class="databox1"></div>
		</div>  
    </div>
</div>

	<script type="text/javascript">
        $(document).ready(function(){
            var utype = parseInt($(".dashboard-holder").attr("utype"));
            call_site_stat_box(1,1);
    
            $(".main").off("click", ".databoxtabbutton").on("click", ".databoxtabbutton", function(){
                $(".databoxtabbutton").removeClass("active");
                $(this).addClass("active");
                call_site_stat_box(1,0);
            });
        });
        
        function call_site_stat_box(inoption, initialcall){
            var boatoption = 1;
            if (initialcall == 0){
                dispay_wait_msg("Please wait!!!!!");
                boatoption = $(".databoxtabbutton.active").attr("boatoption");
            }
            
            var company_id = 0;
            if ($("#company_id").length > 0){
                var company_id = $("#company_id").val();
            }
            
            var az = 154;
            
            var b_sURL = bkfolder + "includes/ajax.php";
            $.post(b_sURL,
            {			
                company_id:company_id,
                boatoption:boatoption,
                inoption:3,			
                az:az,
                dataType: "json"
            },
            function(data){
                data = $.parseJSON(data);
                content = data.doc;
                
                $(".databox1").html(content);
                if (initialcall == 0){
                    hide_wait_msg();
                }
                
                if (initialcall == 1){
                    $(".contentwaitmsg1").hide();
                }			
            });
        }
    </script>

<?php
}
include($bdr."includes/foot.php");
?>