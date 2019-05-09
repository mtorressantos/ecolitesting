(function ($) {

	var selector = '\
		body.woocommerce-checkout .woocommerce,\
		body.woocommerce-cart .woocommerce,\
		body.woocommerce-order-received .woocommerce';

	var inputs = 'input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty), textarea, .select2-selection';

	var cart_fields_focus = 'body.woocommerce-cart .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus';
	var checkout_fields_focus = 'body.woocommerce-checkout .woocommerce input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus';
	var order_fields_focus = 'body.woocommerce-order-received input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):focus';

	// Field Typography.
	wp.customize('mk_cz[sh_cc_sty_fld_typography]', function (value) {

		var typography = mkPreviewTypography(value(), true);
		$( selector ).find(inputs).css(typography);

		value.bind(function (to) {
			var typography = mkPreviewTypography(to);
			$( selector ).find(inputs).css(typography);
		});

	});

	// Field Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_fld_background_color]', function( value ) {

		$( selector ).find(inputs).css({
			backgroundColor: value(),
		});

		value.bind( function( to ) {
			$( selector ).find(inputs).css({
				backgroundColor: to,
			});
		} );

	});


	// Field Border Radius.
	wp.customize( 'mk_cz[sh_cc_sty_fld_border_radius]', function( value ) {

		$( selector ).find(inputs).css({
			'border-radius': value() + 'px',
		});

		value.bind( function( to ) {
			$( selector ).find(inputs).css({
				'border-radius': to + 'px',
			});
		} );

	});

	// Field Border Width.
	wp.customize( 'mk_cz[sh_cc_sty_fld_border_width]', function( value ) {

		$( selector ).find(inputs).css({
			borderWidth: value() + 'px',
		});

		value.bind( function( to ) {
			$( selector ).find(inputs).css({
				borderWidth: to + 'px',
			});
		} );

	});


	// Field Border Color.
	wp.customize( 'mk_cz[sh_cc_sty_fld_border_color]', function( value ) {

		$( selector ).find(inputs).css({
			borderColor: value(),
		});

		value.bind( function( to ) {
			$( selector ).find(inputs).css({
				borderColor: to,
			});
		} );

	});


	// Focus - Field Text Color.
	wp.customize( 'mk_cz[sh_cc_sty_fld_focus_color]', function( value ) {

		var el = 'sh_cc_sty_fld_focus_color';
		var styles = {};
		
		styles[ cart_fields_focus ] = 'color: ' + value() + ' !important';
		styles[ checkout_fields_focus ] = 'color: ' + value() + ' !important';
		styles[ order_fields_focus ] = 'color: ' + value() + ' !important';
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ cart_fields_focus ] = 'color: ' + value() + ' !important';
			styles[ checkout_fields_focus ] = 'color: ' + value() + ' !important';
			styles[ order_fields_focus ] = 'color: ' + value() + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );

	});

	// Focus - Field Background Color.
	wp.customize( 'mk_cz[sh_cc_sty_fld_focus_background_color]', function( value ) {

		var el = 'sh_cc_sty_fld_focus_background_color';
		var styles = {};

		styles[ cart_fields_focus ] = 'background-color: ' + value() + ' !important';
		styles[ checkout_fields_focus ] = 'background-color: ' + value() + ' !important';
		styles[ order_fields_focus ] = 'background-color: ' + value() + ' !important';
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ cart_fields_focus ] = 'background-color: ' + value() + ' !important';
			styles[ checkout_fields_focus ] = 'background-color: ' + value() + ' !important';
			styles[ order_fields_focus ] = 'background-color: ' + value() + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );

	});


	// Focus - Field Border Color.
	wp.customize( 'mk_cz[sh_cc_sty_fld_focus_border_color]', function( value ) {

		var el = 'sh_cc_sty_fld_focus_border_color';
		var styles = {};

		styles[ cart_fields_focus ] = 'border-color: ' + value() + ' !important';
		styles[ checkout_fields_focus ] = 'border-color: ' + value() + ' !important';
		styles[ order_fields_focus ] = 'border-color: ' + value() + ' !important';

		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ cart_fields_focus ] = 'border-color: ' + value() + ' !important';
			styles[ checkout_fields_focus ] = 'border-color: ' + value() + ' !important';
			styles[ order_fields_focus ] = 'border-color: ' + value() + ' !important';
			mkPreviewInternalStyle( styles, el );
		} );

	});

	wp.customize('mk_cz[sh_cc_sty_fld_box_model]', function (value) {

		if ( typeof value() === 'string' ) {
			var boxModel = jQuery.parseJSON( value() );
			$( selector ).find( 'input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code), textarea, .select2-container' ).css({
				marginTop: boxModel.margin_top + 'px',
				marginRight: boxModel.margin_right + 'px',
				marginBottom: boxModel.margin_bottom + 'px',
				marginLeft: boxModel.margin_left + 'px',
			});
			$( selector ).find( 'input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code), textarea, .select2-selection' ).css({
				paddingTop: boxModel.padding_top + 'px',
				paddingRight: boxModel.padding_right + 'px',
				paddingBottom: boxModel.padding_bottom + 'px',
				paddingLeft: boxModel.padding_left + 'px',
			});
		}

		value.bind(function (to) {
			if ( typeof to === 'string' ) {
				var boxModel = jQuery.parseJSON( to );
				$( selector ).find( 'input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code), textarea, .select2-container' ).css({
					marginTop: boxModel.margin_top + 'px',
					marginRight: boxModel.margin_right + 'px',
					marginBottom: boxModel.margin_bottom + 'px',
					marginLeft: boxModel.margin_left + 'px',
				});
				$( selector ).find( 'input:not([type="submit"]):not([type="button"]):not([type="radio"]):not([type="checkbox"]):not(.qty):not(#coupon_code), textarea, .select2-selection' ).css({
					paddingTop: boxModel.padding_top + 'px',
					paddingRight: boxModel.padding_right + 'px',
					paddingBottom: boxModel.padding_bottom + 'px',
					paddingLeft: boxModel.padding_left + 'px',
				});
			}
		});
	});
	
})(jQuery);