(function($){
    $(document).ready(function() {
		$('li.megamenu > ul').addClass('mega-menu');
		$('li.megamenu > ul > li').addClass('clearfixmain');
		$('li.smallmenu > ul').addClass('small-menu');
		$('li.smallmenu > ul > li').addClass('clearfixmain');
		$('li.normalmenu > ul').addClass('normal-menu');
		$('ul#topmenu ul').addClass('animated2 fadeInUp');		

		$(function() {
			$('#topmenu').smartmenus({
				mainMenuSubOffsetX:	0,		   // pixels offset from default position
				mainMenuSubOffsetY:	0,		   // pixels offset from default position
				subMenusSubOffsetX:	0,		   // pixels offset from default position
				subMenusSubOffsetY:	0,		   // pixels offset from default position
				subMenusMinWidth:	'200px',   // min-width for the sub menus (any CSS unit) - if set, the fixed width set in CSS will be ignored
				subMenusMaxWidth:	'100%',    // max-width for the sub menus (any CSS unit) - if set, the fixed width set in CSS will be ignored
				subIndicators: 		true,      // create sub menu indicators - creates a SPAN and inserts it in the A
				showTimeout:		0,		   // timeout before showing the sub menus
				hideTimeout:		0,		   // timeout before hiding the sub menus		
				hideFunction:		function($ul, complete) { $ul.fadeOut(200, complete); },
			});
		});

		/**-----------------------------------------------**/
		// For Mobile Menu Sliding Effect
		/**-----------------------------------------------**/

		$(function() {
			var $mainMenuState = $('#topmenu-state');
			if ($mainMenuState.length) {
					// animate mobile menu
					$mainMenuState.change(function(e) {
					var $menu = $('#topmenu');

					if (this.checked) {
						$menu.hide().slideDown(250, function() { $menu.css('display', ''); });
					} else {
						$menu.show().slideUp(250, function() { $menu.css('display', ''); });
					}
				});

				// hide mobile menu beforeunload
				$(window).on('beforeunload unload', function() {
					if ($mainMenuState[0].checked) {
						$mainMenuState[0].click();
					}
				});
			}
		});	
	

		// --
		$($('ul.mega-menu').find('ul').get().reverse()).each(function(){
			$(this).replaceWith($('<ol>'+$(this).html()+'</ol>'));
		});
		// End 
	});

})(jQuery);