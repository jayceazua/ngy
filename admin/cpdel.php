<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$del_id = $_GET["del_id"];
$t = $_GET["t"];

$del_id = $cm->filtertext($del_id);
$t = $cm->filtertext($t);

if ($t=="companylocation"){
    $yachtclass->delete_companylocation($del_id);
}elseif ($t=="user"){
	if ($del_id > 1){ $yachtclass->delete_user($del_id); }
}elseif ($t=="page"){
    $del_page_parent_id = $db->total_record_count("select parent_id as ttl from tbl_page where id = '". $del_id ."'");
    $sql = "update tbl_page set set parent_id = '". $del_page_parent_id ."' where parent_id = '". $del_id ."'";
    $db->mysqlquery($sql);
	
	$fimg1 = $db->total_record_count("select menu_imgpath as ttl from tbl_page where id = '". $del_id ."'");
	if ($fimg1 != ""){
		$fle->filedelete("../menuimage/".$fimg1);
	}

    $sql = "delete from tbl_page where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="yacht"){
    $yachtclass->delete_yacht($del_id);

}elseif ($t=="yachtimage"){
    $fimg1 = $db->total_record_count("select imgpath as ttl from tbl_yacht_photo where id = '". $del_id ."'");
    $yachtclass->delete_yacht_image($fimg1, $del_id);

    $sql = "delete from tbl_yacht_photo where id = '".$del_id."'";
    $db->mysqlquery($sql);

}elseif ($t=="yachtvideo"){
    $fimg1 = $db->total_record_count("select videopath as ttl from tbl_yacht_video where id = '". $del_id ."'");
    $yachtclass->delete_yacht_video($fimg1);

    $sql = "delete from tbl_yacht_video where id = '".$del_id."'";
    $db->mysqlquery($sql);

}elseif ($t=="slidercategory"){
    $sql = "delete from tbl_slider_category where id = '". $del_id ."'";
    $db->mysqlquery($sql);
	
	$sql = "select imgpath from tbl_image_slider where category_id = '". $del_id ."'";
	$result = $db->fetch_all_array($sql);
	foreach($result as $row){
		$fimg1 = $row["imgpath"];
		if ($fimg1 != ""){
			$fle->filedelete("../sliderimage/".$fimg1);
			$fle->filedelete("../sliderimage/original/".$fimg1);
		}
	}
	
	$sql = "delete from tbl_image_slider where category_id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="sliderimage"){
    $fimg1 = $db->total_record_count("select imgpath as ttl from tbl_image_slider where id = '". $del_id ."'");
    if ($fimg1 != ""){
        $fle->filedelete("../sliderimage/".$fimg1);
		$fle->filedelete("../sliderimage/original/".$fimg1);
    }

    $sql = "delete from tbl_image_slider where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="testimonial"){
	$fimg1 = $db->total_record_count("select imgpath as ttl from tbl_testimonial where id = '". $del_id ."'");
    if ($fimg1 != ""){
        $fle->filedelete("../testimonialimage/".$fimg1);
    }
	
    $sql = "delete from tbl_testimonial where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="blogcategory"){
	$blogclass->delete_blog_category($del_id);
	
}elseif ($t=="blogtag"){
	$blogclass->delete_blog_tag($del_id);
	
}elseif ($t=="blog"){
	$blogclass->delete_blog($del_id);
	
}elseif ($t=="faq"){
    $sql = "delete from tbl_faq where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="servicepartner"){
    $sql = "delete from tbl_service_partners where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="creditapplication"){
    $sql = "delete from tbl_credit_application where id = '". $del_id ."'";
    $db->mysqlquery($sql);
	
	$sql = "delete from tbl_credit_application_value where application_id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="page301"){
    $sql = "delete from tbl_page_301 where id = '". $del_id ."'";
    $db->mysqlquery($sql);

}elseif ($t=="slideshow"){
	$slideshowclass->delete_slideshow($del_id);
	
}elseif ($t=="emailcampaign"){
	$emailcampaignclass->delete_campaign($del_id);
	
}elseif ($t=="logoscroll"){
    $logoscrollclass->delete_logo($del_id);

}elseif ($t=="model"){
    $modelclass->delete_model($del_id);

}else{
	//--
} 
$_SESSION["postmessage"] = "dels";   
header('Location: ' . $_SERVER['HTTP_REFERER']);   
?>