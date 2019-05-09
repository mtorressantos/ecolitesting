(function( $ ) {

	var productSkuWrapper = '.woocommerce-page.single-product div.product .product_meta>span.sku_wrapper';

	// Method for Control's event handlers: sh_pp_sty_sku_typography.
	wp.customize('mk_cz[sh_pp_sty_sku_typography]', function (value) {
		$(productSkuWrapper).css(
			mkPreviewTypography(value(), true, ['weight'])
		);
		$(productSkuWrapper).find('.sku').css(
			mkPreviewTypography(value(), false)
		);
		value.bind(function (to) {
			$(productSkuWrapper).css(
				mkPreviewTypography(to, false, ['weight'])
			);
			$(productSkuWrapper).find('.sku').css(
				mkPreviewTypography(to, false)
			);
		});
	});

	// Method for Control's event handlers: sh_pp_sty_sku_box_model.
	wp.customize('mk_cz[sh_pp_sty_sku_box_model]', function (value) {
		$(productSkuWrapper).css(
			mkPreviewBoxModel(value())
		);
		value.bind(function (to) {
			$(productSkuWrapper).css(
				mkPreviewBoxModel(to)
			);
		});
	});

} )( jQuery );