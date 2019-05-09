<?php
/**
 * Dynamic styles for Field Label Style section in Checkout & Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.7
 */

$css = '.woocommerce-cart .woocommerce h2:not(.woocommerce-loop-product__title),
	.woocommerce-checkout .woocommerce h2.mk-wc-title {';

	$css .= mk_cs_typography( 'sh_cc_sty_big_hdn_typography' );
	$css .= mk_cs_box_model( 'sh_cc_sty_big_hdn_box_model' );

$css .= '}';

return $css;
