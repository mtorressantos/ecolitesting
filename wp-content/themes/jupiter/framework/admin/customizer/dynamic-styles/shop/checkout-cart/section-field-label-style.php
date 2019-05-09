<?php
/**
 * Dynamic styles for Field Label Style section in Checkout & Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.7
 */

$css = 'body.woocommerce-checkout .woocommerce .form-row label:not(.woocommerce-form__label-for-checkbox) {';

	$css .= mk_cs_typography( 'sh_cc_sty_fld_lbl_typography' );
	$css .= mk_cs_box_model( 'sh_cc_sty_fld_lbl_box_model' );

$css .= '}';

return $css;
