<?php
$bdr = "";
include($bdr."includes/common.php");
$systemarray = json_decode($frontend->get_template_page());
$templatefile = $systemarray->templatefile;
$pageid = $systemarray->pageid;
$bodyclass = $systemarray->bodyclass;
$lastpageid = $systemarray->lastpageid;
$startend = $systemarray->startend;
$nohead = $systemarray->nohead;
include($bdr."includes/page_set.php");

//display template
$templatepath = $_SERVER['DOCUMENT_ROOT'] . $cm->folder_for_seo . $templateclass->pagetemplate . '/'. $templatefile;
include($templatepath);
//end
?>