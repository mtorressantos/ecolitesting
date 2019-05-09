<?php
/**
 * Woocommerce Helper Functions.
 *
 * @package Jupiter
 * @since 5.9.4
 */

/**
 * Check WooCommerce version.
 *
 * @since 5.9.4
 * @param string $version The version you want to check for.
 */
function mk_is_woocommerce_version( $version = '3.0' ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, '>=' ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Reset WC template path to Jupiter woocommerce dir. Only run when SC is
 * active as SC replace WC template path.
 *
 * @since 6.1.2
 * @see   WooCommerce/class-woocommerce.php - WooCommerce::template_path()
 * @see   framework/admin/customizer/woocommerce/hooks/global.php
 *
 * @param  string $path Default WC templates path.
 * @return string       Custom WC template.
 */
function mk_reset_wc_content_product_template( $path ) {
	// If Shop Customizer is not active, just return the current template.
	if ( ! mk_is_shop_customizer_active() ) {
		return $path;
	}

	// Return Jupiter woocommerce/ dir path.
	if ( is_dir( THEME_DIR . '/woocommerce' ) ) {
		$path = 'woocommerce/';
	}

	// Return what we found.
	return $path;
}

/**
 * Check if Shop Customizer is active or not.
 *
 * @since  6.1.2
 * @see    Mk_customizer::is_section_enabled()
 *
 * @return boolean Shop customizer active status.
 */
function mk_is_shop_customizer_active() {
	$mk_options = get_option( THEME_OPTIONS );

	if ( ! empty( $mk_options['shop_customizer'] ) && 'true' === $mk_options['shop_customizer'] ) {
		return true;
	}

	return false;
}
