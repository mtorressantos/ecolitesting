<?php
/**
 * Dynamic styles for Sale Price Style section in Product List > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.mk-customizer ul.products li.product .price ins .amount,';
$css .= '.mk-customizer ul.products li.product .price ins .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'sh_pl_sty_sal_prc_box_model' );
$css .= mk_cs_typography( 'sh_pl_sty_sal_prc_typography' );
$css .= '}';

return $css;

