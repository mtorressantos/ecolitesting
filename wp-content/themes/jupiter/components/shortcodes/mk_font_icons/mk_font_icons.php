<?php
$phpinfo = pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$html = file_get_contents( $path . '/template.php' );
$html = phpQuery::newDocument( $html );

$id = Mk_Static_Files::shortcode_id();

$font_icon_container = pq( '.mk-font-icons' );
$font_icon_container->attr( 'id', 'mk-font-icons-' . $id );
$font_icon = $font_icon_container->find( '.font-icon' );
$animation_css = $circle_style = '';
$sizes = array(
	'16px' => 'small',
	'32px' => 'medium',
	'48px' => 'large',
	'64px' => 'x-large',
	'128px' => 'xx-large',
	'256px' => 'xxx-large',
);

// Main logic here.
$color = ! empty( $color ) ? ( 'fill:' . $color . ';' ) : '';

if ( 'true' == $circle ) {
	$font_icon->addClass( 'circle-enabled' );
	$font_icon->addClass( 'center-icon' );
	$circle_style = '
		background-color:' . $circle_color . ';
		border-width: ' . $circle_border_width . 'px;
		border-color: ' . $circle_border_color . ';
		border-style: ' . $circle_border_style . ';
	';
}


$font_icon_container->addClass( 'icon-align-' . $align );
$font_icon_container->addClass( get_viewport_animation_class( $animation ) );
$font_icon_container->addClass( $el_class );
$font_icon_container->addClass( $visibility );

if ( $link ) {
	$font_icon->wrap( '<a target="' . $target . '" href="' . $link . '" class="js-smooth-scroll">' );
}
if ( ! empty( $icon ) ) {
	$icon = ( false !== strpos( $icon, 'mk-' ) ) ? $icon : ( 'mk-' . $icon . '' );
	$font_icon->addClass( 'mk-size-' . $size );

	$svg = null;
	$size_px = intval( array_search( $size, $sizes ) );

	if ( 'gradient_color' == $color_style ) {
		$svg = Mk_SVG_Icons::get_svg_icon_by_class_name( false, $icon, null, null, $grandient_color_style, $grandient_color_angle, $grandient_color_from, $grandient_color_to );
	} else if ( 'single_color' == $color_style ) {
		$svg = Mk_SVG_Icons::get_svg_icon_by_class_name( false, $icon );
		Mk_Static_Files::addCSS( '
		#mk-font-icons-' . $id . ' .font-icon svg {
			' . $color . '
		}
    ', $id );
	}

	if ( ! empty( $svg ) ) {
		$font_icon->append( $svg );
	}
}


/**
 * Custom CSS Output
 * ==================================================================================*/

Mk_Static_Files::addCSS( '
	#mk-font-icons-' . $id . ' {
		margin: ' . $margin_vertical . 'px ' . $margin_horizental . 'px;
	}
	#mk-font-icons-' . $id . ' .font-icon {
		' . $circle_style . '
	}
', $id );

print $html;
