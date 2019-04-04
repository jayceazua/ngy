<?php
$bdr = "../";
include("common.php");
$az = $_REQUEST["az"];
$az = round($az, 0);

if ($az == 50){
	//check login	
	echo $adm->session_check($_SESSION["sesid"], 0, 'az');
}else{
	$yachtclass->check_backend_admin_login();
	if ($az == 1){
		//bespoke link add
		$total_bespoke_footer = round($_POST["total_bespoke_footer"], 0);
		echo $adm->bespoke_footer_display_list_add($total_bespoke_footer);
	}
	
	if ($az == 2){
		//bespoke link delete	
		$besid = $_POST["besid"];
		$adm->bespoke_footer_delete($besid);
	}
	
	if ($az == 3){
		//bespoke link sortable	
		$section = round($_POST["section"], 0);
		$adm->bespoke_footer_sort($section);
	}
	
	if ($az == 4){
		$inoption = round($_REQUEST["inoption"], 0);	
		$updateid = round($_POST["updateid"], 0);
		$reg_date = $_POST["reg_date"];
		
		//testimonial date update
		if ($inoption == 1){
			$adm->update_testimonial_date($updateid, $reg_date);
		}
		
		//blog date update
		if ($inoption == 2){
			$blogclass->update_blog_date($updateid, $reg_date);
		}
		
		//event date update
		if ($inoption == 3){
			$adm->update_event_date($updateid, $reg_date);
		}
		
		//blog tag add
		if ($inoption == 4){
			$cat_name = $_REQUEST["keyterm"];
			$ms = round($_REQUEST["ms"], 0);
			$opt = round($_REQUEST["opt"], 0);
			echo $blogclass->insert_tag_ajax($cat_name);
		}
	}
	
	if ($az == 5){
		$op = round($_POST["op"], 0);
		
		if ($op == 1){
			//location sort - for our team page
			$adm->update_location_rank();
		}
		
		if ($op == 2){
			//location sort by state - for our team page
			$adm->update_location_state_rank();
		}
		
		if ($op == 3){
			//user sort	- for our team page
			$adm->update_user_rank();
		}		
	}
	
	if ($az == 6){
		$op = round($_POST["op"], 0);
		
		if ($op == 1){
			//assign new boat to display on home page for specific manufacture
			$boat_id = round($_POST["boat_id"], 0);
			$manufacturer_id = round($_POST["manufacturer_id"], 0);
			$home_page_new_boats = round($_POST["home_page_new_boats"], 0);
			
			$argu = array(
				'boat_id' => $boat_id,
				'manufacturer_id' => $manufacturer_id,
				'home_page_new_boats' => $home_page_new_boats
			);
						
			$makeclass->assign_new_boats_sub_yc($argu);
		}
	}
	
	if ($az == 7){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//boat assign to slideshow
			$boat_id = round($_REQUEST["boat_id"], 0);
			$slideshow_id = round($_REQUEST["slideshow_id"], 0);
			$slideshowclass->boat_add_slideshow($slideshow_id, $boat_id);			
			echo $slideshowclass->boat_slideshow_list_ajax_call(2, $slideshow_id);
		}
		
		if ($inoption == 2){
			//boat assign sort - slideshow
			$slideshowclass->update_boat_slideshow_list_rank();
		}
		
		if ($inoption == 3){
			//remove boat from slideshow
			$boat_id = round($_REQUEST["boat_id"], 0);
			$slideshow_id = round($_REQUEST["slideshow_id"], 0);
			$slideshowclass->boat_remove_slideshow($slideshow_id, $boat_id);			
			echo $slideshowclass->boat_slideshow_list_ajax_call(1, $slideshow_id);
		}
		
		if ($inoption == 4){
			//available boat search - slideshow
			$slideshow_id = round($_REQUEST["slideshow_id"], 0);
			echo $slideshowclass->boat_search_for_slideshow_ajax($slideshow_id);
		}
	}
	
	if ($az == 9){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//boat assign to campaign
			$boat_id = round($_REQUEST["boat_id"], 0);
			$campaign_id = round($_REQUEST["campaign_id"], 0);
			$emailcampaignclass->boat_add_campaign($campaign_id, $boat_id);			
			echo $emailcampaignclass->boat_campaign_list_ajax_call(2, $campaign_id);
		}
		
		if ($inoption == 2){
			//boat assign sort - campaign
			$emailcampaignclass->update_boat_campaign_list_rank();
		}
		
		if ($inoption == 3){
			//remove boat from campaign
			$boat_id = round($_REQUEST["boat_id"], 0);
			$campaign_id = round($_REQUEST["campaign_id"], 0);
			$emailcampaignclass->boat_remove_campaign($campaign_id, $boat_id);			
			echo $emailcampaignclass->boat_campaign_list_ajax_call(1, $campaign_id);
		}
		
		if ($inoption == 4){
			//available boat search - campaign
			$campaign_id = round($_REQUEST["campaign_id"], 0);
			echo $emailcampaignclass->boat_search_for_campaign_ajax($campaign_id);
		}
		
		if ($inoption == 5){
			//campaign update only			
			echo $emailcampaignclass->update_campaign_ajax();
		}
		
		if ($inoption == 6){
			//campaign preview and html
			$campaign_id = round($_REQUEST["campaign_id"], 0);
			$tabid = round($_REQUEST["tabid"], 0);
			
			$mode = 1;
			if ($tabid == 2){
				$mode = 2;
			}
			
			echo $emailcampaignclass->display_email_campaign($campaign_id, $mode);
		}
	}
	
	if ($az == 13){
		//manufacturer model group and view with sorting
		
		$inoption = round($_REQUEST["inoption"], 0);
		if ($inoption == 1){
			//manage Group
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$group_name = $_REQUEST["group_name"];
			$ms = round($_REQUEST["ms"], 0);
			
			echo $adm->manage_boat_model_group($manufacturer_id, $group_name, $ms);
		}
		
		if ($inoption == 2){
			//group sort
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$adm->boat_model_group_sort($manufacturer_id);
		}
		
		if ($inoption == 3){
			//group delete
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$group_id = round($_REQUEST["group_id"], 0);			
			echo $adm->boat_model_group_delete($manufacturer_id, $group_id);
		}
		
		if ($inoption == 4){
			//display model assign to group
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$group_id = round($_REQUEST["group_id"], 0);
			echo $adm->group_boat_list_ajax_call(2, $manufacturer_id, $group_id);
		}
		
		if ($inoption == 5){
			//model assign to group
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$boat_id = round($_REQUEST["boat_id"], 0);
			$group_id = round($_REQUEST["group_id"], 0);
			$adm->boat_add_group($manufacturer_id, $boat_id, $group_id);			
			echo $adm->group_boat_list_ajax_call(2, $manufacturer_id, $group_id, $view_id);
		}
		
		if ($inoption == 6){
			//model assign sort
			$adm->update_group_boat_list_rank();
		}
		
		if ($inoption == 7){
			//remove model from group
			$manufacturer_id = round($_REQUEST["manufacturer_id"], 0);
			$boat_id = round($_REQUEST["boat_id"], 0);
			$group_id = round($_REQUEST["group_id"], 0);
			$adm->boat_remove_group($manufacturer_id, $boat_id, $group_id);			
			echo $adm->group_boat_list_ajax_call(1, $manufacturer_id, $group_id);
		}
		
		if ($inoption == 15){
			//get model group list for selected make
			$makeid = round($_REQUEST["makeid"], 0);
			echo $ymclass->get_manufacturer_model_group_list_raw($makeid);
		}
		
	}
	
	if ($az == 10){
		//main slider / user profile image
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//insert/update slider						
			echo $sliderclass->slider_insert_update();
		}
		
		if ($inoption == 2){
			//display image crop option
			$slider_id = round($_REQUEST["slider_id"], 0);
			echo $sliderclass->display_slider_image_crop_option($slider_id);
		}
		
		if ($inoption == 3){
			//process crop
			$slider_id = round($_REQUEST["slider_id"], 0);
			echo $sliderclass->process_crop($slider_id);
		}
		
		if ($inoption == 10){
			//process crop
			$user_id = round($_REQUEST["user_id"], 0);
			echo $yachtclass->user_process_crop($user_id);
		}
	}
	
	if ($az == 11){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//make sidebar					
			echo $adm->update_manufacturer_sidebar();
		}
	}
	
	if ($az == 24){
		//generate shortcode - editor
		echo $adm->get_shortcode_list();
	}
	
	if ($az == 25){
		//generate sitemap and submit to google
		echo $sitemapclass->generate_sitemap_main();
	}
	
	if ($az == 26){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//display chosen image details
			$ctype = round($_REQUEST["ctype"], 0);
			$arpoint = round($_REQUEST["arpoint"], 0);
			echo $medialibraryclass->selected_media_files($ctype, $arpoint);
		}
		
		if ($inoption == 2){
			//delete chosen image details
			$ctype = round($_REQUEST["ctype"], 0);
			$arpoint = round($_REQUEST["arpoint"], 0);
			echo $medialibraryclass->delete_media_files($ctype, $arpoint);
		}
		
		if ($inoption == 3){
			//display all media after upload
			echo $medialibraryclass->display_all_media(1);
		}	
	}
	
	if ($az == 27){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//boat assign to tradi-in
			$boat_id = round($_REQUEST["boat_id"], 0);
			$yachtclass->boat_add_trade_in($boat_id);			
			echo $yachtclass->boat_trade_in_list_ajax_call(2);
		}
		
		if ($inoption == 3){
			//remove boat from trade in
			$boat_id = round($_REQUEST["boat_id"], 0);
			$yachtclass->boat_remove_trade_in($boat_id);			
			echo $yachtclass->boat_trade_in_list_ajax_call(1);
		}
		
		if ($inoption == 4){
			//available boat search - Trade IN
			echo $yachtclass->boat_search_for_trade_in_ajax();
		}
		
		
		
		if ($inoption == 5){
			//boat assign to in stock
			$boat_id = round($_REQUEST["boat_id"], 0);
			$yachtclass->boat_add_in_stock($boat_id);			
			echo $yachtclass->boat_in_stock_list_ajax_call(2);
		}
		
		if ($inoption == 6){
			//remove boat from in stock
			$boat_id = round($_REQUEST["boat_id"], 0);
			$yachtclass->boat_remove_in_stock($boat_id);			
			echo $yachtclass->boat_in_stock_list_ajax_call(1);
		}
		
		if ($inoption == 7){
			//available boat search - in stock
			echo $yachtclass->boat_search_for_in_stock_ajax();
		}
		
		
		if ($inoption == 8){
			//boat assign to featured
			$categoryid = round($_REQUEST["categoryid"], 0);
			$boat_id = round($_REQUEST["boat_id"], 0);
			$fea_day_no = round($_POST["fea_day_no"], 0);
			$display_home = round($_POST["display_home"], 0);
			
			$param = array(
				"boat_id" => $boat_id,
				"fea_day_no" => $fea_day_no,
				"categoryid" => $categoryid,
				"display_home" => $display_home
			);
			
			$yachtclass->boat_add_featured($param);			
			echo $yachtclass->boat_featured_ajax_call(2, $categoryid);
		}
		
		if ($inoption == 9){
			//remove boat from featured
			$categoryid = round($_REQUEST["categoryid"], 0);	
			$boat_id = round($_REQUEST["boat_id"], 0);		
			$yachtclass->boat_remove_featured($boat_id);			
			echo $yachtclass->boat_featured_ajax_call(1, $categoryid);
		}
		
		if ($inoption == 10){
			//available boat search - featured
			echo $yachtclass->boat_search_for_featured_ajax();
		}
	}
	
	if ($az == 39){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//delete fav
			$yid = round($_POST["yid"], 0);
			$u = round($_POST["u"], 0);
			$yachtclass->delete_user_yacht_favorites($yid, $u);
		}
		
		if ($inoption == 2){
			//delete yacht finder
			$boatwatchercode = $_POST["boatwatchercode"];
			$boatwatcherclass->boat_watcher_delete_backend($boatwatchercode);
		}
	}
	
	if ($az == 700){
		$inoption = round($_REQUEST["inoption"], 0);
		
		if ($inoption == 1){
			//model image display
			$ms = round($_POST["ms"], 0);
			$make_id = round($_POST["make_id"], 0);
			$photocategoryid = round($_POST["photocategoryid"], 0);
			echo $modelclass->model_image_display_list($ms, $make_id, $photocategoryid);
		}
		
		if ($inoption == 2){
			//model image delete
			$imid = $_POST["imid"];
			$modelclass->delete_model_image_ajax_call($imid);					
			echo 'y';
		}
		
		if ($inoption == 3){
			//model image sort
			$modelclass->update_model_image_rank();
		}
		
		if ($inoption == 4){
			//model image rotate
			echo $modelclass->rotate_model_image();
		}
		
		if ($inoption == 5){
			//model image - remove original
			echo $modelclass->remove_original_model_image();
		}
	}
}
?>