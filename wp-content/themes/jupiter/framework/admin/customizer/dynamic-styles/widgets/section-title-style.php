<?php
/**
 * Customizer Dynamic Styles: Section Widget Title Style.
 *
 * * Prefix: s -> shop, w -> widgets, s -> styles, t -> title.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';
$css .= '#mk-sidebar div.widgettitle {';
$css .= 'margin-bottom: 0px;';
$css .= 'padding-bottom: 15px;';
$css .= 'line-height: 1.66em;';
$css .= mk_cs_typography( 'wg_glb_sty_ttl_typography' );
$css .= mk_cs_box_model( 'wg_glb_sty_ttl_box_model' );

$line_height = mk_cz_get_option( 'wg_glb_sty_ttl_line_height' );
if ( $line_height ) {
	$css .= "line-height: {$line_height}em;";
}

$css .= '}';

return $css;
