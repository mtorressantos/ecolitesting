<?php
/**
 * Header Builder: mkhb_navigation shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Navigation element shortcode.
 *
 * @since 6.0.0
 *
 * @param  array $atts All parameter will be used in the shortcode.
 * @return string $markup Rendered HTML.
 */
function mkhb_nav_shortcode( $atts ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-navigation-1',
			'device' => 'desktop',
			'workspace' => 'normal',
			'visibility' => 'desktop, tablet, mobile',
			'alignment' => 'left',
			'nav-style' => 'text',
			'menu' => 'navigation-preview',
			'display' => '',
			'bar-width' => '15px',
			'bar-thickness' => '2px',
			'bar-spacing' => '4px',
			'bar-color' => '',
			'box-color' => '',
			'box-corner-radius' => '',
			'box-border-radius' => '',
			'box-border-width' => '0 0 0 0',
			'box-border-color' => '#ffffff #ffffff #ffffff #ffffff',
			'bar-hover-color' => '',
			'box-hover-color' => '',
			'border-color' => '',
			'border-width' => '',
			'text-hover-border-color' => '',
			'text-hover-border-width' => '',
			'animation' => '',
			'margin' => '0',
			'padding' => '10px 20px 10px 20px',
			'hover-style' => '1',
			'font-family' => 'Open Sans',
			'font-type' => 'google',
			'text-weight' => '',
			'text-size' => '',
			'gutter-space' => '',
			'text-background-color' => '',
			'text-color' => '',
			'text-hover-color' => '',
			'text-hover-background-color' => '',
			'text-corner-radius' => '6px',
			'sub-menu-text-weight' => '',
			'sub-menu-text-size' => '',
			'sub-menu-text-color' => '',
			'sub-menu-text-background-color' => '',
			'sub-menu-text-hover-color' => '#444444',
			'sub-menu-text-hover-background-color' => '#B3E5FC',
		),
		$atts
	);

	// Check if navigation is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Navigation internal style.
	$style = mkhb_nav_get_markup( $options )[1];

	// Set Navigation markup.
	$markup = mkhb_nav_get_markup( $options )[0];

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	// Enqueue current font.
	$data = array(
		'font-family' => $options['font-family'],
		'font-type' => $options['font-type'],
		'font-weight' => $options['text-weight'],
	);

	$hooks::set_hook( 'fonts', $data );

	return $markup;
}
add_shortcode( 'mkhb_navigation', 'mkhb_nav_shortcode' );

/**
 * Generate the element's markup for use on the front-end.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $markup Element HTML code.
 */
function mkhb_nav_get_markup( $options ) {
	// Set inline block.
	$inline = '';
	if ( ! empty( $options['display'] ) ) {
		if ( 'inline' === $options['display'] ) {
			$inline .= 'display: inline-block; vertical-align: top;';
		}
	}

	// Set text hover style class.
	$text_hover_style = '';
	if ( 'text' === $options['nav-style'] ) {
		$text_hover_style = 'mkhb-menu-hover-style-' . $options['hover-style'];
	}

	// @todo Temporary Solution - Data Attribute for inline container. Navigation additional class.
	$navigation_class = mkhb_shortcode_display_class( $options );
	$data_attr = mkhb_shortcode_display_attr( $options );

	// MARKUP.
	$markup = sprintf(
		'<div id="%s" class="mkhb-nav-container %s mkhb-menu-nav-style-%s %s" %s>
			%s
		</div>',
		esc_attr( $options['id'] ),
		esc_attr( $text_hover_style ),
		esc_attr( $options['nav-style'] ),
		esc_attr( $navigation_class ),
		$data_attr,
		mkhb_nav_body( $options )
	);

	// @todo Set "Responsive Navigation - Devices" as global CSS.
	$style = "
		/* COMMON STYLES */
		#{$options['id']}.mkhb-nav-container {
			margin: {$options['margin']};
			z-index: 301;
			text-align: {$options['alignment']};
			{$inline}
		}
		/* Main menu */
		#{$options['id']} .mkhb-navigation-ul > li.menu-item > a.menu-item-link {
			color: {$options['text-color']};
			padding: {$options['padding']};
			font-size: {$options['text-size']};
			font-weight: {$options['text-weight']};
			margin-right: {$options['gutter-space']};
		}
		#{$options['id']} .mkhb-navigation-ul > li.menu-item:last-of-type > a.menu-item-link {
			margin-right: 0;
		}
		/* Sub menu */
		#{$options['id']} .mkhb-navigation ul.sub-menu a.menu-item-link {
			color: {$options['sub-menu-text-color']};
			font-size: {$options['sub-menu-text-size']};
			font-weight: {$options['sub-menu-text-weight']};
		}
		#{$options['id']} .mkhb-navigation li.hb-no-mega-menu ul.sub-menu {
			background-color: {$options['sub-menu-text-background-color']};
		}
		#{$options['id']} .mkhb-navigation ul.sub-menu a.menu-item-link:hover,
		#{$options['id']} .mkhb-navigation-ul ul.sub-menu li.current-menu-item > a.menu-item-link,
		#{$options['id']} .mkhb-navigation-ul ul.sub-menu li.current-menu-parent > a.menu-item-link {
			background-color: {$options['sub-menu-text-hover-background-color']};
			color: {$options['sub-menu-text-hover-color']};
		}
		/* Responsive Navigation - Common */
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li > a {
			font-family: {$options['font-family']};
			font-weight: {$options['text-weight']};
			font-size: {$options['text-size']};
			color: {$options['text-color']};
			background-color: {$options['text-background-color']};
		}
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li:hover > a {
			color: {$options['text-hover-color']};
			background-color: {$options['text-hover-background-color']};
		}
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li > ul {
			background-color: {$options['sub-menu-text-background-color']};
		}
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li > ul li a {
			font-family: {$options['font-family']};
			font-weight: {$options['sub-menu-text-weight']};
			font-size: {$options['sub-menu-text-size']};
			color: {$options['sub-menu-text-color']};
		}
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li > ul li:hover > a {
			color: {$options['sub-menu-text-hover-color']};
			background-color: {$options['sub-menu-text-hover-background-color']};
		}
	";

	// 4. custom_style merge with default style.
	$style .= mkhb_nav_custom_style( $options, $inline );

	return array( $markup, $style );
}

