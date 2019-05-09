<?php
/**
 * Customizer Dynamic Styles: Section SKU Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

$css .= '.single-product div.product .product_meta>span.sku_wrapper {';
$css .= mk_cs_typography( 'sh_pp_sty_sku_typography', array( 'weight' ) );
$css .= mk_cs_box_model( 'sh_pp_sty_sku_box_model' );
$css .= '}';

$css .= '.single-product div.product .product_meta>span.sku_wrapper .sku {';
$css .= mk_cs_typography( 'sh_pp_sty_sku_typography' );
$css .= '}';

return $css;
