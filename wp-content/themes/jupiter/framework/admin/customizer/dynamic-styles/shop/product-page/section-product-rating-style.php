<?php
/**
 * Customizer Dynamic Styles: Section Rating Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$star_rating = '.single-product .product div.woocommerce-product-rating';

$css = $star_rating . ' .star-rating {';

$font_size = mk_cz_get_option( 'sh_pp_sty_rat_font_size' );
if ( $font_size ) {
	$css .= "font-size: {$font_size}px;";
}

$css .= '}';
$css .= $star_rating . '  .star-rating span::before {';

$star_color = mk_cz_get_option( 'sh_pp_sty_rat_active_star_color' );
if ( $star_color ) {
	$css .= "color: {$star_color} !important;";
}

$css .= '}';
$css .= $star_rating . ' .star-rating::before {';

$star_active_color = mk_cz_get_option( 'sh_pp_sty_rat_star_color' );
if ( $star_active_color ) {
	$css .= "color: {$star_active_color};";
}

$css .= '}';
$css .= $star_rating . ' .woocommerce-review-link {';
$css .= mk_cs_typography( 'sh_pp_sty_rat_typography' );
$css .= '}';
$css .= $star_rating . ' {';
$css .= mk_cs_box_model( 'sh_pp_sty_rat_box_model' );
$css .= '}';

return $css;


