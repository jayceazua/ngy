<?php
$startend = 0;
$slug = $_REQUEST['slug'];
$opengraphmeta = $cm->meta_open_graph($name, $atm2, $imagelink, $fullurl);
include($bdr."includes/head.php");
?>
<div ng-show="showLoader" class="showloader">
	<img src="<?php echo $cm->folder_for_seo; ?>images/loader.gif" />
    <p>Loading...</p>
</div>

<div ng-cloak ng-show="showdata" ng-repeat="data in charterBoatData">
    <div class="detail-banner overlay">
        <img class="full" src="<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/big/{{ data.imgpath }}" alt="">
        <div class="detail-header-text">
            <div class="container">
                <h1 class="detail-banner-h1">{{ data.boatname }}</h1>
                <h3 class="detail-banner-h3">{{ data.subtitle }}</h3>
                <ul class="detail-banner-buttons">
                    <li><a class="cbscroll" g="boatcontact" href="javascript:void(0);">Enquire</a></li>
                    <li><a class="cbscroll" g="boatmain" href="javascript:void(0);">Details</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="boatmain" class="spec-desc wow fadeInUp" data-wow-duration="1.2s" {{data.bg_section1 !== "" ? style="background-image:url(<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/background/{{ data.bg_section1 }});" : ""}}>
		<div class="spec-desc-overlay">
            <div class="container">
                <div class="spec-desc-row">                
                    <div>
                        <h2>Specifications</h2>
                        <ul class="spec-desc-spec-list">
                            <li>
                                Builder
                                <span>{{ data.makename }}</span>
                            </li>
                            <li>
                                Year
                                <span>{{ data.boatyear }}</span>
                            </li>
                            <li>
                                Length (M)
                                <span>{{ data.boatlength }}</span>
                            </li>
                            <li>
                                Guests
                                <span>{{ data.boatguest }}</span>
                            </li>
                        </ul>
                        <ul class="spec-desc-spec-list">
                            <li>
                                Cabins
                                <span>{{ data.boatcabin }}</span>
                            </li>
                            <li>
                                Crew
                                <span>{{ data.boatcrew }}</span>
                            </li>
                            <li>
                                Max Speed (kt)
                                <span>{{ data.maxspeed }}</span>
                            </li>
                            <li>
                                Type
                                <span>{{ data.categoryname }}</span>
                            </li>
                        </ul>
                        
                        <h2 class="mt-3">Cruising Area</h2>
                        <ul class="spec-desc-spec-list4">
                        	<li ng-repeat="cadata in data.cruisingarea">
                                <span>{{ cadata.name }}</span>
                            </li>
                        </ul>                 
                       
                        <h2 class="mt-3">Price</h2>
                        <ul class="spec-desc-spec-list2">
                            <li>
                                Price Per Day
                                <span ng-if="data.priceperday > 0">
                                ${{ data.priceperdayformat }}
                                </span>
                                <span ng-if="data.priceperday <= 0">
                                <a class="cbscroll" g="boatcontact" href="javascript:void(0);">Enquire Now</a>
                                </span>                                
                            </li>
                            <li>
                                Price Per Week
                                <span ng-if="data.priceperweek > 0">
                                ${{ data.priceperweekformat }}
                                </span>
                                <span ng-if="data.priceperweek <= 0">
                                <a class="cbscroll" g="boatcontact" href="javascript:void(0);">Enquire Now</a>
                                </span>
                            </li>                            
                        </ul>                        
                    </div>
                       
                    <div>
                        <h2>Description</h2>
                        <div ng-bind-html="data.description|trustAsHtml"></div>
                    </div>
                    
                </div><!--/spec-desc-row-->
            </div><!--/container-->        
        </div><!--/spec-desc-overlay-->
    </div><!--/spec-desc-->
    
    <div ng-show="showgallery" class="detail-gallery wow fadeInUp" data-wow-duration="1.2s" {{data.bg_section2 !== "" ? style="background-image:url(<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/background/{{ data.bg_section2 }});" : ""}}>
        <div class="detail-gallery-overlay">
            <div class="container">
        
            <h1 class="t-center detail-h1">Gallery</h1>            
                <ul class="detail-gallery-list clearfixmain">
                    <li ng-repeat="galdata in data.boatgallery"><a data-fancybox="gallery"  data-caption="{{ data.boatname }}" href="<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/big/{{ galdata.imgpath }}"><img src="<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/thumbnail/{{ galdata.imgpath }}" alt="{{ data.boatname }}"></a></li>                    
                </ul>
                
            </div><!--/container-->        
        </div><!--/detail-gallery-overlay-->
    </div><!--/detail-gallery-->
    
    <div ng-show="showtendertoy" class="tender-toys wow fadeInUp" data-wow-duration="1.2s" {{data.bg_section3 !== "" ? style="background-image:url(<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/background/{{ data.bg_section3 }});" : ""}}>
        <div class="tender-toys-overlay">
            <div class="container">    
            <h1 class="t-center detail-h1">Tenders & Toys</h1>        
                <ul class="tender-toys-list clearfixmain">
                    <li ng-repeat="ttdata in data.tendertoy">
                        <dl>
                            <dt><img src="<?php echo $cm->folder_for_seo; ?>charterboat/tendertoy/{{ ttdata.iconpath }}" alt="{{ ttdata.name }}"></dt>
                            <dd>1 x {{ ttdata.name }}</dd>
                        </dl>
                    </li>
                </ul>                
            </div><!--/container-->        
        </div><!--/tender-toys-overlay-->
    </div><!--/tender-toys-->
    
    <div ng-show="showdestination" class="itinerary  wow fadeInUp" data-wow-duration="1.2s" {{data.bg_section4 !== "" ? style="background-image:url(<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/background/{{ data.bg_section4 }});" : ""}}>
        <div class="itinerary-overlay">
            <div class="container">        
                <h1 class="t-center detail-h1">Itinerary</h1>            
                <ul class="itinerary-list clearfixmain">
                    <li ng-repeat="desdata in data.destination">
                        <img src="<?php echo $cm->folder_for_seo; ?>charterboat/destination/{{ desdata.imagepath }}" alt="{{ desdata.name }}">
                        <div class="overlayy"></div>
                        <h3>{{ desdata.name }}</h3>
                    </li>                    
                </ul>                
            </div><!--/container-->        
        </div><!--/itinerary-overlay-->
    </div><!--/itinerary-->
    
    <div id="boatcontact" class="detail-enquire wow fadeInUp" data-wow-duration="1.2s" {{data.bg_section5 !== "" ? style="background-image:url(<?php echo $cm->folder_for_seo; ?>charterboat/listings/{{ data.id }}/background/{{ data.bg_section5 }});" : ""}}>
        <div class="detail-enquire-overlay">
            <div class="container">		
                 <h1 class="t-center detail-h1">Enquire</h1>              
                <form method="post" action="<?php echo $cm->folder_for_seo; ?>" id="charterboat-ff" name="charterboat-ff">
                	<input class="finfo" id="email2" name="email2" type="text" />
                    <input type="hidden" id="fcapi" name="fcapi" value="charterboatsubmit" />
                    <input type="hidden" id="pgid" name="pgid" value="53" />                   
                    <ul class="detail-enquire-list clearfixmain">
                        <li>
                            <label for="subject" class="com_none">Subject</label>
                            <span class="pseudo-require"><input type="text" name="subject" placeholder="Subject" id="subject" class="cominput" value=""></span>
                            
                            <label for="name" class="com_none">Full Name</label>                        
                            <span class="pseudo-require"><input type="text" name="name" placeholder="Full Name" id="name" class="cominput" value=""></span>
                            
                            <label for="phone" class="com_none">Phone Number</label>
                            <span class="pseudo-require"><input type="text" name="phone" placeholder="Phone Number" id="phone" class="cominput" value=""></span>
                            
                            <label for="email" class="com_none">Email Address</label>
                            <span class="pseudo-require"><input type="text" name="email" placeholder="Email Address" id="email" class="cominput" value=""></span>
                        </li>
                        <li>
                            <label for="comment" class="com_none">Comment</label>
                            <span class="pseudo-require"><textarea placeholder="Comment" id="comment" name="comment" class="cominput"></textarea></span>                        
                        </li>   
                    </ul>
                    <div class="t-center">
                        <input type="submit" value="SUBMIT">
                    </div>
                </form> 
            </div>
        </div>
    </div>

