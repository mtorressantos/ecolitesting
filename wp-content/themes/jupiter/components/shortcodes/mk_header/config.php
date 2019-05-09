<?php

extract(
	shortcode_atts(
		array(
        'style'           => 1, // only header style 1 or 3 is allowed in header shortcode.
		'align'           => 'left',
		'logo'            => 'true',
		'burger_icon'     => 'true',
		'woo_cart'        => 'true',
		'search_icon'     => 'true',
		'hover_styles'    => 1,
		'menu_location'   => 'primary-menu',
		'bg_color'        => '',
		'border_color'    => '', // top and bottom border color.
		'text_color'      => '',
		'text_hover_skin' => '',
		'visibility'      => '',
		'el_class'        => '',

		), $atts
	)
);
global $is_header_shortcode_added;

/**
 * Used to fix AM-2850.
 *
 * @since 6.0.3
 *
 * If users use more than 2 header shortcodes at the same page,
 * $is_header_shortcode_added value for the 1st header shortcode will be
 * overriden by 2nd header. Need to save header type information in array.
 */
if ( ! is_array( $is_header_shortcode_added ) ) {
	$is_header_shortcode_added = array();
}

$is_header_shortcode_added[] = $style;
