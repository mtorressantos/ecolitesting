<?php
/**
 * WooCommerce hooks actions and filters global scope.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

// Enqueue woocommerce styles.
add_action( 'wp_enqueue_scripts', function() {

	$mk_woocommerce_common_file = '/plugins/min/woocommerce-common.' . THEME_VERSION . '.css';

	wp_enqueue_style(
		'mk-woocommerce-common',
		THEME_STYLES . $mk_woocommerce_common_file,
		'',
		filemtime( THEME_STYLES_DIR . $mk_woocommerce_common_file )
	);

	$mk_cz_woocommerce_css_file = '/woocommerce/assets/css/woocommerce.' . THEME_VERSION . '.css';

	wp_enqueue_style(
		'mk-cz-woocommerce',
		THEME_CUSTOMIZER_URI . $mk_cz_woocommerce_css_file,
		'',
		filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_woocommerce_css_file )
	);

	$mk_cz_woocommerce_js_file = '/woocommerce/assets/js/woocommerce.' . THEME_VERSION . '.js';

	wp_enqueue_script(
		'mk-cz-woocommerce',
		THEME_CUSTOMIZER_URI . $mk_cz_woocommerce_js_file,
		array( 'jquery' ),
		filemtime( THEME_CUSTOMIZER_DIR . $mk_cz_woocommerce_js_file ),
		true
	);

} );

// Add `mk-customizer` to body classes.
add_filter( 'body_class', function( $classes ) {
	return array_merge( $classes, array( 'mk-customizer' ) );
} );

// Change default template folder.
add_filter( 'woocommerce_template_path', function( $path ) {

	// Modification: Get the template from this customizer, if it exists.
	if ( is_dir( THEME_CUSTOMIZER_DIR . '/woocommerce/templates' ) ) {
		$path = 'framework/admin/customizer/woocommerce/templates/';
	}

	// Return what we found.
	return $path;
} );


add_action( 'woocommerce_check_cart_items', 'mk_woo_steps', 9 );
add_action( 'mk_woocommerce_before_complete_order', 'mk_woo_steps' );

/**
 * Renders the steps nan in cart pages.
 *
 * @since 5.9.7
 * @since 6.0.1 Make strings translatable.
 */
