<?php
class Instagramclass {
	
	private $accesstoken = '350149725.1677ed0.c40193aaa58b421584fcb56c02baaa07';
	private $mediafetch = 24;
	
	public function get_instagram_feed($param = array(), $az = 0){
		global $cm;
		$returntext = '';
		$dcon = $this->mediafetch;
		$instaurl = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $this->accesstoken . '&count=' . $dcon;
		
		$p = round($param["p"], 0);
		$max_id_passed = $param["max_id_passed"];
		if ($max_id_passed != ""){
			$instaurl .= '&max_id=' . $max_id_passed;
		}
		
		$instaResult= file_get_contents($instaurl);
		$insta = json_decode($instaResult);
		$max_id = $insta->pagination->next_max_id;
		
		$instadata = $insta->data;
		$found = count($instadata);
		
		$moreviewtext = '';	
		$instapoptext = '';
		
		if ($found > 0){
			if ($az == 0){ 
				$returntext .= '
				<ul id="listingholder-insta">'; 
			}
			
			$count = ($p - 1) * $dcon;
			foreach($instadata as $instarow){				
				$insta_created_time = $instarow->created_time;
				$insta_media_type = $instarow->type;
				
				$insta_img = $instarow->images->standard_resolution->url;
				$insta_video = $instarow->videos->standard_resolution->url;
				
				$insta_text = $instarow->caption->text;
				$insta_c_logo = $instarow->user->profile_picture;
				$insta_c_username = $instarow->user->username;
				
				$insta_likes = $instarow->likes->count;
				$insta_comments = $instarow->comments->count;
				$insta_links = $instarow->link;						
				
				$insta_created_time_display = $cm->timeAgo($insta_created_time);				
				$insta_main_link = "https://www.instagram.com/" . $insta_c_username . "/";
					
				$returntext .= '
				<li><a class="colorbox-inline" rel="colorboxgroup" href="#instapop'. $count .'">
					<div class="topimg2"><img src="'. $insta_img .'" /></div>
					<div class="imagehover">
						<div class="instametaholder clearfixmain">
							<div class="instameta instalike">'. $insta_likes .'</div>
							<div class="instameta instacomment">'. $insta_comments .'</div>
						</div>
					</div>
				</a></li>
				';
				
				$media_pop = '';
				if ($insta_media_type == "video"){
					$media_pop = '
					<div class="video-containerx clearfixmain">
					<video poster="'. $insta_img .'" controls>
						<source src="'. $insta_video .'" type="video/mp4">  
					 </video>
					</div>
					';
				}else{
					$media_pop = '<a href="'. $insta_links .'" target="_blank"><img class="full" src="'. $insta_img .'" /></a>';
				}
				
				$instapoptext .= '
				<div id="instapop'. $count .'" class="instapopcontent clearfixmain">
					<div class="homeleft clearfixmain">'. $media_pop .'</div>
					<div class="homeright clearfixmain">
						<div class="instapophead clearfixmain">
							<div class="instauserimage"><img src="'. $insta_c_logo .'" /></div>
							<div class="instausername"><a href="'. $insta_main_link .'" target="_blank">'. $insta_c_username .'</a></div>
						</div>
						
						<div class="instamedia clearfixmain">
							<div class="instamedialeft clearfixmain">
								<div class="instameta instalike">'. $insta_likes .'</div>
								<div class="instameta instacomment">'. $insta_comments .'</div>
							</div>
							<div class="instamediaright clearfixmain">'. $insta_created_time_display .'</div>
						</div>
						
						<p><strong>'. $insta_c_username .'</strong> '. $insta_text .'</p>
						<p><a class="arrow" href="'. $insta_links .'" target="_blank">More</a></p>
					</div>
				</div>
				';
				
				$count++;
			}			
			
			if ($az == 0){ 
				$returntext .= '
				</ul>
				'; 
			}
			
			$p++;
			if ($max_id != ""){
				$moreviewtext = '<a p="'. $p .'" m="'. $max_id .'" class="moreinstamedia button" href="javascript:void(0);">Load More</a>';
			}
		}
		
		$returnval = array(
			'found' => $found,
            'doc' => $returntext,
			'instapoptext' => $instapoptext,
            'moreviewtext' => $moreviewtext
        );
        return json_encode($returnval);
		
	}
	