/**
 * Get navigation custom style.
 *
 * @since 6.0.0
 *
 * @param  array  $options All options in Navigation shortcode.
 * @param  string $inline  Inline CSS style.
 * @return string          Navigation custom CSS style.
 */
function mkhb_nav_custom_style( $options, $inline ) {
	// 1. custom_style storage variable declaration.
	$custom_style = '';

	/*
	 * CUSTOM STYLE.
	 *
	 * Check nav_style type to load specific styles. In here:
	 * - Load specific style based on Text or Burger.
	 * - If Text, load specific style based on the Hover type. Pass some values to be used there.
	 */
	if ( 'text' === $options['nav-style'] ) {
		// 2.a.1 custom_style store style based on the Text nav type.
		$custom_style .= mkhb_nav_style_text( $options );

		// 2.a.2 custom_style store style based on the Hover type.
		$properties = array(
			'menu_margin' => $options['margin'],
			'menu_text_bg_color' => $options['text-background-color'],
			'menu_text_hover_font_color' => $options['text-hover-color'],
			'menu_text_hover_bg_color' => $options['text-hover-background-color'],
		);
		$custom_style .= mkhb_nav_text_hover( $options, $properties );
	}

	if ( 'burger' === $options['nav-style'] ) {
		// 2.b.1 custom_style store style based on the Burger nav type.
		$properties = array(
			'menu_padding' => $options['padding'],
			'menu_margin' => $options['margin'],
			'inline' => $inline,
		);
		$custom_style .= mkhb_nav_burger_style( $options, $properties );

		if ( 'desktop' !== $options['device'] ) {
			// 2.b.2 custom_style store style based on Responsive state only. Not all, some of it.
			$custom_style .= mkhb_responsive_style( $options );
		}
	}

	// 3. custom_style store style based on the Hover type.
	$custom_style .= mkhb_nav_to_style();

	return $custom_style;
}

/**
 * Get custom style for Text navigation style.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string Custom style from Text navigation.
 */
function mkhb_nav_style_text( $options = array() ) {
	$style = '';

	// Style - Menu.
	$text_font_family = 'initial';
	if ( ! empty( $options['font-family'] ) ) {
		$text_font_family = $options['font-family'];
	}

	$style = "
		#{$options['id']}.mkhb-menu-nav-style-text a {
			font-family: {$text_font_family};
		}
	";

	return $style;
}

/**
 * Get custom style for Burger navigation style.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $properties Pass arguments for navigation.
 * @return string            Custom style from Burger navigation.
 */
