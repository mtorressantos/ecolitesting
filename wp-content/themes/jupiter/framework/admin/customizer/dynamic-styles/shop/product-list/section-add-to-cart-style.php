<?php
/**
 * Customizer Dynamic Styles: Section Add to Cart Button Style.
 *
 * Prefix: pl -> product list, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Control: sh_pl_sty_atc_btn_show_icon.
$css .= '.mk-customizer ul.products li.product svg {';
$show_icon = ( 'true' === mk_cz_get_option( 'sh_pl_sty_atc_btn_show_icon', 'true' ) ) ? 'inline-block' : 'none';
$css .= 'display:' . $show_icon . ';';
$icon_color = mk_cz_get_option( 'sh_pl_sty_atc_btn_icon_color' );
if ( $icon_color ) {
	$css .= "fill: {$icon_color};";
}
$css .= '}';

$css .= '.mk-customizer ul.products li.product .button-text {';
$css .= mk_cs_typography( 'sh_pl_sty_atc_btn_typography' );
$css .= '}';

$css .= '.mk-customizer ul.products li.product a.button {';
$css .= mk_cs_box_model( 'sh_pl_sty_atc_btn_box_model' );
$css .= 'background-color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_background_color', '#f97352' ) . ';';
$css .= 'border-width: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_border', '0' ) . 'px;';
$css .= 'border-color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_border_color', '#000' ) . ';';
$css .= 'border-radius: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_border_radius', '3' ) . 'px;';
$css .= '}';

$css .= '.mk-customizer ul.products li.product a.button:hover {';
$css .= 'background-color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_background_color_hover', '#ae5039' ) . ';';
$css .= 'border-color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_border_color_hover', '#000' ) . ';';
$css .= 'color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_color_hover', '#fff' ) . ';';
$css .= '}';

$css .= '.mk-customizer ul.products li.product a.button:hover .button-text {';
$css .= 'color: ' . mk_cz_get_option( 'sh_pl_sty_atc_btn_color_hover', '#fff' ) . ';';
$css .= '}';
$css .= '.mk-customizer ul.products li.product a.button:hover svg {';
$icon_hover_color = mk_cz_get_option( 'sh_pl_sty_atc_btn_icon_color_hover' );
if ( $icon_hover_color ) {
	$css .= "fill: {$icon_hover_color};";
}
$css .= '}';

return $css;
