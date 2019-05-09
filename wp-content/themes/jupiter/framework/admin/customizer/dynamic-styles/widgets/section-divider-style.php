<?php
/**
 * Customizer Dynamic Styles: Section Widget Divider Style.
 *
 * * Prefix: s -> shop, w -> widgets, s -> styles, t -> title.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$border_color = mk_cz_get_option( 'wg_glb_sty_div_border_color', '#d5d8de' );
$border_width = mk_cz_get_option( 'wg_glb_sty_div_border_width', '0' );

$css = '';
$css .= '#mk-sidebar .widget::after {';
	$css .= 'content: "";';
	$css .= 'display: block;';
	$css .= 'width: 100%;';
	$css .= 'border-bottom-style: solid;';
	$css .= "border-bottom-width: {$border_width}px;";
	$css .= "border-bottom-color: {$border_color};";
	$css .= mk_cs_box_model( 'wg_glb_sty_div_box_model' );
$css .= '}';

return $css;
