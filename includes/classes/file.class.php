<?php
class Fileclass {
	 
	 //image resize and save
	 public function new_image($img, $w, $h, $target_path, $new_name){

		$percent = 0;
		$constrain = "y";
		
		if ($w == 0 OR $h == 0){ $constrain = ""; }
		
		$x = @getimagesize($img);
		$sw = $x[0];
		$sh = $x[1];
		
		$imagemime = $x[2];
		
		if ($percent > 0) {
			// calculate resized height and width if percent is defined
			$percent = $percent * 0.01;
			$w = $sw * $percent;
			$h = $sh * $percent;
		} else {
			if ($w > 0 AND $h == 0) {
				// autocompute height if only width is set
				$h = (100 / ($sw / $w)) * .01;
				$h = @round ($sh * $h);
			} elseif ($h > 0 AND $w == 0) {
				// autocompute width if only height is set
				$w = (100 / ($sh / $h)) * .01;
				$w = @round ($sw * $w);
			} elseif ($w > 0 AND $h > 0 AND $constrain == "y") {
				// get the smaller resulting image dimension if both height
				// and width are set and $constrain is also set
				if (($sw>$w) OR ($sh>$h)){
					$hx = (100 / ($sw / $w)) * .01;
					$hx = @round ($sh * $hx);
		
					$wx = (100 / ($sh / $h)) * .01;
					$wx = @round ($sw * $wx);
		
					if ($hx < $h) {
						$h = (100 / ($sw / $w)) * .01;
						$h = @round ($sh * $h);
					} else {
						$w = (100 / ($sh / $h)) * .01;
						$w = @round ($sw * $w);
					}
				}else{
					$w = $sw;
					$h = $sh;
				}
			}
		}
		
		if ($imagemime == 1){
		 $im = @ImageCreateFromGIF ($img);
		}elseif ($imagemime == 2){
		 $im = @ImageCreateFromJPEG ($img);
		}elseif ($imagemime == 3){
		 $im = @ImageCreateFromPNG ($img);
		}else{
		 $im = false;
		}
		
		if (!$im) {			
			readfile ($img);
		} else {			
			$thumb = @ImageCreateTrueColor ($w, $h);	
			
			if(($imagemime == 2) OR ($imagemime==3)){
			  imagealphablending($thumb, false);
			  imagesavealpha($thumb,true);
			  $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			  imagefilledrectangle($thumb, 0, 0, $w, $h, $transparent);
			}

			@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
			
			$total_path_s = $target_path . $new_name;
			if ($imagemime == 1){
			 //imagecolortransparent($thumb,"0-0-0");
			 @ImageGIF ($thumb, $total_path_s, 100);
			}elseif ($imagemime == 2){
			 @ImageJPEG ($thumb, $total_path_s, 80);
			}elseif ($imagemime == 3){
			 @ImagePNG ($thumb, $total_path_s, 9);
			}
			chmod($total_path_s, 0755);
		}
    }
	
	public function new_image_box($img, $w, $h, $target_path, $new_name, $rotateimage = 0){

		$percent = 0;
		$constrain = "y";
		$ow = $w;
		$oh = $h;
		
		if ($w == 0 OR $h == 0){ $constrain = ""; }
		
		$x = @getimagesize($img);
		$sw = $x[0];
		$sh = $x[1];
		
		$imagemime = $x[2];
		
		if ($imagemime == 1){
			$im = @ImageCreateFromGIF ($img);
		}elseif ($imagemime == 2){
			$im = @ImageCreateFromJPEG ($img);
		}elseif ($imagemime == 3){
			$im = @ImageCreateFromPNG ($img);
		}else{
			$im = false;
		}
		
		if ($rotateimage > 0){
			$im = imagerotate($im, $rotateimage, 0);
			$sw = $x[1];
			$sh = $x[0];
		}
		
		if ($percent > 0) {
			// calculate resized height and width if percent is defined
			$percent = $percent * 0.01;
			$w = $sw * $percent;
			$h = $sh * $percent;
		} else {
			if ($w > 0 AND $h == 0) {
				// autocompute height if only width is set
				$h = (100 / ($sw / $w)) * .01;
				$h = @round ($sh * $h);
			} elseif ($h > 0 AND $w == 0) {
				// autocompute width if only height is set
				$w = (100 / ($sh / $h)) * .01;
				$w = @round ($sw * $w);
			} elseif ($w > 0 AND $h > 0 AND $constrain == "y") {
				// get the smaller resulting image dimension if both height
				// and width are set and $constrain is also set
				if (($sw>$w) OR ($sh>$h)){
					$hx = (100 / ($sw / $w)) * .01;
					$hx = @round ($sh * $hx);
		
					$wx = (100 / ($sh / $h)) * .01;
					$wx = @round ($sw * $wx);
		
					if ($hx < $h) {
						$h = (100 / ($sw / $w)) * .01;
						$h = @round ($sh * $h);
					} else {
						$w = (100 / ($sh / $h)) * .01;
						$w = @round ($sw * $w);
					}
				}else{
					$w = $sw;
					$h = $sh;
				}
			}
		}
		
		if (!$im) {			
			readfile ($img);
		} else {			
			$thumb = @ImageCreateTrueColor ($w, $h);	
			
			if(($imagemime == 2) OR ($imagemime==3)){
			  imagealphablending($thumb, false);
			  imagesavealpha($thumb,true);
			  $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
			  imagefilledrectangle($thumb, 0, 0, $w, $h, $transparent);
			}
			@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
			
			
			$left_i = ($ow - $w) / 2;
			$top_i = ($oh - $h) / 2;
			$dst_image = @ImageCreateTrueColor ($ow, $oh);	
			imagealphablending($dst_image, false);
			imagesavealpha($dst_image, true);
			$transparent = imagecolorallocatealpha($dst_image, 255, 255, 255, 127);
			imagefilledrectangle($dst_image, 0, 0, $ow, $oh, $transparent);
			imagecopy($dst_image, $thumb, $left_i, $top_i, 0, 0, $w, $h);
			
			
			$total_path_s = $target_path . $new_name;
			if ($imagemime == 1){
			 //imagecolortransparent($thumb,"0-0-0");
			 @ImageGIF ($dst_image, $total_path_s, 100);
			}elseif ($imagemime == 2){
			 @ImageJPEG ($dst_image, $total_path_s, 80);
			}elseif ($imagemime == 3){
			 @ImagePNG ($dst_image, $total_path_s, 9);
			}
			chmod($total_path_s, 0755);
		}
    }

