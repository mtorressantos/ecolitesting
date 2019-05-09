jQuery(document).ready(function($) {

	// For Slider
	$( '.recent-post-slider' ).each(function( index ) {
		
		var slider_id   	= $(this).attr('id');			
		var slider_conf 	= $.parseJSON( $(this).closest('.wppsac-slick-slider-wrp').find('.wppsac-slider-conf').attr('data-conf'));
		
		if( typeof(slider_id) != 'undefined' && slider_id != '' ) {
			jQuery('#'+slider_id).slick({
				dots			: (slider_conf.dots) == "true" ? true : false,
				infinite		: true,
				arrows			: (slider_conf.arrows) == "true" ? true : false,
				speed			: parseInt(slider_conf.speed),
				autoplay		: (slider_conf.autoplay) == "true" ? true : false,
				autoplaySpeed	: parseInt(slider_conf.autoplay_interval),
				slidesToShow	: 1,
				slidesToScroll	: 1,
				rtl             : (slider_conf.rtl) == "true" ? true : false,
			});
		}
	});
});