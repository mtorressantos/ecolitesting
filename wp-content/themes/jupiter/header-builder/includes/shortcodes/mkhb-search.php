<?php
/**
 * Header Builder: mkhb_search shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Search element shortcode.
 *
 * @since 6.0.0
 *
 * @param  array $atts All parameter will be used in the shortcode.
 * @return string $markup Rendered HTML.
 */
function mkhb_search_shortcode( $atts ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-search-1',
			'alignment' => '',
			'icon-color' => '',
			'display' => '',
			'icon-hover-color' => '',
			'margin' => '',
			'padding' => '',
			'icon-size' => '16px',
			'icon-type' => '1',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if search is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Search internal style.
	$style = mkhb_search_style( $options );

	// Set Search markup.
	$markup = mkhb_search_markup( $options );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	return $markup;
}
add_shortcode( 'mkhb_search', 'mkhb_search_shortcode' );

/**
 * Generate the element's markup for use on the front-end.
 *
 * @since 6.0.0
 * @since 6.0.3 Rename function name.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $markup Element HTML code.
 */
function mkhb_search_markup( $options ) {
	$markup  = '';

	// Search initial values for search form.
	$preview_input  = '';
	$search_keyword = '';
	$nonce_name     = 'hb-search-nonce';
	$nonce_instance = wp_create_nonce( $nonce_name );

	// Check if the nonce is exist.
	if ( wp_verify_nonce( $nonce_instance, $nonce_name ) ) {
		// Preview param.
		$header_builder_get = ( ! empty( $_GET['header-builder'] ) ) ? sanitize_text_field( $_GET['header-builder'] ) : '';
		if ( ! empty( $header_builder_get ) ) {
			$preview_input = '<input name="header-builder" type="hidden" value="preview" />';
		}

		// Search param.
		$search_keyword = ( ! empty( $_GET['s'] ) ) ? sanitize_text_field( $_GET['s'] ) : '';
	}

	// Search additional class.
	$search_class = mkhb_shortcode_display_class( $options );

	// Search attributes.
	// @todo Temporary Solution - Data Attribute for inline container.
	$data_attr = mkhb_shortcode_display_attr( $options );

	$markup = sprintf(
		'<div id="%s" class="mkhb-search-el %s" %s>
			<a class="mkhb-search-el__container mkhb-trigger__fullscreen--open" href="#">
				<i class="mkhb-search-el__icon-wrapper">
					%s
				</i>
			</a>
			<div class="mkhb-search-el__overlay">
				<a href="#" class="mkhb-search-el__overlay__close">
					<svg class="mkhb-search-el__icon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M390.628 345.372l-45.256 45.256-89.372-89.373-89.373 89.372-45.255-45.255 89.373-89.372-89.372-89.373 45.254-45.254 89.373 89.372 89.372-89.373 45.256 45.255-89.373 89.373 89.373 89.372z"></path></svg>
				</a>
				<div class="mkhb-search-el__overlay__wrapper">
					<p>%s</p>
					<form method="get" id="mkhb-search-el__overlay__search-form" action="%s">
						<input type="text" value="%s" name="s" id="mkhb-search-el__overlay__search-input" />
						<input name="%s" type="hidden" value="%s" />
						%s
						<i class="mkhb-search-el__overlay__search-icon">
							<svg class="mkhb-search-el__icon-svg" style="height:25px;width:23.214285714286px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1664 1792"><path d="M1152 832q0-185-131.5-316.5t-316.5-131.5-316.5 131.5-131.5 316.5 131.5 316.5 316.5 131.5 316.5-131.5 131.5-316.5zm512 832q0 52-38 90t-90 38q-54 0-90-38l-343-342q-179 124-399 124-143 0-273.5-55.5t-225-150-150-225-55.5-273.5 55.5-273.5 150-225 225-150 273.5-55.5 273.5 55.5 225 150 150 225 55.5 273.5q0 220-124 399l343 343q37 37 37 90z"></path></svg>
						</i>
					</form>
				</div>
			</div>
		</div>',
		esc_attr( $options['id'] ),
		esc_attr( $search_class ),
		$data_attr,
		mkhb_search_get_svg_icon( $options['icon-type'], $options['icon-size'] ),
		__( 'Start typing and press Enter to search', 'mk_framework' ),
		get_home_url(),
		esc_attr( $search_keyword ),
		esc_attr( $nonce_name ),
		esc_attr( $nonce_instance ),
		$preview_input
	);

	return $markup;
}

/**
 * Generate the element's style for use on the front-end.
 *
 * There are 2 cases here:
 * 1. If search button link hover styles are overriden, return the overriden hover style.
 * 2. If search button link styles are overriden, return the default hover style. It's
 *    used to fix hover issue on the link.
 *
 * @since 6.0.0
 * @since 6.0.3 Print search style only if it's needed. Rename function name.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string $style Element CSS code.
 */
function mkhb_search_style( $options ) {
	$search_style = '';
	$style = '';

	// Search Display.
	if ( ! empty( $options['display'] ) ) {
		if ( 'inline' === $options['display'] ) {
			$search_style .= 'display: inline-block; vertical-align: top;';
		}
	}

	// Search Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$search_style .= "text-align: {$options['alignment']};";
	}

	// Search Margin and Padding Style.
	$search_style = mkhb_search_layout( $options );

	// Set Search style.
	if ( ! empty( $search_style ) ) {
		$style = "#{$options['id']} { $search_style }";
	}

	// Search color.
	if ( ! empty( $options['icon-color'] ) ) {
		$style .= "#{$options['id']} .mkhb-search-el__container { color: {$options['icon-color']}; }";
	}

	// 1.a Search color hover.
	// 2.a If search color is overriden, set default color for hover state.
	if ( ! empty( $options['icon-hover-color'] ) ) {
		$style .= "#{$options['id']} .mkhb-search-el__container:hover { color: {$options['icon-hover-color']}; }";
	} elseif ( ! empty( $options['icon-color'] ) ) {
		$style .= "#{$options['id']} .mkhb-search-el__container:hover { color: rgba(119, 119, 119, 1); }";
	}

	return $style;
}

/**
 * Generate internal style for HB Search Layout.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Search internal CSS margin and padding.
 */
function mkhb_search_layout( $options ) {
	$style = '';

	// Search Padding.
	if ( ! empty( $options['padding'] ) ) {
		$style .= "padding: {$options['padding']};";
	}

	// Search Margin.
	if ( ! empty( $options['margin'] ) ) {
		$style .= "margin: {$options['margin']};";
	}

	return $style;
}

/**
 * Get SVG icon based on key and set the icon width and height size in pixel.
 *
 * @since 6.0.0
 *
 * @param string $icon_name Icon name.
 * @param string $icon_size Icon size.
 * @return string           Icon SVG.
 */
function mkhb_search_get_svg_icon( $icon_name, $icon_size ) {
	// If the icon type is empty or not array, return null and don't render the element.
	if ( empty( $icon_name ) ) {
		return '';
	}

	$icon_class = mkhb_search_icon_list( $icon_name );

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
 * Return icon class name.
 *
 * @since 6.0.0
 *
 * @param  string $key Icon key number.
 * @return string      Icon class name.
 */
function mkhb_search_icon_list( $key ) {
	$icons = array(
		'1' => 'mk-moon-search-5',
		'2' => 'mk-moon-search-3',
		'3' => 'mk-moon-search-2',
		'4' => 'mk-icon-search',
	);

	if ( ! empty( $key ) || ! empty( $icons[ $key ] ) ) {
		return $icons[ $key ];
	}

	return $icons['1'];
}
