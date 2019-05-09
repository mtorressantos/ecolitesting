(function ($) {

	var selector = '\
		body.woocommerce-cart .woocommerce form.woocommerce-cart-form > h2,\
		body.woocommerce-cart .woocommerce .cart_totals > h2,\
		body.woocommerce-checkout .woocommerce h2.mk-wc-title';

	// Field Typography.
	wp.customize('mk_cz[sh_cc_sty_big_hdn_typography]', function (value) {

		var el = 'sh_cc_sty_big_hdn_typography';
		var styles = {};

		styles[ selector ] = mkPreviewTypography( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ selector ] = mkPreviewTypography( to );
			mkPreviewInternalStyle( styles, el );
		});
	
	});

	wp.customize('mk_cz[sh_cc_sty_big_hdn_box_model]', function (value) {
		
		var el = 'sh_cc_sty_big_hdn_box_model';
		var styles = {};

		styles[ selector ] = mkPreviewBoxModel( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ selector ] = mkPreviewBoxModel( to );
			mkPreviewInternalStyle( styles, el );
		});
		
	});

})(jQuery);