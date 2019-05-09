<?php
/**
 * Header Builder: mkhb_button shortcode.
 *
 * @since 6.0.0
 * @package Header_Builder
 */

/**
 * HB Button element shortcode.
 *
 * @since 6.0.0
 * @since 6.0.3 Print button style only if it's needed. Separate markup logic as
 *              new function mkhb_button_markup().
 *
 * @param  array  $atts All parameter will be used in the shortcode.
 * @param  string $content The enclosed content.
 * @return string      Rendered HTML.
 */
function mkhb_button_shortcode( $atts, $content ) {
	$options = shortcode_atts(
		array(
			'id' => 'mkhb-button-1',
			'url' => '',
			'alignment' => '',
			'display' => '',
			'width' => '',
			'font-weight' => '',
			'font-size' => '',
			'font-style' => '',
			'color' => '',
			'background-color' => '',
			'hover-color' => '',
			'hover-background-color' => '',
			'border-radius' => '',
			'border-width' => '',
			'border-color' => '',
			'margin' => '',
			'padding' => '',
			'target' => '_blank',
			'font-type' => 'google',
			'font-family' => 'Roboto',
			'device' => 'desktop',
			'visibility' => 'desktop, tablet, mobile',
		),
		$atts
	);

	// Check if button is should be displayed in current device or not.
	if ( ! mkhb_is_shortcode_displayed( $options['device'], $options['visibility'] ) ) {
		return '';
	}

	// Set Button internal style.
	$style = mkhb_button_style( $options );

	// Set Button markup.
	$markup = mkhb_button_markup( $options, $content );

	// MKHB Hooks as temporary storage.
	$hooks = mkhb_hooks();

	// Enqueue internal style.
	$hooks::concat_hook( 'styles', $style );

	// Enqueue current font.
	$data = array(
		'font-family' => $options['font-family'],
		'font-type' => $options['font-type'],
		'font-weight' => $options['font-weight'],
	);
	$hooks::set_hook( 'fonts', $data );

	return $markup;
}
add_shortcode( 'mkhb_button', 'mkhb_button_shortcode' );

/**
 * Generate markup for HB Button.
 *
 * @since 6.0.3
 *
 * @param  array $options All options will be used in the shortcode.
 * @param  array $content Button content/text.
 * @return string         Button markup.
 */
function mkhb_button_markup( $options, $content ) {
	// Button ID.
	$button_id = $options['id'];

	// Button additional class.
	$button_class = mkhb_shortcode_display_class( $options );

	// Button attributes.
	// @todo Temporary Solution - Data Attribute for inline container.
	$data_attr = mkhb_shortcode_display_attr( $options );

	// Button URL.
	$link = '';
	if ( ! empty( $options['url'] ) ) {
		$link = 'href="' . esc_url( $options['url'] ) . '"';
	}

	$markup = sprintf( '
		<div id="%s" class="mkhb-button-el %s" %s>
			<a %s class="mkhb-button-el__link" target="%s" role="button">%s</a>
		</div>',
		esc_attr( $button_id ),
		esc_attr( $button_class ),
		$data_attr,
		$link,
		esc_attr( $options['target'] ),
		esc_html( $content )
	);

	return $markup;
}

/**
 * Generate internal style for HB Button.
 *
 * @since 6.0.0
 * @since 6.0.3 Print button style only if it's needed.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Button internal CSS.
 */
