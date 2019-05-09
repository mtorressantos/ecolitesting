(function ($) {
	'use strict';  

	function dynamicHeight() {
		var $this = $( this );

		$this.height( 'auto' );

		if( window.matchMedia( '(max-width: 768px)' ).matches ) {
			return;
		} 
		 
		$this.height( $this.height() );
	}

	var $window = $( window );
	var container = document.getElementById( 'mk-theme-container' );

	function equalColumns() {
		$( '.equal-columns' ).each( function() { 
			dynamicHeight.bind( this );
		    $window.on( 'load', dynamicHeight.bind( this ) );
		    $window.on( 'resize', dynamicHeight.bind( this ) );
		    window.addResizeListener( container, dynamicHeight.bind( this ) );
		});
	}
	equalColumns();
	$window.on( 'vc_reload' , function() {
		equalColumns();
	});

}( jQuery ));