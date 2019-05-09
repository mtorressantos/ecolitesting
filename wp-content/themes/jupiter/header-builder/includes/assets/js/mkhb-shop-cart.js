( function( $ ){
	var shop_cart_box = '.mkhb-shop-cart-el__box';
	// Open the shop list box when hover.
	$( '.mkhb-shop-cart-el' ).hover( function(){
		this_shop_cart_box = $( this ).find( shop_cart_box );
		this_shop_cart_box.fadeIn( 250 );

		// Measure shop-cart-box current offset and width.
		var width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
		var half  = width / 2;
		var offset = this_shop_cart_box.offset();

		// Set shop-cart-box position.
		if ( offset.hasOwnProperty( 'left' ) && offset.left < half ) {
			this_shop_cart_box.css( { 'left': 0 } );
		}
	}, function(){
		this_shop_cart_box = $( this ).find( shop_cart_box );
		this_shop_cart_box.fadeOut( 250 );
	} );
} )( jQuery );