	public function display_instagram_feed_main(){
		$returntext = '';		
		$param = array(
			"p" => 1,
			"max_id_passed" => ''
		);
		
		$retval = json_decode($this->get_instagram_feed($param));
		$found = $retval->found;
		$doc = $retval->doc;
		$instapoptext = $retval->instapoptext;
		$moreviewtext = $retval->moreviewtext;
		
		if ($found > 0){
			$returntext = '
			<div id="filtersection-insta" class="fourcolumnlist clearfixmain">
			'. $doc .'
			</div>
			
			<div id="instapopholder" class="com_none clearfixmain">'. $instapoptext .'</div>
			
			<div class="mostviewed clearfixmain">
				<p class="t-center">'. $moreviewtext .'</p>
			</div>
			';
			
			$returntext .= '
			<script type="text/javascript">
			$(document).ready(function(){
				$.fn.moreinstamedialist = function(p, m){
					b_sURL = bkfolder + "includes/ajax.php";
					$.post(b_sURL,
						{		
							p:p,
							max_id_passed:m,
							subsection:1,
							az:199,
							dataType: \'json\'
						},
						function(data){
							data = $.parseJSON(data);
							content = data.doc;
							instapoptext = data.instapoptext;
							moreviewtext = data.moreviewtext;
							if (content != ""){
								$("#listingholder-insta").append(content);
								$("#instapopholder").append(instapoptext);
							}else{
								$(\'#filtersection-insta\').html(\'Sorry. Record unavailable.\');
							}
							$(".t-center").html(moreviewtext);
							$(document.body).trigger("sticky_kit:recalc");
						});
				}

				$(".main").on("click", ".moreinstamedia", function(){
					var p = $(this).attr("p");
					var m = $(this).attr("m");
					$(this).moreinstamedialist(p, m);
				});
			});
			</script>
			';
		}
		
		return $returntext;
	}
	
	public function display_instagram_feed_home_old($param = array()){
		global $cm;
		$returntext = '';
		$container_start = '';
		$container_end = '';
		
		$innerpage = round($argu["innerpage"]);
		if ($innerpage == 0){
			$container_start = '<div class="containersmall clearfixmain">';
			$container_end = '</div>';
		}
		
		$dcon = 5;
		$instaurl = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $this->accesstoken . '&count=' . $dcon;
		$instaResult = file_get_contents($instaurl);
		$insta = json_decode($instaResult);
		$instadata = $insta->data;
		$found = count($instadata);
		if ($found > 0){
			$instaurl = $cm->get_systemvar('INURL');
			$returntext .= '
			<div class="homesectioninsta clearfixmain">
			'. $container_start .'
			<ul>
				<li><a href="'. $instaurl .'" target="_blank">
					<div class="instaicon2"><i class="fab fa-instagram fa-fw"></i></div>
					<h4>Follow us on<br>Instagram</h4>
				</a></li>
			';
			foreach($instadata as $instarow){
				$insta_img = $instarow->images->low_resolution->url;
				$insta_text = $cm->fc_word_count($instarow->caption->text, 6);
				$insta_links = $instarow->link;
				
				$returntext .= '
				<li><a href="'. $insta_links .'" target="_blank">
					<div class="topimg2"><img alt="'. $instarow->caption->text .'" src="'. $insta_img .'" /></div>
					<div class="imagehover">
						<div class="instametaholder clearfixmain">
							<div class="instaicon"><i class="fab fa-instagram fa-fw"></i><span class="com_none">Instagram</span></div>						
							<div class="instacomment">'. $insta_text .'</div>
						</div>
					</div>
				</a></li>
				';
			}
			
			$returntext .= '
			</ul>
			'. $container_end .'
			</div>
			';
		}
		return $returntext;
	}
	
	public function display_instagram_feed_ajax_call($argu = array()){
		global $cm;
		$returntext = '';
		$hashtag = $argu["hashtag"];
		
		$dcon = 5;
		$instaurl = 'https://api.instagram.com/v1/users/self/media/recent/?access_token=' . $this->accesstoken . '&count=' . $dcon;
		$instaResult = file_get_contents($instaurl);
		$insta = json_decode($instaResult);
		$instadata = $insta->data;
		$found = count($instadata);

		if ($found > 0){
			$instaurl = $cm->get_systemvar('INURL');
			$returntext .= '<ul>
				<li><a href="'. $instaurl .'" target="_blank">
					<div class="instaicon2"><i class="fab fa-instagram fa-fw"></i></div>
					<h4>Follow us on<br>Instagram</h4>
				</a></li>
			';

			foreach($instadata as $instarow){
				$insta_img = $instarow->images->low_resolution->url;
				$insta_text = $cm->fc_word_count($instarow->caption->text, 6);
				$insta_links = $instarow->link;
				
				$returntext .= '
				<li><a href="'. $insta_links .'" target="_blank">
					<div class="topimg2"><img alt="'. $instarow->caption->text .'" src="'. $insta_img .'" /></div>
					<div class="imagehover">
						<div class="instametaholder clearfixmain">
							<div class="instaicon"><i class="fab fa-instagram fa-fw"></i><span class="com_none">Instagram</span></div>						
							<div class="instacomment">'. $insta_text .'</div>
						</div>
					</div>
				</a></li>
				';
			}

			$returntext .= '</ul>';
		}

		$returnval = array(
            'doc' => $returntext
        );
        return json_encode($returnval);

	}	
	
	public function display_instagram_feed_home($param = array()){
		global $cm;
		$returntext = '';
		$container_start = '';
		$container_end = '';
		
		$hashtag = $param["hashtag"];
		$innerpage = round($param["innerpage"]);
		if ($innerpage == 0){
			$container_start = '<div class="containersmall clearfixmain">';
			$container_end = '</div>';
		}

		$returntext = '
		<div class="homesectioninsta clearfixmain">
			'. $container_start .'
			
			<div id="instadiv" class="clearfixmain"></div>
			
			'. $container_end .'
		</div>
		
		<script type="text/javascript">
		$(window).load(function(){
			var targetdiv = "instadiv";
			var b_sURL = bkfolder + "includes/ajax.php";
			$.post(b_sURL,
			{		
				hashtag:"'. $hashtag .'",
				subsection:2,
				az:199,
				dataType: \'json\'
			},
			function(data){
				//alert (data);
				data = $.parseJSON(data);
				content = data.doc;
				$("#" + targetdiv).html(content);
			});
		});
		</script>
		';

		return $returntext;
	}
}
?>
