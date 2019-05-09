(function($) {
   if (window.addEventListener) {
      window.addEventListener('load', handleLoad, false);
    }
    else if (window.attachEvent) {
      window.attachEvent('onload', handleLoad);
    }

	function handleLoad() {
		$('.mk-slideshow-box').each(run);
	}

	function run() {
		var $slider = $(this);

		var $slides = $slider.find('.mk-slideshow-box-item');
		var $transition_time = $slider.data('transitionspeed');
		var $time_between_slides = $slider.data('slideshowspeed');

		$slider.find('.mk-slideshow-box-content').children('p').filter(function() {
			if ( $.trim($(this).text()) == '' ) {
				return true;
			}
		}).remove();

		// set active classes
		$slides.first().addClass('active').fadeIn($transition_time, function(){
			setTimeout(autoScroll, $time_between_slides);
		});

		// auto scroll
		function autoScroll(){
			if (isTest) return;
			var $i = $slider.find('.active').index();
			$slides.eq($i).removeClass('active').fadeOut($transition_time);
			if ($slides.length == $i + 1) $i = -1; // loop to start
			$slides.eq($i + 1).addClass('active').fadeIn($transition_time, function() {
				setTimeout(autoScroll, $time_between_slides);
			});
		}

		/**
		 * Need to set the height manually as min-height property doesn't work with display
		 * table and table-cell in Firefox and Safari. We use Javascript to set them only
		 * for Firefox and Safari to avoid any problem with Chrome users. The problem is only
		 * happen when the window screen size bigger than 850px.
		 */
		var browserName  = MK.utils.browser.name;
		if ( browserName === 'Firefox' || browserName === 'Safari' ) {
			var currentWidth = parseInt( $( window ).width() );
			if ( currentWidth >= 850 ) {
				var height = $slider.css( 'min-height' );
				if ( typeof height !== 'undefined' ) {
					$slider.find( '.mk-slideshow-box-items' ).height( parseInt( height ) );
				}
			} else {
				$slider.find( '.mk-slideshow-box-items' ).removeAttr( 'style' );
			}
		}
	}
	$(window).on('vc_reload', function(){
		handleLoad();
	});

	// Handle resize event only for Safari and Firefox.
	window.addEventListener( 'resize', function(){
		var browserName  = MK.utils.browser.name;
		if ( browserName === 'Firefox' || browserName === 'Safari' ) {
			handleLoad();
		}
	}, true);
}(jQuery));