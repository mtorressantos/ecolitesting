(function ($) {

	var el_coupon = '.woocommerce .cart .coupon input.button';
	var el_coupon_checkout = '.woocommerce-checkout .checkout_coupon input.button';
	var el_cart = '.woocommerce-cart .woocommerce-cart-form input.button';
	var el_checkout = '.woocommerce-cart #mk-checkout-button#mk-checkout-button';
	var el_order = '.woocommerce-checkout .woocommerce-checkout #payment #place_order';
	var el_combined = el_coupon + ", " + el_cart + ", " + el_checkout + ", " + el_order + ", " + el_coupon_checkout;
	var el_combined_hover = el_coupon + ":hover, " + el_cart + ":hover, " + el_checkout + ":hover," + el_order + ":hover, " + el_coupon_checkout + ":hover";

	// Method for Control's event handlers: sh_cc_sty_btn_typography.
	wp.customize('mk_cz[sh_cc_sty_btn_typography]', function (value) {
		
		var el = 'sh_cc_sty_btn_typography';
		var styles = {};

		styles[ el_combined ] = mkPreviewTypography( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined ] = mkPreviewTypography( to );
			mkPreviewInternalStyle( styles, el );
		});
	
	});

	// Method for Control's event handlers: sh_cc_sty_btn_background_color.
	wp.customize( 'mk_cz[sh_cc_sty_btn_background_color]', function( value ) {

		var el = 'sh_cc_sty_btn_background_color';
		var styles = {};

		styles[el_combined] = 'background-color: ' + value() + ' !important';
		styles[ el_cart + ":disabled"] = 'background-color: #bbbbbf !important';
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles[el_combined] = 'background-color: ' + to + ' !important';
			styles[ el_cart + ":disabled"] = 'background-color: #bbbbbf !important';
			mkPreviewInternalStyle( styles, el );
		} );
	
	} );

	// Method for Control's event handlers: sh_cc_sty_btn_border_radius.
	wp.customize('mk_cz[sh_cc_sty_btn_border_radius]', function (value) {
		
		var el = 'sh_cc_sty_btn_border_radius';
		var styles = {};

		styles[ el_combined ] = 'border-radius: ' + value() + 'px;';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined ] = 'border-radius: ' + to + 'px;';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_btn_border.
	wp.customize('mk_cz[sh_cc_sty_btn_border]', function (value) {

		var el = 'sh_cc_sty_btn_border';
		var styles = {};

		styles[ el_combined ] = 'border-width: ' + value() + 'px;';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined ] = 'border-width: ' + to + 'px;';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_btn_border_color.
	wp.customize('mk_cz[sh_cc_sty_btn_border_color]', function (value) {

		var el = 'sh_cc_sty_btn_border_color';
		var styles = {};

		styles[ el_combined ] = 'border-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined ] = 'border-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_btn_color_hover.
	wp.customize('mk_cz[sh_cc_sty_btn_color_hover]', function (value) {
		
		var el = 'sh_cc_sty_btn_color_hover';
		var styles = {};

		styles[ el_combined_hover ] = 'color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined_hover ] = 'color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});
	});

	// Method for Control's event handlers: sh_cc_sty_btn_background_color_hover.
	wp.customize('mk_cz[sh_cc_sty_btn_background_color_hover]', function (value) {

		var el = 'sh_cc_sty_btn_background_color_hover';
		var styles = {};

		styles[ el_combined_hover ] = 'background-color: ' + value() + ' !important';
		styles[ el_cart + ":disabled:hover"] = 'background-color: #bbbbbf !important';
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles[ el_combined_hover ] = 'background-color: ' + to + ' !important';
			styles[ el_cart + ":disabled:hover"] = 'background-color: #bbbbbf !important';
			mkPreviewInternalStyle( styles, el );
		} );
		
	});

	// Method for Control's event handlers: sh_cc_sty_btn_border_color_hover.
	wp.customize('mk_cz[sh_cc_sty_btn_border_color_hover]', function (value) {

		var el = 'sh_cc_sty_btn_border_color_hover';
		var styles = {};

		styles[ el_combined_hover ] = 'border-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined_hover ] = 'border-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});
	});

	// Method for Control's event handlers: sh_cc_sty_btn_box_model.
	wp.customize('mk_cz[sh_cc_sty_btn_box_model]', function (value) {

		var el = 'sh_cc_sty_btn_box_model';
		var styles = {};

		styles[ el_combined ] = mkPreviewBoxModel( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined ] = mkPreviewBoxModel( to );
			mkPreviewInternalStyle( styles, el );
		});

	});

})(jQuery);