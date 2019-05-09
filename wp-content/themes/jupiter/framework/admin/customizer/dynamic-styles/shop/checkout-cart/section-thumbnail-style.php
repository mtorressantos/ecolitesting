<?php
/**
 * Dynamic styles for Thumbnail Style section in Checkout & Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.7
 */

$display = mk_cz_get_option( 'sh_cc_sty_tmn_display' );
$border_radius = mk_cz_get_option( 'sh_cc_sty_tmn_border_radius' );
$border_width = mk_cz_get_option( 'sh_cc_sty_tmn_border_width' );
$border_color = mk_cz_get_option( 'sh_cc_sty_tmn_border_color' );

$css = '.woocommerce .cart_item .mk-cart-product-image {';
	$css .= 'display:' . ( 'true' === $display ) ? 'inline' : 'none';
$css .= '}';

$css = '.woocommerce .cart_item .product-image {';
	$css .= 'display:' . ( 'true' === $display ) ? 'block' : 'none';
$css .= '}';

$css .= '.woocommerce .cart_item .mk-cart-product-image img, .woocommerce .cart_item .product-image img {';
	$css .= "border-radius: {$border_radius}px;";
	$css .= "border-width: {$border_width}px;";
	$css .= "border-color: {$border_color};";
$css .= '}';

$css .= '.woocommerce .cart_item .mk-cart-product-image img {';
	$css .= mk_cs_box_model( 'sh_cc_sty_tmn_box_model' );
$css .= '}';

return $css;
