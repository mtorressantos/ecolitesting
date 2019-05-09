<?php
/**
 * Customizer Dynamic Styles: Section Tags Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';


$css .= '.single-product div.product .product_meta>span.tagged_as {';
$css .= mk_cs_typography( 'sh_pp_sty_tag_typography', array( 'weight' ) );
$css .= mk_cs_box_model( 'sh_pp_sty_tag_box_model' );
$css .= '}';

$css .= '.single-product div.product .product_meta>span.tagged_as a {';
$css .= mk_cs_typography( 'sh_pp_sty_tag_typography' );
$css .= '}';

return $css;
