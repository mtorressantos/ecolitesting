(function( $ ) {

	var productPostedIn = '.woocommerce-page.single-product div.product .product_meta>span.posted_in';

	// Method for Control's event handlers: sh_pp_sty_cat_typography.
	wp.customize('mk_cz[sh_pp_sty_cat_typography]', function (value) {
		$(productPostedIn).css(
			mkPreviewTypography(value(), true, ['weight'])
		);
		$(productPostedIn).find('a').css(
			mkPreviewTypography(value(), false)
		);
		value.bind(function (to) {
			$(productPostedIn).css(
				mkPreviewTypography(to, false, ['weight'])
			);
			$(productPostedIn).find('a').css(
				mkPreviewTypography(to, false)
			);
		});
	});

	// Method for Control's event handlers: sh_pp_sty_cat_box_model.
	wp.customize('mk_cz[sh_pp_sty_cat_box_model]', function (value) {
		$(productPostedIn).css(
			mkPreviewBoxModel(value())
		);
		value.bind(function (to) {
			$(productPostedIn).css(
				mkPreviewBoxModel(to)
			);
		});
	});

} )( jQuery );