function mk_woo_steps() {
	$step_style = mk_cz_get_option( 'sh_cc_sty_stp_style', 'number' );
	$steps_class = '';
	$step_cart_class = '';
	$step_payment_class = '';
	$step_complete_class = '';
	if ( is_cart() ) {
		$steps_class = 'mk-checkout-steps-cart-active';
		$step_cart_class = 'mk-checkout-step-active';
	} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
		$steps_class = 'mk-checkout-steps-complete-active';
		$step_complete_class = 'mk-checkout-step-active';
	} elseif ( is_checkout() ) {
		$steps_class = 'mk-checkout-steps-payment-active';
		$step_payment_class = 'mk-checkout-step-active';
	}

	if ( 'number' === $step_style ) {
		echo '
		<div class="mk-checkout-steps mk-checkout-steps-number ' . esc_attr( $steps_class ) . '">' .
			'<span class="mk-checkout-step mk-checkout-step-cart ' . esc_attr( $step_cart_class ) . '"><span class="mk-checkout-step-svg-wrap"><svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" fill="#d8d8d8"/></svg></span><span class="mk-checkout-step-number">1</span><span class="mk-checkout-step-text">' . esc_html__( 'Cart', 'mk_framework' ) . '</span></span>' .
			'<span class="mk-checkout-step mk-checkout-step-payment ' . esc_attr( $step_payment_class ) . '"><span class="mk-checkout-step-svg-wrap"><svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" fill="#d8d8d8"/></svg></span><span class="mk-checkout-step-number">2</span><span class="mk-checkout-step-text">' . esc_html__( 'Delivery & Payment', 'mk_framework' ) . '</span></span>' .
			'<span class="mk-checkout-step mk-checkout-step-complete ' . esc_attr( $step_complete_class ) . '"><span class="mk-checkout-step-svg-wrap"><svg width="18" height="18" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" fill="#d8d8d8"/></svg></span><span class="mk-checkout-step-number">3</span><span class="mk-checkout-step-text">' . esc_html__( 'Complete Order', 'mk_framework' ) . '</span></span>' .
		'</div>';
	} elseif ( 'icon' === $step_style ) {
		echo '
		<div class="mk-checkout-steps mk-checkout-steps-icon ' . esc_attr( $steps_class ) . '">' .
			'<span class="mk-checkout-step mk-checkout-step-cart ' . esc_attr( $step_cart_class ) . '">
				<span class="mk-checkout-step-svg-wrap"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="54px" height="50.4px" viewBox="0 0 54 50.4" style="enable-background:new 0 0 54 50.4;" xml:space="preserve"><path d="M54,27.9v-20c0-0.8-0.7-1.5-1.5-1.5H11.8l-0.9-5.2C10.8,0.5,10.2,0,9.4,0H1.5C0.7,0,0,0.7,0,1.5S0.7,3,1.5,3
					h6.7L14,38.2c0.1,0.7,0.8,1.3,1.5,1.3h37c0.8,0,1.5-0.7,1.5-1.5s-0.7-1.5-1.5-1.5H21.1l31.8-7C53.5,29.2,54,28.6,54,27.9z M51,26.7
					l-34.6,7.7l-4.2-25H51V26.7z M44,40.4c-2.8,0-5,2.2-5,5s2.2,5,5,5s5-2.2,5-5S46.8,40.4,44,40.4z M44,47.4c-1.1,0-2-0.9-2-2
					s0.9-2,2-2s2,0.9,2,2S45.1,47.4,44,47.4z M24,40.4c-2.8,0-5,2.2-5,5s2.2,5,5,5s5-2.2,5-5S26.7,40.4,24,40.4z M24,47.4
					c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S25.1,47.4,24,47.4z"/></svg>
				</span>
				<span class="mk-checkout-step-text">' . esc_html__( 'Cart', 'mk_framework' ) . '</span>
			</span>' .
			'<span class="mk-checkout-step-seprator"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25.3px" height="47.6px" viewBox="0 0 25.3 47.6" style="enable-background:new 0 0 25.3 47.6;" xml:space="preserve"><path class="st0" d="M24.9,22.7L2.6,0.4C2-0.1,1-0.1,0.4,0.4s-0.6,1.5,0,2.1l21.2,21.2L0.4,45.1c-0.6,0.6-0.6,1.5,0,2.1c0.3,0.3,0.7,0.4,1.1,0.4s0.8-0.1,1.1-0.4l22.3-22.3c0.3-0.3,0.4-0.7,0.4-1.1S25.1,23,24.9,22.7z"/></svg></span><span class="mk-checkout-step mk-checkout-step-payment ' . esc_attr( $step_payment_class ) . '">
				<span class="mk-checkout-step-svg-wrap"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="63px" height="50.9px" viewBox="0 0 63 50.9" style="enable-background:new 0 0 63 50.9;" xml:space="preserve"><path d="M58.5,17.9H45v-5.2c0-0.4-0.2-0.7-0.4-1L35.9,0.6C35.6,0.2,35.2,0,34.7,0H10.2C9.8,0,9.4,0.2,9.1,0.6L0.4,11.7
					C0.2,12,0,12.3,0,12.7v26.8C0,40.4,0.7,41,1.5,41H13v5.4c0,2.5,2,4.5,4.5,4.5h41c2.5,0,4.5-2,4.5-4.5v-24C63,20,61,17.9,58.5,17.9z
					M24,3h10l6.2,8H24V3z M11,3h10v8H4.8L11,3z M13,22.5V38H3V14h39v4H17.5C15,17.9,13,20,13,22.5z M60,46.4c0,0.8-0.7,1.5-1.5,1.5h-41
					c-0.8,0-1.5-0.7-1.5-1.5V32h44V46.4z M60,25H16v-2.5c0-0.8,0.7-1.5,1.5-1.5h26c0,0,0,0,0,0s0,0,0,0h15c0.8,0,1.5,0.7,1.5,1.5V25z
					M20,37h7c0.6,0,1-0.4,1-1s-0.4-1-1-1h-7c-0.6,0-1,0.4-1,1S19.5,37,20,37z M20,41h1c0.6,0,1-0.4,1-1s-0.4-1-1-1h-1c-0.6,0-1,0.4-1,1
					S19.5,41,20,41z M26,39h-1c-0.6,0-1,0.4-1,1s0.4,1,1,1h1c0.6,0,1-0.4,1-1S26.5,39,26,39z M30,41h1c0.6,0,1-0.4,1-1s-0.4-1-1-1h-1
					c-0.6,0-1,0.4-1,1S29.5,41,30,41z M36,39h-1c-0.6,0-1,0.4-1,1s0.4,1,1,1h1c0.6,0,1-0.4,1-1S36.5,39,36,39z M31,37h7c0.6,0,1-0.4,1-1
					s-0.4-1-1-1h-7c-0.6,0-1,0.4-1,1S30.5,37,31,37z"/></svg>
				</span>
				<span class="mk-checkout-step-text">' . esc_html__( 'Delivery & Payment', 'mk_framework' ) . '</span>
			</span>' .
			'<span class="mk-checkout-step-seprator"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25.3px" height="47.6px" viewBox="0 0 25.3 47.6" style="enable-background:new 0 0 25.3 47.6;" xml:space="preserve"><path class="st0" d="M24.9,22.7L2.6,0.4C2-0.1,1-0.1,0.4,0.4s-0.6,1.5,0,2.1l21.2,21.2L0.4,45.1c-0.6,0.6-0.6,1.5,0,2.1c0.3,0.3,0.7,0.4,1.1,0.4s0.8-0.1,1.1-0.4l22.3-22.3c0.3-0.3,0.4-0.7,0.4-1.1S25.1,23,24.9,22.7z"/></svg></span><span class="mk-checkout-step mk-checkout-step-complete ' . esc_attr( $step_complete_class ) . '">
				<span class="mk-checkout-step-svg-wrap"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="39px" height="46px" viewBox="0 0 39 46" style="enable-background:new 0 0 39 46;" xml:space="preserve"><path d="M31,23.5v-20C31,1.6,29.4,0,27.5,0h-24C1.6,0,0,1.6,0,3.5v31C0,36.4,1.6,38,3.5,38h13.1c1.5,4.6,5.8,8,11,8
					C33.9,46,39,40.8,39,34.5C39,29.4,35.6,25,31,23.5z M16,35H3.5C3.2,35,3,34.8,3,34.5v-31C3,3.2,3.2,3,3.5,3h24C27.8,3,28,3.2,28,3.5
					V23c-0.2,0-0.3,0-0.5,0C21.2,23,16,28.2,16,34.5C16,34.7,16,34.8,16,35z M27.5,43c-4.7,0-8.5-3.8-8.5-8.5s3.8-8.5,8.5-8.5
					s8.5,3.8,8.5,8.5S32.2,43,27.5,43z M11,9h11c0.6,0,1-0.4,1-1s-0.4-1-1-1H11c-0.6,0-1,0.4-1,1S10.4,9,11,9z M11,13h7c0.6,0,1-0.4,1-1
					s-0.4-1-1-1h-7c-0.6,0-1,0.4-1,1S10.4,13,11,13z M11,17h9c0.6,0,1-0.4,1-1s-0.4-1-1-1h-9c-0.6,0-1,0.4-1,1S10.4,17,11,17z M24,20
					c0-0.6-0.4-1-1-1H11c-0.6,0-1,0.4-1,1s0.4,1,1,1h12C23.5,21,24,20.5,24,20z M13,23h-2c-0.6,0-1,0.4-1,1s0.4,1,1,1h2c0.6,0,1-0.4,1-1
					S13.5,23,13,23z M8,7C7.4,7,7,7.5,7,8c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C9,7.5,8.5,7,8,7z M8,11c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1
					c0.6,0,1-0.4,1-1C9,11.4,8.5,11,8,11z M8,15c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C9,15.4,8.5,15,8,15z M8,19
					c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1C9,19.4,8.5,19,8,19z M8,23c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1c0.6,0,1-0.4,1-1
					C9,23.5,8.5,23,8,23z M29.5,31.5l-2.9,2.9l-1-1c-0.6-0.6-1.5-0.6-2.1,0s-0.6,1.5,0,2.1l2,2c0.3,0.3,0.7,0.4,1.1,0.4
					c0.4,0,0.8-0.1,1.1-0.4l4-4c0.6-0.6,0.6-1.5,0-2.1C31,30.9,30,30.9,29.5,31.5z"/></svg>
				</span>
				<span class="mk-checkout-step-text">' . esc_html__( 'Complete Order', 'mk_framework' ) . '</span>
			</span>' .
		'</div>';
	} // End if().

}

/**
 * Add column-* class to body.
 *
 * @var array
 * @since 5.9.4
 */
add_filter( 'body_class', function( $classes ) {

	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$classes[] = 'columns-' . mk_cz_get_option( 'sh_pl_set_columns', 4 );
	}

	/**
	 * Since other products loops are using SC styles in Jupiter, it's better to declare
	 * mk-sh-pl-hover-style-{hover-style} in all pages. So, hover effect will be affected
	 * by all product loops. Check task AM-2982.
	 *
	 * @since 6.1.2
	 */
	$classes[] = 'mk-sh-pl-hover-style-' . mk_cz_get_option( 'sh_pl_set_hover_style', 'none' );

	return $classes;
}, 10 );