function mkhb_nav_burger_style( $options = array(), $properties = array() ) {
	// Burger Menu margin and padding.
	$padding = '';
	if ( ! empty( $options['padding'] ) ) {
		$padding = "padding: {$options['padding']};";
	}

	// Burger Menu color.
	$bar_color = $options['bar-color'];
	$box_color = $options['box-color'];
	$bar_color_hover = $options['bar-hover-color'];
	$box_color_hover = $options['box-hover-color'];

	// Burger Menu inline block.
	$inline = '';
	if ( ! empty( $properties['inline'] ) ) {
		$inline = $properties['inline'];
	}

	// Box width and height.
	$border = mkhb_nav_border( $options, 'box-border-color', 'box-border-width' );

	// Burger Menu spacing and height.
	$bar_width = $options['bar-width'];
	$bar_thickness = $options['bar-thickness'];
	$bar_spacing = intval( $options['bar-spacing'] ) + intval( $options['bar-thickness'] );

	// Burger Menu animation.
	$animation = ( ! empty( $options['animation'] ) ) ? $options['animation'] : '';

	// Burger Size.
	$burger_size = mkhb_burger_get_size( $options );

	// Default style.
	$style = "
		#{$options['id']} .mkhb-navigation-resp {
			text-align: {$options['alignment']};
			{$inline}
		}
		#{$options['id']} .mkhb-navigation-resp__box {
			background: {$box_color};
			border-radius: {$options['box-corner-radius']};
			{$padding}
			{$border}
			{$burger_size}
		}
		#{$options['id']} .mkhb-navigation-resp__bar,
		#{$options['id']} .mkhb-navigation-resp__bar:after,
		#{$options['id']} .mkhb-navigation-resp__bar:before {
			width: {$bar_width};
			height: {$bar_thickness};
			background: {$bar_color};
		}
		#{$options['id']} .mkhb-navigation-resp__box:hover {
			background: {$box_color_hover};
		}
		#{$options['id']} .mkhb-navigation-resp__box:hover .mkhb-navigation-resp__bar,
		#{$options['id']} .mkhb-navigation-resp__box:hover .mkhb-navigation-resp__bar:after,
		#{$options['id']} .mkhb-navigation-resp__box:hover .mkhb-navigation-resp__bar:before {
			background: {$bar_color_hover};
		}
		#{$options['id']} .fullscreen-active .mkhb-navigation-resp__bar,
		#{$options['id']} .fullscreen-active .mkhb-navigation-resp__box:hover .mkhb-navigation-resp__bar {
			background: rgba( 255, 255, 255, 0 );
		}
	";

	// Animation style.
	$style_args = array(
		'bar_width' => $bar_width,
		'bar_thickness' => $bar_thickness,
		'bar_color' => $bar_color,
		'bar_color_hover' => $bar_color_hover,
		'bar_spacing' => $bar_spacing,
		'animation' => $animation,
	);
	$style .= mkhb_nav_burger_style_animation( $options, $style_args );

	return $style;

}

/**
 * Generate the element's style for use on the front-end.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string          Icon internal CSS width & height.
 */
function mkhb_burger_get_size( $options ) {
	$box_height = 'auto';
	$box_width = '34px';
	$burger_size = 'height: 34px;width: auto;';

	$border_widths = explode( ' ', $options['box-border-width'] );
	$padding_widths = explode( ' ', $options['padding'] );

	$border_offset['height'] = intval( $border_widths[0] ) + intval( $border_widths[2] );
	$border_offset['width']  = intval( $border_widths[1] ) + intval( $border_widths[3] );
	$padding_offset['height'] = intval( $padding_widths[0] ) + intval( $padding_widths[2] );
	$padding_offset['width']  = intval( $padding_widths[1] ) + intval( $padding_widths[3] );

	$bar_spacing = 0;
	if ( ( ! empty( $options['bar-spacing'] ) ) && ( ! empty( $options['bar-thickness'] ) ) ) {
		$bar_spacing = intval( $options['bar-spacing'] ) + intval( $options['bar-thickness'] );
	}

	$box_height  = intval( $options['bar-thickness'] ) + $bar_spacing * 2 + $border_offset['height'] + $padding_offset['height'];
	$box_width = intval( $options['bar-width'] ) + $border_offset['width'] + $padding_offset['width'];
	$burger_size = "height: {$box_height}px;width: {$box_width}px;";

	return $burger_size;
}

/**
 * Get custom style from Theme Options.
 *
 * @since 6.0.0
 *
 * @return string Custom style from Theme Options.
 */
