(function ($) {

	var productTitle = '.single-product div.product .product_title';

	// Method for Control's event handlers: sh_pp_sty_nam_typography.
	wp.customize('mk_cz[sh_pp_sty_nam_typography]', function (value) {
		$(productTitle).css(
			mkPreviewTypography(value(), true)
		);
		value.bind(function (to) {
			$(productTitle).css(
				mkPreviewTypography(to)
			);
		});
	});

	// Method for Control's event handlers: sh_pp_sty_nam_box_model.
	wp.customize('mk_cz[sh_pp_sty_nam_box_model]', function (value) {
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