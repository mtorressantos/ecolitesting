/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var sale_price_style_container = '.woocommerce-page ul.products li.product .price ins .amount, .mk-woocommerce-carousel.classic-style ul.products li.product .price ins .amount';
	var sale_price_style_separator = '.woocommerce-page ul.products li.product .price ins .mk-price-variation-seprator, .mk-woocommerce-carousel.classic-style ul.products li.product .price ins .mk-price-variation-seprator';

	wp.customize( 'mk_cz[sh_pl_sty_sal_prc_typography]', function( value ) {
		$( sale_price_style_container + ', ' + sale_price_style_separator  ).css(
			mkPreviewTypography( value(), true )
		);

		value.bind(function (to) {
			$( sale_price_style_container + ', ' + sale_price_style_separator  ).css(
				mkPreviewTypography( to )
			);
		});
	});

	wp.customize( 'mk_cz[sh_pl_sty_sal_prc_box_model]', function( value ) {
		$( sale_price_style_container + ', ' + sale_price_style_separator ).css(
			mkPreviewBoxModel( value() )
		);

		value.bind(function (to) {
			$( sale_price_style_container + ', ' + sale_price_style_separator ).css(
				mkPreviewBoxModel( to )
			);
		});
	});

} )( jQuery );