function mkhb_nav_to_style() {
	// Some styles inherited from TO.
	global $mk_options;

	$resp_wrap_bg = '#ffffff';
	if ( ! empty( $mk_options['responsive_nav_color'] ) ) {
		$resp_wrap_bg = $mk_options['responsive_nav_color'];
	}

	$resp_burger_icon_bg = '#444444';
	if ( ! empty( $mk_options['header_burger_color'] ) ) {
		$resp_burger_icon_bg = $mk_options['header_burger_color'];
	}

	$resp_text_color = 'inherit';
	if ( ! empty( $mk_options['responsive_nav_txt_color'] ) ) {
		$resp_text_color = $mk_options['responsive_nav_txt_color'];
	}

	/**
	 * Navigation style.
	 *
	 * @todo Some of the styles are similar with others element instance, need to make it as one file.
	 *       For example: .mkhb-navigation-resp__wrap
	 */
	$style = "
		/* Theme Options Styles */
		.mkhb-navigation-resp__wrap {
			background-color: {$resp_wrap_bg};
		}
		.mkhb-navigation-resp__menu > div {
			background-color: {$resp_burger_icon_bg};
		}
		.mkhb-navigation-resp__ul li ul li .megamenu-title:hover,
		.mkhb-navigation-resp__ul li ul li .megamenu-title,
		.mkhb-navigation-resp__ul li a, .mkhb-navigation-resp__ul li ul li a:hover,
		.mkhb-navigation-resp__ul .mkhb-navigation-resp__arrow {
			color:{$resp_text_color};
		}
	";

	return $style;

}

/**
 * Get custom style for specifici Hover style.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $properties  Pass arguments for navigation.
 * @return string               Custom style from Text - Hover style navigation.
 */
function mkhb_nav_text_hover( $options, $properties ) {
	$style = '';
	$hover_style = $options['hover-style'];

	// Style - Menu.
	$menu_text_bg = 'initial';
	if ( ! empty( $properties['menu_text_bg_color'] ) ) {
		$menu_text_bg = $properties['menu_text_bg_color'];
	}

	// Style - Menu Hover.
	$menu_text_hover_clr = '#444444';
	if ( ! empty( $properties['menu_text_hover_font_color'] ) ) {
		$menu_text_hover_clr = $properties['menu_text_hover_font_color'];
	}

	$menu_text_hover_bg = '#B3E5FC';
	if ( ! empty( $properties['menu_text_hover_bg_color'] ) ) {
		$menu_text_hover_bg = $properties['menu_text_hover_bg_color'];
	}

	// Check current hover style.
	if ( '1' === $hover_style ) {
		$style = "
			/* Hover Style 1 */
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item > a.menu-item-link {
				margin: auto;
			}
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item {
				margin-right: {$options['gutter-space']};
			}
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item:last-of-type  {
				margin-right: 0;
			}
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul>li.menu-item:before {
				background-color: {$menu_text_bg};
			}
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item > a.menu-item-link:hover,
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item:hover > a.menu-item-link,
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.current-menu-item > a.menu-item-link,
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.current-menu-ancestor > a.menu-item-link {
				color: {$menu_text_hover_clr};
			}
			#{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.dropdownOpen:before, #{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.active:before, #{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.open:before, #{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.menu-item:hover:before, #{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.current-menu-item:before, #{$options['id']}.mkhb-menu-hover-style-1 .mkhb-navigation-ul > li.current-menu-ancestor:before {
				background-color: {$menu_text_hover_bg};
			}
		";
	} elseif ( '2' === $hover_style ) {
		$style = "
			/* Hover Style 2 */
			#{$options['id']}.mkhb-menu-hover-style-2 .mkhb-navigation-ul>li.menu-item>a {
				background-color: {$menu_text_bg};
			}
			#{$options['id']}.mkhb-menu-hover-style-2 .mkhb-navigation-ul > li.menu-item > a.menu-item-link:hover, #{$options['id']}.mkhb-menu-hover-style-2 .mkhb-navigation-ul > li.menu-item:hover > a.menu-item-link, #{$options['id']}.mkhb-menu-hover-style-2 .mkhb-navigation-ul > li.current-menu-item > a.menu-item-link, #{$options['id']}.mkhb-menu-hover-style-2 .mkhb-navigation-ul > li.current-menu-ancestor > a.menu-item-link {
				color: {$menu_text_hover_clr};
				background-color: {$menu_text_hover_bg};
			}
		";
	} elseif ( '3' === $hover_style ) {
		// Style - Menu Hover.
		$corner_radius = $options['text-corner-radius'];
		$text_border = mkhb_nav_border( $options, 'border-color', 'border-width' );
		$text_hover_border = mkhb_nav_border( $options, 'text-hover-border-color', 'text-hover-border-width' );
		$style = "
			/* Hover Style 3 */
			#{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.menu-item > a {
				background-color: {$menu_text_bg};
				border-radius: {$corner_radius};
				{$text_border}
			}
			#{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.menu-item > a.menu-item-link:hover, #{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.menu-item:hover > a.menu-item-link, #{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.menu-item:hover > a.menu-item-link, #{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.current-menu-item > a.menu-item-link, #{$options['id']}.mkhb-menu-hover-style-3 .mkhb-navigation-ul > li.current-menu-ancestor > a.menu-item-link {
				color: {$menu_text_hover_clr};
				background-color: {$menu_text_hover_bg};
				{$text_hover_border}
			}
		";
	} elseif ( '4' === $hover_style ) {
		$style = "
			/* Hover Style 4 */
			#{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.menu-item>a.menu-item-link:after {
				background-color: {$menu_text_bg};
			}
			#{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.menu-item:hover>a.menu-item-link,
			#{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.current-menu-ancestor>a.menu-item-link,
			#{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.current-menu-item>a.menu-item-link {
				color: {$menu_text_hover_clr};
			}
			#{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.menu-item:hover>a.menu-item-link::after, #{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.current-menu-ancestor>a.menu-item-link:after, #{$options['id']}.mkhb-menu-hover-style-4 .mkhb-navigation-ul>li.current-menu-item>a.menu-item-link:after {
				background-color: {$menu_text_hover_bg};
			}
		";
	} // End if().

	return $style;

}

