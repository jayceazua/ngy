<?php
class Sitemapclass {
	
	//curl
	public function myCurl($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);			
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $httpCode;
	}

	//sitemap url
	public function get_site_map_url(){
		global $cm;
		$sitemapurl = $cm->site_url . "/sitemap.xml";
		return $sitemapurl;
	}
	
	//generate sitemap
	public function generate_sitemap(){
		global $db, $cm, $yachtclass;		
		$mainurl = $cm->site_url;
		
		$xml_content = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
		';
		
		//CMS Page Collection
		$sql = "select id, page_type, pgnm from tbl_page where page_type = 1 and status = 'y' and only_menu = 0 order by page_level, rank";
		$result = $db->fetch_all_array($sql);
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$priority = '1.00';
			if ($id == 1){
				$priority = '1.00';
				$urls = $mainurl;
			}else{
				$priority = '0.80';
				$urls = $mainurl . '/' . $pgnm;
			}
			
			
			$xml_content .= '<url>
			<loc>'. $urls .'</loc>
			<changefreq>monthly</changefreq>
			<priority>'. $priority .'</priority>
			</url>
			';
		}
		//end
		
		//Boat collection
		$sql = "select a.id from tbl_yacht as a, tbl_yacht_dimensions_weight as c where a.id = c.yacht_id and a.status_id IN (1,3) and a.display_upto >= CURDATE() order by c.length";
		$result = $db->fetch_all_array($sql);
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$priority = '0.80';
			
			//$urls = $mainurl . $cm->get_page_url($id, "yacht");
			$b_ar = array(
				"boatid" => $id, 
				"makeid" => 0, 
				"ownboat" => 0, 
				"feed_id" => "", 
				"getdet" => 1
			);
			$urls = $mainurl . $yachtclass->get_boat_details_url($b_ar);
			
			$xml_content .= '<url>
			<loc>'. $urls .'</loc>
			<changefreq>monthly</changefreq>
			<priority>'. $priority .'</priority>
			</url>
			';
		}
		//end
		
		//Blog Page Collection
		$sql = "select id, slug from tbl_blog where status_id = 1 order by reg_date desc";
		$result = $db->fetch_all_array($sql);
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$priority = '0.80';
			
			$urls = $mainurl . $cm->get_page_url($slug, "blog");
			$xml_content .= '<url>
			<loc>'. $urls .'</loc>
			<changefreq>monthly</changefreq>
			<priority>'. $priority .'</priority>
			</url>
			';
		}
		//end
		
		//User profile collection
		$sql = "select id from tbl_user where status_id = 2 and front_display = 1 order by rank";
		$result = $db->fetch_all_array($sql);
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$priority = '0.80';
			
			$urls = $mainurl . $cm->get_page_url($id, 'user');
			$xml_content .= '<url>
			<loc>'. $urls .'</loc>
			<changefreq>monthly</changefreq>
			<priority>'. $priority .'</priority>
			</url>
			';
		}
		//end
		
		//Location Office collection
		$sql = "select id, slug from tbl_location_office where status_id = 1 order by default_location desc, reg_date desc";
		$result = $db->fetch_all_array($sql);
		
		foreach($result as $row){
			foreach($row AS $key => $val){
				${$key} = $cm->filtertextdisplay($val);
			}
			
			$priority = '0.80';
			
			$urls = $mainurl . $cm->get_page_url($slug, 'locationprofile');
			$xml_content .= '<url>
			<loc>'. $urls .'</loc>
			<changefreq>monthly</changefreq>
			<priority>'. $priority .'</priority>
			</url>
			';
		}
		//end
		
		$xml_content .= '</urlset>';
		$xml_content = trim($xml_content);		
		return $xml_content;
	}
	
	public function submit_sitemap_google(){
		global $cm;
		$sitemapurl = $this->get_site_map_url();
		$url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=".$sitemapurl;
		$data = file_get_contents($url);
		$status = ( strpos($data,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
		return $status;
	}

	public function submit_sitemap_bing(){
		global $cm;
		$sitemapurl = $this->get_site_map_url();
		$url = "http://www.bing.com/webmaster/ping.aspx?siteMap=".$sitemapurl;
		$data = file_get_contents($url);
		$status = ( strpos($data,"Thanks for submitting your Sitemap") !== false ) ? "OK" : "ERROR";
		return $status;
	}
	
	public function generate_sitemap_main(){
		global $cm; 
		$displaytext = '';
		
		//collect sitemap data
		$xml_content = $this->generate_sitemap();
				
		//save sitemap to root folder
		$filename = "sitemap.xml";
		$fn = "../" . $filename;
		file_put_contents($fn, $xml_content);
		
		//submit to google
		$returnCode = $this->submit_sitemap_google();
		$displaytext .= '<p>Google Sitemaps has been pinged (Status: '. $returnCode .').</p>';

		//submit to bing
		$returnCode = $this->submit_sitemap_bing();
		$displaytext .= '<p>Bing Sitemaps has been pinged (Status: '. $returnCode .').</p>';
		
		//return data
		$returnval = array(
			'displaytext' => $displaytext
		);
		return json_encode($returnval);
	}
}
?>