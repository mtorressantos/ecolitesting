<?php
/**
 * Dynamic styles for Regular Price Style section in Product List > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.mk-customizer ul.products li.product .price > .amount,';
$css .= '.mk-customizer ul.products li.product .price del .amount,';
$css .= '.mk-customizer ul.products li.product .price > .mk-price-variation-seprator,';
$css .= '.mk-customizer ul.products li.product .price del .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'sh_pl_sty_reg_prc_box_model' );
$css .= mk_cs_typography( 'sh_pl_sty_reg_prc_typography' );
$css .= '}';

return $css;
