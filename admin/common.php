<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

//check for stage or prod path
(strpos(__DIR__, 'stage') !== false)?require_once("../includes/ycconfig-stage.php"):require_once("../includes/ycconfig.php");
require_once("../includes/classes/db.class.php");
require_once("../includes/classes/common.class.php");
require_once("../includes/classes/admin.class.php");
require_once("../includes/classes/yacht.class.php");
require_once("../includes/classes/yacht.child.class.php");
require_once("../includes/classes/yacht.engine.class.php");
require_once("../includes/classes/geocode.class.php");
require_once("../includes/classes/blog.class.php");
require_once("../includes/classes/slider.class.php");
require_once("../includes/classes/file.class.php");
require_once("../includes/classes/email.class.php");
require_once("../includes/classes/template.class.php");
require_once("../includes/classes/ym.class.php");
require_once("../includes/classes/make.class.php");
require_once("../includes/classes/model.class.php");
require_once("../includes/classes/boattype.class.php");
require_once("../includes/classes/emailcampaign.class.php");
require_once("../includes/classes/slideshow.class.php");
require_once("../includes/classes/creditapplication.class.php");
require_once("../includes/classes/encodedecode.class.php");
require_once("../includes/classes/sitemap.class.php");
require_once("../includes/classes/medialibrary.class.php");
require_once("../includes/classes/logoscroll.class.php");
require_once("../includes/classes/boat.watcher.class.php");
require_once("../includes/classes/charter.boat.class.php");

$db = new Database(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);     //******************  Database class
$cm = new Commonclass();   //******************  Common class
$yachtclass = new Yachtclass();   //******************  Yacht class
$yachtchildclass = new Yachtclass_Child();   //******************  Yacht class child
$yachtengineclass = new Yachtclass_Engine();   //******************  Yacht class engine
$geo = new Geocode();   //******************  Geocoding class
$adm = new Adminclass();   //******************  Admin class
$fle = new Fileclass();    //******************  File class
$blogclass = new Blogclass();   //******************  Blog class
$sliderclass = new Sliderclass();   //******************  Slider class
$sdeml = new Emailclass();   //******************  Email class
$templateclass = new Templateclass();   //******************  Template class
$ymclass = new Ymclass();   //******************  YM class
$makeclass = new Makeclass();   //******************  Make class
$modelclass = new Modelclass();   //******************  Model class
$boattypeclass = new BoatTypeclass();   //******************  Boat Type class
$slideshowclass = new Slideshowclass();   //******************  Slideshow class
$emailcampaignclass = new Emailcampaignclass();   //******************  Email campaign class
$creditappclass = new Creditapplicationclass();   //******************  Credit Application class
$edclass = new Encodedecodeclass();   //******************  Encode Decode class
$sitemapclass = new Sitemapclass();   //******************  Sitemap class
$medialibraryclass = new MediaLibraryclass();   //******************  Media Library class
$logoscrollclass = new Logoscrollclass();   //******************  Logo Scroll class
$boatwatcherclass = new Boatwatcherclass();    //******************  Boat Watcher class
$charterboatclass = new CharterBoatclass();   //******************  Charter Boat class
?>