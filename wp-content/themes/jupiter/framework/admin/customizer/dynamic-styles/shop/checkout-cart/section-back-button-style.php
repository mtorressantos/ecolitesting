<?php
/**
 * Dynamic styles for Boxes Style section in Checkout-Cart > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$back_button = '.woocommerce-page a.button.mk-wc-backword';
$back_button_hover = '.woocommerce-page a.button.mk-wc-backword:hover';

$css = $back_button . '{ ';
$css .= mk_cs_typography( 'sh_cc_sty_bck_btn_typography' );
$css .= mk_cs_box_model( 'sh_cc_sty_bck_btn_box_model' );

$background_color = mk_cz_get_option( 'sh_cc_sty_bck_btn_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$border_radius = mk_cz_get_option( 'sh_cc_sty_bck_btn_border_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = mk_cz_get_option( 'sh_cc_sty_bck_btn_border' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_cc_sty_bck_btn_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= '}';
$css .= $back_button_hover . '{ ';

$color_hover = mk_cz_get_option( 'sh_cc_sty_bck_btn_color_hover' );
if ( $color_hover ) {
	$css .= "color: {$color_hover};";
}

$background_color_hover = mk_cz_get_option( 'sh_cc_sty_bck_btn_background_color_hover' );
if ( $background_color_hover ) {
	$css .= "background-color: {$background_color_hover};";
}

$border_color_hover = mk_cz_get_option( 'sh_cc_sty_bck_btn_border_color_hover' );
if ( $border_color_hover ) {
	$css .= "border-color: {$border_color_hover};";
}

$css .= '}';

return $css;
