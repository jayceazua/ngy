<?php
class MediaLibraryclass {
	private $default_selected_media_text = '<span class=\'initialtext\'>Please select media to get details</span>';
	
	public function get_media_path(){
		global $cm;
		$media_root_folder = "cmsfile";
		$image_folder = "contentimages";
		$files_folder = "contentfiles";
		$start_path = $_SERVER['DOCUMENT_ROOT'] . $cm->folder_for_seo . $media_root_folder . "/";
		
		$image_start_path_abs = $start_path . $image_folder;
		$file_start_path_abs = $start_path . $files_folder;
		
		$image_start_path = $cm->folder_for_seo . $media_root_folder . "/" . $image_folder . "/";
		$file_start_path = $cm->folder_for_seo . $media_root_folder . "/" . $files_folder . "/";
		
		$path_info_ar = array(
			"media_root_folder" => $media_root_folder,
			"image_folder" => $image_folder,
			"files_folder" => $files_folder,
			"start_path" => $start_path,
			"image_start_path_abs" => $image_start_path_abs,
			"file_start_path_abs" => $file_start_path_abs,
			"image_start_path" => $image_start_path,
			"file_start_path" => $file_start_path
		);
		
		return (object)$path_info_ar;	
	}
	
	public function sortArray( $data, $field ) {
		$field = (array) $field;
		uasort( $data, function($a, $b) use($field) {
			$retval = 0;
			foreach( $field as $fieldname ) {
				if( $retval == 0 ) $retval = strnatcmp( $a[$fieldname], $b[$fieldname] );
			}
			return $retval;
		} );
		return $data;
	}
	
	public function get_all_files($path){
		if ( ! is_dir( $path ) ){
			return false;
		}
		
		$results = scandir( $path );
		foreach ( $results as $result ) {
			if ( '.' == $result[0] ){
				continue;
			}
			
			if ( '..' == $result[0] ){
				continue;
			}
			
			if ( 'Thumbs.db' == $result ){
				continue;
			}
			
			if ( 'index.html' == $result ){
				continue;
			}
			
			if ( '.htaccess' == $result ){
				continue;
			}
			//$files[ $result ] = $path . '/' . $result;
			$files[ $result ] = filemtime($path . '/' . $result);
		}
	
		$files_n = array();
		foreach($files as $key => $val){
			$files_n[$key] = $path . '/' . $key;
		}
		
		return $files_n;
	}
	
	public function get_all_media(){
		global $cm;		
		$all_images = array();
		$all_files = array();
		$path_info_ar = $this->get_media_path();
		
		//Collect Image files
		$path = $path_info_ar->image_start_path_abs;	
		$files = (array) $this->get_all_files( $path );
		foreach ( $files as $file => $full_path ){
			$all_images[] = array(
				"date" => filemtime($full_path),
				"name" => $file,
				"path" => $path_info_ar->image_start_path . $file,
				"pathabs" => $full_path
			);		
		}
		
		//Collect Other files
		$path = $path_info_ar->file_start_path_abs;
		$files = (array) $this->get_all_files( $path );
		foreach ( $files as $file => $full_path ) {
			$all_files[] = array(
				"date" => filemtime($full_path),
				"name" => $file,
				"path" => $path_info_ar->file_start_path . $file,
				"pathabs" => $full_path
			);			
		}
		
		//Combine
		$all_media_files = array(
			"images" => $all_images,
			"files" => $all_files
		);
		
		return json_encode($all_media_files);
	}
	