/**
 * Get custom style for specific Animation style.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $style_args Style parameters for animation.
 * @return string            CSS styles for specific animation.
 */
function mkhb_nav_burger_style_animation( $options = array(), $style_args = array() ) {
	// Basic style for all animations.
	$style = "
		#{$options['id']} .mkhb-navigation-resp__bar {
			transform: translateY({$style_args['bar_spacing']}px);
		}
		#{$options['id']} .mkhb-navigation-resp__bar:before {
			bottom: {$style_args['bar_spacing']}px;
		}
		#{$options['id']} .mkhb-navigation-resp__bar:after {
			top: {$style_args['bar_spacing']}px;
		}
		#{$options['id']} .fullscreen-active .mkhb-navigation-resp__bar:after {
			top: 0;
		}
		#{$options['id']} .fullscreen-active .mkhb-navigation-resp__bar:before {
			bottom: 0;
		}
	";

	// Additional styles for Morphing animation.
	if ( 'morphing' === $style_args['animation'] ) {
		$style .= "
			#{$options['id']} .mkhb-navigation-resp__box--morphing .mkhb-navigation-resp__sub-bar,
			#{$options['id']} .mkhb-navigation-resp__box--morphing .mkhb-navigation-resp__sub-bar:after,
			#{$options['id']} .mkhb-navigation-resp__box--morphing .mkhb-navigation-resp__sub-bar:before {
				width: {$style_args['bar_width']};
				height: {$style_args['bar_thickness']};
			}
			#{$options['id']} .mkhb-navigation-resp__box.mkhb-navigation-resp__box--morphing:hover .mkhb-navigation-resp__sub-bar:after,
			#{$options['id']} .mkhb-navigation-resp__box.mkhb-navigation-resp__box--morphing:hover .mkhb-navigation-resp__sub-bar:before {
				background: {$style_args['bar_color_hover']};
			}
			#{$options['id']} .mkhb-navigation-resp__sub-bar {
				transform: translateY({$options['bar-spacing']});
			}
			#{$options['id']} .mkhb-navigation-resp__sub-bar:before,
			#{$options['id']} .mkhb-navigation-resp__sub-bar:after {
				background: {$style_args['bar_color']};
			}
			#{$options['id']} .fullscreen-active .mkhb-navigation-resp__sub-bar:after {
				top: 0;
			}
			#{$options['id']} .fullscreen-active .mkhb-navigation-resp__sub-bar:before {
				bottom: 0;
			}
		";
	}

	return $style;

}

/**
 * Get custom style for Responsive dropdown menu.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string Custom style from Responsive state.
 */
