(function ($) {

	var el_back_button = '.woocommerce-page a.button.mk-wc-backword';
	var el_back_button_hover = '.woocommerce-page a.button.mk-wc-backword:hover';

	// Method for Control's event handlers: sh_cc_sty_bck_btn_typography.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_typography]', function (value) {
		
		var el = 'sh_cc_sty_bck_btn_typography';
		var styles = {};

		styles[ el_back_button ] = mkPreviewTypography( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button ] = mkPreviewTypography( to );
			mkPreviewInternalStyle( styles, el );
		});
	
	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_background_color.
	wp.customize( 'mk_cz[sh_cc_sty_bck_btn_background_color]', function( value ) {

		var el = 'sh_cc_sty_bck_btn_background_color';
		var styles = {};

		styles[el_back_button] = 'background-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles[el_back_button] = 'background-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		} );
	
	} );

	// Method for Control's event handlers: sh_cc_sty_bck_btn_border_radius.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_border_radius]', function (value) {
		
		var el = 'sh_cc_sty_bck_btn_border_radius';
		var styles = {};

		styles[ el_back_button ] = 'border-radius: ' + value() + 'px;';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button ] = 'border-radius: ' + to + 'px;';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_border.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_border]', function (value) {

		var el = 'sh_cc_sty_bck_btn_border';
		var styles = {};

		styles[ el_back_button ] = 'border-width: ' + value() + 'px;';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button ] = 'border-width: ' + to + 'px;';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_border_color.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_border_color]', function (value) {

		var el = 'sh_cc_sty_bck_btn_border_color';
		var styles = {};

		styles[ el_back_button ] = 'border-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button ] = 'border-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});

	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_color_hover.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_color_hover]', function (value) {
		
		var el = 'sh_cc_sty_bck_btn_color_hover';
		var styles = {};

		styles[ el_back_button_hover ] = 'color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button_hover ] = 'color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});
	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_background_color_hover.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_background_color_hover]', function (value) {

		var el = 'sh_cc_sty_bck_btn_background_color_hover';
		var styles = {};

		styles[ el_back_button_hover ] = 'background-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );
	
		value.bind( function( to ) {
			styles[ el_back_button_hover ] = 'background-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		} );
		
	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_border_color_hover.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_border_color_hover]', function (value) {

		var el = 'sh_cc_sty_bck_btn_border_color_hover';
		var styles = {};

		styles[ el_back_button_hover ] = 'border-color: ' + value() + ';';
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button_hover ] = 'border-color: ' + to + ';';
			mkPreviewInternalStyle( styles, el );
		});
	});

	// Method for Control's event handlers: sh_cc_sty_bck_btn_box_model.
	wp.customize('mk_cz[sh_cc_sty_bck_btn_box_model]', function (value) {

		var el = 'sh_cc_sty_bck_btn_box_model';
		var styles = {};

		styles[ el_back_button ] = mkPreviewBoxModel( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_back_button ] = mkPreviewBoxModel( to );
			mkPreviewInternalStyle( styles, el );
		});

	});

})(jQuery);