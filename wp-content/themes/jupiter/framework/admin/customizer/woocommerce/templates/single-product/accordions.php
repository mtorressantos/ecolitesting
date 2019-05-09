<?php
/**
 * Single Product Accordions
 *
 * This template is based on default WooCommerce single-product/tabs/tabs.php
 * This has been modified to be used for accodions.
 *
 * @author  Artbees
 * @package Customizer
 * @version 1.0.0
 * @since 6.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) {

	$accordions = '<div class="mk-woo-accordions woocommerce-tabs">[vc_accordions style="simple-style" action_style="toggle-action"]';

	foreach ( $tabs as $key => $tab ) {
		$accordions .= '[vc_accordion_tab id="tab-' . $key . '" title="' . esc_html( $tab['title'] ) . '"]';

		if ( isset( $tab['callback'] ) ) {
			ob_start();
			call_user_func( $tab['callback'], $key, $tab );
			$accordions .= ob_get_clean();
		}

		$accordions .= '[/vc_accordion_tab]';
	}

	$accordions .= '[/vc_accordions]</div>';

	echo do_shortcode( $accordions );

}
