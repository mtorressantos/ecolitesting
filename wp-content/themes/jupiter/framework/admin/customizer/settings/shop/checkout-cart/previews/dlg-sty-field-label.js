(function ($) {

	var selector = '\
		body.woocommerce-checkout .woocommerce .form-row,\
		body.woocommerce-cart .woocommerce .form-row,\
		body.woocommerce-order-received .woocommerce .form-row';

	var labels = 'label';

	// Field Typography.
	wp.customize('mk_cz[sh_cc_sty_fld_lbl_typography]', function (value) {

		var typography = mkPreviewTypography(value(), true);
		$( selector ).find(labels).css(typography);

		value.bind(function (to) {
			var typography = mkPreviewTypography(to);
			$( selector ).find(labels).css(typography);
		});
	
	});

	wp.customize('mk_cz[sh_cc_sty_fld_lbl_box_model]', function (value) {
		$( selector ).find(labels).css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$( selector ).find(labels).css(mkPreviewBoxModel(to));
		});
	});

})(jQuery);