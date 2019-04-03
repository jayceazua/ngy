<?php 
$bdr = "../";
include("common.php");
$call_function = "a";
include("pageset.php");

$link_name = "Dashboard";

$sqltext = 'select count(*) as ttl from tbl_page where page_type = 1';
$total_page = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_user where type_id = 3';
$total_company_admin = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_user where type_id = 4';
$total_agent = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_user where type_id = 5';
$total_consumer = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_yacht';
$total_yacht = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_image_slider';
$total_gallery_image = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_testimonial';
$total_testimonial = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_testimonial where status_id = 3';
$total_testimonial_pending = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_blog';
$total_blog = $db->total_record_count($sqltext);

$sqltext = 'select count(*) as ttl from tbl_faq';
$total_faq = $db->total_record_count($sqltext);

include("head.php");

$emaillink_arr = $link_option_ar["Email Section"];
?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td width="100%" height="20"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td width="30"><img src="images/sp.gif" border="0"></td>
        <td width="400" align="left" valign="top" class="box_border">
            <div class="main_con">
                <div class="left">
                    <div class="dash-box-head leftpageicon">CMS Page</div>
                    CMS Page: <strong><?php echo $total_page; ?></strong><br />
                    <a href="add_page.php" class="htext">Add</a> | <a href="mod_page.php" class="htext">View</a><br /><br />

					<div class="dash-box-head leftslidericon">Page Slider Image</div>
                    Slider Image: <strong><?php echo $total_gallery_image; ?></strong><br />
                    <a href="add_slider_image.php" class="htext">Add</a> | <a href="mod_slider_image.php" class="htext">View</a><br /><br />

                    <div class="dash-box-head leftsettingsicon">Settings</div>
                    <a href="mod-sysvar.php" class="htext">General Settings</a><br />
                </div>

                <div class="right">
                    <div class="dash-box-head leftemailicon">Email Content</div>
                    <?php
                        foreach($emaillink_arr as $emaillink_ar){
                            if (is_array($emaillink_ar)){
                                if ($emaillink_ar[1] != ""){
                    ?>
                                <a href="<?php echo $emaillink_ar[1]; ?>" class="htext"><?php echo $emaillink_ar[0]; ?></a><br />
                    <?php
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </td>

        <td width=""><img src="images/sp.gif" border="0"></td>

        <td width="400" align="left" valign="top" class="box_border">
            <div class="main_con">
                <div class="left">
                    <div class="dash-box-head leftusericon">User Information</div>
                    Company Admin: <strong><?php echo $total_company_admin; ?></strong><br />
                    Location Admin: <strong><?php echo $total_location_admin; ?></strong><br />
                    Agent: <strong><?php echo $total_agent; ?></strong><br />
                    <a href="add_user.php" class="htext">Add</a> | <a href="mod_user.php" class="htext">View</a><br /><br />
                </div>
                <div class="right">
                    <div class="dash-box-head leftlistingicon">Inventory Module</div>
                    Yacht: <strong><?php echo $total_yacht; ?></strong><br />
                    <a href="add_yacht.php" class="htext">Add</a> | <a href="mod_yacht.php" class="htext">View</a><br /><br />
                </div>
            </div>
        </td>
        <td width="30"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td width="100%" height="20"><img src="images/sp.gif" border="0"></td>
    </tr>
</table>

<?php
include("foot.php");
?>