<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

switch ( $product->get_type() ) {
	case 'variable':
		$icon_class = 'mk-icon-plus';
		break;
	case 'grouped':
		$icon_class = 'mk-moon-search-3';
		break;
	case 'external':
		$icon_class = 'mk-moon-search-3';
		break;
	default:
		$icon_class = 'mk-moon-cart-plus';
		break;
}

$typography = mk_maybe_json_decode( mk_cz_get_option( 'sh_pl_sty_atc_btn_typography' ) );
$icon_class = 'mk-moon-cart-plus';
$icon_size = isset( $typography->size ) ? $typography->size : 12;

if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
	$icon_class = 'mk-moon-search-3';
}

echo apply_filters(
	'woocommerce_loop_add_to_cart_link',
	sprintf(
		'<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s" %s>%s<span class="button-text">%s</span></a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $quantity ) ? $quantity : 1 ),
		esc_attr( $product->get_id() ),
		esc_attr( $product->get_sku() ),
		esc_attr( isset( $class ) ? $class : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		Mk_SVG_Icons::get_svg_icon_by_class_name( false, $icon_class, $icon_size ),
		esc_html( $product->add_to_cart_text() )
	),
	$product, $args
);
