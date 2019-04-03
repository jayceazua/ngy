<?php  
// Standard inclusions   
include("../common.php");
$frontend->go_to_login(1);

include("pChart/pData.class");
include("pChart/pChart.class");

//Set data
$datastring = $_REQUEST["d"];
$labelstring = $_REQUEST["la"];
$cfgstring = $_REQUEST["c"];
$chartstyle = $_REQUEST["chartstyle"];
$cfgstring = explode(',', $cfgstring);

// Dataset definition 
$DataSet = new pData;

// Initialise the graph
$mw = $cfgstring[2];
$mh = $cfgstring[3];
$Test = new pChart($mw,$mh);

//manupulation
$maindata_ar = json_decode($datastring);
$counter = 1;
foreach($maindata_ar as $key => $value){
	$data_ar = $value;
	$datayear = $key;
	$data = array();
	$datacol = array();
	foreach($data_ar as $key => $value){
		$data[] = $value;
		$datacol[] = $key;
	}
	
	$DataSet->AddPoint($data,"Serie" . $counter); 
	$DataSet->AddPoint($datacol,"Dcol" . $counter); 
	$DataSet->AddSerie("Serie" . $counter);
	$DataSet->SetSerieName($datayear,"Serie" . $counter);
	$counter++;
}

$width_sort = 75;
$height_sort = 30;
 
$DataSet->SetAbsciseLabelSerie("Dcol1");
$DataSet->SetYAxisName($cfgstring[1]);
if ($cfgstring[8] != ""){
	$DataSet->SetXAxisName($cfgstring[8]);
	$height_sort = 55;
}

$sw = $mw - $width_sort + 35;
$sh = $mh - $height_sort;

$xaxis_pointer_degree = 0;

$Test->clearShadow();

//BAR GRAPH
if ($chartstyle == 1){	
	$Test->setFontProperties("Fonts/tahoma.ttf",12);
	$Test->setGraphArea($width_sort,$height_sort,$sw,$sh);
	$Test->drawBackground(255,255,255); 
	$Test->drawGraphArea(255,255,255,FALSE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,50,50,50,TRUE,$xaxis_pointer_degree,0,TRUE);   
	$Test->setColorPalette(0,0,156,255);
	$Test->setColorPalette(1,121,121,121);
	
	// Draw the 0 line
	$Test->setFontProperties("Fonts/tahoma.ttf",6);
	$Test->drawTreshold(0,143,55,72,TRUE,TRUE);
	
	// Draw the bar graph  
	$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
	
	$Test->setFontProperties("Fonts/tahoma.ttf",8);
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),array("Serie1","Serie2"));
	
	//Draw pointer label if any
	$labeldata_ar = json_decode($labelstring);
	foreach($labeldata_ar as $key => $value){
		$seriename = $key;
		$data_ar = $value;
		foreach($data_ar as $key => $value){
			if ($value > 0){
				$displaylabel = '# ' . $value;
				$Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),$seriename,$key,$displaylabel,255,255,255);
			}
		}
	}
	
	if ($counter > 2){
		$Test->drawLegend(($mw - 200),0,$DataSet->GetDataDescription(),255,255,255);
	}
}

//PIE CHART
if ($chartstyle == 2){
	$drawPieLegend_x = 600;
	$PieLegend_set = $cfgstring[7];
	$drawPieLegend_x = $drawPieLegend_x - $PieLegend_set;
	
	$palette_shade = round($cfgstring[9], 0);
	if ($palette_shade == 0){
		$palette_shade = 5;
	}
	
	$Test->setFontProperties("Fonts/tahoma.ttf",10);
	$Test->drawBackground(255,255,255); 
	$Test->drawGraphArea(255,255,255,FALSE);
	
	$Test->createColorGradientPalette(0,156,255,230,13,32,$palette_shade);
	$Test->AntialiasQuality = 0;
	
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),270,175,215,PIE_PERCENTAGE,FALSE,50,20,5);
 	
	$Test->setFontProperties("Fonts/tahoma.ttf",11);
	$Test->drawPieLegend($drawPieLegend_x,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
}

$Test->Stroke();
?>
 