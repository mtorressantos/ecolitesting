<?php
/**
 * Dynamic styles for Description Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.single-product div.product .woocommerce-product-details__short-description {';
$css .= mk_cs_box_model( 'sh_pp_sty_des_box_model' );

$background_color = mk_cz_get_option( 'sh_pp_sty_des_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$css .= '}';
$css .= '.single-product div.product .woocommerce-product-details__short-description,';
$css .= '.single-product div.product .woocommerce-product-details__short-description p {';
$css .= mk_cs_typography( 'sh_pp_sty_des_typography' );
$css .= '}';

return $css;
