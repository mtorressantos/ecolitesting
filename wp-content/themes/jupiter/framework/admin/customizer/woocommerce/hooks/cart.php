<?php
/**
 * WooCommerce hooks actions and filters.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

// Add a title before the cart table.
add_action( 'woocommerce_before_cart_table', function() {

	printf( '<h2>%s</h2>', esc_html__( 'Your Cart', 'mk_framework' ) );

} );

// Reorder cross-sells.
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 11 );

// Change number of cross sell grid.
add_filter( 'woocommerce_cross_sells_columns', function( $columns ) {
	return 4;
} );
