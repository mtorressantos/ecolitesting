/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

	var el_coupon = '.woocommerce-page form.checkout_coupon';
	var el_order_table = '.woocommerce-page table.woocommerce-checkout-review-order-table';
	var el_payment_box = '.woocommerce-page .woocommerce-checkout #payment div.payment_box';
	var el_shipping = '.woocommerce-page .cart_totals .shop_table tr.shipping';
	var el_combined = el_coupon + ", " + el_order_table + ", " + el_payment_box;

	wp.customize( 'mk_cz[sh_cc_sty_box_background_color]', function( value ) {

		var el = 'sh_cc_sty_box_background_color';
		var styles = {};

		styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'background-color: ' + value();
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'background-color: ' + to;
			mkPreviewInternalStyle( styles, el );	
		} );

	});

	wp.customize( 'mk_cz[sh_cc_sty_box_border_radius]', function( value ) {

		var el = 'sh_cc_sty_box_border_radius';
		var styles = {};

		styles[ el_combined + ", " + el_shipping ] = 'border-radius:' + value() + 'px';
		styles[ el_shipping + " th" ] = {
			'border-top-left-radius' : value() + 'px',
			'border-bottom-left-radius' : value() + 'px'
		}; 
		styles[ el_shipping + " td" ] = {
			'border-top-right-radius' : value() + 'px',
			'border-bottom-right-radius' : value() + 'px'
		};
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ el_combined + ", " + el_shipping ] = 'border-radius:' + to + 'px';
			styles[ el_shipping + " th" ] = {
				'border-top-left-radius' : to + 'px',
				'border-bottom-left-radius' : to + 'px'
			}; 
			styles[ el_shipping + " td" ] = {
				'border-top-right-radius' : to + 'px',
				'border-bottom-right-radius' : to + 'px'
			};
			mkPreviewInternalStyle( styles, el );
		} );

	});

	wp.customize( 'mk_cz[sh_cc_sty_box_border_width]', function( value ) {

		var el = 'sh_cc_sty_box_border_width';
		var styles = {};

		styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'border-width:' + value() + 'px';
		styles[ el_shipping + " th" ] = 'border-right-width: 0px';
		styles[ el_shipping + " td" ] = 'border-left-width: 0px';
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'border-width:' + to + 'px';
			styles[ el_shipping + " th" ] = 'border-right-width: 0px';
			styles[ el_shipping + " td" ] = 'border-left-width: 0px';
			mkPreviewInternalStyle( styles, el );
		} );

	});

	wp.customize( 'mk_cz[sh_cc_sty_box_border_color]', function( value ) {

		var el = 'sh_cc_sty_box_border_color';
		var styles = {};

		styles[ el_combined + ", " + el_shipping + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'border-color:' + value();
		mkPreviewInternalStyle( styles, el );

		value.bind( function( to ) {
			styles[ el_combined + ", " + el_shipping + ", " + el_shipping + " th, " + el_shipping + " td" ] = 'border-color:' + to;
			mkPreviewInternalStyle( styles, el );
		} );

	});

	wp.customize( 'mk_cz[sh_cc_sty_box_box_model]', function( value ) {

		var el = 'sh_cc_sty_box_box_model';
		var styles = {};

		styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = mkPreviewBoxModel( value() );
		mkPreviewInternalStyle( styles, el );

		value.bind(function (to) {
			styles[ el_combined + ", " + el_shipping + " th, " + el_shipping + " td" ] = mkPreviewBoxModel( to );
			mkPreviewInternalStyle( styles, el );
		});

	});

} )( jQuery );

