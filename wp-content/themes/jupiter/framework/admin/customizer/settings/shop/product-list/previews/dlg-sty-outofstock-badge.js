(function( $ ) {

	var outofstockBadge = '.woocommerce-page li.product .mk-out-of-stock';

	// Typography.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_typography]', function( value ) {

		$(outofstockBadge).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function (to) {
			$(outofstockBadge).css(
				mkPreviewTypography( to )
			);
		} );

	});

	// Background color.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_background_color]', function( value ) {

		$(outofstockBadge).css( 'background-color', value() );

		value.bind( function( to ) {
			$(outofstockBadge).css( 'background-color', to );
		} );

	});

	// Border radius.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_border_radius]', function( value ) {

		$(outofstockBadge).css( 'border-radius', value() + 'px' );

		value.bind( function( to ) {
			$(outofstockBadge).css( 'border-radius', to + 'px' );
		} );

	});

	// Border width.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_border_width]', function( value ) {

		$(outofstockBadge).css( 'border-width', value() );

		value.bind( function( to ) {
			$(outofstockBadge).css( 'border-width', to );
		} );

	});

	// Border color.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_border_color]', function( value ) {

		$(outofstockBadge).css( 'border-color', value() );

		value.bind( function( to ) {
			$(outofstockBadge).css( 'border-color', to );
		} );

	});

	// Box Model.
	wp.customize( 'mk_cz[sh_pl_sty_oos_bdg_box_model]', function( value ) {

		$(outofstockBadge).css(
			mkPreviewBoxModel( value(), true )
		);

		value.bind( function (to) {
			$(outofstockBadge).css(
				mkPreviewBoxModel( to )
			);
		} );

	});

} )( jQuery );
