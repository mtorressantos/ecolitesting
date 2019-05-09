jQuery(document).ready(function( $ ) {

	// Continue only on Safari.
	if ( typeof window.safari === 'undefined' ) {
		return;
	}

	// Fore redraw for Safari. This is a hack.
	function mkRedraw() {
		$('.mk-photo-roller').hide().show(0);
	}

	mkRedraw();

	$( window ).resize( function() {
		mkRedraw()
	} );

});