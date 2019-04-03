<?php
class Templateclass {
	public $pagetemplate = "pagetemplate";
	
	public function get_files($type, $path){
		if ( ! is_dir( $path ) ){
			return false;
		}
		
		$results = scandir( $path );
		foreach ( $results as $result ) {
			if ( '.' == $result[0] ){
				continue;
			}
			$files[ $result ] = $path . '/' . $result;
		}
		return $files;
	}
	
	public function get_page_templates(){
		global $cm;
		$page_templates = array();
		$path = $_SERVER['DOCUMENT_ROOT'] . $cm->folder_for_seo . $this->pagetemplate;
		
		$files = (array) $this->get_files( 'php', $path );
		foreach ( $files as $file => $full_path ) {
			if ( ! preg_match( '|Template Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ){
				continue;
			}
			$page_templates[ $file ] = $header[1];
		}
		
		return $page_templates;
	}
	
	public function page_template_dropdown($templateselected = '') {
		$returntext = '';
		$templates = $this->get_page_templates();
		foreach ( $templates as $tkey => $template ) {
			$vck = '';
		  	if ($templateselected == $tkey){ $vck = ' selected="selected"'; }
			$returntext .= '
			<option value="'. $tkey .'"'. $vck .'>'. $template .'</option>
			';
		}
		return $returntext;
	}
	
	public function get_sidebar_combo($sidebar_id = 0){
		global $db;
		$returntext = '';
		$vsql = "select id, name from tbl_box_content where status = 'y' and sidebar = 1 order by rank";
		$vresult = $db->fetch_all_array($vsql);
		foreach($vresult as $vrow){
		  $c_id = $vrow['id'];  
		  $cname = $vrow['name'];
		  
		  $vck = '';
		  if ($sidebar_id == $c_id){ $vck = ' selected="selected"'; }		  
		  $returntext .= '
		  <option value="'. $c_id .'"'. $vck .'>'. $cname .'</option>
		  '; 
		}
		return $returntext;
	}
}
?>