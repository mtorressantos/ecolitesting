<?php
/**
 * Customizer Dynamic Styles: Section Product Name Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Dynamic styles for sh_pp_sty_nam_typography.
$css .= '.single-product div.product .product_title {' . mk_cs_typography( 'sh_pp_sty_nam_typography' ) . '}';

// Dynamic styles for sh_pp_sty_nam_box_model.
$css .= '.single-product div.product .product_title {' . mk_cs_box_model( 'sh_pp_sty_nam_box_model' ) . '}';

return $css;
