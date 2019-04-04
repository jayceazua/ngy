$(document).ready(function(){
	if (sessionStorage.keepsearchsession) {
		sessionStorage.removeItem("keepsearchsession");
		if (sessionStorage.boatlistingdataright) {
			if ($(".boatlistingmain").length > 0) {
				var curpage = sessionStorage.currentpagename;
				if (curpage == $(location).attr('href')){
					//$(".boatlistingmain").html(sessionStorage.boatlistingdata);
					//alert (sessionStorage.boatlistingdataleft);
					$(".boatlistingmain .boatlistingsearchtop").html(sessionStorage.boatlistingdatatop);
					if ($(".boatlistingmain .left-cell").length > 0) {
						$(".boatlistingmain .left-cell").html(sessionStorage.boatlistingdataleft);
					}
					$(".boatlistingmain #listingholdermain").html(sessionStorage.boatlistingdataright);

					$("#listingholdermain ul.product-list li").removeClass( 'no-transition' ).removeClass( 'hidden-listing' );
					$(document.body).trigger("sticky_kit:recalc");				
				}
			}
		}
	}
});