function mkhb_responsive_style( $options ) {
	// Space top and bottom is used on the dropdown menu margin top and bottom.
	$menu_space_top = 15;
	$menu_space_bottom = 15;

	$text_size = 13;
	if ( ! empty( $options['text-size'] ) ) {
		$text_size = intval( $options['text-size'] );
	}

	if ( ! empty( $options['gutter-space'] ) ) {
		$menu_space_top = 15 + intval( $options['gutter-space'] ) / 2;
		$menu_space_bottom = 15 + intval( $options['gutter-space'] ) / 2;
	}

	// Set line height. The line-height is used to set the height of the item and down arrow.
	$line_height = 8 + $menu_space_bottom + $menu_space_top + $text_size;

	$style = "
		/* Responsive Navigation - Specific */
		#{$options['id']}-wrap .mkhb-navigation-resp__arrow {
			line-height: {$line_height}px;
		}
		#{$options['id']}-wrap .mkhb-navigation-resp__ul > li > a {
			padding-top: {$menu_space_top}px;
			padding-bottom: {$menu_space_bottom}px;
		}

		/* Responsive Navigation - Devices */
		@media (max-width: 767px) {
			#{$options['id']}.mkhb-el-tablet {
				display: none !important;
			}
		}
		@media (min-width: 768px) and (max-width: 1024px) {
			#{$options['id']}.mkhb-el-mobile {
				display: none !important;
			}
		}
		@media (min-width: 1025px) {
			#{$options['id']}.mkhb-el-tablet,
			#{$options['id']}.mkhb-el-mobile {
				display: none !important;
			}
		}
	";

	return $style;

}

/**
 * Get navigation body is desktop or mobile/tablet version.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string HTML markup of navigation body.
 */
function mkhb_nav_body( $options ) {
	$body = mkhb_nav_active_menu( $options );

	$nav_menu = '';
	// Use Jupiter navigation fullscreen if Burger is selected on Desktop.
	if ( 'desktop' === $options['device'] && 'burger' === $options['nav-style'] ) {
		$nav_menu = mkhb_nav_fullscreen( $options );
	}

	if ( 'burger' === $options['nav-style'] ) {
		$animation = ( ! empty( $options['animation'] ) ) ? $options['animation'] : 'none';

		// BURGER MENU MARKUP.
		$body = sprintf(
			'
			<div class="mkhb-navigation-resp" data-device="%s">
				<div class="mkhb-navigation-resp__container %s fullscreen-style">
					<div class="mkhb-navigation-resp__box %s">
						<div class="mkhb-navigation-resp__bar"></div>
						<div class="mkhb-navigation-resp__sub-bar"></div>
					</div>
				</div>
				%s
			</div>',
			esc_attr( $options['device'] ),
			esc_attr( 'mkhb-navigation-resp__container--' . $options['nav-style'] . '-' . $options['device'] ),
			esc_attr( 'mkhb-navigation-resp__box--' . $animation ),
			$nav_menu
		);
	} // End if().

	/**
	 * Set responsive navigation markup after HB container in hb_grid_extra hook.
	 *
	 * @since 6.0.0
	 *
	 * @see views/header/styles/header_custom.php
	 */
	if ( 'desktop' !== $options['device'] && 'burger' === $options['nav-style'] ) {
		// Set options needed.
		$data = array(
			'id' => $options['id'],
			'device' => $options['device'],
			'workspace' => $options['workspace'],
			'menu' => $options['menu'],
			'nav-style' => $options['nav-style'],
		);

		// Hooks burger menu on hb_grid_extra.
		$hooks = mkhb_hooks();
		$hooks::set_hook( 'resp-navigation', $data );
	}

	return $body;
}

/**
 * Set responsive navigation markup for mobile and tablet. Both of Text and Burger style will
 * use same responsive navigation.
 *
 * @since 6.0.0
 */
function mkhb_resp_navigation() {
	// Fetch hooks data.
	$instance = mkhb_hooks();
	$resp_options = $instance::get_hook( 'resp-navigation', array() );

	// Prevent any error because Burger menu doesn't work for now.
	if ( empty( $resp_options ) ) {
		return;
	}
	$resp_options = array_unique( $resp_options, SORT_REGULAR );

	foreach ( $resp_options as $options ) {
		// RESPOSNIVE MARKUP.
		$resp_nav = sprintf(
			'<div id="%s-wrap" class="mkhb-navigation-resp__wrap mkhb-el-%s mkhb-navigation-resp__%s mkhb-navigation-resp__%s">
				%s
			</div>',
			esc_attr( $options['id'] ),
			esc_attr( $options['device'] ),
			esc_attr( $options['workspace'] ),
			esc_attr( $options['device'] ),
			mkhb_nav_active_menu( $options )
		);

		echo wp_kses(
			$resp_nav, array(
				'div' => array(
					'id' => array(),
					'class' => array(),
				),
				'form' => array(
					'class' => array(),
					'method' => array(),
					'action' => array(),
				),
				'input' => array(
					'type' => array(),
					'class' => array(),
					'value' => array(),
					'name' => array(),
					'id' => array(),
					'placeholder' => array(),
				),
				'i' => array(),
				'svg' => array(
					'xmlns' => array(),
					'style' => array(),
					'viewbox' => array(),
				),
				'path' => array(
					'd' => array(),
					'transform' => array(),
				),
				'span' => array(
					'class' => array(),
				),
				'nav' => array(
					'class' => array(),
					'id' => array(),
				),
				'ul' => array(
					'id' => array(),
					'class' => array(),
				),
				'li' => array(
					'id' => array(),
					'class' => array(),
				),
				'a' => array(
					'href' => array(),
					'class' => array(),
				),
			)
		);
	} // End foreach().
}

