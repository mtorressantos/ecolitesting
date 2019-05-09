(function( $ ) {
	'use strict';

	/**
	 * Fix issue with WC Recent Carousel classic style when SC is active.
	 * At the first load of shortcode, the height is not correct, only cover the image
	 * without product detail. This script only set the min-height.
	 */
	function mkWcCarouselSwiperHeight() {
		var mkWCRecentCarousel = $( '.mk-woocommerce-carousel.classic-style' );
		mkWCRecentCarousel.each( function() {
			var maxHeight = 0;
			var height = $( this ).height();
			var childs = $( this ).find( '.item' );
			childs.each( function(){
				if ( $( this ).height() > maxHeight ) {
					maxHeight = $( this ).height();
				}
			} );
			var swiperContainers = $( this ).find( '.mk-swiper-container' );
			swiperContainers[0].style.setProperty( 'min-height', maxHeight + 'px', 'important' );
		} );
	}

	mkWcCarouselSwiperHeight();

})( jQuery );