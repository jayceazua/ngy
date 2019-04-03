<?php
$bdr = "../";
include("common.php");
$adm->admin_login();
$iiid = round($_POST["ms"], 0);
$crop_option = round($_POST["crop_option"], 0);
$rotateimage = round($_POST["rotateimage"], 0);
if($_SERVER['REQUEST_METHOD'] == "POST"){

    $filename = $_FILES['file']['name'];
    $wh_ok = $fle->check_file_ext($cm->allow_image_ext, $filename);
    if ($wh_ok == "y"){
		$listing_no = $yachtclass->get_yacht_no($iiid);
        $i_rank = $db->total_record_count("select max(rank) as ttl from tbl_yacht_photo where yacht_id = '". $iiid ."'") + 1;
        $i_iiid = $cm->get_unq_code("tbl_yacht_photo", "id", 10);
        $sql = "insert into tbl_yacht_photo (id, yacht_id, rank, status_id) values ('". $i_iiid ."', '". $iiid ."', '". $i_rank ."', 1)";
        $db->mysqlquery($sql);

        $filename_tmp = $_FILES['file']['tmp_name'];
        $filename = $fle->uploadfilename($filename);
        $filename1 = $i_iiid."yacht".$filename;

        $target_path_main = "yachtimage/" . $listing_no . "/";
        //if ($frontfrom == 0){
            $target_path_main = "../" . $target_path_main;
        //}

        //thumbnail image
        $r_width = $cm->yacht_im_width_t;
        $r_height = $cm->yacht_im_height_t;
        $target_path = $target_path_main;        
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}

        //big image
        $r_width = $cm->yacht_im_width_b;
        $r_height = $cm->yacht_im_height_b;
        $target_path = $target_path_main . "big/";
        if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}

        //bigger image
        $r_width = $cm->yacht_im_width;
        $r_height = $cm->yacht_im_height;
        $target_path = $target_path_main . "bigger/";
        if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		//slider image
		$r_width = $cm->yacht_im_width_sl;
		$r_height = $cm->yacht_im_height_sl;
		$target_path = $target_path_main . "slider/";
		if ($crop_option == 1){
			$fle->new_image_fixed($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}else{
			$fle->new_image_box($filename_tmp, $r_width, $r_height, $target_path, $cm->filtertextdisplay($filename1), $rotateimage);
		}
		
		//original image store
		$target_path = $target_path_main . 'original/';
		$target_path = $target_path . $cm->filtertextdisplay($filename1);
		$fle->fileupload($filename_tmp, $target_path);
		
		//rotate original image
		if ($rotateimage > 0){
			$im = @ImageCreateFromJPEG ($target_path);
			$im = imagerotate($im, $rotateimage, 0);
			@ImageJPEG ($im, $target_path, 100);
		}		

        //$fle->filedelete($filename_tmp);
        $sql = "update tbl_yacht_photo set imgpath = '".$cm->filtertext($filename1)."' where id = '". $i_iiid ."'";
        $db->mysqlquery($sql);
        echo($_POST['index']);
    }
	exit;
}
?>