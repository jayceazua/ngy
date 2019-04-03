<?php
//Initial
$frontend->go_to_login();
$pageid = 0;
$main_heading = "n";
$googlemap = 0;
$isdashboard = 1;

$yachtclass->check_user_permission(array(1, 2, 3, 4, 5));
$atm1 = $link_name = "My Inventory Feed";

$currenturl = $_SERVER["REQUEST_URI"];
$_SESSION["listing_file_name"] = $currenturl;

$htmlstartend = json_decode($frontend->get_dashboard_initial_html_start_end(array("m1" => 3, "m2" => 5, "link_name" => $link_name)));
$html_start = $htmlstartend->htmlstart;
$html_end = $htmlstartend->htmlend;
$ycappnotice = $htmlstartend->ycappnotice;

$cuser_ar = $cm->get_table_fields('tbl_user', 'uid, type_id, company_id, user_code', $loggedin_member_id);
$brokeruid = $cuser_ar[0]["uid"];
$broker_code = $cuser_ar[0]["user_code"];
$broker_company_id = $cuser_ar[0]["company_id"];
$cuser_type_id = $cuser_ar[0]["type_id"];

//$broker_company_code = $cm->get_common_field_name("tbl_company", "company_code" , $broker_company_id);

include($bdr."includes/head.php");
?>

<?php echo $html_start; ?>


<div class="profile-main clearfixmain">	
    <ul class="invfeed">
    	<li>
        	<h4>My Inventory URL</h4>
            <?php
			 	$my_inv_url = $cm->site_url . "/brokerinventory/" . $broker_code . "/embed/"; 
			?>
            <a href="<?php echo $my_inv_url; ?>" target="_blank"><?php echo $my_inv_url; ?></a>
        </li>
        
        <?php
		if ($cuser_type_id == 1 OR $cuser_type_id == 2 OR $cuser_type_id == 3){
		?>
        <li>
        	<h4>Company Inventory URL</h4>
            <?php
			 	$com_inv_url = $cm->site_url . "/companyinventory/" . $broker_company_id . "/embed/"; 
			?>
            <a href="<?php echo $com_inv_url; ?>" target="_blank"><?php echo $com_inv_url; ?></a>
        </li>
        <?php
		}
		?>
    </ul>
</div>
<?php echo $html_end; ?>



<?php
include($bdr."includes/foot.php");
?>