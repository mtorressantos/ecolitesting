<?php
/**
 * Customizer Dynamic Styles: Section Rating Style.
 *
 * Prefix: pl -> product list, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 * @since 6.0.1 Add overriding style for widget star-rating color.
 */

$star_rating = '.mk-customizer ul.products li.product .star-rating';

$css = $star_rating . '{';

$font_size = mk_cz_get_option( 'sh_pl_sty_rat_font_size' );
if ( $font_size ) {
	$css .= "font-size: {$font_size}px;";
}

$star_color = mk_cz_get_option( 'sh_pl_sty_rat_active_star_color' );
if ( $star_color ) {
	$css .= "color: {$star_color};";
}

$css .= mk_cs_box_model( 'sh_pl_sty_rat_box_model' );

$css .= '}';

$css .= $star_rating . '::before {';

$star_active_color = mk_cz_get_option( 'sh_pl_sty_rat_star_color' );
if ( $star_active_color ) {
	$css .= "color: {$star_active_color};";
}

$css .= '}';

// Additional style for WC widgets.
if ( $font_size ) {
	$css .= "
		.woocommerce.widget .star-rating {
			font-size: {$font_size};
		}
	";
}

if ( $star_color ) {
	$css .= "
		.woocommerce.widget .star-rating span::before {
			color: {$star_color};
		}
	";
}

if ( $star_active_color ) {
	$css .= "
		.woocommerce.widget .star-rating::before {
			color: {$star_active_color};
		}
	";
}

return $css;
