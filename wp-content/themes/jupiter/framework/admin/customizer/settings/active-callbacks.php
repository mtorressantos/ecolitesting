<?php
/**
 * Callback functions for controls.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

/**
 * Hide sections from the Customizer.
 *
 * @since 5.9.4
 * @return boolean Returns always false.
 */
function mk_cz_hide_section() {
	return false;
}

/**
 * Check if WooCommerce is enabled.
 *
 * @since 5.9.4
 * @return boolean Returns true if WooCommerce is enabled.
 */
function mk_cz_wc_is_enabled() {
	return is_plugin_active( 'woocommerce/woocommerce.php' );
}

/**
 * Check if WooCommerce is disabled.
 *
 * @since 5.9.4
 * @return boolean Returns false if WooCommerce is enabled.
 */
function mk_cz_wc_is_disabled() {
	return ! is_plugin_active( 'woocommerce/woocommerce.php' );
}
