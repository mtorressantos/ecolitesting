<?php
/**
 * Dynamic styles for Box Style section in Product List > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.mk-customizer ul.products li.product  .mk-product-warp {';

$background_color = mk_cz_get_option( 'sh_pl_sty_box_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$border_radius = mk_cz_get_option( 'sh_pl_sty_box_border_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = mk_cz_get_option( 'sh_pl_sty_box_border_width' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = mk_cz_get_option( 'sh_pl_sty_box_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= mk_cs_box_model( 'sh_pl_sty_box_box_model' );
$css .= '}';

return $css;

