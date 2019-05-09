/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var price_style_container = '.woocommerce-page ul.products li.product .price > .amount,  .mk-woocommerce-carousel.classic-style ul.products li.product .price > .amount';
	var price_style_del_container = '.woocommerce-page ul.products li.product .price del .amount, .mk-woocommerce-carousel.classic-style ul.products li.product .price del .amount';
	var price_style_separator = '.woocommerce-page ul.products li.product .price > .mk-price-variation-seprator, .mk-woocommerce-carousel.classic-style ul.products li.product .price > .mk-price-variation-seprator';
	var price_style_del_separator = '.woocommerce-page ul.products li.product .price del .mk-price-variation-seprator, .mk-woocommerce-carousel.classic-style ul.products li.product .price del .mk-price-variation-seprator';

	wp.customize( 'mk_cz[sh_pl_sty_reg_prc_typography]', function( value ) {
		$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function( to ) {
			
			$( price_style_container + ', ' + price_style_del_container + ', ' + price_style_separator + ', ' + price_style_del_separator ).css(
				mkPreviewTypography( to )
			);

		} );
	});

	wp.customize( 'mk_cz[sh_pl_sty_reg_prc_box_model]', function( value ) {
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

