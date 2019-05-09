<?php
/**
 * Customizer Dynamic Styles: Section Tags Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

$networks = mk_cz_get_option( 'sh_pp_sty_soc_shr_networks' );

if ( $networks ) {
	$css .= '.single-product .product .social-share .share-by {';
	$css .= 'display:none;';
	$css .= '}';
	if ( is_string( $networks ) ) {
		$networks = explode( ',', $networks );
	}
	$networks_style = array();
	foreach ( $networks as $network ) {
		$networks_style[] = '.single-product .product .social-share .share-by-' . $network;
	}

	$css .= implode( ', ', $networks_style ) . '{';
	$css .= 'display:inline-block;';
	$css .= '}';
}

$css .= '.single-product .product .social-share .share-by a {';
$css .= 'background-color:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_background_color', 'rgba(200, 200, 200, 0)' ) . ';';
$css .= 'border-color:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_border_color', 'rgba(34, 34, 34, 0)' ) . ';';
$css .= 'border-width:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_border', '0' ) . 'px;';
$css .= 'border-radius:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_border_radius', '0' ) . 'px;';
$css .= '}';

$css .= '.single-product .product .social-share .share-by:hover a {';
$css .= 'background-color:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_background_color_hover', 'rgba(200, 200, 200, 0)' ) . ';';
$css .= 'border-color:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_border_color_hover', 'rgba(34, 34, 34, 0)' ) . ';';
$css .= '}';

$css .= '.single-product .product .social-share .share-by svg {';
$css .= 'fill:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_fill_color', 'rgba(34, 34, 34, 1)' ) . ';';
$css .= '}';

$css .= '.single-product .product .social-share .share-by:hover svg {';
$css .= 'fill:' . mk_cz_get_option( 'sh_pp_sty_soc_shr_fill_color_hover', 'rgba(34, 34, 34, 1)' ) . ';';
$css .= '}';

$css .= '.single-product .product .social-share {';
$css .= mk_cs_box_model( 'sh_pp_sty_soc_shr_box_model' );
$css .= '}';

return $css;
