(function( $ ) {

	var starRating = '.woocommerce-page.single-product div.woocommerce-product-rating .star-rating';
	var starRatingText = '.woocommerce-page.single-product div.woocommerce-product-rating .woocommerce-review-link';
	var starRatingContainer = '.woocommerce-page.single-product div.woocommerce-product-rating';

	// Font size.
	wp.customize( 'mk_cz[sh_pp_sty_rat_font_size]', function( value ) {
	
		$( starRating ).css( 'font-size', value() + 'px' );
	
		value.bind( function( to ) {
			$( starRating ).css( 'font-size', to + 'px' );
		} );
	
	} );

	// Star color.
	wp.customize( 'mk_cz[sh_pp_sty_rat_star_color]', function( value ) {

		var el = 'sh_pp_sty_rat_star_color';
		var styles = {};

		styles['.woocommerce-page.single-product div.woocommerce-product-rating .star-rating::before'] = 'color: ' + value();
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles['.woocommerce-page.single-product div.woocommerce-product-rating .star-rating::before'] = 'color: ' + to;
			mkPreviewInternalStyle( styles, el );
		} );
	
	} );

	// Active Star color.
	wp.customize( 'mk_cz[sh_pp_sty_rat_active_star_color]', function( value ) {

		var el = 'sh_pp_sty_rat_active_star_color';
		var styles = {};

		styles['.woocommerce-page.single-product div.woocommerce-product-rating .star-rating span::before'] = 'color: ' + value() + ' !important';
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles['.woocommerce-page.single-product div.woocommerce-product-rating .star-rating span::before'] = 'color: ' + to + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );
	
	} );

	// Typography.
	wp.customize( 'mk_cz[sh_pp_sty_rat_typography]', function( value ) {
		$( starRatingText ).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function( to ) {
			
			$( starRatingText ).css(
				mkPreviewTypography( to )
			);

		} );
	});

	// Box Model.
	wp.customize( 'mk_cz[sh_pp_sty_rat_box_model]', function( value ) {
		
		$( starRatingContainer ).css(
			mkPreviewBoxModel( value(), true )
		);

		value.bind( function (to) {
			$(starRatingContainer).css(
				mkPreviewBoxModel( to )
			);
		} );

	} );

} )( jQuery );