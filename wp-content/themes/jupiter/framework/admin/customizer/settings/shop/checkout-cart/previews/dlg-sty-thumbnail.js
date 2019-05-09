(function ($) {

	var selector = '.woocommerce .cart_item .mk-cart-product-image img, .woocommerce .cart_item .product-image img';

	wp.customize('mk_cz[sh_cc_sty_tmn_display]', function (value) {
		$( '.woocommerce .cart_item .mk-cart-product-image' ).css({
			display: ( value() === 'true' ) ? 'inline' : 'none'
		});
		$(document).ajaxComplete( function() {
			$( '.woocommerce .cart_item .product-image' ).css({
				display: ( value() === 'true' ) ? 'block' : 'none'
			});
		});
		value.bind(function (to) {
			$( '.woocommerce .cart_item .mk-cart-product-image' ).css({
				display: ( to === 'true' ) ? 'inline' : 'none'
			});
			$( '.woocommerce .cart_item .product-image' ).css({
				display: ( to === 'true' ) ? 'block' : 'none'
			});
		});
	});

	// Border Radius.
	wp.customize( 'mk_cz[sh_cc_sty_tmn_border_radius]', function( value ) {

		$(document).ajaxComplete( function() {
			$( selector ).css({
				'border-radius': value() + 'px',
			});
		});

		value.bind( function( to ) {
			$( selector ).css({
				'border-radius': to + 'px',
			});
		} );

	});

	// Border Width.
	wp.customize( 'mk_cz[sh_cc_sty_tmn_border_width]', function( value ) {

		$(document).ajaxComplete( function() {
			$( selector ).css({
				borderWidth: value() + 'px',
			});
		});

		value.bind( function( to ) {
			$( selector ).css({
				borderWidth: to + 'px',
			});
		} );

	});


	// Border Color.
	wp.customize( 'mk_cz[sh_cc_sty_tmn_border_color]', function( value ) {

		$(document).ajaxComplete( function() {
			$( selector ).css({
				borderColor: value(),
			});
		});

		value.bind( function( to ) {
			$( selector ).css({
				borderColor: to,
			});
		} );

	});

	// Margin.
	wp.customize( 'mk_cz[sh_cc_sty_tmn_box_model]', function( value ) {

		$( '.woocommerce .cart_item .mk-cart-product-image img' ).css(
			mkPreviewBoxModel(value(), true)
		);

		value.bind(function (to) {
			$( '.woocommerce .cart_item .mk-cart-product-image img' ).css(
				mkPreviewBoxModel(to)
			);
			$( 'body' ).trigger( 'mk-position-order-summery' );
		});
	
	});

})(jQuery);