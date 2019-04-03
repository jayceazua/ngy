<?php    
    //Set content-type header
    header("Content-type: image/png");

    //Include phpMyGraph5.0.php
    include_once('phpMyGraph5.0.php');
    
    //Set config directives
    $cfgstring = $_REQUEST["c"];
    $cfgstring = explode(',', $cfgstring);

    $cfg['title'] = $cfgstring[0];
    $cfg['width'] = $cfgstring[1];
    $cfg['height'] = $cfgstring[2];
    
    //Set data
    $datastring = $_REQUEST["d"];
    $data_ar = explode(',', $datastring);
    $data = array();
    foreach($data_ar as $key => $value){
        $data[$key + 1] = $value;
    }


    //Create phpMyGraph instance
    $graph = new phpMyGraph();

    //Parse
    $graph->parseVerticalLineGraph($data, $cfg);
?> 