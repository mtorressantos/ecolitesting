<?php
/**
 * Customizer Dynamic Styles: Section Variations Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 6.0.1
 */

$css = '';
$variations = '.single-product div.product form.cart .variations';

$css .= $variations . ' td {';
$css .= mk_cs_box_model( 'sh_pp_sty_var_box_model' );
$css .= '}';

$css .= $variations . ' td label,';
$css .= $variations . ' td select {';
$css .= mk_cs_typography( 'sh_pp_sty_var_typography' );
$css .= '}';

$css .= $variations . ' td select {';

$background_color = mk_cz_get_option( 'sh_pp_sty_var_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$border_width = mk_cz_get_option( 'sh_pp_sty_var_border' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_pp_sty_var_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= '}';

return $css;
