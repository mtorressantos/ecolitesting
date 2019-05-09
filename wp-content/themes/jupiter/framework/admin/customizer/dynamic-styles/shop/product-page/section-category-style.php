<?php
/**
 * Customizer Dynamic Styles: Section Category Style.
 *
 * Prefix: pl -> product list, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

$css .= '.single-product div.product .product_meta>span.posted_in {';
$css .= mk_cs_typography( 'sh_pp_sty_cat_typography', array( 'weight' ) );
$css .= mk_cs_box_model( 'sh_pp_sty_cat_box_model' );
$css .= '}';

$css .= '.single-product div.product .product_meta>span.posted_in a {';
$css .= mk_cs_typography( 'sh_pp_sty_cat_typography' );
$css .= '}';

return $css;
