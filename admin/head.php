<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Site Administrator</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="imgareaselect-default.css" rel="stylesheet" />
<!--[if IE]>
<link rel="stylesheet" href="ie.css" type="text/css" media="screen">
<![endif]-->
<link href="../css/jquery-ui.css" type="text/css" rel="Stylesheet" />
<link rel="stylesheet" href="../css/jquery.fancybox.min.css" type="text/css" />
<link rel="shortcut icon" href="../favicons/favicon.ico" type="image/x-icon" />
<link rel="icon" href="../favicons/favicon.ico" type="image/x-icon" />
<script language="javascript" type="text/javascript">
	var bkfolder = "../";
	var siteurlfull = "<?php echo $cm->site_url; ?>";
</script>

<script language="javascript" type="text/javascript" src="../js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.fancybox.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/validator.js"></script>
<script language="javascript" type="text/javascript" src="../js/multiupload.js"></script>
<script language="javascript" type="text/javascript" src="../js/jscolor.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.imgareaselect.min.js"></script>
<script language="javascript" type="text/javascript" src="common.js?s=2"></script>
</head>
<body>
<?php
if ($fullpage != "y"){
	$subadmin_log = 0;
	if (isset($_SESSION["subadmin"]) AND $_SESSION["subadmin"] == 1){ $subadmin_log = 1; }
    $link_option_ar["Admin Main"][] = "leftadminicon";
    $link_option_ar["Admin Main"][] = array('Dashboard', 'adminhome.php');
    $link_option_ar["Admin Main"][] = array('Open Front-End', $cm->site_url, 'y');

    $link_option_ar["User Section"][] = 'leftusericon';
    $link_option_ar["User Section"][] = array('Add New User', 'add_user.php');
    $link_option_ar["User Section"][] = array('Modify User', 'mod_user.php');
	$link_option_ar["User Section"][] = array('Company Profile', 'mod_company.php');
	$link_option_ar["User Section"][] = array('User Rank', 'user_rank_location.php');
	$link_option_ar["User Section"][] = array('Yacht Finder List', 'yacht_finder_list.php');
	$link_option_ar["User Section"][] = array('Yacht Finder Sent Email', 'yacht_finder_email_list.php');

    $link_option_ar["Inventory"][] = "leftlistingicon";
	$link_option_ar["Inventory"][] = array('Custom Label Background', 'custom_label_bgcolor.php');
    $link_option_ar["Inventory"][] = array('View / Edit Inventory', 'mod_yacht.php');
    $link_option_ar["Inventory"][] = array('Manage Featured Boats', 'featured_yacht.php');
	//$link_option_ar["Inventory"][] = array('Manufacturer Sidebar Option', 'make-catalog.php');
	//$link_option_ar["Inventory"][] = array('Manage New Boats In Stock', 'boat_in_stock.php');
	//$link_option_ar["Inventory"][] = array('Manage Model Groups', 'manage-boat-model-group.php');
	//$link_option_ar["Inventory"][] = array('Home Page New Boats', 'home_new_boats.php');
	$link_option_ar["Inventory"][] = array('sep', '');
	$link_option_ar["Inventory"][] = array('View / Edit Model', 'mod_manufacturer.php');
	$link_option_ar["Inventory"][] = array('Model Search Fields', 'model-search-fields.php');
	$link_option_ar["Inventory"][] = array('sep', '');
	$link_option_ar["Inventory"][] = array('Useful Stats', 'useful-stats.php');
	
	$link_option_ar["Page Slider Section"][] = "leftslidericon";
	$link_option_ar["Page Slider Section"][] = array('Add Slider Category', 'add_slider_category.php');
	$link_option_ar["Page Slider Section"][] = array('Modify Existing Slider Category', 'mod_slider_category.php');
	$link_option_ar["Page Slider Section"][] = array('sep', '');
	$link_option_ar["Page Slider Section"][] = array('Add New Image/Video', 'add_slider_image.php');
    $link_option_ar["Page Slider Section"][] = array('Modify Existing Image/Video', 'mod_slider_image.php');	

    $link_option_ar["Page Section"][] = "leftpageicon";
    $link_option_ar["Page Section"][] = array('Add New Page', 'add_page.php');
    $link_option_ar["Page Section"][] = array('Modify Existing Page', 'mod_page.php');
	//$link_option_ar["Page Section"][] = array('Modify Custom Footer', 'mod-bespoke-footer.php');
	
	/*$link_option_ar["Brand Pre-Owned Section"][] = "leftlogoscrollicon";
	$link_option_ar["Brand Pre-Owned Section"][] = array('For Pre-Owned Yachts', '');
    $link_option_ar["Brand Pre-Owned Section"][] = array('Add New Brand', 'add-make-logo.php?sectionid=1');
    $link_option_ar["Brand Pre-Owned Section"][] = array('Modify Existing Brand', 'mod-make-logo.php?sectionid=1');
	
	$link_option_ar["Page Slider Section"][] = array('sep', '');
	$link_option_ar["Brand Pre-Owned Section"][] = array('For Pre-owned Sailboats and Catamarans', '');
    $link_option_ar["Brand Pre-Owned Section"][] = array('Add New Brand', 'add-make-logo.php?sectionid=2');
    $link_option_ar["Brand Pre-Owned Section"][] = array('Modify Existing Brand', 'mod-make-logo.php?sectionid=2');*/
	
	//$link_option_ar["Brand Logo Section"][] = array('Logo Display Rank', 'make-logo-rank.php');
		
	$link_option_ar["Box Content"][] = "leftboxicon";
	//$link_option_ar["Box Content"][] = array('Home Page Boxes', 'homepagebox.php');
	$link_option_ar["Box Content"][] = array('Brand Box - Home Page', 'brandbox.php?sectionid=1');
	$link_option_ar["Box Content"][] = array('Brand Box - Yacht Page', 'brandbox.php?sectionid=2');
	$link_option_ar["Box Content"][] = array('Brand Box - Catamaran Page', 'brandbox.php?sectionid=3');
	$link_option_ar["Box Content"][] = array('New Yacht / Catamaran Box', 'menu-box.php');
    $linksql = "select id, name from tbl_box_content where status = 'y' order by rank";
    $linkresult = $db->fetch_all_array($linksql);
    $linkfound = count($linkresult);
    foreach( $linkresult as $linkrow ){
        $linkid = $linkrow['id'];
        $linkname = $linkrow['name'];
        $linkname_ln = strlen($linkname);
        if ($linkname_ln > 30){ $linkname_small = substr($linkname,0,30).""; }else{ $linkname_small = $linkname; }
        $link_option_ar["Box Content"][] = array($linkname, 'box-content.php?s=' . $linkid);
    }
	
	$link_option_ar["Blog Section"][] = "leftblogicon";
	$link_option_ar["Blog Section"][] = array('Add Blog Category', 'add_blog_category.php');
	$link_option_ar["Blog Section"][] = array('Modify Existing Blog Category', 'mod_blog_category.php');
	$link_option_ar["Blog Section"][] = array('Add Blog Tag', 'add_blog_tag.php');
	$link_option_ar["Blog Section"][] = array('Modify Existing Blog Tag', 'mod_blog_tag.php');
	$link_option_ar["Blog Section"][] = array('sep', '');
	$link_option_ar["Blog Section"][] = array('Add Blog', 'add_blog.php');
	$link_option_ar["Blog Section"][] = array('Modify Existing Blog', 'mod_blog.php');
	
	$link_option_ar["Testimonial Section"][] = "lefttestimonialicon";
    $link_option_ar["Testimonial Section"][] = array('Add New Testimonial', 'add_testimonial.php');
    $link_option_ar["Testimonial Section"][] = array('Modify Existing Testimonial', 'mod_testimonial.php');
	
	/*$link_option_ar["Boat Slideshow Section"][] = "leftslideshowicon";
    $link_option_ar["Boat Slideshow Section"][] = array('Add New Slideshow', 'add_slideshow.php');
    $link_option_ar["Boat Slideshow Section"][] = array('Modify Existing Slideshow', 'mod_slideshow.php');*/
	
	/*$link_option_ar["Email Campaign Section"][] = "leftemailcampaignicon";
    $link_option_ar["Email Campaign Section"][] = array('Add New Campaign', 'add_email_campaign.php');
    $link_option_ar["Email Campaign Section"][] = array('Modify Existing Campaign', 'mod_email_campaign.php');*/
	
	/*$link_option_ar["FAQ Section"][] = "leftfaqicon";
    $link_option_ar["FAQ Section"][] = array('Add New FAQ', 'add_faq.php');
    $link_option_ar["FAQ Section"][] = array('Modify Existing FAQ', 'mod_faq.php');*/
	
	$link_option_ar["Credit Application"][] = "leftcreditlisticon";
	$link_option_ar["Credit Application"][] = array('Boat Credit Application List', 'credit-application-list.php');

    $link_option_ar["Email Section"][] = "leftemailicon";
    $link_option_ar["Email Section"][] = array('User Account Email', '');
    $linksql = "select id, name from tbl_user_account_status where status = 'y' order by rank";
    $linkresult = $db->fetch_all_array($linksql);
    $linkfound = count($linkresult);
    foreach( $linkresult as $linkrow ){
        $linkid = $linkrow['id'];
        $linkname = $linkrow['name'];
        $link_option_ar["Email Section"][] = array($linkname, 'user-account-email.php?s=' . $linkid);
    }
	
    $link_option_ar["Email Section"][] = array('System Email', '');
    $linksql = "select id, name from tbl_system_email where status = 'y' order by rank";
    $linkresult = $db->fetch_all_array($linksql);
    $linkfound = count($linkresult);
    foreach( $linkresult as $linkrow ){
        $linkid = $linkrow['id'];
        $linkname = $linkrow['name'];
        $linkname_ln = strlen($linkname);
        $link_option_ar["Email Section"][] = array($linkname, 'system-email.php?s=' . $linkid);
    }
	
	$link_option_ar["Email Section"][] = array('Brokerage Services Email', '');
    $linksql = "select id, name from tbl_brokerage_services_email where status = 'y' order by rank";
    $linkresult = $db->fetch_all_array($linksql);
    $linkfound = count($linkresult);
    foreach( $linkresult as $linkrow ){
        $linkid = $linkrow['id'];
        $linkname = $linkrow['name'];
        $linkname_ln = strlen($linkname);
        $link_option_ar["Email Section"][] = array($linkname, 'brokerage-services-email.php?s=' . $linkid);
    }
	
	$link_option_ar["Settings"][] = "leftsettingsicon";
	$link_option_ar["Settings"][] = array('General Settings', 'mod-sysvar.php');
	$link_option_ar["Settings"][] = array('Sitemap', 'sitemap.php');
	$link_option_ar["Settings"][] = array('URL Redirect', 'mod-url-redirect.php');
	$link_option_ar["Settings"][] = array('Media Library', 'media-library.php');
	if ($subadmin_log == 0){ $link_option_ar["Settings"][] = array('Admin Log-in Details', 'add_user.php?id=1'); }
	$link_option_ar["Settings"][] = array('Default Title-Metatag', 'tmt.php');
	$link_option_ar["Settings"][] = array('Default Title-Metatag For Boat', 'tmt-boat.php');
	$link_option_ar["Settings"][] = array('Default Title-Metatag For Make', 'tmt-make.php');
	$link_option_ar["Settings"][] = array('Logout', 'out.php');	
?>
<table width="1200" border="0" cellpadding="3" cellspacing="0" align="center">
    <tr>
        <td width="5" height="10" class="admincolor_dark"><img border="0" src="images/sp.gif" alt="" /></td>
        <td width="240" valign="top" align="left" class="admincolor_dark toplogo"><img border="0" src="images/logo.png" alt="" /></td>
        <td width="25" class="admincolor_dark"><img border="0" src="images/sp.gif" alt="" /></td>
        <td valign="middle" align="left" class="admincolor_dark"><span class="top-heading">Back-end Control Panel</span></td>
        <td width="100" valign="middle" align="right" class="admincolor_dark"><a class="butta" href="out.php"><span class="logoutIcon butta-space">Logout</span></a></td>
    </tr>
</table>

<table width="1200" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td width="100%" height="5"><img border="0" src="images/sp.gif" alt="" /></td>
    </tr>
</table>

<table border="0" width="1200" cellpadding="0" cellspacing="0" align="center" class="tablemain">
    <tr>
        <td width="5" height="10" class="admincolor_dark"><img border="0" src="images/sp.gif" alt="" /></td>
        <td width="240" valign="top" align="center" class="admincolor_dark">

            <table border="0" width="100%" class="admincolor_dark" cellspacing="0" cellpadding="2">
                <?php
                $leftlinkcnt = 1;
                foreach($link_option_ar as $heading=>$arr){
                ?>
                    <tr>
                        <td width="100%" height="2"><img border="0" src="images/sp.gif" alt="" /></td>
                    </tr>

                    <tr>
                        <td width="100%" align="left">
                            <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td width="100%" class="tdalign2"><a id="adminlink<?php echo $leftlinkcnt; ?>" href="javascript:void(0);" boxref="<?php echo $leftlinkcnt; ?>" class="left_menu left_menu_open"><span class="<?php echo $arr[0]; ?>"><?php echo $heading; ?></span></a></td>
                                </tr>
                            </table>
                            <div id="adminoption<?php echo $leftlinkcnt; ?>" style="display: none;">
                                <table border="0" width="100%" class="admincolor_light" cellspacing="0" cellpadding="2" align="center">
                                    <tr>
                                        <td>
                                            <div class="adminoption_left"><ul>
                                                    <?php
                                                    foreach($arr as $ar){
                                                        if (is_array($ar)){
                                                            $targetlink = '';
                                                            if ($ar[1] != ""){
                                                                if ($ar[2] == 'y'){
                                                                    $targetlink = ' target="_blank"';
                                                                }
                                                                ?>
                                                                <li><a class="lefthtext" href="<?php echo $ar[1]; ?>"<?php echo $targetlink; ?>><?php echo $ar[0]; ?></a></li>
                                                            <?php }else{
                                                                if ($ar[0] == "sep"){
                                                            ?>
                                                                    <li class="onlysep"><img border="0" src="images/sp.gif" alt="" /></li>
                                                            <?php
                                                                }else{
                                                            ?>
                                                                    <li class="onlyhead"><?php echo $ar[0]; ?></li>
                                                            <?php
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $leftlinkcnt++;
                }
                ?>
            </table>
        </td>

        <td width="5" class="admincolor_dark"><img border="0" src="images/sp.gif" alt="" /></td>
        <td width="20"><img border="0" src="images/sp.gif" alt="" /></td>
        <td class="whitetd" align="center" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" align="left" class="titlebg"><span class="<?php echo $icclass; ?>"><?php echo strtoupper($link_name); ?></span></td>
                </tr>
            </table>
            <?php }else{?>
            <div class="homepagetopspace"><img border="0" src="images/logo.png" alt="" /></div>
<?php } ?>