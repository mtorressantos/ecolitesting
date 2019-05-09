<?php
/**
 * Dynamic styles for Boxes Style section in Checkout-Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 * @since 6.0.1 Add style into checkout Apply Coupun button.
 */

$coupon = '.woocommerce .cart .coupon input.button, .woocommerce .cart .coupon button.button';
$coupon_checkout = '.woocommerce-checkout .checkout_coupon input.button';
$cart = '.woocommerce-cart .woocommerce-cart-form input.button';
$checkout = '.woocommerce-cart #mk-checkout-button#mk-checkout-button';
$el_order = '.woocommerce-checkout .woocommerce-checkout #payment #place_order';
$element_combined = $coupon . ', ' . $cart . ', ' . $checkout . ', ' . $el_order . ', ' . $coupon_checkout;
$element_combined_hover = $coupon . ':hover, ' . $cart . ':hover, ' . $checkout . ':hover,' . $el_order . ':hover, ' . $coupon_checkout . ':hover';

$css = $element_combined . '{ ';
$css .= mk_cs_typography( 'sh_cc_sty_btn_typography' );
$css .= mk_cs_box_model( 'sh_cc_sty_btn_box_model' );

$background_color = mk_cz_get_option( 'sh_cc_sty_btn_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color} !important;";
}

$border_radius = mk_cz_get_option( 'sh_cc_sty_btn_border_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = mk_cz_get_option( 'sh_cc_sty_btn_border' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_cc_sty_btn_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= '}';
$css .= $element_combined_hover . '{ ';

$color_hover = mk_cz_get_option( 'sh_cc_sty_btn_color_hover' );
if ( $color_hover ) {
	$css .= "color: {$color_hover};";
}

$background_color_hover = mk_cz_get_option( 'sh_cc_sty_btn_background_color_hover' );
if ( $background_color_hover ) {
	$css .= "background-color: {$background_color_hover} !important;";
}

$border_color_hover = mk_cz_get_option( 'sh_cc_sty_btn_border_color_hover' );
if ( $border_color_hover ) {
	$css .= "border-color: {$border_color_hover};";
}

$css .= '}';
$css .= $cart . ':disabled { ';
$css .= mk_cs_box_model( 'sh_cc_sty_btn_box_model' );
$css .= '}';
$css .= $cart . ':disabled:hover { ';
$css .= 'background-color: #bbbbbf !important';
$css .= '}';


return $css;
