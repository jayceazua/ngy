jQuery(document).ready(function () {	
	
	$(".gn-icon-menu").click(function() {
		$(this).toggleClass("on");
		$("nav").toggleClass("active");
	});

	
	if ($(window).width() > 1049) {			
		$('nav ul li.has-sub-menu').hover(function() {
			$('nav ul li.has-sub-menu ul').addClass('animated2 fadeInUpSmall');
		});
		
		$('nav ul li.has-sub-menu ul li').hover(function() {
			$('nav ul li.has-sub-menu ul li ul').addClass('animated2 fadeInUpSmall');
		});
		
		
		$('nav ul li.has-sub-menu ul li ul li').hover(function() {
			$('nav ul li.has-sub-menu ul li ul li ul').addClass('animated2 fadeInUpSmall');
		});
		
		set_outscreen_thumb_menu();
		
	}

	$(window).resize(function () {
		set_respnsive_menu();
		set_outscreen_thumb_menu();
	});
	
	$(window).load(function () {
		set_respnsive_menu();
	});


});

function set_outscreen_thumb_menu(){
	if ($(".with-thumb2").length > 0) {
			$(".with-thumb2").each(function(){
				var topLi = $(this).parents("li").last();
				var topLi_w = topLi.width();
				var total_width = topLi.position().left + topLi_w + 300;
				
				if ($(window).width() < total_width){
					$(this).addClass("outscreen");
				}else{
					$(this).removeClass("outscreen");
				}

			});
		}
}

function set_respnsive_menu(){
	if ($(window).width() > 1049) {
		$("nav > ul, nav > ul  li  ul, nav > ul  li  ul li ul, nav > ul  li  ul li ul li ul").removeAttr("style");
	}else{
		var list = $("nav > ul li > a");
		list.click(function (event) {
			var submenu = this.parentNode.getElementsByTagName("ul").item(0);
			if(submenu!=null){
				event.preventDefault();
				$(submenu).slideToggle();
			}
		});
	}
}

if ($(window).width() > 1049) {			
	$('nav ul li.has-sub-menu').hover(function() {
		$('nav ul li.has-sub-menu ul').addClass('animated2 fadeInUpSmall');
	});
	
	$('nav ul li.has-sub-menu ul li').hover(function() {
		$('nav ul li.has-sub-menu ul li ul').addClass('animated2 fadeInUpSmall');
	});
	
	
	$('nav ul li.has-sub-menu ul li ul li').hover(function() {
		$('nav ul li.has-sub-menu ul li ul li ul').addClass('animated2 fadeInUpSmall');
	});
}