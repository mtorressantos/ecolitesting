/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var price_style_container = '.woocommerce-page.single-product .entry-summary .price > .amount';
	var price_style_del_container = '.woocommerce-page.single-product .entry-summary .price del .amount';
	var price_style_separator = '.woocommerce-page.single-product .entry-summary .price > .mk-price-variation-seprator';
	var price_style_del_separator = '.woocommerce-page.single-product .entry-summary .price del .mk-price-variation-seprator';

	wp.customize( 'mk_cz[sh_pp_sty_reg_prc_typography]', function( value ) {
		$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function( to ) {
			
			$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
				mkPreviewTypography( to )
			);

		} );
	});

	wp.customize( 'mk_cz[sh_pp_sty_reg_prc_box_model]', function( value ) {
		$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
			mkPreviewBoxModel( value(), true )
		);

		value.bind( function( to ) {
			$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
				mkPreviewBoxModel( to )
			);
		} );
	});

} )( jQuery );

