( function( $ ) {
	// Open the overlay box after click the icon.
	$( '.mkhb-trigger__fullscreen--open' ).on( 'click', function( e ) {
		$( '.mkhb-search-el__overlay' ).addClass( 'mkhb-search-el__overlay--show' );
		setTimeout( function() {
			$( '#mkhb-search-el__overlay__search-input' ).focus();
		}, 300 );
		e.preventDefault();
	});

	// Close the overlay box after click close icon.
	$( '.mkhb-search-el__overlay__close' ).on( 'click', function( e ) {
		$( '.mkhb-search-el__overlay' ).removeClass( 'mkhb-search-el__overlay--show' );
		e.preventDefault();
	});
})( jQuery );