<?php
/**
 * Dynamic styles for Out Of Stock Badge Style section in Product List > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.mk-customizer ul.products li.product .mk-out-of-stock {';

$background_color = mk_cz_get_option( 'sh_pl_sty_oos_bdg_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$border_radius = mk_cz_get_option( 'sh_pl_sty_oos_bdg_border_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = mk_cz_get_option( 'sh_pl_sty_oos_bdg_border_width' );
if ( isset( $border_width ) ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_pl_sty_oos_bdg_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= mk_cs_box_model( 'sh_pl_sty_oos_bdg_box_model' );

$css .= mk_cs_typography( 'sh_pl_sty_oos_bdg_typography' );

$css .= '}';

return $css;
