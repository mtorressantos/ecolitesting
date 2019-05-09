<?php
/**
 * Dynamic styles for Sale Price Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.single-product .product .entry-summary .price ins .amount,';
$css .= '.single-product .product .entry-summary .price ins .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'sh_pp_sty_sal_prc_box_model' );
$css .= mk_cs_typography( 'sh_pp_sty_sal_prc_typography' );
$css .= '}';

return $css;
