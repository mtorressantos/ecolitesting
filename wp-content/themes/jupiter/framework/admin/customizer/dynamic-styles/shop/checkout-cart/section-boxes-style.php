<?php
/**
 * Dynamic styles for Boxes Style section in Checkout-Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$coupon = '.woocommerce-page form.checkout_coupon';
$order_table = '.woocommerce-page table.woocommerce-checkout-review-order-table';
$payment_box = '.woocommerce-page .woocommerce-checkout #payment div.payment_box';
$checkout_login = '.woocommerce-page form.woocommerce-form-login';
$shipping = '.woocommerce-page .cart_totals .shop_table tr.shipping';
$element_combined = $coupon . ', ' . $order_table . ', ' . $payment_box . ', ' . $checkout_login;

$css = $element_combined . ', ';
$css .= $shipping . ' th, ';
$css .= $shipping . ' td {';
$background_color = mk_cz_get_option( 'sh_cc_sty_box_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}
$css .= '}';


$border_radius = mk_cz_get_option( 'sh_cc_sty_box_border_radius' );
if ( $border_radius ) {
	$css .= $element_combined . ', ' . $shipping . ' {';
	$css .= "border-radius: {$border_radius}px;";
	$css .= '}';
	$css .= $shipping . ' th {';
	$css .= "border-top-left-radius: {$border_radius}px;";
	$css .= "border-bottom-left-radius: {$border_radius}px;";
	$css .= '}';
	$css .= $shipping . ' td {';
	$css .= "border-top-right-radius: {$border_radius}px;";
	$css .= "border-bottom-right-radius: {$border_radius}px;";
	$css .= '}';
}

$border_width = mk_cz_get_option( 'sh_cc_sty_box_border_width' );
if ( $border_width ) {
	$css .= $element_combined . ', ';
	$css .= $shipping . ', ';
	$css .= $shipping . ' th, ';
	$css .= $shipping . ' td {';
	$css .= "border-width: {$border_width}px;";
	$css .= '}';
	$css .= $shipping . ' th {';
	$css .= 'border-right-width: 0;';
	$css .= '}';
	$css .= $shipping . ' td {';
	$css .= 'border-left-width: 0;';
	$css .= '}';
}

$border_color = mk_cz_get_option( 'sh_cc_sty_box_border_color' );
if ( $border_color ) {
	$css .= $element_combined . ', ';
	$css .= $shipping . ', ';
	$css .= $shipping . ' th, ';
	$css .= $shipping . ' td {';
	$css .= "border-color: {$border_color};";
	$css .= '}';
}

$css .= $element_combined . ', ';
$css .= $shipping . ', th ';
$css .= $shipping . ' td {';
$css .= mk_cs_box_model( 'sh_cc_sty_box_box_model' );
$css .= '}';

return $css;



