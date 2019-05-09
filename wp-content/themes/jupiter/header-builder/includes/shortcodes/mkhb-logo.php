<?php
/**
 * Header Builder: mkhb_logo shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Logo element shortcode.
 *
 * @since 6.0.0
 * @since 6.0.3 Separate markup logic as new function mkhb_logo_markup().
 *
 * @param  array $atts All parameter will be used in the shortcode.
 * @return string      Rendered HTML.
 */
function mkhb_logo_shortcode( $atts ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-logo-1',
			'alignment' => '',
			'display' => '',
			'margin' => '',
			'padding' => '',
			'width' => '',
			'link-homepage' => 'true',
			'theme' => 'dark',
			'workspace' => 'normal',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if logo is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Logo internal style.
	$style = mkhb_logo_style( $options );

	// Set Logo markup.
	$markup = mkhb_logo_markup( $options );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	return $markup;
}
add_shortcode( 'mkhb_logo', 'mkhb_logo_shortcode' );

/**
 * Generate markup for HB Logo.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Logo markup.
 */
function mkhb_logo_markup( $options ) {
	// Logo ID.
	$logo_id = $options['id'];

	// Logo additional class.
	$logo_class = mkhb_shortcode_display_class( $options );

	// Logo attributes.
	// @todo Temporary Solution - Data Attribute for inline container.
	$data_attr = mkhb_shortcode_display_attr( $options );

	// Logo link homepage.
	$link = '';
	$link_status = filter_var( $options['link-homepage'], FILTER_VALIDATE_BOOLEAN );
	if ( $link_status ) {
		$link = 'href="' . get_home_url() . '"';
	}

	// Logo image source and blog/site description.
	$description = get_bloginfo( 'description' );
	$logo_src = mkhb_logo_image_src( $options['theme'], $options['workspace'] );

	$markup = sprintf( '
		<div id="%s" class="mkhb-logo-el %s" %s>
			<a %s class="mkhb-logo-el__link">
				<img class="mkhb-logo-el__image" title="%s" alt="%s" src="%s"/>
			</a>
		</div>',
		esc_attr( $logo_id ),
		esc_attr( $logo_class ),
		$data_attr,
		$link,
		esc_attr( $description ),
		esc_attr( $description ),
		esc_url( $logo_src )
	);

	return $markup;
}

/**
 * Generate internal style for HB Logo.
 *
 * @since 6.0.0
 * @since 6.0.3 Print Logo style only if it's needed.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Logo internal CSS.
 */
function mkhb_logo_style( $options ) {
	$logo_style = '';
	$style = '';

	// Logo Padding.
	if ( ! empty( $options['padding'] ) ) {
		$logo_style .= "padding: {$options['padding']};";
	}

	// Logo Margin.
	if ( ! empty( $options['margin'] ) ) {
		$logo_style .= "margin: {$options['margin']};";
	}

	// Logo Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$logo_style .= "text-align: {$options['alignment']};";
	}

	// Logo ID.
	$logo_id = $options['id'];

	// Set Logo style.
	if ( ! empty( $logo_style ) ) {
		$style .= "#$logo_id { $logo_style }";
	}

	// Logo width.
	if ( ! empty( $options['width'] ) ) {
		$style .= "#$logo_id .mkhb-logo-el__image { width: {$options['width']}; }";
	}

	return $style;
}

/**
 * Get logo image based on Header Workplace and Device.
 *
 * @since 6.0.0
 * @since 6.0.1 Check if logo option value is string or not. If it's not string, use
 *              Jupiter default logo. Used to handle additional logo width setting
 *              in Theme Options.
 *
 * @param  string $logo_theme Logo key used.
 * @param  string $workspace  Workspace used.
 * @return string Logo image source URL.
 */
function mkhb_logo_image_src( $logo_theme, $workspace ) {
	global $mk_options;

	$logo_default = THEME_IMAGES . '/jupiter-logo.png';
	$logo_src = $logo_default;

	// Normal logo list.
	$logo_types = array(
		'dark' => 'logo',
		'sticky' => 'sticky_header_logo',
		'light' => 'light_header_logo',
		'mobile' => 'responsive_logo',
	);

	if ( 'sticky' === $workspace ) {
		$logo_types['dark'] = 'sticky_header_logo';
	}

	// Check current workspace and set correct logo.
	if ( ! empty( $mk_options[ $logo_types[ $logo_theme ] ] ) ) {
		$logo_src = $mk_options[ $logo_types[ $logo_theme ] ];
	}

	// After Theme Options update in 6.0.1, in some case logo will return as an array
	// contain 'width' key and not URL string anymore. If $logo_src is not string,
	// lets use Jupiter default logo and user should update their logo source.
	if ( ! is_string( $logo_src ) ) {
		$logo_src = $logo_default;
	}

	// Check if current page override HB logo source.
	if ( ! mkhb_is_override_by_styling() ) {
		return $logo_src;
	}

	$logo_types['light'] = 'light_logo';

	// Check overriden logo source.
	global $post;
	$logo_src_over = get_post_meta( $post->ID, $logo_types[ $logo_theme ], true );
	if ( ! empty( $logo_src_over ) && is_string( $logo_src_over ) ) {
		return $logo_src_over;
	}

	return $logo_src;
}