</div>

<div ng-cloak ng-show="notfounddata">
	<div class="container">
		<p class="t-center">Sorry. The page you are requesting is not found.</p>
    </div>    
</div>
<script>
var app = angular.module("page_app", []);
app.controller("page_controller", function($scope, $http, $window){
	$scope.fetchData = function(){
		$scope.showLoader = true;
		$http({
			method:"POST",
			url: bkfolder + "includes/nggetdata.php",
			data:{
				az:3,
				slug:"<?php echo $slug; ?>",
			}
		}).then(function successCallback(response){			
			var totalboatfound = response.data.totalrecord;		
			$scope.showLoader = false;

			if (totalboatfound > 0){
				$scope.showdata = true;
				$scope.notfounddata = false;
				$scope.charterBoatData = response.data.allboats;
				var totalimg = response.data.allboats[0].boatgallery.length;
				if (totalimg > 0){
					$scope.showgallery = true;
				}else{
					$scope.showgallery = false;
				}
				
				var totaltendertoy = response.data.allboats[0].tendertoy.length;
				if (totaltendertoy > 0){
					$scope.showtendertoy = true;
				}else{
					$scope.showtendertoy = false;
				}
				
				var totaldestination = response.data.allboats[0].destination.length;
				if (totaldestination > 0){
					$scope.showdestination = true;
				}else{
					$scope.showdestination = false;
				}
				
				if (response.data.allboats[0].m1 != ""){
					$scope.pTitle = response.data.allboats[0].m1;
					$("#ogtitle").attr("content", response.data.allboats[0].m1);
					$window.document.getElementsByName('twitter:title')[0].content = response.data.allboats[0].m1;
				}
				
				if (response.data.allboats[0].m2 != ""){
					$window.document.getElementsByName('description')[0].content = response.data.allboats[0].m2;
					$("#ogdescription").attr("content", response.data.allboats[0].m2);
					$window.document.getElementsByName('twitter:description')[0].content = response.data.allboats[0].m2;
				}
				
				if (response.data.allboats[0].m3 != ""){
					$window.document.getElementsByName('keywords')[0].content = response.data.allboats[0].m3;
				}
				
				if (response.data.allboats[0].imagelink != ""){
					$("#ogimage").attr("content", response.data.allboats[0].imagelink);
					$window.document.getElementsByName('twitter:image')[0].content = response.data.allboats[0].imagelink;
				}
			}else{
				$scope.showdata = false;
				$scope.notfounddata = true;
			}
		});
	}
	
	// Call function
	$scope.fetchData();
});

