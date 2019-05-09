<?php
/**
 * Header Builder: mkhb_icon shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Icon element shortcode.
 *
 * @since 6.0.0
 * @since 6.0.3 Rename function name for generating styles and markup.
 *
 * @param  array $atts All parameter will be used in the shortcode.
 * @return string      Rendered HTML.
 */
function mkhb_icon_shortcode( $atts ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-icon-1',
			'icon' => '',
			'url' => '',
			'alignment' => '',
			'display' => '',
			'size' => '16px',
			'color' => '',
			'box-background-color' => '',
			'box-border-radius' => '',
			'box-border-width' => '',
			'box-border-color' => '',
			'hover-color' => '',
			'hover-box-background-color' => '',
			'hover-box-border-color' => '',
			'padding' => '',
			'margin' => '',
			'alt' => 'Welcome on board',
			'target' => '_blank',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if icon is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Icon inline style.
	$style = mkhb_icon_style( $options );

	// Set Icon markup.
	$markup = mkhb_icon_markup( $options );

	// Enqueue inline style.
	$hooks = mkhb_hooks();
	$hooks::concat_hook( 'styles', $style );

	return $markup;
}
add_shortcode( 'mkhb_icon', 'mkhb_icon_shortcode' );

/**
 * Generate the element's markup for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Rename function name for generating styles and markup.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Element HTML code.
 */
function mkhb_icon_markup( $options ) {
	$markup = sprintf(
		'<div id="%s" class="mkhb-icon-el"></div>',
		esc_attr( $options['id'] )
	);

	// Icon type and size.
	$icon = mkhb_get_icon_svg( $options['icon'], intval( $options['size'] ) );

	// Render only the container, if the icon is empty.
	if ( empty( $icon ) ) {
		return $markup;
	}

	// Icon anchor URL attribute.
	$url_attr = ( ! empty( $options['url'] ) ) ? 'href="' . esc_url( $options['url'] ) . '"' : '';
	$target = ( ! empty( $options['url'] ) && ! empty( $options['target'] ) ) ? 'target="' . esc_attr( $options['target'] ) . '"' : '';

	// Icon additional class.
	$icon_class = mkhb_shortcode_display_class( $options );

	// Icon attributes.
	// @todo Temporary Solution - Data Attribute for inline container.
	$data_attr = mkhb_shortcode_display_attr( $options );

	$markup = sprintf(
		'<div id="%s" class="mkhb-icon-el %s" %s>
			<a class="mkhb-icon-el__link" %s %s alt="%s">
				%s
			</a>
		</div>',
		esc_attr( $options['id'] ),
		esc_attr( $icon_class ),
		$data_attr,
		$target,
		$url_attr,
		esc_attr( $options['alt'] ),
		$icon
	);

	return $markup;
}


/**
 * Generate the element's style for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Print icon style only if it's needed. Rename function name.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Element CSS code.
 */
function mkhb_icon_style( $options ) {
	$icon_style = '';
	$style = '';

	// Icon type and size.
	$icon = mkhb_get_icon_svg( $options['icon'], intval( $options['size'] ) );

	// Render only the container, if the icon is empty.
	if ( empty( $icon ) ) {
		return $style;
	}

	// Icon Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$icon_style .= "text-align: {$options['alignment']};";
	}

	// Icon Display.
	if ( ! empty( $options['display'] ) ) {
		if ( 'inline' === $options['display'] ) {
			$icon_style .= 'display: inline-block; vertical-align: top;';
		}
	}

	// Set icon style.
	if ( ! empty( $icon_style ) ) {
		$style .= "#{$options['id']} { $icon_style }";
	}

	// Icon Link.
	$style .= mkhb_icon_link_style( $options );

	// Icon Link Hover.
	$style .= mkhb_icon_link_hover_style( $options );

	return $style;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Print icon link style only if it's needed. Rename function.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Element CSS code.
 */
