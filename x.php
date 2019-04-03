<?php
$googlemapkey = "AIzaSyAlJl6Onr8SDkBtlUuXuW3gCg0BYMRsEPY";
$address = "Key West, FL, US";
$address = urlencode($address);
$mapdisplay = '<div class="customboattabcontent clearfixmain"><img class="mapgray" style="width: 100%; height:300px;" src="//maps.googleapis.com/maps/api/staticmap?center='. $address .'&zoom=9&size=750x300&maptype=roadmap&sensor=false&markers=size:mid%7Cmarkers=size:mid%7Ccolor:green%7C'. $address .'&key='. $googlemapkey .'"></div>';
//echo $mapdisplay;
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Next Generation Yachting</title>
    <meta name="description" content="We are The European Yacht and Catamaran brokerage boutique of Miami. Our success is bases on service quality and personal approach to a clientele looking for dedication, ethics and results." />
    <meta name="keywords" content="NG Yachting, Next Generation Yachting" />
    <meta name="google-site-verification" content="#" />
    
		  <meta property="og:title" content="Next Generation Yachting" />
		  <meta property="og:description" content="We are The European Yacht and Catamaran brokerage boutique of Miami. Our success is bases on service quality and personal approach to a clientele looking for dedication, ethics and results." />
		  <meta property="og:url" content="http://localhost/ngyachting/ngyachting/" />
		  <meta property="og:image" content="http://localhost/ngyachting/images/logoshare.png" />	  
		   
    <link rel="apple-touch-icon" sizes="57x57" href="/ngyachting/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/ngyachting/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/ngyachting/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/ngyachting/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/ngyachting/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/ngyachting/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/ngyachting/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/ngyachting/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/ngyachting/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ngyachting/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/ngyachting/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ngyachting/favicons/favicon-16x16.png">
    <link rel="manifest" href="/ngyachting/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ngyachting/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="/ngyachting/css/fontawesome-all.min.css" rel="stylesheet" />
    <link href="/ngyachting/css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/animate.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/owl.carousel.min.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/slider.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/style-extend.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/menu.css?s=1" rel="stylesheet" type="text/css" />    
    <link href="/ngyachting/css/jquery.timepicker.css" rel="stylesheet" type="text/css" />
    <link href="/ngyachting/css/colorbox.css" rel="stylesheet" type="text/css" />    
    <link href="/ngyachting/css/responsive.css" rel="stylesheet" type="text/css" />
        
    <!--[if IE]>
    <link rel="stylesheet" href="/ngyachting/css/ie.php?path=/ngyachting/" type="text/css" media="screen">
    <![endif]-->

    <script language="javascript" type="text/javascript">
		var bkfolder = "/ngyachting/";
		var siteurlfull = "http://localhost/ngyachting";
	</script>
	<script type="text/javascript" src="/ngyachting/js/jquery.min.js"></script>     
</head>
<body class="home">
<?php
echo $mapdisplay;
?>
</body>
</html>
