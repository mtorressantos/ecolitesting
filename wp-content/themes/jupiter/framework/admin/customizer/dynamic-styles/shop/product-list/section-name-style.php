<?php
/**
 * Customizer Dynamic Styles: Section Product Name Style.
 *
 * Prefix: pl -> product list, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$typography = mk_maybe_json_decode( mk_cz_get_option( 'sh_pl_sty_nam_typography' ) );

$css = '';

// Dynamic styles for sh_pl_sty_nam_typography.
$css .= '.mk-customizer ul.products li.product .woocommerce-loop-product__title {';

$css .= mk_cs_typography( 'sh_pl_sty_nam_typography', array( 'size' ) );

if ( $typography ) {
	$css .= 'font-size:' . $typography->size . 'px !important;';
}

$css .= '}';

// Dynamic styles for sh_pl_sty_nam_box_model.
$css .= '.mk-customizer ul.products li.product .woocommerce-loop-product__title {' . mk_cs_box_model( 'sh_pl_sty_nam_box_model' ) . '}';

return $css;
