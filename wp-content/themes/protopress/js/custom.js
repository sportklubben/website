jQuery(document).ready( function() {
	jQuery('.eventbox').matchHeight();

	//script for loading gif
	jQuery('#myform').submit(function() {
	    jQuery('#pageloader').css('visibility', 'visible');
	});

});

/*
jQuery(document).on('show.bs.modal', '.modal', function() {
	var body = jQuery('body.modal-open');
	var scrollHeight = body.prop('scrollHeight');
	body.css('top', -scrollHeight);
	console.log(scrollHeight)
});
*/