	//display all files
	public function display_all_media($ajax = 0){
		global $cm, $fle;
		$returntext = '';
		$image_text = '';
		$files_text = '';
		
		$all_media_files = json_decode($this->get_all_media());
		$all_images = $all_media_files->images;
		$all_files = $all_media_files->files;
		
		$all_images_count = count($all_images);
		$all_files_count = count($all_files);
		
		if ($all_images_count > 0 OR $all_files_count > 0){

			$returntext = '	
			<ul class="mediafileslist">
			';
			
			$counter = 0;
			foreach($all_images as $all_images_row){
				$imgname = $all_images_row->name;
				$imgpath = $all_images_row->path;				
				$returntext .= '
				<li class="imgonly row1-'. $counter .'">
					<div ctype="1" arpoint="'. $counter .'" class="medialist box clearfixmain">
						<div class="thumb"><img src="'. $imgpath .'" /></div>
						<div class="mediacon">'. $imgname .'</div>
					</div>				
				</li>';
				$counter++;
			}
			
			$counter = 0;
			foreach($all_files as $all_files_row){
				$filename = $all_files_row->name;
				$filepath = $all_files_row->path;
				//$f_ext = $fle->get_file_extension($filename);
				
				$name_ar = explode(".", $filename);
				$f_ext = array_pop($name_ar);				
				
				$imgpath = 'images/media/' . $f_ext . '.gif';							
				$returntext .= '
				<li class="filesonly row2-'. $counter .'">
					<div ctype="2" arpoint="'. $counter .'" class="medialist box clearfixmain">
						<div class="thumb"><img src="'. $imgpath .'" /></div>
						<div class="mediacon">'. $filename .'</div>
					</div>				
				</li>';
				$counter++;
			}
			
			$returntext .= '			
			</ul>
			';
		}
		
		if ($ajax == 1){
			$return_ar = array(
				"returntext" => $returntext
			);
			
			return json_encode($return_ar);
		}else{
			return $returntext;
		}
	}
	