function mkhb_icon_link_style( $options ) {
	$link_style = '';

	// Icon Color.
	if ( ! empty( $options['color'] ) ) {
		$link_style .= "color: {$options['color']};";
	}

	// Icon BG Color.
	if ( ! empty( $options['box-background-color'] ) ) {
		$link_style .= "background: {$options['box-background-color']};";
	}

	// Icon border.
	$link_style .= mkhb_icon_border( $options );

	// Icon Padding.
	if ( ! empty( $options['padding'] ) ) {
		$link_style .= "padding: {$options['padding']};";
	}

	// Icon Margin.
	if ( ! empty( $options['margin'] ) ) {
		$link_style .= "margin: {$options['margin']};";
	}

	// Icon Border Radius.
	if ( ! empty( $options['box-border-radius'] ) ) {
		$link_style .= "border-radius: {$options['box-border-radius']};";
	}

	// Icon Width and Height.
	$link_style .= mkhb_icon_size( $options );

	// Set icon link style.
	$style = '';
	if ( ! empty( $link_style ) ) {
		$style .= "#{$options['id']} .mkhb-icon-el__link { $link_style }";
	}

	return $style;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * There are 2 cases here:
 * 1. If icon hover color styles are overriden, return the overriden hover style.
 * 2. If icon color styles are overriden, return the default hover style. It's used to
 *    fix hover issue on the link.
 *
 * @since 6.0.0
 * @since 6.0.3 Print icon link hover style only if it's needed. Rename function. Fix
 *              hover issue on the icon link.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Element CSS code.
 */
function mkhb_icon_link_hover_style( $options ) {
	$hover_style = '';
	$style = '';

	// If URL is empty, don't print hover style.
	if ( empty( $options['url'] ) ) {
		return $style;
	}

	// 1.a Icon Hover Color.
	// 2.a If icon color is overriden, set default color for hover state.
	if ( ! empty( $options['hover-color'] ) ) {
		$hover_style .= "color: {$options['hover-color']};";
	} elseif ( ! empty( $options['color'] ) ) {
		$hover_style .= 'color: rgba(34, 34, 34, 1);';
	}

	// 1.b Icon Box BG Color.
	// 2.b If icon bg color is overriden, set default bg color for hover state.
	if ( ! empty( $options['hover-box-background-color'] ) ) {
		$hover_style .= "background: {$options['hover-box-background-color']};";
	} elseif ( ! empty( $options['box-background-color'] ) ) {
		$hover_style .= 'background: rgba(238, 238, 238, 0);';
	}

	// 1.c Icon Box Border Color.
	// 2.c If icon border color is overriden, set default border color for hover state.
	if ( ! empty( $options['hover-box-border-color'] ) ) {
		$hover_style .= "border-color: {$options['hover-box-border-color']};";
	} elseif ( ! empty( $options['box-border-color'] ) ) {
		$hover_style .= 'border-color: rgba(283, 238, 238, 0);';
	}

	// Set icon link hover style.
	if ( ! empty( $hover_style ) ) {
		$style .= "#{$options['id']} .mkhb-icon-el__link:hover { $hover_style }";
	}

	return $style;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Rename function name and update docblock.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string          Icon internal CSS width & height.
 */
function mkhb_icon_size( $options ) {
	// Icon box layout.
	$box_height = ( ! empty( $options['size'] ) ) ? intval( $options['size'] ) : 0;
	$box_width = ( ! empty( $options['size'] ) ) ? intval( $options['size'] ) : 0;

	// If box border and padding are not empty.
	if ( ! empty( $options['box-border-width'] ) && ! empty( $options['padding'] ) ) {
		$border_widths = explode( ' ', $options['box-border-width'] );
		$padding_widths = explode( ' ', $options['padding'] );

		$border_offset['height'] = intval( $border_widths[0] ) + intval( $border_widths[2] );
		$border_offset['width']  = intval( $border_widths[1] ) + intval( $border_widths[3] );
		$padding_offset['height'] = intval( $padding_widths[0] ) + intval( $padding_widths[2] );
		$padding_offset['width']  = intval( $padding_widths[1] ) + intval( $padding_widths[3] );
		$box_height = intval( $options['size'] ) + $border_offset['height'] + $padding_offset['height'];
		$box_width = intval( $options['size'] ) + $border_offset['width'] + $padding_offset['width'];
	}

	$icon_size = "height: {$box_height}px; width: {$box_width}px;";

	return $icon_size;
}


/**
 * Return icon SVG.
 *
 * @since 6.0.0
 *
 * @param string $icon_name Icon name.
 * @param string $icon_size Icon size.
 * @return string           Icon SVG.
 */
function mkhb_get_icon_svg( $icon_name, $icon_size ) {
	// If the icon type is empty or not array, return null and don't render the element.
	if ( empty( $icon_name ) ) {
		return '';
	}

	$icon_class = ( ! empty( $icon_name ) ) ? $icon_name : '';

	/*
	 * Mk_SVG_Icons is a class from Jupiter package. HB - Icon will use it to load the SVG
	 * icon based on the class name. Make sure this class is exist.
	 */
	if ( ! class_exists( 'Mk_SVG_Icons' ) ) {
		require_once THEME_HELPERS . '/svg-icons.php';
	}

	$mk_svg = new Mk_SVG_Icons();
	$icon = $mk_svg::get_svg_icon_by_class_name( false, $icon_class, (int) $icon_size );

	return $icon;
}

/**
 * Generate internal style for HB Icon Border.
 *
 * @since 6.0.0
 * @since 6.0.3 Update border CSS property.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Icon internal CSS border.
 */
function mkhb_icon_border( $options ) {
	$style = '';

	// Border Width.
	if ( ! empty( $options['box-border-width'] ) ) {
		$style .= "border-width: {$options['box-border-width']};";
	}

	// Border Color.
	if ( ! empty( $options['box-border-color'] ) ) {
		$style .= "border-color: {$options['box-border-color']};";
	}

	return $style;
}