    public function new_image_fixed($img, $w, $h, $target_path, $new_name, $rotateimage = 0){

        $desired_image_width = $w;
        $desired_image_height = $h;
        $source_path = $img;

        $x = @getimagesize($source_path);
        $source_width = $x[0];
        $source_height = $x[1];
        $source_type = $x[2];

        if ($source_type == 1){
            $source_gdim = @ImageCreateFromGIF ($source_path);
        }elseif ($source_type == 2){
            $source_gdim = @ImageCreateFromJPEG ($source_path);
        }elseif ($source_type == 3){
            $source_gdim = @ImageCreateFromPNG ($source_path);
        }else{
            $source_gdim = false;
        }
		
		if ($rotateimage > 0){
			$source_gdim = imagerotate($source_gdim, $rotateimage, 0);
			$source_width = $x[1];
        	$source_height = $x[0];
		}

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $desired_image_width / $desired_image_height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            $temp_height = $desired_image_height;
            $temp_width = ( int ) ($desired_image_height * $source_aspect_ratio);
        } else {
            $temp_width = $desired_image_width;
            $temp_height = ( int ) ($desired_image_width / $source_aspect_ratio);
        }

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        if(($source_type == 1) OR ($source_type == 3)){
            imagealphablending($temp_gdim, false);
            imagesavealpha($temp_gdim,true);
            $transparent = imagecolorallocatealpha($temp_gdim, 255, 255, 255, 127);
            imagefilledrectangle($temp_gdim, 0, 0, $temp_width, $temp_height, $transparent);
        }
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0, 0,
            0, 0,
            $temp_width, $temp_height,
            $source_width, $source_height
        );

        $x0 = ($temp_width - $desired_image_width) / 2;
        $y0 = ($temp_height - $desired_image_height) / 2;
        $desired_gdim = imagecreatetruecolor($desired_image_width, $desired_image_height);
        if(($source_type == 1) OR ($source_type == 3)){
            imagealphablending($desired_gdim, false);
            imagesavealpha($desired_gdim,true);
            $transparent = imagecolorallocatealpha($temp_gdim, 255, 255, 255, 127);
            imagefilledrectangle($desired_gdim, 0, 0, $desired_image_width, $desired_image_height, $transparent);
        }
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0, 0,
            $x0, $y0,
            $desired_image_width, $desired_image_height
        );

        $total_path_s = $target_path . $new_name;
        if ($source_type == 1){
            //imagecolortransparent($thumb,"0-0-0");
            @ImageGIF ($desired_gdim, $total_path_s, 100);
        }elseif ($source_type == 2){
            @ImageJPEG ($desired_gdim, $total_path_s, 80);
        }elseif ($source_type == 3){
            @ImagePNG ($desired_gdim, $total_path_s, 9);
        }
        chmod($total_path_s, 0755);
    }
	
	//upload file class
	public function check_file_ext($al_file_ext, $filename){
      $f_ext = $this->get_file_extension($filename);
      $al_file_ext_ar = explode(", ", $al_file_ext);

      $returntxt = "n";
      if (in_array($f_ext, $al_file_ext_ar)) {
            $returntxt = "y";
      }      
      return $returntxt;
    }
    
	public function uploadfilename($ab){
		$ab = str_replace("'","",$ab);
		$ab = str_replace(" ","",$ab);
		$ab = str_replace("&","",$ab);
		$ab = str_replace("&amp;","",$ab);
		$ab = str_replace("#","_",$ab); 
		$ab = str_replace("%","",$ab);
		return $ab;		
	}
	
	public function fileupload($fileinfo, $target_path){
		move_uploaded_file($fileinfo, $target_path);
	}
	
	public function filedelete($filepath){
		unlink($filepath);
	}
    
    public function get_file_extension($filenm){
        $strFileSuffix = strtolower(substr($filenm, strrpos($filenm, ".")));
        return $strFileSuffix;
    }
	
	public function pdf_to_image($filename1, $target_path_folder, $save_folder, $iwidth, $iheight){
		$fa1 = strrpos($filename1,".");
	    $fa1 = substr($filename1,0,$fa1);
		
		$fn = $target_path_folder . $fa1;
		$fn1 = $save_folder.$fa1;
		exec("convert -resize ".$iwidth."x".$iheight." $fn.pdf[0] $fn1.jpg");	
		
		$fn1_final = $fa1 . ".jpg";
		return $fn1_final;
	}
	
	public function copy_folder($source, $destination){
		if(!is_dir($destination)){
			$oldumask = umask(0); 
			mkdir($destination, 0775);
			umask($oldumask);
		}
		
		$dir_handle = @opendir($source) or die("Unable to open");
		
		while ($file = readdir($dir_handle)){
			if($file!="." && $file!=".." && !is_dir("$source/$file")){ //if it is file
				copy("$source/$file","$destination/$file");
			}
			if($file!="." && $file!=".." && is_dir("$source/$file")){ //if it is folder
				$this->copy_folder("$source/$file","$destination/$file");
			}
		}
		
		closedir($dir_handle);
	}	
	
	public function remove_folder($destination){
		if ($destination == ""){
			return;
		}
		
		if (!is_dir($destination) || is_link($destination)) {
			unlink($destination);
			return;
		}
		
		$files = scandir($destination);
		foreach ($files as $file){
			if ($file != "." && $file != ".."){
				if (filetype($destination."/".$file) == "dir"){
					$this->remove_folder($destination."/".$file);
				}else{
					unlink($destination."/".$file);
				}
			}
		}
		rmdir($destination);		
	}
	
	
	//signature
	
	//convert to image
	public function base64_to_jpeg($base64_string, $output_file) {
		$ifp = @fopen($output_file, "wb");
	
		$data = explode(',', $base64_string);
	
		@fwrite($ifp, base64_decode($data[1]));
		@fclose($ifp);
		return $output_file;	
	}
	
	//create script text image
	public function create_image_from_text($text, $font, $font_size, $foldername){
		global $cm;
		
		// Create the image
		$image_width = 400;
		$image_height = 109;
		$im = imagecreatetruecolor($image_width, $image_height);
		
		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		$textcolor = imagecolorallocate($im, 0, 55, 103);
		
		//fill background
		imagefilledrectangle($im, 0, 0, $image_width, $image_height, $white);
		
		//font
		$font = "../fonts/" . $font;
		$angle = 0;
		
		//save to folder
		$target_path_main = "../" . $foldername . "/";
		$imagname = "sig-" . session_id() . ".jpg";
		$full_saved_path = $target_path_main . $imagname;
		
		// Get Bounding Box Size
		$text_box = imagettfbbox($font_size, $angle, $font, $text);
		
		// Get your Text Width and Height
		$text_width = $text_box[2 ]- $text_box[0];
		$text_height = $text_box[3] - $text_box[1];
		
		// Calculate coordinates of the text
		$x = ($image_width/2) - ($text_width/2);
		$y = ($image_height/2) - ($text_height/2);
		
		// Add the text
		imagettftext($im, $font_size, 0, $x, $y, $textcolor, $font, $text);
		@ImageJPEG ($im, $full_saved_path, 100);
		
		$uiq_code = $cm->campaignid(15);
		$imghtml = '<img src="'. $cm->folder_for_seo . $foldername .'/'. $imagname .'?a='. $uiq_code .'" />';
		
		//create array and return
		$returnar = array(
            'name' => $imagname,
			'fullpath' => $full_saved_path,
			'imghtml' => $imghtml,
			'classname' => 'rageitalic'
        );
		return json_encode($returnar);
	}
	
	//create different file name
	public function create_different_file_name($oldfilename){
		$f_ar = explode("t_y", $oldfilename);
		$f_ar_count = count($f_ar);	

		if ($f_ar_count > 1){
			$f_ar_1 = array_shift($f_ar);
			$filename = microtime(true) . "t_y" . implode("t_y", $f_ar);
		}else{
			$filename = microtime(true) . "t_y" . $oldfilename;
		}

		return $filename;
	}
	
	//rename file
	public function rename_existing_file($oldname, $newname){
		rename($oldname, $newname);
	}
}
?>