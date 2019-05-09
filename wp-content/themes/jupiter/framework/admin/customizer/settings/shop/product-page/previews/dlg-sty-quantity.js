jQuery(document).ready(function( $ ) {

	var quantity_style = '.woocommerce-page.single-product div.product div.quantity';
	var quantity_style_label = '.woocommerce-page.single-product div.product .mk-quantity-label';
	var quantity_style_input = '.woocommerce-page.single-product div.product div.quantity input.qty';
	var quantity_style_button = '.woocommerce-page.single-product div.product div.quantity .quantity-button';
	var quantity_style_button_up = '.woocommerce-page.single-product div.product div.quantity .quantity-button.quantity-up';
	var quantity_style_button_down = '.woocommerce-page.single-product div.product div.quantity .quantity-button.quantity-down';

	// Method for Control's event handlers: sh_pp_sty_qty_typography.
	wp.customize('mk_cz[sh_pp_sty_qty_typography]', function (value) {
		var typography = mkPreviewTypography(value(), true);
		// calculate quantity input with based on border and font size.
		var quantity_input_width = parseInt( typography['font-size'], 10 ) + ( parseInt(wp.customize('mk_cz[sh_pp_sty_qty_border]')(), 10)  * 2 );

		$( quantity_style_label + ', ' + quantity_style_input ).css(typography);
		$( quantity_style_input ).css( 'width', quantity_input_width + 'px' );

		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			var typography = mkPreviewTypography(to);
			// calculate quantity input with based on border and font size.
			var quantity_input_width = parseInt( typography['font-size'], 10 ) + ( parseInt(wp.customize('mk_cz[sh_pp_sty_qty_border]')(), 10) * 2 );
			parseInt( typography['font-size'], 10 ) + parseInt(wp.customize('mk_cz[sh_pp_sty_qty_border]')(), 10)
			$( quantity_style_label + ', ' + quantity_style_input ).css(typography);
			$( quantity_style_input ).css( 'width', quantity_input_width + 'px' );
		});

	});

	// Method for Control's event handlers: sh_pp_sty_qty_background_color.
	wp.customize( 'mk_cz[sh_pp_sty_qty_background_color]', function( value ) {

		$( quantity_style_input ).css( 'background-color', value() );

		value.bind( function( to ) {
			$( quantity_style_input ).css( 'background-color', to );
		} );

	} );

	// Method for Control's event handlers: sh_pp_sty_qty_border.
	wp.customize('mk_cz[sh_pp_sty_qty_border]', function (value) {

		$( quantity_style_input + ', ' + quantity_style_button ).css({
			'border-width': value() + 'px'
		});

		// Double the border value and add to quantity button.
		var quantity_btn_width = parseInt( value(), 10) * 2 + 29;
		// calculate quantity input with based on border and font size.
		var typography = mkPreviewTypography( wp.customize('mk_cz[sh_pp_sty_qty_typography]')() );
		var quantity_input_width = parseInt( typography['font-size'], 10 ) + ( parseInt(value(), 10) * 2 );

		$( quantity_style_button ).css( 'width', quantity_btn_width + 'px' );
		$( quantity_style_button_up ).css({
			'top': value() + 'px',
			'height' : 'calc(50% - ' + value() + 'px )'
		});
		$( quantity_style_button_down ).css({
			'bottom': value() + 'px',
			'height' : 'calc(50% - ' + value() + 'px )'
		});
		$( quantity_style_input ).css( 'width', quantity_input_width + 'px' );

		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			$( quantity_style_input + ', ' + quantity_style_button ).css({
				'border-width': to + 'px'
			});

			// Double the border value and add to quantity button's default value.
			var quantity_btn_width = parseInt( to, 10) * 2 + 29;
			// calculate quantity input with based on border and font size.
			var typography = mkPreviewTypography( wp.customize('mk_cz[sh_pp_sty_qty_typography]')() );
			var quantity_input_width = parseInt( typography['font-size'], 10 ) + ( parseInt(to, 10) * 2 );

			$( quantity_style_button ).css( 'width', quantity_btn_width + 'px' );
			$( quantity_style_button_up ).css({
				'top': to + 'px',
				'height' : 'calc(50% - ' + to + 'px )'
			});
			$( quantity_style_button_down ).css({
				'bottom': to + 'px',
				'height' : 'calc(50% - ' + to + 'px )'
			});
			$( quantity_style_input ).css( 'width', quantity_input_width + 'px' );
		});
	});

	// Method for Control's event handlers: sh_pp_sty_qty_border_color.
	wp.customize('mk_cz[sh_pp_sty_qty_border_color]', function (value) {
		$( quantity_style_input + ', ' + quantity_style_button ).css({
			'border-color': value()
		});
		value.bind(function (to) {
			$( quantity_style_input + ', ' + quantity_style_button ).css({
				'border-color': to
			});
		});
	});

	// Method for Control's event handlers: sh_pp_sty_qty_box_model.
	wp.customize('mk_cz[sh_pp_sty_qty_box_model]', function (value) {
		$( quantity_style ).css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$(document.body).trigger('mk-woo-align-quantity');
			$( quantity_style ).css(mkPreviewBoxModel(to));
		});
	});

});