/**
 * Get current active menu and add responsive stuff class.
 *
 * @since 6.0.0
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string HTML menu markup.
 */
function mkhb_nav_active_menu( $options ) {
	// Text - Dekstop/Tablet/Mobile.
	$container_id = '';
	$container_class = 'mkhb-navigation mkhb-js-nav';
	$menu_class = 'mkhb-navigation-ul';
	$fallback_cb = 'mk_link_to_menu_editor';
	$sub_level_arrrow = '';
	$walker = new hb_main_menu();

	// Burger - Mobile/Tablet.
	if ( 'desktop' !== $options['device'] && 'burger' === $options['nav-style'] ) {
		$container_class = 'menu-main-navigation-container';
		$menu_class = 'mkhb-navigation-resp__ul';
		$walker = new MKHB_Walker_Nav_Responsive();

		// For demo or Preview menu only.
		$sub_level_arrrow = '<span class="mkhb-navigation-resp__arrow mkhb-navigation-resp__sub-closed"><svg style="height:16px;width: 16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 192l-96-96-160 160-160-160-96 96 256 255.999z"></path></svg></span>';
	}

	// Burger - Desktop.
	if ( 'desktop' === $options['device'] && 'burger' === $options['nav-style'] ) {
		$container_id = 'fullscreen-navigation';
		$container_class = 'fullscreen-menu';
		$menu_class = 'fullscreen-navigation-ul';
		$fallback_cb = '';
		$walker = new MKHB_Walker_Nav_Burger();

		// For demo ora Preview menu only.
		$sub_level_arrrow = '<span class="menu-sub-level-arrow"><svg class="mk-svg-icon" style="height:16px;width: 16px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M512 192l-96-96-160 160-160-160-96 96 256 255.999z"></path></svg></span>';
	}

	// If the menu is navigation-preview for demo.
	if ( 'navigation-preview' === $options['menu'] ) {
		$menu = sprintf(
			'
			<nav id="%s" class="%s">
				<ul class="%s dropdownJavascript">
					<li class="menu-item current-menu-item current_page_item hb-no-mega-menu">
						<a href="#" class="menu-item-link mkhb-js-smooth-scroll">Current Menu</a>
					</li>
					<li class="menu-item mkhb-has-mega-menu">
						<a href="#" class="menu-item-link mkhb-js-smooth-scroll">Menu</a>
					</li>
					<li class="menu-item menu-item-has-children hb-no-mega-menu">
						<a href="#" class="menu-item-link mkhb-js-smooth-scroll">Menu</a>
						%s
						<ul class="sub-menu">
							<li class="menu-item"><a href="#" class="menu-item-link mkhb-js-smooth-scroll">Menu</a></li>
							<li class="menu-item"><a href="#" class="menu-item-link mkhb-js-smooth-scroll">Menu</a></li>
						</ul>
					</li>
				</ul>
			</nav>',
			( ! empty( $container_id ) ) ? esc_attr( $container_id ) : 'mkhb-nav-preview',
			esc_attr( $container_class ),
			esc_attr( $menu_class ),
			$sub_level_arrrow
		);

		return $menu;
	}

	if ( isset( $_GET['header-builder-preview'] ) && (bool) $_GET['header-builder-preview'] ) { // WPCS: XSS ok, CSRF ok.
		add_filter( 'wp_nav_menu', 'mkhb_active_current_menu_item' );
	}

	// Get menu and the items list.
	$menu = wp_nav_menu(
		array(
			'menu' => $options['menu'],
			'container' => 'nav',
			'container_id' => $container_id,
			'container_class' => $container_class,
			'menu_class' => $menu_class,
			'echo' => false,
			'fallback_cb' => $fallback_cb,
			'walker' => $walker,
		)
	);

	remove_filter( 'wp_nav_menu', 'mkhb_active_current_menu_item' );

	return $menu;
}

