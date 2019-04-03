var fcCaptchaCallback = function(){        
	$('.defaultcaptcha').each(function(){
		var object = $(this);
		grecaptcha.render(this,	{
			'sitekey' : '6LcwGwgUAAAAAJN4_TdrEza_l3sM3iSk0QXfC0L_',
			'callback' : function(token) {				
				$("html, body").animate({ scrollTop: $(this).offset().top }, "slow");
			}
			
		});
	})
	
	/*$( ".invisible-recaptcha" ).each(function() {
		var object = $(this);
		grecaptcha.render($( this ).attr('id'), {
			'sitekey' : '6LceTHEUAAAAAM8okTHbTXQOjvcdv0MuqbenDmNZ',
			"callback" : function(token) {
				object.parents('form').find(".g-recaptcha-response").val(token);
				//object.parents('form').submit();
			}
		});
	});*/
};