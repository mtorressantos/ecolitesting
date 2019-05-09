(function( $ ) {

	var saleBadge = '.woocommerce-page li.product .onsale,  .mk-woocommerce-carousel.classic-style ul.products li.product .onsale';

	// Typography.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_typography]', function( value ) {

		$(saleBadge).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function( to ) {
			$(saleBadge).css(
				mkPreviewTypography( to )
			);
		} );

	});

	// Background color.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_background_color]', function( value ) {

		$(saleBadge).css( 'background-color', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'background-color', to );
		} );

	} );

	// Border radius.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_border_radius]', function( value ) {

		$(saleBadge).css( 'border-radius', value() + 'px' );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-radius', to + 'px' );
		} );

	} );

	// Border width.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_border_width]', function( value ) {

		$(saleBadge).css( 'border-width', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-width', to );
		} );

	} );

	// Border color.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_border_color]', function( value ) {

		$(saleBadge).css( 'border-color', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-color', to );
		} );

	} );

	// Box Model.
	wp.customize( 'mk_cz[sh_pl_sty_sal_bdg_box_model]', function( value ) {

		$(saleBadge).css(
			mkPreviewBoxModel( value(), true )
		);

		value.bind( function (to) {
			$(saleBadge).css(
				mkPreviewBoxModel( to )
			);
		} );

	} );

} )( jQuery );
