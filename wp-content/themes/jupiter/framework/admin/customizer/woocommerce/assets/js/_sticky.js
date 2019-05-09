jQuery(document).ready(function( $ ) {
	/*
	 * May 2015
	 * scrollDetection 1.0.0
	 * @author Mario Vidov
	 * @url http://vidov.it
	 * @twitter MarioVidov
	 * MIT license
	 */
	$.scrollDetection = function(options) {
		var settings = $.extend({
			scrollDown: function() {},
			scrollUp: function() {}
		}, options);

		var scrollPosition = 0;
		$(window).scroll(function () {
			var cursorPosition = $(this).scrollTop();
			if (cursorPosition > scrollPosition) {
				settings.scrollDown();
			}
			else if (cursorPosition < scrollPosition) {
				settings.scrollUp();
			}
			scrollPosition = cursorPosition;
		});
	};

	/**
	 * Init sticky sidebar.
	 *
	 * @since 6.0.3
	 */
	if ( MK.utils.isMobile() ) {
		return;
	}

	$( 'body' ).on( 'mk:woo-info-sticky', mkWooStickySidebar );

	if ( 0 === $( '.mk-info-sticky', '.single-product' ).length ) {
		return;
	}

	mkWooStickySidebar();

	function mkWooStickySidebar() {
		mkStickyScrollUp();

		$.scrollDetection({
			scrollUp: function() {
				mkStickyScrollUp();
			},
			scrollDown: function() {
				mkStickyScrollDown()
			}
		});
	}

	/**
	 * Calculate top value on up scroll.
	 *
	 * @since 6.0.3
	 * @return integer top value.
	 */
	function mkStickyScrollUp() {
		var top = 0;
		top += $( '.mk-single-product-badges' ).outerHeight() / 2;
		top += $( '.a-sticky .mk-header-holder' ).outerHeight() || 50;

		$( '.entry-summary' ).css({
			top: top
		});
	}

	/**
	 * Calculate top value on down scroll.
	 *
	 * @since 6.0.3
	 * @return integer top value.
	 */
	function mkStickyScrollDown() {
		var top = 20;
		var sidebarHeight = $( '.entry-summary' ).outerHeight();
		var viewportHeight = $( window ).height();

		if ( sidebarHeight > viewportHeight ) {
			top = viewportHeight - sidebarHeight;
		} else {
			top += $( '.a-sticky .mk-header-holder' ).outerHeight() || 30;
		}

		$( '.entry-summary' ).css({
			top: top
		});
	}

});