/**
 * Get navigation content.
 * We should keep some of Jupiter class to inheritance Jupiter theme options.
 *
 * @since 6.0.0
 * @see views/header/master/fullscreen-nav.php Source of the HTML content.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string Return fullscreen navigation HTML.
 */
function mkhb_nav_fullscreen( $options ) {
	// Get Logo Settings.
	$logo_settings = mkhb_nav_logo_settings();

	$mobile_logo = $logo_settings['mobile_logo'];
	$logo = $logo_settings['logo'];

	$is_repsonive_logo = ! empty( $mobile_logo ) ? 'logo-is-responsive' : '';

	// Collect the navigation content.
	ob_start();
	?>

	<div class="mkhb-navigation-resp__nav mk-fullscreen-nav <?php echo esc_attr( $is_repsonive_logo ); ?>">
		<div class="mk-fullscreen-inner _ flex flex-center flex-items-center">
			<div class="mk-fullscreen-nav-wrapper">

				<?php if ( ! empty( $logo ) ) : ?>
				<img class="mk-fullscreen-nav-logo dark-logo" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $logo ); ?>" />
				<?php endif; ?>

				<?php if ( ! empty( $logo ) ) : ?>
				<img class="mk-fullscreen-nav-logo responsive-logo" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" src="<?php echo esc_url( $mobile_logo ); ?>" />
				<?php endif; ?>

				<?php
					echo wp_kses(
						mkhb_nav_active_menu( $options ), array(
							'i' => array(),
							'svg' => array(
								'class' => array(),
								'xmlns' => array(),
								'style' => array(),
								'viewbox' => array(),
							),
							'path' => array(
								'd' => array(),
								'transform' => array(),
							),
							'span' => array(
								'class' => array(),
							),
							'nav' => array(
								'class' => array(),
								'id' => array(),
							),
							'ul' => array(
								'id' => array(),
								'class' => array(),
							),
							'li' => array(
								'id' => array(),
								'class' => array(),
							),
							'a' => array(
								'href' => array(),
								'class' => array(),
							),
						)
					);
				?>
			</div>
		</div>
	</div>

	<?php
	$nav_content = ob_get_clean();

	return $nav_content;
}

/**
 * Get logo settings.

 * @since 6.0.0
 *
 * @return array Logo and mobile settings.
 */
function mkhb_nav_logo_settings() {
	// Get all theme options settings.
	global $mk_options;

	$params = mkhb_nav_logo_params();

	$settings['mobile_logo'] = ( 'dark' === $params['mob_logo_skin'] ) ? $mk_options['logo'] : $params['light_logo'];
	$settings['logo'] = ( 'dark' === $params['logo_skin'] ) ? $mk_options['logo'] : $params['light_logo'];

	// If Mobile Logo is a Custom Logo.
	if ( 'custom' === $params['mob_logo_skin'] ) {
		$settings['mobile_logo'] = ! empty( $mk_options['fullscreen_nav_mobile_logo_custom'] ) ? $mk_options['fullscreen_nav_mobile_logo_custom'] : $settings['mobile_logo'];
	}

	return $settings;
}

/**
 * Get logo parameter settings.
 *
 * @since 6.0.0
 *
 * @return array Logo and mobile parameter settings.
 */
function mkhb_nav_logo_params() {
	// Get all theme options settings.
	global $mk_options;

	$params_setting['mob_logo_skin'] = ! empty( $mk_options['fullscreen_nav_mobile_logo'] ) ? $mk_options['fullscreen_nav_mobile_logo'] : 'dark';
	$params_setting['logo_skin']  = ! empty( $mk_options['fullscreen_nav_logo'] ) ? $mk_options['fullscreen_nav_logo'] : 'dark';
	$params_setting['light_logo'] = isset( $mk_options['light_header_logo'] ) ? $mk_options['light_header_logo'] : '';

	return $params_setting;
}

/**
 * Generate internal style for HB Row Border.
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $color   Color of border.
 * @param  array $width   Widht of border.
 * @return string         Row internal CSS border.
 */
function mkhb_nav_border( $options, $color, $width ) {
	$style = '';

	// Border Width, Style, and Color.
	if ( ! empty( $options[ $width ] ) && ! empty( $options[ $color ] ) ) {
		$border_widths = explode( ' ', $options[ $width ] );
		$border_colors = explode( ' ', $options[ $color ] );

		$style .= "
			border-top: {$border_widths[0]} solid {$border_colors[0]};
			border-right: {$border_widths[1]} solid {$border_colors[1]};
			border-bottom: {$border_widths[2]} solid {$border_colors[2]};
			border-left: {$border_widths[3]} solid {$border_colors[3]};
		";
	}

	return $style;
}