	public function display_all_media_main(){
		global $cm, $fle;
		$returntext = '';
		
		$media_text = $this->display_all_media();
		//if ($media_text != ""){
			$returntext .= '
			<div class="mediafilesholder clearfixmain">
				<ul class="mediatab">
					<li><a href="javascript:void(0);" ctype="0" class="mediafilechoose mediafilechoose0 active">All</a></li>
					<li><a href="javascript:void(0);" ctype="1" class="mediafilechoose mediafilechoose1">Images</a></li>
					<li><a href="javascript:void(0);" ctype="2" class="mediafilechoose mediafilechoose2">Other Files</a></li>
				</ul>
				
				<div class="mediafileslistholder clearfixmain">
					<div class="mediafileslistholderleft">
					'. $media_text .'
					</div>
					
					<div class="mediafileslistholderright">'. $this->default_selected_media_text .'</div>
				</div>
			</div>	
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				$(".mediafilesholder").off("click", ".mediafilechoose").on("click", ".mediafilechoose", function(){
					var ctype = parseInt($(this).attr("ctype"));
					$(".mediafilechoose").removeClass("active");
					$(this).addClass("active");
					
					$(".filesonly").removeClass("com_none");
					$(".imgonly").removeClass("com_none");
					
					if (ctype == 1){
						$(".filesonly").addClass("com_none");
					}
					
					if (ctype == 2){
						$(".imgonly").addClass("com_none");
					}					
				});
				
				$(".mediafilesholder").off("click", ".medialist").on("click", ".medialist", function(){
					$(".waitdiv").show();
					$(".waitmessage").html("Please wait....");
	
					var ctype = parseInt($(this).attr("ctype"));
					var arpoint = parseInt($(this).attr("arpoint"));
						
					var b_sURL = "onlyadminajax.php";
					$.post(b_sURL,
					{ 	
						az:26,
						inoption:1,
						ctype:ctype,
						arpoint:arpoint,
						dataType: "json"
					},
					function(data){
						data = $.parseJSON(data);
						returntext = data.returntext;
						$(".mediafileslistholderright").html(returntext);
						
						$(".waitdiv").hide();
						$(".waitmessage").html("");
					});					
				});
				
				$(".mediafilesholder").off("click", ".chosenmediadel").on("click", ".chosenmediadel", function(){
					var ctype = parseInt($(this).attr("ctype"));
					var arpoint = parseInt($(this).attr("arpoint"));
						
					var b_sURL = "onlyadminajax.php";
					$.post(b_sURL,
					{ 	
						az:26,
						inoption:2,
						ctype:ctype,
						arpoint:arpoint
					},
					function(data){
						$(".row" + ctype + "-" + arpoint).hide();
						$(".mediafileslistholderright").html("'. $this->default_selected_media_text .'");
					});	
				});
			});
			</script>
			';
		//}
		
		return $returntext;
	}
	
	//display chosen image details
	public function selected_media_files($ctype, $arpoint){
		global $cm, $fle;
		$returntext = '';
		
		$path_info_ar = $this->get_media_path();
		$all_media_files = json_decode($this->get_all_media());		
		if ($ctype == 2){
			//other files
			$all_files = $all_media_files->files;
			$filename = $all_files[$arpoint]->name;
			$filepath = $all_files[$arpoint]->path;
			$name_ar = explode(".", $filename);
			$f_ext = array_pop($name_ar);
			$imgpath = 'images/media/' . $f_ext . '.gif';
				
			$fullpath = $cm->site_url . '/' . $path_info_ar ->media_root_folder . '/' . $path_info_ar->files_folder . '/' . $filename;
			
			$returntext = '
			<div class="chosenmedia clearfixmain"><img src="'. $imgpath .'" /></div>
			
			<div class="chosenmediaurl clearfixmain">
			<span class="chosenmedia_title">Full URL</span>
			<span class="chosenmedia_data"><a href="'. $fullpath .'" target="_blank">'. $fullpath .'</a></span>
			</div>
			
			<div class="chosenmediaurl clearfixmain">
			<span class="chosenmedia_title">Relative Path</span>
			<span class="chosenmedia_data">'. $filepath .'</span>
			</div>
			
			<div class="chosenmediadelmain clearfixmain"><a ctype="'. $ctype .'" arpoint="'. $arpoint .'" class="chosenmediadel" href="javascript:void(0);"><img src="images/del.png" /></a></div>
			';
		}else{
			//images
			$all_images = $all_media_files->images;
			$imgname = $all_images[$arpoint]->name;
			$imgpath = $all_images[$arpoint]->path;
			$fullpath = $cm->site_url . '/' . $path_info_ar ->media_root_folder . '/' . $path_info_ar->image_folder . '/' . $imgname;
			
			$returntext = '
			<div class="chosenmedia clearfixmain"><img src="'. $imgpath .'" /></div>
			
			<div class="chosenmediaurl clearfixmain">
			<span class="chosenmedia_title">Full URL</span>
			<span class="chosenmedia_data"><a href="'. $fullpath .'" target="_blank">'. $fullpath .'</a></span>
			</div>
			
			<div class="chosenmediaurl clearfixmain">
			<span class="chosenmedia_title">Relative Path</span>
			<span class="chosenmedia_data">'. $imgpath .'</span>
			</div>
			
			<div class="chosenmediadelmain clearfixmain"><a ctype="'. $ctype .'" arpoint="'. $arpoint .'" class="chosenmediadel" href="javascript:void(0);"><img src="images/del.png" /></a></div>
			';
		}
		
		$returnar = array(
			"path" => $imgpath,
			"name" => $imgname,
			"returntext" => $returntext
		);
		
		return json_encode($returnar);
	}
	
	//delete media files
	public function delete_media_files($ctype, $arpoint){
		global $cm, $fle;		
		$path_info_ar = $this->get_media_path();
		$all_media_files = json_decode($this->get_all_media());
		
		if ($ctype == 2){
			//other files
			$all_files = $all_media_files->files;
			$delete_file = $all_files[$arpoint]->pathabs;			
		}else{
			//images
			$all_images = $all_media_files->images;
			$delete_file = $all_images[$arpoint]->pathabs;
		}
		
		$fle->filedelete($delete_file);
	}
	
	//upload media files
	public function upload_media_files(){
		global $cm, $fle;
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$filename = $_FILES['file']['name'];
			$path_info_ar = $this->get_media_path();
			if ($filename != ""){
				$if_image = $fle->check_file_ext($cm->allow_image_ext, $filename);
				if ($if_image == "y"){
					//image upload
					$upload_path = $path_info_ar->image_start_path_abs . "/";
					//$upload_path = "../" . $path_info_ar->media_root_folder . "/" . $path_info_ar->image_folder . "/";
				}else{
					//other file upload
					$upload_path = $path_info_ar->file_start_path_abs . "/";
				}
				
				$upload_path = $upload_path . $cm->filtertextdisplay($filename);
				$fle->fileupload($_FILES['file']['tmp_name'], $upload_path);
				echo($_POST['index']);
			}
		}
	}
}
?>