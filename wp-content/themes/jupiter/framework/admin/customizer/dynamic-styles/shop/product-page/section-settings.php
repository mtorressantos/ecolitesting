<?php
/**
 * Customizer Dynamic Styles: Section Settings.
 *
 * Prefix: pp -> product page
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 * @since 6.0.3 Add style for layout 10.
 */

$css = '';
$mk_options = get_option( THEME_OPTIONS );
$layout = mk_cz_get_option( 'sh_pp_set_layout', '1' );
$grid_width = ! empty( $mk_options['grid_width'] ) ? $mk_options['grid_width'] + 125 : 0;

$selectors = array(
	'.summary .price ins',
	'.summary .price del',
	'.summary .woocommerce-product-rating',
	'.summary .product_meta .posted_in',
	'.summary .product_meta .tagged_as',
	'.summary .product_meta .sku_wrapper',
	'.summary .woocommerce-product-details__short-description',
	'.summary .variations',
	'.summary .cart .quantity',
	'.summary .social-share',
	'.woocommerce-tabs #tab-title-description|.woocommerce-tabs #tab-description',
	'.woocommerce-tabs #tab-title-reviews|.woocommerce-tabs #tab-reviews',
	'.woocommerce-tabs #tab-title-additional_information|.woocommerce-tabs #tab-additional_information',
);

$selected = mk_cz_get_option( 'sh_pp_set_product_info', array(
	'.summary .price ins',
	'.summary .price del',
	'.summary .woocommerce-product-rating',
	'.summary .product_meta .posted_in',
	'.summary .product_meta .tagged_as',
	'.summary .product_meta .sku_wrapper',
	'.summary .woocommerce-product-details__short-description',
	'.summary .cart .quantity',
	'.summary .variations',
	'.summary .social-share',
	'.woocommerce-tabs #tab-title-description|.woocommerce-tabs #tab-description',
	'.woocommerce-tabs #tab-title-reviews|.woocommerce-tabs #tab-reviews',
	'.woocommerce-tabs #tab-title-additional_information|.woocommerce-tabs #tab-additional_information',
) );

if ( ! empty( $selected ) ) {
	if ( is_string( $selected ) ) {
		$selected = explode( ',', $selected );
	}
}

if ( ! is_array( $selected ) ) {
	$selected = array();
}

$class_prefix = '.woocommerce-page.single-product .product ';

$class = array();
$hide_tabs = true;
foreach ( $selectors as $selector ) {
	if ( ! in_array( $selector, $selected, true ) ) {
		$parts = explode( '|', $selector );
		foreach ( $parts as $part ) {
			$class[] = $class_prefix . $part;
			if ( '.summary .price del' === $part ) {
				$class[] = $class_prefix . '.summary .price > .amount';
			}
		}
	} else {
		if ( false !== strpos( $selector, '.woocommerce-tabs' ) ) {
			$hide_tabs = false;
		}
	}
}

if ( $hide_tabs ) {
	$class[] = $class_prefix . '.woocommerce-tabs';
}

if ( $class ) {
	$css .= implode( ', ', $class ) . '{display:none;}';
}

if ( '10' === $layout ) {
	$css .= '@media handheld, only screen and (min-width: ' . $grid_width . 'px) {
		.mk-product-layout-10 .full-width-layout {
			padding-left: 15%;
			padding-right: 5%;
		}
	}';
};

return $css;
