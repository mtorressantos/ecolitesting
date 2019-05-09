<?php
/**
 * Customizer Dynamic Styles: Section Add to Cart Button Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Control: sh_pp_sty_atc_btn_show_icon.
$css .= '.single-product div.product .single_add_to_cart_button svg {';
$show_icon = ( 'true' === mk_cz_get_option( 'sh_pp_sty_atc_btn_show_icon', 'true' ) ) ? 'inline-block' : 'none';
$css .= 'display:' . $show_icon . ';';
$css .= 'fill: ' . mk_cz_get_option( 'sh_pp_sty_atc_btn_icon_color' ) . ';';
$css .= '}';

$css .= '.single-product div.product .single_add_to_cart_button {';
$css .= mk_cs_typography( 'sh_pp_sty_atc_btn_typography' );
$css .= mk_cs_box_model( 'sh_pp_sty_atc_btn_box_model' );

$background_color = mk_cz_get_option( 'sh_pp_sty_atc_btn_background_color', '#f97352' );
if ( $background_color ) {
	$css .= "background-color: {$background_color} !important;";
}

$border_radius = mk_cz_get_option( 'sh_pp_sty_atc_btn_border_radius', 3 );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = mk_cz_get_option( 'sh_pp_sty_atc_btn_border' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_pp_sty_atc_btn_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= '}';

$css .= '.single-product div.product .single_add_to_cart_button:hover {';

$text_hover_color = mk_cz_get_option( 'sh_pp_sty_atc_btn_color_hover' );
if ( $text_hover_color ) {
	$css .= "color: {$text_hover_color};";
}

$background_hover_color = mk_cz_get_option( 'sh_pp_sty_atc_btn_background_color_hover', '#ae5039' );
if ( $background_hover_color ) {
	$css .= "background-color: {$background_hover_color} !important;";
}

$border_hover_color = mk_cz_get_option( 'sh_pp_sty_atc_btn_border_color_hover' );
if ( $border_hover_color ) {
	$css .= "border-color: {$border_hover_color};";
}

$css .= '}';
$css .= '.single-product div.product .single_add_to_cart_button:hover svg {';
$css .= 'fill: ' . mk_cz_get_option( 'sh_pp_sty_atc_btn_icon_color_hover' ) . ';';
$css .= '}';

return $css;
