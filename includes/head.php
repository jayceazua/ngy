<?php
if (isset($include_fck) AND $include_fck == 1){
	include($bdr."ckeditor/ckeditor.php");
	include_once $bdr.'ckeditor/ckfinder/ckfinder.php';
    $sBasePath = $cm->editorbasepath;
}

if (isset($isdashboard) AND $isdashboard == 1){
	$bodyclass = ' class="dashboardpages"';
}else{
	$isdashboard = 0;
}

if ($atm1 != ""){ $tm1 = $atm1; }
if ($atm2 != ""){ $tm2 = $atm2; }
if ($atm3 != ""){ $tm3 = $atm3; }

if (!isset($opengraphmeta) OR $opengraphmeta == ""){
	$opengraphmeta = $cm->meta_open_graph($tm1, $tm2, $imagelink, $cm->site_url . $currenpageturl);
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $tm1; ?></title>
    <meta name="description" content="<?php echo $tm2; ?>" />
    <meta name="keywords" content="<?php echo $tm3; ?>" />
    <meta name="google-site-verification" content="<?php echo $googlesiteverification; ?>" />
    <?php echo $opengraphmeta; ?>
   
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $cm->folder_for_seo; ?>favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $cm->folder_for_seo; ?>favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $cm->folder_for_seo; ?>favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $cm->folder_for_seo; ?>favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $cm->folder_for_seo; ?>favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $cm->folder_for_seo; ?>favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $cm->folder_for_seo; ?>favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="<?php echo $cm->folder_for_seo; ?>css/fontawesome-all.min.css" rel="stylesheet" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/slick/slick.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $cm->folder_for_seo; ?>css/slick/slick-theme.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/jquery.fancybox.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/slider.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/style-extend.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/menu.css?s=1" rel="stylesheet" type="text/css" /> 
    <link href="<?php echo $cm->folder_for_seo; ?>css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $cm->folder_for_seo; ?>css/cookie-popup.css" rel="stylesheet" type="text/css" />
    
    <!--[if IE]>
    <link rel="stylesheet" href="<?php echo $cm->folder_for_seo; ?>css/ie.php?path=<?php echo $cm->folder_for_seo; ?>" type="text/css" media="screen">
    <![endif]-->

    <script language="javascript" type="text/javascript">
		var bkfolder = "<?php echo $cm->folder_for_seo; ?>";
		var siteurlfull = "<?php echo $cm->site_url; ?>";
	</script>
	<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.min.js"></script> 
    <script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.extra.top.js"></script>
    <script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/waypoints.min.js"></script>
	<script type="text/javascript" src="<?php echo $cm->folder_for_seo; ?>js/jquery.counterup.js"></script>  
</head>
<body<?php echo $bodyclass; ?>>
<!--==============================header start=================================-->
<?php
echo $frontend->display_noscript_message();

if ($nohead > 0){
	$_SESSION["s_insidedb"] = 0;
}
	
if ($nohead == 1){
	echo '
	<div class="main clearfixmain">
		<div class="container clearfixmain">
	';
}elseif ($nohead == 3){
	$containerclass = "";
	if (isset($design_id) AND $design_id == 1){ $containerclass = " fixedwidth"; }
	if (isset($design_id) AND $design_id == 2){ $containerclass = " fixedwidth topbottomborder"; }
	
	echo '
	<div class="main'. $containerclass .' clearfixmain">
	<div class="container clearfixmain">
	';
}else{
    echo $frontend->get_header(array("loggedin_member_id" => $loggedin_member_id, "headertemplate" => $headertemplate, "pageid" => $pageid)); 
?>
<!--==============================header end=================================-->
    
    <!--Image Slider-->
    <?php
	$param = array(
		"pageid" => $pageid,
		"templatefile" => $templatefile,
		"slider_make_id" => $slider_make_id,
		"display_make_name" => $display_make_name
	);
    echo $sliderclass->page_top_banner($param);
	?>
    <!--Image Slider end-->      
       
    <!--Main start-->
	<div class="main clearfixmain">
		<?php if ($startend == 1){?>
		<div class="container text_area clearfixmain">
		<?php
		if (isset($breadcrumb) AND $breadcrumb == 1){
			echo $frontend->page_brdcmp_array($brdcmp_array);
		}
		?>
		<?php if ($main_heading != "n"){ ?><h1 class="borderstyle1"><?php echo $frontend->head_title($link_name); ?></h1><?php } ?>  
<?php 
	} 
}
?>