function mkhb_button_style( $options ) {
	$link_style = '';
	$style = '';

	// Button ID.
	$button_id = $options['id'];

	// Button Width.
	if ( ! empty( $options['width'] ) ) {
		$link_style .= "width: {$options['width']};";
	}

	// Button Color.
	if ( ! empty( $options['color'] ) ) {
		$link_style .= "color: {$options['color']};";
	}

	// Button Background Color.
	if ( ! empty( $options['background-color'] ) ) {
		$link_style .= "background-color: {$options['background-color']};";
	}

	// Button Border Radius.
	if ( ! empty( $options['border-radius'] ) ) {
		$link_style .= "border-radius: {$options['border-radius']};";
	}

	// Button Margin and Padding Style.
	$link_style .= mkhb_button_layout( $options );

	// Button Link Style.
	$link_style .= mkhb_button_font_style( $options );

	// Button Border Style.
	$link_style .= mkhb_button_border( $options );

	// If link style not empty.
	if ( ! empty( $link_style ) ) {
		$style .= "#$button_id .mkhb-button-el__link { $link_style }";
	}

	// Button Hover Style.
	$link_hover_style = mkhb_button_hover( $options );
	if ( ! empty( $link_hover_style ) ) {
		$style .= "#$button_id .mkhb-button-el__link:hover { $link_hover_style }";
	}

	// Button Alignment.
	if ( ! empty( $options['alignment'] ) ) {
		$style .= "#$button_id { text-align: {$options['alignment']}; }";
	}

	return $style;
}

/**
 * Generate internal style for HB Button Layout.
 *
 * @since 6.0.0
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Button internal CSS margin and padding.
 */
function mkhb_button_layout( $options ) {
	$style = '';

	// Button Padding.
	if ( ! empty( $options['padding'] ) ) {
		$style .= "padding: {$options['padding']};";
	}

	// Button Margin.
	if ( ! empty( $options['margin'] ) ) {
		$style .= "margin: {$options['margin']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Button Border.
 *
 * @since 6.0.0
 * @since 6.0.3 Update border CSS property.
 *
 * @param  array $options  All options will be used in the shortcode.
 * @return string          Button internal CSS border.
 */
function mkhb_button_border( $options ) {
	$style = '';

	// Border Width.
	if ( ! empty( $options['border-width'] ) ) {
		$style .= "border-width: {$options['border-width']};";
	}

	// Border Color.
	if ( ! empty( $options['border-color'] ) ) {
		$style .= "border-color: {$options['border-color']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Button Link.
 *
 * @since 6.0.0
 * @since 6.0.3 Print font-family Button attribute only if font-family is not empty and
 *              it is not Roboto.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Button Link internal CSS.
 */
function mkhb_button_font_style( $options ) {
	$style = '';

	// Button Font Weight.
	if ( ! empty( $options['font-weight'] ) ) {
		$style .= "font-weight: {$options['font-weight']};";
	}

	// Button Font Size.
	if ( ! empty( $options['font-size'] ) ) {
		$style .= "font-size: {$options['font-size']};";
	}

	// Button Font Style.
	if ( ! empty( $options['font-style'] ) ) {
		$style .= "font-style: {$options['font-style']};";
	}

	// Button Font Family.
	if ( ! empty( $options['font-family'] ) && 'Roboto' !== $options['font-family'] ) {
		$style .= "font-family: {$options['font-family']};";
	}

	return $style;
}

/**
 * Generate internal style for HB Button Link Hover.
 *
 * There are 2 cases here:
 * 1. If button link hover styles are overriden, return the overriden hover style.
 * 2. If button link styles are overriden, return the default hover style. It's
 *    used to fix hover issue on the link.
 *
 * @since 6.0.0
 * @since 6.0.3 Fix hover issue if button color and bg color are updated.
 *
 * @param  array $options All options will be used in the shortcode.
 * @return string         Button Link Hover internal CSS.
 */
function mkhb_button_hover( $options ) {
	$style = '';

	// 1.a Hover Button Color.
	// 2.a If button color is overriden, set default color for hover state.
	if ( ! empty( $options['hover-color'] ) ) {
		$style .= "color: {$options['hover-color']};";
	} elseif ( ! empty( $options['color'] ) ) {
		$style .= 'color: rgba(77,208,225,1);';
	}

	// 1.b Hover Button Background Color.
	// 2.b If button bg color is overriden, set default bg color for hover state.
	if ( ! empty( $options['hover-background-color'] ) ) {
		$style .= "background-color: {$options['hover-background-color']};";
	} elseif ( ! empty( $options['color'] ) ) {
		$style .= 'background-color: rgba(216,241,244,1);';
	}

	// 1.c If hover color or bg color is overriden, return the hover style.
	if ( ! empty( $style ) ) {
		return $style;
	}

	return $style;
}
