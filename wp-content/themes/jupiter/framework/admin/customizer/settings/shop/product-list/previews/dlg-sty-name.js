(function ($) {

	var productTitle = '.woocommerce-page ul.products li.product .woocommerce-loop-product__title, .mk-woocommerce-carousel.classic-style ul.products li.product .woocommerce-loop-product__title';

	// Method for Control's event handlers: sh_pl_sty_nam_typography.
	wp.customize('mk_cz[sh_pl_sty_nam_typography]', function (value) {

		var el = 'mk_cz[sh_pl_sty_nam_typography]';
		var styles = {};

		// Add size only since it has !imporatnt by default.
		if ( typeof value() === 'string' ) {
			var typography = jQuery.parseJSON( value() );

			styles[ productTitle ] = 'font-size: ' + typography.size + 'px !important';
			mkPreviewInternalStyle( styles, el );
		}

		$(productTitle).css(
			mkPreviewTypography(value(), true)
		);

		value.bind(function (to) {

			// Add size only since it has !imporatnt by default.
			var typography = jQuery.parseJSON( to );

			styles[ productTitle ] = 'font-size: ' + typography.size + 'px !important';
			mkPreviewInternalStyle( styles, el );

			$(productTitle).css(
				mkPreviewTypography(to)
			);
		});
	});

	// Method for Control's event handlers: sh_pl_sty_nam_box_model.
	wp.customize('mk_cz[sh_pl_sty_nam_box_model]', function (value) {
		$(productTitle).css(
			mkPreviewBoxModel(value())
		);
		value.bind(function (to) {
			$(productTitle).css(
				mkPreviewBoxModel(to)
			);
		});
	});

})(jQuery);