app.filter('trustAsHtml',['$sce', function($sce) {
    return function(text) {
      return $sce.trustAsHtml(text);
    };
}]);



	if ($(".cbscroll").length > 0){
		$(document).on("click", ".cbscroll", function() {
			var goto = $(this).attr("g");
			jQuery('html, body').animate({
				scrollTop: jQuery("#" + goto).offset().top - $(".blank_top").height()
			}, 500);
	
			return false;
		});
		$(".cbscrolldd").click(function(){
			alert("aa");
			
		});
	}	

	$(document).on("submit", "#charterboat-ff", function() {
		var all_ok = "y";
		var setfocus = "n";
		
		if (!field_validation_border("subject", 1, 1)){
			all_ok = "n";
			setfocus = set_field_focus(setfocus, "subject");
		}
		
		if (!field_validation_border("name", 1, 1)){
			all_ok = "n";
			setfocus = set_field_focus(setfocus, "name");
		}
		
		if (!field_validation_border("phone", 1, 1)){
			all_ok = "n";
			setfocus = set_field_focus(setfocus, "phone");
		}
		
		if (!field_validation_border("email", 2, 1)){
			all_ok = "n";
			setfocus = set_field_focus(setfocus, "email");
		}
		
		if (!field_validation_border("comment", 1, 1)){
			all_ok = "n";
			setfocus = set_field_focus(setfocus, "comment");
		}
		
		if (all_ok == "n"){
			return false;
		}
		return true;
	});
	

</script>
<?php
include($bdr."includes/foot.php");
?>