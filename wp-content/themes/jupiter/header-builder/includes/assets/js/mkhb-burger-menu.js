/*
 * Burger menu navigation style. This script will run only when Burger element is exist.
 * Version: 5.9.3
 * Deprecated: 5.9.5
 */
( function( $ ) {
	var burger_menu_container = $( '.hb-burger-menu-el__container' );
	burger_menu_container.on( 'click', function( e ) {
		var $this = $( this ),
			$body = $( 'body' ),
			$fullscreen_box = $( '.hb-burger-menu-el__nav' );

		// @see js/src/elementClickEvents.js
		if ( e.stopPropagation ) {
      		e.stopPropagation();
    	} else if ( window.event ) {
      		window.event.cancelBubble = true;
    	}

		// @see components/layout/header/menu-sidebar.js
		// @todo HB only support fullscreen right now.
		if ( $this.hasClass( 'dashboard-style' ) ) {
			if ( ! $this.hasClass( 'dashboard-active' ) ) {
				$this.addClass( 'dashboard-active' );
				$body.addClass( 'dashboard-opened' );
			} else {
				$this.removeClass( 'dashboard-active' );
				$body.removeClass( 'dashboard-opened' );
			}
		} else if ( $this.hasClass( 'fullscreen-style' ) ) {
			if ( ! $this.hasClass( 'fullscreen-active' ) ) {
				$this.addClass( 'fullscreen-active' );
				$body.addClass( 'fullscreen-nav-opened' );
				$fullscreen_box.addClass( 'opened' );
			} else {
				$this.removeClass( 'fullscreen-active' );
				$body.removeClass( 'fullscreen-nav-opened' );
				$fullscreen_box.removeClass( 'opened' );
			}
		}
		e.preventDefault();
	});

	// @see components/layout/header/fullscreen-nav.js
	$( '.mk-fullscreen-nav-close, .mk-fullscreen-nav-wrapper, #fullscreen-navigation a' ).on( 'click', function(e) {
		burger_menu_container.removeClass( 'fullscreen-active' );
	});
})( jQuery );