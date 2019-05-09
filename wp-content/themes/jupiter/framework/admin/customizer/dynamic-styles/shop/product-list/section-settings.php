<?php
/**
 * Dynamic styles for Setting section in Product List Page.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$full_width = mk_cz_get_option( 'sh_pl_set_full_width', 'false' );
$horizontal_space = mk_cz_get_option( 'sh_pl_set_horizontal_space', 30 );
$vertical_space = mk_cz_get_option( 'sh_pl_set_vertical_space', 30 );
$product_info_align = mk_cz_get_option( 'sh_pl_set_product_info_align' );

$info_selected = mk_cz_get_option( 'sh_pl_set_product_info', array(
	'.woocommerce-loop-product__title',
	'.price ins',
	'.price del',
	'.button',
	'.star-rating',
) );

if ( $info_selected && is_string( $info_selected ) ) {
	$info_selected = explode( ',', $info_selected );
}

if ( ! is_array( $info_selected ) ) {
	$info_selected = array();
}

$wrap_margin = (($horizontal_space / 2) - $horizontal_space);

$item_margin = $horizontal_space / 2;

$info_selectors = array(
	'.woocommerce-loop-product__title',
	'.price ins',
	'.price del',
	'.button',
	'.star-rating',
);

$css = '';

if ( 'true' === $full_width ) {
	$css .= '.mk-customizer.archive .theme-page-wrapper.mk-grid {';
	$css .= 'width:100%;';
	$css .= 'max-width:100%;';
	$css .= '}';
}

$css .= '.woocommerce-page ul.products {';
$css .= 'margin-left:' . $wrap_margin . 'px;';
$css .= 'margin-right:' . $wrap_margin . 'px;';
$css .= '}';

$css .= '.woocommerce-page ul.products li.product {';
$css .= 'margin-left:' . $item_margin . 'px;';
$css .= 'margin-right:' . $item_margin . 'px;';
$css .= 'margin-bottom:' . $vertical_space . 'px;';
$css .= '}';

if ( $product_info_align ) {
	$css .= '.mk-customizer ul.products li.product .mk-product-warp {';
	$css .= 'text-align:' . $product_info_align . ';';
	$css .= '}';
}

$class = array();

foreach ( $info_selectors as $info_selector ) {
	if ( ! in_array( $info_selector, $info_selected, true ) ) {
		$class[] = '.mk-customizer ul.products li.product ' . $info_selector;
		if ( '.price del' === $info_selector ) {
			$class[] = '.mk-customizer ul.products li.product .price > .amount';
		}
	}
}

if ( $class ) {
	$css .= implode( ', ', $class ) . '{display:none;}';
}

for ( $i = 1; $i <= 4 ; $i++ ) {
	$css .= '.woocommerce-page.columns-' . $i . ' ul.products li.product {';
	$css .= 'width: calc(' . (100 / $i) . '% - ' . $horizontal_space . 'px);';
	$css .= '}';
}

return $css;
