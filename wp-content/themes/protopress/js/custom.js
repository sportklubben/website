jQuery(document).ready( function() {
	jQuery('#searchicon').click(function() {
		jQuery('#jumbosearch').fadeIn();
		jQuery('#jumbosearch input').focus();
	});
	jQuery('#jumbosearch .closeicon').click(function() {
		jQuery('#jumbosearch').fadeOut();
	});
	jQuery('body').keydown(function(e){
	    
	    if(e.keyCode == 27){
	        jQuery('#jumbosearch').fadeOut();
	    }
	});

	jQuery('.eventbox').matchHeight();
	//jQuery('#sb-slider').slicebox();


	//script for loading gif
	jQuery('#myform').submit(function() {
	    jQuery('#pageloader').css('visibility', 'visible');
	});

	

});