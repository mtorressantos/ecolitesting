<?php
/**
 * Dynamic styles for Regular Price Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.single-product .product .entry-summary .price > .amount,';
$css .= '.single-product .product .entry-summary .price del .amount,';
$css .= '.single-product .product .entry-summary .price > .mk-price-variation-seprator,';
$css .= '.single-product .product .entry-summary .price del .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'sh_pp_sty_reg_prc_box_model' );
$css .= mk_cs_typography( 'sh_pp_sty_reg_prc_typography' );
$css .= '}';

return $css;
